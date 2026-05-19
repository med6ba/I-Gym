<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.courses') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif
        @unless($hasActiveSubscription)<x-alert type="warning">{{ __('messages.subscription_required') }}</x-alert>@endunless
        <form method="GET" class="flex flex-wrap gap-3">
            <select name="category" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950"><option value="">All categories</option>@foreach($categories as $category)<option value="{{ $category }}" @selected(request('category')===$category)>{{ $category }}</option>@endforeach</select>
            <x-button>Filter</x-button>
        </form>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach($courses as $course)
                <div class="igym-card igym-hover p-5">
                    <div class="flex items-start justify-between gap-3"><div><p class="font-black">{{ $course->title }}</p><p class="text-sm text-slate-500">{{ $course->coach->name }} · {{ $course->starts_at->format('M d, H:i') }}</p></div><x-badge :status="$course->category" /></div>
                    <div class="mt-4"><div class="mb-1 flex justify-between text-xs font-bold"><span>{{ $course->active_reservations_count }}/{{ $course->max_capacity }}</span><span>{{ $course->occupancy_rate }}%</span></div><x-progress-bar :value="$course->occupancy_rate" /></div>
                    @if($course->smart_alert)<x-alert type="{{ $course->is_full ? 'danger' : 'warning' }}" class="mt-4">{{ $course->smart_alert }}</x-alert>@endif
                    <form method="POST" action="{{ route('member.courses.reserve', $course) }}" class="mt-4">@csrf
                        <button @disabled(!$hasActiveSubscription || $course->is_full || in_array($course->id, $reservedCourseIds)) class="igym-focus w-full rounded-lg border border-amber-500 bg-amber-500 px-4 py-2 text-sm font-black text-slate-950 transition enabled:hover:bg-amber-400 disabled:cursor-not-allowed disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400 dark:disabled:border-slate-700 dark:disabled:bg-slate-800">
                            {{ in_array($course->id, $reservedCourseIds) ? 'Reserved' : __('messages.book_class') }}
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
        {{ $courses->links() }}
    </div>
</x-app-layout>
