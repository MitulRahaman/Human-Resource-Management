<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankAddRequest;
use App\Http\Requests\BankEditRequest;
use App\Services\BankService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Traits\AuthorizationTrait;

class BankController extends Controller
{
    use AuthorizationTrait;
    private $bankService;
    public function __construct(BankService $bankService)
    {
        $this->bankService = $bankService;
        View::share('main_menu', 'System Settings');
        View::share('sub_menu', 'Banks');
    }
    public function index()
    {
        $hasBankManagePermission = $this->setId(auth()->user()->id)->setSlug('manageBank')->hasPermission();
        $addBankPermission = $this->setSlug('addBank')->hasPermission();
        return \view('backend.pages.bank.index', compact('hasBankManagePermission','addBankPermission'));
    }
    public function fetchData()
    {
        return $this->bankService->fetchData();
    }
    public function create()
    {
        abort_if(!$this->setSlug('addBank')->hasPermission(), 403, 'You don\'t have permission!');
        return \view('backend.pages.bank.create');
    }
    public function validate_inputs(Request $request)
    {
        return $this->bankService->validateInputs($request->all());
    }
    public function store(BankAddRequest $request)
    {
        abort_if(!$this->setSlug('addBank')->hasPermission(), 403, 'You don\'t have permission!');
        try{
            $response = $this->bankService->createBank($request->validated());
            if (is_int($response)) {
                return redirect('bank/')->with('success', 'Bank saved successfully.');
            } else {
                return redirect()->back()->with('error', $response);
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function edit($id )
    {
        abort_if(!$this->setSlug('editBank')->hasPermission(), 403, 'You don\'t have permission!');
        $bank_info = $this->bankService->getBank($id);
        if($bank_info=="Restore first")
            return redirect()->back()->with('error', $bank_info);
        return \view('backend.pages.bank.edit',compact('bank_info'));
    }
    public function validate_name(Request $request, int $id)
    {
        return $this->bankService->validateName($request->all(),$id);
    }
    public function update(BankEditRequest $request)
    {
        abort_if(!$this->setSlug('editBank')->hasPermission(), 403, 'You don\'t have permission!');
        try{
            if($this->bankService->edit($request->validated()))
                return redirect('bank/')->with('success', "Bank updated successfully.");
            return redirect('bank/')->with('success', "Bank not updated.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Bank could not be updated.");
        }
    }
    public function delete($id)
    {
        abort_if(!$this->setSlug('manageBank')->hasPermission(), 403, 'You don\'t have permission!');
        try{
            if($this->bankService->delete($id))
                return redirect('bank/')->with('success', "Bank deleted successfully.");
            return redirect('bank/')->with('error', "Bank not deleted.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Bank could not be deleted.");
        }
    }
    public function restore($id)
    {
        abort_if(!$this->setSlug('manageBank')->hasPermission(), 403, 'You don\'t have permission!');
        try{
            if($this->bankService->restore($id))
                return redirect('bank/')->with('success', "Bank restored successfully.");
            return redirect('bank/')->with('success', "Bank not restored.");
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', "OOPS! Bank could not be restored.");
        }
    }
}
