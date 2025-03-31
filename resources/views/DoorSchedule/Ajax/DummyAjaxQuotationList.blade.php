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
                } else if($val['QuotationStatus'] == 'All'){
                    $quotation_status = '<strong class="QuotationStatus" style="background:#808080;">'.$val['QuotationStatus'].'</strong>';
                } else {
                    $quotation_status = '<strong class="QuotationStatus" style="background:red;">'.$val['QuotationStatus'].'</strong>';
                }
            } else {
                $quotation_status = null;
            }

            $version = $val['version'] != ""?$val['version']:1;
            $QVID = $val['QVID'] != ""?$val['QVID']:0;
            $bomTag = $val['bomTag'] != ""?$val['bomTag']:0;
        @endphp


        <a href="{{url('quotation/generate/'.$val['QuotationId']).'/'.$QVID}}" class="QuotationCode">{{$val['QuotationGenerationId'].'-'.$version}}</a>
        <div class="QuotationCompanyName">
            <b>{{$val['CstCompanyName']!=''?$val['CstCompanyName']:'-----------'}}{{$val['editBy']}} {!! $quotation_status !!}</b>
        </div>
        <div class="QuotationStatusNumber">GBP 10,187.00</div>
        <div class="QuotationListData">
            <b>Quotation Name 1111111{{$bomTag}}</b>
            <span>{{$val['QuotationName']!=''?$val['QuotationName']:'-----------'}}</span>
            <b>Project</b>
            <span>{{$val['ProjectName']!=''?$val['ProjectName']:'-----------'}}</span>
            <b>Due Date</b>
            <span>{{$val['ExpiryDate']!=''?$val['ExpiryDate']:'-----------'}}</span>
            <b>Number of Door Sets</b>
            <span>
            {{--  @if($version > 0)  --}}
                {{ NumberOfDoorSets($QVID,$val['QuotationId']) }}
            @endif
            {{--  </span>  --}}
        </div>
        <div class="QuotationListNumber">
            <b>P.O. Number</b>
            <span>{{$val['PONumber']!=''?$val['PONumber']:'-----------'}}</span>
        </div>
        <div class="QuotationModifiedDate">
            <p>Last modified by Mark woods on {{$val['updated_at']}}</p>
        </div>
        <div class="filter_action">
            <label for="filter" class="quote_filter">
                <i class="fas fa-ellipsis-h"></i>
            </label>
            <ul class="QuotationMenu">
                <li>
                    <a href="{{url('quotation/generate/'.$val['QuotationId']).'/'.$QVID}}" target="_blank"><i class="fas fa-mouse-pointer"></i> Open</a>
                </li>
                <li><a href="javascript:void(0);" onClick="CopyQuotation({{$val['QuotationId']}},{{$QVID}});"><i class="far fa-copy"></i> Copy</a></li>
                <li><a href="javascript:void(0);" onClick="PrintInvoice({{$val['QuotationId']}},{{$QVID}},{{$bomTag}});"><i class="fas fa-print"></i> Generate PDF</a></li>

                <li><a href="javascript:void(0);" onClick="ExcelExport({{$val['QuotationId']}},{{$QVID}});">
                    <i class="fas fa-file-export"></i> Export</a>
                </li>
                <li>
                <a href="javascript:void(0);" onClick="DeleteQuotation({{$val['QuotationId']}},{{$QVID}});"><i class="far fa-trash-alt"></i> Delete</a></li>
            </ul>
        </div>

    </div>
</div>
@endforeach
<br>
