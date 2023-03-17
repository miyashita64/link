<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameEmploymentsToWorkers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employments', function(Blueprint $table){
            $table->renameColumn('worker_id', 'user_id');
        });
        Schema::rename("employments", "workers");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workers', function(Blueprint $table){
            $table->renameColumn('user_id', 'worker_id');
        });
        Schema::rename("workers", "employments");
    }
}
