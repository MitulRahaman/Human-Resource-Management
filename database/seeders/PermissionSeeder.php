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
            [
                'id' => 42,
                'slug' => 'manageUser',
                'name' => 'Manage User',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 43,
                'slug' => 'addUser',
                'name' => 'Add User',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 44,
                'slug' => 'editUser',
                'name' => 'Edit User',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 45,
                'slug' => 'viewLog',
                'name' => 'View Log',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 46,
                'slug' => 'distributeAsset',
                'name' => 'Distribute Asset',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 47,
                'slug' => 'notifyLeaveApply',
                'name' => 'Notify Leave Apply',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 48,
                'slug' => 'notifyRequisitionRequest',
                'name' => 'Notify Requisition Request',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 49,
                'slug' => 'manageTicket',
                'name' => 'Manage Tickets',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 50,
                'slug' => 'editTicket',
                'name' => 'Edit Tickets',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 51,
                'slug' => 'addTicket',
                'name' => 'Add Tickets',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 52,
                'slug' => 'manageEvents',
                'name' => 'Manage Events',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 53,
                'slug' => 'viewLeaveReports',
                'name' => 'View Leave Reports',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 54,
                'slug' => 'manageWarning',
                'name' => 'Manage Warnings',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 55,
                'slug' => 'editWarning',
                'name' => 'Edit Warnings',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 56,
                'slug' => 'addWarning',
                'name' => 'Add Warnings',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 57,
                'slug' => 'viewUsers',
                'name' => 'View Users',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 58,
                'slug' => 'viewAssets',
                'name' => 'View Assets',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 59,
                'slug' => 'viewRequisitions',
                'name' => 'View Requisitions',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 60,
                'slug' => 'viewTickets',
                'name' => 'View Tickets',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 61,
                'slug' => 'viewAppliedLeave',
                'name' => 'View Applied Leave',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 62,
                'slug' => 'viewEvents',
                'name' => 'View Events',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 63,
                'slug' => 'viewWarnings',
                'name' => 'View Warnings',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 64,
                'slug' => 'viewAssetType',
                'name' => 'View Asset Type',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 65,
                'slug' => 'viewBanks',
                'name' => 'View Banks',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 66,
                'slug' => 'viewCalendar',
                'name' => 'View Calendar',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 67,
                'slug' => 'viewDegrees',
                'name' => 'View Degrees',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 68,
                'slug' => 'viewDepartments',
                'name' => 'View Departments',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 69,
                'slug' => 'viewBranches',
                'name' => 'View Branches',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 70,
                'slug' => 'viewDesignations',
                'name' => 'View Designations',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 71,
                'slug' => 'viewInstitutes',
                'name' => 'View Institutes',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 72,
                'slug' => 'viewLeaves',
                'name' => 'View Leaves',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 73,
                'slug' => 'viewRoles',
                'name' => 'View Roles',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 74,
                'slug' => 'viewMeetingPlaces',
                'name' => 'View Meeting Places',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 75,
                'slug' => 'manageMeetingPlace',
                'name' => 'Manage Meeting Place',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 76,
                'slug' => 'addMeetingPlace',
                'name' => 'Add Meeting Place',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 77,
                'slug' => 'editMeetingPlace',
                'name' => 'Edit Meeting Place',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 78,
                'slug' => 'viewMeetings',
                'name' => 'View Meetings',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 79,
                'slug' => 'manageMeeting',
                'name' => 'Manage Meeting',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 80,
                'slug' => 'addMeeting',
                'name' => 'Add Meeting',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 81,
                'slug' => 'editMeeting',
                'name' => 'Edit Meeting',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 82,
                'slug' => 'viewComplaints',
                'name' => 'View Complaints',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 83,
                'slug' => 'manageComplaint',
                'name' => 'Manage Complaint',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 84,
                'slug' => 'addComplaint',
                'name' => 'Add Complaint',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 85,
                'slug' => 'editComplaint',
                'name' => 'Edit Complaint',
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
