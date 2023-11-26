<?php

namespace App\Services;

use App\Repositories\AssetRepository;
use Illuminate\Support\Facades\Config;

class AssetService
{
    private $assetRepository;

    public function __construct(AssetRepository $assetRepository)
    {
        $this->assetRepository = $assetRepository;
    }
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
    public function deleteAssetType($data)
    {
        return $this->assetRepository->deleteAssetType($data);
    }
    public function changeStatusAssetType($data)
    {
        return $this->assetRepository->changeStatusAssetType($data);
    }
    public function fetchDataAssetType()
    {
        $result = $this->assetRepository->getAllAssetTypeData();
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
                array_push($temp, $action_btn);
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

}
