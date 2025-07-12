@extends('layouts.app')

@section('content')
<section class="section">
    <div class="container-fluid">
        <div class="section-header">
            <h1>Kelola Kategori</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('/home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Kelola Kategori</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Kategori</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createKategoriModal"><i class="fas fa-plus"></i> Tambah Kategori</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="kategori-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Kategori</th>
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

@include('layouts.admin.kategori.create')
@include('layouts.admin.kategori.edit')

@push('scripts')
<script>
    $(function() {
        let table = $('#kategori-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.kategori.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'skpd_name', name: 'skpd_name' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        // Save Kategori
        $('#createKategoriForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            $.ajax({
                url: "{{ route('admin.kategori.store') }}",
                method: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#createKategoriModal').modal('hide');
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

        // Edit Kategori
        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            $.ajax({
                url: `/admin/kategori/${id}/edit`,
                method: "GET",
                success: function(data) {
                    $('#editKategoriModal').modal('show');
                    $('#editKategoriForm #kategori_id').val(data.id);
                    $('#editKategoriForm #edit_name').val(data.name);
                    $('#editKategoriForm #edit_skpd_id').val(data.skpd_id);
                },
                error: function(xhr) {
                    toastr.error('Terjadi kesalahan saat mengambil data');
                }
            });
        });

        // Update Kategori
        $('#editKategoriForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let id = $('#kategori_id').val();
            $.ajax({
                url: `/admin/kategori/${id}`,
                method: "PUT",
                data: form.serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#editKategoriModal').modal('hide');
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

    // Delete Kategori with SweetAlert
    window.deleteKategori = function(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Kategori yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/kategori/${id}`,
                    type: 'DELETE',
                    data: { "_token": "{{ csrf_token() }}" },
                    success: function(response) {
                        $('#kategori-table').DataTable().ajax.reload();
                        Swal.fire(
                            'Terhapus!',
                            'Kategori berhasil dihapus.',
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