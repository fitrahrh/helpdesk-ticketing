@extends('layouts.user-layout')

@section('content')
<div class="pt-5"></div>
<div class="container position-relative">
    <div class="row justify-content-center">
        <div class="col-md-3">
            @include('layouts.teknisi.partials.status-nav')
        </div>
        <div class="col-md-9">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h5><i class="fa fa-folder-open"></i> Tiket Proses</h5>
                </div>
            </div>
            <hr>
            <div class="card border-0 rounded-lg fade-in">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="diproses-tickets-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Tiket</th>
                                    <th>Pelapor</th>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Disetujui Oleh</th>
                                    <th>Tanggal Persetujuan</th>
                                    <th>Urgensi</th>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof $ !== 'undefined') {
            initDataTable();
        } else {
            console.error('jQuery is not loaded!');
        }
    });

    function initDataTable() {
        let table = $('#diproses-tickets-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('teknisi.diproses.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { 
                    data: 'no_tiket', 
                    name: 'no_tiket',
                    render: function(data, type, row) {
                        return '<a href="' + "{{ route('teknisi.ticket.show', '') }}" + '/' + row.id + '" class="text-primary fw-bold"><span class="text-secondary">#</span>' + data + '</a>';
                    }
                },
                { data: 'pelapor', name: 'pelapor' },
                { data: 'judul', name: 'judul' },
                { data: 'kategori', name: 'kategori' },
                { data: 'disetujui_oleh', name: 'disetujui_oleh' },
                { data: 'approved_at', name: 'approved_at' },
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
                            default:
                                badgeClass = 'badge-secondary';
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
                        return '<a href="' + "{{ route('teknisi.ticket.show', '') }}" + '/' + row.id + '" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Detail</a>';
                    }
                }
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
    }
</script>
@endpush
@endsection