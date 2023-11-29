<?php

namespace App\Http\Controllers\Institute;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstituteAddRequest;
use App\Http\Requests\InstituteEditRequest;
use App\Services\InstituteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Traits\AuthorizationTrait;

class InstituteController extends Controller
{
    use AuthorizationTrait;
    private $instituteService;
    public function __construct(InstituteService $instituteService)
    {
        $this->instituteService = $instituteService;
        View::share('main_menu', 'System Settings');
        View::share('sub_menu', 'Institutes');
    }
    public function index()
    {
        $hasInstitutionManagePermission = $this->setId(auth()->user()->id)->setSLug('manageInstitution')->checkAuthorization();
        return \view('backend.pages.institute.index', compact('hasInstitutionManagePermission'));
    }
    public function fetchData()
    {
        return $this->instituteService->fetchData();
    }
    public function create()
    {
        return \view('backend.pages.institute.create');
    }
    public function validate_inputs(Request $request)
    {
        return $this->instituteService->validateInputs($request->all());
    }
    public function store(InstituteAddRequest $request)
    {
        try{
            $response = $this->instituteService->createInstitute($request->validated());
            if (is_int($response)) {
                return redirect('institute/')->with('success', 'Institute saved successfully.');
            } else {
                return redirect()->back()->with('error', $response);
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function edit($id )
    {
        $institute_info = $this->instituteService->getInstitute($id);
        if($institute_info=="Restore first")
            return redirect()->back()->with('error', $institute_info);
        return \view('backend.pages.institute.edit',compact('institute_info'));
    }
    public function validate_name(Request $request, $id)
    {
        return $this->instituteService->validateName($request->all(),$id);
    }
    public function update(InstituteEditRequest $request)
    {
        try{
            if($this->instituteService->edit($request->validated()))
                return redirect('institute/')->with('success', "Institute updated successfully.");
            return redirect('institute/')->with('success', "Institute not updated.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function delete($id)
    {
        try{
            if($this->instituteService->delete($id))
                return redirect('institute/')->with('success', "Institute deleted successfully.");
            return redirect('institute/')->with('error', "Institute not deleted.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function restore($id)
    {
        try{
            if($this->instituteService->restore($id))
                return redirect('institute/')->with('success', "Institute restored successfully.");
            return redirect('institute/')->with('success', "Institute not restored.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function changeStatus($id)
    {
        try{
            if($this->instituteService->changeStatus($id))
                return redirect('institute/')->with('success', "Institute status changed successfully.");
            return redirect('institute/')->with('error', "Institute status not changed.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
