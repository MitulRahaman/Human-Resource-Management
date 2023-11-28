<?php

namespace App\Http\Controllers\Asset;

use App\Http\Requests\AssetAddRequest;
use App\Http\Requests\AssetEditRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AssetService;
use Illuminate\Support\Facades\View;
use App\Http\Requests\AssetTypeAddRequest;
use App\Http\Requests\AssetTypeEditRequest;

class AssetController extends Controller
{
    private $assetService;
    public function __construct(AssetService $assetService)
    {
        $this->assetService = $assetService;
        View::share('main_menu', 'System Settings');
    }
//    =============================start asset======================
    public function index()
    {
        View::share('sub_menu', 'Manage Assets');
        return \view('backend.pages.asset.index');
    }
    public function fetchData()
    {
        return $this->assetService->fetchData();
    }
    public function create()
    {
        View::share('sub_menu', 'Add Asset');
        $asset_type = $this->assetService->getAllAssetTypeData();
        $branches = $this->assetService->getAllBranches();
        return \view('backend.pages.asset.create', compact('asset_type', 'branches'));
    }
    public function store(AssetAddRequest $request)
    {
        try {
            $response = $this->assetService->createAsset($request->validated());
            if(!$response)
                return redirect('asset')->with('error', 'Failed to add Asset');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect('asset')->with('success', 'Asset saved successfully.');
    }
    public function edit($id )
    {
        View::share('sub_menu', 'Manage Assets');
        $asset = $this->assetService->getAsset($id);
        if($asset && !is_null($asset->deleted_at))
            return redirect()->back()->with('error', 'Restore first');
        $asset_type = $this->assetService->getAllAssetTypeData();
        $branches = $this->assetService->getAllBranches();
        return \view('backend.pages.asset.edit',compact('asset', 'asset_type', 'branches'));
    }
    public function update(AssetEditRequest $request)
    {
        try {
            $asset = $this->assetService->update($request->validated());
            if(!$asset)
                return redirect('asset')->with('error', 'Failed to update Asset');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect('/asset')->with('success', 'Asset updated successfully');
    }
    public function delete($id)
    {
        try{
            if($this->assetService->delete($id))
                return redirect('asset/')->with('success', "Assets  deleted successfully.");
            return redirect('asset/')->with('error', "Assets  not deleted.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function restore($id)
    {
        try{
            if($this->assetService->restore($id))
                return redirect('asset/')->with('success', "Assets  restored successfully.");
            return redirect('asset/')->with('success', "Assets  not restored.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function changeStatus($id)
    {
        try{
            if($this->assetService->changeStatus($id))
                return redirect('asset/')->with('success', 'Assets status changed successfully!');
            return redirect('asset/')->with('error', 'Assets status not changed!');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

//    =============================end asset======================

//    =============================start asset type======================
    public function assetTypeIndex()
    {
        View::share('sub_menu', 'Assets Type');
        return \view('backend.pages.asset.assetTypeIndex');
    }
    public function fetchDataAssetType()
    {
        return $this->assetService->fetchDataAssetType();
    }
    public function createAssetType()
    {
        View::share('sub_menu', 'Assets Type');
        return \view('backend.pages.asset.createAssetType');
    }
    public function validate_inputs_asset_type(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if($validator)
            return $this->assetService->validate_inputs_asset_type($request->all());
        return redirect()->back()->with('error', 'Name is Required to validate');
    }
    public function storeAssetType(AssetTypeAddRequest $request)
    {
        try{
            $response = $this->assetService->createAssetType($request->validated());
            if (is_int($response)) {
                return redirect('assetsType/')->with('success', 'Asset Type saved successfully.');
            } else {
                return redirect()->back()->with('error', $response);
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function edit_asset_type($id )
    {
        View::share('sub_menu', 'Assets Type');
        $asset_type = $this->assetService->getAssetType($id);
        if($asset_type && !is_null($asset_type->deleted_at))
            return redirect()->back()->with('error', 'Restore first');
        return \view('backend.pages.asset.editAssetType',compact('asset_type'));
    }
    public function validate_name_asset_type(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if($validator)
            return $this->assetService->validate_name_asset_type($request->all(),$id);
        return redirect()->back()->with('error', 'Name is Required to validate');
    }
    public function update_asset_type(AssetTypeEditRequest $request)
    {
        try{
            if($this->assetService->edit_asset_type($request->validated()))
                return redirect('assetsType/')->with('success', "Assets Type updated successfully.");
            return redirect('assetsType/')->with('success', "Assets Type not updated.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function deleteAssetType($id)
    {
        try{
            if($this->assetService->deleteAssetType($id))
                return redirect('assetsType/')->with('success', "Assets Type deleted successfully.");
            return redirect('assetsType/')->with('error', "Assets Type not deleted.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function restoreAssetType($id)
    {
        try{
            if($this->assetService->restoreAssetType($id))
                return redirect('assetsType/')->with('success', "Assets Type restored successfully.");
            return redirect('assetsType/')->with('success', "Assets Type not restored.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function changeStatusAssetType($id)
    {
        try{
            if($this->assetService->changeStatusAssetType($id))
                return redirect('assetsType/')->with('success', "Assets Type status changed successfully.");
            return redirect('assetsType/')->with('error', "Assets Type status not changed.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    //    =============================end asset type======================
}
