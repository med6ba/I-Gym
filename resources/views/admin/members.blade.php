<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.members') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif

        <div class="flex justify-end">
            <x-button type="button" class="gap-2" x-on:click="$dispatch('open-modal', 'add-member')">
                <x-icon name="plus" size="17" />
                {{ __('messages.add_member') }}
            </x-button>
        </div>

        <x-modal name="add-member" :show="old('_modal') === 'add-member'" maxWidth="xl">
            <form method="POST" action="{{ route('admin.members.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="_modal" value="add-member">
                <input type="hidden" name="status" value="active">

                <div>
                    <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.add_member') }}</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('messages.members') }}</p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.member_name') }}</span>
                        <input name="name" value="{{ old('_modal') === 'add-member' ? old('name') : '' }}" placeholder="Sarah Johnson" class="igym-input" required>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.email') }}</span>
                        <input type="email" name="email" value="{{ old('_modal') === 'add-member' ? old('email') : '' }}" placeholder="member@example.com" class="igym-input" required>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.phone') }}</span>
                        <input name="phone" value="{{ old('_modal') === 'add-member' ? old('phone') : '' }}" placeholder="+212 600 000 000" class="igym-input">
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.password') }}</span>
                        <input type="password" name="password" placeholder="Minimum 8 characters" class="igym-input" required>
                    </label>
                </div>

                <div class="flex justify-end gap-3">
                    <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', 'add-member')">{{ __('messages.cancel') }}</x-button>
                    <x-button>{{ __('messages.add_member') }}</x-button>
                </div>
            </form>
        </x-modal>

        <x-table>
            <thead class="bg-slate-50 dark:bg-slate-800/60"><tr><th class="px-4 py-3 text-start">{{ __('messages.name') }}</th><th class="px-4 py-3 text-start">{{ __('messages.subscription') }}</th><th class="px-4 py-3 text-start">{{ __('messages.status') }}</th><th class="px-4 py-3 text-end">{{ __('messages.actions') }}</th></tr></thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @foreach($members as $member)
                    @php($editModal = 'edit-member-'.$member->id)
                    @php($deleteModal = 'delete-member-'.$member->id)
                    <tr>
                        <td class="px-4 py-3"><p class="font-bold">{{ $member->name }}</p><p class="text-xs text-slate-500">{{ $member->email }}</p></td>
                        <td class="px-4 py-3">{{ $member->activeSubscription?->plan_name ?? 'No active plan' }}</td>
                        <td class="px-4 py-3"><x-badge :status="$member->status" /></td>
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

        @foreach($members as $member)
            @php($editModal = 'edit-member-'.$member->id)
            @php($deleteModal = 'delete-member-'.$member->id)

            <x-modal name="{{ $editModal }}" :show="old('_modal') === $editModal" maxWidth="xl">
                <form method="POST" action="{{ route('admin.members.update', $member) }}" class="space-y-5">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="_modal" value="{{ $editModal }}">

                    <div>
                        <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.edit') }} {{ __('messages.member') }}</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $member->name }}</p>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <label class="igym-field">
                            <span class="igym-label">{{ __('messages.member_name') }}</span>
                            <input name="name" value="{{ old('_modal') === $editModal ? old('name') : $member->name }}" placeholder="Sarah Johnson" class="igym-input" required>
                        </label>
                        <label class="igym-field">
                            <span class="igym-label">{{ __('messages.email') }}</span>
                            <input type="email" name="email" value="{{ old('_modal') === $editModal ? old('email') : $member->email }}" placeholder="member@example.com" class="igym-input" required>
                        </label>
                        <label class="igym-field">
                            <span class="igym-label">{{ __('messages.phone') }}</span>
                            <input name="phone" value="{{ old('_modal') === $editModal ? old('phone') : $member->phone }}" placeholder="+212 600 000 000" class="igym-input">
                        </label>
                        <label class="igym-field">
                            <span class="igym-label">{{ __('messages.new_password') }}</span>
                            <input type="password" name="password" placeholder="{{ __('messages.leave_blank_keep_password') }}" class="igym-input">
                        </label>
                        <label class="igym-field md:col-span-2">
                            <span class="igym-label">{{ __('messages.status') }}</span>
                            <select name="status" class="igym-input">
                                @foreach(['active', 'inactive'] as $status)
                                    <option value="{{ $status }}" @selected((old('_modal') === $editModal ? old('status') : $member->status) === $status)>{{ Str::headline($status) }}</option>
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
                <form method="POST" action="{{ route('admin.members.destroy', $member) }}" class="space-y-5">
                    @csrf
                    @method('DELETE')

                    <div>
                        <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.delete') }} {{ __('messages.member') }}</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $member->name }} · {{ $member->email }}</p>
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

        {{ $members->links() }}
    </div>
</x-app-layout>
