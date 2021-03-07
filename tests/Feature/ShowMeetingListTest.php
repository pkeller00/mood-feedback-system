<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Jetstream\Features;

use App\Models\Meeting;
use App\Models\FeedbackQuestion;
use App\Models\User;

class ShowMeetingListTest extends TestCase
{
    use RefreshDatabase;

    public function testMeetingsAreLoaded()
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

        $meeting2 = new Meeting();
        $meeting2->name ='Lecture';
        $meeting2->meeting_start ='2020-01-01T00:00';
        $meeting2->meeting_end ='2020-01-09T00:00';
        $meeting2->user_id =$user->id;
        $meeting2->meeting_reference ='QWERTYI';
        $meeting2->save();

        $response = $this->get('/events');

        $content = $response->getOriginalContent()->getData()['page']['props']['meetings'];

        $this->assertEquals('QWERTYI', $content[0]['meeting_reference']);
        $this->assertEquals('QWERTYU', $content[1]['meeting_reference']);
        
    }

    public function testOnlyMeetingsOwnedByUserAreLoaded()
    {   
        $user_other = User::factory()->create();
        $meeting_not_owned = new Meeting();
        $meeting_not_owned->name ='workshop';
        $meeting_not_owned->meeting_start ='2012-01-01T00:00';
        $meeting_not_owned->meeting_end ='2012-01-09T00:00';
        $meeting_not_owned->user_id =$user_other->id;
        $meeting_not_owned->meeting_reference ='ASDFGHJ';
        $meeting_not_owned->save();

        $this->actingAs($user = User::factory()->create());

        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2012-01-01T00:00';
        $meeting->meeting_end ='2012-01-09T00:00';
        $meeting->user_id =$user->id;
        $meeting->meeting_reference ='QWERTYU';
        $meeting->save();

        $meeting2 = new Meeting();
        $meeting2->name ='Lecture';
        $meeting2->meeting_start ='2020-01-01T00:00';
        $meeting2->meeting_end ='2020-01-09T00:00';
        $meeting2->user_id =$user->id;
        $meeting2->meeting_reference ='QWERTYI';
        $meeting2->save();

        $response = $this->get('/events');

        $content = $response->getOriginalContent()->getData()['page']['props']['meetings'];

        $this->assertEquals('QWERTYI', $content[0]['meeting_reference']);
        $this->assertEquals('QWERTYU', $content[1]['meeting_reference']);
        $this->assertArrayNotHasKey(2, $content); 
        
    }

}
