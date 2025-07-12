<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="createUserForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Tambah User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">Nama Depan</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name">Nama Belakang</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nip">NIP</label>
                                        <input type="text" class="form-control" id="nip" name="nip">
                                    </div>
                                    <div class="form-group">
                                        <label for="no_hp">Nomor HP</label>
                                        <input type="text" class="form-control" id="no_hp" name="no_hp">
                                    </div>
                                    <div class="form-group">
                                        <label for="telegram_id">Telegram ID</label>
                                        <input type="text" class="form-control" id="telegram_id" name="telegram_id">
                                    </div>
                                    <div class="form-group">
                                        <label for="skpd_id">SKPD</label>
                                        <select class="form-control select2" id="skpd_id" name="skpd_id">
                                            <option value="">Pilih SKPD</option>
                                            @foreach($skpds as $skpd)
                                            <option value="{{ $skpd->id }}">{{ $skpd->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="create_bidang_id">Bidang</label>
                                        <select class="form-control select2" id="create_bidang_id" name="bidang_id" disabled>
                                            <option value="">Pilih Bidang</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="jabatan_id">Jabatan</label>
                                        <select class="form-control select2" id="create_jabatan_id" name="jabatan_id" disabled>
                                            <option value="">Pilih Jabatan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="role_id">Role</label>
                                <select class="form-control" id="role_id" name="role_id" required>
                                    <option value="">Pilih Role</option>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
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