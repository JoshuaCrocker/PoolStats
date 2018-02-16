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
     * @return PlayerTeam|null
     */
    public function getMembershipAttribute()
    {
        return $this->getMembership();
    }

    /**
     * @return Team|null
     */
    public function getTeamAttribute()
    {
        return $this->getTeam();
    }

    // TODO test - historic
    public function getTeam(Carbon $when = null)
    {
        if ($when == null) {
            $when = Carbon::now();
        }

        return $this->getMembership($when) == null ? null : $this->getMembership($when);
    }

    // TODO test - historic
    public function getMembership(Carbon $when = null)
    {
        if ($when == null) {
            $when = Carbon::now();
        }

        $team_link = PlayerTeam::where('player_id', $this->id)// Where the player is/was a member
        ->where('member_from', '<=', $when, 'AND')// where is membership started in the past
        ->where(function ($query) use ($when) {
            $query->where('member_to', null)// and is continuous
            ->where('member_to', '>=', $when, 'OR'); // or ends in the future
        });

        // TODO error handling

        if ($team_link->get()->count() == 0) {
            return null;
        }

        return $team_link->get()->first();
    }
}
