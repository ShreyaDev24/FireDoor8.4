// alert(55);
$(document).ready(function() {

  // alert( "ready!" );
  getFloorPlanDoors();
  // alert(window.parent.document.body.style.zoom.toString());
  // window.parent.document.body.style.zoom = 1.5;
  // $('body').css('zoom','80%');
  // $('body').css('zoom','0.8');
  // $('body').css('-moz-transform',scale(0.8, 0.8));

});





  // -89.233742, 277.988262
  // var map = L.map('map').setView([51.505, -0.09], 13);
  // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  //     maxZoom: 9,
  //     attribution: '© OpenStreetMap'
  // }).addTo(map);

  // var imageUrl = "http://localhost/testPro/image_2022_07_12T10_36_23_676Z.png",
  // [-683, -600],
  // [150, 205],

 // initialize the map on the "map" div with a given center and zoom
 const map = L.map("map", {
  // center: [-89.233742, 277.988262],
  center: [51.505, -0.09],
  // center: [17.342761, 78.552432],
  zoom: 13,
});

var imageUrl = $('#FloorStructureSource').text(),
imageBounds = [
  [-683, -600],
  [150, -205],
];

// imageBounds = [
//    [-39.90973623453719, -462.65625000000006],[83.82994542398042, -181.40625]
// ];


//  alert(map.getBounds());
//  console.log(map.getBounds());

var imageOverlay = L.imageOverlay(imageUrl, imageBounds).addTo(map);
map.invalidateSize(true);
map.fitBounds(imageBounds);
// map.fitBounds(imageBounds, { padding: [10, 10] });
map.setZoom(1);

// map.on('moveend', function() {
//   alert(map.getBounds());
//   // alert(map.getBounds());
//   console.log(map.getBounds());
// });


map.on("click", function (ev) {
  // alert(ev.latlng); // ev is an event object (MouseEvent in this case)

  $("#myModalFloorPlan").modal();

  var lat = ev.latlng.lat;
  var lng = ev.latlng.lng;

  $('input[name="latPosition"]').val(lat);
  $('input[name="lngPosition"]').val(lng);

});

$(".linkDoorBtn").on("click", function (ev) {

  addFloorPlanDoors();

  // $(".leaflet-image-layer").css({"margin-left": "399px","margin-top": "138px"});



});

function removeFloorPlanDoors(id, marker){

  $.ajax({
      type: "POST",
      url: $('#remove-floor-plan-door').text(),
      data: {
          _token: $("#_token").val(),
          id: id
      },
      dataType: 'Json',
      success: function(data) {
          if (data.status == "success") {
              // alert(data.status);
              marker.remove();
              getFloorPlanDoors("option");

          } else {
              swal("Oops!", data.message, "error");
          }
      },
      error: function(data) {
          swal("Oops!", "Something went wrong. Please try again.", "error");
      }
  });

}

function addFloorPlanDoors(){
  var lat = $('input[name="latPosition"]').val();
  var lng = $('input[name="lngPosition"]').val();
  var doorId = $('input[name="doorId"]:checked').val();

  // alert(doorId);

  if(doorId == undefined){
    alert("Please select any door.");
    return 0;
  }

  $.ajax({
      type: "POST",
      url: $('#add-floor-plan-door').text(),
      data: {
          _token: $("#_token").val(),
          lat: lat,
          lng: lng,
          doorId: doorId,
      },
      dataType: 'Json',
      success: function(data) {
          if (data.status == "success") {
              // alert(data.status);

              var htmlPopup ='<div class="floormap-title">'+data.data.DoorType+'</div><div class="floormap-popup"><div class="popup-info"><div class="popup-info-title">Door Ident No.</div><div class="popup-info-data">'+data.data.doorNumber+'</div></div><div class="popup-info"><div class="popup-info-title">Fire Rating</div><div class="popup-info-data">'+data.data.FireRating+'</div></div></div><div class=" flex j-center" style="padding: 0px; margin: 10px 0px 0px;"><div class="" style="padding: 0px; margin: 0px;"><button class="button default unlinkDoorBtn" data-planId="'+data.data.id+'" style="background: rgb(255, 67, 67); color: white; margin-right: 10px; font-size: 14px;">Unlink Door</button></div></div>';

              let Lmarker = L.marker([lat, lng])
                .addTo(map)
                .bindPopup(htmlPopup)
                .on("click", function (ev) {
                  //this.openPopup();
                  let marker =  this;
                  $(".unlinkDoorBtn").on("click", function (ev) {
                    alert(1);
                    console.log(ev);
                    console.log(this);
                    // alert($(this).attr("data-planId"));
                    // marker.remove();
                    marker = marker;
                    removeFloorPlanDoors($(this).attr("data-planId"), marker);
                  });
                });

              getFloorPlanDoors("option");


          } else {
              swal("Oops!", data.message, "error");
          }
      },
      error: function(data) {
          swal("Oops!", "Something went wrong. Please try again.", "error");
      }
  });

}

function updateDoorOptions(data){

  var optionHtml = '';
  data.forEach(element => {
    console.log(element);

    if(!element.id){
        optionHtml += '<li> <div class="row"> <div class="col-md-10 col-sm-10"> <label> <b><i class="fa fa-check-circle" aria-hidden="true"></i>'+element.DoorType+'</b> </label> </div><div class="col-md-2 col-sm-2"> <div class="control-group"> <label class="control control-checkbox"> <input type="radio" name="doorId" class="leaf1_glass_type" value="'+element.itemID+'"> <div class="control_indicator"></div></label> </div></div></div></li>';
    }
  });
  console.log(optionHtml);
  $('.optionHtml').empty().append(optionHtml);

}

function updateDoorLocations(data){

  data.forEach(element => {

    if(element.id){

      var htmlPopup ='<div class="floormap-title">'+element.DoorType+'</div><div class="floormap-popup"><div class="popup-info"><div class="popup-info-title">Door Ident No.</div><div class="popup-info-data">'+element.doorNumber+'</div></div><div class="popup-info"><div class="popup-info-title">Fire Rating</div><div class="popup-info-data">'+element.FireRating+'</div></div></div><div class=" flex j-center" style="padding: 0px; margin: 10px 0px 0px;"><div class="" style="padding: 0px; margin: 0px;"><button class="button default unlinkDoorBtn"  data-planId="'+element.id+'" style="background: rgb(255, 67, 67); color: white; margin-right: 10px; font-size: 14px;">Unlink Door</button></div></div>';

        let Lmarker = L.marker([element.latPosition, element.lngPosition])
          .addTo(map)
          .bindPopup(htmlPopup)
          .on("click", function (ev) {
            // //this.openPopup();
            let marker =  this;
            $(".unlinkDoorBtn").on("click", function (ev) {
              // marker.remove();
              marker = marker;
              removeFloorPlanDoors($(this).attr("data-planId"), marker);
            });
          });
      console.log(Lmarker);

    }


  });

}

function getFloorPlanDoors(updateType=""){
  let projectId = $('#projectId').val();
  $.ajax({
      type: "GET",
      url: $('#get-floor-plan-doors').text(),
      data: {
          _token: $("#_token").val(),
          projectId: projectId
      },
      dataType: 'Json',
      success: function(data) {
          if (data.status == "success") {

            if(updateType == ""){
              updateDoorOptions(data.data);
              updateDoorLocations(data.data);

            }else if(updateType == "option"){
              updateDoorOptions(data.data);

            }else if(updateType == "map"){
              updateDoorLocations(data.data);

            }

          } else {
              swal("Oops!", data.message, "error");
          }
      },
    //   error: function(data) {
    //       swal("Oops!", "Something went wrong. Please try again.", "error");
    //   }
  });

}



