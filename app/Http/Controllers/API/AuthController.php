<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {

            $validated = $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
            ]);

            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => User::STATUS_EMPOYEE,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'User created successfully.',
                'user'    => $user
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation error.',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('User Registration Error: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong, please try again later.'
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try{
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                throw ValidationException::withMessages(['email' => 'Invalid credentials']);
            }

            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful.',
                'user'   => $user,
                'token'  => $token
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation error.',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Login Error: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong, please try again later.'
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json([
                'status'  => 'success',
                'message' => 'Logged out successfully'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Logout Error: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong, please try again later.'
            ], 500);
        }
    }
}
