<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('plan_name');
            $table->decimal('price', 10, 2)->default(0);
            $table->date('starts_at');
            $table->date('ends_at');
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active')->index();
            $table->enum('payment_status', ['paid', 'unpaid'])->default('paid');
            $table->timestamps();

            $table->index(['gym_id', 'status', 'ends_at']);
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $table->foreignId('coach_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->enum('category', ['Crossfit', 'Yoga', 'Cardio', 'Strength', 'Boxing', 'Pilates'])->index();
            $table->text('description')->nullable();
            $table->dateTime('starts_at')->index();
            $table->dateTime('ends_at');
            $table->unsignedInteger('max_capacity')->default(12);
            $table->string('room')->nullable();
            $table->enum('status', ['scheduled', 'cancelled', 'completed'])->default('scheduled')->index();
            $table->timestamps();

            $table->index(['gym_id', 'coach_id', 'starts_at']);
        });

        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['reserved', 'cancelled', 'attended', 'no_show'])->default('reserved')->index();
            $table->timestamps();

            $table->unique(['user_id', 'course_id']);
            $table->index(['gym_id', 'course_id', 'status']);
        });

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('checked_in_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('check_in_time')->index();
            $table->enum('method', ['qr', 'manual'])->default('manual');
            $table->timestamps();

            $table->index(['gym_id', 'user_id', 'check_in_time']);
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['info', 'warning', 'success', 'danger'])->default('info');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['gym_id', 'user_id', 'is_read']);
        });

        Schema::create('training_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $table->foreignId('coach_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->enum('goal', ['weight_loss', 'muscle_gain', 'fitness', 'endurance'])->default('fitness');
            $table->text('description')->nullable();
            $table->json('exercises')->nullable();
            $table->timestamps();

            $table->index(['gym_id', 'coach_id', 'member_id']);
        });

        Schema::create('member_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('body_fat', 5, 2)->nullable();
            $table->decimal('muscle_mass', 5, 2)->nullable();
            $table->string('goal')->nullable();
            $table->text('notes')->nullable();
            $table->date('recorded_at')->index();
            $table->timestamps();

            $table->index(['gym_id', 'member_id', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_progress');
        Schema::dropIfExists('training_plans');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('subscriptions');
    }
};
