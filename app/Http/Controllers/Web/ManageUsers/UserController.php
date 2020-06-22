<?php


namespace App\Http\Controllers\Web\ManageUsers;

use App\Http\Controllers\WebBaseController;
use App\Http\Requests\ChangeInfoRequest;
use App\Http\Requests\NewUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\MailNotify;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends WebBaseController
{
    /*-----------users-------------*/
    public function index(){
        return view('workspace.manageUsers.users');
    }

    public function show(){
        $users = User::withTrashed()->orderBy('created_at','desc')->paginate(10);
        return response()->json([
            'users' => $users
        ],200);
    }

    public function destroy(Request $request){
        $user = User::find($request->user_id);
        if ($user){
            if ($user->delete()){
                return response()->json([
                    'status' => 'success'
                ],200);
            }
        }
        else {
            $user = User::withTrashed()->find($request->user_id);
            if ($user){
                if ($user->restore()){
                    return response()->json([
                        'status' => 'success'
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
                'status' => 'success'
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
                    'user' => User::find($user->id)
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
        if ($user) {
            if ($request->name && $request->name != ''){
                $user->name = $request->name;
            }
            if ($request->email && $request->email != ''){
                $request->validate([
                    'email' => 'required|email'
                ]);
                $user->email = $request->email;
            }
            if ($request->role && $request->role != ''){
                $request->validate([
                    'role' => [Rule::in(['Admin', 'Employee', 'Manager']), 'string']
                ]);
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
                'users' => $users
            ],200);
        }
        return response()->json([
            'status' => 'fails'
        ],404);
    }

    /*--------------------------------Send Change Infomation Link To User -------------------------------*/

    /*Verify the email and create the token*/
    public function sendResetLinkEmail(Request $request, User $user)
    {

        $row = DB::table('password_resets')->where('user_id', $user->id)->first();
        /* True if the email exist in password_resets table*/
        if ($row) {
            if (Carbon::now()->subMinutes(180)->lte($row->created_at)){
                /*Each token is only alive in 180 minutes, If the token is alive -> will back with message "Too much request",
                Unless  -> will delete this token and create new one then send an email to user */
                return response()->json([
                        'message' => 'Too Much Request For This User Please Check Mail or Wait 180 Minutes and Try Again'
                ],400);
            }
            else {
                DB::table('password_resets')->where('user_id', $user->id)->delete();
            }
        }

        DB::beginTransaction();
        try {
        DB::table('password_resets')->insert(
            [
                'user_id'       => $user->id,
                'email'         => $user->email,
                'token'         => Str::random(20),
                'created_at'    => Carbon::now()
            ]
        );
        $tokenData = DB::table('password_resets')->where('user_id', $user->id)->whereEmail($user->email)->first();

        /*Action for sending email*/

            /* \request()->getHost()  -> get the current host */
            $link = \request()->getHost().'/change_info/'.$tokenData->token.'/'.$user->email;
            $details = [
                "link" => $link,
                "username" => $user->username
            ];
            Mail::to($user->email)->send(new MailNotify($details));
            DB::commit();
            return response()->json([
                'message' => 'Success ! Please Check Mail'
            ],200);
        }
        catch (\Exception $exception){
            DB::rollBack();
            return response()->json([
                'message'               => 'Too Much Request For This User Please Check Mail or Wait 180 Minutes and Try Again',
                'messsage_system'       =>  $exception->getMessage()
            ],400);
        }
    }

    /*Return the form to resset password*/
    public function resetInfoForm($token, $email){
        /*Query the row which equal email AND token*/
        $row = DB::table('password_resets')->where('email', $email)->where('token', $token)->first();

        /*If the row exist, the after condition will true*/
        if ($row) {

            /* Checking the token is alive (a token is alive in 60 minutes) */
            if (Carbon::now()->subMinutes(180)->lte($row->created_at)){
                return view('auth.change_info')->with([
                    'token' => $token,
                    'email' => $email,
                    'user'  => User::find($row->user_id)
                ]);
            }
            else {
                return abort(404);
            }
        }
        return abort(404);
    }

    /*Verify the email and password in request to reset password*/
    public function resetInfo(Request $request, $token,$email){
        $row = DB::table('password_resets')
            ->where('email', $email)
            ->where('token', $token)
            ->first();
        /*If */
        /* True if the email exist in password_resets table*/
        if ($row && $row->email == $email) {
            /* Check the token being alive*/
            if (Carbon::now()->subMinutes(180)->lte($row->created_at)) {
                $user= User::find($row->user_id);
               if ($user){
                   if ($request->name && $request->name != ''){
                       $request->validate([
                           'name' => 'required|string'
                       ]);
                       $user->name = $request->name;
                   }

                   if ($request->email && $request->email != ''){
                       $request->validate([
                           'email' => 'required|email'
                       ]);
                       $user->email = $request->email;
                   }

                   if ($request->password  && $request->password != '') {
                       $request->validate([
                           'password' => 'required|min:5',
                           'password_confirmation' => 'required|same:password',
                       ]);
                       $user->password = $request->password;
                   }

                   if ($user->save()){
                       session()->flash('success', 'Saved All');
                       return redirect()->back();
                   }
                   else {
                       session()->flash('error', 'Can Not Save Please Try Again ');
                       return redirect()->back();
                   }
               }
               else {
                   session()->flash('error', 'User Not Found');
                   return redirect()-back();
               }
            } else {
                session()->flash('error', 'URL Is Not Avaiable. Please Contact To Admin');
                return redirect()-back();
            }
        }
        /* Enter wrong email or email don not math with the token */
        session()->flash('error', 'Email Is Not Suitable');
        return redirect()-back();
    }
}
