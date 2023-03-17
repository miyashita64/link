<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiarieItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diarie_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diarie_id')->constrained('diaries');
            $table->foreignId('writer_id')->constrained('users');
            $table->time('time');
            $table->string('activity', 40);
            $table->string('comment')->nullable();
            $table->boolean('active')->default(true);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diarie_items');
    }
}
