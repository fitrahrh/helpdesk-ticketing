@extends('layouts.user-layout')

@section('content')
<div class="pt-5"></div>
<div class="container position-relative">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h5>Teknisi Ticket Status</h5>
                </div>
            </div>
                <hr>
                <div class="h-100 mb-4">
                    <div class="list-group list-group-flush">
                        <!-- Pending Status -->
                        <a href="{{ route('teknisi.baru') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->routeIs('teknisi.baru') || (isset($ticket) && request()->routeIs('teknisi.ticket.show') && $ticket->status == 'Baru') ? 'active bg-light' : '' }}">
                            <div>
                                <i class="fas fa-envelope me-2 text-dark"></i> 
                                <span class="{{ request()->routeIs('teknisi.baru') || (isset($ticket) && request()->routeIs('teknisi.ticket.show') && $ticket->status == 'Baru') ? 'fw-bold' : '' }}">Tiket Baru</span>
                            </div>
                            <span class="badge {{ request()->routeIs('teknisi.baru') || (isset($ticket) && request()->routeIs('teknisi.ticket.show') && $ticket->status == 'Baru') ? 'bg-light text-dark' : 'bg-light' }} rounded-pill ticket-circle">
                                <!-- Mengambil jumlah tiket baru yang ditugaskan ke kategori yang ditangani teknisi ini -->
                                {{ \App\Models\Ticket::whereIn('kategori_id', Auth::user()->penanggungjawabs()->pluck('kategori_id'))->where('status', 'Baru')->count() }}
                            </span>
                        </a>
                        
                        <a href="{{ route('teknisi.diproses') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->routeIs('teknisi.diproses') || (isset($ticket) && request()->routeIs('teknisi.ticket.show') && $ticket->status == 'Diproses') ? 'active bg-light' : '' }}">
                            <div>
                                <i class="fas fa-spinner me-2 text-dark"></i> 
                                <span class="{{ request()->routeIs('teknisi.diproses') || (isset($ticket) && request()->routeIs('teknisi.ticket.show') && $ticket->status == 'Diproses') ? 'fw-bold' : '' }}">Diproses</span>
                            </div>
                            <span class="badge {{ request()->routeIs('teknisi.diproses') || (isset($ticket) && request()->routeIs('teknisi.ticket.show') && $ticket->status == 'Diproses') ? 'bg-light' : 'bg-light' }} rounded-pill ticket-circle">
                                {{ \App\Models\Ticket::whereIn('kategori_id', Auth::user()->penanggungjawabs()->pluck('kategori_id'))->where('status', 'Diproses')->count() }}
                            </span>
                        </a>
                        
                        <a href="{{ route('teknisi.selesai') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request()->routeIs('teknisi.selesai') || (isset($ticket) && request()->routeIs('teknisi.ticket.show') && $ticket->status == 'Selesai') ? 'active bg-light' : '' }}">
                            <div>
                                <i class="fas fa-check me-2 text-dark"></i> 
                                <span class="{{ request()->routeIs('teknisi.selesai') || (isset($ticket) && request()->routeIs('teknisi.ticket.show') && $ticket->status == 'Selesai') ? 'fw-bold' : '' }}">Selesai</span>
                            </div>
                            <span class="badge {{ request()->routeIs('teknisi.selesai') || (isset($ticket) && request()->routeIs('teknisi.ticket.show') && $ticket->status == 'Selesai') ? 'bg-light' : 'bg-light' }} rounded-pill ticket-circle">
                                {{ \App\Models\Ticket::whereIn('kategori_id', Auth::user()->penanggungjawabs()->pluck('kategori_id'))->where('status', 'Selesai')->count() }}
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        
        <!-- Main Content -->
        <div class="col-md-9">
            <div class="d-flex align-items-center justify-content-between mb-3"> <!-- Added mb-3 for spacing -->
                <div class="d-flex align-items-center">
                    <span class="fs-5">
                        <h5><i class="fa fa-folder-open"></i> Detail Tiket #{{ $ticket->no_tiket }}</h5>
                    </span>
                </div>
                    <div>
                        @php
                            $statusIcon = 'clock-o';
                            $statusColor = 'warning';
                            $statusText = 'Baru';
                            
                            if($ticket->status == 'Diproses') {
                                $statusIcon = 'spinner fa-spin';
                                $statusColor = 'info';
                                $statusText = 'Diproses';
                            } elseif($ticket->status == 'Disposisi') {
                                $statusIcon = 'exchange';
                                $statusColor = 'danger';
                                $statusText = 'Disposisi';
                            } elseif($ticket->status == 'Selesai') {
                                $statusIcon = 'check';
                                $statusColor = 'success';
                                $statusText = 'Selesai';
                            }
                        @endphp
                        
                        <button type="button" class="btn btn-{{ $statusColor }} btn-sm" disabled>
                            <i class="fa fa-{{ $statusIcon }} me-1"></i> Status: {{ $statusText }}
                        </button>
                        
                        @if($ticket->status == 'Baru')
                        <button type="button" class="btn btn-success btn-sm ms-2" data-toggle="modal" data-target="#approveModal">
                            <i class="fa fa-check-circle me-1"></i> Setujui & Proses
                        </button>
                        
                        <button type="button" class="btn btn-danger btn-sm ms-2" data-toggle="modal" data-target="#disposisiModal">
                            <i class="fa fa-exchange-alt me-1"></i> Disposisi
                        </button>
                        @endif
                        
                        @if($ticket->status == 'Diproses')
                        <button type="button" class="btn btn-warning btn-sm ms-2" data-toggle="modal" data-target="#urgensiModal">
                            <i class="fa fa-exclamation-triangle me-1"></i> Ubah Urgensi
                        </button>
                        
                        <button type="button" class="btn btn-danger btn-sm ms-2" data-toggle="modal" data-target="#disposisiModal">
                            <i class="fa fa-exchange-alt me-1"></i> Disposisi
                        </button>
                        
                        <button type="button" class="btn btn-success btn-sm ms-2" data-toggle="modal" data-target="#closeModal">
                            <i class="fa fa-check me-1"></i> Tandai Selesai?
                        </button>
                        @endif
                    </div>
                </div>
                
                <hr>
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="me-4" style="margin-right: 15px !important;">
                            <div class="bg-primary d-flex align-items-center justify-content-center text-white" style="width:60px;height:60px;font-size:1.5rem;">
                                <span>{{ substr($ticket->user->first_name, 0, 1) }}{{ substr($ticket->user->last_name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div>
                            <div class="fw-bold fs-5">{{ $ticket->user->first_name }} {{ $ticket->user->last_name }}</div>
                            <div class="text-muted">{{ $ticket->kategori && $ticket->kategori->skpd ? $ticket->kategori->skpd->name : '-' }}</div>
                        @php
                            $urgensiColor = 'secondary';
                            if($ticket->urgensi == 'Rendah') $urgensiColor = 'info';
                            elseif($ticket->urgensi == 'Sedang') $urgensiColor = 'success';
                            elseif($ticket->urgensi == 'Tinggi') $urgensiColor = 'warning';
                            elseif($ticket->urgensi == 'Mendesak') $urgensiColor = 'danger';
                        @endphp
                        
                        <span class="badge bg-{{ $urgensiColor }} me-2 mb-2">Urgensi: {{ $ticket->urgensi }}</span>
                        <span class="badge bg-dark text-light me-2 mb-2">Kategori: {{ $ticket->kategori->name }}</span>
                        <span class="badge bg-secondary me-2 mb-2">Dibuat: {{ $ticket->created_at->format('d M Y H:i') }}</span>
                        
                        @if($ticket->approved_at)
                        <span class="badge bg-info me-2 mb-2">Diproses: {{ \Carbon\Carbon::parse($ticket->approved_at)->format('d M Y H:i') }}</span>
                        @endif
                        
                        @if($ticket->closed_at)
                        <span class="badge bg-success me-2 mb-2">Diselesaikan: {{ \Carbon\Carbon::parse($ticket->closed_at)->format('d M Y H:i') }}</span>
                        @endif
                        </div>
                    </div>
                    <hr>
                    
                    <!-- Konten Tiket -->
                    <h4>{{ $ticket->judul }}</h4>
                    
                    <div class="ticket-content my-3">
                        {!! $ticket->masalah !!}
                    </div>
                    
                    <!-- File Pendukung Section -->
                    @if($ticket->lampiran)
                    <div class="mt-4 mb-3">
                        <h5 class="mb-2"><i class="fa fa-paperclip text-primary"></i> File Pendukung</h5>
                        <div class="ps-3 border-start border-primary border-3">
                            <div class="list-group">
                                @if(is_array($ticket->lampiran))
                                    @foreach($ticket->lampiran as $attachment)
                                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center bg-light border-0 rounded mb-2">
                                            <div>
                                                <span class="fw-semibold">Lampiran {{ $loop->iteration }}</span>
                                            </div>
                                            <div>
                                                <a href="{{ Storage::url($attachment) }}" class="btn btn-sm btn-outline-primary me-1" target="_blank">
                                                    <i class="fa fa-eye me-1"></i> Lihat
                                                </a>
                                                <a href="{{ Storage::url($attachment) }}" class="btn btn-sm btn-outline-success" download>
                                                    <i class="fa fa-download me-1"></i> Unduh
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center bg-light border-0 rounded mb-2">
                                        <div>
                                            <span class="fw-semibold">Lampiran</span>
                                        </div>
                                        <div>
                                            <a href="{{ Storage::url($ticket->lampiran) }}" class="btn btn-sm btn-outline-primary me-1" target="_blank">
                                                <i class="fa fa-eye me-1"></i> Lihat
                                            </a>
                                            <a href="{{ Storage::url($ticket->lampiran) }}" class="btn btn-sm btn-outline-success" download>
                                                <i class="fa fa-download me-1"></i> Unduh
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Komentar Section -->
                    <h5 class="mt-4">Komentar ({{ $ticket->comments->count() }})</h5>
                    
                    @forelse($ticket->comments->sortBy('created_at') as $comment)
                    <hr>
                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <div class="{{ $comment->user_id == Auth::id() ? 'bg-primary' : 'bg-secondary' }} d-flex align-items-center justify-content-center text-white" style="width:40px;height:40px">
                                <span>{{ substr($comment->user->first_name, 0, 1) }}{{ substr($comment->user->last_name, 0, 1) }}</span>
                                
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="card shadow-sm border-0 {{ $comment->readBy->contains(Auth::id()) ? '' : 'border-start border-danger border-3' }}">
                                <div class="card-header bg-white py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <span class="fw-bold">
                                                {{ $comment->user ? $comment->user->first_name.' '.$comment->user->last_name : 'Unknown User' }}
                                                @php
                                                    $roleName = $comment->user->id == $ticket->user_id ? 'Pelapor' : ($comment->user->role ? $comment->user->role->name : 'User');
                                                    $badgeColor = 'secondary'; // default color
                                                    
                                                    // Set badge color based on role
                                                    if ($roleName == 'Pelapor') {
                                                        $badgeColor = 'light';
                                                    } elseif ($roleName == 'Teknisi') {
                                                        $badgeColor = 'warning';
                                                    } elseif ($roleName == 'Admin') {
                                                        $badgeColor = 'danger';
                                                    }
                                                @endphp
                                                <span class="badge bg-{{ $badgeColor }} ms-2" style="font-size: 0.75rem; font-weight: 500;">
                                                    {{ $roleName }}
                                                </span>
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="text-muted small me-5">
                                                @if($comment->created_at)
                                                    @php
                                                        $commentDate = \Carbon\Carbon::parse($comment->created_at);
                                                        $now = \Carbon\Carbon::now();
                                                    @endphp
                                                    
                                                    @if($commentDate->gt($now))
                                                        {{ $commentDate->format('d M Y H:i') }}
                                                    @else
                                                        {{ $commentDate->locale('id')->diffForHumans() }}
                                                    @endif
                                                @else
                                                    Waktu tidak tercatat
                                                @endif
                                            </div>
                                            
                                            <!-- Add eye icon here with fixed tooltip -->
                                            <div class="comment-readers">
                                                <i class="fas fa-eye {{ $comment->readBy->count() > 0 ? 'text-secondary' : 'text-muted' }}" 
                                                   data-id="{{ $comment->id }}" 
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-html="true"
                                                   title="<strong></strong>
                                                          @if($comment->readBy->count() > 0)
                                                              @foreach($comment->readBy as $reader)
                                                                  {{ $reader->first_name }} {{ $reader->last_name }}{{ !$loop->last ? ',<br>' : '' }}
                                                              @endforeach
                                                          @else
                                                              Belum ada yang membaca
                                                          @endif">
                                                </i>
                                                <span class="readers-count small">{{ $comment->readBy->count() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body py-3">
                                    <div class="comment-content">
                                        {!! $comment->pesan !!}
                                    </div>
                                    
                                    @if(!empty($comment->lampiran))
                                    <div class="mt-3 pt-2 border-top">
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($comment->attachmentUrls as $attachment)
                                                <div class="d-flex justify-content-between align-items-center bg-light rounded p-2 w-100">
                                                    <span class="text-truncate">{{ $attachment['name'] }}</span>
                                                    <div>
                                                        <a href="{{ $attachment['url'] }}" class="btn btn-sm btn-outline-primary me-1" target="_blank">
                                                            <i class="fa fa-eye me-1"></i> Lihat
                                                        </a>
                                                        <a href="{{ $attachment['url'] }}" class="btn btn-sm btn-outline-success" download>
                                                            <i class="fa fa-download me-1"></i> Unduh
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-light text-center my-4">
                        <i class="fa fa-comments fa-2x mb-3 d-block text-muted"></i>
                        <p>Belum ada komentar pada tiket ini.</p>
                    </div>
                    @endforelse
                    
                    @if($ticket->status != 'Selesai')
                    <div class="alert alert-dismissible" style="background-color: #fadce0; color: #dc3545; border-color: #f5c6cb; padding: 12px 10px; border-radius: 4px;" id="ticketInfoAlert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Penting!</strong> Jika pengaduan anda selesai, mohon TUTUP TIKET.
                    </div>
                    
                    <div class="d-flex mt-4">
                        <div class="me-4" style="margin-right: 15px !important;">
                            <div class="bg-primary d-flex align-items-center justify-content-center text-white" style="width:40px;height:40px">
                                <span>{{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <form id="commentForm" class="comment-form">
                                @csrf
                                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                <div class="form-group mb-3">
                                    <textarea class="form-control summernote" id="komentar-summernote" name="komentar" rows="5" required></textarea>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="lampiran" class="form-label d-flex align-items-center">
                                        <i class="fa fa-paperclip me-1 text-primary"></i>
                                        <span>Lampiran (Opsional)</span>
                                    </label>
                                    <input type="file" class="form-control" id="lampiran" name="lampiran[]" multiple>
                                    <small class="text-muted">Maksimal 5 file, maks. 5MB per file</small>
                                </div>
                                
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-paper-plane me-1"></i> Kirim Komentar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

<!-- Modal Approve Ticket -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">Setujui & Proses Tiket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="approveForm">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menyetujui dan memproses tiket ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Setujui & Proses</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Disposisi Ticket -->
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
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle me-2"></i> Tiket ini akan didisposisikan dan akan ditinjau oleh admin untuk diarahkan ke kategori yang tepat.
                    </div>
                    
                    <p class="mb-3">Apakah Anda yakin tiket ini salah posisi dan perlu didisposisikan?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Konfirmasi Disposisi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ubah Urgensi Ticket -->
<div class="modal fade" id="urgensiModal" tabindex="-1" role="dialog" aria-labelledby="urgensiModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="urgensiModalLabel">Ubah Urgensi Tiket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="urgensiForm">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="urgensi">Urgensi</label>
                        <select class="form-control" id="urgensi" name="urgensi" required>
                            <option value="Rendah" {{ $ticket->urgensi == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                            <option value="Sedang" {{ $ticket->urgensi == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="Tinggi" {{ $ticket->urgensi == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                            <option value="Mendesak" {{ $ticket->urgensi == 'Mendesak' ? 'selected' : '' }}>Mendesak</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Ubah Urgensi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Close Ticket -->
<div class="modal fade" id="closeModal" tabindex="-1" role="dialog" aria-labelledby="closeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="closeModalLabel">Tandai Tiket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="closeForm">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-body">
                    <!-- Hapus textarea solusi, hanya konfirmasi -->
                    <div class="alert alert-success">
                        <i class="fa fa-check-circle me-2"></i>
                        Apakah Anda yakin ingin menandai tiket ini sebagai selesai?
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Tandai Tiket</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('css')
<style>
    .timeline {
        position: relative;
        padding-left: 12px;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    
    .timeline-marker {
        position: absolute;
        left: -18px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        top: 6px;
    }
    
    .timeline-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: -12px;
        width: 1px;
        background: #dee2e6;
        top: 18px;
        bottom: 0;
    }
    
    .comment-content img {
        max-width: 100%;
        height: auto;
    }
    
    /* Styling tambahan untuk komentar */
    .avatar-sm {
        width: 36px;
        height: 36px;
    }
    
    .avatar {
        width: 30px;
        height: 30px;
        font-size: 14px;
    }
    
    .comment-box {
        width: 100%;
    }
    
    .comment-mine .comment-body {
        background-color: #e7f3ff !important;
    }
    
    .unread-comment {
        border-left: 3px solid #dc3545;
        padding-left: 10px;
    }
    
    .bg-light-primary {
        background-color: #e7f3ff !important;
    }
    
    /* Tooltip dan readers styling */
    .comment-readers {
        display: flex;
        align-items: center;
        cursor: help;
    }

    .readers-count {
        margin-left: 3px;
        font-size: 0.8rem;
        color: #6c757d;
    }

    .tooltip-inner {
        max-width: 300px;
        text-align: left;
    }

    .text-muted.small {
        margin-right: 0.4rem !important;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Summernote
    $('.summernote').summernote({
        placeholder: 'Tuliskan komentar Anda di sini...',
        tabsize: 2,
        height: 200,
        toolbar: [
            "fontsize",
            "paragraph",
            "table",
            ["insert", ["link"]],
            "codeview",
        ],
        fontSizes: ['8', '9', '10', '11', '12', '14', '18', '24', '36'],
        callbacks: {
            onInit: function() {
                $('.note-editable').css('line-height', '1.4');
            },
            onImageUpload: function(files) {
                toastr.warning('Untuk menambahkan gambar, gunakan fitur lampiran');
            }
        }
    });
    
    // Handle comment submission
    $('#commentForm').submit(function(e) {
        e.preventDefault();
        
        // Disable submit button and show loading
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Mengirim...');
        
        // Get ticket ID from the form
        const ticketId = $('input[name="ticket_id"]').val();
        
        // Create FormData object for file uploads
        const formData = new FormData(this);
        
        // PERBAIKAN: Gunakan 'pesan' sebagai field name alih-alih 'komentar'
        formData.append('pesan', $('#komentar-summernote').summernote('code'));
        formData.delete('komentar'); // Hapus field 'komentar'
        
        $.ajax({
            // PERBAIKAN: Gunakan URL khusus teknisi
            url: '/ticket/comment/store',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status) {
                    toastr.success(response.message);
                    
                    // Clear form
                    $('#komentar-summernote').summernote('reset');
                    $('#lampiran').val('');
                    
                    // Reload page after delay
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message);
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
                toastr.error('Terjadi kesalahan saat mengirim komentar');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Handle approve ticket
    $('#approveForm').submit(function(e) {
        e.preventDefault();
        
        const ticketId = '{{ $ticket->id }}';
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
        
        $.ajax({
            url: '/teknisi/ticket/' + ticketId + '/approve',
            type: 'PUT',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status) {
                    toastr.success(response.message);
                    $('#approveModal').modal('hide');
                    
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message);
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
                toastr.error('Terjadi kesalahan saat menyetujui tiket');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Handle disposisi ticket
    $('#disposisiForm').submit(function(e) {
        e.preventDefault();
        
        const ticketId = '{{ $ticket->id }}';
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
        
        $.ajax({
            url: '/teknisi/ticket/' + ticketId + '/disposisi',
            type: 'PUT',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status) {
                    toastr.success(response.message);
                    $('#disposisiModal').modal('hide');
                    
                    setTimeout(function() {
                        window.location.href = response.redirect_url || window.location.href;
                    }, 1000);
                } else {
                    toastr.error(response.message);
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
                toastr.error('Terjadi kesalahan saat disposisi tiket');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Handle change urgency
    $('#urgensiForm').submit(function(e) {
        e.preventDefault();
        
        const ticketId = '{{ $ticket->id }}';
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
        
        $.ajax({
            url: '/teknisi/ticket/' + ticketId + '/urgency',
            type: 'PUT',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status) {
                    toastr.success(response.message);
                    $('#urgensiModal').modal('hide');
                    
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message);
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
                toastr.error('Terjadi kesalahan saat mengubah urgensi');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Handle close ticket
    $('#closeForm').submit(function(e) {
        e.preventDefault();
        
        const ticketId = '{{ $ticket->id }}';
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
        
        // Create FormData object
        const formData = new FormData(this);
        
        // Add summernote content
        formData.append('solusi', $('#solusi-summernote').summernote('code'));
        
        $.ajax({
            url: '/teknisi/ticket/' + ticketId + '/close',
            type: 'PUT',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status) {
                    toastr.success(response.message);
                    $('#closeModal').modal('hide');
                    
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message);
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
                toastr.error('Terjadi kesalahan saat menyelesaikan tiket');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Perbaikan fungsi markCommentsAsRead yang sebelumnya tidak bekerja
    function markCommentsAsRead() {
        // Gunakan selector yang KONSISTEN untuk menemukan komentar yang belum dibaca
        const unreadCards = $('.border-danger.border-3');
        
        if (unreadCards.length > 0) {
            const commentIds = [];
            
            unreadCards.each(function() {
                const commentId = $(this).find('[data-id]').data('id');
                if (commentId) {
                    commentIds.push(commentId);
                }
            });
            
            if (commentIds.length > 0) {
                console.log('Marking comments as read:', commentIds);
                
                $.ajax({
                    url: '/ticket/comment/mark-as-read', // KONSISTEN untuk SEMUA VIEW
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        comment_ids: commentIds
                    },
                    success: function(response) {
                        if (response.status) {
                            unreadCards.each(function() {
                                // 1. Hapus border merah
                                $(this).removeClass('border-start border-danger border-3');
                                
                                // 2. Update ikon dengan cara yang KONSISTEN
                                const $icon = $(this).find('[data-id]');
                                if ($icon.length > 0) {
                                    $icon.addClass('text-secondary').removeClass('text-muted');
                                    
                                    // 3. Update jumlah pembaca - PENTING!
                                    const $readerCount = $icon.next('.readers-count');
                                    if ($readerCount.length > 0) {
                                        let currentCount = parseInt($readerCount.text() || '0');
                                        $readerCount.text(currentCount + 1);
                                    }
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                    }
                });
            }
        }
    }
    
    // Inisialisasi tooltip
    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip({
            html: true,
            trigger: 'hover',
            boundary: 'window',
            placement: 'top'
        });
    });
    
    // Panggil fungsi markCommentsAsRead setelah 3 detik
    setTimeout(markCommentsAsRead, 3000);
    
    // Tambahkan event handler scroll untuk menandai komentar saat digeser
    $(window).on('scroll', function() {
        clearTimeout($.data(this, 'scrollTimer'));
        $.data(this, 'scrollTimer', setTimeout(function() {
            markCommentsAsRead();
        }, 250));
    });
    
});
</script>
@endpush
@endsection