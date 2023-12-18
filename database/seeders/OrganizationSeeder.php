<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organizations = [
            [
                'id' => 1,
                'name' => 'Enosis',
                'address' => '',
            ],
            [
                'id' => 2,
                'name' => 'TigerIT',
                'address' => '',
            ],[
                'id' => 3,
                'name' => 'Cefalo',
                'address' => '',
            ],
            [
                'id' => 4,
                'name' => 'LeadSoft',
                'address' => '',
            ],
            [
                'id' => 5,
                'name' => 'Kaz Software',
                'address' => '',
            ],
            [
                'id' => 6,
                'name' => 'BJIT',
                'address' => '',
            ],
            [
                'id' => 7,
                'name' => 'Dream 71 Bangladesh',
                'address' => '',
            ],
            [
                'id' => 8,
                'name' => 'Riseup Labs',
                'address' => '',
            ],
            [
                'id' => 9,
                'name' => 'Soft BD Limited',
                'address' => '',
            ],
            [
                'id' => 10,
                'name' => 'Therap Ltd Bd',
                'address' => '',
            ],
            [
                'id' => 11,
                'name' => 'SAAS solutions',
                'address' => '',
            ],
            [
                'id' => 12,
                'name' => 'Brainstation 23',
                'address' => '',
            ],
            [
                'id' => 13,
                'name' => 'Appinion BD Limited',
                'address' => '',
            ],
            [
                'id' => 14,
                'name' => 'Geeksntechnology Limited',
                'address' => '',
            ],
            [
                'id' => 15,
                'name' => 'WellDev',
                'address' => '',
            ],
            [
                'id' => 16,
                'name' => 'DataSoft',
                'address' => '',
            ],[
                'id' => 17,
                'name' => 'Apploye',
                'address' => '',
            ],
            [
                'id' => 18,
                'name' => 'Selise',
                'address' => '',
            ],
            [
                'id' => 19,
                'name' => 'Samsung R&D',
                'address' => '',
            ],
            [
                'id' => 20,
                'name' => 'Optimizely',
                'address' => '',
            ],
            [
                'id' => 21,
                'name' => 'Daraz Bangladesh',
                'address' => '',
            ],
            [
                'id' => 22,
                'name' => 'Food Panda',
                'address' => '',
            ],
            [
                'id' => 23,
                'name' => 'Pathhao',
                'address' => '',
            ],
            [
                'id' => 24,
                'name' => 'Augmedix',
                'address' => '',
            ],
            [
                'id' => 25,
                'name' => 'Field Nation',
                'address' => '',
            ],[
                'id' => 26,
                'name' => 'Grameenphone',
                'address' => '',
            ],
            [
                'id' => 27,
                'name' => 'Robi',
                'address' => '',
            ],
            [
                'id' => 28,
                'name' => 'Banglalink',
                'address' => '',
            ],
            [
                'id' => 29,
                'name' => 'Augmedix',
                'address' => '',
            ],
            [
                'id' => 30,
                'name' => 'BRAC IT',
                'address' => '',
            ],

        ];

        foreach ($organizations as $key=>$organization)
        {
            Organization::updateOrCreate([
                'id'=> $organization['id']
            ],
                $organization);
        }
    }
}
