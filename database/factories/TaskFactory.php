<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition($userId=null, $projectId=null): array
    {
        return [
            'title' => Str::random(10),
            'description' => fake()->paragraph(),
            'text' => fake()->paragraph(),
            'deadline' => Carbon::now()->addDays(5)->toDateTimeString(),
            'project_id' => $projectId,
            'user_id' => $userId,
            'dlmessage' => '',
            'status' => 'todo'
        ];
    }

    public function createForTaskTests($userId, $projectId)
    {
        return $this->create($this->definition($userId, $projectId));
    }

}
