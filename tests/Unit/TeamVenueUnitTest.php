<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamVenueUnitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_get_its_team()
    {
        $group = $this->teamWithVenue();

        $link = $group['membership'];
        $team = $group['team'];

        $this->assertEquals(
            $team->name,
            $link->team->name
        );
    }

    /**
     * @test
     */
    public function it_can_get_its_venue()
    {
        $group = $this->teamWithVenue();

        $link = $group['membership'];
        $venue = $group['venue'];

        $this->assertEquals(
            $venue->name,
            $link->venue->name
        );
    }
}
