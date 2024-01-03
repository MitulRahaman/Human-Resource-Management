<?php

namespace App\Services;

use App\Jobs\WarningAcknowledgedJob;
use App\Jobs\WarningJob;
use App\Mail\WarningAcknowledgedMail;
use App\Repositories\WarningRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use App\Traits\AuthorizationTrait;

class WarningService
{
    use AuthorizationTrait;
    private $warningRepository;

    public function __construct(WarningRepository $warningRepository)
    {
        $this->warningRepository = $warningRepository;
    }

    public function getUsers()
    {
        return $this->warningRepository->getUsers();
    }

    public function getWarning($id)
    {
        return $this->warningRepository->setId($id)->getWarning();
    }

    public function create($data)
    {
        $warning = $this->warningRepository->setSubject($data['subject'])
            ->setWarningBy($data['warning_by'])
            ->setWarningTo($data['warning_to'])
            ->setDate(Carbon::createFromFormat('d-m-Y', $data['date'])->format('Y-m-d'))
            ->setDescription($data['description'])
            ->setStatus(Config::get('variable_constants.warning_status.pending'))
            ->setCreatedAt(date('Y-m-d H:i:s'))
            ->create();
        if($warning)
        {
            $to = $this->warningRepository->getUserEmail($data['warning_to']);
            $data =[
                'warning_info' =>  $data,
                'to' => $to,
                'user_email' => auth()->user()->email,
                'user_name' => auth()->user()->full_name,
                'subject' => "!!Warning!!",
            ];
            WarningJob::dispatch($data);
            return true;
        }
        return false;
    }

    public function update($data)
    {
        $warning = $this->warningRepository->setId($data['id'])
            ->setSubject($data['subject'])
            ->setWarningBy($data['warning_by'])
            ->setWarningTo($data['warning_to'])
            ->setDate(Carbon::createFromFormat('d-m-Y', $data['date'])->format('Y-m-d'))
            ->setDescription($data['description'])
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->update();
        if($warning)
        {
            $to = $this->warningRepository->getUserEmail($data['warning_to']);
            $data =[
                'warning_info' =>  $data,
                'to' => $to,
                'user_email' => auth()->user()->email,
                'user_name' => auth()->user()->full_name,
                'subject' => "Warning Updated",
            ];
            WarningJob::dispatch($data);
            return true;
        }
        return false;
    }

    public function acknowledged($id)
    {
        $warning_ack = $this->warningRepository->setId($id)->acknowledged();
        if($warning_ack)
        {
            $warning = $this->warningRepository->setId($id)->getWarning();
            $to = $this->warningRepository->getUserEmail($warning->warning_by);
            $data =[
                'warning_info' =>  $warning,
                'to' => $to,
                'user_email' => auth()->user()->email,
                'user_name' => auth()->user()->full_name,
                'subject' => "Warning Acknowledged",
            ];
            WarningAcknowledgedJob::dispatch($data);
            return true;
        }
        return false;
    }

    public function fetchData()
    {
        $manageWarningPermission = $this->setSlug("manageWarning")->hasPermission();
        $userId= auth()->user()->id;
        $this->warningRepository->setPermission($manageWarningPermission);
        if(!$manageWarningPermission)
            $this->warningRepository->setUserId($userId);
        $result = $this->warningRepository->getTableData();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $id = $row->id;
                $subject = $row->subject;
                $warning_to_name = $row->warning_to_name;
                $date = Carbon::createFromFormat('Y-m-d', $row->date)->format('d-m-Y') ;
                $description = $row->description;
                $status="";
                if($row->status== Config::get('variable_constants.warning_status.pending'))
                    $status = "<span class=\"badge badge-warning\">pending</span><br>" ;
                elseif($row->status== Config::get('variable_constants.warning_status.acknowledged'))
                    $status = "<span class=\"badge badge-success\">acknowledged</span><br>" ;

                $acknowledge_url = url('warning/status/'.$id.'/acknowledged');
                $acknowledge_btn ="<a class=\"dropdown-item\" href=\"$acknowledge_url\">acknowledged</a>";

                $action_btn = "<div class=\"col-sm-6 col-xl-4\">
                                    <div class=\"dropdown\">
                                        <button type=\"button\" class=\"btn btn-secondary dropdown-toggle\" id=\"dropdown-default-secondary\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            Action
                                        </button>
                                        <div class=\"dropdown-menu font-size-sm\" aria-labelledby=\"dropdown-default-secondary\">";

                $edit_url = url('warning/'.$id.'/edit');
                $edit_btn = "<a class=\"dropdown-item\" href=\"$edit_url\">Edit</a>";

                if ($userId==$row->warning_to)
                {
                    if($row->status== Config::get('variable_constants.warning_status.pending'))
                        $action_btn.=" $acknowledge_btn ";
                    else
                        $action_btn = "N/A";
                }
                elseif($manageWarningPermission)
                {
                    if($row->status== Config::get('variable_constants.warning_status.pending'))
                        $action_btn.=" $edit_btn ";
                    else
                        $action_btn = "N/A";
                }
                else
                    $action_btn = "N/A";

                $action_btn .= "</div>
                                    </div>
                                </div>";
                $created_at = $row->created_at;
                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $subject);
                array_push($temp, $warning_to_name);
                array_push($temp, $description);
                array_push($temp, $date);
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

}
