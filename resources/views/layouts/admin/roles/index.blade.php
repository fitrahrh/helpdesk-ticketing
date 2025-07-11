@extends('layouts.app')

@section('title', 'Kelola Role')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Kelola Role</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ url('/home') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Kelola Role</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Daftar Role</h4>
                        <div class="card-header-action">
                            <a href="#" class="btn btn-icon btn-primary" id="btn-add"><i class="fas fa-plus"></i> Tambah Role</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="roles-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Hak Akses</th>
                                        <th>Action</th>
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

<!-- Include the create and edit modals -->
@include('layouts.admin.roles.create')
@include('layouts.admin.roles.edit')
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#roles-table').DataTable({
        responsive: true, 
        processing: true,
        autoWidth: false,
        paging: true,
        serverSide: true, 
        searching: true,
        ajax: "{{ url('/admin/dataroles') }}",
        columns: [
            { 
                data: null, 
                name: 'no', 
                orderable: false, 
                searchable: false, 
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1; // Generate iteration number
                }
            },
            { data: 'name', name: 'name' },
            { 
                data: 'hak_akses', 
                name: 'hak_akses',
                render: function(data, type, row) {
                    if (type === 'display') {
                        var html = '';
                        
                        // Check if data exists and handle different formats
                        if (data) {
                            // Handle if it's a JSON string
                            if (typeof data === 'string' && data.startsWith('[')) {
                                try {
                                    data = JSON.parse(data);
                                } catch(e) {
                                    // If parsing fails, split by comma
                                    data = data.replace(/[\[\]"']/g, '').split(',');
                                }
                            }
                            
                            // Handle if it's an object
                            if (typeof data === 'object' && !Array.isArray(data)) {
                                data = Object.values(data);
                            }
                            
                            // Ensure it's an array
                            if (!Array.isArray(data)) {
                                data = [data];
                            }
                            
                            // Generate badges for each permission
                            data.forEach(function(permission) {
                                if (permission && permission.trim() !== '') {
                                    html += '<span class="badge badge-light mr-1 mb-1" style="border: 1px solid #ccc; border-radius: 20px; padding: 5px 10px; background-color: #f8f9fa;">' + 
                                            permission.replace(/_/g, ' ') + '</span>';
                                }
                            });
                        }
                        
                        return html || '<span class="text-muted">-</span>';
                    }
                    return data;
                }
            },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        buttons: ['copy', 'excel', 'pdf', 'print'],
        lengthMenu: [10, 25, 50, 100], // Tambahkan opsi "Show entries"
        language: {
            processing: '<i class="fas fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>',
            zeroRecords: "Tidak ada data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            lengthMenu: "Tampilkan _MENU_ data per halaman" // Tambahkan teks untuk length menu
        }
    });

    // Open Create Modal
    $('#btn-add').click(function() {
        $('#roleForm').trigger("reset");
        $('#method').val('POST');
        $('#role_id').val('');
        $('#roleModalLabel').html("Tambah Role");
        $('#roleModal').modal('show');
        
        // Initialize Select2 with specific parent
        $('#hak_akses').select2({
            dropdownParent: $('#roleModal')
        });
    });
    
    // Create Role Form Submit
    $('#roleForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: "{{ route('admin.roles.store') }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#roleModal').modal('hide');
                table.ajax.reload();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    timer: 1500
                });
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                var errorMessage = '';
                
                $.each(errors, function(key, value) {
                    errorMessage += value + '<br>';
                });
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: errorMessage || 'Terjadi kesalahan pada sistem'
                });
            }
        });
    });
    
    // Handle "All" checkbox in create modal
    $('#all').change(function() {
        // When "All" is checked, check all other permissions
        $('.permission-checkbox').prop('checked', $(this).is(':checked'));
    });
    
    // Open Edit Modal
    $(document).on('click', '.btn-edit', function() {
        var id = $(this).data('id');
        
        $.ajax({
            url: '/admin/roles/' + id + '/edit',
            type: 'GET',
            success: function(data) {
                console.log('Retrieved role data:', data); // Debug data
                
                // Set form values
                $('#edit_role_id').val(data.id);
                $('#edit_name').val(data.name);
                
                // Reset all checkboxes
                $('.permission-checkbox').prop('checked', false);
                $('#edit_all').prop('checked', false);
                
                // Process hak_akses data
                let hakAkses = [];
                
                if (data.hak_akses) {
                    // Handle JSON string format
                    if (typeof data.hak_akses === 'string') {
                        try {
                            hakAkses = JSON.parse(data.hak_akses);
                        } catch (e) {
                            hakAkses = data.hak_akses.split(',').map(item => item.trim());
                        }
                    }
                    // Handle array format
                    else if (Array.isArray(data.hak_akses)) {
                        hakAkses = data.hak_akses;
                    }
                    // Handle object format
                    else if (typeof data.hak_akses === 'object') {
                        hakAkses = Object.values(data.hak_akses);
                    }
                }
                
                console.log('Processed hak_akses:', hakAkses); // Debug processed values
                
                // Check if 'all' permission is present
                if (hakAkses.includes('all')) {
                    $('#edit_all').prop('checked', true);
                    $('.permission-checkbox').prop('checked', true);
                } else {
                    // Check specific permissions
                    hakAkses.forEach(function(permission) {
                        // Convert permission value to valid ID by replacing spaces with underscores
                        var permissionId = 'edit_' + permission.replace(/ /g, '_');
                        $('#' + permissionId).prop('checked', true);
                    });
                }
                
                // Initialize Select2 before showing modal
                $('#edit_hak_akses').select2({
                    dropdownParent: $('#editRoleModal'),
                    width: '100%'
                });
                
                // Show modal after initialization
                $('#editRoleModal').modal('show');
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Tidak dapat mengambil data role'
                });
            }
        });
    });
    
    // Edit Role Form Submit
    $('#editRoleForm').submit(function(e) {
        e.preventDefault();
        var id = $('#edit_role_id').val();
        
        $.ajax({
            url: '/admin/roles/' + id,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                $('#editRoleModal').modal('hide');
                table.ajax.reload();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    timer: 1500
                });
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                var errorMessage = '';
                
                $.each(errors, function(key, value) {
                    errorMessage += value + '<br>';
                });
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: errorMessage || 'Terjadi kesalahan pada sistem'
                });
            }
        });
    });
    
    // Delete function with SweetAlert
    window.deleteRole = function(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Role yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/roles/' + id,
                    type: 'DELETE',
                    data: { "_token": "{{ csrf_token() }}" },
                    success: function(result) {
                        table.ajax.reload();
                        Swal.fire(
                            'Terhapus!',
                            'Role berhasil dihapus.',
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
    
    // Handle "All" checkbox in edit modal
    $('#edit_all').change(function() {
        // When "All" is checked, check all other permissions
        $('.permission-checkbox').prop('checked', $(this).is(':checked'));
    });
});
</script>
@endpush