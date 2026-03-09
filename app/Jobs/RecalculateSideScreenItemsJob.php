<?php

namespace App\Jobs;

use App\Models\SideScreenItem;
use App\Models\ScreenBOMCalculation;
use App\Models\SideScreenItemMaster;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecalculateSideScreenItemsJob implements ShouldQueue
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
        $SideScreenItems = SideScreenItem::where(['side_screen_items.QuotationId' => $this->quotationId, 'side_screen_items.VersionId' => $this->selectVersionID])->get();
        if(!empty($SideScreenItems)){
            foreach($SideScreenItems as $data){
                $id = $data->id;
                sideScreenBOM($data,$this->userLoginId);

                $ScreenBOMCalculation = ScreenBOMCalculation::select('*')->where('QuotationId',$this->quotationId)->where('ScreenType',$data->ScreenType)->where('ScreenId',$id)->get();
                $GTSellPrice = 0;
                $GTSellPriceTotal = 0;
                if(!empty($ScreenBOMCalculation)){
                    foreach($ScreenBOMCalculation as $value){
                        $GTSellPrice += $value->GTSellPrice;
                    }

                    $ItemMaster = SideScreenItemMaster::where('ScreenId',$id)->get()->count();
                    $GTSellPriceTotal = round(($GTSellPrice/$ItemMaster),2);
                }
                if($data->ScreenAdjustPrice  != 0 || $data->ScreenAdjustPrice  != null){
                    SideScreenItem::where('id', $id)->update([
                        'ScreenPrice' => $GTSellPriceTotal,
                        'ScreenAdjustPrice' => $GTSellPriceTotal,
                    ]);
                } else{
                    SideScreenItem::where('id', $id)->update([
                        'ScreenPrice' => $GTSellPriceTotal
                    ]);
                }
            }
        }
    }
}
