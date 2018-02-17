<?php

namespace Tests\Feature;

use App\Player;
use App\PlayerTeam;
use App\Team;
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
        $this->signIn();

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

    /**
     * @test
     */
    public function the_user_can_add_a_new_member()
    {
        $this->signIn();

        // Given we have a player
        $player = create(Player::class);

        // and a team
        $team = create(Team::class);

        // and we hit the create PlayerTeam endpoint
        $payload = [
            'player_id' => $player->id,
            'member_from' => Carbon::now()->toDateString(),
            'member_to' => Carbon::parse('+1 month')->toDateString()
        ];

        $response = $this->post('/teams/' . $team->id . '/membership', $payload);

        // the new membership exists
        $this->assertDatabaseHas('player_teams', $payload);
    }

    /**
     * @test
     */
    public function it_parses_a_blank_member_to_as_null()
    {
        $this->signIn();

        // Given we have a player
        $player = create(Player::class);

        // and a team
        $team = create(Team::class);

        // and we hit the create PlayerTeam endpoint
        $payload = [
            'player_id' => $player->id,
            'member_from' => Carbon::now()->toDateString(),
            'member_to' => ""
        ];

        $response = $this->post('/teams/' . $team->id . '/membership', $payload);

        // the new membership exists
        $payload['member_to'] = null;

        $this->assertDatabaseHas('player_teams', $payload);
    }

    /**
     * @test
     */
    public function when_creating_membership_player_id_is_required()
    {
        $this->signIn();
        $this->withExceptionHandling();

        // Given we have a player
        $player = create(Player::class);

        // and a team
        $team = create(Team::class);

        // and we hit the create PlayerTeam endpoint
        $payload = [
            'member_from' => Carbon::now()->toDateString(),
            'member_to' => Carbon::parse('+1 month')->toDateString()
        ];

        $response = $this->post('/teams/' . $team->id . '/membership', $payload);
        $response->assertSessionHasErrors('player_id');

        // the new membership exists
        $this->assertDatabaseMissing('player_teams', $payload);
    }

    /**
     * @test
     */
    public function when_creating_membership_player_id_must_exist()
    {
        $this->signIn();
        $this->withExceptionHandling();

        // Given we have a player
        $player = create(Player::class);

        // and a team
        $team = create(Team::class);

        // and we hit the create PlayerTeam endpoint
        $payload = [
            'player_id' => 999,
            'member_from' => Carbon::now()->toDateString(),
            'member_to' => Carbon::parse('+1 month')->toDateString()
        ];

        $response = $this->post('/teams/' . $team->id . '/membership', $payload);
        $response->assertSessionHasErrors('player_id');

        // the new membership exists
        $this->assertDatabaseMissing('player_teams', $payload);
    }

    /**
     * @test
     */
    public function when_creating_membership_member_from_is_required()
    {
        $this->signIn();
        $this->withExceptionHandling();

        // Given we have a player
        $player = create(Player::class);

        // and a team
        $team = create(Team::class);

        // and we hit the create PlayerTeam endpoint
        $payload = [
            'player_id' => $player->id,
            'member_to' => Carbon::parse('+1 month')->toDateString()
        ];

        $response = $this->post('/teams/' . $team->id . '/membership', $payload);
        $response->assertSessionHasErrors('member_from');

        // the new membership exists
        $this->assertDatabaseMissing('player_teams', $payload);
    }

    /**
     * @test
     */
    public function when_creating_membership_member_from_must_be_the_correct_format()
    {
        $this->signIn();
        $this->withExceptionHandling();

        // Given we have a player
        $player = create(Player::class);

        // and a team
        $team = create(Team::class);

        // and we hit the create PlayerTeam endpoint
        $payload = [
            'player_id' => $player->id,
            'member_from' => '17-03-2018',
            'member_to' => Carbon::parse('+1 month')->toDateString()
        ];

        $response = $this->post('/teams/' . $team->id . '/membership', $payload);
        $response->assertSessionHasErrors('member_from');

        // the new membership exists
        $this->assertDatabaseMissing('player_teams', $payload);
    }

    /**
     * @test
     */
    public function when_creating_membership_member_from_must_be_before_member_to()
    {
        $this->signIn();
        $this->withExceptionHandling();

        // Given we have a player
        $player = create(Player::class);

        // and a team
        $team = create(Team::class);

        // and we hit the create PlayerTeam endpoint
        $payload = [
            'player_id' => $player->id,
            'member_from' => Carbon::parse('+2 months')->toDateString(),
            'member_to' => Carbon::parse('+1 month')->toDateString()
        ];

        $response = $this->post('/teams/' . $team->id . '/membership', $payload);
        $response->assertSessionHasErrors('member_from');

        // the new membership exists
        $this->assertDatabaseMissing('player_teams', $payload);
    }

    /**
     * @test
     */
    public function when_creating_membership_member_to_must_be_the_correct_format()
    {
        $this->signIn();
        $this->withExceptionHandling();

        // Given we have a player
        $player = create(Player::class);

        // and a team
        $team = create(Team::class);

        // and we hit the create PlayerTeam endpoint
        $payload = [
            'player_id' => $player->id,
            'member_from' => Carbon::now()->toDateString(),
            'member_to' => '17-03-2018'
        ];

        $response = $this->post('/teams/' . $team->id . '/membership', $payload);
        $response->assertSessionHasErrors('member_to');

        // the new membership exists
        $this->assertDatabaseMissing('player_teams', $payload);
    }

    /**
     * @test
     */
    public function when_creating_membership_member_to_must_be_after_member_from()
    {
        $this->signIn();
        $this->withExceptionHandling();

        // Given we have a player
        $player = create(Player::class);

        // and a team
        $team = create(Team::class);

        // and we hit the create PlayerTeam endpoint
        $payload = [
            'player_id' => $player->id,
            'member_from' => Carbon::now()->toDateString(),
            'member_to' => Carbon::parse('-1 month')->toDateString()
        ];

        $response = $this->post('/teams/' . $team->id . '/membership', $payload);
        $response->assertSessionHasErrors('member_to');

        // the new membership exists
        $this->assertDatabaseMissing('player_teams', $payload);
    }
}
