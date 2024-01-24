<?php

namespace App\Services;

use App\Events\RequisitionRequested;
use App\Jobs\RequisitionRequestApproveJob;
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
            ->setStatus(Config::get('variable_constants.requisition_status.pending'))
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
            'to' => $receivers[1],
            'cc'=> $receivers[0],
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
        return $this->requisitionRepository->setId($id)->getRequisitionRequest();
    }
    public function delete($id)
    {
        return $this->requisitionRepository->setId($id)->delete();
    }
    public function approve($id)
    {
        $approve = $this->requisitionRepository->setId($id)->approve();
        if($approve)
        {
            $requisition_info = $this->requisitionRepository->getRequisitionInfo($id);
            $to = $this->requisitionRepository->getRequestedUserMail($id);
            $receivers = $this->requisitionRepository->setId(auth()->user()->id)->getRequisitionApproveEmailRecipient($id);
            if(!$receivers) {
                $receivers='';
            }
            $data =[
                'requisition_info' =>  $requisition_info,
                'to' => $to->preferred_email,
                'cc'=> $receivers,
                'user_email' => auth()->user()->email,
                'user_name' => auth()->user()->full_name
            ];
            RequisitionRequestApproveJob::dispatch($data);
            return true;
        }
        return false;
    }
    public function reject($id)
    {
        return $this->requisitionRepository->setId($id)->reject();
    }
    public function cancel($id)
    {
        return $this->requisitionRepository->setId($id)->cancel();
    }
    public function receive($id)
    {
        return $this->requisitionRepository->setId($id)->receive();
    }
    public function processing($id)
    {
        return $this->requisitionRepository->setId($id)->processing();
    }
    public function deliver($id, $data)
    {
        return $this->requisitionRepository->setId($id)->setAssetId($data['asset_id'])->deliver();
    }
    public function getAllAssets($data)
    {
        return $this->requisitionRepository->setId($data['id'])->getAllAssets();
    }
    public function fetchData()
    {
        $hasManageRequisitionPermission = $this->setSlug(Config::get('variable_constants.permission.manageRequisition'))->hasPermission();
        $userId= auth()->user()->id;
        $this->requisitionRepository->setPermission($hasManageRequisitionPermission);
        if(!$hasManageRequisitionPermission)
            $this->requisitionRepository->setUserId($userId);
        $result = $this->requisitionRepository->getTableData();
        $super_user = auth()->user()->is_super_user;
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
                if($row->status== Config::get('variable_constants.requisition_status.pending'))
                    $status = "<span class=\"badge badge-primary\">pending</span><br>" ;
                elseif($row->status== Config::get('variable_constants.requisition_status.received'))
                    $status = "<span class=\"badge badge-primary\">received</span><br>" ;
                elseif ($row->status== Config::get('variable_constants.requisition_status.approved'))
                    $status = "<span class=\"badge badge-success\">approved</span><br>" ;
                elseif ($row->status== Config::get('variable_constants.requisition_status.rejected'))
                    $status = "<span class=\"badge badge-danger\">rejected</span><br>" ;
                elseif ($row->status== Config::get('variable_constants.requisition_status.canceled'))
                    $status = "<span class=\"badge badge-danger\">canceled</span><br>" ;
                elseif($row->status== Config::get('variable_constants.requisition_status.processing'))
                    $status = "<span class=\"badge badge-primary\">processing</span><br>" ;
                elseif($row->status== Config::get('variable_constants.requisition_status.delivered'))
                    $status = "<span class=\"badge badge-success\">delivered</span><br>" ;

                $delete_url = url('requisition/'.$id.'/delete');
                $toggle_delete_btn = "<a class=\"dropdown-item\" href=\"$delete_url\">Delete</a>";
                $action_btn = "<div class=\"col-sm-6 col-xl-4\">
                                    <div class=\"dropdown\">
                                        <button type=\"button\" class=\"btn btn-secondary dropdown-toggle\" id=\"dropdown-default-secondary\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            Action
                                        </button>
                                        <div class=\"dropdown-menu font-size-sm\" aria-labelledby=\"dropdown-default-secondary\">";

                $approve_btn="<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_approve_modal(\"$id\", \"$name\")'>Approve</a>";
                $reject_btn="<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_reject_modal(\"$id\", \"$name\")'>Reject</a>";
                $edit_url = url('requisition/'.$id.'/edit');
                $edit_btn = "<a class=\"dropdown-item\" href=\"$edit_url\">Edit</a>";
                $cancel_url = url('requisition/status/'.$id.'/cancel');
                $cancel_btn = "<a class=\"dropdown-item\" href=\"$cancel_url\">Cancel</a>";
                $receive_url = url('requisition/status/'.$id.'/receive');
                $receive_btn = "<a class=\"dropdown-item\" href=\"$receive_url\">Receive</a>";
                $processing_url = url('requisition/status/'.$id.'/processing');
                $processing_btn = "<a class=\"dropdown-item\" href=\"$processing_url\">Process</a>";
                $deliver_btn="<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_deliver_modal(\"$id\", \"$name\")'>Deliver</a>";
                if($super_user)
                {
                    $action_btn .= "$receive_btn $approve_btn $reject_btn $processing_btn $deliver_btn";
                }
                elseif($hasManageRequisitionPermission)
                {
                    if($row->status== Config::get('variable_constants.requisition_status.pending'))
                    {
                        $action_btn .= "$receive_btn ";
                        if($userId==$row->user_id)
                        {
                            $action_btn .= "$edit_btn $cancel_btn $toggle_delete_btn";
                        }
                    }
                    elseif ($row->status== Config::get('variable_constants.requisition_status.received'))
                    {
                        $action_btn .= "$approve_btn $reject_btn";
                    }
                    elseif ($row->status== Config::get('variable_constants.requisition_status.approved'))
                    {
                        $action_btn .= "$processing_btn ";
                    }
                    elseif ($row->status== Config::get('variable_constants.requisition_status.processing'))
                    {
                        $action_btn .= "$deliver_btn ";
                    }
                    else $action_btn = "N/A";
                }
                elseif ($userId==$row->user_id)
                {
                    if($row->status== Config::get('variable_constants.requisition_status.pending'))
                    {
                        $action_btn .= "$edit_btn $cancel_btn $toggle_delete_btn";
                    }
                    else $action_btn = "N/A";
                }else
                {
                    $action_btn = "N/A";
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
