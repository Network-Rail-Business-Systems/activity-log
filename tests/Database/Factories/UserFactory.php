<?php

namespace NetworkRailBusinessSystems\ActivityLog\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use NetworkRailBusinessSystems\ActivityLog\Tests\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
        ];
    }
}
