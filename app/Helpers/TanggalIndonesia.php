<?php
function tanggal_indonesia($tgl, $tampil_hari = true, $koma = true)
{
    $nama_hari = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu");
    $nama_bulan = array(
        1 => "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember"
    );
    $tahun = substr($tgl, 0, 4);
    $bulan = $nama_bulan[(int) substr($tgl, 5, 2)];
    $tanggal = substr($tgl, 8, 2);
    $text = "";
    if ($tampil_hari) {
        $urutan_hari = date('w', mktime(0, 0, 0, substr($tgl, 5, 2), $tanggal, $tahun));
        $hari = $nama_hari[$urutan_hari];
        $text .= $hari;
    }
    if ($koma === true) {
        $text .= ", ";
    } else {
        $text .= " ";
    }
    $text .= $tanggal . " " . $bulan . " " . $tahun;
    return $text;
}
function tanggal_indonesia_terbilang($tgl, $tampil_hari = true, $koma = true)
{
    $nama_hari = array("minggu", "senin", "selasa", "rabu", "kamis", "jumat", "sabtu");
    $nama_bulan = array(
        1 => "januari",
        "februari",
        "maret",
        "april",
        "mei",
        "juni",
        "juli",
        "agustus",
        "september",
        "oktober",
        "november",
        "desember"
    );
    $tahun = substr($tgl, 0, 4);
    $bulan = $nama_bulan[(int) substr($tgl, 5, 2)];
    $tanggal = substr($tgl, 8, 2);
    $text = "";
    if ($tampil_hari) {
        $urutan_hari = date('w', mktime(0, 0, 0, substr($tgl, 5, 2), $tanggal, $tahun));
        $hari = $nama_hari[$urutan_hari];
        $text .= $hari . " tanggal ";
    }
    if ($koma === true) {
        $text .= ", ";
    } else {
        $text .= " ";
    }
    $text .= terbilang($tanggal) . " bulan " . $bulan . " tahun " . $tahun;
    return $text;
}
function capital_tanggal_indonesia($tgl, $tampil_hari = true)
{
    $nama_hari = array("minggu", "senin", "selasa", "rabu", "kamis", "jum'at", "sabtu");
    $nama_bulan = array(
        1 => "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember"
    );
    $tahun = substr($tgl, 0, 4);
    $bulan = $nama_bulan[(int) substr($tgl, 5, 2)];
    $tanggal = substr($tgl, 8, 2);
    $text = "";
    if ($tampil_hari) {
        $urutan_hari = date('w', mktime(0, 0, 0, substr($tgl, 5, 2), $tanggal, $tahun));
        $hari = $nama_hari[$urutan_hari];
        $text .= "hari " . $hari . " ";
    }
    $text .= "tanggal " . $tanggal . " bulan  " . $bulan . " tahun " . $tahun;
    return $text;
}
function bulan_indonesia($tgl)
{
    $nama_bulan = array(
        1 => "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember"
    );
    $bulan = $nama_bulan[(int) substr($tgl, 5, 2)];

    return $bulan;
}
function tahun_indonesia($tgl)
{
    $tahun = substr($tgl, 0, 4);

    return $tahun;
}
function bulan_indonesia_romawi($tgl)
{
    return getRomawi((int) substr($tgl, 5, 2));
}

function getRomawi($bln)
{
    switch ($bln) {
        case 1:
            return "I";
            break;
        case 2:
            return "II";
            break;
        case 3:
            return "III";
            break;
        case 4:
            return "IV";
            break;
        case 5:
            return "V";
            break;
        case 6:
            return "VI";
            break;
        case 7:
            return "VII";
            break;
        case 8:
            return "VIII";
            break;
        case 9:
            return "IX";
            break;
        case 10:
            return "X";
            break;
        case 11:
            return "XI";
            break;
        case 12:
            return "XII";
            break;
    }
}
