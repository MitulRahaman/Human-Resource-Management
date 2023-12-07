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
                'slug' => 'manageRole',
                'name' => 'Manage Role',
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
                'slug' => 'manageDesignation',
                'name' => 'Manage Designation',
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
                'slug' => 'manageCalender',
                'name' => 'Manage Calender',
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
            [
                'id' => 18,
                'slug' => 'addPermission',
                'name' => 'Add Permission',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 19,
                'slug' => 'editPermission',
                'name' => 'Edit Permission',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 20,
                'slug' => 'managePermission',
                'name' => 'Manage Permission',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 21,
                'slug' => 'manageMenu',
                'name' => 'Manage Menu',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 22,
                'slug' => 'addMenu',
                'name' => 'Add Menu',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 23,
                'slug' => 'editMenu',
                'name' => 'Edit Menu',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 24,
                'slug' => 'editRequisition',
                'name' => 'Edit Requisition Requests',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 25,
                'slug' => 'addAsset',
                'name' => 'Add Asset',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 26,
                'slug' => 'editAsset',
                'name' => 'Edit Asset',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 27,
                'slug' => 'manageAsset',
                'name' => 'Manage Asset',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 28,
                'slug' => 'addAssetType',
                'name' => 'Add Asset Type',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 29,
                'slug' => 'editAssetType',
                'name' => 'Edit Asset Type',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 30,
                'slug' => 'manageAssetType',
                'name' => 'Manage Asset Type',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 31,
                'slug' => 'addDegree',
                'name' => 'Add Degree',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 32,
                'slug' => 'editDegree',
                'name' => 'Edit Degree',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 33,
                'slug' => 'manageDegree',
                'name' => 'Manage Degree',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 34,
                'slug' => 'addInstitute',
                'name' => 'Add Institute',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 35,
                'slug' => 'editInstitute',
                'name' => 'Edit Institute',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 36,
                'slug' => 'manageInstitute',
                'name' => 'Manage Institute',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 34,
                'slug' => 'addInstitute',
                'name' => 'Add Institute',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 35,
                'slug' => 'editInstitute',
                'name' => 'Edit Institute',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 36,
                'slug' => 'manageInstitute',
                'name' => 'Manage Institute',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 37,
                'slug' => 'addRole',
                'name' => 'Add Role',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 38,
                'slug' => 'editRole',
                'name' => 'Edit Role',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 39,
                'slug' => 'manageBank',
                'name' => 'Manage Bank',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 40,
                'slug' => 'addBank',
                'name' => 'Add Bank',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 41,
                'slug' => 'editBank',
                'name' => 'Edit Bank',
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
