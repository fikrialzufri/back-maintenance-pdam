<?php

use App\Http\Controllers\Api\AduanController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\JenisController;
use App\Http\Controllers\Api\JenisAduanController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\PenunjukanPekerjaanController;
use App\Http\Controllers\Api\RekananController;
use App\Http\Controllers\Api\PelaksanaanPekerjaanController;
use App\Http\Controllers\Api\TagihanController;
use App\Http\Controllers\NotifikasiController;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;


Route::get(
    '/csrf-cookie',
    CsrfCookieController::class . '@show'
)->middleware('web')->name('auth.cookies');


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('me', AuthController::class . '@me')->name('auth.me');
    Route::get('refresh', AuthController::class . '@refresh')->name('auth.refresh');

    // Item
    Route::get('item', ItemController::class . '@index')->name('item.api.index');
    Route::get('item/{slug}', ItemController::class . '@show')->name('item.api.show');

    // Jenis
    Route::get('jenis', JenisController::class . '@index')->name('jenis.api.index');

    // Jenis Aduan
    Route::get('jenis-aduan', JenisAduanController::class . '@index')->name('jenis-aduan.api.index');

    // Rekanan
    Route::get('rekanan', RekananController::class . '@index')->name('rekanan.api.index');

    // Aduan
    Route::get('aduan', AduanController::class . '@index')->name('aduan.api.index');

    // list penunjukan-pekerjaan
    Route::get('list-pekerjaan', PenunjukanPekerjaanController::class . '@index')->name('penunjukan.api.index');

    // simpan penunjukan-pekerjaan
    Route::post('penunjukan-pekerjaan', PenunjukanPekerjaanController::class . '@store')->name('penunjukan.api.store');

    // pelaksanaan-pekerjaan
    Route::get('pelaksanaan-pekerjaan', PelaksanaanPekerjaanController::class . '@index')->name('pelaksanaan.api.index');

    // proses terima pekerjaan
    Route::post('proses-pekerjaan', PelaksanaanPekerjaanController::class . '@store')->name('pelaksanaan.api.store');

    // update tag lokasi pekerjaan
    Route::post('update-pekerjaan', PelaksanaanPekerjaanController::class . '@proses')->name('pelaksanaan.api.proses');

    // proses selesai pekerjaan
    Route::post('bahan-pekerjaan', PelaksanaanPekerjaanController::class . '@prosesAkhir')->name('pelaksanaan.api.proses.akhir');

    // bahan dari pelaksanaan-pekerjaan
    Route::post('selesai-pekerjaan', PelaksanaanPekerjaanController::class . '@selesai')->name('pelaksanaan.api.selesai');

    // update tag lokasi pekerjaan
    Route::post('setujui-pekerjaan', PelaksanaanPekerjaanController::class . '@update')->name('pelaksanaan.api.status');

    // bahan dari pelaksanaan-pekerjaan
    Route::post('item-pekerjaan', PelaksanaanPekerjaanController::class . '@item')->name('pelaksanaan.api.item');

    Route::post('item-pekerjaan-remove', PelaksanaanPekerjaanController::class . '@itemRemove')->name('pelaksanaan.api.item.delete');

    Route::post('galian-pekerjaan', PelaksanaanPekerjaanController::class . '@galian')->name('pelaksanaan.api.galian');

    Route::post('galian-pekerjaan-remove', PelaksanaanPekerjaanController::class . '@galianRemove')->name('pelaksanaan.api.galian.delete');

    // modul tagihan
    // list pekerjaan yang selesai
    Route::get('list-pekerjaan-rekanan', PelaksanaanPekerjaanController::class . '@index')->name('pelaksanaan.api.list-pekerjaan');

    Route::get('list-tagihan', TagihanController::class . '@index')->name('tagihan.api.index');

    // tagiahan
    Route::get('tagihan', TagihanController::class . '@show')->name('tagihan.api.show');

    Route::post('simpan-tagihan', TagihanController::class . '@store')->name('tagihan.api.store');

    Route::get('refresh', AuthController::class . '@refresh')->name('auth.refresh');

    // uplod pelaksanaan-pekerjaan
    Route::post('media', MediaController::class . '@store')->name('media.api.store');
    // uplod pelaksanaan-pekerjaan
    Route::post('media-remove', MediaController::class . '@destroy')->name('media.api.destroy');

    Route::get('notification', [NotifikasiController::class, 'all'])->name('notification.api');
    Route::get('notification/{id}', [NotifikasiController::class, 'detail'])->name('notification.api.detail');
});
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
