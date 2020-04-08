<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\WebBaseController;
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
        if (Auth::attempt($credentials)) {
            session()->flash("success", "Login Successfully");
            return redirect()->route('dashboard');
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

