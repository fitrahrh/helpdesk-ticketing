<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editRoleForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="id" id="edit_role_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Nama Role</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Hak Akses</label>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="edit_all" name="hak_akses[]" value="all">
                                    <label class="form-check-label font-weight-bold" for="edit_all">
                                        All
                                    </label>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="edit_dashboard" value="dashboard">
                                            <label class="form-check-label" for="edit_dashboard">
                                                Dashboard
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="edit_data_master" value="data_master">
                                            <label class="form-check-label" for="edit_data_master">
                                                Data Master
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="edit_kelola_penanggungjawab" value="kelola_penanggungjawab">
                                            <label class="form-check-label" for="edit_kelola_penanggungjawab">
                                                Kelola Penanggung Jawab
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="edit_kelola_menu_tiket" value="kelola_menu_tiket">
                                            <label class="form-check-label" for="edit_kelola_menu_tiket">
                                                Kelola Menu Tiket
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="edit_disposisi_tiket" value="disposisi_tiket">
                                            <label class="form-check-label" for="edit_disposisi_tiket">
                                                Disposisi Tiket
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="edit_riwayat_tiket" value="riwayat_tiket">
                                            <label class="form-check-label" for="edit_riwayat_tiket">
                                                Riwayat Tiket
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="edit_laporan" value="laporan">
                                            <label class="form-check-label" for="edit_laporan">
                                                Laporan
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="edit_akses_pelapor" value="akses_pelapor">
                                            <label class="form-check-label" for="edit_akses_pelapor">
                                                Akses Pelapor
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="edit_akses_teknisi" value="akses_teknisi">
                                            <label class="form-check-label" for="edit_akses_teknisi">
                                                Akses Teknisi
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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