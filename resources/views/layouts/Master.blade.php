@include('layouts.Header')
<style>
    li.nav-item:hover{
        color: rgb(23, 43, 77);
        background-color: rgb(244, 245, 247);
        text-decoration: none;
    }
    .dropdown-menu-header{
        margin-bottom:0rem;
    }
    .dropdown-menu.dropdown-menu-lg {
        min-width: 15rem;
    }
    /* .dropdown-menu {
        transform: translate3d(-167px, 44px, 0px) !important;
    } */
    a{
        color: rgb(23, 43, 77);
    }

    .rounded-circle{
        width: 40px;
        height: 40px;
    }
    .notification-btn{
        border-radius: 50%;
        padding: 3px;
        border: none;
        color: #44546F;
        background: white;
    }
    .notification-btn:hover{
        color: #44546F;
        background-color:#091E4224;
    }
    .notification-box .dropdown-menu{
        top: 6px !important;
        width: 440px;
        max-height: 500px;
        overflow-y: auto;
    }
    .notification-box .dropdown-heading{
        padding: 6px 23px;
    }
    .notification h3 {
    font-size: 20px;
    font-weight: 700;
}
.notification .headeing {
    border-bottom: 1px solid #d6d6d6;
    margin: 0 25px 0;
    position: relative;
}
.notification .headeing h6 {
    color: #3f6ad8;
    font-size: 15px;
    font-weight: 600;
}
.notification .headeing :before {
    content: "";
    position: absolute;
    height: 1px;
    width: 45px;
    bottom: 0;
    left: 0;
    background: #3f6ad8;
}
.noti-message img {
    width: 35px;
    height: 35px;
    margin: 0 auto;
    display: block;
}
.noti-message {
    padding: 15px 25px 0;
}
.noti-message h5 {
    font-size: 14px;
    font-weight: 700;
    padding-right: 20px;
}
.noti-message h5 span {
    font-size: 11px;
    font-weight: normal;
    display: block;
    margin: 5px 0 -5px;
}
.noti-message p {
    font-size: 13px;
}
.blue-dot {
    height: 8px;
    width: 8px;
    background: #3f6ad8;
    border-radius: 100%;
    position: absolute;
    top: 5px;
    right: 20px;
}
.notification-card:hover{
    color: #16181b;
    text-decoration: none;
    background-color: #e0f3ff;
}
.notification-count{
    background-color: red;
    color: white;
    border-radius: 155px;
    text-align: center;
    top: -4px;
    right: -2px;
    font-size: 10px;
    padding: 0px 6px;
}
.overflow-x-hiden{
    overflow-x: hidden;
}
</style>

<body>
    <input name="url_segment1" type="hidden" value="{{ Request::segment(1) }}" />
    <input name="url_segment2" type="hidden" value="{{ Request::segment(2) }}" />
    <input name="url_segment3" type="hidden" value="{{ Request::segment(3) }}" />
    <input name="url_segment4" type="hidden" value="{{ Request::segment(4) }}" />
    <input name="url_segment5" type="hidden" value="{{ Request::segment(5) }}" />
    <input name="base_url" type="hidden" value="{{url('/')}}" />

    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
        <div class="app-header header-shadow">
            <div class="app-header__logo">
                <div class="logo-src">
                    <a href="{{url('/')}}">
                        <img src="{{url('/')}}/CompanyLogo/companylogo.png" style="width: 100%">
                    </a>
                </div>
                <div class="header__pane ml-auto">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                            data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="app-header__menu">
                <span>
                    <button type="button"
                        class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
            </div>
            <div class="app-header__content">
                <div class="app-header-right">
                    @if(Auth::user()->UserType==2 || Auth::user()->UserType==3)
                        <div class="mx-2  dropdown notification-box position-relative">
                            <div class="notification-count position-absolute" id="notificationCountNumber"></div>
                            <button class="notification-btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg width="24" height="24" viewBox="0 0 24 24" role="presentation" class="cursor-pointer"><path d="M6.485 17.669a2 2 0 002.829 0l-2.829-2.83a2 2 0 000 2.83zm4.897-12.191l-.725.725c-.782.782-2.21 1.813-3.206 2.311l-3.017 1.509c-.495.248-.584.774-.187 1.171l8.556 8.556c.398.396.922.313 1.171-.188l1.51-3.016c.494-.988 1.526-2.42 2.311-3.206l.725-.726a5.048 5.048 0 00.64-6.356 1.01 1.01 0 10-1.354-1.494c-.023.025-.046.049-.066.075a5.043 5.043 0 00-2.788-.84 5.036 5.036 0 00-3.57 1.478z" fill="currentColor" fill-rule="evenodd"></path></svg>
                            </button>

                            <div class="dropdown-menu dropdown-menu-right notification" aria-labelledby="dropdownMenuButton" style="top: 10px; transform: translate3d(0, 30px, 0px) !important;">
                                <div class="dropdown-heading">
                                    <h3 class="">Notification</h5>
                                </div>
                                <div class="headeing">
                                    <h6>Direct</h6>
                                </div>
                            {{-- <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#">Something else here</a> --}}
                                <div id="notificationContainer"></div>
                            </div>
                        </div>
                    @endif
                    <div>
                        @if(Auth::user()->UserType==1)
                        Super Admin
                        @elseif(Auth::user()->UserType==2)
                        Door Company
                        @elseif(Auth::user()->UserType==3)
                        User
                        @elseif(Auth::user()->UserType==4)
                        Architect
                        @elseif(Auth::user()->UserType==5)
                        Main Contractor
                        @endif
                    </div>
                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                            class="p-0 btn">
                                            @if(!empty(Auth::user()->UserImage))
                                                @if (Auth::user()->UserType == '3' || (Auth::user()->UserType == '1' && Auth::user()->CreatedBy != ''))
                                                    <img width="42" class="rounded-circle" src="{{url('/')}}/UserImage/{{Auth::user()->UserImage}}" alt="image">
                                                @else
                                                    <img width="42" class="rounded-circle" src="{{url('/')}}/CompanyLogo/{{Auth::user()->UserImage}}" alt="image">
                                                @endif
                                            @else
                                                <img width="42" class="rounded-circle" src="{{url('/')}}/CompanyLogo/default-image.jpg" alt="image" >
                                            @endif
                                            <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true"
                                            class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
                                            <div class="dropdown-menu-header">

                                                <div class="scroll-area-xs" style="height: auto;">
                                                    <div class="scrollbar-container ps">
                                                        <ul class="nav flex-column">
                                                            <li class="nav-item-header nav-item">Activity
                                                            </li>
                                                            @if(Auth::user()->UserType=='1')
                                                            <li class="nav-item">
                                                                <a href="{{route('admin/profile')}}"
                                                                    class="nav-link">Profile
                                                                </a>
                                                            </li>
                                                            @elseif(Auth::user()->UserType=='2')
                                                            <li class="nav-item">

                                                                <a href="{{route('company/profile')}}"
                                                                    class="nav-link">Profile
                                                                </a>
                                                            </li>

                                                            @elseif(Auth::user()->UserType=='4')
                                                            <li class="nav-item">

                                                                <a href="{{route('Architect/profile')}}"  class="nav-link">Profile</a>
                                                            </li>

                                                            @elseif(Auth::user()->UserType=='5')
                                                            <li class="nav-item">

                                                                <a href="{{route('contractor/profile')}}"  class="nav-link">Profile</a>
                                                            </li>

                                                            @elseif(Auth::user()->UserType=='3')
                                                            <li class="nav-item">

                                                                <a href="{{route('user/profile')}}"  class="nav-link">Profile</a>
                                                            </li>
                                                            @endif

                                                            <li class="nav-item">
                                                                <a href="{{route('ChargePassword')}}" class="nav-link">
                                                                    Reset Password
                                                                </a>
                                                            </li>
                                                            <li class="nav-item"  style="border-top: 2px solid #ddd;">
                                                                <a href="javascript:void(0)" onClick="logout()" class="nav-link" >Logout</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content-left  ml-3 header-user-info">
                                    <div class="widget-heading" style="color:white">
                                        {{Auth::user()->FirstName}}
                                    </div>
                                    <div class="widget-subheading" style="color:white">
                                        {{Auth::user()->UserEmail}}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- <div class="header-btn-lg">
                        <button type="button" class="hamburger hamburger--elastic open-right-drawer">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="app-main">
            @include("layouts.sidebar")
            @yield('main_section')
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="ralColor" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ralColorModalLabel">Frame Finish Color</h5>
                    <button type="button" class="btn btn-default btn-close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <div id="printedColor"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <form action="{{route('logout')}}" method="post" id="submitlogout">
       {{csrf_field()}}
    </form>
    @include("layouts.Footer")
    <script>
    //     $(document).on('click','.delproject',function(){
    //     // var r = confirm("Are you sure! you wan't to delete it.");
    //     // if (r == true) {
    //         let id = $(this).siblings('input').val();
    //         $('#projectId1').val(id);
    //         // $('#delSubmit').submit();

    //         // Build form here
    //         var content = "";
    //         element = document.querySelector('#msform_accept_body');
    //         container = $("#msform_accept_body");
    //         container.empty();
    //         div = document.createElement('div');
    //         content += `<input type="text" name="test" /><br>`;
    //         div.innerHTML = content;
    //         element.appendChild(div);
    //         // show now the modal
    //         $('#form_accept_modal').modal('show');




    //     // }
    // })

    </script>
    <script type="text/javascript" src="{{url('/')}}/js/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA2efcZ1Lvfr6qlg0NPEsBh80R12bwy5i4&libraries=places"></script>
    <script type="text/javascript" src="{{url('/')}}/js/google-auto-address.js"></script>






    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="{{url('/')}}/js/datepicker.js"></script>
    <script type="text/javascript" src="{{url('/')}}/js/custom.js"></script>
    <script type="text/javascript" src="{{url('/')}}/js/sweetalert2.all.min.js"></script>
    <script type="text/javascript" src="{{url('/')}}/js/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"
        integrity="sha512-aUhL2xOCrpLEuGD5f6tgHbLYEXRpYZ8G5yD+WlFrXrPy2IrWBlu6bih5C9H6qGsgqnU6mgx6KtU8TreHpASprw=="
        crossorigin="anonymous"></script>

    <script src="{{asset('js/custome-rules.js')}}"></script>

    <script src="{{asset('js/change-event-calculation.js')}}"></script>
    <script src="{{asset('js/pagination.js')}}"></script>
    <script src="{{asset('js/quotation-box.js')}}"></script>


    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    @yield('js')

    <script type="text/javascript">
    function profile() {
        $("#profileview").click();
    }
    function logout(){
        $('#submitlogout').submit();
    }
    $(function(){
        $('.selectpicker').selectpicker();
    })

    </script>

<script>
    $(document).ready(function () {

        function getNotification() {
            $.ajax({
                url: "{{ route('notification') }}",
                type: "POST",
                data: {
                    id: '1',
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'html',
                success: function (data) {
                    $('#notificationContainer').html(data);
                    $('#showNotification').html(data);
                    $('#showNotification .noti-message').addClass('p-0 overflow-x-hiden');
                    let notiMessageDiv = document.querySelector('.noti-message');

                    let childElements = notiMessageDiv.children.length;

                    let countNotification =  document.getElementById('notificationCountNumber');
                    countNotification.innerHTML = childElements > 9 ? "9+" : childElements;
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        }
        getNotification();
        // document.querySelector('.app-container').addEventListener('click', getNotification);
    });
</script>


    <!-- <script>
       $(document).ready(function(){
        $.ajax({
            url: "{{ route('notification') }}",
            type: "POST",
            data: {
                id: '1',
                _token: '{{ csrf_token() }}'
            },
            dataType: 'html',
            success: function (data) {
                $('#notificationContainer').html(data);
                window.location.href = url;
            }
        });

       });
    </script> -->

    @yield('script_section')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.6/css/dataTables.dataTables.css" />

<script src="https://cdn.datatables.net/2.0.6/js/dataTables.js"></script>


</body>

</html>
