@extends('layouts.app')

@section('content')
<section class="section">
    <div class="container-fluid">
        <div class="section-header">
            <h1>Tiket Disposisi</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('/home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Tiket Disposisi</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Tiket Salah Disposisi</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="ticket-disposisi-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Tiket</th>
                                            <th>Pelapor</th>
                                            <th>Kategori</th>
                                            <th>SKPD</th>
                                            <th>Judul</th>
                                            <th>Salah Disposisi Oleh</th>
                                            <th>Urgensi</th>
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

<!-- Modal Disposisi -->
<div class="modal fade" id="disposisiModal" tabindex="-1" role="dialog" aria-labelledby="disposisiModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="disposisiModalLabel">Disposisi Tiket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="disposisiForm">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="ticket_id" id="ticket_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kategori_id">Pilih Kategori Baru</label>
                        <select class="form-control" id="kategori_id" name="kategori_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->name }} ({{ $kategori->skpd ? $kategori->skpd->name : '-' }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Disposisi</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function() {
        let table = $('#ticket-disposisi-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.disposisi.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'no_tiket', name: 'no_tiket' },
                { data: 'pelapor', name: 'pelapor' },
                { data: 'kategori', name: 'kategori' },
                { data: 'skpd', name: 'skpd' },
                { data: 'judul', name: 'judul' },
                { data: 'disposisi_oleh', name: 'disposisi_oleh' },
                { 
                    data: 'urgensi', 
                    name: 'urgensi',
                    render: function(data) {
                        let badgeClass = 'badge-light';
                        
                        switch(data) {
                            case 'Rendah':
                                badgeClass = 'badge-info';
                                break;
                            case 'Sedang':
                                badgeClass = 'badge-success';
                                break;
                            case 'Tinggi':
                                badgeClass = 'badge-warning';
                                break;
                            case 'Mendesak':
                                badgeClass = 'badge-danger';
                                break;
                            case 'Standby':
                                badgeClass = 'badge-secondary';
                                break;
                        }
                        
                        return '<span class="badge ' + badgeClass + '">' + data + '</span>';
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
        
        // Handle Disposisi Button Click
        $(document).on('click', '.btn-disposisi', function() {
            let id = $(this).data('id');
            $('#ticket_id').val(id);
            $('#disposisiModal').modal('show');
        });
        
        // Handle Disposisi Form Submit
        $('#disposisiForm').on('submit', function(e) {
            e.preventDefault();
            let ticket_id = $('#ticket_id').val();
            
            $.ajax({
                url: `/admin/disposisi/${ticket_id}`,
                method: 'PUT',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#disposisiModal').modal('hide');
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
                        toastr.error('Terjadi kesalahan saat disposisi tiket');
                    }
                }
            });
        });
    });
</script>
@endpush
@endsection