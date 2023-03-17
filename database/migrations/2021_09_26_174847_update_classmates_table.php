<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClassmatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classmates', function (Blueprint $table) {
            $table->dropForeign('classmates_school_id_foreign');
            $table->dropColumn(['school_id']);
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
            $table->foreignId('school_id')->nullable()->constrained('schools');
        });
    }
}
