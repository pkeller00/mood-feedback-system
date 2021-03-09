<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Jetstream\Features;

use App\Models\Meeting;
use App\Models\FeedbackQuestion;
use App\Models\User;

class ShowMeetingDetailsTest extends TestCase
{
    use RefreshDatabase;

    public function testMeetingDataIsSentCorrectly()
    {   
        $question = FeedbackQuestion::factory()->create();
        $meeting = Meeting::find($question->meeting_id);
        $user = User::find($meeting->user_id);

        $this->actingAs($user);


        $response = $this->get(route('meetings.show', $meeting));

        $content = $response->getOriginalContent()->getData()['page']['props'];

        $this->assertEquals($meeting->toArray(), $content['meeting']);
        $this->assertEquals($question->question, $content['questions'][0]['question']);
        $this->assertEquals($question->question_type, $content['questions'][0]['question_type']);
        $this->assertArrayNotHasKey(1, $content['questions']); 
        $this->assertEquals(true, $content['event_started']);
        
    }

    public function testMeetingDataIsOnlyShownIfUserHostsEvent()
    {   
        $question = FeedbackQuestion::factory()->create();
        $meeting = Meeting::find($question->meeting_id);

        $this->actingAs($user = User::factory()->create());

        $response = $this->get(route('meetings.show', $meeting));

        $response->assertRedirect(route('meetings.index'));
        
    }

    public function testIfToBeShownMeetingNotInDatabaseThenRedirectToIndex()
    {   

        $this->actingAs($user = User::factory()->create());

        //$this->withoutExceptionHandling();

        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2020-01-01T00:00';
        $meeting->meeting_end ='2020-01-09T00:00';
        $meeting->user_id =$user->id;
        $meeting->meeting_reference ='QWERTYU';

        $question = new FeedbackQuestion();
        $question->question = '‘Lorem’';
        $question->question_type = '3';
        $question->meeting_id = $meeting->id;

        $response = $this->get(route('meetings.show', $meeting));

        $response->assertRedirect(route('meetings.index'));
        
    }

}
