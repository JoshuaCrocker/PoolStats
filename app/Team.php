<?php

namespace App;

use App\Support\Database\CacheQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * Class Team
 * @package App
 */
class Team extends Model
{
    use SoftDeletes, CacheQueryBuilder;

    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the URL endpoint for the Team Model
     *
     * @return string
     */
    public function endpoint()
    {
        return '/teams/' . $this->id;
    }

    /**
     * Get the current team members
     *
     * @return Collection
     */
    public function getCurrentRoster()
    {
        return PlayerTeam::where('team_id', $this->id)
            ->where('member_from', '<=', date('Y-m-d'), 'AND')
            ->where('member_to', NULL, 'AND')
            ->where('member_to', '>=', date('Y-m-d'), 'OR')
            ->get()
            ->map(function ($pt) {
                return $pt->player;
            });
    }

    public function getVenueAttribute() {
        // NYI - this needs to be changed to a `venue` method
        return Venue::all()->first();
    }
}
