<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kategori extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'skpd_id'
    ];

    public function skpd()
    {
        return $this->belongsTo(SKPD::class);
    }
    
}