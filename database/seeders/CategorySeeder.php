<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Cultural Events',
                'description' => 'Dance, music, drama, fashion shows, and artistic performance competitions.',
                'icon' => 'palette'
            ],
            [
                'name' => 'Technical Fests',
                'description' => 'Hackathons, coding challenges, robotics competitions, and tech symposiums.',
                'icon' => 'code'
            ],
            [
                'name' => 'Sports Meets',
                'description' => 'Track & field events, football, basketball, cricket, and indoor sports tournaments.',
                'icon' => 'trophy'
            ],
            [
                'name' => 'Annual Day Functions',
                'description' => 'College anniversary, prize distribution, and annual celebration functions.',
                'icon' => 'star'
            ],
            [
                'name' => 'Workshops & Seminars',
                'description' => 'Academic lectures, industry expert talks, skill-building workshops, and webinars.',
                'icon' => 'book'
            ],
            [
                'name' => 'Intercollegiate Competitions',
                'description' => 'Multi-college tournaments, debates, quizzes, and inter-university fests.',
                'icon' => 'users'
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'icon' => $cat['icon']
                ]
            );
        }
    }
}
