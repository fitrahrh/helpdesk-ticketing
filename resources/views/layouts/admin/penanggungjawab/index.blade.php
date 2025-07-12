@extends('layouts.app')

@section('content')
<section class="section">
    <div class="container-fluid">
        <div class="section-header">
            <h1>Kelola Penanggung Jawab</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('/home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Kelola Penanggung Jawab</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Penanggung Jawab</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createPenanggungjawabModal"><i class="fas fa-plus"></i> Tambah Penanggung Jawab</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="penanggungjawab-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Teknisi</th>
                                            <th>Kategori</th>
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

@include('layouts.admin.penanggungjawab.create')
@include('layouts.admin.penanggungjawab.edit')

@push('scripts')
<script>
    $(function() {
        let table = $('#penanggungjawab-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.penanggungjawab.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'user_name', name: 'user_name' },
                { data: 'kategori_name', name: 'kategori_name' },
                { data: 'skpd_name', name: 'skpd_name' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        // Save Penanggungjawab
        $('#createPenanggungjawabForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            $.ajax({
                url: "{{ route('admin.penanggungjawab.store') }}",
                method: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#createPenanggungjawabModal').modal('hide');
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

        // Edit Penanggungjawab
        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            $.ajax({
                url: `/admin/penanggungjawab/${id}/edit`,
                method: "GET",
                success: function(data) {
                    $('#editPenanggungjawabModal').modal('show');
                    $('#editPenanggungjawabForm #penanggungjawab_id').val(data.id);
                    $('#editPenanggungjawabForm #edit_user_id').val(data.user_id);
                    $('#editPenanggungjawabForm #edit_kategori_id').val(data.kategori_id);
                },
                error: function(xhr) {
                    toastr.error('Terjadi kesalahan saat mengambil data');
                }
            });
        });

        // Update Penanggungjawab
        $('#editPenanggungjawabForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let id = $('#penanggungjawab_id').val();
            $.ajax({
                url: `/admin/penanggungjawab/${id}`,
                method: "PUT",
                data: form.serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#editPenanggungjawabModal').modal('hide');
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

    // Delete Penanggungjawab with SweetAlert
    window.deletePenanggungjawab = function(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Penanggung jawab yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/penanggungjawab/${id}`,
                    type: 'DELETE',
                    data: { "_token": "{{ csrf_token() }}" },
                    success: function(response) {
                        $('#penanggungjawab-table').DataTable().ajax.reload();
                        Swal.fire(
                            'Terhapus!',
                            'Penanggung jawab berhasil dihapus.',
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