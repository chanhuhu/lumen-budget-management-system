<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = "users";

    protected $fillable = [
        'email', 'first', 'last' ,'password', 'role_id', 'status_id'
    ];

    protected $hidden = [
        'password', 'updated_at', 'created_at'
    ];

    public function activities()
    {
        return $this->belongsToMany('App\Models\Activity', 'user_activity');
    }

}
