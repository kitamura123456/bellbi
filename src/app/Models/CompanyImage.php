<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'path',
        'sort_order',
        'delete_flg',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'sort_order' => 'integer',
        'delete_flg' => 'integer',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
