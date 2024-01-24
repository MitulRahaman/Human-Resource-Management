<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function index()
    {
        return view('backend.auth.login');
    }

    public function authenticate(LoginRequest $request)
    {
        try {
            $response = $this->authService->authenticate($request->validated());
            if ($response === true) {
                return redirect(route('dashboard'));
            } else {
                return redirect()->back()->with('error', $response);
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function logout()
    {
        $this->authService->logout();
        return redirect(route('viewLogin'));
    }

    public function viewChangePassword()
    {
        return view('backend.auth.changePassword');
    }
    public function validatePasswords(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password|min:5'
        ]);
        if($validator)
        {
            return $this->authService->validatePasswords($request->all());
        }
        return redirect()->back()->with('error', 'All fields are required.');
    }
    public function changePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required|min:5',
                'confirm_password' => 'required|same:new_password|min:5'
            ]);
            if($validator)
            {

                $user = $this->authService->changePassword($request->all());
                if($user)
                    return redirect('user/profile')->with('success', 'New password saved successfully.');
                return redirect()->back()->with('error', 'Sorry! password not updated!');
            }
            return redirect()->back()->with('error', 'All fields are required.');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
