<?php

namespace App;

use App\Support\Database\CacheQueryBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use CacheQueryBuilder, SoftDeletes;

    public function getMatchesAttribute()
    {
        $id = $this->id;

        return LeagueMatch::where('match_date', '>', Carbon::now())
            ->get()
            ->filter(function ($match) use ($id) {
//                dd($match->homeTeam->venue);

                return $match->homeTeam->venue->id == $id;
            });
    }

}
