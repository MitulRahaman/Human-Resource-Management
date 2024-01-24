<?php

namespace App\Http\Controllers\Warning;

use App\Http\Requests\WarningAddRequest;
use App\Http\Requests\WarningEditRequest;
use App\Services\WarningService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class WarningController extends Controller
{
    private $warningService;

    public function __construct(WarningService $warningService)
    {
        $this->warningService = $warningService;
        View::share('main_menu', 'Warnings');
    }

    public function index()
    {
        View::share('sub_menu', 'Manage Warnings');
        return view('backend.pages.warning.index');
    }

    public function fetchData()
    {
        return $this->warningService->fetchData();
    }

    public function create()
    {
        View::share('sub_menu', 'Add Warning');
        $users = $this->warningService->getUsers();
        return \view('backend.pages.warning.create', compact('users'));
    }

    public function store(WarningAddRequest $request)
    {
        try {
            $warning = $this->warningService->create($request->validated());
            if(!$warning)
                return redirect('warning')->with('error', 'Failed to add warning');
            return redirect('/warning')->with('success', 'Warning added successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function acknowledged($id)
    {
        try {
            $this->warningService->acknowledged($id);
            return redirect()->back()->with('success', 'Warning acknowledged');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function edit($id)
    {
        View::share('sub_menu', 'Manage Warnings');
        $warning = $this->warningService->getWarning($id);
        if($warning && !is_null($warning->deleted_at))
            return redirect()->back()->with('error', 'Invalid warning');
        $users = $this->warningService->getUsers();
        return \view('backend.pages.warning.edit',compact('warning','users'));
    }

    public function update(WarningEditRequest $request)
    {
        try {
            $warning = $this->warningService->update($request->validated());
            if(!$warning)
                return redirect('warning')->with('error', 'Failed to update warning');
            return redirect('/warning')->with('success', 'Warning updated successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
