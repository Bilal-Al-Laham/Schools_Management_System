<?php

namespace App\Services;

use App\Models\School;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

interface UserServiceInterface
{
    public function  signup(array $data);

    public function signIn(array $data);

    public function userProfile();

    public function signOut();

}

class UserService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepositoryInterface) {
        $this->userRepository = $userRepositoryInterface;
    }

    public function signup(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->createUser($data);

        $user['token'] = $user->createToken('personalAccessToken')->plainTextToken;

        $school = School::find($data['school_id']);
        if (!$school) {
            throw new \Exception('School not found.', 404);;
            
        }
        $user['school_id'] = $school->name;

        return $user;

        // $clientRole = Role::query()->where('name','client')->first();
        // $user->assignRole($clientRole);

        // Assign permissions associated with the role to the user
        // $permissions = $clientRole->permissions()->pluck('name')->toArray();
        // $user->givePermissionTo($permissions);

        // Load the user's roles and permissions
        // $user->load('roles','permissions');

        // Reload the user instance to get updated roles and permissions
        // $user= $this->appendRolesAndPermissions($user);

        // $qrCode = QrCode::format('png')->size(200)->generate($user->id);
        // $qrCodePath = 'qrcodes/user_'.$user->id.'.png';
        // Storage::disk('public')->put($qrCodePath,$qrCode);
        // $user->qr_code_path = $qrCodePath;
    }
    public function registerAsPlanner($request):array
    {
        $user = User::query()->create([

            'name'=> $request['name'],
            'email'=> $request['email'],
            'password'=> Hash::make($request['password']),
        ]);

        // {

        // ||==============================================================================||
        // ||this block of code will be put in admin's function (givePermissionToPlanner())||
        // ||==============================================================================||


        // // Assign permissions associated with the role to the user
        // $permissions = $plannerRole->permissions()->pluck('name')->toArray();
        // $user->givePermissionTo($permissions);

        // // Load the user's roles and permissions
        // $user->load('roles','permissions');

        // // Reload the user instance to get updated roles and permissions
        // $user= User::query()->find($user['id']);
        //
        // $permissions = [];
        // foreach ($user->permissions as $permission){
        //     $permissions[] = $permission->name;
        // }
        // unset($user['permissions']);
        // $user['permissions'] = $permissions;

        // }

        // $plannerRole = Role::query()->where('name','planner')->first();
        // $user->assignRole($plannerRole);


        $user= User::query()->find($user['id']);
        // $roles = [];
        // foreach ($user->roles as $role){
        //     $roles[] = $role->name;
        // }
        // unset($user['roles']);
        // $user['roles'] = $roles;


        $user['token'] = $user->createToken("PassportToken")->accessToken;

        $message = "waiting for admin's permission";
        return ['user' => $user , 'message' => $message];
    }

    public function signIn(array $data)
    {
        $user = $this->userRepository->findByEmail($data['email']);
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new \Exception('Invalid email or password', 401);
        }
        if($user->hasVerifiedEmail()){
            throw new \Exception('please verify your email address', 403);
        }
        $token = $user->createToken('personalAccessToken')->plainTextToken;
        
        if (!is_null($user)) {
            if (!Auth::attempt($data)) {                
                $message = 'Email Or Password Is Not Valid';
                throw new \Exception($message, 401);
            }
        }
        $this->userRepository->updateLastLogin($user);
            // else {
            //     $user = $this->appendRolesAndPermissions($user);
            //     $user['token'] = $user->createToken("PassportToken")->accessToken;
            //     $message = 'user logged in successfully';
            //     $status = 200;
            // }
            $userdata = ['user' => $user , 'token' => $token];
            return $userdata;


              // }
        // else{
        //     $message = 'invalid Token';
        //     $status = 404;
        // }
        // return ['user' => $user , 'message' => $message , 'status'=>$status];
    }
    // public function Adminlogin($request):array
    // {
    //     $user = User::query()
    //         ->where('email',$request['email'])
    //         ->first();

    //     if (!is_null($user)) {
    //         if (!Auth::attempt($request) || $request['email'] != 'Admin@example.com') {
    //             $message = 'You are not the admin';
    //             $status = 401;
    //         }
    //         else {
    //             $user = $this->appendRolesAndPermissions($user);
    //             $user['token'] = $user->createToken("PassportToken")->accessToken;
    //             $message = 'Hello Admin';
    //             $status = 200;
    //         }
    //     }
    //     else{
    //         $message = 'invalid Token';
    //         $status = 404;
    //     }
    //     return ['user' => $user , 'message' => $message , 'status'=>$status];
    // }
    public function userProfile()
    {
        $user = Auth::user();
        return $user;
    }

    public function signOut()
    {
        $user = Auth::user();
        if ($user) {
            $user->tokens()->delete();
            return response()->json(['message' => "$user->name logged out successfully"]);
        }
        return $user;
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
