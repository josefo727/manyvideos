<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Video>
 */
class VideoFactory extends Factory
{
    protected $model = Video::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->sentence,
            'path' => 'videos/' . $this->faker->uuid . '.mp4',
            'size' => null,
            'duration' => null,
            'resolution' => null,
            'thumbnail' => null,
        ];
    }
}
