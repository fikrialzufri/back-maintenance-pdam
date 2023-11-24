<?php
function terbilang($angka)
{
    $angka = abs($angka);
    $baca = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");

    $terbilang = "";
    if ($angka < 12) {
        $terbilang = " " . $baca[$angka];
    } else if ($angka < 20) {
        $terbilang = terbilang($angka - 10) . " belas";
    } else if ($angka < 100) {
        $terbilang = terbilang($angka / 10) . " puluh" . terbilang($angka % 10);
    } else if ($angka < 200) {
        $terbilang = " seratus" . terbilang($angka - 100);
    } else if ($angka < 1000) {
        $terbilang = terbilang($angka / 100) . " ratus" . terbilang($angka % 100);
    } else if ($angka < 2000) {
        $terbilang = " seribu" . terbilang($angka - 1000);
    } else if ($angka < 1000000) {
        $terbilang = terbilang($angka / 1000) . " ribu" . terbilang($angka % 1000);
    } else if ($angka < 1000000000) {
        $terbilang = terbilang($angka / 1000000) . " juta" . terbilang($angka % 1000000);
    }
    return $terbilang;
}

function romanToNumber($roman)
{
    $romans = [
        'I' => 1,
        'V' => 5,
        'X' => 10,
        'L' => 50,
        'C' => 100,
        'D' => 500,
        'M' => 1000,
    ];

    $result = 0;
    $prevValue = 0;

    for ($i = strlen($roman) - 1; $i >= 0; $i--) {
        $currentValue = $romans[$roman[$i]];
        if ($currentValue < $prevValue) {
            $result -= $currentValue;
        } else {
            $result += $currentValue;
        }
        $prevValue = $currentValue;
    }

    return $result;
}

function sortByRoman($array)
{
    usort($array, function ($a, $b) {
        $romanA = strtoupper($a);
        $romanB = strtoupper($b);

        $numberA = romanToNumber($romanA);
        $numberB = romanToNumber($romanB);

        return $numberA - $numberB;
    });

    return $array;
}
