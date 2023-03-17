<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('seats');
        Schema::dropIfExists('seating_charts');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('seating_charts', function (Blueprint $table) {
            $table->id();
            $table->integer('row_size');
            $table->integer('column_size');
            $table->boolean('active')->default(true);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seating_chart_id')->constrained('seating_charts');
            $table->foreignId('student_id')->constrained('students');
            $table->integer('row');
            $table->integer('column');
            $table->boolean('active')->default(true);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }
}
