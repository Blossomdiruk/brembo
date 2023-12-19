<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points_redeem', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('product_points');
            $table->integer('workshop_id');
            $table->string('points');
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
        Schema::dropIfExists('points_redeem');
    }
};
