<?php

namespace Tests\Unit;

use App\PlayerTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayerTeamUnitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_generate_its_endpoint()
    {
        // Given we have a Match
        $player = create(PlayerTeam::class);

        // it can generate its endpoint
        $this->assertEquals(
            $player->endpoint(),
            '/teams/' . $player->team_id . '/membership/1'
        );
    }
}
