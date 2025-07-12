<!-- Edit Bidang Modal -->
<div class="modal fade" id="editBidangModal" tabindex="-1" role="dialog" aria-labelledby="editBidangModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBidangModalLabel">Edit Bidang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editBidangForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="id" id="bidang_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Nama Bidang</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_skpd_id">SKPD</label>
                        <select class="form-control" id="edit_skpd_id" name="skpd_id" required>
                            <option value="">Pilih SKPD</option>
                            @foreach($skpds as $skpd)
                                <option value="{{ $skpd->id }}">{{ $skpd->name }}</option>
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