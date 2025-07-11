<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids, softDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
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