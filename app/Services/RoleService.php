<?php

namespace App\Services;

use App\Repositories\RoleRepository;
use Illuminate\Support\Facades\Config;
use App\Traits\AuthorizationTrait;

class RoleService
{
    use AuthorizationTrait;
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }
    public function createRole($data)
    {
        return $this->roleRepository->setName($data['name'])
            ->setSl_no($data['sl_no'])
            ->setDescription($data['description'])
            ->setPermission_ids(isset($data['permissions']) ? $data['permissions']:null)
            ->setBranch_ids(isset($data['branches']) ? $data['branches']:null)
            ->setStatus(Config::get('variable_constants.activation.active'))
            ->setCreatedAt(date('Y-m-d H:i:s'))
            ->create();

    }
    public function getAllPermissions($id)
    {
        return $this->roleRepository->getAllPermissions($id);
    }
    public function getAllBranches($id)
    {
        return $this->roleRepository->getAllBranches($id);
    }
    public function getPermissions()
    {
        return $this->roleRepository->getPermissions();
    }
    public function getBranches()
    {
        return $this->roleRepository->getBranches();
    }
    public function getRole($id)
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
    public function changeStatus($data)
    {
        return $this->roleRepository->change($data);
    }
    public function delete($data)
    {
        return $this->roleRepository->delete($data);
    }
    public function restore($id)
    {
        return $this->roleRepository->restore($id);
    }
    public function update($data)
    {
        return $this->roleRepository->setId($data['id'])
            ->setSl_no($data['sl_no'])
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setPermission_ids(isset($data['permissions']) ? $data['permissions']:null)
            ->setBranch_ids(isset($data['branches']) ? $data['branches']:null)
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->update();
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
        $hasRoleManagePermission = $this->setId(auth()->user()->id)->setSlug('manageRole')->hasPermission();
        if ($result->count() > 0) {
            $data = array();

            foreach ($result as $key=>$row) {
                $id = $row->id;
                $name = $row->name;
                $description = $row->description? $row->description:'N/A';
                $sl_no = $row->sl_no;
                $created_at = $row->created_at;
                $permissions = '';
                foreach ($row->permissions as $p) {
                    $permissions.="<span class=\"badge badge-primary\">$p</span><br>";
                }
                $branches = '';
                foreach ($row->branches as $b) {
                    $branches.="<span class=\"badge badge-primary\">$b</span><br>";
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
                array_push($temp, $branches);
                if ($row->deleted_at) {
                    array_push($temp, ' <span class="badge badge-danger" >Yes</span>');
                } else {
                    array_push($temp, ' <span class="badge badge-success">No</span>');
                }
                array_push($temp, $created_at);
                if($hasRoleManagePermission) {
                    array_push($temp, $action_btn);
                }
                else
                    array_push($temp,'N/A');
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

    public function getRoles()
    {
        return $this->roleRepository->getRoles();
    }
}
