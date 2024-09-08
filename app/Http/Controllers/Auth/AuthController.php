<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Responses\Response;
use App\Models\School;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    // public function __construct(){
    //     $this->middleware('verified');
    // }


    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'school_id' => ['required', 'integer', 'exists:schools,id'],
            'phone_number' => ['required', 'string', 'regex:/^09[0-9]{8}$/']
        ]);

        if ($validator->fails()) {
            return Response::Error($validator->errors(), 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'school_id' => $request->school_id,
                'phone_number' => $request->phone_number
            ]);

            $user['token'] = $user->createToken('personalAccessToken')->plainTextToken;

            $school = School::find($request->school_id);
            if (!$school) {
                return Response::Error('School not found.', 404);
            }
            $user['school_id'] = $school->name;

            // $studentRole = Role::query()->where('name', 'student')->first();

            // if ($studentRole) {
            //     $user->assignRole($studentRole);
            //     $permissions = $studentRole->permissions()->pluck('name')->toArray();
            //     $user->givePermissionTo($permissions);
            // } else {
            //     return Response::Error('Role "student" not found.', 500);
            // }
            // $user->load('roles','permissions');

            // $user= $this->appendRolesAndPermissions($user);
            // event(new Registered($user));
            $message = __('User created successfully');

            return Response::Success($user, $message, 201);


        } catch (Exception $e) {
            return Response::Error('An unexpected error occurred:' . $e->getMessage(), 500);
        }
    }

    public function registerAsPlanner($request)
    {
        $user = User::query()->create([

            'name'=> $request['name'],
            'email'=> $request['email'],
            'password'=> Hash::make($request['password']),
            'blocked'=> 1,
        ]);

        $plannerRole = Role::query()->where('name','student')->first();
        $user->assignRole($plannerRole);


        $user= User::query()->find($user['id']);
        $roles = [];
        foreach ($user->roles as $role){
            $roles[] = $role->name;
        }
        unset($user['roles']);
        $user['roles'] = $roles;


        $user['token'] = $user->createToken("personalAccessToken")->plainTextToken;

        $message = "waiting for admin's permission";

        return Response::Success($user, $message, 201);
    }
    
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string','email'],
            'password' => ['required', 'string']
        ]);
        
        if($validator->fails()){
            return Response::Error($validator->errors());
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return Response::Error('Invalid email or password', 401);
        }

        if($user->hasVerifiedEmail()){
            return Response::Error('please verify your email address', 403);
        }

        $token = $user->createToken('personalAccessToken')->plainTextToken;

        $userdata = ['user' => $user , 'token' => $token];
        $message = __('user logged in successfuly');

        return Response::Success($userdata, $message, 200 );
    }

    public function profile_info($id){
        try {
        $user = Auth::user();
        $user = User::findOrFail($id);
        $message = "This is the $id th user's profile ";
        
        return Response::Success($user, $message);

        } catch (Exception $th){
            $php_errormsg = 'faild to get user profile data';
            return Response::Error($php_errormsg . ", " . $th->getMessage(), 500);
        }
    }

    public function logout($id){
        try{
            $user = User::findOrFail($id);
            $user->tokens()->delete();
            $message = "The $id th user logged out successfully";
            return Response::Success($user, $message);
        } catch(Exception $t){
            $php_errormsg = 'falid in logged out ';
            return Response::Error($php_errormsg . ', ' . $t->getMessage(), 500);
        }
    }
    private function appendRolesAndPermissions($user)
    {
        $roles = [];
        foreach ($user->roles as $role){
            $roles[] = $role->name;
        }

        unset($user['roles']);
        $user['roles'] = $roles;

        $permissions = [];
        foreach ($user->permissions as $permission){
            $permissions[] = $permission->name;
        }
        unset($user['permissions']);
        $user['permissions'] = $permissions;

        return $user;

    }
}
