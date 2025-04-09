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

class BomCalculationExport implements WithMultipleSheets
{
    use Exportable;

    protected array $result;

    protected $ironmongery_info;

    public function __construct(protected $id,protected $vid) {
        $this->result = BOMCAlculationExport($this->id,$this->vid);
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            'Summary' => new Summary($this->id,$this->vid,$this->result),
            'Glazing Beads' => new GlazingBeads($this->id,$this->vid,$this->result),
            'Frame' => new Frame($this->id,$this->vid,$this->result),
            'Acoustics' => new Accoustics($this->id,$this->vid,$this->result),
            'Architrave' => new Architrave($this->id,$this->vid,$this->result),
            'Glass' => new Glass($this->id,$this->vid,$this->result),
            'Glazing System' => new GlazingSystem($this->id,$this->vid,$this->result),
            'Intumescent Strip - Seals' => new IntumescentStripSeals($this->id,$this->vid,$this->result),
            'Leaf Set Bespoke' => new LeafSetBespoke($this->id,$this->vid,$this->result),
            'Ironmongery Machining Cost' => new IronmongeryMachiningCost($this->id,$this->vid,$this->result),
            'General Labour Costs' => new GeneralLabourCosts($this->id,$this->vid,$this->result),
            'Ironmongery' => new Ironmongery($this->id,$this->vid,$this->result)
        ];
    }
}
