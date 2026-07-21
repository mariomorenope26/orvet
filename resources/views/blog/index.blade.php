@extends('layouts.app')

@section('title', 'Blog')

@section('content')
<section class="bg-gradient-to-r from-primary-dark to-primary py-14 text-white">
    <div class="mx-auto max-w-7xl px-6 text-center">
        <h1 class="text-4xl font-extrabold">Blog</h1>
        <p class="mx-auto mt-3 max-w-2xl text-white/90">Noticias y consejos sobre salud animal.</p>
    </div>
</section>

<section class="py-14">
    <div class="mx-auto max-w-7xl px-6">
        @if($posts->isEmpty())
            <div class="rounded-xl border border-dashed border-gray-300 bg-white p-12 text-center text-gray-500">
                Aún no hay entradas publicadas.
            </div>
        @else
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($posts as $post)
                    <article class="flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md">
                        <a href="{{ route('blog.show', $post) }}" class="block aspect-video overflow-hidden bg-gray-100">
                            @if($post->image)
                                <img src="{{ media_url($post->image) }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition duration-300 hover:scale-105">
                            @endif
                        </a>
                        <div class="flex flex-1 flex-col p-6">
                            @if($post->category)<span class="text-xs font-semibold uppercase tracking-wide text-accent">{{ $post->category }}</span>@endif
                            <h2 class="mt-2 text-lg font-bold text-gray-800"><a href="{{ route('blog.show', $post) }}" class="hover:text-primary">{{ $post->title }}</a></h2>
                            <p class="mt-2 flex-1 text-sm text-gray-500">{{ $post->excerpt }}</p>
                            <div class="mt-4 flex items-center justify-between text-xs text-gray-400">
                                <span>{{ $post->published_at?->translatedFormat('d M Y') }}</span>
                                <a href="{{ route('blog.show', $post) }}" class="font-semibold text-primary hover:underline">Leer más →</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="mt-10">{{ $posts->links() }}</div>
        @endif
    </div>
</section>
@endsection
