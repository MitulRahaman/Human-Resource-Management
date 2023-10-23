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
        return DB::table('roles as r')
            ->select('r.id',  'r.name', 'r.description', 'r.sl_no','r.status', DB::raw('date_format(r.created_at, "%d/%m/%Y") as created_at'), DB::raw('date_format(r.deleted_at, "%d/%m/%Y") as deleted_at'))
            ->get();
    }
    public function getAllPermissions()
    {
        return Permission::get();
    }
    public function getPermission(int $id)
    {
//        $permission=DB::table('role_permissions')->where('role_id',$id)->get();

        return Permission::findOrFail($id)->get();
    }
    public function getRole(int $id)
    {
        return Role::findOrFail($id);
    }
    public function create(array $data)
    {
        dd($data);
        if(Role::create($data))
        {
//            dd($data->permissions);
            foreach ($data->permissions as $p)
            {
                DB::table('role_permissions')->insert([
                    'role_id'=>DB::table('roles')->where('name', $data->name)->value('id'),
                    'permission_id'=>$p,
                ]);
            }
            return response()->json(['message' => 'Role added successfully']);
        }


        return response()->json(['error' => 'Bad request: Role not added']);

    }

}
