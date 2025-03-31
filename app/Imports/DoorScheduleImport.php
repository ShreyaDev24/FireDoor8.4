<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use App\DoorSchedule;

class DoorScheduleImport implements ToModel
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return new DoorSchedule([
            'Mark'     => $row[0],
            'Type'    => $row[1],
            'MarkLevel' => $row[2],
            'FireRating' => $row[3],
            'VisionPanel' => $row[4],
            'Internal/External' => $row[5],
            'StructuralWidth' => $row[6],
            'StructuralHeight' => $row[7],
            'DoorFinish' => $row[8],
            'NBS' => $row[9],
        ]);
    }
}
