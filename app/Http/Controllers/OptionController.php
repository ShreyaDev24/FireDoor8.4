<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Item;
use App\Models\Option;
use App\Models\GlassType;
use App\Models\LeafType;
use App\Models\SelectedLeafType;
use App\Models\Color;
use App\Models\SelectedOption;
use App\Models\SelectedColor;
use App\Models\DoorDimension;
use App\Models\IntumescentSealLeafType;
use App\Models\SettingIntumescentSeals2;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\SelectedIntumescentSeals2;
use App\Imports\DoorScheduleImport;
use App\Models\LippingSpecies;
use App\Models\LippingSpeciesItems;
use App\Models\SelectedLippingSpecies;
use App\Models\SelectedLippingSpeciesItems;
use App\Models\SelectedDoordimension;
use App\Models\SelectedGlassType;
use App\Models\SelectedGlazingSystem;
use App\Models\GlazingSystem;
use App\Models\GlassGlazingSystem;
use App\Models\IntumescentSealColor;
use App\Models\SelectedIntumescentSealColor;
use App\Models\Accoustics;
use App\Models\SelectedAccoustics;
use App\Models\DoorLeafFacing;
use App\Models\SelectedDoorLeafFacing;
use App\Models\SelectedArchitraveType;
use App\Models\ArchitraveType;
use Session;
use Illuminate\Support\Facades\Auth;

use App\Models\ConfigurableItems;
use Illuminate\Support\Facades\DB;
use App\Models\{ScreenGlassType, ScreenGlazingType, OverpanelGlassGlazing,SelectedOverpanelGlassGlazing, SelectedScreenGlass, SelectedScreenGlazing};

class OptionController extends Controller
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
    public function index($id)
    {
        if (Auth::user()->UserType == 1) {
            $option = Option::select('OptionSlug', 'OptionName')->orderBy('OptionName')->distinct()->get();
            $firerating = Option::where('OptionSlug', 'fire_rating')->get();
            $ConfigurableItems = ConfigurableItems::get();
            $upd = Option::where('id', $id)->first();
        }
        if ($id > 0) {
            $ci = ConfigurableItems::where('id', $upd->configurableitems)->first();
        } else {
            $ci = '';
        }
        return view('option/addOption', compact('option', 'firerating', 'id', 'upd', 'ConfigurableItems', 'ci'));
    }

    public function index1($id, $optionType)
    {
        if (Auth::user()->UserType == 2) {
            $UserId = ['1', Auth::user()->id];
        } else {
            $UserId = ['1'];
        }
        if (Auth::user()->UserType == 2) {
            $slug = [$optionType];
            // $slug = ['door_leaf_facing_value','Glass_Integrity','leaf1_glass_type','leaf1_glazing_systems','Accoustics','Intumescent_Seal_Color'];
            $option = Option::select('OptionSlug', 'OptionName')->orderBy('OptionName')->where('OptionSlug', $slug)->distinct()->get();
            $firerating = Option::where('OptionSlug', 'fire_rating')->get();
            $ConfigurableItems = ConfigurableItems::get();
            $upd = Option::where('id', $id)->first();
        }
        if ($id > 0) {
            $ci = ConfigurableItems::where('id', $upd->configurableitems)->first();
        } else {
            $ci = '';
        }
        return view('option/addOption1', compact('option', 'firerating', 'id', 'upd', 'ConfigurableItems', 'ci', 'optionType'));
    }

    public function filterConfiguretype(Request $request)
    {
        if (Auth::user()->UserType == 2) {
            $UserId = ['1', Auth::user()->id];
        } else {
            $UserId = ['1'];
        }
        $value = ['FD30s', 'FD60s'];
        $pageId = $request->pageId;
        $option = Option::where(['configurableitems' => $pageId, 'OptionSlug' => 'fire_rating'])->wherein('editBy', $UserId)->whereNotIn('OptionKey', $value)->get();
        $tbl = '<option value="">Select Fire Rating</option>';
        foreach ($option as $rr) {
            $tbl .= '<option value="' . $rr->OptionKey . '">' . $rr->OptionKey . '</option>';
        }
        return $tbl;
        // echo json_encode([
        //     'status'=>'success',
        //     'data' => $option
        // ]);
    }

    public function get(request $request)
    {
        $where = $request->where;
        $where = explode(",", $where);
        if (!empty($where)) {

            $option = Option::where([$where])->get();

            if (!empty($option) && count($option) > 0) {

                ms(array(
                    'st' => "1",
                    'txt' => 'Data found',
                    'data' => $option,
                ));
            } else {

                ms(array(
                    'st' => "0",
                    'txt' => 'Data not found!!',
                    'data' => "",
                ));
            }
        } else {

            $option = Option::get();
            if (!empty($option) && count($option) > 0) {

                ms(array(
                    'st' => "0",
                    'txt' => 'Data not found',
                    'data' => $option,
                ));
            } else {

                ms(array(
                    'st' => "0",
                    'txt' => 'Data not found!!',
                    'data' => "",
                ));
            }
        }
    }



    public function store(request $request)
    {
        $update_val = $request->update;
        if (!is_null($update_val)) {
            $valid = $request->validate(
                [
                    'OptionValue' => 'required',
                    'image' => 'mimes:jpeg,jpg,png,gif|max:10000'
                ],
                [
                    'OptionValue.required' => 'The Option Value field is required.',
                ]
            );

            $data = Option::find($update_val);

            $data->OptionValue = $request->OptionValue;
            $data->OptionCost = $request->OptionCost;
            $image = $request->image;
            if (!empty($image)) {
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $data->file = $imageName;
                $image->move(public_path('uploads/Options'), $imageName);
            }
            $data->save();
        } else {
            $valid = $request->validate(
                [
                    'InputOptName' => 'required',
                    'optionSlug' => 'required'
                ],
                [
                    'InputOptName.required' => 'The Option Name field is required.',
                    'optionSlug.required' => 'The Option Slug field is required.',
                ]
            );
            $ParentAttr = $request->ParentAttr;
            $selectOptionName = $request->selectOptionName;
            $optionID = $request->optionID;
            $OptionName2 = $request->OptionName2;
            $firerating = $request->firerating;
            $configurableitems = $request->configurableitems;
            $UnderParent2 = '';
            if (!empty($optionID)) {
                $opt = Option::where('id', $optionID)->first();
                if ($opt->OptionSlug == 'Accoustics') {
                    $UnderAttribute = $opt->UnderAttribute;
                } else if ($opt->OptionSlug == 'Glass_Integrity') {
                    $UnderAttribute = $firerating;
                    $UnderParent2 = $opt->OptionKey;
                } else if ($opt->OptionSlug == 'door_leaf_facing_value') {
                    $UnderAttribute = $opt->UnderAttribute;
                } else {
                    $UnderAttribute = $opt->OptionKey;
                }
            } else {
                $UnderAttribute = null;
            }

            $op = Option::where('OptionSlug', $selectOptionName)->get();
            $optVal = '';
            foreach ($op as $tt) {
                if (!empty($tt->UnderAttribute)) {
                    $optValue = $tt->UnderAttribute . ' | ' . $tt->OptionValue;
                } else {
                    $optValue = $tt->OptionValue;
                }

                if ($optionID == $tt->id) {
                    $select = 'selected';
                } else {
                    $select = null;
                }
                $optVal .= '<option value="' . $tt->id . '" ' . $select . '>' . $optValue . '</option>';
            }

            $InputOptName = $request->InputOptName;
            $optionSlug = $request->optionSlug;
            if ($optionSlug == 'leaf1_glazing_systems') {
                $UnderAttribute = $firerating;
            }

            $images = $request->file('image');
            if ($request->hasFile('image')) {
                foreach ($images as $image) {
                    $filename = time() . '-' . $image->getClientOriginalName();
                    $destination = public_path('uploads/Options');
                    $ext = pathinfo($destination . $filename, PATHINFO_EXTENSION);
                    if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png') {
                        $image->move($destination, $filename);
                    }
                }
            }

            $count = count($request->OptionKey);
            $i = 0;
            while ($count > $i) {
                $data = new Option();
                $file = $_FILES['image']['name'][$i];
                if (!empty($file)) {
                    $data->file = time() . '-' . $file;
                } else {
                    $data->file = null;
                }

                $data->configurableitems = $configurableitems;
                $data->firerating = $firerating;
                $data->optionName = $InputOptName;
                $data->OptionSlug = $optionSlug;
                $data->OptionKey = $request->OptionKey[$i];
                $data->OptionValue = $request->OptionValue[$i];
                $data->OptionStatus = 1;
                $data->UnderAttribute = $UnderAttribute;
                $data->UnderParent2 = $UnderParent2;
                $data->OptionCost = $request->OptionCost[$i];
                $data->editBy = Auth::user()->id;
                $data->save();

                $i++;
            }
        } // else part end here

        if (!is_null($update_val)) {
            return redirect()->back()->with('success', 'Fields Option update successfully!');
        } else {
            return redirect()->back()->with(['success' => 'Fields Option added successfully!', 'InputOptName' => $InputOptName, 'optionSlug' => $optionSlug, 'ParentAttr' => $ParentAttr, 'selectOptionName' => $selectOptionName, 'optionID' => $optVal, 'OptionName2' => $OptionName2, 'firerating' => $firerating]);
        }
    }

    public function list($pageId)
    {
        if (Auth::user()->UserType == 2) {
            $UserId = ['1', Auth::user()->id];
        } else {
            $UserId = ['1'];
        }
        $data = Option::where(['configurableitems' => $pageId, 'is_deleted' => 0])->wherein('editBy', $UserId)->orderBy('id', 'desc')->get();
        return view('option.OptionList', compact('data', 'pageId'));
    }

    public function edit(request $request)
    {
        if (isset($request->edit)) {
            Session::put('edit_option', $request->edit);
            return redirect()->route('options/edit');
        } else {
            $id = Session::get('edit_option');
            $editdata = Option::where('id', $id)->first();
            return view('option.addOption', compact('editdata'));
        }
    }

    public function delete(request $request)
    {
        $data = Option::where('id', $request->id)->first();
        $data->is_deleted = 1;
        $data->save();
        $flash = "deleted";
        return 'success';
        // $request->session()->flash($flash, 'data');
        // $url = route('options/list',$data->configurableitems);
        // return $url;
        // return redirect()->route('options/list',$data->configurableitems);
    }

    public function deleteGlassType(request $request)
    {
        switch($request->optionType){
            case 'GlassGlazingSystem':
                GlassGlazingSystem::where('id', $request->id)->delete();
            break;
            case 'leaf_type':
                LeafType::where('id', $request->id)->delete();
                if(Auth::user()->id == 1){
                    SelectedLeafType::where('leaf_id', $request->id)->delete();
                }else{
                    SelectedLeafType::where('leaf_id', $request->id)->where('editBy', Auth::user()->id)->delete();
                }

            break;
            case 'leaf1_glass_type':
                GlassType::where('id', $request->id)->delete();
                if(Auth::user()->id == 1){
                    SelectedGlassType::where('glass_id', $request->id)->delete();
                }else{
                    SelectedGlassType::where('glass_id', $request->id)->where('editBy', Auth::user()->id)->delete();
                }

            break;
            case 'leaf1_glazing_systems':
                GlazingSystem::where('id', $request->id)->delete();
                if(Auth::user()->id == 1){
                    SelectedGlazingSystem::where('glazingId', $request->id)->delete();
                }else{
                    SelectedGlazingSystem::where('glazingId', $request->id)->where('userId', Auth::user()->id)->delete();
                }

            break;
            case 'Intumescent_Seal_Color':
                IntumescentSealColor::where('id', $request->id)->delete();
                if(Auth::user()->id == 1){
                    SelectedIntumescentSealColor::where('intumescentSealColorId', $request->id)->delete();
                }else{
                    SelectedIntumescentSealColor::where('intumescentSealColorId', $request->id)->where('userId', Auth::user()->id)->delete();
                }

            break;
            case 'Accoustics':
                Accoustics::where('id', $request->id)->delete();
                if(Auth::user()->id == 1){
                    SelectedAccoustics::where('accousticsId', $request->id)->delete();
                }else{
                    SelectedAccoustics::where('accousticsId', $request->id)->where('userId', Auth::user()->id)->delete();
                }

            break;
            case 'door_leaf_facing_value':
                DoorLeafFacing::where('id', $request->id)->delete();
                if(Auth::user()->id == 1){
                    SelectedDoorLeafFacing::where('doorLeafFacingId', $request->id)->delete();
                }else{
                    SelectedDoorLeafFacing::where('doorLeafFacingId', $request->id)->where('userId', Auth::user()->id)->delete();
                }

            break;
            case 'Architrave_Type':
                ArchitraveType::where('id', $request->id)->delete();
                if(Auth::user()->id == 1){
                    SelectedArchitraveType::where('architraveTypeId', $request->id)->delete();
                }else{
                    SelectedArchitraveType::where('architraveTypeId', $request->id)->where('userId', Auth::user()->id)->delete();
                }

            break;
            case 'color_list':
                Color::where('id', $request->id)->delete();
                if(Auth::user()->id == 1){
                    SelectedColor::where('SelectedColorId', $request->id)->delete();
                }else{
                    SelectedColor::where('SelectedColorId', $request->id)->where('SelectedUserId', Auth::user()->id)->delete();
                }

            break;
            case 'intumescentSealArrangement':
                SettingIntumescentSeals2::where('id', $request->id)->delete();
                if(Auth::user()->id == 1){
                    SelectedIntumescentSeals2::where('intumescentseals2_id', $request->id)->delete();
                }else{
                    SelectedIntumescentSeals2::where('intumescentseals2_id', $request->id)->where('selected_intumescentseals2_user_id', Auth::user()->id)->delete();
                }

            break;

            case 'SideScreen_Glass_Type':
                ScreenGlassType::where('id', $request->id)->delete();
                if(Auth::user()->id == 1){
                    SelectedScreenGlass::where('glass_id', $request->id)->delete();
                }else{
                    SelectedScreenGlass::where('glass_id', $request->id)->where('editBy', Auth::user()->id)->delete();
                }

            break;

            case 'SideScreen_Glazing_System':
                ScreenGlazingType::where('id', $request->id)->delete();
                if(Auth::user()->id == 1){
                    SelectedScreenGlazing::where('glazing_id', $request->id)->delete();
                }else{
                    SelectedScreenGlazing::where('glazing_id', $request->id)->where('editBy', Auth::user()->id)->delete();
                }

            break;
        }

        return 'success';
    }

    public function get_option_value(request $request)
    {
        if (Auth::user()->UserType == 2) {
            $UserId = ['1', Auth::user()->id];
        } else {
            $UserId = ['1'];
        }
        $pageId = $request->pageId;
        $data = Option::where(['configurableitems' => $pageId, 'OptionSlug' => $request->id])->wherein('editBy', $UserId)->get();
        $optVal = '';
        foreach ($data as $tt) {
            if (!empty($tt->UnderAttribute)) {
                $optValue = $tt->UnderAttribute . ' | ' . $tt->OptionValue;
            } else {
                $optValue = $tt->OptionValue;
            }
            $optVal .= '<option value="' . $tt->id . '">' . $optValue . '</option>';
        }
        return $optVal;
    }


    public function selectOption($pageId, $optionType)
    {

        $pageId = $pageId;
        $authdata = Auth::user();
        $ConfigurableItems = ConfigurableItems::get();
        if (Auth::user()->UserType == 2) {
            $UserId = ['1', $authdata->id];
        } else {
            $UserId = ['1'];
        }

        switch ($optionType) {
            case 'intumescentSealArrangement':
                if (Auth::user()->UserType == 2) {
                    $UserId = ['1', $authdata->id];
                } else {
                    $UserId = ['1'];
                }
                $allDataStmt = SettingIntumescentSeals2::where(['setting_intumescentseals2.configurableitems' => $pageId])
                    ->leftJoin('selected_intumescentseals2', function ($join) use ($authdata) {
                        $join->on('setting_intumescentseals2.id', '=', 'selected_intumescentseals2.intumescentseals2_id')
                            ->where('selected_intumescentseals2.selected_intumescentseals2_user_id', '=', $authdata->id);
                    })->wherein('setting_intumescentseals2.editBy', $UserId)
                    ->select('selected_intumescentseals2.*', 'setting_intumescentseals2.id as setting_intumescentseals2_id', 'setting_intumescentseals2.*', 'selected_intumescentseals2.id as selected_intumescentseals2_id')->orderBy('setting_intumescentseals2.firerating', 'ASC')->orderBy('setting_intumescentseals2.brand', 'ASC')->orderBy('setting_intumescentseals2.intumescentSeals', 'ASC')->orderBy('setting_intumescentseals2.firerating', 'ASC')->groupBy('brand', 'intumescentSeals', 'firerating');

                $allData = $allDataStmt->get();

                // count option tbl for GlassType
                $selectedData = $allDataStmt->where('selected_intumescentseals2.selected_intumescentseals2_user_id', "=", $authdata->id)->get();

                // count SelectedOption tbl for GlassType
                $countAll = $allData->count();
                // dd($countAll);
                $countSelected = $selectedData->count();

                $optionCheckall = '';
                if ($countAll == $countSelected) {
                    $optionCheckall = 'checked';
                }


                $tbl1 = '
                <li>
                    <div class="row">
                        <div class="col-sm-10"></div>
                        <div class="col-sm-2">
                            <div class="control-group">Check All
                                <label class="control control-checkbox">
                                    <input type="checkbox" class="checkall" value="' . $optionType . '" ' . $optionCheckall . '/>
                                    <div class="control_indicator"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </li>';

                $tbl1 .= '<div class="question">
                <header>

                    <h3>' . $optionType . '</h3>
                    <i class="fa fa-chevron-down"></i>
                </header>

                <main style="display: block;">
                    <ul class="accordian_list">';

                $tbl1 .= '<li>
                            <div class="row">
                                <div class="col-md-3 font-weight-bolder">
                                    NAME
                                </div>
                                <div class="col-md-5  text-center font-weight-bolder">FIRE RATING</div>
                                <div class="col-md-4  text-center font-weight-bolder" style="
                                margin-left: -126px;
                            ">PRICE PER L/M</div></div></li>';

                foreach ($allData as $row) {

                    // dd($row);

                    // $selectedOption = SelectedIntumescentSeals2::where(['selected_configurableitems'=>$pageId,'intumescentseals2_id'=>$row->id,'selected_intumescentseals2_user_id'=>$authdata->id])->count();
                    // if($selectedOption > 0){
                    if ($row->selected_intumescentseals2_id) {
                        $select = 'checked';
                    } else {
                        $select = '';
                    }

                    $tbl1 .= '
                        <li>
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <label><b><i class="fa fa-check-circle" aria-hidden="true"></i>' . $row->brand . '-' . $row->intumescentSeals . '</b></label>
                                </div>

                                <div class="col-md-3 col-sm-3 text-center">' . $row->firerating . '</div>
                                <div class="col-md-3 col-sm-3 text-center">';

                    if ($row->selected_cost !== null) {

                        $tbl1 .= '
                                        <div class="input-group">
                                            <input type="text" onkeyup="$(this).next().find(\'button\').attr(\'SelectedOptionCost\', this.value)" value="' . $row->selected_cost . '" class="form-control price_update" data-optionId="' . $row->id . '" data-optionName="intumescentSealArrangement" id="' . $row->selected_intumescentseals2_id . '" >

                                            <div class="input-group-append">
                                                <button hidden class="btn btn-success updateSelectedOptionCost prices" OptionType="' . $optionType . '" SelectedOptionCost="' . $row->selected_cost . '" SelectedOptionId="' . $row->selected_intumescentseals2_id . '" brand="' . $row->brand . '" intumescentSeals="' . $row->intumescentSeals . '" id="' . $row->selected_intumescentseals2_id . '">Update</button>
                                            </div>
                                        </div>

                                    ';
                    }
                    $tbl1 .= '</div><div class="col-md-2 col-sm-12">
                                    <div class="control-group">
                                        <label class="control control-checkbox">
                                            <input type="checkbox" class="' . $optionType . '"  value="' . $row->setting_intumescentseals2_id . '" ' . $select . '/>
                                            <div class="control_indicator"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </li>
                        ';
                }


                $tbl1 .= '</ul>
                    <button type="button" class="btn btn-success accordian_update_button" onclick="updateMe(\'' . $optionType . '\',' . $pageId . ')">Update</button>
                </main>
            </div>';
            break;

            case 'lippingSpecies':
                if (Auth::user()->UserType == 2) {
                    $UserId = ['1', $authdata->id];
                } else {
                    $UserId = ['1'];
                }
                $allDataStmt = GetOptions(['lipping_species.Status' => 1], "leftJoin", "lippingSpecies", "query");
                // dd($allDataStmt->get()->toArray());
                $allData = $allDataStmt->wherein('lipping_species.editBy', $UserId)->get();
                $optionCheckall = "";

                $tbl1 = '';
                $tbl1 .= '<div class="row">
                        <div class="col-sm-10"></div>
                        <div class="col-sm-2">
                            <div class="control-group d-flex">Check All
                                <label class="control control-checkbox">
                                    <input type="checkbox" class="checkall" value="' . $optionType . '" />
                                    <div class="control_indicator"></div>
                                </label>
                            </div>
                        </div>
                    </div>';
                foreach ($allData as $row) {

                    if (!empty($row->lipping_species_items->toArray())) {
                        foreach ($row->lipping_species_items as $row_item) {

                            if (@$row_item->selected_lipping_species_items[0]->id) {
                                $select = 'checked';
                            } else {
                                $select = '';
                            }
                        }
                        $tbl1 .= '<div class="question">

                            <header>

                                <h3>' . $row->SpeciesName . ' (' . $row->MinValue . '-' . $row->MaxValues . ')</h3>

                                <i class="fa fa-chevron-down"></i>
                            </header>

                            <main><div class="row">
                                <div class="col-sm-10"></div>
                                <div class="col-sm-2">
                                    <div class="control-group d-flex">Check All
                                        <label class="control control-checkbox">
                                            <input type="checkbox" class="checkall" value="' . $optionType . $row->id . '" ' . $optionCheckall . " " . $select . '/>
                                            <div class="control_indicator"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <ul class="accordian_list">';
                    } else {
                        $tbl1 .= '<div class="question">
                            <header>

                                <h3>' . $row->SpeciesName . ' (' . $row->MinValue . '-' . $row->MaxValues . ')</h3>

                            </header>

                            <main>
                                <ul class="accordian_list">';
                    }

                    $tbl1 .= '<li>
                    <div class="row">
                        <div class="col-md-3 col-sm-12 font-weight-bolder">
                            SPECIES NAME
                        </div>
                        <div class="col-md-1 col-sm-2 text-center font-weight-bolder">INCH</div>
                        <div class="col-md-2 col-sm-2 text-center font-weight-bolder">MM</div>
                        <div class="col-md-2 col-sm-2 text-center font-weight-bolder">Status</div>
                        <div class="col-md-2 col-sm-2 text-center font-weight-bolder">PRICE PER M3</div></div></li>';
                    $tbl1 .= '
                            ';
                    foreach ($row->lipping_species_items as $row_item) {

                        if (@$row_item->selected_lipping_species_items[0]->id) {
                            $select = 'checked';
                        } else {
                            $select = '';
                        }
                        if ($row_item->thickness <= 4) {


                            $tbl1 .= '<li>
                                        <div class="row">
                                            <div class="col-md-3 col-sm-12">
                                                <label><b><i class="fa fa-check-circle" aria-hidden="true"></i>' . $row->SpeciesName . '</b></label>
                                            </div>

                                            <div class="col-md-1 col-sm-2 text-center"> ' . $row_item->thickness . '</div>
                                            <div class="col-md-2 col-sm-2 text-center"> ' . $row_item->thickness * 25.4 . '</div>
                                            <div class="col-md-2 col-sm-2 text-center"> ' . (($row_item->status) ? "Active" : "Inactive") . '</div>
                                            <div class="col-md-2 col-sm-2 text-center">';


                            if (@$row_item->selected_lipping_species_items[0]->id) {

                                $tbl1 .= '
                                                                <div class="input-group">
                                                                    <input type="text" onkeyup="$(this).next().find(\'button\').attr(\'SelectedOptionCost\', this.value)" value="' . $row_item->selected_lipping_species_items[0]->selected_price . '" class="form-control price_update">

                                                                    <div class="input-group-append">
                                                                        <button hidden class="btn btn-success updateSelectedOptionCost prices" OptionType="' . $optionType . '" SelectedOptionCost="' . $row_item->selected_lipping_species_items[0]->selected_price . '" SelectedOptionId="' . $row_item->selected_lipping_species_items[0]->id . '">Update</button>
                                                                    </div>
                                                                </div>

                                                            ';
                            }



                            $tbl1 .= '</div><div class="col-md-2 col-sm-12">
                                                <div class="control-group">
                                                <label class="control control-checkbox">
                                                    <input type="checkbox" class="' . $optionType . ' ' . $optionType . $row->id . '" value="' . $row_item->id . '" ' . $select . '/>
                                                    <div class="control_indicator"></div>
                                                </label>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    ';
                        }
                    }

                    $tbl1 .= '</ul>
                        <button type="button" class="btn btn-success accordian_update_button" onclick="updateMe(\'' . $optionType . '\',' . $pageId . ')">Update</button>

                            </main>
                        </div>';
                }
                $tbl1 .= '<button type="button" class="btn btn-success accordian_update_button" onclick="updateMe(\'' . $optionType . '\',' . $pageId . ')">Update</button>';

                break;

            case 'door_leaf_facing_value':
                if (Auth::user()->UserType == 2) {
                    $UserId = ['1', $authdata->id];
                } else {
                    $UserId = ['1'];
                }
                $aa_value = Option::where(['options.configurableitems' => $pageId, 'options.OptionSlug' => $optionType])->wherein('options.editBy', $UserId)
                    ->select('options.UnderAttribute')->orderBy('options.UnderAttribute', 'ASC')->groupBy('options.UnderAttribute')->get();

                $aa = Option::where(['options.configurableitems' => $pageId, 'options.OptionSlug' => $optionType ,'options.is_deleted' => 0])->wherein('options.editBy', $UserId)
                    ->leftJoin('selected_option', function ($join) use ($authdata) {
                        $join->on('options.id', '=', 'selected_option.optionId')
                            ->where('selected_option.SelectedUserId', '=', $authdata->id);
                    })
                    ->select('options.id', 'options.OptionName', 'options.OptionSlug', 'options.OptionValue', 'options.UnderAttribute', 'options.editBy', 'options.OptionKey', 'selected_option.id as SelectedOptionId', 'selected_option.SelectedOptionCost')->orderBy('options.firerating', 'ASC')->orderBy('options.UnderAttribute', 'ASC')->orderBy('options.OptionValue', 'ASC')->get();



                $tbl1 = '';
                foreach ($aa_value as $data) {

                    $countOption = Option::where(['configurableitems' => $pageId, 'OptionSlug' => $optionType, 'UnderAttribute' => $data->UnderAttribute, 'options.is_deleted' => 0])->count();

                    $optionSelectedTag = "doorleaffacingvalue";

                    $countSelectedOption = SelectedOption::where(['configurableitems' => $pageId, 'SelectedUserId' => $authdata->id, 'tag' => $optionSelectedTag, 'SelectedUnderAttribute' => $data->UnderAttribute])->count();
                    $optionCheckall = '';
                    if ($countOption == $countSelectedOption) {
                        $optionCheckall = 'checked';
                    }

                    $tbl1 .= '<div class="row">
                    <div class="col-sm-10"></div>
                    <div class="col-sm-2">
                        <div class="control-group">Check All
                            <label class="control control-checkbox">
                                <input type="checkbox" class="checkall ' . $data->UnderAttribute . '" value="' . $data->UnderAttribute . '" ' . $optionCheckall . '/>
                                <div class="control_indicator"></div>
                            </label>
                        </div>
                    </div>
                </div>
                    <div class="question">

                    <header>

                        <h3>' . $data->UnderAttribute . '</h3>
                        <i class="fa fa-chevron-down"></i>

                    </header>
                    <main>

                        <ul class="accordian_list">';

                    if ($data->UnderAttribute == 'PVC' || $data->UnderAttribute == 'Laminate') {
                        $tbl1 .= '<li>
                        <div class="row">
                            <div class="col-md-3 font-weight-bolder">
                                NAME
                            </div>
                            <div class="col-md-2 font-weight-bolder">VALUE</div>
                            <div class="col-md-3 font-weight-bolder" style="
                            margin-left: 68px;
                        ">PRICE PER SHEET</div><div class="col-md-2 col-sm-2 font-weight-bolder">ACTION</div><div class="col-md-2 col-sm-2 text-center font-weight-bolder"></div></div></li>';
                    }
                    if ($data->UnderAttribute == 'Veneer') {
                        $tbl1 .= '<li>
                        <div class="row">
                            <div class="col-md-3 font-weight-bolder">
                                NAME
                            </div>
                            <div class="col-md-2 font-weight-bolder">VALUE</div>
                            <div class="col-md-3  font-weight-bolder" style="
                            margin-left: 68px;
                        ">PRICE PER M2</div><div class="col-md-2 col-sm-2  font-weight-bolder">ACTION</div><div></div class="col-md-2 col-sm-2 text-center font-weight-bolder"></div></li>';
                    }

                    foreach ($aa as $row) {

                        $selectedOption = SelectedOption::where(['configurableitems' => $pageId, 'optionId' => $row->id, 'SelectedUserId' => $authdata->id])->count();
                        if ($selectedOption > 0) {
                            $select = 'checked';
                        } else {
                            $select = '';
                        }

                        if ($row->UnderAttribute == $data->UnderAttribute) {
                            $tbl1 .= '
                            <li>
                                <div class="row">
                                    <div class="col-md-3 col-sm-12">
                                        <label><b><i class="fa fa-check-circle" aria-hidden="true"></i>' . $row->UnderAttribute . '</b></label>
                                    </div>
                                    <div class="col-md-2 col-sm-12">' . $row->OptionValue . '</div>
                                    <div class="col-md-3 col-sm-12 text-center">';

                            if ($row->SelectedOptionCost !== null) {

                                $tbl1 .= '
                                            <div class="input-group">
                                                <input type="text" onkeyup="$(this).next().find(\'button\').attr(\'SelectedOptionCost\', this.value)" value="' . $row->SelectedOptionCost . '" class="form-control price_update" data-optionId="' . $row->id . '" id="' . $row->SelectedOptionId . '" data-optionName="option">

                                                <div class="input-group-append">
                                                    <button hidden class="btn btn-success updateSelectedOptionCost prices" OptionType="' . $optionType . '" SelectedOptionCost="' . $row->SelectedOptionCost . '" SelectedOptionId="' . $row->SelectedOptionId . '" id="' . $row->SelectedOptionId . '">Update</button>
                                                </div>
                                            </div>

                                        ';
                            }
                            $action = '';
                            if (($row->editBy != 1 || Auth::user()->UserType == 1)) {
                                $action = '<a href="'.route("options/add1",[$row->id, $optionType]).'" class="btn btn-success " style="color: #fff; font-size:13px"><i class="fa fa-edit text-white text-center"></i></a>
                                <button type="button" class="btn btn-danger" style="color: #fff; font-size:13px" onclick="deletefunction('.$row->id.')">
                                    <i class="fa fa-trash text-white text-center"></i>
                                </button>';
                            }
                            $tbl1 .= '</div><div class="col-md-2 col-sm-12 text-center">
                            ' . $action . '
                            </div><div class="col-md-2 col-sm-12">
                                        <div class="control-group">
                                            <label class="control control-checkbox">
                                                <input type="checkbox" class="' . $optionType . ' ' . $data->UnderAttribute . '" value="' . $row->id . '" ' . $select . '/>
                                                <div class="control_indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            ';
                        }
                    }

                    $tbl1 .= '</ul>
                        <button type="button" class="btn btn-success accordian_update_button" onclick="updateMe(\'' . $optionType . '\',' . $pageId . ')">Update</button>
                    </main>
                    </div>';


                    $electedOption = Option::where(['configurableitems' => $pageId, 'OptionSlug' => $optionType])
                        ->select('OptionName', 'OptionSlug', 'OptionValue', 'UnderAttribute', 'OptionKey')
                        ->get();

                    if (!empty($electedOption) && count((array)$electedOption) > 0) {
                        foreach ($electedOption as $row) {
                            $selectedOption = SelectedOption::where([['SelectedOptionKey', $row->OptionKey], ['SelectedUserId', $authdata->id]])->first();
                            if (!empty($selectedOption) &&  count((array)$selectedOption) > 0) {
                                $row->selected = 1;
                            } else {
                                $row->selected = 0;
                            }
                        }
                    }
                }

                break;
            case 'color_list':
                if (Auth::user()->UserType == 2) {
                    $UserId = ['1', $authdata->id];
                } else {
                    $UserId = ['1'];
                }
                $aa_value = Color::wherein('editBy',$UserId)->groupBy('DoorLeafFacing')->get();

                $aa = Color::leftJoin('selected_color', function ($join) use ($authdata) {
                        $join->on('color.id', '=', 'selected_color.SelectedColorId')
                            ->where('selected_color.SelectedUserId', '=', $authdata->id);
                    })->wherein('color.editBy', $UserId)

                    ->select('color.id','color.ColorName','color.Hex','color.editBy','selected_color.id as colorId','selected_color.SelectedPrice','color.DoorLeafFacing')->get();

                    // echo '<pre>';
                    // print_r($aa);die;
                $tbl1 = '';
                foreach ($aa_value as $data) {


                    $countOption = Color::wherein('editBy',$UserId)->where(['DoorLeafFacing' => $data->DoorLeafFacing])->count();

                    $optionSelectedTag = "color_list";

                    $countSelectedOption = SelectedColor::where(['SelectedUserId' => $authdata->id , 'DoorLeafFacing' => $data->DoorLeafFacing])->count();
                    $optionCheckall = '';
                    if ($countOption == $countSelectedOption) {
                        $optionCheckall = 'checked';
                    }

                    $tbl1 .= '<div class="row">
                    <div class="col-sm-10"></div>
                    <div class="col-sm-2">
                        <div class="control-group">Check All
                            <label class="control control-checkbox">
                                <input type="checkbox" class="checkall ' . $data->DoorLeafFacing . '" value="' . $data->DoorLeafFacing . '" ' . $optionCheckall . '/>
                                <div class="control_indicator"></div>
                            </label>
                        </div>
                    </div>
                </div>
                    <div class="question">
                    <header>

                        <h3>' . $data->DoorLeafFacing . '</h3>
                        <i class="fa fa-chevron-down"></i>

                    </header>
                    <main>

                        <ul class="accordian_list">';

                        $tbl1 .= '<li>
                                <div class="row">
                                    <div class="col-md-3 font-weight-bolder">Color Name</div>
                                    <div class="col-md-2 font-weight-bolder">Color</div>
                                    <div class="col-md-2 font-weight-bolder">Code</div>
                                    <div class="col-md-2 font-weight-bolder" style="margin-left: 68px;">PRICE</div>
                                    <div class="col-md-2 col-sm-2 font-weight-bolder">ACTION</div>
                                    <div class="col-md-1 col-sm-2 text-center font-weight-bolder"></div>
                                </div></li>';


                    foreach ($aa as $row) {

                        $selectedOption = SelectedColor::where(['SelectedColorId'=>$row->id,'SelectedUserId'=>$authdata->id])->count();
                        if ($selectedOption > 0) {
                            $select = 'checked';
                        } else {
                            $select = '';
                        }

                        if ($row->DoorLeafFacing == $data->DoorLeafFacing) {
                            $tbl1 .= '
                            <li>
                                <div class="row">
                                    <div class="col-md-3 col-sm-12">
                                        <label><b><i class="fa fa-check-circle" aria-hidden="true"></i>' .$row->ColorName.' </b></label>
                                    </div>
                                    <div class="col-md-2 col-sm-12"><span class="color_box" style="background:'.$row->Hex.'"></span></div>
                                    <div class="col-md-2 col-sm-12">'.$row->Hex.'</div>
                                    <div class="col-md-2 col-sm-12 text-center">';

                            if ($row->SelectedPrice !== null) {

                                $tbl1 .= '<div class="input-group">
                                                <input type="text" onkeyup="$(this).next().find(\'button\').attr(\'SelectedOptionCost\', this.value)" value="' . $row->SelectedPrice . '" class="form-control price_update" data-optionId="' . $row->id . '" id="' . $row->colorId . '" data-optionName="option">

                                                <div class="input-group-append">
                                                    <button hidden class="btn btn-success updateSelectedOptionCost prices" OptionType="' . $optionType . '" SelectedOptionCost="' . $row->SelectedPrice . '" SelectedOptionId="' . $row->colorId . '" id="' . $row->colorId . '">Update</button>
                                                </div>
                                            </div>';
                            }
                            $action = '';

                            if (($row->editBy != 1 || Auth::user()->UserType == 1)) {
                                $edit =
                                '<form action="'.route('editcolor').'" method="post">
                                            '.csrf_field().'
                                            <input type="hidden" value="'.$row->configurableitems.'" name="pageId">

                                            <button type="submit" value="'.$row->id.'" name="upd" class="btn btn-success" ><i class="fa fa-edit text-white text-center"></i></button>
                                        </form>';
                                $action = $edit.'<button type="button" class="btn btn-danger" style="color: #fff; font-size:13px" onclick="deletecolor('.$row->id.')">
                                    <i class="fa fa-trash text-white text-center"></i>
                                </button>';
                            }
                            $tbl1 .= '</div><div class="col-md-2 col-sm-12 text-center d-flex">
                            ' . $action . '
                            </div><div class="col-md-1 col-sm-12">
                                        <div class="control-group">
                                            <label class="control control-checkbox">
                                                <input type="checkbox" class="' . $optionType . ' ' . $data->DoorLeafFacing . '" value="' . $row->id . '" ' . $select . '/>
                                                <div class="control_indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            ';
                        }
                    }

                    $tbl1 .= '</ul>
                        <button type="button" class="btn btn-success accordian_update_button" onclick="updateMe(\'' . $optionType . '\',' . $pageId . ')">Update</button>
                    </main>
                    </div>';


                    // $electedOption = Option::where(['configurableitems' => $pageId, 'OptionSlug' => $optionType])
                    //     ->select('OptionName', 'OptionSlug', 'OptionValue', 'UnderAttribute', 'OptionKey')
                    //     ->get();

                    // if (!empty($electedOption) && count((array)$electedOption) > 0) {
                    //     foreach ($electedOption as $row) {
                    //         $selectedOption = SelectedOption::where([['SelectedOptionKey', $row->OptionKey], ['SelectedUserId', $authdata->id]])->first();
                    //         if (!empty($selectedOption) &&  count((array)$selectedOption) > 0) {
                    //             $row->selected = 1;
                    //         } else {
                    //             $row->selected = 0;
                    //         }
                    //     }
                    // }
                }

                break;

            case 'Accoustics':
                $aa_value = Option::where(['options.configurableitems' => $pageId, 'options.OptionSlug' => $optionType])->wherein('options.editBy', $UserId)
                    ->select('options.UnderAttribute')->orderBy('options.UnderAttribute', 'ASC')->groupBy('options.UnderAttribute')->get();

                $aa = Option::where(['options.configurableitems' => $pageId, 'options.OptionSlug' => $optionType,'options.is_deleted' => 0])->wherein('options.editBy', $UserId)
                    ->leftJoin('selected_option', function ($join) use ($authdata) {
                        $join->on('options.id', '=', 'selected_option.optionId')
                            ->where('selected_option.SelectedUserId', '=', $authdata->id);
                    })
                    ->select('options.id', 'options.OptionName', 'options.OptionSlug', 'options.OptionValue', 'options.UnderAttribute', 'options.OptionKey', 'selected_option.id as SelectedOptionId', 'selected_option.SelectedOptionCost', 'options.editBy')->orderBy('options.firerating', 'ASC')->orderBy('options.UnderAttribute', 'ASC')->orderBy('options.OptionValue', 'ASC')->get();



                $tbl1 = '';
                foreach ($aa_value as $data) {

                    $countOption = Option::where(['configurableitems' => $pageId, 'OptionSlug' => $optionType, 'UnderAttribute' => $data->UnderAttribute, 'options.is_deleted' => 0])->count();

                    $optionSelectedTag = "Accoustics";

                    $countSelectedOption = SelectedOption::where(['configurableitems' => $pageId, 'SelectedUserId' => $authdata->id, 'tag' => $optionSelectedTag, 'SelectedUnderAttribute' => $data->UnderAttribute])->count();
                    $optionCheckall = '';
                    if ($countOption == $countSelectedOption) {
                        $optionCheckall = 'checked';
                    }

                    $tbl1 .= '
                        <div class="row">
                            <div class="col-sm-10"></div>
                            <div class="col-sm-2">
                                <div class="control-group">Check All
                                    <label class="control control-checkbox">
                                        <input type="checkbox" class="checkall ' . $data->UnderAttribute . '" value="' . $data->UnderAttribute . '" ' . $optionCheckall . '/>
                                        <div class="control_indicator"></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="question">
                    <header>

                        <h3>' . $data->UnderAttribute . '</h3>
                        <i class="fa fa-chevron-down"></i>

                    </header>
                    <main>
                        <ul class="accordian_list">';
                    if ($data->UnderAttribute == "Meeting_Stiles" || $data->UnderAttribute == "Perimeter_Seal_1" || $data->UnderAttribute == "Perimeter_Seal_2") {
                        $tbl1 .= '<li>
                            <div class="row">
                                <div class="col-md-3 col-sm-12 font-weight-bolder">
                                    NAME
                                </div>

                                <div class="col-md-2 col-sm-2 text-center font-weight-bolder" style="
                                margin-left: -40px;
                            ">VALUE</div>
                                <div class="col-md-2 col-sm-2 text-center font-weight-bolder" style="
                                margin-left: 114px;
                            ">PRICE PER L/M</div><div class="col-md-2 col-sm-2 text-center">Action</div><div class="col-md-2 col-sm-2 text-center "></div></div></li>';
                    }

                    if ($data->UnderAttribute == "Threshold_Seal_1" || $data->UnderAttribute == "Threshold_Seal_2") {
                        $tbl1 .= '<li>
                            <div class="row">
                                <div class="col-md-3 col-sm-12 font-weight-bolder">
                                    NAME
                                </div>

                                <div class="col-md-2 col-sm-2 text-center font-weight-bolder" style="
                                margin-left: -40px;
                            ">VALUE</div>
                                <div class="col-md-2 col-sm-2 text-center font-weight-bolder" style="
                                margin-left: 114px;
                            ">UNIT COST</div>
                            <div class="col-md-2 col-sm-2 text-center">Action</div>
                            <div class="col-md-2 col-sm-2 text-center"></div>
                            </div>
                            </li>';
                    }

                    foreach ($aa as $row) {

                        $selectedOption = SelectedOption::where(['configurableitems' => $pageId, 'optionId' => $row->id, 'SelectedUserId' => $authdata->id])->count();
                        if ($selectedOption > 0) {
                            $select = 'checked';
                        } else {
                            $select = '';
                        }

                        if ($row->UnderAttribute == $data->UnderAttribute) {
                            $tbl1 .= '
                            <li>
                                <div class="row">
                                    <div class="col-md-3 col-sm-12">
                                        <label><b><i class="fa fa-check-circle" aria-hidden="true"></i>' . $row->UnderAttribute . '</b></label>
                                    </div>
                                    <div class="col-md-2 col-sm-4">' . $row->OptionValue . '</div>
                                    <div class="col-md-2 col-sm-2">';

                            if ($row->SelectedOptionCost !== null) {

                                $tbl1 .= '
                                            <div class="input-group">
                                                <input type="text" onkeyup="$(this).next().find(\'button\').attr(\'SelectedOptionCost\', this.value)" value="' . $row->SelectedOptionCost . '" class="form-control price_update" data-optionId="' . $row->id . '" id="' . $row->SelectedOptionId . '" data-optionName="option">

                                                <div class="input-group-append">
                                                    <button hidden class="btn btn-success updateSelectedOptionCost prices" OptionType="' . $optionType . '" SelectedOptionCost="' . $row->SelectedOptionCost . '" SelectedOptionId="' . $row->SelectedOptionId . '" id="' . $row->SelectedOptionId . '">Update</button>
                                                </div>
                                            </div>

                                        ';
                            }
                            $action = '';
                            if (($row->editBy != 1 || Auth::user()->UserType == 1)) {
                                $action = '<a href="'.route("options/add1",[$row->id, $optionType]).'" class="btn btn-success" style="color: #fff; font-size:13px"><i class="fa fa-edit text-white text-center"></i></a>
                                <button type="button" class="btn btn-danger" style="color: #fff; font-size:13px" onclick="deletefunction('.$row->id.')">
                                    <i class="fa fa-trash text-white text-center"></i>
                                </button>';
                            }
                            $tbl1 .= '</div><div class="col-md-2 col-sm-12">
                            ' . $action . '
                            </div><div class="col-md-2 col-sm-12">
                                        <div class="control-group">
                                            <label class="control control-checkbox">
                                                <input type="checkbox" class="' . $optionType . ' ' . $data->UnderAttribute . '" value="' . $row->id . '" ' . $select . '/>
                                                <div class="control_indicator"></div>
                                            </label>
                                        </div>
                                    </div>
                            </li>
                            ';
                        }
                    }

                    $tbl1 .= '</ul>
                        <button type="button" class="btn btn-success accordian_update_button" onclick="updateMe(\'' . $optionType . '\',' . $pageId . ')">Update</button>
                    </main>
                    </div>';


                    $electedOption = Option::where(['configurableitems' => $pageId, 'OptionSlug' => $optionType])
                        ->select('OptionName', 'OptionSlug', 'OptionValue', 'UnderAttribute', 'OptionKey')
                        ->get();

                    if (!empty($electedOption) && count((array)$electedOption) > 0) {
                        foreach ($electedOption as $row) {
                            $selectedOption = SelectedOption::where([['SelectedOptionKey', $row->OptionKey], ['SelectedUserId', $authdata->id]])->first();
                            if (!empty($selectedOption) &&  count((array)$selectedOption) > 0) {
                                $row->selected = 1;
                            } else {
                                $row->selected = 0;
                            }
                        }
                    }
                }

                break;

            case 'door_leaf_finish':
                $aa_value = Option::where(['options.configurableitems' => $pageId, 'options.OptionSlug' => $optionType])->wherein('options.editBy', $UserId)
                    ->select('options.UnderAttribute')->orderBy('options.UnderAttribute', 'ASC')->groupBy('options.UnderAttribute')->get();

                $aa = Option::where(['options.configurableitems' => $pageId, 'options.OptionSlug' => $optionType,'options.is_deleted' => 0])->wherein('options.editBy', $UserId)
                    ->leftJoin('selected_option', function ($join) use ($authdata) {
                        $join->on('options.id', '=', 'selected_option.optionId')
                            ->where('selected_option.SelectedUserId', '=', $authdata->id);
                    })
                    ->select('options.id', 'options.OptionName', 'options.OptionSlug', 'options.OptionValue', 'options.UnderAttribute', 'options.OptionKey', 'selected_option.id as SelectedOptionId', 'selected_option.SelectedOptionCost')->orderBy('options.firerating', 'ASC')->orderBy('options.UnderAttribute', 'ASC')->orderBy('options.OptionValue', 'ASC')->get();



                $tbl1 = '';
                foreach ($aa_value as $data) {
                    $i = 0;
                    if($data->UnderAttribute == 'Kraft_Paper' && $i == 0){
                        $i++;
                        $aa_doorLeafFace = Option::where(['options.configurableitems' => $pageId, 'options.OptionSlug' => 'Door_Leaf_Facing' ,'options.OptionKey' => 'Kraft_Paper' ,'options.is_deleted' => 0])
                                ->leftJoin('selected_option', function ($join) use ($authdata) {
                                    $join->on('options.id', '=', 'selected_option.optionId')
                                        ->where('selected_option.SelectedUserId', '=', $authdata->id);
                                })->wherein('options.editBy', $UserId)
                                ->select('options.id', 'options.OptionName', 'options.OptionSlug', 'options.OptionValue', 'options.UnderAttribute', 'options.editBy', 'options.OptionKey', 'selected_option.id as SelectedOptionId', 'selected_option.SelectedOptionCost')->orderBy('options.firerating', 'ASC')->orderBy('options.UnderAttribute', 'ASC')->orderBy('options.OptionValue', 'ASC')->first();

                        $selectedOption = SelectedOption::where(['configurableitems' => $pageId, 'optionId' => $aa_doorLeafFace->id, 'SelectedUserId' => $authdata->id])->count();
                        if ($selectedOption > 0) {
                            $selected = 'checked';
                        } else {
                            $selected = '';
                        }

                        $tbl1 .= '
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12">
                                            <label><b><i class="fa fa-check-circle" aria-hidden="true"></i>' . $aa_doorLeafFace->OptionValue . '</b></label>
                                        </div>
                                        <div class="col-md-4 col-sm-2">';

                                if ($aa_doorLeafFace->SelectedOptionCost !== null) {

                                $tbl1 .= '
                                                <div class="input-group">
                                                    <input type="text" onkeyup="$(this).next().find(\'button\').attr(\'SelectedOptionCost\', this.value)" value="' . $aa_doorLeafFace->SelectedOptionCost . '" class="form-control price_update" data-optionId="' . $aa_doorLeafFace->id . '" id="' . $aa_doorLeafFace->SelectedOptionId . '" data-optionName="option">

                                                    <div class="input-group-append">
                                                        <button hidden class="btn btn-success updateSelectedOptionCost prices" OptionType="Door_Leaf_Facing" SelectedOptionCost="' . $aa_doorLeafFace->SelectedOptionCost . '" SelectedOptionId="' . $aa_doorLeafFace->SelectedOptionId . '" id="' . $aa_doorLeafFace->SelectedOptionId . '">Update</button>
                                                    </div>
                                                </div>

                                            ';
                                }
                                $optionTypeFace = "Door_Leaf_Facing";
                                $tbl1 .= '</div><div class="col-md-2 col-sm-12">
                                            <div class="control-group">
                                                <label class="control control-checkbox">
                                                    <input type="checkbox" class="Door_Leaf_Facing" value="' . $aa_doorLeafFace->id . '" ' . $selected . '/>
                                                    <div class="control_indicator"></div>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-12"><button type="button" class="btn btn-success" onclick="updateMe(\'' . $optionTypeFace . '\',' . $pageId . ')">Update</button></div>
                                    </div>
                                ';
                    }

                    $countOption = Option::where(['configurableitems' => $pageId, 'OptionSlug' => $optionType, 'UnderAttribute' => $data->UnderAttribute, 'options.is_deleted' => 0])->count();

                    $optionSelectedTag = "door_leaf_finish";

                    $countSelectedOption = SelectedOption::where(['configurableitems' => $pageId, 'SelectedUserId' => $authdata->id, 'tag' => $optionSelectedTag, 'SelectedUnderAttribute' => $data->UnderAttribute])->count();
                    $optionCheckall = '';
                    if ($countOption == $countSelectedOption) {
                        $optionCheckall = 'checked';
                    }

                    $tbl1 .= '<div class="question">
                    <div class="row">
                                <div class="col-sm-10"></div>
                                <div class="col-sm-2">
                                    <div class="control-group">Check All
                                        <label class="control control-checkbox">
                                            <input type="checkbox" class="checkall ' . $data->UnderAttribute . '" value="' . $data->UnderAttribute . '" ' . $optionCheckall . '/>
                                            <div class="control_indicator"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <header>

                            <h3>' . $data->UnderAttribute . '_finish</h3>
                            <i class="fa fa-chevron-down"></i>

                        </header>
                        <main>

                            <ul class="accordian_list">';


                    $tbl1 .= '<li>
                            <div class="row">
                                <div class="col-md-2 font-weight-bolder">
                                    NAME
                                </div>
                                <div class="col-md-3  text-center font-weight-bolder">VALUE</div>
                                <div class="col-md-6  text-center font-weight-bolder" style="
                                margin-left: 68px;
                            ">PRICE PER M2</div></div></li>';


                    foreach ($aa as $row) {

                        $selectedOption = SelectedOption::where(['configurableitems' => $pageId, 'optionId' => $row->id, 'SelectedUserId' => $authdata->id])->count();
                        if ($selectedOption > 0) {
                            $select = 'checked';
                        } else {
                            $select = '';
                        }

                        if ($row->UnderAttribute == $data->UnderAttribute) {



                            $tbl1 .= '
                                <li>
                                    <div class="row">
                                        <div class="col-md-3 col-sm-12">
                                            <label><b><i class="fa fa-check-circle" aria-hidden="true"></i>' . $row->UnderAttribute . '</b></label>
                                        </div>
                                        <div class="col-md-5 col-sm-4">' . $row->OptionValue . '</div>
                                        <div class="col-md-2 col-sm-2">';

                            if ($row->SelectedOptionCost !== null) {

                                $tbl1 .= '
                                                <div class="input-group">
                                                    <input type="text" onkeyup="$(this).next().find(\'button\').attr(\'SelectedOptionCost\', this.value)" value="' . $row->SelectedOptionCost . '" class="form-control price_update" data-optionId="' . $row->id . '" id="' . $row->SelectedOptionId . '" data-optionName="option">

                                                    <div class="input-group-append">
                                                        <button hidden class="btn btn-success updateSelectedOptionCost prices" OptionType="' . $optionType . '" SelectedOptionCost="' . $row->SelectedOptionCost . '" SelectedOptionId="' . $row->SelectedOptionId . '" id="' . $row->SelectedOptionId . '">Update</button>
                                                    </div>
                                                </div>

                                            ';
                            }
                            $tbl1 .= '</div><div class="col-md-2 col-sm-12">
                                            <div class="control-group">
                                                <label class="control control-checkbox">
                                                    <input type="checkbox" class="' . $optionType . ' ' . $data->UnderAttribute . '" value="' . $row->id . '" ' . $select . '/>
                                                    <div class="control_indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                ';
                        }
                    }

                    $tbl1 .= '</ul>
                            <button type="button" class="btn btn-success accordian_update_button" onclick="updateMe(\'' . $optionType . '\',' . $pageId . ')">Update</button>
                        </main>
                        </div>';


                    $electedOption = Option::where(['configurableitems' => $pageId, 'OptionSlug' => $optionType])
                        ->select('OptionName', 'OptionSlug', 'OptionValue', 'UnderAttribute', 'OptionKey')
                        ->get();

                    if (!empty($electedOption) && count((array)$electedOption) > 0) {
                        foreach ($electedOption as $row) {
                            $selectedOption = SelectedOption::where([['SelectedOptionKey', $row->OptionKey], ['SelectedUserId', $authdata->id]])->first();
                            if (!empty($selectedOption) &&  count((array)$selectedOption) > 0) {
                                $row->selected = 1;
                            } else {
                                $row->selected = 0;
                            }
                        }
                    }
                }

                break;

            case 'door_dimension':
                if (Auth::user()->UserType == 2) {
                    $UserId = ['1', $authdata->id];
                } else {
                    $UserId = ['1'];
                }
                $allDataStmt = DoorDimension::where(['door_dimension.configurableitems' => $pageId])
                    ->leftJoin('selected_doordimension', function ($join) use ($authdata) {
                        $join->on('door_dimension.id', '=', 'selected_doordimension.doordimension_id')
                            ->where('selected_doordimension.doordimension_user_id', '=', $authdata->id);
                    })
                    ->select('selected_doordimension.*', 'door_dimension.id as door_dimension_id', 'door_dimension.*', 'selected_doordimension.id as selected_doordimension_id','door_dimension.editBy as edit')->orderBy('door_dimension.fire_rating', 'ASC')->orderBy('door_dimension.mm_width', 'ASC')->orderBy('door_dimension.mm_height', 'ASC')->where('door_dimension.is_deleted', '0')->wherein('door_dimension.editBy', $UserId);

                $allData = $allDataStmt->get();

                // count option tbl for GlassType
                $selectedData = $allDataStmt->where('UserId', "=", Auth::user()->id)->get();

                // count SelectedOption tbl for GlassType
                $countAll = $allData->count();
                // dd($countAll);
                $countSelected = $selectedData->count();

                $optionCheckall = '';
                if ($countAll == $countSelected) {
                    $optionCheckall = 'checked';
                }


                $tbl1 = '


                    <div class="row">
                        <div class="col-sm-10"></div>
                        <div class="col-sm-2">
                            <div class="control-group">Check All
                                <label class="control control-checkbox">
                                    <input type="checkbox" class="checkall" value="' . $optionType . '" ' . $optionCheckall . '/>
                                    <div class="control_indicator"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                ';

                $tbl1 .= '<div class="question">
                <header>

                    <h3>' . $optionType . '</h3>
                    <i class="fa fa-chevron-down"></i>
                </header>

                <main style="display: block;">
                    <ul class="accordian_list">';

                if ($optionType == "door_dimension") {
                    $tbl1 .= '<li>
                        <div class="row">
                            <div class="col-md-1 col-sm-12 font-weight-bolder">

                            </div>

                            <div class="col-md-3 col-sm-2 text-center font-weight-bolder"
                        ">VALUE</div>
                        <div class="col-md-2 col-sm-2 text-center font-weight-bolder">FIRE RATING</div>
                            <div class="col-md-3 col-sm-2 text-center font-weight-bolder">UNIT COST</div><div class="col-md-2 col-sm-2 text-center font-weight-bolder">ACTION</div></div></li>';
                }

                foreach ($allData as $row) {

                    // dd($row);

                    // $selectedOption = SelectedIntumescentSeals2::where(['selected_configurableitems'=>$pageId,'intumescentseals2_id'=>$row->id,'selected_intumescentseals2_user_id'=>$authdata->id])->count();
                    // if($selectedOption > 0){
                    if ($row->doordimension_id) {
                        $select = 'checked';
                    } else {
                        $select = '';
                    }

                    $tbl1 .= '
                        <li>
                            <div class="row">
                                <div class="col-md-1 col-sm-12">
                                    <label><b><i class="fa fa-check-circle" aria-hidden="true"></i>' . $row->code . '</b></label>
                                </div>
                                <div class="col-md-3 col-sm-2 text-center">' . $row->mm_width . ' x ' . $row->mm_height . '</div>
                                <div class="col-md-2 col-sm-2 text-center">' . $row->fire_rating . '</div>
                                <div class="col-md-3 col-sm-2 text-center">';

                    if ($row->selected_cost !== null) {

                        $tbl1 .= '
                                        <div class="input-group">
                                            <input type="text" onkeyup="$(this).next().find(\'button\').attr(\'SelectedOptionCost\', this.value)" value="' . $row->selected_cost . '" class="form-control price_update">

                                            <div class="input-group-append">
                                                <button hidden class="btn btn-success updateSelectedOptionCost prices" OptionType="' . $optionType . '" SelectedOptionCost="' . $row->selected_cost . '" SelectedOptionId="' . $row->selected_doordimension_id . '">Update</button>
                                            </div>
                                        </div>

                                    ';
                    }
                    $action = '';
                    if (($row->edit != 1 || Auth::user()->UserType == 1)) {
                        $action = '<a href="' . route('DoorDimension/edit', $row->door_dimension_id) . '" class="btn btn-success"><i class="fa fa-pencil" style="color: #fff; font-size:13px"></i></a>
                        <button onClick="dimension_delete(' . $row->door_dimension_id . ')" class="btn btn-danger"><i class="fa fa-trash" style="color: #fff; font-size:13px"></i></button>';
                    }
                    $tbl1 .= '</div><div class="col-md-2 col-sm-12">
                                ' . $action . '
                                </div><div class="col-md-1 col-sm-12">
                                    <div class="control-group">
                                        <label class="control control-checkbox">
                                            <input type="checkbox" class="' . $optionType . '" value="' . $row->door_dimension_id . '" ' . $select . '/>
                                            <div class="control_indicator"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </li>
                        ';
                }


                $tbl1 .= '</ul>
                    <button type="button" class="btn btn-success accordian_update_button" onclick="updateMe(\'' . $optionType . '\',' . $pageId . ')">Update</button>
                </main>
            </div>';

                break;

            default:
                $aa = Option::where(['options.configurableitems' => $pageId, 'options.OptionSlug' => $optionType ,'options.is_deleted' => 0])
                    ->leftJoin('selected_option', function ($join) use ($authdata) {
                        $join->on('options.id', '=', 'selected_option.optionId')
                            ->where('selected_option.SelectedUserId', '=', $authdata->id);
                    })->wherein('options.editBy', $UserId)
                    ->select('options.id', 'options.OptionName', 'options.OptionSlug', 'options.OptionValue', 'options.UnderAttribute', 'options.editBy', 'options.OptionKey', 'selected_option.id as SelectedOptionId', 'selected_option.SelectedOptionCost')->orderBy('options.firerating', 'ASC')->orderBy('options.UnderAttribute', 'ASC')->orderBy('options.OptionValue', 'ASC')->get();

                // dd($aa);
                // Check and Check All option
                // count option tbl for GlassType
                $countOption = Option::where(['configurableitems' => $pageId, 'OptionSlug' => $optionType, 'options.is_deleted' => 0])->count();

                switch ($optionType) {
                    case 'leaf1_glass_type':
                        $optionSelectedTag = "glasstype";
                        break;

                    case 'leaf1_glass_thickness':
                        $optionSelectedTag = "glasstypethickness";
                        break;

                    case 'door_leaf_facing_value':
                        $optionSelectedTag = "doorleaffacingvalue";
                        break;

                    default:
                        $optionSelectedTag = $optionType;
                        break;
                }

                // count SelectedOption tbl for GlassType
                $countSelectedOption = SelectedOption::where(['configurableitems' => $pageId, 'SelectedUserId' => $authdata->id, 'tag' => $optionSelectedTag])->count();
                $optionCheckall = '';
                if ($countOption == $countSelectedOption) {
                    $optionCheckall = 'checked';
                }


                $tbl1 = '

                    <div class="row">
                        <div class="col-sm-10"></div>
                        <div class="col-sm-2">
                            <div class="control-group">Check All
                                <label class="control control-checkbox">
                                    <input type="checkbox" class="checkall" value="' . $optionType . '" ' . $optionCheckall . '/>
                                    <div class="control_indicator"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                ';

                $tbl1 .= '<div class="question">
                <header>

                    <h3>' . $optionType . '</h3>
                    <i class="fa fa-chevron-down"></i>

                </header>

                <main style="display: block;">
                    <ul class="accordian_list">';

                if ($optionType == 'leaf1_glass_type') {
                    $tbl1 .= '<li>
                        <div class="row">
                        <div class="col-md-2 col-sm-12 font-weight-bolder">
                            FIRE RATING
                        </div>
                        <div class="col-md-3 col-sm-12 font-weight-bolder">GLASS TYPE</div>
                        <div class="col-md-3 col-sm-12 font-weight-bolder">PRICE PER M2</div>
                        <div class="col-md-2 col-sm-12 font-weight-bolder">Action</div>
                        <div class="col-md-2 col-sm-12 text-center font-weight-bolder"></div>
                        </div></li>';
                }
                if ($optionType == 'Architrave_Type') {
                    $tbl1 .= '<li>
                        <div class="row">
                        <div class="col-md-6 col-sm-12 font-weight-bolder text-center">
                            NAME
                        </div>

                        <div class="col-md-6 col-sm-12 text-center font-weight-bolder">PRICE PER M3</div></div></li>';
                }
                if ($optionType == 'leaf1_glazing_systems') {
                    $tbl1 .= '<li>
                        <div class="row">
                        <div class="col-md-2 col-sm-12 font-weight-bolder">
                            FIRE RATING
                        </div>
                        <div class="col-md-3 col-sm-12 font-weight-bolder">
                            GLAZING NAME
                        </div>
                        <div class="col-md-3 col-sm-12 text-center font-weight-bolder">PRICE PER L/M</div>
                        <div class="col-md-2 col-sm-12 text-center font-weight-bolder">Action</div>
                        <div class="col-md-2 col-sm-12 text-center font-weight-bolder"></div>
                        </div></li>';
                }
                if ($optionType == 'Intumescent_Seal_Color') {
                    $tbl1 .= '<li>
                        <div class="row">
                        <div class="col-md-4 col-sm-12 font-weight-bolder">
                            NAME
                        </div>
                        <div class="col-md-4 col-sm-12 text-center font-weight-bolder">PRICE PER L/M</div>
                        <div class="col-md-2 col-sm-12 text-center font-weight-bolder">Action</div>
                        <div class="col-md-2 col-sm-12 text-center font-weight-bolder"></div>
                        </div></li>';
                }
                foreach ($aa as $row) {

                    $selectedOption = SelectedOption::where(['configurableitems' => $pageId, 'optionId' => $row->id, 'SelectedUserId' => $authdata->id])->count();
                    if ($selectedOption > 0) {
                        $select = 'checked';
                    } else {
                        $select = '';
                    }


                    if ($optionType == 'leaf1_glazing_systems') {
                        $tbl1 .= '
                        <li>
                            <div class="row">
                                <div class="col-md-2 col-sm-12">
                                    <label><b><i class="fa fa-check-circle" aria-hidden="true"></i>' . $row->UnderAttribute . '</b></label>
                                </div>
                                <div class="col-md-3 col-sm-4">' . $row->OptionValue . '</div>
                                <div class="col-md-3 col-sm-2">';

                    if ($row->SelectedOptionCost !== null) {

                        $tbl1 .= '
                                        <div class="input-group">
                                            <input type="text" onkeyup="$(this).next().find(\'button\').attr(\'SelectedOptionCost\', this.value)" value="' . $row->SelectedOptionCost . '" class="form-control price_update" data-optionId="' . $row->id . '" id="' . $row->SelectedOptionId . '" data-optionName="option">

                                            <div class="input-group-append">
                                                <button hidden class="btn btn-success updateSelectedOptionCost prices" OptionType="' . $optionType . '" SelectedOptionCost="' . $row->SelectedOptionCost . '" SelectedOptionId="' . $row->SelectedOptionId . '" id="' . $row->SelectedOptionId . '">Update</button>
                                            </div>
                                        </div>

                                    ';
                    }
                    $action = '';
                            if (($row->editBy != 1 || Auth::user()->UserType == 1)) {
                                $action = '<a href="'.route("options/add1",[$row->id, $optionType]).'" class="btn btn-success" style="color: #fff; font-size:13px"><i class="fa fa-edit text-white text-center"></i></a>
                                <button type="button" class="btn btn-danger" style="color: #fff; font-size:13px" onclick="deletefunction('.$row->id.')">
                                    <i class="fa fa-trash text-white text-center"></i>
                                </button>';
                            }
                    $tbl1 .= '</div><div class="col-md-2 col-sm-12">'.$action.'</div><div class="col-md-2 col-sm-12">
                                    <div class="control-group">
                                        <label class="control control-checkbox">
                                            <input type="checkbox" class="' . $optionType . '" value="' . $row->id . '" ' . $select . '/>
                                            <div class="control_indicator"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </li>
                        ';
                    }elseif ($optionType == 'Intumescent_Seal_Color') {
                        $tbl1 .= '
                        <li>
                            <div class="row">

                                <div class="col-md-4 col-sm-12"><label><b><i class="fa fa-check-circle" aria-hidden="true"></i>' . $row->UnderAttribute . '</b></label>' . $row->OptionValue . '</div>
                                <div class="col-md-4 col-sm-12">';

                    if ($row->SelectedOptionCost !== null) {

                        $tbl1 .= '
                                        <div class="input-group">
                                            <input type="text" onkeyup="$(this).next().find(\'button\').attr(\'SelectedOptionCost\', this.value)" value="' . $row->SelectedOptionCost . '" class="form-control price_update" data-optionId="' . $row->id . '" id="' . $row->SelectedOptionId . '" data-optionName="option">

                                            <div class="input-group-append">
                                                <button hidden class="btn btn-success updateSelectedOptionCost prices" OptionType="' . $optionType . '" SelectedOptionCost="' . $row->SelectedOptionCost . '" SelectedOptionId="' . $row->SelectedOptionId . '" id="' . $row->SelectedOptionId . '">Update</button>
                                            </div>
                                        </div>

                                    ';
                    }
                    $action = '';
                            if (($row->editBy != 1 || Auth::user()->UserType == 1)) {
                                $action = '<a href="'.route("options/add1",[$row->id, $optionType]).'" class="btn btn-success" style="color: #fff; font-size:13px"><i class="fa fa-edit text-white text-center"></i></a>
                                <button type="button" class="btn btn-danger" style="color: #fff; font-size:13px" onclick="deletefunction('.$row->id.')">
                                    <i class="fa fa-trash text-white text-center"></i>
                                </button>';
                            }
                    $tbl1 .= '</div><div class="col-md-2 col-sm-12">'.$action.'</div><div class="col-md-2 col-sm-12">
                                    <div class="control-group">
                                        <label class="control control-checkbox">
                                            <input type="checkbox" class="' . $optionType . '" value="' . $row->id . '" ' . $select . '/>
                                            <div class="control_indicator"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </li>
                        ';
                    }elseif($optionType == 'leaf1_glass_type'){
                        $tbl1 .= '
                        <li>
                            <div class="row">
                                <div class="col-md-2 col-sm-12">
                                    <label><b><i class="fa fa-check-circle" aria-hidden="true"></i>' . $row->UnderAttribute . '</b></label>
                                </div>
                                <div class="col-md-3 col-sm-4">' . $row->OptionValue . '</div>
                                <div class="col-md-3 col-sm-2">';

                        if ($row->SelectedOptionCost !== null) {

                        $tbl1 .= '
                                        <div class="input-group">
                                            <input type="text" onkeyup="$(this).next().find(\'button\').attr(\'SelectedOptionCost\', this.value)" value="' . $row->SelectedOptionCost . '" class="form-control price_update" data-optionId="' . $row->id . '" id="' . $row->SelectedOptionId . '" data-optionName="option">

                                            <div class="input-group-append">
                                                <button hidden class="btn btn-success updateSelectedOptionCost prices" OptionType="' . $optionType . '" SelectedOptionCost="' . $row->SelectedOptionCost . '" SelectedOptionId="' . $row->SelectedOptionId . '" id="' . $row->SelectedOptionId . '">Update</button>
                                            </div>
                                        </div>

                                    ';
                        }
                        $action = '';
                            if (($row->editBy != 1 || Auth::user()->UserType == 1)) {
                                $action = '<a href="'.route("options/add1",[$row->id, $optionType]).'" class="btn btn-success" style="color: #fff; font-size:13px"><i class="fa fa-edit text-white text-center"></i></a>
                                <button type="button" class="btn btn-danger" style="color: #fff; font-size:13px" onclick="deletefunction('.$row->id.')">
                                    <i class="fa fa-trash text-white text-center"></i>
                                </button>';
                            }
                        $tbl1 .= '</div><div class="col-md-2 col-sm-12">'.$action.'</div><div class="col-md-2 col-sm-12">
                                    <div class="control-group">
                                        <label class="control control-checkbox">
                                            <input type="checkbox" class="' . $optionType . '" value="' . $row->id . '" ' . $select . '/>
                                            <div class="control_indicator"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </li>
                        ';
                    }
                    else{
                        $tbl1 .= '
                        <li>
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <label><b><i class="fa fa-check-circle" aria-hidden="true"></i>' . $row->UnderAttribute . '</b></label>
                                </div>
                                <div class="col-md-5 col-sm-4">' . $row->OptionValue . '</div>
                                <div class="col-md-2 col-sm-2">';

                        if ($row->SelectedOptionCost !== null) {

                        $tbl1 .= '
                                        <div class="input-group">
                                            <input type="text" onkeyup="$(this).next().find(\'button\').attr(\'SelectedOptionCost\', this.value)" value="' . $row->SelectedOptionCost . '" class="form-control price_update" data-optionId="' . $row->id . '" id="' . $row->SelectedOptionId . '" data-optionName="option">

                                            <div class="input-group-append">
                                                <button hidden class="btn btn-success updateSelectedOptionCost prices" OptionType="' . $optionType . '" SelectedOptionCost="' . $row->SelectedOptionCost . '" SelectedOptionId="' . $row->SelectedOptionId . '" id="' . $row->SelectedOptionId . '">Update</button>
                                            </div>
                                        </div>

                                    ';
                        }
                        $tbl1 .= '</div><div class="col-md-2 col-sm-12">
                                    <div class="control-group">
                                        <label class="control control-checkbox">
                                            <input type="checkbox" class="' . $optionType . '" value="' . $row->id . '" ' . $select . '/>
                                            <div class="control_indicator"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </li>
                        ';
                    }

                }

                $tbl1 .= '</ul>
                    <button type="button" class="btn btn-success accordian_update_button" onclick="updateMe(\'' . $optionType . '\',' . $pageId . ')">Update</button>
                </main>
            </div>';


                $electedOption = Option::where(['configurableitems' => $pageId, 'OptionSlug' => $optionType])
                    ->select('OptionName', 'OptionSlug', 'OptionValue', 'UnderAttribute', 'OptionKey')
                    ->get();

                if (!empty($electedOption) && count((array)$electedOption) > 0) {
                    foreach ($electedOption as $row) {
                        $selectedOption = SelectedOption::where([['SelectedOptionKey', $row->OptionKey], ['SelectedUserId', $authdata->id]])->first();
                        if (!empty($selectedOption) &&  count((array)$selectedOption) > 0) {
                            $row->selected = 1;
                        } else {
                            $row->selected = 0;
                        }
                    }
                }

                break;
        }

        //  dd($optionType);
        // return view('option/ChooseOptions',compact('electedOption','ConfigurableItems','pageId','tbl1'));
        return view('option/ChooseOptions', compact('ConfigurableItems', 'pageId', 'tbl1', 'optionType'));
    }

    public function updateSelectOption(request $request)
    {
        // dd($request->all());
        $pageId = $request->pageId;
        if($request->className == 'color_list' || $request->className == 'door_dimension'){
            $keys =  explode(',',$request->selectedValue);
            }else{
                $keys = $request->selectedValue;
            }
        $className = $request->className;
        $UserId = Auth::user()->id;
        if (!empty($keys) && count($keys)) {

            $optionKey = $className;
            switch ($className) {
                case 'leaf1_glass_type':
                    $className = "leaf1_glass_type";
                    break;

                case 'leaf1_glass_thickness':
                    $className = "glasstypethickness";
                    break;

                case 'door_dimension':
                    $className = "doordimension";
                    break;

                default:
                    $className = $className;
                    break;
            }

            switch ($className) {
                case 'doordimension':
                    SelectedDoordimension::where(['doordimension_user_id' => $UserId])->whereNotIn('selected_configurableitems', [1,2,7,8])->whereNotIn('doordimension_id', $keys)->delete();
                    foreach ($keys as $key) {
                        $electedOption = array();
                        $electedOption = DoorDimension::Where('id', $key)->select('*')->first();
                        $selectedOptionExist = SelectedDoordimension::where(['doordimension_user_id' => $UserId, 'doordimension_id' => $key])->first();
                        // dd($electedOption);
                        if (!empty($electedOption) && count((array) $electedOption) > 0 && empty($selectedOptionExist)) {
                            $selectedOption = new SelectedDoordimension();
                            // $selectedOption->selected_configurableitems = $pageId;
                            $selectedOption->doordimension_id = $electedOption->id;
                            $selectedOption->selected_configurableitems = $electedOption->configurableitems;
                            $selectedOption->doordimension_user_id = Auth::user()->id;
                            $selectedOption->selected_firerating = $electedOption->fire_rating;
                            $selectedOption->selected_code = $electedOption->code;
                            $selectedOption->selected_mm_height = $electedOption->mm_height;
                            $selectedOption->selected_mm_width = $electedOption->mm_width;
                            $selectedOption->selected_sellingprice = $electedOption->selling_price;
                            $selectedOption->selected_cost = ($electedOption->cost_price) ?? 0;
                            $selectedOption->save();
                            // dd($selectedOption);
                        }
                    }

                    break;


                case 'intumescentSealArrangement':

                    // $delete_id = array();
                    SelectedIntumescentSeals2::where(['selected_intumescentseals2_user_id' => $UserId])->whereNotIn('selected_configurableitems', [3,4,5,6])->whereNotIn('intumescentseals2_id', $keys)->delete();
                    // foreach ($keys as $key) {
                    //     $electedOption = array();
                    //     $electedOption = SettingIntumescentSeals2::Where('id', $key)->select('*')->first();

                        // $electedOptionids = SettingIntumescentSeals2::Where('intumescentSeals', $electedOption->intumescentSeals)->Where('brand', $electedOption->brand)->Where('firerating', $electedOption->firerating)->select('*')->get();

                    //     if (!empty($electedOptionids) && count((array) $electedOptionids) > 0) {
                    //         foreach ($electedOptionids as $value) {
                    //             $delete_id[] = $value->id;
                    //         }
                    //     }
                    // }
                    // SelectedIntumescentSeals2::where(['selected_configurableitems' => $pageId, 'selected_intumescentseals2_user_id' => $UserId])->whereNotIn('intumescentseals2_id', $delete_id)->delete();

                    foreach ($keys as $key) {
                        $electedOption = array();
                        $electedOption = SettingIntumescentSeals2::Where('id', $key)->select('*')->first();

                        // $electedOptionids = SettingIntumescentSeals2::Where('intumescentSeals', $electedOption->intumescentSeals)->Where('brand', $electedOption->brand)->Where('firerating', $electedOption->firerating)->select('*')->get();

                        $selectedOptionExist = SelectedIntumescentSeals2::where(['selected_intumescentseals2_user_id' => $UserId, 'intumescentseals2_id' => $key])->first();
                        if (!empty($electedOption) && count((array) $electedOption) > 0 && empty($selectedOptionExist)) {
                            // foreach ($electedOptionids as $value) {
                                $selectedOption = new SelectedIntumescentSeals2();
                                $selectedOption->selected_configurableitems = $electedOption->configurableitems;
                                $selectedOption->intumescentseals2_id = $electedOption->id;
                                $selectedOption->selected_intumescentseals2_user_id = Auth::user()->id;
                                $selectedOption->selected_configuration = $electedOption->configuration;
                                $selectedOption->selected_doorname = $electedOption->doorname;
                                $selectedOption->selected_firerating = $electedOption->firerating;
                                $selectedOption->selected_tag = $electedOption->tag;
                                $selectedOption->selected_intumescentSeals = $electedOption->intumescentSeals;
                                $selectedOption->selected_brand = $electedOption->brand;
                                $selectedOption->selected_firetested = $electedOption->firetested;
                                $selectedOption->selected_Point1height = $electedOption->Point1height;
                                $selectedOption->selected_Point1width = $electedOption->Point1width;
                                $selectedOption->selected_Point2height = $electedOption->Point2height;
                                $selectedOption->selected_Point2width = $electedOption->Point2width;
                                $selectedOption->selected_cost = ($electedOption->cost) ?? 0;
                                $selectedOption->save();
                            // }
                        }
                    }

                    break;

                case 'intumescentSealArrangementCustome':
                    SelectedIntumescentSeals2::where(['selected_intumescentseals2_user_id' => $UserId])->whereNotIn('selected_configurableitems', [3,4,5,6])->whereNotIn('intumescentseals2_id', $keys)->delete();

                        foreach ($keys as $key) {
                            $electedOption = array();
                            $electedOption = SettingIntumescentSeals2::Where('id', $key)->select('*')->first();
                            $selectedOptionExist = SelectedIntumescentSeals2::where(['selected_intumescentseals2_user_id' => $UserId, 'intumescentseals2_id' => $key])->first();
                            if (!empty($electedOption) && count((array) $electedOption) > 0 && empty($selectedOptionExist)) {
                                // foreach ($electedOptionids as $value) {
                                    $selectedOption = new SelectedIntumescentSeals2();
                                    $selectedOption->selected_configurableitems = $electedOption->configurableitems;
                                    $selectedOption->intumescentseals2_id = $electedOption->id;
                                    $selectedOption->selected_intumescentseals2_user_id = Auth::user()->id;
                                    $selectedOption->selected_configuration = $electedOption->configuration;
                                    $selectedOption->selected_doorname = $electedOption->doorname;
                                    $selectedOption->selected_firerating = $electedOption->firerating;
                                    $selectedOption->selected_tag = $electedOption->tag;
                                    $selectedOption->selected_intumescentSeals = $electedOption->intumescentSeals;
                                    $selectedOption->selected_brand = $electedOption->brand;
                                    $selectedOption->selected_firetested = $electedOption->firetested;
                                    $selectedOption->selected_Point1height = $electedOption->Point1height;
                                    $selectedOption->selected_Point1width = $electedOption->Point1width;
                                    $selectedOption->selected_Point2height = $electedOption->Point2height;
                                    $selectedOption->selected_Point2width = $electedOption->Point2width;
                                    $selectedOption->selected_cost = ($electedOption->cost) ?? 0;
                                    $selectedOption->save();
                                // }
                            }
                        }

                break;

                case 'lippingSpecies':

                    SelectedLippingSpeciesItems::where(['selected_user_id' => $UserId])->whereNotIn('selected_lipping_species_items_id', $keys)->delete();

                    foreach ($keys as $key) {
                        $electedOption = array();
                        $electedOption = LippingSpeciesItems::Where('id', $key)->select('*')->first();
                        $selectedOptionExist = SelectedLippingSpeciesItems::where(['selected_user_id' => $UserId, 'selected_lipping_species_items_id' => $key])->first();
                        // dd($key);
                        if (!empty($electedOption) && count((array) $electedOption) > 0 && empty($selectedOptionExist)) {
                            $selectedOption = new SelectedLippingSpeciesItems();
                            $selectedOption->selected_lipping_species_id = $electedOption->lipping_species_id;
                            $selectedOption->selected_lipping_species_items_id = $electedOption->id;
                            $selectedOption->selected_user_id = Auth::user()->id;
                            $selectedOption->selected_thickness = $electedOption->thickness;
                            $selectedOption->selected_price = $electedOption->price;
                            $selectedOption->selected_status = $electedOption->status;
                            $selectedOption->save();
                            // dd($electedOption);
                        }
                    }

                    break;

                case 'leaf1_glass_type':
                    $customGlassTypeIds = GlassType::where(function ($query) {
                        $query->whereNotNull('VicaimaDoorCore')
                              ->orWhereNotNull('Seadec')
                              ->orWhereNotNull('Deanta');
                    })
                    ->pluck('id');
                  SelectedGlassType::where(['editBy' => $UserId])->whereIn('glass_id', $customGlassTypeIds)->whereNotIn('glass_id', $keys)->delete();

                    foreach ($keys as $key) {
                        $electedOption = array();
                        $electedOption = GlassType::Where('id', $key)->select('*')->first();
                        $selectedOptionExist = SelectedGlassType::where(['editBy' => $UserId, 'glass_id' => $key])->first();
                        // dd($key);
                        if (!empty($electedOption) && count((array) $electedOption) > 0 && empty($selectedOptionExist)) {
                            $selectedOption = new SelectedGlassType();
                            $selectedOption->glass_id = $electedOption->id;
                            $selectedOption->editBy = Auth::user()->id;
                            $selectedOption->save();
                            // dd($electedOption);
                        }
                    }

                    break;

                    case 'leaf1_glass_type_custome':
                        $customGlassTypeIds = GlassType::where(function ($query) {
                            $query->whereNotNull('Streboard')
                          ->orWhereNotNull('Halspan')
                          ->orWhereNotNull('Stredor')
                          ->orWhereNotNull('Flamebreak');
                        })
                        ->pluck('id');

                        SelectedGlassType::where(['editBy' => $UserId])->whereIn('glass_id', $customGlassTypeIds)->whereNotIn('glass_id', $keys)->delete();

                        foreach ($keys as $key) {
                            $electedOption = array();
                            $electedOption = GlassType::Where('id', $key)->select('*')->first();
                            $selectedOptionExist = SelectedGlassType::where(['editBy' => $UserId, 'glass_id' => $key])->first();
                            if (!empty($electedOption) && count((array) $electedOption) > 0 && empty($selectedOptionExist)) {
                                $selectedOption = new SelectedGlassType();
                                $selectedOption->glass_id = $electedOption->id;
                                $selectedOption->editBy = Auth::user()->id;
                                $selectedOption->save();
                            }
                        }

                        break;

                case 'leaf1_glazing_systems':
                    $customGlassTypeIds = GlazingSystem::where(function ($query) {
                        $query->whereNotNull('VicaimaDoorCore')
                        ->orWhereNotNull('Seadec')
                        ->orWhereNotNull('Deanta');
                    })
                    ->pluck('id');
                    SelectedGlazingSystem::where(['userId' => $UserId])->whereIn('glazingId', $customGlassTypeIds)->whereNotIn('glazingId', $keys)->delete();

                    foreach ($keys as $key) {
                        $electedOption = array();
                        $electedOption = GlazingSystem::Where('id', $key)->select('*')->first();
                        $selectedOptionExist = SelectedGlazingSystem::where(['userId' => $UserId, 'glazingId' => $key])->first();
                        // dd($key);
                        if (!empty($electedOption) && count((array) $electedOption) > 0 && empty($selectedOptionExist)) {
                            $selectedOption = new SelectedGlazingSystem();
                            $selectedOption->glazingId = $electedOption->id;
                            $selectedOption->userId = Auth::user()->id;
                            $selectedOption->save();
                            // dd($electedOption);
                        }
                    }

                    break;

                case 'leaf1_glazing_systems_custome':
                    $customGlassTypeIds = GlazingSystem::where(function ($query) {
                        $query->whereNotNull('Streboard')
                              ->orWhereNotNull('Halspan')
                              ->orWhereNotNull('Stredor')
                              ->orWhereNotNull('Flamebreak');
                    }) ->pluck('id');
                    SelectedGlazingSystem::where(['userId' => $UserId])->whereIn('glazingId', $customGlassTypeIds)->whereNotIn('glazingId', $keys)->delete();

                        foreach ($keys as $key) {
                            $electedOption = array();
                            $electedOption = GlazingSystem::Where('id', $key)->select('*')->first();
                            $selectedOptionExist = SelectedGlazingSystem::where(['userId' => $UserId, 'glazingId' => $key])->first();
                            // dd($key);
                            if (!empty($electedOption) && count((array) $electedOption) > 0 && empty($selectedOptionExist)) {
                                $selectedOption = new SelectedGlazingSystem();
                                $selectedOption->glazingId = $electedOption->id;
                                $selectedOption->userId = Auth::user()->id;
                                $selectedOption->save();
                                // dd($electedOption);
                            }
                        }

                break;

                case 'Intumescent_Seal_Color':

                    SelectedIntumescentSealColor::where(['userId' => $UserId])->whereNotIn('intumescentSealColorId', $keys)->delete();

                    foreach ($keys as $key) {
                        $electedOption = array();
                        $electedOption = IntumescentSealColor::Where('id', $key)->select('*')->first();
                        $selectedOptionExist = SelectedIntumescentSealColor::where(['userId' => $UserId, 'intumescentSealColorId' => $key])->first();
                        // dd($key);
                        if (!empty($electedOption) && count((array) $electedOption) > 0 && empty($selectedOptionExist)) {
                            $selectedOption = new SelectedIntumescentSealColor();
                            $selectedOption->intumescentSealColorId = $electedOption->id;
                            $selectedOption->userId = Auth::user()->id;
                            $selectedOption->save();
                            // dd($electedOption);
                        }
                    }

                    break;

                case 'Accoustics':

                    SelectedAccoustics::where(['userId' => $UserId])->whereNotIn('accousticsId', $keys)->delete();

                    foreach ($keys as $key) {
                        $electedOption = array();
                        $electedOption = Accoustics::Where('id', $key)->select('*')->first();
                        $selectedOptionExist = SelectedAccoustics::where(['userId' => $UserId, 'accousticsId' => $key])->first();
                        if (!empty($electedOption) && count((array) $electedOption) > 0 && empty($selectedOptionExist)) {
                            $selectedOption = new SelectedAccoustics();
                            $selectedOption->accousticsId = $electedOption->id;
                            $selectedOption->userId = Auth::user()->id;
                            $selectedOption->save();
                        }
                    }

                    break;

                case 'Architrave_Type':

                    SelectedArchitraveType::where(['userId' => $UserId])->whereNotIn('architraveTypeId', $keys)->delete();

                    foreach ($keys as $key) {
                        $electedOption = array();
                        $electedOption = ArchitraveType::Where('id', $key)->select('*')->first();
                        $selectedOptionExist = SelectedArchitraveType::where(['userId' => $UserId, 'architraveTypeId' => $key])->first();
                        if (!empty($electedOption) && count((array) $electedOption) > 0 && empty($selectedOptionExist)) {
                            $selectedOption = new SelectedArchitraveType();
                            $selectedOption->architraveTypeId = $electedOption->id;
                            $selectedOption->userId = Auth::user()->id;
                            $selectedOption->save();
                        }
                    }

                    break;

                case 'color_list':
                    SelectedColor::where(['SelectedUserId' => $UserId])->whereNotIn('SelectedColorId', $keys)->where('DoorLeafFacingName',$request->colorType)->delete();

                    foreach ($keys as $key) {
                        $electedOption = array();
                        $electedOption = Color::Where('id', $key)->select('*')->first();
                        $selectedOptionExist = SelectedColor::where(['SelectedUserId' => $UserId, 'SelectedColorId' => $key])->first();
                        if (!empty($electedOption) && count((array) $electedOption) > 0 && empty($selectedOptionExist)) {
                            $selectedOption = new SelectedColor();
                            $selectedOption->SelectedColorId = $electedOption->id;
                            $selectedOption->DoorLeafFacingName = $electedOption->DoorLeafFacing;
                            $selectedOption->SelectedUserId = Auth::user()->id;
                            $selectedOption->save();
                        }
                    }

                    break;

                case 'door_leaf_facing_value':
                    SelectedDoorLeafFacing::where(['userId' => $UserId])->whereNotIn('doorLeafFacingId', $keys)->delete();

                    foreach ($keys as $key) {
                        $electedOption = array();
                        $electedOption = DoorLeafFacing::Where('id', $key)->select('*')->first();
                        $selectedOptionExist = SelectedDoorLeafFacing::where(['userId' => $UserId, 'doorLeafFacingId' => $key])->first();
                        if (!empty($electedOption) && count((array) $electedOption) > 0 && empty($selectedOptionExist)) {
                            $selectedOption = new SelectedDoorLeafFacing();
                            $selectedOption->doorLeafFacingId = $electedOption->id;
                            $selectedOption->userId = Auth::user()->id;
                            $selectedOption->save();
                        }
                    }

                    break;

                    case 'leaf_type':

                        SelectedLeafType::where(['editBy' => $UserId])->whereNotIn('leaf_id', $keys)->delete();

                        foreach ($keys as $key) {
                            $electedOption = array();
                            $electedOption = LeafType::Where('id', $key)->select('*')->first();
                            $selectedOptionExist = SelectedLeafType::where(['editBy' => $UserId, 'leaf_id' => $key])->first();
                            // dd($key);
                            if (!empty($electedOption) && count((array) $electedOption) > 0 && empty($selectedOptionExist)) {
                                $selectedOption = new SelectedLeafType();
                                $selectedOption->leaf_id = $electedOption->id;
                                $selectedOption->editBy = Auth::user()->id;
                                $selectedOption->save();
                                // dd($electedOption);
                            }
                        }

                        break;
                        case 'Overpanel_Glass_Type':

                            SelectedOverpanelGlassGlazing::where(['editBy' => $UserId])->whereNotIn('glass_glazing_id', $keys)->delete();

                            foreach ($keys as $key) {
                                $electedOption = array();
                                $electedOption = OverpanelGlassGlazing::Where('id', $key)->select('*')->first();
                                $selectedOptionExist = SelectedOverpanelGlassGlazing::where(['editBy' => $UserId, 'glass_glazing_id' => $key])->first();
                                // dd($key);
                                if (!empty($electedOption) && count((array) $electedOption) > 0 && empty($selectedOptionExist)) {
                                    $selectedOption = new SelectedOverpanelGlassGlazing();
                                    $selectedOption->glass_glazing_id = $electedOption->id;
                                    $selectedOption->editBy = Auth::user()->id;
                                    $selectedOption->save();
                                    // dd($electedOption);
                                }
                            }

                            break;
                            case 'Overpanel_Glazing_System':

                                SelectedOverpanelGlassGlazing::where(['editBy' => $UserId])->whereNotIn('glass_glazing_id', $keys)->delete();

                                foreach ($keys as $key) {
                                    $electedOption = array();
                                    $electedOption = OverpanelGlassGlazing::Where('id', $key)->select('*')->first();
                                    $selectedOptionExist = SelectedOverpanelGlassGlazing::where(['editBy' => $UserId, 'glass_glazing_id' => $key])->first();
                                    // dd($key);
                                    if (!empty($electedOption) && count((array) $electedOption) > 0 && empty($selectedOptionExist)) {
                                        $selectedOption = new SelectedOverpanelGlassGlazing();
                                        $selectedOption->glass_glazing_id = $electedOption->id;
                                        $selectedOption->editBy = Auth::user()->id;
                                        $selectedOption->save();
                                        // dd($electedOption);
                                    }
                                }

                                break;

                    case 'SideScreen_Glass_Type':

                        SelectedScreenGlass::where(['editBy' => $UserId])->whereNotIn('glass_id', $keys)->delete();

                        foreach ($keys as $key) {
                            $electedOption = array();
                            $electedOption = ScreenGlassType::Where('id', $key)->select('*')->first();
                            $selectedOptionExist = SelectedScreenGlass::where(['editBy' => $UserId, 'glass_id' => $key])->first();
                            // dd($key);
                            if (!empty($electedOption) && count((array) $electedOption) > 0 && empty($selectedOptionExist)) {
                                $selectedOption = new SelectedScreenGlass();
                                $selectedOption->glass_id = $electedOption->id;
                                $selectedOption->editBy = Auth::user()->id;
                                $selectedOption->save();
                                // dd($electedOption);
                            }
                        }

                    break;
                    case 'SideScreen_Glazing_System':

                        SelectedScreenGlazing::where(['editBy' => $UserId])->whereNotIn('glazing_id', $keys)->delete();

                        foreach ($keys as $key) {
                            $electedOption = array();
                            $electedOption = ScreenGlazingType::Where('id', $key)->select('*')->first();
                            $selectedOptionExist = SelectedScreenGlazing::where(['editBy' => $UserId, 'glazing_id' => $key])->first();
                            // dd($key);
                            if (!empty($electedOption) && count((array) $electedOption) > 0 && empty($selectedOptionExist)) {
                                $selectedOption = new SelectedScreenGlazing();
                                $selectedOption->glazing_id = $electedOption->id;
                                $selectedOption->editBy = Auth::user()->id;
                                $selectedOption->save();
                                // dd($electedOption);
                            }
                        }

                    break;

                default:
                    SelectedOption::where(['SelectedUserId' => $UserId, 'SelectedOptionSlug' => $optionKey])->whereNotIn('optionId', $keys)->delete();
                    foreach ($keys as $key) {
                        $electedOption = array();
                        $electedOption = Option::Where('id', $key)->select('id', 'OptionSlug', 'OptionValue', 'UnderAttribute', 'OptionKey','configurableitems')->first();
                        $selectedOptionExist = SelectedOption::where(['SelectedUserId' => $UserId, 'SelectedOptionSlug' => $optionKey, 'optionId' => $key])->first();

                        if (!empty($electedOption) && count((array) $electedOption) > 0 && empty($selectedOptionExist)) {
                            $selectedOption = new SelectedOption();
                            $selectedOption->configurableitems = $electedOption->configurableitems;
                            $selectedOption->optionId = $electedOption->id;
                            $selectedOption->SelectedUserId = Auth::user()->id;
                            $selectedOption->tag = $className;
                            $selectedOption->SelectedUnderAttribute = $electedOption->UnderAttribute;
                            $selectedOption->SelectedOptionSlug = $electedOption->OptionSlug;
                            $selectedOption->SelectedOptionKey = $electedOption->OptionKey;
                            $selectedOption->SelectedOptionValue = $electedOption->OptionValue;
                            $selectedOption->save();
                            // dd($selectedOption);
                        }
                    }
                    break;
            }

            echo json_encode(array("status" => "ok", "msg" => "options are updated"));
        } else {
            if ($className == 'lippingSpecies') {
                SelectedLippingSpeciesItems::where(['selected_user_id' => $UserId])->delete();
            }
            if ($className == 'doordimension') {
                SelectedDoorDimension::where(['doordimension_user_id' => $UserId])->whereNotIn('selected_configurableitems', [1,2,7,8])->delete();
            }
            if ($className == 'intumescentSealArrangement') {
                SelectedIntumescentSeals2::where(['selected_intumescentseals2_user_id' => $UserId])->whereIn('selected_configurableitems', [3,4,5,6])->delete();
            }
            if ($className == 'intumescentSealArrangementCustome') {
                SelectedIntumescentSeals2::where(['selected_intumescentseals2_user_id' => $UserId])->whereIn('selected_configurableitems', [1, 2, 7, 8])->delete();
            }
            if ($className == "door_leaf_facing_value") {
                SelectedOption::where(['configurableitems' => $pageId, 'SelectedUserId' => $UserId, 'tag' => 'doorleaffacingvalue'])->delete();
            }
            if ($className == "Accoustics") {
                SelectedOption::where(['configurableitems' => $pageId, 'SelectedUserId' => $UserId, 'tag' => 'Accoustics'])->delete();
            }
            if ($className == "door_leaf_finish") {
                SelectedOption::where(['configurableitems' => $pageId, 'SelectedUserId' => $UserId, 'tag' => 'door_leaf_finish'])->delete();
            }
            if ($className == "color_list") {
                SelectedColor::where(['SelectedUserId' => $UserId])->where('DoorLeafFacingName',$request->colorType)->delete();
            }
            if ($className == "leaf_type") {
                SelectedLeafType::where(['editBy' => $UserId])->delete();
            }
            if ($className == "leaf1_glass_type") {
                // SelectedGlassType::where(['editBy' => $UserId])->delete();
                $customGlassTypeIds = GlassType::where(function ($query) {
                    $query->whereNotNull('VicaimaDoorCore')
                          ->orWhereNotNull('Seadec')
                          ->orWhereNotNull('Deanta');
                })
                ->pluck('id'); // Assuming `id` is the primary key in Options table

            // Delete only the records for the custom module and current user
            SelectedGlassType::where('editBy', $UserId)
                ->whereIn('glass_id', $customGlassTypeIds)
                ->delete();
            }
            if ($className == "leaf1_glass_type_custome") {
                $customGlassTypeIds = GlassType::where(function ($query) {
                    $query->whereNotNull('Streboard')
                          ->orWhereNotNull('Halspan')
                          ->orWhereNotNull('Stredor')
                          ->orWhereNotNull('Flamebreak');
                })
                ->pluck('id'); // Assuming `id` is the primary key in Options table

            // Delete only the records for the custom module and current user
            SelectedGlassType::where('editBy', $UserId)
                ->whereIn('glass_id', $customGlassTypeIds)
                ->delete();
                // SelectedGlassType::where(['editBy' => $UserId])->delete();
            }
            if ($className == "leaf1_glazing_systems") {
                $customGlassTypeIds = GlazingSystem::where(function ($query) {
                    $query->whereNotNull('VicaimaDoorCore')
                    ->orWhereNotNull('Seadec')
                    ->orWhereNotNull('Deanta');
                })
                ->pluck('id');
                 SelectedGlazingSystem::where('userId', $UserId)
                    ->whereIn('glazingId', $customGlassTypeIds)
                    ->delete();
            }
            if ($className == "leaf1_glazing_systems_custome") {
                $customGlassTypeIds = GlazingSystem::where(function ($query) {
                    $query->whereNotNull('Streboard')
                          ->orWhereNotNull('Halspan')
                          ->orWhereNotNull('Stredor')
                          ->orWhereNotNull('Flamebreak');
                })
                ->pluck('id');
                 SelectedGlazingSystem::where('userId', $UserId)
                    ->whereIn('glazingId', $customGlassTypeIds)
                    ->delete();
            }
            if ($className == "Intumescent_Seal_Color") {
                SelectedIntumescentSealColor::where(['userId' => $UserId])->delete();
            }
            if ($className == "Accoustics") {
                SelectedAccoustics::where(['userId' => $UserId])->delete();
            }
            if ($className == "door_leaf_facing_value") {
                SelectedDoorLeafFacing::where(['userId' => $UserId])->delete();
            }
            if ($className == "Architrave_Type") {
                SelectedArchitraveType::where(['userId' => $UserId])->delete();
            }
            if ($className == "Overpanel_Glazing_System" || $className == "Overpanel_Glass_Type") {
                SelectedOverpanelGlassGlazing::where(['editBy' => $UserId])->delete();
            }
            if ($className == "SideScreen_Glass_Type") {
                SelectedScreenGlass::where(['editBy' => $UserId])->delete();
            }
            if ($className == "SideScreen_Glazing_System") {
                SelectedScreenGlazing::where(['editBy' => $UserId])->delete();
            }

            SelectedOption::where(['configurableitems' => $pageId, 'SelectedUserId' => $UserId, 'tag' => $className])->delete();
            echo json_encode(array("status" => "ok", "msg" => "please check options"));
        }
    }

    public function updateSelectOptionCustome(request $request)
    {

        $pageId = $request->pageId;
        $className = $request->className;
        if($request->className == 'door_dimension_custome'){
            $keys =  explode(',',$request->selectedValue);
        }else{
                $keys = $request->selectedValue;
            }
        $UserId = Auth::user()->id;
        if (!empty($keys) && count($keys)) {
            $optionKey = $className;
            switch ($className) {
                case 'door_dimension_custome':
                    $className = "door_dimension_custome";
                    break;

                default:
                    $className = $className;
                    break;
            }
            switch ($className) {
                case 'door_dimension_custome':
                    SelectedDoordimension::where(['doordimension_user_id' => $UserId])->whereNotIn('selected_configurableitems', [3,4,5,6])->whereNotIn('doordimension_id', $keys)->delete();
                    foreach ($keys as $key) {
                        $electedOption = array();
                        $electedOption = DoorDimension::Where('id', $key)->select('*')->first();
                        $selectedOptionExist = SelectedDoordimension::where(['doordimension_user_id' => $UserId, 'doordimension_id' => $key])->first();
                        // dd($electedOption);
                        if (!empty($electedOption) && count((array) $electedOption) > 0 && empty($selectedOptionExist)) {
                            $selectedOption = new SelectedDoordimension();
                            // $selectedOption->selected_configurableitems = $pageId;
                            $selectedOption->doordimension_id = $electedOption->id;
                            $selectedOption->selected_configurableitems = $electedOption->configurableitems;
                            $selectedOption->doordimension_user_id = Auth::user()->id;
                            $selectedOption->selected_firerating = $electedOption->fire_rating;
                            $selectedOption->selected_code = $electedOption->code;
                            $selectedOption->selected_mm_height = $electedOption->mm_height;
                            $selectedOption->selected_mm_width = $electedOption->mm_width;
                            $selectedOption->selected_sellingprice = $electedOption->selling_price;
                            $selectedOption->custome_door_selected_cost = ($electedOption->custome_door_selected_cost) ?? 0;
                            $selectedOption->save();
                            // dd($selectedOption);
                        }
                    }

                    break;
                default:
                    SelectedOption::where(['SelectedUserId' => $UserId, 'SelectedOptionSlug' => $optionKey])->whereNotIn('optionId', $keys)->delete();
                    foreach ($keys as $key) {
                        $electedOption = array();
                        $electedOption = Option::Where('id', $key)->select('id', 'OptionSlug', 'OptionValue', 'UnderAttribute', 'OptionKey','configurableitems')->first();
                        $selectedOptionExist = SelectedOption::where(['SelectedUserId' => $UserId, 'SelectedOptionSlug' => $optionKey, 'optionId' => $key])->first();

                        if (!empty($electedOption) && count((array) $electedOption) > 0 && empty($selectedOptionExist)) {
                            $selectedOption = new SelectedOption();
                            $selectedOption->configurableitems = $electedOption->configurableitems;
                            $selectedOption->optionId = $electedOption->id;
                            $selectedOption->SelectedUserId = Auth::user()->id;
                            $selectedOption->tag = $className;
                            $selectedOption->SelectedUnderAttribute = $electedOption->UnderAttribute;
                            $selectedOption->SelectedOptionSlug = $electedOption->OptionSlug;
                            $selectedOption->SelectedOptionKey = $electedOption->OptionKey;
                            $selectedOption->SelectedOptionValue = $electedOption->OptionValue;
                            $selectedOption->save();
                            // dd($selectedOption);
                        }
                    }
                    break;
            }

            echo json_encode(array("status" => "ok", "msg" => "options are updated"));
        } else {
            if ($className == 'door_dimension_custome') {
                SelectedDoorDimension::where(['doordimension_user_id' => $UserId])->whereNotIn('selected_configurableitems', [3,4,5,6])->delete();
            }
            echo json_encode(array("status" => "ok", "msg" => "please check options"));
        }
    }

    public function updateSelectOptionCost(request $request)
    {
        $SelectedOptionId = $request->SelectedOptionId;
        $SelectedOptionCost = $request->SelectedOptionCost;
        $OptionType = $request->OptionType;
        $brand = $request->brand;
        $intumescentSeals = $request->intumescentSeals;

        $UserId = Auth::user()->id;



        switch ($OptionType) {
            case 'lippingSpecies':
                $selectedOption = SelectedLippingSpeciesItems::where(['id' => $SelectedOptionId])->first();
                if ($selectedOption) {

                    $selectedOption->selected_price = $SelectedOptionCost;
                    $selectedOption->save();
                    echo json_encode(array("status" => "ok", "msg" => "Option cost updated"));
                } else {
                    echo json_encode(array("status" => "error", "msg" => "option not found"));
                }
                break;

            case 'intumescentSealArrangement':
                $selectedOption = SelectedIntumescentSeals2::where(['id' => $request->selectedId])->first();
                // $selectedOptionids = SelectedIntumescentSeals2::Where('selected_intumescentSeals', $selectedOption->selected_intumescentSeals)->Where('selected_brand', $selectedOption->selected_brand)->Where('selected_firerating', $selectedOption->selected_firerating)->select('*')->get();

                if (!empty($selectedOption)) {
                    // foreach ($selectedOptionids as $value) {
                        $selectedOption = SelectedIntumescentSeals2::where(['id' => $request->selectedId])->first();
                        $selectedOption->selected_cost = $request->price;
                        $selectedOption->save();
                    // }

                    echo json_encode(array("status" => "ok", "msg" => "Option cost updated"));
                } else {
                    echo json_encode(array("status" => "error", "msg" => "option not found"));
                }
                break;
            case 'intumescentSealArrangementCustome':
                $selectedOption = SelectedIntumescentSeals2::where(['id' => $request->selectedId])->first();
                // $selectedOptionids = SelectedIntumescentSeals2::Where('selected_intumescentSeals', $selectedOption->selected_intumescentSeals)->Where('selected_brand', $selectedOption->selected_brand)->Where('selected_firerating', $selectedOption->selected_firerating)->select('*')->get();

                if (!empty($selectedOption)) {
                    // foreach ($selectedOptionids as $value) {
                        $selectedOption = SelectedIntumescentSeals2::where(['id' => $request->selectedId])->first();
                        $selectedOption->selected_cost = $request->price;
                        $selectedOption->save();
                    // }

                    echo json_encode(array("status" => "ok", "msg" => "Option cost updated"));
                } else {
                    echo json_encode(array("status" => "error", "msg" => "option not found"));
                }
                break;

            case 'door_dimension':
                $selectedOption = SelectedDoordimension::where(['id' => $request->selectedId])->first();
                if ($selectedOption) {
                    $selectedOption->selected_cost = $request->price;
                    $selectedOption->save();

                    echo json_encode(array("status" => "ok", "msg" => "Option cost updated"));
                } else {
                    echo json_encode(array("status" => "error", "msg" => "option not found"));
                }
                break;
                case 'door_dimension_custome':
                    // dd($request->all());
                    $selectedOption = SelectedDoordimension::where(['id' => $request->selectedId])->first();
                    if ($selectedOption) {
                        $selectedOption->selected_cost = $request->price;
                        $selectedOption->save();

                        echo json_encode(array("status" => "ok", "msg" => "Option cost updated"));
                    } else {
                        echo json_encode(array("status" => "error", "msg" => "option not found"));
                    }
                    break;
            case 'leaf1_glass_type':
                $selectedOption = SelectedGlassType::where(['id' => $request->selectedId])->first();
                if ($selectedOption) {
                    $selectedOption->SelectedPrice = $request->price;
                    $selectedOption->save();

                    echo json_encode(array("status" => "ok", "msg" => "Option cost updated"));
                } else {
                    echo json_encode(array("status" => "error", "msg" => "option not found"));
                }
                break;
            case 'leaf1_glass_type_custome':
                $selectedOption = SelectedGlassType::where(['id' => $request->selectedId])->first();
                if ($selectedOption) {
                    $selectedOption->SelectedPrice = $request->price;
                    $selectedOption->save();

                    echo json_encode(array("status" => "ok", "msg" => "Option cost updated"));
                } else {
                    echo json_encode(array("status" => "error", "msg" => "option not found"));
                }
                // dd($selectedOption);
                break;
            case 'leaf_type':
                $selectedOption = SelectedLeafType::where(['id' => $request->selectedId])->first();
                if ($selectedOption) {
                    $selectedOption->SelectedPrice = $request->price;
                    $selectedOption->save();

                    echo json_encode(array("status" => "ok", "msg" => "Option cost updated"));
                } else {
                    echo json_encode(array("status" => "error", "msg" => "option not found"));
                }
                break;

            case 'leaf1_glazing_systems':
                $selectedOption = SelectedGlazingSystem::where(['id' => $request->selectedId])->first();
                if ($selectedOption) {
                    $selectedOption->selectedPrice = $request->price;
                    $selectedOption->save();

                    echo json_encode(array("status" => "ok", "msg" => "Option cost updated"));
                } else {
                    echo json_encode(array("status" => "error", "msg" => "option not found"));
                }
                break;
            case 'leaf1_glazing_systems_custome':
                $selectedOption = SelectedGlazingSystem::where(['id' => $request->selectedId])->first();
                if ($selectedOption) {
                    $selectedOption->selectedPrice = $request->price;
                    $selectedOption->save();

                    echo json_encode(array("status" => "ok", "msg" => "Option cost updated"));
                } else {
                    echo json_encode(array("status" => "error", "msg" => "option not found"));
                }
                break;

            case 'Intumescent_Seal_Color':
                $selectedOption = SelectedIntumescentSealColor::where(['id' => $request->selectedId])->first();
                if ($selectedOption) {
                    $selectedOption->selectedPrice = $request->price;
                    $selectedOption->save();

                    echo json_encode(array("status" => "ok", "msg" => "Option cost updated"));
                } else {
                    echo json_encode(array("status" => "error", "msg" => "option not found"));
                }
                break;

            case 'Architrave_Type':
                $selectedOption = SelectedArchitraveType::where(['id' => $request->selectedId])->first();
                if ($selectedOption) {
                    $selectedOption->selectedPrice = $request->price;
                    $selectedOption->save();

                    echo json_encode(array("status" => "ok", "msg" => "Option cost updated"));
                } else {
                    echo json_encode(array("status" => "error", "msg" => "option not found"));
                }
                break;

            case 'color_list':
                $selectedOption = SelectedColor::where(['id' => $request->selectedId])->first();
                if ($selectedOption) {
                    $selectedOption->SelectedPrice = $request->price;
                    $selectedOption->save();

                    echo json_encode(array("status" => "ok", "msg" => "Option cost updated"));
                } else {
                    echo json_encode(array("status" => "error", "msg" => "option not found"));
                }
                break;

            case 'Accoustics':
                $selectedOption = SelectedAccoustics::where(['id' => $request->selectedId])->first();
                if ($selectedOption) {
                    $selectedOption->selectedPrice = $request->price;
                    $selectedOption->save();

                    echo json_encode(array("status" => "ok", "msg" => "Option cost updated"));
                } else {
                    echo json_encode(array("status" => "error", "msg" => "option not found"));
                }
                break;
            case 'door_leaf_facing_value':
                $selectedOption = SelectedDoorLeafFacing::where(['id' => $request->selectedId])->first();
                if ($selectedOption) {
                    $selectedOption->selectedPrice = $request->price;
                    $selectedOption->save();

                    echo json_encode(array("status" => "ok", "msg" => "Option cost updated"));
                } else {
                    echo json_encode(array("status" => "error", "msg" => "option not found"));
                }
                break;

            case 'door_leaf_finish':
                $selectedOption = SelectedOption::where(['id' => $request->selectedId])->first();
                if ($selectedOption) {

                    $selectedOption->SelectedOptionCost = $request->price;
                    $selectedOption->save();
                    echo json_encode(array("status" => "ok", "msg" => "option cost updated"));
                } else {
                    echo json_encode(array("status" => "error", "msg" => "option not found"));
                }
            break;
            case 'Overpanel_Glass_Type':
                $selectedOption = SelectedOverpanelGlassGlazing::where(['id' => $request->selectedId])->first();
                if ($selectedOption) {
                    $selectedOption->glassSelectedPrice = $request->price;
                    $selectedOption->save();

                    echo json_encode(array("status" => "ok", "msg" => "Option cost updated"));
                } else {
                    echo json_encode(array("status" => "error", "msg" => "option not found"));
                }
            break;
            case 'Overpanel_Glazing_System':
                $selectedOption = SelectedOverpanelGlassGlazing::where(['id' => $request->selectedId])->first();
                if ($selectedOption) {
                    $selectedOption->glazingSelectedPrice = $request->price;
                    $selectedOption->save();

                    echo json_encode(array("status" => "ok", "msg" => "Option cost updated"));
                } else {
                    echo json_encode(array("status" => "error", "msg" => "option not found"));
                }
            break;
            case 'SideScreen_Glass_Type':
                $selectedOption = SelectedScreenGlass::where(['id' => $request->selectedId])->first();
                if ($selectedOption) {

                    $selectedOption->glassSelectedPrice = $request->price;
                    $selectedOption->save();
                    echo json_encode(array("status" => "ok", "msg" => "option cost updated"));
                } else {
                    echo json_encode(array("status" => "error", "msg" => "option not found"));
                }
            break;
            case 'SideScreen_Glazing_System':
                $selectedOption = SelectedScreenGlazing::where(['id' => $request->selectedId])->first();
                if ($selectedOption) {

                    $selectedOption->glazingSelectedPrice = $request->price;
                    $selectedOption->save();
                    echo json_encode(array("status" => "ok", "msg" => "option cost updated"));
                } else {
                    echo json_encode(array("status" => "error", "msg" => "option not found"));
                }
            break;

            default:
                $selectedOption = SelectedOption::where(['id' => $request->selectedId])->first();
                if ($selectedOption) {

                    $selectedOption->SelectedOptionCost = $request->price;
                    $selectedOption->save();
                    echo json_encode(array("status" => "ok", "msg" => "option cost updated"));
                } else {
                    echo json_encode(array("status" => "error", "msg" => "option not found"));
                }
                break;
        }
    }

    public function updateSelectOptionCostCustome(request $request)
    {
        // dd('hii');
        // Debugging the incoming request payload
        // dd($request->all());

        $OptionType = $request->OptionType;

        // Check if OptionType is 'door_dimension_custome'
        switch ($OptionType) {
            case 'door_dimension_custome':
                // Retrieve the selected option based on the selectedId
                $selectedOption = SelectedDoordimension::where('id', $request->selectedId)->first();

                if ($selectedOption) {
                    // Decode existing costs as an array (ensure it’s stored in JSON in the DB)
                    $costs = $selectedOption->custome_door_selected_cost ? json_decode($selectedOption->custome_door_selected_cost, true) : [];

                    // Update the specific leafid's price in the costs array
                    $costs[$request->leafid] = $request->price[$request->leafid];

                    // Encode the costs array back to JSON and save it
                    $selectedOption->custome_door_selected_cost = json_encode($costs);
                    $selectedOption->save();

                    return response()->json(["status" => "ok", "msg" => "Option cost updated"]);
                } else {
                    return response()->json(["status" => "error", "msg" => "Option not found"]);
                }
                break;

            default:
                // Handle other option types (if applicable)
                $selectedOption = SelectedOption::where('id', $request->selectedId)->first();

                if ($selectedOption) {
                    // Update the cost for non-door-dimension options
                    $selectedOption->SelectedOptionCost = $request->price;
                    $selectedOption->save();

                    return response()->json(["status" => "ok", "msg" => "Option cost updated"]);
                } else {
                    return response()->json(["status" => "error", "msg" => "Option not found"]);
                }
                break;
        }
    }



    public function filter_swing_type(request $request)
    {
        if ($request->firerating == 'NFR') {
            $data = Option::where('configurableitems', 3)
                ->where('OptionSlug', 'door_configuration_swing_type')
                ->get();
        } else {
            $data = Option::where('configurableitems', 3)
                ->where('OptionSlug', 'door_configuration_swing_type')
                ->where('OptionKey', 'SA')
                ->first();
        }
        return $data;
    }

    public function filter_door_dimensions(request $request)
    {
        if ($request->leaf_type == 'Flush') {
            if ($request->door_leaf_facing && $request->door_leaf_finish) {
                $data =    DoorDimension::where('leaf_type', $request->leaf_type)
                    ->where('door_leaf_facing', $request->door_leaf_facing)
                    ->where('door_leaf_finish', $request->door_leaf_finish)
                    ->get();
            } elseif ($request->door_leaf_facing) {
                $data = DoorDimension::where('leaf_type', $request->leaf_type)->where('door_leaf_facing', $request->door_leaf_facing)->get();
            } else {
                $data = DoorDimension::where('leaf_type', $request->leaf_type)->where('door_leaf_finish', $request->door_leaf_finish)->get();
            }
            return $data;
        } elseif (isset($request->page_id) && ($request->page_id == 4 || $request->page_id == 5) && $request->door_leaf_facing && $request->leaf_type && $request->firerating) {
            $fireRating = fireRatingDoor($request->firerating);
            $data = $fireRating != 'NFR'? GetOptions(['door_dimension.configurableitems' => $request->page_id ,'door_dimension.leaf_type' => $request->leaf_type,'door_dimension.door_leaf_facing' => $request->door_leaf_facing,'door_dimension.fire_rating' => $fireRating], "join","DoorDimension") : GetOptions(['door_dimension.configurableitems' => $request->page_id ,'door_dimension.leaf_type' => $request->leaf_type,'door_dimension.door_leaf_facing' => $request->door_leaf_facing], "join","DoorDimension");
            return $data;
        } else {
            if ($request->door_leaf_facing && $request->door_leaf_finish) {
                $data =    DoorDimension::where('leaf_type', '!=', 'Flush')
                    ->where('door_leaf_facing', $request->door_leaf_facing)
                    ->where('door_leaf_finish', $request->door_leaf_finish)
                    ->get();
            } elseif ($request->door_leaf_facing && $request->firerating) {
                $data = DoorDimension::where('leaf_type', '!=', 'Flush')->where('door_leaf_facing', $request->door_leaf_facing)->where('fire_rating', $request->firerating)->get();
            } elseif ($request->door_leaf_facing) {
                $data = DoorDimension::where('leaf_type', '!=', 'Flush')->where('door_leaf_facing', $request->door_leaf_facing)->get();
            } else {
                $data = DoorDimension::where('leaf_type', '!=', 'Flush')->where('door_leaf_finish', $request->door_leaf_finish)->get();
            }
            return $data;
        }
    }

    public function filter_door_dimensions_leaf(request $request)
    {
        if (isset($request->page_id) && ($request->page_id == 4 || $request->page_id == 5 || $request->page_id == 6) && $request->door_leaf_facing && $request->leaf_type && $request->firerating && $request->DoorDimensionId) {
            $fireRating = fireRatingDoor($request->firerating);

            $mm_height = DoorDimension::where('id',$request->DoorDimensionId)->first();
            $data = $fireRating != 'NFR'? GetOptions(['door_dimension.configurableitems' => $request->page_id ,'door_dimension.leaf_type' => $request->leaf_type,'door_dimension.door_leaf_facing' => $request->door_leaf_facing,'door_dimension.fire_rating' => $fireRating,'door_dimension.mm_height' => $mm_height->mm_height], "join","DoorDimension") : GetOptions(['door_dimension.configurableitems' => $request->page_id ,'door_dimension.leaf_type' => $request->leaf_type,'door_dimension.door_leaf_facing' => $request->door_leaf_facing,'door_dimension.mm_height' => $mm_height->mm_height], "join","DoorDimension");
            return $data;
        }
    }

    public function filter_latch_type(request $request)
    {
        extract($_POST);
        if ($door_set_type == "SD" && $swing_type == "SA") {
            $data = Option::where('configurableitems', 3)
                ->where('OptionValue', 'L = Latched')
                ->where('OptionKey', 'L')
                ->get();
        }
        if ($door_set_type == "DD" && $swing_type == "SA") {
            $data = Option::where('configurableitems', 3)
                ->where('OptionSlug', 'door_configuration_latch_type')
                ->get();
        }
        if ($door_set_type == "leaf_and_a_half" && $swing_type == "SA") {
            $data = Option::where('configurableitems', 3)
                ->where('OptionSlug', 'door_configuration_latch_type')
                ->get();
        }
        return $data;
    }

    public function checkSelectOption(request $request)
    {
        $option = $request->optionname;
        switch ($option) {
            case "option":
                $Option = Option::where(['id' => $request->optionId])->first();
                if ($Option) {
                    if ($Option->firerating == 'FD30') {
                        $optionSelection = Option::where(['firerating' => 'FD30s', 'UnderParent2' => $Option->UnderParent2, 'OptionName' => $Option->OptionName, 'OptionSlug' => $Option->OptionSlug, 'OptionKey' => $Option->OptionKey, 'OptionValue' => $Option->OptionValue])->first();

                        $selectedOption = Option::join('selected_option', 'selected_option.optionId', '=', 'options.id')->select('options.id', 'options.OptionName', 'options.OptionSlug', 'options.OptionValue', 'options.UnderAttribute', 'options.OptionKey', 'selected_option.id as SelectedOptionId', 'selected_option.SelectedOptionCost')->where(['options.firerating' => 'FD30s', 'options.UnderParent2' => $Option->UnderParent2, 'options.OptionName' => $Option->OptionName, 'options.OptionSlug' => $Option->OptionSlug, 'options.OptionKey' => $Option->OptionKey, 'options.OptionValue' => $Option->OptionValue, 'selected_option.SelectedUserId' => Auth::user()->id])->first();
                    } elseif ($Option->firerating == 'FD30s') {
                        $optionSelection = Option::where(['firerating' => 'FD30', 'UnderParent2' => $Option->UnderParent2, 'OptionName' => $Option->OptionName, 'OptionSlug' => $Option->OptionSlug, 'OptionKey' => $Option->OptionKey, 'OptionValue' => $Option->OptionValue])->first();

                        $selectedOption = Option::join('selected_option', 'selected_option.optionId', '=', 'options.id')->select('options.id', 'options.OptionName', 'options.OptionSlug', 'options.OptionValue', 'options.UnderAttribute', 'options.OptionKey', 'selected_option.id as SelectedOptionId', 'selected_option.SelectedOptionCost')->where(['options.firerating' => 'FD30', 'options.UnderParent2' => $Option->UnderParent2, 'options.OptionName' => $Option->OptionName, 'options.OptionSlug' => $Option->OptionSlug, 'options.OptionKey' => $Option->OptionKey, 'options.OptionValue' => $Option->OptionValue, 'selected_option.SelectedUserId' => Auth::user()->id])->first();
                    } elseif ($Option->firerating == 'FD60') {
                        $optionSelection = Option::where(['firerating' => 'FD60s', 'UnderParent2' => $Option->UnderParent2, 'OptionName' => $Option->OptionName, 'OptionSlug' => $Option->OptionSlug, 'OptionKey' => $Option->OptionKey, 'OptionValue' => $Option->OptionValue])->first();

                        $selectedOption = Option::join('selected_option', 'selected_option.optionId', '=', 'options.id')->select('options.id', 'options.OptionName', 'options.OptionSlug', 'options.OptionValue', 'options.UnderAttribute', 'options.OptionKey', 'selected_option.id as SelectedOptionId', 'selected_option.SelectedOptionCost')->where(['options.firerating' => 'FD60s', 'options.UnderParent2' => $Option->UnderParent2, 'options.OptionName' => $Option->OptionName, 'options.OptionSlug' => $Option->OptionSlug, 'options.OptionKey' => $Option->OptionKey, 'options.OptionValue' => $Option->OptionValue, 'selected_option.SelectedUserId' => Auth::user()->id])->first();
                    } elseif ($Option->firerating == 'FD60s') {
                        $optionSelection = Option::where(['firerating' => 'FD60', 'UnderParent2' => $Option->UnderParent2, 'OptionName' => $Option->OptionName, 'OptionSlug' => $Option->OptionSlug, 'OptionKey' => $Option->OptionKey, 'OptionValue' => $Option->OptionValue])->first();

                        $selectedOption = Option::join('selected_option', 'selected_option.optionId', '=', 'options.id')->select('options.id', 'options.OptionName', 'options.OptionSlug', 'options.OptionValue', 'options.UnderAttribute', 'options.OptionKey', 'selected_option.id as SelectedOptionId', 'selected_option.SelectedOptionCost')->where(['options.firerating' => 'FD60', 'options.UnderParent2' => $Option->UnderParent2, 'options.OptionName' => $Option->OptionName, 'options.OptionSlug' => $Option->OptionSlug, 'options.OptionKey' => $Option->OptionKey, 'options.OptionValue' => $Option->OptionValue, 'selected_option.SelectedUserId' => Auth::user()->id])->first();
                    }

                    if (!empty($optionSelection)) {
                        if (!empty($selectedOption) && !empty($optionSelection)) {
                            echo json_encode(array("status" => "ok", "id" => $optionSelection->id, "SelectedOptionId" => $selectedOption->SelectedOptionId, "optionType" => 'option'));
                        } elseif (empty($selectedOption)) {
                            echo json_encode(array("status" => "ok", "id" => $optionSelection->id, "optionType" => 'option'));
                        } else {
                            echo json_encode(array("status" => "ok", "SelectedOptionId" => $selectedOption->SelectedOptionId, "optionType" => 'option'));
                        }
                    } else {
                        echo json_encode(array("status" => "error", "msg" => "Id not found"));
                    }
                } else {
                    echo json_encode(array("status" => "error", "msg" => "Id not found"));
                }
                break;

            case 'intumescentSealArrangement':
                $Option = SettingIntumescentSeals2::where(['id' => $request->optionId])->first();
                if ($Option) {
                    if ($Option->firerating == 'FD30') {
                        $optionSelection = SettingIntumescentSeals2::where(['firerating' => 'FD30s', 'intumescentSeals' => $Option->intumescentSeals, 'brand' => $Option->brand, 'configuration' => $Option->configuration, 'configurableitems' => $Option->configurableitems, 'doorname' => $Option->doorname])->first();

                        $selectedOption = SettingIntumescentSeals2::join('selected_intumescentseals2', 'selected_intumescentseals2.intumescentseals2_id', '=', 'setting_intumescentseals2.id')->select('selected_intumescentseals2.id as SelectedOptionId', 'selected_intumescentseals2.selected_intumescentSeals', 'setting_intumescentseals2.brand')->where(['setting_intumescentseals2.firerating' => 'FD30s', 'setting_intumescentseals2.intumescentSeals' => $Option->intumescentSeals, 'setting_intumescentseals2.brand' => $Option->brand, 'setting_intumescentseals2.configuration' => $Option->configuration, 'setting_intumescentseals2.configurableitems' => $Option->configurableitems, 'setting_intumescentseals2.doorname' => $Option->doorname, 'selected_intumescentseals2.selected_intumescentseals2_user_id' => Auth::user()->id])->first();
                    } elseif ($Option->firerating == 'FD30s') {
                        $optionSelection = SettingIntumescentSeals2::where(['firerating' => 'FD30', 'intumescentSeals' => $Option->intumescentSeals, 'brand' => $Option->brand, 'configuration' => $Option->configuration, 'configurableitems' => $Option->configurableitems, 'doorname' => $Option->doorname])->first();

                        $selectedOption = SettingIntumescentSeals2::join('selected_intumescentseals2', 'selected_intumescentseals2.intumescentseals2_id', '=', 'setting_intumescentseals2.id')->select('selected_intumescentseals2.id as SelectedOptionId', 'selected_intumescentseals2.selected_intumescentSeals', 'setting_intumescentseals2.brand')->where(['setting_intumescentseals2.firerating' => 'FD30', 'setting_intumescentseals2.intumescentSeals' => $Option->intumescentSeals, 'setting_intumescentseals2.brand' => $Option->brand, 'setting_intumescentseals2.configuration' => $Option->configuration, 'setting_intumescentseals2.configurableitems' => $Option->configurableitems, 'setting_intumescentseals2.doorname' => $Option->doorname, 'selected_intumescentseals2.selected_intumescentseals2_user_id' => Auth::user()->id])->first();
                    } elseif ($Option->firerating == 'FD60') {
                        $optionSelection = SettingIntumescentSeals2::where(['firerating' => 'FD60s', 'intumescentSeals' => $Option->intumescentSeals, 'brand' => $Option->brand, 'configuration' => $Option->configuration, 'configurableitems' => $Option->configurableitems, 'doorname' => $Option->doorname])->first();

                        $selectedOption = SettingIntumescentSeals2::join('selected_intumescentseals2', 'selected_intumescentseals2.intumescentseals2_id', '=', 'setting_intumescentseals2.id')->select('selected_intumescentseals2.id as SelectedOptionId', 'selected_intumescentseals2.selected_intumescentSeals', 'setting_intumescentseals2.brand')->where(['setting_intumescentseals2.firerating' => 'FD60s', 'setting_intumescentseals2.intumescentSeals' => $Option->intumescentSeals, 'setting_intumescentseals2.brand' => $Option->brand, 'setting_intumescentseals2.configuration' => $Option->configuration, 'setting_intumescentseals2.configurableitems' => $Option->configurableitems, 'setting_intumescentseals2.doorname' => $Option->doorname, 'selected_intumescentseals2.selected_intumescentseals2_user_id' => Auth::user()->id])->first();
                    } elseif ($Option->firerating == 'FD60s') {
                        $optionSelection = SettingIntumescentSeals2::where(['firerating' => 'FD60', 'intumescentSeals' => $Option->intumescentSeals, 'brand' => $Option->brand, 'configuration' => $Option->configuration, 'configurableitems' => $Option->configurableitems, 'doorname' => $Option->doorname])->first();

                        $selectedOption = SettingIntumescentSeals2::join('selected_intumescentseals2', 'selected_intumescentseals2.intumescentseals2_id', '=', 'setting_intumescentseals2.id')->select('selected_intumescentseals2.id as SelectedOptionId', 'selected_intumescentseals2.selected_intumescentSeals', 'setting_intumescentseals2.brand')->where(['setting_intumescentseals2.firerating' => 'FD60', 'setting_intumescentseals2.intumescentSeals' => $Option->intumescentSeals, 'setting_intumescentseals2.brand' => $Option->brand, 'setting_intumescentseals2.configuration' => $Option->configuration, 'setting_intumescentseals2.configurableitems' => $Option->configurableitems, 'setting_intumescentseals2.doorname' => $Option->doorname, 'selected_intumescentseals2.selected_intumescentseals2_user_id' => Auth::user()->id])->first();
                    }

                    if (!empty($optionSelection)) {
                        if (!empty($selectedOption) && !empty($optionSelection)) {
                            echo json_encode(array("status" => "ok", "id" => $optionSelection->id, "SelectedOptionId" => $selectedOption->SelectedOptionId, "optionType" => 'intumescentSealArrangement', "brand" => $selectedOption->brand, "intumescentSeals" => $selectedOption->selected_intumescentSeals));
                        } elseif (empty($selectedOption)) {
                            echo json_encode(array("status" => "ok", "id" => $optionSelection->id, "optionType" => 'intumescentSealArrangement'));
                        } else {
                            echo json_encode(array("status" => "ok", "SelectedOptionId" => $selectedOption->SelectedOptionId, "optionType" => 'intumescentSealArrangement', "brand" => $selectedOption->brand, "intumescentSeals" => $selectedOption->selected_intumescentSeals));
                        }
                    } else {
                        echo json_encode(array("status" => "error", "msg" => "Id not found"));
                    }
                } else {
                    echo json_encode(array("status" => "error", "msg" => "Id not found"));
                }
                break;
        }
    }

    public function importglassglazing(Request $request){
        $UserId = Auth::user()->id;
        if($UserId == 1){
            $data = Excel::toArray(new DoorScheduleImport, request()->file('ExcelFile'));
            $i = 0;
            foreach($data[0] as $row){

                if($i == 0){
                    $i++;
                    continue;
                }

                $j = 0;
                $glasstype = trim($row[$j++]);
                $glazing = trim($row[$j++]);
                $vpareasize = trim($row[$j++]);
                if($vpareasize != "N/A"){
                    $GlassType = GlassType::where('GlassType',$glasstype)->where('status',1)->where('Flamebreak',7)->first();
                    $GlazingSystem = GlazingSystem::where('GlazingSystem',$glazing)->where('status',1)->where('Flamebreak',7)->first();
                    if(!empty($GlassType) && !empty($GlazingSystem)){
                        $data = new GlassGlazingSystem();
                        $data->Configurableitems = intval(7);
                        $data->glass_id = $GlassType->id;
                        $data->glazing_system = $GlazingSystem->id;
                        $data->GlassType = $GlassType->GlassType;
                        $data->GlazingSystem = $GlazingSystem->GlazingSystem;
                        $data->VPAreaSize = $vpareasize;
                        $data->UserId = Auth::user()->id;
                        $data->save();
                    }
                }
            }
            return redirect()->back()->with('success',"Excel file imported successfully");
        }
    }
    public function importglasstype(Request $request){
        $UserId = Auth::user()->id;
        if($UserId == 1){
            $data = Excel::toArray(new DoorScheduleImport, request()->file('ExcelFile'));
            $i = 0;
            foreach($data[0] as $row){

                if($i == 0){
                    $i++;
                    continue;
                }

                $j = 0;
                $glasstype = trim($row[$j++]);
                $thickness = trim($row[$j++]);
                $integrity = trim($row[$j++]);
                $vpareasize = trim($row[$j++]);
                $configurable = trim($row[$j++]);
                $FireRating = trim($row[$j++]);
                $glazingBead = trim($row[$j++]);

                $implode = json_encode(explode(',',$glazingBead));

                $key = str_replace(' ', '_', $glasstype);

                $data = new GlassType();

                $data->Streboard = NULL;$data->Halspan = NULL;$data->NormaDoorCore = NULL;$data->VicaimaDoorCore = NULL;$data->Seadec = NULL;$data->Deanta = NULL;
                if($configurable == '1'){
                    $data->Streboard = 1;
                }
                if($configurable == '2'){
                    $data->Halspan = 2;
                }
                if($configurable == '7'){
                    $data->Flamebreak = 7;
                }
                if($configurable == '8'){
                    $data->Stredor = 8;
                }
                $data->NFR = NULL;

                if($FireRating == 'FD30'){
                    $data->FD30 = 'FD30';
                }
                if($FireRating == 'FD60'){
                    $data->FD60 = 'FD60';
                }

                $data->Key = $key;
                $data->GlassType = $glasstype;
                $data->GlassThickness = $thickness;
                $data->VpAreaSize = $vpareasize;
                $data->GlassIntegrity = $integrity;
                $data->GlazingBeads = $implode;
                $data->EditBy = Auth::user()->id;
                $data->save();
            }
            return redirect()->back()->with('success',"Excel file imported successfully");
        }
    }
    public function importglazingsystem(Request $request){
        $UserId = Auth::user()->id;
        if($UserId == 1){
            $data = Excel::toArray(new DoorScheduleImport, request()->file('ExcelFile'));
            $i = 0;
            foreach($data[0] as $row){

                if($i == 0){
                    $i++;
                    continue;
                }


                $j = 0;
                $glazing = trim($row[$j++]);
                $thickness = trim($row[$j++]);
                $fixingDetails = trim($row[$j++]);
                $FireRating = trim($row[$j++]);
                $configurable = trim($row[$j++]);

                $key = str_replace(' ', '_', $glazing);

                $data = new GlazingSystem();
                $data->Streboard = NULL;$data->Halspan = NULL;$data->Flamebreak = NULL;$data->NormaDoorCore = NULL;$data->VicaimaDoorCore = NULL;$data->Seadec = NULL;$data->Deanta = NULL;$data->Stredor = NULL;

                if($configurable == '1'){
                    $data->Streboard = 1;
                }
                if($configurable == '2'){
                    $data->Halspan = 2;
                }
                if($configurable == '7'){
                    $data->Flamebreak = 7;
                }
                if($configurable == '8'){
                    $data->Stredor = 8;
                }

                $data->NFR = NULL;

                if($FireRating == 'FD30'){
                    $data->FD30 = 'FD30';
                }
                if($FireRating == 'FD60'){
                    $data->FD60 = 'FD60';
                }

                $data->Key = $key;
                $data->GlazingSystem = $glazing;
                $data->GlazingThickness = $thickness;
                $data->GlazingBeadFixingDetail = $fixingDetails;
                $data->VpAreaSize = 0;
                $data->editBy = Auth::user()->id;
                $data->save();
            }
            return redirect()->back()->with('success',"Excel file imported successfully");
        }
    }

    public function updateintumescentSealArrangement(Request $request){
        $UserId = Auth::user()->id;
        if($UserId == 1){
            $data = Excel::toArray(new DoorScheduleImport, request()->file('ExcelFile'));
            $i = 0;
            foreach($data[0] as $row){

                if($i == 0){
                    $i++;
                    continue;
                }

                $j = 0;
                $DoorType = trim($row[$j++]);
                $FireRating = trim($row[$j++]);
                $Configuration = trim($row[$j++]);
                $intumescentSeals = trim($row[$j++]);
                $brand = trim($row[$j++]);
                $firetested = trim($row[$j++]);
                $Point1height = trim($row[$j++]);
                $Point2height = trim($row[$j++]);
                $Point1width = trim($row[$j++]);
                $Point2width = trim($row[$j++]);
                $MeetingEdges = trim($row[$j++]);
                $leaftype1 = trim($row[$j++]);
                $leaftype2 = trim($row[$j++]);

                $doorConfiguration = "";
                if(strcasecmp($DoorType,"Streboard") == 0){
                    $doorConfiguration = 1;
                }elseif(strcasecmp($DoorType,"Halspan") == 0){
                    $doorConfiguration = 2;
                }elseif(strcasecmp($DoorType,"Norma") == 0){
                    $doorConfiguration = 3;
                }elseif(strcasecmp($DoorType,"Vicaima") == 0){
                    $doorConfiguration = 4;
                }elseif(strcasecmp($DoorType,"Seadec") == 0){
                    $doorConfiguration = 5;
                }elseif(strcasecmp($DoorType,"Deanta") == 0){
                    $doorConfiguration = 6;
                 }elseif(strcasecmp($DoorType,"Flamebreak") == 0){
                    $doorConfiguration = 7;
                }
                elseif(strcasecmp($DoorType,"Stredor") == 0){
                    $doorConfiguration = 8;
                }

                $leafType = [];
                if(!empty($leaftype1)){
                    $IntumescentSealLeafType1 = IntumescentSealLeafType::where('leaf_type_key', 'like', '%' . $leaftype1 . '%')->where('status',1)->where('configurableitems',$doorConfiguration)->first();
                    $leafType[] = (!empty($IntumescentSealLeafType1))?$IntumescentSealLeafType1->id:'';
                }
                if(!empty($leaftype2)){
                    $IntumescentSealLeafType2 = IntumescentSealLeafType::where('leaf_type_key', 'like', '%' . $leaftype2 . '%')->where('status',1)->where('configurableitems',$doorConfiguration)->first();
                    $leafType[] = (!empty($IntumescentSealLeafType2))?$IntumescentSealLeafType2->id:'';
                }
                if(!empty($leaftype3)){
                    $IntumescentSealLeafType3 = IntumescentSealLeafType::where('leaf_type_key', 'like', '%' . $leaftype3 . '%')->where('status',1)->where('configurableitems',$doorConfiguration)->first();
                    $leafType[] = (!empty($IntumescentSealLeafType3))?$IntumescentSealLeafType3->id:'';
                }
                if(!empty($leaftype4)){
                    $IntumescentSealLeafType4 = IntumescentSealLeafType::where('leaf_type_key', 'like', '%' . $leaftype4 . '%')->where('status',1)->where('configurableitems',$doorConfiguration)->first();
                    $leafType[] = (!empty($IntumescentSealLeafType4))?$IntumescentSealLeafType4->id:'';
                }
                if(!empty($leaftype5)){
                    $IntumescentSealLeafType5 = IntumescentSealLeafType::where('leaf_type_key', 'like', '%' . $leaftype5 . '%')->where('status',1)->where('configurableitems',$doorConfiguration)->first();
                    $leafType[] = (!empty($IntumescentSealLeafType5))?$IntumescentSealLeafType5->id:'';
                }
                if(!empty($leaftype6)){
                    $IntumescentSealLeafType6 = IntumescentSealLeafType::where('leaf_type_key', 'like', '%' . $leaftype6 . '%')->where('status',1)->where('configurableitems',$doorConfiguration)->first();
                    $leafType[] = (!empty($IntumescentSealLeafType6))?$IntumescentSealLeafType6->id:'';
                }

                $fireRating = "";
                if(strcasecmp($FireRating,"FD30") == 0){
                    $fireRating = "FD30";
                }elseif(strcasecmp($FireRating,"FD60") == 0){
                    $fireRating = "FD60";
                }elseif(strcasecmp($FireRating,"NFR") == 0){
                    $fireRating = "NFR";
                }

                $a = new SettingIntumescentSeals2;
                $a->created_at = date('Y-m-d H:i:s');
                $a->configurableitems = $doorConfiguration;
                $a->firerating = $fireRating;
                $a->tag = $fireRating;
                $a->configuration = $Configuration;
                $a->intumescentSeals = $intumescentSeals;
                $a->brand = $brand;
                $a->firetested = $firetested;
                $a->Point1height = $Point1height;
                $a->Point2height = $Point2height;
                $a->Point1width = $Point1width;
                $a->Point2width = $Point2width;
                $a->MeetingEdges = $MeetingEdges;
                $a->customeleafTypes = implode(',', $leafType);
                $a->updated_at = date('Y-m-d H:i:s');
                $a->editBy = Auth::user()->id;
                $a->save();

                $selectedOption = new SelectedIntumescentSeals2();
                $selectedOption->selected_configurableitems = $a->configurableitems;
                $selectedOption->intumescentseals2_id = $a->id;
                $selectedOption->selected_intumescentseals2_user_id = Auth::user()->id;
                $selectedOption->selected_configuration = $a->configuration;
                $selectedOption->selected_doorname = $a->doorname;
                $selectedOption->selected_firerating = $a->firerating;
                $selectedOption->selected_tag = $a->tag;
                $selectedOption->selected_intumescentSeals = $a->intumescentSeals;
                $selectedOption->selected_brand = $a->brand;
                $selectedOption->selected_firetested = $a->firetested;
                $selectedOption->selected_Point1height = $a->Point1height;
                $selectedOption->selected_Point1width = $a->Point1width;
                $selectedOption->selected_Point2height = $a->Point2height;
                $selectedOption->selected_Point2width = $a->Point2width;
                $selectedOption->MeetingEdges = $a->MeetingEdges;
                $selectedOption->selected_cost = ($request->IntumescentSealPrice) ?? 0;
                $selectedOption->save();
            }
            return redirect()->back()->with('success',"Excel file imported successfully");
        }
    }
     public function updateOverpanelGlassGlazing(Request $request){
        $UserId = Auth::user()->id;
        if($UserId == 1){
            $data = Excel::toArray(new DoorScheduleImport, request()->file('ExcelFile'));
            $i = 0;
            foreach($data[0] as $row){

                if($i == 0){
                    $i++;
                    continue;
                }

                $j = 0;
                $GlassType = trim($row[$j++]);
                $GlassThickness = trim($row[$j++]);
                $Integrity = trim($row[$j++]);
                $DoorType = trim($row[$j++]);
                $FireRating = trim($row[$j++]);
                $FLWidth = trim($row[$j++]);
                $FLHeight = trim($row[$j++]);
                $SSWidth = trim($row[$j++]);
                $SSheight = trim($row[$j++]);
                $TransomThickness = trim($row[$j++]);
                $TransomDepth = trim($row[$j++]);
                $GlazingSystem = trim($row[$j++]);
                $GlazingThickness = trim($row[$j++]);
                $Beading = trim($row[$j++]);
                $BeadingHeight = trim($row[$j++]);
                $BeadingWidth = trim($row[$j++]);
                $FixingDetails = trim($row[$j++]);

                $a = new OverpanelGlassGlazing;
                $a->created_at = date('Y-m-d H:i:s');
                $a->GlassIntegrity = $Integrity;
                $a->Key = str_replace(' ', '_', $GlassType);
                $a->GlassType = $GlassType;
                $a->GlassThickness = $GlassThickness;
                $a->FanLightWidth = $FLWidth;
                $a->FanLightHeight = $FLHeight;
                $a->SideScreenWidth = $SSWidth;
                $a->SideScreenHeight = $SSheight;
                $a->TransomThickness = $TransomThickness;
                $a->TransomDepth = $TransomDepth;
                $a->GlazingSystem = $GlazingSystem;
                $a->GlazingThickness = $GlazingThickness;
                $a->Beading = $Beading;
                $a->BeadingHeight = $BeadingHeight;
                $a->BeadingWidth = $BeadingWidth;
                $a->FixingDetails = $FixingDetails;
                $a->Streboard = NULL;$a->Halspan = NULL;$a->Flamebreak = NULL;

                if($DoorType == 'Streboard'){
                    $a->Streboard = 1;
                }
                if($DoorType == 'Halspan'){
                    $a->Halspan = 2;
                }
                if($DoorType == 'Flamebreak'){
                    $a->Flamebreak = 7;
                }
                if($DoorType == 'Stredor'){
                    $a->Stredor = 8;
                }
                $a->NFR = NULL;
                if($FireRating == 'FD30'){
                    $a->FD30 = 'FD30';
                }
                if($FireRating == 'FD60'){
                    $a->FD60 = 'FD60';
                }
                $a->updated_at = date('Y-m-d H:i:s');
                $a->editBy = Auth::user()->id;
                $a->save();
            }
            return redirect()->back()->with('success',"Excel file imported successfully");
        }
    }

    public function updatesidescreen(Request $request){
        $UserId = Auth::user()->id;
        if($UserId == 1){
            $data = Excel::toArray(new DoorScheduleImport, request()->file('ExcelFile'));
            $i = 0;
            foreach($data[0] as $row){

                if($i == 0){
                    $i++;
                    continue;
                }

                $j = 0;
                $FireRating = trim($row[$j++]);
                $db = trim($row[$j++]);
                $glasstype = trim($row[$j++]);
                $height1 = trim($row[$j++]);
                $height2 = trim($row[$j++]);
                $width1 = trim($row[$j++]);
                $width2 = trim($row[$j++]);
                $thickness = trim($row[$j++]);
                $depth = trim($row[$j++]);
                $area = trim($row[$j++]);
                $glazing = trim($row[$j++]);
                $glazingThickness = trim($row[$j++]);
                $density = trim($row[$j++]);
                $beading = trim($row[$j++]);
                $beadingH = trim($row[$j++]);
                $beadingW = trim($row[$j++]);
                $fixing = trim($row[$j++]);

                $a = new ScreenGlassType;
                $a->FireRating = $FireRating;
                $a->GlassType = $glasstype;
                $a->DFRating = $db;
                $a->HeightPoint1 = $height1;
                $a->HeightPoint2 = $height2;
                $a->WidthPoint1 = $width1;
                $a->WidthPoint2 = $width2;
                $a->TransomThickness = $thickness;
                $a->TransomDepth = $depth;
                $a->AreaSize = $area;
                $a->FrameDensity = $density;
                $a->EditBy = Auth::user()->id;
                $a->save();

                $b = new ScreenGlazingType;
                $b->FireRating = $FireRating;
                $b->ScreenGlassId = $a->id;
                $b->GlazingSystem = $glazing;
                $b->GlazingThickness = $glazingThickness;
                $b->Beading = $beading;
                $b->BeadingHeight = $beadingH;
                $b->BeadingWidth = $beadingW;
                $b->FixingDetails = $fixing;
                $b->EditBy = Auth::user()->id;
                $b->save();
            }
            return redirect()->back()->with('success',"Excel file imported successfully");
        }
    }

    public function updateGlassType(Request $request){
        // dd($request->all());
        $config = $request->config;
        $firerating = $request->firerating;
        $optionName = $request->optionName;
        switch($optionName){
            case 'LeafType':
                $leafType = $request->leafType;
                $leafPrice = $request->leafPrice;
                $key = str_replace(' ', '_', $leafType);
                if((isset($request->vicaimaDoorCore) || isset($request->normaDoorCore) || isset($request->seadecDoorCore) || isset($request->deantaDoorCore)) &&  !empty($leafType)){

                    if(!empty($request->id)){
                        $data = LeafType::find($request->id);
                        if(!empty($request->selectId)){
                            $selectedOption = SelectedLeafType::find($request->selectId);
                        }else{
                            $selectedOption = new SelectedLeafType();
                        }

                    }else{
                        $data = new LeafType();
                        $selectedOption = new SelectedLeafType();
                    }
                    $data->NormaDoorCore = NULL;$data->VicaimaDoorCore = NULL;$data->Seadec = NULL;$data->Deanta = NULL;

                    if(isset($request->normaDoorCore)){
                        $data->NormaDoorCore = 3;
                    }
                     if(isset($request->vicaimaDoorCore)){
                        $data->VicaimaDoorCore = 4;
                    }
                    if(isset($request->seadecDoorCore)){
                        $data->Seadec = 5;
                    }
                    if(isset($request->deantaDoorCore)){
                        $data->Deanta = 6;
                    }
                    $data->Key = $key;
                    $data->UnderAttribute = $key;
                    $data->LeafType = $leafType;
                    $data->EditBy = Auth::user()->id;
                    $data->save();

                    if(Auth::user()->id != 1){ //admin will not add selected price
                        $selectedOption->leaf_id = $data->id;
                        if(empty($data->id) && empty($request->selectId)){
                            $selectedOption->leaf_id = $request->id;
                        }
                        $selectedOption->selectedPrice = $leafPrice;
                        $selectedOption->editBy = Auth::user()->id;
                        $selectedOption->save();
                    }

                    $request->session()->flash('success',"Leaf Type added successfully!");
                    return redirect('options/selected/leaf_type')->with('success', 'Leaf Type added successfully!');
                }else{
                    $request->session()->flash('error',"Something went wrong!");
                    return redirect('options/selected/leaf_type')->with('error', 'Something went wrong!');
                }

            break;
            case 'Glass':
                $integrity = $request->integrity;
                $glassType = $request->glassType;
                $glassThickness = $request->glassThickness;
                $glassPrice = $request->glassPrice;
                $key = str_replace(' ', '_', $glassType);
                if(!empty($config[0]) && !empty($firerating[0]) &&  !empty($integrity)  &&  !empty($glassType) && !empty($glassThickness)){

                    if(!empty($request->id)){
                        $data = GlassType::find($request->id);
                        if(!empty($request->selectId)){
                            $selectedOption = SelectedGlassType::find($request->selectId);
                        }else{
                            $selectedOption = new SelectedGlassType();
                        }

                    }else{
                        $data = new GlassType();
                        $selectedOption = new SelectedGlassType();
                    }
                    $data->Streboard = NULL;$data->Halspan = NULL;$data->NormaDoorCore = NULL;$data->VicaimaDoorCore = NULL;$data->Seadec = NULL;$data->Deanta = NULL;$data->Flamebreak = NULL;$data->Stredor = NULL;
                    for($m = 0; $m < count($config); $m++){
                        if($config[$m] == '1'){
                            $data->Streboard = 1;
                        }
                        if($config[$m] == '2'){
                            $data->Halspan = 2;
                        }
                        if($config[$m] == '3'){
                            $data->NormaDoorCore = 3;
                        }
                        if($config[$m] == '4'){
                            $data->VicaimaDoorCore = 4;
                        }
                        if($config[$m] == '5'){
                            $data->Seadec = 5;
                        }
                        if($config[$m] == '6'){
                            $data->Deanta = 6;
                        }
                        if($config[$m] == '7'){
                            $data->Flamebreak = 7;
                        }
                        if($config[$m] == '8'){
                            $data->Stredor = 8;
                        }
                    }
                    $data->NFR = NULL;$data->FD30 = NULL;$data->FD60 = NULL;
                    for($n = 0; $n < count($firerating); $n++){
                        if($firerating[$n] == 'NFR'){
                            $data->NFR = 'NFR';
                        }
                        if($firerating[$n] == 'FD30'){
                            $data->FD30 = 'FD30';
                        }
                        if($firerating[$n] == 'FD60'){
                            $data->FD60 = 'FD60';
                        }
                    }
                    $glazingBeads = json_encode($request->glazingBeads);
                    $data->Key = $key;
                    $data->GlassType = $glassType;
                    $data->GlassThickness = $glassThickness;
                    $data->VpAreaSize = $request->vpareasize;
                    $data->GlassIntegrity = $integrity;
                    $data->GlazingBeads = $glazingBeads;
                    $data->EditBy = Auth::user()->id;
                    $data->save();

                    if(Auth::user()->id != 1){ //admin will not add selected price
                        $selectedOption->glass_id = $data->id;
                        if(empty($data->id) && empty($request->selectId)){
                            $selectedOption->glass_id = $request->id;
                        }
                        $selectedOption->selectedPrice = $glassPrice;
                        $selectedOption->editBy = Auth::user()->id;
                        $selectedOption->save();
                    }
                    if($request->glassTypeurl == 'leaf1_glass_type_custome'){
                        $request->session()->flash('success',"Glass Type added successfully!");
                        return redirect('options/selected/leaf1_glass_type_custome')->with('success', 'Glass Type added successfully!');
                    }
                    else{
                        $request->session()->flash('success',"Glass Type added successfully!");
                        return redirect('options/selected/leaf1_glass_type')->with('success', 'Glass Type added successfully!');
                    }

                }else{
                    if($request->glassTypeurl == 'leaf1_glass_type_custome'){
                        $request->session()->flash('success',"Glass Type added successfully!");
                        return redirect('options/selected/leaf1_glass_type_custome')->with('success', 'Glass Type added successfully!');
                    }
                    else{
                        $request->session()->flash('success',"Glass Type added successfully!");
                        return redirect('options/selected/leaf1_glass_type')->with('success', 'Glass Type added successfully!');
                    }
                }

            break;
            case 'Glazing':
                $glazingSystem = $request->glazingSystem;
                $glazingPrice = $request->glazingPrice;
                $key = str_replace(' ', '_', $glazingSystem);
                if(!empty($config[0]) && !empty($firerating[0]) &&  !empty($glazingSystem)){
                    if(!empty($request->id)){
                        //update in glazing system and selectedglazingsystem table
                        $data = GlazingSystem::find($request->id);
                        if(!empty($request->selectId)){
                            $selectedOption = SelectedGlazingSystem::find($request->selectId);
                        }else{
                            $selectedOption = new SelectedGlazingSystem();
                        }

                    }else{
                        //insert data in glazing system table
                        $data = new GlazingSystem();
                        //insert into selectedglazingsystem table
                        $selectedOption = new SelectedGlazingSystem();
                    }
                    $data->Streboard = NULL;$data->Halspan = NULL;$data->NormaDoorCore = NULL;$data->VicaimaDoorCore = NULL;$data->Seadec = NULL;$data->Deanta = NULL;$data->Flamebreak = NULL;$data->Stredor = NULL;
                    for($m = 0; $m < count($config); $m++){
                        if($config[$m] == '1'){
                            $data->Streboard = 1;
                        }
                        if($config[$m] == '2'){
                            $data->Halspan = 2;
                        }
                        if($config[$m] == '3'){
                            $data->NormaDoorCore = 3;
                        }
                        if($config[$m] == '4'){
                            $data->VicaimaDoorCore = 4;
                        }
                        if($config[$m] == '5'){
                            $data->Seadec = 5;
                        }
                        if($config[$m] == '6'){
                            $data->Deanta = 6;
                        }
                        if($config[$m] == '7'){
                            $data->Flamebreak = 7;
                        }
                        if($config[$m] == '8'){
                            $data->Stredor = 8;
                        }
                    }
                    $data->NFR = NULL;$data->FD30 = NULL;$data->FD60 = NULL;
                    for($n = 0; $n < count($firerating); $n++){
                        if($firerating[$n] == 'NFR'){
                            $data->NFR = 'NFR';
                        }
                        if($firerating[$n] == 'FD30'){
                            $data->FD30 = 'FD30';
                        }
                        if($firerating[$n] == 'FD60'){
                            $data->FD60 = 'FD60';
                        }
                    }

                    $data->Key = $key;
                    $data->GlazingSystem = $glazingSystem;
                    $data->GlazingThickness = $request->GlazingThickness;
                    $data->GlazingBeadFixingDetail = $request->GlazingBeadFixingDetail;
                    $data->VpAreaSize = $request->vpareasize;
                    $data->editBy = Auth::user()->id;
                    $data->save();

                    if(Auth::user()->id != 1){ //admin will not add selected price
                        $selectedOption->glazingId = $data->id;
                        if(empty($data->id) && empty($request->selectId)){
                            $selectedOption->glazingId = $request->id;
                        }
                        $selectedOption->selectedPrice = $glazingPrice;
                        $selectedOption->userId = Auth::user()->id;
                        $selectedOption->save();
                    }
                    if($request->glassTypeurl == 'leaf1_glazing_systems_custome'){
                        $request->session()->flash('success',"Glazing System added successfully!");
                        return redirect('options/selected/leaf1_glazing_systems_custome')->with('success', 'Glazing System added successfully!');
                    }
                    else{
                        $request->session()->flash('success',"Glazing System added successfully!");
                        return redirect('options/selected/leaf1_glazing_systems')->with('success', 'Glazing System added successfully!');
                    }
                }else{
                    $request->session()->flash('error',"Something went wrong!");
                    return redirect('options/selected/leaf1_glazing_systems')->with('error', 'Something went wrong!');
                }

            break;
            case 'GlassGlazing':
                $glazingSystem = $request->glazingSystem;
                $GlassType = $request->GlassType;
                $config = configurationDoor($request->config);
                if(!empty($request->config) && !empty($request->vpareasize) && !empty($GlassType) &&  !empty($glazingSystem)){
                    if(!empty($request->id)){
                        //update in glazing system and selectedglazingsystem table
                        $data = GlassGlazingSystem::find($request->id);

                    }else{
                        //insert data in glazing system table
                        $data = new GlassGlazingSystem();
                    }
                    $GlassTypeId = GlassType::where('status',1)->where('GlassType',$GlassType)->first();
                    $GlazingSystemId = GlazingSystem::where('status',1)->where('GlazingSystem',$glazingSystem)->first();

                    $data->Configurableitems = $request->config;
                    $data->glass_id = $GlassTypeId->id;
                    $data->glazing_system = $GlazingSystemId->id;
                    $data->GlassType = $GlassType;
                    $data->GlazingSystem = $glazingSystem;
                    $data->VPAreaSize = $request->vpareasize;
                    $data->UserId = Auth::user()->id;
                    $data->save();

                    $request->session()->flash('success',"Glass Glazing System added successfully!");
                    return redirect('options/filter/leaf1_glazing_systems/' . $config)->with('success', 'Glazing System added successfully!');
                }else{
                    $request->session()->flash('error',"Something went wrong!");
                    return redirect('options/filter/leaf1_glazing_systems/' . $config)->with('error', 'Something went wrong!');
                }

            break;
            case 'IntumescentSealColor':
                $IntumescentSealColorName = $request->IntumescentSealColorName;
                // $IntumescentSealColorPrice = $request->IntumescentSealColorPrice;
                $IntumescentSealColorPrice = 0;
                $key = str_replace(' ', '_', $IntumescentSealColorName);
                if(!empty($config[0]) &&  !empty($IntumescentSealColorName)){

                        if(!empty($request->id)){
                            //update in IntumescentSealColor and selectedIntumescentSealColor table
                            $data = IntumescentSealColor::find($request->id);
                            if(!empty($request->selectId)){
                                $selectedOption = SelectedIntumescentSealColor::find($request->selectId);
                            }else{
                                $selectedOption = new SelectedIntumescentSealColor();
                            }

                        }else{
                            //insert data in IntumescentSealColor system table
                            $data = new IntumescentSealColor();
                            //insert into SelectedIntumescentSealColor table
                            $selectedOption = new SelectedIntumescentSealColor();
                        }
                        $data->Streboard = NULL;$data->Halspan = NULL;$data->NormaDoorCore = NULL;$data->VicaimaDoorCore = NULL;$data->Seadec = NULL;$data->Deanta = NULL;$data->Flamebreak = NULL;$data->Stredor = NULL;
                        for($m = 0; $m < count($config); $m++){
                            if($config[$m] == '1'){
                                $data->Streboard = 1;
                            }
                            if($config[$m] == '2'){
                                $data->Halspan = 2;
                            }
                            if($config[$m] == '3'){
                                $data->NormaDoorCore = 3;
                            }
                             if($config[$m] == '5'){
                                $data->Seadec = 5;
                            }
                            if($config[$m] == '6'){
                                $data->Deanta = 6;
                            }
                            if($config[$m] == '7'){
                                $data->Flamebreak = 7;
                            }
                            if($config[$m] == '8'){
                                $data->Stredor = 8;
                            }
                        }

                        $data->Key = $key;
                        $data->IntumescentSealColor = $IntumescentSealColorName;
                        $data->editBy = Auth::user()->id;
                        $data->save();

                        if(Auth::user()->id != 1){ //admin will not add selected price
                            $selectedOption->intumescentSealColorId = $data->id;
                            if(empty($data->id) && empty($request->selectId)){
                                $selectedOption->intumescentSealColorId = $request->id;
                            }
                            $selectedOption->selectedPrice = $IntumescentSealColorPrice;
                            $selectedOption->userId = Auth::user()->id;
                            $selectedOption->save();
                        }

                    $request->session()->flash('success',"Intumescent Seal Color added successfully!");
                    return redirect('options/selected/Intumescent_Seal_Color')->with('success', 'Intumescent Seal Color added successfully!');
                }else{
                    $request->session()->flash('error',"Something went wrong!");
                    return redirect('options/selected/Intumescent_Seal_Color')->with('error', 'Something went wrong!');
                }

            break;
            case 'Architrave_Type':
                $ArchitraveTypeName = $request->ArchitraveTypeName;
                $ArchitraveTypePrice = $request->ArchitraveTypePrice;
                $key = str_replace(' ', '_', $ArchitraveTypeName);
                if(!empty($config[0]) &&  !empty($ArchitraveTypeName)){

                        if(!empty($request->id)){
                            //update in ArchitraveType and selectedArchitraveType table
                            $data = ArchitraveType::find($request->id);
                            if(!empty($request->selectId)){
                                $selectedOption = SelectedArchitraveType::find($request->selectId);
                            }else{
                                $selectedOption = new SelectedArchitraveType();
                            }

                        }else{
                            //insert data in ArchitraveType system table
                            $data = new ArchitraveType();
                            //insert into SelectedArchitraveType table
                            $selectedOption = new SelectedArchitraveType();
                        }
                        $data->Streboard = NULL;$data->Halspan = NULL;$data->NormaDoorCore = NULL;$data->VicaimaDoorCore = NULL;$data->Seadec = NULL;$data->Deanta = NULL;$data->Flamebreak = NULL;$data->Stredor = NULL;
                        for($m = 0; $m < count($config); $m++){
                            if($config[$m] == '1'){
                                $data->Streboard = 1;
                            }
                            if($config[$m] == '2'){
                                $data->Halspan = 2;
                            }
                            if($config[$m] == '3'){
                                $data->NormaDoorCore = 3;
                            }
                            if($config[$m] == '4'){
                                $data->VicaimaDoorCore = 4;
                            }
                            if($config[$m] == '5'){
                                $data->Seadec = 5;
                            }
                            if($config[$m] == '6'){
                                $data->Deanta = 6;
                            }
                            if($config[$m] == '7'){
                                $data->Flamebreak = 7;
                            }
                            if($config[$m] == '8'){
                                $data->Stredor = 8;
                            }
                        }

                        $data->Key = $key;
                        $data->ArchitraveType = $ArchitraveTypeName;
                        $data->editBy = Auth::user()->id;
                        $data->save();

                        if(Auth::user()->id != 1){ //admin will not add selected price
                            $selectedOption->architraveTypeId = $data->id;
                            if(empty($data->id) && empty($request->selectId)){
                                $selectedOption->architraveTypeId = $request->id;
                            }
                            $selectedOption->selectedPrice = $ArchitraveTypePrice;
                            $selectedOption->userId = Auth::user()->id;
                            $selectedOption->save();
                        }

                    $request->session()->flash('success',"Architrave Type added successfully!");
                    return redirect('options/selected/Architrave_Type')->with('success', ' Architrave Type added successfully!');
                }else{
                    $request->session()->flash('error',"Something went wrong!");
                    return redirect('options/selected/Architrave_Type')->with('error', 'Something went wrong!');
                }

            break;
            case 'Accoustics':
                $AccousticsOption = $request->AccousticsOption;
                $AccousticsName = $request->AccousticsName;
                $AccousticsPrice = $request->AccousticsPrice;
                $key = str_replace(' ', '_', $AccousticsName);
                $image = $request->image;
                if (!empty($image)) {
                    $imageName = time() . '.' . $image->getClientOriginalExtension();

                    $image->move(public_path('uploads/Options'), $imageName);
                }

                if(!empty($config[0]) &&  !empty($AccousticsOption)  &&  !empty($AccousticsName)){
                    if(!empty($request->id)){
                        //update in Accoustics and SelectedAccoustics table
                        $data = Accoustics::find($request->id);
                        if(!empty($request->selectId)){
                            $selectedOption = SelectedAccoustics::find($request->selectId);
                        }else{
                            $selectedOption = new SelectedAccoustics();
                        }

                    }else{
                        //insert data in Accoustics system table
                        $data = new Accoustics();
                        //insert into SelectedAccoustics table
                        $selectedOption = new SelectedAccoustics();
                    }
                    $data->Streboard = NULL;$data->Halspan = NULL;$data->NormaDoorCore = NULL;$data->VicaimaDoorCore = NULL;$data->Seadec = NULL;$data->Deanta = NULL;$data->Flamebreak = NULL;$data->Stredor = NULL;
                    for($m = 0; $m < count($config); $m++){
                        if($config[$m] == '1'){
                            $data->Streboard = 1;
                        }
                        if($config[$m] == '2'){
                            $data->Halspan = 2;
                        }
                        if($config[$m] == '3'){
                            $data->NormaDoorCore = 3;
                        }
                        if($config[$m] == '4'){
                            $data->VicaimaDoorCore = 4;
                        }
                        if($config[$m] == '5'){
                            $data->Seadec = 5;
                        }
                        if($config[$m] == '6'){
                            $data->Deanta = 6;
                        }
                        if($config[$m] == '7'){
                            $data->Flamebreak = 7;
                        }
                        if($config[$m] == '8'){
                            $data->Stredor = 8;
                        }
                    }

                    $data->Key = $key;
                    $data->UnderAttribute = $AccousticsOption;
                    $data->Accoustics = $AccousticsName;
                    if(!empty($imageName)){
                        $data->file = $imageName;
                    }
                    $data->editBy = Auth::user()->id;
                    $data->save();

                    if(Auth::user()->id != 1){ //admin will not add selected price
                        $selectedOption->accousticsId = $data->id;
                        if(empty($data->id) && empty($request->selectId)){
                            $selectedOption->accousticsId = $request->id;
                        }
                        $selectedOption->selectedPrice = $AccousticsPrice;
                        $selectedOption->userId = Auth::user()->id;
                        $selectedOption->save();
                    }
                    $request->session()->flash('success',"Accoustics added successfully!");
                    return redirect('options/selected/Accoustics')->with('success', 'Accoustics added successfully!');
                }else{
                    $request->session()->flash('error',"Something went wrong!");
                    return redirect('options/selected/Accoustics')->with('error', 'Something went wrong!');
                }

            break;

            case 'DoorLeafFacing':

                $DoorLeafOption = $request->DoorLeafOption;
                $DoorLeafFacingName = $request->DoorLeafFacingName;
                $DoorLeafFacingPrice = $request->DoorLeafFacingPrice;
                $key = str_replace(' ', '_', $DoorLeafFacingName);

                if( !empty($request->Streboard || $request->Halspan || $request->NormaDoorCore || $request->VicaimaDoorCore || $request->SeadecDoorCore || $request->deantaDoorCore || $request->Flamebreak || $request->Stredor) && !empty($DoorLeafOption)  &&  !empty($DoorLeafFacingName)){
                    if(!empty($request->id)){
                        //update in DoorLeafFacing and SelectedDoorLeafFacing table
                        $data = DoorLeafFacing::find($request->id);
                        if(!empty($request->selectId)){
                            $selectedOption = SelectedDoorLeafFacing::find($request->selectId);
                        }else{
                            $selectedOption = new SelectedDoorLeafFacing();
                        }

                    }else{
                        //insert data in DoorLeafFacing system table
                        $data = new DoorLeafFacing();
                        //insert into SelectedDoorLeafFacing table
                        $selectedOption = new SelectedDoorLeafFacing();
                    }
                    $data->Streboard = NULL;$data->Halspan = NULL;$data->NormaDoorCore = NULL;$data->VicaimaDoorCore = NULL;$data->Seadec = NULL;$data->Deanta = NULL;$data->Flamebreak = NULL;$data->Stredor = NULL;

                    if(isset($request->Streboard)){
                        $data->Streboard = 1;
                    }
                     if(isset($request->Halspan)){
                        $data->Halspan = 2;
                    }
                    if(isset($request->NormaDoorCore)){
                        $data->NormaDoorCore = 3;
                    }
                    if(isset($request->VicaimaDoorCore)){
                        $data->VicaimaDoorCore = 4;
                    }
                    if(isset($request->SeadecDoorCore)){
                        $data->Seadec = 5;
                    }
                    if(isset($request->deantaDoorCore)){
                        $data->Deanta = 6;
                    }
                    if(isset($request->Flamebreak)){
                        $data->Flamebreak = 7;
                    }
                    if(isset($request->Stredor)){
                        $data->Stredor = 8;
                    }

                    $data->Key = $key;
                    $data->doorLeafFacing = $DoorLeafOption;
                    $data->doorLeafFacingValue = $DoorLeafFacingName;
                    $data->editBy = Auth::user()->id;
                    $data->save();

                    if(Auth::user()->id != 1){ //admin will not add selected price
                        $selectedOption->doorLeafFacingId = $data->id;
                        if(empty($data->id) && empty($request->selectId)){
                            $selectedOption->doorLeafFacingId = $request->id;
                        }
                        $selectedOption->selectedPrice = $DoorLeafFacingPrice;
                        $selectedOption->userId = Auth::user()->id;
                        $selectedOption->save();
                    }

                    $request->session()->flash('success',"Door Leaf Facing added successfully!");
                    return redirect('options/selected/door_leaf_facing_value')->with('success', 'Accoustics added successfully!');
                }else{
                    $request->session()->flash('error',"Something went wrong!");
                    return redirect('options/selected/door_leaf_facing_value')->with('error', 'Something went wrong!');
                }

            break;
            case 'color':
                $DoorLeafFacing = $request->DoorLeafFacing;
                $ColorName = $request->ColorName;
                $Hex = $request->Hex;
                $colorPrice = $request->colorPrice;

                if(!empty($DoorLeafFacing)  &&  !empty($ColorName) &&  !empty($Hex)){
                    if(!empty($request->id)){
                        //update in Color and SelectedColor table
                        $data = Color::find($request->id);
                        if(!empty($request->selectId)){
                            $selectedOption = SelectedColor::find($request->selectId);
                        }else{
                            $selectedOption = new SelectedColor();
                        }

                    }else{
                        //insert data in Color system table
                        $data = new Color();
                        //insert into SelectedColor table
                        $selectedOption = new SelectedColor();
                    }

                    $data->ColorName = $ColorName;
                    $data->DoorLeafFacing = $DoorLeafFacing;
                    if(!empty($request->DoorLeafFacingval)){
                        $data->DoorLeafFacingValue = $request->DoorLeafFacingval;
                    }
                    $data->Hex = $Hex;
                    $data->editBy = Auth::user()->id;
                    $data->save();

                    $selectedOption->SelectedColorId = $data->id;
                    if(empty($data->id) && empty($request->selectId)){
                        $selectedOption->SelectedColorId = $request->id;
                    }
                    $selectedOption->SelectedPrice = $colorPrice;
                    $selectedOption->DoorLeafFacingName = $DoorLeafFacing;
                    $selectedOption->SelectedUserId = Auth::user()->id;
                    $selectedOption->save();


                    $request->session()->flash('success',"Color added successfully!");
                    return redirect('options/selected1/color_list/'.$DoorLeafFacing)->with('success', 'color added successfully!');
                }else{
                    $request->session()->flash('error',"Something went wrong!");
                    return redirect('options/selected1/color_list/'.$DoorLeafFacing)->with('error', 'Something went wrong!');
                }

            break;
            case 'Overpanel_Glass_Type':
                $GlassIntegrity = $request->GlassIntegrity;
                $GlassType = $request->GlassType;
                $GlassThickness = $request->GlassThickness;
                $FanLightWidth = $request->FanLightWidth;
                $FanLightHeight = $request->FanLightHeight;
                $SideScreenWidth = $request->SideScreenWidth;
                $SideScreenHeight = $request->SideScreenHeight;
                $TransomThickness = $request->TransomThickness;
                $TransomDepth = $request->TransomDepth;
                $glassPrice = $request->glassPrice;
                $key = str_replace(' ', '_', $GlassType);
                if(!empty($config[0]) && !empty($firerating[0]) &&  !empty($GlassIntegrity)  &&  !empty($GlassType)){
                    if(!empty($request->id)){
                        $data = OverpanelGlassGlazing::find($request->id);
                        if(!empty($request->selectId)){
                            $selectedOption = SelectedOverpanelGlassGlazing::find($request->selectId);
                        }else{
                            $selectedOption = new SelectedOverpanelGlassGlazing();
                        }

                    }else{
                        $data = new OverpanelGlassGlazing();
                        $selectedOption = new SelectedOverpanelGlassGlazing();
                    }
                    $data->Streboard = NULL;$data->Halspan = NULL;$data->Flamebreak = NULL;
                    for($m = 0; $m < count($config); $m++){
                        if($config[$m] == '1'){
                            $data->Streboard = 1;
                        }
                        if($config[$m] == '2'){
                            $data->Halspan = 2;
                        }
                        if($config[$m] == '7'){
                            $data->Flamebreak = 7;
                        }
                        if($config[$m] == '8'){
                            $data->Stredor = 8;
                        }
                    }
                    $data->NFR = NULL;$data->FD30 = NULL;$data->FD60 = NULL;
                    for($n = 0; $n < count($firerating); $n++){
                        if($firerating[$n] == 'NFR'){
                            $data->NFR = 'NFR';
                        }
                        if($firerating[$n] == 'FD30'){
                            $data->FD30 = 'FD30';
                        }
                        if($firerating[$n] == 'FD60'){
                            $data->FD60 = 'FD60';
                        }
                    }

                    $data->Key = $key;
                    $data->GlassType = $GlassType;
                    $data->GlassThickness = $GlassThickness;
                    $data->GlassIntegrity = $GlassIntegrity;
                    $data->FanLightWidth = $FanLightWidth;
                    $data->FanLightHeight = $FanLightHeight;
                    $data->SideScreenWidth = $SideScreenWidth;
                    $data->SideScreenHeight = $SideScreenHeight;
                    $data->TransomThickness = $TransomThickness;
                    $data->TransomDepth = $TransomDepth;
                    $data->EditBy = Auth::user()->id;
                    $data->save();


                    if(Auth::user()->id != 1){ //admin will not add selected price
                        $selectedOption->glass_glazing_id = $data->id;
                        if(empty($data->id) && empty($request->selectId)){
                            $selectedOption->glass_glazing_id = $request->id;
                        }
                        $selectedOption->glassSelectedPrice = $glassPrice;
                        $selectedOption->editBy = Auth::user()->id;
                        $selectedOption->save();
                    }


                    $request->session()->flash('success',"Glass Type added successfully!");
                    return redirect('options/selected/Overpanel_Glass_Type')->with('success', 'Glass Type added successfully!');
                }else{
                    $request->session()->flash('error',"Something went wrong!");
                    return redirect('options/selected/Overpanel_Glass_Type')->with('error', 'Something went wrong!');
                }
            break;

            case 'Overpanel_Glazing_System':
                $GlassType = $request->GlassTypeOverpanel;
                $GlazingThickness = $request->GlazingThickness;
                $Beading = $request->Beading;
                $BeadingHeight = $request->BeadingHeight;
                $BeadingWidth = $request->BeadingWidth;
                $GlazingSystem = $request->GlazingSystem;
                $glazingPrice = $request->glazingPrice;
                $FixingDetails = $request->FixingDetails;
                $key = str_replace(' ', '_', $GlassType);
                if(!empty($config[0]) && !empty($firerating[0]) &&  !empty($GlazingSystem)){
                    if(!empty($request->id)){
                        $data = OverpanelGlassGlazing::find($request->id);
                        if(!empty($request->selectId)){
                            $selectedOption = SelectedOverpanelGlassGlazing::find($request->selectId);
                        }else{
                            $selectedOption = new SelectedOverpanelGlassGlazing();
                        }

                    }else{
                        if($GlassType){
                            $data = OverpanelGlassGlazing::where('key',$key)->first();
                            $selectedOption = SelectedOverpanelGlassGlazing::where('glass_glazing_id',$data->id)->first();
                        }
                    }
                    $data->Streboard = NULL;$data->Halspan = NULL;$data->Flamebreak = NULL;
                    for($m = 0; $m < count($config); $m++){
                        if($config[$m] == '1'){
                            $data->Streboard = 1;
                        }
                        if($config[$m] == '2'){
                            $data->Halspan = 2;
                        }
                        if($config[$m] == '7'){
                            $data->Flamebreak = 7;
                        }
                    }
                    $data->NFR = NULL;$data->FD30 = NULL;$data->FD60 = NULL;
                    for($n = 0; $n < count($firerating); $n++){
                        if($firerating[$n] == 'NFR'){
                            $data->NFR = 'NFR';
                        }
                        if($firerating[$n] == 'FD30'){
                            $data->FD30 = 'FD30';
                        }
                        if($firerating[$n] == 'FD60'){
                            $data->FD60 = 'FD60';
                        }
                    }

                    $data->GlazingThickness = $GlazingThickness;
                    $data->GlazingThickness = $GlazingThickness;
                    $data->Beading = $Beading;
                    $data->BeadingHeight = $BeadingHeight;
                    $data->BeadingWidth = $BeadingWidth;
                    $data->GlazingSystem = $GlazingSystem;
                    $data->FixingDetails = $FixingDetails;
                    $data->EditBy = Auth::user()->id;
                    $data->save();


                    if(Auth::user()->id != 1){ //admin will not add selected price
                        $selectedOption->glass_glazing_id = $data->id;
                        if(empty($data->id) && empty($request->selectId)){
                            $selectedOption->glass_glazing_id = $request->id;
                        }
                        $selectedOption->glazingSelectedPrice = $glazingPrice;
                        $selectedOption->editBy = Auth::user()->id;
                        $selectedOption->save();
                    }


                    $request->session()->flash('success',"Glass Type added successfully!");
                    return redirect('options/selected/Overpanel_Glazing_System')->with('success', 'Glass Type added successfully!');
                }else{
                    $request->session()->flash('error',"Something went wrong!");
                    return redirect('options/selected/Overpanel_Glazing_System')->with('error', 'Something went wrong!');
                }
            break;
            case 'SideScreen_Glass_Type':
                $GlassType = $request->GlassType;
                $FireRating = $request->FireRating;
                $DFRating = $request->DFRating;
                $WidthPoint1 = $request->WidthPoint1;
                $HeightPoint1 = $request->HeightPoint1;
                $WidthPoint2 = $request->WidthPoint2;
                $HeightPoint2 = $request->HeightPoint2;
                $TransomThickness = $request->TransomThickness;
                $TransomDepth = $request->TransomDepth;
                $AreaSize = $request->AreaSize;
                $glassPrice = $request->glassPrice;
                $FrameDensity = $request->FrameDensity;
                $key = str_replace(' ', '_', $GlassType);
                if(!empty($GlassType)){
                    if(!empty($request->id)){
                        $data = ScreenGlassType::find($request->id);
                        if(!empty($request->selectId)){
                            $selectedOption = SelectedScreenGlass::find($request->selectId);
                        }else{
                            $selectedOption = new SelectedScreenGlass();
                        }

                    }else{
                        $data = new ScreenGlassType();
                        $selectedOption = new SelectedScreenGlass();
                    }
                    $data->GlassType = $GlassType;
                    $data->DFRating = $DFRating;
                    $data->FireRating = $FireRating;
                    $data->WidthPoint1 = $WidthPoint1;
                    $data->HeightPoint1 = $HeightPoint1;
                    $data->HeightPoint2 = $HeightPoint2;
                    $data->WidthPoint2 = $WidthPoint2;
                    $data->TransomThickness = $TransomThickness;
                    $data->TransomDepth = $TransomDepth;
                    $data->AreaSize = $AreaSize;
                    $data->FrameDensity = $FrameDensity;
                    $data->EditBy = Auth::user()->id;
                    $data->save();


                    if(Auth::user()->id != 1){ //admin will not add selected price
                        $selectedOption->glass_id = $data->id;
                        if(empty($data->id) && empty($request->selectId)){
                            $selectedOption->glass_id = $request->id;
                        }
                        $selectedOption->glassSelectedPrice = $glassPrice;
                        $selectedOption->editBy = Auth::user()->id;
                        $selectedOption->save();
                    }


                    $request->session()->flash('success',"Glass Type added successfully!");
                    return redirect('options/selected/SideScreen_Glass_Type')->with('success', 'Glass Type added successfully!');
                }else{
                    $request->session()->flash('error',"Something went wrong!");
                    return redirect('options/selected/SideScreen_Glass_Type')->with('error', 'Something went wrong!');
                }
            break;

            case 'SideScreen_Glazing_System':
                $FireRating = $request->FireRating;
                $GlassType = $request->GlassTypeSideScreen;
                $GlazingThickness = $request->GlazingThickness;
                $BeadingHeight = $request->BeadingHeight;
                $BeadingWidth = $request->BeadingWidth;
                $GlazingSystem = $request->GlazingSystem;
                $glazingPrice = $request->glazingPrice;
                $FixingDetails = $request->FixingDetails;
                $Beading = $request->Beading;
                $key = str_replace(' ', '_', $GlassType);
                if(!empty($GlazingSystem)){
                    if(!empty($request->id)){
                        $data = ScreenGlazingType::find($request->id);
                        if(!empty($request->selectId)){
                            $selectedOption = SelectedScreenGlazing::find($request->selectId);
                        }else{
                            $selectedOption = new SelectedScreenGlazing();
                        }

                    }else{
                        if($GlassType){
                            $data = new ScreenGlazingType();
                            $selectedOption = new SelectedScreenGlazing();
                        }
                    }
                    $data->FireRating = $FireRating;
                    $data->ScreenGlassId = $GlassType;
                    $data->GlazingThickness = $GlazingThickness;
                    $data->BeadingHeight = $BeadingHeight;
                    $data->BeadingWidth = $BeadingWidth;
                    $data->GlazingSystem = $GlazingSystem;
                    $data->Beading = $Beading;
                    $data->FixingDetails = $FixingDetails;
                    $data->EditBy = Auth::user()->id;
                    $data->save();


                    if(Auth::user()->id != 1){ //admin will not add selected price
                        $selectedOption->glazing_id = $data->id;
                        if(empty($data->id) && empty($request->selectId)){
                            $selectedOption->glazing_id = $request->id;
                        }
                        $selectedOption->glazingSelectedPrice = $glazingPrice;
                        $selectedOption->editBy = Auth::user()->id;
                        $selectedOption->save();
                    }


                    $request->session()->flash('success',"Glass Type added successfully!");
                    return redirect('options/selected/SideScreen_Glazing_System')->with('success', 'Glass Type added successfully!');
                }else{
                    $request->session()->flash('error',"Something went wrong!");
                    return redirect('options/selected/SideScreen_Glazing_System')->with('error', 'Something went wrong!');
                }
            break;
        }

    }

    public function filterGlazingSystem($optionType,$configurableItem)
    {
        $authdata = Auth::user();
        if (Auth::user()->UserType == 2) {
            $UserId = CompanyUsers();
        } else {
            $UserId = ['1'];
        }
        if($configurableItem == 'Halspan'){
            $doorValue = 2;
        }
        elseif($configurableItem == 'Flamebreak'){
            $doorValue = 7;
        }elseif($configurableItem == 'Stredor'){
            $doorValue = 8;
        }elseif($configurableItem == 'Streboard'){
            $doorValue = 1;
        }

        $IntumescentSealsConfiguration = SettingIntumescentSeals2::wherein('editBy', $UserId)->groupBy('configuration')->get();
        $tbl1 = leaf1_glazing_systems_filter($authdata,$optionType,$UserId,$configurableItem);
        $leaftype = GetOptions(['leaf_type.Status' => 1], "join","leaf_type");
        // dd($tbl1);

        $leaftype2 = DB::table('leaf_type')->where('Seadec',5)->get();
        $option_data = Option::where('configurableitems',3)->get();
        $GlassType = GlassType::where('status',1)->where($configurableItem,$doorValue)->get();
        $GlazingSystem = GlazingSystem::where('status',1)->where($configurableItem,$doorValue)->get();
        $intumenseLeafType = IntumescentSealLeafType::where('status',1)->get();
        return view('option/ChooseOptionNew', compact('optionType','tbl1','option_data','IntumescentSealsConfiguration','leaftype','leaftype2','GlassType','GlazingSystem','configurableItem','intumenseLeafType'));
    }

    public function selectOptionNew($optionType){

        $authdata = Auth::user();
        $ConfigurableItems = ConfigurableItems::get();
        $intumenseLeafType = IntumescentSealLeafType::where('status',1)->get();
        $leaftype = GetOptions(['leaf_type.Status' => 1], "join","leaf_type");

         $leaftype2 = DB::table('leaf_type')->where('Seadec',5)->get();
       // dd($leaftype2);


        if (Auth::user()->UserType == 2) {
            // $UserId = ['1', $authdata->id];
            $myAdminGroup = CompanyUsers();
            $UserId = array_merge(['1'], $myAdminGroup);
        } else {
            $UserId = ['1'];
        }

        $IntumescentSealsConfiguration = SettingIntumescentSeals2::wherein('editBy', $UserId)->groupBy('configuration')->get();
        $tbl1 = '';
        $option_data = Option::where('configurableitems',3)->get();
        $GlassType = GlassType::where('status',1)->where('Halspan',2)->get();
        $GlazingSystem = GlazingSystem::where('status',1)->where('Halspan',2)->get();
        $screenGlassType = ScreenGlassType::all();
        switch ($optionType) {
            case 'leaf_type':
                $tbl1 = leaf_type($authdata,$optionType,$UserId);
            break;

            case 'leaf1_glass_type':
                $tbl1 = leaf1_glass_type($authdata,$optionType,$UserId);
            break;

            case 'leaf1_glass_type_custome':
                $tbl1 = leaf1_glass_type_custome($authdata,$optionType,$UserId);
            break;

            //For Glazing System standard
            case 'leaf1_glazing_systems';
                $tbl1 = leaf1_glazing_systems($authdata,$optionType,$UserId);
            break;

            //For Glazing System custome
            case 'leaf1_glazing_systems_custome';
                $tbl1 = leaf1_glazing_systems_custome($authdata,$optionType,$UserId);
            break;

            //For Intumescent_Seal_Color
            case 'Intumescent_Seal_Color';
                $tbl1 = Intumescent_Seal_Color($authdata,$optionType,$UserId);
            break;

            case 'Accoustics';
                $tbl1 = Accoustics($authdata,$optionType,$UserId);
            break;

            case 'door_leaf_facing_value';

                $tbl1 = door_leaf_facing_value($authdata,$optionType,$UserId);
             break;

            case 'Architrave_Type';
                $tbl1 = SelectedArchitraveType($authdata,$optionType,$UserId);
            break;

            case 'door_leaf_finish';
                $tbl1 = door_leaf_finish($authdata,$optionType,$UserId);
            break;

            case 'Architrave_Finish';
                $tbl1 = Architrave_Finish($authdata,$optionType,$UserId);
            break;
            case 'Frame_Finish';
                $tbl1 = Architrave_Finish($authdata,$optionType,$UserId);
            break;

            case 'Door_Leaf_Facing';
                $tbl1 = Door_Leaf_Facing($authdata,$optionType,$UserId);
            break;
            case 'door_dimension';
                $tbl1 = door_dimension($authdata,$optionType,$UserId);
            break;
            case 'door_dimension_custome';
                $tbl1 = door_dimension_custome($authdata,$optionType,$UserId);
            break;
            case 'intumescentSealArrangement';
                $tbl1 = intumescentSealArrangement($authdata,$optionType,$UserId);
            break;
            case 'Overpanel_Glass_Type';
                $tbl1 = Overpanel_Glass_Type($authdata,$optionType,$UserId);
            break;
            case 'Overpanel_Glazing_System';
                $tbl1 = Overpanel_Glazing_System($authdata,$optionType,$UserId);
            break;
            case 'intumescentSealArrangementCustome';
                $tbl1 = intumescentSealArrangementCustome($authdata,$optionType,$UserId);
            break;
            case 'SideScreen_Glass_Type' ;
                $tbl1 = SideScreen_Glass_Type($authdata,$optionType,$UserId);
            break;
            case 'SideScreen_Glazing_System' ;
                $tbl1 = SideScreen_Glazing_System($authdata,$optionType,$UserId);

        }
        return view('option/ChooseOptionNew', compact('optionType','tbl1','option_data','IntumescentSealsConfiguration','leaftype','leaftype2','intumenseLeafType','GlassType','GlazingSystem','screenGlassType'));
    }

    public function colorOptionNew($optionType,$colorType){
        $authdata = Auth::user();
        if (Auth::user()->UserType == 2) {
            $UserId = ['1', $authdata->id];
            $myAdminGroup = CompanyUsers();
            // $UserId = array_merge(['1'], $myAdminGroup);
        } else {
            $UserId = ['1'];
        }
        $leaftype = GetOptions(['leaf_type.Status' => 1], "join","leaf_type");
        $IntumescentSealsConfiguration = SettingIntumescentSeals2::wherein('editBy', $UserId)->groupBy('configuration')->get();
        $intumenseLeafType = IntumescentSealLeafType::where('status',1)->get();
        $ConfigurableItems = ConfigurableItems::get();
        $option_data = Option::where('configurableitems',3)->get();
        if (Auth::user()->UserType == 2) {
            // $UserId = ['1', $authdata->id];
            // $UserId = $UserId;
        } else {
            $UserId = ['1'];
        }
        $tbl1 = '';
        switch ($optionType) {

            case 'color_list';
                $tbl1 = color($authdata,$optionType,$UserId,$colorType);
            break;

        }
        return view('option/ChooseOptionNew', compact('optionType','tbl1','colorType','option_data','IntumescentSealsConfiguration','leaftype','intumenseLeafType'));
    }

    public function getGlazingBeads(Request $request)
    {
        // Get selected configurations and fire ratings from the request
        $configurations = $request->input('configurations', []); // Defaults to an empty array
        $optionsslug = $request->optionsslug; // Defaults to an empty array

        // Fetch glazing beads based on the selected configurations and fire ratings
        $glazingBeads = DB::table('options')
                        ->whereIn('configurableitems', $configurations)
                        ->where('OptionSlug', $optionsslug)
                        ->get(['id', 'OptionKey', 'OptionValue']); // Adjust to fetch the necessary fields

        return response()->json($glazingBeads);
    }

    public function glassconfigvalue(Request $request){
        $configurationDoor = configurationDoor($request->confi);
        $data['GlassType'] = GlassType::where('status',1)->where($configurationDoor,$request->confi)->get();
        $data['GlazingSystem'] = GlazingSystem::where('status',1)->where($configurationDoor,$request->confi)->get();
        return response()->json($data);
    }

    public function filter_leaf_type(Request $request){
        $data['leaftype'] = IntumescentSealLeafType::where('configurableitems',$request->configurableitems)->get();
        if($data['leaftype']){
            return response()->json($data['leaftype']);
        }
        else{
            return false;
        }
    }

    public function filter_glass_type_overpanel(Request $request){
        $configurationDoor = configurationDoor($request->confi);
        $firerating = fireRatingDoor($request->firerating);
        $data['GlassType'] = OverpanelGlassGlazing::where('status',1)->where($configurationDoor,$request->confi)->where($firerating,$request->firerating)->get();
        return response()->json($data);
    }

    public function filterFanLightBeading(Request $request){
        $configurationDoor = $request->pageId; // Replace with actual value if dynamic
        $firerating = fireRatingDoor($request->fireRating); // Replace with actual value if dynamic
        $data = Option::where('configurableitems', $configurationDoor)
        ->where('firerating', $firerating)
        ->where('OptionSlug', 'fan_light_glazing_beads')
        ->get();
        return json_encode(array('status'=>'ok','data'=> $data));
    }
    public function filterSideLightBeading(Request $request){
        $configurationDoor = $request->pageId;
        $firerating = fireRatingDoor($request->fireRating);
        $data = Option::where('configurableitems',$configurationDoor)->where('firerating',$firerating)->where('OptionSlug','side_light_glazing_beads')->get();
        return json_encode(array('status'=>'ok','data'=> $data));
    }
}
