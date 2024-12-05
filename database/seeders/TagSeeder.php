<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $videoTags = [
            'sports',
            'adventure',
            'gaming',
            'travel',
            'food',
            'technology',
            'health',
            'fitness',
            'education',
            'entertainment',
            'nature',
            'comedy',
            'music',
            'fashion',
            'lifestyle',
            'news',
            'science',
            'history',
            'art',
            'motivation'
        ];
        Tag::factory(count($videoTags))
            ->sequence(fn ($sequence) => ['name' => $videoTags[$sequence->index]])
            ->create();
    }
}
