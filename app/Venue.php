<?php

namespace App;

use App\Support\Database\CacheQueryBuilder;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use CacheQueryBuilder;
}
