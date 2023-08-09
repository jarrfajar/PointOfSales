<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public static function register(object $request)
    {
        $user = User::create([
            'username'    => $request->username,
            'kode_cabang' => $request->cabang,
            'password'    => Hash::make($request->password)
        ]);

        $user->cabang()->attach($request->cabang);
        auth()->login($user);      
    }

    public static function login(object $request)
    {
        $credentials = $request->only('username', 'password');
        
        
        if (auth()->attempt($credentials)) {
            $user = Auth::user();

            // jika user tidak memiliki cabang tersebut maka logout
            if (!$user->cabang->contains('kode_cabang', $request->cabang)) {
                Auth::logout();
                return response()->json([
                    "message" => "cabang tidak valid"
                ], 401);
            }

            User::where('username', $request->username)->update(['kode_cabang' => $request->cabang]);

            return response()->json(['message' => 'success'], 200);
        }

        return response()->json(['message' => 'username atau password salah!.'], 422);
    }

    public static function logout(object $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}
