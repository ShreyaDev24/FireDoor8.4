<div class="noti-message">
    @foreach($notifications as $notification)
    @php
        $timeDifference = now()->diff($notification['created_at']);
    @endphp
        @switch($notification['notificationType'])

            @case('project')
                <div class="row pb-3 notification-card pt-1">
                    <div class="col-2">
                        @if(!empty($notification['UserImage']))
                            @if ($notification['UserType'] == '3')
                                <img class="rounded-circle" src="{{url('/')}}/UserImage/{{$notification['UserImage']}}" alt="image">
                            @else
                                <img class="rounded-circle" src="{{url('/')}}/CompanyLogo/{{$notification['UserImage']}}" alt="image">
                            @endif
                        @else
                            <img class="rounded-circle" src="{{url('/')}}/CompanyLogo/default-image.jpg" alt="image" >
                        @endif
                    </div>
                    <div class="col-10 pl-0">
                        <a href="{{url('project/quotation-list/'.$notification['GeneratedKey'])}}">
                            <h5>{{$notification['FirstName']}} Created a New Project<span>{{Carbon\Carbon::parse($notification['created_at'])->diffForHumans()}}</span></h5>
                            <p class="m-0">{{$notification['ProjectName']}}</p>
                            <p class="m-0">{{$notification['AddressLine1']}}</p>
                            <div class="blue-dot"></div>
                        </a>                 
                    </div> 
                </div>
                
            @break


            @case('quote')
                <div class="row pb-3 notification-card pt-1">
                    <div class="col-2">
                        @if(!empty($notification['UserImage']))
                            @if ($notification['UserType'] == '3')
                                <img class="rounded-circle" src="{{url('/')}}/UserImage/{{$notification['UserImage']}}" alt="image">
                            @else
                                <img class="rounded-circle" src="{{url('/')}}/CompanyLogo/{{$notification['UserImage']}}" alt="image">
                            @endif
                        @else
                            <img class="rounded-circle" src="{{url('/')}}/CompanyLogo/default-image.jpg" alt="image" >
                        @endif
                    </div>
                    <div class="col-10 pl-0">
                        @php
                        $QVID = $notification['QVID'] != ""?$notification['QVID']:0;
                        @endphp
                        <a href="{{url('quotation/generate/'.$notification['id']).'/'.$QVID}}">
                        <h5>{{$notification['FirstName']}} Created A New Quotation<span>{{Carbon\Carbon::parse($notification['created_at'])->diffForHumans()}} </span></h5>
                        <p class="m-0">{{$notification['QuotationGenerationId']}}</p>
                        <p class="m-0">Name: {{$notification['QuotationName']}}</p>
                        <div class="blue-dot"></div>
                        </a>
                    </div>                    
                </div>
                
            @break


            @case('contractor')
                <div class="row pb-3 notification-card pt-1">
                    <div class="col-2">
                        @if(!empty($notification['UserImage']))
                            @if ($notification['UserType'] == '3')
                                <img class="rounded-circle" src="{{url('/')}}/UserImage/{{$notification['UserImage']}}" alt="image">
                            @else
                                <img class="rounded-circle" src="{{url('/')}}/CompanyLogo/{{$notification['UserImage']}}" alt="image">
                            @endif
                        @else
                            <img class="rounded-circle" src="{{url('/')}}/CompanyLogo/default-image.jpg" alt="image" >
                        @endif
                    </div>
                    <div class="col-10 pl-0">
                        <a href="{{route('contractor/details', $notification['id'])}}">
                        <h5>{{$notification['FirstName']}} Created A New Contractor ({{$notification['ContractorName']}})<span>{{Carbon\Carbon::parse($notification['created_at'])->diffForHumans()}} </span></h5>
                        <p class="m-0">{{$notification['CstCompanyAddressLine1']}}</p>
                        <p class="m-0">{{$notification['CstCompanyAddressLine2']}}</p>
                        <div class="blue-dot"></div>
                        </a>
                    </div>                    
                </div>
                
            @break


            @case('user')
                <div class="row pb-3 notification-card pt-1">
                    <div class="col-2">
                        @if(!empty($notification['UserImage']))
                            @if ($notification['UserType'] == '3')
                                <img class="rounded-circle" src="{{url('/')}}/UserImage/{{$notification['UserImage']}}" alt="image">
                            @else
                                <img class="rounded-circle" src="{{url('/')}}/CompanyLogo/{{$notification['UserImage']}}" alt="image">
                            @endif
                        @else
                            <img class="rounded-circle" src="{{url('/')}}/CompanyLogo/default-image.jpg" alt="image" >
                        @endif
                    </div>
                    <div class="col-10 pl-0">
                        <a href="{{route('user/details', $notification['id'])}}">
                        <h5>{{$notification['FirstName']}} Created A New {{$notification['childType'] == 2 ? 'Admin' : 'User'}}<span>{{Carbon\Carbon::parse($notification['created_at'])->diffForHumans()}} </span></h5>
                        <p class="m-0">{{$notification['childName']}}</p>
                        <p class="m-0">{{$notification['notificationType']}}</p>
                        <div class="blue-dot"></div>
                        </a>
                    </div>                    
                </div>
                
            @break


            @case('teamBoard')
                <div class="row pb-3 notification-card pt-1">
                    <div class="col-2">
                        @if(!empty($notification['UserImage']))
                            @if ($notification['UserType'] == '3')
                                <img class="rounded-circle" src="{{url('/')}}/UserImage/{{$notification['UserImage']}}" alt="image">
                            @else
                                <img class="rounded-circle" src="{{url('/')}}/CompanyLogo/{{$notification['UserImage']}}" alt="image">
                            @endif
                        @else
                            <img class="rounded-circle" src="{{url('/')}}/CompanyLogo/default-image.jpg" alt="image" >
                        @endif
                    </div>
                    <div class="col-10 pl-0">
                        <a href="{{url('project/quotation-list/'.$notification['GeneratedKey'])}}?from=notification">
                        <h5>{{$notification['unreadCount']}} New {{$notification['unreadCount'] == 1 ? 'Comment' : "Comment's" }} added on {{$notification['ProjectName']}}
                        <span>{{Carbon\Carbon::parse($notification['created_at'])->diffForHumans()}} </span></h5>
                        <p class="m-0">Added by <strong>{{$notification['FirstName']}}</strong></p>
                        <p class="m-0">{{strlen($notification['Message']) > 40 ? substr($notification['Message'], 0, 40).'...': $notification['Message'] }}</p>
                        <div class="blue-dot"></div>
                        </a>
                    </div>                    
                </div>
                
            @break

            @case('quoteStatus')
                <div class="row pb-3 notification-card pt-1">
                    <div class="col-2">
                        @if(!empty($notification['UserImage']))
                            @if ($notification['UserType'] == '3')
                                <img class="rounded-circle" src="{{url('/')}}/UserImage/{{$notification['UserImage']}}" alt="image">
                            @else
                                <img class="rounded-circle" src="{{url('/')}}/CompanyLogo/{{$notification['UserImage']}}" alt="image">
                            @endif
                        @else
                            <img class="rounded-circle" src="{{url('/')}}/CompanyLogo/default-image.jpg" alt="image" >
                        @endif
                    </div>
                    <div class="col-10 pl-0">
                        @php
                        $QVID = $notification['QVID'] != ""?$notification['QVID']:0;
                        @endphp
                        <a href="{{url('quotation/generate/'.$notification['id']).'/'.$QVID}}">
                        <h5>{{$notification['QuotationStatus'] == 'Accept'? "Quotation Accepted" : "Quotation Rejected"}}<span>{{$notification['status_accept_reject_at'] ? Carbon\Carbon::parse($notification['status_accept_reject_at'])->diffForHumans() : Carbon\Carbon::parse($notification['created_at'])->diffForHumans()}} </span></h5>
                        @if($notification['QuotationStatus'] == 'Accept')
                            <p class="m-0">{{$notification['QuotationGenerationId']}}</p>
                            <p class="m-0">PO Number : {{$notification['PONumber']}}</p>
                        @elseif($notification['QuotationStatus'] == 'Reject')
                            <p class="m-0">{{$notification['QuotationGenerationId']}}</p>
                            <p class="m-0">{{strlen($notification['rejectreason']) > 40 ? substr($notification['rejectreason'], 0, 40).'...': $notification['rejectreason'] }}</p>
                        @endif
                        <div class="blue-dot"></div>
                        </a>
                    </div>                    
                </div>                
            @break

        @endswitch   
     @endforeach   
        
      
</div>
