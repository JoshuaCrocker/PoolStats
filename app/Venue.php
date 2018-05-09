<?php

namespace App;

use App\Support\Database\CacheQueryBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use SoftDeletes;

    public function endpoint() {
        return 'venues/' . $this->id;
    }

    public function getMatchesAttribute()
    {
        $id = $this->id;

        return LeagueMatch::where('match_date', '>', Carbon::now())
            ->get()
            ->filter(function ($match) use ($id) {
                return $match->homeTeam->venue->id == $id;
            });
    }

    public function getCurrentTeamsAttribute()
    {
        return TeamVenue::where('venue_id', $this->id)
            ->where('venue_from', '<=', date('Y-m-d'), 'AND')
            ->where(function($query) {
                $query->where('venue_to', NULL, 'AND')
                      ->where('venue_to', '>', date('Y-m-d'), 'OR');
            }, 'AND')
            ->get()
            ->map(function ($tv) {
                return [
                    'team' => $tv->team,
                    'link' => $tv
                ];
            });
    }

    // Get historic teams
    public function getHistoricTeamsAttribute()
    {
        return TeamVenue::where('venue_id', $this->id)
            ->where('venue_to', '<=', Carbon::now())
            ->get()
            ->map(function ($tv) {
                return [
                    'team' => $tv->team,
                    'link' => $tv
                ];
            });
    }

}
