<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Permission;

class PermissionRepository
{
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
}
