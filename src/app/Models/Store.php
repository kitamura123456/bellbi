<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'store_type',
        'postal_code',
        'address',
        'tel',
        'description',
        'thumbnail_image',
        'accepts_reservations',
        'cancel_deadline_hours',
        'max_concurrent_reservations',
        'delete_flg',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'store_type' => 'integer',
        'accepts_reservations' => 'integer',
        'cancel_deadline_hours' => 'integer',
        'max_concurrent_reservations' => 'integer',
        'delete_flg' => 'integer',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function jobPosts()
    {
        return $this->hasMany(JobPost::class);
    }

    public function staffs()
    {
        return $this->hasMany(StoreStaff::class);
    }

    public function serviceMenus()
    {
        return $this->hasMany(ServiceMenu::class);
    }

    public function schedules()
    {
        return $this->hasMany(StoreSchedule::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function reservationBlocks()
    {
        return $this->hasMany(ReservationBlock::class);
    }
}


