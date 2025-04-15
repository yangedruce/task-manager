<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::create([
            'title' => 'First Task',
            'is_completed' => false, 
        ]);

        Task::create([
            'title' => 'Second Task',
            'is_completed' => true, 
        ]);
    }
}
