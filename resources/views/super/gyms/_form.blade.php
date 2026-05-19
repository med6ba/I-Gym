@csrf
@php($admin = $gym->primaryAdmin)
@php($inModal = $inModal ?? false)
@php($modalName = $modalName ?? null)
@php($useOld = ! $modalName || old('_modal') === $modalName)
@php($fieldValue = fn (string $field, mixed $default = null) => $useOld ? old($field, $default) : $default)
@if($modalName)
    <input type="hidden" name="_modal" value="{{ $modalName }}">
@endif
<div class="grid gap-4 md:grid-cols-2">
    <label class="igym-field"><span class="igym-label">{{ __('messages.name') }}</span><input name="name" value="{{ $fieldValue('name', $gym->name) }}" placeholder="I-Gym Casablanca" class="igym-input" required></label>
    <label class="igym-field"><span class="igym-label">{{ __('messages.slug') }}</span><input name="slug" value="{{ $fieldValue('slug', $gym->slug) }}" placeholder="i-gym-casablanca" class="igym-input"></label>
    <label class="igym-field"><span class="igym-label">{{ __('messages.phone') }}</span><input name="phone" value="{{ $fieldValue('phone', $gym->phone) }}" placeholder="+212 600 000 000" class="igym-input"></label>
    <label class="igym-field"><span class="igym-label">{{ __('messages.city') }}</span><input name="city" value="{{ $fieldValue('city', $gym->city) }}" placeholder="Casablanca" class="igym-input"></label>
    <label class="igym-field"><span class="igym-label">{{ __('messages.address') }}</span><input name="address" value="{{ $fieldValue('address', $gym->address) }}" placeholder="123 Fitness Avenue" class="igym-input"></label>
    <label class="igym-field"><span class="igym-label">{{ __('messages.status') }}</span><select name="status" class="igym-input">@foreach(['active','trial','expired','suspended'] as $status)<option value="{{ $status }}" @selected($fieldValue('status', $gym->status ?? 'trial') === $status)>{{ Str::headline($status) }}</option>@endforeach</select></label>
    <label class="igym-field"><span class="igym-label">{{ __('messages.plan') }}</span><select name="subscription_plan" class="igym-input">@foreach(['basic','pro','business'] as $plan)<option value="{{ $plan }}" @selected($fieldValue('subscription_plan', $gym->subscription_plan ?? 'basic') === $plan)>{{ Str::headline($plan) }}</option>@endforeach</select></label>
    <label class="igym-field"><span class="igym-label">{{ __('messages.started_at') }}</span><input type="date" name="subscription_started_at" value="{{ $fieldValue('subscription_started_at', $gym->exists ? optional($gym->subscription_started_at)->format('Y-m-d') : '2026-07-01') }}" placeholder="YYYY-MM-DD" class="igym-input"></label>
    <label class="igym-field"><span class="igym-label">{{ __('messages.ends_at') }}</span><input type="date" name="subscription_ends_at" value="{{ $fieldValue('subscription_ends_at', $gym->exists ? optional($gym->subscription_ends_at)->format('Y-m-d') : '2027-07-01') }}" placeholder="YYYY-MM-DD" class="igym-input"></label>
</div>
<div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-900/60 dark:bg-amber-950/20">
    <div class="mb-4">
        <p class="text-sm font-black uppercase text-amber-700 dark:text-amber-300">{{ __('messages.gym_admin') }}</p>
        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('messages.admin_account_help') }}</p>
    </div>
    <div class="grid gap-4 md:grid-cols-2">
        <label class="igym-field"><span class="igym-label">{{ __('messages.name') }}</span><input name="admin_name" value="{{ $fieldValue('admin_name', $admin?->name) }}" placeholder="Gym Owner" class="igym-input" required></label>
        <label class="igym-field"><span class="igym-label">{{ __('messages.email') }}</span><input type="email" name="admin_email" value="{{ $fieldValue('admin_email', $admin?->email) }}" placeholder="admin@gym.com" class="igym-input" required></label>
        <label class="igym-field">
            <span class="igym-label">{{ $gym->exists ? __('messages.new_password') : __('messages.password') }}</span>
            <input type="password" name="admin_password" placeholder="{{ $gym->exists ? __('messages.leave_blank_keep_password') : 'Minimum 8 characters' }}" class="igym-input" @required(! $gym->exists || ! $admin)>
            @if($gym->exists && $admin)
                <span class="mt-1 block text-xs text-slate-500">{{ __('messages.leave_blank_keep_password') }}</span>
            @endif
        </label>
        <label class="igym-field">
            <span class="igym-label">{{ __('messages.confirm_password') }}</span>
            <input type="password" name="admin_password_confirmation" placeholder="{{ __('messages.confirm_password') }}" class="igym-input" @required(! $gym->exists || ! $admin)>
        </label>
    </div>
</div>
@if($errors->any())
    <x-alert type="danger" class="mt-4">{{ $errors->first() }}</x-alert>
@endif
<div class="mt-5 flex justify-end gap-3">
    @if($inModal)
        <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', '{{ $modalName }}')">{{ __('messages.cancel') }}</x-button>
    @else
        <a href="{{ route('super.gyms.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-bold dark:border-slate-700">{{ __('messages.cancel') }}</a>
    @endif
    <x-button>{{ __('messages.save_gym') }}</x-button>
</div>
