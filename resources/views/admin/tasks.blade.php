@extends('layouts.app')

@section('title', 'Tugas Assigned (Admin)')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Daftar Tugas yang Telah Diberikan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Driver</th>
                            <th>Note / Instruksi</th>
                            <th>Status</th>
                            <th>Waktu Assign</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $index => $task)
                        <tr>
                            <td>{{ $tasks->firstItem() + $index }}</td>
                            <td>{{ $task->driver ? $task->driver->name : 'Unknown Driver' }}</td>
                            <td>{{ $task->note }}</td>
                            <td>
                                @if($task->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-success">Selesai</span>
                                @endif
                            </td>
                            <td>{{ $task->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada tugas yang diassign.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $tasks->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
