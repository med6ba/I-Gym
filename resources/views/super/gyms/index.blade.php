<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.gyms') }}</h2>
            <div class="flex gap-2">
                <button type="button" x-on:click="$dispatch('open-modal', 'add-gym')" class="inline-flex items-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-black text-slate-950 hover:bg-amber-400">
                    <x-icon name="plus" size="17" />
                    {{ __('messages.add_gym') }}
                </button>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif

        <x-modal name="add-gym" :show="old('_modal') === 'add-gym'" maxWidth="2xl">
            <form method="POST" action="{{ route('super.gyms.store') }}" class="space-y-5">
                <div>
                    <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.add_admin_with_gym') }}</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('messages.gyms') }}</p>
                </div>
                @include('super.gyms._form', ['gym' => new \App\Models\Gym, 'inModal' => true, 'modalName' => 'add-gym'])
            </form>
        </x-modal>

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
                    @php($editModal = 'edit-gym-'.$gym->id)
                    @php($deleteModal = 'delete-gym-'.$gym->id)
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

        @foreach($gyms as $gym)
            @php($editModal = 'edit-gym-'.$gym->id)
            @php($deleteModal = 'delete-gym-'.$gym->id)

            <x-modal name="{{ $editModal }}" :show="old('_modal') === $editModal" maxWidth="2xl">
                <form method="POST" action="{{ route('super.gyms.update', $gym) }}" class="space-y-5">
                    @method('PATCH')
                    <div>
                        <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.edit_gym', ['name' => $gym->name]) }}</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $gym->email }}</p>
                    </div>
                    @include('super.gyms._form', ['gym' => $gym, 'inModal' => true, 'modalName' => $editModal])
                </form>
            </x-modal>

            <x-modal name="{{ $deleteModal }}" maxWidth="md">
                <form method="POST" action="{{ route('super.gyms.destroy', $gym) }}" class="space-y-5">
                    @csrf
                    @method('DELETE')

                    <div>
                        <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.delete') }} {{ __('messages.gym') }}</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $gym->name }} · {{ $gym->email }}</p>
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

        {{ $gyms->links() }}
    </div>
</x-app-layout>
