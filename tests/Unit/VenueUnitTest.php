<?php

namespace Tests\Unit;

use App\Venue;
use App\Team;
use App\TeamVenue;
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
    public function it_can_generate_its_endpoint()
    {
        $venue = create(Venue::class);

        $this->assertEquals(
            'venues/' . $venue->id,
            $venue->endpoint()
        );
    }

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

    /**
     * @test
     */
    public function it_can_get_its_current_teams()
    {
        $venue = create(Venue::class);

        $team1 = create(Team::class);
        $team2 = create(Team::class);

        $link1 = create(TeamVenue::class, [
            'team_id' => $team1->id,
            'venue_id' => $venue->id,
            'venue_from' => Carbon::parse('-1 day')
        ]);

        $link2 = create(TeamVenue::class, [
            'team_id' => $team2->id,
            'venue_id' => $venue->id,
            'venue_from' => Carbon::parse('-1 day'),
            'venue_to' => Carbon::parse('+1 day')
        ]);

        $this->assertEquals(
            2,
            $venue->currentTeams->count()
        );
    }

    /**
     * @test
     */
    public function it_can_get_its_historic_teams()
    {
        $venue = create(Venue::class);

        $team1 = create(Team::class);
        $team2 = create(Team::class);

        create(TeamVenue::class, [
            'team_id' => $team1->id,
            'venue_id' => $venue->id,
            'venue_from' => Carbon::parse('-1 day')
        ]);

        $link = create(TeamVenue::class, [
            'team_id' => $team2->id,
            'venue_id' => $venue->id,
            'venue_from' => Carbon::parse('-1 day'),
            'venue_to' => Carbon::parse('now')
        ]);

        $this->assertEquals(
            1,
            $venue->historicTeams->count()
        );
    }
}
