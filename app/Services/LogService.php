<?php

namespace App\Services;

use App\Repositories\LogRepository;
use App\Traits\AuthorizationTrait;

class LogService
{
    use AuthorizationTrait;
    private $logRepository;
    public function __construct(LogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }
    public function fetchData()
    {
        $result = $this->logRepository->getAllLogs();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $id = $row->id;
                $employee_id = $row->employee_id;
                $uri= $row->uri;
                $params = $row->params;
                $method = $row->method;
                $created_at= $row->created_at;
                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $employee_id);
                array_push($temp, $uri);
                array_push($temp, $method);
                array_push($temp, $params);
                array_push($temp, $created_at);
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
