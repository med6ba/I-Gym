<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.subscriptions') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif

        <div class="flex justify-end">
            <x-button type="button" class="gap-2" x-on:click="$dispatch('open-modal', 'add-subscription')">
                <x-icon name="plus" size="17" />
                {{ __('messages.create_subscription') }}
            </x-button>
        </div>

        <x-modal name="add-subscription" :show="old('_modal') === 'add-subscription'" maxWidth="2xl">
            <form method="POST" action="{{ route('admin.subscriptions.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="_modal" value="add-subscription">
                <input type="hidden" name="status" value="active">

                <div>
                    <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.create_subscription') }}</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('messages.subscriptions') }}</p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.member') }}</span>
                        <select name="user_id" class="igym-input" required>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" @selected((int) (old('_modal') === 'add-subscription' ? old('user_id') : 0) === $member->id)>{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.plan') }}</span>
                        <input name="plan_name" value="{{ old('_modal') === 'add-subscription' ? old('plan_name') : 'Monthly Access' }}" placeholder="Monthly Access" class="igym-input" required>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.price') }}</span>
                        <input type="number" name="price" value="{{ old('_modal') === 'add-subscription' ? old('price') : 299 }}" min="0" step="0.01" placeholder="299" class="igym-input" required>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.payment') }}</span>
                        <select name="payment_status" class="igym-input">
                            @foreach(['paid' => __('messages.paid'), 'unpaid' => __('messages.unpaid')] as $status => $label)
                                <option value="{{ $status }}" @selected((old('_modal') === 'add-subscription' ? old('payment_status') : 'paid') === $status)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.started_at') }}</span>
                        <input type="date" name="starts_at" value="{{ old('_modal') === 'add-subscription' ? old('starts_at') : now()->toDateString() }}" placeholder="YYYY-MM-DD" class="igym-input" required>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.ends_at') }}</span>
                        <input type="date" name="ends_at" value="{{ old('_modal') === 'add-subscription' ? old('ends_at') : now()->addMonth()->toDateString() }}" placeholder="YYYY-MM-DD" class="igym-input" required>
                    </label>
                </div>

                <div class="flex justify-end gap-3">
                    <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', 'add-subscription')">{{ __('messages.cancel') }}</x-button>
                    <x-button>{{ __('messages.create_subscription') }}</x-button>
                </div>
            </form>
        </x-modal>

        @if($expiring->isNotEmpty())<x-alert type="warning">{{ $expiring->count() }} subscriptions expire within 7 days.</x-alert>@endif
        <x-table>
            <thead class="bg-slate-50 dark:bg-slate-800/60"><tr><th class="px-4 py-3 text-start">{{ __('messages.member') }}</th><th class="px-4 py-3 text-start">{{ __('messages.plan') }}</th><th class="px-4 py-3 text-start">{{ __('messages.price') }}</th><th class="px-4 py-3 text-start">{{ __('messages.ends') }}</th><th class="px-4 py-3 text-start">{{ __('messages.status') }}</th><th class="px-4 py-3 text-end">{{ __('messages.actions') }}</th></tr></thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @foreach($subscriptions as $subscription)
                    @php($editModal = 'edit-subscription-'.$subscription->id)
                    <tr>
                        <td class="px-4 py-3 font-bold">{{ $subscription->member->name }}</td>
                        <td class="px-4 py-3">{{ $subscription->plan_name }}</td>
                        <td class="px-4 py-3">{{ format_currency($subscription->price) }}</td>
                        <td class="px-4 py-3">{{ $subscription->ends_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3"><x-badge :status="$subscription->status" /></td>
                        <td class="px-4 py-3 text-end">
                            <button type="button" class="igym-action igym-action-edit" x-on:click="$dispatch('open-modal', '{{ $editModal }}')" title="{{ __('messages.edit') }}">
                                <x-icon name="edit" size="16" />
                                {{ __('messages.edit') }}
                            </button>
                        </td>
                    </tr>

                @endforeach
            </tbody>
        </x-table>

        @foreach($subscriptions as $subscription)
            @php($editModal = 'edit-subscription-'.$subscription->id)

            <x-modal name="{{ $editModal }}" :show="old('_modal') === $editModal" maxWidth="2xl">
                <form method="POST" action="{{ route('admin.subscriptions.update', $subscription) }}" class="space-y-5">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="_modal" value="{{ $editModal }}">

                    <div>
                        <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.edit') }} {{ __('messages.subscription') }}</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $subscription->member->name }}</p>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <label class="igym-field">
                            <span class="igym-label">{{ __('messages.member') }}</span>
                            <select name="user_id" class="igym-input" required>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" @selected((int) (old('_modal') === $editModal ? old('user_id') : $subscription->user_id) === $member->id)>{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="igym-field">
                            <span class="igym-label">{{ __('messages.plan') }}</span>
                            <input name="plan_name" value="{{ old('_modal') === $editModal ? old('plan_name') : $subscription->plan_name }}" placeholder="Monthly Access" class="igym-input" required>
                        </label>
                        <label class="igym-field">
                            <span class="igym-label">{{ __('messages.price') }}</span>
                            <input type="number" name="price" value="{{ old('_modal') === $editModal ? old('price') : $subscription->price }}" min="0" step="0.01" placeholder="299" class="igym-input" required>
                        </label>
                        <label class="igym-field">
                            <span class="igym-label">{{ __('messages.payment') }}</span>
                            <select name="payment_status" class="igym-input">
                                @foreach(['paid' => __('messages.paid'), 'unpaid' => __('messages.unpaid')] as $status => $label)
                                    <option value="{{ $status }}" @selected((old('_modal') === $editModal ? old('payment_status') : $subscription->payment_status) === $status)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="igym-field">
                            <span class="igym-label">{{ __('messages.started_at') }}</span>
                            <input type="date" name="starts_at" value="{{ old('_modal') === $editModal ? old('starts_at') : $subscription->starts_at->format('Y-m-d') }}" placeholder="YYYY-MM-DD" class="igym-input" required>
                        </label>
                        <label class="igym-field">
                            <span class="igym-label">{{ __('messages.ends_at') }}</span>
                            <input type="date" name="ends_at" value="{{ old('_modal') === $editModal ? old('ends_at') : $subscription->ends_at->format('Y-m-d') }}" placeholder="YYYY-MM-DD" class="igym-input" required>
                        </label>
                        <label class="igym-field md:col-span-2">
                            <span class="igym-label">{{ __('messages.status') }}</span>
                            <select name="status" class="igym-input">
                                @foreach(['active', 'expired', 'cancelled'] as $status)
                                    <option value="{{ $status }}" @selected((old('_modal') === $editModal ? old('status') : $subscription->status) === $status)>{{ Str::headline($status) }}</option>
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
        @endforeach

        {{ $subscriptions->links() }}
    </div>
</x-app-layout>
