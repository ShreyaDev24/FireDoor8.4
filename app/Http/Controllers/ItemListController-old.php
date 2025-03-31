<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Option;
use App\Models\Company;
use App\Models\Color;
use App\Models\Doors;
use App\Models\LippingSpecies;
use App\Models\DoorSchedule;
use App\Models\Items;
use DB;
use App\Models\QuotationVersion;
use App\Models\QuotationVersionItems;
use App\Models\ItemMaster;
use App\Models\SettingIntumescentSeals2;

class ItemListController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $item=Item::all();
        $options_data = Option::where('is_deleted',0)->get();
        $company_data = Company::join('users','users.id','companies.UserId')->select('users.*')->get();
        return view('Items/AddItem',['itemlist'=>$item,'option_data'=>$options_data,'company_list'=>$company_data]);

    }

    public function itmeList()
    {
        $item=Item::all();
        return view('itemlist',['itemlist'=>$item]);
    }
    public function changeStatus(Request $request)
    {
        if($request->id){
            $Item               = Item::find(crypt::decrypt($request->id));
            if($Item){
                $Item->ItemStatus   = ($Item->ItemStatus==1) ? 2 : 1;
                $result=$Item->save();
                $statuscode=http_response_code();
            }else{
                $statuscode='fail';
            }
        }
        return response()->json(['success'=>$statuscode,'item'=>$Item]);
    }


    public function tab(){


        $item=Item::all();
        return view('items.tab',['itemlist'=>$item]);
    }


    public function filterFireRating(Request $request){
        $pageId = $request->pageId;
        $fireRating = $request->fireRating;
        $integrityValue = $request->integrity;
        $integrityValue2 = $request->integrity2;

        $integrity = "";

        if(!empty($integrityValue)){
            $integrity = $integrityValue;
        } else if(!empty($integrityValue2)){
            $integrity = $integrityValue2;
        }
        $leaf1VpAreaSizeM2Value = $request->leaf1VpAreaSizeM2Value;
        $userType = Auth::user()->UserType;
        if(empty($request->isIntegrity)) {
            echo json_encode(array('status' => 'error','message' => 'Something went wrong.', 'data' => ''));
            die();
        }
        if($request->isIntegrity === true && $request->integrity == ""){
            echo json_encode(array('status' => 'error','message' => "Integrity is required.", 'data' => ''));
            die();
        }
        if($userType=="1" || $userType=="4"){
            if($fireRating=="NFR"){
                $glassType = Option::where('configurableitems',$pageId)
                // ->where('UnderAttribute',$fireRating)
                ->where('OptionSlug','leaf1_glass_type')
                ->get();
            }else{
                if($request->isIntegrity === true){
                    if($leaf1VpAreaSizeM2Value != ''){
                        $glassType = Option::where('configurableitems',$pageId)->where('UnderAttribute',$fireRating)
                        ->where('OptionSlug','leaf1_glass_type')
                        ->where('UnderParent2',$integrity)
                        ->where('VpAreaSize','>=',$leaf1VpAreaSizeM2Value)
                        ->distinct('options.id')
                        ->get();
                    } else {
                        $glassType = Option::where('configurableitems',$pageId)->where('UnderAttribute',$fireRating)
                            ->where('OptionSlug','leaf1_glass_type')
                            ->where('UnderParent2',$integrity)
                            ->distinct('options.id')
                            ->get();
                    }
                }else{
                    if($leaf1VpAreaSizeM2Value != ''){
                        if($integrity != ""){
                            $glassType = Option::where('configurableitems',$pageId)
                            ->where('UnderAttribute',$fireRating)
                            ->where('OptionSlug','leaf1_glass_type')
                            ->where('UnderParent2',$integrity)
                            ->where('VpAreaSize','>=',$leaf1VpAreaSizeM2Value)
                            ->distinct('options.id')
                            ->get();
                        }else{
                            $glassType = Option::where('configurableitems',$pageId)
                            ->where('UnderAttribute',$fireRating)
                            ->where('OptionSlug','leaf1_glass_type')
                            ->where('VpAreaSize','>=',$leaf1VpAreaSizeM2Value)
                            ->distinct('options.id')
                            ->get();
                        }
                    } else {
                        $glassType = Option::where('configurableitems',$pageId)
                            ->where('UnderAttribute',$fireRating)
                            ->where('OptionSlug','leaf1_glass_type')
                            ->distinct('options.id')
                            ->get();
                    }
                }
            }
        } else {
            if($fireRating=="NFR"){
                if((string)$request->isIntegrity == "true"){
                    $glassType = Option::Join('selected_option', function($join) {
                        $join->on('options.OptionKey', '=', 'selected_option.SelectedOptionKey');
                    })
                        ->where('options.configurableitems',$pageId)
                        // ->where('options.UnderAttribute',$fireRating)
                        ->where('options.OptionSlug','leaf1_glass_type')
                        ->where('options.UnderParent2',$integrity)
                        ->where('selected_option.SelectedUserId',Auth::user()->id)
                        ->distinct('options.id')
                        ->get(['options.*']);

                }else{
                    $glassType = Option::Join('selected_option', function($join) {
                        $join->on('options.OptionKey', '=', 'selected_option.SelectedOptionKey');
                    })
                        ->where('options.configurableitems',$pageId)
                        // ->where('options.UnderAttribute',$fireRating)
                        ->where('options.OptionSlug','leaf1_glass_type')
                        ->where('selected_option.SelectedUserId',Auth::user()->id)
                        ->distinct('options.id')
                        ->get(['options.*']);
                }
            } else {
                if((string)$request->isIntegrity == "true"){
                    if($leaf1VpAreaSizeM2Value != ''){
                        $glassType = Option::Join('selected_option', function($join) {
                            $join->on('options.OptionKey', '=', 'selected_option.SelectedOptionKey');
                        })
                            ->where('options.configurableitems',$pageId)
                            ->where('options.UnderAttribute',$fireRating)
                            ->where('options.OptionSlug','leaf1_glass_type')
                            ->where('options.UnderParent2',$integrity)
                            ->where('options.VpAreaSize','>=',$leaf1VpAreaSizeM2Value)
                            ->where('selected_option.SelectedUserId',Auth::user()->id)
                            ->distinct('options.id')
                            ->get(['options.*']);
                    } else {
                        $glassType = Option::Join('selected_option', function($join) {
                            $join->on('options.OptionKey', '=', 'selected_option.SelectedOptionKey');
                        })
                            ->where('options.configurableitems',$pageId)
                            ->where('options.UnderAttribute',$fireRating)
                            ->where('options.OptionSlug','leaf1_glass_type')
                            ->where('options.UnderParent2',$integrity)
                            ->where('selected_option.SelectedUserId',Auth::user()->id)
                            ->distinct('options.id')
                            ->get(['options.*']);
                    }
                } else {
                    if($leaf1VpAreaSizeM2Value != ''){

                        if($integrity != ""){
                            $glassType = Option::Join('selected_option', function($join) {
                                $join->on('options.OptionKey', '=', 'selected_option.SelectedOptionKey');
                            })
                                ->where('options.configurableitems',$pageId)
                                ->where('options.UnderAttribute',$fireRating)
                                ->where('options.OptionSlug','leaf1_glass_type')
                                ->where('options.UnderParent2',$integrity)
                                ->where('options.VpAreaSize','>=',$leaf1VpAreaSizeM2Value)
                                ->where('selected_option.SelectedUserId',Auth::user()->id)
                                ->distinct('options.id')
                                ->get(['options.*']);
                        }else{
                            $glassType = Option::Join('selected_option', function($join) {
                                $join->on('options.OptionKey', '=', 'selected_option.SelectedOptionKey');
                            })
                                ->where('options.configurableitems',$pageId)
                                ->where('options.UnderAttribute',$fireRating)
                                ->where('options.OptionSlug','leaf1_glass_type')
                                ->where('options.VpAreaSize','>=',$leaf1VpAreaSizeM2Value)
                                ->where('selected_option.SelectedUserId',Auth::user()->id)
                                ->distinct('options.id')
                                ->get(['options.*']);
                        }
                    } else {
                        $glassType = Option::Join('selected_option', function($join) {
                            $join->on('options.OptionKey', '=', 'selected_option.SelectedOptionKey');
                        })
                            ->where('options.configurableitems',$pageId)
                            ->where('options.UnderAttribute',$fireRating)
                            ->where('options.OptionSlug','leaf1_glass_type')
                            ->where('selected_option.SelectedUserId',Auth::user()->id)
                            ->distinct('options.id')
                            ->get(['options.*']);
                    }
                }
            }
        }

        if(!empty($glassType) && count( $glassType)){
            echo json_encode(array('status'=>'ok','data'=> $glassType));
        }else{
            echo json_encode(array('status'=>'error','data'=> ''));
        }
    }



    public function glassTypeFilter(Request $request){
        $pageId = $request->pageId;
        $glassType = $request->glassType;
        $userType = Auth::user()->UserType;
        if($userType=="1" ||$userType=="4"){
            $glassThikness = Option::where('configurableitems',$pageId)->where('UnderAttribute',$glassType)->where('OptionSlug','leaf1_glass_thickness')->get();
        } else {
            $glassThikness = Option::Join('selected_option', function($join) {
                $join->on('options.id', '=', 'selected_option.optionId');
              })
              ->where('options.configurableitems',$pageId)
              ->where('options.UnderAttribute',$glassType)
              ->where('options.OptionSlug','leaf1_glass_thickness')
              ->where('selected_option.SelectedUserId',Auth::user()->id)
              ->get(['options.*']);
        }
        if(!empty($glassThikness) && count( $glassThikness)){
            echo json_encode(array('status'=>'ok','data'=> $glassThikness));
        } else {
            echo json_encode(array('status'=>'error','data'=> ''));
        }
    }



    public function fileterGlazingSystem(Request $request){
        $pageId = $request->pageId;
        $fireRating = $request->fireRating;
        $leaf1VpAreaSizeM2Value = $request->leaf1VpAreaSizeM2Value;
        $lippingSpecies=array();
        if($leaf1VpAreaSizeM2Value == ""){
            if($fireRating == 'NFR'){
                $glaszingSystem = Option::where('configurableitems',$pageId)->where('OptionSlug','leaf1_glazing_systems')->get();
            } else {
                $glaszingSystem = Option::where('configurableitems',$pageId)->where('UnderAttribute',$fireRating)->where('OptionSlug','leaf1_glazing_systems')->get();
            }
        }else{
            if($fireRating == 'NFR'){
                $glaszingSystem = Option::where('configurableitems',$pageId)
                ->where('VpAreaSize','>=',$leaf1VpAreaSizeM2Value)
                ->where('OptionSlug','leaf1_glazing_systems')->get();
            } else {
                $glaszingSystem = Option::where('configurableitems',$pageId)->where('UnderAttribute',$fireRating)
                ->where('VpAreaSize','>=',$leaf1VpAreaSizeM2Value)
                ->where('OptionSlug','leaf1_glazing_systems')->get();
            }
        }

        if($fireRating=="FD30"){
            $lippingSpecies = LippingSpecies::where('MinValue','<=','530')->where('MaxValues','>','530')->orderBy('SpeciesName', 'ASC')->get();
        }else if($fireRating=="FD60"){
            $lippingSpecies = LippingSpecies::where('MinValue','<=','640')->where('MaxValues','>=','640')->orderBy('SpeciesName', 'ASC')->get();
        }else{
            $lippingSpecies = LippingSpecies::orderBy('SpeciesName', 'ASC')->get();
        }

        if(!empty($glaszingSystem) && count( $glaszingSystem) >0){
            echo json_encode(array('status'=>'ok','data'=> $glaszingSystem,'lippingSpecies'=>$lippingSpecies));
        }else{
            echo json_encode(array('status'=>'error','data'=> '','lippingSpecies'=>$lippingSpecies));
        }
    }



    public function fileterGlazingThikness(Request $request){
        $pageId = $request->pageId;
        $glazingSystem = $request->glazingSystems;
        $glaszingSystemThickness = Option::where('configurableitems',$pageId)->where('UnderAttribute',$glazingSystem)->where('OptionSlug','leaf1_glazing_system_thickness')->get();
        $GlazingBeadFixingDetail  = Option::where('configurableitems',$pageId)->where(['UnderAttribute' => $glazingSystem , 'OptionSlug' => 'Fixing_Detail' ])->first();
        if(!empty($glaszingSystemThickness) && count($glaszingSystemThickness)){
            echo json_encode(array('status'=>'ok','data'=> $glaszingSystemThickness,'data2' => $GlazingBeadFixingDetail));
        } else if(!empty($GlazingBeadFixingDetail)){
            echo json_encode(array('status'=>'ok','data'=> '','data2' => $GlazingBeadFixingDetail));
        } else {
            echo json_encode(array('status'=>'error','data'=> '','data2'=>''));
        }
    }



    public function fileterGlazingBeads(Request $request){
        $pageId = $request->pageId;
        $fireRating = $request->fireRating;
        $glaszingBeads = Option::where('configurableitems',$pageId)->where('UnderAttribute',$fireRating)->where('OptionSlug','leaf1_glazing_beads')->get();
        if(!empty($glaszingBeads) && count( $glaszingBeads)){
            echo json_encode(array('status'=>'ok','data'=> $glaszingBeads));
        }else{
            echo json_encode(array('status'=>'error','data'=> ''));
        }
    }


    public function filterFrameMaterial(Request $request){
        $fireRating = $request->fireRating;
        $lippingSpecies = array();
        // $frameMaterial = Option::where('configurableitems',$pageId)->where('UnderAttribute',$fireRating)->where('OptionSlug','Frame_Material')->get();
        if($fireRating=="FD30"){
            $lippingSpecies = LippingSpecies::where('MinValue','<=','450')->where('MaxValues','>','450')->orderBy('SpeciesName', 'ASC')->get();
        }else if($fireRating=="FD60"){
            $lippingSpecies = LippingSpecies::where('MinValue','<=','640')->where('MaxValues','>','640')->orderBy('SpeciesName', 'ASC')->get();
        }
        else{
            $lippingSpecies = LippingSpecies::orderBy('SpeciesName', 'ASC')->get();
        }
        echo json_encode(array('status'=>'ok','data'=> '','leepingSpecies'=>$lippingSpecies));
        // if(!empty($frameMaterial) && count( $frameMaterial)){
        //     echo json_encode(array('status'=>'ok','data'=> $frameMaterial,'leepingSpecies'=>$lippingSpecies));
        // }else{
        //     echo json_encode(array('status'=>'error','data'=> '','leepingSpecies'=>''));
        // }
    }

    public function scalloppedLippingThickness(Request $request){
        $pageId = $request->pageId;
        $fireRating = $request->fireRating;
        $scalloppedLippingThickness = Option::where('configurableitems',$pageId)->where('UnderAttribute',$fireRating)->where('OptionSlug','scalloped_lipping_thickness')->get();
        if(!empty($scalloppedLippingThickness) && count( $scalloppedLippingThickness)){
            echo json_encode(array('status'=>'ok','data'=> $scalloppedLippingThickness));
        } else {
            echo json_encode(array('status'=>'error','data'=> ''));
        }
    }

    public function flatLippingThickness(Request $request){
        $pageId = $request->pageId;
        $fireRating = $request->fireRating;
        $flatLippingThickness = Option::where('configurableitems',$pageId)->where('UnderAttribute',$fireRating)->where('OptionSlug','flat_lipping_thickness')->get();
        if(!empty($flatLippingThickness) && count( $flatLippingThickness)){
            echo json_encode(array('status'=>'ok','data'=> $flatLippingThickness));
        }else{
            echo json_encode(array('status'=>'error','data'=> ''));
        }
    }

    public function rebatedLippingThickness(Request $request){
        $pageId = $request->pageId;
        $fireRating = $request->fireRating;
        $rebatedLippingThickness = Option::where('configurableitems',$pageId)->where('UnderAttribute',$fireRating)->where('OptionSlug','rebeated_lipping_thickness')->get();
        if(!empty($rebatedLippingThickness) && count( $rebatedLippingThickness)){
            echo json_encode(array('status'=>'ok','data'=> $rebatedLippingThickness));
        }else{
            echo json_encode(array('status'=>'error','data'=> ''));
        }
    }



    public function filterDoorThickness(Request $request){
        $pageId = $request->pageId;
        $fireRating = $request->fireRating;
        $doorThickness = Option::where(['configurableitems' => $pageId , 'OptionSlug'=>'Glass_Integrity'])
                                ->orWhere(['configurableitems' => $pageId ,'OptionSlug'=>'Door_Thickness'])->get();
        if(!empty($doorThickness) && count( $doorThickness)){
            echo json_encode(array('status'=>'ok','data'=> $doorThickness));
        }else{
            echo json_encode(array('status'=>'error','data'=> ''));
        }
    }

    public function filterDoorleafFacingValue(Request $request){
        $pageId = $request->pageId;
        $doorLeafFacing = $request->doorLeafFacing;
        $userType = Auth::user()->UserType;
        if($userType=="1" ||$userType=="4"){
            $doorfacingValue = Option::where(['configurableitems'=>$pageId,'OptionSlug'=>'door_leaf_finish'])
                                    ->orWhere(['configurableitems' => $pageId , 'OptionSlug'=>'door_leaf_facing_value'])->get();
        }else{
            $doorfacingValue = Option::Join('selected_option', function($join) {
                $join->on('options.OptionKey', '=', 'selected_option.SelectedOptionKey');
              })
              ->where('options.configurableitems',$pageId)
              ->where('options.OptionSlug','door_leaf_facing_value')
            //   ->Where('options.OptionSlug','door_leaf_finish')
              ->where('selected_option.SelectedUserId',Auth::user()->id)
              ->get(['options.*']);
        }

        if(!empty($doorfacingValue) && count( $doorfacingValue)){
            if($doorLeafFacing=="Laminate" || $doorLeafFacing=="PVC"){
                // $color = Color::where('DoorLeafFacing',$doorLeafFacing)->get();
                $color = Color::Join('selected_color', function($join) {
                    $join->on('color.id', '=', 'selected_color.SelectedColorId');
                    })
                    ->where('color.DoorLeafFacing',$doorLeafFacing)
                    ->where('selected_color.SelectedUserId',Auth::user()->id)
                    ->get(['color.*']);
                echo json_encode(array('status'=>'ok','data'=> $doorfacingValue,'color'=>$color));
            }elseif($doorLeafFacing=="Veneer"){
                $color = Option::where('configurableitems',$pageId)->where('OptionSlug','door_leaf_finish')->where('UnderAttribute',$doorLeafFacing)->get();
                echo json_encode(array('status'=>'ok','data'=> $doorfacingValue,'color'=>$color));
            }elseif($doorLeafFacing=="Kraft_Paper"){
                $color = Option::where('configurableitems',$pageId)->where('OptionSlug','door_leaf_finish')->where('UnderAttribute',$doorLeafFacing)->get();
                echo json_encode(array('status'=>'ok','data'=> $doorfacingValue,'color'=>$color));
            } else {
                echo json_encode(array('status'=>'ok','data'=> $doorfacingValue,'color'=>''));
            }
        }else{
            $color = Option::where('configurableitems',$pageId)->where('OptionSlug','door_leaf_finish')->where('UnderAttribute',$doorLeafFacing)->get();
            echo json_encode(array('status'=>'ok','data'=> $doorfacingValue,'color'=>$color));
            echo json_encode(array('status'=>'error','data'=> ''));
        }
    }



    public function filterRalColor(Request $request){
        $doorLeafFinish = $request->doorLeafFinish;
        $userType = Auth::user()->UserType;
        if($userType=="1" ||$userType=="4"){
            $colors = Color::where(['DoorLeafFacing'=>$doorLeafFinish])->get();
        }else{
            if($doorLeafFinish == 'Painted' || $doorLeafFinish == 'Paint_Finish'){
                $colors = Color::Join('selected_color', function($join) {
                    $join->on('color.id', '=', 'selected_color.SelectedColorId');
                  })
                  ->where('color.DoorLeafFacingValue','Painted')
                  ->where('selected_color.SelectedUserId',Auth::user()->id)
                  ->get(['color.*']);
            }else {
                $colors = Color::Join('selected_color', function($join) {
                    $join->on('color.id', '=', 'selected_color.SelectedColorId');
                  })
                  ->where('color.DoorLeafFacing',$doorLeafFinish)
                  ->where('selected_color.SelectedUserId',Auth::user()->id)
                  ->get(['color.*']);
            }
        }
        if(!empty($colors) && count( $colors)){
            echo json_encode(array('status'=>'ok','data'=> $colors));
        } else {
            echo json_encode(array('status'=>'error','data'=> ''));
        }
    }



    public function itemStore(Request $request){


        $door = new Doors();
        // $ds = new DoorSchedule();
        $door->QuotationId =0;
        $door->ComanyId =  0;
        $door->Mark ='';
        $door->Type = '';
        $door->FieldsValue =json_encode($request->all());
        $door->Status = 1;
        $door->save();
        // DB::table('door_schedule')->where('id',$request->itemID )->where('QuotationId',$request->QuotationId )->update(array('Status' => 1));
        // return redirect('quotation/request/'.$request->QuotationId);
    //    return redirect('quotation/request/'.$quotation->id);
    return redirect()->back()->with('msg', 'item added succesfully');

    }

    public function itemStore1(Request $request){


        $pageIdentity = $request->pageIdentity;
        $QuotationId = $request->QuotationId;
        $doorType = $request->doorType;
        $DoorNo = $request->DoorNo;
        $versionId = $request->version_id;

        $SvgImage = "";

        $id = $request->itemID;

        $qq = Quotation::find($QuotationId);
        $qq->editBy = Auth::user()->id;
        $qq->updated_at = date('Y-m-d H:i:s');
        $qq->save();
        $Quotation = Quotation::where("id",$QuotationId)->first();

        if(!empty($id)){

            // if(!empty($request->SvgImage)){
            //     $Base64SvgImage = str_replace("data:image/png;base64,","",$request->SvgImage);

            //     $SvgImage = str_replace("#","",$Quotation->QuotationGenerationId)."-".$id."-".(($request->version_id != 0)?$request->version_id:"1").".png";

            //     $fileData = base64_decode($Base64SvgImage);
            //     $path = public_path('uploads/files/' . $SvgImage);

            //     $fp = fopen($path, "wb");

            //     fwrite($fp, $fileData);

            //     fclose($fp);
            // }


            // update
            $updateDetails = [
                //item (update)
                    //Main Options
                        'LeafConstruction'                      => $request->leafConstruction,
                        'FireRating'                            => $request->fireRating,
                        'DoorsetType'                           => $request->doorsetType,
                        'SwingType'                             => $request->swingType,
                        'LatchType'                             => $request->latchType,
                        'Handing'                               => $request->Handing,
                        'OpensInwards'                          => $request->OpensInwards,
                        'COC'                                   => $request->COC,
                        'Tollerance'                            => $request->tollerance,
                        'Undercut'                              => $request->undercut,
                        'FloorFinish'                           => $request->floorFinish,
                        'GAP'                                   => $request->gap,
                        'FrameThickness'                        => $request->frameThickness,

                    //Door Dimensions & Door Leaf
                        'SOWidth'                               => $request->sOWidth,
                        'SOHeight'                              => $request->sOHeight,
                        'SOWallThick'                           => $request->sODepth,
                        'LeafWidth1'                            => $request->leafWidth1,
                        'LeafWidth2'                            => $request->leafWidth2,
                        'LeafHeight'                            => $request->leafHeightNoOP,
                        'LeafThickness'                         => $request->doorThickness,
                        'DoorLeafFacing'                        => $request->doorLeafFacing,
                        'DoorLeafFacingValue'                   => $request->doorLeafFacingValue,
                        'DoorLeafFinish'                        => $request->doorLeafFinish,
                        'DoorLeafFinishColor'                   => $request->doorLeafFinishColor,
                        'SheenLevel'                            => $request->SheenLevel,
                        'DecorativeGroves'                      => $request->decorativeGroves,
                        'GrooveLocation'                        => $request->grooveLocation,
                        'GrooveWidth'                           => $request->grooveWidth,
                        'GrooveDepth'                           => $request->grooveDepth,
                        'MaxNumberOfGroove'                     => $request->maxNumberOfGroove,
                        'NumberOfGroove'                        => $request->numberOfGroove,
                        'NumberOfVerticalGroove'                => $request->numberOfVerticalGroove,
                        'NumberOfHorizontalGroove'              => $request->numberOfHorizontalGroove,

                    //Vision Panel
                        'Leaf1VisionPanel'                      => $request->leaf1VisionPanel,
                        'Leaf1VisionPanelShape'                 => $request->leaf1VisionPanelShape,
                        'VisionPanelQuantity'                   => $request->visionPanelQuantity,
                        'AreVPsEqualSizesForLeaf1'              => $request->AreVPsEqualSizes,
                        'DistanceFromtopOfDoor'                 => $request->distanceFromTopOfDoor,
                        'DistanceFromTheEdgeOfDoor'             => $request->distanceFromTheEdgeOfDoor,
                        'DistanceBetweenVPs'                    => $request->distanceBetweenVPs,
                        'Leaf1VPWidth'                          => $request->vP1Width,
                        'Leaf1VPHeight1'                        => $request->vP1Height1,
                        'Leaf1VPHeight2'                        => $request->vP1Height2,
                        'Leaf1VPHeight3'                        => $request->vP1Height3,
                        'Leaf1VPHeight4'                        => $request->vP1Height4,
                        'Leaf1VPHeight5'                        => $request->vP1Height5,
                        'Leaf1VPAreaSizem2'                     => $request->leaf1VpAreaSizeM2,
                        'Leaf2VisionPanel'                      => $request->leaf2VisionPanel,
                        'sVPSameAsLeaf1'                        => $request->vpSameAsLeaf1,
                        'Leaf2VisionPanelQuantity'              => ($request->vpSameAsLeaf1 == "Yes")?$request->visionPanelQuantity:$request->visionPanelQuantityforLeaf2,
                        'AreVPsEqualSizesForLeaf2'              => ($request->vpSameAsLeaf1 == "Yes")?$request->AreVPsEqualSizes:$request->AreVPsEqualSizesForLeaf2,
                        'DistanceFromTopOfDoorForLeaf2'         => $request->distanceFromTopOfDoorforLeaf2,
                        'DistanceFromTheEdgeOfDoorforLeaf2'     => $request->distanceFromTheEdgeOfDoorforLeaf2,
                        'DistanceBetweenVp'                     => $request->distanceBetweenVPsforLeaf2,
                        'Leaf2VPWidth'                          => $request->vP2Width,
                        'Leaf2VPHeight1'                        => $request->vP2Height1,
                        'Leaf2VPHeight2'                        => $request->vP2Height2,
                        'Leaf2VPHeight3'                        => $request->vP2Height3,
                        'Leaf2VPHeight4'                        => $request->vP2Height4,
                        'Leaf2VPHeight5'                        => $request->vP2Height5,
                        'GlassIntegrity'                        => $request->lazingIntegrityOrInsulationIntegrity,
                        'GlassType'                             => $request->glassType,
                        'GlassThickness'                        => $request->glassThickness,
                        'GlazingSystems'                        => $request->glazingSystems,
                        'GlazingSystemThickness'                => $request->glazingSystemsThickness,
                        'GlazingBeads'                          => $request->glazingBeads,
                        'GlazingBeadsThickness'                 => $request->glazingBeadsThickness,
                        'glazingBeadsWidth'                     => $request->glazingBeadsWidth,
                        'glazingBeadsHeight'                    => $request->glazingBeadsHeight,
                        'glazingBeadsFixingDetail'              => $request->glazingBeadsFixingDetail,
                        'GlazingBeadSpecies'                    => $request->glazingBeadSpecies,

                    //Frame
                        'FrameMaterial'                         => $request->frameMaterial,
                        'FrameType'                             => $request->frameType,
                            // streboard
                            'PlantonStopWidth'                  => $request->plantonStopWidth,
                            'PlantonStopHeight'                 => $request->plantonStopHeight,
                            //halspan
                            'standardWidth'                     => $request->standardWidth,
                            'standardHeight'                    => $request->standardHeight,
                        'FrameWidth'                            => $request->frameWidth,
                        'FrameHeight'                           => $request->frameHeight,
                        'FrameDepth'                            => $request->frameDepth,
                        'FrameFinish'                           => $request->frameFinish,
                        'FrameFinishColor'                      => $request->framefinishColor,
                        'ExtLiner'                              => $request->extLiner,
                        'DoorFrameConstruction'                 => $request->frameCostuction,
                        'ExtLinerValue'                         => $request->extLinerValue,
                        'ExtLinerThickness'                     => $request->extLinerThickness,
                        'ExtLinerFInish'                        => $request->extLinerFinish,
                        'IntumescentSeal'                       => $request->intumescentSeal,
                        'IntumescentSealColor'                  => $request->intumescentSealColor,
                        'IntumescentSealSize'                   => $request->intumescentSealSize,
                        'IronmongerySet'                        => $request->ironmongerySet,
                        'IronmongeryID'                         => $request->IronmongeryID,
                        'SpecialFeatureRefs'                    => $request->specialFeatureRefs,

                    //Over Panel Section
                        'Overpanel'                             => $request->overpanel,
                        'OPLippingThickness'                    => $request->OPLippingThickness,
                        'OPWidth'                               => $request->oPWidth,
                        'OPHeigth'                              => $request->oPHeigth,
                        'OPTransom'                             => $request->opTransom,
                        'TransomThickness'                      => $request->transomThickness,
                        'opGlassIntegrity'                      => $request->opGlassIntegrity,
                        'OPGlassType'                           => $request->opGlassType,
                        'OPGlazingBeads'                        => $request->opGlazingBeads,
                        'OPGlazingBeadSpecies'                  => $request->opGlazingBeadSpecies,

                    //Side Light
                        'SideLight1'                            => $request->sideLight1,
                        'SideLight1GlassType'                   => $request->sideLight1GlassType,
                        'BeadingType'                           => $request->SideLight1BeadingType,
                        'SL1GlazingBeadSpecies'                 => $request->SideLight1GlazingBeadSpecies,
                        'SL1Width'                              => $request->SL1Width,
                        'SL1Height'                             => $request->SL1Height,
                        'SL1Depth'                              => $request->SL1Depth,
                        'SL1Transom'                            => $request->SL1Transom,
                        'SideLight2'                            => $request->sideLight2,
                        'DoYouWantToCopySameAsSL1'              => $request->copyOfSideLite1,
                        'SideLight2GlassType'                   => ($request->copyOfSideLite1 == "Yes")?$request->sideLight1GlassType:$request->sideLight2GlassType,
                        'SideLight2BeadingType'                 => ($request->copyOfSideLite1 == "Yes")?$request->SideLight1BeadingType:$request->SideLight2BeadingType,
                        'SideLight2GlazingBeadSpecies'          => ($request->copyOfSideLite1 == "Yes")?$request->SideLight1GlazingBeadSpecies:$request->SideLight2GlazingBeadSpecies,
                        'SL2Width'                              => $request->SL2Width,
                        'SL2Height'                             => $request->SL2Height,
                        'SL2Depth'                              => $request->SL2Depth,
                        'SL2Transom'                            => ($request->copyOfSideLite1 == "Yes")?$request->SL1Transom:$request->SL2Transom,
                        'SLtransomHeightFromTop'                => $request->SLtransomHeightFromTop,
                        'SLtransomThickness'                    => $request->SLtransomThickness,

                    //Lipping And Intumescent
                        'LippingType'                           => $request->lippingType,
                        'LippingThickness'                      => $request->lippingThickness,
                        'LippingSpecies'                        => $request->lippingSpecies,
                        'MeetingStyle'                          => $request->meetingStyle,
                        'ScallopedLippingThickness'             => $request->scallopedLippingThickness,
                        'FlatLippingThickness'                  => $request->flatLippingThickness,
                        'RebatedLippingThickness'               => $request->rebatedLippingThickness,
                        'CoreWidth1'                            => $request->coreWidth1,
                        'CoreWidth2'                            => $request->coreWidth2,
                        'CoreHeight'                            => $request->coreHeight,
                        'IntumescentLeapingSealType'            => $request->intumescentSealType,
                        'IntumescentLeapingSealLocation'        => $request->intumescentSealLocation,
                        'IntumescentLeapingSealColor'           => $request->intumescentSealColor,
                        'IntumescentLeapingSealArrangement'     => $request->intumescentSealArrangement,

                    //Accoustics
                        'Accoustics'                            => $request->accoustics,
                        'rWdBRating'                            => $request->rWdBRating,
                        'perimeterSeal1'                        => $request->perimeterSeal1,
                        'perimeterSeal2'                        => $request->perimeterSeal2,
                        'thresholdSeal1'                        => $request->thresholdSeal1,
                        'thresholdSeal2'                        => $request->thresholdSeal2,
                        'AccousticsMeetingStiles'               => $request->accousticsmeetingStiles,

                    //Architrave
                        'Architrave'                            => $request->Architrave,
                        'ArchitraveMaterial'                    => $request->architraveMaterial,
                        'ArchitraveType'                        => $request->architraveType,
                        'ArchitraveWidth'                       => $request->architraveWidth,
                        'ArchitraveHeight'                      => $request->architraveHeight,
                        'ArchitraveDepth'                       => $request->architraveDepth,
                        'ArchitraveFinish'                      => $request->architraveFinish,
                        'ArchitraveSetQty'                      => $request->architraveSetQty,

                    //Transport
                        // 'VehicleType'                           => $request->vehicleType,
                        // 'DeliveryTime'                          => $request->deliveryTime,
                        // 'Packaging'                             => $request->packaging
            ];

            // if(!empty($SvgImage)){
                // $updateDetails['SvgImage'] = $SvgImage;
                $updateDetails['SvgImage'] = $request->SvgImage;
            // }
            $item = Item::where('itemId',$id)->update($updateDetails);
            // return redirect('quotation/generate/'.$request->QuotationId.'/'.$versionId)->with('success','Updated configure door successfully.');

            $successmsg = 'Updated configure door successfully.';
            $url = 'quotation/generate/'.$request->QuotationId.'/'.$versionId;
            \Session::flash('success', __($successmsg));
            return response()->json(['status'=>'success','data'=>$successmsg,'url'=>$url]);

        } else {
            // checking configurableitems is empty or not
            // if empty then only these code work
            // it update `quotation` table. These tells about Strebord or Halspan door for perticular quotation
            if(empty($Quotation->configurableitems)){
                Quotation::where('id',$QuotationId)->update(["configurableitems" => $pageIdentity]);
            }

            // insert
            $item = new Item();

            // check these `Quotation ID` with `Door Number` is not duplicate entry
            // it show message if it is more than 0 (zero) - Quotation ID with Door Number is already exist.
            $mm =  Item::where(['QuotationId' => $QuotationId , 'DoorType' => $doorType])->count();
            if($mm > 0){
                // return redirect()->back()->withInput()->with('error', 'Door Type '.$doorType.' is already exist for these quotation.');
                $errorlist = 'Door Type '.$doorType.' is already exist for these quotation.';
                return response()->json(['status'=>'error','errors'=>$errorlist]);
            } else {
                // item (insert)
                    $item->QuotationId = $request->QuotationId;
                    $item->UserId = Auth::user()->id;
                    $item->DoorQuantity = 1;
                    //Main Options
                        $item->LeafConstruction = $request->leafConstruction;
                        $item->DoorType = $request->doorType;
                        $item->FireRating = $request->fireRating;
                        $item->DoorsetType = $request->doorsetType;
                        $item->SwingType = $request->swingType;
                        $item->LatchType = $request->latchType;
                        $item->Handing = $request->Handing;
                        $item->OpensInwards = $request->OpensInwards;
                        $item->COC = $request->COC;
                        $item->Tollerance = $request->tollerance;
                        $item->Undercut = $request->undercut;
                        $item->FloorFinish = $request->floorFinish;
                        $item->GAP = $request->gap;
                        $item->FrameThickness = $request->frameThickness;

                    //Door Dimensions & Door Leaf
                        $item->SOWidth = $request->sOWidth;
                        $item->SOHeight = $request->sOHeight;
                        $item->SOWallThick = $request->sODepth;
                        $item->LeafWidth1 = $request->leafWidth1;
                        $item->LeafWidth2 = $request->leafWidth2;
                        $item->LeafHeight = $request->leafHeightNoOP;
                        $item->LeafThickness = $request->doorThickness;
                        $item->DoorLeafFacing = $request->doorLeafFacing;
                        $item->DoorLeafFacingValue = $request->doorLeafFacingValue;
                        $item->DoorLeafFinish = $request->doorLeafFinish;
                        $item->DoorLeafFinishColor = $request->doorLeafFinishColor;
                        $item->SheenLevel = $request->SheenLevel;
                        $item->DecorativeGroves = $request->decorativeGroves;
                        $item->GrooveLocation = $request->grooveLocation;
                        $item->GrooveWidth = $request->grooveWidth;
                        $item->GrooveDepth = $request->grooveDepth;
                        $item->MaxNumberOfGroove = $request->maxNumberOfGroove;
                        $item->NumberOfGroove = $request->numberOfGroove;
                        $item->NumberOfVerticalGroove = $request->numberOfVerticalGroove;
                        $item->NumberOfHorizontalGroove = $request->numberOfHorizontalGroove;

                    //Vision Panel
                        $item->Leaf1VisionPanel = $request->leaf1VisionPanel;
                        $item->Leaf1VisionPanelShape = $request->leaf1VisionPanelShape;
                        $item->VisionPanelQuantity = $request->visionPanelQuantity;
                        $item->AreVPsEqualSizesForLeaf1 = $request->AreVPsEqualSizes;
                        $item->DistanceFromtopOfDoor = $request->distanceFromTopOfDoor;
                        $item->DistanceFromTheEdgeOfDoor = $request->distanceFromTheEdgeOfDoor;
                        $item->DistanceBetweenVPs = $request->distanceBetweenVPs;
                        $item->Leaf1VPWidth = $request->vP1Width;
                        $item->Leaf1VPHeight1 = $request->vP1Height1;
                        $item->Leaf1VPHeight2 = $request->vP1Height2;
                        $item->Leaf1VPHeight3 = $request->vP1Height3;
                        $item->Leaf1VPHeight4 = $request->vP1Height4;
                        $item->Leaf1VPHeight5 = $request->vP1Height5;
                        $item->Leaf1VPAreaSizem2 = $request->leaf1VpAreaSizeM2;
                        $item->Leaf2VisionPanel = $request->leaf2VisionPanel;
                        $item->sVPSameAsLeaf1 = $request->vpSameAsLeaf1;
                        $item->Leaf2VisionPanelQuantity = ($request->vpSameAsLeaf1 == "Yes")?$request->visionPanelQuantity:$request->visionPanelQuantityforLeaf2;
                        $item->AreVPsEqualSizesForLeaf2 = ($request->vpSameAsLeaf1 == "Yes")?$request->AreVPsEqualSizes:$request->AreVPsEqualSizesForLeaf2;
                        $item->DistanceFromTopOfDoorForLeaf2 = $request->distanceFromTopOfDoorforLeaf2;
                        $item->DistanceFromTheEdgeOfDoorforLeaf2 = $request->distanceFromTheEdgeOfDoorforLeaf2;
                        $item->DistanceBetweenVp = $request->distanceBetweenVPsforLeaf2;
                        $item->Leaf2VPWidth = $request->vP2Width;
                        $item->Leaf2VPHeight1 = $request->vP2Height1;
                        $item->Leaf2VPHeight2 = $request->vP2Height2;
                        $item->Leaf2VPHeight3 = $request->vP2Height3;
                        $item->Leaf2VPHeight4 = $request->vP2Height4;
                        $item->Leaf2VPHeight5 = $request->vP2Height5;
                        $item->GlassIntegrity = $request->lazingIntegrityOrInsulationIntegrity;
                        $item->GlassType = $request->glassType;
                        $item->GlassThickness = $request->glassThickness;
                        $item->GlazingSystems = $request->glazingSystems;
                        $item->GlazingSystemThickness = $request->glazingSystemsThickness;
                        $item->GlazingBeads = $request->glazingBeads;
                        $item->GlazingBeadsThickness = $request->glazingBeadsThickness;
                        $item->glazingBeadsWidth = $request->glazingBeadsWidth;
                        $item->glazingBeadsHeight = $request->glazingBeadsHeight;
                        $item->glazingBeadsFixingDetail = $request->glazingBeadsFixingDetail;
                        $item->GlazingBeadSpecies = $request->glazingBeadSpecies;

                    //Frame
                        $item->FrameMaterial = $request->frameMaterial;
                        $item->FrameType = $request->frameType;
                            // streboard
                            $item->PlantonStopWidth = $request->plantonStopWidth;
                            $item->PlantonStopHeight = $request->plantonStopHeight;
                            //halspan
                            $item->standardWidth = $request->standardWidth;
                            $item->standardHeight = $request->standardHeight;
                        $item->FrameWidth = $request->frameWidth;
                        $item->FrameHeight = $request->frameHeight;
                        $item->FrameDepth = $request->frameDepth;
                        $item->FrameFinish = $request->frameFinish;
                        $item->FrameFinishColor = $request->framefinishColor;
                        $item->ExtLiner = $request->extLiner;
                        $item->DoorFrameConstruction = $request->frameCostuction;
                        $item->ExtLinerValue = $request->extLinerValue;
                        $item->extLinerSize = $request->extLinerSize;
                        $item->ExtLinerThickness = $request->extLinerThickness;
                        $item->ExtLinerFInish = $request->extLinerFinish;
                        $item->IntumescentSeal = $request->intumescentSeal;
                        $item->IntumescentSealColor = $request->intumescentSealColor;
                        $item->IntumescentSealSize = $request->intumescentSealSize;
                        $item->IronmongerySet = $request->ironmongerySet;
                        $item->IronmongeryID = $request->IronmongeryID;
                        $item->SpecialFeatureRefs = $request->specialFeatureRefs;

                    //Over Panel Section
                        $item->Overpanel = $request->overpanel;
                        $item->OPLippingThickness = $request->OPLippingThickness;
                        $item->OPWidth = $request->oPWidth;
                        $item->OPHeigth = $request->oPHeigth;
                        $item->OPTransom = $request->opTransom;
                        $item->TransomThickness = $request->transomThickness;
                        $item->opGlassIntegrity = $request->opGlassIntegrity;
                        $item->OPGlassType = $request->opGlassType;
                        $item->OPGlazingBeads = $request->opGlazingBeads;
                        $item->OPGlazingBeadSpecies = $request->opGlazingBeadSpecies;

                    //Side Light
                        $item->SideLight1 = $request->sideLight1;
                        $item->SideLight1GlassType = $request->sideLight1GlassType;
                        $item->BeadingType = $request->SideLight1BeadingType;
                        $item->SL1GlazingBeadSpecies = $request->SideLight1GlazingBeadSpecies;
                        $item->SL1Width = $request->SL1Width;
                        $item->SL1Height = $request->SL1Height;
                        $item->SL1Depth = $request->SL1Depth;
                        $item->SL1Transom = $request->SL1Transom;
                        $item->SideLight2 = $request->sideLight2;
                        $item->DoYouWantToCopySameAsSL1 = $request->copyOfSideLite1;
                        $item->SideLight2GlassType = ($request->copyOfSideLite1 == "Yes")?$request->sideLight1GlassType:$request->sideLight2GlassType;
                        $item->SideLight2BeadingType = ($request->copyOfSideLite1 == "Yes")?$request->SideLight1BeadingType:$request->SideLight2BeadingType;
                        $item->SideLight2GlazingBeadSpecies = ($request->copyOfSideLite1 == "Yes")?$request->SideLight1GlazingBeadSpecies:$request->SideLight2GlazingBeadSpecies;
                        $item->SL2Width = $request->SL2Width;
                        $item->SL2Height = $request->SL2Height;
                        $item->SL2Depth = $request->SL2Depth;
                        $item->SL2Transom = ($request->copyOfSideLite1 == "Yes")?$request->SL1Transom:$request->SL2Transom;
                        $item->SLtransomHeightFromTop = $request->SLtransomHeightFromTop;
                        $item->SLtransomThickness = $request->SLtransomThickness;

                    //Lipping And Intumescent
                        $item->LippingType = $request->lippingType;
                        $item->LippingThickness = $request->lippingThickness;
                        $item->LippingSpecies = $request->lippingSpecies;
                        $item->MeetingStyle = $request->meetingStyle;
                        $item->ScallopedLippingThickness = $request->scallopedLippingThickness;
                        $item->FlatLippingThickness = $request->flatLippingThickness;
                        $item->RebatedLippingThickness = $request->rebatedLippingThickness;
                        $item->CoreWidth1 = $request->coreWidth1;
                        $item->CoreWidth2 = $request->coreWidth2;
                        $item->CoreHeight = $request->coreHeight;
                        $item->IntumescentLeapingSealType = $request->intumescentSealType;
                        $item->IntumescentLeapingSealLocation = $request->intumescentSealLocation;
                        $item->IntumescentLeapingSealColor = $request->intumescentSealColor;
                        $item->IntumescentLeapingSealArrangement = $request->intumescentSealArrangement;

                    //Accoustics
                        $item->Accoustics = $request->accoustics;
                        $item->rWdBRating = $request->rWdBRating;
                        $item->perimeterSeal1 = $request->perimeterSeal1;
                        $item->perimeterSeal2 = $request->perimeterSeal2;
                        $item->thresholdSeal1 = $request->thresholdSeal1;
                        $item->thresholdSeal2 = $request->thresholdSeal2;
                        $item->AccousticsMeetingStiles = $request->accousticsmeetingStiles;

                    //Architrave
                        $item->Architrave = $request->Architrave;
                        $item->ArchitraveMaterial = $request->architraveMaterial;
                        $item->ArchitraveType = $request->architraveType;
                        $item->ArchitraveWidth = $request->architraveWidth;
                        $item->ArchitraveHeight = $request->architraveHeight;
                        $item->ArchitraveDepth = $request->architraveDepth;
                        $item->ArchitraveFinish = $request->architraveFinish;
                        $item->ArchitraveSetQty = $request->architraveSetQty;

                        $item->SvgImage = $request->SvgImage;

                    //Transport
                        // $item->VehicleType = $request->vehicleType;
                        // $item->DeliveryTime = $request->deliveryTime;
                        // $item->Packaging = $request->packaging;

                    $item->save();
                // if(!empty($request->SvgImage)){
                //     $Quotation = Quotation::where("id",$QuotationId)->first();
                //     $Base64SvgImage = str_replace("data:image/png;base64,","",$request->SvgImage);
                //     $SvgImage = str_replace("#","",$Quotation->QuotationGenerationId)."-".$item->id."-".(($request->version_id != 0)?$request->version_id:"1").".png";
                //     $fileData = base64_decode($Base64SvgImage);
                //     $path = public_path('uploads/files/' . $SvgImage);
                //     $fp = fopen($path, "wb");
                //     fwrite($fp, $fileData);
                //     fclose($fp);
                //     Item::where('itemId',$item->id)->update(["SvgImage" => $SvgImage]);
                // }
                $successmsg = 'Configure door successfully, now please add door\'s.';
                $url = 'quotation/add-new-doors/'.$request->QuotationId.'/'.$versionId;
                \Session::flash('success', __($successmsg));
                return response()->json(['status'=>'success','data'=>$successmsg,'url'=>$url]);
            }
        }
    }


    public function getHandingOptions(request $request){
        if(empty($request->doorsetType)){
            ms(array(
                'st' => "0",
                'txt' => '',
                'html' => "",
            ));
        }

        if(empty($request->doorsetType)){
            ms(array(
                'st' => "0",
                'txt' => '',
                'html' => "",
            ));
        }

        $pageId = $request->pageId;
        $doorsetType = $request->doorsetType;
        $swingType = $request->swingType;
        $optionResponse = Option::where('configurableitems', $pageId)->where('OptionSlug','Handing')->Where('UnderAttribute',$swingType)->where('UnderParent2',$doorsetType)->get()->toArray();
        if($optionResponse!='' && count($optionResponse)>0){
            echo json_encode(array('status'=>'ok', 'data'=>$optionResponse));
        }else{
            echo json_encode(array('status'=>'error', 'data'=>''));
        }
    }


    public function Filterintumescentseals(Request $request)
    {
        $pageId = $request->pageId;
        $fireRatingValue = $request->fireRatingValue;
        $intumescentseals = $request->intumescentseals;
        $leafWidth1Value = (float)$request->leafWidth1Value;
        $leafHeightNoOPValue = (float)$request->leafHeightNoOPValue;
        $doorLeafFacingValueNew = $request->doorLeafFacingValueNew; // CS_acrovyn
        $frameMaterialNew = $request->frameMaterialNew; // MDF

        if(!empty($leafWidth1Value) && !empty($leafHeightNoOPValue)){


            $data = '';
            $configuration = '';
            $width = $height = 0;

            $width = (int)$request->leafWidth1Value;
            $height = (int)$request->leafHeightNoOPValue;

            $configuration = $request->intumescentseals;

            $allValue =  $frameMaterialNew.'===|'.$doorLeafFacingValueNew.'===|'.$intumescentseals . '==' . $fireRatingValue .'=='.$leafHeightNoOPValue .'=='. $leafWidth1Value;

            if($fireRatingValue == 'NFR'){
                $IntumescentSeals_A = SettingIntumescentSeals2::where(['configurableitems' => $pageId , 'configuration' => $intumescentseals])->get();

                $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId && `configuration` = '$intumescentseals'";
            } else {
                if($doorLeafFacingValueNew == 'CS_acrovyn' && $fireRatingValue == 'FD30'){
                    $IntumescentSeals_A = SettingIntumescentSeals2::where(['configurableitems' => $pageId , 'configuration' => $intumescentseals,'firerating' => $fireRatingValue,'tag' => 'FD60AcrovynFaced'])->get();

                    $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId  && `configuration` = '$intumescentseals' && `firerating` = '$fireRatingValue' && `tag` = 'FD30AcrovynFaced'  && $leafHeightNoOPValue <= `height_max` && $leafWidth1Value<= `width_max`";
                } else if($doorLeafFacingValueNew == 'CS_acrovyn' && $fireRatingValue == 'FD60'){
                    $IntumescentSeals_A = SettingIntumescentSeals2::where(['configurableitems' => $pageId , 'configuration' => $intumescentseals,'firerating' => $fireRatingValue,'tag' => 'FD60AcrovynFaced'])->get();

                    $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId && `firerating` = '$fireRatingValue' && `tag` = 'FD60AcrovynFaced' && `configuration` = '$intumescentseals' && $leafHeightNoOPValue <= `height_max` && $leafWidth1Value<= `width_max`";
                } else if($frameMaterialNew == 'MDF'){
                    $IntumescentSeals_A = SettingIntumescentSeals2::where(['configurableitems' => $pageId , 'configuration' => $intumescentseals,'firerating' => $fireRatingValue,'tag' => 'FD60MDFFrames'])->get();

                    $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId && `firerating` = '$fireRatingValue' && `tag` = 'FD60MDFFrames' && `configuration` = '$intumescentseals' && $leafHeightNoOPValue <= `height_max` && $leafWidth1Value<= `width_max`";
                } else {
                    $IntumescentSeals_A = SettingIntumescentSeals2::where(['configurableitems' => $pageId , 'configuration' => $intumescentseals,'firerating' => $fireRatingValue,'tag' => $fireRatingValue])->get();

                    $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId && `firerating` = '$fireRatingValue' && `tag` = '$fireRatingValue' && `configuration` = '$intumescentseals' && $leafHeightNoOPValue <= `height_max` && $leafWidth1Value<= `width_max`";
                }
            }



            foreach($IntumescentSeals_A as $content){
                $selected = "";
                if($fireRatingValue == 'NFR'){
                    if($request->SelectedValue == $content["id"]){
                        $selected = "selected";
                    }
                    $data .= '<option value="'.$content["id"].'" '.$selected.'>'.$content["brand"].' - '.$content["intumescentSeals"].'</option>';
                } else {
                    if( checkValid($height, $width, $content) ){
                        if($request->SelectedValue == $content["id"]){
                            $selected = "selected";
                        }
                        // echo "id: " . $content["id"] . "&nbsp;&nbsp;&nbsp;" . "\tintumescentSeals: &nbsp;" . $content["intumescentSeals"] . "<br>";
                        // $data .=  "id: " . $content["id"] . "\t" . "configuration: " . $content["configuration"]. "\t" . "intumescentSeals: " . $content["intumescentSeals"] . "\t". $content["widthPoint1"] . "\t" . $content["widthPoint2"] . "\t" . $content["heightPoint1"] . "\t" . $content["heightPoint2"] . "<br>";
                        $data .= '<option value="'.$content["id"].'" '.$selected.'>'.$content["brand"].' - '.$content["intumescentSeals"].'</option>';
                    }
                }
            }

            if($data != ''){
                $IS = '<option value="">Select Intumescent Seal Arrangement</option>'.$data;
                echo json_encode(array('status'=>'ok','data'=> $IS , 'c'=>$IntumescentSeals_A,'allValue'=>$allValue ,'sql'=> $sql,'msg'=>'null'));
            } else {
                // $msg = "In $fireRatingValue, Leaf Width 1 = $leafWidth1Value and Leaf Height = $leafHeightNoOPValue for `$intumescentseals` is not possible.";
                $msg = "It’s not possible to make this door with these configurations. Fire Rating = $fireRatingValue | Leaf Width 1 = $leafWidth1Value | Leaf Height = $leafHeightNoOPValue | Configuration = $intumescentseals ";
                echo json_encode(array('status'=>'error2','data'=> $data, 'c'=>$IntumescentSeals_A,'allValue'=>$allValue,'sql'=> $sql,'msg'=>$msg));
            }


        } else {
            $msg = "Leaf Width 1 and Leaf Height is never be null.";
            echo json_encode(array('status'=>'error2','data'=> 'null', 'c'=>'null','allValue'=>'null','sql'=> 'null','msg'=>$msg));
        }
    }


    // public function Filterintumescentseals(Request $request)
    // {
    //     $pageId = $request->pageId;
    //     $fireRatingValue = $request->fireRatingValue;
    //     $intumescentseals = $request->intumescentseals;
    //     $leafWidth1Value = (float)$request->leafWidth1Value;
    //     $leafHeightNoOPValue = (float)$request->leafHeightNoOPValue;
    //     $doorLeafFacingValueNew = $request->doorLeafFacingValueNew; // CS_acrovyn
    //     $frameMaterialNew = $request->frameMaterialNew; // MDF

    //     if(!empty($leafWidth1Value) && !empty($leafHeightNoOPValue)){


    //         $data = '';
    //         $configuration = '';
    //         $width = $height = 0;

    //         $width = (int)$request->leafWidth1Value;
    //         $height = (int)$request->leafHeightNoOPValue;

    //         $configuration = $request->intumescentseals;

    //         $allValue =  $frameMaterialNew.'===|'.$doorLeafFacingValueNew.'==='.$intumescentseals . '==' . $fireRatingValue .'=='.$leafHeightNoOPValue .'=='. $leafWidth1Value;

    //         if($doorLeafFacingValueNew == 'CS_acrovyn' && $fireRatingValue == 'FD30'){
    //             $IntumescentSeals_A = SettingIntumescentSeals2::where(['configurableitems' => $pageId , 'configuration' => $intumescentseals,'firerating' => $fireRatingValue,'tag' => 'FD60AcrovynFaced'])->get();

    //             $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId  && `configuration` = '$intumescentseals' && `firerating` = '$fireRatingValue' && `tag` = 'FD30AcrovynFaced'  && $leafHeightNoOPValue <= `height_max` && $leafWidth1Value<= `width_max`";
    //         } else if($doorLeafFacingValueNew == 'CS_acrovyn' && $fireRatingValue == 'FD60'){
    //             $IntumescentSeals_A = SettingIntumescentSeals2::where(['configurableitems' => $pageId , 'configuration' => $intumescentseals,'firerating' => $fireRatingValue,'tag' => 'FD60AcrovynFaced'])->get();

    //             $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId && `firerating` = '$fireRatingValue' && `tag` = 'FD60AcrovynFaced' && `configuration` = '$intumescentseals' && $leafHeightNoOPValue <= `height_max` && $leafWidth1Value<= `width_max`";
    //         } else if($frameMaterialNew == 'MDF'){
    //             $IntumescentSeals_A = SettingIntumescentSeals2::where(['configurableitems' => $pageId , 'configuration' => $intumescentseals,'firerating' => $fireRatingValue,'tag' => 'FD60MDFFrames'])->get();

    //             $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId && `firerating` = '$fireRatingValue' && `tag` = 'FD60MDFFrames' && `configuration` = '$intumescentseals' && $leafHeightNoOPValue <= `height_max` && $leafWidth1Value<= `width_max`";
    //         } else {
    //             $IntumescentSeals_A = SettingIntumescentSeals2::where(['configurableitems' => $pageId , 'configuration' => $intumescentseals,'firerating' => $fireRatingValue,'tag' => $fireRatingValue])->get();

    //             $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId && `firerating` = '$fireRatingValue' && `tag` = '$fireRatingValue' && `configuration` = '$intumescentseals' && $leafHeightNoOPValue <= `height_max` && $leafWidth1Value<= `width_max`";
    //         }

    //         foreach($IntumescentSeals_A as $content){
    //             if( checkValid($height, $width, $content) ){
    //                 // echo "id: " . $content["id"] . "&nbsp;&nbsp;&nbsp;" . "\tintumescentSeals: &nbsp;" . $content["intumescentSeals"] . "<br>";
    //                 $data .=  "id: " . $content["id"] . "\t" . "configuration: " . $content["configuration"]. "\t" . "intumescentSeals: " . $content["intumescentSeals"] . "\t". $content["widthPoint1"] . "\t" . $content["widthPoint2"] . "\t" . $content["heightPoint1"] . "\t" . $content["heightPoint2"] . "<br>";
    //             }
    //         }
    //             return $data;
    //         if(count($IntumescentSeals_A) > 0){
    //             echo json_encode(array('status'=>'ok','data'=> $data , 'c'=>'null','allValue'=>$allValue ,'sql'=> $sql,'msg'=>'null'));
    //         } else {
    //             $msg = "In $fireRatingValue, Leaf Width 1 = $leafWidth1Value and Leaf Height = $leafHeightNoOPValue for `$intumescentseals` is not possible.";
    //             echo json_encode(array('status'=>'error2','data'=> $data, 'c'=>'null','allValue'=>$allValue,'sql'=> $sql,'msg'=>$msg));
    //         }

    //         // $IntumescentSeals = SettingIntumescentSeals2::
    //         // where($leafHeightNoOPValue,'<=','height_max' )
    //         // ->where($leafWidth1Value,'<=','width_max')
    //         // ->where($leafWidth1Value,'<=',2029.5-11*$leafHeightNoOPValue/24)
    //         // ->get();
    //         // SELECT * FROM `setting_intumescentseals` WHERE `height_max` >= 2100 AND `width_max` >= 1067


    //             // $c = round(2029.5-11*$leafHeightNoOPValue/24);
    //             // if($leafWidth1Value <= $c){
    //             //     $allValue =  $frameMaterialNew.'===|'.$doorLeafFacingValueNew.'==='.$intumescentseals . '==' . $fireRatingValue .'=='.$leafHeightNoOPValue .'=='. $leafWidth1Value;

    //             //     if($doorLeafFacingValueNew == 'CS_acrovyn' && $fireRatingValue == 'FD30'){
    //             //         $IntumescentSeals_A = SettingIntumescentSeals2::
    //             //         where(['configurableitems' => $pageId , 'configuration' => $intumescentseals,'firerating' => $fireRatingValue,'tag' => 'FD30AcrovynFaced'])
    //             //             ->where('height_max' , '>=' ,  $leafHeightNoOPValue)
    //             //             ->where('width_max' , '>=' , $leafWidth1Value)
    //             //             ->get();

    //             //         $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId && `firerating` = '$fireRatingValue' && `tag` = 'FD30AcrovynFaced' && `configuration` = '$intumescentseals' && $leafHeightNoOPValue <= `height_max` && $leafWidth1Value<= `width_max`";
    //             //     } else if($doorLeafFacingValueNew == 'CS_acrovyn' && $fireRatingValue == 'FD60'){
    //             //         $IntumescentSeals_A = SettingIntumescentSeals2::
    //             //         where(['configurableitems' => $pageId , 'configuration' => $intumescentseals,'firerating' => $fireRatingValue,'tag' => 'FD60AcrovynFaced'])
    //             //             ->where('height_max' , '>=' ,  $leafHeightNoOPValue)
    //             //             ->where('width_max' , '>=' , $leafWidth1Value)
    //             //             ->get();

    //             //         $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId && `firerating` = '$fireRatingValue' && `tag` = 'FD60AcrovynFaced' && `configuration` = '$intumescentseals' && $leafHeightNoOPValue <= `height_max` && $leafWidth1Value<= `width_max`";
    //             //     } else if($frameMaterialNew == 'MDF'){
    //             //         $IntumescentSeals_A = SettingIntumescentSeals2::
    //             //         where(['configurableitems' => $pageId , 'configuration' => $intumescentseals,'firerating' => $fireRatingValue,'tag' => 'FD60MDFFrames'])
    //             //             ->where('height_max' , '>=' ,  $leafHeightNoOPValue)
    //             //             ->where('width_max' , '>=' , $leafWidth1Value)
    //             //             ->get();

    //             //         $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId && `firerating` = '$fireRatingValue' && `tag` = 'FD60MDFFrames' && `configuration` = '$intumescentseals' && $leafHeightNoOPValue <= `height_max` && $leafWidth1Value<= `width_max`";
    //             //     } else {
    //             //         $IntumescentSeals_A = SettingIntumescentSeals2::
    //             //         where(['configurableitems' => $pageId , 'configuration' => $intumescentseals,'firerating' => $fireRatingValue,'tag' => $fireRatingValue])
    //             //             ->where('height_max' , '>=' ,  $leafHeightNoOPValue)
    //             //             ->where('width_max' , '>=' , $leafWidth1Value)
    //             //             ->get();

    //             //         $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId && `firerating` = '$fireRatingValue' && `tag` = '$fireRatingValue' && `configuration` = '$intumescentseals' && $leafHeightNoOPValue <= `height_max` && $leafWidth1Value<= `width_max`";
    //             //     }

    //             //     if(count($IntumescentSeals_A) > 0){
    //             //         echo json_encode(array('status'=>'ok','data'=> $IntumescentSeals_A , 'c'=>$c,'allValue'=>$allValue ,'sql'=> $sql,'msg'=>'null'));
    //             //     } else {
    //             //         $msg = "In $fireRatingValue, Leaf Width 1 = $leafWidth1Value and Leaf Height = $leafHeightNoOPValue for `$intumescentseals` is not possible.";
    //             //         echo json_encode(array('status'=>'error2','data'=> $IntumescentSeals_A, 'c'=>$c,'allValue'=>$allValue,'sql'=> $sql,'msg'=>$msg));
    //             //     }
    //             // } else {
    //             //     $msg = "In $fireRatingValue, Leaf Width 1 = $leafWidth1Value and Leaf Height = $leafHeightNoOPValue for `$intumescentseals` is not possible.";
    //             //     echo json_encode(array('status'=>'error2','data'=> 'null', 'c'=>$c,'allValue'=>'null','sql'=> 'null','msg'=>$msg));
    //             // }

    //         // SELECT * FROM `setting_intumescentseals` WHERE H <= `height_max` && W<= `width_max` && W<=2029.5-11*H/24
    //         // SELECT * FROM `setting_intumescentseals` WHERE 2200 <= `height_max` && 950<= `width_max` && 950<=2029.5-11*2200/24

    //     } else {
    //         $msg = "Leaf Width 1 and Leaf Height is never be null.";
    //         echo json_encode(array('status'=>'error2','data'=> 'null', 'c'=>'null','allValue'=>'null','sql'=> 'null','msg'=>$msg));
    //     }
    // }


    public function opGlassTypeFilterUrl(Request $request)
    {
        $pageId = $request->pageId;
        $fireRating = $request->fireRating;
        $opGlassIntegrity = $request->opGlassIntegrity;
        $userType = Auth::user()->UserType;
        if($userType=="1" || $userType=="4"){
            if($fireRating=="NFR"){
                $opglassType = Option::where('configurableitems',$pageId)->where('OptionSlug','leaf1_glass_type')->get();
            } else {
                $opglassType = Option::where('configurableitems',$pageId)
                    ->where('UnderAttribute',$fireRating)
                    ->where('OptionSlug','leaf1_glass_type')
                    ->where('UnderParent2',$opGlassIntegrity)
                    ->distinct('options.id')
                    ->get();
            }
        } else {
            if($fireRating=="NFR"){
                $opglassType = Option::Join('selected_option', function($join) {
                    $join->on('options.OptionKey', '=', 'selected_option.SelectedOptionKey');
                })
                    ->where('options.configurableitems',$pageId)
                    // ->where('options.UnderAttribute',$fireRating)
                    ->where('options.OptionSlug','leaf1_glass_type')
                    ->where('options.UnderParent2',$opGlassIntegrity)
                    ->where('selected_option.SelectedUserId',Auth::user()->id)
                    ->distinct('options.id')
                    ->get(['options.*']);
            } else {
                $opglassType = Option::Join('selected_option', function($join) {
                    $join->on('options.OptionKey', '=', 'selected_option.SelectedOptionKey');
                })
                    ->where('options.configurableitems',$pageId)
                    ->where('options.UnderAttribute',$fireRating)
                    ->where('options.OptionSlug','leaf1_glass_type')
                    ->where('options.UnderParent2',$opGlassIntegrity)
                    ->where('selected_option.SelectedUserId',Auth::user()->id)
                    ->distinct('options.id')
                    ->get(['options.*']);
            }
        }
        if(!empty($opglassType) && count( $opglassType)){
            echo json_encode(array('status'=>'ok','data'=> $opglassType));
        }else{
            echo json_encode(array('status'=>'error','data'=> ''));
        }
    }

}
