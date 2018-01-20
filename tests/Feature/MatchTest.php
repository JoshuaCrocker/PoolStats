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
}
