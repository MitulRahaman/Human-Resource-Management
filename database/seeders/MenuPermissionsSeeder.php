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
