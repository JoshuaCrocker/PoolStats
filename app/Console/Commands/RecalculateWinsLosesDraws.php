<?php

namespace App\Console\Commands;

use App\LeagueFramePlayer;
use App\WLDStat;
use Illuminate\Console\Command;

class RecalculateWinsLosesDraws extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:wld';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate all Player\'s Wins/Loses/Draws statistic';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $playerStats = $this->getPlayerStats();

        // Total
        foreach ($playerStats as $playerID => $stats) {
            WLDStat::where('player_id', $playerID)->delete();

            $record = new WLDStat();
            $record->player_id = $playerID;
            $record->wins = $stats['wins'];
            $record->loses = $stats['loses'];
            $record->draws = $stats['draws'];
            $record->save();
        }
    }

    private function getPlayerStats()
    {
        // Get all players
        $players = LeagueFramePlayer::all();

        // Iterate
        $playerStats = [];

        $players->each(function ($player) use (&$playerStats) {
            if (!isset($playerStats[$player->player_id])) {
                $playerStats[$player->player_id] = [
                    'wins' => 0,
                    'loses' => 0,
                    'draws' => 0
                ];
            }

            if ($player->frame->isDraw()) {
                $playerStats[$player->player_id]['draws']++;
            } else {
                if ($player->winner) {
                    $playerStats[$player->player_id]['wins']++;
                }

                if (!$player->winner) {
                    $playerStats[$player->player_id]['loses']++;
                }
            }
        });

        return $playerStats;
    }
}
