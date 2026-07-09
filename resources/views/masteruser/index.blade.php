@extends('layouts.sketch')

@section('title', 'Master User')

@section('content')
<div class="container-fluid mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Master User</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12">
            @if (session('role') == 'admin')
            <div class="card mb-3">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        @if (session('name') == 'admin')
                        <button type="button" class="btn btn-secondary">Log</button>
                        @else
                        <div></div>
                        @endif
                        <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#masterdataModal">
                            Add New
                        </button>
                    </div>
                </div>
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive" style="height: 400px; overflow-y: auto;">
                <table class="table table-bordered table-head-fixed text-nowrap">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">Role</th>
                            <th class="text-center">Employee Name</th>
                            <th class="text-center">Password</th>
                            <th class="text-center">Permit</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr style="background-color: {{ $user->role == 'admin' ? '#e8e8e8' : '#ffffff' }}">
                            <td class="text-center">{{ $user->role }}</td>
                            <td class="text-center">{{ $user->nama }}</td>
                            <td class="text-center">{{ $user->password }}</td>
                            <td class="text-center">{{ $user->permit }}</td>
                            <td class="text-center">
                                <button type="button" onclick="toggleEditModal(this)"
                                    data-user="{{ $user->id }};{{ $user->nama }};{{ $user->password }};{{ $user->role }};{{ $user->permit }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </button>

                                @if (session('role') == 'admin')
                                <form action="{{ route('masteruser.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="masterdataModal" tabindex="-1" aria-labelledby="masterdataModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('masteruser.store') }}" method="POST" autocomplete="off">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="masterdataModalLabel">Master Data User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <select class="form-select" name="role" required>
                            <option value="admin">admin</option>
                            <option selected value="user">user</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="nama" class="form-control" placeholder="Enter Employee Name" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" class="form-control" placeholder="password" required>
                    </div>
                    <div class="mb-3">
                        <select class="form-select" name="permit" required>
                            <option selected disabled value="">Choose Permit</option>
                            <option value="7">7</option>
                            <option value="206">206</option>
                            <option value="GRACE">PT. Grace</option>
                            <option value="super">Super</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editdataModal" tabindex="-1" aria-labelledby="editdataModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('masteruser.update') }}" method="POST" autocomplete="off">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editdataModalLabel">Edit Data User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editId">
                    
                    <div class="mb-3">
                        <select class="form-select" name="role" id="editrole" required {{ session('role') == 'user' ? 'hidden' : '' }}>
                            <option disabled>Choose Role</option>
                            <option value="user">user</option>
                            <option value="admin">admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="nama" class="form-control" id="editnama" required placeholder="Employee Name">
                    </div>
                    <div class="mb-3">
                        <input type="text" name="password" class="form-control" id="editpassword" required placeholder="Password">
                    </div>
                    <div class="mb-3">
                        <select class="form-select" name="permit" id="editpermit" required {{ session('role') == 'user' ? 'hidden' : '' }}>
                            <option disabled>Choose Permit</option>
                            <option value="7">7</option>
                            <option value="206">206</option>
                            <option value="GRACE">PT. Grace</option>
                            <option value="super">Super</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script>
    function toggleEditModal(btn) {
        let userStr = btn.getAttribute('data-user');
        let splitted = userStr.split(';');
        
        document.getElementById('editId').value = splitted[0];
        document.getElementById('editnama').value = splitted[1];
        document.getElementById('editpassword').value = splitted[2];
        document.getElementById('editrole').value = splitted[3];
        document.getElementById('editpermit').value = splitted[4];

        let editModal = new bootstrap.Modal(document.getElementById('editdataModal'));
        editModal.show();
    }
</script>
@endpush
