<?php

namespace Tests\Unit;

#use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\MyClasses\Room;

class RoomTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */


    public function test_the_application_returns_a_successful_response(){
        $response = $this->get('/arcade');
        $response->assertStatus(200);
    }

    public function test_room_has()
    {
        $room = new Room(["Jack", "Peter", "Amy"]); // Create a new room
        $this->assertTrue($room->has("Jack")); // Expect true
        $this->assertFalse($room->has("Eric")); // Expect false
    }

    public function test_room_add(){
        $room = new Room(["Jack"]); // Create a new room
        $this->assertContains("Peter", $room->add("Peter"));
    }

    public function test_room_remove(){
        $room = new Room(["Jack", "Peter"]); // Create a new room
        $this->assertCount(1, $room->remove("Peter"));
    }

    
}
