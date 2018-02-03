<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeagueMatch extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'match_date'
    ];

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
        return '/matches/' . $this->id;
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
