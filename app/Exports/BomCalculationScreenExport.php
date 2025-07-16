<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BomCalculationScreenExport implements WithMultipleSheets
{
    use Exportable;
    protected array $result;
    public function __construct(protected $id,protected $vid) {
        $this->result = BOMScreenCalculationExport($this->id,$this->vid);
    }

    public function sheets(): array
    {
        return [
            'Screen Summary' => new ScreenSummary($this->id,$this->vid,$this->result),
            'Side Screens Glazing Beads' => new SideScreenGlazingBeads($this->id,$this->vid,$this->result),
            'Side Screens Frame' => new SideScreenFrame($this->id,$this->vid,$this->result),
            'Side Screens Glass' => new SideScreenGlass($this->id,$this->vid,$this->result),
            'Side Screens Glazing' => new SideScreenGlazing($this->id,$this->vid,$this->result),
            'Side Screens General Labour Costs' => new SideScreenGeneralLabourCosts($this->id,$this->vid,$this->result),
        ];
    }
}
