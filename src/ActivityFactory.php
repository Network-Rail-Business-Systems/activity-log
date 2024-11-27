<?php

namespace NetworkRailBusinessSystems\ActivityLog;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition(): array
    {
        return [
            'created_at' => $this->faker->date,
            'description' => $this->faker->word,
        ];
    }

    public function forSubject(Model $subject): self
    {
        return $this->state([
            'subject_id' => $subject->id,
            'subject_type' => $subject::class,
        ]);
    }
}
