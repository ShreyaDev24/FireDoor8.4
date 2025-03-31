@extends("layouts.Master")

@section("main_section")
<style>
   body {
	 font-family: arial;
}

.product_delete{
	background-image: linear-gradient(#ff5c50, #cc2f24);
    border: 0;
    font-size: 13px;
    padding: 4px 8px;
    border-radius: 5px;
    font-weight: 500;
    cursor: pointer;
    position: absolute;
    bottom: 8px;
    right: 50px;
    color: white;
    display: none;
}

.swal2-icon.swal2-warning{
	font-size: 20px;
}

.product_holder:hover .product_delete{
	display: block;
}

 textarea {
	 font-size: 16px;
	 width: 100%;
}
 .clear {
	 clear: both;
}
 .zoom {
	 box-sizing: border-box;
	 height: 100%;
	 padding-top: 100%;
	 position: relative;
	 width: 100%;
}
 .zoom::before, .zoom::after {
	 background-clip: content-box;
	 background-color: #fff;
	 border: 10px solid rgba(0, 0, 0, 0.5);
	 border-width: 12px 6px;
	 content: "";
	 height: 6px;
	 left: 20px;
	 pointer-events: none;
	 position: absolute;
	 top: 20px;
	 width: 20px;
}
 .zoom::before {
	 border: 0;
	 height: 20px;
	 margin: 5px 0 0 13px;
	 width: 6px;
	 z-index: 20;
}
 .zoom__inner {
	 background-color: #000;
	 border: 10px solid #000;
	 box-sizing: border-box;
	 height: 100%;
	 left: 0;
	 position: absolute;
	 top: 0;
	 width: 100%;
}
 .zoom__image {
	 display: block;
	 width: 100%;
}
 .zoomed::before {
	 display: none;
}
 .zoomed .zoom__inner {
	 overflow: scroll;
	 -webkit-overflow-scrolling: touch;
}
 .zoomed .zoom__inner::-webkit-scrollbar {
	 height: 0 !important;
	 width: 0 !important;
}
 .zoomed .zoom__image {
	 width: 200%;
}
 @media (min-width: 768px) {
	 .zoom {
		 float: left;
		 height: 200px;
		 margin: 0 20px 20px 0;
		 padding-top: 0;
		 width: 200px;
	}
	 .zoom:hover, .zoom:focus {
		 cursor: zoom-in;
	}
	 .zoom:hover .zoom__image-over, .zoom:focus .zoom__image-over {
		 display: block;
	}
	 .zoom__image-over {
		 box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.5);
		 display: none;
		 height: 398px;
		 left: 100%;
		 margin-left: 20px;
		 overflow: hidden;
		 position: absolute;
		 top: 0;
		 width: 398px;
	}
	 .zoom__image-over-inner {
		 background-repeat: none;
		 background-size: cover;
		 display: block;
		 height: 800px;
		 width: 800px;
         z-index: 111;
        position: relative;
	}
	 .zoomed:hover, .zoomed:focus {
		 cursor: zoom-out;
	}
}
 body {
	 font-family: arial;
}
 textarea {
	 font-size: 16px;
	 width: 100%;
}
 .clear {
	 clear: both;
}
 .zoom {
	 box-sizing: border-box;
	 height: 100%;
	 padding-top: 100%;
	 position: relative;
	 width: 100%;
}
 .zoom::before, .zoom::after {
	 background-clip: content-box;
	 background-color: #fff;
	 border: 10px solid rgba(0, 0, 0, 0.5);
	 border-width: 12px 6px;
	 content: "";
	 height: 6px;
	 left: 20px;
	 pointer-events: none;
	 position: absolute;
	 top: 20px;
	 width: 20px;
}
 .zoom::before {
	 border: 0;
	 height: 20px;
	 margin: 5px 0 0 13px;
	 width: 6px;
	 z-index: 20;
}
 .zoom__inner {
	 background-color: #000;
	 border: 10px solid #000;
	 box-sizing: border-box;
	 height: 100%;
	 left: 0;
	 position: absolute;
	 top: 0;
	 width: 100%;
}
 .zoom__image {
	 display: block;
	 width: 100%;
}
 .zoomed::before {
	 display: none;
}
 .zoomed .zoom__inner {
	 overflow: scroll;
	 -webkit-overflow-scrolling: touch;
}
 .zoomed .zoom__inner::-webkit-scrollbar {
	 height: 0 !important;
	 width: 0 !important;
}
 .zoomed .zoom__image {
	 width: 200%;
}
 @media (min-width: 768px) {
	 .zoom {
		 float: left;
		 height: 200px;
		 margin: 0 20px 20px 0;
		 padding-top: 0;
		 width: 200px;
	}
	 .zoom:hover, .zoom:focus {
		 cursor: zoom-in;
	}
	 .zoom:hover .zoom__image-over, .zoom:focus .zoom__image-over {
		 display: block;
	}
	 .zoom__image-over {
		 box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.5);
		 display: none;
		 height: 398px;
		 left: 100%;
		 margin-left: 20px;
		 overflow: hidden;
		 position: absolute;
		 top: 0;
		 width: 398px;
	}
	 .zoom__image-over-inner {
		 background-repeat: none;
		 background-size: cover;
		 display: block;
		 height: 800px;
		 width: 800px;
	}
	 .zoomed:hover, .zoomed:focus {
		 cursor: zoom-out;
	}
}

</style>

<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="card">
            <div class="card-body">
                <div class="tab-content">
                   <form method="post" action="{{ url('ironmongery-info/IronmongeryImport') }}" enctype="multipart/form-data">
                      {{csrf_field()}}
                      <div class="card-body">
                         <div class="form-row">
                            <div class="col-md-3">
                               <div class="position-relative form-group">
                                  <label for="file">Import Excel File</label>
                                  <input name="ExcelFile" id="ExcelFile" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required class="form-control">
                               </div>
                            </div>
                            <div class="col-md-6">
                               <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
                               <input type="hidden" id="base_url" value="{{url('/')}}">
                               <div class="position-relative form-group">
                                  <label for="file" class=""></label>
                                  <input type="submit" value="Submit" class="btn btn-success" style="margin-top: 25px;">
                               </div>
                            </div>
                            <div class="col-md-3">
                               <div class="position-relative form-group">
                                <a href="{{ url('ironmongery-info/IronmongeryExport') }}" class="btn btn-info float-right">Export</a>
                               </div>
                            </div>
                         </div>
                      </div>
                   </form>
                </div>
            </div>
            <div id="accordion">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card-header"><h5 class="card-title">Ironmongery List</h5></div>
                    </div>
                </div>

             @php


                if(isset($IronmongeryInfo->CategoryFieldsJSON)){
                    $categoryFieldsArray = json_decode($IronmongeryInfo->CategoryFieldsJSON);
                }

            @endphp

            @foreach($categoryArray as $categoryIndex => $categoryVal)
                @php  $categoryIndexWithoutSpace = preg_replace('/\s+/', '', $categoryIndex) @endphp
                <div class="question">
                    <header>
                        <h3>@if($categoryIndex == 'Push Plates')
                            Push Plates
                        @else
                            {{$categoryIndex}}
                        @endif</h3><i class="fa fa-chevron-down"></i>
                    </header>
                        <main>
                            <ul class="accordian_list">
                                <div class="row">
                                        @if(!empty($data) && count((array)$data)>0)
                                            @foreach($data as $row)
                                            @if($categoryIndex == 'Push Plates')
                                            @php
                                            $categoryIndexWithoutSpace = 'PushHandles';
                                            @endphp
                                            @endif
                                            @if($categoryIndex == 'Air Transfer Grill')
                                            @php
                                            $categoryIndexWithoutSpace = 'Airtransfergrills';
                                            @endphp
                                            @endif
                                            @if($categoryIndex == 'Locks And Latches')
                                            @php
                                            $categoryIndexWithoutSpace = 'LocksandLatches';
                                            @endphp
                                            @endif

                                            @if($categoryIndex == 'Face Fixed Door Closer')
                                            @php
                                            $categoryIndexWithoutSpace = 'FaceFixedDoorClosers';
                                            @endphp
                                            @endif
                                            @if($categoryIndex == 'Keyhole Escutche')
                                            @php
                                            $categoryIndexWithoutSpace = 'KeyholeEscutcheon';
                                            @endphp
                                            @endif
                                                  @if($row->Category == $categoryIndexWithoutSpace)

                                                        <div class="col-md-3 col-sm-6 col-6">
                                                            <div class="product_holder">
                                                                <div class="product_img">
                                                                    <img src="{{url('uploads/IronmongeryInfo/'.$row->Image)}}">
                                                                    <!-- <div class="zoom">
                                                                    <div class="zoom__inner">
                                                                    <img src="{{url('uploads/IronmongeryInfo/'.$row->Image)}}" data-zoom="{{url('uploads/IronmongeryInfo/'.$row->Image)}}" class="zoom__image" alt="image">
                                                                    </div>
                                                                </div> -->
                                                                </div>
                                                                <a class="product_name" href="#"><span>{{$row->Code}}-</span> {{$row->Name}}</a>
                                                                <div class="product_face">
                                                                    <b>{{$row->FireRating}}</b>
                                                                    <b>{{ $currency }}{{$row->Price}}</b>
                                                                    @if($row->Category == 'PushHandles')
                                                                        <b>PushPlates</b>
                                                                    @else
                                                                        <b>{{$row->Category}}</b>
                                                                    @endif

                                                            </div>
                                                            <!-- <p class="dimension">Dimensions- ${{$row->FireRating}}x${{$row->FireRating}}</p> -->
                                                            <a href="{{url('ironmongery-info/update/'.$row->GeneratedKey)}}" class="product_edit">Edit</a>
                                                            <a href="#" onclick="product_delete('{{$row->id}}','{{$row->Category}}')" class="product_delete">Delete</a>
                                                            </div>
                                                        </div>

                                                  @endif

                                            @endforeach
                                        @endif

                                </div>

                            </ul>

                        </main>
                </div>

            @endforeach

            </div>

        </div>

    </div>
</div>

@endsection


@section('js')

<script>
  // JQUERY
$(document).ready(function() {
  $('#accordion header').click(function() {
    $(this).next()
      .slideToggle(200)
      .closest('.question')
      .toggleClass('active')
      .siblings()
      .removeClass('active')
      .find('main')
      .slideUp(200);
  })
});

</script>

<script>
class Zoom {
  constructor(element) {
    this.element = element;

    this.windowWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

    this.addElements();
    this.setElements();
    this.setData();
    this.setEvents();
  }

  addElements() {
    this.element.innerHTML += '<span class="zoom__image-over"><span class="zoom__image-over-inner"></span></span>';
  }

  setElements() {
    this.inner = this.element.querySelector('.zoom__inner');
    this.image = this.element.querySelector('.zoom__image');
    this.imageOver = this.element.querySelector('.zoom__image-over');
    this.imageOverInner = this.element.querySelector('.zoom__image-over-inner');
  }

  setData() {
    this.data = {
      imageZoom: this.image.getAttribute('data-zoom') || this.image.getAttribute('src')
    }
  }

  setEvents() {
    this.image.addEventListener('mousemove', (event) => {
      const scale = this.imageOver.offsetWidth / this.image.offsetWidth;

      this.imageOverInner.setAttribute('style', 'background-image: url(' + this.data.imageZoom + ');');

      this.imageOver.scrollTop = event.y * scale;
      this.imageOver.scrollLeft = event.x * scale;

      this.inner.scrollTop = event.y;
      this.inner.scrollLeft = event.x;
    });

    this.element.addEventListener('click', (event) => {
      if (this.element.classList.contains('zoomed')) {
        this.element.classList.remove('zoomed');

        this.inner.scrollTop = 0;
        this.inner.scrollLeft = 0;
      } else {
        this.element.classList.add('zoomed');

        this.inner.scrollTop = event.y;
        this.inner.scrollLeft = event.x;
      }

      if (this.data.imageZoom && this.image.getAttribute('src') !== this.data.imageZoom) {
        this.image.setAttribute('src', this.data.imageZoom);
      }
    });
  }
};

const zoom = new Zoom(document.querySelector('.zoom'));
</script>


+<script type="text/javascript">
	$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
	function product_delete(id, Category){
		var category = Category;
		var qty = "hingesQty";
		if(Category=='LocksandLatches'){
			category = "LocksAndLatches";
			qty = "lockesAndLatchesQty";
		}

		else if(Category=='OverheadClosers'){
			category = "ConcealedOverheadCloser";
			qty = "concealedOverheadCloserQty";
		}

		else if(Category=='Airtransfergrills'){
			category = 'AirTransferGrill';
			qty = 'airtransfergrillsQty';
		}

		else if(Category=='DoorSignage'){
			category = 'DoorSinage';
			qty = 'doorSignageQty';
		}

		else if(Category=='FaceFixedDoorClosers'){
			category = 'FaceFixedDoorCloser';
			qty = 'faceFixedDoorClosersQty'
		}

		else if(Category=='KeyholeEscutcheon'){
			category = 'KeyholeEscutchen';
			qty = 'keyholeEscutcheonQty';
		}

		else if(Category=='FloorSpring'){
			qty = 'floorSpringQty';
		}

		else if(Category=='FlushBolts'){
			qty = 'flushBoltsQty';
		}

		else if(Category=='PullHandles'){
			qty = 'pullHandlesQty';
		}

		else if(Category=='PushHandles'){
			qty = 'pushHandlesQty';
		}

		else if(Category=='KickPlates'){
			qty = 'kickPlatesQty';
		}

		else if(Category=='DoorSelectors'){
			qty = 'doorSelectorsQty';
		}

		else if(Category=='Doorsecurityviewer'){
			qty = 'doorSecurityViewerQty';
		}

		else if(Category=='Morticeddropdownseals'){
			qty = 'morticeddropdownsealsQty';
		}

		else if(Category=='Facefixeddropseals'){
			qty = 'facefixeddropsealsQty';
		}

		else if(Category=='AirTransferGrill'){
			qty = 'airtransfergrillsQty';
		}

		else if(Category=='CableWays'){
			qty = 'cableWaysQty';
		}

		else if(Category=='Thumbturn'){
			qty = 'thumbturnQty';
		}
		else if(Category=='KeyholeEscutchen'){
			qty = '	keyholeEscutcheonQty';
		}

		else if(Category=='PanicHardware'){
			qty = 'panicHardwareQty';
		}

		else if(Category=='ThresholdSeal'){
			qty = 'thresholdSealQty';
		}

		else if(Category=='LeverHandle'){
			qty = 'leverHandleQty';
		}

		else if(Category=='SafeHinge'){
			qty = 'safeHingeQty';
		}

		else if(Category=='Letterplates'){
			qty = 'letterplatesQty';
		}


		    swal({
				title: "Are you sure?",
				text: "once delete it. not get back again.",
				type: "warning",
				confirmButtonText: "Yes, visit link!",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
						$.ajax({
							url:`{{url('ironmongery-info/delete/${id}',)}}`,
							type:"POST",
							data:{category:category, qty:qty},
							success:function(res){
								 swal("Success!", "Data Deleted.", "success")
								  .then(function(){
						               window.location.reload();
						            })
							}
						})

					} else if (result.dismiss === 'cancel') {
					    swal(
					      'Cancelled',
					      'Your stay here :)',
					      'error'
					    )
					}
				})
		}


</script>
@endsection
