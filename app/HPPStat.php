<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HPPStat extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'stats_hpp';
}
