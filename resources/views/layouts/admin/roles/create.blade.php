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
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="data_master" value="data_master">
                                            <label class="form-check-label" for="data_master">
                                                Data Master
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="kelola_penanggungjawab" value="kelola_penanggungjawab">
                                            <label class="form-check-label" for="kelola_penanggungjawab">
                                                Kelola Penanggung Jawab
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="kelola_menu_tiket" value="kelola_menu_tiket">
                                            <label class="form-check-label" for="kelola_menu_tiket">
                                                Kelola Menu Tiket
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="disposisi_tiket" value="disposisi_tiket">
                                            <label class="form-check-label" for="disposisi_tiket">
                                                Disposisi Tiket
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="riwayat_tiket" value="riwayat_tiket">
                                            <label class="form-check-label" for="riwayat_tiket">
                                                Riwayat Tiket
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="laporan" value="laporan">
                                            <label class="form-check-label" for="laporan">
                                                Laporan
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="akses_pelapor" value="akses_pelapor">
                                            <label class="form-check-label" for="akses_pelapor">
                                                Akses Pelapor
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input permission-checkbox" type="checkbox" name="hak_akses[]" id="akses_teknisi" value="akses_teknisi">
                                            <label class="form-check-label" for="akses_teknisi">
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
                    <button type="submit" class="btn btn-primary" id="saveBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>