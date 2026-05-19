<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.coaches') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif
        <form method="POST" action="{{ route('admin.coaches.store') }}" class="igym-card grid gap-4 p-5 md:grid-cols-5">
            @csrf
            <input name="name" placeholder="Coach name" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required>
            <input type="email" name="email" placeholder="Email" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required>
            <input name="phone" placeholder="Phone" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950">
            <input type="password" name="password" placeholder="Password" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required>
            <input type="hidden" name="status" value="active"><x-button>Add Coach</x-button>
        </form>
        <x-table>
            <thead class="bg-slate-50 dark:bg-slate-800/60"><tr><th class="px-4 py-3 text-start">Coach</th><th class="px-4 py-3 text-start">Assigned Courses</th><th class="px-4 py-3 text-start">Status</th><th class="px-4 py-3 text-end">Actions</th></tr></thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @foreach($coaches as $coach)
                    <tr>
                        <td class="px-4 py-3"><p class="font-bold">{{ $coach->name }}</p><p class="text-xs text-slate-500">{{ $coach->email }}</p></td>
                        <td class="px-4 py-3">{{ $coach->coached_courses_count }}</td>
                        <td class="px-4 py-3"><x-badge :status="$coach->status" /></td>
                        <td class="px-4 py-3 text-end"><form method="POST" action="{{ route('admin.coaches.destroy', $coach) }}" onsubmit="return confirm('Delete coach?')">@csrf @method('DELETE')<button class="font-bold text-rose-600">Delete</button></form></td>
                    </tr>
                @endforeach
            </tbody>
        </x-table>
        {{ $coaches->links() }}
    </div>
</x-app-layout>
