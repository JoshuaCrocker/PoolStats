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
    use SoftDeletes;

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
     * Get the previous team members
     *
     * @return Collection
     */
    public function getHistoricRoster()
    {
        return PlayerTeam::where('team_id', $this->id)
            ->where('member_to', '<', date('Y-m-d'))
            ->get()
            ->map(function ($pt) {
                $pt->player->link = $pt;
                return $pt->player;
            });
    }

    public function getVenueAttribute()
    {
        return TeamVenue::where('team_id', $this->id)
            ->where('venue_from', '<=', date('Y-m-d'), 'AND')
            ->where(function ($query) {
                return $query->where('venue_to', NULL, 'AND')
                    ->where('venue_to', '>=', date('Y-m-d'), 'OR');
            }, 'AND')
            ->get()
            ->map(function ($tv) {
                $tv->venue->link = $tv;
                return $tv->venue;
            })->first();
    }

    public function getHighestPerformingPlayerAttribute()
    {
        $hppRecord = HPPStat::where('team_id', $this->id)
            ->orderBy('score', 'desc')->get();

        if ($hppRecord->count() == 0) {
            return null;
        }

        return $hppRecord->first()->player;
    }

    public function getMatchesAttribute()
    {
        return LeagueMatch::where('home_team_id', $this->id)->orWhere('away_team_id', $this->id)->get();
    }

    public function getWldAttribute()
    {
        $output = [
            'wins' => 0,
            'loses' => 0,
            'draws' => 0
        ];

        $this->getCurrentRoster()->each(function ($player) use (&$output) {
            $wld = optional(WLDStat::where('player_id', $player->id))->first();

            if (!is_null($wld)) {
                $output['wins'] += $wld->wins;
                $output['loses'] += $wld->loses;
                $output['draws'] += $wld->draws;
            }
        });

        return $output;
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
                $pt->player->link = $pt;
                return $pt->player;
            });
    }
}
