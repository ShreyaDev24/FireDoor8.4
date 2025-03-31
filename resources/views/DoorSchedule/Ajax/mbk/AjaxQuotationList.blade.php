 @foreach($data as $row)

            <div class="col-sm-4">
                <div class="project_list_holder">
                @if(Auth::user()->UserType=='2')
                        <a href="{{url('quotation/generate/'.$row->QuotationId)}}" class="p_code">{{$row->QuotationGenerationId}}</a>
                 @else
                     <a href="javascript:void(0);" class="p_code">{{$row->QuotationGenerationId}}</a>
                 @endif


                    <div class="project_list_data">
                        <b>Company Name</b>
                        <p>{{$row->CompanyName!=''?$row->CompanyName:'-----------'}}</p>
                    </div>
                    <div class="project_list_data">
                        <b>Exchange Rate</b>
                        <p>{{$row->Currency!='' || $row->ExchangeRate!=''?$row->Currency.$row->ExchangeRate :"-----------"}}</p>
                    </div>
                    <div class="project_list_data">
                      <b>Name</b>
                      <span>{{$row->QuotationName!=''?$row->QuotationName:'-----------'}}</span>
                    </div>
                    <div class="project_list_data">
                      <b>Project</b>
                      <span>{{$row->ProjectName!=''?$row->ProjectName:'-----------'}}</span>
                    </div>
                    <div class="project_list_data">
                      <b>P.O. Number</b>
                      <span>{{$row->PONumber!=''?$row->PONumber:'-----------'}}</span>
                    </div>


                    @if(Auth::user()->UserType=='2')
                    <div class="filter_action">                
                        <label for="filter" class="quote_filter">
                            <i class="fas fa-ellipsis-h"></i>
                        </label>

                        <ul>
                            <li><a href="{{url('quotation/request/'.$row->QuotationId)}}" target="_blank"><i class="fas fa-mouse-pointer"></i> Open</a></li>
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


            <script>
            $(".quote_filter").click(function(){

                if($(this).next("ul").css("visibility") =="hidden"){

                      $(this).next("ul").css("visibility", "visible");
                      $(this).next("ul").css("opacity", 1);
                      $(this).next("ul").css("transform", "translateX(-50%) scaleY(1)");


                }else{
                      $(this).next("ul").css("visibility", "hidden");
                      $(this).next("ul").css("opacity", 0);
                      $(this).next("ul").css("transform", "translateX(-50%) scaleY(1)");
                }
        //        alert($(".quote_filter").next("ul").css("visibility"));

            });

            </script>

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
                if(o.target.className == "popup-parent"){
                    popupParent.style.display = "none";
                }
            }

            </script>