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
        return $this->menuService->fetchData();
    }
    public function create()
    {
        $permissions=$this->menuService->getAllPermissions();
        return \view('backend.pages.menu.create', compact('permissions'));
    }
//    public function store(MenuAddRequest $request)
//    {
////        dd($request->all());
//        try{
//            $response = $this->menuService->createMenu($request->validated());
//            if (is_int($response)) {
//                return redirect('menu/')->with('success', 'Menu saved successfully.');
//            } else {
//                return redirect()->back()->with('error', $response);
//            }
//
//        } catch (\Exception $exception) {
//            return redirect()->back()->with('error', $exception->getMessage());
//
//        }
//
//    }

    public function store(MenuAddRequest $request)
    {
        try{
            $menu = $this->menuService->create($request->validated(),(int)$request->id);

            return redirect('menu/')->with('success', $menu->getData()->message);

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Menu  could not be stored.");

        }

    }

}
