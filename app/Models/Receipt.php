<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $table = 'receipts';

    protected $fillable = [
        'remark', 'cost', 'date', 'activity_id', 'accountant_id'
    ];

    protected $hidden = [
        'status_id',
    ];

    public function receipt_image()
    {
        return $this->hasMany('App\Models\Receipt_image');
    }

    public function activity()
    {
        return $this->belongsTo('App\Models\Activity');
    }
}
