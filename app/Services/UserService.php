<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Config;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getTableData()
    {
        $result = $this->userRepository->getTableData();
        if ($result->count() > 0) {
            $data = array();
            // foreach ($result as $key=>$row) {
            //     $id = $row->id;
            //     $name = $row->name;
            //     $total_leaves = $row->total_leaves;
                
            //     $currentYear = date("Y"); 
                
            //     if($year >= $currentYear) {
            //         if ($total_leaves > 0) {
            //             $action_btn = "<td> <button type=\"button\"class=\"d-none d-sm-table-cell font-size-sm border-0\"id=\"btn_update\"name=\"btn_update\"data-toggle=\"modal\" data-target=\"#modal-block-slideup\" onclick=\"openmodal($id)\"> 
            //             <i class=\"fas fa-edit text-success mr-1\"></i>Update</td>";
                        
            //         } else {
            //             $action_btn = "<td> <button type=\"button\"class=\"d-none d-sm-table-cell font-size-sm border-0\"id=\"btn_add\"name=\"btn_add\"data-toggle=\"modal\"data-target=\"#modal-block-slideup\" onclick=\"openmodal($id)\"> 
            //             <i class=\"fas fa-plus text-success mr-1\"></i>Add</td>";
            //         }
            //     } else {
            //         if ($total_leaves > 0) {
            //             $action_btn = "<td> <button type=\"button\"class=\"d-none d-sm-table-cell font-size-sm border-0\"id=\"btn_update\"name=\"btn_update\"data-toggle=\"modal\" data-target=\"#modal-block-slideup\"disabled=\"true\"> 
            //             <i class=\"fas fa-edit text-success mr-1\"></i>Update</td>";
                        
            //         } else {
            //             $action_btn = "<td> <button type=\"button\"class=\"d-none d-sm-table-cell font-size-sm border-0\"id=\"btn_add\"name=\"btn_add\"data-toggle=\"modal\"data-target=\"#modal-block-slideup\"disabled=\"true\"> 
            //             <i class=\"fas fa-plus text-success mr-1\"></i>Add</td>";
            //         }
            //     }
                
            //     $temp = array();
            //     array_push($temp, $key+1);
            //     array_push($temp, $name);
            //     array_push($temp, $year);
            //     array_push($temp, $total_leaves);
            //     array_push($temp, $action_btn);
            //     array_push($data, $temp);
            // }
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
