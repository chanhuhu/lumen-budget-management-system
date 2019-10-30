<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $table = 'receipts';

    protected $fillable = [
        'remark', 'cost', 'date', 'activity_id'
    ];

    protected $hidden = [
        'status_id', 'approver_id'
    ];

    public function Receipt_image()
    {
        return $this->hasMany('App\Models\Receipt_image');
    }
}
