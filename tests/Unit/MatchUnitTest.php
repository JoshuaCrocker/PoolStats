<?php

namespace Tests\Unit;

use App\LeagueMatch;
use App\Team;
use App\Venue;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MatchUnitTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_can_generate_the_match_name()
    {
        // Given we have a match ...
        $venue = create(Venue::class);
        $home = create(Team::class);
        $away = create(Team::class);

        $match = create(LeagueMatch::class, [
            'venue_id' => $venue->id,
            'home_team_id' => $home->id,
            'away_team_id' => $away->id
        ]);

        // ... we can generate the name
        $this->assertEquals(
            $venue->name . ' (' . $home->name . ' vs. ' . $away->name . ')',
            $match->name
        );

    }
}
