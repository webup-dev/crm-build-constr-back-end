<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration to create workflow_stages table
 *
 * @category Migration
 * @package  Workflows
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Migration
 */
class CreateWorkflowStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'workflow_stages',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('workflow_id')->unsigned()->index();
                $table->foreign('workflow_id')->references('id')
                    ->on('workflows')
                    ->onDelete('cascade');
                $table->integer('stage_id')->unsigned()->index();
                $table->foreign('stage_id')->references('id')
                    ->on('stages')
                    ->onDelete('cascade');
                $table->tinyInteger('order');
                $table->softDeletes();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workflow_stages');
    }
}
