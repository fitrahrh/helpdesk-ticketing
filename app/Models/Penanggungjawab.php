<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penanggungjawab extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penanggungjawab';
    
    protected $fillable = [
        'user_id',
        'kategori_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}