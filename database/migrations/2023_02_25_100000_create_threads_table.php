<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThreadsTable extends Migration
{

    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('slug')->unique()->nullable();
            $table->unsignedInteger('channel_id');
            $table->unsignedInteger('replies_count')->default(0);
            $table->string('title');
            $table->text('body');
            $table->unsignedInteger('best_reply_id')->nullable();
            $table->boolean('locked')->default(false);
            $table->timestamps();

            $table->foreign('best_reply_id')
                ->references('id')
                ->on('replies')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('threads');
    }
}