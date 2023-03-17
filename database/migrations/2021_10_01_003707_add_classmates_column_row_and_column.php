<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClassmatesColumnRowAndColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classmates', function (Blueprint $table) {
            $table->integer('row')->nullable();
            $table->integer('column')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classmates', function (Blueprint $table) {
            $table->dropColumn('row');
            $table->dropColumn('column');
        });
    }
}
