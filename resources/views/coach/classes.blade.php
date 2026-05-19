<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.classes') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach($courses as $course)
                <a href="{{ route('coach.classes.attendance', $course) }}" class="igym-card igym-hover p-5">
                    <div class="flex items-start justify-between gap-3"><div><p class="font-black">{{ $course->title }}</p><p class="text-sm text-slate-500">{{ $course->starts_at->format('M d, H:i') }} · {{ $course->room }}</p></div><x-badge :status="$course->status" /></div>
                    <div class="mt-4"><div class="mb-1 flex justify-between text-xs font-bold"><span>{{ $course->active_reservations_count }}/{{ $course->max_capacity }}</span><span>{{ $course->occupancy_rate }}%</span></div><x-progress-bar :value="$course->occupancy_rate" /></div>
                    @if($course->smart_alert)<x-alert type="warning" class="mt-4">{{ $course->smart_alert }}</x-alert>@endif
                </a>
            @endforeach
        </div>
        {{ $courses->links() }}
    </div>
</x-app-layout>
