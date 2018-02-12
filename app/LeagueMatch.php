<?php

namespace App;

use App\Support\Database\CacheQueryBuilder;
use Illuminate\Database\Eloquent\Model;

class LeagueMatch extends Model
{
    use CacheQueryBuilder;

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

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($match) {
            $match->frames->each->delete();
        });
    }

    public function getNameAttribute()
    {
        return $this->venue->name .
            ' (' .
            $this->homeTeam->name .
            ' vs. ' .
            $this->awayTeam->name .
            ')';
    }

    public function getNextFrameNumber()
    {
        $highestFrameNumber = $this->fresh()->frames->map(function ($frame) {
            return $frame->frame_number;
        })->max();

        return ((int)$highestFrameNumber) + 1;
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

    public function frames()
    {
        return $this->hasMany(LeagueFrame::class);
    }
}
