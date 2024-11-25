<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Expert;
use App\Models\Vendor;
use Hash;
use Illuminate\Http\Request;
use Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function _construct(){
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        if(!$token = Auth::attempt($validator->validated())){
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);


    }
    public function login(Request $request){
        $selectedRole = $request->selectedRole;
        if ($selectedRole === 'user') {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
        } elseif ($selectedRole === 'vendor' || $selectedRole === 'expert') {
            $validator = Validator::make($request->all(), [
                'phone' => 'required',
                'password' => 'required',
            ]);
        } else {
            return response()->json(['error' => 'Invalid role selected'], 400);
        }

        if ($validator->fails()){
            return response()->json($validator->errors()->toJson(), 422);
        }
        switch ($selectedRole) {
            case 'user':
                $guard = 'user';
                $provider = 'users';
                break;
            case 'vendor':
                $guard = 'vendor';
                $provider = 'vendors';
                break;
            case 'expert':
                $guard = 'expert';
                $provider = 'experts';
                break;
            default:
                return response()->json(['error' => 'Role is not valid'], 400);
        }

        config(['auth.providers.' . $provider => [
            'driver' => 'eloquent',
            'model' => 'App\\Models\\' . ucfirst($selectedRole)
        ]]);

        if (!$token = Auth::guard($guard)->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    public function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60 ,
        ]);

    }

    public function profile(){
        return response()->json(auth()->user());
    }

    public function logout(){
        auth()->logout();
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'User logged out successfully']);
    }

    public function authUser(Request $request){
        $selectedRole = $request->query('selectedRole');
        if($selectedRole == 'user'){
            $user = Auth::guard('user')->user();
        }
        elseif($selectedRole == 'vendor'){
            $user = Auth::guard('vendor')->user();
            $user = $user ? $user->only(['id', 'name', 'email']) : null;

        }
        elseif($selectedRole == 'expert'){
            $user = Auth::guard('expert')->user();
        }
        return response()->json($user);
    }


}
