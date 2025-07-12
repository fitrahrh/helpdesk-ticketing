<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="editUserForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="user_id" name="user_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card card-warning">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="edit_first_name">Nama Depan</label>
                                        <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_last_name">Nama Belakang</label>
                                        <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_email">Email</label>
                                        <input type="email" class="form-control" id="edit_email" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_password">Password</label>
                                        <input type="password" class="form-control" id="edit_password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="edit_nip">NIP</label>
                                        <input type="text" class="form-control" id="edit_nip" name="nip">
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_no_hp">Nomor HP</label>
                                        <input type="text" class="form-control" id="edit_no_hp" name="no_hp">
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_telegram_id">Telegram ID</label>
                                        <input type="text" class="form-control" id="edit_telegram_id" name="telegram_id">
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_skpd_id">SKPD</label>
                                        <select class="form-control" id="edit_skpd_id" name="skpd_id">
                                            <option value="">Pilih SKPD</option>
                                            @foreach($skpds as $skpd)
                                            <option value="{{ $skpd->id }}">{{ $skpd->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_bidang_id">Bidang</label>
                                        <select class="form-control select2" id="edit_bidang_id" name="bidang_id" disabled>
                                            <option value="">Pilih Bidang</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_jabatan_id">Jabatan</label>
                                        <select class="form-control select2" id="edit_jabatan_id" name="jabatan_id" disabled>
                                            <option value="">Pilih Jabatan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit_role_id">Role</label>
                                <select class="form-control" id="edit_role_id" name="role_id" required>
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
                    <button type="submit" class="btn btn-warning" id="updateBtn">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>