<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = "users";

    protected $fillable = [
        'email', 'first', 'last' ,'password', 'role_id'
    ];

    protected $hidden = [
        'password', 'status_id',
    ];

}
