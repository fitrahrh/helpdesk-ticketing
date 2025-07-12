
@extends('layouts.app')

@section('content')
<section class="section">
    <div class="container-fluid">
        <div class="section-header">
            <h1>Riwayat Tiket</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('/home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Riwayat Tiket</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Riwayat Tiket</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="history-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Tiket</th>
                                            <th>Pelapor</th>
                                            <th>Status</th>
                                            <th>Nilai Lama</th>
                                            <th>Nilai Baru</th>
                                            <th>Keterangan</th>
                                            <th>Tanggal</th>
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
        let table = $('#history-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.history.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'no_tiket', name: 'no_tiket' },
                { data: 'pelapor', name: 'pelapor' },
                { 
                    data: 'status', 
                    name: 'status',
                    render: function(data) {
                        let badgeClass = 'badge-secondary';
                        
                        switch(data) {
                            case 'status_changed':
                                badgeClass = 'badge-info';
                                break;
                            case 'kategori_changed':
                                badgeClass = 'badge-warning';
                                break;
                            case 'comment':
                                badgeClass = 'badge-success';
                                break;
                        }
                        
                        return '<span class="badge ' + badgeClass + '">' + data + '</span>';
                    }
                },
                { 
                    data: 'old_values', 
                    name: 'old_values',
                    render: function(data) {
                        if (!data) return '-';
                        
                        let html = '';
                        try {
                            const values = typeof data === 'object' ? data : JSON.parse(data);
                            Object.entries(values).forEach(([key, value]) => {
                                html += `<div><strong>${key}:</strong> ${value}</div>`;
                            });
                        } catch (e) {
                            html = data;
                        }
                        return html || '-';
                    }
                },
                { 
                    data: 'new_values', 
                    name: 'new_values',
                    render: function(data) {
                        if (!data) return '-';
                        
                        let html = '';
                        try {
                            const values = typeof data === 'object' ? data : JSON.parse(data);
                            Object.entries(values).forEach(([key, value]) => {
                                html += `<div><strong>${key}:</strong> ${value}</div>`;
                            });
                        } catch (e) {
                            html = data;
                        }
                        return html || '-';
                    }
                },
                { data: 'keterangan', name: 'keterangan' },
                { 
                    data: 'created_at', 
                    name: 'created_at',
                    render: function(data) {
                        const date = new Date(data);
                        return date.toLocaleString('id-ID', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }
                }
            ],
            language: {
                processing: '<i class="fas fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>',
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
    });
</script>
@endpush
@endsection