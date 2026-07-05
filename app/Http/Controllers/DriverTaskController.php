<?php

namespace App\Http\Controllers;

use App\Models\DriverTask;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class DriverTaskController extends Controller
{
    public function index()
    {
        // Show tasks for the currently logged in driver
        $tasks = DriverTask::with('admin')->where('driver_id', Auth::id())->orderBy('created_at', 'desc')->paginate(10);
        return view('driver.tasks', compact('tasks'));
    }

    public function adminIndex()
    {
        // Show tasks assigned by the currently logged in admin
        $tasks = DriverTask::with('driver')->where('admin_id', Auth::id())->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.tasks', compact('tasks'));
    }

    public function fetchDrivers()
    {
        // Fetch users who can be assigned as drivers (role 'user' or 'driver')
        $drivers = User::whereIn('role', ['user', 'driver'])->get();
        return response()->json($drivers);
    }

    public function assign(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:users,id',
            'note' => 'nullable|string'
        ]);

        $task = DriverTask::create([
            'admin_id' => Auth::id() ?? 1, // fallback to 1 if no auth (testing)
            'driver_id' => $request->driver_id,
            'note' => $request->note,
            'status' => 'pending'
        ]);

        // Kirim HTTP POST request ke server Node.js Socket.io (tanpa Redis)
        try {
            Http::post('http://localhost:3000/emit', [
                'channel' => 'driver-channel.' . $task->driver_id,
                'event' => 'driver.assigned',
                'data' => [
                    'task' => $task
                ]
            ]);
        } catch (\Exception $e) {
            // Abaikan error jika node server mati, task tetap tersimpan
            \Log::error('Gagal mengirim socket emit: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Tugas berhasil di-assign ke driver.']);
    }

    public function complete($id)
    {
        $task = DriverTask::findOrFail($id);
        
        // Pastikan hanya driver terkait yang bisa menyelesaikan
        if ($task->driver_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $task->update(['status' => 'completed']);

        return redirect()->route('tugas.index')->with('success', 'Tugas berhasil diselesaikan!');
    }
}
