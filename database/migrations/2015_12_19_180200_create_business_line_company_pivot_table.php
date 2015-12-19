<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessLineCompanyPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_line_company', function (Blueprint $table) {
            $table->integer('business_line_id')->unsigned()->index();
            $table->foreign('business_line_id')->references('id')->on('business_lines')->onDelete('cascade');
            $table->integer('company_id')->unsigned()->index();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->date('end_date')->nullable();
            $table->integer('order')->default(0);
            $table->primary(['business_line_id', 'company_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('business_line_company');
    }
}
