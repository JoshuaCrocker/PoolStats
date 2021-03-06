<?php

namespace Tests\Feature;

use App\LeagueFrame;
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
        // Given we're signed in ...
        $this->signIn();

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
        // Given we're signed in ...
        $this->signIn();

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
        // Given we're signed in ...
        $this->signIn();

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
        // Given we're signed in ...
        $this->signIn();

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
        // Given we're signed in ...
        $this->signIn();

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
        // Given we're signed in ...
        $this->signIn();

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

    /** @test */
    public function the_user_must_be_logged_in_to_create_a_frame()
    {
        // Given we're not signed in ...
        // $this->signIn();

        $this->withExceptionHandling();

        // ... and we try to create a new frame
        $match = create(LeagueMatch::class);

        $frame = make(LeagueFrame::class, [
            'league_match_id' => $match->id
        ])->toArray();

        $request = $this->post('/matches/' . $match->id . '/frames', $frame);

        // ... we should be redirected to the login page
        $request->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function the_user_can_edit_a_frame()
    {
        // Give we're signed in ...
        $this->signIn();

        // ... and we have a frame ...
        $match = create(LeagueMatch::class);
        $frame = $this->frameWithPlayers($match);

        // ... when we hit the update endpoint ...
        $payload = [
            'frame_type' => 'double',
            'home_player_id' => [
                create(Player::class)->id,
                create(Player::class)->id
            ],
            'away_player_id' => [
                create(Player::class)->id,
                create(Player::class)->id
            ],
            'winning_team' => 'away'
        ];

        $this->patch($frame['frame']->endpoint(), $payload);

        // ... the record is updated
        $this->assertDatabaseHas('league_frames', [
            'id' => $frame['frame']->id,
            'doubles' => true
        ]);

        foreach ($payload['home_player_id'] as $pid) {
            $this->assertDatabaseHas('league_frame_players', [
                'player_id' => $pid,
                'winner' => false
            ]);
        }

        foreach ($payload['away_player_id'] as $pid) {
            $this->assertDatabaseHas('league_frame_players', [
                'player_id' => $pid,
                'winner' => true
            ]);
        }
    }

    /**
     * @test
     */
    public function the_user_can_delete_a_frame()
    {
        // Given we're signed in ...
        $this->signIn();

        // ... and we have a frame ...
        $frame = create(LeagueFrame::class);

        // ... if we hit the delete endpoint ...
        $this->delete($frame->endpoint());

        // ... the frame is deleted
        $data = [
            'id' => $frame->id,
            'league_match_id' => $frame->league_match_id,
            'frame_number' => $frame->frame_number
        ];

        $this->assertSoftDeleted('league_frames', $data);
    }

    // Historic Data
    // TODO working with historic data
    // All data needs to be current at the time of the match
}
