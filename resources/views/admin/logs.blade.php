<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.activity_logs') }}</h2></x-slot>

    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        <div class="transition">
            <x-table>
                <thead class="bg-slate-50 dark:bg-slate-800/60">
                    <tr>
                        <th class="px-4 py-3 text-start font-bold">{{ __('messages.time') }}</th>
                        <th class="px-4 py-3 text-start font-bold">{{ __('messages.action') }}</th>
                        <th class="px-4 py-3 text-start font-bold">{{ __('messages.actor') }}</th>
                        <th class="px-4 py-3 text-start font-bold">{{ __('messages.details') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($logs as $log)
                        <tr>
                            <td class="whitespace-nowrap px-4 py-3 text-slate-500">{{ $log->created_at->format('M d, H:i') }}</td>
                            <td class="px-4 py-3"><x-badge status="info">{{ Str::headline(str_replace('.', ' ', $log->action)) }}</x-badge></td>
                            <td class="px-4 py-3 font-semibold">{{ $log->actor?->name ?? __('messages.system') }}</td>
                            <td class="px-4 py-3">
                                <p class="font-medium text-slate-900 dark:text-slate-100">{{ $log->description }}</p>
                                @if($log->metadata)
                                    <p class="mt-1 break-all font-mono text-xs text-slate-400">{{ json_encode($log->metadata) }}</p>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8"><x-empty-state :message="__('messages.no_activity_yet')" /></td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table>
            <div class="mt-4">{{ $logs->links() }}</div>
        </div>
    </div>
</x-app-layout>
