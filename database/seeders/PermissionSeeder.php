<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'id' => 1,
                'slug' => 'manageEmployee',
                'name' => 'Manage Employee',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 2,
                'slug' => 'applyLeave',
                'name' => 'Apply leave',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 3,
                'slug' => 'requestRequisition',
                'name' => 'Requests for Requisition',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 4,
                'slug' => 'manageRequisition',
                'name' => 'Manage Requisition Requests',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 5,
                'slug' => 'manageRoles',
                'name' => 'Manage Roles',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 6,
                'slug' => 'manageLeaves',
                'name' => 'Manage Leaves',
                'status' => Config::get('variable_constants.activation.active'),
            ],

            [
                'id' => 7,
                'slug' => 'manageLeaveTypes',
                'name' => 'Manage Leave Types',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 8,
                'slug' => 'manageDesignations',
                'name' => 'Manage Designations',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 9,
                'slug' => 'editDesignation',
                'name' => 'Edit Designation',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 10,
                'slug' => 'addDesignation',
                'name' => 'Add Designation',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 11,
                'slug' => 'manageDepartment',
                'name' => 'Manage Departments',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 12,
                'slug' => 'editDepartment',
                'name' => 'Edit Department',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 13,
                'slug' => 'addDepartment',
                'name' => 'Add Department',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 14,
                'slug' => 'manageCalendar',
                'name' => 'Manage Calendar',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 15,
                'slug' => 'manageBranch',
                'name' => 'Manage Branches',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 16,
                'slug' => 'editBranch',
                'name' => 'Edit Branch',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 17,
                'slug' => 'addBranch',
                'name' => 'Add Branch',
                'status' => Config::get('variable_constants.activation.active'),
            ],
        ];

        foreach ($permissions as $key=>$permission)
        {
            Permission::updateOrCreate([
                'id'=> $permission['id']
            ],
                $permission);
        }

    }
}
