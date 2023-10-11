<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class AuthService
{
    private $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function authenticate($data) : mixed
    {
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'status' => Config::get('variable_constants.activation.active'), 'is_registration_complete' => Config::get('variable_constants.activation.active'), 'deleted_at' => null])) {
            $user = User::find(Auth::id());
            $user->last_login = date('Y-m-d H:i:s');
            $user->save();

            $user_data = [
                'employee_id' => Auth::user()->employee_id,
                'full_name' => Auth::user()->full_name,
                'nick_name' => Auth::user()->nick_name,
                'is_super_user' => $user->is_super_user
            ];

            session(['user_data' => $user_data]);
            return true;
        } else {
            return 'Bad Credentials';
        }
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->flush();
        return true;
    }
}
