<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ticket';
    
    protected $fillable = [
        'no_tiket',
        'user_id',
        'kategori_id',
        'approved_by',
        'approved_at',
        'assigned_to',
        'assigned_at',
        'last_comment_by',
        'last_comment_at',
        'closed_by',
        'closed_at',
        'judul',
        'masalah',
        'status',
        'urgensi',
        'lampiran'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'assigned_at' => 'datetime',
        'last_comment_at' => 'datetime',
        'closed_at' => 'datetime',
        'lampiran' => 'array'
    ];

    /**
     * Get the user who created this ticket
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the kategori for this ticket
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Get the user who approved this ticket
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the teknisi assigned to this ticket
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who last commented on this ticket
     */
    public function lastCommentBy()
    {
        return $this->belongsTo(User::class, 'last_comment_by');
    }

    /**
     * Get the user who closed this ticket
     */
    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    /**
     * Get the comments for this ticket
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the history records for this ticket
     */
    public function histories()
    {
        return $this->hasMany(History::class);
    }
    
    /**
     * Get the feedback for this ticket
     */
    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }

    /**
     * Scope a query to only include tickets with Baru status
     */
    public function scopeBaru($query)
    {
        return $query->where('status', 'Baru');
    }

    /**
     * Scope a query to only include tickets with Diproses status
     */
    public function scopeDiproses($query)
    {
        return $query->where('status', 'Diproses');
    }

    /**
     * Scope a query to only include tickets with Disposisi status
     */
    public function scopeDisposisi($query)
    {
        return $query->where('status', 'Disposisi');
    }

    /**
     * Scope a query to only include tickets with Selesai status
     */
    public function scopeSelesai($query)
    {
        return $query->where('status', 'Selesai');
    }
}