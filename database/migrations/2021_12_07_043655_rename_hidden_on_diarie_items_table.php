<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameHiddenOnDiarieItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diarie_items', function (Blueprint $table) {
            $table->renameColumn('hidden', 'parent_hidden');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('diarie_items', function (Blueprint $table) {
            $table->renameColumn('parent_hidden', 'hidden');
        });
    }
}
