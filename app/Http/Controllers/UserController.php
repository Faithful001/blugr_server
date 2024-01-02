<?php

// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Token;
use function App\Helpers\inputFieldValidation;

class UserController extends Controller
{


    public function getUsers()
    {
        try {
            $users = UserModel::all();
            if ($users->count() > 0) {
                return response()->json([
                    "status" => true,
                    "message" => 'Users found',
                    'data' => $users
                ], 200);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "No user found",
                    'data' => null
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "error" => $e->getMessage(),
                "data" => null
            ], 500);
        }
    }

    public function getUser($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new \Exception("Invalid user ID", 400);
            }
            $user = UserModel::find($id);
            if ($user) {
                return response()->json([
                    "status" => true,
                    "message" => 'User found',
                    "data" => $user
                ], 200);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "User not found",
                    'data' => null
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "error" => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function signupUser(Request $req)
    {
        try {
            $req->validate([
                'user_name' => 'required|string',
                'email' => 'required|email|unique:user_models,email',
                'password' => 'required|string|min:8',
            ]);

            inputFieldValidation($req->user_name, $req->email, $req->password);

            $userExists = UserModel::where('email', $req->email)->exists();

            if ($userExists) {
                return response()->json([
                    "status" => false,
                    "error" => "User is already registered",
                    "data" => null
                ], 400);
            }

            $user = UserModel::create([
                "user_name" => $req->user_name,
                "email" => $req->email,
                "password" => Hash::make($req->password)
            ]);

            $token = (new Token)->generateToken($user, $user->id);

            $clientUser = $user->toArray();
            unset($clientUser['password']);

            return response()->json([
                'status' => true,
                'message' => 'User created successfully',
                'data' => ['user' => $clientUser, 'token' => $token]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "error" => $e->getMessage(),
                "data" => null
            ], 500);
        }
    }

    public function loginUser(Request $req)
    {
        try {
            $req->validate([
                'email' => 'required|email|exists:user_models,email',
                'password' => 'required|string',
            ]);

            $user = UserModel::where('email', $req->email)->first();
            if($user){
                if(Hash::check($req->password, $user->password)){
                    $token = (new Token)->generateToken($user, $user->id);
                    $clientUser = $user->toArray();
                    unset($clientUser['password']);

                    return response()->json([
                        "status" => true,
                        "message" => 'user logged in successfully',
                        "data" => ['user'=>$clientUser, 'token'=>$token]
                    ], 200);
                }else{
                    throw new \Exception("Incorrect password", 401);
                }
            }else{
                throw new \Exception("User not registered", 404);
            }

        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "error" => $e->getMessage(),
                "data" => null
            ], 500);
        }
    }
}

