<div class="modal fade" id="createKategoriModal" tabindex="-1" role="dialog" aria-labelledby="createKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createKategoriModalLabel">Tambah Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createKategoriForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Kategori</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="skpd_id">SKPD</label>
                        <select class="form-control" id="skpd_id" name="skpd_id" required>
                            <option value="">Pilih SKPD</option>
                            @foreach($skpds as $skpd)
                                <option value="{{ $skpd->id }}">{{ $skpd->name }}</option>
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