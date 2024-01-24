<?php

namespace Database\Seeders;

use App\Models\MenuPermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class MenuPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu_permissions = [
            [
                'id' => 1,
                'menu_id' => 13,
                'permission_id' => 42,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 2,
                'menu_id' => 13,
                'permission_id' => 43,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 3,
                'menu_id' => 14,
                'permission_id' => 43,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 4,
                'menu_id' => 15,
                'permission_id' => 42,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 5,
                'menu_id' => 16,
                'permission_id' => 25,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 6,
                'menu_id' => 16,
                'permission_id' => 27,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 7,
                'menu_id' => 17,
                'permission_id' => 25,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 8,
                'menu_id' => 18,
                'permission_id' => 27,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 9,
                'menu_id' => 19,
                'permission_id' => 3,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 10,
                'menu_id' => 19,
                'permission_id' => 4,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 11,
                'menu_id' => 20,
                'permission_id' => 3,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 12,
                'menu_id' => 21,
                'permission_id' => 4,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 13,
                'menu_id' => 26,
                'permission_id' => 51,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 14,
                'menu_id' => 26,
                'permission_id' => 49,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 15,
                'menu_id' => 27,
                'permission_id' => 51,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 16,
                'menu_id' => 28,
                'permission_id' => 49,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 17,
                'menu_id' => 22,
                'permission_id' => 2,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 18,
                'menu_id' => 22,
                'permission_id' => 6,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 19,
                'menu_id' => 23,
                'permission_id' => 2,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 20,
                'menu_id' => 24,
                'permission_id' => 6,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 21,
                'menu_id' => 2,
                'permission_id' => 30,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 22,
                'menu_id' => 2,
                'permission_id' => 39,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 23,
                'menu_id' => 2,
                'permission_id' => 15,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 24,
                'menu_id' => 2,
                'permission_id' => 14,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 25,
                'menu_id' => 2,
                'permission_id' => 33,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 26,
                'menu_id' => 2,
                'permission_id' => 11,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 27,
                'menu_id' => 2,
                'permission_id' => 8,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 28,
                'menu_id' => 2,
                'permission_id' => 36,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 29,
                'menu_id' => 2,
                'permission_id' => 7,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 30,
                'menu_id' => 2,
                'permission_id' => 5,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 31,
                'menu_id' => 3,
                'permission_id' => 30,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 32,
                'menu_id' => 4,
                'permission_id' => 39,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 33,
                'menu_id' => 5,
                'permission_id' => 15,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 34,
                'menu_id' => 6,
                'permission_id' => 14,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 35,
                'menu_id' => 7,
                'permission_id' => 33,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 36,
                'menu_id' => 8,
                'permission_id' => 11,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 37,
                'menu_id' => 9,
                'permission_id' => 8,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 38,
                'menu_id' => 10,
                'permission_id' => 36,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 39,
                'menu_id' => 11,
                'permission_id' => 7,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 40,
                'menu_id' => 12,
                'permission_id' => 5,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 41,
                'menu_id' => 24,
                'permission_id' => 2,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 42,
                'menu_id' => 29,
                'permission_id' => 52,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 43,
                'menu_id' => 30,
                'permission_id' => 52,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 44,
                'menu_id' => 31,
                'permission_id' => 52,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 45,
                'menu_id' => 21,
                'permission_id' => 3,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 46,
                'menu_id' => 15,
                'permission_id' => 43,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 47,
                'menu_id' => 18,
                'permission_id' => 25,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 48,
                'menu_id' => 28,
                'permission_id' => 51,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 49,
                'menu_id' => 32,
                'permission_id' => 54,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 50,
                'menu_id' => 32,
                'permission_id' => 56,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 51,
                'menu_id' => 33,
                'permission_id' => 54,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 52,
                'menu_id' => 33,
                'permission_id' => 56,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 53,
                'menu_id' => 34,
                'permission_id' => 54,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 54,
                'menu_id' => 13,
                'permission_id' => 57,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 55,
                'menu_id' => 15,
                'permission_id' => 57,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 56,
                'menu_id' => 16,
                'permission_id' => 58,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 57,
                'menu_id' => 18,
                'permission_id' => 58,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 58,
                'menu_id' => 19,
                'permission_id' => 59,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 59,
                'menu_id' => 21,
                'permission_id' => 59,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 60,
                'menu_id' => 26,
                'permission_id' => 60,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 61,
                'menu_id' => 28,
                'permission_id' => 60,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 62,
                'menu_id' => 22,
                'permission_id' => 61,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 63,
                'menu_id' => 24,
                'permission_id' => 61,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 64,
                'menu_id' => 29,
                'permission_id' => 62,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 65,
                'menu_id' => 31,
                'permission_id' => 62,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 66,
                'menu_id' => 32,
                'permission_id' => 63,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 67,
                'menu_id' => 34,
                'permission_id' => 63,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 68,
                'menu_id' => 2,
                'permission_id' => 64,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 69,
                'menu_id' => 2,
                'permission_id' => 65,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 70,
                'menu_id' => 2,
                'permission_id' => 66,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 71,
                'menu_id' => 2,
                'permission_id' => 67,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 72,
                'menu_id' => 2,
                'permission_id' => 68,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 73,
                'menu_id' => 2,
                'permission_id' => 69,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 74,
                'menu_id' => 2,
                'permission_id' => 70,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 75,
                'menu_id' => 2,
                'permission_id' => 71,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 76,
                'menu_id' => 2,
                'permission_id' => 72,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 77,
                'menu_id' => 2,
                'permission_id' => 73,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 78,
                'menu_id' => 3,
                'permission_id' => 64,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 79,
                'menu_id' => 4,
                'permission_id' => 65,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 80,
                'menu_id' => 6,
                'permission_id' => 66,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 81,
                'menu_id' => 7,
                'permission_id' => 67,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 82,
                'menu_id' => 8,
                'permission_id' => 68,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 83,
                'menu_id' => 5,
                'permission_id' => 69,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 84,
                'menu_id' => 9,
                'permission_id' => 70,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 85,
                'menu_id' => 10,
                'permission_id' => 71,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 86,
                'menu_id' => 11,
                'permission_id' => 72,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 87,
                'menu_id' => 12,
                'permission_id' => 73,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 88,
                'menu_id' => 35,
                'permission_id' => 74,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 89,
                'menu_id' => 35,
                'permission_id' => 75,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 90,
                'menu_id' => 36,
                'permission_id' => 78,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 91,
                'menu_id' => 36,
                'permission_id' => 79,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 92,
                'menu_id' => 36,
                'permission_id' => 80,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 93,
                'menu_id' => 37,
                'permission_id' => 80,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 94,
                'menu_id' => 38,
                'permission_id' => 78,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 95,
                'menu_id' => 38,
                'permission_id' => 79,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 96,
                'menu_id' => 38,
                'permission_id' => 80,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 97,
                'menu_id' => 39,
                'permission_id' => 82,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 98,
                'menu_id' => 39,
                'permission_id' => 83,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 99,
                'menu_id' => 39,
                'permission_id' => 84,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 100,
                'menu_id' => 40,
                'permission_id' => 84,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 101,
                'menu_id' => 41,
                'permission_id' => 82,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 102,
                'menu_id' => 41,
                'permission_id' => 83,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 103,
                'menu_id' => 41,
                'permission_id' => 84,
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 104,
                'menu_id' => 25,
                'permission_id' => 45,
                'status' => Config::get('variable_constants.activation.active'),
            ],
        ];

        foreach ($menu_permissions as $key=>$mp)
        {
            MenuPermission::updateOrCreate([
                'id'=> $mp['id']
            ],
                $mp);
        }
    }
}
