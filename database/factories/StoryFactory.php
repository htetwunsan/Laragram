<?php

namespace Database\Factories;

use App\Models\Story;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoryFactory extends Factory
{
    use TimestampsBetween;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Story::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'image' => 'https://loremflickr.com/1080/1080'
//            'image' => $this->faker->imageUrl(1080, 1080, 'cats'),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Story $story) {
            $story->image .= '?random=' . $story->id . '&lock=' . $story->id;
            $story->save();
        });
    }
}
