<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectKeyIdToAvailableCheatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('available_cheats', function (Blueprint $table) {
            $table->unsignedBigInteger('project_key_id')->nullable();
            $table->foreign('project_key_id')->on('project_keys')->references('id')->onDelete('set NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('available_cheats', function (Blueprint $table) {
            $table->dropColumn('project_key_id');
        });
    }
}
