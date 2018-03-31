<?php

namespace App\Console\Commands;

use App\HPPStat;
use App\LeagueMatch;
use App\Team;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RecalculateHighestPerformingPlayerStatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:hpp 
                            {--team=* : The ID of the team(s) you\'d like to recalculate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate the Highest Performing Player statistic';

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
        $teams = collect($this->options('team')['team']);

        if ($teams->count() == 0) {
            $teams = Team::all()->pluck('id');
        }

        $teams->each(function ($team) {
            // TODO validate
            $this->recalculateTeamStats($team);
        });
    }

    private function recalculateTeamStats($teamID)
    {
        // Empty these results out...
        HPPStat::where('team_id', $teamID)->delete();

        $this->info("Recalculating " . $teamID);

        $frames = $this->collectFrames($teamID);
        $playerStats = $this->getTeamPlayers($frames, $teamID);

        $this->info('Calculating scores');

        foreach ($playerStats as $playerID => $stats) {
            $score = $this->generateScore($stats['wins'], $stats['loses']);

            $record = new HPPStat();
            $record->team_id = $teamID;
            $record->player_id = $playerID;
            $record->score = $score;
            $record->save();

            $this->info('Player #' . $playerID . ' scored ' . $score . '%');
        }
    }

    private function collectFrames($teamID)
    {
        // Collect all matches
        $match = LeagueMatch::where('home_team_id', $teamID)
            ->orWhere('away_team_id', $teamID)->get();

        $this->info($match->count() . ' matches found');

        // Set up frames array
        $frames = [];

        // Iterate over matches
        $match->each(function ($match) use (&$frames) {
            // Iterates over frames
            $match->frames->each(function ($frame) use (&$frames) {
                // Add frame to frames output
                $frames[] = $frame;
            });
        });

        $this->info(count($frames) . ' frames extracted');

        return $frames;
    }

    private function getTeamPlayers($frames, $teamID)
    {
        // Set up output array
        $playerStats = [];

        // Iterate over frames
        foreach ($frames as $frame) {
            // Get all frame players
            $frame->players->each(function ($player) use ($frame, &$playerStats, $teamID) {
                if ($player->player->findTeam(Carbon::parse($frame->match->match_date))->id == $teamID) {
                    if (!isset($playerStats[$player->player->id])) {
                        $playerStats[$player->player->id] = [
                            'wins' => 0,
                            'loses' => 0
                        ];
                    }

                    // Increment correct counter
                    if ($player->winner) {
                        $playerStats[$player->player->id]['wins']++;
                    } else {
                        $playerStats[$player->player->id]['loses']++;
                    }
                }

            });
        }

        return $playerStats;
    }

    private function generateScore($wins, $loses)
    {
        $total = $wins + $loses;
        $score = $wins / max(1, $total);
        return round($score * 100);
    }
}
