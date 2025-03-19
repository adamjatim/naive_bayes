<?php

// app/Http/Controllers/KaryawanController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawan = User::karyawan()->get();
        return view('pages.karyawan.index', compact('karyawan'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'karyawan',
        ]);

        return response()->json(['message' => 'Karyawan berhasil ditambahkan']);
    }

    public function update(Request $request, $id)
    {
        $karyawan = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $karyawan->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return response()->json(['message' => 'Karyawan berhasil diperbarui']);
    }

    public function destroy($id)
    {
        $karyawan = User::findOrFail($id);
        $karyawan->delete();

        return response()->json(['message' => 'Karyawan berhasil dihapus']);
    }
}
