<?php

namespace App\Http\Controllers\Asset;

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
        View::share('sub_menu', 'Assets Type');
    }
    public function assetTypeIndex()
    {
        return \view('backend.pages.asset.assetTypeIndex');
    }
    public function fetchDataAssetType()
    {
        return $this->assetService->fetchDataAssetType();
    }
    public function createAssetType()
    {
        return \view('backend.pages.asset.createAssetType');
    }
    public function validate_inputs_asset_type(Request $request)
    {
        return $this->assetService->validate_inputs_asset_type($request->all());
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
        $asset_type = $this->assetService->getAssetType($id);
        if($asset_type=="Restore first")
            return redirect()->back()->with('error', $asset_type);
        return \view('backend.pages.asset.editAssetType',compact('asset_type'));
    }
    public function validate_name_asset_type(Request $request, $id)
    {
        return $this->assetService->validate_name_asset_type($request->all(),$id);
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
}
