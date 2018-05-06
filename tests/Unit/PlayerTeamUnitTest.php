<?php

namespace Tests\Unit;

use App\PlayerTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

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

    /**
     * @test
     */
    public function it_can_tell_if_the_player_is_terminated_today()
    {
        $player = create(PlayerTeam::class, [
            'member_from' => Carbon::parse('-1 week'),
            'member_to' => Carbon::now()
        ]);

        $this->assertTrue($player->terminates_today);
    }
}
