<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Record;
use App\Models\LogLogin;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session()->has('name')) {
            return redirect()->route('home');
        }

        // Logic from index.php
        $Date = date('Y-m-d');
        $oldDate = $Date;
        $Date = date('Y-m-d', strtotime($Date . ' - 1 days'));
        
        $checkData = Record::select(DB::raw('COUNT(DISTINCT LotNumber) as count'))
            ->whereDate('Date', $Date)
            ->first();
            
        $count = $checkData ? $checkData->count : 0;

        return view('auth.login', compact('count'));
    }

    public function processLogin(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('nama', $request->nama)
                    ->where('password', $request->password)
                    ->first();

        if ($user) {
            Auth::login($user);
            
            session([
                'id' => $user->id,
                'name' => $user->nama,
                'role' => $user->role,
                'password' => $user->password,
                'permit' => $user->permit
            ]);

            $DateTime = now()->format('Y-m-d H:i:s');
            LogLogin::insert([
                'id' => 0,
                'nama' => $user->nama,
                'dateTime' => $DateTime,
                'action' => 'Login'
            ]);

            // TODO: Include autoRecord.php logic if necessary, or port it later.
            // For now we just redirect to home
            return redirect()->route('home');
        } else {
            return back()->with('error', 'Username atau Password salah');
        }
    }

    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('login');
    }
}
