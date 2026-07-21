<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'La importancia de la desparasitación en el ganado',
                'category' => 'Animales Mayores',
                'image' => 'blog/desparasitacion.jpg',
                'excerpt' => 'Un plan de desparasitación adecuado mejora la productividad y la salud de tu hato. Te explicamos por qué y cada cuánto hacerlo.',
                'body' => '<p>Los parásitos internos y externos representan una de las principales causas de pérdidas económicas en la ganadería. Afectan la ganancia de peso, la producción de leche y la fertilidad de los animales.</p>'
                    .'<h2>¿Por qué desparasitar?</h2>'
                    .'<p>Un animal parasitado consume alimento pero no aprovecha los nutrientes de forma eficiente. Además, las cargas parasitarias elevadas debilitan el sistema inmune y predisponen a otras enfermedades.</p>'
                    .'<h2>¿Cada cuánto?</h2>'
                    .'<ul><li>Terneros: primera dosis al destete y refuerzos según recomendación del veterinario.</li><li>Adultos: al menos dos veces al año, coincidiendo con el inicio y el fin de la época de lluvias.</li></ul>'
                    .'<p>En Orvet contamos con una amplia línea de antiparasitarios de laboratorios líderes. Consulta con nuestro asesor técnico el producto ideal para tu explotación.</p>',
            ],
            [
                'title' => 'Cómo prevenir enfermedades en animales menores',
                'category' => 'Animales Menores',
                'image' => 'blog/prevencion.jpg',
                'excerpt' => 'La prevención es siempre más económica que el tratamiento. Conoce las medidas básicas para mantener sanos a perros y gatos.',
                'body' => '<p>El cuidado preventivo es la mejor inversión para la salud de las mascotas. Con hábitos sencillos se pueden evitar la mayoría de las enfermedades comunes.</p>'
                    .'<h2>Medidas clave</h2>'
                    .'<ul><li>Desparasitación interna y externa periódica.</li><li>Alimentación balanceada acorde a la edad y el peso.</li><li>Visitas regulares al médico veterinario.</li><li>Higiene y baños con productos dermatológicos adecuados.</li></ul>'
                    .'<p>Ante cualquier signo de decaimiento, falta de apetito o cambios en el comportamiento, acude a un profesional. La detección temprana marca la diferencia.</p>',
            ],
            [
                'title' => 'Vitaminas y reconstituyentes: ¿cuándo utilizarlos?',
                'category' => 'Multivitamínicos',
                'image' => 'blog/vitaminas.jpg',
                'excerpt' => 'Los reconstituyentes vitamínicos ayudan en etapas de estrés, convalecencia y crecimiento. Aprende a usarlos correctamente.',
                'body' => '<p>Las vitaminas y minerales cumplen funciones esenciales en el metabolismo animal. Un aporte adecuado favorece el crecimiento, la fertilidad y la respuesta inmune.</p>'
                    .'<h2>Situaciones recomendadas</h2>'
                    .'<ul><li>Animales en crecimiento o gestación.</li><li>Convalecencia posterior a una enfermedad.</li><li>Épocas de estrés (transporte, cambios de clima, destete).</li><li>Estados carenciales por deficiencias en la dieta.</li></ul>'
                    .'<p>Recuerda que la suplementación debe complementar —no reemplazar— una alimentación balanceada. Consulta la dosis con tu veterinario.</p>',
            ],
            [
                'title' => 'Buenas prácticas en el ordeño',
                'category' => 'Para el Ordeño',
                'image' => 'blog/ordeno.jpg',
                'excerpt' => 'La calidad de la leche empieza por un ordeño higiénico. Repasamos las buenas prácticas para prevenir la mastitis.',
                'body' => '<p>La mastitis es la enfermedad más costosa en la producción lechera. Un protocolo de ordeño correcto reduce drásticamente su incidencia.</p>'
                    .'<h2>Rutina recomendada</h2>'
                    .'<ol><li>Despunte y observación de los primeros chorros de leche.</li><li>Limpieza y desinfección de pezones (pre-dipping).</li><li>Secado con toalla individual.</li><li>Colocación correcta de la unidad de ordeño.</li><li>Sellado de pezones al finalizar (post-dipping).</li></ol>'
                    .'<p>Complementa estas prácticas con productos para el cuidado de la ubre disponibles en nuestro catálogo.</p>',
            ],
            [
                'title' => 'Cuidado dermatológico de perros y gatos',
                'category' => 'Dermatología',
                'image' => 'blog/dermatologia.jpg',
                'excerpt' => 'Los problemas de piel son motivo frecuente de consulta. Te contamos cómo identificarlos y tratarlos a tiempo.',
                'body' => '<p>La piel es el órgano más extenso del cuerpo y un reflejo del estado de salud general de la mascota. Las afecciones dermatológicas requieren atención oportuna.</p>'
                    .'<h2>Señales de alerta</h2>'
                    .'<ul><li>Picazón intensa y rascado constante.</li><li>Enrojecimiento, descamación o mal olor.</li><li>Pérdida de pelo en zonas localizadas.</li></ul>'
                    .'<h2>Recomendaciones</h2>'
                    .'<p>Utiliza shampoos medicados adecuados y evita el uso de productos para humanos. Ante lesiones persistentes, consulta al veterinario para descartar causas bacterianas, fúngicas o parasitarias.</p>',
            ],
        ];

        foreach ($posts as $i => $data) {
            BlogPost::updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                array_merge($data, [
                    'is_published' => true,
                    'published_at' => now()->subDays(($i + 1) * 5),
                    'meta_title' => $data['title'],
                    'meta_description' => $data['excerpt'],
                ])
            );
        }
    }
}
