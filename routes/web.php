<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Dosen\AuthController as DosenAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PengajuanController as AdminPengajuanController;
use App\Http\Controllers\Admin\ValidasiController;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LuaranController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RiwayatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public / Redirect Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes (Dosen & Admin Terpisah)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Login Dosen (Menggunakan DosenAuthController)
    Route::get('/login', [DosenAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [DosenAuthController::class, 'login'])->name('login.submit');
    
    // Login Khusus Admin (Menggunakan AdminAuthController)
    Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
});

// Logout Dosen
Route::post('/logout', [DosenAuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Area Dosen (Wajib Login + Role Dosen)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/ubah-password', [DosenAuthController::class, 'showUbahPassword'])->name('ubah-password');
    Route::post('/ubah-password', [DosenAuthController::class, 'ubahPassword'])->name('ubah-password.submit');

    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::post('/profil/foto', [ProfilController::class, 'updateFoto'])->name('profil.foto');

    Route::prefix('pengajuan-baru')->group(function () {
        Route::get('/', [PengajuanController::class, 'step1'])->name('pengajuan.step1');
        Route::post('/', [PengajuanController::class, 'postStep1'])->name('pengajuan.step1.post');

        Route::get('/tim', [PengajuanController::class, 'step2'])->name('pengajuan.step2');
        Route::post('/tim', [PengajuanController::class, 'postStep2'])->name('pengajuan.step2.post');

        Route::get('/proposal', [PengajuanController::class, 'step3'])->name('pengajuan.step3');
        Route::post('/proposal', [PengajuanController::class, 'postStep3'])->name('pengajuan.step3.post');

        Route::get('/luaran', [PengajuanController::class, 'step4'])->name('pengajuan.step4');
        Route::post('/luaran', [PengajuanController::class, 'postStep4'])->name('pengajuan.step4.post');

        Route::get('/review', [PengajuanController::class, 'step5'])->name('pengajuan.step5');
        Route::post('/kirim', [PengajuanController::class, 'submit'])->name('pengajuan.submit');
        Route::post('/batal', [PengajuanController::class, 'batal'])->name('pengajuan.batal');
    });

    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat');
    Route::get('/pengajuan/{pengajuan}', [RiwayatController::class, 'detail'])->name('pengajuan.detail');

    Route::get('/laporan/kemajuan', [LaporanController::class, 'kemajuan'])->name('laporan.kemajuan');
    Route::post('/laporan/kemajuan/{pengajuan}', [LaporanController::class, 'kemajuanStore'])->name('laporan.kemajuan.store');

    Route::get('/laporan/{tipe}', [LaporanController::class, 'index'])->whereIn('tipe', ['hasil'])->name('laporan.index');
    Route::get('/laporan/{tipe}/{pengajuan}', [LaporanController::class, 'form'])->whereIn('tipe', ['hasil'])->name('laporan.form');
    Route::post('/laporan/{tipe}/{pengajuan}', [LaporanController::class, 'store'])->whereIn('tipe', ['hasil'])->name('laporan.store');

    Route::get('/luaran', [LuaranController::class, 'index'])->name('luaran.index');
    Route::get('/luaran/{luaran}', [LuaranController::class, 'form'])->name('luaran.form');
    Route::post('/luaran/{luaran}', [LuaranController::class, 'store'])->name('luaran.store');
});


/*
|--------------------------------------------------------------------------
| Area Admin (Wajib Login + Role Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Logout khusus Admin
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Dashboard Utama Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Menu Daftar Pengajuan
    Route::get('/penelitian', [AdminPengajuanController::class, 'penelitian'])->name('penelitian');
    Route::get('/pengabdian', [AdminPengajuanController::class, 'pengabdian'])->name('pengabdian');
    Route::get('/semua-pengajuan', [AdminPengajuanController::class, 'semua'])->name('semua-pengajuan');
    Route::get('/pengajuan/{id}/dokumen', [AdminPengajuanController::class, 'showDokumen'])->name('pengajuan.dokumen');

    // Menu Validasi Proposal & Laporan oleh Admin
    Route::prefix('validasi')->name('validasi.')->group(function () {
        Route::get('/proposal', [ValidasiController::class, 'index'])->name('proposal.index');
        Route::get('/proposal', [ValidasiController::class, 'index'])->name('proposal'); 
        
        Route::get('/proposal/{id}', [ValidasiController::class, 'proposal'])->name('proposal.detail');
        Route::post('/update/{id}', [ValidasiController::class, 'updateValidasi'])->name('proposal.update');

        Route::get('/laporan-kemajuan', [ValidasiController::class, 'kemajuanIndex'])->name('laporan-kemajuan');
        Route::get('/laporan-kemajuan/{id}', [ValidasiController::class, 'kemajuanDetail'])->name('laporan-kemajuan.detail');
        Route::post('/laporan-kemajuan/{id}', [ValidasiController::class, 'kemajuanUpdate'])->name('laporan-kemajuan.update');

        Route::get('/laporan-hasil', function () {
            return view('admin.validasi.laporan_hasil');
        })->name('laporan_hasil');
    });
    
    // ==========================================
    // MENU MASTER DATA (Digabung dalam MasterDataController)
    // ==========================================
    Route::prefix('master')->name('master.')->group(function () {
        // Skema
        Route::get('/skema', [MasterDataController::class, 'skemaIndex'])->name('skema');
        Route::post('/skema', [MasterDataController::class, 'skemaStore'])->name('skema.store');
        Route::put('/skema/{id}', [MasterDataController::class, 'skemaUpdate'])->name('skema.update');
        Route::delete('/skema/{id}', [MasterDataController::class, 'skemaDestroy'])->name('skema.destroy');
    
        // Data Pegawai & Import Excel
        Route::get('/pegawai', [MasterDataController::class, 'pegawaiIndex'])->name('pegawai');
        Route::post('/pegawai/import', [MasterDataController::class, 'pegawaiImport'])->name('pegawai.import');
        Route::get('/pegawai/template', [MasterDataController::class, 'pegawaiDownloadTemplate'])->name('pegawai.template');
        Route::post('/pegawai', [MasterDataController::class, 'pegawaiStore'])->name('pegawai.store');
        Route::put('/pegawai/{id}', [MasterDataController::class, 'pegawaiUpdate'])->name('pegawai.update');
        Route::delete('/pegawai/{id}', [MasterDataController::class, 'pegawaiDestroy'])->name('pegawai.destroy');

        // Rumpun Ilmu
        Route::get('/rumpun-ilmu', [MasterDataController::class, 'rumpunIlmuIndex'])->name('rumpun');
        Route::post('/rumpun-ilmu', [MasterDataController::class, 'rumpunStore'])->name('rumpun.store');
        Route::put('/rumpun-ilmu/{id}', [MasterDataController::class, 'rumpunUpdate'])->name('rumpun.update');
        Route::delete('/rumpun-ilmu/{id}', [MasterDataController::class, 'rumpunDestroy'])->name('rumpun.destroy');
        
        // Luaran Master
        Route::get('/luaran', [MasterDataController::class, 'luaranIndex'])->name('luaran');
        Route::post('/luaran', [MasterDataController::class, 'luaranStore'])->name('luaran.store');
        Route::put('/luaran/{id}', [MasterDataController::class, 'luaranUpdate'])->name('luaran.update');
        Route::delete('/luaran/{id}', [MasterDataController::class, 'luaranDestroy'])->name('luaran.destroy');
    });

    Route::get('/laporan', function () {
        return view('admin.laporan');
    })->name('laporan');

    // Activity Log
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity_log');

    // Notifikasi
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi');
    Route::post('/notifikasi/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifikasi.readAll');
    Route::post('/notifikasi/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifikasi.read');
});