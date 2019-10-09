<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeUniqueInActivatedProjectKeyUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activated_project_key_user', function (Blueprint $table) {
            $table->unique(['user_id', 'project_key_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activated_project_key_user', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'project_key_id']);
        });
    }
}
