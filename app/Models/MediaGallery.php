<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaGallery extends Model
{
    use HasFactory;

    protected $table = 'media_galleries';

    protected $fillable = [
        'event_id',
        'title',
        'media_type',
        'file_path',
        'category',
        'department',
        'year',
        'uploaded_by',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function savedByUsers()
    {
        return $this->hasMany(SavedMedia::class, 'media_id');
    }
}
