<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MasterUserController extends Controller
{
    public function index()
    {
        if (session('role') == 'user') {
            $users = User::where('id', auth()->id() ?? session('id'))->get();
        } else {
            $users = User::orderBy('role', 'asc')->orderBy('permit', 'asc')->get();
        }

        return view('masteruser.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required',
            'nama' => 'required|unique:users,nama',
            'password' => 'required',
            'permit' => 'required',
        ]);

        User::create([
            'role' => $request->role,
            'nama' => $request->nama,
            'password' => $request->password, // Storing as plain text matching legacy format shown in view
            'permit' => $request->permit,
            'name' => $request->nama, // also fill name if required by User model
            'email' => uniqid('user_') . '@sumitomo.com', // satisfy NOT NULL constraint
        ]);

        return redirect()->back()->with('success', 'User added successfully.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'nama' => 'required|unique:users,nama,' . $request->id,
            'password' => 'required',
        ]);

        $user = User::findOrFail($request->id);
        
        $data = [
            'nama' => $request->nama,
            'password' => $request->password,
            'name' => $request->nama,
        ];

        if (session('role') != 'user') {
            $data['role'] = $request->role;
            $data['permit'] = $request->permit;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        if (session('role') != 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
