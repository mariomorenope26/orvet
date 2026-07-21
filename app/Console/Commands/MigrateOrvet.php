<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSpec;
use App\Models\ProductSection;
use App\Models\ProductImage;
use App\Models\Setting;
use App\Models\Slide;
use App\Models\TeamMember;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MigrateOrvet extends Command
{
    protected $signature = 'orvet:migrate {--no-images : Omitir la descarga de imágenes}';

    protected $description = 'Migra el contenido real de orvet.pe (categorías, marcas, productos, equipo y ajustes) a la base de datos.';

    protected bool $downloadImages = true;

    /** Mapa de laboratorios conocidos para asignar marca. */
    protected array $labMap = [
        'gamma' => 'Gamma',
        'labis' => 'Labis Vet Pharma',
        'biostar' => 'Biostar',
        'exim' => 'Eximvet',
    ];

    public function handle(): int
    {
        $this->downloadImages = ! $this->option('no-images');
        $dir = storage_path('app/migration');

        if (! file_exists("$dir/prods.json") || ! file_exists("$dir/cats.json")) {
            $this->error('Faltan los archivos prods.json / cats.json en storage/app/migration.');
            return self::FAILURE;
        }

        $this->info('Limpiando catálogo anterior...');
        Schema::disableForeignKeyConstraints();
        ProductImage::truncate();
        ProductSpec::truncate();
        ProductSection::truncate();
        Product::truncate();
        Category::truncate();
        Brand::truncate();
        TeamMember::truncate();
        Slide::truncate();
        Schema::enableForeignKeyConstraints();

        $this->seedSettings();
        $brands = $this->seedBrands();
        $categoryMap = $this->importCategories(json_decode(file_get_contents("$dir/cats.json"), true));
        $this->importProducts(json_decode(file_get_contents("$dir/prods.json"), true), $categoryMap, $brands);
        $this->importTeam();
        $this->seedSlides();

        $this->newLine();
        $this->info('Migración completada.');
        $this->table(['Entidad', 'Total'], [
            ['Categorías', Category::count()],
            ['Marcas', Brand::count()],
            ['Productos', Product::count()],
            ['Composiciones', ProductSpec::count()],
            ['Secciones', ProductSection::count()],
            ['Equipo', TeamMember::count()],
        ]);

        return self::SUCCESS;
    }

    protected function seedSettings(): void
    {
        // El logo se sube desde public/logorvet.png al storage.
        $logo = $this->storeLocalLogo();

        Setting::updateOrCreate(['id' => 1], [
            'site_name' => 'Orvet',
            'tagline' => 'Una alternativa confiable para el cuidado de tus animales',
            'logo' => $logo,
            'address' => 'Av. Fraternidad Nº 140, La Victoria – Chiclayo',
            'phone_fixed' => '(074) 216 066',
            'phone_mobile' => '979 699 808',
            'whatsapp' => '51979699808',
            'email' => 'orvet@orvet.pe',
            'schedule_weekday' => 'Lunes a Viernes: 8:00 am – 5:00 pm',
            'schedule_saturday' => 'Sábados: 9:00 am – 1:00 pm',
            'map_embed' => '<iframe src="https://www.google.com/maps?q=Av.+Fraternidad+140,+La+Victoria,+Chiclayo&output=embed" width="100%" height="320" style="border:0;" allowfullscreen loading="lazy"></iframe>',
            'facebook' => null,
            'instagram' => null,
            'twitter' => null,
            'pinterest' => null,
            'about_history' => '<p>Somos una empresa peruana dedicada a la comercialización de productos veterinarios y asesoramiento a los profesionales del área, con productos propios de fabricación nacional e importados en todo el territorio peruano, contando con nuestra propia fuerza de ventas (6 vendedores) y la logística adecuada que cubren la costa, sierra y selva de nuestro país.</p>'
                .'<p>Nos iniciamos como distribuidores de productos nacionales y co-distribuidor de productos importados (OVER, INTERCHEMIE, BROVEL, BURNET) desde el año 2006 teniendo otra razón social, fusionándonos posteriormente con Orvet en el 2014 para dar el gran paso de la importación directa, contando siempre con la experiencia y asesoramiento de nuestro fundador el M.V. Juan Ortiz Vélez. En la zona norte y nor oriente, en el año 2019 al tomar la representación de Gamma Laboratorios nos extendimos a las demás zonas de nuestro país (Sur, Centro y la Capital), en el año 2021 empezamos a representar a Labis S.A. y en el 2023 con Biostar Pharmaceutical, completando aún más nuestro portafolio de productos a ofrecer.</p>'
                .'<p><strong>Contamos con:</strong></p><ul><li>Distribuidores estratégicos en principales cuencas ganaderas o ciudades de potencial económico como Huancayo, Arequipa y Cuzco.</li><li>Una amplia y excelente cartera de clientes en todo el país.</li></ul>',
            'mission' => '<p>Brindar productos veterinarios de la más alta calidad, garantizando la salud y el bienestar de los animales, con un servicio cercano y profesional a nuestros clientes en todo el Perú.</p>',
            'vision' => '<p>Ser la distribuidora de productos veterinarios líder y de mayor confianza del país, reconocida por su calidad, compromiso e innovación.</p>',
            'company_values' => '<ul><li>Compromiso con la salud animal</li><li>Honestidad y transparencia</li><li>Calidad en cada producto</li><li>Servicio cercano al cliente</li><li>Responsabilidad y profesionalismo</li></ul>',
            'footer_about' => 'Distribuidora de productos veterinarios con fuerza de ventas propia y cobertura en la costa, sierra y selva del Perú.',
            'checkout_mode' => 'quote',
        ]);

        $this->line('  ✓ Ajustes institucionales actualizados');
    }

    protected function storeLocalLogo(): ?string
    {
        $source = public_path('logorvet.png');
        if (! file_exists($source)) {
            return null;
        }
        $path = 'brand/logorvet.png';
        Storage::disk('public')->put($path, file_get_contents($source));

        return $path;
    }

    /** @return array<string, Brand> */
    protected function seedBrands(): array
    {
        $brands = [
            ['name' => 'Gamma', 'logo' => 'https://orvet.pe/wp-content/uploads/2025/12/gamma-vet-logo.jpg'],
            ['name' => 'Labis Vet Pharma', 'logo' => 'https://orvet.pe/wp-content/uploads/2025/12/labis.png'],
            ['name' => 'Biostar', 'logo' => 'https://orvet.pe/wp-content/uploads/2025/12/biostar.png'],
            ['name' => 'Eximvet', 'logo' => 'https://orvet.pe/wp-content/uploads/2025/12/exi.png'],
        ];

        $map = [];
        foreach ($brands as $i => $data) {
            $brand = Brand::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'logo' => $this->download($data['logo'], 'brands'),
                'sort' => $i,
                'is_active' => true,
            ]);
            $map[Str::slug($data['name'])] = $brand;
        }
        $this->line('  ✓ '.count($map).' marcas importadas con logo');

        return $map;
    }

    /**
     * @param  array<int, array>  $cats
     * @return array<int, Category>  wp_id => Category
     */
    protected function importCategories(array $cats): array
    {
        $rootImages = [
            'animales-mayores' => 'https://orvet.pe/wp-content/uploads/2026/01/ANIMALES-MAYORES.jpg',
            'animales-menores' => 'https://orvet.pe/wp-content/uploads/2026/01/ANIMALES-MENORES.jpg',
        ];

        // Ordena: raíces primero.
        usort($cats, fn ($a, $b) => $a['parent'] <=> $b['parent']);
        $map = [];

        foreach ($cats as $c) {
            if (strtolower($c['name']) === 'sin categorizar') {
                continue; // Se omite: los productos quedan sin categoría.
            }

            $isRoot = (int) $c['parent'] === 0;
            $slug = Str::slug($c['name']);
            // Evita colisiones de slug entre subcategorías homónimas de distinta raíz.
            if (! $isRoot && Category::where('slug', $slug)->exists()) {
                $slug .= '-'.$c['id'];
            }

            $category = Category::create([
                'name' => html_entity_decode($c['name']),
                'slug' => $slug,
                'parent_id' => $isRoot ? null : ($map[$c['parent']]->id ?? null),
                'image' => $isRoot ? $this->download($rootImages[Str::slug($c['name'])] ?? '', 'categories') : null,
                'sort' => $isRoot ? 0 : (int) ($c['menu_order'] ?? 0),
                'is_featured' => $isRoot,
                'is_active' => true,
            ]);
            $map[$c['id']] = $category;
        }

        $this->line('  ✓ '.count($map).' categorías importadas');

        return $map;
    }

    /**
     * @param  array<int, array>  $products
     * @param  array<int, Category>  $categoryMap
     * @param  array<string, Brand>  $brands
     */
    protected function importProducts(array $products, array $categoryMap, array $brands): void
    {
        $bar = $this->output->createProgressBar(count($products));
        $bar->start();

        foreach (array_values($products) as $i => $p) {
            // Elige el campo con más contenido (algunos productos usan description).
            $sd = $p['short_description'] ?? '';
            $de = $p['description'] ?? '';
            $source = mb_strlen($this->plain($de)) > mb_strlen($this->plain($sd)) ? $de : $sd;
            $parsed = $this->parseSections($source);
            [$brandId, $laboratory] = $this->detectBrand($p, $brands);

            // Categoría más específica (subcategoría antes que raíz).
            $categoryId = null;
            foreach ($p['categories'] ?? [] as $cat) {
                $local = $categoryMap[$cat['id']] ?? null;
                if ($local && $local->parent_id) {
                    $categoryId = $local->id;
                    break;
                }
                if ($local && ! $categoryId) {
                    $categoryId = $local->id;
                }
            }

            $product = Product::create([
                'name' => html_entity_decode($p['name']),
                'slug' => $p['slug'] ?: Str::slug($p['name']),
                'sku' => $p['sku'] ?: null,
                'category_id' => $categoryId,
                'brand_id' => $brandId,
                'laboratory' => $laboratory,
                'price' => null, // orvet.pe no publica precios (modo cotización)
                'stock' => ($p['is_in_stock'] ?? true) ? 10 : 0,
                'availability' => ($p['is_in_stock'] ?? true) ? 'in_stock' : 'on_request',
                'image' => $this->download($p['images'][0]['src'] ?? '', 'products'),
                'short_description' => Str::limit($parsed['intro'] ?: $this->firstSectionText($parsed['sections']), 400),
                'presentation' => Str::limit($this->presentationText($parsed['sections']), 240) ?: null,
                'is_featured' => $i < 8,
                'is_active' => true,
                'sort' => $i,
                'meta_title' => html_entity_decode($p['name']),
            ]);

            // Imágenes adicionales.
            foreach (array_slice($p['images'] ?? [], 1) as $imgSort => $img) {
                if ($path = $this->download($img['src'] ?? '', 'products')) {
                    $product->images()->create(['image' => $path, 'sort' => $imgSort]);
                }
            }

            $this->attachSections($product, $parsed['sections']);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->line('  ✓ '.count($products).' productos importados');
    }

    /**
     * Divide el HTML del producto en secciones a partir de los encabezados <u>...</u>.
     *
     * @return array{intro:string, sections:array<int,array{title:string,body:string}>}
     */
    protected function parseSections(string $html): array
    {
        $html = str_replace(["\r", "\xc2\xa0"], ['', ' '], $html);

        // Marca como encabezado cualquier <p> cuyo texto sea un rótulo en MAYÚSCULAS
        // terminado en ":" (ej. "COMPOSICIÓN:", "DOSIS:", "FÓRMULA:").
        $marked = preg_replace_callback('/<p[^>]*>(.*?)<\/p>/is', function ($m) {
            $inner = $this->plain($m[1]);
            if ($this->looksLikeHeading($inner)) {
                $title = rtrim($inner, ": \t");
                return "\n@@@SEC@@@{$title}@@@\n";
            }
            return $m[0];
        }, $html);

        $parts = explode('@@@SEC@@@', $marked);
        $intro = $this->plain(array_shift($parts));

        $sections = [];
        foreach ($parts as $part) {
            [$title, $body] = array_pad(explode('@@@', $part, 2), 2, '');
            $title = trim($title);
            $body = trim($body);
            if ($title === '' || $this->plain($body) === '') {
                continue;
            }
            $sections[] = ['title' => $this->titleCase($title), 'body' => $body];
        }

        return ['intro' => $intro, 'sections' => $sections];
    }

    /** ¿El texto es un rótulo de sección? (mayúsculas, corto y terminado en ":"). */
    protected function looksLikeHeading(string $text): bool
    {
        $text = trim($text);
        if ($text === '' || mb_strlen($text) > 48 || ! str_ends_with($text, ':')) {
            return false;
        }
        $label = rtrim($text, ': ');
        $letters = preg_replace('/[^\p{L}]/u', '', $label);

        return mb_strlen($letters) >= 3 && mb_strtoupper($label, 'UTF-8') === $label;
    }

    protected function attachSections(Product $product, array $sections): void
    {
        $sort = 0;
        foreach ($sections as $section) {
            $key = Str::lower($this->plain($section['title']));

            // Composición / Fórmula -> intenta tabla de principios activos.
            if (Str::contains($key, ['composici', 'fórmula', 'formula'])) {
                $specs = $this->parseSpecs($section['body']);
                if (count($specs) >= 1) {
                    foreach ($specs as $specSort => $spec) {
                        $product->specs()->create([
                            'active_ingredient' => Str::limit($spec[0], 190, ''),
                            'concentration' => Str::limit($spec[1], 190, ''),
                            'sort' => $specSort,
                        ]);
                    }
                    continue; // No se duplica como sección de texto.
                }
            }

            $product->sections()->create([
                'title' => $section['title'],
                'body' => $section['body'],
                'sort' => $sort++,
            ]);
        }
    }

    /** Parsea líneas "Ingrediente ……… cantidad" a pares [ingrediente, concentración]. */
    protected function parseSpecs(string $html): array
    {
        $text = html_entity_decode(str_replace(['</p>', '</li>', '<br>', '<br/>', '<br />'], "\n", $html));
        $text = strip_tags($text);
        $specs = [];

        foreach (preg_split('/\n+/', $text) as $line) {
            $line = trim(preg_replace('/\s+/u', ' ', $line));
            if ($line === '' || Str::contains(Str::lower($line), ['cada ', 'contiene'])) {
                continue;
            }
            if (preg_match('/^(.{2,}?)[\.\x{2026}]{2,}\s*(.+)$/u', $line, $m)) {
                $ing = trim($m[1], " .:\t");
                $conc = trim($m[2]);
                if ($ing !== '' && preg_match('/[0-9]|c\.?s|q\.?s|csp/i', $conc)) {
                    $specs[] = [$ing, $conc];
                }
            }
        }

        return $specs;
    }

    /** Detecta la marca/laboratorio buscando nombres conocidos en el texto. */
    protected function detectBrand(array $product, array $brands): array
    {
        $haystack = Str::lower($this->plain(($product['short_description'] ?? '').' '.($product['description'] ?? '')));

        foreach ($this->labMap as $needle => $brandName) {
            if (Str::contains($haystack, $needle)) {
                $brand = $brands[Str::slug($brandName)] ?? null;
                return [$brand?->id, $brandName];
            }
        }

        return [null, null];
    }

    protected function presentationText(array $sections): string
    {
        foreach ($sections as $s) {
            if (Str::contains(Str::lower($s['title']), 'presentaci')) {
                return $this->plain($s['body']);
            }
        }
        return '';
    }

    protected function firstSectionText(array $sections): string
    {
        foreach ($sections as $s) {
            if (Str::contains(Str::lower($s['title']), ['descripci', 'propiedad'])) {
                return $this->plain($s['body']);
            }
        }
        return $sections[0]['title'] ?? '';
    }

    protected function importTeam(): void
    {
        $photos = [
            'https://orvet.pe/wp-content/uploads/2026/01/2026-01-12_18h11_45.png',
            'https://orvet.pe/wp-content/uploads/2026/01/2026-01-12_18h13_12.png',
            'https://orvet.pe/wp-content/uploads/2026/01/2026-01-12_18h14_29.png',
            'https://orvet.pe/wp-content/uploads/2026/01/2026-01-12_18h16_53.png',
            'https://orvet.pe/wp-content/uploads/2026/01/2026-01-12_18h18_35.png',
            'https://orvet.pe/wp-content/uploads/2026/01/2026-01-12_18h45_40.png',
            'https://orvet.pe/wp-content/uploads/2026/01/2026-01-12_18h51_53.png',
        ];

        foreach ($photos as $i => $url) {
            TeamMember::create([
                'name' => 'Integrante '.($i + 1),
                'role' => 'Editar cargo',
                'photo' => $this->download($url, 'team'),
                'description' => 'Edita este texto desde el panel de administración con la información real del integrante.',
                'sort' => $i,
                'is_active' => true,
            ]);
        }
        $this->line('  ✓ '.count($photos).' tarjetas de equipo creadas (editar datos en el panel)');
    }

    protected function seedSlides(): void
    {
        $slides = [
            ['subtitle' => 'Distribuidora veterinaria', 'title' => 'Una alternativa confiable para el cuidado de tus animales', 'button_text' => 'Ver productos', 'button_url' => '/productos', 'sort' => 0, 'src' => 'https://orvet.pe/wp-content/uploads/revslider/slider-4/rev_home4.jpg'],
            ['subtitle' => 'Cobertura nacional', 'title' => 'Cuidamos de tus animales con productos de calidad', 'button_text' => 'Conócenos', 'button_url' => '/nosotros', 'sort' => 1, 'src' => 'https://orvet.pe/wp-content/uploads/revslider/slider-4/rev_home4_2.jpg'],
        ];
        foreach ($slides as $slide) {
            $src = $slide['src'];
            unset($slide['src']);
            Slide::create(array_merge($slide, [
                'image' => $this->download($src, 'slides'),
                'is_active' => true,
            ]));
        }
    }

    // ----- Utilidades -----

    protected function plain(?string $html): string
    {
        return trim(preg_replace('/\s+/u', ' ', strip_tags(html_entity_decode((string) $html))));
    }

    protected function titleCase(string $upper): string
    {
        return Str::ucfirst(Str::lower(trim($upper, " :\t")));
    }

    protected function download(string $url, string $dir): ?string
    {
        if (blank($url) || ! $this->downloadImages) {
            return null;
        }

        $path = parse_url($url, PHP_URL_PATH) ?: '';
        $ext = Str::lower(pathinfo($path, PATHINFO_EXTENSION)) ?: 'jpg';
        $name = Str::slug(pathinfo($path, PATHINFO_FILENAME)).'-'.substr(md5($url), 0, 6).'.'.$ext;
        $stored = "$dir/$name";

        if (Storage::disk('public')->exists($stored)) {
            return $stored;
        }

        try {
            $res = Http::withHeaders(['User-Agent' => 'Mozilla/5.0'])->timeout(30)->get($url);
            if ($res->successful() && strlen($res->body()) > 100) {
                Storage::disk('public')->put($stored, $res->body());
                return $stored;
            }
        } catch (\Throwable $e) {
            $this->warn("  ! No se pudo descargar: $url");
        }

        return null;
    }
}
