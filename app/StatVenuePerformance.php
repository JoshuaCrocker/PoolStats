<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatVenuePerformance extends Model
{
    public $timestamps = false;
    
    public function getPercentageAttribute() {
        $percent = round($this->won / max(1, $this->played), 2) * 100;
        return sprintf("%0.2f", $percent);
    }

    public function venue() {
        return $this->belongsTo(Venue::class);
    }
}
