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

class SideScreenExport implements WithMultipleSheets
{
    use Exportable;

    protected $result;

    protected $ironmongery_info;

    public function __construct(protected $id,protected $vid) {
        $this->result = ExportSideScreen($this->id,$this->vid);
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            'Screen Glass' => new ScreenGlass($this->id,$this->vid,$this->result),
            'Screen Frame' => new ScreenFrame($this->id,$this->vid,$this->result),
            'Screen Glazing Beads' => new ScreenGlazingBeads($this->id,$this->vid,$this->result),
        ];
    }
}
