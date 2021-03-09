<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Jetstream\Features;

use App\Models\Meeting;
use App\Models\FeedbackQuestion;
use App\Models\User;

class ShowAttendeeEventFormTest extends TestCase
{
    use RefreshDatabase;

    public function testUserTakenToEventFormIfEventssssHasStarted()
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

}
