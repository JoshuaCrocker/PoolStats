<?php

namespace Tests\PageLoad;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamLoadTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index()
    {
        $this->signIn();
        $route = route('teams.index');
        $request = $this->get($route);
        $request->assertSuccessful();
    }

    /**
     * @test
     */
    public function create()
    {
        $this->signIn();
        $route = route('players.create');
        $request = $this->get($route);
        $request->assertSuccessful();
    }

    /**
     * @test
     */
    public function show()
    {
        $this->signIn();
        $player = create(\App\Player::class);
        $route = route('players.show', $player);
        $request = $this->get($route);
        $request->assertSuccessful();
    }

    /**
     * @test
     */
    public function edit()
    {
        $this->signIn();
        $player = create(\App\Player::class);
        $route = route('players.edit', $player);
        $request = $this->get($route);
        $request->assertSuccessful();
    }

    /**
     * @test
     */
    public function membership_create()
    {
        $this->signIn();
        $team = create(\App\Team::class);
        $route = route('membership.create', $team);
        $request = $this->get($route);
        $request->assertSuccessful();
    }

    /**
     * @test
     */
    public function membership_edit()
    {
        $this->signIn();
        $membership = create(\App\PlayerTeam::class);
        $route = route('membership.edit', [
            'team' => $membership->team,
            'membership' => $membership
        ]);

        $request = $this->get($route);
        $request->assertSuccessful();
    }
}
