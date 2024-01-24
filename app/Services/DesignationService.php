<?php

namespace App\Services;

use App\Repositories\DesignationRepository;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Traits\AuthorizationTrait;

class DesignationService
{
    use AuthorizationTrait;
    private $designationRepository;
    public function __construct(DesignationRepository $designationRepository)
    {
        $this->designationRepository = $designationRepository;
    }
    public function getBranches()
    {
        return $this->designationRepository->getBranches();
    }
    public function validateInputs($data)
    {

        $this->designationRepository->setName($data['name']);
        $is_name_exists = $this->designationRepository->isNameExists();
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
    public function createDesignation($data)
    {
        return $this->designationRepository->setName($data['name'])
            ->setDescription($data['description'])
            ->setBranch_ids(isset($data['branches']) ? $data['branches']:null)
            ->setDepartment($data['department'])
            ->setStatus(Config::get('variable_constants.activation.active'))
            ->setCreatedAt(date('Y-m-d H:i:s'))
            ->create();
    }
    public function changeStatus($data)
    {
        return $this->designationRepository->change($data);
    }
    public function delete($data)
    {
        return $this->designationRepository->delete($data);
    }
    public function restore($id)
    {
        return $this->designationRepository->restore($id);
    }
    public function getDesignation($id)
    {
        return $this->designationRepository->getDesignation($id);
    }
    public function getAllBranches($id)
    {
        return $this->designationRepository->getAllBranches($id);
    }
    public function validateName($data,$id)
    {
        $this->designationRepository->setName($data['name']);
        $is_name_exists = $this->designationRepository->isNameUnique($id);
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
    public function update($data)
    {
        return $this->designationRepository->setId($data['id'])
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setBranch_ids(isset($data['branches']) ? $data['branches']:null)
            ->setDepartment($data['department'])
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->update();
    }
    public function fetchDepartments($data)
    {
        return $this->designationRepository->fetchDepartments($data);
    }
    public function fetchData()
    {
        $result = $this->designationRepository->getAllDesignationData();
        $hasDesignationManagePermission = $this->setId(auth()->user()->id)->setSlug('manageDesignation')->hasPermission();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $id = $row->id;
                $name= $row->name;
                $description = $row->description? $row->description:'N/A';
                $department = $row->department;
                $created_at = $row->created_at;
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
                $edit_url = route('edit_designation', ['designation'=>$id]);
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
                array_push($temp, $status);
                array_push($temp, $branches);
                array_push($temp, $department);
                if ($row->deleted_at) {
                    array_push($temp, ' <span class="badge badge-danger" >Yes</span>');
                } else {
                    array_push($temp, ' <span class="badge badge-success">No</span>');
                }
                array_push($temp, $created_at);
                if($hasDesignationManagePermission) {
                    array_push($temp, $action_btn);
                }
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

    public function getDesignations()
    {
        return $this->designationRepository->getDesignations();
    }
}
