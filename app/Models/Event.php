<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category_id',
        'venue',
        'capacity',
        'available_slots',
        'start_date',
        'end_date',
        'registration_deadline',
        'organizer_id',
        'organizing_department',
        'status',
        'banner_image',
        'rulebook_file',
        'hashtags',
        'rejection_reason',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_deadline' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function activeRegistrations()
    {
        return $this->hasMany(Registration::class)->whereIn('status', ['registered', 'attended']);
    }

    public function waitlists()
    {
        return $this->hasMany(Waitlist::class)->orderBy('position', 'asc');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class)->where('is_approved', true);
    }

    public function mediaGalleries()
    {
        return $this->hasMany(MediaGallery::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function isFull(): bool
    {
        return $this->available_slots <= 0;
    }

    public function averageRating(): float
    {
        return round($this->feedbacks()->avg('overall_rating') ?? 0, 1);
    }
}
