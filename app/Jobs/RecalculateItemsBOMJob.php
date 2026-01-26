<?php

namespace App\Jobs;

use App\Models\Item;
use App\Models\ItemMaster;
use App\Models\Quotation;
use App\Models\BOMCalculation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecalculateItemsBOMJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $quotationId,public int $selectVersionID,public int $userLoginId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $Items = Item::where(['items.QuotationId' => $this->quotationId, 'items.VersionId' => $this->selectVersionID])->get();

        $quotation = Quotation::where('id',$this->quotationId)->first();

        if(!empty($Items)){
            foreach($Items as $data){
                $itemid = $data->itemId;

                BOMUpdate($data, $quotation->configurableitems,$this->userLoginId);

                $BOMCalculation = BOMCalculation::select('*')->where('QuotationId',$this->quotationId)->where('DoorType',$data->DoorType)->where('itemId',$itemid)->get();
                $GTSellPrice = 0;
                $GTSellPriceTotal = 0;
                if(!empty($BOMCalculation)){
                    foreach($BOMCalculation as $value){
                        if($value->Category != 'Ironmongery&MachiningCosts'){
                            $GTSellPrice += $value->GTSellPrice;
                        }
                    }

                    $ItemMaster = ItemMaster::where('itemID',$itemid)->get()->count();
                    $GTSellPriceTotal = ($ItemMaster > 0) ? round(($GTSellPrice/$ItemMaster),2) : $GTSellPrice;
                }

                Item::where('itemId', $itemid)->update([
                    'DoorsetPrice' => $GTSellPriceTotal,
                ]);
            }
        }

    }
}
