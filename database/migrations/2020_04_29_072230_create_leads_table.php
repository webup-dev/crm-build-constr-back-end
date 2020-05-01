<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('organization_id')->unsigned()->index();
            $table->foreign('organization_id')
                ->references('id')
                ->on('organizations')
                ->onDelete('cascade');
            $table->dateTime('due_date')->nullable();
            $table->dateTime('anticipated_project_date')->nullable();
            $table->integer('lead_type_id')->unsigned()->index();
            $table->foreign('lead_type_id')
                ->references('id')
                ->on('lead_types')
                ->onDelete('cascade');
            $table->integer('lead_status_id')->unsigned()->index();
            $table->foreign('lead_status_id')
                ->references('id')
                ->on('lead_statuses')
                ->onDelete('cascade');
            $table->string('declined_reason_other')->nullable();
            $table->integer('lead_source_id')->unsigned()->index();
            $table->foreign('lead_source_id')
                ->references('id')
                ->on('lead_sources')
                ->onDelete('cascade');
            $table->integer('stage_id')->unsigned()->nullable();
            $table->foreign('stage_id')
                ->references('id')
                ->on('stages')
                ->onDelete('cascade');
            $table->string('line_1');
            $table->string('line_2');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->integer('requester_id')->unsigned()->index();
            $table->foreign('requester_id')
                ->references('id')
                ->on('requesters')
                ->onDelete('cascade');
            $table->string('note')->nullable();
            $table->integer('lead_owner_id')->unsigned()->index();
            $table->foreign('lead_owner_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
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
        Schema::dropIfExists('leads');
    }
}
