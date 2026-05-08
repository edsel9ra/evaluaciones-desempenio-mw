<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login($username, $password)
    {
        $user = User::where('username', $username)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return [
                'success' => false,
                'message' => 'Invalid credentials'
            ];
        }

        Auth::login($user);
        return [
            'success' => true,
            'user' => $user
        ];
    }

    public function logout(): void
    {
        Auth::logout();
    }

    public function getCurrentUser(): ?User
    {
        return Auth::user();
    }
}
