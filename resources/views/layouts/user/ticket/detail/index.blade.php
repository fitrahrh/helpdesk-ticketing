@extends('layouts.user-layout')

@section('content')
<br><br><br><br>
<div class="container mt-n3 position-relative">
    <div class="row justify-content-center">
        <!-- Sidebar Navigation -->
        <div class="col-md-3">
            <div class="shadow-sm mb-4">
                <div class="card-body">
            <!-- My Tiket Status Card -->
                    <div class="d-flex align-items-center justify-content-between p-3">
                        <div class="d-flex align-items-center">
                            <h5 class="mb-0"><i class="fa fa-ticket text-dark me-2"></i> My Ticket Status</h5>
                        </div>
                    </div>
                    <htr>
                    
                    <div class="list-group list-group-flush">
                        <!-- Pending Status -->
                        <a href="{{ route('ticket.pending') }}" 
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center 
                                {{ $ticket->status == 'Baru' ? 'active bg-light' : '' }}">
                            <div>
                                <i class="fa fa-clock-o me-2 text-dark"></i> 
                                <span class="{{ $ticket->status == 'Baru' ? 'fw-bold' : '' }}">Pending</span>
                            </div>
                            <span class="badge {{ $ticket->status == 'Baru' ? 'bg-warning text-dark' : 'bg-secondary' }} rounded-pill">
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
                            <span class="badge {{ $ticket->status == 'Disposisi' ? 'bg-danger' : 'bg-secondary' }} rounded-pill">
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
                            <span class="badge {{ $ticket->status == 'Diproses' ? 'bg-info' : 'bg-secondary' }} rounded-pill">
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
                            <span class="badge {{ $ticket->status == 'Selesai' ? 'bg-success' : 'bg-secondary' }} rounded-pill">
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
            
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <span class="fs-5">
                        <h5><i class="fa fa-folder-open"></i> Tiket {{ $ticket->status }}</h5>
                    </span>
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
                </div>
            </div>
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

    $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip({
            html: true,
            trigger: 'hover',
            boundary: 'window',
            placement: 'top'
        });
    });
    
    // Mark comments as read after 3 seconds on page
    setTimeout(markCommentsAsRead, 3000);
    
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