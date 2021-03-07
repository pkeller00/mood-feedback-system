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
        $this->actingAs($user = User::factory()->create());

        $this->withoutExceptionHandling();
        
        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2012-01-01T00:00';
        $meeting->meeting_end ='2012-01-09T00:00';
        $meeting->user_id =$user->id;
        $meeting->meeting_reference ='QWERTYU';
        $meeting->save();
        
        $response = $this->delete(route('meetings.destroy', $meeting));

        $response->assertStatus(302);
        $this->assertNull($meeting->fresh());
    }

    public function testIfMeetingDeletedThenMeetingQuestionAlsoDeleted()
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
        $question->question_type = '3';
        $question->meeting_id = $meeting->id;
        $question->save();

        $response = $this->delete(route('meetings.destroy', $meeting));

        $response->assertStatus(302);
        $this->assertNull($meeting->fresh());
        $this->assertNull($question->fresh());
        $response->assertRedirect(route('meetings.index'));
    }

    public function testIfMeetingDeletedThenAllMeetingFormQuestionsAlsoDeleted()
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
        $question->question_type = '3';
        $question->meeting_id = $meeting->id;
        $question->save();

        $question2 = new FeedbackQuestion();
        $question2->question = 'What would you change about the meeting?';
        $question2->question_type = '3';
        $question2->meeting_id = $meeting->id;
        $question2->save();

        $response = $this->delete(route('meetings.destroy', $meeting));

        $response->assertStatus(302);
        $this->assertNull($meeting->fresh());
        $this->assertNull($question->fresh());
        $this->assertNull($question2->fresh());
        $response->assertRedirect(route('meetings.index'));
    }

    public function testDoNotDeleteMeetingIfNotMeetingCreator()
    {   
        $user = User::factory()->create();
        
        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2012-01-01T00:00';
        $meeting->meeting_end ='2012-01-09T00:00';
        $meeting->user_id = $user->id;
        $meeting->meeting_reference ='QWERTYU';
        $meeting->save();
        
        $question = new FeedbackQuestion();
        $question->question = 'Did you like the meeting?';
        $question->question_type = '3';
        $question->meeting_id = $meeting->id;
        $question->save();


        $this->actingAs($user2 = User::factory()->create());

        $response = $this->delete(route('meetings.destroy', $meeting));

        $response->assertStatus(302);
        $this->assertNotNull($meeting->fresh());
        $this->assertNotNull($question->fresh());
        $response->assertRedirect(route('meetings.index'));
    }

    public function testIfToBeDeletedMeetingNotInDatabaseThenRedirectToIndex()
    {   
        $this->actingAs($user = User::factory()->create());;
        
        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2012-01-01T00:00';
        $meeting->meeting_end ='2012-01-09T00:00';
        $meeting->user_id = $user->id;
        $meeting->meeting_reference ='QWERTYU';

        $response = $this->delete(route('meetings.destroy', $meeting));

        $response->assertRedirect(route('meetings.index'));
    }

}
