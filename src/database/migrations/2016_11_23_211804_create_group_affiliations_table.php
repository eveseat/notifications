<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupAffiliationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('group_affiliations', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('notification_group_id')->unsigned();
            $table->string('type');
            $table->integer('affiliation_id');

            $table->foreign('notification_group_id')->references('id')
                ->on('notification_groups')
                ->onDelete('cascade');
            $table->index('type');

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

        Schema::dropIfExists('group_affiliations');
    }
}
