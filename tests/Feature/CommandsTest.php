<?php

namespace Tests\Feature;

use App\LeagueFrame;
use App\LeagueFramePlayer;
use App\LeagueMatch;
use App\Team;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CommandsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function players_are_scored_depending_on_their_match_performance()
    {
        // Given we have a team
        $team = create(Team::class);

        // with a few players
        $player1 = $this->playerWithTeam($team)['player'];
        $player2 = $this->playerWithTeam($team)['player'];
        $player3 = $this->playerWithTeam($team)['player'];

        // who have attended / played various frames
        $match = create(LeagueMatch::class, [
            'match_date' => Carbon::now(),
            'home_team_id' => $team->id
        ]);

        $frame = create(LeagueFrame::class, [
            'league_match_id' => $match->id
        ]);

        //- Player 1 Games
        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $player1->id,
            'winner' => true
        ]);

        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $player1->id,
            'winner' => false
        ]);

        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $player1->id,
            'winner' => true
        ]);

        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $player1->id,
            'winner' => true
        ]);

        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $player1->id,
            'winner' => false
        ]);

        //- Player 2
        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $player2->id,
            'winner' => false
        ]);

        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $player2->id,
            'winner' => true
        ]);

        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $player2->id,
            'winner' => false
        ]);

        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $player2->id,
            'winner' => false
        ]);

        //- Player 3
        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $player3->id,
            'winner' => true
        ]);

        create(LeagueFramePlayer::class, [
            'league_frame_id' => $frame->id,
            'player_id' => $player3->id,
            'winner' => false
        ]);

        // the application can determine the highest-performing player
        Artisan::call('stats:hpp');

        $player1stats = [
            'team_id' => $team->id,
            'player_id' => $player1->id,
            'score' => round((3 / 5) * 100)
        ];

        $player2stats = [
            'team_id' => $team->id,
            'player_id' => $player2->id,
            'score' => round((1 / 4) * 100)
        ];

        $player3stats = [
            'team_id' => $team->id,
            'player_id' => $player3->id,
            'score' => round((1 / 2) * 100)
        ];

        $this->assertDatabaseHas('stats_hpp', $player1stats);
        $this->assertDatabaseHas('stats_hpp', $player2stats);
        $this->assertDatabaseHas('stats_hpp', $player3stats);
    }

    /**
     * @test
     */
    public function players_wins_loses_and_draws_are_calculated()
    {
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

        $data = [
            'player_id' => $player['player']->id,
            'wins' => 3,
            'loses' => 1,
            'draws' => 2
        ];

        $this->assertDatabaseHas('stats_wins_loses', $data);
    }
}
