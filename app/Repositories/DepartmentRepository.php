<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\Department;
use App\Models\BranchDepartment;
use App\Models\Branch;

class DepartmentRepository
{
    private $name;
    public function getAllDepartmentData()
    {
        return DB::table('departments as d')
            ->select('d.id', 'd.name', 'd.address', 'd.status', DB::raw('date_format(p.created_at, "%d/%m/%Y") as created_at'), DB::raw('date_format(p.deleted_at, "%d/%m/%Y") as deleted_at'))
            ->get();
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function indexDepartment()
    {
        return DB::table('departments as d')
        ->leftjoin('branch_departments as bd', 'd.id', '=', 'bd.department_id')
        ->leftjoin('branches as b', 'b.id', '=', 'bd.branch_id')
        ->groupBy('d.id')
        ->select('d.id', 'd.name', 'd.description', 'd.status', 'd.created_at', 'd.deleted_at', DB::raw('group_concat(b.name SEPARATOR ", ") as branches'))
        ->get();
    }
    public function storeDepartment($data)
    {
        DB::beginTransaction();
        try {
            $department = new Department();
            $department->name = $data->name;
            $department->description = $data->description;
            $department->status = 1;
            $department->save();

            $id = $department->id;

            foreach ($data->branchID as $b){
                $branch_department = new BranchDepartment();
                $branch_department->department_id = $id;
                $branch_department->branch_id = $b;
                $branch_department->status = 1;
                $branch_department->save();
            }
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }

    public function departmentInfo($id)
    {
        return Department::with('branch_departments')->with('branch_departments.branch')->where('id', $id)->first();
    }

    public function updateDepartment($data, $id)
    {
        DB::beginTransaction();
        try {
            $department = Department::find($id);
            $department->update($data->validated());

            if($data->branchID != null)
            {
                $branch_department = DB::table('branch_departments')->where('department_id', $id);
                $branch_department->delete();

                foreach ($data->branchID as $b){
                    $branch_department = new BranchDepartment();
                    $branch_department->department_id = $id;
                    $branch_department->branch_id = $b;
                    $branch_department->status = 1;
                    $branch_department->save();
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
    public function updateStatus($id)
    {
        $data = Department::find($id);
        if($data->status)
            $data->update(array('status' => Config::get('variable_constants.check.no')));
        else
            $data->update(array('status' => Config::get('variable_constants.check.yes')));
    }

    public function destroyDepartment($id)
    {
        $data = Department::find($id);
        $data->update(array('status' => 0));
        return $data->delete();
    }

    public function restoreDepartment($id)
    {
        return DB::table('departments')->where('id', $id)->limit(1)->update(array('deleted_at' => NULL));
    }

    public function isNameExists()
    {
        return DB::table('departments')->where('name', '=', $this->name)->first();
    }

    public function isNameExistsForUpdate($current_name)
    {
        return DB::table('departments')->where('name', '!=', $current_name)->where('name', $this->name)->first();
    }

    public function getDepartments()
    {
        return DB::table('departments')
            ->whereNull('deleted_at')
            ->where('status', '=', Config::get('variable_constants.activation.active'))
            ->select('id', 'name')
            ->get();
    }
}
