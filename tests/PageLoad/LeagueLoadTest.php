<?php

namespace Tests\PageLoad;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeagueLoadTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index()
    {
        $this->signIn();
        $route = route('leagues.index');
        $request = $this->get($route);
        $request->assertSuccessful();
    }

    /**
     * @test
     */
    public function create()
    {
        $this->signIn();
        $route = route('leagues.create');
        $request = $this->get($route);
        $request->assertSuccessful();
    }

    /**
     * @test
     */
    public function show()
    {
        $this->signIn();
        $league = create(\App\League::class);
        $route = route('leagues.show', $league);
        $request = $this->get($route);
        $request->assertSuccessful();
    }

    /**
     * @test
     */
    public function edit()
    {
        $this->signIn();
        $league = create(\App\League::class);
        $route = route('leagues.edit', $league);
        $request = $this->get($route);
        $request->assertSuccessful();
    }
}
