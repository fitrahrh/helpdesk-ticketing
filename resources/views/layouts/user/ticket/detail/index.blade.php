@extends('layouts.user-layout')

@section('banner-content')
<div class="container mt-n3 position-relative">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Ticket Details -->
            <div class="card shadow-lg border-0 rounded-lg fade-in mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Detail Tiket</h4>
                    <span class="badge 
                        @if($ticket->status == 'Baru') bg-warning
                        @elseif($ticket->status == 'Diproses') bg-primary
                        @elseif($ticket->status == 'Disposisi') bg-info
                        @elseif($ticket->status == 'Selesai') bg-success
                        @else bg-secondary @endif
                        fs-6">
                        {{ $ticket->status == 'Baru' ? 'Menunggu' : $ticket->status }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td style="width: 150px;"><strong>No Tiket</strong></td>
                                    <td>: {{ $ticket->no_tiket }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal</strong></td>
                                    <td>: {{ $ticket->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Pelapor</strong></td>
                                    <td>: {{ $ticket->user->first_name }} {{ $ticket->user->last_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kategori</strong></td>
                                    <td>: {{ $ticket->kategori->name ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td style="width: 150px;"><strong>SKPD</strong></td>
                                    <td>: {{ $ticket->kategori->skpd->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Urgensi</strong></td>
                                    <td>: <span class="badge 
                                        @if($ticket->urgensi == 'Rendah') bg-info
                                        @elseif($ticket->urgensi == 'Sedang') bg-success
                                        @elseif($ticket->urgensi == 'Tinggi') bg-warning
                                        @elseif($ticket->urgensi == 'Mendesak') bg-danger
                                        @else bg-secondary @endif">
                                        {{ $ticket->urgensi }}
                                        </span>
                                    </td>
                                </tr>
                                @if($ticket->status == 'Diproses')
                                <tr>
                                    <td><strong>Disetujui Oleh</strong></td>
                                    <td>: {{ $ticket->approvedBy->first_name ?? '-' }} {{ $ticket->approvedBy->last_name ?? '' }}</td>
                                </tr>
                                @endif
                                @if($ticket->status == 'Selesai')
                                <tr>
                                    <td><strong>Diselesaikan Oleh</strong></td>
                                    <td>: {{ $ticket->closedBy->first_name ?? '-' }} {{ $ticket->closedBy->last_name ?? '' }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="mb-3">{{ $ticket->judul }}</h5>
                        <div class="bg-light p-3 rounded">
                            {!! $ticket->deskripsi !!}
                        </div>
                    </div>
                    
                    @if($ticket->lampiran)
                    <div class="mb-4">
                        <h6>Lampiran:</h6>
                        <div class="d-flex align-items-center">
                            <i class="fa fa-file-o fa-2x me-2"></i>
                            <a href="{{ asset('storage/'.$ticket->lampiran) }}" target="_blank" class="text-decoration-none">
                                {{ basename($ticket->lampiran) }}
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Comments -->
            <div class="card shadow border-0 rounded-lg fade-in-delay mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Riwayat Aktivitas</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @forelse($histories as $history)
                        <div class="timeline-item mb-4 pb-4 border-bottom">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-sm bg-light rounded-circle p-2">
                                        <i class="fa fa-user text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex align-items-center mb-1">
                                        <strong>{{ $history->user->first_name }} {{ $history->user->last_name }}</strong>
                                        <small class="text-muted ms-2">{{ $history->created_at->format('d M Y H:i') }}</small>
                                    </div>
                                    
                                    @if($history->status == 'status_changed')
                                        <p class="mb-0">
                                            Mengubah status dari 
                                            <span class="badge bg-secondary">{{ $history->old_values['status'] ?? '-' }}</span>
                                            menjadi
                                            <span class="badge 
                                                @if(($history->new_values['status'] ?? '') == 'Baru') bg-warning
                                                @elseif(($history->new_values['status'] ?? '') == 'Diproses') bg-primary
                                                @elseif(($history->new_values['status'] ?? '') == 'Disposisi') bg-info
                                                @elseif(($history->new_values['status'] ?? '') == 'Selesai') bg-success
                                                @else bg-secondary @endif">
                                                {{ $history->new_values['status'] ?? '-' }}
                                            </span>
                                        </p>
                                        @if($history->keterangan)
                                            <p class="mb-0 text-muted">{{ $history->keterangan }}</p>
                                        @endif
                                    @elseif($history->status == 'kategori_changed')
                                        <p class="mb-0">
                                            Tiket telah didisposisi ke kategori baru
                                            @if(isset($history->new_values['kategori_id']))
                                                @php
                                                    $kategori = \App\Models\Kategori::find($history->new_values['kategori_id']);
                                                @endphp
                                                <span class="badge bg-info">{{ $kategori->name ?? '-' }}</span>
                                            @endif
                                        </p>
                                    @elseif($history->status == 'comment')
                                        <div class="bg-light p-3 rounded">
                                            {!! $history->keterangan !!}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <p class="text-muted">Belum ada aktivitas</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Add Comment -->
            @if($ticket->status != 'Selesai')
            <div class="card shadow border-0 rounded-lg fade-in-delay" style="animation-delay: 0.6s;">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Tambahkan Komentar</h5>
                </div>
                <div class="card-body">
                    <form id="commentForm" action="{{ route('user.ticket.comment', $ticket->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea class="form-control summernote" id="komentar" name="komentar" rows="4"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-paper-plane"></i> Kirim Komentar
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(function() {
        // Initialize Summernote
        $('.summernote').summernote({
            height: 150,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough']],
                ['para', ['ul', 'ol']],
                ['insert', ['link']]
            ]
        });
        
        // Handle comment submission
        $('#commentForm').submit(function(e) {
            e.preventDefault();
            
            let form = $(this);
            let formData = form.serialize();
            
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: formData,
                beforeSend: function() {
                    form.find('button[type="submit"]').html('<i class="fa fa-spinner fa-spin"></i> Mengirim...').attr('disabled', true);
                },
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Komentar berhasil ditambahkan',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Terjadi kesalahan saat menambahkan komentar'
                        });
                    }
                    form.find('button[type="submit"]').html('<i class="fa fa-paper-plane"></i> Kirim Komentar').attr('disabled', false);
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON?.errors;
                    let errorMessage = 'Terjadi kesalahan saat menambahkan komentar';
                    
                    if (errors) {
                        errorMessage = Object.values(errors).flat().join('<br>');
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errorMessage
                    });
                    form.find('button[type="submit"]').html('<i class="fa fa-paper-plane"></i> Kirim Komentar').attr('disabled', false);
                }
            });
        });
    });
</script>
@endsection