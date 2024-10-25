<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = ['id'];

    public function myCourse()
    {
        return $this->belongsTo(MyCourse::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}
