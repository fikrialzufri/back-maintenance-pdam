<?php
function format_uang($angka)
{
    $hasil = number_format($angka, 0, ',', '.');
    return $hasil;
}

function pembulatan($angka)
{
    $totalharga = ceil($angka);
    if (substr($totalharga, -3) > 499) {
        $total_harga = round($totalharga, -3) - 1000;
    } else {
        $total_harga = round($totalharga, -3) - 1000;
    }

    return number_format($total_harga, 0, ',', '.');
}
