@extends('layouts.app')

@section('title', 'Tugas Assigned (Admin)')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Tugas yang Telah Diberikan (Leader)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Driver</th>
                            <th>Note / Instruksi</th>
                            <th>Status</th>
                            <th>Waktu Assign</th>
                            <th style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $index => $task)
                        <tr>
                            <td class="text-center">{{ $tasks->firstItem() + $index }}</td>
                            <td><strong>{{ $task->driver ? $task->driver->name : 'Unknown Driver' }}</strong></td>
                            <td id="task-note-{{ $task->id }}">{{ $task->note }}</td>
                            <td class="text-center">
                                @if($task->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-success">Selesai</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $task->created_at->format('d M Y H:i') }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-warning btn-sm me-1 text-dark" 
                                    onclick="openEditNoteModal({{ $task->id }}, '{{ addslashes($task->note) }}')">
                                    <i class="fas fa-edit"></i> Edit Note
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteTask({{ $task->id }})">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada tugas yang diassign.</td>
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

<!-- Modal Edit Note -->
<div class="modal fade" id="modalEditNote" tabindex="-1" aria-labelledby="modalEditNoteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formEditNote" onsubmit="event.preventDefault(); submitEditNote();">
            @csrf
            <input type="hidden" id="editTaskId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditNoteLabel">Edit Note / Instruksi Tugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Note / Instruksi</label>
                        <textarea class="form-control" id="editTaskNote" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Form Delete Task -->
<form id="formDeleteTask" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
    let editNoteModal = new bootstrap.Modal(document.getElementById('modalEditNote'));

    function openEditNoteModal(id, note) {
        $('#editTaskId').val(id);
        $('#editTaskNote').val(note);
        editNoteModal.show();
    }

    function submitEditNote() {
        let id = $('#editTaskId').val();
        let note = $('#editTaskNote').val();

        $.ajax({
            type: "POST",
            url: "/admin/tasks/" + id + "/update-note",
            data: {
                _token: '{{ csrf_token() }}',
                note: note
            },
            success: function(res) {
                editNoteModal.hide();
                $('#task-note-' + id).text(note);
                Swal.fire('Berhasil', res.message, 'success');
            },
            error: function(err) {
                Swal.fire('Error', 'Gagal memperbarui note tugas: ' + (err.responseJSON?.message || 'Error'), 'error');
            }
        });
    }

    function deleteTask(id) {
        Swal.fire({
            title: 'Hapus Tugas?',
            text: "Tugas ini akan dihapus dari daftar.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.getElementById('formDeleteTask');
                form.action = '/admin/tasks/' + id;
                form.submit();
            }
        });
    }
</script>
@endpush
