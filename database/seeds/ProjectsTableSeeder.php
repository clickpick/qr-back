<?php

use App\Project;
use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $project = Project::create([
            'name' => 'Хакатон',
            'description' => 'Внутренний квест',
            'prize' => 'panda',
            'is_active' => true
        ]);
    }
}
