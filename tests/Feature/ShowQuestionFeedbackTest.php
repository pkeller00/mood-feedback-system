<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Jetstream\Features;

use App\Models\Meeting;
use App\Models\FeedbackQuestion;
use App\Models\User;

class ShowQuestionFeedbackTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanViewFeedbackForAWordedQuestionInForm()
    {   

        $this->actingAs($user = User::factory()->create());

        $this->withoutExceptionHandling();

        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2012-01-01T00:00';
        $meeting->meeting_end ='2012-01-09T00:00';
        $meeting->user_id =$user->id;
        $meeting->meeting_reference ='QWERTYU';
        $meeting->save();

        $question = new FeedbackQuestion();
        $question->question = 'Did you like the meeting?';
        $question->question_type = '0';
        $question->meeting_id = $meeting->id;
        $question->save();

        

        $response = $this->get(route('meetings.feedback', [$meeting,1]));
        
        $content = $response->getOriginalContent()->getData()['page']['props'];

        $this->assertEquals($meeting->toArray(),$content['meeting']);
        $this->assertEquals($question->question, $content['question']['question']);
        $this->assertEquals($question->question_type, $content['question']['question_type']);

    }

    public function testUserCanViewFeedbackForAWordedQuestionInFormWithManyWordedQuestions()
    {   

        $this->actingAs($user = User::factory()->create());

        $this->withoutExceptionHandling();

        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2012-01-01T00:00';
        $meeting->meeting_end ='2012-01-09T00:00';
        $meeting->user_id =$user->id;
        $meeting->meeting_reference ='QWERTYU';
        $meeting->save();

        $question = new FeedbackQuestion();
        $question->question = 'Did you like the meeting?';
        $question->question_type = '0';
        $question->meeting_id = $meeting->id;
        $question->save();

        $question2 = new FeedbackQuestion();
        $question2->question = 'What would you change about the workshop';
        $question2->question_type = '0';
        $question2->meeting_id = $meeting->id;
        $question2->save();

        

        $response = $this->get(route('meetings.feedback', [$meeting,2]));
        
        $content = $response->getOriginalContent()->getData()['page']['props'];

        $this->assertEquals($meeting->toArray(),$content['meeting']);
        $this->assertEquals($question2->question, $content['question']['question']);
        $this->assertEquals($question2->question_type, $content['question']['question_type']);

    }

    public function testUserRedirectedIfQuestionNumberIsMoreThanMaximumPossible()
    {   

        $this->actingAs($user = User::factory()->create());

        //$this->withoutExceptionHandling();

        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2012-01-01T00:00';
        $meeting->meeting_end ='2012-01-09T00:00';
        $meeting->user_id =$user->id;
        $meeting->meeting_reference ='QWERTYU';
        $meeting->save();

        $question = new FeedbackQuestion();
        $question->question = 'Did you like the meeting?';
        $question->question_type = '0';
        $question->meeting_id = $meeting->id;
        $question->save();

        
        //Only have 1 question so max number is 1
        $response = $this->get(route('meetings.feedback', [$meeting,2]));

        $response->assertRedirect('/');
        
    }

    public function testUserRedirectedIfQuestionNumberIsForANonWordedResponseQuestion()
    {   

        $this->actingAs($user = User::factory()->create());

        //$this->withoutExceptionHandling();

        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2012-01-01T00:00';
        $meeting->meeting_end ='2012-01-09T00:00';
        $meeting->user_id =$user->id;
        $meeting->meeting_reference ='QWERTYU';
        $meeting->save();

        $question = new FeedbackQuestion();
        $question->question = 'Did you like the meeting?';
        $question->question_type = '3';
        $question->meeting_id = $meeting->id;
        $question->save();

        
        $response = $this->get(route('meetings.feedback', [$meeting,1]));

        $response->assertRedirect('/');
        
    }

    public function testUserRedirectedIfQuestionNumberIsNegative()
    {   

        $this->actingAs($user = User::factory()->create());

        //$this->withoutExceptionHandling();

        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2012-01-01T00:00';
        $meeting->meeting_end ='2012-01-09T00:00';
        $meeting->user_id =$user->id;
        $meeting->meeting_reference ='QWERTYU';
        $meeting->save();

        $question = new FeedbackQuestion();
        $question->question = 'Did you like the meeting?';
        $question->question_type = '0';
        $question->meeting_id = $meeting->id;
        $question->save();

        
        $response = $this->get(route('meetings.feedback', [$meeting,-1]));

        $response->assertRedirect('/');
        
    }
    public function testUserRedirectedBackIfQuestionNumberIsNotANumber()
    {   

        $this->actingAs($user = User::factory()->create());

        //$this->withoutExceptionHandling();

        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2012-01-01T00:00';
        $meeting->meeting_end ='2012-01-09T00:00';
        $meeting->user_id =$user->id;
        $meeting->meeting_reference ='QWERTYU';
        $meeting->save();

        $question = new FeedbackQuestion();
        $question->question = 'Did you like the meeting?';
        $question->question_type = '0';
        $question->meeting_id = $meeting->id;
        $question->save();

        $response = $this->get(route('meetings.feedback', [$meeting,'question_number' => 'string']));

        $response->assertRedirect('/');
        
    }

    public function testUserRedirectedBackIfQuestionNumberIsNotInteger()
    {   

        $this->actingAs($user = User::factory()->create());

        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2012-01-01T00:00';
        $meeting->meeting_end ='2012-01-09T00:00';
        $meeting->user_id =$user->id;
        $meeting->meeting_reference ='QWERTYU';
        $meeting->save();

        $question = new FeedbackQuestion();
        $question->question = 'Did you like the meeting?';
        $question->question_type = '0';
        $question->meeting_id = $meeting->id;
        $question->save();

        $question2 = new FeedbackQuestion();
        $question2->question = 'Did you like the meeting?';
        $question2->question_type = '0';
        $question2->meeting_id = $meeting->id;
        $question2->save();

        
        $response = $this->get(route('meetings.feedback', [$meeting,'question_number' => '1.5']));

        $response->assertRedirect('/');
        
    }

}
