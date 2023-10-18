<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Permission;

class PermissionRepository
{
    private $slug, $name;

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


    public function getAllPermissionData()
    {
        return DB::table('permissions as p')
            ->select('p.id', 'p.slug', 'p.name', 'p.description', 'p.status', DB::raw('date_format(p.created_at, "%d/%m/%Y") as created_at'), DB::raw('date_format(p.deleted_at, "%d/%m/%Y") as deleted_at'))
            ->get();
    }
    public function create(array $data)
    {
        return Permission::create($data);
    }
    public function change(int $data)
    {
        $permission = Permission::findOrFail($data);
        $old=$permission->status;
        $status= config('variable_constants.activation');
            if($old==$status['active'])
            {
                $permission->status=$status['inactive'];
                $permission->save();

            }
            else
            {

                $permission->status=$status['active'];
                $permission->save();

            }
    }
    public function delete(int $id)
    {
        $permission= Permission::findOrFail($id);
        return $permission->delete();
    }
    public function getPermission(int $id)
    {
        return Permission::findOrFail($id);
    }
    public function edit( $data, int $id)
    {
        $permission= Permission:: findorFail($id);
        return $permission->update($data);
    }
    public function restore(int $id)
    {
        return Permission::withTrashed()->where('id', $id)->restore();;
    }
    public function isSlugExists()
    {
        if (Permission::where('slug', $this->slug)->exists()) {
            return true;
        } else {
            return false;
        }
    }
    public function isNameExists()
    {
        if (Permission::where('name', $this->name)->exists()) {
            return true;
        } else {
            return false;
        }
    }
    public function isNameUnique()
    {
        $id = $this->route('id');
        $id=(int)$id;

        // Check if a permission with the given name exists, excluding the current record
        if (Permission::where('name', $this->name)->where('id', '!=', $id)->exists()) {
            return true; // Permission with the given name already exists, excluding the current record
        } else {
            return false; // Permission with the given name does not exist, or it's the current record
        }
    }
}
