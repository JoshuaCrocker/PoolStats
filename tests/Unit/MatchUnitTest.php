<?php

namespace Tests\Unit;

use App\LeagueFrame;
use App\LeagueMatch;
use App\Team;
use App\Venue;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MatchUnitTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_can_generate_its_endpoint() {
        // Given we have a Match
        $match = create(LeagueMatch::class);

        // it can generate its endpoint
        $this->assertEquals(
            $match->endpoint(),
            '/matches/1'
        );
    }

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

    /**
     * @test
     */
    public function it_can_retrieve_its_frames()
    {
        // Given we have a match
        $match = create(LeagueMatch::class);

        // with some frames
        $frame1 = create(LeagueFrame::class, [
            'league_match_id' => $match->id
        ]);

        $frame2 = create(LeagueFrame::class, [
            'league_match_id' => $match->id
        ]);

        // it can retrieve a collection of its frames
        $this->assertEquals(2, $match->frames->count());

        $this->assertEquals($frame1->frame_number, $match->frames[0]->frame_number);
        $this->assertEquals($frame1->doubles, $match->frames[0]->doubles);

        $this->assertEquals($frame2->frame_number, $match->frames[1]->frame_number);
        $this->assertEquals($frame2->doubles, $match->frames[1]->doubles);
    }
}
