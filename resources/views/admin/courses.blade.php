<x-app-layout>
    <x-slot name="header"><h2 class="text-2xl font-black text-slate-950 dark:text-white">{{ __('messages.courses') }}</h2></x-slot>
    @php($defaultCourseStart = '2026-07-01T09:00')
    @php($defaultCourseEnd = '2026-07-01T10:00')
    <div class="mx-auto max-w-7xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('status')) <x-alert type="success">{{ session('status') }}</x-alert> @endif
        @if($errors->any()) <x-alert type="danger">{{ $errors->first() }}</x-alert> @endif

        <div class="flex justify-end">
            <x-button type="button" class="gap-2" x-on:click="$dispatch('open-modal', 'add-course')">
                <x-icon name="plus" size="17" />
                {{ __('messages.create_course') }}
            </x-button>
        </div>

        <x-modal name="add-course" :show="old('_modal') === 'add-course'" maxWidth="2xl">
            <form method="POST" action="{{ route('admin.courses.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="_modal" value="add-course">
                <input type="hidden" name="status" value="scheduled">

                <div>
                    <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.create_course') }}</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('messages.courses') }}</p>
                </div>

                <div class="grid gap-4 lg:grid-cols-2">
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.class_title') }}</span>
                        <input name="title" value="{{ old('_modal') === 'add-course' ? old('title') : '' }}" placeholder="Morning Strength" class="igym-input" required>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.category') }}</span>
                        <select name="category" class="igym-input">
                            @foreach($categories as $category)
                                <option value="{{ $category }}" @selected((old('_modal') === 'add-course' ? old('category') : null) === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.coach') }}</span>
                        <select name="coach_id" class="igym-input" required>
                            @foreach($coaches as $coach)
                                <option value="{{ $coach->id }}" @selected((int) (old('_modal') === 'add-course' ? old('coach_id') : 0) === $coach->id)>{{ $coach->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.room') }}</span>
                        <input name="room" value="{{ old('_modal') === 'add-course' ? old('room') : '' }}" placeholder="Studio A" class="igym-input">
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.started_at') }}</span>
                        <input type="datetime-local" name="starts_at" value="{{ old('_modal') === 'add-course' ? old('starts_at') : $defaultCourseStart }}" placeholder="YYYY-MM-DD HH:MM" class="igym-input" required>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.ends_at') }}</span>
                        <input type="datetime-local" name="ends_at" value="{{ old('_modal') === 'add-course' ? old('ends_at') : $defaultCourseEnd }}" placeholder="YYYY-MM-DD HH:MM" class="igym-input" required>
                    </label>
                    <label class="igym-field">
                        <span class="igym-label">{{ __('messages.capacity') }}</span>
                        <input type="number" name="max_capacity" value="{{ old('_modal') === 'add-course' ? old('max_capacity') : 12 }}" min="1" placeholder="12" class="igym-input" required>
                    </label>
                    <label class="igym-field lg:col-span-2">
                        <span class="igym-label">{{ __('messages.description') }}</span>
                        <textarea name="description" rows="3" placeholder="Short class notes for members" class="igym-input">{{ old('_modal') === 'add-course' ? old('description') : '' }}</textarea>
                    </label>
                </div>

                <div class="flex justify-end gap-3">
                    <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', 'add-course')">{{ __('messages.cancel') }}</x-button>
                    <x-button>{{ __('messages.create_course') }}</x-button>
                </div>
            </form>
        </x-modal>

        <div class="transition">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach($courses as $course)
                    @php($editModal = 'edit-course-'.$course->id)
                    @php($deleteModal = 'delete-course-'.$course->id)
                    <div class="igym-card igym-hover p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div><p class="font-black">{{ $course->title }}</p><p class="text-sm text-slate-500">{{ $course->coach->name }} · {{ $course->starts_at->format('M d, H:i') }}</p></div>
                            <x-badge :status="$course->status" />
                        </div>
                        <div class="mt-4"><div class="mb-1 flex justify-between text-xs font-bold"><span>{{ $course->active_reservations_count }}/{{ $course->max_capacity }}</span><span>{{ $course->occupancy_rate }}%</span></div><x-progress-bar :value="$course->occupancy_rate" /></div>
                        @if($course->smart_alert)<x-alert type="warning" class="mt-4">{{ $course->smart_alert }}</x-alert>@endif
                        <div class="mt-4 flex flex-wrap gap-2">
                            <button type="button" class="igym-action igym-action-edit" x-on:click="$dispatch('open-modal', '{{ $editModal }}')" title="{{ __('messages.edit') }}">
                                <x-icon name="edit" size="16" />
                                {{ __('messages.edit') }}
                            </button>
                            <button type="button" class="igym-action igym-action-danger" x-on:click="$dispatch('open-modal', '{{ $deleteModal }}')" title="{{ __('messages.cancel_course') }}">
                                <x-icon name="trash" size="16" />
                                {{ __('messages.cancel_course') }}
                            </button>
                        </div>
                    </div>

                    <x-modal name="{{ $editModal }}" :show="old('_modal') === $editModal" maxWidth="2xl">
                        <form method="POST" action="{{ route('admin.courses.update', $course) }}" class="space-y-5">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="_modal" value="{{ $editModal }}">

                            <div>
                                <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.edit') }} {{ __('messages.courses') }}</h3>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $course->title }}</p>
                            </div>

                            <div class="grid gap-4 lg:grid-cols-2">
                                <label class="igym-field">
                                    <span class="igym-label">{{ __('messages.class_title') }}</span>
                                    <input name="title" value="{{ old('_modal') === $editModal ? old('title') : $course->title }}" placeholder="Morning Strength" class="igym-input" required>
                                </label>
                                <label class="igym-field">
                                    <span class="igym-label">{{ __('messages.category') }}</span>
                                    <select name="category" class="igym-input">
                                        @foreach($categories as $category)
                                            <option value="{{ $category }}" @selected((old('_modal') === $editModal ? old('category') : $course->category) === $category)>{{ $category }}</option>
                                        @endforeach
                                    </select>
                                </label>
                                <label class="igym-field">
                                    <span class="igym-label">{{ __('messages.coach') }}</span>
                                    <select name="coach_id" class="igym-input" required>
                                        @foreach($coaches as $coach)
                                            <option value="{{ $coach->id }}" @selected((int) (old('_modal') === $editModal ? old('coach_id') : $course->coach_id) === $coach->id)>{{ $coach->name }}</option>
                                        @endforeach
                                    </select>
                                </label>
                                <label class="igym-field">
                                    <span class="igym-label">{{ __('messages.room') }}</span>
                                    <input name="room" value="{{ old('_modal') === $editModal ? old('room') : $course->room }}" placeholder="Studio A" class="igym-input">
                                </label>
                                <label class="igym-field">
                                    <span class="igym-label">{{ __('messages.started_at') }}</span>
                                    <input type="datetime-local" name="starts_at" value="{{ old('_modal') === $editModal ? old('starts_at') : $course->starts_at->format('Y-m-d\TH:i') }}" placeholder="YYYY-MM-DD HH:MM" class="igym-input" required>
                                </label>
                                <label class="igym-field">
                                    <span class="igym-label">{{ __('messages.ends_at') }}</span>
                                    <input type="datetime-local" name="ends_at" value="{{ old('_modal') === $editModal ? old('ends_at') : $course->ends_at->format('Y-m-d\TH:i') }}" placeholder="YYYY-MM-DD HH:MM" class="igym-input" required>
                                </label>
                                <label class="igym-field">
                                    <span class="igym-label">{{ __('messages.capacity') }}</span>
                                    <input type="number" name="max_capacity" value="{{ old('_modal') === $editModal ? old('max_capacity') : $course->max_capacity }}" min="1" placeholder="12" class="igym-input" required>
                                </label>
                                <label class="igym-field">
                                    <span class="igym-label">{{ __('messages.status') }}</span>
                                    <select name="status" class="igym-input">
                                        @foreach(['scheduled', 'cancelled', 'completed'] as $status)
                                            <option value="{{ $status }}" @selected((old('_modal') === $editModal ? old('status') : $course->status) === $status)>{{ Str::headline($status) }}</option>
                                        @endforeach
                                    </select>
                                </label>
                                <label class="igym-field lg:col-span-2">
                                    <span class="igym-label">{{ __('messages.description') }}</span>
                                    <textarea name="description" rows="3" placeholder="Short class notes for members" class="igym-input">{{ old('_modal') === $editModal ? old('description') : $course->description }}</textarea>
                                </label>
                            </div>

                            <div class="flex justify-end gap-3">
                                <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', '{{ $editModal }}')">{{ __('messages.cancel') }}</x-button>
                                <x-button>{{ __('messages.save') }}</x-button>
                            </div>
                        </form>
                    </x-modal>

                    <x-modal name="{{ $deleteModal }}" maxWidth="md">
                        <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" class="space-y-5">
                            @csrf
                            @method('DELETE')

                            <div>
                                <h3 class="text-lg font-black text-slate-950 dark:text-white">{{ __('messages.cancel_course') }}</h3>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $course->title }} · {{ $course->starts_at->format('M d, H:i') }}</p>
                            </div>

                            <div class="flex justify-end gap-3">
                                <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', '{{ $deleteModal }}')">{{ __('messages.cancel') }}</x-button>
                                <x-button variant="danger" class="gap-2">
                                    <x-icon name="trash" size="16" />
                                    {{ __('messages.cancel_course') }}
                                </x-button>
                            </div>
                        </form>
                    </x-modal>
                @endforeach
            </div>
            <div class="mt-5">{{ $courses->links() }}</div>
        </div>
    </div>
</x-app-layout>
