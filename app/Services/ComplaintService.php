<?php

namespace App\Services;

use App\Jobs\ComplaintJob;
use App\Jobs\ComplaintAcknowledgedJob;
use App\Repositories\ComplaintRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Traits\AuthorizationTrait;

class ComplaintService
{
    use AuthorizationTrait;
    private $complaintRepository, $fileUploadService;

    public function __construct(ComplaintRepository $complaintRepository, FileUploadService $fileUploadService)
    {
        $this->complaintRepository = $complaintRepository;
        $this->fileUploadService = $fileUploadService;
    }

    public function getAllUsers()
    {
        return $this->complaintRepository->getAllUsers();
    }

    public function storeComplaint($request)
    {
        $fileName = null;
        if($request['photo']) {
            $fileName = $this->fileUploadService->setPath($request['photo']);
            $this->fileUploadService->setPathName(Config::get('variable_constants.file_path.complaint'))->uploadFile($fileName, $request['photo']);
        }
        $receivers = $this->complaintRepository->getComplaintEmailRecipient();
        $name = $this->complaintRepository->setId($request->againstWhom)->getAgainstWhomName();
        $data =[
            'title' => $request->title,
            'byWhom' => $request->byWhom,
            'email' => auth()->user()->email,
            'againstWhom' => $name,
            'description' => $request->description,
            'file' => $fileName,
            'receiver' => $receivers
        ];
        $stored = $this->complaintRepository
                ->setTitle($request->title)
                ->setId($request->againstWhom)
                ->setDescription($request->description)
                ->setFile($fileName)
                ->storeComplaints();

        if($stored) {
            ComplaintJob::dispatch($data);
            return true;
        } else {
            return false;
        }
    }

    public function editComplaint($id)
    {
        return $this->complaintRepository->setId($id)->getComplaintInfo();
    }

    public function updateComplaint($request, $id)
    {
        $fileName = null;
        if($request['photo']) {
            $fileName = $this->fileUploadService->setPath($request['photo']);
            $this->fileUploadService->setPathName(Config::get('variable_constants.file_path.complaint'))->uploadFile($fileName, $request['photo']);
        }

        return $this->complaintRepository
            ->setComplaintId($id)
            ->setTitle($request->title)
            ->setId($request->againstWhom)
            ->setDescription($request->description)
            ->setFile($fileName)
            ->updateComplaints();
    }

    public function acknowledgeComplaint($data, $id)
    {
        $complaintdata = $this->complaintRepository->setComplaintId($id)->getComplaintEmailRecipient();
        if($this->complaintRepository->setComplaintId($id)->setRemarks($data)->acknowledgeComplaint()) {
            $data =[
                'againstWhom' => $complaintdata->name,
                'email' => auth()->user()->email,
                'receiver' => $complaintdata->email
            ];
            ComplaintAcknowledgedJob::dispatch($data);
            return true;
        } else {
            return false;
        }
    }

    public function rejectComplaint($data, $id)
    {
        return $this->complaintRepository
                ->setComplaintId($id)
                ->setRemarks($data)
                ->rejectComplaint();
    }

    public function delete($id)
    {
        return $this->complaintRepository->setComplaintId($id)->delete();
    }

    public function getTableData()
    {
        $result = $this->complaintRepository->getTableData();
        $userId = auth()->user()->id;
        $hasManageComplaintPermission = $this->setId($userId)->setSlug('manageComplaint')->hasPermission();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $id = $row->id;
                $user_id = $row->user_id;
                $title = $row->title;
                $byWhom = $row->byWhom;
                $againstWhom = $row->againstWhom;
                $description = $row->description;
                $complaint_date = date("d-m-Y", strtotime($row->complaint_date));
                $remarks = $row->remarks;
                $status="";
                $file="";
                if($row->status== Config::get('variable_constants.leave_status.pending'))
                    $status = "<span class=\"badge badge-primary\">pending</span><br>" ;
                elseif ($row->status== Config::get('variable_constants.leave_status.approved'))
                    $status = "<span class=\"badge badge-success\">acknowledged</span><br>" ;
                elseif ($row->status== Config::get('variable_constants.leave_status.rejected'))
                    $status = "<span class=\"badge badge-danger\">rejected</span><br>" ;

                $delete_url = url('complaint/'.$id.'/delete');
                $toggle_delete_btn = "<li><a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_delete_modal(\"$id\")'>Delete</a></li>";
                $action_btn = "<div class=\"col-sm-6 col-xl-4\">
                                    <div class=\"dropdown\">
                                        <button type=\"button\" class=\"btn btn-secondary dropdown-toggle\" id=\"dropdown-default-secondary\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            Action
                                        </button>
                                        <div class=\"dropdown-menu font-size-sm\" aria-labelledby=\"dropdown-default-secondary\">
                                        <ul style=\"max-height: 100px; overflow-x:hidden\">";

                $approve_btn="<li><a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_approve_modal(\"$id\")'>Acknowledge</a></li>";
                $reject_btn="<li><a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_reject_modal(\"$id\")'>Reject</a></li>";
                $edit_url = url('complaint/'.$id.'/edit');
                $edit_btn = "<li><a class=\"dropdown-item\" href=\"$edit_url\">Edit</a></li>";
                if($hasManageComplaintPermission && ($userId == $row->user_id))
                {
                    if($row->status== Config::get('variable_constants.leave_status.pending'))
                    {
                        $action_btn .= "$edit_btn $toggle_delete_btn $approve_btn $reject_btn";
                    }
                    else
                    {
                        $action_btn = "N/A";
                    }

                }
                elseif ($hasManageComplaintPermission && ($userId != $row->user_id))
                {
                    if($row->status== Config::get('variable_constants.leave_status.pending'))
                    {
                        $action_btn .= "$approve_btn $reject_btn";
                    }
                    else
                    {
                        $action_btn = "N/A";
                    }
                }
                elseif (!$hasManageComplaintPermission && ($userId == $row->user_id))
                {
                    if($row->status== Config::get('variable_constants.leave_status.pending'))
                    {
                        $action_btn .= "$edit_btn $toggle_delete_btn";
                    }
                    else
                    {
                        $action_btn = "N/A";
                    }
                }

                $action_btn .= "</ul>
                                </div>
                            </div>
                        </div>";

                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $title);
                array_push($temp, $byWhom);
                array_push($temp, $againstWhom);
                array_push($temp, $description);
                array_push($temp, $complaint_date);
                if($remarks == null) {
                    array_push($temp, "N/A");
                } else {
                    array_push($temp, $remarks);
                }
                array_push($temp, $status);
                if($file == null) {
                    array_push($temp, "N/A");
                } else {
                    array_push($temp, $file);
                }
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
