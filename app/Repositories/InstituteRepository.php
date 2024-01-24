<?php

namespace App\Repositories;

use App\Models\Institute;
use Illuminate\Support\Facades\DB;

class InstituteRepository
{
    private  $name, $id, $address, $status,  $created_at, $updated_at, $deleted_at;

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }
    public function setId($id)
    {
        $this->id = $id;
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
    public function getAllInstituteData()
    {
        return DB::table('institutes as i')
            ->select('i.id',  'i.name', 'i.address', 'i.status', DB::raw('date_format(i.created_at, "%d/%m/%Y") as created_at'), DB::raw('date_format(i.deleted_at, "%d/%m/%Y") as deleted_at'))
            ->orderBy('i.id', 'desc')
            ->get();
    }
    public function isNameExists()
    {
        return Institute::withTrashed()->where('name', $this->name)->exists() ;
    }
    public function save()
    {
        return DB::table('institutes')
            ->insertGetId([
                'name' => $this->name,
                'address' => $this->address,
                'status' => $this->status,
                'created_at' => $this->created_at
            ]);
    }
    public function getInstitute($id)
    {
        $institute = Institute::onlyTrashed()->find($id);
        if($institute)
            return "Restore first";
        return Institute::findOrFail($id);
    }
    public function isNameUnique($id)
    {
        return Institute::withTrashed()->where('name',$this->name)->where('id', '!=', $id)->first() ;
    }
    public function update()
    {
        return DB::table('institutes')
            ->where('id', '=', $this->id)
            ->update([
                'name' => $this->name,
                'address' => $this->address ? $this->address : null,
                'updated_at' => $this->updated_at
            ]);
    }
    public function delete($id)
    {
        $institute= Institute::findOrFail($id);
        return $institute->delete();
    }
    public function restore($id)
    {
        return Institute::withTrashed()->where('id', $id)->restore();
    }
    public function change($data)
    {
        $institute = Institute::findOrFail($data);
        $old=$institute->status;
        $status= config('variable_constants.activation');
        if($old==$status['active'])
        {
            $institute->status=$status['inactive'];
        }
        else
        {
            $institute->status=$status['active'];
        }
        return $institute->save();
    }

}
