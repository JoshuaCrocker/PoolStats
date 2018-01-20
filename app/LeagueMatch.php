<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeagueMatch extends Model
{
    public function getNameAttribute()
    {
        return $this->venue->name .
            ' (' .
            $this->homeTeam->name .
            ' vs. ' .
            $this->awayTeam->name .
            ')';
    }

    /**
     * Get the URL endpoint for the LeagueMatch Model
     *
     * @return string
     */
    public function endpoint()
    {
        // NYI
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function homeTeam()
    {
        return $this->belongsTo(Team::class);
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class);
    }
}
