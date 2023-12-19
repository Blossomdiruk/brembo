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
        Schema::create('michanics', function (Blueprint $table) {
            $table->id();
            $table->integer('workshop_id')->unsigned();
            $table->foreign('workshop_id')->references('id')->on('workshops');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
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
        Schema::dropIfExists('michanics');
    }
};
