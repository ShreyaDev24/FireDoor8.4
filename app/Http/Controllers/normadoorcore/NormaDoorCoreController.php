<?php

namespace App\Http\Controllers\normadoorcore;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ConfigurableDoorFormula;
use App\Models\LippingSpecies;
use App\Models\Option;
use App\Models\Color;
use App\Models\Company;
use App\Models\Tooltip;
use App\Models\Quotation;
use App\Models\AddIronmongery;
use App\Models\BOMSetting;

class NormaDoorCoreController extends Controller
{
    public function add_norma_door_core($id,$vid = null,$itemId = null)
    {
        $item = [];
        $ConfigurableDoorFormulaData = ConfigurableDoorFormula::where('status',1)->get();
        $LippingSpeciesData = LippingSpecies::where('Status',1)->get();
        $OptionsData = Option::where(['configurableitems'=>3 ,'is_deleted'=>0])->get();
        $ColorData = Color::where('Status',1)->get();
        $company_data = Company::join('users','users.id','companies.UserId')->select('users.*')->get();
        $tooltip = Tooltip::first();
        $quotation = Quotation::where('id',$id)->first();
        if(!empty($quotation->ProjectId)){
            $setIronmongery = AddIronmongery::where('ProjectId',$quotation->ProjectId)->get();
        } else {
            $setIronmongery = null;
        }

        $BOMSetting = BOMSetting::where("id",1)->get()->first();
        return view('Items/NormaDoorCore/NormaDoorConfiguration',[
            "QuotationId" => $id,
            'Item' => $item,
            'option_data' => $OptionsData,
            'color_data' => $ColorData,
            'lipping_species' => $LippingSpeciesData,
            'ConfigurableDoorFormula' => $ConfigurableDoorFormulaData,
            'company_list' => $company_data,
            'issingleconfiguration' => '1',
            'versionId' => $vid,
            'tooltip' => $tooltip,
            'setIronmongery' => $setIronmongery,
            'BOMSetting' => $BOMSetting,
        ]);
    }
}
