<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diaries', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('writer_id')->nullable()->constrained('users');
            // 送迎
            $table->bigInteger('pick_driver_id')->nullable();
            $table->bigInteger('drop_driver_id')->nullable();
            $table->string('pick_driver_name', 60)->nullable();
            $table->string('drop_driver_name', 60)->nullable();
            $table->string('pick_addres')->nullable();
            $table->string('drop_addres')->nullable();
            $table->time('pick_depart_time')->nullable();
            $table->time('drop_depart_time')->nullable();
            $table->time('pick_arrive_time')->nullable();
            $table->time('drop_arrive_time')->nullable();
            // 連絡帳
            $table->string('private_msg')->nullable();
            $table->string('hidden_msg')->nullable();
            // 書類
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->smallInteger('service_type')->nullable();
            $table->string('content')->nullable();
            $table->foreignId('sign_id')->nullable()->constrained('sign_images');
            $table->string('etc_note')->nullable();
            // その他
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
        Schema::dropIfExists('diaries');
    }
}
