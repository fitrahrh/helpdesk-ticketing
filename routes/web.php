<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExampleController;


Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\Ticket\UserTicketController::class, 'index'])->name('dashboard');

    Route::get('/admin', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changepassword'])->name('profile.change-password');
    Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::get('/blank-page', [App\Http\Controllers\HomeController::class, 'blank'])->name('blank');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Role management routes
    Route::get('/roles', [App\Http\Controllers\Roles\RoleController::class, 'index'])->name('roles.index');
    Route::get('/dataroles', [App\Http\Controllers\Roles\RoleController::class, 'dataroles'])->name('roles.data');
    Route::post('/roles', [App\Http\Controllers\Roles\RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}/edit', [App\Http\Controllers\Roles\RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{id}', [App\Http\Controllers\Roles\RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [App\Http\Controllers\Roles\RoleController::class, 'destroy'])->name('roles.destroy');

    // User management routes
    Route::get('/users', [App\Http\Controllers\Users\UserController::class, 'index'])->name('users.index');
    Route::get('/datausers', [App\Http\Controllers\Users\UserController::class, 'datausers'])->name('users.data');
    Route::post('/users', [App\Http\Controllers\Users\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [App\Http\Controllers\Users\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [App\Http\Controllers\Users\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [App\Http\Controllers\Users\UserController::class, 'destroy'])->name('users.destroy');

    // Skpd management routes
    Route::get('/skpd', [App\Http\Controllers\Skpds\SKPDController::class, 'index'])->name('skpd.index');
    Route::get('/skpd/data', [App\Http\Controllers\Skpds\SKPDController::class, 'dataskpd'])->name('skpd.data');
    Route::post('/skpd', [App\Http\Controllers\Skpds\SKPDController::class, 'store'])->name('skpd.store');
    Route::get('/skpd/{id}/edit', [App\Http\Controllers\Skpds\SKPDController::class, 'edit'])->name('skpd.edit');
    Route::put('/skpd/{id}', [App\Http\Controllers\Skpds\SKPDController::class, 'update'])->name('skpd.update');
    Route::delete('/skpd/{id}', [App\Http\Controllers\Skpds\SKPDController::class, 'destroy'])->name('skpd.destroy');

    // Bidang management routes
    Route::get('/bidang', [App\Http\Controllers\Bidangs\BidangController::class, 'index'])->name('bidang.index');
    Route::get('/bidang/data', [App\Http\Controllers\Bidangs\BidangController::class, 'databidang'])->name('bidang.data');
    Route::post('/bidang', [App\Http\Controllers\Bidangs\BidangController::class, 'store'])->name('bidang.store');
    Route::get('/bidang/{id}/edit', [App\Http\Controllers\Bidangs\BidangController::class, 'edit'])->name('bidang.edit');
    Route::put('/bidang/{id}', [App\Http\Controllers\Bidangs\BidangController::class, 'update'])->name('bidang.update');
    Route::delete('/bidang/{id}', [App\Http\Controllers\Bidangs\BidangController::class, 'destroy'])->name('bidang.destroy');

    // Jabatan management routes
    Route::get('/jabatan', [App\Http\Controllers\Jabatans\JabatanController::class, 'index'])->name('jabatan.index');
    Route::get('/jabatan/data', [App\Http\Controllers\Jabatans\JabatanController::class, 'datajabatan'])->name('jabatan.data');
    Route::post('/jabatan', [App\Http\Controllers\Jabatans\JabatanController::class, 'store'])->name('jabatan.store');
    Route::get('/jabatan/{id}/edit', [App\Http\Controllers\Jabatans\JabatanController::class, 'edit'])->name('jabatan.edit');
    Route::put('/jabatan/{id}', [App\Http\Controllers\Jabatans\JabatanController::class, 'update'])->name('jabatan.update');
    Route::delete('/jabatan/{id}', [App\Http\Controllers\Jabatans\JabatanController::class, 'destroy'])->name('jabatan.destroy');

    Route::get('/get-bidang/{skpd_id}', [App\Http\Controllers\Users\UserController::class, 'getBidangBySkpd']);
    Route::get('/get-jabatan/{bidang_id}', [App\Http\Controllers\Users\UserController::class, 'getJabatanByBidang']);
    Route::get('/get-jabatan-info/{jabatan_id}', [App\Http\Controllers\Users\UserController::class, 'getJabatanInfo']);

    Route::get('/kategori', [App\Http\Controllers\KategoriController\KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/data', [App\Http\Controllers\KategoriController\KategoriController::class, 'datakategori'])->name('kategori.data');
    Route::post('/kategori', [App\Http\Controllers\KategoriController\KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [App\Http\Controllers\KategoriController\KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id}', [App\Http\Controllers\KategoriController\KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [App\Http\Controllers\KategoriController\KategoriController::class, 'destroy'])->name('kategori.destroy');

    Route::get('/penanggungjawab', [App\Http\Controllers\Penanggungjawabs\PenanggungjawabController::class, 'index'])->name('penanggungjawab.index');
    Route::get('/penanggungjawab/data', [App\Http\Controllers\Penanggungjawabs\PenanggungjawabController::class, 'datapenanggungjawab'])->name('penanggungjawab.data');
    Route::post('/penanggungjawab', [App\Http\Controllers\Penanggungjawabs\PenanggungjawabController::class, 'store'])->name('penanggungjawab.store');
    Route::get('/penanggungjawab/{id}/edit', [App\Http\Controllers\Penanggungjawabs\PenanggungjawabController::class, 'edit'])->name('penanggungjawab.edit');
    Route::put('/penanggungjawab/{id}', [App\Http\Controllers\Penanggungjawabs\PenanggungjawabController::class, 'update'])->name('penanggungjawab.update');
    Route::delete('/penanggungjawab/{id}', [App\Http\Controllers\Penanggungjawabs\PenanggungjawabController::class, 'destroy'])->name('penanggungjawab.destroy');

    Route::get('/get-kategori-by-skpd/{skpd_id}', [App\Http\Controllers\Penanggungjawabs\PenanggungjawabController::class, 'getKategoriBySkpd'])->name('get-kategori-by-skpd');

    Route::get('/baru', [App\Http\Controllers\Ticket\TicketController::class, 'indexBaru'])->name('baru');
    Route::get('/baru/data', [App\Http\Controllers\Ticket\TicketController::class, 'dataTicketBaru'])->name('baru.data');
    
    // Diproses
    Route::get('/diproses', [App\Http\Controllers\Ticket\TicketController::class, 'indexDiproses'])->name('diproses');
    Route::get('/diproses/data', [App\Http\Controllers\Ticket\TicketController::class, 'dataTicketDiproses'])->name('diproses.data');
    
    // Disposisi
    Route::get('/disposisi', [App\Http\Controllers\Ticket\TicketController::class, 'indexDisposisi'])->name('disposisi');
    Route::get('/disposisi/data', [App\Http\Controllers\Ticket\TicketController::class, 'dataTicketDisposisi'])->name('disposisi.data');
    Route::put('/disposisi/{id}', [App\Http\Controllers\Ticket\TicketController::class, 'updateDisposisi'])->name('disposisi.update');
    
    // Selesai
    Route::get('/selesai', [App\Http\Controllers\Ticket\TicketController::class, 'indexSelesai'])->name('selesai');
    Route::get('/selesai/data', [App\Http\Controllers\Ticket\TicketController::class, 'dataTicketSelesai'])->name('selesai.data');
    
    // Ticket history
    Route::get('/history', [App\Http\Controllers\History\HistoryController::class, 'index'])->name('history.index');
    Route::get('/history/data', [App\Http\Controllers\History\HistoryController::class, 'data'])->name('history.data');

    Route::get('/harian', [App\Http\Controllers\Laporan\LaporanController::class, 'harian'])->name('report.harian');
    Route::get('/bulanan', [App\Http\Controllers\Laporan\LaporanController::class, 'bulanan'])->name('report.bulanan');
    Route::get('/berjangka', [App\Http\Controllers\Laporan\LaporanController::class, 'berjangka'])->name('report.berjangka');
});

// User Beranda & Ticket Management Routes
Route::middleware(['auth'])->prefix('ticket')->name('ticket.')->group(function () {

    // Ticket creation route
    Route::get('/create', [App\Http\Controllers\Ticket\UserTicketController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Ticket\UserTicketController::class, 'store'])->name('store');
    
    // Status routes - SPECIFIC ROUTES FIRST
    Route::get('/pending', [App\Http\Controllers\Ticket\UserTicketController::class, 'indexPending'])->name('pending');
    Route::get('/diproses', [App\Http\Controllers\Ticket\UserTicketController::class, 'indexDiproses'])->name('diproses');
    Route::get('/disposisi', [App\Http\Controllers\Ticket\UserTicketController::class, 'indexDisposisi'])->name('disposisi');
    Route::get('/selesai', [App\Http\Controllers\Ticket\UserTicketController::class, 'indexSelesai'])->name('selesai');
    Route::get('/proses/{id}', [App\Http\Controllers\Ticket\UserTicketController::class, 'show'])->name('ticket.show');
    
    // Data routes for AJAX
    Route::get('/pending/data', [App\Http\Controllers\Ticket\UserTicketController::class, 'pendingData'])->name('pending.data');
    Route::get('/diproses/data', [App\Http\Controllers\Ticket\UserTicketController::class, 'diprosesData'])->name('diproses.data');
    Route::get('/disposisi/data', [App\Http\Controllers\Ticket\UserTicketController::class, 'disposisiData'])->name('disposisi.data');
    Route::get('/selesai/data', [App\Http\Controllers\Ticket\UserTicketController::class, 'selesaiData'])->name('selesai.data');
    
    // Feedback route
    Route::post('/feedback/store', [App\Http\Controllers\Feedback\FeedbackController::class, 'store'])->name('feedback.store');
    
    // Comment route
    Route::post('/comment/store', [App\Http\Controllers\Comments\CommentController::class, 'store'])->name('comment.store');
    Route::post('/comment/mark-as-read', [App\Http\Controllers\Comments\CommentController::class, 'markAsRead'])->name('comment.markAsRead');
}); 

// Teknisi Ticket Routes
Route::middleware(['auth'])->prefix('teknisi')->name('teknisi.')->group(function() {
    // Baru Tickets
    Route::get('/baru', [App\Http\Controllers\Ticket\TeknisiTicketController::class, 'indexBaru'])->name('baru');
    Route::get('/baru/data', [App\Http\Controllers\Ticket\TeknisiTicketController::class, 'baruData'])->name('baru.data');
    
    // Diproses Tickets
    Route::get('/diproses', [App\Http\Controllers\Ticket\TeknisiTicketController::class, 'indexDiproses'])->name('diproses');
    Route::get('/diproses/data', [App\Http\Controllers\Ticket\TeknisiTicketController::class, 'diprosesData'])->name('diproses.data');
    
    // Selesai Tickets
    Route::get('/selesai', [App\Http\Controllers\Ticket\TeknisiTicketController::class, 'indexSelesai'])->name('selesai');
    Route::get('/selesai/data', [App\Http\Controllers\Ticket\TeknisiTicketController::class, 'selesaiData'])->name('selesai.data');
    
    // Ticket Details
    Route::get('/ticket/{id}', [App\Http\Controllers\Ticket\TeknisiTicketController::class, 'show'])->name('ticket.show');
    
    // Ticket Actions
    Route::put('/ticket/{id}/approve', [App\Http\Controllers\Ticket\TeknisiTicketController::class, 'approve'])->name('ticket.approve');
    Route::put('/ticket/{id}/disposisi', [App\Http\Controllers\Ticket\TeknisiTicketController::class, 'disposisi'])->name('ticket.disposisi');
    Route::put('/ticket/{id}/close', [App\Http\Controllers\Ticket\TeknisiTicketController::class, 'close'])->name('ticket.close');
    Route::put('/ticket/{id}/urgency', [App\Http\Controllers\Ticket\TeknisiTicketController::class, 'updateUrgency'])->name('ticket.urgency');

    // Comment route
    Route::post('/comment/store', [App\Http\Controllers\Comments\CommentController::class, 'store'])->name('comment.store');
    // Mark comments as read
    Route::post('/comment/mark-as-read', [App\Http\Controllers\Comments\CommentController::class, 'markAsRead'])->name('comment.markAsRead');
});

