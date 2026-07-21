@extends('layouts.app')

@section('title', $post->meta_title ?: $post->title)
@section('meta_description', $post->meta_description ?: $post->excerpt)

@section('content')
<section class="bg-gradient-to-r from-primary-dark to-primary py-14 text-white">
    <div class="mx-auto max-w-4xl px-6">
        @if($post->category)<span class="text-sm font-semibold uppercase tracking-wide text-accent">{{ $post->category }}</span>@endif
        <h1 class="mt-2 text-3xl font-extrabold sm:text-4xl">{{ $post->title }}</h1>
        <p class="mt-3 text-sm text-white/80">{{ $post->published_at?->translatedFormat('d \d\e F, Y') }}</p>
    </div>
</section>

<section class="py-14">
    <div class="mx-auto grid max-w-6xl gap-10 px-6 lg:grid-cols-3">
        <article class="lg:col-span-2">
            @if($post->image)
                <img src="{{ media_url($post->image) }}" alt="{{ $post->title }}" class="mb-8 w-full rounded-2xl object-cover">
            @endif
            <div class="prose-orvet max-w-none text-gray-600">{!! $post->body !!}</div>
        </article>

        <aside>
            <div class="rounded-2xl border border-gray-200 bg-white p-6">
                <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-gray-700">Entradas recientes</h3>
                <ul class="space-y-4">
                    @forelse($recent as $item)
                        <li>
                            <a href="{{ route('blog.show', $item) }}" class="group flex gap-3">
                                @if($item->image)
                                    <img src="{{ media_url($item->image) }}" alt="" class="h-14 w-14 shrink-0 rounded-lg object-cover">
                                @endif
                                <span class="text-sm font-medium text-gray-700 group-hover:text-primary">{{ $item->title }}</span>
                            </a>
                        </li>
                    @empty
                        <li class="text-sm text-gray-400">No hay más entradas.</li>
                    @endforelse
                </ul>
            </div>
        </aside>
    </div>
</section>
@endsection
