<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

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
}
