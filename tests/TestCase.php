<?php
namespace Tests;

use App\Exceptions\Handler;
use App\LeagueFrame;
use App\LeagueFramePlayer;
use App\LeagueMatch;
use App\Player;
use App\PlayerTeam;
use App\Team;
use App\Venue;
use Carbon\Carbon;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->disableExceptionHandling();
    }

    /**
     * @param null $user
     * @return $this
     */
    protected function signIn($user = null)
    {
        $user = $user ?: create('App\User');
        $this->actingAs($user);
        return $this;
    }

    /**
     * @param Team|null $team
     * @return array
     */
    protected function playerWithTeam(Team $team = null)
    {
        $output = [
            'player' => create(Player::class),
            'team' => $team === null ? create(Team::class) : $team
        ];

        $output['subscription'] = create(PlayerTeam::class, [
            'player_id' => $output['player']->id,
            'team_id' => $output['team']->id,
            'member_from' => Carbon::parse('-1 day'),
            'member_to' => null
        ]);

        return $output;
    }

    protected function teamWithVenue()
    {
        $team = create(Team::class);
        $venue = create(Venue::class);

        $membership = create(TeamVenue::class, [
            'team_id' => $team->id,
            'venue_id' => $venue->id,
            'venue_from' => Carbon::parse('-1 day')
        ]);

        return [
            'team' => $team,
            'venue' => $venue,
            'membership' => $membership
        ];
    }

    // Hat tip, @adamwathan.
    protected function disableExceptionHandling()
    {
        $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct() {}
            public function report(\Exception $e) {}

            /**
             * @param \Illuminate\Http\Request $request
             * @param \Exception $e
             * @return \Illuminate\Http\Response|void
             * @throws \Exception
             */
            public function render($request, \Exception $e) {
                throw $e;
            }
        });
    }

    protected function withExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);
        return $this;
    }

    /**
     * @param LeagueMatch $match
     * @param Player|null $playerHome
     * @param Player|null $playerAway
     * @param string $winner
     * @return array
     */
    protected function frameWithPlayers(LeagueMatch $match, Player $playerHome = null, Player $playerAway = null, $winner = 'home')
    {
        $output = [];

        if ($playerHome === null) {
            $playerHome = $this->playerWithTeam()['player'];
        }

        if ($playerAway === null) {
            $playerAway = $this->playerWithTeam()['player'];
        }

        $output['player_home'] = $playerHome;
        $output['player_away'] = $playerAway;

        $output['player_home_id'] = $playerHome->id;
        $output['player_away_id'] = $playerAway->id;

        $output['frame'] = create(LeagueFrame::class, [
            'league_match_id' => $match->id,
            'doubles' => false
        ]);

        $output['frame_player_home'] = create(LeagueFramePlayer::class, [
            'league_frame_id' => $output['frame']->id,
            'player_id' => $playerHome->id,
            'winner' => $winner == 'home'
        ]);

        $output['frame_player_away'] = create(LeagueFramePlayer::class, [
            'league_frame_id' => $output['frame']->id,
            'player_id' => $playerAway->id,
            'winner' => $winner == 'away'
        ]);

        return $output;
    }

    /**
     * @param LeagueMatch $match
     * @param Player|null $playerHome1
     * @param Player|null $playerHome2
     * @param Player|null $playerAway1
     * @param Player|null $playerAway2
     * @param string $winner
     * @return array
     */
    protected function doublesFrameWithPlayers(LeagueMatch $match, Player $playerHome1 = null, Player $playerHome2 = null,
                                               Player $playerAway1 = null, Player $playerAway2 = null, string $winner = 'home')
    {
        $output = [];

        if ($playerHome1 === null) {
            $playerHome1 = $this->playerWithTeam()['player'];
        }

        if ($playerHome2 === null) {
            $playerHome2 = $this->playerWithTeam()['player'];
        }

        if ($playerAway1 === null) {
            $playerAway1 = $this->playerWithTeam()['player'];
        }

        if ($playerAway2 === null) {
            $playerAway2 = $this->playerWithTeam()['player'];
        }

        $output['player_home'] = [$playerHome1, $playerHome2];
        $output['player_away'] = [$playerAway1, $playerAway2];

        $output['player_home_id'] = [$playerHome1->id, $playerHome2->id];
        $output['player_away_id'] = [$playerAway1->id, $playerAway2->id];

        $output['frame'] = create(LeagueFrame::class, [
            'league_match_id' => $match->id,
            'doubles' => true
        ]);

        $output['frame_player_home1'] = create(LeagueFramePlayer::class, [
            'league_frame_id' => $output['frame']->id,
            'player_id' => $playerHome1->id,
            'winner' => $winner == 'home'
        ]);

        $output['frame_player_home2'] = create(LeagueFramePlayer::class, [
            'league_frame_id' => $output['frame']->id,
            'player_id' => $playerHome2->id,
            'winner' => $winner == 'home'
        ]);

        $output['frame_player_away1'] = create(LeagueFramePlayer::class, [
            'league_frame_id' => $output['frame']->id,
            'player_id' => $playerAway1->id,
            'winner' => $winner == 'away'
        ]);

        $output['frame_player_away2'] = create(LeagueFramePlayer::class, [
            'league_frame_id' => $output['frame']->id,
            'player_id' => $playerAway2->id,
            'winner' => $winner == 'away'
        ]);

        return $output;
    }

    protected function assertDatabaseHas($table, array $data, $connection = null)
    {
        if (isset($data['created_at'])) {
            unset($data['created_at']);
        }

        if (isset($data['updated_at'])) {
            unset($data['updated_at']);
        }

        parent::assertDatabaseHas($table, $data, $connection);
    }

    protected function assertDatabaseMissing($table, array $data, $connection = null)
    {
        if (isset($data['created_at'])) {
            unset($data['created_at']);
        }

        if (isset($data['updated_at'])) {
            unset($data['updated_at']);
        }

        parent::assertDatabaseMissing($table, $data, $connection);
    }
}
