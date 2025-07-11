@extends('layouts.app')

@section('content')
<section class="section">
<div class="container-fluid">
    <div class="section-header">
        <h1>Kelola SKPD</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ url('/home') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Kelola SKPD</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Daftar Satuan Kerja Perangkat Daerah (SKPD)</h4>
                        <div class="card-header-action">
                            <a href="#" class="btn btn-icon btn-primary" data-toggle="modal" data-target="#createSKPDModal" id="btn-add"><i class="fas fa-plus"></i> Tambah SKPD</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="skpd-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Singkatan</th>
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

@include('layouts.admin.skpd.create')
@include('layouts.admin.skpd.edit')

@push('scripts')
<script>
    $(function() {
        let table = $('#skpd-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.skpd.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'singkatan', name: 'singkatan' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        // Save SKPD using button click instead of form submit
        $('#saveBtn').on('click', function() {
            let form = $('#createSKPDForm');
            $.ajax({
                url: "{{ route('admin.skpd.store') }}",
                method: "POST",
                data: form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status) {
                        $('#createSKPDModal').modal('hide');
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

        // Edit SKPD
        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            $.ajax({
                url: `/admin/skpd/${id}/edit`,
                method: "GET",
                success: function(data) {
                    $('#editSKPDModal').modal('show');
                    $('#editSKPDForm #skpd_id').val(data.id);
                    $('#editSKPDForm #edit_name').val(data.name);
                    $('#editSKPDForm #edit_singkatan').val(data.singkatan);
                },
                error: function(xhr) {
                    toastr.error('Terjadi kesalahan saat mengambil data');
                }
            });
        });

        // Update SKPD
        $('#editSKPDForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let id = $('#skpd_id').val();
            $.ajax({
                url: `/admin/skpd/${id}`,
                method: "PUT",
                data: form.serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#editSKPDModal').modal('hide');
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

    // Delete SKPD
    window.deleteSKPD = function(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "SKPD yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/skpd/${id}`,
                    type: 'DELETE',
                    data: { "_token": "{{ csrf_token() }}" },
                    success: function(response) {
                        $('#skpd-table').DataTable().ajax.reload();
                        Swal.fire(
                            'Terhapus!',
                            'SKPD berhasil dihapus.',
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