<?php

namespace App;

use App\Support\Database\CacheQueryBuilder;
use Illuminate\Database\Eloquent\Model;

class TeamVenue extends Model
{
    use CacheQueryBuilder;
}
