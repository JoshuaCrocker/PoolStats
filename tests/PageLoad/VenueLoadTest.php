<?php

namespace Tests\PageLoad;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VenueLoadTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index()
    {
        $this->signIn();
        $route = route('venues.index');
        $request = $this->get($route);
        $request->assertSuccessful();
    }

    /**
     * @test
     */
    public function create()
    {
        $this->signIn();
        $route = route('venues.create');
        $request = $this->get($route);
        $request->assertSuccessful();
    }

    /**
     * @test
     */
    public function show()
    {
        $this->signIn();
        $venue = create(\App\Venue::class);
        $route = route('venues.show', $venue);
        $request = $this->get($route);
        $request->assertSuccessful();
    }

    /**
     * @test
     */
    public function edit()
    {
        $this->signIn();
        $venue = create(\App\Venue::class);
        $route = route('venues.edit', $venue);
        $request = $this->get($route);
        $request->assertSuccessful();
    }

    /**
     * @test
     */
    public function membership_create()
    {
        $this->signIn();
        $venue = create(\App\Venue::class);
        $route = route('venues.membership.create', $venue);
        $request = $this->get($route);
        $request->assertSuccessful();
    }

    /**
     * @test
     */
    public function membership_edit()
    {
        $this->signIn();
        $membership = create(\App\TeamVenue::class);
        $route = route('venues.membership.edit', [
            'venue' => $membership->venue,
            'membership' => $membership
        ]);

        $request = $this->get($route);
        $request->assertSuccessful();
    }
}
