<?php

namespace App\Services;

use App\Repositories\InstituteRepository;
use Illuminate\Support\Facades\Config;
use App\Traits\AuthorizationTrait;

class InstituteService
{
    use AuthorizationTrait;
    private $instituteRepository;

    public function __construct(InstituteRepository $instituteRepository)
    {
        $this->instituteRepository = $instituteRepository;
    }
    public function validateInputs($data)
    {
        $this->instituteRepository->setName($data['name']);
        $is_name_exists = $this->instituteRepository->isNameExists();
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
    public function createInstitute($data)
    {
        return $this->instituteRepository->setName($data['name'])
            ->setAddress($data['address'])
            ->setStatus(Config::get('variable_constants.activation.active'))
            ->setCreatedAt(date('Y-m-d H:i:s'))
            ->save();
    }
    public function getInstitute($id)
    {
        return $this->instituteRepository->getInstitute($id);
    }
    public function validateName($data,$id)
    {
        $this->instituteRepository->setName($data['name']);
        $is_name_exists = $this->instituteRepository->isNameUnique($id);
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
    public function edit($data)
    {
        return $this->instituteRepository->setId($data['id'])
            ->setName($data['name'])
            ->setAddress($data['address'])
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->update();
    }
    public function restore($id)
    {
        return $this->instituteRepository->restore($id);
    }
    public function delete($data)
    {
        return $this->instituteRepository->delete($data);
    }
    public function changeStatus($data)
    {
        return $this->instituteRepository->change($data);
    }
    public function fetchData()
    {
        $result = $this->instituteRepository->getAllInstituteData();
        $hasInstitutionManagePermission = $this->setId(auth()->user()->id)->setSlug('manageInstitute')->hasPermission();
        if ($result->count() > 0) {
            $data = array();

            foreach ($result as $key=>$row) {
                $id = $row->id;
                $name = $row->name;
                $address = $row->address? $row->address:'N/A';
                $created_at = $row->created_at;
                if ($row->status == Config::get('variable_constants.activation.active')) {
                    $status = "<span class=\"badge badge-success\">Active</span>";
                    $status_msg = "Deactivate";
                }else{
                    $status = "<span class=\"badge badge-danger\" >Inactive</span>";
                    $status_msg = "Activate";
                }
                $edit_url = route('edit_institute', ['institute'=>$id]);
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
                array_push($temp, $address);
                array_push($temp, $status);
                if ($row->deleted_at) {
                    array_push($temp, ' <span class="badge badge-danger" >Yes</span>');
                } else {
                    array_push($temp, ' <span class="badge badge-success">No</span>');
                }
                array_push($temp, $created_at);
                if($hasInstitutionManagePermission){
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

}
