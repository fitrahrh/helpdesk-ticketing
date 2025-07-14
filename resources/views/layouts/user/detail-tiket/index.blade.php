@extends('layouts.user-layout')

@section('content')
<div class="pt-5"></div>
<div class="container position-relative">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h5>My Ticket Status</h5>
                </div>
            </div>
                <hr>
                <div class="h-100 mb-4">      
                    <div class="list-group list-group-flush">
                        <!-- Pending Status -->
                        <a href="{{ route('ticket.pending') }}" 
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center 
                                {{ $ticket->status == 'Baru' ? 'active bg-light' : '' }}">
                            <div>
                                <i class="fa fa-clock-o me-2 text-dark"></i> 
                                <span class="{{ $ticket->status == 'Baru' ? 'fw-bold' : '' }}">Pending</span>
                            </div>
                            <span class="badge {{ $ticket->status == 'Baru' ? 'bg-light text-dark' : 'bg-secondary' }} rounded-pill">
                                {{ Auth::user()->tickets()->where('status', 'Baru')->count() }}
                            </span>
                        </a>
                        
                        <!-- Disposisi Status -->
                        <a href="{{ route('ticket.disposisi') }}" 
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center 
                                {{ $ticket->status == 'Disposisi' ? 'active bg-light' : '' }}">
                            <div>
                                <i class="fa fa-exchange-alt me-2 text-dark"></i> 
                                <span class="{{ $ticket->status == 'Disposisi' ? 'fw-bold' : '' }}">Disposisi</span>
                            </div>
                            <span class="badge {{ $ticket->status == 'Disposisi' ? 'bg-light' : 'bg-secondary' }} rounded-pill">
                                {{ Auth::user()->tickets()->where('status', 'Disposisi')->count() }}
                            </span>
                        </a>
                        
                        <!-- Diproses Status -->
                        <a href="{{ route('ticket.diproses') }}" 
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center 
                                {{ $ticket->status == 'Diproses' ? 'active bg-light' : '' }}">
                            <div>
                                <i class="fa fa-spinner me-2 text-dark"></i> 
                                <span class="{{ $ticket->status == 'Diproses' ? 'fw-bold' : '' }}">Proses</span>
                            </div>
                            <span class="badge {{ $ticket->status == 'Diproses' ? 'bg-light' : 'bg-secondary' }} rounded-pill">
                                {{ Auth::user()->tickets()->where('status', 'Diproses')->count() }}
                            </span>
                        </a>
                        
                        <!-- Selesai Status -->
                        <a href="{{ route('ticket.selesai') }}" 
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center 
                                {{ $ticket->status == 'Selesai' ? 'active bg-light' : '' }}">
                            <div>
                                <i class="fa fa-check me-2 text-dark"></i> 
                                <span class="{{ $ticket->status == 'Selesai' ? 'fw-bold' : '' }}">Selesai</span>
                            </div>
                            <span class="badge {{ $ticket->status == 'Selesai' ? 'bg-light' : 'bg-secondary' }} rounded-pill">
                                {{ Auth::user()->tickets()->where('status', 'Selesai')->count() }}
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Ticket Status Card -->
                    @php
                        $statusIcon = 'clock-o';
                        $statusColor = 'warning';
                        $statusText = 'Menunggu';
                        
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

        
        <!-- Main Content -->
        <div class="col-md-9">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="fs-5">
                        <h5><i class="fa fa-folder-open"></i> Detail Tiket #{{ $ticket->no_tiket }}</h5>
                    </span>
                </div>
                    <div>
                        <button type="button" class="btn btn-{{ $statusColor }} btn-sm" disabled>
                            <i class="fa fa-circle-info me-1"></i> Status: {{ $ticket->status }}
                        </button>
                        
                        @if($ticket->status == 'Selesai' && !$ticket->feedback)
                            <button type="button" class="btn btn-warning btn-sm ms-2" data-toggle="modal" data-target="#feedbackModal">
                                <i class="fa fa-star me-1"></i> Berikan Feedback
                            </button>
                        @elseif($ticket->status == 'Selesai')
                            <!-- Show rating instead of button -->
                            <div class="btn btn-outline-success btn-sm ms-2">
                                Rating: 
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa fa-{{ $i <= $ticket->feedback->rating ? 'star' : 'star-o' }} {{ $i <= $ticket->feedback->rating ? 'text-warning' : '' }}"></i>
                                @endfor
                            </div>
                        @endif
                        
                        @if($ticket->status != 'Selesai')
                            <a href="" class="btn btn-danger btn-sm ms-2 close-ticket-btn">
                                <i class="fa fa-times"></i> Tutup Tiket
                            </a>
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
                            <div class="text-dark">{{ $ticket->kategori && $ticket->kategori->skpd ? $ticket->kategori->skpd->name : '-' }}</div>
                            <span class="badge bg-dark text-light me-2 mb-2">Kategori: {{ $ticket->kategori->name }}</span>
                            <span class="badge bg-secondary me-2 mb-2">Dibuat: {{ $ticket->created_at->format('d M Y H:i') }}</span>
                            
                            @if($ticket->approved_at)
                            <span class="badge bg-info me-2 mb-2">Diproses: {{ \Carbon\Carbon::parse($ticket->approved_at)->format('d M Y H:i') }}</span>
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
                                            <div class="text-muted small me-5 comment-timestamp"> {{-- Tambahkan class comment-timestamp --}}
                                                @php
                                                    $commentDate = \Carbon\Carbon::parse($comment->created_at);
                                                    $updatedDate = \Carbon\Carbon::parse($comment->updated_at);
                                                    $now = \Carbon\Carbon::now();
                                                    $isEdited = $comment->updated_at && $updatedDate->gt($commentDate->addSeconds(5)); // Cek apakah diedit
                                                @endphp

                                                @if($isEdited)
                                                    {{-- Jika diedit, tampilkan hanya waktu edit --}}
                                                    (Diedit {{ $updatedDate->locale('id')->diffForHumans() }})
                                                @else
                                                    {{-- Jika belum diedit, tampilkan waktu dibuat --}}
                                                    @if($comment->created_at)
                                                        @if($commentDate->gt($now))
                                                            {{ $commentDate->format('d M Y H:i') }}
                                                        @else
                                                            {{ $commentDate->locale('id')->diffForHumans() }}
                                                        @endif
                                                    @else
                                                        Waktu tidak tercatat
                                                    @endif
                                                @endif
                                            </div>

                                            <!-- Add eye icon here with fixed tooltip -->
                                            <div class="comment-readers">
                                                <i class="fas fa-eye {{ $comment->readBy->count() > 0 ? 'text-secondary' : 'text-muted' }}"
                                                   data-id="{{ $comment->id }}"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-html="true"
                                                   title="<strong>Dibaca oleh:</strong>
                                                          @if($comment->readBy->count() > 0)
                                                              @foreach($comment->readBy as $reader)
                                                                  {{ $reader->first_name }} {{ $reader->last_name }}{{ !$loop->last ? '<br>' : '' }}
                                                              @endforeach
                                                          @else
                                                              Belum ada yang membaca
                                                          @endif">
                                                </i>
                                                <span class="readers-count small">{{ $comment->readBy->count() }}</span>
                                            </div>
                                            {{-- Tombol Edit dihapus dari sini --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body py-3 comment-body"> {{-- Tambahkan class comment-body --}}
                                    <div class="d-flex justify-content-between align-items-start"> {{-- Tambahkan div flex untuk layout --}}
                                        <div class="comment-content flex-grow-1 me-3"> {{-- Konten komentar --}}
                                            {!! $comment->pesan !!}
                                        </div>
                                        {{-- Tombol Edit dipindahkan ke sini --}}
                                        @if($comment->user_id == Auth::id())
                                            <button type="button" class="btn btn-sm btn-outline-secondary btn-edit-comment flex-shrink-0"
                                                    data-id="{{ $comment->id }}"
                                                    data-pesan="{{ $comment->pesan }}"> {{-- Simpan pesan di data attribute --}}
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                        @endif
                                    </div>


                                    @if(!empty($comment->lampiran))
                                    <div class="mt-3 pt-2 border-top">
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($comment->attachmentUrls as $attachment)
                                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center bg-light rounded p-2 w-100">
                                                    <div>
                                                        <span class="fw-semibold">Lampiran {{ $loop->iteration }}</span>
                                                    </div>
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

<!-- Modal Edit Comment -->
<div class="modal fade" id="editCommentModal" tabindex="-1" role="dialog" aria-labelledby="editCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCommentModalLabel">Edit Komentar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editCommentForm">
                @csrf
                @method('PUT') {{-- Gunakan method PUT --}}
                <input type="hidden" name="comment_id" id="edit_comment_id">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="edit_pesan" class="form-label">Pesan Komentar</label>
                        <textarea class="form-control summernote-edit" id="edit_pesan" name="pesan" rows="5" required></textarea>
                    </div>
                    {{-- Lampiran tidak bisa diedit/ditambah setelah komentar dibuat --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="updateCommentBtn">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feedbackModalLabel">Berikan Feedback</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="feedbackForm" action="{{ route('ticket.feedback.store') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                    
                    <p class="text-muted">Bantu kami meningkatkan layanan dengan memberikan rating untuk penanganan tiket ini.</p>
                    
                    <div class="form-group text-center mb-4">
                        <label class="form-label fw-bold d-block mb-3">Rating Pelayanan</label>
                        <div class="star-rating d-flex justify-content-center gap-3 mt-2">
                            @for($i = 1; $i <= 5; $i++)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input visually-hidden" type="radio" name="rating" id="modalRating{{ $i }}" value="{{ $i }}" required>
                                    <label class="form-check-label modal-rating-star" for="modalRating{{ $i }}">
                                        <i class="fa fa-star fa-2x"></i>
                                        <span class="d-block mt-1">
                                            @if($i == 1) Sangat Buruk
                                            @elseif($i == 2) Buruk
                                            @elseif($i == 3) Cukup
                                            @elseif($i == 4) Baik
                                            @else Sangat Baik
                                            @endif
                                        </span>
                                    </label>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim Feedback</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('css')
<style>
    .avatar-sm {
        width: 36px;
        height: 36px;
    }
    
    .avatar {
        width: 30px;
        height: 30px;
        font-size: 14px;
    }
    
    .icon-circle {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
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
    
    .modal-rating-star {
        cursor: pointer;
        text-align: center;
        color: #ccc;
        transition: all 0.2s;
        margin: 0 10px;
    }

    .modal-rating-star:hover i,
    .form-check-input:checked + .modal-rating-star i {
        color: #ffc107;
    }

    .modal-rating-star span {
        font-size: 12px;
        color: #6c757d;
    }

    .form-check-input:checked + .modal-rating-star span {
        color: #495057;
        font-weight: 500;
    }


    
    .note-editor .dropdown-toggle::after {
        all: unset;
    }
    
    .note-editor .note-dropdown-menu {
        box-sizing: content-box;
    }
    
    .note-editor .note-modal-footer {
        box-sizing: content-box;
    }

    /* Complete hiding of radio buttons */
    .form-check-input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
        margin: 0;
    }
    
    /* Better spacing for the rating stars */
    .star-rating {
        display: flex;
        justify-content: space-around;
        width: 100%;
    }
    
    .form-check-inline {
        margin-right: 0;
    }

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
        margin-right: 0.4rem !important; /* Increase the space */
    }

    .comment-content {
        word-break: break-word; /* Memastikan teks panjang otomatis pindah baris */
        overflow-wrap: break-word; /* Alternatif/tambahan untuk word-break */
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
                console.log('Summernote initialized successfully');
                $('.note-editable').css('line-height', '1.4');
            },
            onImageUpload: function(files) {
                toastr.warning('Untuk menambahkan gambar, gunakan fitur lampiran');
            }
        }
    });

    // Initialize Summernote for the edit modal
    $('#edit_pesan').summernote({
        placeholder: 'Edit komentar Anda di sini...',
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
                console.log('Summernote Edit initialized successfully');
                $('#editCommentModal .note-editable').css('line-height', '1.4');
            },
            onImageUpload: function(files) {
                toastr.warning('Untuk menambahkan gambar, gunakan fitur lampiran saat membuat komentar baru.');
            }
        }
    });

    // Handle click on Edit Comment button
    $(document).on('click', '.btn-edit-comment', function() {
        const commentId = $(this).data('id');
        const commentPesan = $(this).data('pesan'); // Ambil pesan dari data attribute

        // Populate the modal form
        $('#edit_comment_id').val(commentId);
        // Set Summernote content
        $('#edit_pesan').summernote('code', commentPesan);

        // Show the modal
        $('#editCommentModal').modal('show');
    });
    
    // Define the missing markCommentsAsRead function
    function markCommentsAsRead() {
        // Get all unread comments
        const unreadComments = $('.border-danger.border-3').closest('.card').find('.fa-eye.text-primary');
        
        if (unreadComments.length > 0) {
            // Collect all comment IDs
            const commentIds = [];
            unreadComments.each(function() {
                commentIds.push($(this).data('id'));
            });
            
            // Mark all as read in one request
            if (commentIds.length > 0) {
                $.ajax({
                    url: '{{ route("ticket.comment.markAsRead") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        comment_ids: commentIds
                    },
                    success: function(response) {
                        if (response.status) {
                            // Update UI for all marked comments
                            unreadComments.each(function() {
                                const $icon = $(this);
                                $icon.removeClass('fa-eye text-primary').addClass('fa-eye-slash text-muted');
                                $icon.attr('title', 'Tandai belum dibaca');
                                const commentId = $icon.data('id');
                                $icon.attr('onclick', `markAsUnread(this, ${commentId})`);
                                
                                // Remove "Baru" badge
                                $icon.closest('.d-flex').parent().find('.badge.bg-danger').remove();
                                
                                // Remove border
                                $icon.closest('.card').removeClass('border-start border-danger border-3');
                            });
                        }
                    }
                });
            }
        }
    }
    
    // Call the function after 3 seconds
    setTimeout(markCommentsAsRead, 3000);

    // Mark comments as read when viewed
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

    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip({
            html: true,
            trigger: 'hover',
            boundary: 'window',
            placement: 'top'
        });
    });
    
    // Handle comment submission with attachment
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
        
        // Add summernote content - IMPORTANT: Use 'pesan' instead of 'komentar'
        formData.append('pesan', $('.summernote').summernote('code'));
        
        $.ajax({
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
                    $('.summernote').summernote('reset');
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
    
    // Star rating functionality
    $('.modal-rating-star').hover(
        function() {
            const rating = $(this).prev('input').val();
            for (let i = 1; i <= rating; i++) {
                $(`#modalRating${i}`).next('.modal-rating-star').find('i').addClass('text-warning');
            }
        },
        function() {
            if (!$(this).prev('input').prop('checked')) {
                $(this).find('i').removeClass('text-warning');
            }
        }
    );

    $('input[name="rating"]').change(function() {
        $('.modal-rating-star i').removeClass('text-warning');
        const rating = $(this).val();
        for (let i = 1; i <= rating; i++) {
            $(`#modalRating${i}`).next('.modal-rating-star').find('i').addClass('text-warning');
        }
    });
});

    // Handle Edit Comment Form Submission
    $('#editCommentForm').submit(function(e) {
        e.preventDefault();

        const commentId = $('#edit_comment_id').val();
        const submitBtn = $(this).find('#updateCommentBtn');
        const originalText = submitBtn.html();

        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

        // Get Summernote content
        const editedPesan = $('#edit_pesan').summernote('code');

        $.ajax({
            url: `/ticket/comment/${commentId}`, // Gunakan route update
            type: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                pesan: editedPesan
            },
            success: function(response) {
                if (response.status) {
                    toastr.success(response.message);

                    // Update the comment on the page
                    const commentElement = $(`[data-id="${commentId}"]`).closest('.d-flex.mb-3'); // Cari elemen komentar berdasarkan ID
                    if (commentElement.length > 0) {
                        // Update isi komentar
                        commentElement.find('.comment-content').html(editedPesan);

                        // Tambahkan atau update waktu edit menggunakan updated_at dari response
                        let editedAtSpan = commentElement.find('.comment-timestamp .text-muted.small.ms-2');
                        const updatedTime = response.updated_at_for_humans; // Ambil dari response

                        if (editedAtSpan.length === 0) {
                             // Jika belum ada, tambahkan span baru
                             editedAtSpan = $(`<span class="text-muted small ms-2">(Diedit ${updatedTime})</span>`);
                             commentElement.find('.comment-timestamp').append(editedAtSpan);
                        } else {
                            // Jika sudah ada, update teksnya
                            editedAtSpan.text(`(Diedit ${updatedTime})`);
                        }
                    }

                    // Hide the modal
                    $('#editCommentModal').modal('hide');
                } else {
                    toastr.error(response.message);
                }
                submitBtn.prop('disabled', false).html(originalText);
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
                toastr.error('Terjadi kesalahan saat memperbarui komentar');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
// Handle close ticket button click
// Handle "Tutup Tiket" button click
    $('.close-ticket-btn').on('click', function(e) {
        e.preventDefault(); // Prevent default link behavior

        const ticketId = '{{ $ticket->id }}'; // Get ticket ID from the Blade variable

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan menutup tiket ini. Aksi ini tidak dapat dibatalkan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Tutup Tiket!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("ticket.close", $ticket->id) }}', // Assuming a route named 'ticket.close' exists
                    type: 'PUT', // Use PUT or POST for updates
                    data: {
                        _token: '{{ csrf_token() }}', // Include CSRF token
                        // You might need to send additional data if required by the backend
                    },
                    success: function(response) {
                        if (response.status) {
                            toastr.success(response.message);
                            // Reload the page or update UI to reflect the status change
                            $('#feedbackModal').modal('show');
                            // Hapus tombol "Tutup Tiket" atau sembunyikan jika perlu
                            $('.close-ticket-btn').hide();
                            // Opsional: Update status tiket di UI tanpa reload
                            $('.btn-{{ $statusColor }}').removeClass('btn-{{ $statusColor }}').addClass('btn-success').html('<i class="fa fa-circle-info me-1"></i> Status: Selesai');
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                        toastr.error('Terjadi kesalahan saat menutup tiket');
                    }
                });
            }
        });
    });

// Handle feedback form submission
$('#feedbackForm').submit(function(e) {
    e.preventDefault();
    
    // Disable submit button and show loading
    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.html();
    submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Mengirim...');
    
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.status) {
                // Hide modal
                $('#feedbackModal').modal('hide');
                
                // Show success message
                toastr.success(response.message);
                
                // Reload page after short delay to show the feedback stars
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
            toastr.error('Terjadi kesalahan saat mengirim feedback');
            submitBtn.prop('disabled', false).html(originalText);
        }
    });
});
</script>
@endpush
@endsection