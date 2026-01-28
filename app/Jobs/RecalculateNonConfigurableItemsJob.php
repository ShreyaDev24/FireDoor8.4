<?php

namespace App\Jobs;

use App\Models\Quotation;
use App\Models\NonConfigurableItemStore;
use App\Models\QuotationVersion;
use App\Models\NonConfigurableItems;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecalculateNonConfigurableItemsJob implements ShouldQueue
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
        $NonConfigurableItemStore = NonConfigurableItemStore::where(['non_configurable_item_store.quotationId' => $this->quotationId, 'non_configurable_item_store.versionId' => $this->selectVersionID])->get();
        $currencyPrice = getCurrencyRate($this->quotationId,$this->userLoginId);
        $margin = QuotationVersion::where(['quotation_id'=> $this->quotationId,'id'=> $this->selectVersionID])->value('discountQuotation');
        if(!empty($NonConfigurableItemStore)){
            foreach($NonConfigurableItemStore as $val){
                $NonConfigurableItems = NonConfigurableItems::where('id',$val->nonConfigurableId)->first();
                $QuoteSummaryDiscountValue = 0 ;
                if($margin != 0){
                    $QuoteSummaryDiscountValue = ($NonConfigurableItems->price * $margin) / 100;
                }

                $price = ($margin > 0)? ($NonConfigurableItems->price + $QuoteSummaryDiscountValue):
                ($NonConfigurableItems->price - $QuoteSummaryDiscountValue);
                NonConfigurableItemStore::where('id', $val->id)->update([
                    'price' => $price * $currencyPrice,
                    'total_price' => $price * $val->quantity * $currencyPrice,
                ]);
            }
        }
    }
}
