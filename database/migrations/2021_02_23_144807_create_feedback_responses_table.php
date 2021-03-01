<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback_responses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('feedback_response_id')->unsigned();
            $table->bigInteger('feedback_question_id')->unsigned();

            $table->json('response');
            $table->decimal('score');

            $table->timestamps();

            $table->foreign('feedback_response_id')->references('id')->on('response_information');
            $table->foreign('feedback_question_id')->references('id')->on('feedback_questions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedback_responses');
    }
}
