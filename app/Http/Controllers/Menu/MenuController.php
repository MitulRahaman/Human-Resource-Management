<?php

namespace App\Http\Controllers\Menu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Services\MenuService;
use App\Http\Requests\MenuAddRequest;
use App\Http\Requests\MenuEditRequest;

class MenuController extends Controller
{
    private $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
        View::share('main_menu', 'Menus');
        View::share('sub_menu', 'Menus');
    }
    public function index()
    {
        return \view('backend.pages.menu.index');
    }
    public function fetchData()
    {
        return $this->menuService->fetchData();
    }
    public function create()
    {
        $permissions=$this->menuService->getAllPermissions();
        $menus=$this->menuService->getParentMenu();
        return \view('backend.pages.menu.create', compact('permissions', 'menus', 'parent_menus'));
    }
    public function store(MenuAddRequest $request)
    {
        try{
            $menu = $this->menuService->create($request->validated(),(int)$request->id);
            return redirect('menu/')->with('success', $menu->getData()->message);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Menu  could not be stored.");
        }
    }
    public function changeStatus($id)
    {
        try{
            $id = (int)$id;
            $this->menuService->changeStatus($id);
            return redirect('menu/')->with('success', 'Menu status changed successfully!');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Menu status could not be saved.");
        }
    }
    public function delete($id)
    {
        try{
            $id = (int)$id;
            $this->menuService->delete($id);
            return redirect('menu/')->with('success', "Menu deleted successfully!");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Menu could not be deleted.");
        }
    }
    public function restore($id)
    {
        try{
            $id = (int)$id;
            $this->menuService->restore($id);
            return redirect('menu/')->with('success', "Menu restored successfully!");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Menu could not be restored.");
        }
    }
    public function edit($id )
    {
        $menu_info = $this->menuService->getMenu($id);
        $permissions = $this->menuService->getAllPermissions();
        $permission_id = $this->menuService->getPermission($id);
        $menus=$this->menuService->getParentMenu();
//        dd($menu_info->parent_menu);
        return \view('backend.pages.menu.edit',compact('menu_info','permissions','permission_id','menus'));
    }
    public function update(MenuEditRequest $request)
    {
        try{
            $menu = $this->menuService->update($request->validated(),(int)$request->id);
            return redirect('menu/')->with('success', $menu->getData()->message);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Menu could not be updated.");
        }
    }
}
