<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDiarieItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diarie_items', function (Blueprint $table) {
            $table->foreignId('diarie_id')->nullable()->change();
            $table->foreignId('share_item_id')->nullable()->constrained('diarie_items');
            $table->string('activity', 40)->nullable()->change();
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
            $table->dropForeign('diarie_items_share_item_id_foreign');
            $table->dropColumn(['share_item_id']);
        });
    }
}
