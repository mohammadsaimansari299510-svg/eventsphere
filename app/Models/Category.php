<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'icon'];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get the FontAwesome icon class for the category.
     */
    public function getIconClassAttribute()
    {
        $slug = strtolower($this->slug ?? \Illuminate\Support\Str::slug($this->name));

        if (str_contains($slug, 'cultural') || str_contains($slug, 'art') || str_contains($slug, 'music') || str_contains($slug, 'dance') || str_contains($slug, 'drama')) {
            return 'masks-theater';
        }
        if (str_contains($slug, 'tech') || str_contains($slug, 'hack') || str_contains($slug, 'code') || str_contains($slug, 'robot') || str_contains($slug, 'dev')) {
            return 'laptop-code';
        }
        if (str_contains($slug, 'sport') || str_contains($slug, 'athletic') || str_contains($slug, 'tournament') || str_contains($slug, 'football') || str_contains($slug, 'cup')) {
            return 'trophy';
        }
        if (str_contains($slug, 'annual') || str_contains($slug, 'convocation') || str_contains($slug, 'gala') || str_contains($slug, 'function')) {
            return 'graduation-cap';
        }
        if (str_contains($slug, 'workshop') || str_contains($slug, 'seminar') || str_contains($slug, 'bootcamp') || str_contains($slug, 'webinar') || str_contains($slug, 'talk')) {
            return 'chalkboard-user';
        }
        if (str_contains($slug, 'intercollegiate') || str_contains($slug, 'competition') || str_contains($slug, 'meet') || str_contains($slug, 'contest')) {
            return 'people-group';
        }

        if (!empty($this->icon)) {
            $legacyMap = [
                'palette' => 'masks-theater',
                'code' => 'laptop-code',
                'trophy' => 'trophy',
                'star' => 'graduation-cap',
                'book' => 'chalkboard-user',
                'users' => 'people-group',
            ];
            return $legacyMap[$this->icon] ?? $this->icon;
        }

        return 'calendar-check';
    }
}
