@extends("layouts.Master")
@section("main_section")
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
<style>    
    .profile_container,
        .info,
        .back {
            margin: 60px 100px 0px;
            max-width: 900px;
            display: flex;
            overflow-x: hidden;
        }
        .profile_img-LG {
            height: 250px;
            width: 250px;
            border-radius: 50%;
            object-fit: cover;
            object-position: 50% 50%;
            background-position: 40% 50%;
        }
        .description {
            margin-bottom: 30px;
            margin-top: 0px;
        }        
        .profile_img_section {
            margin-right: 50px;
        }        
        .profile_desc_section {
            display: flex;
            flex-direction: column;
        
            margin-left: 50px;
        }
        
        .interests_item {
            display: inline-block;
            padding: 5px 15px;
            margin-right: 7.5px;
            margin-bottom: 10px;
            line-height: 35px;
            border-radius: 50px;
            border: 1px solid #e2e2e2;
            color: #000;
        }
        
        .info {
            margin-top: -20px;
            margin-left: 100px;
        }
        
        .link_img_wrapper {
            width: 40px;
            height: 40px;
            background-color: #f2f2f2;
            border-radius: 10px;
            position: relative;
        }
        
        .link_img {
            height: 20px;
            width: 20px;
            position: absolute;
            right: 0;
            left: 0;
            top: 0;
            bottom: 0;
            margin: auto auto;
        }
        
        .edit_button {
            text-decoration: none;
            color: #fff;
            background: #F44336;
            display: inline-block;
            text-align: center;
            font-size: 18px;
            max-width: 170px;
            width: 100%;
            padding: 10px 0px;
            border-radius: 6px;
            margin-top: 12px;
        }
        
        
        .edit_button:hover{
            color: #fff;
            text-decoration: none;
        }
        
        .profile_name {
            font-size: 30px;
            color: #000;
        }
        
        .interests {
            padding: 20px 0px;
        }
        
        .interests_item b i {
            font-size: 25px;
            vertical-align: middle;
            margin-right: 10px;
        }
        
        
        .profile_details_holder {
            max-width: 1100px;
            width: 100%;
            margin: 0 auto;
        }
        
        .details_box {
            background: #fff;
            border-radius: 16px;
            padding: 20px 30px !important;
            border: 1px solid #ececec;
        }
        
        
        
        
        @media screen and (max-width: 1000px) {
            .profile_container,
            .info,
            .back {
                margin: 60px 33px 0px;
            }
        
            .profile_container {
                flex-direction: column;
            }
        
            .profile_img_section {
                margin: 0 auto;
            }
        
            .profile_img-LG {
                width: 300px;
                height: 300px;
                border-radius: 100%;
            }
        
            
        
            .profile_desc_section {
                margin-left: 0px;
                margin-bottom: 10px;
                margin-top: -40px;
            }
        
            .info {
                margin-top: 10px;
                margin-left: 33px;
            }
        }
        
    </style>

<div class="app-main__outer">
<div class="app-main__inner">
<div class="app-inner-layout chat-layout">
    <div class="details_box">
        <section class="profile_container">
            <div class="profile_img_section">
                @if(!empty(Auth::user()->UserImage ))
                <img class="profile_img-LG" src="{{url('/')}}/CompanyLogo/{{Auth::user()->UserImage}} " />
                @else
                <img class="profile_img-LG" src="{{url('/')}}/CompanyLogo/default-image.jpg" />
                @endif
            </div>

            <div class="profile_desc_section mt-4">
                <h2 class="profile_name mb-2"><b>{{Auth::user()->FirstName}} {{Auth::user()->LastName}}</b></h2>
                @if(Auth::user()->UserType=='2')
                <a href="#" class="edit_button" onclick="editcustomer()">Edit Customer</a>
                <form action="{{route('company/edit')}}" method="post">
                    {{csrf_field()}}
                <input type="hidden" name="edit" value="{{$data->id}}">
                <button type="submit" id="editsubmit"  class="active btn btn-focus mr-1" style="display: none;"></button>
                </form>
                @endif

                <div class="interests">
                    <span class="interests_item"><b><i style="color: #4CAF50;" class="fa fa-phone" aria-hidden="true"></i> {{Auth::user()->UserPhone}}</b></span>
                    <span class="interests_item"><b><i style="color: #F44336;" class="fa fa-envelope" aria-hidden="true"></i> {{Auth::user()->UserEmail}}</b></span>
                </div>
              
                <div class="profile">
                    <p><i class="fa fa-info   icon_style" aria-hidden="true"></i><b>More Information</b>
                    <strong>{{Auth::user()->UserMoreInfo}} </strong></p>
                </div>
                       
            </div>

    </section>
</div>

    

</div>
</div>
</div>

@endsection

