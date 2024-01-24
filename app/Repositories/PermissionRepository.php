<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Permission;

class PermissionRepository
{
    private $slug, $name, $id, $description, $status, $created_at, $updated_at, $deleted_at;

    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }
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
    public function save()
    {
        return DB::table('permissions')
            ->insertGetId([
                'slug' => $this->slug,
                'name' => $this->name,
                'description' => $this->description,
                'status' => $this->status,
                'created_at' => $this->created_at
            ]);
    }
    public function update()
    {
        return DB::table('permissions')
            ->where('id', '=', $this->id)
            ->update([
                'name' => $this->name,
                'description' => $this->description ? $this->description : null,
                'updated_at' => $this->updated_at
            ]);
    }
    public function getAllPermissionData()
    {
        return DB::table('permissions as p')
            ->select('p.id', 'p.slug', 'p.name', 'p.description', 'p.status', DB::raw('date_format(p.created_at, "%d/%m/%Y") as created_at'), DB::raw('date_format(p.deleted_at, "%d/%m/%Y") as deleted_at'))
            ->orderBy('p.id', 'desc')
            ->get();
    }
    public function change($data)
    {
        $permission = Permission::findOrFail($data);
        $old=$permission->status;
        $status= config('variable_constants.activation');
            if($old==$status['active'])
            {
                $permission->status=$status['inactive'];
            }
            else
            {
                $permission->status=$status['active'];
            }
        return $permission->save();
    }
    public function delete($id)
    {
        $permission= Permission::findOrFail($id);
        return $permission->delete();
    }
    public function getPermission($id)
    {
        $permissions = Permission::onlyTrashed()->find($id);
        if($permissions)
            return "Restore first";
        return Permission::findOrFail($id);
    }
    public function restore($id)
    {
       return Permission::withTrashed()->where('id', $id)->restore();
    }
    public function isSlugExists()
    {
        return Permission::withTrashed()->where('slug', $this->slug)->exists() ;
    }
    public function isNameExists()
    {
        return Permission::withTrashed()->where('name', $this->name)->exists() ;
    }
    public function isNameUnique($id)
    {
        return Permission::withTrashed()->where('name',$this->name)->where('id', '!=', $id)->first() || !$this->name;
    }
}
