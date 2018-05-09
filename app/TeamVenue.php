<?php

namespace App;

use App\Support\Database\CacheQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamVenue extends Model
{
    use SoftDeletes;

    public function endpoint()
    {
        return 'venues/' . $this->venue->id . '/membership/' . $this->id;
    }

    public function getVenueToAttribute($value)
    {
        if ($value == null) {
            return 'Current';
        }

        return $value;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}
