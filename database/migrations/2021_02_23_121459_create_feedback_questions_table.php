<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback_questions', function (Blueprint $table) {
            $table->id();

            // question itself
            $table->string('question');

            // question type to render in form (thinking of numbers that represent the different set states)
            $table->smallInteger('question_type');
            // $table->smallInteger('question_type')->nullable($value = false);

            $table->foreignId('meeting_id')->constrained()->onDelete('cascade');
            // $table->bigInteger('meeting_id')->unsigned();

            $table->timestamps();

            // $table->foreign('meeting_id')->references('id')->on('meetings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedback_questions');
    }
}
