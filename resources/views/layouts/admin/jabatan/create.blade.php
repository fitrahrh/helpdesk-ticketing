<!-- Create Jabatan Modal -->
<div class="modal fade" id="createJabatanModal" tabindex="-1" role="dialog" aria-labelledby="createJabatanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createJabatanModalLabel">Tambah Jabatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createJabatanForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Jabatan</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="bidang_id">Bidang</label>
                        <select class="form-control" id="bidang_id" name="bidang_id" required>
                            <option value="">Pilih Bidang</option>
                            @foreach($bidangs as $bidang)
                                <option value="{{ $bidang->id }}">{{ $bidang->name }} ({{ $bidang->skpd->name }})</option>
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