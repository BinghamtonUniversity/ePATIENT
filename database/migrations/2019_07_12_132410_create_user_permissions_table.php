<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->index();
            $table->enum('permission',[
                'manage_users',
                'manage_user_permissions',
                'manage_teams',
                'manage_scenarios',
                'manage_products',
                'manage_prescribers',
                'manage_solutions',
                'manage_labs'
            ])->nullable()->default(null);
            $table->timestamps();
            $table->unique(['user_id','permission']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_permissions');
    }
}
