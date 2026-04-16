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
        Schema::table('messages', function (Blueprint $table) {

            // For private chat
            $table->unsignedBigInteger('receiver_id')->nullable()->after('sender_id');

            // Optional: message type
            $table->enum('type', ['group', 'private'])->default('group')->after('message');

            // Optional: read status
            $table->timestamp('read_at')->nullable()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['receiver_id', 'type', 'read_at']);
        });
    }
};
