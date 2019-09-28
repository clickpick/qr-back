<?php

use App\Project;
use Illuminate\Database\Seeder;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws DiskDoesNotExist
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function run()
    {
        $project = Project::create([
            'name' => 'Борьба за тигра',
            'description' => 'Спасите тигра',
            'prize' => 'panda',
            'goal_funds' => 2387000,
            'raised_funds' => 1026000,
            'is_active' => true
        ]);

        $project->addMedia(storage_path('app/seed/panda.png'))->toMediaCollection('poster');
        $project->addMedia(storage_path('app/seed/banner.png'))->toMediaCollection('banner');
    }
}
