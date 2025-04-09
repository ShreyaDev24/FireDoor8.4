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

class IronmongeryExport implements WithMultipleSheets
{
    use Exportable;

    protected $id;

    protected $vid;

    protected array $result;

    public function __construct($id,$vid) {
        $this->result = BOMCAlculationExport($id,$vid);
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            'Ironmongery' => new Ironmongery($this->id,$this->vid,$this->result)
        ];
    }
}
