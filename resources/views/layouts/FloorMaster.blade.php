@include('layouts.Header')


<body>

    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
        <div class="app-main">
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

    @yield('script_section')

</body>

</html>
