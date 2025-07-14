@extends('layouts.app')

@section('content')
<section class="section">
    <div class="container-fluid">
        <div class="section-header">
            <h1>Laporan Harian</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('/home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Laporan Harian</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Cari Laporan</h4>
                        </div>
                        <div class="card-body">
                            <p>Pilih tanggal dan status untuk ditampilkan.</p>
                            <form id="filterForm" action="{{ route('admin.report.harian') }}" method="GET" class="row">
                                <div class="form-group col-md-3">
                                    <label>Tanggal</label>
                                    <input type="date" class="form-control" name="tanggal" id="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}">
                                </div>
                                
                                <div class="form-group col-md-3">
                                    <label>Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="">Semua Status</option>
                                        <option value="Baru" {{ request('status') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                        <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="Disposisi" {{ request('status') == 'Disposisi' ? 'selected' : '' }}>Disposisi</option>
                                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </div>
                                
                                <div class="form-group col-md-3">
                                    <label>Kategori</label>
                                    <select class="form-control" name="kategori_id" id="kategori_id">
                                        <option value="">Semua Kategori</option>
                                        @foreach($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                                {{ $kategori->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary mr-2">Tampilkan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="laporan-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No. Tiket</th>
                                            <th>Urgensi</th>
                                            <th>Kategori</th>
                                            <th>Judul</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tickets as $ticket)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $ticket->no_tiket }}</td>
                                                <td>
                                                    @php
                                                        $badgeClass = 'badge-secondary';
                                                        if($ticket->urgensi == 'Rendah') $badgeClass = 'badge-info';
                                                        elseif($ticket->urgensi == 'Sedang') $badgeClass = 'badge-success';
                                                        elseif($ticket->urgensi == 'Tinggi') $badgeClass = 'badge-warning';
                                                        elseif($ticket->urgensi == 'Mendesak') $badgeClass = 'badge-danger';
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }}">{{ $ticket->urgensi }}</span>
                                                </td>
                                                <td>{{ $ticket->kategori->name ?? '-' }}</td>
                                                <td>{{ $ticket->judul }}</td>
                                                <td>
                                                    @php
                                                        $statusClass = 'badge-secondary';
                                                        if($ticket->status == 'Baru') $statusClass = 'badge-warning';
                                                        elseif($ticket->status == 'Diproses') $statusClass = 'badge-info';
                                                        elseif($ticket->status == 'Disposisi') $statusClass = 'badge-danger';
                                                        elseif($ticket->status == 'Selesai') $statusClass = 'badge-success';
                                                    @endphp
                                                    <span class="badge {{ $statusClass }}">{{ $ticket->status }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
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
<!-- Library DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
    $(function() {
        $('#laporan-table').DataTable({
            "pageLength": 25,
            "language": {
                "search": "Pencarian:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data tersedia",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            },
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'csv',
                    text: '<i class="fas fa-file-csv"></i> CSV',
                    className: 'btn btn-sm btn-secondary'
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-sm btn-success'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-sm btn-danger'
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Cetak',
                    className: 'btn btn-sm btn-primary'
                }
            ]
        });
    });
</script>
@endpush
@endsection