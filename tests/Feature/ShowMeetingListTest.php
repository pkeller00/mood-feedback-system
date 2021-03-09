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
        $meeting = Meeting::factory()->create();
        $user = User::find($meeting->user_id);

        $meeting3 = new Meeting();
        $meeting3->name ='workshop';
        $meeting3->meeting_start ='2012-01-01T00:00';
        $meeting3->meeting_end ='2012-01-09T00:00';
        $meeting3->user_id =$user->id;
        $meeting3->meeting_reference ='QWERTYU';
        $meeting3->save();

        $this->actingAs($user);

        $response = $this->get('/events');

        $content = $response->getOriginalContent()->getData()['page']['props']['meetings'];

        $this->assertEquals($meeting->meeting_reference, $content[0]['meeting_reference']);
        $this->assertEquals('QWERTYU', $content[1]['meeting_reference']);
        
    }

    public function testOnlyMeetingsOwnedByUserAreLoaded()
    {   
        $meeting = Meeting::factory()->create();

        $meeting2 = Meeting::factory()->create();
        $user = User::find($meeting2->user_id);

        $meeting3 = new Meeting();
        $meeting3->name ='workshop';
        $meeting3->meeting_start ='2012-01-01T00:00';
        $meeting3->meeting_end ='2012-01-09T00:00';
        $meeting3->user_id =$user->id;
        $meeting3->meeting_reference ='QWERTYU';
        $meeting3->save();

        $this->actingAs($user);
        $response = $this->get('/events');

        $content = $response->getOriginalContent()->getData()['page']['props']['meetings'];

        $this->assertEquals($meeting2->meeting_reference, $content[0]['meeting_reference']);
        $this->assertEquals('QWERTYU', $content[1]['meeting_reference']);
        $this->assertArrayNotHasKey(2, $content); 
        
    }

}
