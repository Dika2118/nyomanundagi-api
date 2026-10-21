<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\SiteStat;
use Illuminate\Database\Seeder;

class SiteStatSeeder extends Seeder
{
    public function run(): void
    {
        $projectCount = (string) (Project::count() ?: 6);

        $stats = [
            [
                'key'        => 'customers',
                'label'      => 'Customers',
                'value'      => '35000',
                'suffix'     => 'k+',
                'sort_order' => 1,
            ],
            [
                'key'        => 'years_founded',
                'label'      => 'Years Founded',
                'value'      => '2010',
                'suffix'     => null,
                'sort_order' => 2,
            ],
            [
                'key'        => 'projects',
                'label'      => 'Projects',
                'value'      => $projectCount,
                'suffix'     => '+',
                'sort_order' => 3,
            ],
            [
                'key'        => 'awards',
                'label'      => 'Design Awards',
                'value'      => '4',
                'suffix'     => null,
                'sort_order' => 4,
            ],
        ];

        foreach ($stats as $stat) {
            SiteStat::updateOrCreate(['key' => $stat['key']], $stat);
        }
    }
}
