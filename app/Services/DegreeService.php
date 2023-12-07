<?php

namespace App\Services;

use App\Repositories\DegreeRepository;
use Illuminate\Support\Facades\Config;
use App\Traits\AuthorizationTrait;

class DegreeService
{
    use AuthorizationTrait;
    private $degreeRepository;

    public function __construct(DegreeRepository $degreeRepository)
    {
        $this->degreeRepository = $degreeRepository;
    }
    public function validateInputs($data)
    {
        $this->degreeRepository->setName($data['name']);
        $is_name_exists = $this->degreeRepository->isNameExists();
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
    public function createDegree($data)
    {
        return $this->degreeRepository->setName($data['name'])
            ->setDescription($data['description'])
            ->setCreatedAt(date('Y-m-d H:i:s'))
            ->save();
    }
    public function getDegree($id)
    {
        return $this->degreeRepository->getDegree($id);
    }
    public function validateName($data,$id)
    {
        $this->degreeRepository->setName($data['name']);
        $is_name_exists = $this->degreeRepository->isNameUnique($id);
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
        return $this->degreeRepository->setId($data['id'])
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->update();
    }
    public function delete($data)
    {
        return $this->degreeRepository->delete($data);
    }
    public function restore($id)
    {
        return $this->degreeRepository->restore($id);
    }
    public function fetchData()
    {
        $result = $this->degreeRepository->getAllDegreeData();
        $hasDegreeManagePermission = $this->setId(auth()->user()->id)->setSlug('manageDegree')->hasPermission();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $id = $row->id;
                $name = $row->name;
                $description = $row->description? $row->description:'N/A';
                $created_at = $row->created_at;
                $edit_url = route('edit_degree', ['degree'=>$id]);
                $edit_btn = "<a class=\"dropdown-item\" href=\"$edit_url\">Edit</a>";
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
                $toggle_delete_btn
                ";
                $action_btn .= "</div>
                                    </div>
                                </div>";
                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $name);
                array_push($temp, $description);
                if ($row->deleted_at) {
                    array_push($temp, ' <span class="badge badge-danger" >Yes</span>');
                } else {
                    array_push($temp, ' <span class="badge badge-success">No</span>');
                }
                array_push($temp, $created_at);
                if($hasDegreeManagePermission)
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

}
