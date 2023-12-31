<?php

namespace App\Services;

use App\Jobs\TicketAssignJob;
use App\Repositories\TicketRepository;
use Illuminate\Support\Facades\Config;

class TicketService
{
    private $ticketRepository;

    public function __construct(TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    public function getUsers()
    {
        return $this->ticketRepository->getUsers();
    }

    public function create($data)
    {
        $ticket = $this->ticketRepository->setSubject($data['subject'])
            ->setCreatedBy(auth()->user()->id)
            ->setAssignedTo($data['assigned_to'])
            ->setPriority($data['priority'])
            ->setDeadline($data['deadline'])
            ->setDescription($data['description'])
            ->setStatus(Config::get('variable_constants.ticket_status.open'))
            ->setCreatedAt(date('Y-m-d H:i:s'))
            ->create();
        if($ticket)
        {
            $to = $this->ticketRepository->getUserEmail($data['assigned_to']);
            $data =[
                'ticket_info' =>  $data,
                'to' => $to,
                'user_email' => auth()->user()->email,
                'user_name' => auth()->user()->full_name
            ];
            TicketAssignJob::dispatch($data);
            return true;
        }
        return false;
    }

    public function update($data)
    {
        return $this->ticketRepository->setId($data['id'])
            ->setSubject($data['subject'])
            ->setAssignedTo($data['assigned_to'])
            ->setPriority($data['priority'])
            ->setDeadline($data['deadline'])
            ->setDescription($data['description'])
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->update();
    }

    public function getTicket($id)
    {
        return $this->ticketRepository->setId($id)->getTicket();
    }

    public function close($id)
    {
        return $this->ticketRepository->setId($id)->close();
    }

    public function hold($id)
    {
        return $this->ticketRepository->setId($id)->hold();
    }

    public function fetchData()
    {
        $userId= auth()->user()->id;
        $result = $this->ticketRepository->getTableData();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $id = $row->id;
                $subject = $row->subject;
                $created_by_name = $row->created_by_name;
                $assigned_to_name = $row->assigned_to_name;
                $deadline = $row->deadline;
                $description = $row->description;

                $remarks = $row->remarks;
                $status="";
                if($row->status== Config::get('variable_constants.ticket_status.open'))
                    $status = "<span class=\"badge badge-primary\">open</span><br>" ;
                elseif($row->status== Config::get('variable_constants.ticket_status.hold'))
                    $status = "<span class=\"badge badge-warning\">hold</span><br>" ;
                elseif ($row->status== Config::get('variable_constants.ticket_status.completed'))
                    $status = "<span class=\"badge badge-success\">completed</span><br>" ;
                elseif ($row->status== Config::get('variable_constants.ticket_status.closed'))
                    $status = "<span class=\"badge badge-danger\">closed</span><br>" ;

                if($row->priority== Config::get('variable_constants.ticket_priority.low'))
                    $priority = "<span class=\"badge badge-primary\">low</span><br>" ;
                elseif ($row->priority== Config::get('variable_constants.ticket_priority.medium'))
                    $priority = "<span class=\"badge badge-info\">medium</span><br>" ;
                elseif ($row->priority== Config::get('variable_constants.ticket_priority.high'))
                    $priority = "<span class=\"badge badge-warning\">high</span><br>" ;
                elseif ($row->priority== Config::get('variable_constants.ticket_priority.critical'))
                    $priority = "<span class=\"badge badge-danger\">critical</span><br>" ;

                $hold_url = url('ticket/status/'.$id.'/hold');
                $hold_btn ="<a class=\"dropdown-item\" href=\"$hold_url\">Hold</a>";
                $close_url = url('ticket/status/'.$id.'/close');
                $close_btn ="<a class=\"dropdown-item\" href=\"$close_url\">Close</a>";

                $action_btn = "<div class=\"col-sm-6 col-xl-4\">
                                    <div class=\"dropdown\">
                                        <button type=\"button\" class=\"btn btn-secondary dropdown-toggle\" id=\"dropdown-default-secondary\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            Action
                                        </button>
                                        <div class=\"dropdown-menu font-size-sm\" aria-labelledby=\"dropdown-default-secondary\">";

                $complete_btn="<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_complete_modal(\"$id\")'>Complete</a>";
                $edit_url = url('ticket/'.$id.'/edit');
                $edit_btn = "<a class=\"dropdown-item\" href=\"$edit_url\">Edit</a>";

                if($userId==$row->created_by)
                {
                    if($row->status!= Config::get('variable_constants.ticket_status.closed'))
                        $action_btn.="$edit_btn $close_btn ";
                    else
                        $action_btn = "N/A";
                }
                elseif ($userId==$row->assigned_to)
                {
                    if($row->status== Config::get('variable_constants.ticket_status.open'))
                        $action_btn.="$hold_btn ";
                    elseif($row->status== Config::get('variable_constants.ticket_status.hold'))
                        $action_btn.="$complete_btn ";
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
                array_push($temp, $created_by_name);
                array_push($temp, $assigned_to_name);
                array_push($temp, $priority);
                array_push($temp, $deadline);
                array_push($temp, $description);
                array_push($temp, $status);
                array_push($temp, $remarks);
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
