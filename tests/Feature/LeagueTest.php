<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\League;

class LeagueTest extends TestCase
{
    use DatabaseMigrations;
    
    /**
     * @test
     */
    public function it_displays_a_list_of_leagues()
    {
        // Given we have a league
        $league = create(League::class);

        // and we visit the leagues URL ...
        $request = $this->get('/leagues');

        // ... we see it on the page
        $request->assertSee($league->name);
    }

    /**
     * @test
     */
    public function a_new_league_can_be_created()
    {
        // Given we're signed in ...
        $this->signIn();

        // ... and we try to create a new league ...
        $league = make(League::class)->toArray();
        $this->post('/leagues', $league);

        // ... the new league is created
        $this->assertDatabaseHas('leagues', $league);
    }

    /**
     * @test
     */
    public function the_user_must_be_logged_in_to_create_a_league()
    {
        // Given we're not signed in ...
        // $this->signIn();

        $this->withExceptionHandling();

        // ... and we try to create a new league ...
        $league = make(League::class)->toArray();
        $request = $this->post('/leagues', $league);

        // ... we should be redirected to the login page
        $request->assertRedirect('/login');
    }
}
