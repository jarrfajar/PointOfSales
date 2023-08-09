<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    function ShowLogin()
    {
        return view('pages.auth-login2');
    }

    public function showRegister()
    {
        return view('pages.auth-register');
    }

    public function register(AuthRequest $request)
    {
        return AuthService::register($request);
    }

    public function login(Request $request)
    {
        return AuthService::login($request);
    }

    public function logout(Request $request)
    {
        return AuthService::logout($request);
    }
}
