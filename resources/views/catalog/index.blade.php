@extends('layouts.app')

@section('title', $category?->name ?? 'Productos')

@section('content')
<div class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-6">
        {{-- Migas de pan --}}
        <nav class="mb-4 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-primary">Inicio</a>
            <span class="mx-2">/</span>
            <a href="{{ route('products.index') }}" class="hover:text-primary">Productos</a>
            @if($category)
                <span class="mx-2">/</span>
                @if($category->parent)
                    <a href="{{ route('categories.show', $category->parent) }}" class="hover:text-primary">{{ $category->parent->name }}</a>
                    <span class="mx-2">/</span>
                @endif
                <span class="text-gray-800">{{ $category->name }}</span>
            @endif
        </nav>
        <h1 class="text-2xl font-bold text-gray-800">{{ $category?->name ?? 'Catálogo de productos' }}</h1>
        @if($category?->description)
            <p class="mt-1 text-sm text-gray-500">{{ $category->description }}</p>
        @endif
    </div>
</div>

<div class="mx-auto grid max-w-7xl gap-8 px-4 py-8 lg:grid-cols-4">
    {{-- Sidebar --}}
    <aside class="space-y-8 lg:col-span-1">
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <h3 class="mb-3 border-b border-gray-100 pb-2 text-sm font-bold uppercase tracking-wide text-gray-700">Categorías</h3>
            <ul class="space-y-1 text-sm">
                <li><a href="{{ route('products.index') }}" class="block rounded px-2 py-1 hover:bg-gray-50 {{ ! $category ? 'font-semibold text-primary' : 'text-gray-600' }}">Todos los productos</a></li>
                @foreach($navCategories as $root)
                    <li class="pt-1">
                        <a href="{{ route('categories.show', $root) }}" class="block rounded px-2 py-1 font-semibold hover:bg-gray-50 {{ $category?->id === $root->id ? 'text-primary' : 'text-gray-700' }}">
                            {{ $root->name }}
                        </a>
                        @if($root->children->isNotEmpty())
                            <ul class="ml-3 mt-1 space-y-0.5 border-l border-gray-100 pl-2">
                                @foreach($root->children as $child)
                                    <li>
                                        <a href="{{ route('categories.show', $child) }}" class="block rounded px-2 py-1 text-gray-600 hover:bg-gray-50 {{ $category?->id === $child->id ? 'font-semibold text-primary' : '' }}">
                                            {{ $child->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        @if($brands->isNotEmpty())
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <h3 class="mb-3 border-b border-gray-100 pb-2 text-sm font-bold uppercase tracking-wide text-gray-700">Marcas</h3>
                <ul class="space-y-1 text-sm">
                    @foreach($brands as $brand)
                        <li>
                            <a href="{{ request()->fullUrlWithQuery(['brand' => $brand->id, 'page' => null]) }}"
                               class="block rounded px-2 py-1 hover:bg-gray-50 {{ request('brand') == $brand->id ? 'font-semibold text-primary' : 'text-gray-600' }}">
                                {{ $brand->name }}
                            </a>
                        </li>
                    @endforeach
                    @if(request('brand'))
                        <li><a href="{{ request()->fullUrlWithQuery(['brand' => null, 'page' => null]) }}" class="block px-2 py-1 text-xs text-accent hover:underline">Quitar filtro de marca</a></li>
                    @endif
                </ul>
            </div>
        @endif
    </aside>

    {{-- Listado --}}
    <div class="lg:col-span-3">
        <form method="GET" class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3">
            @if(request('brand'))<input type="hidden" name="brand" value="{{ request('brand') }}">@endif
            <p class="text-sm text-gray-500">{{ $total }} producto(s) encontrado(s)</p>
            <div class="flex items-center gap-2">
                @if(request('q'))
                    <span class="text-sm text-gray-500">Búsqueda: <strong>{{ request('q') }}</strong></span>
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
                <label class="text-sm text-gray-500">Ordenar:</label>
                <select name="sort" onchange="this.form.submit()" class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:border-primary focus:outline-none">
                    <option value="name" @selected(request('sort') === 'name' || ! request('sort'))>Nombre (A-Z)</option>
                    <option value="latest" @selected(request('sort') === 'latest')>Más recientes</option>
                    <option value="price_asc" @selected(request('sort') === 'price_asc')>Precio: menor a mayor</option>
                    <option value="price_desc" @selected(request('sort') === 'price_desc')>Precio: mayor a menor</option>
                </select>
            </div>
        </form>

        @if($products->isEmpty())
            <div class="rounded-xl border border-dashed border-gray-300 bg-white p-12 text-center text-gray-500">
                No se encontraron productos con los filtros seleccionados.
            </div>
        @else
            <div class="grid grid-cols-2 gap-5 sm:grid-cols-3">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
            <div class="mt-8">{{ $products->links() }}</div>
        @endif
    </div>
</div>
@endsection
