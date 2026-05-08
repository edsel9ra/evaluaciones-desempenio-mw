<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AuthService;
use Inertia\Inertia;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLogin()
    {
        return Inertia::render('Login');
    }

    public function login(Request $request)
    {
        $result = $this->authService->login(
            $request->username,
            $request->password
        );

        if ($result['success']) {
            return redirect()->route('dashboard');
        }

        return back()->with('error', $result['message']);
    }

    public function logout()
    {
        $this->authService->logout();
        return redirect()->route('login');
    }

    public function currentUser()
    {
        return Auth::user();
    }
}
