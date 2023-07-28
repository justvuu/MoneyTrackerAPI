<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required',
            'question' => 'required',
            'answer' => 'required',
        ]);

        $user = User::create([
            'username' => $validatedData['username'],
            'password' => bcrypt($validatedData['password']),
            'question' => $validatedData['question'],
            'answer' => $validatedData['answer'],
        ]);

        return response()->json(['message' => 'Registration successful'], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            return response()->json(['message' => 'Login successful'], 200);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    public function resetPassword(Request $request)
    {
        $credentials = $request->only('username', 'question', 'answer', 'newPassword');

        $user = User::where('username', $credentials['username'])->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid username'], 400);
        }

        if ($user->question !== $credentials['question'] || $user->answer !== $credentials['answer']) {
            return response()->json(['message' => 'Invalid question or answer'], 400);
        }

        $user->password =bcrypt($credentials['newPassword']);
        $user->save();

        return response()->json(['message' => 'Password reset successfully'], 200);
    }

    public function changePassword(Request $request)
    {
        $credentials = $request->only('username', 'oldPassword', 'newPassword');
        $user = User::where('username', $credentials['username'])->first();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }


        if (!Hash::check($credentials['oldPassword'], $user->password)) {
            return response()->json(['message' => 'Incorrect old password'], 400);
        }

        $user->password = Hash::make($credentials['newPassword']);
        $user->save();

        return response()->json(['message' => 'Password changed successfully'], 200);
    }

    public function updateUserIncome(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $newIncome = $request->input('newIncome');

        $user->income = $newIncome;
        $user->save();

        return response()->json(['message' => 'User income updated successfully'], 200);
    }

    public function updateUserExpense(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $newExpense = $request->input('newExpense');

        $user->expense = $newExpense;
        $user->save();

        return response()->json(['message' => 'User expense updated successfully'], 200);
    }
}
