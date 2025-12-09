<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreStaff extends Model
{
    use HasFactory;

    protected $table = 'store_staffs';

    protected $fillable = [
        'store_id',
        'name',
        'display_order',
        'is_active',
        'delete_flg',
    ];

    protected $casts = [
        'store_id' => 'integer',
        'display_order' => 'integer',
        'is_active' => 'integer',
        'delete_flg' => 'integer',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'staff_id');
    }
}

