<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use Illuminate\Support\Facades\Validator;


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
       if(Permission::create($data))
           return response()->json(['message' => 'Permission added successfully'], 201);
    
       return response()->json(['error' => 'Bad request: Permission not added'], 400);

    }
    public function change(int $data)
    {
        $permission = Permission::findOrFail($data);
        $old=$permission->status;
        $status= config('variable_constants.activation');
            if($old==$status['active'])
            {
                $permission->status=$status['inactive'];
                if($permission->save())
                    return response()->json(['message' => 'Permission status changed successfully'], 204);
    
                return response()->json(['error' => 'Bad request: Permission status not changed'], 400);

            }
            else
            {
                if($permission->save())
                    return response()->json(['message' => 'Permission status changed successfully'], 204);
    
                return response()->json(['error' => 'Bad request: Permission status not changed'], 400);

            }
    }
    public function delete(int $id)
    {
        $permission= Permission::findOrFail($id);
        if($permission->delete())
                return response()->json(['message' => 'Permission deleted successfully'], 204);
    
        return response()->json(['error' => 'Bad request: Permission not deleted'], 400);
    }
    public function getPermission(int $id)
    {
        return Permission::findOrFail($id);
    }
    public function edit( $data, int $id)
    {
        $permission= Permission:: findorFail($id);
        if( $permission->update($data))
                return response()->json(['message' => 'Permission edited successfully'], 204);
        return response()->json(['error' => 'Bad request: Permission not edited'], 400);
    }
    public function restore(int $id)
    {
       
        if( Permission::withTrashed()->where('id', $id)->restore())
                return response()->json(['message' => 'Permission restored successfully'], 204);
        return response()->json(['error' => 'Bad request: Permission not restored'], 400);
    }
    public function isSlugExists()
    {
        if (Permission::where('slug', $this->slug)->exists() || !$this->slug) {
            return true;
        } else {
            return false;
        }
    }
    public function isNameExists()
    {
        if (Permission::where('name', $this->name)->exists() || !$this->name) {
            return true;
        } else {
            return false;
        }
    }
    public function isNameUnique($data)
    {
        $id=$data["id"];
        $id=(int)$id;
        if (Permission::where('name',$this->name)->where('id', '!=', $id)->first() || !$this->name) {
            return true;
        } else {
            return false;
        }
    }
}
