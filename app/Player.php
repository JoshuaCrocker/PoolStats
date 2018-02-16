<?php

namespace App;

use App\Support\Database\CacheQueryBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use CacheQueryBuilder;

    /**
     * Get the URL endpoint for the Player Model
     *
     * @return string
     */
    public function endpoint()
    {
        return '/players/' . $this->id;
    }

    /**
     * @return Team|null
     */
    public function getTeamAttribute()
    {
        // Get teams
        $team_link = PlayerTeam::where('player_id', $this->id)// Where the player is/was a member
        ->where('member_from', '<=', Carbon::now(), 'AND')// where is membership started in the past
        ->where(function ($query) {
            $query->where('member_to', null)// and is continuous
            ->where('member_to', '>=', Carbon::now(), 'OR'); // or ends in the future
        });


        if ($team_link->get()->count() > 1) {
            // Error?
        }

        if ($team_link->get()->count() == 0) {
            return null;
        }

        return $team_link->first()->team;
    }
}
