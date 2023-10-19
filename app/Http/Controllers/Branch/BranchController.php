<?php

namespace App\Http\Controllers\Branch;

use Validator;
use Response;
use App\Models\Branch;
use App\Http\Controllers\Controller;
use App\Http\Requests\BranchAddRequest;
use App\Http\Requests\BranchUpdateRequest;
use App\Services\BranchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    private $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
        View::share('main_menu', 'System Settings');
        View::share('sub_menu', 'Branches');
    }

    public function index()
    {
        $branches = DB::table('branches')->get();
        return \view('backend.pages.branch.index', compact('branches'));
    }

    public function create()
    {
        return \view('backend.pages.branch.create');
    }

    public function edit($branch)
    {
        $data = Branch::find($branch);
        return \view('backend.pages.branch.edit', compact('data'));
    }

    public function store(BranchAddRequest $request)
    {
        try{
            $branch = Branch::create([
                'name' => $request->name,
                'address' => $request->address,
                'status' => auth()->user()->status,
            ]);
        }catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect('branch');
    }

    public function update(BranchUpdateRequest $request, $branch)
    {
        try{
            $data = Branch::find($branch);
            $data->update($request->validated());
        }catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        
        return redirect('/branch');
    }

    public function destroy($branch)
    {
        try{
            $data = Branch::find($branch);
            $data->delete();
        }catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect()->back();
    }

    public function restore($branch)
    {
        try{
            DB::table('branches')->where('id', $branch)->limit(1)->update(array('deleted_at' => NULL));
            
        }catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect()->back();
    }

    public function verifydata(Request $request)
    {
        return $this->branchService->validateInputs($request->all());
    }
}
