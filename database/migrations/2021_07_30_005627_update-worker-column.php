<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWorkerColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workers', function(Blueprint $table){
            Schema::disableForeignKeyConstraints();
            // user_idをついて、NULLを許容する
            $table->bigInteger('user_id')->unsigned()->nullable()->change();
            Schema::enableForeignKeyConstraints();
            $table->string('name', 60);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workers', function(Blueprint $table){
            Schema::disableForeignKeyConstraints();
            // user_idをついて、NULLを許容しない
            $table->bigInteger('user_id')->unsigned()->nullable(false)->change();
            Schema::enableForeignKeyConstraints();
            $table->dropColumn('name');
        });
    }
}
