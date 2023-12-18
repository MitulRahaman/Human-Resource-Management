<?php

namespace Database\Seeders;

use App\Models\Institute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class InstituteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $institutes = [
            [
                'id' => 1,
                'name' => 'Bangladesh University of Engineering & Technology',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 2,
                'name' => 'Dhaka University',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 3,
                'name' => 'Chittagong University of Engineering and Technology',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 4,
                'name' => 'Rajshahi University of Engineering and Technology',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 5,
                'name' => 'Shahjalal University of Science and Technology',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 6,
                'name' => 'Khulna University of Engineering and Technology',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 7,
                'name' => 'North South University',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 8,
                'name' => 'BRAC University',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 9,
                'name' => 'Independent University Bangladesh',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 10,
                'name' => 'Ahsanullah University of Science and Technology',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 11,
                'name' => 'East West University',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 12,
                'name' => 'American International University-Bangladesh',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 13,
                'name' => 'Notre Dame College',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 14,
                'name' => 'Dhaka City College',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 15,
                'name' => 'Viqarunnisa Noon School and College',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 16,
                'name' => 'Milestone College',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 17,
                'name' => 'Rajuk Uttara Model College',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 18,
                'name' => 'Adamjee Cantonment College',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 19,
                'name' => 'Holy Cross College',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 20,
                'name' => 'Birshreshtha Noor Mohammad Public College',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 21,
                'name' => 'Dhaka Cantonment Girls Public School and College',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 22,
                'name' => 'University of Liberal Arts Bangladesh',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 23,
                'name' => 'International University of Business Agriculture and Technology',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 24,
                'name' => 'United International University',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 25,
                'name' => 'Independent University, Bangladesh',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 26,
                'name' => 'National University',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 27,
                'name' => 'Green University',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 28,
                'name' => 'University of Asia Pacific',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 29,
                'name' => 'Daffodil International University',
                'address' => '',
                'status' => Config::get('variable_constants.activation.active'),
            ],
        ];

        foreach ($institutes as $key=>$institute)
        {
            Institute::updateOrCreate([
                'id'=> $institute['id']
            ],
                $institute);
        }
    }
}
