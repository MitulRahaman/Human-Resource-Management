<?php

namespace App\Services;

use App\Events\RequisitionRequested;
use App\Jobs\RequisitionRequestJob;
use Mail;
use App\Mail\RequisitionMail;
use App\Repositories\RequisitionRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Traits\AuthorizationTrait;

class RequisitionService
{
    use AuthorizationTrait;
    private $requisitionRepository;

    public function __construct(RequisitionRepository $requisitionRepository)
    {
        $this->requisitionRepository = $requisitionRepository;
    }
    public function getAllAssetType()
    {
        return $this->requisitionRepository->getAllAssetType();
    }
    public function create($data,$request)
    {
        $requisition = $this->requisitionRepository->setName($data['name'])
            ->setSpecification($data['specification'])
            ->setAssetTypeId($data['asset_type_id'])
            ->setRemarks($data['remarks'])
            ->setStatus(Config::get('variable_constants.status.pending'))
            ->setCreatedAt(date('Y-m-d H:i:s'))
            ->create();
        if(is_int($requisition))
        {
            event(new RequisitionRequested($request->all()));
            return true;
        }
        return false;
    }
    public function requisitionEmail($data)
    {
        $assetType = $this->requisitionRepository->getAssetTypeName($data['asset_type_id']);
        $receivers = $this->requisitionRepository->setId(auth()->user()->id)->getRequisitionEmailRecipient();
        if(!$receivers) {
            return false;
        }
        $data =[
            'data' => $data,
            'assetTypeName' =>  $assetType->name,
            'to' => $receivers[1]->preferred_email,
            'from'=> $receivers[0]->email,
            'user_email' => auth()->user()->email,
            'user_name' => auth()->user()->full_name
        ];
        RequisitionRequestJob::dispatch($data);
        return true;
    }
    public function update($data)
    {
        return $this->requisitionRepository->setId($data['id'])
            ->setName($data['name'])
            ->setSpecification($data['specification'])
            ->setAssetTypeId($data['asset_type_id'])
            ->setRemarks($data['remarks'])
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->update();
    }
    public function getRequisitionRequest($id)
    {
        return $this->requisitionRepository->getRequisitionRequest($id);
    }
    public function delete($id)
    {
        return $this->requisitionRepository->delete($id);
    }
    public function approve($id)
    {
        return $this->requisitionRepository->approve( $id);
    }
    public function reject($id)
    {
        return $this->requisitionRepository->reject( $id);
    }
    public function cancel($id)
    {
        return $this->requisitionRepository->cancel($id);
    }
    public function fetchData()
    {
        $hasManageRequisitionPermission = $this->setSlug(Config::get('variable_constants.permission.manageRequisition'))->hasPermission();
        $userId= auth()->user()->id;
        $this->requisitionRepository->setPermission($hasManageRequisitionPermission);
        if(!$hasManageRequisitionPermission)
            $this->requisitionRepository->setUserId($userId);
        $result = $this->requisitionRepository->getTableData();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $id = $row->id;
                $employeeId = $row->employee_id;
                $employeeName = $row->full_name;
                $asset_type = $row->type_name? $row->type_name:'N/A';
                $name= $row->name;
                $specification= $row->specification;
                $remarks = $row->remarks;
                $status="";
                if($row->status== Config::get('variable_constants.status.pending'))
                    $status = "<span class=\"badge badge-primary\">pending</span><br>" ;
                elseif ($row->status== Config::get('variable_constants.status.approved'))
                    $status = "<span class=\"badge badge-success\">approved</span><br>" ;
                elseif ($row->status== Config::get('variable_constants.status.rejected'))
                    $status = "<span class=\"badge badge-danger\">rejected</span><br>" ;
                elseif ($row->status== Config::get('variable_constants.status.canceled'))
                    $status = "<span class=\"badge badge-danger\">canceled</span><br>" ;
                elseif ($row->status== Config::get('variable_constants.status.given'))
                    $status = "<span class=\"badge badge-success\">given</span><br>" ;

                $delete_url = url('requisition/'.$id.'/delete');
                $toggle_delete_btn = "<a class=\"dropdown-item\" href=\"$delete_url\">Delete</a>";
                $action_btn = "<div class=\"col-sm-6 col-xl-4\">
                                    <div class=\"dropdown\">
                                        <button type=\"button\" class=\"btn btn-secondary dropdown-toggle\" id=\"dropdown-default-secondary\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            Action
                                        </button>
                                        <div class=\"dropdown-menu font-size-sm\" aria-labelledby=\"dropdown-default-secondary\">";

                $approve_btn="<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_approve_modal(\"$id\", \"$asset_type\")'>Approve</a>";
                $reject_btn="<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_reject_modal(\"$id\", \"$asset_type\")'>Reject</a>";
                if($hasManageRequisitionPermission)
                {
                    if($row->status== Config::get('variable_constants.status.pending'))
                    {
                        $action_btn .= "$approve_btn $reject_btn";
                    }
                }
                elseif ($userId==$row->user_id)
                {
                    if($row->status== Config::get('variable_constants.status.pending'))
                    {
                        $edit_url = url('requisition/'.$id.'/edit');
                        $edit_btn = "<a class=\"dropdown-item\" href=\"$edit_url\">Edit</a>";
                        $cancel_url = url('requisition/status/'.$id.'/cancel');
                        $cancel_btn = "<a class=\"dropdown-item\" href=\"$cancel_url\">Cancel</a>";
                        $action_btn .= "$edit_btn $cancel_btn $toggle_delete_btn";
                    }
                    else $action_btn .= "$toggle_delete_btn";
                }

                $action_btn .= "</div>
                                    </div>
                                </div>";

                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $employeeId);
                array_push($temp, $employeeName);
                array_push($temp, $asset_type);
                array_push($temp, $name);
                array_push($temp, $specification);
                array_push($temp, $status);
                array_push($temp, $remarks);
                if($hasManageRequisitionPermission)
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
