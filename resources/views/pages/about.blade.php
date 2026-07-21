@extends('layouts.app')

@section('title', 'Nosotros')

@section('content')
<section class="bg-gradient-to-r from-primary-dark to-primary py-16 text-white">
    <div class="mx-auto max-w-7xl px-6 text-center">
        <h1 class="text-4xl font-extrabold">Nosotros</h1>
        <p class="mx-auto mt-3 max-w-2xl text-white/90">Conoce la historia y los valores que nos respaldan.</p>
    </div>
</section>

{{-- Historia --}}
<section class="py-16">
    <div class="mx-auto max-w-4xl px-6">
        <h2 class="mb-6 text-2xl font-bold text-gray-800">¿Quiénes somos?</h2>
        <div class="prose-orvet max-w-none text-gray-600">
            @if(setting('about_history'))
                {!! setting('about_history') !!}
            @else
                <p>Somos una empresa peruana dedicada a la distribución de productos veterinarios, comprometida con la salud y el bienestar animal en el norte del país.</p>
            @endif
        </div>
    </div>
</section>

{{-- Galería institucional --}}
@if($gallery->isNotEmpty())
    <section class="bg-white py-12">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                @foreach($gallery as $photo)
                    <div class="overflow-hidden rounded-xl shadow-sm">
                        <img src="{{ media_url($photo->image) }}" alt="{{ $photo->title }}" class="h-56 w-full object-cover transition duration-300 hover:scale-105">
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- Misión / Visión / Valores --}}
<section class="py-16">
    <div class="mx-auto grid max-w-7xl gap-6 px-6 md:grid-cols-3">
        @php
            $blocks = [
                ['title' => 'Misión', 'content' => setting('mission'), 'icon' => 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25'],
                ['title' => 'Visión', 'content' => setting('vision'), 'icon' => 'M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z'],
                ['title' => 'Valores', 'content' => setting('company_values'), 'icon' => 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.562.562 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z'],
            ];
        @endphp
        @foreach($blocks as $block)
            <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
                <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $block['icon'] }}"/></svg>
                </div>
                <h3 class="mb-3 text-xl font-bold text-gray-800">{{ $block['title'] }}</h3>
                <div class="prose-orvet max-w-none text-sm text-gray-600">
                    {!! $block['content'] ?: '<p>Contenido por definir.</p>' !!}
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- Equipo --}}
@if($team->isNotEmpty())
    <section class="py-16">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-10 text-center">
                <h2 class="text-2xl font-bold text-gray-800">Nuestro Equipo</h2>
                <p class="mt-2 text-sm text-gray-500">Un equipo comprometido con la salud animal.</p>
            </div>
            <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-4">
                @foreach($team as $member)
                    <x-team-card :member="$member" />
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- Marcas --}}
@if($brands->isNotEmpty())
    <section class="bg-white py-14">
        <div class="mx-auto max-w-7xl px-6 text-center">
            <h2 class="text-2xl font-bold text-gray-800">Laboratorios que representamos</h2>
            <div class="mt-10 grid grid-cols-2 items-center gap-8 sm:grid-cols-3 lg:grid-cols-4">
                @foreach($brands as $brand)
                    <div class="flex items-center justify-center">
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
@endsection
