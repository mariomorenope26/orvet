@props(['member'])

<div tabindex="0" class="group h-72 [perspective:1200px] focus:outline-none">
    <div class="relative h-full w-full rounded-2xl shadow-md transition-transform duration-500 [transform-style:preserve-3d] group-hover:[transform:rotateY(180deg)] group-focus:[transform:rotateY(180deg)]">

        {{-- Frente --}}
        <div class="absolute inset-0 overflow-hidden rounded-2xl bg-white [backface-visibility:hidden]">
            @if($member->photo)
                <img src="{{ media_url($member->photo) }}" alt="{{ $member->name }}" class="h-full w-full object-cover">
            @else
                <div class="flex h-full w-full items-center justify-center bg-primary/10 text-primary">
                    <svg class="h-20 w-20" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                </div>
            @endif
            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 to-transparent p-4 text-white">
                <h3 class="text-lg font-bold leading-tight">{{ $member->name }}</h3>
                @if($member->role)<p class="text-sm text-accent">{{ $member->role }}</p>@endif
            </div>
        </div>

        {{-- Reverso --}}
        <div class="absolute inset-0 flex flex-col justify-center rounded-2xl bg-primary p-6 text-center text-white [backface-visibility:hidden] [transform:rotateY(180deg)]">
            <h3 class="text-lg font-bold">{{ $member->name }}</h3>
            @if($member->role)<p class="mb-3 text-sm font-medium text-white/80">{{ $member->role }}</p>@endif
            @if($member->description)<p class="text-sm leading-relaxed text-white/90">{{ $member->description }}</p>@endif
            <div class="mt-4 space-y-1 text-sm">
                @if($member->phone)
                    <p class="flex items-center justify-center gap-1.5">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                        {{ $member->phone }}
                    </p>
                @endif
                @if($member->email)
                    <p class="flex items-center justify-center gap-1.5 break-all">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        {{ $member->email }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
