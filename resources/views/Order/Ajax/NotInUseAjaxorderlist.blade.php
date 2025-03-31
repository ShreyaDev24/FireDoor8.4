@foreach($Quotations as $val)
<div class="col-sm-3 mb-3">
    <div class="QuotationBox">
        @php
        if($val['QuotationStatus'] != ''){
            if($val['QuotationStatus'] == 'Open'){
                $quotation_status = '<strong class="QuotationStatus" style="background: #69e4a6;">'.$val['QuotationStatus'].'</strong>';
            } 
            else if($val['QuotationStatus'] == 'Ordered'){
                $quotation_status = '<strong class="QuotationStatus" style="background: #47a91f;">'.$val['QuotationStatus'].'</strong>';
            } else {
                $quotation_status = '<strong class="QuotationStatus">'.$val['QuotationStatus'].'</strong>';
            }
        } else {
            $quotation_status = null;
        }

        $version = $val['version'] != ""?$val['version']:1;
        @endphp


        @if(Auth::user()->UserType=='2')
            <a href="{{url('quotation/generate/'.$val['QuotationId'])}}" class="QuotationCode">{{$val['QuotationGenerationId'].'-'.$version}}</a>
        @else
            <a href="javascript:void(0);" class="QuotationCode">{{$val['NewQuotationGenerationId'].'-'.$version}}</a>
        @endif
        <div class="QuotationCompanyName">
            <b>{{$val['CompanyName']!=''?$val['CompanyName']:'-----------'}} {!! $quotation_status !!}</b>
        </div>
        <div class="QuotationStatusNumber">GBP 10,187.00</div>
        <div class="QuotationListData">                
            <b>Name</b>
            <span>{{$val['QuotationName']!=''?$val['QuotationName']:'-----------'}}</span>       
            <b>Project</b>
            <span>{{$val['ProjectName']!=''?$val['ProjectName']:'-----------'}}</span>
        </div>
        <div class="QuotationListNumber">
            <b>P.O. Number</b>
            <span>{{$val['PONumber']!=''?$val['PONumber']:'-----------'}}</span>
        </div>
        <div class="QuotationModifiedDate">
            <p>Last modified by Mark woods on 25/02/2021 11:07AM</p>   
        </div>  
        @if(Auth::user()->UserType=='2')
            <div class="filter_action">
                <label for="filter" class="quote_filter">
                    <i class="fas fa-ellipsis-h"></i>
                </label>
                <ul class="QuotationMenu">
                    <li onClick="return openQuotation({{$val['QuotationId']}})">
                        <a href="#" target="_blank"><i class="fas fa-mouse-pointer"></i> Open</a>
                    </li>
                    <li><a href="#"><i class="far fa-copy"></i> Copy</a></li>
                    <li><a href="#"><i class="fas fa-print"></i> Print</a></li>
                    <li><a href="#"><i class="far fa-trash-alt"></i> Delete</a></li>
                    <li><a href="#"><i class="fas fa-file-export"></i> Export</a></li>
                </ul>
            </div>
        @endif
    </div>
</div>
@endforeach
<br>
<div class="col-sm-12 mb-4"><br>
    {{-- {!! $data->render() !!} --}}
</div>

<script>



let popupParent = document.querySelector(".popup-parent");
let btn = document.getElementById("btn");
let btnClose = document.querySelector(".close");
let mainSection = document.querySelector(".mainSection");


btn.addEventListener("click", showPopup);

function showPopup() {
    popupParent.style.display = "block";
}

btnClose.addEventListener("click", closePopup);

function closePopup() {
    popupParent.style.display = "none";
}
popupParent.addEventListener("click", closeOutPopup);

function closeOutPopup(o) {
    if (o.target.className == "popup-parent") {
        popupParent.style.display = "none";
    }
}
</script>