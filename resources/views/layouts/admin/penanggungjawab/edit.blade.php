<!-- Edit Penanggungjawab Modal -->
<div class="modal fade" id="editPenanggungjawabModal" tabindex="-1" role="dialog" aria-labelledby="editPenanggungjawabModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPenanggungjawabModalLabel">Edit Penanggung Jawab</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editPenanggungjawabForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="id" id="penanggungjawab_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_user_id">Teknisi</label>
                        <select class="form-control select2" id="edit_user_id" name="user_id" required>
                            <option value="">Pilih Teknisi</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_kategori_id">Kategori</label>
                        <select class="form-control select2" id="edit_kategori_id" name="kategori_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->name }} ({{ $kategori->skpd ? $kategori->skpd->name : '-' }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning" id="updateBtn">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2 for better dropdown experience
        $('#editPenanggungjawabModal .select2').select2({
            dropdownParent: $('#editPenanggungjawabModal')
        });
    });
</script>
@endpush