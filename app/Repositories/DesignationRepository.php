<?php

namespace App\Repositories;

use App\Models\Branch;
use App\Models\Designation;
use Illuminate\Support\Facades\DB;

class DesignationRepository
{
    private $id, $name, $branch_ids, $description, $status, $created_at, $updated_at, $deleted_at;

    public function setId($id)
    {
        $this->id = $id;
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
    public function delete(int $id)
    {
        $designation= Designation::findOrFail($id);
        return $designation->delete();
    }
    public function restore($id)
    {
        return Designation::withTrashed()->where('id', $id)->restore();
    }
    public function getAllDesignationData()
    {
        $designations=DB::table('designations as d')
            ->select('d.id', 'd.name', 'd.description', 'd.status', DB::raw('date_format(d.created_at, "%d/%m/%Y") as created_at'), DB::raw('date_format(d.deleted_at, "%d/%m/%Y") as deleted_at'))
            ->selectRaw('GROUP_CONCAT(b.name) as branches')
            ->leftJoin('branch_designations as bd', 'd.id', '=', 'bd.designation_id')
            ->leftJoin('branches as b', 'bd.branch_id', '=', 'b.id')
            ->groupBy('d.id', 'd.name', 'd.description', 'd.status', 'd.created_at', 'd.deleted_at')
            ->get();
        foreach ($designations as $designation) {
            $designation->branches = explode(',', $designation->branches);
        }

        return $designations;
    }
    public function create()
    {
        $designation = DB::table('designations')
            ->insertGetId([
                'name' => $this->name,
                'description' => $this->description,
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
            return response()->json(['message' => 'Designation added successfully']);
        }
        return response()->json(['error' => 'Bad request: Designation not added']);
    }
    public function getDesignation($id)
    {
        $designation = Designation::onlyTrashed()->find($id);
        if($designation)
            return "Restore first";
        $designations = DB::table('designations as d')
            ->where('d.id','=', $id)
            ->select('d.id', 'd.name',  'd.description', 'd.status', DB::raw('date_format(d.created_at, "%d/%m/%Y") as created_at'), DB::raw('date_format(d.deleted_at, "%d/%m/%Y") as deleted_at'))
            ->selectRaw('GROUP_CONCAT(b.id) as branches')
            ->leftJoin('branch_designations as bd', 'd.id', '=', 'bd.designation_id')
            ->leftJoin('branches as b', 'bd.branch_id', '=', 'b.id')
            ->groupBy('d.id', 'd.name', 'd.description', 'd.status', 'd.created_at', 'd.deleted_at')
            ->first();
        $designations->branches =($designations->branches)? explode(',', $designations->branches):[];
        return $designations;
    }
    public function getAllBranches($id)
    {
        $id = (int)$id;
        return DB::table('branches')
            ->where('branches.status', 1)
            ->where('branches.deleted_at', null)
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
        $designations = DB::table('designations')
            ->where('id', '=', $this->id)
            ->update([
                'name' => $this->name,
                'description' => $this->description,
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

            return response()->json(['message' => 'Designation updated successfully']);
        }
        return response()->json(['error' => 'Bad request: Designation not updated']);

    }

}
