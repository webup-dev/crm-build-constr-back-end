<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateRequestersTable
 */
class CreateRequestersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requesters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('organization_id')->unsigned()->index();
            $table->foreign('organization_id')->references('id')
                ->on('organizations')->onDelete('cascade');
            $table->string('prefix')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('suffix')->nullable();
            $table->string('email_work')->nullable();
            $table->string('email_personal')->nullable();
            $table->string('line_1');
            $table->string('line_2');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('phone_home')->nullable();
            $table->string('phone_work')->nullable();
            $table->string('phone_extension')->nullable();
            $table->string('phone_mob1')->nullable();
            $table->string('phone_mob2')->nullable();
            $table->string('phone_fax')->nullable();
            $table->string('website')->nullable();
            $table->string('other_source')->nullable();
            $table->string('note')->nullable();
            $table->integer('created_by_id')->unsigned()->index();
            $table->foreign('created_by_id')->references('id')
                ->on('users')->onDelete('cascade');
            $table->integer('updated_by_id')->unsigned()->nullable();
            $table->foreign('updated_by_id')->references('id')
                ->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('requesters');
    }
}
