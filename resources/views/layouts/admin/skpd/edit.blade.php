<!-- Edit SKPD Modal -->
<div class="modal fade" id="editSKPDModal" tabindex="-1" role="dialog" aria-labelledby="editSKPDModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSKPDModalLabel">Edit SKPD</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editSKPDForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="id" id="skpd_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Nama SKPD</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_singkatan">Singkatan</label>
                        <input type="text" class="form-control" id="edit_singkatan" name="singkatan" required>
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