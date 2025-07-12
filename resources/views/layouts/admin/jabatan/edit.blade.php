<!-- Edit Jabatan Modal -->
<div class="modal fade" id="editJabatanModal" tabindex="-1" role="dialog" aria-labelledby="editJabatanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editJabatanModalLabel">Edit Jabatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editJabatanForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="id" id="jabatan_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Nama Jabatan</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_bidang_id">Bidang</label>
                        <select class="form-control" id="edit_bidang_id" name="bidang_id" required>
                            <option value="">Pilih Bidang</option>
                            @foreach($bidangs as $bidang)
                                <option value="{{ $bidang->id }}">{{ $bidang->name }} ({{ $bidang->skpd->name }})</option>
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