<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Register a new user
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string',
            'role' => 'required|in:boss,developer,designer,intern',
            'picture' => 'required',
            'phone_number' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'picture' => $request->picture,
            'phone_number' => $request->phone_number
        ]);

        return [
            'user' => $user,
            'token' => $user->createToken('owow')->plainTextToken
        ];
    }

    /**
     * Log an existing user in
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $user = User::where('name', $request->name)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'name' => ['The provided credentials are incorrect.'],
            ]);
        }

        return [
            'user' => $user,
            'token' => $user->createToken('owow')->plainTextToken
        ];
    }

    /**
     * Remove all tokens from an user
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return response(200);
    }

}
