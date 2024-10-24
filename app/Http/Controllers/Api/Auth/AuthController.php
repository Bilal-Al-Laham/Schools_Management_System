<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\loginUserRequest;
use App\Http\Requests\registerUserRequest;
use App\Http\Responses\Response;
use App\Models\User;
use App\Services\UserService;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function register(RegisterUserRequest $request){

        try {
            $user = $this->userService->signup($request->validated());
            $message = __('User created successfully');
            return Response::Success($user, $message, 201);

        } catch (ValidationException $th) {
            return Response::Error(['errors' => $th->getMessage()], 422);
        } catch (Exception $e) {
            return Response::Error('An unexpected error occurred:' . $e->getMessage() . $e->getFile() . $e->getLine(), 500);
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
    
    public function login(LoginUserRequest $request){
        $validateData = $request->validated();

        $user = $this->userService->signIn($validateData);
        $message = __('user logged in successfuly');

        return Response::Success($user, $message, 200 );
    }

    public function profile_info(){
        try {
        $user = $this->userService->userProfile();
        $message = "This is info of $user->name's profile.";
        return Response::Success($user, $message);

        } catch (Exception $th){
            $php_errormsg = 'faild to get user profile data';
            return Response::Error($php_errormsg . ", " . $th->getMessage(), 500);
        }
    }

    public function logout(){
        try{
            $user = $this->userService->signOut();
            return $user;
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
