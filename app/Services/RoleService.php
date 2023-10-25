<?php

namespace App\Services;

use App\Repositories\RoleRepository;
use Illuminate\Support\Facades\Config;

class RoleService
{
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }
    public function createRole($data)
    {

        return $this->roleRepository->create($data);
    }
    public function getAllPermissions()
    {
        return $this->roleRepository->getAllPermissions();
    }
    public function getPermission($id)
    {
        return $this->roleRepository->getPermission($id);
    }
    public function getRole(int $id)
    {
        return $this->roleRepository->getRole($id);
    }
    public function validateInputs($data)
    {

        $this->roleRepository->setName($data['name']);
        $is_name_exists = $this->roleRepository->isNameExists();
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
    public function changeStatus(int $data)
    {
        return $this->roleRepository->change($data);
    }
    public function delete(int $data)
    {
        return $this->roleRepository->delete($data);
    }
    public function restore(int $id)
    {
        return $this->roleRepository->restore($id);
    }
    public function edit($data, int $id)
    {
        return $this->roleRepository->edit($data,$id);
    }
    public function validateName($data,$id)
    {
        $this->roleRepository->setName($data['name']);
        $is_name_exists = $this->roleRepository->isNameUnique($id);
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
    public function fetchData()
    {
        $result = $this->roleRepository->getAllRoleData();
        if ($result->count() > 0) {
            $data = array();

            foreach ($result as $key=>$row) {
                $id = $row->id;
                $name = $row->name;
                $description = $row->description;
                $sl_no = $row->sl_no;
                $created_at = $row->created_at;
                $permissions = '';
                foreach ($row->permissions as $p) {
                    $permissions .= ($permissions ? ', ' : '') . $p;
                }
                if ($row->status == Config::get('variable_constants.activation.active')) {
                    $status = "<span class=\"badge badge-success\">Active</span>";
                    $status_msg = "Deactivate";
                }else{
                    $status = "<span class=\"badge badge-danger\" >Inactive</span>";
                    $status_msg = "Activate";
                }
                $edit_url = route('edit_role', ['role'=>$id]);
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

                array_push($temp, $description);
                array_push($temp, $sl_no);
                array_push($temp, $status);
                array_push($temp, $permissions);
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
