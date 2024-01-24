<?php

namespace App\Http\Controllers\Branch;

use Validator;
use App\Models\Branch;
use App\Http\Controllers\Controller;
use App\Http\Requests\BranchAddRequest;
use App\Http\Requests\BranchUpdateRequest;
use App\Services\BranchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Traits\AuthorizationTrait;

class BranchController extends Controller
{
    use AuthorizationTrait;
    private $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
        View::share('main_menu', 'System Settings');
        View::share('sub_menu', 'Branches');
    }

    public function index()
    {
        $hasBranchManagePermission = $this->setId(auth()->user()->id)->setSlug('manageBranch')->hasPermission();
        $branches = $this->branchService->indexBranch();
        return \view('backend.pages.branch.index', compact('branches', 'hasBranchManagePermission'));
    }

    public function create()
    {
        return \view('backend.pages.branch.create');
    }

    public function store(BranchAddRequest $request)
    {
        try {
            if(!is_object($this->branchService->storeBranch($request)))
                return redirect('branch')->with('error', 'Failed to add branch');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect('branch')->with('success', 'Branch added successfully');
    }

    public function edit($id)
    {
        try {
            $data = $this->branchService->editBranch($id);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return \view('backend.pages.branch.edit', compact('data'));
    }

    public function update(BranchUpdateRequest $request, $id)
    {
        try {
            if(!$this->branchService->updateBranch($request, $id))
                return redirect('branch')->with('error', 'Failed to update branch');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect('/branch')->with('success', 'Branch updated successfully');
    }

    public function status($id)
    {
        try {
            $this->branchService->updateStatus($id);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'You need to restore the branch first');
        }
        return redirect()->back()->with('success', 'Status has been changed');
    }

    public function destroy($id)
    {
        try {
            if(!$this->branchService->destroyBranch($id))
                return redirect('branch')->with('error', 'Failed to delete branch');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect()->back()->with('success', 'Branch deleted successfully');
    }

    public function restore($id)
    {
        try {
            if(!$this->branchService->restoreBranch($id))
                return redirect('branch')->with('error', 'Failed to restore branch');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
        return redirect()->back()->with('success', 'Branch restored successfully');
    }

    public function verifydata(Request $request)
    {
        try {
            return $this->branchService->validateInputs($request);
        } catch(\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function updatedata(Request $request)
    {
        try {
            return $this->branchService->updateInputs($request);
        } catch(\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
