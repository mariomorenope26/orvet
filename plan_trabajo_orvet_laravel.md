# Plan de Trabajo: Migración de orvet.pe a Laravel + MySQL

**Cliente:** Orvet (distribuidora de productos veterinarios) — Av. Fraternidad Nº 140, La Victoria, Chiclayo
**Sitio actual:** https://orvet.pe (WordPress + WooCommerce, tema "Orvet – Multi-store WooCommerce Theme", con Slider Revolution y Elementor)
**Objetivo:** Reconstruir el sitio completo con Laravel (PHP) + MySQL, manteniendo (y mejorando) todas las secciones, con **panel de administración donde absolutamente todo sea editable**, incluidos textos, imágenes, sliders/gráficos, banners de marcas, tablas de composición de producto y menús.

---

## 1. Auditoría del sitio actual

Secciones y funcionalidades relevadas en orvet.pe:

### 1.1 Cabecera / navegación
- Logo (editable, actualmente en `wp-content/uploads`)
- Menú principal: Inicio, Nosotros, Productos, Contacto
- Buscador de productos
- Iconos de cuenta, carrito, lista de deseos

### 1.2 Página de inicio
- Slider hero (Slider Revolution) con 2 slides, imagen + texto superpuesto ("Una Alternativa Confiable...", "Cuidamos de tus Animales...")
- Bloque "Somos Distribuidores en Perú": logos de laboratorios representados (Gamma, Labis Vet Pharma, Biostar, Eximvet)
- Bloque "Categorías": Animales Mayores / Animales Menores (con imagen de portada)
- Bloque "Nuestro Equipo": galería de fotos (9 imágenes)

### 1.3 Nosotros (About us)
- Texto "¿Quiénes somos?" (historia de la empresa desde 2006, fusión con Orvet en 2014, representaciones desde 2019/2021/2023)
- Galería institucional (9 imágenes)
- Bloque Misión / Visión / Valores (listas)
- Repetición de logos de marcas representadas

### 1.4 Tienda / catálogo (WooCommerce)
- Listado de productos en grid/lista, 50 productos, paginado
- Ordenamiento (popularidad, últimos, precio asc/desc)
- Sidebar con:
  - Árbol de categorías y subcategorías con contador de productos:
    - **Animales Mayores** (38): Antiparasitarios, Antibióticos, Antinflamatorios, Multivitamínicos y Reconstituyentes, Para el Ordeño, Reproductivos, Varios
    - **Animales Menores** (9): Anti Inflamatorios, Antibióticos, Antiparasitarios Orales/Externos, Dermatología, Multivitamínicos y Reconstituyentes, Grooming
    - Sin categorizar
  - Filtro por marca (actualmente vacío/no usado)
  - Filtro por etiquetas
  - Widget "Productos más pedidos"
- Funciones: añadir a lista de deseos, comparar productos, vista rápida

### 1.5 Ficha de producto
- Imagen ampliable, disponibilidad (stock)
- Navegación prev/next dentro de la categoría
- Descripción estructurada por producto (varía por tipo, pero suele incluir):
  - Composición (tabla de principios activos y concentraciones)
  - Presentación
  - Propiedades
  - Indicaciones
  - Dosis
  - Laboratorio fabricante/representado
- Categorías asociadas, botones compartir en redes
- Productos relacionados

### 1.6 Cuenta de usuario / e-commerce
- Mi cuenta, Dashboard, Mis pedidos, Carrito, Checkout/Finalizar compra, Wishlist, Order tracking, Login/Registro
- Nota: el checkout actualmente enlaza a un dominio externo (`source.wpopal.com`), lo que sugiere que es contenido de demo del tema y **no un checkout de pago real en producción**. Se debe confirmar con el cliente si necesitan pasarela de pago o solo catálogo + solicitud de cotización/pedido.

### 1.7 Otras páginas
- Blog, FAQs, Páginas de muestra (demo del tema, probablemente descartables)

### 1.8 Pie de página (footer)
- Logo, dirección, teléfonos (fijo y móvil), redes sociales (Facebook, Twitter, Instagram, Pinterest)
- Copyright, enlaces a Política de privacidad y Términos de servicio (actualmente vacíos/placeholder)
- Formulario de login embebido

### 1.9 Contacto
- Dirección, teléfonos, horario de atención (Lun-Vie 8am-5pm, Sáb 9am-1pm), correo (orvet@orvet.pe)
- Mapa de Google embebido
- Formulario de contacto

---

## 2. Alcance propuesto del nuevo sitio

| Módulo | Incluido |
|---|---|
| Home con slider, marcas, categorías destacadas, equipo | Sí |
| Nosotros (historia, misión/visión/valores, galería) | Sí |
| Catálogo de productos con categorías/subcategorías | Sí |
| Ficha de producto con secciones estructuradas editables | Sí |
| Buscador y filtros (categoría, marca, etiqueta) | Sí |
| Lista de deseos y comparador de productos | Sí (opcional, a confirmar) |
| Carrito/checkout con pago real en línea | **A definir con el cliente** (ver punto 8) |
| Cuenta de cliente, pedidos, seguimiento de pedido | Sí, si se confirma e-commerce transaccional |
| Blog | Sí |
| Formulario de contacto + mapa | Sí |
| Panel de administración 100% editable (CMS) | Sí — núcleo del proyecto |

---

## 3. Arquitectura técnica propuesta

- **Backend:** Laravel 11.x (PHP 8.3), arquitectura MVC + Repositorios/Servicios para catálogo y contenido
- **Base de datos:** MySQL 8
- **Panel de administración:** Laravel Filament (paneles CRUD rápidos, media library, generación de formularios dinámicos) — permite editar todas las secciones sin tocar código
- **Editor de contenido enriquecido:** TipTap o CKEditor integrado en Filament, para textos con formato (historia, misión/visión, descripciones de producto)
- **Constructor de secciones/gráficos ("todo editable"):** Bloques configurables tipo "page builder" (JSON + componentes Blade) para el slider hero, banners de marcas, galerías e imágenes de categoría, de modo que el cliente pueda subir/cambiar imágenes y textos sin depender de un desarrollador
- **Imágenes/medios:** Spatie Media Library (conversión automática a WebP, miniaturas responsivas, orden por drag & drop)
- **Frontend público:** Blade + Tailwind CSS (o Livewire/Alpine.js para interactividad sin SPA completa), diseño responsive equivalente o superior al actual
- **Buscador:** Laravel Scout (driver DB o Meilisearch si el catálogo crece)
- **Autenticación de clientes:** Laravel Breeze/Fortify (cuenta, pedidos, wishlist)
- **Carrito/checkout (si aplica):** paquete propio sobre Laravel + integración de pasarela peruana (Culqi, Niubiz o Mercado Pago) — a definir
- **Envío de formularios/notificaciones:** Laravel Mail + cola (queue) con Redis o base de datos
- **SEO:** meta tags editables por página/producto, sitemap.xml automático, slugs amigables (paridad o mejora de URLs actuales)
- **Hosting:** compatible con cPanel/WHM (entorno donde ya trabajas), o VPS con Nginx + PHP-FPM si se prefiere más control/rendimiento

---

## 4. Modelo de datos (entidades principales)

- `pages` (Home, Nosotros, Contacto) — contenido en bloques editables (JSON) + SEO por página
- `sliders` / `slides` — imagen, título, subtítulo, botón, orden, estado activo
- `brands` (marcas representadas: Gamma, Labis, Biostar, Eximvet) — logo, link, orden
- `categories` (autoreferenciada para subcategorías: Animales Mayores/Menores y sus hijas) — nombre, imagen, orden
- `products` — nombre, slug, SKU, categoría(s), marca/laboratorio, precio, stock, disponibilidad, imágenes (múltiples), etiquetas
- `product_specs` — tabla clave-valor editable (composición: principio activo/concentración) por producto
- `product_sections` — bloques de texto reutilizables por producto (Presentación, Propiedades, Indicaciones, Dosis) editables vía WYSIWYG
- `team_members` o `gallery_images` — fotos del equipo/galería institucional
- `testimonials` (opcional, si se desea agregar en el futuro)
- `blog_posts` + `blog_categories`
- `wishlists`, `comparisons`, `carts`, `orders`, `order_items` (si se confirma e-commerce)
- `contact_messages` — mensajes del formulario de contacto
- `settings` — datos institucionales editables (dirección, teléfonos, horario, correo, redes sociales, mapa, textos de footer, políticas)

---

## 5. Fases y cronograma estimado

| Fase | Contenido | Estimado |
|---|---|---|
| **1. Descubrimiento y diseño** | Confirmar alcance de e-commerce, wireframes de home/tienda/producto/nosotros/contacto, definición de paleta y componentes | 1 semana |
| **2. Base del proyecto** | Setup Laravel + MySQL, autenticación, panel Filament, estructura de módulos y migraciones | 1 semana |
| **3. Módulo de contenido institucional** | Home (slider, marcas, categorías, equipo), Nosotros, Contacto, Settings globales — todo editable desde el panel | 1.5 semanas |
| **4. Catálogo y ficha de producto** | CRUD de categorías/subcategorías, productos, specs, secciones, imágenes, relacionados, buscador y filtros | 2 semanas |
| **5. Cuenta de usuario y funciones de tienda** | Wishlist, comparador, cuenta, pedidos (y checkout/pago si se confirma) | 1.5–2.5 semanas (según alcance de pago) |
| **6. Migración de datos (volcado completo)** | Copiar íntegramente el contenido del sitio actual (productos, categorías, imágenes, textos institucionales, banners, galería, datos de contacto) a la base de datos MySQL de migración, conservándolo como set de datos de ejemplo/diseño anterior | 1 semana (paralelo a fase 4-5) |
| **7. Blog y páginas legales** | Blog, política de privacidad, términos de servicio | 3 días |
| **8. QA, SEO y performance** | Pruebas funcionales, redirecciones 301 desde URLs antiguas, sitemap, metadatos, optimización de imágenes | 1 semana |
| **9. Despliegue y capacitación** | Puesta en producción, capacitación al cliente en el uso del panel de administración | 3 días |

**Duración total estimada: 8–10 semanas**, dependiendo de si se incluye pasarela de pago transaccional.

---

## 6. Migración de datos desde WordPress (volcado completo como set de ejemplo)

Se copiará **toda la información del sitio actual** (no solo el catálogo) hacia la base de datos MySQL del nuevo sistema, para conservarla como set de datos de ejemplo/diseño anterior mientras se define el contenido definitivo del nuevo sitio. Esto incluye:

1. **Catálogo:** los 50 productos con SKU, categorías/subcategorías, descripciones, tablas de composición, presentación, propiedades, indicaciones, dosis, precio y stock (vía WooCommerce REST API o exportador CSV)
2. **Biblioteca de medios completa:** descarga de todas las imágenes de productos, banners del slider, logos de marcas, galería de equipo y galería institucional (`wp-content/uploads`), reubicadas en el nuevo storage (Spatie Media Library)
3. **Contenido institucional:** textos de "Nosotros" (historia, misión, visión, valores), datos de contacto (dirección, teléfonos, horario, correo, mapa), textos del footer y enlaces de redes sociales
4. **Estructura de categorías:** árbol completo de categorías y subcategorías (Animales Mayores/Menores y sus hijas), mapeado 1:1 al nuevo modelo
5. **Otros contenidos:** entradas de blog, FAQs y páginas legales existentes, si tienen contenido real
6. Redirecciones 301 de las URLs antiguas (`/producto/...`, `/categoria-producto/...`) a las nuevas, para no perder SEO

**Importante:** todo este contenido migrado se tratará como **datos de ejemplo / referencia del diseño anterior**, cargado tal cual en la base de datos de migración. El cliente podrá revisarlo, editarlo o reemplazarlo por completo desde el panel de administración una vez el sitio esté en marcha — no se trata de contenido final ni definitivo.

Esto **requiere acceso al WordPress actual** (ver sección 8).

---

## 7. Panel de administración — "todo editable"

Requisito clave del proyecto. El panel permitirá, sin tocar código:

- Cambiar el logo, slides del slider hero (imagen, texto, orden, activar/desactivar)
- Agregar/quitar marcas representadas y su logo
- Editar textos institucionales (historia, misión, visión, valores) con editor enriquecido
- Administrar la galería de equipo/instalaciones (subir, reordenar, eliminar fotos)
- Crear/editar categorías y subcategorías con su imagen
- Crear/editar productos con todas sus secciones (composición, presentación, propiedades, indicaciones, dosis) y múltiples imágenes
- Editar datos de contacto, horario, redes sociales y el mapa desde "Ajustes generales"
- Gestionar el blog
- Ver mensajes recibidos del formulario de contacto y pedidos de clientes

---

## 8. Información pendiente / a solicitar

Para iniciar la migración de contenido y evitar recrear todo manualmente, se necesita:

1. **Credenciales de acceso al WordPress actual de orvet.pe** (usuario y contraseña de administrador, o acceso temporal), para:
   - Exportar el catálogo completo de productos vía WooCommerce
   - Revisar contenido no visible públicamente (bloques de Elementor, configuraciones de FAQs, etc.)
2. Acceso a **hosting/cPanel** actual (opcional, si se requiere exportar la base de datos directamente en vez de usar la API/exportador)
3. Definición de negocio: ¿el nuevo sitio debe procesar pagos en línea (pasarela real) o solo mostrar catálogo con solicitud de pedido/cotización por WhatsApp o formulario?
4. Confirmar si se mantiene el listado de marcas actual (Gamma, Labis, Biostar, Eximvet) o se actualizará
5. Logo en alta resolución y manual de marca (colores, tipografía), si existe

> Cuando tengas estas credenciales, puedes compartirlas para iniciar la fase de migración de datos.

---

## 9. Entregables

- Código fuente del proyecto Laravel (repositorio Git)
- Base de datos MySQL con el catálogo migrado
- Panel de administración funcional y capacitación
- Sitio en producción con redirecciones desde las URLs antiguas
- Documentación técnica básica (instalación, estructura de módulos, credenciales de despliegue)
