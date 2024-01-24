<?php

namespace App\Services;

use App\Repositories\LeaveRepository;
use Illuminate\Support\Facades\Config;
use App\Traits\AuthorizationTrait;

class LeaveService
{
    use AuthorizationTrait;
    private $leaveRepository;

    public function __construct(LeaveRepository $leaveRepository)
    {
        $this->leaveRepository = $leaveRepository;
    }

    public function indexLeave()
    {
        return $this->leaveRepository->indexLeave();
    }

    public function manageLeave()
    {
        return $this->leaveRepository->manageLeave();
    }

    public function storeLeave($data)
    {
        return $this->leaveRepository->storeLeave($data);
    }

    public function editLeave($id)
    {
        return $this->leaveRepository->editLeave($id);
    }

    public function updateLeave($data, $id)
    {
        return $this->leaveRepository->updateLeave($data, $id);
    }

    public function updateStatus($id)
    {
        return $this->leaveRepository->updateStatus($id);
    }

    public function destroyLeave($id)
    {
        return $this->leaveRepository->destroyLeave($id);
    }

    public function restoreLeave($id)
    {
        return $this->leaveRepository->restoreLeave($id);
    }

    public function validateInputs($data)
    {
        $this->leaveRepository->setName($data['name']);
        $is_name_exists = $this->leaveRepository->isNameExists();
        if ($data->name == null) {
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
        $this->leaveRepository->setName($data['name']);
        $is_name_exists_for_update = $this->leaveRepository->isNameExistsForUpdate($data['current_name']);
        if ($data->name == null) {
            return [
                'success' => false,
                'name_null_msg' => 'Please select a name',
            ];
        } else if($is_name_exists_for_update != null) {
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

    public function getTypeWiseTotalLeavesData($year)
    {
        $result = $this->leaveRepository->setYear($year)->getTypeWiseTotalLeavesData();
        $hasManageLeavePermission = $this->setId(auth()->user()->id)->setSlug('manageLeaves')->hasPermission();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $id = $row->id;
                $name = $row->name;
                $total_leaves = $row->total_leaves;

                $currentYear = date("Y");

                if($year >= $currentYear) {
                    if ($total_leaves > 0) {
                        $action_btn = "<td> <button type=\"button\"class=\"d-none d-sm-table-cell font-size-sm border-0\"id=\"btn_update\"name=\"btn_update\"data-toggle=\"modal\" data-target=\"#modal-block-slideup\" onclick=\"openmodal($id, $total_leaves)\">
                        <i class=\"fas fa-edit text-success mr-1\"></i>Update</td>";

                    } else {
                        $action_btn = "<td> <button type=\"button\"class=\"d-none d-sm-table-cell font-size-sm border-0\"id=\"btn_add\"name=\"btn_add\"data-toggle=\"modal\"data-target=\"#modal-block-slideup\" onclick=\"openmodal($id, $total_leaves)\">
                        <i class=\"fas fa-plus text-success mr-1\"></i>Add</td>";
                    }
                } else {
                    if ($total_leaves > 0) {
                        $action_btn = "<td> <button type=\"button\"class=\"d-none d-sm-table-cell font-size-sm border-0\"id=\"btn_update\"name=\"btn_update\"data-toggle=\"modal\" data-target=\"#modal-block-slideup\"disabled=\"true\">
                        <i class=\"fas fa-edit text-success mr-1\"></i>Update</td>";

                    } else {
                        $action_btn = "<td> <button type=\"button\"class=\"d-none d-sm-table-cell font-size-sm border-0\"id=\"btn_add\"name=\"btn_add\"data-toggle=\"modal\"data-target=\"#modal-block-slideup\"disabled=\"true\">
                        <i class=\"fas fa-plus text-success mr-1\"></i>Add</td>";
                    }
                }

                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $name);
                array_push($temp, $year);
                array_push($temp, $total_leaves);
                if($hasManageLeavePermission) {
                    array_push($temp, $action_btn);
                }
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

    public function addTotalLeave($data, $id)
    {
        $is_type_and_year_exist = $this->leaveRepository->setUpdateYear($data->updateYear)->setTotalLeave($data->totalLeave)->isTypeAndYearExist($data, $id);
        return $this->leaveRepository->addTotalLeave($is_type_and_year_exist, $data, $id);
    }
 }
