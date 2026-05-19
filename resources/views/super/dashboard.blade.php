<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm font-bold uppercase text-amber-600">{{ __('messages.saas_command_center') }}</p>
                <h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.dashboard') }}</h2>
            </div>
            <button type="button" x-on:click="$dispatch('open-modal', 'add-gym')" class="inline-flex items-center gap-2 rounded-lg bg-amber-500 px-4 py-2 text-sm font-black text-slate-950 hover:bg-amber-400">
                <x-icon name="plus" size="17" />
                {{ __('messages.add_admin_with_gym') }}
            </button>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
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

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            <x-stat-card :label="__('messages.gyms')" :value="$totalGyms" />
            <x-stat-card :label="__('messages.active_gyms')" :value="$activeGyms" />
            <x-stat-card :label="__('messages.trial_gyms')" :value="$trialGyms" />
            <x-stat-card :label="__('messages.expired_gyms')" :value="$expiredGyms" />
            <x-stat-card :label="__('messages.admin_accounts')" :value="$totalAdmins" />
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <x-chart-card :title="__('messages.saas_growth')">
                <div class="igym-chart-frame">
                    <canvas id="growthChart"></canvas>
                </div>
            </x-chart-card>
            <x-chart-card :title="__('messages.gym_status')">
                <div class="igym-chart-frame">
                    <canvas id="statusChart"></canvas>
                </div>
            </x-chart-card>
        </div>

        <x-chart-card :title="__('messages.recent_gym_customers')">
            <div class="space-y-3">
                @foreach($recentGyms as $gym)
                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                        <div>
                            <p class="font-bold text-slate-950 dark:text-white">{{ $gym->name }}</p>
                            <p class="text-sm text-slate-500">{{ $gym->city }} · {{ $gym->primaryAdmin?->name ?? __('messages.no_admin_assigned') }} · {{ $gym->primaryAdmin?->email }}</p>
                        </div>
                        <x-badge :status="$gym->status" />
                    </div>
                @endforeach
            </div>
        </x-chart-card>

        <x-chart-card :title="__('messages.admin_accounts')">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[680px] text-sm">
                    <thead class="text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-3 py-2 text-start font-black">{{ __('messages.gym_admin') }}</th>
                            <th class="px-3 py-2 text-start font-black">{{ __('messages.gym_name') }}</th>
                            <th class="px-3 py-2 text-start font-black">{{ __('messages.status') }}</th>
                            <th class="px-3 py-2 text-end font-black">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        @foreach($adminAccounts as $admin)
                            <tr>
                                <td class="px-3 py-3">
                                    <p class="font-black text-slate-950 dark:text-white">{{ $admin->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $admin->email }}</p>
                                </td>
                                <td class="px-3 py-3">
                                    <p class="font-bold text-slate-800 dark:text-slate-100">{{ $admin->gym?->name ?? __('messages.no_data') }}</p>
                                    <p class="text-xs text-slate-500">{{ $admin->gym?->city }}</p>
                                </td>
                                <td class="px-3 py-3"><x-badge :status="$admin->status" /></td>
                                <td class="px-3 py-3 text-end">
                                    @if($admin->gym)
                                        @php($editModal = 'edit-dashboard-gym-'.$admin->gym->id)
                                        <button type="button" class="igym-action igym-action-edit" x-on:click="$dispatch('open-modal', '{{ $editModal }}')" title="{{ __('messages.edit') }}">
                                            <x-icon name="edit" size="16" />
                                            {{ __('messages.edit') }}
                                        </button>

                                        <x-modal name="{{ $editModal }}" :show="old('_modal') === $editModal" maxWidth="2xl">
                                            <form method="POST" action="{{ route('super.gyms.update', $admin->gym) }}" class="space-y-5">
                                                @method('PATCH')
                                                <div>
                                                    <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.edit_gym', ['name' => $admin->gym->name]) }}</h3>
                                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $admin->email }}</p>
                                                </div>
                                                @include('super.gyms._form', ['gym' => $admin->gym, 'inModal' => true, 'modalName' => $editModal])
                                            </form>
                                        </x-modal>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-chart-card>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            window.igymChart('growthChart', {
                type: 'line',
                data: { labels: @json($growthChart['labels']), datasets: [{ label: @js(__('messages.gyms')), data: @json($growthChart['data']), borderColor: '#F59E0B', backgroundColor: 'rgba(245, 158, 11, .15)', tension: .35, fill: true }] },
            });
            window.igymChart('statusChart', {
                type: 'doughnut',
                data: { labels: @json($statusChart['labels']), datasets: [{ data: @json($statusChart['data']), backgroundColor: ['#22C55E', '#F59E0B', '#EF4444', '#64748B'] }] },
            });
        });
    </script>
</x-app-layout>
