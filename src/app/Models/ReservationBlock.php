<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationBlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'staff_id',
        'block_date',
        'start_time',
        'end_time',
        'reason',
        'delete_flg',
    ];

    protected $casts = [
        'store_id' => 'integer',
        'staff_id' => 'integer',
        'block_date' => 'date',
        'delete_flg' => 'integer',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function staff()
    {
        return $this->belongsTo(StoreStaff::class, 'staff_id');
    }
}

