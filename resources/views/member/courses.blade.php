<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.courses') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif
        @unless($hasActiveSubscription)<x-alert type="warning">{{ __('messages.subscription_required') }}</x-alert>@endunless
        <div class="transition">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach($courses as $course)
                    @php($reserveModal = 'reserve-course-'.$course->id)
                    @php($isReserved = in_array($course->id, $reservedCourseIds))
                    <div class="igym-card igym-hover p-5">
                        <div class="flex flex-wrap items-start justify-between gap-3"><div><p class="font-black">{{ $course->title }}</p><p class="text-sm text-slate-500">{{ $course->coach->name }} · {{ $course->starts_at->format('M d, H:i') }}</p></div><x-badge :status="$course->category" /></div>
                        <div class="mt-4"><div class="mb-1 flex justify-between text-xs font-bold"><span>{{ $course->active_reservations_count }}/{{ $course->max_capacity }}</span><span>{{ $course->occupancy_rate }}%</span></div><x-progress-bar :value="$course->occupancy_rate" /></div>
                        @if($course->smart_alert)<x-alert type="{{ $course->is_full ? 'danger' : 'warning' }}" class="mt-4">{{ $course->smart_alert }}</x-alert>@endif
                        <button type="button" x-on:click="$dispatch('open-modal', '{{ $reserveModal }}')" @disabled(!$hasActiveSubscription || $course->is_full || $isReserved) class="igym-focus mt-4 w-full rounded-lg border border-amber-500 bg-amber-500 px-4 py-2 text-sm font-black text-slate-950 transition enabled:hover:bg-amber-400 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400 dark:disabled:border-slate-700 dark:disabled:bg-slate-800">
                            {{ $isReserved ? __('messages.reserved_label') : __('messages.book_class') }}
                        </button>
                    </div>

                    <x-modal name="{{ $reserveModal }}" maxWidth="md">
                        <form method="POST" action="{{ route('member.courses.reserve', $course) }}" class="space-y-5">
                            @csrf

                            <div>
                                <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.book_class') }}</h3>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $course->title }} · {{ $course->starts_at->format('M d, H:i') }}</p>
                            </div>

                            <div class="flex justify-end gap-3">
                                <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', '{{ $reserveModal }}')">{{ __('messages.cancel') }}</x-button>
                                <x-button>{{ __('messages.book_class') }}</x-button>
                            </div>
                        </form>
                    </x-modal>
                @endforeach
            </div>
            <div class="mt-5">{{ $courses->links() }}</div>
        </div>
    </div>
</x-app-layout>
