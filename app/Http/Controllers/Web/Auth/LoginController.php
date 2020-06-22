<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\WebBaseController;
use App\Models\User;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
class LoginController extends WebBaseController
{
    use Authenticatable;
    public function index(){
        return view('auth.login');
    }
    public  function create(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');

        $user = User::query()
            ->where('role', '<>', 'Employee')
            ->where('username', $credentials['username'])
            ->exists();
        if (!$user){
            session()->flash("error", "Wrong Username Or Password");
            return redirect()->back();
        }

        if (Auth::attempt($credentials)) {
            session()->flash("success", "Login Successfully");
            return redirect()->route('receipts');
        }
        session()->flash("error", "Wrong Username Or Password");
        return redirect()->back();
    }

    public function logout(){
        if (Auth::check())
        {
            Auth::logout();
            session()->flash("success", "Logout Successfully");
        }
        return redirect('login');
    }
}

