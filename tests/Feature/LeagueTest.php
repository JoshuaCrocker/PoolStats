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
}
