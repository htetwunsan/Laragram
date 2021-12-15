<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Model;

trait TimestampsBetween
{
    public function timestampsBetween($startDate, $endDate = 'now')
    {
        return $this->state(function (array $attributes) use ($startDate, $endDate) {
            $time = $this->faker->dateTimeBetween($startDate, $endDate);
            return [
                'created_at' => $time,
                'updated_at' => $time
            ];
        });
    }

    public function parentTimestampsBetween($endDate = 'now')
    {
        return $this->state(function (array $attributes, Model $model) use ($endDate) {
            $time = $this->faker->dateTimeBetween($model->created_at, $endDate);
            return [
                'created_at' => $time,
                'updated_at' => $time
            ];
        });
    }
}
