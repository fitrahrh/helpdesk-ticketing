
@extends('layouts.user-layout')

@section('content')
<div class="d-flex flex-column align-items-center justify-content-center center-action" style="background-color: #fff; margin: 20px 0; padding: 20px; border-radius: 8px;">
    <div class="rounded-circle bg-info d-flex align-items-center justify-content-center fade-in"
        data-toggle="modal"
        data-target="#ticketModal"
        style="width:90px;height:90px;cursor:pointer;">
        <i class="fa fa-question fa-3x text-white"></i>
    </div>
<div class="mt-3 fade-in-delay" data-toggle="modal" data-target="#ticketModal" style="cursor: pointer; font-size: 1.2rem; font-weight: bold;">
    Kirim <b>Tiket</b>
</div>
</div>

<!-- Ticket Creation Modal -->
<div class="modal fade" id="ticketModal" tabindex="-1" aria-labelledby="ticketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Ubah ukuran modal menjadi lebih kecil -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ticketModalLabel">Buat Tiket Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('/ticket') }}" method="POST" enctype="multipart/form-data" id="ticketForm">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="judul" class="form-label">Judul <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="judul" name="judul" required placeholder="Masukkan judul tiket">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-8 mb-3">
                            <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-control select2" id="kategori_id" name="kategori_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($skpds as $skpd)
                                    <optgroup label="{{ $skpd->name }}">
                                        @foreach($skpd->kategoris as $kategori)
                                            <option value="{{ $kategori->id }}">{{ $kategori->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="urgensi" class="form-label">Urgensi <span class="text-danger">*</span></label>
                            <select class="form-control select2" id="urgensi" name="urgensi" required>
                                <option value="">Pilih Urgensi</option>
                                <option value="Rendah">Rendah</option>
                                <option value="Sedang">Sedang</option>
                                <option value="Tinggi">Tinggi</option>
                                <option value="Mendesak">Mendesak</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="masalah" class="form-label">Deskripsi Masalah <span class="text-danger">*</span></label>
                            <textarea class="form-control summernote" id="masalah" name="masalah" rows="3" required placeholder="Jelaskan masalah Anda secara detail"></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="lampiran" class="form-label">Lampiran (Opsional)</label>
                            <input type="file" class="form-control" id="lampiran" name="lampiran[]" multiple>
                        </div>
                    </div>
                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitTicket">Kirim Tiket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
    $(document).ready(function() {
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
    });
    </script>
@endpush
@endsection
