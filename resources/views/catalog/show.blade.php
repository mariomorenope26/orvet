@extends('layouts.app')

@section('title', $product->meta_title ?: $product->name)
@section('meta_description', $product->meta_description ?: $product->short_description)

@section('content')
<div class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-4">
        <nav class="text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-primary">Inicio</a>
            <span class="mx-2">/</span>
            <a href="{{ route('products.index') }}" class="hover:text-primary">Productos</a>
            @if($product->category)
                <span class="mx-2">/</span>
                <a href="{{ route('categories.show', $product->category) }}" class="hover:text-primary">{{ $product->category->name }}</a>
            @endif
            <span class="mx-2">/</span>
            <span class="text-gray-800">{{ $product->name }}</span>
        </nav>
    </div>
</div>

<div class="mx-auto max-w-7xl px-4 py-8">
    <div class="grid gap-10 lg:grid-cols-2">
        {{-- Galería --}}
        <div>
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
                @if($product->image)
                    <img src="{{ media_url($product->image) }}" alt="{{ $product->name }}" class="mx-auto aspect-square w-full object-contain p-6">
                @else
                    <div class="flex aspect-square items-center justify-center text-gray-300">
                        <svg class="h-24 w-24" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/></svg>
                    </div>
                @endif
            </div>
            @if($product->images->isNotEmpty())
                <div class="mt-4 grid grid-cols-4 gap-3">
                    @foreach($product->images as $img)
                        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
                            <img src="{{ media_url($img->image) }}" alt="{{ $product->name }}" class="aspect-square w-full object-contain p-2">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Info --}}
        <div>
            @if($product->category)
                <span class="text-sm font-medium uppercase tracking-wide text-accent">{{ $product->category->name }}</span>
            @endif
            <h1 class="mt-1 text-3xl font-bold text-gray-800">{{ $product->name }}</h1>

            <div class="mt-4 flex flex-wrap items-center gap-4 text-sm">
                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 font-semibold {{ $product->availability === 'in_stock' ? 'bg-primary/10 text-primary' : 'bg-accent/10 text-accent' }}">
                    <span class="h-2 w-2 rounded-full {{ $product->availability === 'in_stock' ? 'bg-primary' : 'bg-accent' }}"></span>
                    {{ $product->availability_label }}
                </span>
                @if($product->sku)<span class="text-gray-500">SKU: {{ $product->sku }}</span>@endif
                @if($product->brand)<span class="text-gray-500">Marca: <strong>{{ $product->brand->name }}</strong></span>@endif
            </div>

            @if($product->price)
                <p class="mt-5 text-3xl font-extrabold text-primary">S/ {{ number_format($product->price, 2) }}</p>
            @endif

            @if($product->short_description)
                <p class="mt-4 text-justify leading-relaxed text-gray-600">{{ $product->short_description }}</p>
            @endif

            <dl class="mt-5 space-y-1 text-sm">
                @if($product->presentation)
                    <div class="flex gap-2"><dt class="font-semibold text-gray-700">Presentación:</dt><dd class="text-gray-600">{{ $product->presentation }}</dd></div>
                @endif
                @if($product->laboratory)
                    <div class="flex gap-2"><dt class="font-semibold text-gray-700">Laboratorio:</dt><dd class="text-gray-600">{{ $product->laboratory }}</dd></div>
                @endif
            </dl>

            {{-- Composición --}}
            @if($product->specs->isNotEmpty())
                <div class="mt-6 overflow-hidden rounded-xl border border-gray-200">
                    <div class="bg-primary px-4 py-2 text-sm font-semibold text-white">Composición</div>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr><th class="px-4 py-2 text-left font-medium">Principio activo</th><th class="px-4 py-2 text-left font-medium">Concentración</th></tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($product->specs as $spec)
                                <tr><td class="px-4 py-2 text-gray-700">{{ $spec->active_ingredient }}</td><td class="px-4 py-2 text-gray-600">{{ $spec->concentration }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Cotización --}}
            <div class="mt-8 flex flex-wrap gap-3">
                @if(setting('whatsapp'))
                    @php $waText = rawurlencode("Hola, quisiera cotizar el producto: {$product->name}"); @endphp
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', setting('whatsapp')) }}?text={{ $waText }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 rounded-full bg-[#25D366] px-6 py-3 font-semibold text-white shadow transition hover:brightness-95">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347"/></svg>
                        Cotizar por WhatsApp
                    </a>
                @endif
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 rounded-full border border-primary px-6 py-3 font-semibold text-primary transition hover:bg-primary hover:text-white">
                    Consultar
                </a>
            </div>
        </div>
    </div>

    {{-- Secciones descriptivas --}}
    @if($product->sections->isNotEmpty())
        <div class="mt-14 grid gap-6 lg:grid-cols-2">
            @foreach($product->sections as $section)
                <div class="rounded-2xl border border-gray-200 bg-white p-6">
                    <h2 class="mb-3 text-lg font-bold text-primary-dark">{{ $section->title }}</h2>
                    <div class="prose-orvet max-w-none text-justify text-gray-600">{!! $section->body !!}</div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Relacionados --}}
    @if($related->isNotEmpty())
        <div class="mt-16">
            <h2 class="mb-6 text-xl font-bold text-gray-800">Productos relacionados</h2>
            <div class="grid grid-cols-2 gap-5 md:grid-cols-4">
                @foreach($related as $item)
                    <x-product-card :product="$item" />
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
