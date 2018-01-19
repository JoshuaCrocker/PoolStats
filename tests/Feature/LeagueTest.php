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

    /**
     * @test
     */
    public function a_new_league_must_have_a_name()
    {
        // Given we're signed in ...
        $this->signIn();
        $this->withExceptionHandling();

        // ... and we have a league with an empty name ...
        $league = make(
            League::class,
            ['name' => '']
        )->toArray();

        // ... which is posted to the create endpoint ...
        $response = $this->post('/leagues', $league);

        // ... there should be an error in the session
        $response->assertSessionHasErrors('name');
    }
    
    /**
     * @test
     */
    public function a_league_can_be_updated()
    {
        // Given we're signed in ...
        $this->signIn();

        // ... and we have a league ...
        $league = create(League::class);

        // ... and we update the name
        $updateData = [
            'name' => 'Updated Name'
        ];

        $this->patch($league->endpoint(), $updateData);

        // ... the name changes in the database
        $this->assertDatabaseHas('leagues', $updateData);
    }

    /**
     * @test
     */
    public function the_user_muser_be_logged_in_to_update_a_league()
    {
        $this->withExceptionHandling();

        // Given we're not signed in ...
        // $this->signIn();

        // ... and we have a league ...
        $league = create(League::class);

        // ... and we update the name
        $updateData = [
            'name' => 'Updated Name'
        ];

        $response = $this->patch($league->endpoint(), $updateData);

        // ... the user is redirected to the login page ...
        $response->assertRedirect('/login');

        // ... and the record isn't updated
        $this->assertDatabaseMissing('leagues', $updateData);
    }

    /**
     * @test
     */
    public function when_updating_a_league_requires_a_name()
    {
        $this->withExceptionHandling();

        // Given we're signed in ...
        $this->signIn();

        // ... and we have a league ...
        $league = create(League::class);

        // ... and we update the name to be blank
        $updateData = [
            'name' => ''
        ];

        $request = $this->patch($league->endpoint(), $updateData);

        // ... we should get an error ...
        $request->assertSessionHasErrors('name');

        // ... and the database shouldn't be updated
        $this->assertDatabaseMissing('leagues', $updateData);
    }

    /**
     * @test
     */
    public function a_team_can_be_deleted()
    {
        // Given we're signed in ...
        $this->signIn();

        // ... and we have a league ...
        $league = create(League::class);

        // ... and we hit the delete endpoint ...
        $this->delete($league->endpoint());

        // ... the league is deleted
        $this->assertDatabaseMissing('leagues', $league->toArray());
    }

    /**
     * @test
     */
    public function the_user_must_be_logged_in_to_delete_a_league()
    {
        $this->withExceptionHandling();

        // Given we're not signed in ...
        // $this->signIn();

        // ... and we have a league ...
        $league = create(League::class);

        // ... and we hit the delete endpoint ...
        $response = $this->delete($league->endpoint());

        // ... the user is redirected to the login page ...
        $response->assertRedirect('/login');

        // ... and the league is not deleted
        $this->assertDatabaseHas('leagues', $league->toArray());
    }
}
