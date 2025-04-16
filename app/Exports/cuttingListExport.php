<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\File;
use App\Models\Item;
use App\Models\Project;
use App\Models\Quotation;
use App\Models\LippingSpecies;
use App\Models\CustomerContact;
use App\Models\QuotationVersion;
use App\Models\Company;
use Auth;

class cuttingListExport implements WithMultipleSheets
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
            'Door Order Sheet' => new DoorOrderSheet($this->id,$this->vid,$this->result),
            'Frames and Transoms' => new FramesTransoms($this->id,$this->vid,$this->result),
            'Glass Order Sheet' => new GlassOrderSheet ($this->id,$this->vid,$this->result),
            'Glazing Beads for Doors' => new GlazingBeadsDoors ($this->id,$this->vid,$this->result),
            'Screen Glass' => new ScreenGlass($this->id,$this->vid,$this->result),
            'Screen Frame' => new ScreenFrame($this->id,$this->vid,$this->result),
            'Screen Glazing Beads' => new ScreenGlazingBeads($this->id,$this->vid,$this->result),
        ];
    }
}
