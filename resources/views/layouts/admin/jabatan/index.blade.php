@extends('layouts.app')

@section('content')
<section class="section">
    <div class="container-fluid">
        <div class="section-header">
            <h1>Kelola Jabatan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('/home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Kelola Jabatan</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Jabatan</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createJabatanModal"><i class="fas fa-plus"></i> Tambah Jabatan</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="jabatan-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Jabatan</th>
                                            <th>Bidang</th>
                                            <th>SKPD</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('layouts.admin.jabatan.create')
@include('layouts.admin.jabatan.edit')

@push('scripts')
<script>
    $(function() {
        let table = $('#jabatan-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.jabatan.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'bidang_name', name: 'bidang_name' },
                { data: 'skpd_name', name: 'skpd_name' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        // Save Jabatan
        $('#createJabatanForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            $.ajax({
                url: "{{ route('admin.jabatan.store') }}",
                method: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#createJabatanModal').modal('hide');
                        form[0].reset();
                        table.ajax.reload();
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('Terjadi kesalahan saat menyimpan data');
                    }
                }
            });
        });

        // Edit Jabatan
        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            $.ajax({
                url: `/admin/jabatan/${id}/edit`,
                method: "GET",
                success: function(data) {
                    $('#editJabatanModal').modal('show');
                    $('#editJabatanForm #jabatan_id').val(data.id);
                    $('#editJabatanForm #edit_name').val(data.name);
                    $('#editJabatanForm #edit_bidang_id').val(data.bidang_id);
                },
                error: function(xhr) {
                    toastr.error('Terjadi kesalahan saat mengambil data');
                }
            });
        });

        // Update Jabatan
        $('#editJabatanForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let id = $('#jabatan_id').val();
            $.ajax({
                url: `/admin/jabatan/${id}`,
                method: "PUT",
                data: form.serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#editJabatanModal').modal('hide');
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

    // Delete Jabatan with SweetAlert
    window.deleteJabatan = function(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Jabatan yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/jabatan/${id}`,
                    type: 'DELETE',
                    data: { "_token": "{{ csrf_token() }}" },
                    success: function(response) {
                        $('#jabatan-table').DataTable().ajax.reload();
                        Swal.fire(
                            'Terhapus!',
                            'Jabatan berhasil dihapus.',
                            'success'
                        );
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'Terjadi kesalahan saat menghapus data.',
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>
@endpush
@endsection