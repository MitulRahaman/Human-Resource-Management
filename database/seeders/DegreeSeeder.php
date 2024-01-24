<?php

namespace Database\Seeders;

use App\Models\Degree;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $degrees = [
            [
                'id' => 1,
                'name' => 'SSC',
                'description' => '',
            ],
            [
                'id' => 2,
                'name' => 'HSC',
                'description' => '',
            ],
            [
                'id' => 3,
                'name' => 'BSC',
                'description' => '',
            ],
            [
                'id' => 4,
                'name' => 'BA',
                'description' => '',
            ],
            [
                'id' => 5,
                'name' => 'MSC',
                'description' => '',
            ],[
                'id' => 6,
                'name' => 'MA',
                'description' => '',
            ],[
                'id' => 7,
                'name' => 'PhD',
                'description' => '',
            ],
            [
                'id' => 8,
                'name' => 'Postdoc',
                'description' => '',
            ],
            [
                'id' => 9,
                'name' => 'BCom',
                'description' => '',
            ],

        ];

        foreach ($degrees as $key=>$degree)
        {
            Degree::updateOrCreate([
                'id'=> $degree['id']
            ],
                $degree);
        }

    }
}
