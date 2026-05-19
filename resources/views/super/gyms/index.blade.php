<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.gyms') }}</h2>
            <a href="{{ route('super.admins.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-black text-slate-950 hover:bg-amber-400">
                <x-icon name="plus" size="17" />
                {{ __('messages.add_admin_with_gym') }}
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        <x-table>
            <thead class="bg-slate-50 dark:bg-slate-800/60">
                <tr>
                    <th class="px-4 py-3 text-start font-bold">{{ __('messages.gym_name') }}</th>
                    <th class="px-4 py-3 text-start font-bold">{{ __('messages.gym_admin') }}</th>
                    <th class="px-4 py-3 text-start font-bold">{{ __('messages.plan') }}</th>
                    <th class="px-4 py-3 text-start font-bold">{{ __('messages.status') }}</th>
                    <th class="px-4 py-3 text-start font-bold">{{ __('messages.platform_users') }}</th>
                    <th class="px-4 py-3 text-end font-bold">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @foreach($gyms as $gym)
                    <tr>
                        <td class="px-4 py-3">
                            <p class="font-bold text-slate-950 dark:text-white">{{ $gym->name }}</p>
                            <p class="text-xs text-slate-500">{{ $gym->city }} · {{ $gym->email }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-bold text-slate-950 dark:text-white">{{ $gym->primaryAdmin?->name ?? __('messages.no_admin_assigned') }}</p>
                            <p class="text-xs text-slate-500">{{ $gym->primaryAdmin?->email }}</p>
                        </td>
                        <td class="px-4 py-3">{{ Str::headline($gym->subscription_plan) }}</td>
                        <td class="px-4 py-3"><x-badge :status="$gym->status" /></td>
                        <td class="px-4 py-3">{{ $gym->users_count }}</td>
                        <td class="px-4 py-3 text-end">
                            <a href="{{ route('super.gyms.edit', $gym) }}" class="text-sm font-bold text-amber-600 hover:text-amber-500">{{ __('messages.edit') }}</a>
                            <form method="POST" action="{{ route('super.gyms.destroy', $gym) }}" class="inline" onsubmit="return confirm('{{ __('messages.delete_gym_confirm') }}')">
                                @csrf @method('DELETE')
                                <button class="ms-3 text-sm font-bold text-rose-600">{{ __('messages.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </x-table>
        {{ $gyms->links() }}
    </div>
</x-app-layout>
