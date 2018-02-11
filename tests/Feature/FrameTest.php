<?php

namespace Tests\Feature;

use App\LeagueMatch;
use App\Player;
use App\PlayerTeam;
use App\Team;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrameTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function the_user_can_record_a_singles_frame()
    {
        // Given we have a match
        // - Teams
        $teamHome = create(Team::class);
        $teamAway = create(Team::class);

        // - Players
        $playerHome = create(Player::class);
        $playerAway = create(Player::class);

        // - Membership
        create(PlayerTeam::class, [
            'team_id' => $teamHome->id,
            'player_id' => $playerHome->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => Carbon::parse('+1 day')
        ]);

        create(PlayerTeam::class, [
            'team_id' => $teamHome->id,
            'player_id' => $playerAway->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => Carbon::parse('+1 day')
        ]);

        // - Match
        $match = create(LeagueMatch::class, [
            'home_team_id' => $teamHome->id,
            'away_team_id' => $teamAway->id
        ]);

        // and we hit the 'add frame' endpoint
        $payload = [
            'frame_type' => 'single',
            'home_player_id' => [$playerHome->id],
            'away_player_id' => [$playerAway->id],
            'winning_team' => 'home'
        ];

        $this->post('/matches/' . $match->id . '/frames', $payload);

        // the frame is saved to the database
        // - LeagueFrame
        $leagueFrame = [
            'league_match_id' => (string)$match->id,
            'frame_number' => (string)1,
            'doubles' => (string)0
        ];

        $this->assertDatabaseHas('league_frames', $leagueFrame);

        // - Home LeagueFramePlayer
        $leagueFramePlayerHome = [
            'player_id' => (string)$playerHome->id,
            'winner' => (string)1
        ];

        $this->assertDatabaseHas('league_frame_players', $leagueFramePlayerHome);

        // - Away LeagueFramePlayer
        $leagueFramePlayerAway = [
            'player_id' => (string)$playerAway->id,
            'winner' => (string)0
        ];

        $this->assertDatabaseHas('league_frame_players', $leagueFramePlayerAway);
    }

    /**
     * @test
     */
    public function the_user_can_record_a_doubles_frame()
    {
        // Given we have a match
        // - Teams
        $teamHome = create(Team::class);
        $teamAway = create(Team::class);

        // - Players
        $playerHome1 = create(Player::class);
        $playerHome2 = create(Player::class);
        $playerAway1 = create(Player::class);
        $playerAway2 = create(Player::class);

        // - Membership
        create(PlayerTeam::class, [
            'team_id' => $teamHome->id,
            'player_id' => $playerHome1->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => Carbon::parse('+1 day')
        ]);

        create(PlayerTeam::class, [
            'team_id' => $teamHome->id,
            'player_id' => $playerHome2->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => Carbon::parse('+1 day')
        ]);

        create(PlayerTeam::class, [
            'team_id' => $teamHome->id,
            'player_id' => $playerAway1->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => Carbon::parse('+1 day')
        ]);

        create(PlayerTeam::class, [
            'team_id' => $teamHome->id,
            'player_id' => $playerAway2->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => Carbon::parse('+1 day')
        ]);

        // - Match
        $match = create(LeagueMatch::class, [
            'home_team_id' => $teamHome->id,
            'away_team_id' => $teamAway->id
        ]);

        // and we hit the 'add frame' endpoint
        $payload = [
            'frame_type' => 'double',
            'home_player_id' => [$playerHome1->id, $playerHome2->id],
            'away_player_id' => [$playerAway1->id, $playerAway2->id],
            'winning_team' => 'home'
        ];

        $this->post('/matches/' . $match->id . '/frames', $payload);

        // the frame is saved to the database
        // - LeagueFrame
        $leagueFrame = [
            'league_match_id' => (string)$match->id,
            'frame_number' => (string)1,
            'doubles' => (string)1
        ];

        $this->assertDatabaseHas('league_frames', $leagueFrame);

        // - Home LeagueFramePlayer
        $leagueFramePlayerHome1 = [
            'player_id' => (string)$playerHome1->id,
            'winner' => (string)1
        ];

        $this->assertDatabaseHas('league_frame_players', $leagueFramePlayerHome1);

        $leagueFramePlayerHome2 = [
            'player_id' => (string)$playerHome2->id,
            'winner' => (string)1
        ];

        $this->assertDatabaseHas('league_frame_players', $leagueFramePlayerHome2);

        // - Away LeagueFramePlayer
        $leagueFramePlayerAway1 = [
            'player_id' => (string)$playerAway1->id,
            'winner' => (string)0
        ];

        $this->assertDatabaseHas('league_frame_players', $leagueFramePlayerAway1);

        $leagueFramePlayerAway2 = [
            'player_id' => (string)$playerAway2->id,
            'winner' => (string)0
        ];

        $this->assertDatabaseHas('league_frame_players', $leagueFramePlayerAway2);
    }

    /**
     * @test
     */
    public function a_frame_must_have_a_valid_type()
    {
        $this->withExceptionHandling();

        $match = create(LeagueMatch::class);

        $payload = [
            'frame_type' => 'invalid',
            'home_player_id' => create(Player::class)->id,
            'away_player_id' => create(Player::class)->id,
            'winning_team' => 'home'
        ];

        $request = $this->post('/matches/' . $match->id . '/frames', $payload);

        $request->assertSessionHasErrors('frame_type');
    }

    /**
     * @test
     */
    public function a_frame_must_have_a_valid_home_player()
    {
        $this->withExceptionHandling();

        $match = create(LeagueMatch::class);

        $payload = [
            'frame_type' => 'single',
            'home_player_id' => 999,
            'away_player_id' => create(Player::class)->id,
            'winning_team' => 'home'
        ];

        $request = $this->post('/matches/' . $match->id . '/frames', $payload);

        $request->assertSessionHasErrors('home_player_id');
    }

    /**
     * @test
     */
    public function a_frame_must_have_a_valid_away_player()
    {
        $this->withExceptionHandling();

        $match = create(LeagueMatch::class);

        $payload = [
            'frame_type' => 'double',
            'home_player_id' => create(Player::class)->id,
            'away_player_id' => 999,
            'winning_team' => 'home'
        ];

        $request = $this->post('/matches/' . $match->id . '/frames', $payload);

        $request->assertSessionHasErrors('away_player_id');
    }

    /**
     * @test
     */
    public function a_frame_must_have_a_valid_winning_team()
    {
        $this->withExceptionHandling();

        $match = create(LeagueMatch::class);

        $payload = [
            'frame_type' => 'single',
            'home_player_id' => create(Player::class)->id,
            'away_player_id' => create(Player::class)->id,
            'winning_team' => 'invalid'
        ];

        $request = $this->post('/matches/' . $match->id . '/frames', $payload);

        $request->assertSessionHasErrors('winning_team');
    }
}
