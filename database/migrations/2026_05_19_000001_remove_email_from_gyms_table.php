<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('gyms', 'email')) {
            return;
        }

        Schema::table('gyms', function (Blueprint $table): void {
            $table->dropUnique('gyms_email_unique');
        });

        Schema::table('gyms', function (Blueprint $table): void {
            $table->dropColumn('email');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('gyms', 'email')) {
            return;
        }

        Schema::table('gyms', function (Blueprint $table): void {
            $table->string('email')->nullable()->unique()->after('slug');
        });
    }
};
