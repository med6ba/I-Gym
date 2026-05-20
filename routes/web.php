<?php

use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\CoachController as AdminCoachController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\Coach\ClassController as CoachClassController;
use App\Http\Controllers\Coach\MemberController as CoachMemberController;
use App\Http\Controllers\Coach\ProgressController as CoachProgressController;
use App\Http\Controllers\Coach\TrainingPlanController as CoachTrainingPlanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Member\CourseController as MemberCourseController;
use App\Http\Controllers\Member\IgymaController as MemberIgymaController;
use App\Http\Controllers\Member\NfcController as MemberNfcController;
use App\Http\Controllers\Member\NotificationController as MemberNotificationController;
use App\Http\Controllers\Member\ProgressController as MemberProgressController;
use App\Http\Controllers\Member\ReservationController as MemberReservationController;
use App\Http\Controllers\Member\SubscriptionController as MemberSubscriptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Reception\ScannerController as ReceptionScannerController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Super\GymController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::post('/settings/language', [SettingsController::class, 'updateLanguage'])->name('settings.language.update');
Route::patch('/settings/theme', [SettingsController::class, 'updateTheme'])->name('settings.theme.update');

Route::get('/dashboard', [DashboardController::class, 'redirect'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/save', [SettingsController::class, 'save'])->name('settings.save');
    Route::get('/settings/language', [SettingsController::class, 'language'])->name('settings.language');
    Route::get('/settings/theme', [SettingsController::class, 'theme'])->name('settings.theme');
});

Route::middleware(['auth', 'role:super_admin'])->prefix('super')->name('super.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'super'])->name('dashboard');
    Route::get('/admins/create', [GymController::class, 'create'])->name('admins.create');
    Route::get('/gyms/export/{format}', [GymController::class, 'export'])
        ->whereIn('format', ['excel', 'pdf'])
        ->name('gyms.export');
    Route::resource('gyms', GymController::class)->except(['show']);
});

Route::middleware(['auth', 'role:gym_admin', 'gym.access'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    Route::get('/members', [AdminMemberController::class, 'index'])->name('members.index');
    Route::post('/members', [AdminMemberController::class, 'store'])->name('members.store');
    Route::patch('/members/{member}', [AdminMemberController::class, 'update'])->name('members.update');
    Route::delete('/members/{member}', [AdminMemberController::class, 'destroy'])->name('members.destroy');

    Route::get('/coaches', [AdminCoachController::class, 'index'])->name('coaches.index');
    Route::post('/coaches', [AdminCoachController::class, 'store'])->name('coaches.store');
    Route::patch('/coaches/{coach}', [AdminCoachController::class, 'update'])->name('coaches.update');
    Route::delete('/coaches/{coach}', [AdminCoachController::class, 'destroy'])->name('coaches.destroy');

    Route::get('/courses', [AdminCourseController::class, 'index'])->name('courses.index');
    Route::post('/courses', [AdminCourseController::class, 'store'])->name('courses.store');
    Route::patch('/courses/{course}', [AdminCourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [AdminCourseController::class, 'destroy'])->name('courses.destroy');

    Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
    Route::patch('/reservations/{reservation}', [AdminReservationController::class, 'update'])->name('reservations.update');

    Route::get('/subscriptions', [AdminSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions', [AdminSubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::patch('/subscriptions/{subscription}', [AdminSubscriptionController::class, 'update'])->name('subscriptions.update');

    Route::get('/attendance', [AdminAttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AdminAttendanceController::class, 'store'])->name('attendance.store');

    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications', [AdminNotificationController::class, 'store'])->name('notifications.store');
    Route::get('/logs', [AdminActivityLogController::class, 'index'])->name('logs.index');
});

Route::middleware(['auth', 'role:coach', 'gym.access'])->prefix('coach')->name('coach.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'coach'])->name('dashboard');
    Route::get('/classes', [CoachClassController::class, 'index'])->name('classes.index');
    Route::get('/classes/{course}/attendance', [CoachClassController::class, 'attendance'])->name('classes.attendance');
    Route::post('/classes/{course}/attendance', [CoachClassController::class, 'markAttendance'])->name('classes.attendance.store');
    Route::get('/members', [CoachMemberController::class, 'index'])->name('members.index');
    Route::get('/training-plans', [CoachTrainingPlanController::class, 'index'])->name('training-plans.index');
    Route::post('/training-plans', [CoachTrainingPlanController::class, 'store'])->name('training-plans.store');
    Route::get('/progress', [CoachProgressController::class, 'index'])->name('progress.index');
    Route::post('/progress', [CoachProgressController::class, 'store'])->name('progress.store');
});

Route::middleware(['auth', 'role:member', 'gym.access'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'member'])->name('dashboard');

    Route::get('/courses', [MemberCourseController::class, 'index'])->name('courses.index');
    Route::post('/courses/{course}/reserve', [MemberCourseController::class, 'reserve'])->name('courses.reserve');
    Route::get('/reservations', [MemberReservationController::class, 'index'])->name('reservations.index');
    Route::patch('/reservations/{reservation}/cancel', [MemberReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::get('/subscription', MemberSubscriptionController::class)->name('subscription');
    Route::get('/progress', MemberProgressController::class)->name('progress');
    Route::get('/notifications', [MemberNotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [MemberNotificationController::class, 'markRead'])->name('notifications.read');
    Route::get('/nfc', [MemberNfcController::class, 'index'])->name('nfc');

    Route::get('/igyma', [MemberIgymaController::class, 'index'])->name('igyma');
    Route::post('/igyma/chat', [MemberIgymaController::class, 'chat'])
        ->middleware('throttle:20,1')
        ->name('igyma.chat');
});

Route::middleware(['auth', 'role:reception', 'gym.access'])->prefix('reception')->name('reception.')->group(function () {
    Route::get('/scanner', [ReceptionScannerController::class, 'index'])->name('scanner');
});

require __DIR__.'/auth.php';
