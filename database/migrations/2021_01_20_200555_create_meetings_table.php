<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('meeting_reference')->unique();
            $table->text('name');
            // need to assign a meeting to the author that has created it
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // $table->bigInteger('user_id')->unsigned();

            // need to assign a date and times for the meetings
            $table->dateTime('meeting_start');
            $table->dateTime('meeting_end');
            // $table->dateTime('meeting_date');
            // $table->time('start_time');
            // $table->time('end_time');

            $table->timestamps();

            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meetings');
    }
}
