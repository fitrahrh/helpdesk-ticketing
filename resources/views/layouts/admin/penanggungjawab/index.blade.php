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
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>Filter Penanggung Jawab</h4>
                        </div>
                        <div class="card-body">
                            <form id="filterForm" class="row">
                                <!-- Filter SKPD -->
                                <div class="form-group col-md-3">
                                    <label for="filter_skpd">SKPD</label>
                                    <select class="form-control select2" id="filter_skpd" name="filter_skpd">
                                        <option value="">Semua SKPD</option>
                                        @foreach($skpds as $skpd)
                                            <option value="{{ $skpd->id }}">{{ $skpd->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Filter Kategori -->
                                <div class="form-group col-md-3">
                                    <label for="filter_kategori">Kategori</label>
                                    <select class="form-control select2" id="filter_kategori" name="filter_kategori" disabled>
                                        <option value="">Semua Kategori</option>
                                    </select>
                                </div>
                                
                                <!-- Filter Teknisi -->
                                <div class="form-group col-md-3">
                                    <label for="filter_teknisi">Teknisi</label>
                                    <select class="form-control select2" id="filter_teknisi" name="filter_teknisi">
                                        <option value="">Semua Teknisi</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group col-md-3 d-flex align-items-end">
                                    <button type="button" id="btn-filter" class="btn btn-primary mr-2">Terapkan Filter</button>
                                    <button type="button" id="btn-reset" class="btn btn-secondary">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Penanggung Jawab</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createPenanggungjawabModal">
                                    <i class="fas fa-plus"></i> Tambah Penanggung Jawab
                                </button>
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
        // Initialize Select2
        $('.select2').select2();
        
        // Inisialisasi DataTable sekali saja dengan semua konfigurasi yang dibutuhkan
        let table = $('#penanggungjawab-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.penanggungjawab.data') }}",
                data: function(d) {
                    // Add filter parameters
                    d.filter_skpd = $('#filter_skpd').val();
                    d.filter_kategori = $('#filter_kategori').val();
                    d.filter_teknisi = $('#filter_teknisi').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'user_name', name: 'user_name' },
                { data: 'kategori_name', name: 'kategori_name' },
                { data: 'skpd_name', name: 'skpd_name' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>',
                zeroRecords: "Tidak ada data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                search: "Cari:",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });
        
        // Apply filter when button is clicked
        $('#btn-filter').click(function() {
            table.ajax.reload();
        });
        
        // Reset filter
        $('#btn-reset').click(function() {
            $('#filter_skpd').val('').trigger('change');
            $('#filter_kategori').val('').prop('disabled', true).trigger('change');
            $('#filter_teknisi').val('').trigger('change');
            table.ajax.reload();
        });
        
        // Handle SKPD dropdown change to load related categories
        $('#filter_skpd').change(function() {
            let skpdId = $(this).val();
            
            if (skpdId) {
                $.ajax({
                    url: `/admin/get-kategori-by-skpd/${skpdId}`,
                    type: 'GET',
                    success: function(data) {
                        $('#filter_kategori').prop('disabled', false);
                        $('#filter_kategori').empty().append('<option value="">Semua Kategori</option>');
                        
                        $.each(data, function(key, value) {
                            $('#filter_kategori').append(`<option value="${value.id}">${value.name}</option>`);
                        });
                    }
                });
            } else {
                $('#filter_kategori').val('').prop('disabled', true).trigger('change');
            }
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
                    
                    // Set nilai dropdown dan trigger change event untuk memperbarui Select2
                    $('#editPenanggungjawabForm #edit_user_id').val(data.user_id).trigger('change');
                    $('#editPenanggungjawabForm #edit_kategori_id').val(data.kategori_id).trigger('change');
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