<?php

namespace App\Http\Controllers\Designation;

use App\Http\Controllers\Controller;
use App\Http\Requests\DesignationAddRequest;
use App\Http\Requests\DesignationEditRequest;
use App\Services\DesignationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class DesignationController extends Controller
{
    private $designationService;

    public function __construct(DesignationService $designationService)
    {
        $this->designationService = $designationService;
        View::share('main_menu', 'System Settings');
        View::share('sub_menu', 'Designations');
    }
    public function index()
    {
        return \view('backend.pages.designation.index');
    }
    public function create()
    {
        $branches = $this->designationService->getBranches();
        return \view('backend.pages.designation.create', compact('branches'));
    }
    public function validate_inputs(Request $request)
    {
        return $this->designationService->validateInputs($request->all());
    }
    public function store(DesignationAddRequest $request)
    {
        try {
            $designation = $this->designationService->createDesignation($request->validated());
            if(!$designation)
                return redirect()->back()->with('error', "Failed to add Designation.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect('designation/')->with('success', "Designation added successfully.");
    }
    public function changeStatus($id)
    {
        try{
            if($this->designationService->changeStatus($id))
                return redirect('designation/')->with('success', 'Designation status changed successfully!');
            return redirect('designation/')->with('error', 'Designation status not changed!');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Designation status could not be saved.");
        }
    }
    public function delete($id)
    {
        try{
            if($this->designationService->delete($id))
                return redirect('designation/')->with('success', "Designation deleted successfully!");
            return redirect('designation/')->with('error', "Designation not deleted!");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Designation could not be deleted.");
        }
    }
    public function restore($id)
    {
        try{
            if($this->designationService->restore($id))
                return redirect('designation/')->with('success', "Designation restored successfully!");
            return redirect('designation/')->with('error', "Designation could not be restored!");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Designation could not be restored.");
        }
    }
    public function edit($id )
    {
        $designation_info = $this->designationService->getDesignation($id);
        if($designation_info=="Restore first")
            return redirect()->back()->with('error', $designation_info);
        $branches = $this->designationService->getAllBranches($id);
        return \view('backend.pages.designation.edit',compact('designation_info','branches'));
    }
    public function validate_name(Request $request,$id)
    {
        return $this->designationService->validateName($request->all(),$id);
    }
    public function update(DesignationEditRequest $request)
    {
        try {
            $designation = $this->designationService->update($request->validated());
            if(!$designation)
                return redirect()->back()->with('error', "Failed to update Designation.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect('designation/')->with('success', "Designation updated successfully.");
    }
    public function fetchData()
    {
        return $this->designationService->fetchData();
    }
    public function fetchDepartments(Request $request)
    {
        return $this->designationService->fetchDepartments($request->all());
    }
}
