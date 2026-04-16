<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ✅ Add last_seen (only if not exists)
        if (!Schema::hasColumn('users', 'last_seen')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('last_seen')->nullable()->after('updated_at');
            });
        }

        // ✅ Remove mobile_no (only if exists)
        if (Schema::hasColumn('users', 'mobile_no')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('mobile_no');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 🔁 Re-add mobile_no if rollback
        if (!Schema::hasColumn('users', 'mobile_no')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('mobile_no')->nullable();
            });
        }

        // 🔁 Remove last_seen if rollback
        if (Schema::hasColumn('users', 'last_seen')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('last_seen');
            });
        }
    }
};