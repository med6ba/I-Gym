<?php

namespace App\Actions;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class RecordAttendance
{
    /**
     * @throws AuthorizationException
     */
    public function handle(User $actor, User $member, ?Course $course, string $method = 'nfc'): Attendance
    {
        if ($member->role !== 'member' || $member->gym_id !== $actor->gym_id) {
            throw new AuthorizationException('Member does not belong to this gym.');
        }

        if ($course && $course->gym_id !== $actor->gym_id) {
            throw new AuthorizationException('Course does not belong to this gym.');
        }

        if ($course && $actor->isCoach() && $course->coach_id !== $actor->id) {
            throw new AuthorizationException('Coach can only mark attendance for assigned classes.');
        }

        $attendance = Attendance::create([
            'gym_id' => $actor->gym_id,
            'user_id' => $member->id,
            'course_id' => $course?->id,
            'checked_in_by' => $actor->id,
            'check_in_time' => now(),
            'method' => $method,
        ]);

        if ($course) {
            Reservation::where('gym_id', $actor->gym_id)
                ->where('course_id', $course->id)
                ->where('user_id', $member->id)
                ->whereIn('status', ['reserved', 'no_show'])
                ->update(['status' => 'attended']);
        }

        return $attendance;
    }
}
