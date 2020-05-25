<?php


namespace App\Http\Controllers\Web\ManageUsers;

use App\Http\Controllers\WebBaseController;
use App\Http\Requests\NewUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends WebBaseController
{
    /*-----------users-------------*/
    public function index(){
        return view('workspace.manageUsers.users');
    }

    public function show(){
        $users = User::withTrashed()->orderBy('created_at','desc')->paginate(10);
        return response()->json([
            'users' => $users,
            'auth_id' => Auth::id()
        ],200);
    }

    public function destroy(Request $request){
        $user = User::find($request->user_id);
        if ($user){
            if ($user->delete()){
                return response()->json([
                    'status' => 'success',
                ],200);
            }
        }
        else {
            $user = User::withTrashed()->find($request->user_id);
            if ($user){
                if ($user->restore()){
                    return response()->json([
                        'status' => 'success',
                    ],200);
                }
            }
        }
        return response()->json([
            'status' => 'fail',
        ],404);
    }

    public function forceDelete(Request $request){
        $user = User::withTrashed()->find($request->user_id);
        $user->schedules()->delete();
        if ($user->forceDelete()){
            return response()->json([
                'status' => 'success',
            ],200);
        }
    }

    public function getUser(Request $request){
        $user = User::withTrashed()->find($request->user_id_modal);
        if ($user) {
            return response()->json([
                'status' => 'success',
                'user' => $user
            ],200);
        }
        return response()->json([
            'status' => 'fails',
        ],404);
    }

    public function create(NewUserRequest $request){
        if ($request->validated()) {
            $user = User::query()->create($request->validated());
            if ($user){
                return response()->json([
                    'status' => 'success',
                    'user' => $user
                ],201);
            }
            return response()->json([
                'status' => 'fail',
            ],500);

        }
        else{
            return response()->json([
                "status" =>"fails",
                "validated" =>$request->validated()
            ],422);
        }
    }

    public function edit(Request $request) {
        $user = User::withTrashed()->find($request->user_id_modal);
        if ($request->name){
            $user->name = $request->name;
        }
        if ($request->role){
            $user->role = $request->role;
        }

        if ($request->password) {
            $validatedData = Validator::make([$request->password, $request->passwordConfirm],[
                'password' => 'min:5',
                'passwordConfirm' => 'same:password',
            ]);
            if ($validatedData->fails()){
                $errors = $validatedData->errors();
                return response()->json([
                    'status' => 'fail',
                    'errors' => $errors
                ],400);
            }
            $user->password = $request->password;
        }

        if ($user->save()){
            return response()->json([
                'status' => 'success',
                'user' => $user
            ],200);
        }
        return response()->json([
            'status' => 'fail',
        ],500);

    }

    public function search(Request $request){
        $users = User::query()->withTrashed()->whereLike(['name','username'],$request->search)->get();
        if (count($users) > 0){
            return response()->json([
                'status' => 'success',
                'users' => $users,
                'auth_id' => Auth::id()
            ],200);
        }
        return response()->json([
            'status' => 'fails'
        ],404);
    }
}
