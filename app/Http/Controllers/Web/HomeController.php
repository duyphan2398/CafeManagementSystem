<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller{
    public function index(){
        return redirect()->route('receipts');
    }
    public function info(){
        return response()->json([
            'auth_user' => Auth::user()
        ],200);
    }
}
