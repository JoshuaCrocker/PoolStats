<?php

namespace Tests\Unit;

use App\Team;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TeamUnitTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_can_generate_its_endpoint()
    {
        // Given we have a test
        $team = create(Team::class);

        // it can generate its endpoint
        $this->assertEquals(
            $team->endpoint(),
            '/teams/1'
        );


    }
}
