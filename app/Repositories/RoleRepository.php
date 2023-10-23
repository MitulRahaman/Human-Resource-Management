<?php

namespace App\Repositories;

use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;


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
        // dd($var);
        return $var;
        // return DB::table('roles as r')
        //     ->select('r.id',  'r.name', 'r.description', 'r.sl_no','r.status', DB::raw('date_format(r.created_at, "%d/%m/%Y") as created_at'), DB::raw('date_format(r.deleted_at, "%d/%m/%Y") as deleted_at'))
        //     ->get();
    }
    public function getAllPermissions()
    {
        return Permission::get();
    }
    public function getPermission(int $id)
    {
       $permission=DB::table('role_permissions')->where('role_id',$id)->get();
        // dd($permission);
        return Permission::findOrFail($id)->get();
    }
    public function getRole(int $id)
    {
        return Role::findOrFail($id);
    }
    public function create(array $data)
    {
        //  dd(DB::table('roles')->where('name', 'vvfd')->value('id'));
        if(Role::create($data))
        {
//            dd($data->permissions);
            foreach ($data['permissions'] as $p)
            {
                DB::table('role_permissions')->insert([
                    'role_id'=>DB::table('roles')->where('name', $data['name'])->value('id'),
                    'permission_id'=>(int)$p,
                ]);
            }
            return response()->json(['message' => 'Role added successfully']);
        }


        return response()->json(['error' => 'Bad request: Role not added']);

    }
    public function change(int $data)
    {
        $role = Role::findOrFail($data);
        $old=$role->status;
        $status= config('variable_constants.activation');
            if($old==$status['active'])
            {
                $role->status=$status['inactive'];
                if($role->save())
                    return response()->json(['message' => 'Role status changed successfully']);
                return response()->json(['error' => 'Bad request: Role status not changed']);

            }
            else
            {
                $role->status=$status['active'];
                if($role->save())
                    return response()->json(['message' => 'Role status changed successfully']);
    
                return response()->json(['error' => 'Bad request: Role status not changed']);

            }
    }

}
