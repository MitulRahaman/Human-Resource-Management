<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    private $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function authenticate($data) : mixed
    {
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'status' => Config::get('variable_constants.activation.active'), 'deleted_at' => null])) {
            $user = User::find(Auth::id());
            $user->last_login = date('Y-m-d H:i:s');
            $user->save();

            $basicInfo = $this->authRepository->setUserId(Auth::id())->getBasicInfo();
            if($basicInfo)
            {
                $permissions = $this->authRepository->setRoleId($basicInfo->role_id)->getRolePermissions();
                $menus = $this->authRepository->setPermissions($permissions)->getMenus();
            }
            elseif ($user->is_super_user)
            {
                $permissions = $this->authRepository->getAllPermission();
                $menus = $this->authRepository->setPermissions($permissions)->getMenus();
            }

            $user_data = [
                'employee_id' => Auth::user()->employee_id,
                'full_name' => Auth::user()->full_name,
                'nick_name' => Auth::user()->nick_name,
                'is_super_user' => $user->is_super_user,
                'basic_info' => $basicInfo,
                'user_permissions' => $permissions,
                'user_menus' => $menus,
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
    public function validatePasswords($data)
    {
        $current_password_msg = null ;
        $confirm_password_msg = null ;
        $password = $this->authRepository->getUserPassword();
        if(!Hash::check($data['current_password'],$password))
            $current_password_msg = 'Current password not matched';
        if($data['new_password']!=$data['confirm_password'])
            $confirm_password_msg = 'Confirm password not matched with new password';
        if($current_password_msg || $confirm_password_msg){
            return [
                'success' => false,
                'current_password_msg' => $current_password_msg,
                'confirm_password_msg' => $confirm_password_msg
            ];
        }
       else {
            return [
                'success' => true,
                'current_password_msg' => $current_password_msg,
                'confirm_password_msg' => $confirm_password_msg
            ];
        }
    }
    public function changePassword($data)
    {
        $password = $this->authRepository->getUserPassword();
        if(!Hash::check($data['current_password'],$password) || $data['new_password']!=$data['confirm_password'])
            return false;
        return $this->authRepository->setPassword($data['new_password'])->changePassword();
    }
}
