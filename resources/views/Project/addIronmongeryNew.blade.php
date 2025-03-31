@extends("layouts.Master")

@section("main_section")
<style>
.input-icons i {
    position: absolute;
    left: 0;
}
.input-icons{
    display: flex;
}
.input-group-text{
    text-align: left;
    padding-left: 18px;
}
.qty{
    width: 80px;
}
.text_style{
    font-size: 12px;
    color: #f00;
    margin-top: -14px;
}
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
.customcross{
    font-size: 15px;
    background: #ffffff;
    border: 1px solid #ececec;
    font-weight: bold;
    color: red;
}
.product_holder {
    position: relative;
    margin-bottom: 30px;
    border: 1px solid #f1f1f1;
    padding: 10px 10px;
    height: 330px;
}
.product_img {
    width: 100%;
    height: 75%;
    overflow: hidden;
}
.product_img img {
    width: 100%;
    display: block;
    height: auto;
}

.product_name {
    color: #454545;
    text-transform: uppercase;
    font-weight: 600;
    padding: 6px 0px 5px;
    display: block;
}

.product_name:hover{
    text-decoration: none;
    color: #000;
}

.product_face b {
    font-size: 12px;
    color: #505050;
    padding: 0px 5px;
    border-right: 1px solid #bfbfbf;
}

.product_face b:last-child{
    border-right: 0px !important;
}

.dimension {
    font-size: 15px;
    color: #8c8c8c;
}


.product_edit {
    background-image: linear-gradient(#ff5c50, #cc2f24);
    border: 0;
    color: #fff;
    font-size: 13px;
    padding: 4px 8px;
    border-radius: 5px;
    font-weight: 500;
    position: absolute;
    bottom: 8px;
    right: 10px;
    display: none;
}

.product_holder:hover .product_edit{
    display: block;
}
</style>
<script>
    function Tooltip(tooltipValue) {
        let TooltipCode2 =
            `<i class="fa fa-info-circle field_info tooltip" aria-hidden="true">
                <span class="tooltiptext info_tooltip">` + tooltipValue + `</span>
            </i>`;
        return TooltipCode2;
    }
</script>
<div class="app-main__outer">
    <div class="app-main__inner">

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
        <form method="post" action="{{route('subaddironmongery')}}" enctype="multipart/form-data" id="ironmongery-form">
            {{csrf_field()}}
            <input type="hidden" name="addironmongeryID" value="@if(isset($item)){{$item->id}}@endif">
            <input type="hidden" name="ProjectId" id="ProjectId" value="@if(!empty($pid)){{$pid}}@endif">
            <div class="tab-content">
                <!-- Fitting Hardware/Ironmongery -->
                <div class="main-card mb-3 custom_card">
                    <div class="">
                        <div class="tab-content">
                            <div class="card-header">
                                <h5 class="card-title" style="margin-top: 10px">Fitting Hardware/Ironmongery </h5>
                                <input type="hidden" id="ironIronmongerydata">
                                <input type="hidden" id="currency">
                            </div>
                            <div class="">
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <label for="Setname">Set Name<span class="text-danger">*</span></label>
                                        <div class="input-icons">
                                            <input type="text" name="Setname" id="Setname" class="form-control" value="@if(isset($item)){{$item->Setname}}@else{{old('Setname')}}@endif" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Total Price</label>
                                        <div class="input-icons">
                                            <input type="text" name="totalprice" id="totalprice" value="@if(isset($item)){{$item->totalprice}}@else{{0}}@endif" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Discounted Price <span class="text-danger">*</span></label>
                                        <div class="input-icons">
                                            <input type="text" min="0" name="discountprice" value="@if(isset($item)){{$item->discountprice}}@endif" class="form-control" required oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1'); if(!/^\d*\.?\d{0,2}$/.test(this.value)) { this.value = this.value.slice(0, -1); }">

                                        </div>
                                    </div>
                                    <div class="col-md-12"></div>
                                </div>
                                <div class="form-row main-form">
                                    @if(!isset($item) && empty($item) && $item == '')
                                    @foreach ($list as $val)
                                        @php
                                            $category = $val->category;
                                            $quantity = $val->quantity;
                                            if($category == 'DoorSignage'){
                                                $category = 'DoorSinage';
                                                $quantity = 'doorSignageQty';
                                            }
                                            if($category == 'FaceFixedDoorClosers'){
                                                $category = 'FaceFixedDoorCloser';
                                            }
                                            if($category == 'KeyholeEscutcheon'){
                                                $category = 'KeyholeEscutchen';
                                            }
                                            if($category == 'Airtransfergrills'){
                                                $category = 'AirTransferGrill';
                                            }
                                            if($category == 'LocksandLatches'){
                                                $category = 'LocksAndLatches';
                                            }
                                        @endphp
                                        <div class="col-md-3 mt-3" id="main-{{ $val->category }}">
                                            <div class="position-relative form-group">
                                                <div class="d-flex justify-content-between">
                                                    <label for="{{ $val->key }}">{{ $val->name }}</label><i class="fa fa-times" aria-hidden="true" onclick="clearInput('{{ $val->category }}Key','{{ $quantity }}','{{ $val->category }}Value')" style="font-size: 12px;margin: 8px;"></i>
                                                </div>
                                                <div class="input-icons">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fa fa-info icon" id="{{ $val->data_fill }}" onClick="IronMongery('{{ $val->category }}','{{ $val->name }}', this)"></i>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="{{ $val->Value }}[]" id="{{ $val->category }}Value" value="">

                                                        <input type="hidden" name="{{ $val->Price }}[]" class="price" value="">

                                                        <input type="text" name="{{ $val->key }}[]" id="{{ $val->category }}Key" class="form-control bg-white {{ $val->category }}Key"  value="" readonly>

                                                    </div>

                                                    <input type="hidden" id="{{ $category }}id_1" value="1">

                                                    <input type="hidden" id="{{ $category }}val" value="1">

                                                    <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" id="{{ $quantity }}" name="{{ $quantity }}[]" class="form-control qty">

                                                    <button type="button" class="btn customcross">
                                                        <i style="margin-top: -5px;color: black;" class="fa fa-plus cursor-pointer" data-id="main-{{ $val->category }}" onclick="addMoreCategory(this , '{{ $val->category }}','{{ $val->name }}','{{ $val->key }}','{{ $val->data_fill }}','{{ $val->Value }}','{{ $val->quantity }}','{{ $val->Price }}','{{ $val->msg }}','{{ $category }}')"></i>
                                                    </button>

                                                </div>
                                            </div>
                                            <div class="text_style {{ $val->msg }}" id="{{ $val->msg }}"></div>
                                        </div>
                                        {{-- <div id="appendData{{ $val->category }}"></div> --}}
                                    @endforeach
                                @else

                                    @foreach ($list as $val)

                                    @php
                                        $category = $val->category;
                                        $quantity = $val->quantity;
                                        if($category == 'DoorSignage'){
                                            $category = 'DoorSinage';
                                            $quantity = 'doorSignageQty';
                                        }
                                        if($category == 'FaceFixedDoorClosers'){
                                            $category = 'FaceFixedDoorCloser';
                                        }
                                        if($category == 'KeyholeEscutcheon'){
                                            $category = 'KeyholeEscutchen';
                                        }
                                        if($category == 'Airtransfergrills'){
                                            $category = 'AirTransferGrill';
                                        }
                                        $catPrice = $category.'Price';
                                        if($category == 'LocksandLatches'){
                                            $category = 'LocksAndLatches';
                                        }
                                    @endphp
                                    @php
                                        $data = explode(",",$item->$category);
                                        $qty = explode(",",$item->$quantity);
                                        $count = count($data);
                                        if($count == 0){
                                            $count = 1;
                                        }
                                        $j = 0;
                                    @endphp

                                    <div class="col-md-3 mt-3" id="main-{{ $val->category }}">
                                        @if(isset($val->val_name) && count($val->val_name))
                                            @foreach ($val->val_name as $k => $n)
                                                @php
                                                    $j++;
                                                @endphp
                                                <div class="position-relative form-group" @if ($k > 0)id="child-{{ $val->category }}"@endif>
                                                    @if($k == 0)
                                                    <div class="d-flex justify-content-between">
                                                        <label for="{{ $val->key }}">{{ $val->name }}</label><i class="fa fa-times" aria-hidden="true" onclick="clearInput('{{ $val->category }}Key','{{ $quantity }}','{{ $val->category }}Value')" style="font-size: 12px;margin: 8px;"></i>
                                                    </div>
                                                    @else
                                                    <label for="{{ $val->key }}">{{ $val->name }}
                                                        @if(!empty($tooltip->$val->key))
                                                        <script type="text/javascript">
                                                        document.write(Tooltip('{{$tooltip->$val->key }}'));
                                                        </script>
                                                        @endif
                                                    </label>
                                                    @endif
                                                    <div class="input-icons">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <i class="fa fa-info icon" id="{{ $val->data_fill }}" onClick="IronMongery('{{ $val->category }}','{{ $val->name }}', this)"></i>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="{{ $val->Value }}[]" id="{{ $val->category }}Value" value="@if(isset($data[$k])){{$data[$k] }}@else{{old($category)}}@endif">

                                                            <input type="hidden" name="{{ $val->Price }}[]" class="price" value="@if(isset($$catPrice[$k])){{$$catPrice[$k]}}@endif">

                                                            <input type="text" name="{{ $val->key }}[]" id="{{ $val->category }}Key" class="form-control bg-white {{ $val->category }}Key"  value="@if(isset($qty[$k])){{ $n }}@endif" readonly>

                                                        </div>

                                                        <input type="hidden" id="{{ $category }}id_{{ $j }}" value="{{ $j }}">

                                                        <input type="hidden" id="{{ $category }}val" value="{{ $j }}">

                                                        <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" id="{{ $quantity }}" name="{{ $quantity }}[]" value="@if($qty[$k]){{$qty[$k] }}@else{{old($quantity)}}@endif" class="form-control qty {{ $quantity }}" placeholder="QTY" @if(isset($item) && isset($qty[$k]) && !empty($qty[$k])){{ 'required' }}@endif>

                                                        @if ($k >= 1)
                                                        <button type="button" class="btn customcross" onclick="removeMe(this)" data-id="child-{{ $category }}" data-main-id="main-{{ $category }}">X</button>
                                                        @endif

                                                        @if ($k == 0)
                                                        <button type="button" class="btn customcross">
                                                            <i style="margin-top: -5px;color: black;" class="fa fa-plus cursor-pointer" data-id="main-{{ $val->category }}" onclick="addMoreCategory(this , '{{ $val->category }}','{{ $val->name }}','{{ $val->key }}','{{ $val->data_fill }}','{{ $val->Value }}','{{ $val->quantity }}','{{ $val->Price }}','{{ $val->msg }}','{{ $category }}')"></i>
                                                        </button>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="text_style {{ $val->msg }}" id="{{ $val->msg }}"></div>
                                            @endforeach

                                        @else
                                            @php
                                                $j++;
                                            @endphp
                                            <div class="position-relative form-group">
                                                <div class="d-flex justify-content-between">
                                                    <label for="{{ $val->key }}">{{ $val->name }}</label><i class="fa fa-times" aria-hidden="true" onclick="clearInput('{{ $val->category }}Key','{{ $quantity }}','{{ $val->category }}Value')" style="font-size: 12px;margin: 8px;"></i>
                                                </div>
                                                    @if(!empty($tooltip->$val->key))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->$val->key }}'));
                                                    </script>
                                                    @endif

                                                <div class="input-icons">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fa fa-info icon" id="{{ $val->data_fill }}" onClick="IronMongery('{{ $val->category }}','{{ $val->name }}', this)"></i>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="{{ $val->Value }}[]" id="{{ $val->category }}Value" value="">

                                                        <input type="hidden" name="{{ $val->Price }}[]" class="price" value="">

                                                        <input type="text" name="{{ $val->key }}[]" id="{{ $val->category }}Key" class="form-control bg-white {{ $val->category }}Key"  value="" readonly>

                                                    </div>

                                                    <input type="hidden" id="{{ $category }}id_{{ $j }}" value="{{ $j }}">

                                                    <input type="hidden" id="{{ $category }}val" value="{{ $j }}">

                                                    <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" id="{{ $quantity }}" name="{{ $quantity }}[]" value="" class="form-control qty {{ $quantity }}" placeholder="QTY">

                                                    <button type="button" class="btn customcross">
                                                        <i style="margin-top: -5px;color: black;" class="fa fa-plus cursor-pointer" data-id="main-{{ $val->category }}" onclick="addMoreCategory(this , '{{ $val->category }}','{{ $val->name }}','{{ $val->key }}','{{ $val->data_fill }}','{{ $val->Value }}','{{ $val->quantity }}','{{ $val->Price }}','{{ $val->msg }}','{{ $category }}')"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="text_style {{ $val->msg }}" id="{{ $val->msg }}"></div>
                                        @endif
                                    </div>

                                    {{-- @endfor --}}
                                    {{-- <div id="appendData{{ $val->category }}"></div> --}}
                                @endforeach
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                <div class="main-card mb-3 custom_card">
                    <div class="d-block text-right">
                        <button type="submit" id="submitMyForm" class="d-none"></button>
                        <button type="button" id="submit" class="btn-wide btn btn-success">
                            @if(isset($item)){{'Update Now'}} @else{{'Submit Now'}}@endif
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
@endsection
@section('js')
<script src="{{url('/')}}/js/AddIronmongeryjsfile.js"></script>
<script>
    var currendDis = '';
    $('.icon').addClass('cursor-pointer');
    $(document).ready(function(){
            $.ajax({
                url: "{{route('ironmongery-info/filter-iron-mongery-category')}}",
                method:"POST",
                data:{_token:$("#_token").val()},
                dataType:"Json",
                success: function(result){ console.log(result)
                    if(result.status=="ok"){
                        $("#ironIronmongerydata").val(JSON.stringify(result.data));
                        $("#currency").val(result.currency);
                        // alert(result.data)
                    }else{
                        $("#ironIronmongerydata").html('');
                    }
                }
            });



        // $(document).on('change', '#configurableitems', function() {
        //     let pageId = $(this).val();
        //     let pid = $('#ProjectId').val();
        //     let url = "{{url('project/add-ironmongery/')}}/"+pid+'/'+pageId;
        //     window.location.href =url;
        // })
    })

    async function removeMe(dis) {
        let id = $(dis).attr('data-id');
        $(`#${id}`).remove();
        setTimeout(() => {
            syncRequire($(dis).attr('data-main-id'));
            TotalPrice();
        }, 500);
    }

    async function addMoreCategory(dis,category,name,key,data_fill,Value ,quantity,Price,msg,saveCategory){
        var cateory_id = $('#'+saveCategory+'_val').val();
        $('#'+saveCategory+'_val').val(parseInt(cateory_id) + 1);
        var cateoryCount =  $('#'+saveCategory+'_val').val();

        var html = '';
        // html += '<div class="col-md-3 mt-3" id="child-'+category+'">';
        html += '<div class="position-relative form-group" id="child-'+category+'">';
        html += '<label for="'+key+'">'+name+'</label>';
        html += '<div class="input-icons"><div class="input-group">';
        html += '<div class="input-group-prepend">';
        html += ' <div class="input-group-text">';
        html += '<i class="fa fa-info icon cursor-pointer" id="'+data_fill+'" onClick="IronMongery(\''+category+'\',\''+name+'\', this)"></i></div></div>';

        html += '<input type="hidden" name="'+Value+'[]" id="'+category+'Value" value="" required>';
        html += '<input type="hidden" name="'+Price+'[]" class="price" value="">';
        html += '<input type="text" name="'+key+'[]" id="'+category+'Key" class="form-control bg-white '+category+'Key" value="" readonly required>';
        html += '</div>';

        html += '<input type="hidden" name="'+saveCategory+'_id_'+cateoryCount+'" id="'+saveCategory+'_id_'+cateoryCount+'" value="'+cateoryCount+'">';

        html += '<input type="number" min="1" id="'+quantity+'" name="'+quantity+'[]" value="" class="form-control qty '+quantity+'" placeholder="QTY" required>';
        html += '<button type="button" class="btn customcross" onclick="removeMe(this)" data-id="child-'+category+'" data-main-id="main-'+category+'">X</button>';
        html += ' </div></div></div>';
        html += '<div class="text_style '+msg+'" id="'+msg+'" ></div>';
        // html += '</div>';


        let currentId = $(dis).attr('data-id');
        let fields = document.querySelectorAll('.main-form > div');
        var metchDiv = 0;
        var countDv = 0;
        var isApnd = 0;
        var lastDiv = '';
        $(`#${currentId}`).append(html)
        setTimeout(() => {
            syncRequire(currentId);
        }, 500);
    }

    async function syncRequire(id) {
        let crntInput = document.querySelectorAll(`#${id} input`);
        let required = document.querySelectorAll(`#${id} .form-group`).length <= 1 ? false : true;
        crntInput.forEach(fld => {
            if(required){
                fld.setAttribute('required', required);
            }else{
                fld.removeAttribute('required', required);
            }
        });
    }

    function IronMongery(ironCategoryType,ironCategoryName, dis){
        if(ironCategoryName == 'Push Handles'){
            ironCategoryName = 'Push Plates';
        }
        var data = $("#ironIronmongerydata").val();
        var currency = $("#currency").val();

        if(data!=''){
            data =  JSON.parse(data);
            var lenght = data.length;
            innerHtml = '';
            for(var index = 0; index<lenght;index++){
                if(data[index].Category==ironCategoryType){
                    if(data[index].Category == 'PushHandles'){
                        data[index].Category = 'PushPlates';
                    }
                    var image = "{{url('/')}}/uploads/IronmongeryInfo/"+data[index].Image;
                    innerHtml+=' <div class="col-md-4 col-sm-6 col-6">';
                    innerHtml+='<div class="product_holder">';
                    innerHtml+='<div class="product_img"><img src="'+image+'"></div>';
                    innerHtml+='<a class="product_name" href="#"><span>'+data[index].Code+'-</span> '+data[index].Name+'</a>';
                    innerHtml+='<div class="product_face">';
                    innerHtml+='<b>'+data[index].FireRating+'</b>';
                    innerHtml+='<b>'+currency+data[index].Price+'</b>';
                    innerHtml+='<b>'+data[index].Category+'</b>';
                    innerHtml+='</div>';
                    // innerHtml+='<a href="javascript:void(0);" onClick="makeOption('+data[index].id+',\''+data[index].Name+'\',\''+data[index].Code+'\',\''+ironCategoryType+'\','+data[index].Price+')" class="product_edit" id="product_edit">Select</a>';
                    let name = data[index].Name.replace(/"/g, '&quot;'); // Replace double quotes with &quot;
                    innerHtml += '<a href="javascript:void(0);" onClick="makeOption(' +
                                    data[index].id + ', \'' + name + '\', \'' +
                                    data[index].Code + '\', \'' + ironCategoryType + '\', ' +
                                    data[index].Price + ')" class="product_edit" id="product_edit">Select</a>';
                    innerHtml+='</div></div>';
                }
            }
            if(innerHtml==''){
                innerHtml+='<div class=" col-md-12 alert alert-danger" role="alert"> No '+ ironCategoryName.toLowerCase() +' found </div>'

            }
        } else {
            innerHtml = '';
            innerHtml+='<div class=" col-md-12 alert alert-danger" role="alert"> No '+ ironCategoryName.toLowerCase() +' found </div>'
        }
        currendDis = dis;
        $("#content").empty().append(innerHtml);
        $("#modalTitle").empty().append('Select '+ironCategoryName);
        $("#iron").modal('show');
    }
    function makeOption(id,name,code,category,price){
        let prntDv = $(currendDis).parent().parent().parent().parent();
        $(prntDv).find("#"+category+'Value').val(id);
        $(prntDv).find("#"+category+'Key').val(code+'-'+name);
        $(prntDv).find("#"+category+'Key').siblings('.price').val(price);
        // $("#"+category+'Key').parent('div').siblings('.qty').val('');
        $(prntDv).find("#"+category+'Key').parent('div').siblings('.qty').attr({'required':true});
        $("#iron").modal('hide');

        TotalPrice();


    }

    $(document).on('change','.qty',function(){
        TotalPrice();
    })


    function TotalPrice(){
        let totPrice = 0;
        $('.price').each(function(){
            var input = $(this); // This is the jquery object of the input, do what you will
            let price = parseFloat(input.val());
            let quantity = parseInt($(input).parent('.input-group').siblings('.qty').val());
            if(price != '' && quantity != ''){
                let calculatePrice = quantity*price;
                if(!isNaN(calculatePrice)){
                    totPrice += calculatePrice;
                }
            }
        });
        // $('#totalprice').empty().val(totPrice);
        // $('input[name=discountprice]').empty().val(totPrice);

        $('#totalprice').empty().val(parseFloat(totPrice).toFixed(2));
$('input[name=discountprice]').empty().val(parseFloat(totPrice).toFixed(2));

    }

    $(document).on('click','#submit',function(e){
        event.preventDefault();
        let required = document.querySelectorAll('input:not([type="hidden"])[required]');
        $('.border-danger').removeClass('border-danger');
        let isValid = 1;
        required.forEach((tag)=>{
            if(tag.value == ''){
                console.log(tag);
                tag.classList.add('border-danger');
                isValid = 0;
            }else{
                tag.classList.remove('border-danger');
            }
        });
        if(!isValid){
            return false;
        }
        $('#submitMyForm').click();
    })

    $(document).ready(function() {
        $("input[type=number]").on("focus", function() {
            $(this).on("keydown", function(event) {
                if (event.keyCode === 38 || event.keyCode === 40) {
                    event.preventDefault();
                }
            });
        });
    });
    // $(document).on('keyup','.hingesQty',function(e){
    //     e.preventDefault();
    //     if($(this).val() != '' || $('.2HingesKey').val() != ''){
    //         $(".HingesKey").prop('required',true);
    //         if($(".HingesKey").val()=='' || $(".HingesKey").val()==undefined){
    //         $(".msg").text("Please select the Hinges then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $(".hingesQty").prop('required',false);
    //         $(".msg").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#data_fill_Hinges").click(function(){
    //     $("#msg").empty();
    //     // $("#submit").prop('disabled',false);
    // });


    // $("#floorSpringQty").keyup(function(){
    //     if($(this).val() != '' || $('#FloorSpringKey').val() != ''){
    //         $("#FloorSpringKey").prop('required',true);
    //         if($("#FloorSpringKey").val()=='' || $("#FloorSpringKey").val()==undefined){
    //         $("#msg2").text("Please select the Floor Spring then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#floorSpringQty").prop('required',false);
    //         $("#msg2").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#data_fill_floorSpring").click(function(){
    //     $("#msg2").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#lockesAndLatchesQty").keyup(function(){
    //     if($(this).val() != '' || $('#LocksandLatchesKey').val() != ''){
    //         $("#LocksandLatchesKey").prop('required',true);
    //         if($("#LocksandLatchesKey").val()=='' || $("#LocksandLatchesKey").val()==undefined){
    //         $("#msg3").text("Please select the Lock And Latches then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#lockesAndLatchesQty").prop('required',false);
    //         $("#msg3").empty();
    //         // $("#submit").prop('disabled',false);
    //     }

    // });
    // $("#data_fill_lockesAndLatches").click(function(){
    //     $("#msg3").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#flushBoltsQty").keyup(function(){
    //     if($(this).val() != '' || $('#FlushBoltsKey').val() != ''){
    //         $("#FlushBoltsKey").prop('required',true);
    //         if($("#FlushBoltsKey").val()=='' || $("#FlushBoltsKey").val()==undefined){
    //         $("#msg4").text("Please select the Flush Bolts then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#flushBoltsQty").prop('required',false);
    //         $("#msg4").empty();
    //         // $("#submit").prop('disabled',false);
    //     }

    // });
    // $("#data_fill_FlushBolts").click(function(){
    //     $("#msg4").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#concealedOverheadCloserQty").keyup(function(){
    //     if($(this).val() != '' || $('#ConcealedOverheadCloserKey').val() != ''){
    //         $("#ConcealedOverheadCloserKey").prop('required',true);
    //         if($("#ConcealedOverheadCloserKey").val()=='' || $("#ConcealedOverheadCloserKey").val()==undefined){
    //         $("#msg5").text("Please select the Over head Closers then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#concealedOverheadCloserQty").prop('required',false);
    //         $("#msg5").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#data_fill_concealedOverheadCloser").click(function(){
    //     $("#msg5").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#pullHandlesQty").keyup(function(){
    //     if($(this).val() != '' || $('#PullHandlesKey').val() != ''){
    //         $("#PullHandlesKey").prop('required',true);
    //         if($("#PullHandlesKey").val()=='' || $("#PullHandlesKey").val()==undefined){
    //         $("#msg6").text("Please select the Pull Handles then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#pullHandlesQty").prop('required',false);
    //         $("#msg6").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#data_fill_PullHandles").click(function(){
    //     $("#msg6").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#pushHandlesQty").keyup(function(){
    //     if($(this).val() != '' || $('#PushHandlesKey').val() != ''){
    //         $("#PushHandlesKey").prop('required',true);
    //         if($("#PushHandlesKey").val()=='' || $("#PushHandlesKey").val()==undefined){
    //         $("#msg7").text("Please select the Flush Bolts then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#pushHandlesQty").prop('required',false);
    //         $("#msg7").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#data_fill_PushHandles").click(function(){
    //     $("#msg7").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#kickPlatesQty").keyup(function(){
    //     if($(this).val() != '' || $('#KickPlatesKey').val() != ''){
    //         $("#KickPlatesKey").prop('required',true);
    //         if($("#KickPlatesKey").val()=='' || $("#KickPlatesKey").val()==undefined){
    //         $("#msg8").text("Please select the Kick plates then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#kickPlatesQty").prop('required',false);
    //         $("#msg8").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#data_fill_KickPlates").click(function(){
    //     $("#msg8").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#doorSelectorsQty").keyup(function(){
    //     if($(this).val() != '' || $('#DoorSelectorsKey').val() != ''){
    //         $("#DoorSelectorsKey").prop('required',true);
    //         if($("#DoorSelectorsKey").val()=='' || $("#DoorSelectorsKey").val()==undefined){
    //         $("#msg9").text("Please select the Door Selectors then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#doorSelectorsQty").prop('required',false);
    //         $("#msg9").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#data_fill_doorSelectors").click(function(){
    //     $("#msg9").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#panicHardwareQty").keyup(function(){
    //     if($(this).val() != '' || $('#PanicHardwareKey').val() != ''){
    //         $("#PanicHardwareKey").prop('required',true);
    //         if($("#PanicHardwareKey").val()=='' || $("#PanicHardwareKey").val()==undefined){
    //         $("#msg10").text("Please select the Panic Hardware then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#panicHardwareQty").prop('required',false);
    //         $("#msg10").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#data_fill_panicHardware").click(function(){
    //     $("#msg10").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#doorSecurityViewerQty").keyup(function(){
    //     if($(this).val() != '' || $('#DoorsecurityviewerKey').val() != ''){
    //         $("#DoorsecurityviewerKey").prop('required',true);
    //         if($("#DoorsecurityviewerKey").val()=='' || $("#DoorsecurityviewerKey").val()==undefined){
    //         $("#msg11").text("Please select the Door security viewer then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#doorSecurityViewerQty").prop('required',false);
    //         $("#msg11").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#data_fill_doorSecurityViewer").click(function(){
    //     $("#msg11").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#morticeddropdownsealsQty").keyup(function(){
    //     if($(this).val() != '' || $('#MorticeddropdownsealsKey').val() != ''){
    //         $("#MorticeddropdownsealsKey").prop('required',true);
    //         if($("#MorticeddropdownsealsKey").val()=='' || $("#MorticeddropdownsealsKey").val()==undefined){
    //         $("#msg12").text("Please select the Morticed dropdown seals then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#morticeddropdownsealsQty").prop('required',false);
    //         $("#msg12").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#data_fill_morticeddropdownseals").click(function(){
    //     $("#msg12").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#facefixeddropsealsQty").keyup(function(){
    //     if($(this).val() != '' || $('#FacefixeddropsealsKey').val() != ''){
    //         $("#FacefixeddropsealsKey").prop('required',true);
    //         if($("#FacefixeddropsealsKey").val()=='' || $("#FacefixeddropsealsKey").val()==undefined){
    //         $("#msg13").text("Please select the Face fixed drop seals then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#facefixeddropsealsQty").prop('required',false);
    //         $("#msg13").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#data_fill_facefixeddropseals").click(function(){
    //     $("#msg13").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#thresholdSealQty").keyup(function(){
    //     if($(this).val() != '' || $('#ThresholdSealKey').val() != ''){
    //         $("#ThresholdSealKey").prop('required',true);
    //         if($("#ThresholdSealKey").val()=='' || $("#ThresholdSealKey").val()==undefined){
    //         $("#msg14").text("Please select the Threshold Seal then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#thresholdSealQty").prop('required',false);
    //         $("#msg14").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#data_fill_thresholdSeal").click(function(){
    //     $("#msg14").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#airtransfergrillsQty").keyup(function(){
    //     if($(this).val() != '' || $('#AirtransfergrillsKey').val() != ''){
    //         $("#AirtransfergrillsKey").prop('required',true);
    //         if($("#AirtransfergrillsKey").val()=='' || $("#AirtransfergrillsKey").val()==undefined){
    //         $("#msg15").text("Please select the Air transfer grill then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#airtransfergrillsQty").prop('required',false);
    //         $("#msg15").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#data_fill_airtransfergrills").click(function(){
    //     $("#msg15").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#letterplatesQty").keyup(function(){
    //     if($(this).val() != '' || $('#LetterplatesKey').val() != ''){
    //         $("#LetterplatesKey").prop('required',true);
    //         if($("#LetterplatesKey").val()=='' || $("#LetterplatesKey").val()==undefined){
    //         $("#msg16").text("Please select the Letter plates then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#letterplatesQty").prop('required',false);
    //         $("#msg16").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#data_fill_letterplates").click(function(){
    //     $("#msg16").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#cableWaysQty").keyup(function(){
    //     if($(this).val() != '' || $('#CableWaysKey').val() != ''){
    //         $("#CableWaysKey").prop('required',true);
    //         if($("#CableWaysKey").val()=='' || $("#CableWaysKey").val()==undefined){
    //         $("#msg17").text("Please select the Cable Ways then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#cableWaysQty").prop('required',false);
    //         $("#msg17").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#data_fill_CableWays").click(function(){
    //     $("#msg17").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#safeHingeQty").keyup(function(){
    //     if($(this).val() != '' || $('#SafeHingeKey').val() != ''){
    //         $("#SafeHingeKey").prop('required',true);
    //         if($("#SafeHingeKey").val()=='' || $("#SafeHingeKey").val()==undefined){
    //         $("#msg18").text("Please select the SafeHinge then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#safeHingeQty").prop('required',false);
    //         $("#msg18").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#data_fill_SafeHinge").click(function(){
    //     $("#msg18").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#leverHandleQty").keyup(function(){
    //     if($(this).val() != '' || $('#LeverHandleKey').val() != ''){
    //         $("#LeverHandleKey").prop('required',true);
    //         if($("#LeverHandleKey").val()=='' || $("#LeverHandleKey").val()==undefined){
    //         $("#msg19").text("Please select the Lever Handle then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#leverHandleQty").prop('required',false);
    //         $("#msg19").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#data_fill_LeverHandle").click(function(){
    //     $("#msg19").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#doorSignageQty").keyup(function(){
    //     if($(this).val() != '' || $('#DoorSignageKey').val() != ''){
    //         $("#DoorSignageKey").prop('required',true);
    //         if($("#DoorSignageKey").val()=='' || $("#DoorSignageKey").val()==undefined){
    //         $("#msg20").text("Please select the Door Signage then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#doorSignageQty").prop('required',false);
    //         $("#msg20").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#data_fill_DoorSignage").click(function(){
    //     $("#msg20").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#faceFixedDoorClosersQty").keyup(function(){
    //     if($(this).val() != '' || $('#FaceFixedDoorClosersKey').val() != ''){
    //         $("#FaceFixedDoorClosersKey").prop('required',true);
    //         if($("#FaceFixedDoorClosersKey").val()=='' || $("#FaceFixedDoorClosersKey").val()==undefined){
    //         $("#msg21").text("Please select the Face Fixed Door Closer then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#faceFixedDoorClosersQty").prop('required',false);
    //         $("#msg21").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#data_fill_faceFixedDoorClosers").click(function(){
    //     $("#msg21").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#thumbturnQty").keyup(function(){
    //     if($(this).val() != '' || $('#ThumbturnKey').val() != ''){
    //         $("#ThumbturnKey").prop('required',true);
    //         if($("#ThumbturnKey").val()=='' || $("#ThumbturnKey").val()==undefined){
    //         $("#msg22").text("Please Select the Thumbturn then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#thumbturnQty").prop('required',false);
    //         $("#msg22").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#data_fill_Thumbturn").click(function(){
    //     $("#msg22").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#keyholeEscutcheonQty").keyup(function(){
    //     if($(this).val() != '' || $('#KeyholeEscutcheonKey').val() != ''){
    //         $("#KeyholeEscutcheonKey").prop('required',true);
    //         if($("#KeyholeEscutcheonKey").val()=='' || $("#KeyholeEscutcheonKey").val()==undefined){
    //         $("#msg23").text("Please select the Keyhole Escutchen then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#keyholeEscutcheonQty").prop('required',false);
    //         $("#msg23").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#data_fill_keyholeEscutcheon").click(function(){
    //     $("#msg23").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#DoorStopsQty").keyup(function(){
    //     if($(this).val() != '' || $('#DoorStopsKey').val() != ''){
    //         $("#DoorStopsKey").prop('required',true);
    //         if($("#DoorStopsKey").val()=='' || $("#DoorStopsKey").val()==undefined){
    //         $("#msg24").text("Please select the Door Stops then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#DoorStopsQty").prop('required',false);
    //         $("#msg24").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#data_fill_DoorStopsValue").click(function(){
    //     $("#msg24").empty();
    //     // $("#submit").prop('disabled',false);
    // });

    // $("#CylindersQty").keyup(function(){
    //     if($(this).val() != '' || $('#CylindersKey').val() != ''){
    //         $("#CylindersKey").prop('required',true);
    //         if($("#CylindersKey").val()=='' || $("#CylindersKey").val()==undefined){
    //         $("#msg25").text("Please select the Cylinders then add Qty...");
    //         // $("#submit").prop('disabled',true);
    //         }
    //     }
    //     else{
    //         $("#CylindersQty").prop('required',false);
    //         $("#msg25").empty();
    //         // $("#submit").prop('disabled',false);
    //     }
    // });
    // $("#door_fill_CylindersValue").keyup(function(){
    //     $("#msg25").empty();
    //     // $("#submit").prop('disabled',false);
    // });




</script>
<script>
    function clearInput(category, quantity,value){
        document.getElementById(quantity).required = false;
        document.getElementById(category).value = '';
        document.getElementById(quantity).value = '';
        document.getElementById(value).value = '';
        TotalPrice();
    }
</script>
@endsection

<!-- Modal -->
<div class="modal fade" id="iron" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="display:block !important;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" id="modalTitle"></h5>
            </div>
            <div class="modal-body">
                <div class="row" id="content"></div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>
