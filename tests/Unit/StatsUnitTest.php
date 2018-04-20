<?php

namespace Tests\Unit;

use App\LeagueFrame;
use App\LeagueFramePlayer;
use App\LeagueMatch;
use App\Player;
use App\PlayerTeam;
use App\Team;
use App\Venue;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class StatsUnitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_can_calculate_a_players_wins_loses_draws_statistic()
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

        $check = [
            'player_id' => $player['player']->id,
            'wins' => 3,
            'loses' => 1,
            'draws' => 2
        ];

        $this->assertDatabaseHas('stats_wins_loses', $check);
    }

    /**
     * @test
     */
    public function it_can_calculate_a_players_wins_loses_draws_statistic_including_historic_data()
    {
        $this->signIn();

        $player = create(Player::class);
        $team = create(Team::class);

        $subscription = create(PlayerTeam::class, [
            'player_id' => $player->id,
            'team_id' => $team->id,
            'member_from' => Carbon::parse('-1 month'),
            'member_to' => Carbon::parse('-1 week')
        ]);

        $match = create(LeagueMatch::class, [
            'home_team_id' => $team->id
        ]);

        $this->frameWithPlayers($match, $player);
        $this->frameWithPlayers($match, $player);
        $this->frameWithPlayers($match, $player);
        $this->frameWithPlayers($match, $player, null, 'away');
        $this->frameWithPlayers($match, $player, null, 'draw');
        $this->frameWithPlayers($match, $player, null, 'draw');

        Artisan::call('stats:wld');

        $check = [
            'player_id' => $player->id,
            'wins' => 3,
            'loses' => 1,
            'draws' => 2
        ];

        $this->assertDatabaseHas('stats_wins_loses', $check);
    }

    /**
     * @test
     */
    public function it_can_calculate_the_players_match_attendance_statistic()
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

        $check = [
            'player_id' => $player['player']->id,
            'played' => 3,
            'total' => 5
        ];

        $this->assertDatabaseHas('stat_attendances', $check);
    }

    /**
     * @test
     */
    public function it_can_calculate_the_players_match_attendance_statistic_including_historic_data()
    {
        $this->signIn();

        $player = create(Player::class);
        $team = create(Team::class);

        $subscription = create(PlayerTeam::class, [
            'player_id' => $player->id,
            'team_id' => $team->id,
            'member_from' => Carbon::parse('-1 month'),
            'member_to' => Carbon::parse('-1 week')
        ]);

        // Create matches where player didn't attend
        create(LeagueMatch::class, [
            'home_team_id' => $team->id
        ]);

        create(LeagueMatch::class, [
            'home_team_id' => $team->id
        ]);

        // Create matches where player did attend
        $match1 = create(LeagueMatch::class, [
            'home_team_id' => $team->id
        ]);

        $match2 = create(LeagueMatch::class, [
            'home_team_id' => $team->id
        ]);

        $match3 = create(LeagueMatch::class, [
            'home_team_id' => $team->id
        ]);

        $this->frameWithPlayers($match1, $player);
        $this->frameWithPlayers($match2, $player);
        $this->frameWithPlayers($match3, $player);

        Artisan::call('stats:attendance');

        $check = [
            'player_id' => $player->id,
            'played' => 3,
            'total' => 5
        ];

        $this->assertDatabaseHas('stat_attendances', $check);
    }

    /**
     * @test
     */
    public function in_can_calculate_the_players_venue_performance_statistic()
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

        $check = [
            'player_id' => $player['player']->id,
            'venue_id' => $venue->id,
            'played' => 2,
            'won' => 1
        ];

        $this->assertDatabaseHas('stat_venue_performances', $check);
    }

    /**
     * @test
     */
    public function in_can_calculate_the_players_venue_performance_statistic_including_historic_data()
    {
        $venue = create(Venue::class);

        $player = create(Player::class);
        $team = create(Team::class);

        $subscription = create(PlayerTeam::class, [
            'player_id' => $player->id,
            'team_id' => $team->id,
            'member_from' => Carbon::parse('-1 month'),
            'member_to' => Carbon::parse('-1 week')
        ]);

        $match = create(LeagueMatch::class, [
            'venue_id' => $venue->id,
            'home_team_id' => $team->id
        ]);

        $this->frameWithPlayers($match, $player, null, 'home');
        $this->frameWithPlayers($match, $player, null, 'away');

        Artisan::call('stats:venues');

        $check = [
            'player_id' => $player->id,
            'venue_id' => $venue->id,
            'played' => 2,
            'won' => 1
        ];

        $this->assertDatabaseHas('stat_venue_performances', $check);
    }

    // Base Highest Performing Player Stat

    /**
     * @test
     */
    public function it_can_calculate_the_highest_performing_player_statistic()
    {
        // Given we have a team
        $team = create(Team::class);

        // with a few players
        $player1 = $this->playerWithTeam($team)['player'];
        $player2 = $this->playerWithTeam($team)['player'];

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
            'winner' => true
        ]);

        //- Player 2 Games
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

        // Calculate the Highest Performing Player
        Artisan::call('stats:hpp');

        $check = [
            'team_id' => $team->id,
            'player_id' => $player1->id,
            'score' => 100
        ];

        $this->assertDatabaseHas('stats_hpp', $check);
    }
}
