<?php

namespace Tests\PageLoad;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrameLoadTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function create()
    {
        $this->signIn();
        $match = create(\App\LeagueMatch::class);
        $route = route('frames.create', $match);
        $request = $this->get($route);
        $request->assertSuccessful();
    }

    /**
     * @test
     */
    public function edit()
    {
        $this->signIn();
        $frame = create(\App\LeagueFrame::class);
        $route = route('frames.edit', [
            'match' => $frame->match,
            'frame' => $frame
        ]);

        $request = $this->get($route);
        $request->assertSuccessful();
    }
}
