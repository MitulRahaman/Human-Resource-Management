<?php

namespace App\Http\Controllers\Degree;

use App\Http\Controllers\Controller;
use App\Http\Requests\DegreeAddRequest;
use App\Http\Requests\DegreeEditRequest;
use App\Services\DegreeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class DegreeController extends Controller
{
    private $degreeService;
    public function __construct(DegreeService $degreeService)
    {
        $this->degreeService = $degreeService;
        View::share('main_menu', 'System Settings');
        View::share('sub_menu', 'Degree');
    }
    public function index()
    {
        return \view('backend.pages.degree.index');
    }
    public function fetchData()
    {
        return $this->degreeService->fetchData();
    }
    public function create()
    {
        return \view('backend.pages.degree.create');
    }
    public function validate_inputs(Request $request)
    {
        return $this->degreeService->validateInputs($request->all());
    }
    public function store(DegreeAddRequest $request)
    {
        try{
            $response = $this->degreeService->createDegree($request->validated());
            if (is_int($response)) {
                return redirect('degree/')->with('success', 'Degree saved successfully.');
            } else {
                return redirect()->back()->with('error', $response);
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function edit($id )
    {
        $degree_info = $this->degreeService->getDegree($id);
        if($degree_info=="Restore first")
            return redirect()->back()->with('error', $degree_info);
        return \view('backend.pages.degree.edit',compact('degree_info'));
    }
    public function validate_name(Request $request, int $id)
    {
        return $this->degreeService->validateName($request->all(),$id);
    }
    public function update(DegreeEditRequest $request)
    {
        try{
            if($this->degreeService->edit($request->validated()))
                return redirect('degree/')->with('success', "Degree updated successfully.");
            return redirect('degree/')->with('success', "Degree not updated.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Degree could not be updated.");
        }
    }
    public function delete($id)
    {
        try{
            if($this->degreeService->delete($id))
                return redirect('degree/')->with('success', "Degree deleted successfully.");
            return redirect('degree/')->with('error', "Degree not deleted.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Degree could not be deleted.");
        }
    }
    public function restore($id)
    {
        try{
            if($this->degreeService->restore($id))
                return redirect('degree/')->with('success', "Degree restored successfully.");
            return redirect('degree/')->with('success', "Degree not restored.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Degree could not be restored.");
        }
    }
}