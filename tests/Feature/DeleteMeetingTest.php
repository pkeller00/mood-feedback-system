<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Jetstream\Features;

use App\Models\Meeting;
use App\Models\FeedbackQuestion;
use App\Models\User;
use Carbon\Carbon;

class DeleteMeetingTest extends TestCase
{
    use RefreshDatabase;

    public function testMeetingCanBeDeleted()
    {   

        $meeting = Meeting::factory()->create();
        $user = User::find($meeting->user_id);
        $this->actingAs($user);

        $response = $this->delete(route('meetings.destroy', $meeting));

        $response->assertStatus(302);
        $this->assertNull($meeting->fresh());
    }

    public function testIfMeetingDeletedThenMeetingQuestionAlsoDeleted()
    {   
        $question = FeedbackQuestion::factory()->create();
        $meeting = Meeting::find($question->meeting_id);
        $user = User::find($meeting->user_id);
        $this->actingAs($user);

        $this->actingAs($user);

        $response = $this->delete(route('meetings.destroy', $meeting));

        $response->assertStatus(302);
        $this->assertNull($meeting->fresh());
        $this->assertNull($question->fresh());
        $response->assertRedirect(route('meetings.index'));
    }

    public function testIfMeetingDeletedThenAllMeetingFormQuestionsAlsoDeleted()
    {   

        $question = FeedbackQuestion::factory()->create();
        $meeting = Meeting::find($question->meeting_id);

        $question2 = new FeedbackQuestion();
        $question2->question = 'What would you change about the meeting?';
        $question2->question_type = '3';
        $question2->meeting_id = $meeting->id;
        $question2->save();

        $user = User::find($meeting->user_id);
        $this->actingAs($user);

        $response = $this->delete(route('meetings.destroy', $meeting));

        $response->assertStatus(302);
        $this->assertNull($meeting->fresh());
        $this->assertNull($question->fresh());
        $this->assertNull($question2->fresh());
        $response->assertRedirect(route('meetings.index'));
    }

    public function testDoNotDeleteMeetingIfNotMeetingCreator()
    {   
        $this->actingAs($user = User::factory()->create());

        $question = FeedbackQuestion::factory()->create();
        $meeting = Meeting::find($question->meeting_id);

        

        $response = $this->delete(route('meetings.destroy', $meeting));

        $response->assertStatus(302);
        $this->assertNotNull($meeting->fresh());
        $this->assertNotNull($question->fresh());
        $response->assertRedirect(route('meetings.index'));
    }

    // public function testIfToBeDeletedMeetingNotInDatabaseThenRedirectToIndex()
    // {   $this->withoutExceptionHandling();
        
    //     $this->actingAs($user = User::factory()->create());;
        
    //     $meeting = new Meeting();
    //     $meeting->name ='workshop';
    //     $meeting->meeting_start ='2020-01-01T00:00';
    //     $meeting->meeting_end ='2020-01-09T00:00';
    //     $meeting->user_id = $user->id;
    //     $meeting->meeting_reference ='QWERTYU';

    //     $response = $this->delete(route('meetings.destroy', $meeting));

    //     $response->assertRedirect(route('meetings.index'));
    // }

}
