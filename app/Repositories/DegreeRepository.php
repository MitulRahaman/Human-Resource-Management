<?php

namespace App\Repositories;

use App\Models\Degree;
use Illuminate\Support\Facades\DB;

class DegreeRepository
{
    private  $name, $id, $description, $created_at, $updated_at, $deleted_at;

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function setDescription($description)
    {
        $this->description = $description;
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
    public function getAllDegreeData()
    {
        return DB::table('degrees as d')
            ->select('d.id', 'd.name', 'd.description',  DB::raw('date_format(d.created_at, "%d/%m/%Y") as created_at'), DB::raw('date_format(d.deleted_at, "%d/%m/%Y") as deleted_at'))
            ->orderBy('d.id', 'desc')
            ->get();
    }
    public function isNameExists()
    {
        return Degree::withTrashed()->where('name', $this->name)->exists() ;
    }
    public function save()
    {
        return DB::table('degrees')
            ->insertGetId([
                'name' => $this->name,
                'description' => $this->description,
                'created_at' => $this->created_at
            ]);
    }
    public function getDegree($id)
    {
        $degree = Degree::onlyTrashed()->find($id);
        if($degree)
            return "Restore first";
        return Degree::findOrFail($id);
    }
    public function isNameUnique($id)
    {
        return Degree::withTrashed()->where('name',$this->name)->where('id', '!=', $id)->first() ;
    }
    public function update()
    {
        return DB::table('degrees')
            ->where('id', '=', $this->id)
            ->update([
                'name' => $this->name,
                'description' => $this->description ? $this->description : null,
                'updated_at' => $this->updated_at
            ]);
    }
    public function delete($id)
    {
        $degree= Degree::findOrFail($id);
        return $degree->delete();
    }
    public function restore($id)
    {
        return Degree::withTrashed()->where('id', $id)->restore();
    }

}
