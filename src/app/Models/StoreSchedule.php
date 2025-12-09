<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'day_of_week',
        'is_open',
        'open_time',
        'close_time',
        'delete_flg',
    ];

    protected $casts = [
        'store_id' => 'integer',
        'day_of_week' => 'integer',
        'is_open' => 'integer',
        'delete_flg' => 'integer',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}

