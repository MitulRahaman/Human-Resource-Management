<?php

namespace App\Repositories;

use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RoleRepository
{
    private $name;
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    public function isNameExists()
    {
        if (Role::where('name', $this->name)->exists() || !$this->name) {
            return true;
        } else {
            return false;
        }
    }
    public function isNameUnique($id)
    {
        if (Role::where('name',$this->name)->where('id', '!=', $id)->first() || !$this->name) {
            return true;
        } else {
            return false;
        }
    }
    public function getAllRoleData()
    {
        $var=DB::table('roles as r')
        ->select('r.id', 'r.name', 'r.description', 'r.sl_no', 'r.status', DB::raw('date_format(r.created_at, "%d/%m/%Y") as created_at'), DB::raw('date_format(r.deleted_at, "%d/%m/%Y") as deleted_at'))
        ->selectRaw('GROUP_CONCAT(p.name) as permissions')
        ->leftJoin('role_permissions as rp', 'r.id', '=', 'rp.role_id')
        ->leftJoin('permissions as p', 'rp.permission_id', '=', 'p.id')
        ->groupBy('r.id', 'r.name', 'r.description', 'r.sl_no', 'r.status', 'r.created_at', 'r.deleted_at')
        ->get();
        foreach ($var as $role) {
            $role->permissions = explode(',', $role->permissions);
        }
        return $var;
    }
    public function getAllPermissions()
    {
        return Permission::get();
    }
    public function getPermission($id)
    {
        return DB::table('role_permissions')->where('role_id',$id)->pluck('permission_id')->toArray();
    }
    public function getRole($id)
    {
        return Role::findOrFail($id);
    }
    public function create(array $data)
    {
        if(Role::create($data))
        {
            if(isset($data['permissions']))
            {
            foreach ($data['permissions'] as $p)
            {
                DB::table('role_permissions')->insert([
                    'role_id'=>DB::table('roles')->where('name', $data['name'])->value('id'),
                    'permission_id'=>(int)$p,
                ]);
            }}
            return response()->json(['message' => 'Role added successfully']);
        }
        return response()->json(['error' => 'Bad request: Role not added']);
    }
    public function change($data)
    {
        $role = Role::findOrFail($data);
        $old=$role->status;
        $status= config('variable_constants.activation');
            if($old==$status['active'])
            {
                $role->status=$status['inactive'];
                return $role->save();
            }
            else
            {
                $role->status=$status['active'];
                return $role->save();
            }
    }
    public function delete($id)
    {
        $role= Role::findOrFail($id);
        return $role->delete();
    }
    public function restore($id)
    {
       return Role::withTrashed()->where('id', $id)->restore();
    }
    public function edit($data, $id)
    {
        $role= Role:: findorFail($id);
        if( $role->update($data))
        {
            DB::table('role_permissions')->where('role_id',$id)->delete();
            if(sizeof($data)>2)
            {
                foreach ($data['permissions'] as $p)
                {
                    DB::table('role_permissions')->insert([
                        'role_id'=>DB::table('roles')->where('name', $data['name'])->value('id'),
                        'permission_id'=>(int)$p,
                    ]);
                }
            }
            return response()->json(['message' => 'Role updated successfully']);
        }
        return response()->json(['error' => 'Bad request: Role not updated']);
    }
}
