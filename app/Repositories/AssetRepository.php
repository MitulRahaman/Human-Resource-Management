<?php

namespace App\Repositories;

use App\Models\AssetType;
use Illuminate\Support\Facades\DB;

class AssetRepository
{
    private  $name, $id, $status, $created_at, $updated_at, $deleted_at;

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
    public function getAllAssetTypeData()
    {
        return DB::table('asset_types as a')
            ->select('a.id',  'a.name', 'a.status', DB::raw('date_format(a.created_at, "%d/%m/%Y") as created_at'), DB::raw('date_format(a.deleted_at, "%d/%m/%Y") as deleted_at'))
            ->get();
    }
    public function isNameExists()
    {
        return AssetType::withTrashed()->where('name', $this->name)->exists() ;
    }
    public function saveAssetType()
    {
        return DB::table('asset_types')
            ->insertGetId([
                'name' => $this->name,
                'status' => $this->status,
                'created_at' => $this->created_at
            ]);
    }
    public function getAssetType($id)
    {
        $asset_type = AssetType::onlyTrashed()->find($id);
        if($asset_type)
            return "Restore first";
        return AssetType::findOrFail($id);
    }
    public function isNameUnique($id)
    {
        return AssetType::withTrashed()->where('name',$this->name)->where('id', '!=', $id)->first() ;
    }
    public function updateAssetType()
    {
        return DB::table('asset_types')
            ->where('id', '=', $this->id)
            ->update([
                'name' => $this->name,
                'updated_at' => $this->updated_at
            ]);
    }
    public function deleteAssetType($id)
    {
        $asset_type= AssetType::findOrFail($id);
        return $asset_type->delete();
    }
    public function restoreAssetType($id)
    {
        return AssetType::withTrashed()->where('id', $id)->restore();
    }
    public function changeStatusAssetType($data)
    {
        $asset_type = AssetType::findOrFail($data);
        $old=$asset_type->status;
        $status= config('variable_constants.activation');
        if($old==$status['active'])
        {
            $asset_type->status=$status['inactive'];
        }
        else
        {
            $asset_type->status=$status['active'];
        }
        return $asset_type->save();
    }
}
