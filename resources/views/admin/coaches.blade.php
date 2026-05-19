<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.coaches') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif

        <div class="flex justify-end">
            <x-button type="button" class="gap-2" x-on:click="$dispatch('open-modal', 'add-coach')">
                <x-icon name="plus" size="17" />
                {{ __('messages.add_coach') }}
            </x-button>
        </div>

        <x-modal name="add-coach" :show="old('_modal') === 'add-coach'" maxWidth="xl">
            <form method="POST" action="{{ route('admin.coaches.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="_modal" value="add-coach">
                <input type="hidden" name="status" value="active">

                <div>
                    <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.add_coach') }}</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('messages.coaches') }}</p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.coach_name') }}</span>
                        <input name="name" value="{{ old('_modal') === 'add-coach' ? old('name') : '' }}" placeholder="Amine Carter" class="igym-input" required>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.email') }}</span>
                        <input type="email" name="email" value="{{ old('_modal') === 'add-coach' ? old('email') : '' }}" placeholder="coach@example.com" class="igym-input" required>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.phone') }}</span>
                        <input name="phone" value="{{ old('_modal') === 'add-coach' ? old('phone') : '' }}" placeholder="+212 600 000 000" class="igym-input">
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.password') }}</span>
                        <input type="password" name="password" placeholder="Minimum 8 characters" class="igym-input" required>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.confirm_password') }}</span>
                        <input type="password" name="password_confirmation" placeholder="{{ __('messages.confirm_password') }}" class="igym-input" required>
                    </label>
                </div>

                <div class="flex justify-end gap-3">
                    <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', 'add-coach')">{{ __('messages.cancel') }}</x-button>
                    <x-button>{{ __('messages.add_coach') }}</x-button>
                </div>
            </form>
        </x-modal>

        <x-table>
            <thead class="bg-slate-50 dark:bg-slate-800/60"><tr><th class="px-4 py-3 text-start">{{ __('messages.coach') }}</th><th class="px-4 py-3 text-start">{{ __('messages.assigned_courses') }}</th><th class="px-4 py-3 text-start">{{ __('messages.status') }}</th><th class="px-4 py-3 text-end">{{ __('messages.actions') }}</th></tr></thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @foreach($coaches as $coach)
                    @php($editModal = 'edit-coach-'.$coach->id)
                    @php($deleteModal = 'delete-coach-'.$coach->id)
                    <tr>
                        <td class="px-4 py-3"><p class="font-bold">{{ $coach->name }}</p><p class="text-xs text-slate-500">{{ $coach->email }}</p></td>
                        <td class="px-4 py-3">{{ $coach->coached_courses_count }}</td>
                        <td class="px-4 py-3"><x-badge :status="$coach->status" /></td>
                        <td class="px-4 py-3 text-end">
                            <div class="flex justify-end gap-1">
                                <button type="button" class="igym-action igym-action-edit" x-on:click="$dispatch('open-modal', '{{ $editModal }}')" title="{{ __('messages.edit') }}">
                                    <x-icon name="edit" size="16" />
                                    {{ __('messages.edit') }}
                                </button>
                                <button type="button" class="igym-action igym-action-danger" x-on:click="$dispatch('open-modal', '{{ $deleteModal }}')" title="{{ __('messages.delete') }}">
                                    <x-icon name="trash" size="16" />
                                    {{ __('messages.delete') }}
                                </button>
                            </div>
                        </td>
                    </tr>

                @endforeach
            </tbody>
        </x-table>

        @foreach($coaches as $coach)
            @php($editModal = 'edit-coach-'.$coach->id)
            @php($deleteModal = 'delete-coach-'.$coach->id)

            <x-modal name="{{ $editModal }}" :show="old('_modal') === $editModal" maxWidth="xl">
                <form method="POST" action="{{ route('admin.coaches.update', $coach) }}" class="space-y-5">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="_modal" value="{{ $editModal }}">

                    <div>
                        <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.edit') }} {{ __('messages.coach') }}</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $coach->name }}</p>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <label class="igym-field">
                            <span class="igym-label">{{ __('messages.coach_name') }}</span>
                            <input name="name" value="{{ old('_modal') === $editModal ? old('name') : $coach->name }}" placeholder="Amine Carter" class="igym-input" required>
                        </label>
                        <label class="igym-field">
                            <span class="igym-label">{{ __('messages.email') }}</span>
                            <input type="email" name="email" value="{{ old('_modal') === $editModal ? old('email') : $coach->email }}" placeholder="coach@example.com" class="igym-input" required>
                        </label>
                        <label class="igym-field">
                            <span class="igym-label">{{ __('messages.phone') }}</span>
                            <input name="phone" value="{{ old('_modal') === $editModal ? old('phone') : $coach->phone }}" placeholder="+212 600 000 000" class="igym-input">
                        </label>
                        <label class="igym-field">
                            <span class="igym-label">{{ __('messages.new_password') }}</span>
                            <input type="password" name="password" placeholder="{{ __('messages.leave_blank_keep_password') }}" class="igym-input">
                        </label>
                        <label class="igym-field">
                            <span class="igym-label">{{ __('messages.confirm_password') }}</span>
                            <input type="password" name="password_confirmation" placeholder="{{ __('messages.confirm_password') }}" class="igym-input">
                        </label>
                        <label class="igym-field md:col-span-2">
                            <span class="igym-label">{{ __('messages.status') }}</span>
                            <select name="status" class="igym-input">
                                @foreach(['active', 'inactive'] as $status)
                                    <option value="{{ $status }}" @selected((old('_modal') === $editModal ? old('status') : $coach->status) === $status)>{{ Str::headline($status) }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>

                    <div class="flex justify-end gap-3">
                        <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', '{{ $editModal }}')">{{ __('messages.cancel') }}</x-button>
                        <x-button>{{ __('messages.save') }}</x-button>
                    </div>
                </form>
            </x-modal>

            <x-modal name="{{ $deleteModal }}" maxWidth="md">
                <form method="POST" action="{{ route('admin.coaches.destroy', $coach) }}" class="space-y-5">
                    @csrf
                    @method('DELETE')

                    <div>
                        <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.delete') }} {{ __('messages.coach') }}</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $coach->name }} · {{ $coach->email }}</p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', '{{ $deleteModal }}')">{{ __('messages.cancel') }}</x-button>
                        <x-button variant="danger" class="gap-2">
                            <x-icon name="trash" size="16" />
                            {{ __('messages.delete') }}
                        </x-button>
                    </div>
                </form>
            </x-modal>
        @endforeach

        {{ $coaches->links() }}
    </div>
</x-app-layout>
