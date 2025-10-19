<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Shift;
use App\Models\Office;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'shift_id' => Shift::inRandomOrder()->first()?->id ?? Shift::factory(),
            'office_id' => Office::inRandomOrder()->first()?->id ?? Office::factory(),
            'is_wfa' => fake()->boolean(30), // 30% kemungkinan WFA
            'is_banned' => fake()->boolean(10), // 10% kemungkinan banned
        ];
    }
}
