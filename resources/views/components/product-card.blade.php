@props(['product'])

<article class="group flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md">
    <a href="{{ route('products.show', $product) }}" class="relative block aspect-square overflow-hidden bg-gray-50">
        @if($product->image)
            <img src="{{ media_url($product->image) }}" alt="{{ $product->name }}"
                 class="h-full w-full object-contain p-4 transition duration-300 group-hover:scale-105">
        @else
            <div class="flex h-full w-full items-center justify-center text-gray-300">
                <svg class="h-16 w-16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
            </div>
        @endif
        @if($product->availability !== 'in_stock')
            <span class="absolute left-3 top-3 rounded-full bg-accent px-2.5 py-0.5 text-xs font-semibold text-white">
                {{ $product->availability_label }}
            </span>
        @endif
    </a>
    <div class="flex flex-1 flex-col p-4">
        @if($product->category)
            <span class="mb-1 text-xs font-medium uppercase tracking-wide text-accent">{{ $product->category->name }}</span>
        @endif
        <h3 class="mb-2 flex-1 text-sm font-semibold leading-snug text-gray-800">
            <a href="{{ route('products.show', $product) }}" class="hover:text-primary">{{ $product->name }}</a>
        </h3>
        <div class="mt-2 flex items-center justify-between">
            @if($product->price)
                <span class="text-lg font-bold text-primary">S/ {{ number_format($product->price, 2) }}</span>
            @else
                <span class="text-sm font-medium text-gray-500">Consultar precio</span>
            @endif
            <a href="{{ route('products.show', $product) }}"
               class="rounded-full bg-primary/10 px-3 py-1.5 text-xs font-semibold text-primary transition hover:bg-primary hover:text-white">
                Ver más
            </a>
        </div>
    </div>
</article>
