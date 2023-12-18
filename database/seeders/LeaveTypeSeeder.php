<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $leaveTypes = [
            [
                'id' => 1,
                'name' => 'Sick leave',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 2,
                'name' => 'Causal Leave',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 3,
                'name' => 'Annual Leave',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 4,
                'name' => 'Maternity Leave',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 5,
                'name' => 'Paternity Leave',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 6,
                'name' => 'Unpaid Leave',
                'status' => Config::get('variable_constants.activation.active'),
            ],

        ];

        foreach ($leaveTypes as $key=>$leaveType)
        {
            LeaveType::updateOrCreate([
                'id'=> $leaveType['id']
            ],
                $leaveType);
        }
    }
}
