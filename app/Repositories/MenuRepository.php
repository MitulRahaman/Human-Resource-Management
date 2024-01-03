<?php

namespace App\Repositories;

use App\Models\Menu;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;

class MenuRepository
{
    private $id, $title, $url, $icon, $description, $menu_order, $parent_menu, $status, $created_at, $updated_at, $deleted_at, $permission_ids;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function setPermission_ids($permission_ids)
    {
        $this->permission_ids = $permission_ids;
        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }
    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    public function setMenu_order($menu_order)
    {
        $this->menu_order = $menu_order;
        return $this;
    }
    public function setParent_menu($parent_menu)
    {
        $this->parent_menu = $parent_menu;
        return $this;
    }
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function setDeletedAt($deleted_at)
    {
        $this->deleted_at = $deleted_at;
        return $this;
    }

    public function create()
    {
        DB::beginTransaction();
        try {
            $menus = DB::table('menus')
                ->insertGetId([
                    'title' => $this->title,
                    'url' => $this->url,
                    'icon' => $this->icon,
                    'description' => $this->description,
                    'menu_order' => $this->menu_order,
                    'parent_menu' => $this->parent_menu,
                    'status' => $this->status,
                    'created_at' => $this->created_at
                ]);
            if($menus)
            {
                if($this->permission_ids)
                {
                    foreach ($this->permission_ids as $p)
                    {
                        DB::table('menu_permissions')->insert([
                            'menu_id'=> $menus,
                            'permission_id'=>(int)$p,
                        ]);
                    }
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
    public function getAllMenuData()
    {
        $menus=DB::table('menus as m')
            ->select('m.id', 'm.title', 'm.url', 'm.icon', 'm.description', 'm.menu_order', 'm.parent_menu', 'm.status', DB::raw('date_format(m.created_at, "%d/%m/%Y") as created_at'), DB::raw('date_format(m.deleted_at, "%d/%m/%Y") as deleted_at'))
            ->leftJoin('menu_permissions as mp', 'm.id', '=', 'mp.menu_id')
            ->leftJoin('permissions as p', 'mp.permission_id', '=', 'p.id')
            ->selectRaw('GROUP_CONCAT(p.name) as permissions')
            ->groupBy('m.id', 'm.title', 'm.url', 'm.icon','m.description', 'm.menu_order', 'm.parent_menu', 'm.status', 'm.created_at', 'm.deleted_at')
            ->orderBy('m.id', 'desc')
            ->get();
        foreach ($menus as $menu) {
            $menu->permissions = explode(',', $menu->permissions);
        }
        return $menus;
    }
    public function getAllPermissions($id)
    {
        $id =(int) $id;
        return DB::table('permissions')
            ->where('permissions.status','=', Config::get('variable_constants.activation.active'))
            ->whereNull('permissions.deleted_at')
            ->select('permissions.*', DB::raw('IF(menu_permissions.menu_id = ' . $id . ', "yes", "no") as selected'))
            ->leftJoin('menu_permissions', function ($join) use ($id) {
                $join->on('permissions.id', '=', 'menu_permissions.permission_id')
                    ->where('menu_permissions.menu_id', '=', $id);
            })
            ->get();
    }
    public function getPermissions()
    {
        return Permission::where('status',1)->get();
    }
    public function getParentMenu()
    {
        return Menu::where('parent_menu',null)->where('status',1)->get();
    }
    public function getMenuTitle($id)
    {
        return Menu::where('id', $id)->pluck('title');
    }
    public function change( $data)
    {
        $menu = Menu::findOrFail($data);
        $old=$menu->status;
        $status= config('variable_constants.activation');
        if($old==$status['active'])
        {
            $menu->status=$status['inactive'];
            return $menu->save();
        }
        else
        {
            $menu->status=$status['active'];
            return $menu->save();
        }
    }
    public function delete( $id)
    {
        $menu= Menu::findOrFail($id);
        return $menu->delete();
    }
    public function restore($id)
    {
        return Menu::withTrashed()->where('id', $id)->restore();
    }
    public function getMenu($id)
    {
        $menus = Menu::onlyTrashed()->find($id);
        if($menus)
            return "Restore first";
        $menus = DB::table('menus as m')
            ->where('m.id','=', $id)
            ->select('m.id', 'm.title', 'm.url', 'm.icon', 'm.description', 'm.menu_order', 'm.parent_menu', 'm.status', DB::raw('date_format(m.created_at, "%d/%m/%Y") as created_at'), DB::raw('date_format(m.deleted_at, "%d/%m/%Y") as deleted_at'))
            ->selectRaw('GROUP_CONCAT(p.id) as permissions')
            ->leftJoin('menu_permissions as mp', 'm.id', '=', 'mp.menu_id')
            ->leftJoin('permissions as p', 'mp.permission_id', '=', 'p.id')
            ->groupBy('m.id', 'm.title', 'm.url', 'm.icon','m.description', 'm.menu_order', 'm.parent_menu', 'm.status', 'm.created_at', 'm.deleted_at')
            ->first();
        $menus->permissions =($menus->permissions)? explode(',', $menus->permissions):[];
        return $menus;
    }
    public function update()
    {
        DB::beginTransaction();
        try {
            $menus = DB::table('menus')
                ->where('id', '=', $this->id)
                ->update([
                    'title' => $this->title,
                    'url' => $this->url,
                    'icon' => $this->icon,
                    'description' => $this->description,
                    'menu_order' => $this->menu_order,
                    'parent_menu' => $this->parent_menu,
                    'updated_at' => $this->updated_at
                ]);
            if( $menus)
            {
                DB::table('menu_permissions')->where('menu_id',$this->id)->delete();
                if($this->permission_ids)
                {
                    foreach ($this->permission_ids as $p)
                    {
                        DB::table('menu_permissions')->insert([
                            'menu_id'=> $this->id,
                            'permission_id'=>(int)$p,
                        ]);
                    }
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }
}
