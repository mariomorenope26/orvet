@extends('layouts.app')

@section('title', $title)

@section('content')
<section class="bg-gradient-to-r from-primary-dark to-primary py-14 text-white">
    <div class="mx-auto max-w-4xl px-6">
        <h1 class="text-3xl font-extrabold">{{ $title }}</h1>
    </div>
</section>

<section class="py-14">
    <div class="mx-auto max-w-4xl px-6">
        <div class="prose-orvet max-w-none text-gray-600">
            @if($content)
                {!! $content !!}
            @else
                <p>El contenido de esta sección estará disponible próximamente.</p>
            @endif
        </div>
    </div>
</section>
@endsection
