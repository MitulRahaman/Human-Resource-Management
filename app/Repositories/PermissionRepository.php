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
        $permission = Permission::find($data);
        $old=$permission->status;
            // Update the status to a new value, for example, 1 for active or 0 for inactive
            if($old==1)
            {
                $permission->status=0;
                $permission->save();

            }
            else
            {

                $permission->status=1;
                $permission->save();

            }
    }
    public function delete(int $id)
    {
        Permission::destroy($id);
    }
    public function edit(int $id)
    {

    }
}
