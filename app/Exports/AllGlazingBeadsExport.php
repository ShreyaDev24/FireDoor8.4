<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AllGlazingBeadsExport implements WithMultipleSheets
{
    use Exportable;

    protected $id,$vid,$result,$ironmongery_info;

    function __construct($id,$vid) {
        $this->id = $id;
        $this->vid = $vid;
        $this->result = ExportSideScreen($id,$vid);
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            'Vision Panel Glazing Beads' => new VisionPanelGlazingBeads($this->id,$this->vid,$this->result),
            // 'Side Light Glazing Beads' => new SideLightGlazingBeads($this->id,$this->vid,$this->result),
            // 'Fan Light Glazing Beads' => new FanLightGlazingBeads($this->id,$this->vid,$this->result),
        ];
    }
}
