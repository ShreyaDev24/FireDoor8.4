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

    protected array $result;

    public function __construct(protected $id,protected $vid) {
        $this->result = BOMCAlculationExport($this->id,$this->vid);
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            'Summary' => new SummaryIronMongery($this->id,$this->vid,$this->result),
            'Ironmongery' => new Ironmongery($this->id,$this->vid,$this->result)
        ];
    }
}
