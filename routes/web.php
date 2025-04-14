<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerpustakaanController;
use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\KategoriController;
use App\Models\Kategori;

Route::get('/', function () {
    return view('welcome');
});

// Group untuk auth dan verified user
Route::middleware(['auth', 'verified'])->group(function () {

    // Halaman khusus admin
    Route::middleware(['role:admin'])->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');
        Route::resource('/manage-user',userkos::class);

        // API Admin
        Route::get('/kategoris/data', [KategoriController::class, 'getData'])->name('kategoris.data');
        Route::get('/dashboard/chart-data', [DashboardAdminController::class, 'getChartData']);
        Route::get('/admin/buku/data', [BukuController::class, 'getData'])->name('admin.buku.data');
        Route::get('admin/peminjamans/data', [PeminjamanController::class, 'data'])->name('peminjamans.data');

        // kategori
        Route::get('/kategoris/{id}', [KategoriController::class, 'show'])->name('kategoris.show');
        Route::get('/admin/kategoris', [KategoriController::class, 'index'])->name('kategoris.index');
        Route::get('/admin/kategoris/create', [KategoriController::class, 'create'])->name('kategoris.create');
        Route::post('/admin/kategoris/store', [KategoriController::class, 'store'])->name('kategoris.store');
        Route::get('/admin/kategoris/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategoris.edit');
        Route::put('/admin/kategoris/{kategori}', [KategoriController::class, 'update'])->name('kategoris.update');
        Route::delete('/admin/kategoris/{kategori}', [KategoriController::class, 'destroy'])->name('kategoris.destroy');
       
        // Buku 
        Route::get('/admin/buku', [BukuController::class, 'index'])->name('admin.buku.index');
        Route::get('/admin/buku/create', [BukuController::class, 'create'])->name('admin.bukus.create');
        Route::post('/admin/buku/store', [BukuController::class, 'store'])->name('bukus.store');
        Route::get('/admin/buku/{buku}/edit', [BukuController::class, 'edit'])->name('bukus.edit');
        Route::put('/admin/buku/{buku}', [BukuController::class, 'update'])->name('bukus.update');
        Route::delete('/admin/buku/{buku}', [BukuController::class, 'destroy'])->name('bukus.destroy');
        Route::post('/admin/bukus/import', [BukuController::class, 'import'])->name('admin.bukus.import');


        // peminjaman
        Route::get('admin/peminjamans', [PeminjamanController::class, 'index'])->name('peminjamans.index');
        Route::get('admin/peminjamans/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjamans.kembalikan');
        Route::get('admin/peminjamans/create', [PeminjamanController::class, 'create'])->name('peminjamans.create');
        Route::post('admin/peminjamans/store', [PeminjamanController::class, 'store'])->name('peminjamans.store');
        Route::get('admin/peminjamans/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjamans.show');
        Route::get('admin/peminjamans/{peminjaman}/edit', [PeminjamanController::class, 'edit'])->name('peminjamans.edit');
        Route::put('admin/peminjamans/{peminjaman}', [PeminjamanController::class, 'update'])->name('peminjamans.update');
        Route::delete('admin/peminjamans/{peminjaman}', [PeminjamanController::class, 'destroy'])->name('peminjamans.destroy');

        //list penguna
        Route::get('admin/data-user', [UserController::class, 'getUsers'])->name('users.data');
    });

    // Halaman khusus user
    Route::middleware(['role:user'])->group(function () {
        //Api User
        Route::get('user/perpustakaan/buku-list', [PerpustakaanController::class, 'getBukuList'])->name('getBukuList');
        Route::get('peminjaman/data', [PerpustakaanController::class, 'data'])->name('peminjaman.data');

        Route::get('user/perpustakaan', [PerpustakaanController::class, 'index'])->name('user.dashboard');
        Route::get('user/perpustakaan/all-buku', [PerpustakaanController::class, 'ShowMore'])->name('user.ShowMore');
       
        Route::get('user/peminjaman/update-status/{id}', [PerpustakaanController::class, 'updateStatus'])->name('peminjaman.updateStatus');
        Route::post('user/perpustakaan/pinjam/{id}', [PerpustakaanController::class, 'pinjam'])->name('peminjaman.pinjam');
        Route::get('user/perpustakaan/riwayat', [PerpustakaanController::class, 'riwayat'])->name('peminjaman.riwayat');
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});

require __DIR__.'/auth.php';
