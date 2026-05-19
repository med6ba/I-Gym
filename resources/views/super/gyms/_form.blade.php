@csrf
<div class="grid gap-4 md:grid-cols-2">
    <label class="block"><span class="text-sm font-bold">Name</span><input name="name" value="{{ old('name', $gym->name) }}" class="mt-1 w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required></label>
    <label class="block"><span class="text-sm font-bold">Slug</span><input name="slug" value="{{ old('slug', $gym->slug) }}" class="mt-1 w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950"></label>
    <label class="block"><span class="text-sm font-bold">Email</span><input type="email" name="email" value="{{ old('email', $gym->email) }}" class="mt-1 w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required></label>
    <label class="block"><span class="text-sm font-bold">Phone</span><input name="phone" value="{{ old('phone', $gym->phone) }}" class="mt-1 w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950"></label>
    <label class="block"><span class="text-sm font-bold">City</span><input name="city" value="{{ old('city', $gym->city) }}" class="mt-1 w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950"></label>
    <label class="block"><span class="text-sm font-bold">Address</span><input name="address" value="{{ old('address', $gym->address) }}" class="mt-1 w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950"></label>
    <label class="block"><span class="text-sm font-bold">Status</span><select name="status" class="mt-1 w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950">@foreach(['active','trial','expired','suspended'] as $status)<option value="{{ $status }}" @selected(old('status', $gym->status ?? 'trial') === $status)>{{ Str::headline($status) }}</option>@endforeach</select></label>
    <label class="block"><span class="text-sm font-bold">Plan</span><select name="subscription_plan" class="mt-1 w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950">@foreach(['basic','pro','business'] as $plan)<option value="{{ $plan }}" @selected(old('subscription_plan', $gym->subscription_plan ?? 'basic') === $plan)>{{ Str::headline($plan) }}</option>@endforeach</select></label>
    <label class="block"><span class="text-sm font-bold">Started at</span><input type="date" name="subscription_started_at" value="{{ old('subscription_started_at', optional($gym->subscription_started_at)->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950"></label>
    <label class="block"><span class="text-sm font-bold">Ends at</span><input type="date" name="subscription_ends_at" value="{{ old('subscription_ends_at', optional($gym->subscription_ends_at)->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950"></label>
</div>
@if($errors->any())
    <x-alert type="danger" class="mt-4">{{ $errors->first() }}</x-alert>
@endif
<div class="mt-5 flex justify-end gap-3">
    <a href="{{ route('super.gyms.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-bold dark:border-slate-700">Cancel</a>
    <x-button>Save Gym</x-button>
</div>
