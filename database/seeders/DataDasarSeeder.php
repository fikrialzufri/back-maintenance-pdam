<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\Cabang;
use App\Models\Gudang;
use App\Models\MetodePembayaran;
use App\Models\Pusat;
use App\Models\Supplier;
use App\Models\Toko;
use Illuminate\Database\Seeder;

class DataDasarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $PusatSatu = new Pusat();
        $PusatSatu->nama = 'Jordan Hair Studio';
        $PusatSatu->alamat = 'Jl. Imam Bonjol';
        $PusatSatu->no_hp = '0853133898';
        $PusatSatu->email = 'jordansamarinda@gmail.com';
        $PusatSatu->save();

        $cabangSatu = new Cabang();
        $cabangSatu->nama = 'Jordan Hair Studio';
        $cabangSatu->alamat = 'Jl. Imam Bonjol';
        $cabangSatu->no_hp = '0855133898';
        $cabangSatu->email = 'jordanhairstuido.imambonjol@gmail.com';
        $cabangSatu->pusat_id =  $PusatSatu->id;
        $cabangSatu->save();

        $GudangSatu = new Gudang();
        $GudangSatu->nama = 'Jordan Gudang';
        $GudangSatu->alamat = 'Jl. Imam Bonjol';
        $GudangSatu->no_hp = '0851113898';
        $GudangSatu->email = 'Gudangsatu@gmail.com';
        $GudangSatu->cabang_id =  $cabangSatu->id;
        $GudangSatu->save();

        $TokoSatu = new Toko();
        $TokoSatu->nama = 'Jordan Hair Studio';
        $TokoSatu->alamat = 'Jl. Imam Bonjol';
        $TokoSatu->no_hp = '0851241898';
        $TokoSatu->email = 'Tokosatu@gmail.com';
        $TokoSatu->cabang_id =  $cabangSatu->id;
        $TokoSatu->save();

        $supplierSatu = new Supplier();
        $supplierSatu->nama = 'Indomaret Agus salim';
        $supplierSatu->alamat = 'Jl. Agus salim';
        $supplierSatu->no_hp = '0855241898';
        $supplierSatu->email = 'indomaret@gmail.com';
        $supplierSatu->save();

        $MetodePembayaranCash = new MetodePembayaran();
        $MetodePembayaranCash->nama = 'Cash';
        $MetodePembayaranCash->save();

        $MetodePembayaranDebit = new MetodePembayaran();
        $MetodePembayaranDebit->nama = 'Debit / Credit Card';
        $MetodePembayaranDebit->save();

        $MetodePembayaranTransfer = new MetodePembayaran();
        $MetodePembayaranTransfer->nama = 'Transfer';
        $MetodePembayaranTransfer->save();

        $MetodePembayaranEwallet = new MetodePembayaran();
        $MetodePembayaranEwallet->nama = 'E-Wallet';
        $MetodePembayaranEwallet->save();

        $BankCash = new Bank();
        $BankCash->nama = 'Cash';
        $BankCash->metode_pembayaran_id = $MetodePembayaranCash->id;
        $BankCash->save();

        $BankCash = new Bank();
        $BankCash->nama = 'BCA';
        $BankCash->metode_pembayaran_id = $MetodePembayaranDebit->id;
        $BankCash->save();

        $BankCash = new Bank();
        $BankCash->nama = 'BRI';
        $BankCash->metode_pembayaran_id = $MetodePembayaranTransfer->id;
        $BankCash->save();

        $BankDana = new Bank();
        $BankDana->nama = 'Dana';
        $BankDana->metode_pembayaran_id = $MetodePembayaranEwallet->id;
        $BankDana->save();
    }
}
