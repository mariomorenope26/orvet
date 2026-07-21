<x-filament-panels::page>
    @php($backups = $this->getBackups())
    @if(session('backup_status'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800 dark:border-green-700 dark:bg-green-900/30 dark:text-green-200">
            {{ session('backup_status') }}
        </div>
    @endif

    <x-filament::section>
        <x-slot name="heading">Restaurar desde un archivo</x-slot>
        <x-slot name="description">Sube un archivo <code>.sql</code> generado previamente para restaurar la base de datos. Esta acción reemplaza los datos actuales.</x-slot>

        <form action="{{ route('admin.backups.upload') }}" method="POST" enctype="multipart/form-data"
              onsubmit="return confirm('¿Restaurar la base de datos con este archivo? Se reemplazarán los datos actuales.');"
              class="flex flex-col gap-3 sm:flex-row sm:items-center">
            @csrf
            <input type="file" name="file" accept=".sql" required
                   class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-600 file:px-4 file:py-2 file:text-white hover:file:bg-primary-500 dark:text-gray-300" />
            <x-filament::button type="submit" color="warning" icon="heroicon-o-arrow-up-tray">
                Restaurar
            </x-filament::button>
        </form>
    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">Copias disponibles ({{ count($backups) }})</x-slot>
        <x-slot name="description">Se guardan en <code>storage/app/backups</code>.</x-slot>

        @if(empty($backups))
            <p class="text-sm text-gray-500">Aún no hay copias. Usa el botón «Crear copia de seguridad».</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-200 text-left text-gray-500 dark:border-gray-700">
                        <tr>
                            <th class="py-2 pr-4 font-medium">Archivo</th>
                            <th class="py-2 pr-4 font-medium">Fecha</th>
                            <th class="py-2 pr-4 font-medium">Tamaño</th>
                            <th class="py-2 pr-4 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($backups as $item)
                            <tr>
                                <td class="py-2 pr-4 font-mono text-xs text-gray-700 dark:text-gray-300">{{ $item['name'] }}</td>
                                <td class="py-2 pr-4 text-gray-500">{{ $item['date']->format('d/m/Y H:i') }}</td>
                                <td class="py-2 pr-4 text-gray-500">{{ $this->humanSize($item['size']) }}</td>
                                <td class="py-2 pr-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.backups.download', $item['name']) }}"
                                           class="rounded-md bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200">Descargar</a>

                                        <form action="{{ route('admin.backups.restore', $item['name']) }}" method="POST"
                                              onsubmit="return confirm('¿Restaurar «{{ $item['name'] }}»? Se reemplazarán los datos actuales.');">
                                            @csrf
                                            <button type="submit" class="rounded-md bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800 hover:bg-amber-200 dark:bg-amber-900/40 dark:text-amber-200">Restaurar</button>
                                        </form>

                                        <form action="{{ route('admin.backups.delete', $item['name']) }}" method="POST"
                                              onsubmit="return confirm('¿Eliminar esta copia?');">
                                            @csrf
                                            <button type="submit" class="rounded-md bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-200 dark:bg-red-900/40 dark:text-red-200">Eliminar</button>
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
