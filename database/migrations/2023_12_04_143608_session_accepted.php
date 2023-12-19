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
        Schema::create('training_allocated', function (Blueprint $table) {
            $table->id();
            $table->integer('training_id')->unsigned();
            $table->foreign('training_id')->references('id')->on('trainnings');
            $table->integer('workshop_id')->unsigned();
            $table->foreign('workshop_id')->references('id')->on('workshops');
            $table->char('enrole_status','1');
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
        Schema::dropIfExists('training_allocated');
    }
};
