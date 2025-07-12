@extends('layouts.user-layout')

@section('banner-content')
<div class="container mt-n3 position-relative"><br>
    <div class="row justify-content-center">
        <div class="col-md-3">
            @include('layouts.user.ticket.partials.status-nav')
        </div>
        <div class="col-md-9">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0 me-3"><i class="fa fa-exchange text-danger text-primary me-2"></i> Tiket Disposisi</h4>
                </div>
            </div>
            <hr>    
            <div class="card shadow-lg border-0 rounded-lg fade-in">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="disposisi-tickets-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Tiket</th>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Tanggal Disposisi</th>
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
@endsection

@section('scripts')
<script>
    $(function() {
        let table = $('#disposisi-tickets-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('ticket.disposisi.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'no_tiket', name: 'no_tiket' },
                { data: 'judul', name: 'judul' },
                { data: 'kategori', name: 'kategori' },
                { data: 'updated_at', name: 'updated_at' },
                { 
                    data: 'urgensi', 
                    name: 'urgensi',
                    render: function(data) {
                        let badgeClass = 'bg-secondary';
                        
                        switch(data) {
                            case 'Rendah':
                                badgeClass = 'bg-info';
                                break;
                            case 'Sedang':
                                badgeClass = 'bg-success';
                                break;
                            case 'Tinggi':
                                badgeClass = 'bg-warning';
                                break;
                            case 'Mendesak':
                                badgeClass = 'bg-danger';
                                break;
                            case 'Standby':
                                badgeClass = 'bg-secondary';
                                break;
                        }
                        
                        return '<span class="badge ' + badgeClass + '">' + data + '</span>';
                    }
                },
                { 
                    data: 'action', 
                    name: 'action', 
                    orderable: false, 
                    searchable: false,
                    render: function(data, type, row) {
                        return '<a href="/user/ticket/' + row.id + '" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Detail</a>';
                    }
                }
            ],
            language: {
                processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>',
                zeroRecords: "Tidak ada data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                lengthMenu: "Tampilkan _MENU_ data per halaman"
            }
        });
    });
</script>
@endsection