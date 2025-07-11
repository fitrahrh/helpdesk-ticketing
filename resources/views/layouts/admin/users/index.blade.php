
@extends('layouts.app')

@section('content')
<section class="section">
<div class="container-fluid">
    <div class="section-header">
        <h1>Kelola User</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ url('/home') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Kelola User</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Daftar User</h4>
                        <div class="card-header-action">
                            <a href="#" class="btn btn-icon btn-primary" data-toggle="modal" data-target="#createUserModal" id="btn-add"><i class="fas fa-plus"></i> Tambah User</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="users-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be filled by DataTables -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('layouts.admin.users.create')
@include('layouts.admin.users.edit')

@push('scripts')
<script>
    $(function() {
        let table = $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.users.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'role.name', name: 'role.name' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        // Save User
        $('#createUserForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            $.ajax({
                url: "{{ route('admin.users.store') }}",
                method: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#createUserModal').modal('hide');
                        form[0].reset();
                        table.ajax.reload();
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                }
            });
        });

        // Edit User
        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            $.ajax({
                url: `/admin/users/${id}/edit`,
                method: "GET",
                success: function(data) {
                    $('#editUserModal').modal('show');
                    $('#editUserForm #user_id').val(data.id);
                    $('#editUserForm #edit_name').val(data.name);
                    $('#editUserForm #edit_email').val(data.email);
                    $('#editUserForm #edit_role_id').val(data.role_id);
                },
                error: function(xhr) {
                    toastr.error('Terjadi kesalahan saat mengambil data');
                }
            });
        });

        // Update User
        $('#editUserForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let id = $('#user_id').val();
            $.ajax({
                url: `/admin/users/${id}`,
                method: "PUT",
                data: form.serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#editUserModal').modal('hide');
                        table.ajax.reload();
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                }
            });
        });
    });

    // Delete User
    function deleteUser(id) {
        if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
            $.ajax({
                url: `/admin/users/${id}`,
                method: "DELETE",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.status) {
                        $('#users-table').DataTable().ajax.reload();
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('Terjadi kesalahan saat menghapus data');
                }
            });
        }
    }

$(document).on('click', '.btn-edit', function() {
    let id = $(this).data('id');
    $.ajax({
        url: `/admin/users/${id}/edit`,
        method: "GET",
        success: function(data) {
            $('#editUserModal').modal('show');
            $('#editUserForm #user_id').val(data.id);
            $('#editUserForm #edit_first_name').val(data.first_name);
            $('#editUserForm #edit_last_name').val(data.last_name);
            $('#editUserForm #edit_email').val(data.email);
            $('#editUserForm #edit_role_id').val(data.role_id);
        },
        error: function(xhr) {
            toastr.error('Terjadi kesalahan saat mengambil data');
        }
    });
});
</script>
@endpush
@endsection
  