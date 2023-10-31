<?php

namespace App\Services;

use App\Repositories\BranchRepository;
use Illuminate\Support\Facades\Config;

class BranchService
{
    private $branchRepository;

    public function __construct(BranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    public function fetchData()
    {
        $result = $this->branchRepository->getAllBranchData();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $id = $row->id;
                $name = $row->name;
                $address = $row->address;
                $created_at = $row->created_at;
                $deleted_at = $row->deleted_at;
                
                $edit_url = route('edit_branch', ['branch'=>$id]);

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
                array_push($temp, $address);
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

    public function indexBranch()
    {
        return $this->branchRepository->indexBranch();
    }

    public function storeBranch($data)
    {
        return $this->branchRepository->storeBranch($data);
    }

    public function editBranch($id)
    {
        return $this->branchRepository->editBranch($id);
    }

    public function updateBranch($data, $id)
    {
        return $this->branchRepository->updateBranch($data, $id);
    }

    public function updateStatus($id)
    {
        return $this->branchRepository->updateStatus($id);
    }

    public function destroyBranch($id)
    {
        return $this->branchRepository->destroyBranch($id);
    }

    public function restoreBranch($id)
    {
        return $this->branchRepository->restoreBranch($id);
    }

    public function validateInputs($data)
    {
        $this->branchRepository->setName($data['name']);
        $is_name_exists = $this->branchRepository->isNameExists();
        if ($data['name'] == null) {
            return [
                'success' => false,
                'name_null_msg' => 'Please select a name',
            ];
        } else if($is_name_exists != null) {
            return [
                'success' => false,
                'name_msg' => 'Name already taken',
            ];
        }
        else {
            return [
                'success' => true,
                'name_msg' => null,
            ];
        }
    }

    public function UpdateInputs($data)
    {
        $this->branchRepository->setName($data['name']);
        $is_name_exists_for_update = $this->branchRepository->isNameExistsForUpdate($data['current_name']);
        if ($data->name == null) {
            return [
                'success' => false,
                'name_msg' => 'Please select a name',
            ];
        }
        else if ($is_name_exists_for_update) {
            return [
                'success' => true,
            ];
        } else {
            return [
                'success' => false,
                'name_msg' => 'Name already taken',
            ];
        }
    }
}
