@extends('layouts.user-layout')

@section('content')
<div class="pt-5"></div>
<div class="container position-relative">
    <!-- Main Content -->
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h5>My Ticket Status</h5>
                </div>
            </div>
            <hr>
            @include('layouts.user.ticket.partials.status-nav')
        </div>
        <div class="col-md-9">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <h5><i class="fa fa-folder-open"></i> Tiket Pending</h5>
                </div>
            </div>
            <hr>
            <div class="card border-0 rounded-lg fade-in">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="pending-tickets-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Tiket</th>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Tanggal</th>
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

<!-- Edit Ticket Modal -->
<div class="modal fade" id="editTicketModal" tabindex="-1" aria-labelledby="editTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTicketModalLabel">Edit Tiket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editTicketForm">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="ticket_id" id="edit_ticket_id">
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="edit_judul" class="form-label">Judul <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_judul" name="judul" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="edit_masalah" class="form-label">Deskripsi Masalah <span class="text-danger">*</span></label>
                            <textarea class="form-control summernote" id="edit_masalah" name="masalah" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        let table = $('#pending-tickets-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('ticket.pending.data') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { 
                    data: 'no_tiket', 
                    name: 'no_tiket',
                    render: function(data, type, row) {
                        return '<a href="' + "{{ route('ticket.ticket.show', '') }}" + '/' + row.id + '" class="text-primary fw-bold"><span class="text-secondary">#</span>' + data + '</a>';
                    }
                },
                { data: 'judul', name: 'judul' },
                { data: 'kategori', name: 'kategori' },
                { data: 'created_at', name: 'created_at' },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<button class="btn btn-sm btn-warning btn-edit" data-id="${row.id}" data-judul="${row.judul}" data-masalah="${row.masalah}"><i class="fa fa-edit"></i> Edit</button>`;
                    }
                }
            ]
        });

        // Initialize Summernote for the edit modal
        $('.summernote').summernote({
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
        });

        // Handle Edit Button Click
        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            let judul = $(this).data('judul');
            let masalah = $(this).data('masalah');

            $('#edit_ticket_id').val(id);
            $('#edit_judul').val(judul);
            $('.summernote').summernote('code', masalah);

            $('#editTicketModal').modal('show');
        });

        // Handle Edit Ticket Form Submission
        $('#editTicketForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let id = $('#edit_ticket_id').val();

            $.ajax({
                url: "{{ route('ticket.ticket.update', '') }}/" + id, // Dynamically append the ticket ID
                method: 'PUT',
                data: form.serialize(),
                success: function(response) {
                    $('#editTicketModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(response.message);
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                }
            });
        });
    });
</script>
@endpush
@endsection