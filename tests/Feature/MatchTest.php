<?php

namespace Tests\Feature;

use App\LeagueMatch;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $request->assertSee($match->name);
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
        $response->assertSee($match->name);
    }
}
