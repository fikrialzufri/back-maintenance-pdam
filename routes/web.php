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
use App\Http\Controllers\PelaksanaanPekerjaanController;
use App\Http\Controllers\PenunjukanPekerjaanController;
use App\Http\Controllers\RekananController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TagihanController;
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
    Route::get('upload/item', [ItemController::class, 'upload'])->name('item.upload');
    Route::get('itemdetail', [ItemController::class, 'getdetail'])->name('item.detail');
    Route::post('upload/item', [ItemController::class, 'uploaddata'])->name('item.uploaddata');

    Route::resource('jenis-aduan', JenisAduanController::class, ['names' => 'jenis_aduan']);

    // Aduan
    Route::resource('aduan', AduanController::class)->except('show');

    Route::get('notifikasi/aduan/{id}', [PenunjukanPekerjaanController::class, 'opennotifikasi'])->name('penunjukan_pekerjaan.notification');

    Route::resource('penunjukan-pekerjaan', PenunjukanPekerjaanController::class, ['names' => 'penunjukan_pekerjaan'])->except('destroy');

    Route::get('upload/penunjukan-pekerjaan', [PenunjukanPekerjaanController::class, 'upload'])->name('penunjukan_pekerjaan.upload');

    Route::post('upload/penunjukan-pekerjaan', [PenunjukanPekerjaanController::class, 'uploaddata'])->name('penunjukan_pekerjaan.uploaddata');

    Route::post('tambah-galian', [PelaksanaanPekerjaanController::class, 'galian'])->name('pelaksanaan-pekerjaan.galian');
    Route::post('hapus-galian', [PelaksanaanPekerjaanController::class, 'hapusgalian'])->name('pelaksanaan-pekerjaan.galian.hapus');

    Route::post('tambah-item', [PelaksanaanPekerjaanController::class, 'item'])->name('pelaksanaan-pekerjaan.item');
    Route::post('hapus-item', [PelaksanaanPekerjaanController::class, 'hapusitem'])->name('pelaksanaan-pekerjaan.hapus.item');

    Route::resource('pelaksanaan-pekerjaan', PelaksanaanPekerjaanController::class);
    //

    // tagihan
    Route::resource('tagihan', TagihanController::class);
    Route::get('upload/tagihan', [TagihanController::class, 'upload'])->name('tagihan.upload');
    Route::post('upload/tagihan', [TagihanController::class, 'uploaddata'])->name('tagihan.uploaddata');
    Route::post('adjusttagihan', [TagihanController::class, 'adjust'])->name('tagihan.adjust');
    Route::get('exxceltagihan', [TagihanController::class, 'exxceltagihan'])->name('tagihan.excel');
    Route::get('wordtagihan', [TagihanController::class, 'wordtagihan'])->name('tagihan.word');


    // Karyawan
    Route::resource('departemen', DepartemenController::class);
    Route::resource('wilayah', WilayahController::class);
    Route::resource('divisi', DivisiController::class);
    Route::resource('jabatan', JabatanController::class);
    Route::resource('karyawan', KaryawanController::class);

    // Rekanan
    Route::resource('rekanan', RekananController::class);
    Route::get('upload/rekanan', [RekananController::class, 'upload'])->name('rekanan.upload');
    Route::post('upload/rekanan', [RekananController::class, 'uploaddata'])->name('rekanan.uploaddata');

    // Notifikasi
    Route::get('notification', [NotifikasiController::class, 'index'])->name('notification');

    // Setting
    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::post('/setting', [SettingController::class, 'store'])->name('setting.store');
});

Route::get('previewtagihan/{slug}', [TagihanController::class, 'preview'])->name('tagihan.preview');
