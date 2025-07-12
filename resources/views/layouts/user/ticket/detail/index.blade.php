
@extends('layouts.user-layout')

@section('content')
<br><br><br><br>
<div class="container mt-n3 position-relative"><br>
    <div class="row justify-content-center">
        <!-- Sidebar Navigation -->
        <div class="col-md-3">
            <!-- My Tiket Status Card -->
            <div class="card border-1 shadow-sm mb-4">
                <div class="card-body">
                    @include('layouts.user.ticket.partials.status-nav')
                </div>
            </div>

            <!-- Ticket Status Card -->
            <div class="card border-1 shadow-sm rounded-lg mb-4">
                <div class="card-body">
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
                    
                    <div class="text-center mb-3">
                        <div class="icon-circle bg-{{ $statusColor }} text-white mb-2">
                            <i class="fa fa-{{ $statusIcon }} fa-2x"></i>
                        </div>
                        <h5 class="mb-0">Status: {{ $statusText }}</h5>
                    </div>
                    
                    <hr>
                    
                    <div class="ticket-info small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">ID Tiket:</span>
                            <span class="fw-bold">{{ $ticket->no_tiket }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Dibuat:</span>
                            <span>{{ $ticket->created_at->format('d M Y H:i') }}</span>
                        </div>
                        @if($ticket->status == 'Diproses')
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Disetujui:</span>
                            <span>{{ $ticket->approved_at ? $ticket->approved_at->format('d M Y H:i') : '-' }}</span>
                        </div>
                        @endif
                        @if($ticket->status == 'Selesai')
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Diselesaikan:</span>
                            <span>{{ $ticket->closed_at ? $ticket->closed_at->format('d M Y H:i') : '-' }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Support Section -->
            <div class="card border-1 shadow-sm rounded-lg mb-4">
                <div class="card-body">
                    <h6 class="fw-bold">Butuh Bantuan?</h6>
                    <p class="small text-muted">Jika Anda memiliki pertanyaan atau membutuhkan informasi tambahan, hubungi:</p>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fa fa-envelope text-primary me-2"></i>
                        <span>support@helpdesk.riau.go.id</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fa fa-phone text-primary me-2"></i>
                        <span>(0761)-45505</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <span class="fs-5">
                        <i class="fa fa-folder-open"></i> Tiket {{ $ticket->status }}
                    </span>
                    <div>
                        <span class="badge bg-{{ $statusColor }} p-2">Status: {{ $ticket->status }}</span>
                        
                        @if($ticket->status != 'Selesai')
                            <a href="" class="btn btn-danger btn-sm ms-2 close-ticket-btn">
                                <i class="fa fa-times"></i> Tutup Tiket
                            </a>
                        @endif
                    </div>
                </div>
                
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
                                            <span class="fw-bold">{{ $comment->user ? $comment->user->first_name.' '.$comment->user->last_name : 'Unknown User' }}</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="text-muted small me-2">
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
                                            
                                            <!-- Add eye icon here -->
                                            @if($comment->readBy->contains(Auth::id()))
                                                <i class="fas fa-eye text-muted" title="Tandai belum dibaca" data-id="{{ $comment->id }}" onclick="markAsUnread(this, {{ $comment->id }})"></i>
                                            @else
                                                <i class="fas fa-eye text-primary" title="Tandai sudah dibaca" data-id="{{ $comment->id }}" onclick="markAsRead(this, {{ $comment->id }})"></i>
                                            @endif
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
                                
                                @if($comment->readBy->count() > 0)
                                <div class="card-footer bg-light py-1">
                                    <small class="text-muted">
                                        <i class="fa fa-check-circle"></i> Dibaca oleh {{ $comment->readBy->count() }} orang
                                    </small>
                                </div>
                                @endif
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
                    <div class="alert" style="background-color: #fadce0; color: #dc3545; border-color: #f5c6cb; padding: 12px 20px; border-radius: 4px;">
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
                    
                    <!-- Feedback Section (only for completed tickets) -->
                    @if($ticket->status == 'Selesai')
                        @if(!$ticket->feedback)
                        <hr>
                        <div class="mt-4">
                            <h5 class="mb-3"><i class="fa fa-star text-warning me-1"></i> Berikan Feedback</h5>
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <p class="text-muted">Bantu kami meningkatkan layanan dengan memberikan feedback Anda tentang penanganan tiket ini.</p>
                                    
                                    <form action="{{ route('user.feedback.store') }}" method="POST" class="mt-3">
                                        @csrf
                                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                        
                                        <div class="form-group mb-4">
                                            <label class="form-label fw-bold">Rating Pelayanan</label>
                                            <div class="star-rating d-flex flex-wrap gap-3 mt-2">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input visually-hidden" type="radio" name="rating" id="rating1" value="1" required>
                                                    <label class="form-check-label rating-star" for="rating1">
                                                        <i class="fa fa-star fa-2x"></i>
                                                        <span class="d-block mt-1">Sangat Buruk</span>
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input visually-hidden" type="radio" name="rating" id="rating2" value="2">
                                                    <label class="form-check-label rating-star" for="rating2">
                                                        <i class="fa fa-star fa-2x"></i>
                                                        <span class="d-block mt-1">Buruk</span>
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input visually-hidden" type="radio" name="rating" id="rating3" value="3">
                                                    <label class="form-check-label rating-star" for="rating3">
                                                        <i class="fa fa-star fa-2x"></i>
                                                        <span class="d-block mt-1">Cukup</span>
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input visually-hidden" type="radio" name="rating" id="rating4" value="4">
                                                    <label class="form-check-label rating-star" for="rating4">
                                                        <i class="fa fa-star fa-2x"></i>
                                                        <span class="d-block mt-1">Baik</span>
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input visually-hidden" type="radio" name="rating" id="rating5" value="5">
                                                    <label class="form-check-label rating-star" for="rating5">
                                                        <i class="fa fa-star fa-2x"></i>
                                                        <span class="d-block mt-1">Sangat Baik</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group mb-4">
                                            <label for="komentar" class="form-label fw-bold">Komentar</label>
                                            <textarea class="form-control" id="komentar" name="komentar" rows="3" required placeholder="Bagaimana pengalaman Anda dengan layanan kami?"></textarea>
                                        </div>
                                        
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-send me-1"></i> Kirim Feedback
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @else
                        <hr>
                        <div class="mt-4">
                            <h5 class="mb-3"><i class="fa fa-star text-warning me-1"></i> Feedback Anda</h5>
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <div class="rating-display mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $ticket->feedback->rating)
                                                    <i class="fa fa-star fa-2x text-warning"></i>
                                                @else
                                                    <i class="fa fa-star-o fa-2x text-muted"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <div class="rating-label">
                                            @php
                                                $ratingLabel = '';
                                                switch($ticket->feedback->rating) {
                                                    case 1: $ratingLabel = 'Sangat Buruk'; break;
                                                    case 2: $ratingLabel = 'Buruk'; break;
                                                    case 3: $ratingLabel = 'Cukup'; break;
                                                    case 4: $ratingLabel = 'Baik'; break;
                                                    case 5: $ratingLabel = 'Sangat Baik'; break;
                                                }
                                            @endphp
                                            <span class="badge bg-secondary">{{ $ratingLabel }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="p-3 bg-white rounded">
                                        <h6 class="fw-bold mb-2">Komentar:</h6>
                                        <p>{{ $ticket->feedback->komentar }}</p>
                                    </div>
                                    
                                    <div class="text-muted text-end mt-2 small">
                                        Dikirim pada: {{ $ticket->feedback->created_at ? $ticket->feedback->created_at->format('d M Y H:i') : 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
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
    
    .rating-star {
        cursor: pointer;
        text-align: center;
        color: #ccc;
        transition: all 0.2s;
    }
    
    .rating-star:hover i,
    .form-check-input:checked + .rating-star i {
        color: #ffc107;
    }
    
    .rating-star span {
        font-size: 12px;
        color: #6c757d;
    }
    
    .form-check-input:checked + .rating-star span {
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
            tabsize: 2,
            height: 250,
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
            },
            onImageUpload: function(files) {
                toastr.warning('Untuk menambahkan gambar, gunakan fitur lampiran');
            }
        }
    });
    
    // Mark comments as read when viewed
function markAsRead(element, commentId) {
    $.ajax({
        url: '{{ route("ticket.comment.markAsRead") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            comment_ids: [commentId]
        },
        success: function(response) {
            if (response.status) {
                // Change icon to read
                $(element).removeClass('fa-eye text-primary').addClass('fa-eye-slash text-muted');
                $(element).attr('title', 'Tandai belum dibaca');
                $(element).attr('onclick', `markAsUnread(this, ${commentId})`);
                
                // Remove "Baru" badge
                $(element).closest('.d-flex').parent().find('.badge.bg-danger').remove();
                
                // Remove border if it exists
                $(element).closest('.card').removeClass('border-start border-danger border-3');
            }
        }
    });
}
    
    // Mark comments as read after 3 seconds on page
    setTimeout(markCommentsAsRead, 3000);
    
    // Handle comment submission with attachment
    $('#commentForm').submit(function(e) {
        e.preventDefault();
        
        // Disable submit button and show loading
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Mengirim...');
        
        // Create FormData object for file uploads
        const formData = new FormData(this);
        formData.append('komentar', $('.summernote').summernote('code'));
        
        $.ajax({
            url: '{{ route("ticket.comment.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status) {
                    // Show success message
                    toastr.options = {
                        "progressBar": true,
                        "timeOut": "1500"
                    };
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
                toastr.error('Terjadi kesalahan saat mengirim komentar');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
    
    // Star rating functionality
    $('.rating-star').hover(
        function() {
            const rating = $(this).prev('input').val();
            for (let i = 1; i <= rating; i++) {
                $(`#rating${i}`).next('.rating-star').find('i').addClass('text-warning');
            }
        },
        function() {
            if (!$(this).prev('input').prop('checked')) {
                $(this).find('i').removeClass('text-warning');
            }
        }
    );
    
    $('input[name="rating"]').change(function() {
        $('.rating-star i').removeClass('text-warning');
        const rating = $(this).val();
        for (let i = 1; i <= rating; i++) {
            $(`#rating${i}`).next('.rating-star').find('i').addClass('text-warning');
        }
    });
});
</script>
@endpush
@endsection