<?php

namespace Tests\PageLoad;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatchLoadTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index()
    {
        $this->signIn();
        $route = route('matches.index');
        $request = $this->get($route);
        $request->assertSuccessful();
    }

    /**
     * @test
     */
    public function create()
    {
        $this->signIn();
        $route = route('matches.create');
        $request = $this->get($route);
        $request->assertSuccessful();
    }

    /**
     * @test
     */
    public function show()
    {
        $this->signIn();
        $match = create(\App\LeagueMatch::class);
        $route = route('matches.show', $match);
        $request = $this->get($route);
        $request->assertSuccessful();
    }

    /**
     * @test
     */
    public function edit()
    {
        $this->signIn();
        $match = create(\App\LeagueMatch::class);
        $route = route('matches.edit', $match);
        $request = $this->get($route);
        $request->assertSuccessful();
    }
}
