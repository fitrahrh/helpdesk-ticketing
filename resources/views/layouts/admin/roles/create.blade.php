<!-- Add Role Modal -->
<div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-labelledby="roleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roleModalLabel">Tambah Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="roleForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="method" value="POST">
                <input type="hidden" name="id" id="role_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Role</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Hak Akses</label>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="all" name="hak_akses[]" value="all">
                                    <label class="form-check-label font-weight-bold" for="all">
                                        All
                                    </label>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="dashboard" value="dashboard">
                                            <label class="form-check-label" for="dashboard">
                                                Dashboard
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="pengajuan_tiket" value="pengajuan tiket">
                                            <label class="form-check-label" for="pengajuan_tiket">
                                                Pengajuan Tiket
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="persetujuan_tiket" value="persetujuan tiket">
                                            <label class="form-check-label" for="persetujuan_tiket">
                                                Persetujuan Tiket
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="disposisi_tiket" value="disposisi tiket">
                                            <label class="form-check-label" for="disposisi_tiket">
                                                Disposisi Tiket
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="all_tiket" value="all tiket">
                                            <label class="form-check-label" for="all_tiket">
                                                All Tiket
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="kelola_user" value="kelola user">
                                            <label class="form-check-label" for="kelola_user">
                                                Kelola User
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="kelola_role" value="kelola role">
                                            <label class="form-check-label" for="kelola_role">
                                                Kelola Role
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="kelola_penanggungjawab" value="kelola penanggungjawab">
                                            <label class="form-check-label" for="kelola_penanggungjawab">
                                                Kelola Penanggungjawab
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="laporan" value="laporan">
                                            <label class="form-check-label" for="laporan">
                                                Laporan
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
                    <button type="submit" class="btn btn-primary" id="saveBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>