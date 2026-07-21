@props(['member'])

@php
    $waDigits = preg_replace('/\D/', '', (string) $member->whatsapp);
    if ($waDigits && strlen($waDigits) === 9) {
        $waDigits = '51'.$waDigits;
    }
    $waText = rawurlencode('Hola '.$member->name.', me comunico desde la web de Orvet.');
@endphp

<div tabindex="0" class="group h-80 [perspective:1200px] focus:outline-none">
    <div class="relative h-full w-full rounded-2xl shadow-md transition-transform duration-500 [transform-style:preserve-3d] group-hover:[transform:rotateY(180deg)] group-focus:[transform:rotateY(180deg)]">

        {{-- Frente --}}
        <div class="absolute inset-0 flex flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white [backface-visibility:hidden]">
            <div class="flex flex-1 items-center justify-center overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                @if($member->photo)
                    <img src="{{ media_url($member->photo) }}" alt="{{ $member->name }}" class="h-full w-full object-cover">
                @else
                    <img src="{{ media_url(setting('logo'), asset('logorvet.png')) }}" alt="Orvet" class="max-h-28 w-auto object-contain p-6">
                @endif
            </div>
            <div class="px-4 py-4 text-center">
                <h3 class="text-base font-bold leading-tight text-gray-800">{{ $member->name }}</h3>
                @if($member->zone)<p class="mt-0.5 text-sm font-semibold text-accent">{{ $member->zone }}</p>@endif
                @if($member->role)<p class="mt-0.5 text-xs text-gray-500">{{ $member->role }}</p>@endif
            </div>
        </div>

        {{-- Reverso --}}
        <div class="absolute inset-0 flex flex-col items-center justify-center rounded-2xl bg-primary p-6 text-center text-white [backface-visibility:hidden] [transform:rotateY(180deg)]">
            <img src="{{ media_url(setting('logo'), asset('logorvet.png')) }}" alt="Orvet" class="mb-3 h-10 w-auto rounded bg-white/95 p-1">
            <h3 class="text-lg font-bold leading-tight">{{ $member->name }}</h3>
            @if($member->role)<p class="text-sm font-medium text-white/80">{{ $member->role }}</p>@endif
            @if($member->zone)<p class="mt-1 text-sm text-accent">📍 {{ $member->zone }}</p>@endif
            @if($member->description)<p class="mt-2 text-sm leading-relaxed text-white/90">{{ $member->description }}</p>@endif

            <div class="mt-4 flex flex-col items-center gap-2 text-sm">
                @if($waDigits)
                    <a href="https://wa.me/{{ $waDigits }}?text={{ $waText }}" target="_blank" rel="noopener"
                       class="flex items-center gap-2 rounded-full bg-[#25D366] px-4 py-1.5 font-semibold text-white shadow transition hover:brightness-95">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.29.173-1.414-.074-.124-.272-.198-.57-.347"/></svg>
                        {{ $member->whatsapp }}
                    </a>
                @endif
                @if($member->phone)
                    <p class="text-white/90">Tel: {{ $member->phone }}</p>
                @endif
                @if($member->email)
                    <a href="mailto:{{ $member->email }}" class="break-all text-white/90 underline hover:text-white">{{ $member->email }}</a>
                @endif
            </div>
        </div>
    </div>
</div>
