@extends("layouts.Master")

@section("main_section")
@if(session()->has('error'))
<style type="text/css">
    #useremail {
        border-color: red
    }
</style>
@endif

{{-- @php

dd($IronmongeryInfo);

@endphp --}}


<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="col-lg-12">
            @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Alert!</h5>
                {{ session()->get('success') }}
            </div>
            @endif
            @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="fas fa-skull-crossbones"></i> Alert!</h5>
                {{ session()->get('error') }}
            </div>
            @endif
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="fas fa-skull-crossbones"></i> Alert!</h5>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="card-body tab-card-body">
                <form id="signupForm" enctype="multipart/form-data" method="post" action="{{route('ironmongery-info/store')}}" novalidate="novalidate">
                    {{csrf_field()}}
                    @if(isset($IronmongeryInfo->GeneratedKey))
                    <input type="hidden" name="update" value="{{$IronmongeryInfo->GeneratedKey}}">
                    @endif
                    <div class="tab-content">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="card-header">

                                        @if(isset($IronmongeryInfo->GeneratedKey))
                                        <h5 class="card-title" style="margin-top: 10px">Update Ironmongery Info</h5>
                                        @else
                                        <h5 class="card-title" style="margin-top: 10px">Create Ironmongery Info</h5>
                                        @endif
                                        <?php
                                        $loginUser = Auth::user();
                                        ?>
                                        @if($loginUser->UserType == 2)
                                        <a href="{{route('ironmongery-info/list',['import'])}}" style="width:100px; position:absolute; right:2%">
                                            <input type="button" class="btn-wide btn btn-success" value="Import" />
                                        </a>
                                        @endif

                                    </div>
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="firerating">Select Fire Rating<span class="text-danger">*</span></label>
                                                    @if(isset($IronmongeryInfo->FireRating))
                                                    @php
                                                    $firerate = explode(',',$IronmongeryInfo->FireRating);
                                                    @endphp
                                                    <select required name="FireRating[]" id="firerating" multiple class="form-control selectpicker">
                                                        <option value="">Select Fire Rating</option>
                                                        @foreach($option as $rr)
                                                        <option value="{{ $rr->OptionKey }}" @if(in_array($rr->OptionKey,$firerate))
                                                            {{ 'selected'}}
                                                            @endif
                                                            >{{ $rr->OptionKey }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    @else
                                                    <select required name="FireRating[]" id="firerating" multiple class="form-control selectpicker">
                                                        @foreach($option as $rr)
                                                        <option value="{{ $rr->OptionKey }}">{{ $rr->OptionKey }}</option>
                                                        @endforeach
                                                    </select>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-2">
                                <div class="position-relative form-group"><label for="Name"
                                        class="">Fire Rating</label>
                                    <select required name="FireRating" id="FireRating"
                                        class="form-control">
                                        @php
                                        $finishesArray = [
                                        "NFR","FD30","FD60"
                                        ];
                                        @endphp

                                        <option value="">Select any option</option>
                                        @foreach($finishesArray as $finishesIndex => $finishesVal)

                                        <option value="{{preg_replace('/\s+/', '', $finishesVal)}}"
                                            @if(isset($IronmongeryInfo->FireRating))
                                            @if($IronmongeryInfo->FireRating == preg_replace('/\s+/',
                                            '', $finishesVal)){{"selected"}} @endif
                                            @endif>{{$finishesVal}}</option>

                                        @endforeach

                                    </select>
                                </div>
                            </div> -->
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="Image" class="">Image<span class="text-danger">*</span></label>
                                                    <input type="file" name="Image" accept=".jpg, .jpeg, .png, .JPG, .JPEG, .PNG" @if(!isset($IronmongeryInfo->GeneratedKey)) required @endif
                                                    class="form-control">
                                                </div>
                                            </div>

                                         {{--   <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="First Name" class="">Category<span class="text-danger">*</span></label>
                                                    <select required name="Category" id="Category" class="form-control" onchange="IronmongeryInfoCategoryChange(this.value);">
                                                        @php
                                                        $categoryArray = [
                                                        "Hinges" => ["Width","Height","Thickness","Knuckle Diameter"],
                                                        "Floor Spring" => ["Length","Width","Height","Max Door Weight","Max Door Width"],
                                                        "Locks and Latches" => ["Case Depth","Backset"],
                                                        "Flush Bolts" => ["Length","Width","Mortice Depth"],
                                                        "Concealed Overhead Closer" => ["Width","Height","Depth","Max Door Width","Max Door Height","Max Door Thickness","Max Weight"],
                                                        "Pull Handles" => ["Length","Fixing Distance","Projection","Door Thickness","Handle Diameter"],
                                                        "Push Handles" => ["Length","Width","Thickness"],
                                                        "Kick Plates" => ["Length","Width","Thickness"],
                                                        "Door Selectors" => ["Width","Height","Length","Thickness","Max door width","Max door rebate"],
                                                        "Panic Hardware" => ["Max Door Height","Max Door Width","Height","Width","Max Weight"],
                                                        "Door security viewer" => ["Cut out diameter","Outer lens diameter","Door Thickness"],
                                                        "Morticed drop down seals" =>["Length","Height","Width","Acoustic rating","Min cut length"],
                                                        "Face fixed drop seals" => ["Length","Height","Width","Acoustic rating","Min cut length"],
                                                        "Threshold Seal" => ["Length","Height","Width"],
                                                        "Air transfer grills" => ["Length","Height","Width"],
                                                        "Letterplates" => ["Overall Length","Overall Height","Aperture Length","Aperture Height","Fixing bolt centres"],
                                                        "Cable Ways" => ["Height","Width"],
                                                        "Safe Hinge" => ["Height","Width","Thickness","Knuckle Diameter"],
                                                        "Lever Handle" => ["Rose Width","Rose Thickness","Lever Length"],
                                                        "Safe Hinge" => ["Height","Width","Thickness","Knuckle Diameter"],
                                                        "Door Signage" => ["Length","Height"],
                                                        "Face Fixed Door Closers" =>["Width","Height","Depth","Width","Min Door Width","Min Door Height","Min Door Thickness"],
                                                        "Thumbturn" => ["Length","Thickness"],
                                                        "Keyhole Escutcheon" => ["Length","Thickness"],
                                                        "Door Stops" => ["Length","Height","Diameter"],
                                                        "Cylinders" => ["Length","Width","Depth","Number of Pins","Security","Cylinder Type"," Number of Keys","Keying Function"],
                                                        ];

                                                        if(isset($IronmongeryInfo->CategoryFieldsJSON)){
                                                        $categoryFieldsArray =
                                                        json_decode($IronmongeryInfo->CategoryFieldsJSON);
                                                        }

                                                        @endphp

                                                        <option value="">Select any option</option>
                                                        @foreach($categoryArray as $categoryIndex => $categoryVal)

                                                        <option value="{{preg_replace('/\s+/', '', $categoryIndex)}}" @if(isset($IronmongeryInfo->Category))
                                                            @if($IronmongeryInfo->Category == preg_replace('/\s+/', '',
                                                            $categoryIndex)) selected @endif @endif>@if($categoryIndex == 'Push Handles') Push Plates @else {{ $categoryIndex }} @endif
                                                        </option>

                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                            @foreach($categoryArray as $index => $val)
                                            @foreach($val as $indexInner => $valInner)
                                            @php $dynamicFiledName = preg_replace('/\s+/', '', $index.$valInner) @endphp
                                            <div class="col-md-6 CategoryChangeDynamicFields div{{preg_replace('/\s+/', '', $index)}}">
                                                <div class="position-relative form-group">
                                                    <label for="First Name">@if(!empty($valInner)){{$valInner}}@endif</label>
                                                    <input type="number" name="@if(!empty($dynamicFiledName)){{$dynamicFiledName}}@endif" class="form-control" placeholder="Enter @if(!empty($valInner)){{$valInner}}@endif" value="@if(isset($categoryFieldsArray))
                                            @if(!empty($categoryFieldsArray->$dynamicFiledName))
                                                {{$categoryFieldsArray->$dynamicFiledName}}
                                            @endif
                                        @endif" min="0" step="0.01">
                                                </div>
                                            </div>
                                            @endforeach
                                            @endforeach --}}

                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="First Name" class="">Category<span class="text-danger">*</span></label>
                                                    <select required name="Category" id="Category" class="form-control" >
                                                        @php
                                                        $categoryArray = [
                                                        "Hinges",
                                                        "Floor Spring",
                                                        "Locks and Latches",
                                                        "Flush Bolts",
                                                        "Concealed Overhead Closer",
                                                        "Pull Handles",
                                                        "Push Handles",
                                                        "Kick Plates",
                                                        "Door Selectors",
                                                        "Panic Hardware",
                                                        "Door security viewer",
                                                        "Morticed drop down seals",
                                                        "Face fixed drop seals",
                                                        "Threshold Seal",
                                                        "Air transfer grills",
                                                        "Letterplates",
                                                        "Cable Ways",
                                                        "Safe Hinge",
                                                        "Lever Handle",
                                                        "Safe Hinge",
                                                        "Door Signage",
                                                        "Face Fixed Door Closers",
                                                        "Thumbturn",
                                                        "Keyhole Escutcheon",
                                                        "Door Stops",
                                                        "Cylinders",
                                                        ];

                                                        if(isset($IronmongeryInfo->CategoryFieldsJSON)){
                                                        $categoryFieldsArray =
                                                        json_decode($IronmongeryInfo->CategoryFieldsJSON);
                                                        }

                                                        @endphp

                                                        <option value="">Select any option</option>
                                                        @foreach($categoryArray as $categoryIndex)

                                                        <option value="{{preg_replace('/\s+/', '', $categoryIndex)}}" @if(isset($IronmongeryInfo->Category))
                                                            @if($IronmongeryInfo->Category == preg_replace('/\s+/', '',
                                                            $categoryIndex)) selected @endif @endif>@if($categoryIndex == 'Push Handles') Push Plates @else {{ $categoryIndex }} @endif
                                                        </option>

                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>

                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="staticWidth" class="">Width<span class="text-danger">*</span></label>
                                                    <input name="staticWidth" value="@if(isset($IronmongeryInfo->staticWidth)){{$IronmongeryInfo->staticWidth}}@else{{old('staticWidth')}}@endif" required placeholder="Enter Width" type="number" min="0" step="0.01" pattern='[0-9]+(\\.[0-9][0-9]?)?' class="form-control">
                                                </div>
                                            </div>
                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="staticHeight" class="">Height<span class="text-danger">*</span></label>
                                                    <input name="staticHeight" value="@if(isset($IronmongeryInfo->staticHeight)){{$IronmongeryInfo->staticHeight}}@else{{old('staticHeight')}}@endif" required placeholder="Enter Height" type="number" min="0" step="0.01" pattern='[0-9]+(\\.[0-9][0-9]?)?' class="form-control">
                                                </div>
                                            </div>
                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="staticDepth" class="">Depth</label>
                                                    <input name="staticDepth" value="@if(isset($IronmongeryInfo->staticDepth)){{$IronmongeryInfo->staticDepth}}@else{{old('staticDepth')}}@endif" placeholder="Enter Depth" type="number" min="0" step="0.01" pattern='[0-9]+(\\.[0-9][0-9]?)?' class="form-control">
                                                </div>
                                            </div>

                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="Name" class="">Name<span class="text-danger">*</span></label>
                                                    <input name="Name" value="@if(isset($IronmongeryInfo->Name)){{$IronmongeryInfo->Name}}@else{{old('Name')}}@endif" required placeholder="Enter Name" type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="Code" class="">Code<span class="text-danger">*</span></label>
                                                    <input name="Code" value="@if(isset($IronmongeryInfo->Code)){{$IronmongeryInfo->Code}}@else{{old('Code')}}@endif" required placeholder="Enter Code" type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="distanceFromBottomOfDoor" class="">Distance from bottom of door<span class="text-danger">*</span></label>
                                                    <input name="distanceFromBottomOfDoor" value="@if(isset($IronmongeryInfo->distanceFromBottomOfDoor)){{$IronmongeryInfo->distanceFromBottomOfDoor}}@else{{old('distanceFromBottomOfDoor')}}@endif" required placeholder="Enter Distance from bottom of door" type="number" min="0" step="0.01" pattern='[0-9]+(\\.[0-9][0-9]?)?' class="form-control">
                                                </div>
                                            </div>
                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="distanceFromLeadingEdgeOfDoor" class="">Distance from leading edge of door<span class="text-danger">*</span></label>
                                                    <input name="distanceFromLeadingEdgeOfDoor" value="@if(isset($IronmongeryInfo->distanceFromLeadingEdgeOfDoor)){{$IronmongeryInfo->distanceFromLeadingEdgeOfDoor}}@else{{old('distanceFromLeadingEdgeOfDoor')}}@endif" required placeholder="Enter Distance from leading edge" type="number" min="0" step="0.01" pattern='[0-9]+(\\.[0-9][0-9]?)?' class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="centered">Centred</label>
                                                    <!-- Hidden input to store 0 if checkbox is unchecked -->
                                                    <input type="hidden" name="centered" value="0">
                                                    <input type="checkbox" name="centered" id="centered" style="margin: 36px;"
                                                        class=""
                                                        value="1"
                                                        @if(old('centered', isset($IronmongeryInfo->centered) && $IronmongeryInfo->centered == 1)) checked @endif>
                                                </div>
                                            </div>
                                          {{--  <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="Name" class="">Dimensions<span class="text-danger">*</span></label>
                                                    <input name="Dimensions" value="@if(isset($IronmongeryInfo->Dimensions)){{$IronmongeryInfo->Dimensions}}@else{{old('Dimensions')}}@endif" required placeholder="Enter Dimensions" type="text" class="form-control">
                                                </div>
                                            </div> --}}
                                            @php
                                            $finishesArray = [
                                            "Chrome","Polished Brass","Polished Nickel","Satin Nickel",
                                            "Antique Brass","Satin Chrome","Bronze
                                            Finish","Black","Bronze","Gold","Silver","White","Satin Stainless Steel","Polished Stainless Steel","Polished Chrome","Aluminium Silver","Yellow Grey","Traffic Black(Ebony Black)","Matte Chrome","Satin Anodised Aluminium","Golden Yellow","Ruby Red","Wine Red","Ultramarine Blue (Cobalt Blue)","Sapphire Blue (Midnight Blue)","Turquoise Green","Anthracite Grey","Manhattan Grey","Traffic White (Diamond White)","Stainless Steel Brushed (Anti Microbial Coated)","Matte Nickel Plate"
                                            ];
                                            @endphp
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="First Name" class="">Finishes<span class="text-danger">*</span></label>
                                                    <select required name="Finishes[]" id="Finishes" class="form-control selectpicker" multiple data-live-search="true">
                                                        <option value="">Select any option</option>
                                                        @foreach($finishesArray as $finishesIndex => $finishesVal)
                                                        <option value="{{preg_replace('/\s+/', '', $finishesVal)}}" @if(isset($IronmongeryInfo->Finishes))
                                                            @php
                                                            $a2 = preg_replace('/\s+/', '',$finishesVal);
                                                            $a1 = explode(',',$IronmongeryInfo->Finishes);
                                                            if(in_array($a2 , $a1)){
                                                            echo 'selected';
                                                            }
                                                            @endphp <!-- @if($IronmongeryInfo->Finishes == preg_replace('/\s+/', '',$finishesVal))
                                                    selected
                                                    @endif  -->
                                                            @endif >
                                                            {{$finishesVal}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="FireCartNoUK" class="">Fire Cert No. UK </label>

                                                    <input name="FireCartNoUK" type="text" value="@if(isset($IronmongeryInfo->FireCartNoUK)){{ $IronmongeryInfo->FireCartNoUK }}@endif" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="FireCartNoEU" class="">Fire Cert No. EU </label>
                                                    <input name="FireCartNoEU" type="text" value="@if(isset($IronmongeryInfo->FireCartNoEU)){{ $IronmongeryInfo->FireCartNoEU }}@endif" class="form-control">
                                                </div>
                                            </div>
                                            <div id="" class="col-md-12">
                                                <div class="position-relative form-group">
                                                    <label for="Name" class="">Description<span class="text-danger">*</span></label>

                                                    <textarea rows="10" cols="10" required="" placeholder="Enter Descriptions..." name="Description" class="form-control">@if(isset($IronmongeryInfo->Description)){{$IronmongeryInfo->Description}}@else{{old('Description')}}@endif</textarea>

                                                </div>
                                            </div>


                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="Name" class="">Price<span class="text-danger">*</span></label>
                                                    <input name="Price" value="@if(isset($IronmongeryInfo->Price)){{$IronmongeryInfo->Price}}@else{{old('Price')}}@endif" required placeholder="Enter Price" type="number" min="0" step="0.01" pattern='[0-9]+(\\.[0-9][0-9]?)?' class="form-control">
                                                </div>
                                            </div>

                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="Name" class="">Supplier<span class="text-danger">*</span></label>
                                                    <input name="Supplier" value="@if(isset($IronmongeryInfo->Supplier)){{$IronmongeryInfo->Supplier}}@else{{old('Supplier')}}@endif" required placeholder="Enter Supplier" type="text" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="projectImage" class="">PDF specification <span class="text-danger">*</span></label>
                                                    <input name="PdfSpecification" accept=".pdf,.PDF" @if(!isset($IronmongeryInfo->GeneratedKey)) required @endif
                                                    type="file" class="form-control">
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="First Name" class="">Status<span class="text-danger">*</span></label>

                                                    <select required name="Status" id="Status" class="form-control">

                                                        <option value="1" @if(isset($IronmongeryInfo->Status))
                                                            @if($IronmongeryInfo->Status == "1") selected @endif
                                                            @endif>Active</option>
                                                        <option value="0" @if(isset($IronmongeryInfo->Status))
                                                            @if($IronmongeryInfo->Status == "0") selected @endif
                                                            @endif>Inactive</option>

                                                    </select>
                                                </div>
                                            </div>

                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="Code" class="">Intumescentseal FD30</label>
                                                    <input name="intumescentseal_fd30" value="@if(isset($IronmongeryInfo->intumescentseal_fd30)){{$IronmongeryInfo->intumescentseal_fd30}}@else{{old('intumescentseal_fd30')}}@endif" placeholder="Enter intumescentseal fd30" type="text" class="form-control">
                                                </div>
                                            </div>

                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="Name" class="">Intumescentseal FD30 Price</label>
                                                    <input name="intumescentseal_fd30_price" value="@if(isset($IronmongeryInfo->intumescentseal_fd30_price)){{$IronmongeryInfo->intumescentseal_fd30_price}}@else{{old('intumescentseal_fd30_price')}}@endif"  placeholder="Enter intumescentseal fd30 price" type="number" min="0" step="0.01" pattern='[0-9]+(\\.[0-9][0-9]?)?' class="form-control">
                                                </div>
                                            </div>

                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="Code" class="">Intumescentseal FD60</label>
                                                    <input name="intumescentseal_fd60" value="@if(isset($IronmongeryInfo->intumescentseal_fd60)){{$IronmongeryInfo->intumescentseal_fd60}}@else{{old('intumescentseal_fd60')}}@endif" placeholder="Enter intumescentseal fd60" type="text" class="form-control">
                                                </div>
                                            </div>

                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="Name" class="">Intumescentseal FD60 Price</label>
                                                    <input name="intumescentseal_fd60_price" value="@if(isset($IronmongeryInfo->intumescentseal_fd60_price)){{$IronmongeryInfo->intumescentseal_fd60_price}}@else{{old('intumescentseal_fd60_price')}}@endif" placeholder="Enter intumescentseal fd60 price" type="number" min="0" step="0.01" pattern='[0-9]+(\\.[0-9][0-9]?)?' class="form-control">
                                                </div>
                                            </div>

                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="Name" class="">Man Minutes</label>
                                                    <input name="ManMinutes" min="0" value="@if(isset($IronmongeryInfo->ManMinutes)){{$IronmongeryInfo->ManMinutes}}@else{{old('ManMinutes')}}@endif" placeholder="Enter Man Minutese" type="text" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode == 46 && this.value.indexOf('.') === -1)"
                                                    class="form-control">
                                                </div>
                                            </div>

                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="Name" class="">Machine Minutes</label>
                                                    <input name="MachineMinutes" min="0" value="@if(isset($IronmongeryInfo->MachineMinutes)){{$IronmongeryInfo->MachineMinutes}}@else{{old('ManMinutes')}}@endif" placeholder="Enter Machine Minutese" type="text" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode == 46 && this.value.indexOf('.') === -1)"
                                                    class="form-control">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="d-block text-right card-footer">
                                <button type="submit" id="submit" class="btn-wide btn btn-success" style="margin-right: 20px">
                                    @if(isset($IronmongeryInfo->GeneratedKey))
                                    Update Now
                                    @else
                                    Create Now
                                    @endif
                                </button>
                            </div>

                        </div>
                    </div>
                </form>


            </div>


        </div>

    </div>

    @endsection

    @section('js')


    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //Ironmongery Ticket 20-11-2024
        // Add Rules by selection of category
            // start rules




            $(document).ready(function () {
                function toggleDistanceInput() {
                    const isCentered = $('#centered').is(':checked');
                    const distanceInput = $('#distanceFromLeadingEdgeOfDoor');

                    if (isCentered) {
                        $('input[name="distanceFromLeadingEdgeOfDoor"]')
                        .prop('disabled', true)
                        .removeAttr('required')
                        .val('');
                    } else {
                        $('input[name="distanceFromLeadingEdgeOfDoor"]')
                                    .prop('disabled', false)
                                    .attr('required', true);
                    }
                }
                // Call function on checkbox change
                $('#centered').on('change', toggleDistanceInput);

            // Call function on page load to set initial state
                toggleDistanceInput();

                    function applyRules(selectedCategory) {
                        // Rule (a) and Rule (f)
                        if (selectedCategory === 'Airtransfergrills' || selectedCategory === 'KickPlates') {
                            $('input[name="centered"]').prop('checked', true).prop('disabled', true);
                            $('input[name="centered"]').siblings('input[type="hidden"]').prop('disabled', false).val(1);
                            if (selectedCategory === 'Airtransfergrills') {
                                $('input[name="distanceFromLeadingEdgeOfDoor"]')
                                    .prop('disabled', true)
                                    .removeAttr('required')
                                    .val('');
                            } else {
                                $('input[name="distanceFromLeadingEdgeOfDoor"]')
                                    .prop('disabled', false)
                                    .attr('required', true);
                            }
                        } else {
                            // $('input[name="centered"]').prop('disabled', false).prop('checked', false);
                            // $('input[name="centered"]').siblings('input[type="hidden"]');
                            $('input[name="distanceFromLeadingEdgeOfDoor"]')
                                .prop('disabled', false)
                                .attr('required', true);
                        }

                        // Rule (b)
                        if (['Cylinders', 'Thumbturn', 'LocksandLatches'].includes(selectedCategory)) {
                            $('input[name="distanceFromBottomOfDoor"]')
                                .attr('min', 800)
                                .attr('max', 1000)
                                .prop('readonly', false);
                                //.val('');
                            $('input[name="distanceFromLeadingEdgeOfDoor"]')
                                .attr('max', 100)
                                .prop('readonly', false)
                                // .val('');
                        } else {
                            $('input[name="distanceFromBottomOfDoor"]').removeAttr('min max').prop('readonly', false);
                            $('input[name="distanceFromLeadingEdgeOfDoor"]').removeAttr('max').prop('readonly', false);
                        }

                        // Rule (c)
                        if (selectedCategory === 'DoorSignage') {
                            $('input[name="distanceFromBottomOfDoor"]').val(1550).prop('readonly', true);
                        } else {
                            $('input[name="distanceFromBottomOfDoor"]').prop('readonly', false);
                        }

                        // Rule (d)
                        if (selectedCategory === 'Morticeddropdownseals') {
                            $('input[name="staticHeight"]').val(50).prop('readonly', true);
                            $('input[name="distanceFromBottomOfDoor"]').val('').removeAttr('required').prop('readonly', true);
                            $('input[name="distanceFromLeadingEdgeOfDoor"]').val('').removeAttr('required').prop('readonly', true);
                        } else {
                            $('input[name="staticHeight"]').prop('readonly', false);
                            $('input[name="distanceFromBottomOfDoor"]').prop('readonly', false).attr('required', true);
                            $('input[name="distanceFromLeadingEdgeOfDoor"]').prop('readonly', false).attr('required', true);
                        }

                        // Rule (g), (h), (i)
                        if (['PullHandles', 'PushHandles', 'LeverHandle'].includes(selectedCategory)) {
                            $('input[name="distanceFromBottomOfDoor"]')
                                .attr('min', 800)
                                .attr('max', 1000)
                                .prop('readonly', false);
                            $('input[name="distanceFromLeadingEdgeOfDoor"]')
                                .attr('max', 100)
                                .prop('readonly', false);
                            if (['PullHandles', 'PushHandles'].includes(selectedCategory)) {
                                $('input[name="staticHeight"]')
                                    .attr('min', 300)
                                    .prop('readonly', false);
                            } else {
                                $('input[name="staticHeight"]')
                                    .removeAttr('min')
                                    .prop('readonly', false);
                            }
                        } else {
                            $('input[name="distanceFromBottomOfDoor"]').removeAttr('min max').prop('readonly', false);
                            $('input[name="distanceFromLeadingEdgeOfDoor"]').removeAttr('max').prop('readonly', false);
                            $('input[name="staticHeight"]').removeAttr('min').prop('readonly', false);
                        }
                    }

                    // On category change
                    $('#Category').on('change', function () {
                        const selectedCategory = $(this).val();
                        applyRules(selectedCategory);
                    });

                    // On page load (for edit mode)
                    const selectedCategory = $('#Category').val(); // Assuming the dropdown is pre-filled
                    if (selectedCategory) {
                        applyRules(selectedCategory);
                    }
                });


            // end rules


        // $(document).ready(function() {
        //     $(".CategoryChangeDynamicFields").hide();

            // $(document).on('change','#configurableitems',function(){
            //     let pageId = $(this).val();
            //     $.ajax({
            //         url: "{{route('filterConfiguretype')}}",
            //         type: "POST",
            //         data: {pageId: pageId},
            //         success: function(data) {
            //             $("#firerating").empty().append(data);
            //             // $("#firerating").empty().html(dt);
            //             // $(".multiselect").empty().append(data);
            //             $('.selectpicker').selectpicker('refresh');
            //         }
            //     })
            // })
        // });

        // function IronmongeryInfoCategoryChange(value) {

        //     $(".CategoryChangeDynamicFields").hide();
        //     $(".CategoryChangeDynamicFields").find("input[type='text']").attr("required", false);
        //     $(".CategoryChangeDynamicFields").find("input[type='text']").val("");

        //     $(".div" + value).find("input[type='text']").attr("required", false);
        //     $(".div" + value).show();
        // }
    </script>
    @endsection
