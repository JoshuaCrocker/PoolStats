<?php

namespace App\Console\Commands;

use App\Player;
use App\StatVenuePerformance;
use Illuminate\Console\Command;

class RecalculateVenuePerformanceStat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:venues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate the Venue Performance of the playerss';

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
        // Clear all
        StatVenuePerformance::truncate();

        // Get all players
        $players = Player::all();

        $players->each(function($player) {
            $venues = [];

            // Get all matches
            $memberships = $player->memberships;

            $memberships->each(function($membership) use (&$venues, $player) {
                // Calculate total number of matches played
                $membership->team->matches->each(function($match) use (&$venues, $player) {
                    // Create venue entry if it doesn't exist
                    if (!isset($venues[$match->venue_id])) {
                        $venues[$match->venue_id] = [
                            'won' => 0,
                            'played' => 0
                        ];
                    }

                    $framesWithPlayer = $match->frames->filter(function($frame) use (&$venues, $match, $player) {
                        $venues[$match->venue_id]['won'] += $frame->players->filter(function($framePlayer) use ($player) {
                            return $framePlayer->player_id == $player->id && $framePlayer->winner == true;
                        })->count();

                        $venues[$match->venue_id]['played'] += $frame->players->filter(function($framePlayer) use ($player) {
                            return $framePlayer->player_id == $player->id;
                        })->count();
                    })->count();
                });
            });

            // Count won/lost frames
            foreach ($venues as $venue => $stats) {
                $stat = new StatVenuePerformance;
                $stat->player_id = $player->id;
                $stat->venue_id = $venue;
                $stat->won = $stats['won'];
                $stat->played = $stats['played'];
                $stat->save();
            }
        });

        
    }
}
