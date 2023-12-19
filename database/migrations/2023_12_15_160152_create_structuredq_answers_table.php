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
        Schema::create('structuredq_answers', function (Blueprint $table) {
            $table->id();
            $table->integer('exam_id')->unsigned();
            $table->foreign('exam_id')->references('id')->on('exam');
            $table->integer('structured_id')->unsigned();
            $table->foreign('structured_id')->references('id')->on('structured_questions');
            $table->integer('workshop_id')->unsigned();
            $table->foreign('workshop_id')->references('id')->on('workshops');
            $table->text('quest_answer');
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
        Schema::dropIfExists('structuredq_answers');
    }
};
