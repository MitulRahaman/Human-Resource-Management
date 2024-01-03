<?php

namespace App\Http\Controllers\Designation;

use App\Http\Controllers\Controller;
use App\Http\Requests\DesignationAddRequest;
use App\Http\Requests\DesignationEditRequest;
use App\Services\DesignationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Traits\AuthorizationTrait;

class DesignationController extends Controller
{
    use AuthorizationTrait;

    private $designationService;

    public function __construct(DesignationService $designationService)
    {
        $this->designationService = $designationService;
        View::share('main_menu', 'System Settings');
        View::share('sub_menu', 'Designations');
    }

    public function index()
    {
        $hasDesignationManagePermission = $this->setId(auth()->user()->id)->setSlug('manageDesignation')->hasPermission();
        $addDesignationPermission = $this->setSlug('addDesignation')->hasPermission();
        return \view('backend.pages.designation.index', compact('hasDesignationManagePermission','addDesignationPermission'));
    }

    public function create()
    {
        abort_if(!$this->setSlug('addDesignation')->hasPermission(), 403, 'You don\'t have permission!');
        $branches = $this->designationService->getBranches();
        return \view('backend.pages.designation.create', compact('branches'));
    }

    public function validate_inputs(Request $request)
    {
        return $this->designationService->validateInputs($request->all());
    }

    public function store(DesignationAddRequest $request)
    {
        abort_if(!$this->setSlug('addDesignation')->hasPermission(), 403, 'You don\'t have permission!');
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
        abort_if(!$this->setSlug('manageDesignation')->hasPermission(), 403, 'You don\'t have permission!');
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
        abort_if(!$this->setSlug('manageDesignation')->hasPermission(), 403, 'You don\'t have permission!');
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
        abort_if(!$this->setSlug('manageDesignation')->hasPermission(), 403, 'You don\'t have permission!');
        try{
            if($this->designationService->restore($id))
                return redirect('designation/')->with('success', "Designation restored successfully!");
            return redirect('designation/')->with('error', "Designation could not be restored!");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Designation could not be restored.");
        }
    }

    public function edit($id)
    {
        abort_if(!$this->setSlug('editDesignation')->hasPermission(), 403, 'You don\'t have permission!');
        $designation_info = $this->designationService->getDesignation($id);
        abort_if(!$designation_info, 404);
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
        abort_if(!$this->setSlug('editDesignation')->hasPermission(), 403, 'You don\'t have permission!');
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
