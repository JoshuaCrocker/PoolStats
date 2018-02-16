<?php

namespace Tests\Feature;

use App\PlayerTeam;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayerTeamTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function the_user_can_terminate_a_players_membership()
    {
        // Given we have a player on a team
        $res = $this->playerWithTeam();

        // and we hit the terminate endpoint
        $this->delete($res['subscription']->endpoint());

        // The player's membership has been terminated today
        $link = PlayerTeam::find($res['subscription']->id);

        $this->assertNotNull($link->member_to);
        $this->assertEquals(
            Carbon::parse('-1 day')->toDateString(),
            Carbon::parse($link->member_to)->toDateString()
        );
    }
}
