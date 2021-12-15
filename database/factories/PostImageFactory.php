<?php

namespace Database\Factories;

use App\Models\PostImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostImageFactory extends Factory
{
    use TimestampsBetween;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PostImage::class;

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
        return $this->afterCreating(function (PostImage $postImage) {
            $postImage->image .= '?random=' . $postImage->id . '&lock=' . $postImage->id;
            $postImage->save();
        });
    }
}
