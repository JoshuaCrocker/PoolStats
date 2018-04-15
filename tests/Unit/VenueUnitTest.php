<?php

namespace Tests\Unit;

use App\LeagueMatch;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VenueUnitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_get_the_related_matches()
    {
        $team1 = $this->teamWithVenue();
        $team2 = $this->teamWithVenue();

        $match = create(LeagueMatch::class, [
            'match_date' => Carbon::parse('+1 week'),
            'home_team_id' => $team1['team']->id,
            'away_team_id' => $team2['team']->id
        ]);

        $player1 = $this->playerWithTeam($team1['team'])['player'];
        $player2 = $this->playerWithTeam($team2['team'])['player'];

        $this->frameWithPlayers($match, $player1, $player2);

        $this->assertEquals(1, $team1['venue']->matches->count());
        $this->assertEquals(
            $match->id,
            $team1['venue']->matches->first()->id
        );
    }
}
