@extends("layouts.Master")

@section("main_section")
@if(session()->has('error'))
<style type="text/css">
#useremail {
border-color: red;
}
.alert-dismissible {
word-wrap: break-word;
}
</style>
@endif
<style type="text/css">
.alert-dismissible {
word-wrap: break-word;
}
.inptfrm {
    font-size: 16px;
}
.inpt_flx {
    display: flex;
}
.labl_ipt {
    border:1px solid #ced4da;
    width: 148px;
    height: 65px;
    padding: 10px;
    font-weight: 800;
}
.frst_frm input {
    border: 1px solid #ced4da;
    width: 130px;
    height: 65px;
    font-weight: 800;
    padding: 10px;
    padding-top: 0;
}

</style>
<div class="app-main__outer">
<div class="app-main__inner">
@if(session()->has('success'))
<div class="alert alert-success alert-dismissible">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
<h5><i class="icon fas fa-check"></i> Alert!</h5>
{!! session()->get('success') !!}
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
<form method="post" action="{{route('project/store')}}" enctype="multipart/form-data">
{{csrf_field()}}

<input type="hidden" name="updval" value="@if(isset($projects->GeneratedKey)){{$projects->GeneratedKey}}@endif">
<input type="hidden" name="projectId" value="@if(isset($projects->id)){{$projects->id}}@endif">

<div class="tab-content">
<div class="main-card mb-3 card">
<div class="">
<div class="tab-content">
    <div class="card-header">
        <h5 class="card-title" style="margin-top: 10px">Create Project</h5>
    </div>
    <div class="">
        <div class="form-row">
            <div class="col-md-12">
                <div class="main-card mb-3">
                    <div class="card-body">
                        <div class="col-md-12 p-0">
                            <h5 class="card-title">Project Details</h5>
                            <div class="row">
                            @if(Auth::user()->UserType !='4')
                                <div class="col-md-3">
                                    <div class="form-group">

                                        <label for="selectcustomer">Select Main Contractor<span class="text-danger">*</span></label>
                                        <select name="customerId" id="selectcustomer" class="form-control" required>
                                            <option value="">Select Main Contractor</option>
                                            @if(!empty($companykacustomer))
                                                @foreach($companykacustomer as $row)
                                                    @if($row->CstCompanyName != '')
                                                        <option value="{{$row->id}}"
                                                        @if(!empty($projects->customerId))
                                                            @if($projects->customerId == $row->id)
                                                                {{'selected'}}
                                                            @endif
                                                        @endif
                                                        >{{$row->CstCompanyName}}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>

                                    </div>
                                </div>
                                @endif
                                <div class="col-md-3">
                                    <div class="form-group">

                                        <label for="selectcustomer">Select Building Type<span class="text-danger">*</span></label>
                                        <select name="building_type" id="building_type" class="form-control" required>
                                            <option value="">Select Building Type</option>
                                            <option value="House" @if(isset($projects->BuildingType) && $projects->BuildingType == 'House'){{'selected'}} @endif>House</option>
                                            <option value="Apartment" @if(isset($projects->BuildingType) && $projects->BuildingType == 'Apartment') {{'selected'}} @endif>Apartment</option>
                                            <option value="Commercial" @if(isset($projects->BuildingType) && $projects->BuildingType == 'Commercial') {{'selected'}} @endif>Commercial</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        @if (!empty($ProjectBuildingDetails) && $ProjectBuildingDetails != 'null' && $projects->BuildingType == 'House')
                            <div class="col-md-12 p-0 house" id="house">
                                <h5 class="card-title">Building Details</h5>
                                <div class="form-group" id="add-service">
                                    <a style="float: right; margin-right: 10px; margin-top: -45px" href="javascript:void(0);" id="add" class="btn-shadow btn btn-success add">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($ProjectBuildingDetails as $key => $value)
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>House Type <span class="text-danger">*</span></label>
                                            <input class="form-control houseType" type="text" name="houseType[]" required="" value="@if(isset($projects->id)){{$value->houseType}}@else{{old('ProjectName')}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>No of Floors <span class="text-danger">*</span></label>
                                            <input type="number" min="1" max="99" name="floorCount[]" class="form-control floorCount" required value="@if(isset($projects->id)){{$value->floorCount}}@else{{old('ProjectName')}}@endif">
                                        </div>
                                    </div>

                                    @if ($i != 0)
                                    <div class="col-md-3">
                                        <a href="javascript:void(0);" class="btn btn-danger closes" style="margin-top: 1.9rem !important;"><i class="far fa-times-circle"></i></a>
                                    </div>
                                    @endif
                                    @php
                                        $i++;
                                    @endphp
                                </div>
                            @endforeach
                            <div id="add-data"></div>
                            </div>
                            @else
                            <div class="col-md-12 p-0 house" id="house">
                                <h5 class="card-title">Building Details</h5>
                                <div class="form-group" id="add-service">
                                    <a style="float: right; margin-right: 10px; margin-top: -45px" href="javascript:void(0);" id="add" class="btn-shadow btn btn-success add">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>House Type <span class="text-danger">*</span></label>
                                            <input class="form-control houseType" type="text" name="houseType[]" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>No of Floors <span class="text-danger">*</span></label>
                                            <input type="number" min="1" max="99" name="floorCount[]" class="form-control floorCount" required>
                                        </div>
                                    </div>
                                </div>
                                <div id="add-data"></div>

                            </div>
                        @endif

                        @if (!empty($ProjectBuildingDetails) && $ProjectBuildingDetails != 'null' && $projects->BuildingType == 'Commercial')
                            <div class="col-md-12 p-0 commercial" id="commercial">
                                <h5 class="card-title">Building Details</h5>
                                <div class="form-group" id="add-service">
                                    {{--  <a style="float: right; margin-right: 10px; margin-top: -45px" href="javascript:void(0);" id="add1" class="btn-shadow btn btn-success add1">
                                        <i class="fa fa-plus"></i>
                                    </a>  --}}
                                </div>
                                @php
                                    $i = count($ProjectBuildingDetails);
                                @endphp
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>No of Floors <span class="text-danger">*</span></label>
                                            <input type="number" min="1" max="99" name="floorCount1" class="form-control floorCount1" required value="@if(isset($ProjectBuildingDetails[$i-1]->id)){{$ProjectBuildingDetails[$i-1]->floorCount + 1 }}@else{{old('ProjectName')}}@endif">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="col-md-12 p-0 commercial" id="commercial">
                                <h5 class="card-title">Building Details</h5>
                                <div class="form-group" id="add-service">
                                    {{--  <a style="float: right; margin-right: 10px; margin-top: -45px" href="javascript:void(0);" id="add1" class="btn-shadow btn btn-success add1">
                                        <i class="fa fa-plus"></i>
                                    </a>  --}}
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>No of Floors <span class="text-danger">*</span></label>
                                            <input type="number" min="1" max="99" name="floorCount1" class="form-control floorCount1" required>
                                        </div>
                                    </div>
                                </div>
                                <div id="add1-data"></div>

                            </div>
                            @endif

                        <div class="col-md-12 p-0">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="First Name">Project Name<span class="text-danger">*</span></label>
                                        <input name="ProjectName" type="text" class="form-control" placeholder="Enter Project Name"
                                            value="@if(isset($projects->id)){{$projects->ProjectName}}@else{{old('ProjectName')}}@endif" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-5">
                                        <label for="First Name">Project Image</label>
                                        <input type="file" name="ProjectImage" id="ProjectImage" class="form-control"
                                            value="@if(isset($projects->ProjectImage)){{$projects->ProjectImage}}@else{{old('ProjectImage')}}@endif">
                                    </div>
                                </div>
                                @if(isset($projects->ProjectImage))
                                    <div class="col-md-1 p-1">
                                        <img src="{{url('/')}}/uploads/Project/{{$projects->ProjectImage}}" alt="ProjectImage" style="width:100px">
                                    </div>
                                @endif
                                <div class="col-md-2">
                                    <div class="form-group mb-5">
                                        <label for="returnTenderDate">Return Tender Date<span class="text-danger">*</span></label>
                                        <input type="text" name="returnTenderDate" class="form-control datepicker" value="@if(isset($projects->returnTenderDate))@if($projects->returnTenderDate != '0000-00-00'){{ date('d-m-Y',strtotime($projects->returnTenderDate)) }}@endif @endif" required autocomplete="off">
                                    </div>
                                </div>
                                @if ($loginUserType != 4)
                                <div class="col-md-2">
                                    <div class="position-relative form-group">
                                        <label for="ProductName">Currency <span
                                                class="text-danger">*</span></label>
                                        <select name="projectCurrency" class="form-control">
                                            <option value="">Select Currency</option>
                                            {{--  <option value="$_US_DOLLAR"
                                                @php
                                                    if(!empty($projects->projectCurrency)){
                                                        if($projects->projectCurrency == '$_US_DOLLAR'){
                                                            echo 'selected';
                                                        }
                                                    } else {
                                                        if(!empty($currency->currency)){
                                                            if($currency->currency == '$_US_DOLLAR'){
                                                                echo 'selected';
                                                            }
                                                        }
                                                    }
                                                @endphp
                                            >$ US DOLLAR</option>  --}}
                                            <option value="£_GBP"
                                                @php
                                                    if(!empty($projects->projectCurrency)){
                                                        if($projects->projectCurrency == '£_GBP'){
                                                            echo 'selected';
                                                        }
                                                    } else {
                                                        if(!empty($currency->currency)){
                                                            if($currency->currency == '£_GBP'){
                                                                echo 'selected';
                                                            }
                                                        }
                                                    }
                                                @endphp
                                            >£ GBP</option>
                                            <option value="€_EURO"
                                                @php
                                                    if(!empty($projects->projectCurrency)){
                                                        if($projects->projectCurrency == '€_EURO'){
                                                            echo 'selected';
                                                        }
                                                    } else {
                                                        if(!empty($currency->currency)){
                                                            if($currency->currency == '€_EURO'){
                                                                echo 'selected';
                                                            }
                                                        }
                                                    }
                                                @endphp
                                            >€ EURO</option>
                                        </select>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-2">
                                    <div class="position-relative form-group">
                                        <label for="COC">COC</label>
                                        <select name="COC" id="COC" class="form-control" required>
                                            <option value="">Select COC</option>
                                            @foreach($OptionsData as $row)
                                            @if($row->OptionSlug=='COC')
                                            <option value="{{$row->OptionKey}}" @if(!empty($projects->coc))
                                                @if($projects->coc==$row->OptionKey) {{'selected'}} @endif
                                                @endif>{{$row->OptionValue}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 p-0">
                            <h5 class="card-title">Project Address</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="projectImage">Address Line 1<span class="text-danger">*</span></label>
                                        <input type="text" name="AddressLine1" class="form-control" id="searchTextField" placeholder="Enter Address Line 1" value="@if(isset($projects->AddressLine1)){{$projects->AddressLine1}}@else{{old('AddressLine1')}}@endif" required  maxlength="150">
                                        <input type="hidden" id="city2" name="city2" />
                                        <input type="hidden" name="Lat" id="CstLat">
                                        <input type="hidden" name="Long" id="CstLong">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="projectImage">City<span class="text-danger">*</span></label>
                                        <input type="text" name="City" id="City" class="form-control" placeholder="Enter City"
                                            value="@if(isset($projects->City)){{$projects->City}}@else{{old('City')}}@endif" required>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="projectImage">Province</label>
                                        <input name="Province" id="State"
                                            value="@if(isset($projects->Province)){{$projects->Province}}@else{{old('Province')}}@endif"
                                            placeholder="Enter Province" type="text"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="projectImage" class="">Country<span
                                                class="text-danger">*</span></label>
                                        <input name="Country" id="Country"
                                            value="@if(isset($projects->Country)){{$projects->Country}}@else{{old('Country')}}@endif"
                                            placeholder="Enter Country" required type="text"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="projectImage">Postal Code/Eircode<span class="text-danger">*</span></label>
                                        <input name="PostalCode" id="PinCode"
                                            value="@if(isset($projects->PostalCode)){{$projects->PostalCode}}@else{{old('PostalCode')}}@endif"
                                            placeholder="Enter Postal Code/Eircode" required type="text"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="projectImage">Address Line 2</label>
                                        <input type="text" name="AddressLine2" class="form-control" placeholder="Enter Address Line 2"
                                            value="@if(isset($projects->AddressLine2)){{$projects->AddressLine2}}@else{{old('AddressLine2')}}@endif" maxlength="150">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="projectImage" class="">More Information <span
                                                class="text-danger"></span></label>
                                        <textarea rows="10" cols="10"
                                            placeholder="Enter More Information..." name="MoreInformation"
                                            class="form-control">@if(isset($projects->MoreInformation)){{$projects->MoreInformation}}@else{{old('MoreInformation')}}@endif</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 p-0">
                            <h5 class="card-title">Project Files</h5>
                            <div class="row">
                                @php
                                    $projectFilesArray = ["Door Schedule","Door Elevations","Floor Plan","NBS","BOQ (Bill of Quantities)","Other Files","Ironmongery Schedule"];
                                    if(isset($projects->ProjectFiles)){
                                        $projectFilesArrayUpdateData = (array)json_decode($projects->ProjectFiles);
                                    }
                                @endphp
                                @foreach($projectFilesArray as $projectFileIndex => $projectFileVal)
                                    @php
                                        $projectFileValKey = preg_replace('/\s+/', '', $projectFileVal);
                                        $filename = preg_replace('/\s+/', '', $projectFileVal);
                                        if($filename == 'DoorSchedule'){
                                            $filetype = 'multiple';
                                            $accept_type = '.csv' ;
                                            $altername = $filename.'[]';
                                            $matchname = $filename;
                                        } else if($filename == 'BOQ(BillofQuantities)'){
                                            $filetype = 'multiple';
                                            $altername = 'BOQ[]';
                                            $matchname = 'BOQ';
                                        } else {
                                            $filetype = 'multiple';
                                            $altername = $filename.'[]';
                                            $matchname = $filename;
                                        }
                                    @endphp
                                    <div class="col-md-3">
                                        <div class="projectFiles {{$projectFileVal}}">
                                            <label for="exampleEmail">{{$projectFileVal}} ( {{$filetype}} )</label>
                                            <div class="input-group mb-2">

                                                <input type="file" name="{{$altername}}" class="form-control" accept="{{$accept_type}}" {{$filetype}}>
                                            </div>
                                        </div>
                                        @if(!empty($ProjectFiles))
                                            @foreach($ProjectFiles as $newFile)
                                                @if($newFile->tag == $matchname)
                                                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                                        <strong>{{$newFile->file}}</strong>

                                                        <input type="hidden" class="projectFileID" value="{{$newFile->id}}">
                                                        <input type="hidden" class="filename" value="{{$newFile->file}}">
                                                        <a href="#" class="close DeleteProjectFile" data-dismiss="alert" aria-label="Close"><span class="fa fa-trash"></span></a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="inptfrm mt-2">
                            <h5 class="card-title">Certification</h5>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="inpt_flx">
                                        <div class="labl_ipt">IO No.</div>
                                        <div class="frst_frm">
                                            <input type="text" id="ioNumberOne" name="ioNumberOne" class="frt_frm-inpt" placeholder="001" value="@if(isset($projects->id)){{$projects->ioNumberOne}}@else{{old('ioNumberOne')}}@endif">
                                        </div>
                                        <div class="frst_frm">
                                            <input type="text" id="ioNumberTwo" name="ioNumberTwo" class="frt_frm-inpt" placeholder="002" value="@if(isset($projects->id)){{$projects->ioNumberTwo}}@else{{old('ioNumberTwo')}}@endif">
                                        </div>
                                        <div class="frst_frm">
                                            <input type="text" id="ioNumberThree" name="ioNumberThree" class="frt_frm-inpt" placeholder="003" value="@if(isset($projects->id)){{$projects->ioNumberThree}}@else{{old('ioNumberThree')}}@endif">
                                        </div>
                                    </div>
                                    <div class="inpt_flx mt-1">
                                        <div class="labl_ipt">Door Po</div>
                                        <div class="frst_frm">
                                            <input type="text" id="doorPoOne" name="doorPoOne" class="frt_frm-inpt" placeholder="001" value="@if(isset($projects->id)){{$projects->doorPoOne}}@else{{old('doorPoOne')}}@endif">
                                        </div>
                                        <div class="frst_frm">
                                            <input type="text" id="doorPoTwo" name="doorPoTwo" class="frt_frm-inpt" placeholder="002" value="@if(isset($projects->id)){{$projects->doorPoTwo}}@else{{old('doorPoTwo')}}@endif">
                                        </div>
                                        <div class="frst_frm">
                                            <input type="text" id="doorPoThree" name="doorPoThree" class="frt_frm-inpt" placeholder="003" value="@if(isset($projects->id)){{$projects->doorPoThree}}@else{{old('doorPoThree')}}@endif">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="inpt_flx">
                                        <div class="labl_ipt">Frame PO</div>
                                        <div class="frst_frm">
                                            <input type="text" id="framePoOne" name="framePoOne" class="frt_frm-inpt" placeholder="001" value="@if(isset($projects->id)){{$projects->framePoOne}}@else{{old('framePoOne')}}@endif">
                                        </div>
                                        <div class="frst_frm">
                                            <input type="text" id="framePoTwo" name="framePoTwo" class="frt_frm-inpt" placeholder="002" value="@if(isset($projects->id)){{$projects->framePoTwo}}@else{{old('framePoTwo')}}@endif">
                                        </div>
                                        <div class="frst_frm">
                                            <input type="text" id="framePoThree" name="framePoThree" class="frt_frm-inpt" placeholder="003" value="@if(isset($projects->id)){{$projects->framePoThree}}@else{{old('framePoThree')}}@endif">
                                        </div>
                                    </div>
                                    <div class="inpt_flx mt-1">
                                        <div class="labl_ipt">Glass PO</div>
                                        <div class="frst_frm">
                                            <input type="text" id="glassPoOne" name="glassPoOne" class="frt_frm-inpt" placeholder="001" value="@if(isset($projects->id)){{$projects->glassPoOne}}@else{{old('glassPoOne')}}@endif">
                                        </div>
                                        <div class="frst_frm">
                                            <input type="text" id="glassPoTwo" name="glassPoTwo" class="frt_frm-inpt" placeholder="002" value="@if(isset($projects->id)){{$projects->glassPoTwo}}@else{{old('glassPoTwo')}}@endif">
                                        </div>
                                        <div class="frst_frm">
                                            <input type="text" id="glassPoThree" name="glassPoThree" class="frt_frm-inpt" placeholder="003" value="@if(isset($projects->id)){{$projects->glassPoThree}}@else{{old('glassPoThree')}}@endif">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="inpt_flx">
                                        <div class="labl_ipt">Ironmongery PO</div>
                                        <div class="frst_frm">
                                            <input type="text" id="ironmongeryPoOne" name="ironmongeryPoOne" class="frt_frm-inpt" placeholder="001" value="@if(isset($projects->id)){{$projects->ironmongeryPoOne}}@else{{old('ironmongeryPoOne')}}@endif">
                                        </div>
                                        <div class="frst_frm">
                                            <input type="text" id="ironmongeryPoTwo" name="ironmongeryPoTwo" class="frt_frm-inpt" placeholder="002" value="@if(isset($projects->id)){{$projects->ironmongeryPoTwo}}@else{{old('ironmongeryPoTwo')}}@endif">
                                        </div>
                                        <div class="frst_frm">
                                            <input type="text" id="ironmongeryPoThree" name="ironmongeryPoThree" class="frt_frm-inpt" placeholder="003" value="@if(isset($projects->id)){{$projects->ironmongeryPoThree}}@else{{old('ironmongeryPoThree')}}@endif">
                                        </div>
                                    </div>
                                    <div class="inpt_flx mt-1">
                                        <div class="labl_ipt">Intumescent Po</div>
                                        <div class="frst_frm">
                                            <input type="text" id="intumescentPoOne" name="intumescentPoOne" class="frt_frm-inpt" placeholder="001" value="@if(isset($projects->id)){{$projects->intumescentPoOne}}@else{{old('intumescentPoOne')}}@endif">
                                        </div>
                                        <div class="frst_frm">
                                            <input type="text" id="intumescentPoTwo" name="intumescentPoTwo" class="frt_frm-inpt" placeholder="002" value="@if(isset($projects->id)){{$projects->intumescentPoTwo}}@else{{old('intumescentPoTwo')}}@endif">
                                        </div>
                                        <div class="frst_frm">
                                            <input type="text" id="intumescentPoThree" name="intumescentPoThree" class="frt_frm-inpt" placeholder="003" value="@if(isset($projects->id)){{$projects->intumescentPoThree}}@else{{old('intumescentPoThree')}}@endif">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<div class="main-card mb-3 custom_card">
<div class="d-block text-right">
<button type="submit" id="submit" class="btn-wide btn btn-success">
    @if(isset($projects->GeneratedKey))
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
@endsection
@section("script_section")
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function() {
    $(".CategoryChangeDynamicFields").hide();
        $('.datepicker').datepicker({
        format: 'dd-mm-yyyy'
    });

    $('.house').hide();
    $('.commercial').hide();

    var building_type = $('#building_type').val();
        if(building_type == 'House'){
            $('.commercial').hide();
            $('.house').show();
            $('.houseType').attr('required', true);
            $('.floorCount').attr('required', true);
            $('.floorCount1').removeAttr('required');
        }else if(building_type == 'Commercial'){
            $('.house').hide();
            $('.commercial').show();
            $('.floorCount1').attr('required', true);
            $('.houseType').removeAttr('required');
            $('.floorCount').removeAttr('required');
        }else{
            $('.house').hide();
            $('.commercial').hide();
            $('.houseType').removeAttr('required');
            $('.floorCount').removeAttr('required');
            $('.floorCount1').removeAttr('required');
        }

    $('#building_type').on('change',function(){
        var building_type = $('#building_type').val();
        if(building_type == 'House'){
            $('.commercial').hide();
            $('.house').show();
            $('.houseType').attr('required', true);
            $('.floorCount').attr('required', true);
            $('.floorCount1').removeAttr('required');
        }else if(building_type == 'Commercial'){
            $('.house').hide();
            $('.commercial').show();
            $('.floorCount1').attr('required', true);
            $('.houseType').removeAttr('required');
            $('.floorCount').removeAttr('required');
        }else{
            $('.house').hide();
            $('.commercial').hide();
            $('.houseType').removeAttr('required');
            $('.floorCount').removeAttr('required');
            $('.floorCount1').removeAttr('required');
        }
    });

    $("#add").click(function(){
        var data = '';
        data += '<div class="row">';
        data += '<div class="col-md-3">';
        data += '<div class="form-group">';
        data += '<label>House Type <span class="text-danger">*</span></label>';
        data += '<input class="form-control houseType" type="text" name="houseType[]" required="">';
        data += '</div>';
        data += '</div>';
        data += '<div class="col-md-3">';
        data += '<div class="form-group">';
        data += '<label>No of Floors <span class="text-danger">*</span></label>';
        data += '<input type="number" min="1" max="99" name="floorCount[]" class="form-control floorCount" required>';
        data += '</div>';
        data += '</div>';
        data += '<div class="col-md-3"><a href="javascript:void(0);" class="btn btn-danger closes" style="margin-top: 1.9rem !important;"><i class="far fa-times-circle"></i></a></div>';
        data += '</div>';
        $("#add-data").append(data);
    });
    $('#add-data').on('click', '.closes', function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
    });


    $("#add1").click(function(){
        var data = '';
        data += '<div class="row">';
        data += '<div class="col-md-3">';
        data += '<div class="form-group">';
        data += '<label>No of Floors <span class="text-danger">*</span></label>';
        data += '<input type="number" min="1" max="99" name="floorCount1[]" class="form-control floorCount1" required>';
        data += '</div>';
        data += '</div>';
        data += '<div class="col-md-3"><a href="javascript:void(0);" class="btn btn-danger closes" style="margin-top: 1.9rem !important;"><i class="far fa-times-circle"></i></a></div>';
        data += '</div>';
        $("#add1-data").append(data);
    });
    $('#add1-data').on('click', '.closes', function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
    });

    $('.closes').on('click', function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
    });
});
$(document).on('click','.DeleteProjectFile',function(){
    const projectFileID = $(this).siblings('.projectFileID').val();
    const filename = $(this).siblings('.filename').val();
    $.ajax({
        type: "POST",
        url: "{{route('deleteProjectFile')}}",
        data: { projectFileID: projectFileID, filename: filename },
        dataType: "json",
        success: function(data) {
        console.log(data);
    }
    });
});


</script>
@endsection
