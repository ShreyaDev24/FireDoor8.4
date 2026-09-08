<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class HangingDocumentExport implements WithMultipleSheets
{
    use Exportable;
    protected $id,$vid;

    function __construct($id,$vid) {
        $this->id = $id;
        $this->vid = $vid;
    }

   public function sheets(): array
    {
        return [
            'Worksheet Frame Hanging Sheet' => new FrameHangingSheet($this->id,$this->vid),
            'Worksheet Door Hanging Sheet' => new DoorHangingSheet($this->id,$this->vid),
        ];
    }
}
