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
        $meeting = Meeting::factory()->create();
        $user = User::find($meeting->user_id);
        $this->actingAs($user);

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
        $question = FeedbackQuestion::factory()->create();
        $meeting = Meeting::find($question->meeting_id);
        $user = User::find($meeting->user_id);

        $question2 = new FeedbackQuestion();
        $question2->question = 'What would you change about the workshop';
        $question2->question_type = '0';
        $question2->meeting_id = $meeting->id;
        $question2->save();

        $this->actingAs($user);

        $response = $this->get(route('meetings.feedback', [$meeting,2]));
        
        $content = $response->getOriginalContent()->getData()['page']['props'];

        $this->assertEquals($meeting->toArray(),$content['meeting']);
        $this->assertEquals($question2->question, $content['question']['question']);
        $this->assertEquals($question2->question_type, $content['question']['question_type']);

    }

    public function testUserRedirectedIfQuestionNumberIsMoreThanMaximumPossible()
    {   

        $meeting = Meeting::factory()->create();
        $user = User::find($meeting->user_id);

        $question = new FeedbackQuestion();
        $question->question = 'Did you like the meeting?';
        $question->question_type = '0';
        $question->meeting_id = $meeting->id;
        $question->save();

        $this->actingAs($user);

        //Only have 1 question so max number is 1
        $response = $this->get(route('meetings.feedback', [$meeting,2]));

        $response->assertRedirect('/');
        
    }

    public function testUserRedirectedIfQuestionNumberIsForANonWordedResponseQuestion()
    {   
        $meeting = Meeting::factory()->create();
        $user = User::find($meeting->user_id);

        $this->actingAs($user);

        $question = new FeedbackQuestion();
        $question->question = 'What would you change about the meeting?';
        $question->question_type = '3';
        $question->meeting_id = $meeting->id;
        $question->save();
        
        $response = $this->get(route('meetings.feedback', [$meeting,1]));

        $response->assertRedirect('/');
        
    }

    public function testUserRedirectedIfQuestionNumberIsNegative()
    {   
        $meeting = Meeting::factory()->create();
        $user = User::find($meeting->user_id);

        $question = new FeedbackQuestion();
        $question->question = 'Did you like the meeting?';
        $question->question_type = '0';
        $question->meeting_id = $meeting->id;
        $question->save();

        $this->actingAs($user);

        $response = $this->get(route('meetings.feedback', [$meeting,-1]));

        $response->assertRedirect('/');
        
    }
    public function testUserRedirectedBackIfQuestionNumberIsNotANumber()
    {   

        $meeting = Meeting::factory()->create();
        $user = User::find($meeting->user_id);

        $question = new FeedbackQuestion();
        $question->question = 'Did you like the meeting?';
        $question->question_type = '0';
        $question->meeting_id = $meeting->id;
        $question->save();

        $this->actingAs($user);
        
        $response = $this->get(route('meetings.feedback', [$meeting,'question_number' => 'string']));

        $response->assertRedirect('/');
        
    }

    public function testUserRedirectedBackIfQuestionNumberIsNotInteger()
    {   
        $meeting = Meeting::factory()->create();
        $user = User::find($meeting->user_id);

        $question = new FeedbackQuestion();
        $question->question = 'Did you like the meeting?';
        $question->question_type = '0';
        $question->meeting_id = $meeting->id;
        $question->save();

        $this->actingAs($user);

        
        $response = $this->get(route('meetings.feedback', [$meeting,'question_number' => '0.5']));

        $response->assertRedirect('/');
        
    }

}
