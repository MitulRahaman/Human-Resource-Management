<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchAddRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class BranchController extends Controller
{
    public function __construct()
    {
        View::share('main_menu', 'System Settings');
        View::share('sub_menu', 'Branches');
    }

    public function index()
    {
        return \view('backend.pages.branch.index');
    }

    public function create()
    {
        return \view('backend.pages.branch.create');
    }

    public function store(BranchAddRequest $request)
    {
        dd($request->validated());
    }
}
