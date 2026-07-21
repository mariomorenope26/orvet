@extends('layouts.app')

@section('content')

    {{-- Hero slider --}}
    @if($slides->isNotEmpty())
        <section data-slider class="relative h-[380px] overflow-hidden bg-primary-dark sm:h-[480px] lg:h-[560px]">
            @foreach($slides as $slide)
                <div data-slide class="absolute inset-0 opacity-0 transition-opacity duration-700 ease-in-out">
                    @if($slide->image)
                        <img src="{{ media_url($slide->image) }}" alt="{{ $slide->title }}" class="h-full w-full object-cover">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent"></div>
                    <div class="absolute inset-0 flex items-center">
                        <div class="mx-auto w-full max-w-7xl px-6">
                            <div class="max-w-xl text-white">
                                @if($slide->subtitle)
                                    <p class="mb-3 text-sm font-semibold uppercase tracking-widest text-accent">{{ $slide->subtitle }}</p>
                                @endif
                                @if($slide->title)
                                    <h1 class="mb-5 text-3xl font-extrabold leading-tight drop-shadow sm:text-4xl lg:text-5xl">{{ $slide->title }}</h1>
                                @endif
                                @if($slide->button_text && $slide->button_url)
                                    <a href="{{ $slide->button_url }}" class="inline-block rounded-full bg-accent px-7 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-accent-dark">
                                        {{ $slide->button_text }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($slides->count() > 1)
                <button data-prev class="absolute left-4 top-1/2 z-10 -translate-y-1/2 rounded-full bg-white/20 p-2 text-white backdrop-blur transition hover:bg-white/40" aria-label="Anterior">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                </button>
                <button data-next class="absolute right-4 top-1/2 z-10 -translate-y-1/2 rounded-full bg-white/20 p-2 text-white backdrop-blur transition hover:bg-white/40" aria-label="Siguiente">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </button>
                <div class="absolute bottom-5 left-1/2 z-10 flex -translate-x-1/2 gap-2">
                    @foreach($slides as $i => $slide)
                        <button data-dot class="h-2.5 w-2.5 rounded-full bg-white/40 transition" aria-label="Ir al slide {{ $i + 1 }}"></button>
                    @endforeach
                </div>
            @endif
        </section>
    @else
        <section class="bg-gradient-to-r from-primary-dark to-primary py-20 text-center text-white">
            <div class="mx-auto max-w-3xl px-6">
                <h1 class="mb-4 text-4xl font-extrabold">Una alternativa confiable para el cuidado de tus animales</h1>
                <p class="mb-6 text-lg text-white/90">Distribuidora de productos veterinarios en Chiclayo, Perú.</p>
                <a href="{{ route('products.index') }}" class="inline-block rounded-full bg-accent px-7 py-3 font-semibold shadow-lg transition hover:bg-accent-dark">Ver productos</a>
            </div>
        </section>
    @endif

    {{-- Distribuidores / marcas --}}
    @if($brands->isNotEmpty())
        <section class="border-b border-gray-100 bg-white py-14">
            <div class="mx-auto max-w-7xl px-6 text-center">
                <h2 class="text-2xl font-bold text-gray-800">Somos Distribuidores en Perú</h2>
                <p class="mx-auto mt-2 max-w-2xl text-sm text-gray-500">Representamos a laboratorios líderes en salud animal.</p>
                <div class="mt-10 grid grid-cols-2 items-center gap-8 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach($brands as $brand)
                        <div class="flex items-center justify-center grayscale transition hover:grayscale-0">
                            @if($brand->logo)
                                <img src="{{ media_url($brand->logo) }}" alt="{{ $brand->name }}" class="max-h-16 w-auto">
                            @else
                                <span class="text-lg font-bold text-gray-400">{{ $brand->name }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Categorías --}}
    @php $cats = $featuredCategories->isNotEmpty() ? $featuredCategories : $rootCategories; @endphp
    @if($cats->isNotEmpty())
        <section class="py-16">
            <div class="mx-auto max-w-7xl px-6">
                <div class="mb-10 text-center">
                    <h2 class="text-2xl font-bold text-gray-800">Nuestras Categorías</h2>
                    <p class="mt-2 text-sm text-gray-500">Productos para animales mayores y menores.</p>
                </div>
                <div class="flex flex-wrap justify-center gap-6">
                    @foreach($cats as $category)
                        <a href="{{ route('categories.show', $category) }}" class="group relative block h-56 w-full max-w-sm overflow-hidden rounded-2xl bg-primary-dark shadow-md sm:w-80">
                            @if($category->image)
                                <img src="{{ media_url($category->image) }}" alt="{{ $category->name }}" class="h-full w-full object-cover opacity-70 transition duration-300 group-hover:scale-105 group-hover:opacity-60">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute inset-x-0 bottom-0 p-5 text-center text-white">
                                <h3 class="text-xl font-bold">{{ $category->name }}</h3>
                                <span class="mt-1 inline-flex items-center gap-1 text-sm text-accent">Ver productos
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Productos destacados --}}
    @if($featuredProducts->isNotEmpty())
        <section class="bg-white py-16">
            <div class="mx-auto max-w-7xl px-6">
                <div class="mb-10 flex items-end justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Productos Destacados</h2>
                        <p class="mt-2 text-sm text-gray-500">Los favoritos de nuestros clientes.</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="hidden text-sm font-semibold text-primary hover:underline sm:inline">Ver todo el catálogo →</a>
                </div>
                <div class="grid grid-cols-2 gap-5 md:grid-cols-3 lg:grid-cols-4">
                    @foreach($featuredProducts as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Equipo --}}
    @if($team->isNotEmpty())
        <section class="py-16">
            <div class="mx-auto max-w-7xl px-6">
                <div class="mb-10 text-center">
                    <h2 class="text-2xl font-bold text-gray-800">Nuestro Equipo</h2>
                    <p class="mt-2 text-sm text-gray-500">Pasa el cursor (o toca) cada tarjeta para conocer más.</p>
                </div>
                <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach($team as $member)
                        <x-team-card :member="$member" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="bg-brandblue py-12">
        <div class="mx-auto flex max-w-5xl flex-col items-center justify-between gap-4 px-6 text-center text-white sm:flex-row sm:text-left">
            <div>
                <h2 class="text-2xl font-bold">¿Necesitas asesoría o una cotización?</h2>
                <p class="mt-1 text-white/80">Escríbenos y con gusto te atenderemos.</p>
            </div>
            <a href="{{ route('contact') }}" class="rounded-full bg-accent px-7 py-3 font-semibold shadow-lg transition hover:bg-accent-dark">Contáctanos</a>
        </div>
    </section>

@endsection
