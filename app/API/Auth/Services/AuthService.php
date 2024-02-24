<?php

namespace App\API\Auth\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Api\Auth\Contracts\AuthServiceInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService implements AuthServiceInterface
{
    public function login($email, $password)
    {

        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        return ['token' =>  $user->createToken("Personal Access Token")->accessToken, 'user' => $user];
    }

    public function register($registerData)
    {
        return $registerData;
    }

    public function logout()
    {
        return auth()->user()->tokens()->delete();
    }

    public function getUser()
    {
        return auth()->user();
    }
}
