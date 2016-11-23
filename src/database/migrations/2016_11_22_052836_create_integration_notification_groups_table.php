<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntegrationNotificationGroupsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('integration_notification_group', function (Blueprint $table) {

            $table->integer('integration_id')->unsigned()->index();
            $table->foreign('integration_id')->references('id')->on('integrations')
                ->onDelete('cascade');

            $table->integer('notification_group_id')->unsigned()->index();
            $table->foreign('notification_group_id')->references('id')->on('notification_groups')
                ->onDelete('cascade');

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

        Schema::dropIfExists('integration_notification_group');
    }
}
