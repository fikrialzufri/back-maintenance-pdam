<?php

namespace App\Jobs;

use App\Exports\PelaksanaanPekerjaanExport;
use App\Models\Notifikasi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Excel;
use Illuminate\Support\Facades\Queue;

class GenerateExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $start, $end, $kategori, $rekanan_id, $status, $namaFile;

    public $timeout = 9999999;
    function __construct($start, $end, $kategori, $rekanan_id, $status, $namaFile)
    {
        $this->start = $start;
        $this->end = $end;
        $this->kategori = $kategori;
        $this->rekanan_id = $rekanan_id;
        $this->status = $status;
        $this->namaFile = $namaFile;

        Queue::after(function ($connection, $job, $data) {
            if ($job instanceof GenerateExcel) {
                // logger('GenerateExcel job processed: ' . $job->getRawBody());
                // $notification = new Notifikasi();
                // $notification->modul_id = "Laporan";
                // $notification->title = "Pekerjaan";
                // $notification->body = "body";
                // $notification->modul = "apa";
                // $notification->modul_slug = "slug";
                // $notification->status = 'belum';
                // $notification->from_user_id = "apa";
                // $notification->to_user_id = "to_user_id";
                // $notification->save();
            }
        });
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $start = $this->start;
        $end = $this->end;
        $kategori = $this->kategori;
        $rekanan_id = $this->rekanan_id;
        $status = $this->status;
        $end = $this->end;
        $kategori = $this->kategori;
        $rekanan_id = $this->rekanan_id;
        $status = $this->status;
        $namaFile = $this->namaFile;

        // return Excel::download(new PelaksanaanPekerjaanExport($start, $end, $kategori, $rekanan_id, $status), 'Export Pekerjaan ' . $namaFile . '.xlsx');
        Excel::store(new PelaksanaanPekerjaanExport($start, $end, $kategori, $rekanan_id, $status), 'public/Export Pekerjaan ' . $namaFile . '.xlsx');
        $notification = new Notifikasi();
        $notification->modul_id = "Laporan";
        $notification->title = "Pekerjaan";
        $notification->body = "body";
        $notification->modul = "apa";
        $notification->modul_slug = "slug";
        $notification->status = 'belum';
        $notification->from_user_id = "apa";
        $notification->to_user_id = "to_user_id";
        $notification->save();
    }
}
