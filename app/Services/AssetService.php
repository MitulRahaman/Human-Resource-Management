<?php

namespace App\Services;

use App\Repositories\AssetRepository;
use Illuminate\Support\Facades\Config;
use App\Traits\AuthorizationTrait;
use Illuminate\Support\Facades\Storage;

class AssetService
{
    use AuthorizationTrait;
    private $assetRepository;

    public function __construct(AssetRepository $assetRepository)
    {
        $this->assetRepository = $assetRepository;
    }
    //    =============================start asset======================
    public function getAllAssetTypeData()
    {
        return $this->assetRepository->getAllAssetTypeData();
    }
    public function getAllBranches()
    {
        return $this->assetRepository->getAllBranches();
    }
    public function getAllUsers()
    {
        return $this->assetRepository->getAllUsers();
    }
    public function createAsset($data)
    {
        $file_name=null;
        if (isset($data['url'])) {

            $extension = $data['url']->getClientOriginalExtension();
            $file_name = random_int(0001, 9999).'.'.$extension;
            $file_path = 'asset/'.$file_name;
            Storage::disk('public')->put($file_path, file_get_contents($data['url']));
        } else {
            $file_path = null;
        }
        return $this->assetRepository->setName($data['name'])
            ->setTypeId($data['type_id'])
            ->setSlNo($data['sl_no'])
            ->setBranchId($data['branch_id'])
            ->setUrl($file_name)
            ->setSpecification($data['specification'])
            ->setPurchaseAt($data['purchase_at'])
            ->setPurchaseBy($data['purchase_by'])
            ->setPurchasePrice($data['purchase_price'])
            ->setStatus(Config::get('variable_constants.activation.active'))
            ->setCondition(Config::get('variable_constants.asset_condition.good'))
            ->setCreatedAt(date('Y-m-d H:i:s'))
            ->save();
    }
    public function update($data)
    {
        $file_name=null;
        if (isset($data['url'])) {

            $extension = $data['url']->getClientOriginalExtension();
            $file_name = random_int(0001, 9999).'.'.$extension;
            $file_path = 'asset/'.$file_name;
            Storage::disk('public')->put($file_path, file_get_contents($data['url']));
        } else {
            $file_path = null;
        }
        return $this->assetRepository->setId($data['id'])
            ->setName($data['name'])
            ->setTypeId($data['type_id'])
            ->setSlNo($data['sl_no'])
            ->setBranchId($data['branch_id'])
            ->setUrl($file_name)
            ->setSpecification($data['specification'])
            ->setPurchaseAt($data['purchase_at'])
            ->setPurchaseBy($data['purchase_by'])
            ->setPurchasePrice($data['purchase_price'])
            ->setStatus(Config::get('variable_constants.activation.active'))
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->update();
    }
    public function delete($data)
    {
        return $this->assetRepository->delete($data);
    }
    public function restore($id)
    {
        return $this->assetRepository->restore($id);
    }
    public function changeStatus($id)
    {
        return $this->assetRepository->change($id);
    }
    public function changeUserAssetStatus($id)
    {
        return $this->assetRepository->changeUserAssetStatus($id);
    }
    public function changeCondition($id, $data)
    {
        return $this->assetRepository->setId($id)->setCondition($data['condition'])->changeCondition();
    }
    public function getAsset($id)
    {
        return $this->assetRepository->getAsset($id);
    }
    public function fetchData()
    {
        $result = $this->assetRepository->getAllAssetData();
        $hasManageAssetPermission = $this->setSlug('manageAsset')->hasPermission();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $id = $row->id;
                $img_url = $this->assetRepository->getAssetImage($id);
                if($img_url) {
                    $url = asset('storage/asset/'. $img_url);
                    $img = "<td> <img src=\"$url\" class=\"w-100 rounded\" alt=\"user_img\"></td>";
                } else {
                    $url = asset('images/asset.jpeg');
                    $img = "<td> <img src=\"$url\" class=\"w-100 rounded\" alt=\"user_img\"></td>";
                }
                $name = $row->name;
                $asset_type= $this->assetRepository->getAssetType($row->type_id)->name;
                $sl_no = $row->sl_no? $row->sl_no:'N/A';
                $branch = $this->assetRepository->getBranchName($row->branch_id)->name;
                $specification = $row->specification? $row->specification:'N/A';
                $purchase_at = $row->purchase_at? $row->purchase_at:'N/A';
                $purchase_by = $row->purchase_by? $row->purchase_by:'N/A';
                $purchase_price = $row->purchase_price? $row->purchase_price:'N/A';
                $condition = $row->condition;
                $created_at = $row->created_at;
                $action = true;
                if ($condition == Config::get('variable_constants.asset_condition.good')) {
                    $condition = "<span class=\"badge badge-success text-center\">Good</span>";
                }elseif ($condition == Config::get('variable_constants.asset_condition.need_repair')){
                    $condition = "<span class=\"badge badge-warning text-center\" >Need to Repair</span>";
                }elseif ($condition == Config::get('variable_constants.asset_condition.damaged')){
                    $condition = "<span class=\"badge badge-danger text-center\" >Damaged</span>";
                    $action = false;
                }elseif ($condition == Config::get('variable_constants.asset_condition.destroyed')){
                    $condition = "<span class=\"badge badge-danger text-center\" >Destroyed</span>";
                    $action = false;
                }
                if ($row->status == Config::get('variable_constants.activation.active')) {
                    $status = "<span class=\"badge badge-success text-center\">Active</span>";
                    $status_msg = "Deactivate";
                }else{
                    $status = "<span class=\"badge badge-danger text-center\" >Inactive</span>";
                    $status_msg = "Activate";
                }
                $edit_url = route('edit_asset', ['id'=>$id]);
                $edit_btn = "<a class=\"dropdown-item\" href=\"$edit_url\">Edit</a>";
                $toggle_btn = "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_status_modal(\"$id\", \"$status_msg\")'> $status_msg </a>";
                $condition_btn = "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_condition_modal(\"$id\", \"$row->condition\")'> Change Condition</a>";
                if ($row->deleted_at) {
                    $toggle_delete_btn = "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_restore_modal(\"$id\", \"$name\")'>Restore</a>";
                } else {
                    $toggle_delete_btn = "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_delete_modal(\"$id\", \"$name\")'>Delete</a>";
                }
                $action_btn = "<div class=\"col-sm-6 col-xl-4\">
                                    <div class=\"dropdown\">
                                        <button type=\"button\" class=\"btn btn-success dropdown-toggle\" id=\"dropdown-default-success\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            Action
                                        </button>
                                        <div class=\"dropdown-menu font-size-sm\" aria-labelledby=\"dropdown-default-success\">";
                $action_btn .= "$edit_btn
                $toggle_btn
                $condition_btn
                $toggle_delete_btn
                ";
                $action_btn .= "</div>
                                    </div>
                                </div>";
                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $img);
                array_push($temp, $name);
                array_push($temp, $asset_type);
                array_push($temp, $sl_no);
                array_push($temp, $branch);
                array_push($temp, $specification);
                array_push($temp, $purchase_at);
                array_push($temp, $purchase_by);
                array_push($temp, $purchase_price);
                array_push($temp, $status);
                array_push($temp, $condition);
                if ($row->deleted_at) {
                    array_push($temp, ' <span class="badge badge-danger" >Yes</span>');
                } else {
                    array_push($temp, ' <span class="badge badge-success">No</span>');
                }

                array_push($temp, $created_at);
                if($hasManageAssetPermission && $action)
                    array_push($temp, $action_btn);
                else
                    array_push($temp, 'N/A');
                array_push($data, $temp);
            }
            return json_encode(array('data'=>$data));
        } else {
            return '{
                    "sEcho": 1,
                    "iTotalRecords": "0",
                    "iTotalDisplayRecords": "0",
                    "aaData": []
                }';
        }
    }
    public function fetchUserAssetData()
    {
        $result = $this->assetRepository->getAllUserAssetData();
        $hasDistributeAssetPermission = $this->setSlug('distributeAsset')->hasPermission();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $imgage = $row->image;
                if($imgage) {
                    $url = asset('storage/asset/'. $imgage);
                    $img = "<img src=\"$url\" class=\"w-100 rounded\" alt=\"user_img\">";
                } else {
                    $url = asset('images/asset.jpeg');
                    $img = "<img src=\"$url\" class=\"w-100 rounded\" alt=\"user_img\">";
                }
                $employee_id = $row->employee_id;
                $user_name = $row->user_name;
                $name = $row->name;
                $asset_type= $row->asset_type;
                $sl_no = $row->sl_no? $row->sl_no:'N/A';
                $branch = $row->branch;
                $condition = $row->condition;
                $created_at = $row->created_at;
                $by_requisition = $row->by_requisition;
                $action = true;
                if($by_requisition)
                    $by_requisition= "<span class=\"badge badge-success text-center\">Yes</span>";
                else
                    $by_requisition= "<span class=\"badge badge-danger text-center\">No</span>";
                if ($condition == Config::get('variable_constants.asset_condition.good')) {
                    $condition = "<span class=\"badge badge-success text-center\">Good</span>";
                }elseif ($condition == Config::get('variable_constants.asset_condition.need_repair')){
                    $condition = "<span class=\"badge badge-warning text-center\" >Need to Repair</span>";
                }elseif ($condition == Config::get('variable_constants.asset_condition.damaged')){
                    $condition = "<span class=\"badge badge-danger text-center\" >Damaged</span>";
                    $action = false;
                }elseif ($condition == Config::get('variable_constants.asset_condition.destroyed')){
                    $condition = "<span class=\"badge badge-danger text-center\" >Destroyed</span>";
                    $action = false;
                }
                if ($row->status == Config::get('variable_constants.activation.active')) {
                    $status = "<span class=\"badge badge-success text-center\">Active</span>";
                    $status_msg = "Deactivate";
                }else{
                    $status = "<span class=\"badge badge-danger text-center\" >Inactive</span>";
                    $status_msg = "Activate";
                }

                $toggle_btn = "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_status_modal(\"$row->user_asset_id\", \"$status_msg\")'> $status_msg </a>";
                $condition_btn = "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_condition_modal(\"$row->asset_id\", \"$row->condition\")'> Change Condition</a>";
                $action_btn = "<div class=\"col-sm-6 col-xl-4\">
                                    <div class=\"dropdown\">
                                        <button type=\"button\" class=\"btn btn-success dropdown-toggle\" id=\"dropdown-default-success\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            Action
                                        </button>
                                        <div class=\"dropdown-menu font-size-sm\" aria-labelledby=\"dropdown-default-success\">";
                $action_btn .= "$toggle_btn $condition_btn";
                $action_btn .= "</div>
                                    </div>
                                </div>";
                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $img);
                array_push($temp, $employee_id);
                array_push($temp, $user_name);
                array_push($temp, $name);
                array_push($temp, $asset_type);
                array_push($temp, $sl_no);
                array_push($temp, $branch);
                array_push($temp, $status);
                array_push($temp, $condition);
                array_push($temp, $by_requisition);
                array_push($temp, $created_at);
                if($hasDistributeAssetPermission && $action)
                    array_push($temp, $action_btn);
                else
                    array_push($temp, 'N/A');
                array_push($data, $temp);
            }
            return json_encode(array('data'=>$data));
        } else {
            return '{
                    "sEcho": 1,
                    "iTotalRecords": "0",
                    "iTotalDisplayRecords": "0",
                    "aaData": []
                }';
        }
    }
    //    =============================end asset======================

//    =============================start asset type======================
    public function validate_inputs_asset_type($data)
    {
        $this->assetRepository->setName($data['name']);
        $is_name_exists = $this->assetRepository->isNameExists();
        $name_msg = $is_name_exists ? 'Name already taken' : null;
        if(!$data['name']) $name_msg = 'Name is required';
        if ( $is_name_exists) {
            return [
                'success' => false,
                'name_msg' => $name_msg,
            ];
        } else {
            return [
                'success' => true,
                'name_msg' => $name_msg,
            ];
        }
    }
    public function createAssetType($data)
    {
        return $this->assetRepository->setName($data['name'])
            ->setStatus(Config::get('variable_constants.activation.active'))
            ->setCreatedAt(date('Y-m-d H:i:s'))
            ->saveAssetType();
    }
    public function getAssetType($id)
    {
        return $this->assetRepository->getAssetType($id);
    }
    public function validate_name_asset_type($data,$id)
    {
        $this->assetRepository->setName($data['name']);
        $is_name_exists = $this->assetRepository->isNameUnique($id);
        $name_msg = $is_name_exists ? 'Name already taken' : null;
        if(!$data['name']) $name_msg = 'Name is required';
        if ( $is_name_exists) {
            return [
                'success' => false,
                'name_msg' => $name_msg,
            ];
        } else {
            return [
                'success' => true,
                'name_msg' => $name_msg,
            ];
        }
    }
    public function edit_asset_type($data)
    {
        return $this->assetRepository->setId($data['id'])
            ->setName($data['name'])
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->updateAssetType();
    }
    public function restoreAssetType($id)
    {
        return $this->assetRepository->restoreAssetType($id);
    }
    public function deleteAssetType($id)
    {
        return $this->assetRepository->deleteAssetType($id);
    }
    public function changeStatusAssetType($id)
    {
        return $this->assetRepository->changeStatusAssetType($id);
    }
    public function fetchDataAssetType()
    {
        $result = $this->assetRepository->getAllAssetTypeData();
        $hasManageAssetTypePermission = $this->setSlug('manageAssetType')->hasPermission();
        if ($result->count() > 0) {
            $data = array();

            foreach ($result as $key=>$row) {
                $id = $row->id;
                $name = $row->name;
                $created_at = $row->created_at;
                if ($row->status == Config::get('variable_constants.activation.active')) {
                    $status = "<span class=\"badge badge-success\">Active</span>";
                    $status_msg = "Deactivate";
                }else{
                    $status = "<span class=\"badge badge-danger\" >Inactive</span>";
                    $status_msg = "Activate";
                }
                $edit_url = route('edit_asset_type', ['id'=>$id]);
                $edit_btn = "<a class=\"dropdown-item\" href=\"$edit_url\">Edit</a>";
                $toggle_btn = "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_status_modal(\"$id\", \"$status_msg\")'> $status_msg </a>";
                if ($row->deleted_at) {
                    $toggle_delete_btn = "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_restore_modal(\"$id\", \"$name\")'>Restore</a>";
                } else {
                    $toggle_delete_btn = "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_delete_modal(\"$id\", \"$name\")'>Delete</a>";
                }
                $action_btn = "<div class=\"col-sm-6 col-xl-4\">
                                    <div class=\"dropdown\">
                                        <button type=\"button\" class=\"btn btn-success dropdown-toggle\" id=\"dropdown-default-success\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            Action
                                        </button>
                                        <div class=\"dropdown-menu font-size-sm\" aria-labelledby=\"dropdown-default-success\">";
                $action_btn .= "$edit_btn
                $toggle_btn
                $toggle_delete_btn
                ";
                $action_btn .= "</div>
                                    </div>
                                </div>";
                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $name);
                array_push($temp, $status);
                if ($row->deleted_at) {
                    array_push($temp, ' <span class="badge badge-danger" >Yes</span>');
                } else {
                    array_push($temp, ' <span class="badge badge-success">No</span>');
                }

                array_push($temp, $created_at);
                if($hasManageAssetTypePermission)
                    array_push($temp, $action_btn);
                else
                    array_push($temp, 'N/A');
                array_push($data, $temp);
            }
            return json_encode(array('data'=>$data));
        } else {
            return '{
                    "sEcho": 1,
                    "iTotalRecords": "0",
                    "iTotalDisplayRecords": "0",
                    "aaData": []
                }';
        }
    }
    //    =============================end asset type======================
}
