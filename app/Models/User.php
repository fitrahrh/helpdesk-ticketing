<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'password',
    'role_id',
    'jabatan_id', // Pastikan field ini ada
    'nip',
    'no_hp',
    'telegram_id',
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the role associated with the user
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the SKPD associated with the user
     */
    public function skpd()
    {
        return $this->belongsTo(SKPD::class);
    }

    /**
     * Get the Bidang associated with the user
     */
    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    /**
     * Get the Jabatan associated with the user
     */
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    /**
     * Get the tickets associated with the user
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function penanggungjawabs()
    {
        return $this->hasMany(Penanggungjawab::class);
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission($permission)
    {
        if (!$this->role) {
            return false;
        }

        $permissions = $this->role->hak_akses;
        
        return in_array($permission, $permissions) || in_array('all', $permissions);
    }

}