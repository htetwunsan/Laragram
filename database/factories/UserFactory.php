<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    use TimestampsBetween;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $email = $this->faker->unique()->email();
        $name = $this->faker->name();

        return [
            'name' => $name,
            'email' => $email,
            'birthday' => $this->faker->date('Y-m-d', now()->sub('year', 5)),
            'profile_image' => null,
            'website' => $this->faker->url(),
            'bio' => $this->faker->paragraph(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'gender' => 'prefer not to say',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10)
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function addImage()
    {
        return $this->state(function (array $attributes) {
            return [
                'profile_image' => $this->faker->imageUrl(320, 320)
//                'profile_image' => 'profile_images/' . $this->faker->image('public/storage/profile_images', 320, 320, $attributes['name'], false),
            ];
        });
    }
}
