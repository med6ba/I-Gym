<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.members') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif
        <form method="POST" action="{{ route('admin.members.store') }}" class="igym-card grid gap-4 p-5 md:grid-cols-5">
            @csrf
            <input name="name" placeholder="{{ __('messages.member_name') }}" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required>
            <input type="email" name="email" placeholder="{{ __('messages.email') }}" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required>
            <input name="phone" placeholder="{{ __('messages.phone') }}" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950">
            <input type="password" name="password" placeholder="{{ __('messages.password') }}" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required>
            <input type="hidden" name="status" value="active"><x-button>{{ __('messages.add_member') }}</x-button>
        </form>
        <x-table>
            <thead class="bg-slate-50 dark:bg-slate-800/60"><tr><th class="px-4 py-3 text-start">{{ __('messages.name') }}</th><th class="px-4 py-3 text-start">{{ __('messages.subscription') }}</th><th class="px-4 py-3 text-start">{{ __('messages.status') }}</th><th class="px-4 py-3 text-end">{{ __('messages.actions') }}</th></tr></thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @foreach($members as $member)
                    <tr>
                        <td class="px-4 py-3"><p class="font-bold">{{ $member->name }}</p><p class="text-xs text-slate-500">{{ $member->email }}</p></td>
                        <td class="px-4 py-3">{{ $member->activeSubscription?->plan_name ?? 'No active plan' }}</td>
                        <td class="px-4 py-3"><x-badge :status="$member->status" /></td>
                        <td class="px-4 py-3 text-end">
                            <form method="POST" action="{{ route('admin.members.destroy', $member) }}" onsubmit="return confirm('{{ __('messages.delete') }}?')">@csrf @method('DELETE')<button class="font-bold text-rose-600">{{ __('messages.delete') }}</button></form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </x-table>
        {{ $members->links() }}
    </div>
</x-app-layout>
