<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.training_plans') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif

        <div class="flex justify-end">
            <x-button type="button" class="gap-2" x-on:click="$dispatch('open-modal', 'create-training-plan')">
                <x-icon name="plus" size="17" />
                {{ __('messages.create_plan') }}
            </x-button>
        </div>

        <x-modal name="create-training-plan" :show="old('_modal') === 'create-training-plan'" maxWidth="2xl">
            <form method="POST" action="{{ route('coach.training-plans.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="_modal" value="create-training-plan">

                <div>
                    <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.create_plan') }}</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('messages.training_plans') }}</p>
                </div>

                <div class="grid gap-4 lg:grid-cols-2">
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.member') }}</span>
                        <select name="member_id" class="igym-input" required>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" @selected((int) (old('_modal') === 'create-training-plan' ? old('member_id') : 0) === $member->id)>{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.plan_title') }}</span>
                        <input name="title" value="{{ old('_modal') === 'create-training-plan' ? old('title') : '' }}" placeholder="8-week strength plan" class="igym-input" required>
                    </label>
                    <label class="igym-field lg:col-span-2">
                        <span class="igym-label">{{ __('messages.goal') }}</span>
                        <select name="goal" class="igym-input">
                            @foreach(['weight_loss','muscle_gain','fitness','endurance'] as $goal)
                                <option value="{{ $goal }}" @selected((old('_modal') === 'create-training-plan' ? old('goal') : 'fitness') === $goal)>{{ Str::headline($goal) }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.coaching_notes') }}</span>
                        <textarea name="description" rows="4" placeholder="Weekly focus, intensity, and recovery notes" class="igym-input">{{ old('_modal') === 'create-training-plan' ? old('description') : '' }}</textarea>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.one_exercise_per_line') }}</span>
                        <textarea name="exercises" rows="4" placeholder="Squats&#10;Push-ups&#10;Plank" class="igym-input">{{ old('_modal') === 'create-training-plan' ? old('exercises') : '' }}</textarea>
                    </label>
                </div>

                <div class="flex justify-end gap-3">
                    <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', 'create-training-plan')">{{ __('messages.cancel') }}</x-button>
                    <x-button>{{ __('messages.create_plan') }}</x-button>
                </div>
            </form>
        </x-modal>

        <div class="grid gap-4 md:grid-cols-2">
            @foreach($plans as $plan)
                <div class="igym-card p-5"><div class="flex flex-wrap justify-between gap-3"><p class="font-black">{{ $plan->title }}</p><x-badge status="info">{{ Str::headline($plan->goal) }}</x-badge></div><p class="mt-1 text-sm text-slate-500">{{ $plan->member->name }}</p><p class="mt-3 text-sm">{{ $plan->description }}</p></div>
            @endforeach
        </div>
        {{ $plans->links() }}
    </div>
</x-app-layout>
