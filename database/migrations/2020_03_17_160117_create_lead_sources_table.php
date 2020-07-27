<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration to create lead_s table
 *
 * @category Migration
 * @package  LeadSources
 * @author   Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * @license  https://opensource.org/licenses/CDDL-1.0 CDDL-1.0
 * @link     Migration
 */
class CreateLeadSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'lead_sources',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->integer('category_id')->unsigned()->index();
                $table->foreign('category_id')
                    ->references('id')
                    ->on('ls_categories')
                    ->onDelete('cascade');
                $table->integer('organization_id')->unsigned()->index();
                $table->foreign('organization_id')
                    ->references('id')
                    ->on('organizations')
                    ->onDelete('cascade');
                $table->enum('status', ['active', 'inactive']);
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
        Schema::dropIfExists('lead_sources');
    }
}
