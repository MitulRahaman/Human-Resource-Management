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
use Illuminate\Support\Facades\Validator;
use App\Traits\AuthorizationTrait;

class AssetController extends Controller
{
    use AuthorizationTrait;
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
        $addAssetPermission = $this->setSlug('addAsset')->hasPermission();
        return \view('backend.pages.asset.index',compact('addAssetPermission'));
    }
    public function fetchData()
    {
        return $this->assetService->fetchData();
    }
    public function fetchUserAssetData()
    {
        return $this->assetService->fetchUserAssetData();
    }
    public function create()
    {
        abort_if(!$this->setSlug('addAsset')->hasPermission(), 403, 'You don\'t have permission!');
        View::share('sub_menu', 'Add Asset');
        $asset_type = $this->assetService->getAllAssetTypeData();
        $branches = $this->assetService->getAllBranches();
        $users = $this->assetService->getAllUsers();
        return \view('backend.pages.asset.create', compact('asset_type', 'branches', 'users'));
    }
    public function store(AssetAddRequest $request)
    {
        abort_if(!$this->setSlug('addAsset')->hasPermission(), 403, 'You don\'t have permission!');
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
        abort_if(!$this->setSlug('editAsset')->hasPermission(), 403, 'You don\'t have permission!');
        View::share('sub_menu', 'Manage Assets');
        $asset = $this->assetService->getAsset($id);
        if($asset && !is_null($asset->deleted_at))
            return redirect()->back()->with('error', 'Restore first');
        $asset_type = $this->assetService->getAllAssetTypeData();
        $branches = $this->assetService->getAllBranches();
        $users = $this->assetService->getAllUsers();
        return \view('backend.pages.asset.edit',compact('asset', 'asset_type', 'branches', 'users'));
    }
    public function update(AssetEditRequest $request)
    {
        abort_if(!$this->setSlug('editAsset')->hasPermission(), 403, 'You don\'t have permission!');
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
        abort_if(!$this->setSlug('manageAsset')->hasPermission(), 403, 'You don\'t have permission!');
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
        abort_if(!$this->setSlug('manageAsset')->hasPermission(), 403, 'You don\'t have permission!');
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
        abort_if(!$this->setSlug('manageAsset')->hasPermission(), 403, 'You don\'t have permission!');
        try{
            if($this->assetService->changeStatus($id))
                return redirect('asset/')->with('success', 'Assets status changed successfully!');
            return redirect('asset/')->with('error', 'Assets status not changed!');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function changeUserAssetStatus($id)
    {
        abort_if(!$this->setSlug('distributeAsset')->hasPermission(), 403, 'You don\'t have permission!');
        try{
            if($this->assetService->changeUserAssetStatus($id))
                return redirect('asset/user_assets')->with('success', 'User Assets status changed successfully!');
            return redirect('asset/user_assets')->with('error', 'User Assets status not changed!');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function changeCondition($id, Request $request)
    {
        abort_if(!$this->setSlug('manageAsset')->hasPermission(), 403, 'You don\'t have permission!');
        try{
            if($this->assetService->changeCondition($id, $request->all()))
                return redirect()->back()->with('success', 'Assets condition changed successfully!');
            return redirect()->back()->with('error', 'Assets condition not changed!');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function userAssets()
    {
        View::share('sub_menu', 'User Assets');
        return \view('backend.pages.asset.userAssets');
    }

//    =============================end asset======================

//    =============================start asset type======================
    public function assetTypeIndex()
    {
        View::share('sub_menu', 'Assets Type');
        $addAssetTypePermission = $this->setSlug('addAssetType')->hasPermission();
        return \view('backend.pages.asset.assetTypeIndex', compact('addAssetTypePermission'));
    }
    public function fetchDataAssetType()
    {
        return $this->assetService->fetchDataAssetType();
    }
    public function createAssetType()
    {
        abort_if(!$this->setSlug('addAssetType')->hasPermission(), 403, 'You don\'t have permission!');
        View::share('sub_menu', 'Assets Type');
        return \view('backend.pages.asset.createAssetType');
    }
    public function validate_inputs_asset_type(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if(!$validator->fails())
            return $this->assetService->validate_inputs_asset_type($request->all());
        return redirect()->back()->with('error', 'Name is Required to validate');
    }
    public function storeAssetType(AssetTypeAddRequest $request)
    {
        abort_if(!$this->setSlug('addAssetType')->hasPermission(), 403, 'You don\'t have permission!');
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
        abort_if(!$this->setSlug('editAssetType')->hasPermission(), 403, 'You don\'t have permission!');
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
        if(!$validator->fails())
            return $this->assetService->validate_name_asset_type($request->all(),$id);
        return redirect()->back()->with('error', 'Name is Required to validate');
    }
    public function update_asset_type(AssetTypeEditRequest $request)
    {
        abort_if(!$this->setSlug('editAssetType')->hasPermission(), 403, 'You don\'t have permission!');
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
        abort_if(!$this->setSlug('manageAssetType')->hasPermission(), 403, 'You don\'t have permission!');
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
        abort_if(!$this->setSlug('manageAssetType')->hasPermission(), 403, 'You don\'t have permission!');
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
        abort_if(!$this->setSlug('manageAssetType')->hasPermission(), 403, 'You don\'t have permission!');
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
