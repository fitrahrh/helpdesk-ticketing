@extends('layouts.app')

@section('content')
<section class="section">
    <div class="container-fluid">
        <div class="section-header">
            <h1>Tiket Baru</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('/home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Tiket Baru</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Tiket Baru</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="ticket-baru-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Tiket</th>
                                            <th>Pelapor</th>
                                            <th>Kategori</th>
                                            <th>SKPD</th>
                                            <th>Judul</th>
                                            <th>Urgensi</th>
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

@push('scripts')
<script>
    $(function() {
        let table = $('#ticket-baru-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.baru.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { 
                    data: 'no_tiket', 
                    name: 'no_tiket',
                    render: function(data, type, row) {
                        return '<a href="' + "{{ route('ticket.ticket.show', '') }}" + '/' + row.id + '" class="text-primary fw-bold"><span class="text-secondary">#</span>' + data + '</a>';
                    }
                },
                { data: 'pelapor', name: 'pelapor' },
                { data: 'kategori', name: 'kategori' },
                { data: 'skpd', name: 'skpd' },
                { data: 'judul', name: 'judul' },
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
            ]
        });
    });
</script>
@endpush
@endsection