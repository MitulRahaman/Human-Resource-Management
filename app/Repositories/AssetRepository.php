<?php

namespace App\Repositories;

use App\Models\Asset;
use App\Models\AssetType;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;

class AssetRepository
{
    private  $name, $id, $url, $type_id, $sl_no, $branch_id, $specification, $purchase_at, $purchase_by, $purchase_price, $status, $created_at, $updated_at, $deleted_at;

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
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }
    public function setTypeId($type_id)
    {
        $this->type_id = $type_id;
        return $this;
    }
    public function setSlNo($sl_no)
    {
        $this->sl_no = $sl_no;
        return $this;
    }
    public function setBranchId($branch_id)
    {
        $this->branch_id = $branch_id;
        return $this;
    }
    public function setSpecification($specification)
    {
        $this->specification = $specification;
        return $this;
    }
    public function setPurchaseAt($purchase_at)
    {
        $this->purchase_at = $purchase_at;
        return $this;
    }
    public function setPurchaseBy($purchase_by)
    {
        $this->purchase_by = $purchase_by;
        return $this;
    }
    public function setPurchasePrice($purchase_price)
    {
        $this->purchase_price = $purchase_price;
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
    //    =============================start asset======================

    public function getAllAssetData()
    {
        return DB::table('assets as a')
            ->select('a.id',  'a.name', 'a.type_id', 'a.sl_no', 'a.branch_id','a.specification', 'a.purchase_at', 'a.purchase_by', 'a.purchase_price', 'a.status', DB::raw('date_format(a.created_at, "%d/%m/%Y") as created_at'), DB::raw('date_format(a.deleted_at, "%d/%m/%Y") as deleted_at'))
            ->orderBy('a.id', 'desc')
            ->get();
    }
    public function getBranchName($id)
    {
        return Branch::where('id',$id)->select('name')->first();
    }
    public function getAllBranches()
    {
        return Branch::where('status', Config('variable_constants.activation.active'))->get();
    }
    public function getAsset($id)
    {
        $asset = DB::table('assets as a')
            ->where('a.id','=', $id)
            ->leftJoin('asset_images as ai', 'a.id', '=', 'ai.asset_id')
            ->select('ai.url','a.id', 'a.name', 'a.type_id', 'a.sl_no', 'a.branch_id', 'a.specification', 'a.purchase_at', 'a.purchase_by', 'a.purchase_price','a.status',DB::raw('date_format(a.created_at, "%d/%m/%Y") as created_at'), DB::raw('date_format(a.deleted_at, "%d/%m/%Y") as deleted_at'))
            ->first();
        return $asset;
    }
    public function save()
    {
        DB::beginTransaction();
        try {
                $asset = DB::table('assets')
                    ->insertGetId([
                        'name' => $this->name,
                        'type_id' => $this->type_id,
                        'sl_no' => $this->sl_no? $this->sl_no:'',
                        'branch_id' => $this->branch_id,
                        'specification' => $this->specification? $this->specification:'',
                        'purchase_at' => $this->purchase_at? $this->purchase_at:'',
                        'purchase_by' => $this->purchase_by? $this->purchase_by:'',
                        'purchase_price' => $this->purchase_price? $this->purchase_price:'',
                        'status' => $this->status,
                        'created_at' => $this->created_at
                    ]);
                if($this->url)
                {
                    DB::table('asset_images')
                        ->insert([
                            'asset_id'=> $asset,
                            'url' => $this->url,
                            'status' => $this->status,
                            'created_at' => $this->created_at
                        ]);
                }

            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
    public function update()
    {
        DB::beginTransaction();
        try {
             DB::table('assets')
                ->where('id',$this->id)
                ->update([
                    'name' => $this->name,
                    'type_id' => $this->type_id,
                    'sl_no' => $this->sl_no? $this->sl_no:'',
                    'branch_id' => $this->branch_id,
                    'specification' => $this->specification? $this->specification:'',
                    'purchase_at' => $this->purchase_at? $this->purchase_at:'',
                    'purchase_by' => $this->purchase_by? $this->purchase_by:'',
                    'purchase_price' => $this->purchase_price? $this->purchase_price:'',
                    'updated_at' => $this->updated_at
                ]);
            if($this->url)
            {
                DB::table('asset_images')
                    ->where('asset_id', $this->id)
                    ->update([
                        'url' => $this->url,
                        'updated_at' => $this->updated_at
                    ]);
            }

            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
    public function delete( $id)
    {
        $asset= Asset::findOrFail($id);
        return $asset->delete();
    }
    public function restore($id)
    {
        return Asset::withTrashed()->where('id', $id)->restore();
    }
    public function change( $data)
    {
        $asset = Asset::findOrFail($data);
        $old=$asset->status;
        $status= config('variable_constants.activation');
        if($old==$status['active'])
        {
            $asset->status=$status['inactive'];
            return $asset->save();
        }
        else
        {
            $asset->status=$status['active'];
            return $asset->save();
        }
    }
    //    =============================end asset======================

//    =============================start asset type======================
    public function getAllAssetTypeData()
    {
        return DB::table('asset_types as a')
            ->select('a.id',  'a.name', 'a.status', DB::raw('date_format(a.created_at, "%d/%m/%Y") as created_at'), DB::raw('date_format(a.deleted_at, "%d/%m/%Y") as deleted_at'))
            ->orderBy('a.id', 'desc')
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
        return DB::table('asset_types')->where('id',$id)->select('*')->first();
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
    //    =============================end asset type======================
}
