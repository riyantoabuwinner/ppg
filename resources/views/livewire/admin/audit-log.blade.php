<div>
    <div class="card overflow-hidden">
        <div class="px-5 py-4 border-b border-purple-50 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-semibold text-gray-700 text-sm">Log Seluruh Aktivitas Sistem</h3>
            <input type="text" wire:model.live="search" placeholder="Cari aktivitas..." class="text-sm border rounded-lg px-3 py-1.5 w-56">
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelaku</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Subjek</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 bg-white">
                    @forelse($logs as $log)
                    <tr class="hover:bg-purple-50/30 transition">
                        <td class="px-5 py-3 whitespace-nowrap text-xs text-gray-500">{{ $log->created_at->format('d/m/y H:i') }}</td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-800">{{ $log->causer?->name ?? 'System' }}</span>
                            <span class="block text-xs text-gray-400">{{ $log->causer?->role ?? '-' }}</span>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            <span class="text-xs px-2 py-0.5 rounded-full
                                @if(str_contains($log->description, 'verif')) badge-verified
                                @elseif(str_contains($log->description, 'tolak') || str_contains($log->description, 'reject')) badge-rejected
                                @else badge-pending @endif">
                                {{ $log->description }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-xs text-gray-500">{{ class_basename($log->subject_type) ?? '-' }} #{{ $log->subject_id }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center text-gray-400 text-sm">Belum ada log aktivitas yang tersimpan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 bg-gray-50/50 border-t border-gray-100">
            {{ $logs->links() }}
        </div>
    </div>
</div>
