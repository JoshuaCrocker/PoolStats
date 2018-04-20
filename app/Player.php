<?php

namespace App;

use App\Support\Database\CacheQueryBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    use SoftDeletes;

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
        return $this->findMembership();
    }

    public function findMembership(Carbon $when = null)
    {
        if ($when == null) {
            $when = Carbon::now();
        }

        $team_link = PlayerTeam::where('player_id', $this->id)// Where the player is/was a member
        ->where('member_from', '<=', $when, 'AND')// where is membership started in the past
        ->where(function ($query) use ($when) {
            $query->where('member_to', null)// and is continuous
            ->orWhere('member_to', '>=', $when); // or ends in the future
        });

        // TODO error handling

        if ($team_link->get()->count() == 0) {
            return null;
        }

        return $team_link->get()->first();
    }

    // TODO test - historic

    /**
     * @return Team|null
     */
    public function getTeamAttribute()
    {
        return $this->findTeam();
    }

    // TODO test - historic

    public function findTeam(Carbon $when = null)
    {
        if ($when == null) {
            $when = Carbon::now();
        }

        $membership = $this->findMembership($when);

        if ($membership === null) {
            return null;
        }

        return $membership->team;
    }

    public function getMembershipsAttribute()
    {
        return PlayerTeam::where('player_id', $this->id)->get();
    }
}
