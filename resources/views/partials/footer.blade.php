<footer class="mt-16 bg-gray-900 text-gray-300">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:grid-cols-2 lg:grid-cols-4">
        <div>
            <img src="{{ media_url(setting('logo'), asset('logorvet.png')) }}" alt="{{ setting('site_name', 'Orvet') }}"
                 class="mb-4 h-16 w-auto rounded bg-white p-2">
            <p class="text-sm leading-relaxed text-gray-400">
                {{ setting('footer_about', 'Distribuidora de productos veterinarios comprometida con la salud animal en el norte del Perú.') }}
            </p>
        </div>

        <div>
            <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-white">Enlaces</h3>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('home') }}" class="hover:text-primary-light">Inicio</a></li>
                <li><a href="{{ route('about') }}" class="hover:text-primary-light">Nosotros</a></li>
                <li><a href="{{ route('products.index') }}" class="hover:text-primary-light">Productos</a></li>
                <li><a href="{{ route('blog.index') }}" class="hover:text-primary-light">Blog</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-primary-light">Contacto</a></li>
            </ul>
        </div>

        <div>
            <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-white">Contacto</h3>
            <ul class="space-y-2 text-sm text-gray-400">
                @if(setting('address'))<li>{{ setting('address') }}</li>@endif
                @if(setting('phone_fixed'))<li>Tel: {{ setting('phone_fixed') }}</li>@endif
                @if(setting('phone_mobile'))<li>Cel: {{ setting('phone_mobile') }}</li>@endif
                @if(setting('email'))<li><a href="mailto:{{ setting('email') }}" class="hover:text-primary-light">{{ setting('email') }}</a></li>@endif
            </ul>
        </div>

        <div>
            <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-white">Horario de atención</h3>
            <ul class="space-y-2 text-sm text-gray-400">
                @if(setting('schedule_weekday'))<li>{{ setting('schedule_weekday') }}</li>@endif
                @if(setting('schedule_saturday'))<li>{{ setting('schedule_saturday') }}</li>@endif
            </ul>
        </div>
    </div>

    <div class="border-t border-gray-800">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-2 px-4 py-4 text-xs text-gray-500 sm:flex-row">
            <p>&copy; {{ date('Y') }} {{ setting('site_name', 'Orvet') }}. Todos los derechos reservados.</p>
            <div class="flex gap-4">
                <a href="{{ route('privacy') }}" class="hover:text-primary-light">Política de privacidad</a>
                <a href="{{ route('terms') }}" class="hover:text-primary-light">Términos de servicio</a>
            </div>
        </div>
    </div>

    {{-- Crédito de desarrollo --}}
    <div class="border-t border-gray-800 bg-gray-950">
        <div class="mx-auto max-w-7xl px-4 py-3 text-center text-xs text-gray-500">
            Sitio web desarrollado por
            <span class="font-semibold text-gray-300">JMMS Solutions EIRL</span>
            <span class="mx-1 text-gray-600">::</span>
            <a href="https://wa.me/51959199368" target="_blank" rel="noopener" class="text-gray-300 hover:text-primary-light">+51 959 199 368</a>
        </div>
    </div>
</footer>
