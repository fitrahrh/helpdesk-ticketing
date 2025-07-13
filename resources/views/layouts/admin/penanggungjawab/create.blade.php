<!-- Create Penanggungjawab Modal -->
<div class="modal fade" id="createPenanggungjawabModal" tabindex="-1" role="dialog" aria-labelledby="createPenanggungjawabModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPenanggungjawabModalLabel">Tambah Penanggung Jawab</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createPenanggungjawabForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="user_id">Teknisi</label>
                        <select class="form-control select2" id="user_id" name="user_id" required>
                            <option value="">Pilih Teknisi</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kategori_id">Kategori</label>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2 for better dropdown experience
        $('#createPenanggungjawabModal .select2').select2({
            dropdownParent: $('#createPenanggungjawabModal')
        });
    });
</script>
@endpush