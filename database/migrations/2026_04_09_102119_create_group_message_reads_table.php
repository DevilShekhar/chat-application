<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('group_message_reads', function (Blueprint $table) {
            $table->id();

            // message reference
            $table->unsignedBigInteger('message_id');

            // user who read
            $table->unsignedBigInteger('user_id');

            // read time
            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            // indexes (important for performance)
            $table->index(['message_id', 'user_id']);

            // optional foreign keys
            $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // prevent duplicate read entries
            $table->unique(['message_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_message_reads');
    }
};