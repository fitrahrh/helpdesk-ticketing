@extends('layouts.app')

@push('styles')
<style>
.is-loading {
    position: relative;
    pointer-events: none;
}
.is-loading::after {
    content: "";
    position: absolute;
    right: 10px;
    top: 50%;
    width: 20px;
    height: 20px;
    margin-top: -10px;
    border: 2px solid rgba(0,0,0,0.2);
    border-top-color: #007bff;
    border-radius: 50%;
    animation: spinner 0.6s linear infinite;
}
@keyframes spinner {
    to {transform: rotate(360deg);}
}
</style>
@endpush

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
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createUserModal">
                                    <i class="fas fa-plus"></i> Tambah User
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="users-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>NIP</th>
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
    </div>
</section>

@include('layouts.admin.users.create')
@include('layouts.admin.users.edit')

@endsection

@push('scripts')
<script>
    $(function () {
        // Initialize DataTable
        let table = $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.users.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'nip', name: 'nip' },
                { data: 'email', name: 'email' },
                { data: 'role.name', name: 'role.name' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        // Handle Create User
        $('#createUserForm').on('submit', function (e) {
            e.preventDefault();
            let form = $(this);

            $.ajax({
                url: "{{ route('admin.users.store') }}",
                method: "POST",
                data: form.serialize(),
                success: function (response) {
                    if (response.status) {
                        $('#createUserModal').modal('hide');
                        form[0].reset();
                        table.ajax.reload();
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function (xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('Terjadi kesalahan saat menyimpan data');
                    }
                }
            });
        });

        // Handle Edit User
        $(document).on('click', '.btn-edit', function () {
            let id = $(this).data('id');

            $.ajax({
                url: `/admin/users/${id}/edit`,
                method: "GET",
                success: function (data) {
                    console.log("User data retrieved:", data);
                    
                    $('#editUserModal').modal('show');
                    $('#editUserForm #user_id').val(data.id);
                    $('#editUserForm #edit_first_name').val(data.first_name);
                    $('#editUserForm #edit_last_name').val(data.last_name);
                    $('#editUserForm #edit_email').val(data.email);
                    $('#editUserForm #edit_role_id').val(data.role_id);
                    $('#editUserForm #edit_nip').val(data.nip);
                    $('#editUserForm #edit_no_hp').val(data.no_hp);
                    $('#editUserForm #edit_telegram_id').val(data.telegram_id);
                    
                    // Reset form dropdowns
                    $('#edit_skpd_id').val('').trigger('change');
                    $('#edit_bidang_id').prop('disabled', true).empty().append('<option value="">Pilih Bidang</option>');
                    $('#edit_jabatan_id').prop('disabled', true).empty().append('<option value="">Pilih Jabatan</option>');
                    
                    // Jika user memiliki jabatan
                    if (data.jabatan_id) {
                        // Get jabatan details through additional request
                        $.ajax({
                            url: `/admin/get-jabatan-info/${data.jabatan_id}`,
                            type: 'GET',
                            beforeSend: function() {
                                // Tambahkan loading indicator jika perlu
                                $('#edit_skpd_id').addClass('is-loading');
                                $('#edit_bidang_id').addClass('is-loading');
                                $('#edit_jabatan_id').addClass('is-loading');
                            },
                            success: function(jabatanInfo) {
                                console.log("Jabatan info retrieved:", jabatanInfo);
                                
                                // Set SKPD first (without trigger change)
                                $('#edit_skpd_id').val(jabatanInfo.bidang.skpd_id);
                                
                                // Chaining promises untuk mengisi dropdown secara berurutan
                                getBidangBySkpd(jabatanInfo.bidang.skpd_id)
                                    .then(() => {
                                        // Tambahkan trigger 'change' untuk memperbarui Select2
                                        $('#edit_bidang_id').val(jabatanInfo.bidang_id).trigger('change');
                                        return getJabatanByBidang(jabatanInfo.bidang_id);
                                    })
                                    .then(() => {
                                        // Tambahkan trigger 'change' untuk memperbarui Select2
                                        $('#edit_jabatan_id').val(jabatanInfo.id).trigger('change');
                                        
                                        // Hapus loading indicator
                                        $('#edit_skpd_id').removeClass('is-loading');
                                        $('#edit_bidang_id').removeClass('is-loading');
                                        $('#edit_jabatan_id').removeClass('is-loading');
                                    })
                                    .catch(error => {
                                        console.error("Error in cascading dropdowns:", error);
                                        toastr.error('Gagal memuat data jabatan');
                                        
                                        // Hapus loading indicator jika terjadi error
                                        $('#edit_skpd_id').removeClass('is-loading');
                                        $('#edit_bidang_id').removeClass('is-loading');
                                        $('#edit_jabatan_id').removeClass('is-loading');
                                    });
                            },
                            error: function(xhr) {
                                console.error("Error fetching jabatan info:", xhr);
                                toastr.error('Gagal memuat informasi jabatan');
                                
                                // Hapus loading indicator jika terjadi error
                                $('#edit_skpd_id').removeClass('is-loading');
                                $('#edit_bidang_id').removeClass('is-loading');
                                $('#edit_jabatan_id').removeClass('is-loading');
                            }
                        });
                    }
                },
                error: function () {
                    toastr.error('Terjadi kesalahan saat mengambil data');
                }
            });
        });

        // Handle Update User
        $('#editUserForm').on('submit', function (e) {
            e.preventDefault();
            let form = $(this);
            let id = $('#user_id').val();

            $.ajax({
                url: `/admin/users/${id}`,
                method: "PUT",
                data: form.serialize(),
                success: function (response) {
                    if (response.status) {
                        $('#editUserModal').modal('hide');
                        table.ajax.reload();
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function (xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('Terjadi kesalahan saat memperbarui data');
                    }
                }
            });
        });

        // Handle Delete User
        window.deleteUser = function (id) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: "Apakah Anda yakin ingin menghapus user ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/users/${id}`,
                        method: "DELETE",
                        data: { "_token": "{{ csrf_token() }}" }, // Tambahkan CSRF token jika diperlukan
                        success: function (response) {
                            if (response.status) {
                                $('#users-table').DataTable().ajax.reload();
                                toastr.success('✔ Data berhasil disimpan');
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function (xhr) {
                            toastr.error('Terjadi kesalahan saat menghapus data');
                        }
                    });
                }
            });
        };
    });

    $(document).ready(function() {
        // Hapus duplikasi ini
        // $('.select2').select2({
        //     dropdownParent: $('#createUserModal')
        // });
        
        // Gunakan inisialisasi yang lebih spesifik
        $('#createUserModal .select2').select2({
            dropdownParent: $('#createUserModal')
        });
        
        $('#editUserModal .select2').select2({
            dropdownParent: $('#editUserModal')
        });
        
        // SKPD -> Bidang in create form
        $(document).on('change', '#skpd_id', function() {
            var skpd_id = $(this).val();
            console.log("SKPD changed to: " + skpd_id);
            
            if (skpd_id) {
                $.ajax({
                    url: `/admin/get-bidang/${skpd_id}`,
                    type: 'GET',
                    beforeSend: function() {
                        console.log("Fetching bidang data for SKPD ID: " + skpd_id);
                    },
                    success: function(data) {
                        console.log("Bidang data received:", data);
                        $('#create_bidang_id').empty().append('<option value="">Pilih Bidang</option>');
                        $('#create_bidang_id').prop('disabled', false);
                        
                        $.each(data, function(key, value) {
                            $('#create_bidang_id').append(`<option value="${value.id}">${value.name}</option>`);
                        });
                        
                        // Refresh Select2
                        $('#create_bidang_id').select2({
                            dropdownParent: $('#createUserModal')
                        });
                    },
                    error: function(xhr) {
                        console.error("Error fetching bidang:", xhr);
                    }
                });
            } else {
                $('#create_bidang_id').prop('disabled', true).empty().append('<option value="">Pilih Bidang</option>');
                $('#create_jabatan_id').prop('disabled', true).empty().append('<option value="">Pilih Jabatan</option>');
            }
        });
        
        // Bidang -> Jabatan in create form
        $(document).on('change', '#create_bidang_id', function() {
            var bidang_id = $(this).val();
            console.log("Bidang changed to: " + bidang_id);
            
            if (bidang_id) {
                $.ajax({
                    url: `/admin/get-jabatan/${bidang_id}`,
                    type: 'GET',
                    beforeSend: function() {
                        console.log("Fetching jabatan data for Bidang ID: " + bidang_id);
                    },
                    success: function(data) {
                        console.log("Jabatan data received:", data);
                        $('#create_jabatan_id').empty().append('<option value="">Pilih Jabatan</option>');
                        $('#create_jabatan_id').prop('disabled', false);
                        
                        $.each(data, function(key, value) {
                            $('#create_jabatan_id').append(`<option value="${value.id}">${value.name}</option>`);
                        });
                        
                        // Refresh Select2
                        $('#create_jabatan_id').select2({
                            dropdownParent: $('#createUserModal')
                        });
                    },
                    error: function(xhr) {
                        console.error("Error fetching jabatan:", xhr);
                    }
                });
            } else {
                $('#create_jabatan_id').prop('disabled', true).empty().append('<option value="">Pilih Jabatan</option>');
            }
        });
        

        // Similar handlers for edit form
        $(document).on('change', '#edit_skpd_id', function() {
            var skpd_id = $(this).val();
            console.log("Edit SKPD changed to: " + skpd_id);
            
            if (skpd_id) {
                // Panggil fungsi untuk mendapatkan bidang
                getBidangBySkpd(skpd_id)
                    .then(function() {
                        // Bidang berhasil dimuat, sekarang kita bisa cek jabatan
                        var bidang_id = $('#edit_bidang_id').val();
                        if (bidang_id) {
                            // Panggil fungsi untuk mendapatkan jabatan
                            getJabatanByBidang(bidang_id);
                        }
                    })
                    .catch(function(error) {
                        console.error("Error in fetching bidang for edit:", error);
                    });
            } else {
                $('#edit_bidang_id').prop('disabled', true).empty().append('<option value="">Pilih Bidang</option>');
                $('#edit_jabatan_id').prop('disabled', true).empty().append('<option value="">Pilih Jabatan</option>');
            }
        });

        // Bidang -> Jabatan di form edit
        $(document).on('change', '#edit_bidang_id', function() {
            var bidang_id = $(this).val();
            console.log("Edit Bidang changed to: " + bidang_id);
            
            if (bidang_id) {
                // Panggil fungsi untuk mendapatkan jabatan
                getJabatanByBidang(bidang_id);
            } else {
                $('#edit_jabatan_id').prop('disabled', true).empty().append('<option value="">Pilih Jabatan</option>');
            }
        });
    });

    // Fungsi untuk mendapatkan data bidang berdasarkan SKPD dengan Promise
    function getBidangBySkpd(skpdId) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: `/admin/get-bidang/${skpdId}`,
                type: 'GET',
                beforeSend: function() {
                    console.log("Fetching bidang data for SKPD ID: " + skpdId);
                },
                success: function(data) {
                    console.log("Bidang data received:", data);
                    $('#edit_bidang_id').empty().append('<option value="">Pilih Bidang</option>');
                    $('#edit_bidang_id').prop('disabled', false);
                    
                    $.each(data, function(key, value) {
                        $('#edit_bidang_id').append(`<option value="${value.id}">${value.name}</option>`);
                    });
                    
                    // Refresh Select2
                    $('#edit_bidang_id').select2({
                        dropdownParent: $('#editUserModal')
                    });
                    resolve(data);
                },
                error: function(xhr) {
                    console.error("Error fetching bidang:", xhr);
                    reject(xhr);
                }
            });
        });
    }

    // Fungsi untuk mendapatkan data jabatan berdasarkan bidang dengan Promise
    function getJabatanByBidang(bidangId) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: `/admin/get-jabatan/${bidangId}`,
                type: 'GET',
                beforeSend: function() {
                    console.log("Fetching jabatan data for Bidang ID: " + bidangId);
                },
                success: function(data) {
                    console.log("Jabatan data received:", data);
                    $('#edit_jabatan_id').empty().append('<option value="">Pilih Jabatan</option>');
                    $('#edit_jabatan_id').prop('disabled', false);
                    
                    $.each(data, function(key, value) {
                        $('#edit_jabatan_id').append(`<option value="${value.id}">${value.name}</option>`);
                    });
                    
                    // Refresh Select2 dengan timeout
                    setTimeout(() => {
                        $('#edit_jabatan_id').select2({
                            dropdownParent: $('#editUserModal')
                        });
                        resolve(data);
                    }, 50);
                },
                error: function(xhr) {
                    console.error("Error fetching jabatan:", xhr);
                    reject(xhr);
                }
            });
        });
    }
</script>
@endpush