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
    public function __construct(public int $quotationId,public int $selectVersionID,public int $userLoginId,public $existCurrency)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $Items = Item::where(['items.QuotationId' => $this->quotationId, 'items.VersionId' => $this->selectVersionID])
        ->distinct('itemId')->get();

        $quotation = Quotation::where('id',$this->quotationId)->first();

        if(!empty($Items)){
            foreach($Items as $data){
                $itemid = $data->itemId;

                BOMUpdate($data, $quotation->configurableitems,$this->userLoginId);

                $GTSellPrice = BOMCalculation::where('QuotationId', $this->quotationId)
                    ->where('DoorType', $data->DoorType)
                    ->where('itemId', $itemid)
                    ->where('Category', '!=', 'Ironmongery&MachiningCosts')
                    ->sum('GTSellPrice');

                $itemCount = ItemMaster::where('itemID', $itemid)->count();
                $itemCount = max(1, $itemCount); // prevent divide-by-zero

                $GTSellPriceTotal = round($GTSellPrice / $itemCount, 2);
                $GTSellPriceTotal = round($GTSellPrice / $itemCount, 2);
                if($data->AdjustPrice  != 0 || $data->AdjustPrice  != null){
                    if($this->existCurrency == null){
                            Item::where('itemId', $itemid)->update([
                            'AdjustPrice' => $data->AdjustPrice,
                            'DoorsetPrice' => $GTSellPriceTotal,
                        ]);
                    } else {
                         Item::where('itemId', $itemid)->update([
                            'DoorsetPrice' => $GTSellPriceTotal,
                            'AdjustPrice' => $GTSellPriceTotal,
                        ]);
                    }
                } else{
                    if($this->existCurrency == null){
                            Item::where('itemId', $itemid)->update([
                                'DoorsetPrice' => $GTSellPriceTotal,
                                'AdjustPrice' => $data->AdjustPrice,
                            ]);
                    } else{
                        Item::where('itemId', $itemid)->update([
                            'DoorsetPrice' => $GTSellPriceTotal,
                            'AdjustPrice' => $Items->AdjustPrice,
                        ]);
                    }

                }
            }
        }

    }
}
