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


        $facts = [
            'Дом амурского тигра — кедрово-широколиственные леса Дальнего Востока. Из-за пожаров и нелегальных рубок площадь таких лесов за последние 100 лет сократилась в 2 раза.',
            'На черном рынке мертвый тигр стоит дорого. Сдержать натиск браконьеров могут лишь специально обученные и хорошо оснащенные антибраконьерские бригады.',
            'В рамках проекта предполагается создать межхозяйственную оперативную группу из 3-4 производственных инспекторов, провести их обучение и оснастить их необходимым техническим снаряжением и оборудованием.',
            'Проект будет реализован под руководством руководителя отдела по редким видам Амурского филиала WWF России — Павла Фоменко.',
            'Антибраконьерские группа сможет работать на территории сразу нескольких охотхозяйств и тем самым сможет значительно усилить борьбу с браконьерством.'
        ];

        $project = Project::create([
            'name' => 'Борьба за тигра',
            'description' => 'Спасите тигра',
            'prize' => 'panda',
            'goal_funds' => 2387000,
            'raised_funds' => 1026000,
            'is_active' => true,
            'status' => Project::APPROVED
        ]);

        $project->addMedia(storage_path('app/seed/panda.png'))->preservingOriginal()->toMediaCollection('poster');
        $project->addMedia(storage_path('app/seed/banner.png'))->preservingOriginal()->toMediaCollection('banner');

        foreach ($facts as $fact) {
            $project->projectFacts()->create([
                'text' => $fact
            ]);
        }
    }
}
