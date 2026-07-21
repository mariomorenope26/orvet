<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Slide;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSettings();
        $this->seedBrands();
        $categories = $this->seedCategories();
        $this->seedSlides();
        $this->seedProducts($categories);
    }

    private function seedSettings(): void
    {
        Setting::updateOrCreate(['id' => 1], [
            'site_name' => 'Orvet',
            'tagline' => 'Una alternativa confiable para el cuidado de tus animales',
            'address' => 'Av. Fraternidad Nº 140, La Victoria, Chiclayo',
            'phone_fixed' => '(074) 000-000',
            'phone_mobile' => '979 000 000',
            'whatsapp' => '51979000000',
            'email' => 'orvet@orvet.pe',
            'schedule_weekday' => 'Lunes a Viernes: 8:00 am – 5:00 pm',
            'schedule_saturday' => 'Sábados: 9:00 am – 1:00 pm',
            'map_embed' => '<iframe src="https://www.google.com/maps?q=Av.+Fraternidad+140,+La+Victoria,+Chiclayo&output=embed" width="100%" height="300" style="border:0;" allowfullscreen loading="lazy"></iframe>',
            'facebook' => 'https://facebook.com',
            'instagram' => 'https://instagram.com',
            'about_history' => '<p>Orvet es una empresa peruana con más de 15 años de experiencia en la distribución de productos veterinarios. Nuestra historia comienza en el año 2006, y en el 2014 se consolida con la fusión que da origen a la marca Orvet.</p><p>Desde el 2019 asumimos representaciones exclusivas de importantes laboratorios, ampliando nuestra cartera en los años 2021 y 2023 para ofrecer soluciones integrales en salud animal, tanto para animales mayores como menores.</p>',
            'mission' => '<p>Brindar productos veterinarios de la más alta calidad, garantizando la salud y el bienestar de los animales, con un servicio cercano y profesional a nuestros clientes en todo el norte del Perú.</p>',
            'vision' => '<p>Ser la distribuidora de productos veterinarios líder y de mayor confianza en el norte del país, reconocida por su calidad, compromiso e innovación.</p>',
            'company_values' => '<ul><li>Compromiso con la salud animal</li><li>Honestidad y transparencia</li><li>Calidad en cada producto</li><li>Servicio cercano al cliente</li><li>Responsabilidad y profesionalismo</li></ul>',
            'footer_about' => 'Distribuidora de productos veterinarios comprometida con la salud animal en el norte del Perú.',
            'privacy_policy' => '<p>En Orvet respetamos tu privacidad. La información que nos brindes a través de nuestros formularios será utilizada únicamente para atender tus consultas y no será compartida con terceros.</p>',
            'terms' => '<p>El uso de este sitio web implica la aceptación de nuestros términos de servicio. Los precios y la disponibilidad de los productos están sujetos a cambios sin previo aviso.</p>',
            'primary_color' => '#1f8f4e',
            'checkout_mode' => 'quote',
        ]);
    }

    private function seedBrands(): void
    {
        foreach (['Gamma', 'Labis Vet Pharma', 'Biostar', 'Eximvet'] as $i => $name) {
            Brand::updateOrCreate(['slug' => Str::slug($name)], [
                'name' => $name,
                'sort' => $i,
                'is_active' => true,
            ]);
        }
    }

    /**
     * @return array<string, Category>
     */
    private function seedCategories(): array
    {
        $tree = [
            'Animales Mayores' => [
                'Antiparasitarios', 'Antibióticos', 'Antinflamatorios',
                'Multivitamínicos y Reconstituyentes', 'Para el Ordeño', 'Reproductivos', 'Varios',
            ],
            'Animales Menores' => [
                'Anti Inflamatorios', 'Antibióticos', 'Antiparasitarios Orales', 'Antiparasitarios Externos',
                'Dermatología', 'Multivitamínicos y Reconstituyentes', 'Grooming',
            ],
        ];

        $map = [];
        $rootSort = 0;

        foreach ($tree as $rootName => $children) {
            $root = Category::updateOrCreate(['slug' => Str::slug($rootName)], [
                'name' => $rootName,
                'parent_id' => null,
                'sort' => $rootSort++,
                'is_featured' => true,
                'is_active' => true,
            ]);
            $map[$rootName] = $root;

            foreach ($children as $childSort => $childName) {
                $slug = Str::slug($rootName.' '.$childName);
                $child = Category::updateOrCreate(['slug' => $slug], [
                    'name' => $childName,
                    'parent_id' => $root->id,
                    'sort' => $childSort,
                    'is_active' => true,
                ]);
                $map[$rootName.' > '.$childName] = $child;
            }
        }

        return $map;
    }

    private function seedSlides(): void
    {
        $slides = [
            [
                'subtitle' => 'Bienvenido a Orvet',
                'title' => 'Una alternativa confiable para el cuidado de tus animales',
                'button_text' => 'Ver productos',
                'button_url' => '/productos',
                'sort' => 0,
            ],
            [
                'subtitle' => 'Salud animal garantizada',
                'title' => 'Cuidamos de tus animales con productos de calidad',
                'button_text' => 'Conócenos',
                'button_url' => '/nosotros',
                'sort' => 1,
            ],
        ];

        foreach ($slides as $slide) {
            Slide::updateOrCreate(
                ['title' => $slide['title']],
                array_merge($slide, ['is_active' => true])
            );
        }
    }

    /**
     * @param  array<string, Category>  $categories
     */
    private function seedProducts(array $categories): void
    {
        $gamma = Brand::where('slug', 'gamma')->first();
        $biostar = Brand::where('slug', 'biostar')->first();

        $products = [
            [
                'name' => 'Ivermectina 1% Inyectable',
                'category' => 'Animales Mayores > Antiparasitarios',
                'brand_id' => $gamma?->id,
                'laboratory' => 'Gamma',
                'presentation' => 'Frasco x 100 ml / 500 ml',
                'price' => 45.00,
                'short_description' => 'Antiparasitario de amplio espectro para el control de parásitos internos y externos en bovinos, ovinos y porcinos.',
                'specs' => [['Ivermectina', '1 g'], ['Excipientes c.s.p.', '100 ml']],
                'sections' => [
                    ['Propiedades', '<p>La ivermectina actúa contra nematodos gastrointestinales, pulmonares y ectoparásitos como ácaros y piojos.</p>'],
                    ['Indicaciones', '<p>Control y tratamiento de parásitos internos y externos en bovinos, ovinos, caprinos y porcinos.</p>'],
                    ['Dosis', '<p>1 ml por cada 50 kg de peso vivo, vía subcutánea.</p>'],
                ],
            ],
            [
                'name' => 'Oxitetraciclina LA 20%',
                'category' => 'Animales Mayores > Antibióticos',
                'brand_id' => $gamma?->id,
                'laboratory' => 'Gamma',
                'presentation' => 'Frasco x 100 ml',
                'price' => 38.50,
                'short_description' => 'Antibiótico de larga acción y amplio espectro para el tratamiento de infecciones bacterianas.',
                'specs' => [['Oxitetraciclina base', '20 g'], ['Vehículo c.s.p.', '100 ml']],
                'sections' => [
                    ['Indicaciones', '<p>Neumonías, mastitis, metritis, pododermatitis y otras infecciones sensibles a la oxitetraciclina.</p>'],
                    ['Dosis', '<p>1 ml por cada 10 kg de peso vivo, vía intramuscular profunda.</p>'],
                ],
            ],
            [
                'name' => 'Complejo Vitamínico ADE Fuerte',
                'category' => 'Animales Mayores > Multivitamínicos y Reconstituyentes',
                'brand_id' => $biostar?->id,
                'laboratory' => 'Biostar',
                'presentation' => 'Frasco x 250 ml',
                'price' => 32.00,
                'short_description' => 'Reconstituyente vitamínico para mejorar el estado general, la fertilidad y el crecimiento del ganado.',
                'specs' => [['Vitamina A', '500,000 UI'], ['Vitamina D3', '75,000 UI'], ['Vitamina E', '50 mg']],
                'sections' => [
                    ['Indicaciones', '<p>Estados carenciales, convalecencia, mejora de la fertilidad y del rendimiento productivo.</p>'],
                    ['Dosis', '<p>3 a 5 ml por animal, vía intramuscular o subcutánea.</p>'],
                ],
            ],
            [
                'name' => 'Flunixin Meglumine 5%',
                'category' => 'Animales Mayores > Antinflamatorios',
                'brand_id' => $gamma?->id,
                'laboratory' => 'Gamma',
                'presentation' => 'Frasco x 50 ml',
                'price' => 41.00,
                'short_description' => 'Antinflamatorio no esteroideo con acción analgésica y antipirética.',
                'specs' => [['Flunixin (como meglumine)', '5 g'], ['Excipientes c.s.p.', '100 ml']],
                'sections' => [
                    ['Indicaciones', '<p>Procesos inflamatorios, fiebre y dolor asociados a enfermedades respiratorias y musculoesqueléticas.</p>'],
                    ['Dosis', '<p>2 ml por cada 45 kg de peso vivo, vía intravenosa o intramuscular.</p>'],
                ],
            ],
            [
                'name' => 'Antiparasitario Oral Canino',
                'category' => 'Animales Menores > Antiparasitarios Orales',
                'brand_id' => $biostar?->id,
                'laboratory' => 'Biostar',
                'presentation' => 'Caja x 4 tabletas',
                'price' => 18.00,
                'short_description' => 'Desparasitante interno de amplio espectro para perros de todas las edades.',
                'specs' => [['Praziquantel', '50 mg'], ['Pirantel pamoato', '144 mg'], ['Febantel', '150 mg']],
                'sections' => [
                    ['Indicaciones', '<p>Control de parásitos redondos y planos en caninos.</p>'],
                    ['Dosis', '<p>1 tableta por cada 10 kg de peso vivo.</p>'],
                ],
            ],
            [
                'name' => 'Shampoo Dermatológico Antiséptico',
                'category' => 'Animales Menores > Dermatología',
                'brand_id' => $biostar?->id,
                'laboratory' => 'Biostar',
                'presentation' => 'Frasco x 250 ml',
                'price' => 25.00,
                'short_description' => 'Shampoo medicado para el tratamiento de afecciones dermatológicas en perros y gatos.',
                'specs' => [['Clorhexidina', '2%'], ['Ketoconazol', '1%']],
                'sections' => [
                    ['Indicaciones', '<p>Dermatitis bacteriana y fúngica, seborrea y prurito.</p>'],
                    ['Dosis', '<p>Aplicar sobre el pelaje húmedo, dejar actuar 5 minutos y enjuagar. 2 veces por semana.</p>'],
                ],
            ],
            [
                'name' => 'Meloxicam 0.5% Inyectable',
                'category' => 'Animales Menores > Anti Inflamatorios',
                'brand_id' => $gamma?->id,
                'laboratory' => 'Gamma',
                'presentation' => 'Frasco x 20 ml',
                'price' => 29.00,
                'short_description' => 'Antinflamatorio y analgésico para el control del dolor postquirúrgico y crónico en pequeñas especies.',
                'specs' => [['Meloxicam', '0.5 g'], ['Excipientes c.s.p.', '100 ml']],
                'sections' => [
                    ['Indicaciones', '<p>Dolor e inflamación en procesos osteoarticulares y postoperatorios.</p>'],
                    ['Dosis', '<p>0.2 ml por cada kg el primer día, luego 0.1 ml/kg, vía subcutánea.</p>'],
                ],
            ],
            [
                'name' => 'Multivitamínico Reconstituyente Canino',
                'category' => 'Animales Menores > Multivitamínicos y Reconstituyentes',
                'brand_id' => $biostar?->id,
                'laboratory' => 'Biostar',
                'presentation' => 'Frasco x 120 ml',
                'price' => 22.50,
                'short_description' => 'Suplemento vitamínico para perros y gatos en etapas de crecimiento, gestación o recuperación.',
                'specs' => [['Vitaminas del complejo B', 'c.s.'], ['Aminoácidos esenciales', 'c.s.']],
                'sections' => [
                    ['Indicaciones', '<p>Apoyo nutricional en convalecencia, estrés y estados carenciales.</p>'],
                    ['Dosis', '<p>1 ml por cada 5 kg de peso, vía oral.</p>'],
                ],
            ],
        ];

        foreach ($products as $sort => $data) {
            $category = $categories[$data['category']] ?? null;

            $product = Product::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'name' => $data['name'],
                    'sku' => 'ORV-'.str_pad((string) ($sort + 1), 4, '0', STR_PAD_LEFT),
                    'category_id' => $category?->id,
                    'brand_id' => $data['brand_id'],
                    'laboratory' => $data['laboratory'],
                    'presentation' => $data['presentation'],
                    'price' => $data['price'],
                    'stock' => 25,
                    'availability' => 'in_stock',
                    'short_description' => $data['short_description'],
                    'is_featured' => $sort < 4,
                    'is_active' => true,
                    'sort' => $sort,
                ]
            );

            $product->specs()->delete();
            foreach ($data['specs'] as $specSort => [$ingredient, $concentration]) {
                $product->specs()->create([
                    'active_ingredient' => $ingredient,
                    'concentration' => $concentration,
                    'sort' => $specSort,
                ]);
            }

            $product->sections()->delete();
            foreach ($data['sections'] as $sectionSort => [$title, $body]) {
                $product->sections()->create([
                    'title' => $title,
                    'body' => $body,
                    'sort' => $sectionSort,
                ]);
            }
        }
    }
}
