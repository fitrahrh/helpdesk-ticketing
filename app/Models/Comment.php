<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ticket_id',
        'user_id',
        'pesan',
        'lampiran'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'lampiran' => 'json'
    ];

    /**
     * Get the ticket that owns the comment
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the user who created the comment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get users who have read this comment
     */
    public function readBy()
    {
        return $this->belongsToMany(User::class, 'comment_reads')
                    ->withPivot('read_at')
                    ->withTimestamps();
    }

    /**
     * Get the attachment URLs
     */
    public function getAttachmentUrlsAttribute()
    {
        if (!$this->lampiran) {
            return [];
        }

        $urls = [];
        foreach ($this->lampiran as $attachment) {
            if (isset($attachment['path'])) {
                $urls[] = [
                    'name' => $attachment['name'] ?? 'Attachment',
                    'url' => asset('storage/' . $attachment['path']),
                    'type' => $attachment['type'] ?? 'application/octet-stream',
                    'size' => $attachment['size'] ?? 0
                ];
            }
        }

        return $urls;
    }
}