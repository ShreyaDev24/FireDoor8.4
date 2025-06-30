@extends("layouts.Master")

@section("main_section")
@if(session()->has('error'))
<style type="text/css">
    #useremail {
        border-color: red
    }
</style>
@endif
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
                <form id="signupForm" enctype="multipart/form-data" method="post" action="{{route('ironmongery-info/store-miscellaneous')}}" novalidate="novalidate">
                    {{csrf_field()}}
                    @if(isset($IronmongeryInfo->MiscellaneousGeneratedKey))
                    <input type="hidden" name="update" value="{{$IronmongeryInfo->MiscellaneousGeneratedKey}}">
                    @endif
                    <div class="tab-content">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="card-header">

                                        @if(isset($IronmongeryInfo->GeneratedKey))
                                        <h5 class="card-title" style="margin-top: 10px">Update Miscellaneous</h5>
                                        @else
                                        <h5 class="card-title" style="margin-top: 10px">Create Miscellaneous</h5>
                                        @endif

                                    </div>
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="firerating">Select Fire Rating<span class="text-danger">*</span></label>
                                                    @if(isset($IronmongeryInfo->MiscellaneousFireRating))
                                                    @php
                                                    $firerate = explode(',',$IronmongeryInfo->MiscellaneousFireRating);
                                                    @endphp
                                                    <select required name="MiscellaneousFireRating[]" id="firerating" multiple class="form-control selectpicker">
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
                                                    <select required name="MiscellaneousFireRating[]" id="firerating" multiple class="form-control selectpicker">
                                                        @foreach($option as $rr)
                                                        <option value="{{ $rr->OptionKey }}">{{ $rr->OptionKey }}</option>
                                                        @endforeach
                                                    </select>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="Image" class="">Image<span class="text-danger">*</span></label>
                                                    <input type="file" name="MiscellaneousImage" accept=".jpg, .jpeg, .png, .JPG, .JPEG, .PNG" @if(!isset($IronmongeryInfo->MiscellaneousGeneratedKey)) required @endif
                                                    class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="First Name" class="">Category<span class="text-danger">*</span></label>
                                                    <select required name="MiscellaneousCategory" id="Category" class="form-control" >
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

                                                        <option value="{{preg_replace('/\s+/', '', $categoryIndex)}}" @if(isset($IronmongeryInfo->MiscellaneousCategory))
                                                            @if($IronmongeryInfo->MiscellaneousCategory == preg_replace('/\s+/', '',
                                                            $categoryIndex)) selected @endif @endif>@if($categoryIndex == 'Push Handles') Push Plates @else {{ $categoryIndex }} @endif
                                                        </option>

                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>

                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="Name" class="">Name<span class="text-danger">*</span></label>
                                                    <input name="MiscellaneousName" value="@if(isset($IronmongeryInfo->MiscellaneousName)){{$IronmongeryInfo->MiscellaneousName}}@else{{old('Name')}}@endif" required placeholder="Enter Name" type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="Code" class="">Code<span class="text-danger">*</span></label>
                                                    <input name="MiscellaneousCode" value="@if(isset($IronmongeryInfo->MiscellaneousCode)){{$IronmongeryInfo->MiscellaneousCode}}@else{{old('Code')}}@endif" required placeholder="Enter Code" type="text" class="form-control">
                                                </div>
                                            </div>
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
                                                    <select required name="MiscellaneousFinishes[]" id="Finishes" class="form-control selectpicker" multiple data-live-search="true">
                                                        <option value="">Select any option</option>
                                                        @foreach($finishesArray as $finishesIndex => $finishesVal)
                                                        <option value="{{preg_replace('/\s+/', '', $finishesVal)}}" @if(isset($IronmongeryInfo->MiscellaneousFinishes))
                                                            @php
                                                            $a2 = preg_replace('/\s+/', '',$finishesVal);
                                                            $a1 = explode(',',$IronmongeryInfo->MiscellaneousFinishes);
                                                            if(in_array($a2 , $a1)){
                                                            echo 'selected';
                                                            }
                                                            @endphp
                                                            @endif >
                                                            {{$finishesVal}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="FireCartNoUK" class="">Fire Cert No. UK </label>

                                                    <input name="MiscellaneousFireCartNoUK" type="text" value="@if(isset($IronmongeryInfo->MiscellaneousFireCartNoUK)){{ $IronmongeryInfo->MiscellaneousFireCartNoUK }}@endif" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="FireCartNoEU" class="">Fire Cert No. EU </label>
                                                    <input name="MiscellaneousFireCartNoEU" type="text" value="@if(isset($IronmongeryInfo->MiscellaneousFireCartNoEU)){{ $IronmongeryInfo->MiscellaneousFireCartNoEU }}@endif" class="form-control">
                                                </div>
                                            </div>
                                            <div id="" class="col-md-12">
                                                <div class="position-relative form-group">
                                                    <label for="Name" class="">Description<span class="text-danger">*</span></label>

                                                    <textarea rows="10" cols="10" required="" placeholder="Enter Descriptions..." name="MiscellaneousDescription" class="form-control">@if(isset($IronmongeryInfo->MiscellaneousDescription)){{$IronmongeryInfo->MiscellaneousDescription}}@else{{old('Description')}}@endif</textarea>

                                                </div>
                                            </div>


                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="Name" class="">Price<span class="text-danger">*</span></label>
                                                    <input name="MiscellaneousPrice" value="@if(isset($IronmongeryInfo->MiscellaneousPrice)){{$IronmongeryInfo->MiscellaneousPrice}}@else{{old('Price')}}@endif" required placeholder="Enter Price" type="number" min="0" step="0.01" pattern='[0-9]+(\\.[0-9][0-9]?)?' class="form-control">
                                                </div>
                                            </div>

                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="Name" class="">Supplier<span class="text-danger">*</span></label>
                                                    <input name="MiscellaneousSupplier" value="@if(isset($IronmongeryInfo->MiscellaneousSupplier)){{$IronmongeryInfo->MiscellaneousSupplier}}@else{{old('Supplier')}}@endif" required placeholder="Enter Supplier" type="text" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="position-relative form-group"><label for="projectImage" class="">PDF specification <span class="text-danger">*</span></label>
                                                    <input name="MiscellaneousPdfSpecification" accept=".pdf,.PDF" @if(!isset($IronmongeryInfo->MiscellaneousGeneratedKey)) required @endif
                                                    type="file" class="form-control">
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="First Name" class="">Status<span class="text-danger">*</span></label>

                                                    <select required name="MiscellaneousStatus" id="Status" class="form-control">

                                                        <option value="1" @if(isset($IronmongeryInfo->MiscellaneousStatus))
                                                            @if($IronmongeryInfo->MiscellaneousStatus == "1") selected @endif
                                                            @endif>Active</option>
                                                        <option value="0" @if(isset($IronmongeryInfo->MiscellaneousStatus))
                                                            @if($IronmongeryInfo->MiscellaneousStatus == "0") selected @endif
                                                            @endif>Inactive</option>

                                                    </select>
                                                </div>
                                            </div>

                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="Code" class="">Intumescentseal FD30</label>
                                                    <input name="Miscellaneousintumescentseal_fd30" value="@if(isset($IronmongeryInfo->Miscellaneousintumescentseal_fd30)){{$IronmongeryInfo->Miscellaneousintumescentseal_fd30}}@else{{old('intumescentseal_fd30')}}@endif" placeholder="Enter intumescentseal fd30" type="text" class="form-control">
                                                </div>
                                            </div>

                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="Name" class="">Intumescentseal FD30 Price</label>
                                                    <input name="Miscellaneousintumescentseal_fd30_price" value="@if(isset($IronmongeryInfo->Miscellaneousintumescentseal_fd30_price)){{$IronmongeryInfo->Miscellaneousintumescentseal_fd30_price}}@else{{old('intumescentseal_fd30_price')}}@endif"  placeholder="Enter intumescentseal fd30 price" type="number" min="0" step="0.01" pattern='[0-9]+(\\.[0-9][0-9]?)?' class="form-control">
                                                </div>
                                            </div>

                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="Code" class="">Intumescentseal FD60</label>
                                                    <input name="Miscellaneousintumescentseal_fd60" value="@if(isset($IronmongeryInfo->Miscellaneousintumescentseal_fd60)){{$IronmongeryInfo->Miscellaneousintumescentseal_fd60}}@else{{old('intumescentseal_fd60')}}@endif" placeholder="Enter intumescentseal fd60" type="text" class="form-control">
                                                </div>
                                            </div>

                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="Name" class="">Intumescentseal FD60 Price</label>
                                                    <input name="Miscellaneousintumescentseal_fd60_price" value="@if(isset($IronmongeryInfo->Miscellaneousintumescentseal_fd60_price)){{$IronmongeryInfo->Miscellaneousintumescentseal_fd60_price}}@else{{old('intumescentseal_fd60_price')}}@endif" placeholder="Enter intumescentseal fd60 price" type="number" min="0" step="0.01" pattern='[0-9]+(\\.[0-9][0-9]?)?' class="form-control">
                                                </div>
                                            </div>

                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="Name" class="">Man Minutes</label>
                                                    <input name="MiscellaneousManMinutes" min="0" value="@if(isset($IronmongeryInfo->MiscellaneousManMinutes)){{$IronmongeryInfo->MiscellaneousManMinutes}}@else{{old('ManMinutes')}}@endif" placeholder="Enter Man Minutese" type="text" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode == 46 && this.value.indexOf('.') === -1)"
                                                    class="form-control">
                                                </div>
                                            </div>

                                            <div id="" class="col-md-6">
                                                <div class="position-relative form-group"><label for="Name" class="">Machine Minutes</label>
                                                    <input name="MiscellaneousMachineMinutes" min="0" value="@if(isset($IronmongeryInfo->MiscellaneousMachineMinutes)){{$IronmongeryInfo->MiscellaneousMachineMinutes}}@else{{old('ManMinutes')}}@endif" placeholder="Enter Machine Minutese" type="text" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode == 46 && this.value.indexOf('.') === -1)"
                                                    class="form-control">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="d-block text-right card-footer">
                                <button type="submit" id="submit" class="btn-wide btn btn-success" style="margin-right: 20px">
                                    @if(isset($IronmongeryInfo->MiscellaneousGeneratedKey))
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

                         if (selectedCategory === 'DoorSignage') {
                            $('input[name="distanceFromBottomOfDoor"]')
                            .prop('disabled', false)
                            .attr('required', true)
                            .attr('min', 1500)
                            .attr('max', 2000);
                        } else {
                            $('input[name="distanceFromBottomOfDoor"]').removeAttr('min max').prop('readonly', false);
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
    </script>
    @endsection
