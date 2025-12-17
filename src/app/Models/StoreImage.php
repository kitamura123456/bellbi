<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'path',
        'sort_order',
        'delete_flg',
    ];

    protected $casts = [
        'store_id' => 'integer',
        'sort_order' => 'integer',
        'delete_flg' => 'integer',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
