@extends('layouts.user-layout')

@section('title', 'Edit Profil & Ganti Password')

@section('content')
<div class="main-content mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">{{ __('Edit Profil') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <label for="first_name" class="col-md-4 col-form-label text-md-right">Nama Depan</label>
                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name', $user->first_name) }}" required autocomplete="first_name" autofocus>
                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="last_name" class="col-md-4 col-form-label text-md-right">Nama Belakang</label>
                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name', $user->last_name) }}">
                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nip" class="col-md-4 col-form-label text-md-right">NIP</label>
                            <div class="col-md-6">
                                <input id="nip" type="text" class="form-control @error('nip') is-invalid @enderror" name="nip" value="{{ old('nip', $user->nip) }}">
                                @error('nip')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="no_hp" class="col-md-4 col-form-label text-md-right">Nomor HP</label>
                            <div class="col-md-6">
                                <input id="no_hp" type="text" class="form-control @error('no_hp') is-invalid @enderror" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}">
                                @error('no_hp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="telegram_id" class="col-md-4 col-form-label text-md-right">Telegram ID</label>
                            <div class="col-md-6">
                                <input id="telegram_id" type="text" class="form-control @error('telegram_id') is-invalid @enderror" name="telegram_id" value="{{ old('telegram_id', $user->telegram_id) }}">
                                <small class="form-text text-muted">
                                    Kirim pesan <b>/start</b> ke bot Telegram Anda, lalu masukkan Telegram ID Anda di sini.<br>
                                    <a href="https://t.me/Helpdesk_Provinsi_Riau_Tetrisbot?start=1" target="_blank" class="text-primary">
                                        Klik di sini untuk membuka bot Telegram
                                    </a>
                                </small>
                                @error('telegram_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <button type="button" class="btn btn-info mt-2" id="btnTestTelegram">
                                    <i class="fab fa-telegram-plane"></i> Test Notifikasi Telegram
                                </button>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Update Profil
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">{{ __('Ganti Password') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <label for="current_password" class="col-md-4 col-form-label text-md-right">Password Sekarang</label>
                            <div class="col-md-6">
                                <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required autocomplete="current_password">
                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="new_password" class="col-md-4 col-form-label text-md-right">Password Baru</label>
                            <div class="col-md-6">
                                <input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" required autocomplete="new_password">
                                @error('new_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="new_password_confirmation" class="col-md-4 col-form-label text-md-right">Konfirmasi Password Baru</label>
                            <div class="col-md-6">
                                <input id="new_password_confirmation" type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" name="new_password_confirmation" required autocomplete="new_password_confirmation">
                                @error('new_password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Ganti Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    $('#btnTestTelegram').click(function() {
        let telegramId = $('#telegram_id').val();
        if (!telegramId) {
            toastr.warning('Masukkan Telegram ID terlebih dahulu');
            return;
        }
        $.ajax({
            url: "{{ route('profile.test-telegram') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                telegram_id: telegramId
            },
            beforeSend: function() {
                $('#btnTestTelegram').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Mengirim...');
            },
            success: function(response) {
                toastr.success(response.message || 'Notifikasi berhasil dikirim ke Telegram Anda');
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Gagal mengirim notifikasi');
            },
            complete: function() {
                $('#btnTestTelegram').prop('disabled', false).html('<i class="fab fa-telegram-plane"></i> Test Notifikasi Telegram');
            }
        });
    });
</script>
@endpush
@endsection