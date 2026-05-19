<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.classes') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        <form method="GET" data-ajax-filter data-ajax-target="#coach-class-results" class="flex flex-wrap gap-3">
            <select name="status" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950"><option value="">{{ __('messages.status') }}</option>@foreach(['scheduled','completed','cancelled'] as $status)<option value="{{ $status }}" @selected(request('status')===$status)>{{ Str::headline($status) }}</option>@endforeach</select>
            <x-button type="submit">{{ __('messages.filter') }}</x-button>
        </form>
        <div id="coach-class-results" data-ajax-target="#coach-class-results" class="transition">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach($courses as $course)
                    <a href="{{ route('coach.classes.attendance', $course) }}" class="igym-card igym-hover p-5">
                        <div class="flex items-start justify-between gap-3"><div><p class="font-black">{{ $course->title }}</p><p class="text-sm text-slate-500">{{ $course->starts_at->format('M d, H:i') }} · {{ $course->room }}</p></div><x-badge :status="$course->status" /></div>
                        <div class="mt-4"><div class="mb-1 flex justify-between text-xs font-bold"><span>{{ $course->active_reservations_count }}/{{ $course->max_capacity }}</span><span>{{ $course->occupancy_rate }}%</span></div><x-progress-bar :value="$course->occupancy_rate" /></div>
                        @if($course->smart_alert)<x-alert type="warning" class="mt-4">{{ $course->smart_alert }}</x-alert>@endif
                    </a>
                @endforeach
            </div>
            <div class="mt-5">{{ $courses->links() }}</div>
        </div>
    </div>
</x-app-layout>
