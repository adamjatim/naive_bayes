<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;

class ProfileController extends Controller
{
    public function index()
    {
        return view('pages.profile.index');
    }

    public function update(Request $request, $id)
    {
        $userId = Crypt::decrypt($id);

        // Pastikan hanya user yang login yang bisa mengedit profilnya sendiri
        if ($userId != Auth::user()->id) {
            return redirect()->route('profile.index')->with('message', [
                'type' => 'Error',
                'text' => 'Unauthorized action.',
            ]);
        }

        // Cek apakah ini update nama & email atau update password
        if ($request->has('name') && $request->has('email')) {
            // Validasi data
            $request->validate([
                'name'  => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $userId,
            ]);

            // Update data user
            $user = User::findOrFail($userId);
            $user->update([
                'name'  => $request->name,
                'email' => $request->email,
            ]);

            return redirect()->route('profile.index')->with('message', [
                'type' => 'Success',
                'text' => 'Profile updated successfully.',
            ]);
        }

        if ($request->has('current_password') && $request->has('password')) {
            // Validasi password
            $request->validate([
                'current_password'      => 'required',
                'password'              => 'required|min:8|confirmed',
            ]);

            // Cek apakah password lama cocok
            if (!Hash::check($request->current_password, Auth::user()->password)) {
                return redirect()->route('profile.index')->with('message', [
                'type' => 'Error',
                'text' => 'Current password is incorrect.',
            ]);
            }

            // Update password
            $user = User::findOrFail($userId);
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('profile.index')->with('message', [
                'type' => 'Success',
                'text' => 'Password updated successfully.',
            ]);
        }

        return redirect()->route('profile.index')->with('message', [
                'type' => 'Error',
                'text' => 'Invalid request.',
            ]);
    }

}
