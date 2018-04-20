<?php

namespace Tests\Feature;

use App\LeagueMatch;
use App\Player;
use App\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class PlayerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_shows_a_list_of_players()
    {
        $player = create(Player::class);

        $request = $this->get('players');

        $request->assertSee(e($player->name));
    }

    /**
     * @test
     */
    public function the_user_can_create_a_player()
    {
        $this->signIn();
        $player = make(Player::class)->toArray();
        $request = $this->post('players', $player);
        $this->assertDatabaseHas('players', $player);
    }

    /**
     * @test
     */
    public function the_user_must_be_logged_in_to_create_a_player()
    {
        // Given we're not signed in ...
        // $this->signIn();

        $this->withExceptionHandling();

        $player = make(Player::class)->toArray();
        $request = $this->post('/players', $player);

        // ... we should be redirected to the login page
        $request->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function a_new_player_requires_a_name()
    {
        $this->signIn();
        $this->withExceptionHandling();

        $player = [
            'name' => ''
        ];

        $request = $this->post('players', $player);
        $request->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('players', $player);
    }

    /**
     * @test
     */
    public function the_user_can_update_a_player()
    {
        $this->signIn();

        $player = create(Player::class);

        $update = [
            'name' => 'Updated Name'
        ];

        $request = $this->patch(route('players.update', $player), $update);

        $update['id'] = $player->id;

        $this->assertDatabaseHas('players', $update);
    }

    /**
     * @test
     */
    public function the_user_must_be_logged_in_to_update_a_player()
    {
        // Given we're not signed in ...
        // $this->signIn();

        $this->withExceptionHandling();

        $player = create(Player::class);

        $update = [
            'name' => 'Updated Name'
        ];

        $request = $this->patch(route('players.update', $player), $update);

        // ... we should be redirected to the login page
        $request->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function an_updated_player_requires_a_name()
    {
        $this->signIn();
        $this->withExceptionHandling();

        $player = create(Player::class);

        $update = [
            'name' => ''
        ];

        $request = $this->patch(route('players.update', $player), $update);
        $request->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('players', $update);
    }

    /**
     * @test
     */
    public function the_user_can_delete_a_player()
    {
        $this->signIn();

        $player = create(Player::class);

        $request = $this->delete(route('players.destroy', $player));

        $this->assertSoftDeleted('players', $player->toArray());
    }

    /**
     * @test
     */
    public function the_user_must_be_signed_in_to_delete_a_player()
    {
        $this->withExceptionHandling();

        $player = create(Player::class);

        $request = $this->delete(route('players.destroy', $player));

        $request->assertRedirect('/login');
        $this->assertDatabaseHas('players', $player->toArray());
    }

    /**
     * @test
     */
    public function the_player_page_displays_the_player_details()
    {
        $this->signIn();

        $player = create(Player::class);

        $request = $this->get(route('players.show', $player));

        $request->assertSee(e($player->name));
    }

    /**
     * @test
     */
    public function the_player_page_displays_all_memberships()
    {
        $this->signIn();

        $data = $this->playerWithTeam();

        $request = $this->get(route('players.show', $data['player']));

        $request->assertSee(e($data['team']->name));
        $request->assertSee(e($data['subscription']->member_from->toDateString()));
        $request->assertSee("Current");
    }

    /**
     * @test
     */
    public function the_player_page_displays_the_players_all_time_wins_and_loses()
    {
        $this->signIn();

        $player = $this->playerWithTeam();

        $match = create(LeagueMatch::class, [
            'home_team_id' => $player['team']->id
        ]);

        $this->frameWithPlayers($match, $player['player']);
        $this->frameWithPlayers($match, $player['player']);
        $this->frameWithPlayers($match, $player['player']);
        $this->frameWithPlayers($match, $player['player'], null, 'away');
        $this->frameWithPlayers($match, $player['player'], null, 'draw');
        $this->frameWithPlayers($match, $player['player'], null, 'draw');

        Artisan::call('stats:wld');

        $request = $this->get(route('players.show', $player['player']));
        $request->assertSeeText('3W');
        $request->assertSeeText('1L');
        $request->assertSeeText('2D');
    }

    /**
     * @test
     */
    public function the_player_page_displays_the_players_match_attendance()
    {
        $this->signIn();

        $player = $this->playerWithTeam();

        // Create matches where player didn't attend
        create(LeagueMatch::class, [
            'home_team_id' => $player['team']->id
        ]);

        create(LeagueMatch::class, [
            'home_team_id' => $player['team']->id
        ]);

        // Create matches where player did attend
        $match1 = create(LeagueMatch::class, [
            'home_team_id' => $player['team']->id
        ]);

        $match2 = create(LeagueMatch::class, [
            'home_team_id' => $player['team']->id
        ]);

        $match3 = create(LeagueMatch::class, [
            'home_team_id' => $player['team']->id
        ]);

        $this->frameWithPlayers($match1, $player['player']);
        $this->frameWithPlayers($match2, $player['player']);
        $this->frameWithPlayers($match3, $player['player']);

        Artisan::call('stats:attendance');

        $request = $this->get(route('players.show', $player['player']));

        $request->assertSeeText('60.00%');
        $request->assertSeeText('3 / 5');
    }

    /**
     * @test
     */
    public function the_player_page_displays_the_players_venue_performance()
    {
        $venue = create(Venue::class);
        $player = $this->playerWithTeam();

        $match = create(LeagueMatch::class, [
            'venue_id' => $venue->id,
            'home_team_id' => $player['team']->id
        ]);

        $this->frameWithPlayers($match, $player['player'], null, 'home');
        $this->frameWithPlayers($match, $player['player'], null, 'away');

        Artisan::call('stats:venues');

        $request = $this->get(route('players.show', $player['player']));

        $request->assertSee(e($venue->name));
        $request->assertSee('50.00%');
    }
}
