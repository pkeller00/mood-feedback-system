<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Jetstream\Features;

use App\Models\Meeting;
use App\Models\FeedbackQuestion;
use App\Models\User;
use Carbon\Carbon;

class UpdateMeetingTest extends TestCase
{
    use RefreshDatabase;

    public function testMeetingCanBeUpdated()
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
        
        $response = $this->put(route('meetings.update',[
            'name' => 'New Name',
            'meeting_start' => '2012-01-01T00:00',
            'meeting_end' => '2012-01-09T00:00',
            $meeting,
        ]));
        
        $response->assertStatus(302);
        $this->assertEquals('New Name', $meeting->fresh()->name);
        $this->assertEquals('2012-01-01T00:00',$meeting->fresh()->meeting_start);
        $this->assertEquals('2012-01-09T00:00',$meeting->fresh()->meeting_end);
        $response->assertRedirect(route('meetings.show', $meeting));
        
    }

    public function testDoNotUpdateMeetingIfNotMeetingCreator()
    {   
        $user = User::factory()->create();
        $this->withoutExceptionHandling();

        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2012-01-01T00:00';
        $meeting->meeting_end ='2012-01-09T00:00';
        $meeting->user_id =$user->id;
        $meeting->meeting_reference ='QWERTYU';
        $meeting->save();
        
        $this->actingAs($user2 = User::factory()->create());

        $response = $this->put(route('meetings.update',[
            'name' => 'New Name',
            'meeting_start' => '2012-01-01T00:00',
            'meeting_end' => '2012-01-09T00:00',
            $meeting,
        ]));
        
        $response->assertStatus(302);
        $this->assertEquals('workshop', $meeting->fresh()->name);
        $this->assertEquals('2012-01-01T00:00',$meeting->fresh()->meeting_start);
        $this->assertEquals('2012-01-09T00:00',$meeting->fresh()->meeting_end);
        $response->assertRedirect(route('meetings.index'));
        
    }

    public function testIfToBeUpdatedMeetingNotInDatabaseThenRedirectToIndex()
    {   
        $this->actingAs($user = User::factory()->create());;
        
        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2012-01-01T00:00';
        $meeting->meeting_end ='2012-01-09T00:00';
        $meeting->user_id = $user->id;
        $meeting->meeting_reference ='QWERTYU';

        $response = $this->put(route('meetings.update',[
            'name' => 'New Name',
            'meeting_start' => '2012-01-01T00:00',
            'meeting_end' => '2012-01-09T00:00',
            $meeting,
        ]));

        $response->assertRedirect(route('meetings.index'));
    }

}
