<?php

namespace App\Http\Controllers\Menu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Services\MenuService;
use App\Http\Requests\MenuAddRequest;





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
//        dd('fkjg');
        return $this->menuService->fetchData();
    }
    public function create()
    {
        $permissions=$this->menuService->getAllPermissions();
        return \view('backend.pages.menu.create', compact('permissions'));
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
    public function changeStatus(Request $request)
    {
        try{
            $id = $request->menu_id;
            $menu = $this->menuService->changeStatus($id);
            return redirect('menu/')->with('success', 'Menu status changed successfully!');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Menu status could not be saved.");

        }

    }

}
