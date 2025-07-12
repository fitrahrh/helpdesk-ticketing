
@extends('layouts.app')

@section('content')
<section class="section">
    <div class="container-fluid">
        <div class="section-header">
            <h1>Kelola Bidang</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('/home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Kelola Bidang</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Bidang</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createBidangModal"><i class="fas fa-plus"></i> Tambah Bidang</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="bidang-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Bidang</th>
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

@include('layouts.admin.bidang.create')
@include('layouts.admin.bidang.edit')

@push('scripts')
<script>
    $(function() {
        let table = $('#bidang-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.bidang.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'skpd_name', name: 'skpd_name' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        // Save Bidang
        $('#createBidangForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            $.ajax({
                url: "{{ route('admin.bidang.store') }}",
                method: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#createBidangModal').modal('hide');
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

        // Edit Bidang
        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            $.ajax({
                url: `/admin/bidang/${id}/edit`,
                method: "GET",
                success: function(data) {
                    $('#editBidangModal').modal('show');
                    $('#editBidangForm #bidang_id').val(data.id);
                    $('#editBidangForm #edit_name').val(data.name);
                    $('#editBidangForm #edit_skpd_id').val(data.skpd_id);
                },
                error: function(xhr) {
                    toastr.error('Terjadi kesalahan saat mengambil data');
                }
            });
        });

        // Update Bidang
        $('#editBidangForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let id = $('#bidang_id').val();
            $.ajax({
                url: `/admin/bidang/${id}`,
                method: "PUT",
                data: form.serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#editBidangModal').modal('hide');
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

    // Populate SKPD options on page load
    $('#skpd_id').change(function() {
        var skpd_id = $(this).val();
        if (skpd_id) {
            $('#bidang_id').prop('disabled', false);

            // Ambil data bidang berdasarkan SKPD
            $.ajax({
                url: `/admin/get-bidang/${skpd_id}`,
                type: 'GET',
                success: function(data) {
                    console.log('Bidang data:', data); // Debugging
                    $('#bidang_id').empty().append('<option value="">Pilih Bidang</option>');
                    $.each(data, function(key, value) {
                        $('#bidang_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                },
                error: function(xhr) {
                    console.error('Error fetching bidang:', xhr.responseText); // Debugging
                }
            });
        } else {
            $('#bidang_id').prop('disabled', true).empty().append('<option value="">Pilih Bidang</option>');
            $('#jabatan_id').prop('disabled', true).empty().append('<option value="">Pilih Jabatan</option>');
        }
    });

    // Delete Bidang with SweetAlert
    window.deleteBidang = function(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Bidang yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/bidang/${id}`,
                    type: 'DELETE',
                    data: { "_token": "{{ csrf_token() }}" },
                    success: function(response) {
                        $('#bidang-table').DataTable().ajax.reload();
                        Swal.fire(
                            'Terhapus!',
                            'Bidang berhasil dihapus.',
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