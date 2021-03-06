<?php

namespace Tests\Unit;

use App\League;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LeagueUnitTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_can_generate_its_endpoint()
    {
        // Given we have a test
        $team = create(League::class);

        // it can generate its endpoint
        $this->assertEquals(
            $team->endpoint(),
            '/leagues/1'
        );
    }
}
