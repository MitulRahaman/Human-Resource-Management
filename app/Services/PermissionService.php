<?php

namespace App\Services;

use App\Repositories\PermissionRepository;
use Illuminate\Support\Facades\Config;

class PermissionService
{
    private $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }


    public function createPermission($data)
    {
        // You can add any business logic or validation here before saving
        return $this->permissionRepository->create($data);
    }

    public function changeStatus(int $data)
    {
        // You can add any business logic or validation here before saving
        return $this->permissionRepository->change($data);
    }
    public function delete(int $data)
    {
        // You can add any business logic or validation here before saving
        return $this->permissionRepository->delete($data);
    }

    public function getPermission(int $id)
    {
        return $this->permissionRepository->getPermission($id);
    }
    public function edit($data, int $id)
    {
        return $this->permissionRepository->edit($data,$id);
    }
    public function restore(int $id)
    {
        return $this->permissionRepository->restore($id);
    }

    public function fetchData()
    {
        $result = $this->permissionRepository->getAllPermissionData();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $id = $row->id;
                $slug = $row->slug;
                $name = $row->name;
                $description = $row->description;
                $created_at = $row->created_at;
                $deleted_at = $row->deleted_at;
                if ($row->status == Config::get('variable_constants.activation.active')) {
                    $status = "Active";
                    $status_msg = "Deactivate";
                }else{
                    $status = "Inactive";
                    $status_msg = "Activate";
                }
                $edit_url = route('edit_permission', ['permission'=>$id]);
//              dd($edit_url);
                $edit_btn = "<a class=\"dropdown-item\" href=\"$edit_url\">Edit</a>";
                $toggle_btn = "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_status_modal(\"$id\", \"$status_msg\")'>$status_msg</a>";
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
                array_push($temp, $slug);
                array_push($temp, $description);
                array_push($temp, $status);
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

    public function validateInputs($data)
    {
        $this->permissionRepository->setSlug($data['slug']);
        $this->permissionRepository->setName($data['name']);

        $is_slug_exists = $this->permissionRepository->isSlugExists();
        $is_name_exists = $this->permissionRepository->isNameExists();


        $slug_msg = $is_slug_exists ? 'Slug already taken' : null;
        $name_msg = $is_name_exists ? 'Name already taken' : null;

        if ($is_slug_exists || $is_name_exists) {
            return [
                'success' => false,
                'slug_msg' => $slug_msg,
                'name_msg' => $name_msg,
            ];
        } else {
            return [
                'success' => true,
                'slug_msg' => $slug_msg,
                'name_msg' => $name_msg,
            ];
        }
    }
    public function validateName($data)
    {
        $this->permissionRepository->setName($data['name']);

        $is_name_exists = $this->permissionRepository->isNameUnique($data);

        $name_msg = $is_name_exists ? 'Name already taken' : null;

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
}
