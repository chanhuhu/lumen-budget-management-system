<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    // all status defined in the database
    public static $ACTIVE       = 1;
    public static $INACTIVE     = 2;
    public static $DELETED      = 3;
    public static $WAITING      = 4;
    public static $QUEUED       = 5;
    public static $PENDING      = 6;
    public static $PROCESSING   = 7;
    public static $DRAFT        = 8;
}
