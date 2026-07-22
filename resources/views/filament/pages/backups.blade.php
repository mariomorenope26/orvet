<x-filament-panels::page>
    @php($backups = $this->getBackups())

    <style>
        .bk-alert { border-radius: 12px; padding: .8rem 1rem; font-size: .9rem; font-weight: 500; background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
        .dark .bk-alert { background: rgba(6,78,59,.4); border-color: #065f46; color: #a7f3d0; }
        .bk-upload { display: flex; flex-wrap: wrap; align-items: center; gap: .75rem; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 1rem; }
        .dark .bk-upload { background: #1f2937; border-color: #4b5563; }
        .bk-file { font-size: .85rem; color: #475569; flex: 1 1 220px; }
        .dark .bk-file { color: #cbd5e1; }
        .bk-table { width: 100%; border-collapse: collapse; font-size: .875rem; }
        .bk-table th { text-align: left; padding: .6rem .75rem; font-weight: 600; color: #64748b; border-bottom: 2px solid #e2e8f0; font-size: .78rem; text-transform: uppercase; letter-spacing: .03em; }
        .dark .bk-table th { color: #94a3b8; border-color: #374151; }
        .bk-table td { padding: .7rem .75rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .dark .bk-table td { border-color: #273244; }
        .bk-table tr:hover td { background: #f8fafc; }
        .dark .bk-table tr:hover td { background: #1f2937; }
        .bk-name { font-family: ui-monospace, monospace; font-size: .8rem; color: #334155; }
        .dark .bk-name { color: #e2e8f0; }
        .bk-badge { display: inline-block; background: #eef2ff; color: #4338ca; border-radius: 9999px; padding: .1rem .6rem; font-size: .75rem; font-weight: 600; }
        .dark .bk-badge { background: rgba(67,56,202,.25); color: #c7d2fe; }
        .bk-actions { display: flex; gap: .4rem; justify-content: flex-end; flex-wrap: wrap; }
        .bk-btn { display: inline-flex; align-items: center; gap: .3rem; border: 0; cursor: pointer; border-radius: 8px; padding: .35rem .75rem; font-size: .78rem; font-weight: 600; text-decoration: none; transition: filter .15s; }
        .bk-btn:hover { filter: brightness(.95); }
        .bk-btn--down { background: #e2e8f0; color: #334155; }
        .dark .bk-btn--down { background: #374151; color: #e5e7eb; }
        .bk-btn--restore { background: #fef3c7; color: #92400e; }
        .dark .bk-btn--restore { background: rgba(146,64,14,.35); color: #fcd34d; }
        .bk-btn--delete { background: #fee2e2; color: #b91c1c; }
        .dark .bk-btn--delete { background: rgba(153,27,27,.35); color: #fca5a5; }
        .bk-empty { text-align: center; color: #94a3b8; padding: 2rem 0; }
        .bk-form { margin: 0; }
    </style>

    @if(session('backup_status'))
        <div class="bk-alert">✓ {{ session('backup_status') }}</div>
    @endif

    <x-filament::section>
        <x-slot name="heading">Restaurar desde un archivo</x-slot>
        <x-slot name="description">Sube un archivo <code>.sql</code> generado previamente. Esta acción reemplaza todos los datos actuales.</x-slot>

        <form action="{{ route('admin.backups.upload') }}" method="POST" enctype="multipart/form-data"
              onsubmit="return confirm('¿Restaurar la base de datos con este archivo? Se reemplazarán los datos actuales.');"
              class="bk-form">
            @csrf
            <div class="bk-upload">
                <input type="file" name="file" accept=".sql" required class="bk-file">
                <x-filament::button type="submit" color="warning" icon="heroicon-o-arrow-up-tray">
                    Restaurar
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">Copias disponibles ({{ count($backups) }})</x-slot>
        <x-slot name="description">Se guardan en <code>storage/app/backups</code>.</x-slot>

        @if(empty($backups))
            <p class="bk-empty">Aún no hay copias. Usa el botón «Crear copia de seguridad» de arriba a la derecha.</p>
        @else
            <div style="overflow-x:auto;">
                <table class="bk-table">
                    <thead>
                        <tr>
                            <th>Archivo</th>
                            <th>Fecha</th>
                            <th>Tamaño</th>
                            <th style="text-align:right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($backups as $item)
                            <tr>
                                <td><span class="bk-name">{{ $item['name'] }}</span></td>
                                <td style="color:#64748b;white-space:nowrap;">{{ $item['date']->format('d/m/Y H:i') }}</td>
                                <td><span class="bk-badge">{{ $this->humanSize($item['size']) }}</span></td>
                                <td>
                                    <div class="bk-actions">
                                        <a href="{{ route('admin.backups.download', $item['name']) }}" class="bk-btn bk-btn--down">⤓ Descargar</a>
                                        <form action="{{ route('admin.backups.restore', $item['name']) }}" method="POST" class="bk-form"
                                              onsubmit="return confirm('¿Restaurar «{{ $item['name'] }}»? Se reemplazarán los datos actuales.');">
                                            @csrf
                                            <button type="submit" class="bk-btn bk-btn--restore">↺ Restaurar</button>
                                        </form>
                                        <form action="{{ route('admin.backups.delete', $item['name']) }}" method="POST" class="bk-form"
                                              onsubmit="return confirm('¿Eliminar esta copia de forma permanente?');">
                                            @csrf
                                            <button type="submit" class="bk-btn bk-btn--delete">✕ Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-filament::section>
</x-filament-panels::page>
