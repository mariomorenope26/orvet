@extends('layouts.app')

@section('title', 'Contacto')

@section('content')
<section class="bg-gradient-to-r from-primary-dark to-primary py-16 text-white">
    <div class="mx-auto max-w-7xl px-6 text-center">
        <h1 class="text-4xl font-extrabold">Contáctanos</h1>
        <p class="mx-auto mt-3 max-w-2xl text-white/90">Estamos para atenderte. Envíanos tu consulta.</p>
    </div>
</section>

<section class="py-16">
    <div class="mx-auto grid max-w-7xl gap-10 px-6 lg:grid-cols-2">
        {{-- Info --}}
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Información de contacto</h2>
            <ul class="mt-6 space-y-4 text-gray-600">
                @if(setting('address'))
                    <li class="flex gap-3">
                        <svg class="mt-0.5 h-6 w-6 shrink-0 text-primary" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        <span>{{ setting('address') }}</span>
                    </li>
                @endif
                @if(setting('phone_fixed') || setting('phone_mobile'))
                    <li class="flex gap-3">
                        <svg class="mt-0.5 h-6 w-6 shrink-0 text-primary" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                        <span>{{ setting('phone_fixed') }} @if(setting('phone_fixed') && setting('phone_mobile')) · @endif {{ setting('phone_mobile') }}</span>
                    </li>
                @endif
                @if(setting('email'))
                    <li class="flex gap-3">
                        <svg class="mt-0.5 h-6 w-6 shrink-0 text-primary" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        <a href="mailto:{{ setting('email') }}" class="hover:text-primary">{{ setting('email') }}</a>
                    </li>
                @endif
                @if(setting('schedule_weekday') || setting('schedule_saturday'))
                    <li class="flex gap-3">
                        <svg class="mt-0.5 h-6 w-6 shrink-0 text-primary" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ setting('schedule_weekday') }}<br>{{ setting('schedule_saturday') }}</span>
                    </li>
                @endif
            </ul>

            @if(setting('map_embed'))
                <div class="mt-8 overflow-hidden rounded-2xl border border-gray-200">
                    {!! setting('map_embed') !!}
                </div>
            @endif
        </div>

        {{-- Formulario --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
            <h2 class="text-2xl font-bold text-gray-800">Envíanos un mensaje</h2>

            @if(session('status'))
                <div class="mt-4 rounded-lg bg-primary/10 px-4 py-3 text-sm font-medium text-primary-dark">{{ session('status') }}</div>
            @endif

            <form action="{{ route('contact.send') }}" method="POST" class="mt-6 space-y-4">
                @csrf
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Nombre *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                        @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Correo *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                        @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Asunto</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Mensaje *</label>
                    <textarea name="message" rows="5" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary">{{ old('message') }}</textarea>
                    @error('message')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="rounded-full bg-primary px-8 py-3 font-semibold text-white shadow transition hover:bg-primary-dark">Enviar mensaje</button>
            </form>
        </div>
    </div>
</section>
@endsection
