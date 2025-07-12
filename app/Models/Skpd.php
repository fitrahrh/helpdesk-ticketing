<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SKPD extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'skpds';
    
    protected $fillable = [
        'name',
        'singkatan'
    ];

    public function bidangs()
    {
        return $this->hasMany(Bidang::class);
    }

    public function kategoris()
    {
        return $this->hasMany(Kategori::class, 'skpd_id');
    }

}