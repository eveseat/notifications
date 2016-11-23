<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateL53NotificationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('notifications');

        Schema::create('notifications', function (Blueprint $table) {

            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('notifications');

        Schema::create('notifications', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('user_id');
            $table->string('hash');
            $table->string('subject');
            $table->text('message');

            // Index
            $table->index('hash');
            $table->timestamps();
        });
    }
}
