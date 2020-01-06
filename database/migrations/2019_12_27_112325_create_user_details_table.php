<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('prefix', ['Mr', 'Mrs', 'Ms', 'Dr', 'Sir']);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->string('work_title')->nullable();
            $table->string('work_department')->nullable();
            $table->string('work_role')->nullable();
            $table->string('phone_home')->nullable();
            $table->string('phone_work')->nullable();
            $table->string('phone_extension')->nullable();
            $table->string('phone_mob')->nullable();
            $table->string('phone_fax')->nullable();
            $table->string('email_work')->nullable();
            $table->string('email_personal');
            $table->string('line_1');
            $table->string('line_2');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->enum('status', ['active', 'inactive'])->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_details');
    }
}
