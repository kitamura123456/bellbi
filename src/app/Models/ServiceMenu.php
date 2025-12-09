<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'name',
        'description',
        'thumbnail_image',
        'duration_minutes',
        'price',
        'category',
        'display_order',
        'is_active',
        'delete_flg',
    ];

    protected $casts = [
        'store_id' => 'integer',
        'duration_minutes' => 'integer',
        'price' => 'integer',
        'category' => 'integer',
        'display_order' => 'integer',
        'is_active' => 'integer',
        'delete_flg' => 'integer',
    ];

    // メニューカテゴリ定数
    public const CATEGORY_CUT = 1;
    public const CATEGORY_COLOR = 2;
    public const CATEGORY_PERM = 3;
    public const CATEGORY_TREATMENT = 4;
    public const CATEGORY_HEAD_SPA = 5;
    public const CATEGORY_EYELASH = 6;
    public const CATEGORY_NAIL = 7;
    public const CATEGORY_FACIAL = 8;
    public const CATEGORY_BODY = 9;
    public const CATEGORY_OTHER = 99;

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function reservationMenus()
    {
        return $this->hasMany(ReservationMenu::class);
    }
}

