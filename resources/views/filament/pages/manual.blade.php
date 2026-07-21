<x-filament-panels::page>
    @php $img = fn ($n) => asset('manual/img/'.$n.'.webp'); @endphp

    <style>
        .manual { color: #1f2937; }
        .dark .manual { color: #e5e7eb; }
        .manual h2 { font-size: 1.35rem; font-weight: 800; color: #157040; margin: 2.2rem 0 .6rem; padding-top: .4rem; border-top: 2px solid #e5e7eb; }
        .dark .manual h2 { border-color: #374151; }
        .manual h3 { font-size: 1.05rem; font-weight: 700; margin: 1.2rem 0 .4rem; }
        .manual p { line-height: 1.7; margin: .5rem 0; }
        .manual ol, .manual ul { margin: .5rem 0 1rem 1.3rem; line-height: 1.7; }
        .manual ol { list-style: decimal; }
        .manual ul { list-style: disc; }
        .manual figure { margin: 1rem 0 1.6rem; }
        .manual img { width: 100%; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,.08); }
        .dark .manual img { border-color: #374151; }
        .manual figcaption { font-size: .8rem; color: #6b7280; text-align: center; margin-top: .4rem; }
        .manual .tip { background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: .8rem 1rem; margin: 1rem 0; font-size: .92rem; }
        .dark .manual .tip { background: rgba(6,78,59,.35); border-color: #065f46; }
        .manual .warn { background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: .8rem 1rem; margin: 1rem 0; font-size: .92rem; }
        .dark .manual .warn { background: rgba(120,53,15,.3); border-color: #92400e; }
        .manual .toc { background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 14px; padding: 1rem 1.4rem; columns: 2; }
        .dark .manual .toc { background: #1f2937; border-color: #374151; }
        .manual .toc a { color: #0e5aa0; text-decoration: none; }
        .dark .manual .toc a { color: #93c5fd; }
        .manual .toc a:hover { text-decoration: underline; }
        .manual code { background: #f1f5f9; padding: 1px 6px; border-radius: 6px; font-size: .88em; }
        .dark .manual code { background: #374151; }
        .manual .lead { font-size: 1.05rem; color: #4b5563; }
        .dark .manual .lead { color: #9ca3af; }
    </style>

    <div class="manual">
        <p class="lead">Esta guía explica, paso a paso y con imágenes reales del sistema, cómo administrar todo el sitio web de Orvet. Todo el contenido de la web es editable desde este panel; no necesitas tocar código.</p>

        <h3>Contenido</h3>
        <div class="toc">
            <a href="#acceso">1. Acceso al panel</a><br>
            <a href="#menu">2. El menú del panel</a><br>
            <a href="#ajustes">3. Ajustes generales</a><br>
            <a href="#slider">4. Slider de inicio</a><br>
            <a href="#marcas">5. Marcas / Laboratorios</a><br>
            <a href="#categorias">6. Categorías</a><br>
            <a href="#productos">7. Productos</a><br>
            <a href="#equipo">8. Nuestro Equipo</a><br>
            <a href="#blog">9. Blog</a><br>
            <a href="#mensajes">10. Mensajes de contacto</a><br>
            <a href="#backups">11. Copias de seguridad</a><br>
            <a href="#clave">12. Cambiar tu contraseña</a><br>
            <a href="#publico">13. El sitio público</a><br>
        </div>

        <h2 id="acceso">1. Acceso al panel</h2>
        <p>Ingresa a <code>tudominio/admin</code> y escribe tu correo y contraseña.</p>
        <ol>
            <li>Abre la dirección del panel en tu navegador.</li>
            <li>Escribe tu <strong>correo</strong> y <strong>contraseña</strong>.</li>
            <li>Pulsa <strong>Iniciar sesión</strong>.</li>
        </ol>
        <figure><img src="{{ $img('admin-login') }}" alt="Pantalla de inicio de sesión"><figcaption>Pantalla de inicio de sesión.</figcaption></figure>

        <h2 id="menu">2. El menú del panel</h2>
        <p>El menú de la izquierda agrupa todas las secciones administrables:</p>
        <ul>
            <li><strong>Configuración:</strong> Copias de seguridad y Ajustes generales.</li>
            <li><strong>Mensajes:</strong> Mensajes recibidos del formulario de contacto.</li>
            <li><strong>Catálogo:</strong> Categorías y Productos.</li>
            <li><strong>Contenido:</strong> Slider, Marcas, Galería/Equipo, Nuestro Equipo y Blog.</li>
            <li><strong>Ayuda:</strong> este Manual de uso.</li>
        </ul>
        <figure><img src="{{ $img('admin-dashboard') }}" alt="Escritorio del panel"><figcaption>Escritorio del panel con el menú de navegación.</figcaption></figure>

        <h2 id="ajustes">3. Ajustes generales</h2>
        <p>Aquí se edita la información institucional que aparece en toda la web. Se organiza en pestañas:</p>
        <ul>
            <li><strong>Identidad:</strong> logo, eslogan, color principal y el modo del catálogo (cotización o tienda).</li>
            <li><strong>Contacto:</strong> dirección, teléfonos, WhatsApp, correo, horario y el mapa de Google.</li>
            <li><strong>Redes sociales:</strong> enlaces a Facebook, Instagram, etc.</li>
            <li><strong>Nosotros:</strong> historia, misión, visión y valores.</li>
            <li><strong>Footer y legales:</strong> texto del pie, política de privacidad y términos.</li>
        </ul>
        <ol>
            <li>Entra a <strong>Configuración → Ajustes generales</strong>.</li>
            <li>Edita los campos de la pestaña que necesites.</li>
            <li>Pulsa <strong>Guardar cambios</strong> al final.</li>
        </ol>
        <figure><img src="{{ $img('admin-ajustes') }}" alt="Ajustes generales"><figcaption>Ajustes generales del sitio.</figcaption></figure>
        <div class="tip">💡 El número de <strong>WhatsApp</strong> que pongas aquí activa el botón flotante verde en toda la web y el botón “Cotizar” en cada producto.</div>

        <h2 id="slider">4. Slider de inicio</h2>
        <p>Son las imágenes grandes que rotan al inicio de la página principal.</p>
        <ol>
            <li>Entra a <strong>Contenido → Slider (Home)</strong>.</li>
            <li>Pulsa <strong>Crear</strong> o edita uno existente.</li>
            <li>Sube la <strong>imagen de fondo</strong> y escribe título, subtítulo y el botón (texto + enlace).</li>
            <li>Usa <strong>Orden</strong> para acomodarlos y <strong>Activo</strong> para mostrarlos u ocultarlos.</li>
        </ol>
        <figure><img src="{{ $img('admin-slider') }}" alt="Slider de inicio"><figcaption>Administración del slider de la página de inicio.</figcaption></figure>

        <h2 id="marcas">5. Marcas / Laboratorios</h2>
        <p>Los logos que aparecen en “Somos Distribuidores” y en la página Nosotros.</p>
        <ol>
            <li>Entra a <strong>Contenido → Marcas / Laboratorios</strong>.</li>
            <li>Crea una marca, sube su <strong>logo</strong> y (opcional) su enlace web.</li>
            <li>Arrastra para reordenar; desactiva para ocultar.</li>
        </ol>
        <figure><img src="{{ $img('admin-marcas') }}" alt="Marcas"><figcaption>Marcas y laboratorios representados.</figcaption></figure>

        <h2 id="categorias">6. Categorías</h2>
        <p>Organizan el catálogo (por ejemplo: Animales Mayores → Antibióticos).</p>
        <ol>
            <li>Entra a <strong>Catálogo → Categorías</strong>.</li>
            <li>Para una subcategoría, elige su <strong>Categoría padre</strong>; déjala vacía si es principal.</li>
            <li>Sube una <strong>imagen de portada</strong> y marca <strong>Destacada en la home</strong> si quieres mostrarla en el inicio.</li>
        </ol>
        <figure><img src="{{ $img('admin-categorias') }}" alt="Categorías"><figcaption>Árbol de categorías y subcategorías.</figcaption></figure>

        <h2 id="productos">7. Productos</h2>
        <p>El corazón del catálogo. Cada producto se edita en <strong>4 pestañas</strong>:</p>
        <ol>
            <li><strong>Datos generales:</strong> nombre, categoría, marca, laboratorio, presentación, precio, stock, disponibilidad, descripción y etiquetas.</li>
            <li><strong>Composición:</strong> agrega los principios activos y su concentración (se muestran como tabla en la ficha).</li>
            <li><strong>Secciones descriptivas:</strong> bloques con editor de texto (Indicaciones, Dosis, Propiedades…).</li>
            <li><strong>Imágenes y SEO:</strong> imagen principal, imágenes adicionales y meta datos.</li>
        </ol>
        <figure><img src="{{ $img('admin-productos') }}" alt="Listado de productos"><figcaption>Listado de productos con buscador y filtros.</figcaption></figure>
        <figure><img src="{{ $img('admin-producto-edit') }}" alt="Editar producto"><figcaption>Ficha de edición de un producto con sus pestañas.</figcaption></figure>
        <div class="tip">💡 Activa <strong>Destacado en la home</strong> para que el producto aparezca en la sección “Productos Destacados” del inicio.</div>

        <h2 id="equipo">8. Nuestro Equipo</h2>
        <p>Las tarjetas volteables del equipo. Cada tarjeta tiene un <strong>frente</strong> y un <strong>reverso</strong>:</p>
        <ul>
            <li><strong>Frente:</strong> foto (opcional — si no hay, se muestra el logo de Orvet), nombre y zona.</li>
            <li><strong>Reverso</strong> (al pasar el mouse): cargo, descripción y el <strong>N° de WhatsApp</strong>, que al pulsarlo abre un chat con esa persona.</li>
        </ul>
        <ol>
            <li>Entra a <strong>Contenido → Nuestro Equipo</strong>.</li>
            <li>Completa <strong>Nombre</strong>, <strong>Zona</strong>, <strong>Cargo</strong> y <strong>N° de WhatsApp</strong>.</li>
            <li>La foto es opcional. Ordena con <strong>Orden</strong> y activa/desactiva con <strong>Activo</strong>.</li>
        </ol>
        <figure><img src="{{ $img('admin-equipo-edit') }}" alt="Editar integrante del equipo"><figcaption>Edición de una tarjeta del equipo (frente y reverso).</figcaption></figure>

        <h2 id="blog">9. Blog</h2>
        <ol>
            <li>Entra a <strong>Contenido → Blog</strong> y pulsa <strong>Crear</strong>.</li>
            <li>Escribe título, categoría, resumen y el contenido con el editor.</li>
            <li>Sube la imagen destacada, marca <strong>Publicado</strong> y define la fecha.</li>
        </ol>
        <figure><img src="{{ $img('admin-blog') }}" alt="Blog"><figcaption>Administración de entradas del blog.</figcaption></figure>

        <h2 id="mensajes">10. Mensajes de contacto</h2>
        <p>Aquí llegan los mensajes que envían los visitantes desde la página de Contacto. El menú muestra un contador de mensajes sin leer.</p>
        <figure><img src="{{ $img('admin-mensajes') }}" alt="Mensajes de contacto"><figcaption>Bandeja de mensajes de contacto.</figcaption></figure>

        <h2 id="backups">11. Copias de seguridad</h2>
        <p>Permite respaldar y restaurar toda la base de datos.</p>
        <ol>
            <li>Entra a <strong>Configuración → Copias de seguridad</strong>.</li>
            <li>Pulsa <strong>Crear copia de seguridad</strong> para generar un respaldo.</li>
            <li>Puedes <strong>Descargar</strong>, <strong>Restaurar</strong> o <strong>Eliminar</strong> cada copia, o subir un archivo <code>.sql</code> para restaurar.</li>
        </ol>
        <figure><img src="{{ $img('admin-backups') }}" alt="Copias de seguridad"><figcaption>Copias de seguridad de la base de datos.</figcaption></figure>
        <div class="warn">⚠️ Restaurar <strong>reemplaza</strong> todos los datos actuales por los del respaldo. Haz una copia antes de restaurar.</div>

        <h2 id="clave">12. Cambiar tu contraseña</h2>
        <p>Puedes cambiar tu contraseña y tus datos desde tu perfil.</p>
        <ol>
            <li>Pulsa tu <strong>avatar</strong> (arriba a la derecha) y elige <strong>Perfil</strong>.</li>
            <li>Actualiza tu nombre, correo y/o <strong>contraseña</strong>.</li>
            <li>Guarda los cambios.</li>
        </ol>
        <div class="tip">💡 Se recomienda cambiar la contraseña inicial apenas ingreses por primera vez.</div>

        <h2 id="publico">13. El sitio público</h2>
        <p>Así se ve la web que ven tus clientes, alimentada por todo lo que editas en el panel:</p>
        <figure><img src="{{ $img('front-home') }}" alt="Página de inicio"><figcaption>Página de inicio (slider, marcas, categorías, productos y equipo).</figcaption></figure>
        <figure><img src="{{ $img('front-productos') }}" alt="Catálogo"><figcaption>Catálogo con buscador, categorías y filtros.</figcaption></figure>
        <figure><img src="{{ $img('front-producto') }}" alt="Ficha de producto"><figcaption>Ficha de producto (composición, secciones y botón de cotización).</figcaption></figure>
        <figure><img src="{{ $img('front-nosotros') }}" alt="Nosotros"><figcaption>Página Nosotros con las tarjetas del equipo.</figcaption></figure>
        <figure><img src="{{ $img('front-contacto') }}" alt="Contacto"><figcaption>Página de contacto con formulario y mapa.</figcaption></figure>

        <div class="tip" style="margin-top:2rem">✅ Recuerda pulsar <strong>Guardar</strong> después de cada cambio. Los cambios se reflejan de inmediato en la web pública.</div>
        <p style="text-align:center;color:#6b7280;font-size:.85rem;margin-top:2rem">Sitio desarrollado por JMMS Solutions EIRL · +51 959 199 368</p>
    </div>
</x-filament-panels::page>
