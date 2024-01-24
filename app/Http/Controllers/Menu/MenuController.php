<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Services\MenuService;
use App\Http\Requests\MenuAddRequest;
use App\Http\Requests\MenuEditRequest;
use App\Traits\AuthorizationTrait;

class MenuController extends Controller
{
    use AuthorizationTrait;
    private $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
        View::share('main_menu', 'Menus');
        View::share('sub_menu', 'Menus');
    }
    public function index()
    {
        $addMenuPermission = $this->setSlug('addMenu')->hasPermission();
        return \view('backend.pages.menu.index',compact('addMenuPermission'));
    }
    public function fetchData()
    {
        return $this->menuService->fetchData();
    }
    public function create()
    {
        abort_if(!$this->setSlug('addMenu')->hasPermission(), 403, 'You don\'t have permission!');
        $permissions=$this->menuService->getPermissions();
        $menus=$this->menuService->getParentMenu();
        return \view('backend.pages.menu.create', compact('permissions', 'menus'));
    }
    public function store(MenuAddRequest $request)
    {
        abort_if(!$this->setSlug('addMenu')->hasPermission(), 403, 'You don\'t have permission!');
        try {
            $menu = $this->menuService->create($request->validated());
            if(!$menu)
                return redirect('menu')->with('error', 'Failed to add Menu');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect('/menu')->with('success', 'Menu added successfully');
    }
    public function changeStatus($id)
    {
        abort_if(!$this->setSlug('manageMenu')->hasPermission(), 403, 'You don\'t have permission!');
        try{
            if($this->menuService->changeStatus($id))
                return redirect('menu/')->with('success', 'Menu status changed successfully!');
            return redirect('menu/')->with('error', 'Menu status not changed!');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Menu status could not be saved.");
        }
    }
    public function delete($id)
    {
        abort_if(!$this->setSlug('manageMenu')->hasPermission(), 403, 'You don\'t have permission!');
        try{
            if($this->menuService->delete($id))
                return redirect('menu/')->with('success', "Menu deleted successfully!");
            return redirect('menu/')->with('error', "Menu not deleted!");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Menu could not be deleted.");
        }
    }
    public function restore($id)
    {
        abort_if(!$this->setSlug('manageMenu')->hasPermission(), 403, 'You don\'t have permission!');
        try{
            if($this->menuService->restore($id))
                return redirect('menu/')->with('success', "Menu restored successfully!");
            return redirect('menu/')->with('error', "Menu could not be restored!");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Menu could not be restored.");
        }
    }
    public function edit($id )
    {
        abort_if(!$this->setSlug('editMenu')->hasPermission(), 403, 'You don\'t have permission!');
        $menu_info = $this->menuService->getMenu($id);
        if($menu_info=="Restore first")
            return redirect()->back()->with('error', $menu_info);
        $permissions = $this->menuService->getAllPermissions($id);
        $menus=$this->menuService->getParentMenu();
        $parent_menu_title = $this->menuService->getMenuTitle($menu_info->parent_menu);
        return \view('backend.pages.menu.edit',compact('menu_info','permissions','menus', 'parent_menu_title'));
    }
    public function update(MenuEditRequest $request)
    {
        abort_if(!$this->setSlug('editMenu')->hasPermission(), 403, 'You don\'t have permission!');
        try {
            $menu = $this->menuService->update($request->validated());
            if(!$menu)
                return redirect('menu')->with('error', 'Failed to update Menu');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect('/menu')->with('success', 'Menu updated successfully');
    }
}
