<?php

namespace App\Services;

use Mail;
use App\Mail\RequisitionMail;
use App\Repositories\DashboardRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Traits\AuthorizationTrait;
use Carbon\Carbon;

class DashboardService
{
    use AuthorizationTrait;
    private $dashboardRepository;

    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }
    public function totalRequisitionRequests()
    {
        return $this->dashboardRepository->totalRequisitionRequests();
    }
    public function totalOnLeave()
    {
        return $this->dashboardRepository->totalOnLeave();
    }
    public function totalPendingLeave()
    {
        return $this->dashboardRepository->totalPendingLeave();
    }
    public function totalPendingRequisition()
    {
        return $this->dashboardRepository->totalPendingRequisition();
    }
    public function totalUser()
    {
        return $this->dashboardRepository->totalUser();
    }
    public function fetchRequisitionData($page, $limit)
    {
        $offset = ($page-1)*$limit;
        $this->dashboardRepository->setOffset($offset)->setLimit($limit);
        $result = $this->dashboardRepository->getRequisitionTableData();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key => $row) {
                $employeeId = $row->employee_id;
                $employeeName = $row->full_name;
                $asset_type = $row->type_name;
                $name= $row->name;
                $specification= $row->specification;
                $remarks = $row->remarks;
                $status="";
                if($row->status== Config::get('variable_constants.requisition_status.pending'))
                    $status = "<span class=\"font-size-sm font-w600 px-2 py-1 rounded  bg-primary-light text-primary\">pending</span><br>" ;
                elseif($row->status== Config::get('variable_constants.requisition_status.received'))
                    $status = "<span class=\"font-size-sm font-w600 px-2 py-1 rounded  bg-primary-light text-primary\">received</span><br>" ;
                elseif ($row->status== Config::get('variable_constants.requisition_status.approved'))
                    $status = "<span class=\"font-size-sm font-w600 px-2 py-1 rounded  bg-success-light text-success\">approved</span><br>" ;
                elseif ($row->status== Config::get('variable_constants.requisition_status.rejected'))
                    $status = "<span class=\"font-size-sm font-w600 px-2 py-1 rounded  bg-danger-light text-danger\">rejected</span><br>" ;
                elseif ($row->status== Config::get('variable_constants.requisition_status.canceled'))
                    $status = "<span class=\"font-size-sm font-w600 px-2 py-1 rounded  bg-danger-light text-danger\">canceled</span><br>" ;
                elseif($row->status== Config::get('variable_constants.requisition_status.processing'))
                    $status = "<span class=\"font-size-sm font-w600 px-2 py-1 rounded  bg-primary-light text-primary\">processing</span><br>" ;
                elseif($row->status== Config::get('variable_constants.requisition_status.delivered'))
                    $status = "<span class=\"font-size-sm font-w600 px-2 py-1 rounded  bg-success-light text-success\">delivered</span><br>" ;
                $created_at = Carbon::parse($row->created_at)->diffForHumans();
                $created_at = "<span class =\"d-none d-sm-table-cell font-size-sm font-w600 text-muted \">".$created_at."</span>";
                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $employeeId);
                array_push($temp, $employeeName);
                array_push($temp, $asset_type);
                array_push($temp, $name);
                array_push($temp, $specification);
                array_push($temp, $status);
                array_push($temp, $remarks);
                array_push($temp, $created_at);
                array_push($data, $temp);
        }

            return json_encode(['data' => $data]);
        } else {
            return '{
                "data":[],
                "sEcho": 1,
                "iTotalRecords": "0",
                "iTotalDisplayRecords": "0",
                "aaData": []
            }';
        }
    }

    public function fetchOnLeaveData($limit)
    {
        $this->dashboardRepository->setLimit($limit);
        $result = $this->dashboardRepository->getOnLeaveTableData();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $employeeId = $row->employee_id;
                $employeeName = $row->full_name;
                $designation = $row->designation_name;
                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $employeeId);
                array_push($temp, $employeeName);
                array_push($temp, $designation);
                array_push($data, $temp);
            }
            return json_encode(array('data'=>$data));
        } else {
            return '{
                "data":[],
                "sEcho": 1,
                "iTotalRecords": "0",
                "iTotalDisplayRecords": "0",
                "aaData": []
            }';
        }
    }
    public function fetchPendingLeaveData($limit)
    {
        $this->dashboardRepository->setLimit($limit);
        $result = $this->dashboardRepository->getPendingLeaveTableData();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $employeeId = $row->employee_id;
                $employeeName = $row->full_name;
                $leave_type = $row->leave_type;
                $start_date = $row->start_date;
                $end_dtae = $row->end_date;
                $created_at = Carbon::parse($row->created_at)->diffForHumans();
                $created_at = "<span class =\"d-none d-sm-table-cell font-size-sm font-w600 text-muted \">".$created_at."</span>";
                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $employeeId);
                array_push($temp, $employeeName);
                array_push($temp, $leave_type);
                array_push($temp, $start_date);
                array_push($temp, $end_dtae);
                array_push($temp, $created_at);
                array_push($data, $temp);
            }
            return json_encode(array('data'=>$data));
        } else {
            return '{
                "data":[],
                "sEcho": 1,
                "iTotalRecords": "0",
                "iTotalDisplayRecords": "0",
                "aaData": []
            }';
        }
    }

}
