<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatAttendance extends Model
{
    public $timestamps = false;
    
    public function getPercentageAttribute() {
        $percent = round($this->played / max(1, $this->total), 2) * 100;
        return sprintf("%0.2f", $percent);
    }
}
