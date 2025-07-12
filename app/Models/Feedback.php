<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
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
        'rating',
        'komentar'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'rating' => 'integer'
    ];

    /**
     * Get the ticket associated with this feedback
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the user who created this feedback
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the feedback rating as stars
     */
    public function getRatingStarsAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<i class="fa fa-star text-warning"></i>';
            } else {
                $stars .= '<i class="fa fa-star-o text-muted"></i>';
            }
        }
        return $stars;
    }
}