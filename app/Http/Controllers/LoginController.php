<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    private $userService;

    public function __construct()
    {
        $this->userService = new UserServices();
    }

    public function login(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ])->stopOnFirstFailure(true);

        $credentials = request(['email', 'password']);

        if(Auth::attempt($credentials))
        {
            $user = $request->user();
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return response()->json([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 200);
        }else
        {
            return response()->json([
                'message' => 'Credentials are wrong'
            ], 401);
        }
        
        return $validated->errors();
    }

    public function register(UserRequest $request)
    {
        $data = $request->validated();

        $save_user = $this->userService->addOrEditUser($data);

        if(!$save_user)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'User registration failed',
            ], 500);
        }else{
            return response()->json([
                'status' => 'success',
                'data' => $data,
            ], 200);
        }
    }
}
