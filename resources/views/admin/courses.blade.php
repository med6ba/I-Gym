<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.courses') }}</h2></x-slot>
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif
        <form method="POST" action="{{ route('admin.courses.store') }}" class="igym-card grid gap-4 p-5 lg:grid-cols-4">
            @csrf
            <input name="title" placeholder="{{ __('messages.class_title') }}" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required>
            <select name="category" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950">@foreach($categories as $category)<option>{{ $category }}</option>@endforeach</select>
            <select name="coach_id" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950">@foreach($coaches as $coach)<option value="{{ $coach->id }}">{{ $coach->name }}</option>@endforeach</select>
            <input name="room" placeholder="{{ __('messages.room') }}" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950">
            <input type="datetime-local" name="starts_at" value="{{ now()->addDay()->format('Y-m-d\TH:i') }}" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required>
            <input type="datetime-local" name="ends_at" value="{{ now()->addDay()->addHour()->format('Y-m-d\TH:i') }}" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required>
            <input type="number" name="max_capacity" value="12" min="1" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950" required>
            <input type="hidden" name="status" value="scheduled"><textarea name="description" placeholder="{{ __('messages.description') }}" class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-950 lg:col-span-3"></textarea><x-button>{{ __('messages.create_course') }}</x-button>
        </form>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach($courses as $course)
                <div class="igym-card igym-hover p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div><p class="font-black">{{ $course->title }}</p><p class="text-sm text-slate-500">{{ $course->coach->name }} · {{ $course->starts_at->format('M d, H:i') }}</p></div>
                        <x-badge :status="$course->status" />
                    </div>
                    <div class="mt-4"><div class="mb-1 flex justify-between text-xs font-bold"><span>{{ $course->active_reservations_count }}/{{ $course->max_capacity }}</span><span>{{ $course->occupancy_rate }}%</span></div><x-progress-bar :value="$course->occupancy_rate" /></div>
                    @if($course->smart_alert)<x-alert type="warning" class="mt-4">{{ $course->smart_alert }}</x-alert>@endif
                    <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" class="mt-4">@csrf @method('DELETE')<button class="text-sm font-bold text-rose-600">{{ __('messages.cancel_course') }}</button></form>
                </div>
            @endforeach
        </div>
        {{ $courses->links() }}
    </div>
</x-app-layout>
