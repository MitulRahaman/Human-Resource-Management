<?php

namespace App\Services;

use App\Repositories\MenuRepository;
use Illuminate\Support\Facades\Config;
use App\Traits\AuthorizationTrait;

class MenuService
{
    use AuthorizationTrait;
    private $menuRepository;
    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }
    public function getAllPermissions($id)
    {
        return $this->menuRepository->getAllPermissions($id);
    }
    public function getPermissions()
    {
        return $this->menuRepository->getPermissions();
    }
    public function getParentMenu()
    {
        return $this->menuRepository->getParentMenu();
    }
    public function getMenuTitle($id)
    {
        return $this->menuRepository->getMenuTitle($id);
    }
    public function create($data)
    {
        return $this->menuRepository->setTitle($data['title'])
            ->setUrl($data['url'])
            ->setIcon($data['icon'])
            ->setDescription($data['description'])
            ->setMenu_order($data['menu_order'])
            ->setParent_menu($data['parent_menu'])
            ->setPermission_ids(isset($data['permissions']) ? $data['permissions']:null)
            ->setStatus(Config::get('variable_constants.activation.active'))
            ->setCreatedAt(date('Y-m-d H:i:s'))
            ->create();
    }
    public function changeStatus($data)
    {
        return $this->menuRepository->change($data);
    }
    public function delete($data)
    {
        return $this->menuRepository->delete($data);
    }
    public function restore($id)
    {
        return $this->menuRepository->restore($id);
    }
    public function getMenu($id)
    {
        return $this->menuRepository->getMenu($id);
    }
    public function update($data)
    {
        return $this->menuRepository->setId($data['id'])
            ->setTitle($data['title'])
            ->setUrl($data['url'])
            ->setIcon($data['icon'])
            ->setDescription($data['description'])
            ->setMenu_order($data['menu_order'])
            ->setParent_menu($data['parent_menu'])
            ->setPermission_ids(isset($data['permissions']) ? $data['permissions']:null)
            ->setUpdatedAt(date('Y-m-d H:i:s'))
            ->update();
    }

    public function fetchData()
    {
        $result = $this->menuRepository->getAllMenuData();
        $hasManageMenuPermission = $this->setSlug('manageMenu')->hasPermission();
        if ($result->count() > 0) {
            $data = array();
            foreach ($result as $key=>$row) {
                $id = $row->id;
                $title = $row->title;
                $url = $row->url? $row->url:'N/A';
                $icon = $row->icon? $row->icon:'N/A';
                $description = $row->description? $row->description:'N/A';
                $menu_order = $row->menu_order? $row->menu_order:'N/A';
                $parent_menu = $this->menuRepository->getMenuTitle($row->parent_menu);
                $created_at = $row->created_at;
                $permissions = '';
                if($row->permissions)
                {
                    foreach ($row->permissions as $p) {
                        $permissions.="<span class=\"badge badge-primary\">$p</span><br>";
                    }
                }
                else
                {
                    $permissions = 'N/A';
                }
                if ($row->status == Config::get('variable_constants.activation.active')) {
                    $status = "<span class=\"badge badge-success\">Active</span>";
                    $status_msg = "Deactivate";
                }else{
                    $status = "<span class=\"badge badge-danger\" >Inactive</span>";
                    $status_msg = "Activate";
                }
                $edit_url = route('edit_menu', ['menu'=>$id]);
                $edit_btn = "<a class=\"dropdown-item\" href=\"$edit_url\">Edit</a>";
                $toggle_btn = "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_status_modal(\"$id\", \"$status_msg\")'> $status_msg </a>";
                if ($row->deleted_at) {
                    $toggle_delete_btn = "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_restore_modal(\"$id\", \"$title\")'>Restore</a>";
                } else {
                    $toggle_delete_btn = "<a class=\"dropdown-item\" href=\"javascript:void(0)\" onclick='show_delete_modal(\"$id\", \"$title\")'>Delete</a>";
                }
                $action_btn = "<div class=\"col-sm-6 col-xl-4\">
                                    <div class=\"dropdown\">
                                        <button type=\"button\" class=\"btn btn-success dropdown-toggle\" id=\"dropdown-default-success\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            Action
                                        </button>
                                        <div class=\"dropdown-menu font-size-sm\" aria-labelledby=\"dropdown-default-success\">";
                $action_btn .= "$edit_btn
                $toggle_btn
                $toggle_delete_btn
                ";
                $action_btn .= "</div>
                                    </div>
                                </div>";
                $temp = array();
                array_push($temp, $key+1);
                array_push($temp, $title);
                array_push($temp, $url);
                array_push($temp, $icon);
                array_push($temp, $description);
                array_push($temp, $menu_order);
                array_push($temp, $parent_menu);
                array_push($temp, $status);
                array_push($temp, $permissions);
                if ($row->deleted_at) {
                    array_push($temp, ' <span class="badge badge-danger" >Yes</span>');
                } else {
                    array_push($temp, ' <span class="badge badge-success">No</span>');
                }
                array_push($temp, $created_at);
                if($hasManageMenuPermission)
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
