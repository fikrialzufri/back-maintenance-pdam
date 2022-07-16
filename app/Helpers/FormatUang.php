<?php
function format_uang($angka)
{
    $hasil = number_format($angka, 0, ',', '.');
    return $hasil;
}

function pembulatan($angka)
{
    $total_harga = ceil($angka) - substr(ceil($angka), -3);

    return number_format($total_harga, 0, ',', '.');
}
