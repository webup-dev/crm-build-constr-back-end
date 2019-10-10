<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('title')->nullable();
            $table->integer('department_id')->unsigned()->index();
            $table->foreign('department_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->string('phone_home')->nullable();
            $table->string('phone_work')->nullable();
            $table->string('phone_extension')->nullable();
            $table->string('phone_mob')->nullable();
            $table->string('email_personal')->nullable();
            $table->string('email_work')->nullable();
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->enum('status', ['active','inactive','terminated']);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('termination_date')->nullable();
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
        Schema::dropIfExists('user_profiles');
    }
}
