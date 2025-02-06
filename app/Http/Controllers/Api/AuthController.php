<?php

namespace App\Http\Controllers\Api;

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
use Throwable;

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

    public function registerAsTeacher(registerUserRequest $request)
    {
        $userData = $request->validated();
        return $this->userService->registerAsTeacher($userData);
    }

    public function teacherlogin(LoginUserRequest $request)
    {
        $userData = $request->validated();
        return $this->userService->teacherlogin($userData);
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

    public function Adminlogin(loginUserRequest $request){
        $data = [];
        try {
            $data = $this->userService->Adminlogin($request->validated());
            return Response::Success($data['user'],$data['message']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
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
