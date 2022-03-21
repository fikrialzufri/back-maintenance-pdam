<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Roster;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;

class RosterImport implements ToCollection, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row)
        {
            $roster = new Roster;
            $roster->tanggal = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[2]));
            $roster->shift = $row[3];
            $roster->jam_masuk = $row[4];
            $roster->jam_pulang = $row[5];
            $roster->save();
        }
    }
}
