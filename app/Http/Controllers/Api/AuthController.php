<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /** method for user login and create token */
    public function login(Request $request)
    {
        // Validasi input user
        $request->validate([
            'email'    => ['required', 'email'],
            'password'    => ['required', 'string', 'min:8'],
        ]);

        try {
            // Mengecek apakah email dan password sudah sesuai atau belum 
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user(); 
                // Membuat token jika email dan password sudah sesuai
                $token = $user->createToken('jds-test')->accessToken;
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully Login',
                'token' => $token
            ]);
        } catch (\Throwable $th) {
            info($th);

            return response()->json([
                'status' => 'failed',
                'message' => 'Failed to login',
            ], 500);
        }
    }

    /** method for user register */
    public function register(Request $request)
    {
        // Validasi input user
        $data_validate = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8'],
        ]);

        try {
            // Menambahkan data yang sudah diinputkan user ke database
            User::create(Arr::except($data_validate, ['password']) + [
                'password' => Hash::make($data_validate['password'])
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Register Successfully, you can login now',
            ]);
        } catch (\Throwable $th) {
            info($th);
            return response()->json([
                'status' => 'failed',
                'message' => 'Failed to register',
            ], 500);
        }
    }

     /** method for user logout */
     public function logout(Request $request)
     {
         try {
            // Menghapus token yang sudah digunakan sekarang
             $request->user()->tokens()->delete();
 
             return response()->json([
                 'status' => 'success',
                 'message' => 'Logout Successfully'
             ]);
         } catch (\Throwable $th) {
             info($th);
 
             return response()->json([
                 'status' => 'failed',
                 'message' => 'Failed to logout',
             ], 500);
         }
     }
}
