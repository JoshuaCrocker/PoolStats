<?php

namespace Tests\Feature;

use App\Venue;
use App\Team;
use App\TeamVenue;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamVenueTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_create_a_membership_between_a_team_and_a_venue()
    {
        $this->signIn();

        $venue = create(Venue::class);
        $team = create(Team::class);

        $payload = [
            'team_id' => $team->id,
            'member_from' => Carbon::parse('-1 day')->format('Y-m-d')
        ];

        $request = $this->post(route('venues.membership.store', $venue), $payload);

        $check = [
            'venue_id' => $venue->id,
            'team_id' => $team->id,
            'venue_from' => $payload['member_from'],
            'venue_to' => NULL
        ];

        $this->assertDatabaseHas('team_venues', $check);
    }

    /**
     * @test
     */
    public function it_requires_a_team_when_creating_a_membership()
    {
        $this->signIn();
        $this->withExceptionHandling();

        $venue = create(Venue::class);

        $payload = [
            'team_id' => -1,
            'member_from' => Carbon::parse('-1 day')->format('Y-m-d')
        ];

        $request = $this->post(route('venues.membership.store', $venue), $payload);

        $check = [
            'venue_id' => $venue->id,
            'team_id' => -1,
            'venue_from' => $payload['member_from'],
            'venue_to' => NULL
        ];

        $request->assertSessionHasErrors('team_id');
        $this->assertDatabaseMissing('team_venues', $check);
    }

    /**
     * @test
     */
    public function it_requires_a_from_date_when_creating_a_membership()
    {
        $this->signIn();
        $this->withExceptionHandling();

        $venue = create(Venue::class);
        $team = create(Team::class);

        $payload = [
            'team_id' => $team->id,
            'member_from' => NULL
        ];

        $request = $this->post(route('venues.membership.store', $venue), $payload);

        $check = [
            'venue_id' => $venue->id,
            'team_id' => $team->id,
            'venue_from' => $payload['member_from'],
            'venue_to' => NULL
        ];

        $request->assertSessionHasErrors('member_from');
        $this->assertDatabaseMissing('team_venues', $check);
    }

    /**
     * @test
     */
    public function it_requires_a_valid_from_date_when_creating_a_membership()
    {
        $this->signIn();
        $this->withExceptionHandling();

        $venue = create(Venue::class);
        $team = create(Team::class);

        $payload = [
            'team_id' => $team->id,
            'member_from' => 'test'
        ];

        $request = $this->post(route('venues.membership.store', $venue), $payload);

        $check = [
            'venue_id' => $venue->id,
            'team_id' => $team->id,
            'venue_from' => $payload['member_from'],
            'venue_to' => NULL
        ];

        $request->assertSessionHasErrors('member_from');
        $this->assertDatabaseMissing('team_venues', $check);
    }

    /**
     * @test
     */
    public function it_requires_a_valid_to_date_when_creating_a_membership()
    {
        $this->signIn();
        $this->withExceptionHandling();

        $venue = create(Venue::class);
        $team = create(Team::class);

        $payload = [
            'team_id' => $team->id,
            'member_from' => Carbon::parse('-1 day')->format('Y-m-d'),
            'member_to' => 'test'
        ];

        $request = $this->post(route('venues.membership.store', $venue), $payload);

        $check = [
            'venue_id' => $venue->id,
            'team_id' => $team->id,
            'venue_from' => $payload['member_from'],
            'venue_to' => $payload['member_to']
        ];

        $request->assertSessionHasErrors('member_to');
        $this->assertDatabaseMissing('team_venues', $check);
    }

    // Venue to must be after venue from
    /**
     * @test
     */
    public function it_requires_the_from_date_to_be_before_the_to_date_when_creating_a_membership()
    {
        $this->signIn();
        $this->withExceptionHandling();

        $venue = create(Venue::class);
        $team = create(Team::class);

        $payload = [
            'team_id' => $team->id,
            'member_from' => Carbon::parse('-1 day')->format('Y-m-d'),
            'member_to' => Carbon::parse('-2 day')->format('Y-m-d')
        ];

        $request = $this->post(route('venues.membership.store', $venue), $payload);

        $check = [
            'venue_id' => $venue->id,
            'team_id' => $team->id,
            'venue_from' => $payload['member_from'],
            'venue_to' => NULL
        ];

        $request->assertSessionHasErrors('member_from');
        $this->assertDatabaseMissing('team_venues', $check);
    }

    /**
     * @test
     */
    public function it_terminates_any_current_memberships_when_a_new_one_is_registered()
    {
        $this->signIn();

        $team = create(Team::class);

        $initial = $this->teamWithVenue($team);

        $venue = create(Venue::class);

        $payload = [
            'team_id' => $team->id,
            'member_from' => Carbon::now()->format('Y-m-d')
        ];

        $request = $this->post(route('venues.membership.store', $venue), $payload);

        $check_old = [
            'venue_id' => $initial['venue']->id,
            'team_id' => $team->id,
            'venue_from' => $initial['membership']['venue_from'],
            'venue_to' => Carbon::now()->format('Y-m-d')
        ];

        $check_new = [
            'venue_id' => $venue->id,
            'team_id' => $team->id,
            'venue_from' => $payload['member_from'],
            'venue_to' => NULL
        ];

        $this->assertDatabaseHas('team_venues', $check_old);
        $this->assertDatabaseHas('team_venues', $check_new);
    }

    /**
     * @test
     */
    public function the_user_can_edit_a_teams_membership()
    {
        $this->signIn();

        // Give we have a membership record
        $joint = $this->teamWithVenue();

        $team = $joint['team'];
        $venue = $joint['venue'];
        $membership = $joint['membership'];

        // and we hit its endpoint with updated dates
        $payload = [
            'member_from' => Carbon::parse('+1 month')->toDateString(),
            'member_to' => Carbon::parse('+2 months')->toDateString()
        ];

        $route = route('venues.membership.update', [
            'venue' => $venue,
            'membership' => $membership
        ]);

        $request = $this->patch($route, $payload);

        $check = [
            'id' => $membership->id,
            'venue_from' => $payload['member_from'],
            'venue_to' => $payload['member_to']
        ];

        // The record is updated
        $this->assertDatabaseHas('team_venues', $check);
    }

    /**
     * @test
     */
    public function an_edited_membership_requires_a_date_from()
    {
        $this->signIn();
        $this->withExceptionHandling();

        // Give we have a membership record
        $joint = $this->teamWithVenue();

        $team = $joint['team'];
        $venue = $joint['venue'];
        $membership = $joint['membership'];

        // and we hit its endpoint with updated dates
        $payload = [
            'member_from' => NULL,
            'member_to' => Carbon::parse('+2 months')->toDateString()
        ];

        $route = route('venues.membership.update', [
            'venue' => $venue,
            'membership' => $membership
        ]);

        $request = $this->patch($route, $payload);

        $check = [
            'id' => $membership->id,
            'venue_from' => $payload['member_from'],
            'venue_to' => $payload['member_to']
        ];

        $request->assertSessionHasErrors('member_from');
        $this->assertDatabaseMissing('team_venues', $check);
    }

    /**
     * @test
     */
    public function an_edited_membership_requires_a_date_from_in_yyyy_mm_dd_format()
    {
        $this->signIn();
        $this->withExceptionHandling();

        // Give we have a membership record
        $joint = $this->teamWithVenue();

        $team = $joint['team'];
        $venue = $joint['venue'];
        $membership = $joint['membership'];

        // and we hit its endpoint with updated dates
        $payload = [
            'member_from' => 'test',
            'member_to' => Carbon::parse('+2 months')->toDateString()
        ];

        $route = route('venues.membership.update', [
            'venue' => $venue,
            'membership' => $membership
        ]);

        $request = $this->patch($route, $payload);

        $check = [
            'id' => $membership->id,
            'venue_from' => $payload['member_from'],
            'venue_to' => $payload['member_to']
        ];

        $request->assertSessionHasErrors('member_from');
        $this->assertDatabaseMissing('team_venues', $check);
    }

    /**
     * @test
     */
    public function an_edited_membership_requires_a_date_from_before_the_date_to()
    {
        $this->signIn();
        $this->withExceptionHandling();

        // Give we have a membership record
        $joint = $this->teamWithVenue();

        $team = $joint['team'];
        $venue = $joint['venue'];
        $membership = $joint['membership'];

        // and we hit its endpoint with updated dates
        $payload = [
            'member_from' => Carbon::parse('+3 months')->toDateString(),
            'member_to' => Carbon::parse('+2 months')->toDateString()
        ];

        $route = route('venues.membership.update', [
            'venue' => $venue,
            'membership' => $membership
        ]);

        $request = $this->patch($route, $payload);

        $check = [
            'id' => $membership->id,
            'venue_from' => $payload['member_from'],
            'venue_to' => $payload['member_to']
        ];

        $request->assertSessionHasErrors('member_from');
        $request->assertSessionHasErrors('member_to');
        $this->assertDatabaseMissing('team_venues', $check);
    }

    /**
     * @test
     */
    public function the_user_can_terminate_a_teams_membership()
    {
        $this->signIn();

        // Given we have a player on a team
        $res = $this->teamWithVenue();

        // and we hit the terminate endpoint
        $this->delete($res['membership']->endpoint());

        // The player's membership has been terminated today
        $link = TeamVenue::find($res['membership']->id);

        $this->assertNotNull($link->venue_to);
        $this->assertEquals(
            Carbon::now()->toDateString(),
            Carbon::parse($link->venue_to)->toDateString()
        );
    }
}
