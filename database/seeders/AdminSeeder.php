<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        if (count(User::where('email', 'admin@admin.com')->get()) == 0) {
            User::create([
                'name'              => 'Admin',
                'email'             => 'admin@admin.com',
                'email_verified_at' => '2025-02-09 23:14:29',
                'password'          => bcrypt('password'),
                'remember_token'    => Str::random(60),
                'role'              => 1
            ]);

            $user = User::create([
                'name'              => 'User',
                'email'             => 'user@user.com',
                'email_verified_at' => '2025-02-09 23:14:29',
                'password'          => bcrypt('password'),
                'remember_token'    => Str::random(60),
                'role'              => 2
            ]);
            
            $project = Project::factory()->createForTaskTests();
            Task::factory()->createForTaskTests($user->id, $project->id);
        }
    }
}
