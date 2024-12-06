<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'login_face', 'refresh', 'register']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        } else {
            // $user = User::where('username', $username)->first();

            $user = User::select('users.*', 'users_role.role')
                ->orderBy('created_at', 'desc')
                ->join('users_role', 'users_role.id', '=', 'users.role_id')
                ->where('users.username', $username)
                ->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Your Account or Username is not registered'
                ], 400);
            }

            $isValidPassword = Hash::check($password, $user->password);

            if (!$isValidPassword) {
                return response()->json([
                    'status' => false,
                    'message' => 'Login failed! Check Your Password'
                ], 400);
            }

            $userStatus = $user->status;

            if ($userStatus == 2) {
                return response()->json([
                    'status' => false,
                    'message' => 'Your account is inactive'
                ], 400);
            }

            // $checkToken = $user->api_token;

            // if ($checkToken) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'Akun anda sedang digunakan'
            //     ], 400);
            // }

            $user->update([
                'remember_token' => Helper::genSSH512($user->id)
            ]);

            $url = Helper::url();

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'data' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'foto' => $user->foto == null ? $url."user/default.jpg" : $url."user/".$user->foto,
                    'role_id' => $user->role_id,
                    'role' => $user->role,
                    'status' => $user->status
                ],
                'headers' => [
                    'key' => 'Authorization',
                    'token_type' => 'Bearer',
                    'access_token' => $user->remember_token
                ]
            ], 200);
        }
    }

    public function login_face(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'similar' => 'required'
        ]);

        $username = $request->input('subject');
        $similarity = $request->input('similar');

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        } else {
            $user = User::select('users.*', 'users_role.role')
                ->orderBy('created_at', 'desc')
                ->join('users_role', 'users_role.id', '=', 'users.role_id')
                ->where('users.username', $username)
                ->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Your Account or Username is not registered'
                ], 400);
            }

            if ($similarity < 0.98) {
                return response()->json([
                    'status' => false,
                    'message' => 'Face is not registered'
                ], 400);
            }

            $userStatus = $user->status;

            if ($userStatus == 2) {
                return response()->json([
                    'status' => false,
                    'message' => 'Your account is inactive'
                ], 400);
            }

            $user->update([
                'remember_token' => Helper::genSSH512($user->id)
            ]);

            $url = Helper::url();

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'data' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'foto' => $user->foto == null ? $url."user/default.jpg" : $url."user/".$user->foto,
                    'role_id' => $user->role_id,
                    'role' => $user->role,
                    'status' => $user->status
                ],
                'headers' => [
                    'key' => 'Authorization',
                    'token_type' => 'Bearer',
                    'access_token' => $user->remember_token
                ]
            ], 200);
        }
    }

    public function logout(Request $request)
    {
        $token = explode(' ', $request->header('Authorization'));
        $username = $request->username;

        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 400);
        } else {
            $user->update([
                'remember_token' => null
            ]);

            return response()->json([
                'status' => true,
                'message' => 'You are Logged Out'
            ], 200);
        }
    }
}
