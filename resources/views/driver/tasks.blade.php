@extends('layouts.sketch')

@section('title', 'Daftar Tugas')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f0f2f5;
    }
    
    .task-container {
        max-width: 900px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .page-title {
        font-weight: 700;
        color: #1A314B;
        margin-bottom: 30px;
        position: relative;
        display: inline-block;
    }

    .page-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 50%;
        height: 4px;
        background: linear-gradient(90deg, #3498db, #2ecc71);
        border-radius: 2px;
    }

    .task-card {
        background: rgba(255, 255, 255, 0.95);
        border: none;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        backdrop-filter: blur(10px);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .task-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 6px;
        background: #3498db;
        border-radius: 16px 0 0 16px;
    }

    .task-card.completed::before {
        background: #2ecc71;
    }

    .task-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .task-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .admin-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .admin-name i {
        color: #3498db;
    }

    .task-time {
        font-size: 0.85rem;
        color: #7f8c8d;
        font-weight: 500;
    }

    .task-body {
        margin-bottom: 20px;
    }

    .task-note {
        font-size: 1rem;
        color: #34495e;
        line-height: 1.6;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #e9ecef;
    }

    .task-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #eee;
        padding-top: 15px;
    }

    .badge-status {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .badge-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .badge-completed {
        background-color: #d4edda;
        color: #155724;
    }

    .btn-complete {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(46, 204, 113, 0.3);
    }

    .btn-complete:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(46, 204, 113, 0.4);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .empty-state img {
        width: 150px;
        opacity: 0.5;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        color: #2c3e50;
        font-weight: 600;
    }

    .empty-state p {
        color: #7f8c8d;
    }
</style>
@endpush

@section('content')
<div class="task-container">
    <h1 class="page-title">Daftar Tugas Anda</h1>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; box-shadow: 0 4px 15px rgba(46, 204, 113, 0.2); border: none;">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px; border: none;">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="task-list">
        @forelse($tasks as $task)
        <div class="task-card {{ $task->status == 'completed' ? 'completed' : '' }}">
            <div class="task-header">
                <div class="admin-name">
                    <i class="fas fa-user-circle"></i>
                    {{ $task->admin ? $task->admin->name : 'Admin' }}
                </div>
                <div class="task-time">
                    <i class="far fa-clock"></i> {{ $task->created_at->diffForHumans() }} 
                    <small class="text-muted">({{ $task->created_at->format('d M Y, H:i') }})</small>
                </div>
            </div>
            
            <div class="task-body">
                <div class="task-note">
                    {!! nl2br(e($task->note)) !!}
                </div>
            </div>
            
            <div class="task-footer">
                <div>
                    @if($task->status == 'pending')
                        <span class="badge-status badge-pending"><i class="fas fa-hourglass-half"></i> Menunggu Diproses</span>
                    @else
                        <span class="badge-status badge-completed"><i class="fas fa-check-circle"></i> Selesai</span>
                    @endif
                </div>
                
                @if($task->status == 'pending')
                <div>
                    <form action="{{ route('tugas.complete', $task->id) }}" method="POST" id="form-complete-{{ $task->id }}">
                        @csrf
                        <button type="button" class="btn-complete" onclick="confirmComplete({{ $task->id }})">
                            <i class="fas fa-check"></i> Tandai Selesai
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-clipboard-list" style="font-size: 80px; color: #ecf0f1; margin-bottom: 20px;"></i>
            <h3>Tidak Ada Tugas</h3>
            <p>Anda belum memiliki tugas baru saat ini. Silakan beristirahat atau tunggu instruksi dari Admin.</p>
        </div>
        @endforelse
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $tasks->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmComplete(id) {
    Swal.fire({
        title: 'Konfirmasi Penyelesaian',
        text: "Apakah Anda yakin tugas ini sudah selesai dikerjakan?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2ecc71',
        cancelButtonColor: '#e74c3c',
        confirmButtonText: 'Ya, Sudah Selesai!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-complete-' + id).submit();
        }
    })
}
</script>
@endpush
