<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.training_plans') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif
        <form method="POST" action="{{ route('coach.training-plans.store') }}" class="igym-card grid gap-4 p-5 lg:grid-cols-3">
            @csrf
            <select name="member_id" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950">@foreach($members as $member)<option value="{{ $member->id }}">{{ $member->name }}</option>@endforeach</select>
            <input name="title" placeholder="{{ __('messages.plan_title') }}" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required>
            <select name="goal" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950">@foreach(['weight_loss','muscle_gain','fitness','endurance'] as $goal)<option value="{{ $goal }}">{{ Str::headline($goal) }}</option>@endforeach</select>
            <textarea name="description" placeholder="{{ __('messages.coaching_notes') }}" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 lg:col-span-2"></textarea>
            <textarea name="exercises" placeholder="{{ __('messages.one_exercise_per_line') }}" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950"></textarea>
            <x-button>{{ __('messages.create_plan') }}</x-button>
        </form>
        <div class="grid gap-4 md:grid-cols-2">
            @foreach($plans as $plan)
                <div class="igym-card p-5"><div class="flex justify-between gap-3"><p class="font-black">{{ $plan->title }}</p><x-badge status="info">{{ Str::headline($plan->goal) }}</x-badge></div><p class="mt-1 text-sm text-slate-500">{{ $plan->member->name }}</p><p class="mt-3 text-sm">{{ $plan->description }}</p></div>
            @endforeach
        </div>
        {{ $plans->links() }}
    </div>
</x-app-layout>
