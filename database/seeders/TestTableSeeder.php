<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;

class TestTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0, $j = 50; $i < $j; $i++) {
            $user = User::factory()->create();
            $project = Project::factory()->createForTaskTests();
            Task::factory()->createForTaskTests($user->id, $project->id);

        }
    }
}
