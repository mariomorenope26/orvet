<header class="sticky top-0 z-30 shadow-sm">
    {{-- Barra superior --}}
    <div class="bg-brandblue text-white text-sm">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-2 px-4 py-1.5">
            <div class="flex flex-wrap items-center gap-x-5 gap-y-1">
                @if(setting('phone_mobile'))
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                        {{ setting('phone_mobile') }}
                    </span>
                @endif
                @if(setting('email'))
                    <a href="mailto:{{ setting('email') }}" class="inline-flex items-center gap-1.5 hover:text-white/80">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        {{ setting('email') }}
                    </a>
                @endif
            </div>
            <div class="flex items-center gap-3">
                @foreach(['facebook' => 'Facebook', 'instagram' => 'Instagram', 'twitter' => 'Twitter', 'pinterest' => 'Pinterest'] as $net => $label)
                    @if(setting($net))
                        <a href="{{ setting($net) }}" target="_blank" rel="noopener" class="hover:text-white/70" aria-label="{{ $label }}">{{ $label }}</a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Navegación principal --}}
    <div class="bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ media_url(setting('logo'), asset('logorvet.png')) }}" alt="{{ setting('site_name', 'Orvet') }}" class="h-14 w-auto">
            </a>

            {{-- Buscador (desktop) --}}
            <form action="{{ route('products.index') }}" method="GET" class="hidden flex-1 max-w-md md:flex">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Buscar productos..."
                       class="w-full rounded-l-full border border-gray-300 px-4 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                <button type="submit" class="rounded-r-full bg-primary px-5 text-white transition hover:bg-primary-dark" aria-label="Buscar">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </button>
            </form>

            <button data-menu-toggle class="md:hidden rounded-md p-2 text-gray-700 hover:bg-gray-100" aria-label="Menú">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
            </button>
        </div>

        {{-- Menú --}}
        <nav class="border-t border-gray-100 bg-white">
            <div class="mx-auto max-w-7xl px-4">
                <ul data-mobile-menu class="hidden flex-col py-2 text-sm font-semibold text-gray-700 md:flex md:flex-row md:gap-1 md:py-0">
                    @php
                        $links = [
                            ['route' => 'home', 'label' => 'Inicio'],
                            ['route' => 'about', 'label' => 'Nosotros'],
                            ['route' => 'products.index', 'label' => 'Productos'],
                            ['route' => 'blog.index', 'label' => 'Blog'],
                            ['route' => 'contact', 'label' => 'Contacto'],
                        ];
                    @endphp
                    @foreach($links as $link)
                        <li>
                            <a href="{{ route($link['route']) }}"
                               class="block rounded px-4 py-3 transition hover:text-primary {{ request()->routeIs($link['route']) ? 'text-primary' : '' }}">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                    <li class="md:hidden">
                        <form action="{{ route('products.index') }}" method="GET" class="px-4 py-2">
                            <input type="search" name="q" placeholder="Buscar productos..." class="w-full rounded-full border border-gray-300 px-4 py-2 text-sm">
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
