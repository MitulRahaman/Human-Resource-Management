<?php

namespace App\Repositories;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DesignationRepository
{
    private $id, $department, $name, $branch_ids, $description, $status, $created_at, $updated_at, $deleted_at;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function setDepartment($department)
    {
        $this->department = $department;
        return $this;
    }
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }
    public function setDeletedAt($deleted_at)
    {
        $this->deleted_at = $deleted_at;
        return $this;
    }
    public function setBranch_ids($branch_ids)
    {
        $this->branch_ids = $branch_ids;
        return $this;
    }
    public function getBranches()
    {
        return Branch::where('status',1)->get();
    }
    public function isNameExists()
    {
        return Designation::withTrashed()->where('name', $this->name)->exists();
    }
    public function change(int $data)
    {
        $designation = Designation::findOrFail($data);
        $old=$designation->status;
        $status= config('variable_constants.activation');
        if($old==$status['active'])
        {
            $designation->status=$status['inactive'];
        }
        else
        {
            $designation->status=$status['active'];
        }
        return $designation->save();
    }
    public function delete( $id)
    {
        $designation= Designation::findOrFail($id);
        return $designation->delete();
    }
    public function restore($id)
    {
        return Designation::withTrashed()->where('id', $id)->restore();
    }
    public function fetchDepartments($data)
    {
        if (array_key_exists('branches', $data)) {
            $branches = $data['branches'];
            $dep = [];

            foreach ($branches as $b)
            {
                $d= DB::table('branch_departments')->where('branch_id', '=',$b)
                    ->pluck('department_id')->toArray();
                $dep = array_unique(array_merge($dep, $d));
            }
            $departments = Department::whereIn('id', $dep)->get();
            return $departments;
        }

    }
    public function getAllDesignationData()
    {
        $designations = DB::table('designations as d')
            ->select('d.id', 'd.name', 'd.description', 'd.status', 'd.department_id', DB::raw('date_format(d.created_at, "%d/%m/%Y") as created_at'), DB::raw('date_format(d.deleted_at, "%d/%m/%Y") as deleted_at'), 'departments.name as department')
            ->selectRaw('GROUP_CONCAT(b.name) as branches')
            ->leftJoin('branch_designations as bd', 'd.id', '=', 'bd.designation_id')
            ->leftJoin('branches as b', 'bd.branch_id', '=', 'b.id')
            ->leftJoin('departments', 'd.department_id', '=', 'departments.id') // Join the departments table
            ->groupBy('d.id', 'd.name', 'd.description', 'd.status', 'd.department_id', 'd.created_at', 'd.deleted_at', 'departments.name')
            ->orderBy('d.id', 'desc')
            ->get();
        foreach ($designations as $designation) {
            $designation->branches = explode(',', $designation->branches);
        }
        return $designations;
    }
    public function create()
    {
        DB::beginTransaction();
        try {
            $designation = DB::table('designations')
                ->insertGetId([
                    'name' => $this->name,
                    'description' => $this->description,
                    'department_id' => $this->department,
                    'status' => $this->status,
                    'created_at' => $this->created_at
                ]);
            if($designation)
            {
                if($this->branch_ids)
                {
                    foreach ($this->branch_ids as $b)
                    {
                        DB::table('branch_designations')->insert([
                            'designation_id'=> $designation,
                            'branch_id'=>(int)$b,
                        ]);
                    }}
            }
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
    public function getDesignation($id)
    {
        $designation = Designation::onlyTrashed()->find($id);
        if ($designation)
            return "Restore first";
        return DB::table('designations as d')
            ->where('d.id', '=', $id)
            ->select('d.id', 'd.name', 'd.description', 'd.department_id', 'd.status', DB::raw('date_format(d.created_at, "%d/%m/%Y") as created_at'), DB::raw('date_format(d.deleted_at, "%d/%m/%Y") as deleted_at'))
            ->selectRaw('GROUP_CONCAT(b.id) as branches')
            ->selectRaw('departments.name as department_name')
            ->leftJoin('branch_designations as bd', 'd.id', '=', 'bd.designation_id')
            ->leftJoin('branches as b', 'bd.branch_id', '=', 'b.id')
            ->leftJoin('departments', 'd.department_id', '=', 'departments.id') // Join the departments table
            ->groupBy('d.id', 'd.name', 'd.description', 'd.department_id', 'd.status', 'd.created_at', 'd.deleted_at', 'department_name')
            ->first();
    }
    public function getAllBranches($id)
    {
        $id = (int)$id;
        return DB::table('branches')
            ->where('branches.status', '=', Config::get('variable_constants.activation.active'))
            ->whereNull('branches.deleted_at')
            ->select('branches.*', DB::raw('IF(branch_designations.designation_id = ' . $id . ', "yes", "no") as selected'))
            ->leftJoin('branch_designations', function ($join) use ($id) {
                $join->on('branches.id', '=', 'branch_designations.branch_id')
                    ->where('branch_designations.designation_id', '=', $id);
            })
            ->get();
    }
    public function isNameUnique($id)
    {
        return Designation::withTrashed()->where('name',$this->name)->where('id', '!=', $id)->first() ;
    }
    public function update()
    {
        DB::beginTransaction();
        try {
            $designations = DB::table('designations')
                ->where('id', '=', $this->id)
                ->update([
                    'name' => $this->name,
                    'description' => $this->description,
                    'department_id' => $this->department,
                    'updated_at' => $this->updated_at
                ]);
            if($designations)
            {
                DB::table('branch_designations')->where('designation_id',$this->id)->delete();
                if($this->branch_ids)
                {
                    foreach ($this->branch_ids as $b)
                    {
                        DB::table('branch_designations')->insert([
                            'designation_id'=> $this->id,
                            'branch_id'=>(int)$b,
                        ]);
                    }}
            }
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }

    public function getDesignations()
    {
        return DB::table('designations as d')
            ->whereNull('d.deleted_at')
            ->where('d.status', '=', Config::get('variable_constants.activation.active'))
            ->select('d.id', 'd.name')
            ->get();
    }

}
