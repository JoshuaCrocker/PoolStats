<?php

namespace Tests\Feature;

use App\LeagueMatch;
use App\Venue;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VenueTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_shows_a_list_of_venues()
    {
        $venue = create(Venue::class);

        $request = $this->get('venues');

        $request->assertSee(e($venue->name));
    }

    /**
     * @test
     */
    public function the_user_can_create_a_venue()
    {
        $this->signIn();
        $venue = make(Venue::class)->toArray();
        $this->post('venues', $venue);
        $this->assertDatabaseHas('venues', $venue);
    }

    /**
     * @test
     */
    public function the_user_must_be_logged_in_to_create_a_venue()
    {
        // Given we're not signed in ...
        // $this->>signIn();

        $this->withExceptionHandling();

        $venue = make(Venue::Class)->toArray();
        $request = $this->post('venues', $venue);

        // ... we should be redirected to the login page
        $request->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function a_new_venue_requires_a_name()
    {
        $this->signIn();
        $this->withExceptionHandling();

        $venue = [
            'name' => ''
        ];

        $request = $this->post('venues', $venue);
        $request->assertSessionHasErrors('name');
        $this->assertDatabaseMIssing('venues', $venue);
    }

    /**
     * @test
     */
    public function the_user_can_update_a_venue()
    {
        $this->signIn();

        $venue = create(Venue::class);

        $update = [
            'name' => 'Updated Name'
        ];

        $this->patch(
            route('venues.update', $venue),
            $update
        );

        $update['id'] = $venue->id;

        $this->assertDatabaseHas('venues', $update);
    }

    /**
     * @test
     */
    public function the_user_must_be_logged_in_to_update_a_venue()
    {
        // Given we're not signed in ...
        // $this->signIn();

        $this->withExceptionHandling();

        $venue = create(Venue::class);

        $update = [
            'name' => 'Updated Name'
        ];

        $request = $this->patch(route('venues.update', $venue), $update);

        // ... we should be redirected to the login page
        $request->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function an_updated_venue_requires_a_name()
    {
        $this->signIn();
        $this->withExceptionHandling();

        $venue = create(Venue::class);

        $update = [
            'name' => ''
        ];

        $request = $this->patch(route('venues.update', $venue), $update);
        $request->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('venues', $update);
    }

    /**
     * @test
     */
    public function the_user_can_delete_a_venue()
    {
        $this->signIn();

        $venue = create(Venue::class);

        $this->delete(route('venues.destroy', $venue));

        $this->assertSoftDeleted('venues', $venue->toArray());
    }

    /**
     * @test
     */
    public function the_user_must_be_signed_in_to_delete_a_venue()
    {
        $this->withExceptionHandling();

        $venue = create(Venue::class);

        $request = $this->delete(route('venues.destroy', $venue));

        $request->assertRedirect('/login');
        $this->assertDatabaseHas('venues', $venue->toArray());
    }

    /**
     * @test
     */
    public function the_venue_page_displays_the_venue_details()
    {
        $this->signIn();

        $venue = create(Venue::class);

        $request = $this->get(route('venues.show', $venue));

        $request->assertSee(e($venue->name));
    }

    /**
     * @test
     */
    public function the_venue_displays_its_upcoming_matches()
    {
        $this->signIn();

        $group = $this->teamWithVenue();

        $venue = $group['venue'];
        $team = $group['team'];

        $match = create(LeagueMatch::class, [
            'match_date' => Carbon::parse('+1 week'),
            'home_team_id' => $team->id
        ]);

        $request = $this->get(route('venues.show', $venue));

        $request->assertSee(
            e($match->homeTeam->name)
            . ' vs. '
            . e($match->awayTeam->name)
        );
    }
}

