<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Player;
use App\StatAttendance;

class RecalculateMatchAttendanceStat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate the match attendance of all players';

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
        StatAttendance::truncate();

        // Get all players
        $players = Player::all();

        $players->each(function($player) {
            $memberships = $player->memberships;

            $total = 0;
            $played = 0;

            $memberships->each(function($membership) use (&$total, &$played, $player) {
                // Calculate total number of matches for teams
                $total += $membership->team->matches->count();

                // Calculate total number of matches played
                $played += $membership->team->matches->filter(function($match) use ($player) {
                    $framesWithPlayer = $match->frames->filter(function($frame) use ($player) {
                        return $frame->players->filter(function($framePlayer) use ($player) {
                            return $framePlayer->player_id == $player->id;
                        })->count();
                    })->count();

                    return $framesWithPlayer;
                })->count();
            });

            // Save value
            $stat = new StatAttendance;
            $stat->player_id = $player->id;
            $stat->played = $played;
            $stat->total = $total;
            $stat->save();
        });
        
    }
}
