<?php

namespace App\Repositories;

use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RoleRepository
{
    private $name, $id, $description, $sl_no, $status, $created_at, $updated_at, $deleted_at, $permission_ids;
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function setPermission_ids($permission_ids)
    {
        $this->permission_ids = $permission_ids;
        return $this;
    }
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    public function setSl_no($sl_no)
    {
        $this->sl_no = $sl_no;
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
    public function isNameExists()
    {
        return Role::withTrashed()->where('name', $this->name)->exists() || !$this->name;
    }
    public function isNameUnique($id)
    {
        return Role::withTrashed()->where('name',$this->name)->where('id', '!=', $id)->first() || !$this->name;
    }
    public function getAllRoleData()
    {
        $roles=DB::table('roles as r')
        ->select('r.id', 'r.name', 'r.description', 'r.sl_no', 'r.status', DB::raw('date_format(r.created_at, "%d/%m/%Y") as created_at'), DB::raw('date_format(r.deleted_at, "%d/%m/%Y") as deleted_at'))
        ->selectRaw('GROUP_CONCAT(p.name) as permissions')
        ->leftJoin('role_permissions as rp', 'r.id', '=', 'rp.role_id')
        ->leftJoin('permissions as p', 'rp.permission_id', '=', 'p.id')
        ->groupBy('r.id', 'r.name', 'r.description', 'r.sl_no', 'r.status', 'r.created_at', 'r.deleted_at')
        ->get();
        foreach ($roles as $role) {
            $role->permissions = explode(',', $role->permissions);
        }
        return $roles;
    }
    public function getAllPermissions($id)
    {
        $id =(int) $id;
        return DB::table('permissions')
            ->select('permissions.*', DB::raw('IF(role_permissions.role_id = ' . $id . ', "yes", "no") as selected'))
            ->leftJoin('role_permissions', function ($join) use ($id) {
                $join->on('permissions.id', '=', 'role_permissions.permission_id')
                    ->where('role_permissions.role_id', '=', $id);
            })
            ->get();
    }
    public function getRole($id)
    {
        return Role::findOrFail($id);
    }
    public function create()
    {
        $roles = DB::table('roles')
            ->insertGetId([
                'name' => $this->name,
                'sl_no' => $this->sl_no,
                'description' => $this->description,
                'status' => $this->status,
                'created_at' => $this->created_at
            ]);
        if($roles)
        {
            if($this->permission_ids)
            {
                foreach ($this->permission_ids as $p)
                {
                    DB::table('role_permissions')->insert([
                        'role_id'=> $roles,
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
    public function update()
    {
        $role = Role::findorFail($this->id);
        $roles = DB::table('roles')
            ->where('id', '=', $this->id)
            ->update([
                'name' => $this->name,
                'description' => $this->description,
                'updated_at' => $this->updated_at
            ]);
        if( $roles)
        {
            DB::table('role_permissions')->where('role_id',$this->id)->delete();
            if($this->permission_ids)
            {
                foreach ($this->permission_ids as $p)
                {
                    DB::table('role_permissions')->insert([
                        'role_id'=> $this->id,
                        'permission_id'=>(int)$p,
                    ]);
                }}

            return response()->json(['message' => 'Role updated successfully']);
        }
        return response()->json(['error' => 'Bad request: Role not updated']);

    }
}
