@extends("layouts.Master")
@section('css')

@endsection
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
        @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Alert!</h5>
            {{ session()->get('success') }}
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
            <div class="tab-content">
                <div class="row">
                <div class="col-md-6">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <form id="signupForm" enctype="multipart/form-data" method="post" action="{{route('addquotation')}}" novalidate="novalidate">
                            {{csrf_field()}}
                            <input type="hidden" name="ProjectId" id="ProjectId" value="@if(isset($projects)){{$projects->id}}@else{{'0'}}@endif">
                            <div class="tab-content">
                                <div class="card-header">
                                    <h5 class="card-title" style="margin-top: 10px">Quotation Not Assign To Any Project</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label for="selectQuotation">Select Quotation (These Quotation Not Assign To Any Project)</label>
                                            <select name="selectQuotation" class="form-control" required>
                                                <option value="">Select Quotation</option>
                                                @foreach($quotationWithNoProject as $tt)
                                                    <option value="{{$tt->id}}">{{$tt->QuotationGenerationId .' '. $tt->QuotationName}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!--
                                        <div class="col-md-3">
                                            <div class="position-relative form-group">
                                                <label for="QuotationName">Quotation Name</label>
                                                <input type="text" required class="form-control" name="QuotationName" placeholder="Quotation Name">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="position-relative form-group">
                                                <label for="CustomerId">Select Customer</label>
                                                <select name="CustomerId" class="form-control" required>
                                                    <option value="">Select Customer</option>
                                                    @foreach($customer as $tt2)
                                                        @if($tt2->CstCompanyName != '')
                                                            <option value="{{$tt2->id}}">{{$tt2->CstCompanyName}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="position-relative form-group">
                                                <label for="ExpiryDate">Due Date*</label>
                                                <input type="date" required class="form-control" id="ExpiryDate" name="ExpiryDate" placeholder="Expiry Date">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="position-relative form-group">
                                                <label for="QuotationStatus">Quotation Status</label>
                                                <select name="QuotationStatus" class="form-control">
                                                    @php
                                                        $rr = ['Open','Closed','Lost','Abandoned','Expired'];
                                                        $i = 0;
                                                        $count = count($rr);
                                                        $quotStatus = '<option value="">Select Status</option>';
                                                        while($count > $i){
                                                            $selected = '';

                                                            $quotStatus .= '<option value="'.$rr[$i].'" '.$selected.'>'.$rr[$i].'</option>';
                                                            $i++;
                                                        }
                                                        echo  $quotStatus;
                                                    @endphp
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="position-relative form-group">
                                                <label for="Currency">Currency</label>
                                                <select name="Currency" class="form-control">
                                                    <option value="">Select Currency</option>
                                                    @php
                                                    if(!empty($quotation->Currency)){
                                                        if($quotation->Currency == '£_GBP'){
                                                            $newquocurreny = '£ GBP';
                                                        } else if($quotation->Currency == '€_EURO'){
                                                            $newquocurreny = '€ EURO';
                                                        } else if($quotation->Currency == '$_US_DOLLAR'){
                                                            $newquocurreny = '$ US DOLLAR';
                                                        } else {
                                                            $newquocurreny = '';
                                                        }
                                                        if(!empty($newquocurreny)){
                                                            echo '<option value="'.$quotation->Currency.'" selected>'.$newquocurreny.'</option>';
                                                        }
                                                    }
                                                    @endphp
                                                    <option value="£_GBP">£ GBP</option>
                                                    <option value="€_EURO">€ EURO</option>
                                                    <option value="$_US_DOLLAR">$ US DOLLAR</option>
                                                </select>
                                            </div>
                                        </div>
                                        -->

                                    </div>
                                </div>
                            </div>
                            <div class="d-block text-right card-footer mt-5">
                                <button class="btn-wide btn btn-success" style="margin-right: 20px">
                                    @if(isset($editdata->id))
                                    Update Now
                                    @else
                                    Submit Now
                                    @endif
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                </div>
                <div class="col-md-6">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <form id="signupForm" enctype="multipart/form-data" method="post" action="{{route('addquotation2')}}" novalidate="novalidate">
                            {{csrf_field()}}
                            <input type="hidden" name="ProjectId" id="ProjectId" value="@if(isset($projects)){{$projects->id}}@else{{'0'}}@endif">
                            <div class="tab-content">
                                <div class="card-header">
                                    <h5 class="card-title" style="margin-top: 10px">Quotation Filter By Main Contractor</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="col-md-8">
                                            <label for="selectQuotation">Select Quotation (These Quotation Filter By Main Contractor)</label>
                                            <select name="selectQuotation" class="form-control" required>
                                                <option value="">Select Quotation</option>
                                                @foreach($quotationToTheseProject as $tt)
                                                    <option value="{{$tt->id}}">{{$tt->QuotationGenerationId .' '. $tt->QuotationName}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-block text-right card-footer mt-5">
                                <button class="btn-wide btn btn-success" style="margin-right: 20px">
                                    @if(isset($editdata->id))
                                    Update Now
                                    @else
                                    Submit Now
                                    @endif
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                </div>
                </div>
            </div>
    </div>
</div>


@endsection
@section('js')
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
});
</script>
@endsection
