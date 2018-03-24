<?php

namespace Tests\Feature;

use App\League;
use App\LeagueFrame;
use App\LeagueFramePlayer;
use App\LeagueMatch;
use App\Player;
use App\PlayerTeam;
use App\Team;
use App\TeamVenue;
use App\Venue;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MatchTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_displays_a_list_of_matches()
    {
        // Given we have a match ...
        $match = create(LeagueMatch::class);

        // ... and we visit the matches URL ...
        $request = $this->get('/matches');

        // ... we gee it on the page
        $request->assertSee(e($match->name));
    }

    /** @test */
    public function a_new_match_can_be_created()
    {
        // Given we're signed in ...
        $this->signIn();

        // ... and we try to create a new match ...
        $match = make(LeagueMatch::class)->toArray();
        $this->post('/matches', $match);

        // ... the new match is created
        $this->assertDatabaseHas('league_matches', $match);
    }

    /** @test */
    public function a_new_match_requires_a_valid_league_id()
    {
        $this->withExceptionHandling();

        // Given we're signed in ...
        $this->signIn();

        // ... and we try to create a new match ...
        $match = make(LeagueMatch::class)->toArray();

        $match['league_id'] = 999; // an id which doesn't exist

        $request = $this->post('/matches', $match);

        // ... the new match is not created
        $request->assertSessionHasErrors('league_id');
        $this->assertDatabaseMissing('league_matches', $match);
    }

    /** @test */
    public function a_new_match_requires_a_valid_home_team_id()
    {
        $this->withExceptionHandling();

        // Given we're signed in ...
        $this->signIn();

        // ... and we try to create a new match ...
        $match = make(LeagueMatch::class)->toArray();

        $match['home_team_id'] = 999; // an id which doesn't exist

        $request = $this->post('/matches', $match);

        // ... the new match is not created
        $request->assertSessionHasErrors('home_team_id');
        $this->assertDatabaseMissing('league_matches', $match);
    }

    /** @test */
    public function a_new_match_requires_a_valid_away_team_id()
    {
        $this->withExceptionHandling();

        // Given we're signed in ...
        $this->signIn();

        // ... and we try to create a new match ...
        $match = make(LeagueMatch::class)->toArray();

        $match['away_team_id'] = 999; // an id which doesn't exist

        $request = $this->post('/matches', $match);

        // ... the new match is not created
        $request->assertSessionHasErrors('away_team_id');
        $this->assertDatabaseMissing('league_matches', $match);
    }

    /** @test */
    public function a_new_match_requires_a_valid_match_date()
    {
        $this->withExceptionHandling();

        // Given we're signed in ...
        $this->signIn();

        // ... and we try to create a new match ...
        $match = make(LeagueMatch::class)->toArray();

        $match['match_date'] = '21/21/21'; // an id which doesn't exist

        $request = $this->post('/matches', $match);

        // ... the new match is not created
        $request->assertSessionHasErrors('match_date');
        $this->assertDatabaseMissing('league_matches', $match);
    }

    /** @test */
    public function the_user_must_be_logged_in_to_create_a_match()
    {
        // Given we're not signed in ...
        // $this->signIn();

        $this->withExceptionHandling();

        // ... and we try to create a new match
        $match = make(LeagueMatch::class)->toArray();
        $request = $this->post('/matches', $match);

        // ... we should be redirected to the login page
        $request->assertRedirect('/login');
    }

    /** @test */
    public function the_user_can_view_the_match_details()
    {
        // Given we have a match
        $match = create(LeagueMatch::class);

        // and we GET its endpoint
        $response = $this->get($match->endpoint());

        // the match details should be displayed
        $response->assertSee(e($match->name));
    }

    /**
     * @test
     */
    public function it_shows_details_about_single_frames()
    {
        // Given we have:
        // - Two players
        $playerHome = create(Player::class);
        $playerAway = create(Player::class);

        // -- who are members of separate teams
        $teamHome = create(Team::class);
        $teamAway = create(Team::class);

        create(PlayerTeam::class, [
            'team_id' => $teamHome->id,
            'player_id' => $playerHome->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => Carbon::parse('+1 day')
        ]);

        create(PlayerTeam::class, [
            'team_id' => $teamAway->id,
            'player_id' => $playerAway->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => Carbon::parse('+1 day')
        ]);

        // - One Match
        $match = create(LeagueMatch::class, [
            'home_team_id' => $playerHome->team->id,
            'away_team_id' => $playerAway->team->id
        ]);

        // - One Frame (single)
        $frame = create(LeagueFrame::class, [
            'league_match_id' => $match->id,
            'frame_number' => 1,
            'doubles' => FALSE
        ]);

        // And we add the players to the frame
        $framePlayerHome = create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $playerHome->id,
            'winner' => TRUE
        ]);

        $framePlayerAway = create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $playerAway->id,
            'winner' => FALSE
        ]);

        // And we view the match endpoint
        $request = $this->get($match->endpoint());

        // We see details about the frame
        $request->assertSee(e($playerHome->name));
        $request->assertSee(e($playerAway->name));
    }


    /**
     * @test
     */
    public function it_shows_details_about_doubles_frames()
    {
        // Given we have:
        // - Four players
        $playerHome1 = create(Player::class);
        $playerHome2 = create(Player::class);

        $playerAway1 = create(Player::class);
        $playerAway2 = create(Player::class);

        // -- who are members of separate teams
        $teamHome = create(Team::class);
        $teamAway = create(Team::class);

        create(PlayerTeam::class, [
            'team_id' => $teamHome->id,
            'player_id' => $playerHome1->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => Carbon::parse('+1 day')
        ]);

        create(PlayerTeam::class, [
            'team_id' => $teamHome->id,
            'player_id' => $playerHome2->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => Carbon::parse('+1 day')
        ]);

        create(PlayerTeam::class, [
            'team_id' => $teamAway->id,
            'player_id' => $playerAway1->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => Carbon::parse('+1 day')
        ]);

        create(PlayerTeam::class, [
            'team_id' => $teamAway->id,
            'player_id' => $playerAway2->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => Carbon::parse('+1 day')
        ]);

        // - One Match
        $match = create(LeagueMatch::class, [
            'home_team_id' => $teamHome->id,
            'away_team_id' => $teamAway->id
        ]);

        // - One Frame (doubles)
        $frame = create(LeagueFrame::class, [
            'league_match_id' => $match->id,
            'frame_number' => 1,
            'doubles' => TRUE
        ]);

        // And we add the players to the frame
        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $playerHome1->id,
            'winner' => TRUE
        ]);

        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $playerHome2->id,
            'winner' => TRUE
        ]);

        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $playerAway1->id,
            'winner' => FALSE
        ]);

        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $playerAway2->id,
            'winner' => FALSE
        ]);

        // And we view the match endpoint
        $request = $this->get($match->endpoint());

        // We see details about the frame
        $homePlayers = e($playerHome1->name . ' & ' . $playerHome2->name);
        $awayPlayers = e($playerAway1->name . ' & ' . $playerAway2->name);

        $request->assertSee($homePlayers);
        $request->assertSee($awayPlayers);
    }

    /**
     * @test
     */
    public function the_user_can_edit_a_match()
    {
        // Given we are logged in
        $this->signIn();

        // and we have a match
        $match = create(LeagueMatch::class);

        // and we update it at its endpoint
        $payload = [
            'match_date' => Carbon::parse("+2 days")->setTime(0, 0, 0)->toDateTimeString(),
            'venue_id' => create(Venue::class)->id,
            'league_id' => create(League::class)->id
        ];

        $this->patch($match->endpoint(), $payload);

        // the changes should be apparent in the database
        $this->assertDatabaseHas('league_matches', $payload);
    }

    /** @test */
    public function the_user_must_be_logged_in_to_edit_a_match()
    {
        // Given we're not signed in ...
        // $this->signIn();

        $this->withExceptionHandling();

        // and we have a match
        $match = create(LeagueMatch::class);

        // and we update it at its endpoint
        $payload = [
            'match_date' => Carbon::parse("+2 days")->setTime(0, 0, 0)->toDateTimeString(),
            'venue_id' => create(Venue::class)->id,
            'league_id' => create(League::class)->id
        ];

        $request = $this->patch($match->endpoint(), $payload);

        // ... we should be redirected to the login page
        $request->assertRedirect('/login');
    }

    /** @test */
    public function an_updated_match_requires_a_valid_league_id()
    {
        $this->withExceptionHandling();

        // Given we're signed in ...
        $this->signIn();

        // and we have a match
        $match = create(LeagueMatch::class);

        // and we update it at its endpoint
        $payload = [
            'match_date' => $match->match_date,
            'venue_id' => $match->venue_id,
            'league_id' => 999
        ];

        $request = $this->patch($match->endpoint(), $payload);

        // ... the update fails
        $request->assertSessionHasErrors('league_id');
    }

    /** @test */
    public function an_updated_match_requires_a_valid_match_date()
    {
        $this->withExceptionHandling();

        // Given we're signed in ...
        $this->signIn();

        // and we have a match
        $match = create(LeagueMatch::class);

        // and we update it at its endpoint
        $payload = [
            'match_date' => '21/21/21',
            'venue_id' => $match->venue_id,
            'league_id' => $match->league_id
        ];

        $request = $this->patch($match->endpoint(), $payload);

        // ... the new match is not created
        $request->assertSessionHasErrors('match_date');
    }

    /**
     * @test
     */
    public function the_default_venue_can_be_determined()
    {
        // Given we're signed in ...
        $this->signIn();

        // ... and we try to create a new match (with no venue_id) ...
        $league = create(League::class);

        $venueHome = create(Venue::class);
        $venueAway = create(Venue::class);

        $teamHome = create(Team::class);
        $teamAway = create(Team::class);

        create(TeamVenue::class, [
            'team_id' => $teamHome->id,
            'venue_id' => $venueHome->id,
            'venue_from' => Carbon::parse('-1 day'),
            'venue_to' => null
        ]);

        create(TeamVenue::class, [
            'team_id' => $teamAway->id,
            'venue_id' => $venueAway->id,
            'venue_from' => Carbon::parse('-1 day'),
            'venue_to' => null
        ]);

        $payload = [
            'match_date' => Carbon::now(),
            'home_team_id' => $teamHome->id,
            'away_team_id' => $teamAway->id,
            'league_id' => $league->id
        ];

        $this->post('/matches', $payload);

        // The home team's venue is chosen
        $match = $payload;
        $match['venue_id'] = $venueHome->id;

        $this->assertDatabaseHas('league_matches', $match);
    }

    /**
     * @test
     */
    public function an_existing_match_cannot_have_the_teams_changed()
    {
        // Given we are logged in
        $this->signIn();

        // and we have a match
        $match = create(LeagueMatch::class);

        // and we update it at its endpoint
        $payload = [
            'match_date' => Carbon::parse("+2 days")->setTime(0, 0, 0)->toDateTimeString(),
            'venue_id' => create(Venue::class)->id,
            'home_team_id' => create(Team::class)->id,
            'away_team_id' => create(Team::class)->id,
            'league_id' => create(League::class)->id
        ];

        $this->patch($match->endpoint(), $payload);

        // the changes should be apparent in the database
        $merged = [
            'match_date' => Carbon::parse("+2 days")->setTime(0, 0, 0)->toDateTimeString(),
            'venue_id' => $payload['venue_id'],
            'home_team_id' => $match->home_team_id,
            'away_team_id' => $match->away_team_id,
            'league_id' => $payload['league_id']
        ];

        $this->assertDatabaseHas('league_matches', $merged);
        $this->assertDatabaseMissing('league_matches', $payload);
    }

    /**
     * @test
     */
    public function the_user_can_delete_a_match()
    {
        // Given we are logged in
        $this->signIn();

        // and we have a match
        $match = create(LeagueMatch::class);

        // and the delete endpoint is hit
        $this->delete($match->endpoint());

        // the match is deleted
        $this->assertDatabaseMissing('league_matches', $match->toArray());
    }

    /**
     * @test
     */
    public function a_frame_cannot_pair_the_same_team()
    {
        // Given we're signed in ...
        $this->signIn();
        $this->withExceptionHandling();

        // and we try to create a match,
        // where the same team is both home and away
        $team = create(Team::class);

        $match = make(LeagueMatch::class, [
            'home_team_id' => $team->id,
            'away_team_id' => $team->id
        ])->toArray();

        $request = $this->post('/matches', $match);

        // the record isn't added
        $request->assertSessionHasErrors('home_team_id');
        $request->assertSessionHasErrors('away_team_id');
        $this->assertDatabaseMissing('league_matches', $match);
    }

    // Historic Data
    // TODO working with historic data
    // All data needs to be current at the time of the match
}
