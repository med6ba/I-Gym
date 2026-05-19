<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('currency', 3)->default('MAD')->after('theme');
            $table->unsignedTinyInteger('age')->nullable()->after('currency');
            $table->decimal('height_cm', 5, 2)->nullable()->after('age');
            $table->decimal('weight_kg', 5, 2)->nullable()->after('height_cm');
            $table->string('gender', 30)->nullable()->after('weight_kg');
            $table->string('fitness_goal')->nullable()->after('gender');
            $table->text('bio')->nullable()->after('fitness_goal');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'currency',
                'age',
                'height_cm',
                'weight_kg',
                'gender',
                'fitness_goal',
                'bio',
            ]);
        });
    }
};
