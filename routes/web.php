<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\JenisAduanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AduanController;
use App\Http\Controllers\PenunjukanPekerjaanController;
use App\Http\Controllers\RekananController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');

    //ACL -- Access Control List
    Route::resource('user', UserController::class);
    Route::resource('role', RoleController::class);
    Route::resource('task', TaskController::class);

    // ubah profile
    Route::get('/ubahuser', [UserController::class, 'ubah'])->name('user.ubah');
    Route::put('/simpanuser', [UserController::class, 'simpan'])->name('user.simpan');
    Route::put('/save-token', [UserController::class, 'token'])->name('user.token');
    Route::get('/user-notification', [UserController::class, 'notification'])->name('user.notification');

    // Data Item
    Route::resource('satuan', SatuanController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('jenis', JenisController::class);
    Route::resource('item', ItemController::class);
    Route::resource('jenis-aduan', JenisAduanController::class, ['names' => 'jenis_aduan']);

    // Aduan
    Route::resource('aduan', AduanController::class)->except('show');
    Route::get('notifikasi/aduan/{id}', [AduanController::class, 'notifikasi'])->name('aduan.notification');
    Route::resource('penunjukan-pekerjaan', PenunjukanPekerjaanController::class, ['names' => 'penunjukan_pekerjaan'])->except('destroy');

    // Karyawan
    Route::resource('departemen', DepartemenController::class);
    Route::resource('wilayah', WilayahController::class);
    Route::resource('divisi', DivisiController::class);
    Route::resource('jabatan', JabatanController::class);
    Route::resource('karyawan', KaryawanController::class);

    // Rekanan
    Route::resource('rekanan', RekananController::class);
});
