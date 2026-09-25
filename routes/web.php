<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;

// ===== PUBLIK =====
Route::get('/', [PublicController::class, 'landing'])->name('landing');
Route::get('/berita', [PublicController::class, 'beritaList'])->name('public.berita');
Route::get('/berita/{slug}', [PublicController::class, 'beritaShow'])->name('public.berita.show');
Route::get('/halaman/{slug}', [PublicController::class, 'pageShow'])->name('public.page');
Route::get('/help-center', function (\Illuminate\Http\Request $request) {
    if (!auth()->check()) {
        return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu untuk mengakses Help Center & Live Support.');
    }
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.helpdesk', $request->query());
    }
    return redirect()->route('mahasiswa.helpdesk', $request->query());
})->name('help-center');

// ===== AUTH =====
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===== MAHASISWA =====
Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/mahasiswa/dashboard', fn() => view('mahasiswa.dashboard'))->name('mahasiswa.dashboard');
    Route::get('/mahasiswa/helpdesk', fn() => view('mahasiswa.helpdesk'))->name('mahasiswa.helpdesk');
    Route::get('/mahasiswa/cetak-pdf', [\App\Http\Controllers\PdfController::class, 'cetakBukti'])->name('mahasiswa.cetak.pdf');
});

// ===== ADMIN =====
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');
    Route::get('/admin/periode', fn() => view('admin.periode.index'))->name('admin.periode');
    Route::get('/admin/master-data', fn() => view('admin.master-data.index'))->name('admin.master-data');
    Route::get('/admin/master-data/periode', fn() => redirect()->route('admin.periode'))->name('admin.master-data.periode');
    Route::get('/admin/master-data/mahasiswa', fn() => redirect()->route('admin.master-data'))->name('admin.master-data.mahasiswa');
    Route::get('/admin/master-data/template-import', [\App\Http\Controllers\ExportController::class, 'downloadImportTemplate'])->name('admin.master-data.template-import');
    Route::get('/admin/verifikasi/{id}', fn($id) => view('admin.verifikasi', compact('id')))->name('admin.verifikasi');
    Route::get('/admin/helpdesk', fn() => view('admin.helpdesk'))->name('admin.helpdesk');
    Route::get('/admin/help-center/incoming-check', [\App\Http\Controllers\Admin\HelpCenterController::class, 'incomingCheck'])->name('admin.help-center.incoming-check');
    Route::get('/admin/audit-log', fn() => view('admin.audit-log'))->name('admin.audit-log');

    // Pengaturan Sistem (Identitas, Galeri, Halaman, Berita, Pengumuman, Slider)
    Route::prefix('admin/pengaturan')->name('admin.pengaturan.')->group(function () {
        Route::get('/identitas', fn() => view('admin.pengaturan.identitas'))->name('identitas');
        Route::get('/galeri', fn() => view('admin.pengaturan.galeri'))->name('galeri');
        Route::get('/page', fn() => view('admin.pengaturan.page'))->name('page');
        Route::get('/artikel', fn() => view('admin.pengaturan.artikel'))->name('artikel');
        Route::get('/pengumuman', fn() => view('admin.pengaturan.pengumuman'))->name('pengumuman');
        Route::get('/slider', fn() => view('admin.pengaturan.slider'))->name('slider');
        Route::get('/infografis', fn() => view('admin.pengaturan.infografis'))->name('infografis');
        Route::get('/testimoni', fn() => view('admin.pengaturan.testimoni'))->name('testimoni');
        Route::get('/mitra', fn() => view('admin.pengaturan.mitra'))->name('mitra');
        Route::get('/kontak', fn() => view('admin.pengaturan.kontak'))->name('kontak');
        Route::get('/menu', fn() => view('admin.pengaturan.menu'))->name('menu');
        Route::get('/menu/{menu}/builder', fn($menu) => view('admin.pengaturan.menu-builder', compact('menu')))->name('menu.builder');
    });

    Route::get('/admin/galeri', fn() => redirect()->route('admin.pengaturan.galeri'))->name('admin.galeri');

    // Compatibility aliases for admin.cms.*
    Route::get('/admin/cms/slider', fn() => redirect()->route('admin.pengaturan.slider'))->name('admin.cms.slider');
    Route::get('/admin/cms/pengumuman', fn() => redirect()->route('admin.pengaturan.pengumuman'))->name('admin.cms.pengumuman');
    Route::get('/admin/cms/artikel', fn() => redirect()->route('admin.pengaturan.artikel'))->name('admin.cms.artikel');

    // Export
    Route::get('/admin/export/pddikti', [\App\Http\Controllers\ExportController::class, 'downloadPddikti'])->name('admin.export.pddikti');
    Route::get('/admin/export/siakad', [\App\Http\Controllers\ExportController::class, 'downloadSiakad'])->name('admin.export.siakad');
});
