<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamActivityLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_activity_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('team_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->string('form');
            $table->enum('event',['create','read','update','delete','action'])->nullable()->default(null);
            $table->json('data')->nullable();
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
        Schema::dropIfExists('team_activity_log');
    }
}
