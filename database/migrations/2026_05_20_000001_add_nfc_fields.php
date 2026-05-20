<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('bracelet_uid', 50)->nullable()->unique()->after('bio');
        });

        DB::statement("ALTER TABLE attendances MODIFY COLUMN method ENUM('nfc', 'qr', 'manual') NOT NULL DEFAULT 'nfc'");
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('bracelet_uid');
        });

        DB::statement("ALTER TABLE attendances MODIFY COLUMN method ENUM('qr', 'manual') NOT NULL DEFAULT 'manual'");
    }
};
