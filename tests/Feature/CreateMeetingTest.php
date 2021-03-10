<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Jetstream\Features;

use App\Models\Meeting;
use App\Models\FeedbackQuestion;
use App\Models\User;
use Carbon\Carbon;

class CreateMeetingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Can store if all valid
    */

    public function testCanStoreMeetingIfValidMeetingAndValidForm()
    {   

        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2020-01-01T00:00';
        $meeting->meeting_end ='2020-01-09T00:00';

        $this->actingAs($user = User::factory()->create());

        $this->withSession(compact('meeting'));
        $response = $this->post('/events/create/form',[
            'questions' => [['question' => 'Is it a question?','question_type' => '3']],
        ]);
        $this->assertCount(1,Meeting::all());
        $this->assertCount(1,FeedbackQuestion::all());
    }

    /**
     * Test meeting data
    */
    public function testRaiseExceptionIfAMeetingDateIsWrongDataType()
    {
        $this->actingAs($user = User::factory()->create());
        
        $name = "workshop";
        
        $response = $this->post('/events/create', [
            'name' => $name,
            'meeting_start' => 'This is a string',
            'meeting_end' => '2020-01-09T00:00'
        ]);
        $response->assertSessionHasErrors();
    }

    public function testRaiseExceptionIfAMeetingDateIsEmpty()
    {
        $this->actingAs($user = User::factory()->create());
        
        $name = "workshop";
        
        $response = $this->post('/events/create', [
            'name' => $name,
            'meeting_start' => '',
            'meeting_end' => '2020-01-09T00:00'
        ]);
        $response->assertSessionHasErrors();
    }
    
    public function testDoNotRaiseExceptionIfMeetingFinishDateBiggerThanStartDate()
    {
        $this->actingAs($user = User::factory()->create());

        $response = $this->post('/events/create', [
            'name' => 'workshop',
            'meeting_start' => '2020-01-01T00:00',
            'meeting_end' => '2020-01-09T00:00'
        ]);
        $response->assertSessionHasNoErrors();
    }

    public function testRaiseExceptionIfMeetingFinishDateSmallerThanStartDate()
    {
        $this->actingAs($user = User::factory()->create());

        $response = $this->post('/events/create', [
            'name' => 'workshop',
            'meeting_start' => '2020-01-09T00:00',
            'meeting_end' => '2020-01-01T00:00',
        ]);
        $response->assertSessionHasErrors();
    }

    public function testRaiseExceptionIfMeetingFinishDateEqualStartDate()
    {
        $this->actingAs($user = User::factory()->create());

        $response = $this->post('/events/create', [
            'name' => 'workshop',
            'meeting_start' => '2020-01-01T00:00',
            'meeting_end' => '2020-01-01T00:00',
        ]);
        $response->assertSessionHasErrors();
    }

    public function testRaiseExceptionIfEmptyMeetingName()
    {
        $this->actingAs($user = User::factory()->create());

        $response = $this->post('/events/create', [
            'name' => '',
            'meeting_start' => '2020-01-01T00:00',
            'meeting_end' => '2020-01-09T00:00'
        ]);
        $response->assertSessionHasErrors();
    }

    public function testDoNotRaiseExceptionIfMeetingNameHasLessThan255Characters()
    {
        $this->actingAs($user = User::factory()->create());
        
        $name = "Lorem ipsum dolor";
        
        $response = $this->post('/events/create', [
            'name' => $name,
            'meeting_start' => '2020-01-01T00:00',
            'meeting_end' => '2020-01-09T00:00'
        ]);
        $response->assertSessionHasNoErrors();
    }

    public function testRaiseExceptionIfMeetingNameMoreThan255Characters()
    {
        $this->actingAs($user = User::factory()->create());       
        
        $name = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in. ";
        
        $response = $this->post('/events/create', [
            'name' => $name,
            'meeting_start' => '2020-01-01T00:00',
            'meeting_end' => '2020-01-09T00:00'
        ]);
        $response->assertSessionHasErrors();
    }

    public function testDoNotRaiseExceptionIfMeetingNameHas255Characters()
    {
        $this->actingAs($user = User::factory()->create());
        
        $name = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor .";
        
        $response = $this->post('/events/create', [
            'name' => $name,
            'meeting_start' => '2020-01-01T00:00',
            'meeting_end' => '2020-01-09T00:00'
        ]);
        $response->assertSessionHasNoErrors();
    }

    /**
     *Test meeting form 
     *Asummes meeting info (e.g. name etc) is valid
    */
    public function testStoreMeetingAndItsMultipleQuestionObjects()
    {

        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2020-01-01T00:00';
        $meeting->meeting_end ='2020-01-09T00:00';

        $this->actingAs($user = User::factory()->create());

        $this->withSession(compact('meeting'));
        $response = $this->post('/events/create/form',[
            'questions' => [
                ['question' => 'Question 1','question_type' => '3'],
                ['question' => 'Question 2','question_type' => '3'],
                ['question' => 'Question 3','question_type' => '3'],
                ['question' => 'Question 4','question_type' => '3'],
            ],
        ]);
        $this->assertCount(1,Meeting::all());
        $this->assertCount(4,FeedbackQuestion::all());
    }

    public function testDoNotStoreMeetingWithNoQuestionObjects()
    {
        
        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2020-01-01T00:00';
        $meeting->meeting_end ='2020-01-09T00:00';

        $this->actingAs($user = User::factory()->create());

        $this->withSession(compact('meeting'));
        $response = $this->post('/events/create/form',[]);
        $this->assertCount(0,Meeting::all());
        $this->assertCount(0,FeedbackQuestion::all());
    }

    public function testDoNotStoreMeetingWithMissingQuestionTypeAttribute()
    {
        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2020-01-01T00:00';
        $meeting->meeting_end ='2020-01-09T00:00';

        $this->actingAs($user = User::factory()->create());

        $this->withSession(compact('meeting'));
        $response = $this->post('/events/create/form',[
            'questions' => [['question' => 'Question 1?']],
        ]);
        $this->assertCount(0,Meeting::all());
        $this->assertCount(0,FeedbackQuestion::all());
    }

    public function testDoNotStoreMeetingWithMissingQuestionTextAttribute()
    {
        $this->withoutExceptionHandling();
        
        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2020-01-01T00:00';
        $meeting->meeting_end ='2020-01-09T00:00';

        $this->actingAs($user = User::factory()->create());

        $this->withSession(compact('meeting'));
        $response = $this->post('/events/create/form',[
            'questions' => [
                ['type' => '3'],
            ],
        ]);
        $this->assertCount(0,Meeting::all());
        $this->assertCount(0,FeedbackQuestion::all());
    }

    public function testDoNotStoreMeetingWithNoQuestionTextAndTypeAttributes()
    {

        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2020-01-01T00:00';
        $meeting->meeting_end ='2020-01-09T00:00';

        $this->actingAs($user = User::factory()->create());

        $this->withSession(compact('meeting'));
        $response = $this->post('/events/create/form',[
            'questions' => '',
        ]);
        $this->assertCount(0,Meeting::all());
        $this->assertCount(0,FeedbackQuestion::all());
    }

    public function testDoNotStoreMeetingWithInvalidQuestionTypeValue()
    {
        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2020-01-01T00:00';
        $meeting->meeting_end ='2020-01-09T00:00';

        $this->actingAs($user = User::factory()->create());

        $this->withSession(compact('meeting'));
        $response = $this->post('/events/create/form',[
            ['question' => 'Question 1','question_type' => '5'],
        ]);
        $this->assertCount(0,Meeting::all());
        $this->assertCount(0,FeedbackQuestion::all());
    }
    public function testDoNotStoreMeetingWithEmptyQuestionTextValue()
    {

        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2020-01-01T00:00';
        $meeting->meeting_end ='2020-01-09T00:00';

        $this->actingAs($user = User::factory()->create());

        $this->withSession(compact('meeting'));
        $response = $this->post('/events/create/form',[
            ['question' => '','question_type' => '5'],
        ]);
        $this->assertCount(0,Meeting::all());
        $this->assertCount(0,FeedbackQuestion::all());
    }
    public function testDoNotStoreMeetingWithEmptyQuestionTypeValue()
    {
        $meeting = new Meeting();
        $meeting->name ='workshop';
        $meeting->meeting_start ='2020-01-01T00:00';
        $meeting->meeting_end ='2020-01-09T00:00';

        $this->actingAs($user = User::factory()->create());

        $this->withSession(compact('meeting'));
        $response = $this->post('/events/create/form',[
            ['question' => 'Question 1','question_type' => ''],
        ]);
        $this->assertCount(0,Meeting::all());
        $this->assertCount(0,FeedbackQuestion::all());
    }

}
