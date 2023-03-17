<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSchoolItemsColumnSchoolIdClassroomId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('school_items', function (Blueprint $table) {
            $table->foreignId('school_id')->constrained('schools');
            $table->foreignId('classroom_id')->constrained('classrooms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('school_items', function (Blueprint $table) {
          $table->dropForeign('school_items_school_id_foreign');
          $table->dropColumn(['school_id']);
          $table->dropForeign('school_items_classroom_id_foreign');
          $table->dropColumn(['classroom_id']);
        });
    }
}
