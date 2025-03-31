@foreach($data as $val)

@php
$projectFilesArray = (array)json_decode($val->ProjectFiles);
$projectFilesCount = count(array_filter($projectFilesArray));

@endphp


<div class="col-sm-4 mb-4">
    <div class="custom_card">
        <a href="{{url('project/quotation-list/'.$val->GeneratedKey)}}" class="p_code">{{$val->ProjectName}}</a>
        <div class="project_list_data d_flex_remove">
            <b>Company Name
                {{--<strong class="quotation_status">Expired</strong>--}}
            </b>
            <p>{{$val->CompanyName!=''?$val->CompanyName:'-----------'}}</p>
        </div>

        <div class="project_list_data">
            <b>Name</b>
            <span>{{ucwords($val->ProjectName)}}</span>
        </div>
        <div class="project_list_data">
            <b>Files</b>
            <span>{{$projectFilesCount}}</span>
        </div>
        <div class="project_list_data">
            <b>Quotes</b>
            <span>{{$val->quotesCount!=''?$val->quotesCount:'0'}}</span>
        </div>

        <div class="project_list_data">
            <b>Orders</b>
            <span>{{$val->ordersCount !=''?$val->ordersCount:'0'}}</span>
        </div>

    </div>
</div>

@endforeach

