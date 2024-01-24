<?php

namespace App\Services;

use App\Repositories\BankRepository;
use Illuminate\Support\Facades\Config;
use App\Traits\AuthorizationTrait;

class BankService
{
    use AuthorizationTrait;
    private $bankRepository;

    public function __construct(BankRepository $bankRepository)
    {
        $this->bankRepository = $bankRepository;
    }
    public function validateInputs($data)
    {
        $this->bankRepository->setName($data['name']);
        $is_name_exists = $this->bankRepository->isNameExists();
        $name_msg = $is_name_exists ? 'Name already taken' : null;
        if(!$data['name']) $name_msg = 'Name is required';
        if ( $is_name_exists) {
            return [
                'success' => false,
                'name_msg' => $name_msg,
            ];
        } else {
            return [
                'success' => true,
                'name_msg' => $name_msg,
            ];
        }
    }
    public function createBank($data)
    {
        return $this->bankRepository->setName($data['name'])
            ->setAddress($data['address'])
            ->setCreatedAt(date('Y-m-d H:i:s'))
            ->save();
    }
    public function getBank($id)
    {
        return $this->bankRepository->getBank($id);
    }
    public function validateName($data,$id)
    {
        $this->bankRepository->setName($data['name']);
        $is_name_exists = $this->bankRepository->isNameUnique($id);
        $name_msg = $is_name_exists ? 'Name already taken' : null;
        if(!$data['name']) $name_msg = 'Name is required';
        if ( $is_name_exists) {
            return [
                'success' => false,
                'name_msg' => $name_msg,
            ];
        } else {
            return [
                'success' => true,
                'name_msg' => $name_msg,
            ];
        }
    }
    public function edit($data)
    {
        return $this->bankRepository->setId($data['id'])
            ->setName($data['name'])
            ->setAddress($data['address'])
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->update();
    }
    public function delete($data)
    {
        return $this->bankRepository->delete($data);
    }
    public function restore($id)
    {
        return $this->bankRepository->restore($id);
    }
    public function fetchData()
    {
        $result = $this->bankRepository->getAllBankData();
        $hasBankManagePermission = $this->setId(auth()->user()->id)->setSlug('manageBank')->hasPermission();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $id = $row->id;
                $name = $row->name;
                $address = $row->address? $row->address:'N/A';
                $created_at = $row->created_at;
                $edit_url = route('edit_bank', ['bank'=>$id]);
                $edit_btn = "<a class=\"dropdown-item\" href=\"$edit_url\">Edit</a>";
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
                $toggle_delete_btn
                ";
                $action_btn .= "</div>
                                    </div>
                                </div>";
                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $name);
                array_push($temp, $address);
                if ($row->deleted_at) {
                    array_push($temp, ' <span class="badge badge-danger" >Yes</span>');
                } else {
                    array_push($temp, ' <span class="badge badge-success">No</span>');
                }
                array_push($temp, $created_at);
                if($hasBankManagePermission) {
                    array_push($temp, $action_btn);
                }
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
