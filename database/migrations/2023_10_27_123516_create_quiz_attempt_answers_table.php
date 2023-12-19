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
        Schema::create('quiz_attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->integer('quiz_attempt_id')->unsigned();
            $table->foreign('quiz_attempt_id')->references('id')->on('quiz_attempts');
            $table->integer('quiz_id')->unsigned();
            $table->foreign('quiz_id')->references('id')->on('quizzes');
            $table->text('answer');
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
        Schema::dropIfExists('quiz_attempt_answers');
    }
};
