<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'service_menu_id',
        'menu_name',
        'duration_minutes',
        'price',
        'display_order',
        'delete_flg',
    ];

    protected $casts = [
        'reservation_id' => 'integer',
        'service_menu_id' => 'integer',
        'duration_minutes' => 'integer',
        'price' => 'integer',
        'display_order' => 'integer',
        'delete_flg' => 'integer',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function serviceMenu()
    {
        return $this->belongsTo(ServiceMenu::class);
    }
}

