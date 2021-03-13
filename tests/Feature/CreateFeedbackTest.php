<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Jetstream\Features;

use App\Models\Meeting;
use App\Models\FeedbackQuestion;
use App\Models\ResponseInformation;
use App\Models\User;
use App\Models\FeedbackResponse;

class CreateFeedbackTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Can store if all valid
    */

    public function testRedirectHomeIfInvalidQuestionType()
    {   
        $this->withoutExceptionHandling();

        $question = FeedbackQuestion::factory()->create();
        $meeting = Meeting::find($question->meeting_id);
        $user = User::find($meeting->user_id);
        $this->actingAs($user);

        Meeting::where('id',$meeting->id)->update(array('meeting_end'=>'2040-03-05T04:03'));

        $response = $this->post(route('attendevents.store',[
            'name'=>'joe',
            'email'=>'joe@gmail.com',
            'questions' => [
                ['id'=>$question->id,'question'=>$question->question,'question_type'=>5],
            ],
            'responses' => ['Response 1'],
            $meeting,
        ]));

        $this->assertCount(0,ResponseInformation::all());
        $this->assertCount(0,FeedbackResponse::all());
        $response->assertRedirect(route('home'));
    }

    /**
     * Can store if all valid
    */

    public function testCanStoreFeedbackResponseIfValid()
    {   
        $this->withoutExceptionHandling();

        $question = FeedbackQuestion::factory()->create();
        $meeting = Meeting::find($question->meeting_id);
        $user = User::find($meeting->user_id);
        $this->actingAs($user);

        Meeting::where('id',$meeting->id)->update(array('meeting_end'=>'2040-03-05T04:03'));
 
        $response = $this->post(route('attendevents.store',[
            'name'=>'joe',
            'email'=>'joe@gmail.com',
            'questions' => [
                ['id'=>$question->id,'question'=>$question->question,'question_type'=>$question->question_type],
            ],
            'responses' => ['Response 1'],
            $meeting,
        ]));

        $this->assertCount(1,ResponseInformation::all());
        $this->assertCount(1,FeedbackResponse::all());
    }
    public function testIfEventNotStartedDontAcceptFeedback()
    {   
        $this->withoutExceptionHandling();

        $question = FeedbackQuestion::factory()->create();
        $meeting = Meeting::find($question->meeting_id);
        $user = User::find($meeting->user_id);
        $this->actingAs($user);

        Meeting::where('id',$meeting->id)->update(array('meeting_end'=>'2040-03-05T04:03'));
        Meeting::where('id',$meeting->id)->update(array('meeting_start'=>'2030-03-05T04:03'));
 
        $response = $this->post(route('attendevents.store',[
            'name'=>'joe',
            'email'=>'joe@gmail.com',
            'questions' => [
                ['id'=>$question->id,'question'=>$question->question,'question_type'=>$question->question_type],
            ],
            'responses' => ['Response 1'],
            $meeting,
        ]));

        $this->assertCount(0,ResponseInformation::all());
        $this->assertCount(0,FeedbackResponse::all());
        $response->assertRedirect('/');
    }

}
