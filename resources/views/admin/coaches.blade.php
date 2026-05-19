<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.coaches') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif
        <form method="POST" action="{{ route('admin.coaches.store') }}" class="igym-card grid gap-4 p-5 md:grid-cols-5">
            @csrf
            <input name="name" placeholder="{{ __('messages.coach_name') }}" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required>
            <input type="email" name="email" placeholder="{{ __('messages.email') }}" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required>
            <input name="phone" placeholder="{{ __('messages.phone') }}" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950">
            <input type="password" name="password" placeholder="{{ __('messages.password') }}" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required>
            <input type="hidden" name="status" value="active"><x-button>{{ __('messages.add_coach') }}</x-button>
        </form>
        <x-table>
            <thead class="bg-slate-50 dark:bg-slate-800/60"><tr><th class="px-4 py-3 text-start">{{ __('messages.coach') }}</th><th class="px-4 py-3 text-start">{{ __('messages.assigned_courses') }}</th><th class="px-4 py-3 text-start">{{ __('messages.status') }}</th><th class="px-4 py-3 text-end">{{ __('messages.actions') }}</th></tr></thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @foreach($coaches as $coach)
                    <tr>
                        <td class="px-4 py-3"><p class="font-bold">{{ $coach->name }}</p><p class="text-xs text-slate-500">{{ $coach->email }}</p></td>
                        <td class="px-4 py-3">{{ $coach->coached_courses_count }}</td>
                        <td class="px-4 py-3"><x-badge :status="$coach->status" /></td>
                        <td class="px-4 py-3 text-end"><form method="POST" action="{{ route('admin.coaches.destroy', $coach) }}" onsubmit="return confirm('{{ __('messages.delete') }}?')">@csrf @method('DELETE')<button class="font-bold text-rose-600">{{ __('messages.delete') }}</button></form></td>
                    </tr>
                @endforeach
            </tbody>
        </x-table>
        {{ $coaches->links() }}
    </div>
</x-app-layout>
