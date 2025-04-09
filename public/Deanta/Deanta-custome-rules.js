// $(function(){
//     $("#doorPriceCalculate").on("click",function(){
//         $('.loader').css({'display':'block'});
//     });
// });
$("#DDLAH").hide();

function pageIdentity() {
    return $('#pageIdentity').val();
}
// Main option

$("#fireRating").change(function () {
    FireRatingChange();
    //resetAll();
});

$("#doorsetType").change(function () {
    DoorSetTypeChange();
});

$("#vP1Width, #vP1Height1").on("keyup", function () {
    $('#lazingIntegrityOrInsulationIntegrity').val('');
    $("#glassType").find('option:selected').removeAttr("selected");
    $('#glassType').val('');
    $('#glassThickness').val(0);
});

$("#intumescentSealArrangement").change(function () {

    // var intumescentSealArrangementText = $(this).text();
    // var intumescentSealArrangementText = $(this).text().split('-');
    var intumescentSealArrangementText = $(this).find(":selected").text().split('-');
    // alert(intumescentSealArrangementText[1]);
    // console.log(intumescentSealArrangementText);

    if ($("#doorsetType").val() == "DD") {
        $('.intumescentSealMeetingEdgesDiv').show();
        $('#intumescentSealMeetingEdges').val("2NO. " + intumescentSealArrangementText[1]);
    } else {
        $('.intumescentSealMeetingEdgesDiv').hide();
        $('#intumescentSealMeetingEdges').val("");
    }

});

$(".combination_of").change(function () {
    var doorsetType = '';
    var swingType = '';
    var latch = '';
    $(".combination_of").each(function () {
        if ($(this).attr('id') == "doorsetType") {
            doorsetType = $(this).val();
            if ($(this).val() == "SD") {
                $("#leafWidth1").attr('readonly', true);
                $("#leafWidth2").attr('readonly', true);

            } else if ($(this).val() == "DD") {
                $("#leafWidth1").attr('readonly', true);
                $("#leafWidth2").attr('readonly', true);
            } else {
                $("#leafWidth1").val('0');
                $("#leafWidth1").attr('readonly', true);
                $("#leafWidth2").attr('readonly', true);
            }
        } else if ($(this).attr('id') == "swingType") {
            swingType = $(this).val();
        } else {
            latch = $(this).val();
        }
    });
    $('.dsl').html("(" + latch + doorsetType + swingType + ")");
});

$("#swingType").change(function () {
    DoorSetTypeChange();
});

$(document).on('change', '#latchType', function (e) {
    e.preventDefault();
    IntumescentSeals();
});

// Door Dimensions & Door Leaf
$(document).on('change', '#sOWidth', function (e) {
    e.preventDefault();
    IntumescentSeals();
});

$(document).on('change', '#leafWidth1', function (e) {
    e.preventDefault();
    IntumescentSeals();
});

$(document).on('change', '#sOHeight', function (e) {
    e.preventDefault();
    IntumescentSeals();
});
$(document).on('change', '#sODepth', function (e) {
    e.preventDefault();
    const soDepthValue2 = parseInt($(this).val());
    const frameDepthValue2 = parseInt($('#frameDepth').val());
    const extLinerValue2 = $('#extLiner').val();
    if (frameDepthValue2 != '' && !isNaN(soDepthValue2) && extLinerValue2 == 'Yes') {
        const extLinerValue2 = soDepthValue2 - frameDepthValue2;
        $('#extLinerValue').val(extLinerValue2);
    }
});

$(".forcoreWidth1").change(function () {
    corewidth1Value();
});


function corewidth1Value(){
    var leafWidth1 = 0;
    var leafWidth2 = 0;
    var leafHeight = 0;
    var lipping_thickness = 0;
    var randomkey = 2;
    // var leafWidth1=0;
    var thisvalue = document.getElementsByClassName("forcoreWidth1");
    for (var i = 0; i < thisvalue.length; i++) {
        if (thisvalue[i].name == 'leafWidth1') {
            if (thisvalue[i].value == '') {
                leafWidth1 = 0;
            }
            else {
                leafWidth1 = parseInt(thisvalue[i].value);
            }
        }
        if (thisvalue[i].name == 'leafWidth2') {
            if (thisvalue[i].value == '') {
                leafWidth2 = 0;
            }
            else {
                leafWidth2 = parseInt(thisvalue[i].value);
            }
        }
        if (thisvalue[i].name == 'leafHeightNoOP') {
            if (thisvalue[i].value == '') {
                leafHeight = 0;
            }
            else {
                leafHeight = parseInt(thisvalue[i].value);
            }
        }

        if (thisvalue[i].name == 'lippingThickness') {
            if (thisvalue[i].value == '') {
                lipping_thickness = 0;
            }
            else {
                lipping_thickness = parseInt(thisvalue[i].value);
            }
        }
    }

    var ConfigurableDoorFormula = JSON.parse(ConfigurableDoorFormulaJson);
    var LippingThicknessAdditionalNumberForCoreWidth1 = 1;
    var LippingThicknessAdditionalNumberForCoreWidth2 = 1;
    var LippingThicknessAdditionalNumberForCoreHeight = 1;

    ConfigurableDoorFormula.forEach(function (elem, index) {

        var FormulaAdditionalData = JSON.parse(elem.value);
        if (elem.slug == "core_width_1") {
            LippingThicknessAdditionalNumberForCoreWidth1 = parseFloat((FormulaAdditionalData.lipping_thickness != "") ? FormulaAdditionalData.lipping_thickness : 1);
        }

        if (elem.slug == "core_width_2") {
            LippingThicknessAdditionalNumberForCoreWidth2 = parseFloat((FormulaAdditionalData.lipping_thickness != "") ? FormulaAdditionalData.lipping_thickness : 1);
        }

        if (elem.slug == "core_height") {
            LippingThicknessAdditionalNumberForCoreHeight = parseFloat((FormulaAdditionalData.lipping_thickness != "") ? FormulaAdditionalData.lipping_thickness : 1);
        }
    });

    // var calculate = leafWidth1 - (LippingThicknessAdditionalNumberForCoreWidth1 * lipping_thickness);
    var calculate = leafWidth1 - (1 * lipping_thickness);
    var calculateCoreWidth2 = leafWidth2 - (LippingThicknessAdditionalNumberForCoreWidth2 * lipping_thickness);
    var calculateCoreHeight = leafHeight - (LippingThicknessAdditionalNumberForCoreHeight * lipping_thickness);
    // var calculate = leafWidth1-(randomkey*lipping_thickness);


    let checkdoorsetType = $('#doorsetType').val();
    if (checkdoorsetType == 'DD' || checkdoorsetType == 'leaf_and_a_half') {
        $("#coreWidth2").val(calculateCoreWidth2);
    }
    $("#coreWidth1").val(calculate);
    $("#coreHeight").val(calculateCoreHeight);
}

// $(document).on('change','#leafHeightNoOP',function(e){
//     e.preventDefault();
//     IntumescentSeals();
// });

$("#doorLeafFacing").change(function () {
    DoorLeafFacingChange();
    // if($(this).val()=='Laminate'){
    // $("#decorativeGroves").removeAttr('required')
    // }
});

// $("#doorLeafFinish").change(function(){
// doorLeafFinishChange();
// doorLeafFacingPrice('doorLeafFinish');
// });


$(document).on('change', '#doorLeafFacingValue', function (e) {
    e.preventDefault();
    const doorLeafFacingValue_C = $(this).val();
    if (doorLeafFacingValue_C == 'CS_acrovyn') {
        IntumescentSeals();
    } else if ($('#doorLeafFacing').val() == 'Laminate') {
        DoorLeafFacingChange(true);
    }
});

$("#vP1Height1").change(function () {

    var num = parseInt($("#visionPanelQuantity").val());
    var isEqualSize = $("#AreVPsEqualSizes").val();

    if (isEqualSize == 'Yes') {

        for (var j = 2; j <= num; j++) {
            $("#vP1Height" + j).val($(this).val());
        }
    } else {
        for (var j = (num + 1); j <= 5; j++) {
            $("#vP1Height" + j).val('');
        }
    }

});

$("#vP2Height1").change(function () {

    var num = parseInt($("#visionPanelQuantityforLeaf2").val());
    var isEqualSize = $("#AreVPsEqualSizesForLeaf2").val();

    if (isEqualSize == 'Yes') {

        for (var j = 2; j <= num; j++) {
            $("#vP2Height" + j).val($(this).val());
        }
    } else {
        for (var j = (num + 1); j <= 5; j++) {
            $("#vP2Height" + j).val('');
        }
    }

});

$('#GroovesIcon').click(function(){
    DoorDimensionGroovesIcon();
})
$('#GroovesIconLeaf2').click(function(){
    DoorDimensionGroovesIconLeaf2();
})

function DoorDimensionGroovesIcon() {
   let decorativeGroves = $('#decorativeGroves').val();
   let base_url = $("input[name='base_url']").val();
   let pageId = 6;
    if (decorativeGroves == 'Yes' && pageId == 6) {
         $('#GroovesIcon').attr('data-target', '#DoorDimensionGrooves');

        $("#doorDimensionGroove").addClass("bg-white");
        $.ajax({
            url: $("#face-groove-image").html(),
            method: "POST",
            dataType: "Json",
            data: { decorativeGroves: decorativeGroves,pageId:pageId, _token: $("#_token").val() },
            success: function (response) {

                    $("#DoorDimensionBodyGroove").empty();

                    if (response.status === 'ok' && Array.isArray(response.face_grooves)) {
                        response.face_grooves.forEach(function(row) {
                        var html = `<div class="col-md-2 col-sm-4 col-6 cursor-pointer" data-dismiss="modal" onclick="DoorDimensionGrooveValueFill(${row.id},'${row.configurableitems}',${row.grooves_vertical},${row.grooves_horizontal},'${row.imageName}')"><div class="color_box"><div class="frameMaterialImage "><img width="100%" height="100" src="${base_url}/vicaimaImage/${row.grooves_image ? row.grooves_image : "vicaima_default_doorDimantion.jpg"}"></div><h4>${row.imageName}</h4></div></div>`;
                        $("#DoorDimensionBodyGroove").append(html);
                    });
                }
            }



        });
    } else {
        $("#doorDimensionGroove").removeClass("bg-white");
        $("#doorDimensionGroove").attr({ 'disabled': false, "readonly": true }).val('');
    }
}

function DoorDimensionGroovesIconLeaf2() {
    let decorativeGroves = $('#DecorativeGrovesLeaf2').val();
    let base_url = $("input[name='base_url']").val();
    let pageId = pageIdentity();
     if (decorativeGroves == 'Yes' && pageId == 4) {
          $('#GroovesIconLeaf2').attr('data-target', '#DoorDimensionGrooves');

         $("#DoorDimensionGrooveLeaf2").addClass("bg-white");
         $.ajax({
             url: $("#face-groove-image").html(),
             method: "POST",
             dataType: "Json",
             data: { decorativeGroves: decorativeGroves,pageId:pageId, _token: $("#_token").val() },
             success: function (response) {

                     $("#DoorDimensionBodyGroove").empty();

                     if (response.status === 'ok' && Array.isArray(response.face_grooves)) {
                         response.face_grooves.forEach(function(row) {
                         var html = `<div class="col-md-2 col-sm-4 col-6 cursor-pointer" data-dismiss="modal" onclick="DoorDimensionGrooveValueFill2(${row.id},'${row.configurableitems}',${row.grooves_vertical},${row.grooves_horizontal},'${row.imageName}')"><div class="color_box"><div class="frameMaterialImage "><img width="100%" height="100" src="${base_url}/vicaimaImage/${row.grooves_image ? row.grooves_image : "vicaima_default_doorDimantion.jpg"}"></div><h4>${row.imageName}</h4></div></div>`;
                         $("#DoorDimensionBodyGroove").append(html);
                     });
                 }
             }



         });
     } else {
         $("#DoorDimensionGrooveLeaf2").removeClass("bg-white");
         $("#DoorDimensionGrooveLeaf2").attr({ 'disabled': false, "readonly": true }).val('');
     }
 }

function DoorDimensionGrooveValueFill(id, configurableitems, grooves_vertical, grooves_horizontal,imageName) {
    if(configurableitems == 4){
        $('#numberOfVerticalGroove').val(grooves_vertical);
        $('#numberOfHorizontalGroove').val(grooves_horizontal);
        $('#doorDimensionGroove').val(imageName);
        $("#doorDimensionGroove-selected").empty().text(imageName);
        $("#doorDimensionGroove-section").removeClass("table_row_hide");
        $("#doorDimensionGroove-section").addClass("table_row_show");
    }
    let elements = $(this);
    render(elements);
}

function DoorDimensionGrooveValueFill2(id, configurableitems, grooves_vertical, grooves_horizontal,imageName) {
    if(configurableitems == 4){
        $('#NumberOfVerticalGrooveLeaf2').val(grooves_vertical);
        $('#NumberOfHorizontalGrooveLeaf2').val(grooves_horizontal);
        $('#DoorDimensionGrooveLeaf2').val(imageName);
    }
    let elements = $(this);
    render(elements);
}

$("#decorativeGroves").change(function () {

    if ($(this).val() == "Yes") {
        $("#grooveLocation").attr({ 'disabled': false, "required": false });
        $("#grooveWidth").attr({ 'readonly': false, "required": true }).val('0');
        $("#grooveDepth").attr({ 'disabled': false, "required": true }).val('0');

        $("#grooveLocation").val('Vertical_&_Horizontal');
        $("#doorDimensionGroove").attr({ 'disabled': false, "readonly": true });
        $("#doorDimensionGroove").addClass("bg-white");


        if ($("#grooveLocation").val() == "Vertical_&_Horizontal") {
            $("#numberOfGroove").attr({ 'readonly': true, "required": false }).val('0');
            $("#maxNumberOfGroove").attr({ 'readonly': true, "required": false }).val('0');
            $("#numberOfVerticalGroove").attr({ 'readonly': false, "required": true }).val('0');
            $("#numberOfHorizontalGroove").attr({ 'readonly': false, "required": true }).val('0');
        } else {
            $("#numberOfGroove").attr({ 'readonly': false, "required": true }).val('0');
            $("#maxNumberOfGroove").attr({ 'readonly': true, "required": true }).val('0');
            $("#numberOfVerticalGroove").attr({ 'readonly': true, "required": false }).val('0');
            $("#numberOfHorizontalGroove").attr({ 'readonly': true, "required": false }).val('0');
        }
        doorLeafFacingPrice('decorativeGroves', "Yes");
    } else {
        $("#doorDimensionGroove").removeClass("bg-white");
        $("#doorDimensionGroove").attr({ 'disabled': false, "readonly": true }).val('');
        $('#GroovesIcon').removeAttr('data-target', '#DoorDimensionGrooves')
        $("#grooveLocation").attr({ 'disabled': true, "required": false }).val('');
        $("#grooveWidth").attr({ 'readonly': true, "required": false }).val('0');
        $("#grooveDepth").attr({ 'disabled': true, "required": false }).val('0');
        $("#numberOfGroove").attr({ 'readonly': true, "required": false }).val('0');
        $("#maxNumberOfGroove").attr({ 'readonly': true, "required": false }).val('0');
        $("#numberOfVerticalGroove").attr({ 'readonly': true, "required": false }).val('0');
        $("#numberOfHorizontalGroove").attr({ 'readonly': true, "required": false }).val('0');
    }
});

$("#grooveLocation").change(function () {
    var grooveLocation = $(this).val();

    if (grooveLocation == "Vertical_&_Horizontal") {
        $("#numberOfGroove").attr({ 'readonly': true, "required": false }).val('0');
        $("#maxNumberOfGroove").attr({ 'readonly': true, "required": false }).val('0');
        $("#numberOfVerticalGroove").attr({ 'readonly': false, "required": true }).val('0');
        $("#numberOfHorizontalGroove").attr({ 'readonly': false, "required": true }).val('0');
    } else {
        $("#numberOfGroove").attr({ 'readonly': false, "required": true }).val('0');

        $("#maxNumberOfGroove").attr({ 'readonly': true, "required": true }).val('0');

        if (grooveLocation == "Vertical") {

            var leafWidth1 = $("#leafWidth1").val();
            leafWidth1 = leafWidth1 != '' ? (parseFloat(leafWidth1)) : 0;

            $("#maxNumberOfGroove").val(Math.round((leafWidth1 / 100)));

        } else if (grooveLocation == "Horizontal") {

            var leafHeightNoOP = $("#leafHeightNoOP").val();
            leafHeightNoOP = leafHeightNoOP != '' ? parseFloat(leafHeightNoOP) : 0;
            $("#maxNumberOfGroove").val(Math.round((leafHeightNoOP / 100)));

        }

        $("#numberOfVerticalGroove").attr({ 'readonly': true, "required": false }).val('0');
        $("#numberOfHorizontalGroove").attr({ 'readonly': true, "required": false }).val('0');
    }
});

$(document).on('change', "#maxNumberOfGroove", function () {
    var maxNumberOfGroove = Math.round($(this).val());
    $(this).val(maxNumberOfGroove);
    $("#numberOfGroove").attr('max', maxNumberOfGroove);
    $("label[for='numberOfGroove']").text("Number of Grooves (Max " + maxNumberOfGroove + ")");
    var numberOfGrooveCheck = parseFloat($('#numberOfGroove').val());
    if (numberOfGrooveCheck > maxNumberOfGroove) {
        swal('.', '‘Number of Grooves’ is never greater than the value in ‘Maximum Number of Groove’.');
        $('#numberOfGroove').val(0);
    }
});

// checking ‘Number of Grooves’ is never greater than the value in ‘Maximum Number of Groove’.
// Door Dimensions & Door Leaf (Fields)
$(document).on('change', '#numberOfGroove', function (e) {
    e.preventDefault();
    var no_of_groove_B = parseFloat($(this).val());
    var maxNumberOfGroove_B = parseInt($('#maxNumberOfGroove').val());
    if (no_of_groove_B > maxNumberOfGroove_B) {
        swal('Warning', 'Number of Grooves Error - “Number of Grooves should not exceed "' + maxNumberOfGroove_B + ' Grooves”');
        $('#numberOfGroove').val(0);
    }
})

// Vision Panel

$("#leaf1VisionPanel").change(function () {
    if ($(this).val() == "Yes") {
        $("#visionPanelQuantity").attr('disabled', false);
        $("#visionPanelQuantity").attr('required', true);
        $("#leaf1VisionPanelShape").attr('readonly', false);
        $("#leaf1VisionPanelShape").attr('required', true);
        //$("#AreVPsEqualSizes").attr('required',true);
        $("#vP1Width").attr('readonly', true);
        $("#vP1Height1").attr('readonly', true);
        $("#vP1Width").attr('required', true);
        $("#vP1Height1").attr('required', true);
        $("#distanceFromTopOfDoor").attr('readonly', false);
        $("#distanceFromTheEdgeOfDoor").attr({ 'required': true, 'readonly': false });
        $('#glazingSystems').attr('required', true);
        if ($('#fireRating').val() != 'NFR') {
            $('#lazingIntegrityOrInsulationIntegrity').attr('required', true);
            $('#lazingIntegrityOrInsulationIntegrity').attr('disabled', false);
        } else {
            $('#lazingIntegrityOrInsulationIntegrity').attr('disabled', true);
            $('#lazingIntegrityOrInsulationIntegrity').attr('required', false);
        }
        $('#glassType').attr('required', true);
        $('#glassThickness').attr('required', true);
        $('#glazingBeads').attr('required', true);
        $('#glazingBeadsThickness').attr('required', true);
        $('#glazingBeadsWidth').attr('required', true);
        $('#glazingBeadsHeight').attr('required', true);
        $('#glazingBeadsFixingDetail').attr('required', true);
        $('#glazingBeadSpecies').attr('required', true);
        doorLeafFacingPrice('leaf1VisionPanel', 'Yes');
        doorLeafFacingPrice('leaf1VisionPanel1', 'Yes');
    } else {
        $("#visionPanelQuantity").val('').attr({ 'disabled': true, 'required': false });
        $("#leaf1VisionPanelShape").val('').attr({ 'readonly': true, 'required': false });
        //$("#AreVPsEqualSizes").val('').attr({'disabled':true,'required':false});
        $("#AreVPsEqualSizes").val('').attr({ 'disabled': true });
        $("#vP1Width").val('').attr({ 'required': false, 'readonly': true });
        $("#vP1Height1").val('').attr({ 'required': false, 'readonly': true });
        $("#distanceFromTopOfDoor").val('').attr({'readonly':true , 'required':false});
        $("#distanceFromTheEdgeOfDoor").val('').attr({ 'readonly': true, 'required': false });
        $("#distanceBetweenVPs").val('').attr({ 'required': false, 'readonly': true });
        for (var i = 2; i <= 5; i++) {
            $("#vP1Height" + i).attr({ 'required': false, 'readonly': true }).val("");
        }
        $('#leaf1VisionPanelShape').val('').attr({ 'readonly': true, 'required': false }).val("");
        $('#glazingSystems').attr('required', false);
        $('#lazingIntegrityOrInsulationIntegrity').val('').attr('required', false);
        $('#glassType').val('').attr('required', false);
        $('#glazingBeads').val('').attr('required', false);
        $('#glazingBeadsThickness').val('').attr('required', false);
        $('#glazingBeadsWidth').val('').attr('required', false);
        $('#glazingBeadsHeight').val('').attr('required', false);
        $('#glazingBeadsFixingDetail').val('').attr('required', false);
        $('#glazingBeadSpecies').val('').attr('required', false);
        $('#glassThickness').val('').attr('required', false);
    }
});

$("#visionPanelQuantity").change(function () {
    if (parseInt($(this).val()) > 1) {
        $("#distanceBetweenVPs").attr('readonly', false);
        $("#distanceBetweenVPs").attr('required', true);
        $("#vP1Width").attr('readonly', false);
        $("#vP1Width").attr('required', true);
        $("#distanceFromTopOfDoor").attr({ 'required': true, 'readonly': false });
        var num = parseInt($(this).val());
        var isEqualSize = $("#AreVPsEqualSizes").val();
        $("#vP1Height1").attr('readonly', false);
        $("#vP1Height1").attr('required', true);
        $("#AreVPsEqualSizes").attr({ 'required': true, 'readonly': false });
        // var previousNumber =0;
        if (isEqualSize == 'Yes') {
            for (var i = 2; i <= 5; i++) {
                $("#vP1Height" + i).val("").attr({ 'readonly': true, 'required': false });
            }

            for (var j = 2; j <= num; j++) {
                $("#vP1Height" + j).val($("#vP1Height1").val());
            }
        } else {
            for (var i = 1; i <= num; i++) {
                $("#vP1Height" + i).attr('readonly', false);
                $("#vP1Height" + i).attr('required', true);
            }
            for (var j = (num + 1); j <= 5; j++) {
                $("#vP1Height" + j).val('').attr({ 'readonly': true, 'required': false });
            }
        }
        $("#distanceFromTopOfDoor").attr({ 'required': true, 'readonly': false });
        $("#AreVPsEqualSizes").attr('disabled', false);
    } else {

        $("#AreVPsEqualSizes").attr({ 'disabled': true, 'required': false, 'readonly': true }).val('');
        $("#distanceBetweenVPs").attr('required', false);
        $("#distanceBetweenVPs").attr('readonly', true);
        // $("#vP1Width").val('');
        $("#vP1Width").attr('readonly', false);
        $("#vP1Width").attr('required', true);
        $("#vP1Height1").attr('required', true);
        $("#vP1Height1").attr('readonly', false);
        $("#distanceBetweenVPs").val(0);

        for (var i = 2; i <= 5; i++) {
            $("#vP1Height" + i).val('').attr({ 'readonly': true, 'required': false });
        }
    }
});

$("#AreVPsEqualSizes").change(function () {
    $("#vP1Width").attr('readonly', false);
    $("#vP1Width").attr('required', true);
    $("#distanceFromTopOfDoor").attr({ 'required': true, 'readonly': false });
    var VisionPanelQuantity = parseInt($("#visionPanelQuantity").val());
    if ($(this).val() == "Yes") {
        $("#vP1Height1").attr('readonly', false);
        $("#vP1Height1").attr('required', true);

        for (var i = 2; i <= 5; i++) {
            $("#vP1Height" + i).attr({ 'readonly': true, 'required': false });
        }

        for (var j = 2; j <= VisionPanelQuantity; j++) {
            $("#vP1Height" + j).val($("#vP1Height1").val());
        }
    } else {

        if (VisionPanelQuantity > 1) {
            for (var j = 1; j <= VisionPanelQuantity; j++) {
                $("#vP1Height" + j).attr({ 'readonly': false, 'required': true });
            }
            for (var i = parseInt(VisionPanelQuantity) + 1; i <= 5; i++) {
                $("#vP1Height" + i).val('').attr({ 'readonly': true, 'required': false });
            }
        } else {
            for (var i = 2; i <= 5; i++) {
                $("#vP1Height" + i).attr({ 'readonly': true, 'required': false });
            }
        }
    }
});

$("#leaf2VisionPanel").change(function () {
    if ($(this).val() == "Yes") {
        $("#vpSameAsLeaf1").attr({ 'disabled': false, 'required': true });
        $("#visionPanelQuantityforLeaf2").attr({ 'disabled': false, 'required': true });
        // $("#AreVPsEqualSizesForLeaf2").attr({'disabled':false,'required':true});
        $("#distanceFromTopOfDoorforLeaf2").attr({ 'required': true, 'readonly': false });
        $("#distanceFromTheEdgeOfDoorforLeaf2").attr({ 'required': true, 'readonly': false });
        // $("#vP2Width").attr({'readonly':false,'required':true}).val();
    } else {
        $("#vpSameAsLeaf1").attr({ 'disabled': true, 'required': false }).val('');
        $("#visionPanelQuantityforLeaf2").attr({ 'disabled': true, 'required': false }).val('');
        $("#AreVPsEqualSizesForLeaf2").attr({ 'disabled': true, 'required': false }).val('');
        $("#distanceFromTopOfDoorforLeaf2").attr({ 'required': false, 'readonly': true });
        $("#distanceFromTheEdgeOfDoorforLeaf2").attr({ 'required': false, 'readonly': true });

        $("#distanceBetweenVPsforLeaf2").attr({ 'readonly': true, 'required': false }).val('');

        $("#vP2Width").attr({ 'readonly': true, 'required': false }).val("");
        for (var index = 1; index <= 5; index++) {
            $("#vP2Height" + index).attr({ 'readonly': true, 'required': false }).val('');
        }
    }
});

$("#vpSameAsLeaf1").change(function () {
    if ($(this).val() == "Yes") {
        $("#visionPanelQuantityforLeaf2").attr({ 'disabled': true, 'required': false }).val($("#visionPanelQuantity").val());
        $("#AreVPsEqualSizesForLeaf2").attr({ 'disabled': true, 'required': false }).val($("#AreVPsEqualSizes").val());

        $("#distanceFromTopOfDoorforLeaf2").attr({ 'readonly': true, 'required': false }).val($("#distanceFromTopOfDoor").val());
        $("#distanceFromTheEdgeOfDoorforLeaf2").attr({ 'readonly': true, 'required': false }).val($("#distanceFromTheEdgeOfDoor").val());
        $("#distanceBetweenVPsforLeaf2").attr({ 'readonly': true, 'required': false }).val($("#distanceBetweenVPs").val());

        $("#vP2Width").attr({ 'readonly': true, 'required': false }).val($("#vP1Width").val());
        for (var index = 1; index <= 5; index++) {
            $("#vP2Height" + index).attr({ 'readonly': true, 'required': false }).val($("#vP1Height" + index).val());
        }
    } else {
        $("#visionPanelQuantityforLeaf2").attr({ 'disabled': false, 'required': true }).val('');
        // $("#AreVPsEqualSizesForLeaf2").attr({'disabled':false,'required':true}).val('');

        $("#distanceFromTopOfDoorforLeaf2").attr({ 'readonly': false, 'required': true });
        $("#distanceFromTheEdgeOfDoorforLeaf2").attr({ 'readonly': false, 'required': true });
        // $("#distanceBetweenVPsforLeaf2").attr({'readonly':false,'required':true}).val('');

        $("#vP2Width").attr({ 'readonly': false, 'required': true }).val('');
        // for(var index=1;index<=5;index++){
        //     $("#vP2Height"+index).attr({'readonly':false,'required':true}).val('');
        // }
    }
});

$("#visionPanelQuantityforLeaf2").change(function () {
    if (parseInt($(this).val()) > 1) {

        $("#AreVPsEqualSizesForLeaf2").attr({ 'disabled': false, 'required': true });

        $("#distanceBetweenVPsforLeaf2").attr('readonly', false);
        $("#distanceBetweenVPsforLeaf2").attr('required', true);
        $("#vP2Width").attr('readonly', false);
        $("#vP2Width").attr('required', true);
        var num = parseInt($(this).val());
        var isEqualSize = $("#AreVPsEqualSizesForLeaf2").val();
        $("#vP2Height1").attr('readonly', false);
        $("#vP2Height1").attr('required', true);
        // var previousNumber =0;
        if (isEqualSize == 'Yes') {
            for (var i = 2; i <= 5; i++) {
                $("#vP2Height" + i).val('').attr({ 'readonly': true, 'required': false });
            }
            for (var j = 2; j <= num; j++) {
                $("#vP2Height" + j).val($("#vP2Height1").val());
            }
        } else {
            for (var i = 1; i <= num; i++) {
                $("#vP2Height" + i).attr('readonly', false);
                $("#vP2Height" + i).attr('required', true);
            }
            for (var j = (num + 1); j <= 5; j++) {
                $("#vP2Height" + j).val('').attr({ 'readonly': true, 'required': false });
            }
        }

    } else {
        $("#AreVPsEqualSizesForLeaf2").attr({ 'disabled': true, 'required': false });
        $("#distanceBetweenVPsforLeaf2").attr({ 'readonly': true, 'required': false });
        // $("#vP1Width").val('');
        $("#vP2Width").attr('readonly', false);
        $("#vP2Width").attr('required', true);
        $("#vP2Height1").attr('required', true);
        $("#vP2Height1").attr('readonly', false);
        $("#distanceBetweenVPsforLeaf2").val(80);

        for (var i = 2; i <= 5; i++) {
            $("#vP2Height" + i).val('').attr({ 'readonly': true, 'required': false });
        }
    }
});

$("#AreVPsEqualSizesForLeaf2").change(function () {
    $("#vP2Width").attr('readonly', false);
    $("#vP2Width").attr('required', true);

    var VisionPanelQuantity = parseInt($("#visionPanelQuantityforLeaf2").val());
    if ($(this).val() == "Yes") {
        $("#vP2Height1").attr('readonly', false);
        $("#vP2Height1").attr('required', true);

        for (var i = 2; i <= 5; i++) {
            $("#vP2Height" + i).attr({ 'readonly': true, 'required': false });
        }

        for (var j = 2; j <= VisionPanelQuantity; j++) {
            $("#vP2Height" + j).val($("#vP2Height1").val());
        }
    } else {

        if (VisionPanelQuantity > 1) {
            for (var j = 1; j <= VisionPanelQuantity; j++) {
                $("#vP2Height" + j).attr({ 'readonly': false, 'required': true });
            }
            for (var i = parseInt(VisionPanelQuantity) + 1; i <= 5; i++) {
                $("#vP2Height" + i).val('').attr({ 'readonly': true, 'required': false });
            }
        } else {
            for (var i = 2; i <= 5; i++) {
                $("#vP2Height" + i).attr({ 'readonly': true, 'required': false });
            }
        }
    }
});

$("#lazingIntegrityOrInsulationIntegrity").on('change', function () {
    glassTypeFilter(false);
    glazingSystemFIlter($("#fireRating").val());
});

$("#glassType").change(function () {
    GlassTypeChange();
});

$("#opGlassType").change(function(){
    GlassTypeChange(null,'opGlassType');
});

$("#sideLight1GlassType").change(function(){
    GlassTypeChange(null,'sideLight1GlassType');
});

$("#sideLight2GlassType").change(function(){
    GlassTypeChange(null,'sideLight2GlassType');
});

//getting glazing thikness filter using glazing systems
$("#glazingSystems").change(function () {
    GlazingSystemsChange();
});

$("#opglazingSystems").change(function(){
    GlazingSystemsChange(null,"opglazingSystems");
});

$("#sideLight1GlazingSystems").change(function(){
    GlazingSystemsChange(null,"sideLight1GlazingSystems");
});

$("#sideLight2GlazingSystems").change(function(){
    GlazingSystemsChange(null,"sideLight2GlazingSystems");
});

// Frame
$("#plantonStopWidth,#plantonStopHeight,#frameThickness,#frameDepth").on("keyup", function () {
    FramePrice('Plant_on_Stop');
});

//dimension value using frame type
$("#frameType").change(function () {
    framTypeChangeInputEnableDisable();
});

function framTypeChangeInputEnableDisable(){
    let frameonoff = $("#frameonoff").prop('checked');
    if(frameonoff == true){
        $('#frameType').val('');
    }
    let framTypeValue = $('#frameType').val();
    if (framTypeValue == "Plant_on_Stop") {
        // $("#plantonStopWidth").attr('min', '32');
        // $("#plantonStopHeight").attr('min', '12');
        $("#plantonStopWidth").attr({ 'readonly': false, 'required': true });
        $("#plantonStopHeight").attr({ 'readonly': false, 'required': true });
        $("#frameTypeDimensions").val('').attr('readonly', false);
        $("#rebatedWidth").attr({ 'readonly': true, 'required': false }).val(0);
        $("#rebatedHeight").attr({ 'readonly': true, 'required': false }).val(0);
        $("#ScallopedHeight").attr({ 'readonly': true, 'required': false }).val(0);
        $("#ScallopedWidth").attr({ 'readonly': true, 'required': false }).val(0);
        $("#rebatedWidth-section,#rebatedHeight-section,#ScallopedWidth-section,#ScallopedHeight-section").removeClass("table_row_show");
        $("#rebatedWidth-section,#rebatedHeight-section,#ScallopedWidth-section,#ScallopedHeight-section").addClass("table_row_hide");
      //  FramePrice('Plant_on_Stop');
    } else if(framTypeValue == "Scalloped"){
        $("#ScallopedHeight").attr({ 'readonly': false, 'required': true });
        $("#ScallopedWidth").attr({ 'readonly': false, 'required': true });
        $("#plantonStopWidth").attr({ 'readonly': true, 'required': false }).val(0);
        $("#plantonStopHeight").attr({ 'readonly': true, 'required': false }).val(0);
        $("#rebatedWidth").attr({ 'readonly': true, 'required': false }).val(0);
        $("#rebatedHeight").attr({ 'readonly': true, 'required': false }).val(0);
        $("#rebatedWidth-section,#rebatedHeight-section,#plantonStopWidth-section,#plantonStopHeight-section").removeClass("table_row_show");
        $("#rebatedWidth-section,#rebatedHeight-section,#plantonStopWidth-section,#plantonStopHeight-section").addClass("table_row_hide");
    } else if (framTypeValue == "Rebated_Frame") {
        $("#plantonStopWidth").attr({ 'readonly': true, 'required': false }).val(0);
        $("#plantonStopHeight").attr({ 'readonly': true, 'required': false }).val(0);
        $("#rebatedWidth").attr('min', '32');
        $("#rebatedHeight").attr('min', '12');
        $("#rebatedWidth").attr({ 'readonly': false, 'required': true });
        $("#rebatedHeight").attr({ 'readonly': false, 'required': true });
        $("#ScallopedHeight").attr({ 'readonly': true, 'required': false }).val(0);
        $("#ScallopedWidth").attr({ 'readonly': true, 'required': false }).val(0);
        $("#plantonStopWidth-section,#plantonStopHeight-section,#ScallopedWidth-section,#ScallopedHeight-section").removeClass("table_row_show");
        $("#plantonStopWidth-section,#plantonStopHeight-section,#ScallopedWidth-section,#ScallopedHeight-section").addClass("table_row_hide");
       // FramePrice('Rebated_Frame');
    } else {
        $("#rebatedWidth").attr({ 'readonly': true, 'required': false }).val(0);
        $("#rebatedHeight").attr({ 'readonly': true, 'required': false }).val(0);
        $("#ScallopedHeight").attr({ 'readonly': true, 'required': false }).val(0);
        $("#ScallopedWidth").attr({ 'readonly': true, 'required': false }).val(0);
        $("#plantonStopWidth").val(0);
        $("#plantonStopHeight").val(0);
        $("#frameTypeDimensions").val(0).attr('readonly', true);
        $("#plantonStopWidth").attr({ 'readonly': true, 'required': false });
        $("#plantonStopHeight").attr({ 'readonly': true, 'required': false });
        $(".Plant_on_Stop_section").removeClass("table_row_show");
        $(".Plant_on_Stop_section").addClass("table_row_hide");
        $(".Rebated_Frame_section").removeClass("table_row_show");
        $(".Rebated_Frame_section").addClass("table_row_hide");
        $("#plantonStopWidth-section,#plantonStopHeight-section,#ScallopedWidth-section,#ScallopedHeight-section,#rebatedWidth-section,#rebatedHeight-section").removeClass("table_row_show");
        $("#plantonStopWidth-section,#plantonStopHeight-section,#ScallopedWidth-section,#ScallopedHeight-section,#rebatedWidth-section,#rebatedHeight-section").addClass("table_row_hide");
    }
}

$(document).on('change', '#frameDepth', function (e) {
    e.preventDefault();
    const frameDepthValue = parseInt($(this).val());
    const soDepthValue = parseInt($('#sODepth').val());
    const extLinerValue = $('#extLiner').val();
    if (frameDepthValue != '' && !isNaN(soDepthValue) && extLinerValue == 'Yes') {
        const extLinerValue = soDepthValue - frameDepthValue;
        $('#extLinerValue').val(extLinerValue);
    }
})

$("#frameFinish").change(function () {
    FrameFinishChange(true, 'framefinish');
    doorLeafFacingPrice('frameFinish');
});

$("#extLiner").change(function () {
    if ($(this).val() == "Yes") {
        $("#extLinerSize").attr({ 'readonly': false });
        $("#extLinerThickness").attr({ 'readonly': false });
        //$("#extLinerFinish").attr({'readonly':false});

        var sODepth = parseInt($("#sODepth").val());
        var frameDepth = parseInt($("#frameDepth").val());
        if (!isNaN(sODepth) && !isNaN(frameDepth)) {
            $("#extLinerValue").val(parseInt(sODepth) - parseInt(frameDepth));
        }
        doorLeafFacingPrice('extLiner');
        FramePrice('extLiner')
    } else {
        $("#extLiner-section1").addClass("table_row_hide");
        $("#extLiner-section1").removeClass("table_row_show");
        $("#extLinerSize").attr({ 'readonly': true });
        $("#extLinerThickness").attr({ 'readonly': true });
        //$("#extLinerFinish").attr({'readonly':true});
        $(".extLiner_section").removeClass("table_row_show");
        $(".extLiner_section").addClass("table_row_hide");
    }
});

$("#frameFinish,#extLiner").on('change', function () {
    if ($('#extLiner').val() == "Yes") {
        var value = $('#frameFinish').val();
        doorLeafFacingPrice('extLinerFramefinish', value);
    }
});

$("#leaf1VisionPanel,#fireRating").on('change', function () {
    if ($('#leaf1VisionPanel').val() == "Yes") {
        var value = $('#fireRating').val();
        doorLeafFacingPrice('fireRating', value);
        doorLeafFacingPrice('fireRating1', value);
    }
});

$(document).on('change', '#extLinerThickness', function (e) {
    e.preventDefault();
    const extLinerThicknessValue = parseFloat($(this).val());
    const frameThicknessValue = parseInt($('#frameThickness').val());

    if (extLinerThicknessValue > frameThicknessValue) {
        swal('.', 'Ext-Liner Thickness value that should always be less than the frame thickness value');
        $('#extLinerThickness').val(0);
    }
})

// if Ironmongery Set is Yes then Enabled Select Ironmongery Set
// In other words when id `ironmongerySet` is Yes it enabled id `IronmongeryID`
$(document).on('change', '#ironmongerySet,#IronmongeryID', function (e) {
    e.preventDefault();
    const ironmongerySetValue = $("#ironmongerySet").val();
    if (ironmongerySetValue == 'Yes') {
        IronmongeryIDItemsPrice();
        IronmongeryIDPrice();
        $('#IronmongeryID').attr({ 'disabled': false, 'required': true })
    } else {
        $('#IronmongeryID').val('').attr({'disabled':true,'required':false})
        $('#IronmongeryID-selected').empty();
        $("#ironmongerySet-section2").removeClass("table_row_show");
        $("#ironmongerySet-section2").addClass("table_row_hide");
        $("#ironmongerySet-section1").removeClass("table_row_show");
        $("#ironmongerySet-section1").addClass("table_row_hide");
    }
});

// Over Panel Section

$(document).on('change', '#overpanel', function (e) {
    e.preventDefault();

    if($("#overpanel").val()=="Fan_Light"){
        $("#OpBeadThickness").val(0).attr('readonly',false);
        $("#OpBeadHeight").val(0).attr('readonly',false);
        $("#opglazingSystems").attr({ 'disabled': false, "required": true });
        $("#opglazingBeadsThickness").attr({ 'disabled': false, "required": true });
        $("#opglazingBeadsHeight").attr({ 'disabled': false, "required": true });
        $("#opglazingBeadsFixingDetail").attr({ 'disabled': false, "required": true });
        $("#OpBeadThickness").val(0).attr('required',true);
        $("#OpBeadHeight").val(0).attr('required',true);
        $("#opglassThickness").attr('required',true);
        $("#opglazingSystemsThickness").attr('required',true);
        doorLeafFacingPrice('overpanel',"Fan_Light");
        doorLeafFacingPrice('overpanel1',"Fan_Light");
        doorLeafFacingPrice('overpanel2',"Fan_Light");
        FramePrice('overpanel3');
    }else{

        $("#OpBeadThickness").val(0).attr('readonly',true);
        $("#OpBeadHeight").val(0).attr('readonly',true);
        $("#OpBeadThickness").val(0).attr('required',false);
        $("#OpBeadHeight").val(0).attr('required',false);
        $("#opglazingSystems").attr({ 'disabled': true, "required": false });
        $("#opglazingBeadsThickness").attr({ 'disabled': true, "required": false });
        $("#opglazingBeadsHeight").attr({ 'disabled': true, "required": false });
        $("#opglazingBeadsFixingDetail").attr({ 'disabled': true, "required": false });
        $("#opglassThickness").attr({ 'disabled': true, "required": false });
        $("#opglazingSystemsThickness").attr({ 'disabled': true, "required": false });
        $("#overpanel2-section1").removeClass("table_row_show");
        $("#overpanel2-section1").addClass("table_row_hide");
        $(".overpanel3_section").removeClass("table_row_show");
        $(".overpanel3_section").addClass("table_row_hide");
    }

    IntumescentSeals();
});

// we add the field ‘Glass Integrity’ before ‘Glass Type’ in the over panel section.
$("#opGlassIntegrity").change(function () {
    $("#opGlassType").attr('disabled', false);
    opGlassTypeFilter();
    // glazingSystemFIlter($("#fireRating").val());
});

$("#opGlassType").change(function () {
    if ($("#sideLight1").val() == "Yes") {
        $("#sideLight1GlassType").val($(this).val());
    }
});

$("#sideLight1GlassType").change(function () {
    var val = $('#sideLight1GlassType').val();
    $("#sidelight1-selected1").empty().text(val);
});

$("#sideLight2GlassType").change(function () {
    $("#sidelight2-selected1").empty().text($('#sideLight2GlassType').val());
});


$("#opGlazingBeads").change(function () {
    if ($("#sideLight1").val() == "Yes") {
        $("#SideLight1BeadingType").val($(this).val());
    }
});

$("#opGlazingBeadSpecies").change(function () {
    if ($("#sideLight1").val() == "Yes") {
        $("#SideLight1GlazingBeadSpecies").val($(this).val());
    }
});

// Side Light

$("#sideLight1").change(function(){
    sideLight1Change();
});
$("#sideLight2").change(function(){
    sideLight2Change();
});
$("#copyOfSideLite1").change(function(){
    copyOfSideLite1Change();
});
$(".SL1").on("change keyup", function() {
    copyOfSideLite1Change();
});

function sideLight1Change(){
    if($('#sideLight1').val()=="Yes"){


        $("#SlBeadThickness").attr('readonly',false);
        $("#SlBeadHeight").attr('readonly',false);
        $("#SlBeadThickness").attr('required',true);
        $("#SlBeadHeight").attr('required',true);




        if($("#sideLight2").val()=="Yes"){
            $("#copyOfSideLite1").attr({ 'disabled': false, "readonly" : false, "required": true });
        }

        if($("#overpanel").val()=="Yes"){
            $("#sideLight1GlassType").attr({ 'disabled': false, "required": true }).val($("#opGlassType"));
            $("#SideLight1BeadingType").attr({ 'disabled': false, "required": true }).val($("#opGlazingBeads"));
            $("#SideLight1GlazingBeadSpecies").attr({ 'disabled': false, "required": true }).val($("#opGlazingBeadSpecies"));

        } else {
            $("#sideLight1GlassType").attr({ 'disabled': false, "required": true });
            $("#SideLight1BeadingType").attr({ 'disabled': false, "required": true });
            $("#SideLight1GlazingBeadSpecies").attr({ 'disabled': false, "required": true });

            // 12-07-2024
            $("#sideLight1GlazingSystems").attr({ 'disabled': false, "required": true });
            $("#sideLight1GlassThickness").attr({ 'disabled': false, "required": true });
            $("#sideLight1GlazingSystemsThickness").attr({ 'disabled': false, "required": true });
            $("#sideLight1GlazingBeadsThickness").attr({ 'disabled': false, "required": true });
            $("#sideLight1GlazingBeadsWidth").attr({ 'disabled': false, "required": true });
            $("#sideLight1GlazingBeadsFixingDetail").attr({ 'disabled': false, "required": true });
            $("#SlBeadThickness").attr({ 'disabled': false, "required": true });
            $("#SlBeadHeight").attr({ 'disabled': false, "required": true });
        }

        $("#SL1Width").attr({ 'readonly': false, "required": true });
        $("#SL1Height").attr({ 'readonly': true, "required": true }).val($("#leafHeightNoOP").val());;
        $("#SL1Depth").attr({ 'readonly': false, "required": true });
        $("#SL1Transom").attr({ 'disabled': false, "required": true });
        doorLeafFacingPrice('sideLight1',"Yes");
        doorLeafFacingPrice('sideLight11',"Yes");
        doorLeafFacingPrice('sideLight2',"Yes");
        FramePrice('sideLight3');
    } else {

        $("#SlBeadThickness").val(0).attr('readonly',true);
        $("#SlBeadHeight").val(0).attr('readonly',true);
        $("#SlBeadThickness").val(0).attr('required',false);
        $("#SlBeadHeight").val(0).attr('required',false);


        if($("#sideLight2").val()=="Yes"){
            $("#copyOfSideLite1").attr({'disabled': true,"readonly":true }).val("No");

            $("#SideLight2BeadingType").attr({ 'disabled': false, "required": true }).val('');
            $("#SideLight2GlazingBeadSpecies").attr({ 'disabled': false, "required": true }).val('');
            $("#SL2Width").attr({ 'disabled': false, 'readonly': false, "required": true }).val("");
            $("#SL2Height").attr({ 'disabled': false, 'readonly': true, "required": true }).val($("#leafHeightNoOP").val());
            $("#SL2Depth").attr({ 'disabled': false, 'readonly': false, "required": true }).val("");
            $("#SL2Transom").attr({ 'disabled': false, "required": true }).val('');

            // new changes 12-07-2024
            $("#sideLight2GlassThickness").attr({ 'disabled': false, "required": true }).val('');
            $("#sideLight2GlazingSystems").attr({ 'disabled': false, "required": true }).val('');
            $("#sideLight2GlazingSystemsThickness").attr({ 'disabled': false, "required": true }).val('');
            $("#sideLight2GlazingBeadsThickness").attr({ 'disabled': false, "required": true }).val('');
            $("#sideLight2GlazingBeadsWidth").attr({ 'disabled': false, "required": true }).val('');
            $("#sideLight2GlazingBeadsFixingDetail").attr({ 'disabled': false, "required": true }).val('');
            // end


        }

        $("#sideLight1GlazingSystems").attr({ 'disabled': true, "required": false }).val('');
        $("#sideLight1GlassThickness").attr({ 'disabled': true, "required": false }).val('');
        $("#sideLight1GlazingSystemsThickness").attr({ 'disabled': true, "required": false }).val('');
        $("#sideLight1GlazingBeadsThickness").attr({ 'disabled': true, "required": false }).val('');
        $("#sideLight1GlazingBeadsWidth").attr({ 'disabled': true, "required": false }).val('');
        $("#sideLight1GlazingBeadsFixingDetail").attr({ 'disabled': true, "required": false }).val('');
        $("#sideLight1GlassType").attr({ 'disabled': true, "required": false }).val('');
        $("#SideLight1BeadingType").attr({ 'disabled': true, "required": false }).val('');
        $("#SideLight1GlazingBeadSpecies").attr({ 'disabled': true, "required": false }).val('');
        $("#SL1Width").attr({ 'readonly': true, "required": false }).val('');
        $("#SL1Height").attr({ 'readonly': true, "required": false }).val("");
        $("#SL1Depth").attr({ 'readonly': true, "required": false }).val('');
        $("#SL1Transom").attr({ 'disabled': true, "required": false }).val('');

        $("#sideLight2-section1").removeClass("table_row_show");
        $("#sideLight2-section1").addClass("table_row_hide");
        $(".sideLight3_section").removeClass("table_row_show");
        $(".sideLight3_section").addClass("table_row_hide");
    }
}

function sideLight2Change(){
    if($('#sideLight2').val()=="Yes"){
        if($("#sideLight1").val()=="Yes"){
            $("#copyOfSideLite1").attr({ 'disabled': false, "required": true });
        }else{
            $("#copyOfSideLite1").attr({'disabled': true,"readonly":true }).val("No");

            $("#sideLight2GlassType").attr({ 'disabled': false, "required": true }).val('');
            $("#SideLight2BeadingType").attr({ 'disabled': false, "required": true }).val('');
            $("#SideLight2GlazingBeadSpecies").attr({ 'disabled': false, "required": true }).val('');
            $(".sidelight2section").attr({ 'disabled': false, "required": true }).val('');
            $("#SL2Width").attr({ 'disabled': false, 'readonly': false, "required": true }).val("");
            $("#SL2Height").attr({ 'disabled': false, 'readonly': true, "required": true }).val($("#leafHeightNoOP").val());
            $("#SL2Depth").attr({ 'disabled': false, 'readonly': false, "required": true }).val("");
            $("#SL2Transom").attr({ 'disabled': false, "required": true }).val('');
        }
        doorLeafFacingPrice('sideLight12',"Yes");
    } else {
        $("#sideLight2GlassType").attr({ 'disabled': true, "required": false }).val('');
        $("#SideLight2BeadingType").attr({ 'disabled': true, "required": false }).val('');
        $("#SideLight2GlazingBeadSpecies").attr({ 'disabled': true, "required": false }).val('');
        $(".sidelight2section").attr({ 'disabled': true, "required": false }).val('');
        $("#copyOfSideLite1").attr({ 'disabled': true, "required": false }).val('');
        $("#SL2Width").attr({ 'readonly': true, "required": false }).val('');
        $("#SL2Height").attr({ 'readonly': true, "required": false }).val("");
        $("#SL2Depth").attr({ 'readonly': true, "required": false }).val('');
        $("#SL2Transom").attr({ 'disabled': true, "required": false }).val('');
        $("#sideLight12-section1").removeClass("table_row_show");
        $("#sideLight12-section1").addClass("table_row_hide");
    }
}

function copyOfSideLite1Change(){
    if($("#copyOfSideLite1").val()=="Yes"){
        $("#sideLight2GlassType").attr({ 'disabled': true, "required": true }).val($("#sideLight1GlassType").val());
        $("#SideLight2BeadingType").attr({ 'disabled': true, "required": true }).val($("#SideLight1BeadingType").val());
        $("#SideLight2GlazingBeadSpecies").attr({ 'disabled': true, "required": true }).val($("#SideLight1GlazingBeadSpecies").val());
        $("input[name='SideLight2GlazingBeadSpecies']").val($("input[name='SideLight1GlazingBeadSpecies']").val());
        $("#SL2Width").attr({ 'readonly': true, "required": true }).val($("#SL1Width").val());
        // $("#SL2Height").attr({ 'readonly': true, "required": true }).val($("#leafHeightNoOP").val());
        $("#SL2Height").attr({ 'readonly': true, "required": true }).val($("#SL1Height").val());
        $("#SL2Depth").attr({ 'readonly': true, "required": true }).val($("#SL1Depth").val());
        $("#SL2Transom").attr({ 'disabled': true, "required": true }).val($("#SL1Transom").val());

        //new 12-07-2024
        $("#sideLight2GlassThickness").attr({ 'disabled': true, "required": true }).val($("#sideLight1GlassThickness").val());
        $("#sideLight2GlazingSystems").attr({ 'disabled': true, "required": true }).val($("#sideLight1GlazingSystems").val());
        $("#sideLight2GlazingSystemsThickness").attr({ 'disabled': true, "required": true }).val($("#sideLight1GlazingSystemsThickness").val());
        $("#sideLight2GlazingBeadsThickness").attr({ 'disabled': true, "required": true }).val($("#sideLight1GlazingBeadsThickness").val());
        $("#sideLight2GlazingBeadsWidth").attr({ 'disabled': true, "required": true }).val($("#sideLight1GlazingBeadsWidth").val());
        $("#sideLight2GlazingBeadsFixingDetail").attr({ 'disabled': true, "required": true }).val($("#sideLight1GlazingBeadsFixingDetail").val());

        //end

        var val = $('#sideLight1GlassType').val();
        $("#sidelight1-selected1").empty().text(val);
        $("#sidelight2-selected1").empty().text(val);
    } else {
        if($("#sideLight2").val()=="Yes"){
            $("#sideLight2GlassType").attr({ 'disabled': false, "required": true }).val('');
            $("#SideLight2BeadingType").attr({ 'disabled': false, "required": true }).val('');
            $("#SideLight2GlazingBeadSpecies").attr({ 'disabled': false, "required": true }).val('');
            $("input[name='SideLight2GlazingBeadSpecies']").val('');
            $("#SL2Width").attr({ 'readonly': false, "required": true }).val('');
            $("#SL2Height").attr({ 'readonly': true, "required": true }).val($("#leafHeightNoOP").val());
            $("#SL2Depth").attr({ 'readonly': false, "required": true }).val('');
            $("#SL2Transom").attr({ 'disabled': false, "required": true }).val('');
            $("#sideLight2GlassThickness").attr({ 'readonly': false, "required": true }).val('');
            $("#sideLight2GlazingSystems").attr({ 'readonly': false, "required": true }).val('');
            $("#sideLight2GlazingSystemsThickness").attr({ 'readonly': false, "required": true }).val('');
            $("#sideLight2GlazingBeadsThickness").attr({ 'readonly': false, "required": true }).val('');
            $("#sideLight2GlazingBeadsWidth").attr({ 'readonly': false, "required": true }).val('');
            $("#sideLight2GlazingBeadsFixingDetail").attr({ 'readonly': false, "required": true }).val('');
        }else{
            $("#sideLight2GlassType").attr({ 'disabled': true, "required": false }).val('');
            $("#SideLight2BeadingType").attr({ 'disabled': true, "required": false }).val('');
            $("#SideLight2GlazingBeadSpecies").attr({ 'disabled': true, "required": false }).val('');
            $("input[name='SideLight2GlazingBeadSpecies']").val('');
            $("#SL2Width").attr({ 'readonly': true, "required": false }).val('');
            $("#SL2Height").attr({ 'readonly': true, "required": false }).val('');
            $("#SL2Depth").attr({ 'readonly': true, "required": false }).val('');
            $("#SL2Transom").attr({ 'disabled': true, "required": false }).val('');

            $("#sideLight2GlassThickness").attr({ 'readonly': true, "required": false }).val('');
            $("#sideLight2GlazingSystems").attr({ 'readonly': true, "required": false }).val('');
            $("#sideLight2GlazingSystemsThickness").attr({ 'readonly': true, "required": false }).val('');
            $("#sideLight2GlazingBeadsThickness").attr({ 'readonly': true, "required": false }).val('');
            $("#sideLight2GlazingBeadsWidth").attr({ 'readonly': true, "required": false }).val('');
            $("#sideLight2GlazingBeadsFixingDetail").attr({ 'readonly': true, "required": false }).val('');
        }
    }
}

// Lipping And Intumescent

$("#meetingStyle").change(function () {
    if ($(this).val() == "Scalloped") {
        $("#scallopedLippingThickness").attr({ 'disabled': false });
        $("#flatLippingThickness").attr({ 'disabled': true }).val('');
        $("#rebatedLippingThickness").attr({ 'disabled': true }).val('');
    } else if ($(this).val() == "Rebated") {
        $("#scallopedLippingThickness").attr({ 'disabled': true }).val('');
        $("#flatLippingThickness").attr({ 'disabled': true }).val('');
        $("#rebatedLippingThickness").attr({ 'disabled': false });
    } else if ($(this).val() == "Flat") {
        $("#scallopedLippingThickness").attr({ 'disabled': true }).val('');
        $("#flatLippingThickness").attr({ 'disabled': false });
        $("#rebatedLippingThickness").attr({ 'disabled': true }).val('');
    } else {
        $("#scallopedLippingThickness").attr({ 'disabled': true }).val('');
        $("#flatLippingThickness").attr({ 'disabled': true }).val('');
        $("#rebatedLippingThickness").attr({ 'disabled': true }).val('');
    }
});

// Accoustics
$("#accoustics").on("change", function () {
    var value = $("#accoustics").val();
    if (value == "Yes") {
        $("#rWdBRating").attr({ 'readonly': false, 'required': true });
        $("#perimeterSeal1").attr({ 'readonly': false, 'required': true });
        $("#perimeterSeal2").attr({ 'readonly': false, 'required': true });
        $("#thresholdSeal1").attr({ 'readonly': false, 'required': true });
        $("#thresholdSeal2").attr({ 'readonly': false, 'required': true });

        $("#perimeterSeal1").addClass('bg-white');
        $('#perimeterSeal1Icon').attr('onclick', "return openAccousticsModal('perimeterSeal1','Perimeter Seal 1','Perimeter_Seal_1')");

        $("#perimeterSeal2").addClass('bg-white');
        $('#perimeterSeal2Icon').attr('onclick', "return openAccousticsModal('perimeterSeal2','perimeter Seal 2','Perimeter_Seal_2')");

        $("#thresholdSeal1").addClass('bg-white');
        $('#thresholdSeal1Icon').attr('onclick', "return openAccousticsModal('thresholdSeal1','threshold Seal 1','Threshold_Seal_1')");

        $("#thresholdSeal2").addClass('bg-white');
        $('#thresholdSeal2Icon').attr('onclick', "return openAccousticsModal('thresholdSeal2','threshold Seal 1','Threshold_Seal_2')");


        let set = $('#doorsetType').val();
        if (set == "SD") {
            $("#accousticsmeetingStiles").removeClass('bg-white');
            $("#accousticsmeetingStiles").val('');
            $('#accousticsmeetingStilesIcon').attr('onclick', '');
            $('input[name="accousticsmeetingStiles"]').val('');

            $('#accousticsmeetingStiles').css({ 'disaplay': 'none' });
        } else {
            $("#accousticsmeetingStiles").addClass('bg-white');
            $('#accousticsmeetingStilesIcon').attr('onclick', "return openAccousticsModal('accousticsmeetingStiles','MeetingStiles' ,'Meeting_Stiles')");
        }
    } else {
        $("#rWdBRating").val('').attr({ 'readonly': true, 'required': false });
        $("#perimeterSeal1").attr({ 'readonly': true, 'required': false });
        $("#perimeterSeal2").attr({ 'readonly': true, 'required': false });
        $("#thresholdSeal1").attr({ 'readonly': true, 'required': false });
        $("#thresholdSeal2").attr({ 'readonly': true, 'required': false });
        $("#accousticsmeetingStiles").attr({ 'readonly': true, 'required': false });

        $("#perimeterSeal1").removeClass('bg-white');
        $("#perimeterSeal1").val('');
        $('#perimeterSeal1Icon').attr('onclick', '');
        $('input[name="perimeterSeal1"]').val('');

        $("#perimeterSeal2").removeClass('bg-white');
        $("#perimeterSeal2").val('');
        $('#perimeterSeal2Icon').attr('onclick', '');
        $('input[name="perimeterSeal2"]').val('');

        $("#thresholdSeal1").removeClass('bg-white');
        $("#thresholdSeal1").val('');
        $('#thresholdSeal1Icon').attr('onclick', '');
        $('input[name="thresholdSeal1"]').val('');

        $("#thresholdSeal2").removeClass('bg-white');
        $("#thresholdSeal2").val('');
        $('#thresholdSeal2Icon').attr('onclick', '');
        $('input[name="thresholdSeal2"]').val('');

        $("#accousticsmeetingStiles").removeClass('bg-white');
        $("#accousticsmeetingStiles").val('');
        $('#accousticsmeetingStilesIcon').attr('onclick', '');
        $('input[name="accousticsmeetingStiles"]').val('');
    }
});

var value = $("#accoustics").val();
if (value == "Yes") {
    $("#rWdBRating").attr({ 'readonly': false, 'required': true });
    $("#perimeterSeal1").addClass('bg-white');
    $('#perimeterSeal1Icon').attr('onclick', "return openAccousticsModal('perimeterSeal1','Perimeter Seal 1','Perimeter_Seal_1')");

    $("#perimeterSeal2").addClass('bg-white');
    $('#perimeterSeal2Icon').attr('onclick', "return openAccousticsModal('perimeterSeal2','perimeter Seal 2','Perimeter_Seal_2')");

    $("#thresholdSeal1").addClass('bg-white');
    $('#thresholdSeal1Icon').attr('onclick', "return openAccousticsModal('thresholdSeal1','threshold Seal 1','Threshold_Seal_1')");

    $("#thresholdSeal2").addClass('bg-white');
    $('#thresholdSeal2Icon').attr('onclick', "return openAccousticsModal('thresholdSeal2','threshold Seal 1','Threshold_Seal_2')");


    let set = $('#doorsetType').val();
    if (set == "SD") {
        $("#accousticsmeetingStiles").removeClass('bg-white');
        $("#accousticsmeetingStiles").val('');
        $('#accousticsmeetingStilesIcon').attr('onclick', '');
        $('input[name="accousticsmeetingStiles"]').val('');

        $('#accousticsmeetingStiles').css({ 'disaplay': 'none' });
    } else {
        $("#accousticsmeetingStiles").addClass('bg-white');
        $('#accousticsmeetingStilesIcon').attr('onclick', "return openAccousticsModal('accousticsmeetingStiles','MeetingStiles' ,'Meeting_Stiles')");
    }
} else {
    $("#rWdBRating").val('').attr({ 'readonly': true, 'required': false });

    $("#perimeterSeal1").removeClass('bg-white');
    $("#perimeterSeal1").val('');
    $('#perimeterSeal1Icon').attr('onclick', '');
    $('input[name="perimeterSeal1"]').val('');

    $("#perimeterSeal2").removeClass('bg-white');
    $("#perimeterSeal2").val('');
    $('#perimeterSeal2Icon').attr('onclick', '');
    $('input[name="perimeterSeal2"]').val('');

    $("#thresholdSeal1").removeClass('bg-white');
    $("#thresholdSeal1").val('');
    $('#thresholdSeal1Icon').attr('onclick', '');
    $('input[name="thresholdSeal1"]').val('');

    $("#thresholdSeal2").removeClass('bg-white');
    $("#thresholdSeal2").val('');
    $('#thresholdSeal2Icon').attr('onclick', '');
    $('input[name="thresholdSeal2"]').val('');

    $("#accousticsmeetingStiles").removeClass('bg-white');
    $("#accousticsmeetingStiles").val('');
    $('#accousticsmeetingStilesIcon').attr('onclick', '');
    $('input[name="accousticsmeetingStiles"]').val('');
}

// Architrave
$("#Architrave").change(function () {
    let ArcFin = $('#architraveFinish').val();
    if ($(this).val() == "Yes") {
        $("#architraveMaterial").attr({ 'readonly': true, 'required': true }).val('');
        $("#architraveMaterial").addClass('bg-white');
        $('#architraveMaterialIcon').attr('onclick', "return ArchitraveMaterial()");
        $("#architraveType").attr({ 'disabled': false, 'required': true }).val('');
        $("#architraveWidth").attr({ 'readonly': false, 'required': true }).val('');
        // $("#architraveDepth").attr({'readonly':false,'required':true}).val('');
        $("#architraveFinish").attr({ 'disabled': false, 'required': true }).val('');
        $("#architraveSetQty").attr({ 'disabled': false, 'required': true }).val('');
        $("#architraveHeight").attr({ 'readonly': false, 'required': true }).val('');
        $("#architraveFinishcolor").attr('readonly', true).val('');
        if (ArcFin == 'Painted_Finish') {
            $("#architraveFinishcolor").addClass('bg-white');
            $("#architraveFinishcolorIcon").attr('onclick', "return ArchitraveFinishColor()");
        }
    } else {
        $("#architraveMaterial").attr({ 'readonly': true, 'required': false }).val('');
        $("#architraveMaterial").removeClass('bg-white');
        $("#architraveMaterial").val('');
        $('#architraveMaterialIcon').attr('onclick', '');
        $('input[name="architraveMaterial"]').val('');
        $("#architraveType").attr({ 'disabled': true, 'required': false }).val('');
        $("#architraveWidth").attr({ 'readonly': true, 'required': false }).val('');
        // $("#architraveDepth").attr({'readonly':true,'required':false}).val('');
        $("#architraveFinish").attr({ 'disabled': true, 'required': false }).val('');
        $("#architraveSetQty").attr({ 'disabled': true, 'required': false }).val('');
        $("#architraveHeight").attr({ 'readonly': true, 'required': false }).val('');
        $("#architraveFinishcolor").attr('disabled', true).val('');
        $("#architraveFinishcolor").removeClass('bg-white');
        $('#architraveFinishcolorIcon').attr('onclick', '');
        $('input[name="architraveFinishcolor"]').val('');
    }
});

let ArcFin = $('#Architrave').val();
if (ArcFin == "Yes") {
    $("#architraveMaterial").attr({ 'readonly': false, 'required': true });
    $("#architraveMaterial").addClass('bg-white');
    $('#architraveMaterialIcon').attr('onclick', "return ArchitraveMaterial()");
} else {
    $("#architraveMaterial").attr({ 'readonly': true, 'required': false }).val('');
}

$("#architraveFinish").change(function () {
    FrameFinishChange(true, 'architraveFinish');
});
$("#architraveFinishcolorIcon").on('click', function () {
    FrameFinishChange(true, 'architraveFinish');
})


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Page onload
$(function () {
    var formValue = $("#fieldsValue").html();
    if (formValue) {
        setTimeout(function () {
            var parseFormValue = JSON.parse(formValue);
            $.each(parseFormValue, function (i, FormValue) {
                if (i > 0) {
                    $("#" + FormValue.name).val(FormValue.value);
                }
            });
        }, 3000);
    }

    var GrooveLocationValue = document.getElementById('GrooveLocation-value');
    var GrooveWidthValue = document.getElementById('GrooveWidth-value');
    var GrooveDepthValue = document.getElementById('GrooveDepth-value');
    var MaxNumberOfGrooveValue = document.getElementById('MaxNumberOfGroove-value');
    var NumberOfGrooveValue = document.getElementById('NumberOfGroove-value');
    var NumberOfVerticalGrooveValue = document.getElementById('NumberOfVerticalGroove-value');
    var NumberOfHorizontalGrooveValue = document.getElementById('NumberOfHorizontalGroove-value');

    var GrooveLocationLeaf2Value = document.getElementById('GrooveLocationLeaf2-value');
    var IsSameAsDecorativeGroves1Value = document.getElementById('IsSameAsDecorativeGroves1-value');
    var GrooveWidthLeaf2Value = document.getElementById('GrooveWidthLeaf2-value');
    var GrooveDepthLeaf2Value = document.getElementById('GrooveDepthLeaf2-value');
    var MaxNumberOfGrooveLeaf2Value = document.getElementById('MaxNumberOfGrooveLeaf2-value');
    var NumberOfGrooveLeaf2Value = document.getElementById('NumberOfGrooveLeaf2-value');
    var NumberOfVerticalGrooveLeaf2Value = document.getElementById('NumberOfVerticalGrooveLeaf2-value');
    var NumberOfHorizontalGrooveLeaf2Value = document.getElementById('NumberOfHorizontalGrooveLeaf2-value');


    if (GrooveLocationValue != null) {
        GrooveLocationValue = $("#GrooveLocation-value").data("value");
        if (GrooveLocationValue == "") {
            $("#grooveLocation").attr('disabled', true).val('');
        }
    } else {
        $("#grooveLocation").attr('disabled', true).val('');
    }

    if (GrooveWidthValue != null) {
        GrooveWidthValue = $("#GrooveWidth-value").data("value");
        if (GrooveWidthValue == "") {
            $("#grooveWidth").attr('readonly', true).val(0);
        }
    } else {
        $("#grooveWidth").attr('readonly', true).val(0);
    }

    if (GrooveDepthValue != null) {
        GrooveDepthValue = $("#GrooveDepth-value").data("value");
        if (GrooveDepthValue == "") {
            $("#grooveDepth").attr('disabled', true).val(0);
        }
    } else {
        $("#grooveDepth").attr('disabled', true).val(0);
    }


    if (MaxNumberOfGrooveValue != null) {
        MaxNumberOfGrooveValue = $("#MaxNumberOfGroove-value").data("value");
        if (MaxNumberOfGrooveValue == "") {
            $("#maxNumberOfGroove").attr('readonly', true).val(0);
        }
    } else {
        $("#maxNumberOfGroove").attr('readonly', true).val(0);
    }

    if (NumberOfGrooveValue != null) {
        NumberOfGrooveValue = $("#NumberOfGroove-value").data("value");
        if (NumberOfGrooveValue == "") {
            $("#numberOfGroove").attr('readonly', true).val(0);
        }
    } else {
        $("#numberOfGroove").attr('readonly', true).val(0);
    }

    if (NumberOfVerticalGrooveValue != null) {
        NumberOfVerticalGrooveValue = $("#NumberOfVerticalGroove-value").data("value");
        if (NumberOfVerticalGrooveValue == "") {
            $("#numberOfVerticalGroove").attr('readonly', true).val(0);
        }
    } else {
        $("#numberOfVerticalGroove").attr('readonly', true).val(0);
    }

    if (NumberOfHorizontalGrooveValue != null) {
        NumberOfHorizontalGrooveValue = $("#NumberOfHorizontalGroove-value").data("value");
        if (NumberOfHorizontalGrooveValue == "") {
            $("#numberOfHorizontalGroove").attr('readonly', true).val(0);
        }
    } else {
        $("#numberOfHorizontalGroove").attr('readonly', true).val(0);
    }

    if(IsSameAsDecorativeGroves1Value != null){
        IsSameAsDecorativeGroves1Value = $("#IsSameAsDecorativeGroves1-value").data("value");
        if(IsSameAsDecorativeGroves1Value == ""){
            $("#IsSameAsDecorativeGroves1").attr('disabled',true).val('');
        }
    }else{
        $("#IsSameAsDecorativeGroves1").attr('disabled',true).val('');
    }

    if(GrooveLocationLeaf2Value != null){
        GrooveLocationLeaf2Value = $("#GrooveLocationLeaf2-value").data("value");
        if(GrooveLocationLeaf2Value == ""){
            $("#GrooveLocationLeaf2").attr('disabled',true).val('');
        }
    }else{
        $("#GrooveLocationLeaf2").attr('disabled',true).val('');
    }

    if(GrooveWidthLeaf2Value != null){
        GrooveWidthLeaf2Value = $("#GrooveWidthLeaf2-value").data("value");
        if(GrooveWidthLeaf2Value == ""){
            $("#GrooveWidthLeaf2").attr('readonly',true).val(0);
        }
    }else{
        $("#GrooveWidthLeaf2").attr('readonly',true).val(0);
    }

    if(GrooveDepthLeaf2Value != null){
        GrooveDepthLeaf2Value = $("#GrooveDepthLeaf2-value").data("value");
        if(GrooveDepthLeaf2Value == ""){
            $("#GrooveDepthLeaf2").attr('disabled',true).val(0);
        }
    }else{
        $("#GrooveDepthLeaf2").attr('disabled',true).val(0);
    }


    if(MaxNumberOfGrooveLeaf2Value != null){
        MaxNumberOfGrooveLeaf2Value = $("#MaxNumberOfGrooveLeaf2-value").data("value");
        if(MaxNumberOfGrooveLeaf2Value == ""){
            $("#MaxNumberOfGrooveLeaf2").attr('readonly',true).val(0);
        }
    }else{
        $("#MaxNumberOfGrooveLeaf2").attr('readonly',true).val(0);
    }

    if(NumberOfGrooveLeaf2Value != null){
        NumberOfGrooveLeaf2Value = $("#NumberOfGrooveLeaf2-value").data("value");
        if(NumberOfGrooveLeaf2Value == ""){
            $("#NumberOfGrooveLeaf2").attr('readonly',true).val(0);
        }
    }else{
        $("#NumberOfGrooveLeaf2").attr('readonly',true).val(0);
    }

    if(NumberOfVerticalGrooveLeaf2Value != null){
        NumberOfVerticalGrooveLeaf2Value = $("#NumberOfVerticalGrooveLeaf2-value").data("value");
        if(NumberOfVerticalGrooveLeaf2Value == ""){
            $("#NumberOfVerticalGrooveLeaf2").attr('readonly',true).val(0);
        }
    }else{
        $("#NumberOfVerticalGrooveLeaf2").attr('readonly',true).val(0);
    }

    if(NumberOfHorizontalGrooveLeaf2Value != null){
        NumberOfHorizontalGrooveLeaf2Value = $("#NumberOfHorizontalGrooveLeaf2-value").data("value");
        if(NumberOfHorizontalGrooveLeaf2Value == ""){
            $("#NumberOfHorizontalGrooveLeaf2").attr('readonly',true).val(0);
        }
    }else{
        $("#NumberOfHorizontalGrooveLeaf2").attr('readonly',true).val(0);
    }

    const overpanelValue = $('#overpanel').val();
    var tollerance = 0;
    var gap = 0;
    var framethikness = 0;
    var soWidth = 0;
    var soheight = 0;
    var undercut = 0;
    if (overpanelValue == 'No') {
        //var thisvalue = document.getElementsByClassName("foroPWidth");
        var thisvalue = document.getElementsByClassName("form-control");
        for (var i = 0; i < thisvalue.length; i++) {
            if (thisvalue[i].name == 'tollerance') {
                if (thisvalue[i].value == '') {
                    tollerance = 0;
                } else {
                    tollerance = parseInt(thisvalue[i].value);
                }
            }

            if (thisvalue[i].name == 'frameThickness') {
                if (thisvalue[i].value == '') {
                    framethikness = 0;
                } else {
                    framethikness = parseInt(thisvalue[i].value);
                }
            }

            if (thisvalue[i].name == 'gap') {
                if (thisvalue[i].value == '') {
                    gap = 0;
                } else {
                    gap = parseInt(thisvalue[i].value);
                }
            }
            if (thisvalue[i].name == 'sOWidth') {
                if (thisvalue[i].value == '') {
                    soWidth = 0;
                } else {
                    soWidth = parseInt(thisvalue[i].value);
                }
            }
            if (thisvalue[i].name == 'sOHeight') {
                if (thisvalue[i].value == '') {
                    soheight = 0;
                } else {
                    soheight = parseInt(thisvalue[i].value);
                }
            }
            if (thisvalue[i].name == 'undercut') {
                if (thisvalue[i].value == '') {
                    undercut = 0;
                } else {
                    undercut = parseInt(thisvalue[i].value);
                }
            }
        }

        var leafHeightNoOP = soheight - tollerance - framethikness - undercut - gap;

        // $("#leafHeightNoOP").val(leafHeightNoOP).attr('readonly',true);

        if ($("#sideLight1").val() == "Yes") {
            $("#SL1Height").val(leafHeightNoOP).attr({ 'readonly': true, "required": true });
        }

        if ($("#sideLight2").val() == "Yes") {
            $("#SL2Height").val(leafHeightNoOP).attr({ 'readonly': true, "required": true });
        }
        var plantonStopHeight = soheight - tollerance;

        //$("#plantonStopHeight").val(plantonStopHeight);
        $("#frameHeight").val(plantonStopHeight);
        var frameDepth = $("#sODepth").val() != '' ? $("#sODepth").val() : 0;

        $("#leafHeightwithOP").val(0).attr('readonly', true);
        $("#oPWidth").val(0).attr('readonly', true);
        $("#oPHeigth").val(0).attr('readonly', true);
        $("#OPLippingThickness").val('').attr('disabled', true);
        $("#transomThickness").val('').attr('disabled', true);
        $("#opTransom").val('').attr('disabled', true);

        $("#opGlassIntegrity").attr({ 'disabled': true, required: false }).val('');
        $("#opGlassType").attr({ 'disabled': true, required: false }).val('');
        $("#opGlazingBeads").attr({ 'disabled': true, required: false }).val('');
        $("#opGlazingBeadSpecies").attr({ 'disabled': true, readonly: true, required: false }).val('');
        $("#opGlassIntegrity").attr({ 'disabled': true, readonly: true, required: false }).val('');


        // Rround down the field ‘Maximum Number of Groove’
        // for example, if the value there is 20.42 it should be 20.
        // Leaf Height with Groove Location = Vertical
        var grooveLocationValue = $('#grooveLocation').val();
        if (grooveLocationValue == "Horizontal" && leafHeightNoOP != '') {
            $("#maxNumberOfGroove").val(parseInt(Math.round((leafHeightNoOP) / 100)));
        }
    }




    // Vision Panel ( By default No )
    const leaf1VisionPanelValue = $('#leaf1VisionPanel').val();
    if (leaf1VisionPanelValue == 'No') {
        $("#visionPanelQuantity").val('').attr({ 'disabled': true, 'required': false });
        //$("#AreVPsEqualSizes").val('').attr({'disabled':true,'required':false});
        $("#AreVPsEqualSizes").val('').attr({ 'disabled': true });
        $("#vP1Width").attr({ 'required': false, 'readonly': true });
        $("#vP1Height1").attr({ 'required': false, 'readonly': true });
        $("#distanceFromTopOfDoor").attr({ 'required': false, 'readonly': true });
        $("#distanceFromTheEdgeOfDoor").attr({ 'required': false, 'readonly': true });
        $("#distanceBetweenVPs").attr({ 'required': false, 'readonly': true });
        for (var i = 2; i <= 5; i++) {
            $("#vP1Height" + i).attr({ 'required': false, 'readonly': true });
        }
        $("#leaf1VpAreaSizeM2").val(0);
        $('#leaf1VisionPanelShape').attr({ 'required': false, 'readonly': true });
    }


});

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// function
function FireRatingChange() {
    if ($("#fireRating").val() != '') {
        if ($("#fireRating").val() == "NFR") {
            $("#grooveDepth").attr("max", "");
            $("#gap").removeAttr("min");
            $("#gap").removeAttr("max");
            $('label[for="gap"]').hide();
            $('label[for="gap_NFR"]').css({ 'display': 'block' });
            $("#doorThickness").hide()
            $("#door_thickness_div").empty().append("<select name='doorThickness' id='doorThickness' class='form-control'><option value='35'>35</option> <option value='44'>44</option><option value='54'>54</option></select>")
            $("#lazingIntegrityOrInsulationIntegrity").prop('required', false);
            $('#lazingIntegrityOrInsulationIntegrity').attr('disabled', true);
            $("#SL1Width").removeAttr('max');
            $("#SL2Width").removeAttr('max');
            MeetingStyle();
        } else {
             $("#SL1Width").attr('max',600);
            $("#SL2Width").attr('max',600);
            $("#gap").attr("min", 2);
            $("#gap").attr("max", 4);
            // $("#gap").val('');
            $('label[for="gap"]').show();
            $('label[for="gap_NFR"]').css({ 'display': 'none' });

            if ($("#fireRating").val() == "FD30") {
                $("#door_thickness_div").empty().append("<select name='doorThickness' id='doorThickness' class='form-control'><option value='44'>44</option><option value='54'>54</option></select>")
                $("#scallopedLippingThickness").empty().append('<option value="8"><option value="8">');
                $("#grooveDepth").attr("max", 4);
            }
            if ($("#fireRating").val() == "FD60") {
                $("#door_thickness_div").empty().append(`<input type="number" readonly name="doorThickness" id="doorThickness" class="form-control" value="54">`);
                $("#grooveDepth").attr("max", 5);
            }

            if ($("#fireRating").val() == 'FD30s') {
                $("#door_thickness_div").empty().append(`<input type="number" readonly name="doorThickness" id="doorThickness" class="form-control" value="44">`);
                $("#grooveDepth").attr("max", 4);
            }

            if ($("#fireRating").val() == 'FD60s') {
                $("#door_thickness_div").empty().append(`<input type="number" readonly name="doorThickness" id="doorThickness" class="form-control" value="54">`);
                $("#grooveDepth").attr("max", 5);
            }
        }
        $('#opGlassIntegrity').val('');
        $('#opGlassType').val('');
        floor_finish_change();
        MeetingStyle();
        doorThicknessFilter($("#fireRating").val());
        glazingSystemFIlter($("#fireRating").val());
        onlyLipingSpecies($("#fireRating").val());
        glassTypeFilter(false);
        glazingBeadsFilter($("#fireRating").val());
        frameMaterialFilter($("#fireRating").val());
        scalloppedLippingThickness($("#fireRating").val());
        flatLippingThickness($("#fireRating").val());
        rebatedLippingThickness($("#fireRating").val());
    }
    IntumescentSeals();
}

function doorThicknessSelect(value) {
    // $("#doorThickness select").val(value);
    $('#doorThickness option[value=' + value + ']').attr('selected', 'selected');
}

function doorThicknessFilter(fireRating, opGlassIntegrityVal = "") {
    if (fireRating == 'FD30' || fireRating == 'FD30s') {
        fireRating = 'FD30';
    } else if (fireRating == 'FD60' || fireRating == 'FD60s') {
        fireRating = 'FD60';
    }
    let pageId = pageIdentity();
    $.ajax({
        url: $("#door-thickness-filter").html(),
        method: "POST",
        data: { pageId: pageId, fireRating: fireRating, _token: $("#_token").val() },
        dataType: "Json",
        success: function (result) {
            var innerHtml = '';
            var innerHtml1 = '';
            if (result.status == "ok") {
                var data = result.data;
                // console.log(data);
                var datalength = result.data.length;
                var GlassIntegrityValue = document.getElementById('GlassIntegrity-value');
                var opGlassIntegrityValue = document.getElementById('opGlassIntegrity-value');
                // innerHtml+='<option value="">Select Door Thickness</option>';
                for (var index = 0; index < datalength; index++) {
                    if (data[index].OptionSlug == "Door_Thickness" && data[index].UnderAttribute == fireRating) {
                        // $("#doorThickness").val(data[index].OptionKey);
                    }

                    // if(data[index].OptionSlug=="Door_Thickness" &&  data[index].UnderAttribute == fireRating ){


                    //     innerHtml+='<option value="'+data[index].OptionKey+'">'+data[index].OptionValue+'</option>';
                    // }else if(data[index].OptionSlug=="Glass_Integrity" &&  data[index].UnderAttribute == fireRating){
                    //     innerHtml1+='<option value="'+data[index].OptionKey+'">'+data[index].OptionValue+'</option>';
                    // }

                    if (data[index].OptionSlug == "Glass_Integrity" && data[index].UnderAttribute == fireRating) {
                        if (GlassIntegrityValue != null) {
                            GlassIntegrityValue = $("#GlassIntegrity-value").data("value");
                            var GlassIntegritySelected = "";
                            if (GlassIntegrityValue == data[index].OptionKey) {
                                GlassIntegritySelected = "selected";
                            }
                            innerHtml += '<option value="' + data[index].OptionKey + '" ' + GlassIntegritySelected + '>' + data[index].OptionValue + '</option>';
                        } else {
                            innerHtml += '<option value="' + data[index].OptionKey + '">' + data[index].OptionValue + '</option>';
                        }
                    }

                    if (data[index].OptionSlug == "Glass_Integrity" && data[index].UnderAttribute == fireRating) {
                        if (opGlassIntegrityValue != null) {
                            opGlassIntegrityValue = $("#opGlassIntegrity-value").data("value");
                            var OPGlassIntegritySelected = "";
                            if (opGlassIntegrityVal == true) {
                                if (opGlassIntegrityValue == data[index].OptionKey) {
                                    OPGlassIntegritySelected = "selected";
                                }
                            } else {
                                $('#opGlassType').empty();
                            }
                            innerHtml1 += '<option value="' + data[index].OptionKey + '" ' + OPGlassIntegritySelected + '>' + data[index].OptionValue + '</option>';
                        } else {
                            innerHtml1 += '<option value="' + data[index].OptionKey + '">' + data[index].OptionValue + '</option>';
                        }
                    }

                }
                // console.log(GlassIntegrityValue)
                if (innerHtml != '') {
                    var intigrity = '<option value="">Select Glass Intrigrity</option>';
                    if ($('#leaf1VisionPanel').val() == 'Yes') {
                        if ($('#fireRating').val() != 'NFR') {
                            $('#lazingIntegrityOrInsulationIntegrity').attr('disabled', false);
                            $('#lazingIntegrityOrInsulationIntegrity').attr('required', true);
                        } else {
                            $('#lazingIntegrityOrInsulationIntegrity').attr('disabled', true);
                            $('#lazingIntegrityOrInsulationIntegrity').attr('required', false);
                        }
                    }
                    //$("#lazingIntegrityOrInsulationIntegrity").attr('disabled',false).val();
                } else {
                    var intigrity = '';
                    innerHtml1 += '<option value="">No Glass Intrigrity Found</option>';
                    if ($('#leaf1VisionPanel').val() == 'Yes') {
                        if ($('#fireRating').val() != 'NFR') {
                            $('#lazingIntegrityOrInsulationIntegrity').attr('disabled', false);
                            $('#lazingIntegrityOrInsulationIntegrity').attr('required', true);
                        } else {
                            $('#lazingIntegrityOrInsulationIntegrity').attr('disabled', true);
                            $('#lazingIntegrityOrInsulationIntegrity').attr('required', false);
                        }
                    }
                }

                if (innerHtml1 != '') {
                    var intigrity1 = '<option value="">Select Glass Intrigrity</option>';
                    $("#opGlassIntegrity").attr('disabled', false).val();
                } else {
                    var intigrity1 = '';
                    innerHtml1 += '<option value="">No Glass Intrigrity Found</option>';
                    $("#opGlassIntegrity").attr('disabled', true).val('');
                }

                // $("#doorThickness").empty().append(innerHtml);
                $("#lazingIntegrityOrInsulationIntegrity").empty().append(intigrity).append(innerHtml);
                $("#opGlassIntegrity").empty().append(intigrity1).append(innerHtml1);

            } else {
                innerHtml += '<option value="">No Door Thickness</option>';
                // $("#doorThickness").empty().append(innerHtml);
                $("#doorThickness").val(0);

                innerHtml1 += '<option value="">No Glass Intrigrity Found</option>';
                $("#lazingIntegrityOrInsulationIntegrity").empty().append(innerHtml1);
                $("#opGlassIntegrity").empty().append(innerHtml1);
            }
        }
    });
}
function glazingSystemFIlter(fireRating) {
    let pageId = pageIdentity();
    var leaf1VpAreaSizeM2Value = $('#leaf1VpAreaSizeM2').val();
    leaf1VpAreaSizeM2Value = (leaf1VpAreaSizeM2Value == 0) ? "" : leaf1VpAreaSizeM2Value;
    $.ajax({
        url: $("#glazing-system-filter").html(),
        method: "POST",
        dataType: "Json",
        data: { pageId: pageId, fireRating: fireRating, _token: $("#_token").val(), leaf1VpAreaSizeM2Value: leaf1VpAreaSizeM2Value },
        success: function (result) {
            var innerHtml1 = '';
            if (result.status == "ok") {
                var innerHtml = '';
                var data = result.data;
                var datalength = result.data.length;
                var lippingSpecies = result.lippingSpecies;
                var lippingSpeciesLength = result.lippingSpecies.length;
                innerHtml += '<option value="">Select Glazing Type</option>';
                var GlazingSystemsValue = document.getElementById('GlazingSystems-value');
                for (var index = 0; index < datalength; index++) {
                    if (GlazingSystemsValue != null) {
                        GlazingSystemsValue = $("#GlazingSystems-value").data("value");
                        var GlazingSystemsSelected = "";
                        if (GlazingSystemsValue == data[index].Key) {
                            GlazingSystemsSelected = "selected";
                        }
                        innerHtml += '<option value="' + data[index].Key + '" ' + GlazingSystemsSelected + '>' + data[index].GlazingSystem + '</option>';
                    } else {
                        innerHtml += '<option value="' + data[index].Key + '">' + data[index].GlazingSystem + '</option>';
                    }
                }

                if (lippingSpecies != '' && lippingSpeciesLength > 0) {
                    innerHtml1 += '<div class="container"><div class="row">';
                    // innerHtml1+='<option value="">Select Species Type</option>';

                    var LippingSpeciesValue = document.getElementById('LippingSpecies-value');
                    var OPGlazingBeadSpeciesValue = document.getElementById('OPGlazingBeadSpecies-value');
                    var GlazingBeadSpeciesValue = document.getElementById('GlazingBeadSpecies-value');
                    var SL1GlazingBeadSpeciesValue = document.getElementById('SL1GlazingBeadSpecies-value');
                    var SideLight2GlazingBeadSpeciesValue = document.getElementById('SideLight2GlazingBeadSpecies-value');
                    for (var leep = 0; leep < lippingSpeciesLength; leep++) {
                        if (LippingSpeciesValue != null) {
                            LippingSpeciesValue = $("#LippingSpecies-value").data("value");

                            let adjustmentLeafWidth1 = $('#adjustmentLeafWidth1').val();
                            let adjustmentLeafHeightNoOP = $('#adjustmentLeafHeightNoOP').val();

                            if (LippingSpeciesValue != "" && LippingSpeciesValue == lippingSpecies[leep].id && (adjustmentLeafWidth1 != "" || adjustmentLeafHeightNoOP != "")) {
                                $("#lippingSpecies").val(lippingSpecies[leep].SpeciesName);
                            }
                        }

                        if (OPGlazingBeadSpeciesValue != null) {
                            OPGlazingBeadSpeciesValue = $("#OPGlazingBeadSpecies-value").data("value");
                            if (OPGlazingBeadSpeciesValue != "" && OPGlazingBeadSpeciesValue == lippingSpecies[leep].id) {
                                $("#opGlazingBeadSpecies").val(lippingSpecies[leep].SpeciesName);
                            }
                        }

                        if (GlazingBeadSpeciesValue != null) {
                            GlazingBeadSpeciesValue = $("#GlazingBeadSpecies-value").data("value");
                            if (GlazingBeadSpeciesValue != "" && GlazingBeadSpeciesValue == lippingSpecies[leep].id) {
                                $("#glazingBeadSpecies").val(lippingSpecies[leep].SpeciesName);
                            }
                        }

                        if (SL1GlazingBeadSpeciesValue != null) {
                            SL1GlazingBeadSpeciesValue = $("#SL1GlazingBeadSpecies-value").data("value");
                            if (SL1GlazingBeadSpeciesValue != "" && SL1GlazingBeadSpeciesValue == lippingSpecies[leep].id) {
                                $("#SideLight1GlazingBeadSpecies").val(lippingSpecies[leep].SpeciesName);
                            }
                        }

                        if (SideLight2GlazingBeadSpeciesValue != null) {
                            SideLight2GlazingBeadSpeciesValue = $("#SideLight2GlazingBeadSpecies-value").data("value");
                            if (SideLight2GlazingBeadSpeciesValue != "" && SideLight2GlazingBeadSpeciesValue == lippingSpecies[leep].id) {
                                $("#SideLight2GlazingBeadSpecies").val(lippingSpecies[leep].SpeciesName);
                            }
                        }

                        var filepath = $("input[name='base_url']").val() + "/uploads/Options/" + lippingSpecies[leep].file;

                        var possibleSelectedOptionsArray = JSON.parse(possibleSelectedOptionsJson);

                        if (possibleSelectedOptionsArray.hasOwnProperty("lippingSpecies")) {

                            if (lippingSpecies[leep].hasOwnProperty("SelectedLippingSpeciesCost")) {
                                var costToShow = lippingSpecies[leep].SelectedLippingSpeciesCost;

                            } else {
                                var costToShow = lippingSpecies[leep].LippingSpeciesCost;
                            }
                        } else {
                            var costToShow = lippingSpecies[leep].LippingSpeciesCost;
                        }

                        innerHtml1 += '<div class="col-md-2 col-sm-4 col-6 cursor-pointer" onclick="GlazingValueFill(' + lippingSpecies[leep].id + ',\'' + lippingSpecies[leep].SpeciesName + '\',\'#glazingModal\',' + costToShow + ')">'
                            + '<div class="color_box">'
                            + '<div class="frameMaterialImage"><img width="100%" height="100" src="' + filepath + '"></div>'
                            + '<h4>' + lippingSpecies[leep].SpeciesName + '</h4>'
                            + '</div></div>';
                        // innerHtml1+='<option value="'+lippingSpecies[leep].id+'">'+lippingSpecies[leep].SpeciesName+'</option>'

                    }
                } else {
                    // innerHtml1+='<option value="">No Species Found</option>';
                }
                $("#glazingSystems").empty().append(innerHtml);
                $("#opglazingSystems").empty().append(innerHtml);
                $("#sideLight1GlazingSystems").empty().append(innerHtml);
                $("#sideLight2GlazingSystems").empty().append(innerHtml);

                if($("#opglazingSystemsvalue").val() != null){
                    $("select[name=opglazingSystems]").val($("#opglazingSystemsvalue").val()).trigger("change");
                }
                    if($("#sideLight1GlazingSystemsvalue").val() != null){
                    $("select[name=sideLight1GlazingSystems]").val($("#sideLight1GlazingSystemsvalue").val()).trigger("change");
                }
                    if($("#sideLight2GlazingSystemsvalue").val() != null){
                    $("select[name=sideLight2GlazingSystems]").val($("#sideLight2GlazingSystemsvalue").val()).trigger("change");
                }
                // $("#lippingSpecies").empty().append(innerHtml1);
                // $("#glazingBeadSpecies").empty().append(innerHtml1);
                // $("#opGlazingBeadSpecies").empty().append(innerHtml1);
                $("#glazingModalBody").empty().append(innerHtml1);
                // $("#UniversalModalBody").empty().append(innerHtml1);

                // $("#SideLight1GlazingBeadSpecies").empty().append(innerHtml1);
                // $("#SideLight2GlazingBeadSpecies").empty().append(innerHtml1);
            } else {
                var lippingSpecies = result.lippingSpecies;
                var lippingSpeciesLength =result.lippingSpecies.length;
                innerHtml+='<option value="">No Glazing Systems Found</option>';
                $("#glazingSystems").empty().append(innerHtml);
                $("#opglazingSystems").empty().append(innerHtml);
                $("#sideLight1GlazingSystems").empty().append(innerHtml);
                $("#sideLight2GlazingSystems").empty().append(innerHtml);
                if(lippingSpecies!='' && lippingSpeciesLength>0){
                    innerHtml1 = "";
                    costToShow = 0;
                    innerHtml1 += '<div class="container"><div class="row">';
                    for(var leep =0; leep<lippingSpeciesLength;leep++){
                        var filepath = $("input[name='base_url']").val()+"/uploads/Options/"+lippingSpecies[leep].file;
                        innerHtml1 += '<div class="col-md-2 col-sm-4 col-6 cursor-pointer" onClick="GlazingValueFill('+lippingSpecies[leep].id+',\''+lippingSpecies[leep].SpeciesName+'\',\'#glazingModal\','+costToShow+')">'
                        + '<div class="color_box">'
                        + '<div class="frameMaterialImage"><img width="100%" height="100" src="'+ filepath +'"></div>'
                        + '<h4>'+lippingSpecies[leep].SpeciesName+'</h4>'
                        + '</div></div>';
                    }
                } else {
                    innerHtml1+='<option value="">No  Species Found</option>';
                }
                $("#lippingSpecies").empty().append(innerHtml1);
                $("#glazingBeadSpecies").empty().append(innerHtml1);
                // $("#opGlazingBeadSpecies").empty().append(innerHtml1);
                $("#glazingModalBody").empty().append(innerHtml1);
                // $("#UniversalModalBody").empty().append(innerHtml1);
                $("#SideLight1GlazingBeadSpecies").empty().append(innerHtml1);
                $("#SideLight2GlazingBeadSpecies").empty().append(innerHtml1);
            }
            $("#glazingSystemsThickness").val(0);
        }
    });
}
function glassTypeFilter(isIntegrity) {
    let pageId = pageIdentity();
    var fireRating = $("#fireRating").val();
    var integrity = $("#lazingIntegrityOrInsulationIntegrity").val();
    var GlassIntegrityValue = document.getElementById('GlassIntegrity-value');
    if (isIntegrity == true) {
        if (GlassIntegrityValue != null) {
            GlassIntegrityValue = $("#GlassIntegrity-value").data("value");
            if (GlassIntegrityValue != "") {
                integrity = GlassIntegrityValue;
            }
        }
    }

    var leaf1VpAreaSizeM2Value = $('#leaf1VpAreaSizeM2').val();
    leaf1VpAreaSizeM2Value = (leaf1VpAreaSizeM2Value == 0) ? "" : leaf1VpAreaSizeM2Value;
    $.ajax({
        url: $("#fire-rating-filter").html(),
        method: "POST",
        dataType: "Json",
        data: { pageId: pageId, fireRating: fireRating, integrity: integrity, _token: $("#_token").val(), isIntegrity: isIntegrity, leaf1VpAreaSizeM2Value: leaf1VpAreaSizeM2Value },
        success: function (result) {
            var glassTypeInnerHtml = "", OPGlassTypeInnerHtml = "",
                sideLight1GlassTypeInnerHtml = "", sideLight2GlassTypeInnerHtml = "";
            if (result.status == "ok") {
                var data = result.data;
                var length = result.data.length;

                var GlassTypeValue = document.getElementById('GlassType-value');
                var OPGlassTypeValue = document.getElementById('OPGlassType-value');
                var SideLight1GlassTypeValue = document.getElementById('SideLight1GlassType-value');
                var SideLight2GlassTypeValue = document.getElementById('SideLight2GlassType-value');

                glassTypeInnerHtml += '<option value="">Select Glass Type</option>';
                OPGlassTypeInnerHtml += '<option value="">Select Glass Type</option>';
                sideLight1GlassTypeInnerHtml += '<option value="">Select Glass Type</option>';
                sideLight2GlassTypeInnerHtml += '<option value="">Select Glass Type</option>';

                for (var i = 0; i < length; i++) {

                    if (GlassTypeValue != null) {
                        GlassTypeValue = $("#GlassType-value").data("value");
                        var GlassTypeSelected = "";
                        if (GlassTypeValue == data[i].Key) {
                            GlassTypeSelected = "selected";
                        }
                        glassTypeInnerHtml += '<option value="' + data[i].Key + '" ' + GlassTypeSelected + '>' + data[i].GlassType + '</option>';
                    } else {
                        glassTypeInnerHtml += '<option value="' + data[i].Key + '">' + data[i].GlassType + '</option>';
                    }

                    if (OPGlassTypeValue != null) {
                        OPGlassTypeValue = $("#OPGlassType-value").data("value");
                        var OPGlassTypeSelected = "";
                        if (OPGlassTypeValue == data[i].Key) {
                            OPGlassTypeSelected = "selected";
                        }
                        OPGlassTypeInnerHtml += '<option value="' + data[i].Key + '" ' + OPGlassTypeSelected + '>' + data[i].GlassType + '</option>';
                    } else {
                        OPGlassTypeInnerHtml += '<option value="' + data[i].Key + '">' + data[i].GlassType + '</option>';
                    }

                    if (SideLight1GlassTypeValue != null) {
                        SideLight1GlassTypeValue = $("#SideLight1GlassType-value").data("value");
                        var SideLight1GlassTypeSelected = "";
                        if (SideLight1GlassTypeValue == data[i].Key) {
                            SideLight1GlassTypeSelected = "selected";
                        }
                        sideLight1GlassTypeInnerHtml += '<option value="' + data[i].Key + '" ' + SideLight1GlassTypeSelected + '>' + data[i].GlassType + '</option>';
                    } else {
                        sideLight1GlassTypeInnerHtml += '<option value="' + data[i].Key + '">' + data[i].GlassType + '</option>';
                    }

                    if (SideLight2GlassTypeValue != null) {
                        SideLight2GlassTypeValue = $("#SideLight2GlassType-value").data("value");
                        var SideLight2GlassTypeSelected = "";
                        if (SideLight2GlassTypeValue == data[i].Key) {
                            SideLight2GlassTypeSelected = "selected";
                        }
                        sideLight2GlassTypeInnerHtml += '<option value="' + data[i].Key + '" ' + SideLight2GlassTypeSelected + '>' + data[i].GlassType + '</option>';
                    } else {
                        sideLight2GlassTypeInnerHtml += '<option value="' + data[i].Key + '">' + data[i].GlassType + '</option>';
                    }
                }
                $("#glassType").empty().append(glassTypeInnerHtml);
                // $("#opGlassType").empty().append( );
                $("#sideLight1GlassType").empty().append(sideLight1GlassTypeInnerHtml);
                $("#sideLight2GlassType").empty().append(sideLight2GlassTypeInnerHtml);
            } else {
                glassTypeInnerHtml += '<option value="">No Glass Type Found</option>';
                OPGlassTypeInnerHtml += '<option value="">No Glass Type Found</option>';
                sideLight1GlassTypeInnerHtml += '<option value="">No Glass Type Found</option>';
                sideLight2GlassTypeInnerHtml += '<option value="">No Glass Type Found</option>';
                $("#glassType").empty().append(glassTypeInnerHtml);
                // $("#opGlassType").empty().append(OPGlassTypeInnerHtml);
                $("#sideLight1GlassType").empty().append(sideLight1GlassTypeInnerHtml);
                $("#sideLight2GlassType").empty().append(sideLight2GlassTypeInnerHtml);
            }
            // $("#glassThickness").val(0);
        }
    });
}
function glazingBeadsFilter(fireRating) {
    let pageId = pageIdentity();
    $.ajax({
        url: $("#glazing-beads-filter").html(),
        method: "POST",
        dataType: "Json",
        data: { pageId: pageId, fireRating: fireRating, _token: $("#_token").val() },
        success: function (result) {
            var GlazingBeadsInnerHtml = '', OPGlazingBeadsInnerHtml = '',
                SideLight1BeadingTypeInnerHtml = '', SideLight2BeadingTypeInnerHtml = '';
            if (result.status == "ok") {
                var data = result.data;
                var length = result.data.length;
                var GlazingBeadsValue = document.getElementById('GlazingBeads-value');
                var OPGlazingBeadsValue = document.getElementById('OPGlazingBeads-value');
                var BeadingTypeValue = document.getElementById('BeadingType-value');
                var SideLight2BeadingTypeValue = document.getElementById('SideLight2BeadingType-value');
                GlazingBeadsInnerHtml += '<option value="">Select Glazing Beads</option>';
                OPGlazingBeadsInnerHtml += '<option value="">Select Glazing Beads</option>';
                SideLight1BeadingTypeInnerHtml += '<option value="">Select Beading Type</option>';
                SideLight2BeadingTypeInnerHtml += '<option value="">Select Beading Type</option>';

                for (var i = 0; i < length; i++) {
                    if (GlazingBeadsValue != null) {
                        GlazingBeadsValue = $("#GlazingBeads-value").data("value");
                        var GlazingBeadsSelected = "";
                        if (GlazingBeadsValue == data[i].OptionKey) {
                            GlazingBeadsSelected = "selected";
                        }
                        GlazingBeadsInnerHtml += '<option value="' + data[i].OptionKey + '" ' + GlazingBeadsSelected + '>' + data[i].OptionValue + '</option>';
                    } else {
                        GlazingBeadsInnerHtml += '<option value="' + data[i].OptionKey + '">' + data[i].OptionValue + '</option>';
                    }

                    if (OPGlazingBeadsValue != null) {
                        OPGlazingBeadsValue = $("#OPGlazingBeads-value").data("value");
                        var OPGlazingBeadsSelected = "";
                        if (OPGlazingBeadsValue == data[i].OptionKey) {
                            OPGlazingBeadsSelected = "selected";
                        }
                        OPGlazingBeadsInnerHtml += '<option value="' + data[i].OptionKey + '" ' + OPGlazingBeadsSelected + '>' + data[i].OptionValue + '</option>';
                    } else {
                        OPGlazingBeadsInnerHtml += '<option value="' + data[i].OptionKey + '">' + data[i].OptionValue + '</option>';
                    }

                    if (BeadingTypeValue != null) {
                        BeadingTypeValue = $("#BeadingType-value").data("value");
                        var BeadingTypeSelected = "";

                        if (BeadingTypeValue == data[i].OptionKey) {
                            BeadingTypeSelected = "selected";
                        }
                        SideLight1BeadingTypeInnerHtml += '<option value="' + data[i].OptionKey + '" ' + BeadingTypeSelected + '>' + data[i].OptionValue + '</option>';
                    } else {
                        SideLight1BeadingTypeInnerHtml += '<option value="' + data[i].OptionKey + '">' + data[i].OptionValue + '</option>';
                    }

                    if (SideLight2BeadingTypeValue != null) {
                        SideLight2BeadingTypeValue = $("#SideLight2BeadingType-value").data("value");
                        var SideLight2BeadingTypeSelected = "";

                        if (SideLight2BeadingTypeValue == data[i].OptionKey) {
                            SideLight2BeadingTypeSelected = "selected";
                        }
                        SideLight2BeadingTypeInnerHtml += '<option value="' + data[i].OptionKey + '" ' + SideLight2BeadingTypeSelected + '>' + data[i].OptionValue + '</option>';
                    } else {
                        SideLight2BeadingTypeInnerHtml += '<option value="' + data[i].OptionKey + '">' + data[i].OptionValue + '</option>';
                    }
                }

                $("#glazingBeads").empty().append(GlazingBeadsInnerHtml);
                $("#opGlazingBeads").empty().append(OPGlazingBeadsInnerHtml);
                $("#SideLight1BeadingType").empty().append(SideLight1BeadingTypeInnerHtml);
                $("#SideLight2BeadingType").empty().append(SideLight2BeadingTypeInnerHtml);
            } else {
                GlazingBeadsInnerHtml += '<option value="">No Glazing Beads Found</option>';
                OPGlazingBeadsInnerHtml += '<option value="">No Glazing Beads Found</option>';
                SideLight1BeadingTypeInnerHtml += '<option value="">No Beading Type Found</option>';
                SideLight2BeadingTypeInnerHtml += '<option value="">No Beading Type Found</option>';
                $("#glazingBeads").empty().append(GlazingBeadsInnerHtml);
                $("#opGlazingBeads").empty().append(OPGlazingBeadsInnerHtml);
                $("#SideLight1BeadingType").empty().append(SideLight1BeadingTypeInnerHtml);
                $("#SideLight2BeadingType").empty().append(SideLight2BeadingTypeInnerHtml);
            }
        }
    });
}
function frameMaterialFilter(fireRating){
    let pageId = pageIdentity();
    $.ajax({
        url: $("#frame-material-filter").html(),
        method:"POST",
        dataType:"Json",
        data:{pageId:pageId,fireRating:fireRating,_token:$("#_token").val()},
        success: function(result){
            if(result.status=="ok"){
                var innerHtml ='';
                var data = result.data;
                var length = result.data.length;
                var leepingSpecies = result.leepingSpecies;

                //innerHtml+='<option value="">Select Frame Material</option>';
                //for(var i =0; i<length;i++){
                //
                //    innerHtml+='<option value="'+data[i].OptionKey+'">'+data[i].OptionValue+'</option>'
                //
                //}
                //if(leepingSpecies!=''){
                //    var leepingSpecieslength = result.leepingSpecies.length;
                //
                //    for(var j =0; j<leepingSpecieslength;j++){
                //
                //        innerHtml+='<option value="'+leepingSpecies[j].id+'">'+leepingSpecies[j].SpeciesName+'</option>'
                //
                //    }
                //}

                var FrameMaterialValue = document.getElementById('FrameMaterial-value');
                var innerHtmlPopUp='<div class="container"><div class="row">';
                for(var i =0; i<length;i++){
                    var filepath = $("input[name='base_url']").val()+"/uploads/Options/"+data[i].file;
                    var url = "{{route('project/get-project-list')}}";

                    //innerHtml+='<option value="'+data[i].OptionKey+'">'+data[i].OptionValue+'</option>'

                    innerHtmlPopUp+='<div class="col-md-2 col-sm-4 col-6 cursor-pointer" onClick="SelectValueFill(\'#frameMaterial\',\''+data[i].OptionKey+'\',\''+data[i].OptionValue+'\',\'#frameMaterialModal\')">';
                    innerHtmlPopUp+='<div class="color_box">';
                    innerHtmlPopUp+='<div class="frameMaterialImage"><img width="100%" height="100" src="'+filepath+'"></div>';
                    innerHtmlPopUp+=' <h4>'+data[i].OptionValue+'</h4>';
                    innerHtmlPopUp+='</div></div>';

                }

                if(leepingSpecies!=''){
                    var leepingSpecieslength = result.leepingSpecies.length;
                    for(var j =0; j<leepingSpecieslength;j++){
                        if(FrameMaterialValue != null){
                            FrameMaterialValue = $("#FrameMaterial-value").data("value");
                            if(FrameMaterialValue != "" && FrameMaterialValue == leepingSpecies[j].id){
                                $("#frameMaterial").val(leepingSpecies[j].SpeciesName);
                            }
                        }
                        var filepath = $("input[name='base_url']").val()+"/uploads/Options/"+leepingSpecies[j].file;

                        //innerHtml+='<option value="'+leepingSpecies[j].id+'">'+leepingSpecies[j].SpeciesName+'</option>'

                        innerHtmlPopUp+='<div class="col-md-2 col-sm-4 col-6 cursor-pointer" onClick="SelectValueFill(\'#frameMaterial\',\''+leepingSpecies[j].id+'\',\''+leepingSpecies[j].SpeciesName+'\',\'#frameMaterialModal\')">';
                        innerHtmlPopUp+='<div class="color_box">';
                        innerHtmlPopUp+='<div class="frameMaterialImage"><img width="100%" height="100" src="'+filepath+'"></div>';
                        innerHtmlPopUp+=' <h4>'+leepingSpecies[j].SpeciesName+'</h4>';
                        innerHtmlPopUp+='</div></div>';
                    }
                }

                innerHtmlPopUp+='</div></div>';

                $("#frameMaterialModalBody").empty().append(innerHtmlPopUp);
                // $("#UniversalModalBody").empty().append(innerHtmlPopUp);

                $("#frameMaterialIcon").attr("onclick","return FrameMaterial()");
                $("#frameMaterialIcon").addClass("cursor-pointer");

                let overPanel = $('#overpanel').val();
                if(overPanel=="Fan_Light"){
                    $("#opGlazingBeadSpeciesIcon").attr("onclick", "return  OpenglazingModal('OP Glazing Bead Species','opGlazingBeadSpecies')");
                    $("#opGlazingBeadSpeciesIcon").addClass("cursor-pointer");
                }
                $("#glazingBeadSpeciesIcon").attr("onclick","return  OpenglazingModal('Glazing Bead Species','glazingBeadSpecies')");
                $("#glazingBeadSpeciesIcon").addClass("cursor-pointer");
                $("#lippingSpeciesIcon").attr("onclick","return  OpenLipingModal('Lipping Species','lippingSpecies')");
                $("#lippingSpeciesIcon").addClass("cursor-pointer");
                $("#SideLight2GlazingBeadSpeciesIcon").attr("onclick", "return  OpenglazingModal('Side Light 2 Glazing Bead Species','SideLight2GlazingBeadSpecies')");
                $("#SideLight2GlazingBeadSpeciesIcon").addClass("cursor-pointer");
                $("#SideLight1GlazingBeadSpeciesIcon").attr("onclick", "return  OpenglazingModal('Glazing Bead Species','SideLight1GlazingBeadSpecies')");
                $("#SideLight1GlazingBeadSpeciesIcon").addClass("cursor-pointer");

                //$("#frameMaterialModal").modal('show');

                //$("#frameMaterial").empty().append(innerHtmlPopUp);

            }else{
                //innerHtml+='<option value="">No Frame Material Found</option>';
                innerHtmlPopUp+='No Frame Material Found';

                $("#frameMaterial").empty().append(innerHtml);
            }
        }
    });
}
function scalloppedLippingThickness(fireRating) {
    let pageId = pageIdentity();
    $.ajax({
        url: $("#scallopped-lipping-thickness").html(),
        method: "POST",
        dataType: "Json",
        data: { pageId: pageId, fireRating: fireRating, _token: $("#_token").val() },
        success: function (result) {
            if (result.status == "ok") {
                var innerHtml = '';
                var data = result.data;
                var length = result.data.length;
                var ScallopedLippingThicknessValue = document.getElementById('ScallopedLippingThickness-value');
                innerHtml += '<option value="">Select Scallopped lipping thickness</option>';
                for (var i = 0; i < length; i++) {
                    if (ScallopedLippingThicknessValue != null) {
                        ScallopedLippingThicknessValue = $("#ScallopedLippingThickness-value").data("value");
                        var ScallopedLippingThicknessSelected = "";
                        if (ScallopedLippingThicknessValue == data[i].OptionKey) {
                            ScallopedLippingThicknessSelected = "selected";
                        }
                        innerHtml += '<option value="' + data[i].OptionKey + '" ' + ScallopedLippingThicknessSelected + '>' + data[i].OptionValue + '</option>';
                    } else {
                        innerHtml += '<option value="' + data[i].OptionKey + '">' + data[i].OptionValue + '</option>';
                    }
                }
                $("#scallopedLippingThickness").empty().append(innerHtml);
            } else {
                innerHtml += '<option value="">No Scallopped lipping thickness Found</option>';
                $("#scallopedLippingThickness").empty().append(innerHtml);
            }
            if (fireRating == "NFR") {
                var noData = '';
                noData += '<option value="">No Scallopped lipping thickness Found</option>';
                $("#scallopedLippingThickness").empty().append(noData);
            }
        }
    });
}
function flatLippingThickness(fireRating) {
    let pageId = pageIdentity();
    $.ajax({
        url: $("#flat-lipping-thickness").html(),
        method: "POST",
        dataType: "Json",
        data: { pageId: pageId, fireRating: fireRating, _token: $("#_token").val() },
        success: function (result) {
            if (result.status == "ok") {
                var innerHtml = '';
                var FlatLippingThicknessValue = document.getElementById('FlatLippingThickness-value');
                var data = result.data;
                var length = result.data.length;
                innerHtml += '<option value="">Select Flat lipping Thickness</option>';
                for (var i = 0; i < length; i++) {
                    if (FlatLippingThicknessValue != null) {
                        FlatLippingThicknessValue = $("#FlatLippingThickness-value").data("value");
                        var FlatLippingThicknessSelected = "";
                        if (FlatLippingThicknessValue == data[i].OptionKey) {
                            FlatLippingThicknessSelected = "selected";
                        }
                        innerHtml += '<option value="' + data[i].OptionKey + '" ' + FlatLippingThicknessSelected + '>' + data[i].OptionValue + '</option>';
                    } else {
                        innerHtml += '<option value="' + data[i].OptionKey + '">' + data[i].OptionValue + '</option>';
                    }
                }
                $("#flatLippingThickness").empty().append(innerHtml);
            } else {
                innerHtml += '<option value="">No Flat lipping Thickness Found</option>';
                $("#flatLippingThickness").empty().append(innerHtml);
            }
            if (fireRating == "NFR") {
                var noData = '';
                noData += '<option value="">No Flat lipping Thickness Found</option>';
                $("#flatLippingThickness").empty().append(noData);
            }
        }
    });
}
function rebatedLippingThickness(fireRating) {
    let pageId = pageIdentity();
    $.ajax({
        url: $("#rebated-lipping-thickness").html(),
        method: "POST",
        dataType: "Json",
        data: { pageId: pageId, fireRating: fireRating, _token: $("#_token").val() },
        success: function (result) {
            if (result.status == "ok") {
                var innerHtml = '';
                var data = result.data;
                var length = result.data.length;
                innerHtml += '<option value="">Select Rebated Lipping Thickness</option>';
                var RebatedLippingThicknessValue = document.getElementById('RebatedLippingThickness-value');
                for (var i = 0; i < length; i++) {
                    if (RebatedLippingThicknessValue != null) {
                        RebatedLippingThicknessValue = $("#RebatedLippingThickness-value").data("value");

                        var RebatedLippingThicknessSelected = "";
                        if (RebatedLippingThicknessValue == data[i].OptionKey) {
                            RebatedLippingThicknessSelected = "selected";
                        }
                        innerHtml += '<option value="' + data[i].OptionKey + '" ' + RebatedLippingThicknessSelected + '>' + data[i].OptionValue + '</option>';
                    } else {
                        innerHtml += '<option value="' + data[i].OptionKey + '">' + data[i].OptionValue + '</option>';
                    }
                }
                $("#rebatedLippingThickness").empty().append(innerHtml);
            } else {
                innerHtml += '<option value="">No Rebated Lipping Thickness Found</option>';
                $("#rebatedLippingThickness").empty().append(innerHtml);
            }
            if (fireRating == "NFR") {
                var noData = '';
                noData += '<option value="">No Rebated lipping Thickness Found</option>';
                $("#rebatedLippingThickness").empty().append(noData);
            }
        }
    });
}

function DoorSetTypeChange() {
    var accousticsvalue = $("#accoustics").val();
    if (accousticsvalue == "Yes") {
        let set = $('#doorsetType').val();
        if (set == "SD") {
            $("#accousticsmeetingStiles").removeClass('bg-white');
            $("#accousticsmeetingStiles").val('');
            $('#accousticsmeetingStilesIcon').attr('onclick', '');
            $('input[name="accousticsmeetingStiles"]').val('');

            $('#accousticsmeetingStiles').css({ 'disaplay': 'none' });
        } else {
            $('#accousticsmeetingStiles').css({ 'display': 'block' });
            $("#accousticsmeetingStiles").addClass('bg-white');
            $('#accousticsmeetingStilesIcon').attr('onclick', "return openAccousticsModal('accousticsmeetingStiles','MeetingStiles' ,'Meeting_Stiles')");
            $('#accousticsmeetingStiles').attr({ 'required': true });
        }
    } else {
        $("#accousticsmeetingStiles").removeClass('bg-white');
        $("#accousticsmeetingStiles").val('');
        $('#accousticsmeetingStilesIcon').attr('onclick', '');
        $('input[name="accousticsmeetingStiles"]').val('');
        $('#accousticsmeetingStiles').attr({ 'required': false });
    }
    MeetingStyle();
    var ConfigurableDoorFormula = JSON.parse(ConfigurableDoorFormulaJson);
    var TolleranceAdditionalNumber = 1;
    var FrameThicknessAdditionalNumber = 1;
    var GapAdditionalNumber = 1;

    var DoorSetType = $("#doorsetType").val();
    var swingType = $("#swingType").val();
    ConfigurableDoorFormula.forEach(function (elem, index) {
        var FormulaAdditionalData = JSON.parse(elem.value);
        if (DoorSetType == "SD") {
            $("#DDLAH").hide();
            $("#adjustmentLeafWidth2").val(0).attr('readonly', true);
            if (elem.slug == "leaf_width_1_for_single_door_set") {
                TolleranceAdditionalNumber = parseFloat((FormulaAdditionalData.tolerance != "") ? FormulaAdditionalData.tolerance : 1);
                FrameThicknessAdditionalNumber = parseFloat((FormulaAdditionalData.frame_thickness != "") ? FormulaAdditionalData.frame_thickness : 1);
                GapAdditionalNumber = parseFloat((FormulaAdditionalData.gap != "") ? FormulaAdditionalData.gap : 1);
            }
        } else if (DoorSetType == "DD") {
            $("#DDLAH").hide();
            $("#adjustmentLeafWidth2").val(0).attr('readonly', true);
            if (elem.slug == "leaf_width_1_for_double_door_set") {
                TolleranceAdditionalNumber = parseFloat((FormulaAdditionalData.tolerance != "") ? FormulaAdditionalData.tolerance : 1);
                FrameThicknessAdditionalNumber = parseFloat((FormulaAdditionalData.frame_thickness != "") ? FormulaAdditionalData.frame_thickness : 1);
                GapAdditionalNumber = parseFloat((FormulaAdditionalData.gap != "") ? FormulaAdditionalData.gap : 1);
            }

            //if(elem.slug == "leaf_width_2_for_double_door_set"){
            //    TolleranceAdditionalNumber = parseFloat((FormulaAdditionalData.tollerance != "")?FormulaAdditionalData.tollerance:1);
            //    FrameThicknessAdditionalNumber = parseFloat((FormulaAdditionalData.frame_thickness != "")?FormulaAdditionalData.frame_thickness:1);
            //    GapAdditionalNumber = parseFloat((FormulaAdditionalData.gap != "")?FormulaAdditionalData.gap:1);
            //}
        } else if (DoorSetType == "leaf_and_a_half") {
            $("#DDLAH").show();
            $("#adjustmentLeafWidth2").attr('readonly', false);
            if (elem.slug == "leaf_width_2_for_leaf_and_a_half") {
                TolleranceAdditionalNumber = parseFloat((FormulaAdditionalData.tolerance != "") ? FormulaAdditionalData.tolerance : 1);
                FrameThicknessAdditionalNumber = parseFloat((FormulaAdditionalData.frame_thickness != "") ? FormulaAdditionalData.frame_thickness : 1);
                GapAdditionalNumber = parseFloat((FormulaAdditionalData.gap != "") ? FormulaAdditionalData.gap : 1);
            }
        }
    });


    if ($("#doorsetType").val() == "DD") {



        var tollerance = 0;
        var gap = 0;
        var framethikness = 0;
        var soWidth = 0;
        // randomkey = 2;
        //var thisvalue = document.getElementsByClassName("foroPWidth");
        var thisvalue = document.getElementsByClassName("form-control");
        for (var i = 0; i < thisvalue.length; i++) {
            if (thisvalue[i].name == 'tollerance') {
                if (thisvalue[i].value == '') {
                    tollerance = 0;
                }
                else {
                    tollerance = parseInt(thisvalue[i].value);
                }
            }


            if (thisvalue[i].name == 'gap') {
                if (thisvalue[i].value == '') {
                    gap = 0;
                }
                else {
                    gap = parseInt(thisvalue[i].value);
                }
            }


            if (thisvalue[i].name == 'frameThickness') {
                if (thisvalue[i].value == '') {
                    framethikness = 0;
                }
                else {
                    framethikness = parseInt(thisvalue[i].value);
                }
            }

            if (thisvalue[i].name == 'sOWidth') {
                if (thisvalue[i].value == '') {
                    soWidth = 0;
                }
                else {
                    soWidth = parseInt(thisvalue[i].value);
                }
            }
        }
        var calculate = (soWidth - (tollerance * TolleranceAdditionalNumber) - (framethikness * FrameThicknessAdditionalNumber) - (GapAdditionalNumber * gap)) / 2;
        // $("#leafWidth2").val(calculate).attr('readonly',true);
        var Leaf2VisionPanelValue = $('#Leaf2VisionPanel-value').data("value");
        if (Leaf2VisionPanelValue == 'Yes') {
            $("#leaf2VisionPanel").val('Yes').attr("disabled", false);
        } else {
            $("#leaf2VisionPanel").val('No').attr("disabled", false);
        }

    } else if ($("#doorsetType").val() == "leaf_and_a_half") {
        $("#leaf2VisionPanel").attr({ 'disabled': false, 'required': true });
    } else {
        // $("#leafWidth2").val(0).attr('readonly',true);
        var Leaf2VisionPanelValue = $('#Leaf2VisionPanel-value').data("value");
        if (Leaf2VisionPanelValue == '') {
            $("#leaf2VisionPanel").val('No').attr("disabled", true);
        }
    }

    if (swingType == 'DA') {
        $('#latchType').siblings('label').children('.dsl').html('');
        $('#latchType option').eq(0).prop('selected', true);
        $('#latchType').attr("disabled", true);

    } else {
        $('#latchType').attr("disabled", false);
    }
    filterHandling();
    IntumescentSeals();
    DoorSetPrice();
}
function MeetingStyle() {
    // Lipping And Intumescent
    // Meeting Style input field
    // if($("#fireRating").val()!="NFR"){
    var MeetingStyleValue = document.getElementById('MeetingStyle-value');
    if (MeetingStyleValue != null) {
        MeetingStyleValue = $("#MeetingStyle-value").data("value");
        if (MeetingStyleValue == "") {
            $("#meetingStyle").val('');
        }
    } else {
        $("#meetingStyle").val('');
    }

    if ($("#swingType").val() == "SA") {
        if ($("#doorsetType").val() == "DD" || $("#doorsetType").val() == "leaf_and_a_half") {
            $('#meetingStyle').attr('disabled', false);
            $('#meetingStyle').children('option[value="Scalloped"]').hide();
            $('#meetingStyle').children('option[value="Rebated"]').show();
            $('#meetingStyle').children('option[value="Flat"]').show();
        } else {
            $('#meetingStyle').attr('disabled', true).val("");
        }
    } else if ($("#swingType").val() == "DA") {
        if ($("#doorsetType").val() == "DD") {
            $('#meetingStyle').attr('disabled', false);
            $('#meetingStyle').children('option[value="Scalloped"]').show();
            $('#meetingStyle').children('option[value="Rebated"]').hide();
            $('#meetingStyle').children('option[value="Flat"]').hide();
        } else {
            $('#meetingStyle').attr('disabled', true).val("");
        }
    }
    // } else {
    //     $('#meetingStyle').attr('disabled',true).val("");
    // }
}

$(document).ready(function(){
    filterHandling();

    setTimeout(function(){
        filterSpecies();
        frameMaterialFilter($("#fireRating").val());
        framTypeChangeInputEnableDisable();
    }, 3000)

    setTimeout(function(){
        DoorSetTypeChange();
    }, 200);

    $("#doorDimensionGroove,#DoorDimensionGrooveLeaf2").removeClass("bg-white");
    $("#doorDimensionGroove,#DoorDimensionGrooveLeaf2").attr({ 'disabled': false, "readonly": true });

});

function filterSpecies(){
    var url = $("#door_dimension_url").val();
    var base_url = $("input[name='base_url']").val();
    var door_leaf_facing = $("#doorLeafFacing").val();
    var door_leaf_finish = $(".doorLeafFinishSelect").val();
    var leaf_type = $("#leafConstruction").val();
    var firerating = $("#fireRating").val();

    if(!door_leaf_facing || !leafConstruction || !firerating){
        return false;
    }
    let pageId = pageIdentity();

    $.ajax({
        url: url,
        type: "GET",
        data: { door_leaf_facing: door_leaf_facing, door_leaf_finish: door_leaf_finish, leaf_type: leaf_type, firerating, firerating, page_id: pageId },
        success: function (data) {
            console.log("dddddddddddd");
            console.log(data)
            var result = data.map((row) => (
                console.log("dfdfdfdfdf"),
                console.log(row),



                `<div class="col-md-2 col-sm-4 col-6 cursor-pointer" data-dismiss="modal" onclick="DoorDimensionValueFill(${row.id},'${row.code}',${row.mm_width},${row.mm_height})">
                <div class="color_box">
                <div class="frameMaterialImage"><img width="100%" height="100" src="${base_url}/DoorDimension/${row.image ? row.image : "vicaima_default_doorDimantion.jpg"}">
                </div><h4>${row.code}-${row.mm_height}x${row.mm_width}</h4>
                </div>
                </div>`
            ))
            $("#DoorDimensionBody").empty().append(result)


            // data.forEach(function(item) {

            //     let isSelected = item.OptionValue === editDoorLeafFacingValue;
            //     html += `<option value="${item.OptionValue}" ${isSelected ? 'selected' : ''}>${item.OptionValue}</option>`;
            // });

        }
    })


    var tollerance = $("#tollerance").val();

    setTimeout(() => {
        var so_width = parseInt($("#sOWidth").val());
        var so_height = parseInt($("#sOHeight").val());
        framewidth();
        $("#frameHeight").val(so_height - parseInt(tollerance));
    }, 500);
};

function framewidth(){
    var DoorSetType = $('select[name="doorsetType"]').val();
    var Gap = parseInt($('input[name="gap"]').val(), 10);  // Ensure Gap is a number
    var FrameThickness = parseInt($('#frameThickness').val(), 10);  // Ensure FrameThickness is a number
    if (DoorSetType == "SD"){
        var FrameWidth =  parseInt($('input[name="leafWidth1"]').val(), 10) + Gap + Gap + Gap + FrameThickness + FrameThickness;
    }else{
        var FrameWidth = parseInt($('input[name="leafWidth1"]').val(), 10) + parseInt($('input[name="leafWidth2"]').val(), 10) + Gap + Gap + Gap + FrameThickness + FrameThickness;
    }

    $("#frameWidth").val(FrameWidth);
}

function filterHandling() {
    let pageId = pageIdentity();
    var doorsetType = $("#doorsetType").val();
    var swingType = $("#swingType").val();
    if (pageId == '') {
        swal('Warning', 'Somethings went wrong!');
        return false;
    }
    $.ajax({
        url: $("#get-handing-options").text(),
        method: "POST",
        dataType: "Json",
        data: { pageId: pageId, doorsetType: doorsetType, swingType: swingType, _token: $("#_token").val() },
        success: function (result) {
            var innerHtml = "";
            if (result.status == "ok") {
                var data = result.data;
                var length = data.length;
                innerHtml += '<option value="">Select Handing</option>';
                var HandingValue = document.getElementById('Handing-value');
                if (HandingValue != null) {
                    HandingValue = $("#Handing-value").data("value");
                    for (var i = 0; i < length; i++) {
                        var selected = "";
                        if (HandingValue == data[i].OptionKey) {
                            selected = "selected";
                        }
                        innerHtml += '<option value="' + data[i].OptionKey + '" ' + selected + '>' + data[i].OptionValue + '</option>';
                    }
                } else {
                    for (var i = 0; i < length; i++) {
                        innerHtml += '<option value="' + data[i].OptionKey + '">' + data[i].OptionValue + '</option>';
                    }
                }
            } else {
                innerHtml += '<option value="">No Handing Found</option>';
            }
            $("#Handing").empty().append(innerHtml);
            let elements = $(this);
            render(elements);
        },
        error: function (err) {

        }
    });
}


// Lipping & Intumescent
// It is a select field or select tag is `Intumescent Seal Arrangement`
// These only works when minimum these fields Fire Rating , Doorset Type , Swing Type , S.O Width is fillin.
// These function is also related other function you can easily find. Search these 'IntumescentSeals()'
// These is related to `setting_intumescentseals2` table
function IntumescentSeals() {

}
// function IntumescentSeals() {
//     // alert(55);
//     let pageId = pageIdentity();
//     const latchTypeValue = $('#latchType').val();       // L,UL
//     const swingTypeValue = $('#swingType').val();       // SA,DA
//     const doorsetTypeValue = $('#doorsetType').val();   // SD,DD
//     const fireRatingValue = $('#fireRating').val();     // FD30
//     const overpanelValue2 = $('#overpanel').val();     // Yes
//     const leafWidth1Value = $('#leafWidth1').val();
//     const leafHeightNoOPValue = $('#leafHeightNoOP').val();
//     const sOWidthValue = $('#sOWidth').val();
//     const sOHeightValue = $('#sOHeight').val();
//     let overpanel = '';
//     if (overpanelValue2 == 'Yes') {
//         overpanel = 'OP';
//     }

//     const doorLeafFacingValueNew = $('#doorLeafFacingValue').val();
//     const frameMaterialNew = $('#frameMaterialNew').val();
//     // The leaf and a half should be treated as a double door so the same way it works for a double door should work for leaf and a half.
//     // start
//     let $aa = '';
//     if (doorsetTypeValue == 'leaf_and_a_half') {
//         const dobledoor = 'DD';
//         $aa = latchTypeValue + swingTypeValue + dobledoor + overpanel; // LSASD
//     } else {
//         $aa = latchTypeValue + swingTypeValue + doorsetTypeValue + overpanel; // LSASD
//     }
//     // end

//     let SelectedValue = 0;

//     var IntumescentLeapingSealArrangementValue = document.getElementById('IntumescentLeapingSealArrangement-value');
//     if (IntumescentLeapingSealArrangementValue != null) {
//         SelectedValue = $("#IntumescentLeapingSealArrangement-value").data("value");
//     }

//     // console.log($aa);
//     if (fireRatingValue != '' && sOWidthValue != '' && sOHeightValue != '') {
//         $.ajax({
//             url: $("#Filterintumescentseals").text(),
//             method: "POST",
//             dataType: "Json",
//             data: { pageId: pageId, SelectedValue: SelectedValue, fireRatingValue: fireRatingValue, intumescentseals: $aa, leafWidth1Value: leafWidth1Value, leafHeightNoOPValue: leafHeightNoOPValue, doorLeafFacingValueNew: doorLeafFacingValueNew, frameMaterialNew: frameMaterialNew, _token: $("#_token").val() },
//             success: function (result) {
//                 // console.log(result);
//                 // console.log(result.data);
//                 // console.log(result.c);
//                 // console.log(result.allValue);
//                 // console.log(result.sql);
//                 if (result.status == 'ok') {
//                     // let datalength = result.data.length;
//                     // let i = 0;
//                     // let dat = '';
//                     // let data = result.data;
//                     // while(datalength > i){
//                     //     dat += '<option value="'+data[i].id+'">'+data[i].brand+' - '+data[i].intumescentSeals+'</option>';
//                     //     i++;
//                     // }

//                     $('#intumescentSealArrangement').empty().append(result.data);
//                     $('#sOWidth').css({ 'border': '1px solid #ced4da' });
//                     $('#sOHeight').css({ 'border': '1px solid #ced4da' });

//                 } else if (result.status == 'error2') {
//                     $('#intumescentSealArrangement').empty('');
//                     swal('Warning', result.msg);
//                     $('#sOWidth').css({ 'border': '1px solid red' });
//                     $('#sOHeight').css({ 'border': '1px solid red' });
//                 }
//             }
//         });
//     }
// }
function DoorLeafFacingChange($status = false) {
    let pageId = pageIdentity();
    var doorLeafFacing = $("#doorLeafFacing").val();
    var doorLeafFacingValue = $("#doorLeafFacingValue").val();
    var DoorLeafFinishColorValue = document.getElementById('DoorLeafFinishColor-value');
    if (DoorLeafFinishColorValue != null) {
        DoorLeafFinishColorValue = $("#DoorLeafFinishColor-value").data("value");
        if (DoorLeafFinishColorValue == "") {
            $("#doorLeafFinishColor").val("");
        }
    } else {
        $("#doorLeafFinishColor").val("");
    }

    // $("#doorLeafFinish").val("");

    $.ajax({
        url: $("#door-leaf-face-value-filter").html(),
        method: "POST",
        dataType: "Json",
        data: { pageId: pageId, doorLeafFacing: doorLeafFacing, doorLeafFacingValue: doorLeafFacingValue, _token: $("#_token").val() },
        success: function (result) {

            var DoorLeafFacingValueValue = document.getElementById('DoorLeafFacingValue-value');
            var DoorLeafFinishValue = document.getElementById('DoorLeafFinish-value');
            var DecorativeGrovesValue = document.getElementById('DecorativeGroves-value');
            if (result.status == "ok") {
                var innerHtml = '';
                var innerHtml1 = '';
                var data = result.data;
                var length = result.data.length;
                innerHtml += '<option value="">Select Door leaf facing</option>';
                innerHtml1 += '<option value="">Select Door leaf finish</option>';
                for (var index = 0; index < length; index++) {

                    if (data[index].doorLeafFacing == doorLeafFacing) {
                        var DoorLeafFacingValueSelectedStatus = '';
                        if ($status == true && doorLeafFacing == 'Laminate') {
                            if (doorLeafFacingValue == data[index].Key) {
                                DoorLeafFacingValueSelectedStatus = "selected";
                            }
                        }
                        if (DoorLeafFacingValueValue != null && doorLeafFacingValue == '') {
                            DoorLeafFacingValueValue = $("#DoorLeafFacingValue-value").data("value");
                            var DoorLeafFacingValueSelected = "";
                            if (DoorLeafFacingValueValue == data[index].Key) {
                                DoorLeafFacingValueSelected = "selected";
                            }

                            innerHtml += '<option value="' + data[index].Key + '" ' + DoorLeafFacingValueSelected + ' ' + DoorLeafFacingValueSelectedStatus + '>' + data[index].doorLeafFacingValue + '</option>';
                        } else {
                            innerHtml += '<option value="' + data[index].Key + '" ' + DoorLeafFacingValueSelectedStatus + '>' + data[index].doorLeafFacingValue + '</option>';
                        }
                    }
                }

                if (doorLeafFacing == "Laminate" || doorLeafFacing == "PVC") {
                    if (doorLeafFacing == "Laminate") {
                        $("#decorativeGroves").attr({ 'disabled': true });
                        $('#decorativeGroves option:first').prop('selected', true);
                        $("#grooveLocation").attr({ 'disabled': true });
                        $('#grooveLocation option:first').prop('selected', true);
                        $("#grooveWidth").val(0).attr({ 'disabled': true });
                        $("#grooveDepth").val(0).attr({ 'disabled': true });
                        $("#numberOfGroove").val(0).attr({ 'disabled': true });
                        $("#numberOfVerticalGroove").val(0).attr({ 'disabled': true });
                        $("#decorativeGroves").removeAttr('required')
                        $("#numberOfHorizontalGroove").val(0).attr({ 'disabled': true });

                    } else {

                        // $("#decorativeGroves").attr({'required':true});
                        $("#decorativeGroves").attr({ 'disabled': false });
                    }

                    if (DecorativeGrovesValue != null) {
                        DecorativeGrovesValue = $("#DecorativeGroves-value").data("value");
                        if (DecorativeGrovesValue == "") {
                            $("#decorativeGroves").val('No');
                        }
                    } else {
                        // $("#decorativeGroves").val('');
                    }

                    var color = result.color;
                    $("#ralColorModalLabel").text("Door Leaf Finish");
                    if (DoorLeafFinishValue != null) {
                        DoorLeafFinishValue = $("#DoorLeafFinish-value").data("value");
                    } else {
                        DoorLeafFinishValue = "";
                    }
                    innerHtml1 = '<label for="doorLeafFacing">Door Leaf Finish</label><div class="input-icons doorLeafFinishInputDiv"><i class="fa fa-info icon" id="doorLeafFinishIcon" onClick="$(\'#ralColor\').modal(\'show\');"></i><input type="text" required  readonly name="doorLeafFinish" id="doorLeafFinish" value="' + DoorLeafFinishValue + '" class="form-control bg-white"></div>';
                    var innerHtmlPopUp = '<div class="container"><div class="row">';
                    if (color != '') {
                        var length = result.color.length;
                        for (var colorIndex = 0; colorIndex < length; colorIndex++) {
                            innerHtmlPopUp += '<div class="col-md-2 col-sm-4 col-6" onClick="SelectRalColor(\'\',' + color[colorIndex].id + ',\'' + color[colorIndex].Hex + '\',\'' + color[colorIndex].ColorName + '\',\'' + doorLeafFacing + '\')">';
                            innerHtmlPopUp += '<div class="color_box">';
                            innerHtmlPopUp += '<div class="color_place" style="background:' + color[colorIndex].Hex + '"></div>';
                            innerHtmlPopUp += ' <h4>' + color[colorIndex].ColorName + '</h4>';
                            innerHtmlPopUp += '</div></div>';
                        }
                    }

                    innerHtmlPopUp += '</div></div>';
                    $("#printedColor").empty().append(innerHtmlPopUp);

                    // }else if(doorLeafFacing=="Veneer" || doorLeafFacing=="Kraft_Paper" || doorLeafFacing=="Playwood"){
                } else {

                    // $("#decorativeGroves").attr({'required':true});
                    $("#decorativeGroves").attr({ 'disabled': false });
                    if (DecorativeGrovesValue != null) {
                        DecorativeGrovesValue = $("#DecorativeGroves-value").data("value");
                        if (DecorativeGrovesValue == "") {
                            $("#decorativeGroves").val('No');
                        }
                    } else {
                        // $("#decorativeGroves").val('No');
                    }

                    var color = '';
                    var color = result.color;

                    innerHtml1 = `
                        <label for="doorLeafFacing" class="">Door Leaf Finish

                            </label>
                        <select onchange="doorLeafFinishChange();" name="doorLeafFinish" id="doorLeafFinish" option_slug="door_leaf_finish" class="form-control doorLeafFinishSelect">`;
                    innerHtml1 += '<option value="">Select Door Leaf Finish </option>';
                    if (color != '') {
                        var length = result.color.length;
                        for (var colorIndex = 0; colorIndex < length; colorIndex++) {

                            if (DoorLeafFinishValue != null) {

                                DoorLeafFinishValue = $("#DoorLeafFinish-value").data("value");
                                var DoorLeafFinishSelected = "";

                                if (DoorLeafFinishValue == color[colorIndex].OptionKey) {
                                    DoorLeafFinishSelected = "selected";
                                }
                                innerHtml1 += '<option value="' + color[colorIndex].OptionKey + '" ' + DoorLeafFinishSelected + '>' + color[colorIndex].OptionValue + '</option>';

                            } else {

                                innerHtml1 += '<option value="' + color[colorIndex].OptionKey + '">' + color[colorIndex].OptionValue + '</option>';

                            }
                        }
                    }
                    innerHtml1 += '</select>';

                    $("#doorLeafFinishColor").attr({ 'disabled': true });
                    if (DoorLeafFinishColorValue != null) {
                        DoorLeafFinishColorValue = $("#DoorLeafFinishColor-value").data("value");
                        if (DoorLeafFinishColorValue == "") {
                            $("#doorLeafFinishColor").val("");
                        }
                    } else {
                        $("#doorLeafFinishColor").val("");
                    }
                }

                // $(".doorLeafFinishDiv").empty().append(innerHtml1);

                $("#doorLeafFacingValue").empty().append(innerHtml);
                if (doorLeafFacing == 'Kraft_Paper') {
                    $("#doorLeafFacingValue").attr({ 'disabled': true });
                    var noFacingValue = '';
                    noFacingValue += '<option value="">No Door facing Value found</option>';
                    $("#doorLeafFacingValue").empty().append(noFacingValue);
                } else {
                    $("#doorLeafFacingValue").attr({ 'disabled': false });
                    $("#doorLeafFinishColor").removeClass("bg-white");
                    $("#doorLeafFinishColor").val('').attr({ 'disabled': true });
                    $("#doorLeafFinishColorIcon").attr("onclick", "");
                }

            } else {

                if (doorLeafFacing == "Kraft_Paper") {
                    var color = '';
                    var color = result.color;
                    innerHtml1 = '';
                    if (color != '') {
                        var length = result.color.length;
                        innerHtml1 += '<option value="">Select face finish</option>';
                        for (var colorIndex = 0; colorIndex < length; colorIndex++) {
                            if (DoorLeafFinishValue != null) {
                                DoorLeafFinishValue = $("#DoorLeafFinish-value").data("value");
                                var DoorLeafFinishSelected = "";
                                if (DoorLeafFinishValue == color[colorIndex].OptionKey) {
                                    DoorLeafFinishSelected = "selected";
                                }

                                if (color[colorIndex].UnderAttribute == "Kraft_Paper") {
                                    innerHtml1 += '<option value="' + color[colorIndex].OptionKey + '" ' + DoorLeafFinishSelected + '>' + color[colorIndex].OptionValue + '</option>';
                                }
                            } else {
                                if (color[colorIndex].UnderAttribute == "Kraft_Paper") {
                                    innerHtml1 += '<option value="' + color[colorIndex].OptionKey + '">' + color[colorIndex].OptionValue + '</option>';
                                }
                            }
                        }
                    }
                    // $("#doorLeafFinish").empty().append(innerHtml1);
                    $("#doorLeafFinishColor").attr({ 'disabled': true });
                    if (DoorLeafFinishColorValue != null) {
                        DoorLeafFinishColorValue = $("#DoorLeafFinishColor-value").data("value");
                        if (DoorLeafFinishColorValue == "") {
                            $("#doorLeafFinishColor").val("");
                        }
                    } else {
                        $("#doorLeafFinishColor").val("");
                    }

                } else {
                    // $("#doorLeafFinish").empty().append('<option value="">No Door leaf Finish found</option>');

                    $("#doorLeafFinishColor").removeClass("bg-white");
                    $("#doorLeafFinishColor").val('').attr({ 'disabled': true });
                    $("#doorLeafFinishColorIcon").attr("onclick", "");
                }
                $("#doorLeafFacingValue").empty().append('<option value="">No Door leaf facing value found</option>');
            }
        }
    });
    // doorLeafFacingPrice('doorLeafFacing');

}


function GlazingSystemsChange(id = null,type=""){

    var glazingSystems = (id == null)?$("#glazingSystems").val():id;
    if(type == "opglazingSystems"){
        glazingSystems = (id == null)?$("#opglazingSystems").val():id;
    }
    if(type == "sideLight1GlazingSystems"){
        glazingSystems = (id == null)?$("#sideLight1GlazingSystems").val():id;
    }
    if(type == "sideLight2GlazingSystems"){
        glazingSystems = (id == null)?$("#sideLight2GlazingSystems").val():id;
    }

    if(glazingSystems != ''){
        let pageId = pageIdentity();
        $.ajax({
            url:  $("#glazing-thikness-filter").html(),
            method:"POST",
            dataType:"Json",
            data:{pageId:pageId,glazingSystems:glazingSystems,_token:$("#_token").val()},
            success: function(result){
                // console.log(result)
                if(result.status=="ok"){
                    var data = result.data;
                    // var data2 = result.data2;
                    if(data != ''){
                        if(type == "opglazingSystems"){
                            $("#opglazingSystemsThickness").val(data.GlazingThickness);
                            $('#opglazingBeadsFixingDetail').val(data.GlazingBeadFixingDetail);
                        }else if(type == "sideLight1GlazingSystems"){
                            $("#sideLight1GlazingSystemsThickness").val(data.GlazingThickness);
                            $('#sideLight1GlazingBeadsFixingDetail').val(data.GlazingBeadFixingDetail);
                        }else if(type == "sideLight2GlazingSystems"){
                            $("#sideLight2GlazingSystemsThickness").val(data.GlazingThickness);
                            $('#sideLight2GlazingBeadsFixingDetail').val(data.GlazingBeadFixingDetail);
                        }else{
                            $("#glazingSystemsThickness").val(data.GlazingThickness);
                            $('#glazingBeadsFixingDetail').val(data.GlazingBeadFixingDetail);
                        }

                    } else {
                        $('#glazingSystemsThickness').val('');
                    }
                    // if(data2 != '' && data2 != null){
                    //     $('#glazingBeadsFixingDetail').val(data2.OptionValue);
                    // } else {
                    //     $('#glazingBeadsFixingDetail').val('');
                    //     // console.log('2222')
                    // }
                } else {
                    $("#glazingSystemsThickness").val(0);
                }
            }
        });
    }
}

function GlassTypeChange(id = null,type=""){
    var glassType = (id == null)?$("#glassType").val():id;
    if(type == "opGlassType"){
        glassType = (id == null)?$("#opGlassType").val():id;
    }
    if(type == "sideLight1GlassType"){
        glassType = (id == null)?$("#sideLight1GlassType").val():id;
    }
    if(type == "sideLight2GlassType"){
        glassType = (id == null)?$("#sideLight2GlassType").val():id;
    }

    if(glassType != ''){
        let pageId = pageIdentity();
        let fireRating =$("#fireRating").val();
        $.ajax({
            url:  $("#glass-type-filter").html(),
            method:"POST",
            dataType:"Json",
            data:{pageId:pageId,glassType:glassType,fireRating:fireRating,_token:$("#_token").val()},
            success: function(result){
                if(result.status=="ok"){
                    var innerHtml ='';
                    var data = result.data;
                    var length = result.data.length;
                    // innerHtml+='<option value="">Select Glass thikness</option>';

                    var GlassThicknessValue = document.getElementById('GlassThickness-value');
                     console.log(data[0].GlassThickness)
                    if(type == "opGlassType"){
                        $("#opglassThickness").val(data[0].GlassThickness);
                    }else if(type == "sideLight1GlassType"){
                        $("#sideLight1GlassThickness").val(data[0].GlassThickness);
                    }else if(type == "sideLight2GlassType"){
                        $("#sideLight2GlassThickness").val(data[0].GlassThickness);
                    }else{
                        $("#glassThickness").val(data[0].GlassThickness);
                    }
                    // $("#glassThickness").val(data[0].OptionValue);
                }else{
                    $("#glassThickness").val(0);
                }
            }
        });
    }
}
function opGlassTypeFilter(id = null, OPGlassType = null) {
    console.log(OPGlassType);
    let pageId = pageIdentity();
    let fireRating = $("#fireRating").val();
    let opGlassIntegrity = (id == null) ? $("#opGlassIntegrity").val() : id;
    $.ajax({
        url: $("#opGlassTypeFilterUrl").html(),
        method: "POST",
        dataType: "Json",
        data: { pageId: pageId, fireRating: fireRating, opGlassIntegrity: opGlassIntegrity, _token: $('input[name="token"]').val() },
        success: function (result) {
            var OPGlassTypeInnerHtml = "";

            // var OPGlassTypeValue = document.getElementById('OPGlassType-value');

            if (result.status == "ok") {
                var data = result.data;
                var length = result.data.length;
                OPGlassTypeInnerHtml += '<option value="">Select OP Glass Type</option>';
                for (var i = 0; i < length; i++) {

                    var selected = "";

                    // if (OPGlassTypeValue) {
                    // OPGlassTypeValue = $("#opGlassType-selected").text();
                    if (OPGlassType == data[i].Key) {
                        selected = "selected";
                    }
                    // }

                    OPGlassTypeInnerHtml += '<option value="' + data[i].Key + '" ' + selected + '>' + data[i].GlassType + '</option>';
                }
                $("#opGlassType").empty().append(OPGlassTypeInnerHtml);
            } else {
                OPGlassTypeInnerHtml += '<option value="">No OP Glass Type Found</option>';
                $("#opGlassType").empty().append(OPGlassTypeInnerHtml);
            }
        },
        error: function (data) {
            swal("Oops!!", "Something went wrong. Please try again.", "error");
        }
    });
}
function FrameFinishChange(showModal = false, typeinput) {
    let pageId = pageIdentity();
    if (typeinput == 'framefinish') {
        var doorLeafFinish = $("#frameFinish").val();
        $('#ralColorModalLabel').html('Frame Finish Color');
    } if (typeinput == 'architraveFinish') {
        var doorLeafFinish = $("#architraveFinish").val();
        $('#ralColorModalLabel').html('Architrave Finish Color');
    }
    var FrameFinishColorValue = $('#FrameFinishColor-value').val();
    if (doorLeafFinish == "Painted_Finish") {
        if (typeinput == 'architraveFinish') {
            $("#architraveFinishcolor").attr({ 'disabled': false });
            $('#architraveFinishcolor').addClass("bg-white");
        }
        $.ajax({
            url: $("#ral-color-filter").html(),
            method: "POST",
            dataType: "Json",
            data: { pageId: pageId, doorLeafFinish: 'Painted', _token: $("#_token").val() },
            success: function (result) {
                if (result.status == "ok") {
                    var innerHtml = '';
                    var data = result.data;
                    var length = result.data.length;
                    // innerHtml+='<option value="">Select Door leaf color value</option>';
                    innerHtml += '<div class="container"><div class="row">';
                    innerHtml += '<div id="field" hidden>' + doorLeafFinish + '</div>';
                    $("#doorLeafFinishColor").attr({ 'disabled': false });
                    $("#framefinishColor").attr({ 'disabled': false });


                    for (var index = 0; index < length; index++) {
                        if (FrameFinishColorValue != null) {
                            FrameFinishColorValue = $('#FrameFinishColor-value').val();
                            if (FrameFinishColorValue == data[index].id) {
                                var select = '<option value="' + data[index].id + '" selected>' + data[index].ColorName + '</option>';
                                $("#framefinishColor").empty().append(select);
                            }
                        }
                        innerHtml += '<div class="col-md-2 col-sm-4 col-6" onClick="SelectRalColor(\'' + typeinput + '\',' + data[index].id + ',\'' + data[index].Hex + '\',\'' + data[index].ColorName + '\',\'' + doorLeafFinish + '\')">';
                        innerHtml += '<div class="color_box">';
                        innerHtml += '<div class="color_place" style="background:' + data[index].Hex + '"></div>';
                        innerHtml += ' <h4>' + data[index].ColorName + '</h4>';
                        innerHtml += '</div></div>';
                        // innerHtml+='<option value="'+data[index].Hex+'" style="background:'+data[index].Hex+'">'+data[index].ColorName+'</option>'
                    }
                    innerHtml += '</div></div>';
                    $("#printedColor").empty().append(innerHtml);
                    // $("#doorLeafFinishColor").empty().append(innerHtml);
                    if (showModal == true) {
                        $("#ralColor").modal('show');
                    }
                } else {
                    $("#doorLeafFinishColor").empty().append('<option value="">No Door leaf Ral Color Found</option>');
                }
            }
        });
    } else {
        var doorLeafFinish = $("#doorLeafFinish").val();
        var frameFinish = $("#frameFinish").val();
        var architraveFinish = $("#architraveFinish").val();
        if (doorLeafFinish != 'Painted') {
            $("#doorLeafFinishColor").val('').attr({ 'disabled': true });
        } else if (frameFinish != 'Painted_Finish') {
            $("#framefinishColor").val('').attr({ 'disabled': true });
        } else if (architraveFinish != 'Painted_Finish') {
            $('input[name="architraveFinishcolor"]').val('');
            $('#architraveFinishcolor').removeClass('bg-white');
            if (typeinput == 'architraveFinish') {
                $("#architraveFinishcolor").html('<option value="">Architrave  Finish  Color</option>');
                $("#architraveFinishcolor").val('').attr({ 'disabled': true });
            }
        }
    }
    doorLeafFacingPrice('frameFinish');
}

$("#doorLeafFinish").change(function () {
    var value = $("#doorLeafFinish").val();
    doorLeafFacingPrice('doorLeafFinish', value);
    doorLeafFacingPrice('doorLeafFinish1', value);
});


 $(document).on('change','#leafConstruction', function(){
       let leaftypeValue = $(this).val();
       if(leaftypeValue == 'Primed'){
        doorLeafFinishChange();
       }else{
        $("#doorLeafFinishColor").attr({ 'disabled': true });
        $("#doorLeafFinishColor").removeClass("bg-white");
        $("#doorLeafFinishColor-selected").empty();
        $("#doorLeafFinishColor-price").empty();
        $("#doorLeafFinishColor-section").removeClass("table_row_hide");
        $("#doorLeafFinishColor-section").addClass("table_row_show");
       }

    })

    $(document).on('change','#doorLeafFacing', function(){
       let doorLeafFacing = $(this).val();
       $("#doorLeafFinishColor-selected").empty();
       $("#doorLeafFinishColor-price").empty();
       if(doorLeafFacing == 'Factory Industrial Primed' || doorLeafFacing == 'Paint Sanded' || doorLeafFacing == 'Primed 2 Go'){
        doorLeafFinishChange();
       }else{
        $("#doorLeafFinishColor").attr({ 'disabled': true });
        $("#doorLeafFinishColor").removeClass("bg-white");
        $("#doorLeafFinishColor-selected").empty();
        $("#doorLeafFinishColor-price").empty();
        $("#doorLeafFinishColor-section").removeClass("table_row_hide");
        $("#doorLeafFinishColor-section").addClass("table_row_show");
       }

    })

function doorLeafFinishChange() {

    let doorLeafFacing = $('#doorLeafFacing').val();
   let leafConstruction = $('#leafConstruction').val();
    let pageType = 6;

    var changedFieldId = "#doorLeafFinish";
    var doorLeafFinish = $(changedFieldId).val();
    var ActualValue = $(changedFieldId + ' :selected').val();
    var ElementValue = $(changedFieldId + ' :selected').text();
    doorLeafFacingPrice('LeafSet', ActualValue);
    $('#LeafSet-selected1').empty().text(ActualValue);
    if (ActualValue == "") {

        var DoorLeafFinishValue = document.getElementById('DoorLeafFinish-value');
        if (DoorLeafFinishValue != null) {
            DoorLeafFinishValue = $("#DoorLeafFinish-value").data("value");
            if (DoorLeafFinishValue != "") {
                doorLeafFinish = ActualValue = ElementValue = DoorLeafFinishValue;
            }
        }
    }

    var Options = JSON.parse(OptionsJson);
    var SelectedOptions = JSON.parse(SelectedOptionsJson);
    var possibleSelectedOptionsArray = JSON.parse(possibleSelectedOptionsJson);
    var option_slug = $(changedFieldId).attr("option_slug");



    var price = 0.00;

    if (possibleSelectedOptionsArray.hasOwnProperty(option_slug)) {
        SelectedOptions.forEach(function (elem, index) {
            if (ActualValue == elem.OptionKey) {
                price = elem.SelectedOptionCost;
            }
        });

    } else {

        Options.forEach(function (elem, index) {
            if (ActualValue == elem.OptionKey) {
                price = elem.OptionCost;
            }
        });
    }

    var name = $(changedFieldId).attr("name");
    $("#" + name + "-selected").empty().text(ElementValue);
    $("#" + name + "-price").empty().text("£" + price);
    $("#" + name + "-section").removeClass("table_row_hide");
    $("#" + name + "-section").addClass("table_row_show");



    if (leafConstruction == "Primed" && (doorLeafFacing == 'Factory Industrial Primed' || doorLeafFacing == 'Paint Sanded' || doorLeafFacing == 'Primed 2 Go')) {
        $('.SheenLevel').css({ 'display': 'none' });
        $("#doorLeafFinishColor").addClass("bg-white");
        $.ajax({
            url: $("#ral-color-filter").html(),
            method: "POST",
            dataType: "Json",
            data: { leafConstruction: leafConstruction,doorLeafFacing:doorLeafFacing,pageType:pageType, _token: $("#_token").val() },
            success: function (result) {

                if (result.status == "ok") {

                    var innerHtml = '';
                    var innerHtml1 = '';
                    var data = result.data;
                    var length = result.data.length;
                    // innerHtml+='<option value="">Select Door leaf color value</option>';
                    innerHtml += '<div class="container"><div class="row">';
                    $("#ralColorModalLabel").text("Door Leaf Finish Color");
                    $("#doorLeafFinishColor").attr({ 'disabled': false });
                    $("#doorLeafFinishColor").addClass("bg-white");
                    for (var index = 0; index < length; index++) {
                        innerHtml += '<div class="col-md-2 col-sm-4 col-6" onClick="SelectRalColor(\'\',' + data[index].id + ',\'' + data[index].Hex + '\',\'' + data[index].ColorName + '\',\'' + doorLeafFacing + '\')">';
                        innerHtml += '<div class="color_box">';
                        innerHtml += '<div class="color_place" style="background:' + data[index].Hex + '"></div>';
                        innerHtml += ' <h4>' + data[index].ColorName + '</h4>';
                        innerHtml += '</div></div>';
                        // innerHtml+='<option value="'+data[index].Hex+'" style="background:'+data[index].Hex+'">'+data[index].ColorName+'</option>'
                    }
                    innerHtml += '</div></div>';
                    $("#printedColor").empty().append(innerHtml);
                    // $("#doorLeafFinishColor").empty().append(innerHtml);
                    // $("#ralColor").modal('show');
                    $("#doorLeafFinishColorIcon").attr("onclick", "$('#ralColor').modal('show')");
                } else {
                    $("#doorLeafFinishColor").empty().append('<option value="">No Door leaf Ral Color Found</option>');
                }
            }
        });
    } else {
        $("#doorLeafFinishColor").removeClass("bg-white");
        $("#doorLeafFinishColor").val('').attr({ 'disabled': true });
        $("#doorLeafFinishColorIcon").attr("onclick", "");
        $('.SheenLevel').css({ 'display': 'none' });
        if (doorLeafFinish == "Laqure_Finish") {
            $('.SheenLevel').css({ 'display': 'block' });
        }
    }
}
// function doorLeafFinishChange() {

//     var changedFieldId = "#doorLeafFinish";
//     var doorLeafFinish = $(changedFieldId).val();
//     var ActualValue = $(changedFieldId + ' :selected').val();
//     var ElementValue = $(changedFieldId + ' :selected').text();
//     doorLeafFacingPrice('LeafSet', ActualValue);
//     $('#LeafSet-selected1').empty().text(ActualValue);
//     if (ActualValue == "") {

//         var DoorLeafFinishValue = document.getElementById('DoorLeafFinish-value');
//         if (DoorLeafFinishValue != null) {
//             DoorLeafFinishValue = $("#DoorLeafFinish-value").data("value");
//             if (DoorLeafFinishValue != "") {
//                 doorLeafFinish = ActualValue = ElementValue = DoorLeafFinishValue;
//             }
//         }
//     }

//     var Options = JSON.parse(OptionsJson);
//     var SelectedOptions = JSON.parse(SelectedOptionsJson);
//     var possibleSelectedOptionsArray = JSON.parse(possibleSelectedOptionsJson);
//     var option_slug = $(changedFieldId).attr("option_slug");



//     var price = 0.00;

//     if (possibleSelectedOptionsArray.hasOwnProperty(option_slug)) {
//         SelectedOptions.forEach(function (elem, index) {
//             if (ActualValue == elem.OptionKey) {
//                 price = elem.SelectedOptionCost;
//             }
//         });

//     } else {

//         Options.forEach(function (elem, index) {
//             if (ActualValue == elem.OptionKey) {
//                 price = elem.OptionCost;
//             }
//         });
//     }

//     var name = $(changedFieldId).attr("name");
//     $("#" + name + "-selected").empty().text(ElementValue);
//     $("#" + name + "-price").empty().text("£" + price);
//     $("#" + name + "-section").removeClass("table_row_hide");
//     $("#" + name + "-section").addClass("table_row_show");

//  alert('above')
//     if (doorLeafFinish == "Painted" || doorLeafFinish == 'Paint_Finish') {
//         alert("ajax call");
//         $('.SheenLevel').css({ 'display': 'none' });
//         $("#doorLeafFinishColor").addClass("bg-white");
//         $.ajax({
//             url: $("#ral-color-filter").html(),
//             method: "POST",
//             dataType: "Json",
//             data: { doorLeafFinish: doorLeafFinish, _token: $("#_token").val() },
//             success: function (result) {
//                 // console.log(result)
//                 if (result.status == "ok") {

//                     var innerHtml = '';
//                     var innerHtml1 = '';
//                     var data = result.data;
//                     var length = result.data.length;
//                     // innerHtml+='<option value="">Select Door leaf color value</option>';
//                     innerHtml += '<div class="container"><div class="row">';
//                     $("#ralColorModalLabel").text("Door Leaf Finish Color");
//                     $("#doorLeafFinishColor").attr({ 'disabled': false });
//                     for (var index = 0; index < length; index++) {
//                         innerHtml += '<div class="col-md-2 col-sm-4 col-6" onClick="SelectRalColor(\'\',' + data[index].id + ',\'' + data[index].Hex + '\',\'' + data[index].ColorName + '\',\'' + doorLeafFinish + '\')">';
//                         innerHtml += '<div class="color_box">';
//                         innerHtml += '<div class="color_place" style="background:' + data[index].Hex + '"></div>';
//                         innerHtml += ' <h4>' + data[index].ColorName + '</h4>';
//                         innerHtml += '</div></div>';
//                         // innerHtml+='<option value="'+data[index].Hex+'" style="background:'+data[index].Hex+'">'+data[index].ColorName+'</option>'
//                     }
//                     innerHtml += '</div></div>';
//                     $("#printedColor").empty().append(innerHtml);
//                     // $("#doorLeafFinishColor").empty().append(innerHtml);
//                     // $("#ralColor").modal('show');
//                     $("#doorLeafFinishColorIcon").attr("onclick", "$('#ralColor').modal('show')");
//                 } else {
//                     $("#doorLeafFinishColor").empty().append('<option value="">No Door leaf Ral Color Found</option>');
//                 }
//             }
//         });
//     } else {
//         $("#doorLeafFinishColor").removeClass("bg-white");
//         $("#doorLeafFinishColor").val('').attr({ 'disabled': true });
//         $("#doorLeafFinishColorIcon").attr("onclick", "");
//         $('.SheenLevel').css({ 'display': 'none' });
//         if (doorLeafFinish == "Laqure_Finish") {
//             $('.SheenLevel').css({ 'display': 'block' });
//         }
//     }
// }
function IronMongery(ironCategoryType, ironCategoryName) {
    var data = $("#ironIronmongerydata").val();
    var currency = $("#currency").val();

    if (data != '') {
        data = JSON.parse(data);
        var lenght = data.length;
        innerHtml = '';
        for (var index = 0; index < lenght; index++) {
            if (data[index].Category == ironCategoryType) {
                var image = $("#url").html() + '/uploads/IronmongeryInfo/' + data[index].Image;
                innerHtml += ' <div class="col-md-4 col-sm-6 col-6">';
                innerHtml += '<div class="product_holder">';
                innerHtml += '<div class="product_img"><img src="' + image + '"></div>';
                innerHtml += '<a class="product_name" href="#"><span>' + data[index].Code + '-</span> ' + data[index].Name + '</a>';
                innerHtml += '<div class="product_face">';
                innerHtml += '<b>' + data[index].FireRating + '</b>';
                innerHtml += '<b>' + currency + data[index].Price + '</b>';
                innerHtml += '<b>' + data[index].Category + '</b>';
                innerHtml += '</div>';
                innerHtml += '<a href="javascript:void(0);" onClick="makeOption(' + data[index].id + ',\'' + data[index].Name + '\',\'' + data[index].Code + '\',\'' + ironCategoryType + '\')" class="product_edit">Select</a>';
                innerHtml += '</div></div>';
            }
        }
        if (innerHtml == '') {
            innerHtml += '<div class=" col-md-12 alert alert-danger" role="alert"> No ' + ironCategoryName.toLowerCase() + ' found </div>';
        }
    } else {
        innerHtml = '';
        innerHtml += '<div class=" col-md-12 alert alert-danger" role="alert"> No ' + ironCategoryName.toLowerCase() + ' found </div>';
    }
    $("#content").empty().append(innerHtml);
    $("#modalTitle").empty().append('Select ' + ironCategoryName);
    $("#iron").modal('show');
}
function makeOption(id, name, code, category) {
    $("#" + category + 'Value').val(id);
    $("#" + category + 'Key').val(code + '-' + name);
    $("#iron").modal('hide');
}
function SelectRalColor(typeinput, id, code, name, fieldname) {

    var innerHtml = '';
    innerHtml += '<option value="' + code + '" style="background:' + code + '">' + name + '</option>'
    if (fieldname == "Factory Industrial Primed" || fieldname == "Paint Sanded" || fieldname == "Primed 2 Go") {
        // $("#doorLeafFinishColor").empty().append(innerHtml);
        $("#doorLeafFinishColorIcon").show();
        // $("#doorLeafFinishColor").val(code);
        $("#doorLeafFinishColor").val(name);
        var Colors = JSON.parse(ColorsJson);
        var price = 0.00;
        Colors.forEach(function (elem, index) {
            if (id == elem.id) {
                price = elem.ColorCost;
                // console.log("Color id is = ",index);
            }
        });
        $("#doorLeafFinishColor-selected").empty().text(name);
        $("#doorLeafFinishColor-price").empty().text("£" + price);
        $("#doorLeafFinishColor-section").removeClass("table_row_hide");
        $("#doorLeafFinishColor-section").addClass("table_row_show");
    } else if (fieldname == "Laminate" || fieldname == "PVC") {
        // $("#doorLeafFinishColor").empty().append(innerHtml);
        $("#doorLeafFinishIcon").show();
        $("#doorLeafFinish").val(name);
        var Colors = JSON.parse(ColorsJson);
        var price = 0.00;
        Colors.forEach(function (elem, index) {
            if (id == elem.id) {
                price = elem.ColorCost;
                // console.log("Color id is = ",index);
            }
        });
        $("#doorLeafFinishColor-selected").empty().text(name);
        $("#doorLeafFinishColor-price").empty().text("£" + price);
        $("#doorLeafFinishColor-section").removeClass("table_row_hide");
        $("#doorLeafFinishColor-section").addClass("table_row_show");
    } else if (fieldname == "Painted_Finish") {
        if (typeinput == 'architraveFinish') {
            // $("#architraveFinishcolor").empty().append('<option value="'+id+'">'+name+'</option>');
            $("#architraveFinishcolor").val(name)
            $("input[name='architraveFinishcolor']").val(name)
        } else if (typeinput == 'framefinish') {
            $("#framefinishColor").empty().append('<option value="' + id + '">' + name + '</option>');
        }

        var Colors = JSON.parse(ColorsJson);
        var price = 0.00;
        Colors.forEach(function (elem, index) {
            if (id == elem.id) {
                price = elem.ColorCost;
                // console.log("Color id is = ",index);
            }
        });
        $("#doorLeafFinishColor-selected").empty().text(name);
        $("#doorLeafFinishColor-price").empty().text("£" + price);
        $("#doorLeafFinishColor-section").removeClass("table_row_hide");
        $("#doorLeafFinishColor-section").addClass("table_row_show");
    } else {
        // $("#framefinishColor").empty().append(innerHtml);
        $("#doorLeafFinishIcon").hide();
        $("#doorLeafFinish").hide();
    }
    // $("#"+category+'Value').val(id);
    // $("#"+category+'Key').val(code+'-'+name);
    $("#ralColor").modal('hide');
}
function SelectValueFill(id, key, value, modalId, data = []) {


    let inputIdentity = $('.inputIdentity').val();
    if (inputIdentity == 'ArchitraveMaterial') {
        $('#architraveMaterial').val(value);
        $('input[name="architraveMaterial"]').val(key);
        $('#UniversalModal').modal('hide');
    } else {
        if (id == "#frameMaterial") {
            $(id).val(value);
            $("#frameMaterialNew").val(key);
        } else {
            $(id).val(key);
        }
        $(modalId).modal('hide');
        var price = 0;
        $(id + "-selected").empty().text(value);
        $(id + "-price").empty().text("£" + price);
        $(id + "-section").removeClass("table_row_hide");
        $(id + "-section").addClass("table_row_show");
        if (value == 'MDF') {
            // $('#frameMaterialNew').empty().val(value);
            IntumescentSeals();
        }
        if ($("#frameType").val() == 'Rebated_Frame') {
            FramePrice('Rebated_Frame');
        } else if ($("#frameType").val() == 'Plant_on_Stop') {
            FramePrice('Plant_on_Stop');
        }

        if ($("#extLiner").val() == 'Yes') {
            FramePrice('extLiner');
        }
        if ($("#sideLight1").val() == 'Yes') {
            FramePrice('sideLight3');
        }
        if ($("#overpanel").val() == 'Fan_Light') {
            FramePrice('overpanel3');
        }
    }
}

function OpenglazingModal(labelname, inputId) {
    $('#glazingModalLabel').html(labelname);
    $('#inputId').val(inputId);
    $('#glazingModal').modal('show');
}
function OpenLipingModal(labelname,inputId){
    $('#LipingModalLabel').html(labelname);
    $('#inputId').val(inputId);
    $('#LipingModal').modal('show');
}
function OpenArchitraveMaterialModal(labelname, inputId) {
    $('#glazingModalLabel').html(labelname);
    $('#inputId').val(inputId);
    $('#glazingModal').modal('show');
}
// function GlazingValueFill(id,value,modalId, cost ="0.00"){
//     // const inputId = $('#inputId').val();
//     // $('input[id='+inputId+']').val(value);
//     // $('input[name='+inputId+']').val(id);
//     $(modalId).modal('hide');
//     // let inputIdentity = $('.inputIdentity').val();
//     // if (inputIdentity == 'ArchitraveMaterial') {
//     //     $('#architraveMaterial').val(value);
//     //     $('input[name="architraveMaterial"]').val(id);
//     //     $('#UniversalModal').modal('hide');
//     // }

//     // alert($('#lippingThickness').val());

//     let lippingThickness =  $('#lippingThickness').val();
//     // let lippingThickness =  1;

//     var SelectedLippingSpecies = JSON.parse(SelectedLippingSpeciesJson);
//     // var price = 0;


//     SelectedLippingSpecies.forEach(function(elem, index) {
//         if(id == elem.id){
//             // alert(elem.id);

//             // console.log(SelectedLippingSpecies);

//             elem.lipping_species_items.forEach(function(elem1) {

//                 elem1.selected_lipping_species_items.forEach(function(elem2) {
//                     if(lippingThickness == elem2.selected_thickness){
//                         // alert(elem2.selected_price);
//                         cost = elem2.selected_price;
//                     }

//                 });
//             });

//         }
//     });

//     // SetBuildOfMaterial($("#"+ inputId), cost);
//     // alert(inputId);


//     // $("#"+ inputId +"-selected").empty().text(value);
//     // $("#"+ inputId +"-price").empty().text("£" + cost);
//     // $("#"+ inputId +"-section").removeClass("table_row_hide");
//     // $("#"+ inputId +"-section").addClass("table_row_show");
//     doorLeafFacingPrice('LeafSet');
//     if($("#frameType").val() == 'Rebated_Frame'){
//         FramePrice('Rebated_Frame');
//     }else if($("#frameType").val() == 'Plant_on_Stop'){
//         FramePrice('Plant_on_Stop');
//     }

//     if($("#extLiner").val() == 'Yes'){
//         FramePrice('extLiner');
//     }
//     if($("#sideLight1").val() == 'Yes'){
//         FramePrice('sideLight3');
//     }
//     if($("#overpanel").val() == 'Fan_Light'){
//         FramePrice('overpanel3');
//     }

//     doorLeafFacingPrice('glazingBead');
//     $("#glazingBead-selected1").empty().text($("#glazingBeads").val());
//     if($("#sideLight2").val()=="Yes"){
//         doorLeafFacingPrice('sideLight12','Yes');
//     }
//     if($("#sideLight1").val()=="Yes"){
//         doorLeafFacingPrice('sideLight2','Yes');
//     }
//     if($("#overpanel").val()=="Fan_Light"){
//         doorLeafFacingPrice('overpanel2',"Fan_Light");
//     }
// }

function GlazingValueFill(id,value,modalId, cost ="0.00"){
        let inputIdentity = $('.inputIdentity').val();
    if (inputIdentity == 'ArchitraveMaterial') {
        $('#architraveMaterial').val(value);
        $('input[name="architraveMaterial"]').val(id);
        $('#UniversalModal').modal('hide');
        $('#inputId').val('architraveMaterial');
    }

    const inputId = $('#inputId').val();
    $('input[id='+inputId+']').val(value);
    $('input[name='+inputId+']').val(id);
    $(modalId).modal('hide');


    // alert($('#lippingThickness').val());

    let lippingThickness =  $('#lippingThickness').val();
    // let lippingThickness =  1;

    var SelectedLippingSpecies = JSON.parse(SelectedLippingSpeciesJson);
    // var price = 0;


    SelectedLippingSpecies.forEach(function(elem, index) {
        if(id == elem.id){
            // alert(elem.id);

            // console.log(SelectedLippingSpecies);

            elem.lipping_species_items.forEach(function(elem1) {

                elem1.selected_lipping_species_items.forEach(function(elem2) {
                    if(lippingThickness == elem2.selected_thickness){
                        // alert(elem2.selected_price);
                        cost = elem2.selected_price;
                    }

                });
            });

        }
    });

    // SetBuildOfMaterial($("#"+ inputId), cost);
    // alert(inputId);


    $("#"+ inputId +"-selected").empty().text(value);
    $("#"+ inputId +"-price").empty().text("£" + cost);
    $("#"+ inputId +"-section").removeClass("table_row_hide");
    $("#"+ inputId +"-section").addClass("table_row_show");
    doorLeafFacingPrice('LeafSet');
    if($("#frameType").val() == 'Rebated_Frame'){
        FramePrice('Rebated_Frame');
    }else if($("#frameType").val() == 'Plant_on_Stop'){
        FramePrice('Plant_on_Stop');
    }

    if($("#extLiner").val() == 'Yes'){
        FramePrice('extLiner');
    }
    if($("#sideLight1").val() == 'Yes'){
        FramePrice('sideLight3');
    }
    if($("#overpanel").val() == 'Fan_Light'){
        FramePrice('overpanel3');
    }

    doorLeafFacingPrice('glazingBead');
    $("#glazingBead-selected1").empty().text($("#glazingBeads").val());
    if($("#sideLight2").val()=="Yes"){
        doorLeafFacingPrice('sideLight12','Yes');
    }
    if($("#sideLight1").val()=="Yes"){
        doorLeafFacingPrice('sideLight2','Yes');
    }
    if($("#overpanel").val()=="Fan_Light"){
        doorLeafFacingPrice('overpanel2',"Fan_Light");
    }
}

function DoorFrameConstruction(id, OptionKey, OptionValue) {
    $('input[id="frameCostuction"]').val(OptionValue);
    $('input[name="frameCostuction"]').val(OptionKey);
    $('#DoorFrameConstructionModal').modal('hide');
    var price = 0;
    $(id + "-selected").empty().text(OptionValue);
    $(id + "-price").empty().text("£" + price);
    $(id + "-section").removeClass("table_row_hide");
    $(id + "-section").addClass("table_row_show");
}




function LeafCoreCalculation() {
    const doorsetTypeValue_B = $("#doorsetType").val();
    const tolleranceValue_B = $('#tollerance').val();
    const frameThicknessValue_B = $('#frameThickness').val();
    const gapValue_B = $('#gap').val();

    if (doorsetTypeValue_B == 'SD') {
        let leafWidth1 = soWidth - (tolleranceValue_B * TolleranceAdditionalNumber) - (frameThicknessValue_B * FrameThicknessAdditionalNumber) - (gapValue_B * GapAdditionalNumber);
        // $('#leafWidth1').val(leafWidth1).attr('readonly',true);
        // $('#leafWidth2').val('').attr('readonly',true);
    } else if (doorsetTypeValue_B == 'DD') {
        let leafWidth1 = (soWidth - (tolleranceValue_B * TolleranceAdditionalNumber) - (frameThicknessValue_B * FrameThicknessAdditionalNumber) - (gapValue_B * GapAdditionalNumber)) / 2;
        // $('#leafWidth1').val(leafWidth1).attr('readonly',true);
        let leafWidth2 = leafWidth1;
        // $('#leafWidth2').val(leafWidth2).attr('readonly',true);
    } else if (doorsetTypeValue_B == 'leaf_and_a_half') {
        $('#leafWidth1').attr('readonly', false);
        let leafWidth1 = $('#leafWidth1').val();
        let leafWidth2 = soWidth - (tolleranceValue_B * TolleranceAdditionalNumber) - (gapValue_B * GapAdditionalNumber) - (frameThicknessValue_B * FrameThicknessAdditionalNumber) - parseInt(leafWidth1);
        // $('#leafWidth2').val(leafWidth2).attr('readonly',true);
    }
}

// Architrave Material needs to work exactly the same as ‘Frame Material’ so the exact same options needs to be here.
function ArchitraveMaterial() {
    $('#UniversalModalLabel').html('Architrave Material');
    $('.inputIdentity').val('ArchitraveMaterial');

    architrave();

}
function FrameMaterial() {
    $('.inputIdentity').val('FrameMaterial');
    $('#frameMaterialModal').modal('show');
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Error Code
function selectIronMongery() {
    $.ajax({
        url: $("#filter-iron-mongery-category").html(),
        method: "POST",
        // data:{ironCategoryType:ironCategoryType,_token:$("#_token").val()},
        data: { _token: $("#_token").val() },
        dataType: "Json",
        success: function (result) {
            if (result.status == "ok") {
                $("#ironIronmongerydata").val(JSON.stringify(result.data));

            } else {
                $("#ironIronmongerydata").html('');
            }
        }
    });
}




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Not relate to any one
$("#overpanel1").change(function () {
    var tollerance = 0;
    var gap = 0;
    var framethikness = 0;
    var soWidth = 0;
    var soheight = 0;
    var undercut = 0;
    randomkey = 2;
    if ($("#overpanel").val() == "No") {
        var thisvalue = document.getElementsByClassName("foroPWidth");
        for (var i = 0; i < thisvalue.length; i++) {
            if (thisvalue[i].name == 'tollerance') {
                if (thisvalue[i].value == '') {
                    tollerance = 0;
                } else {
                    tollerance = parseInt(thisvalue[i].value);
                }
            }
            if (thisvalue[i].name == 'gap') {
                if (thisvalue[i].value == '') {
                    gap = 0;
                }
                else {
                    gap = parseInt(thisvalue[i].value);
                }
            }

            // if(thisvalue[i].name=='frameThickness'){
            //     if(thisvalue[i].value==''){
            //         framethikness = 0;
            //     }
            //     else{
            //         framethikness = parseInt(thisvalue[i].value);
            //     }
            // }

            if (thisvalue[i].name == 'sOWidth') {
                if (thisvalue[i].value == '') {
                    soWidth = 0;
                } else {
                    soWidth = parseInt(thisvalue[i].value);
                }
            }
            if (thisvalue[i].name == 'sOHeight') {
                if (thisvalue[i].value == '') {
                    soheight = 0;
                } else {
                    soheight = parseInt(thisvalue[i].value);
                }
            }
            if (thisvalue[i].name == 'undercut') {
                if (thisvalue[i].value == '') {
                    undercut = 0;
                } else {
                    undercut = parseInt(thisvalue[i].value);
                }
            }
        }
        var leafHeightNoOP = soheight - tollerance - framethikness - undercut - gap;
        // $("#leafHeightNoOP").val(leafHeightNoOP).attr('readonly',true);
        $("#SL1Height").val(leafHeightNoOP).attr('readonly', true);
        var plantonStopHeight = soheight - tollerance;
        //$("#plantonStopHeight").val(plantonStopHeight);
        $("#frameHeight").val(plantonStopHeight);
        var frameDepth = $("#sODepth").val() != '' ? $("#sODepth").val() : 0;
        // $("#frameDepth").val(frameDepth);
        $("#leafHeightwithOP").val(0).attr('readonly', true);
        $("#oPWidth").val(0).attr('readonly', true);

        $("#OpBeadThickness").val(0).attr('readonly', true);
        $("#OpBeadHeight").val(0).attr('readonly', true);
        $("#OpBeadThickness").val(0).attr('required', false);
        $("#OpBeadHeight").val(0).attr('required', false);

        $("#OPLippingThickness").val('').attr('disabled', 'disabled');
        $("#transomThickness").val('').attr('disabled', 'disabled');
        $("#opTransom").val('').attr('disabled', 'disabled');
    } else {

        if ($("#overpanel").val() == "Fan_Light") {
            $("#OpBeadThickness").val(0).attr('readonly', false);
            $("#OpBeadHeight").val(0).attr('readonly', false);
            $("#OpBeadThickness").val(0).attr('required', true);
            $("#OpBeadHeight").val(0).attr('required', true);
        } else {
            $("#OpBeadThickness").val(0).attr('readonly', true);
            $("#OpBeadHeight").val(0).attr('readonly', true);
            $("#OpBeadThickness").val(0).attr('required', false);
            $("#OpBeadHeight").val(0).attr('required', false);
        }

        //$("#leafHeightNoOP").val(0).attr('readonly',false);
        $("#leafHeightNoOP").attr('readonly', false);
        $("#SL1Height").attr('readonly', false);
        $("#leafHeightwithOP").attr('readonly', false);
        $("#oPWidth").attr('readonly', true);
        $("#oPHeigth").attr('readonly', false);
        $("#OPLippingThickness").val('').attr('disabled', false);
        $("#transomThickness").val('').attr('disabled', false);
        $("#opTransom").val('').attr('disabled', false);
    }
});
$("#visionPanelQuantityforLeaf21").change(function () {
    var eqllSizeLeaf = $("#AreVPsEqualSizesForLeaf2").val();
    var vp2Quantity = $(this).val();
    $("#vP2Width").attr({ 'readonly': false, 'required': true });
    $("#vP2Height1").attr({ 'readonly': false, 'required': true });
    $("#distanceFromTopOfDoorforLeaf2").attr({ 'readonly': false, 'required': true });
    $("#distanceFromTheEdgeOfDoorforLeaf2").attr({ 'readonly': false, 'required': true });
    //$("#AreVPsEqualSizesForLeaf2").attr({'readonly':false,'required':true});
    $("#AreVPsEqualSizesForLeaf2").attr({ 'readonly': false });
    if (vp2Quantity > 1) {
        $("#distanceBetweenVPsforLeaf2").attr({ 'readonly': false, 'required': true }).val('');
        //$("#AreVPsEqualSizesForLeaf2").attr({'readonly':false,'required':true});
        $("#AreVPsEqualSizesForLeaf2").attr({ 'readonly': false });
        if (eqllSizeLeaf == "No") {
            for (var index = 2; index <= parseInt(vp2Quantity); index++) {
                $("#vP2Height" + index).attr({ 'readonly': false, 'required': true }).val('');
            }
            for (var i = parseInt(vp2Quantity); index <= 5; index++) {
                $("#vP2Height" + index).attr({ 'readonly': true, 'required': false }).val('');
            }
        } else {
            for (var index = 2; index <= 5; index++) {
                $("#vP2Height" + index).attr({ 'readonly': true, 'required': false }).val('');
            }
        }
    } else {
        //$("#AreVPsEqualSizesForLeaf2").attr({'readonly':true,'required':false}).val('');
        $("#AreVPsEqualSizesForLeaf2").attr({ 'readonly': true }).val('');
        $("#distanceBetweenVPsforLeaf2").attr({ 'readonly': 'disabled', 'required': false }).val('');
        for (var index = 2; index <= 5; index++) {
            $("#vP2Height" + index).attr({ 'readonly': true, 'required': false }).val('');
        }
    }

    // if(eqllSizeLeaf=="Yes"){

    // if(vp2Quantity>1){
    //     $("#distanceBetweenVPsforLeaf2").attr({'readonly':false,'required':true}).val('');
    //     for(var index=2;index<=5;index++){
    //         $("#vP2Height"+index).attr({'readonly':true,'required':false}).val('');
    //     }
    // }else{
    //     $("#distanceBetweenVPsforLeaf2").attr({'readonly':'disabled','required':false}).val('');
    //     for(var index=2;index<=5;index++){
    //         $("#vP2Height"+index).attr({'readonly':true,'required':false}).val('');
    //     }
    // }

    // }else{


    // if(vp2Quantity>1){
    //     $("#distanceBetweenVPsforLeaf2").attr({'readonly':false,'required':true}).val('');

    //         for(var index=2;index<=vp2Quantity;index++){
    //         $("#vP2Height"+index).attr({'readonly':false,'required':true}).val('');
    //     }

    // }else{
    //     $("#distanceBetweenVPsforLeaf2").attr({'readonly':'disabled','required':false}).val('');


    //     for(var index=2;index<=vp2Quantity;index++){
    //     $("#vP2Height"+index).attr({'readonly':true,'required':false}).val('');
    // }
    // }


    // }
});
$(".for_Leaf_Width_1").change(function () {
    var tollerance = 0;
    var soWidth = 0;
    var framethikness = 0;
    var gap = 0;
    var leaf_width_1 = 0;
    var randomkey = 2;
    var thisvalue = document.getElementsByClassName("for_Leaf_Width_1");
    for (var i = 0; i < thisvalue.length; i++) {
        if (thisvalue[i].name == 'tollerance') {
            if (thisvalue[i].value == '') {
                tollerance = 0;
            }
            else {
                tollerance = parseInt(thisvalue[i].value);
            }
        }

        else if (thisvalue[i].name == 'sOWidth') {
            if (thisvalue[i].value == '') {
                soWidth = 0;
            }
            else {
                soWidthsoWidth = parseInt(thisvalue[i].value);
            }
        }

        else if (thisvalue[i].name == 'gap') {
            if (thisvalue[i].value == '') {
                gap = 0;
            }
            else {
                gap = parseInt(thisvalue[i].value);
            }
        }

        else if (thisvalue[i].name == 'frameThickness') {
            if (thisvalue[i].value == '') {
                framethikness = 0;
            }
            else {
                framethikness = parseInt(thisvalue[i].value);
            }
        }
    }

    DoorSetTypeChange();

    if ($("#doorsetType").val() == "DD") {

        var leafWidth1 = (soWidth - (tollerance * TolleranceAdditionalNumber) - (framethikness * FrameThicknessAdditionalNumber) - (GapAdditionalNumber * gap)) / 2;
        // $("#leafWidth2").val(leafWidth1);
        // $("#leafWidth1").val(leafWidth1);

        // sadique code
        // Rround down the field ‘Maximum Number of Groove’
        // for example, if the value there is 20.42 it should be 20.
        // Leaf Width 1 with Groove Location = Horizontal
        var grooveLocationValue = $('#grooveLocation').val();
        if (grooveLocationValue == "Vertical" && leafWidth1 != '') {
            $("#maxNumberOfGroove").val(Math.round((parseInt(leafWidth1) / 100)));
        }
    } else if ($("#doorsetType").val() == "SD") {
        var leafWidth1 = soWidth - (tollerance * TolleranceAdditionalNumber) - (framethikness * FrameThicknessAdditionalNumber) - (GapAdditionalNumber * gap);
        // $("#leafWidth2").val(0);
        // $("#leafWidth1").val(leafWidth1);

        // sadique code
        // Rround down the field ‘Maximum Number of Groove’
        // for example, if the value there is 20.42 it should be 20.
        // Leaf Width 1 with Groove Location = Horizontal
        var grooveLocationValue = $('#grooveLocation').val();
        if (grooveLocationValue == "Vertical" && leafWidth1 != '') {
            $("#maxNumberOfGroove").val(Math.round((parseInt(leafWidth1) / 100)));
        }
    } else {
        var leafWidth1 = 0;
        $("#leafWidth1").val('').attr('readonly', false);
        if ($("#leafWidth1").val() != '' && parseInt($("#leafWidth1").val())) {
            leafWidth1 = parseInt($("#leafWidth1").val());

            // sadique code
            // Rround down the field ‘Maximum Number of Groove’
            // for example, if the value there is 20.42 it should be 20.
            // Leaf Width 1 with Groove Location = Horizontal
            var grooveLocationValue = $('#grooveLocation').val();
            if (grooveLocationValue == "Vertical" && leafWidth1 != '') {
                $("#maxNumberOfGroove").val(Math.round((parseInt(leafWidth1) / 100)));
            }
        }
        var leafWidth2 = soWidth - (tollerance * TolleranceAdditionalNumber) - (framethikness * FrameThicknessAdditionalNumber) - (GapAdditionalNumber * gap) - leafWidth1;
        // $("#leafWidth2").val(leafWidth2);

    }
})

$("#AreVPsEqualSizesForLeaf21").change(function () {
    var vp2Quantity = $("#visionPanelQuantityforLeaf2").val();
    var eqllSizeLeaf = $(this).val();
    $("#vP2Width").attr({ 'readonly': false, 'required': true });
    $("#vP2Height1").attr({ 'readonly': false, 'required': true });
    $("#distanceFromTopOfDoorforLeaf2").attr({ 'readonly': false, 'required': true });
    $("#distanceFromTheEdgeOfDoorforLeaf2").attr({ 'readonly': false, 'required': true });

    if (eqllSizeLeaf == "Yes") {

        $("#distanceBetweenVPsforLeaf2").attr({ 'readonly': true, 'required': false });
        for (var index = 2; index <= 5; index++) {
            $("#vP2Height" + index).attr({ 'readonly': true, 'required': false }).val('');
        }


    } else {

        if (vp2Quantity > 1) {
            $("#distanceBetweenVPsforLeaf2").attr({ 'readonly': false, 'required': true }).val('');

            for (var index = 2; index <= parseInt(vp2Quantity); index++) {
                $("#vP2Height" + index).attr({ 'readonly': false, 'required': true }).val('');
            }
            for (var index = parseInt(vp2Quantity) + 1; index <= 5; index++) {
                $("#vP2Height" + index).attr({ 'readonly': true, 'required': false }).val('');
            }
        }





    }


    // if($(this).val()=="Yes"){

    //     if(vp2Quantity>1){
    //         $("#distanceBetweenVPsforLeaf2").attr({'readonly':false,'required':true}).val('');
    //         for(var index=2;index<=5;index++){
    //             $("#vP2Height"+index).attr({'readonly':true,'required':false}).val('');
    //         }
    //     }else{
    //         $("#distanceBetweenVPsforLeaf2").attr({'readonly':'disabled','required':false}).val('');
    //         for(var index=2;index<=5;index++){
    //             $("#vP2Height"+index).attr({'readonly':true,'required':false}).val('');
    //         }
    //     }

    //     }else{


    //     if(vp2Quantity>1){
    //         $("#distanceBetweenVPsforLeaf2").attr({'readonly':false,'required':true}).val('');

    //             for(var index=2;index<=vp2Quantity;index++){
    //             $("#vP2Height"+index).attr({'readonly':false,'required':true}).val('');
    //         }

    //     }else{
    //         $("#distanceBetweenVPsforLeaf2").attr({'readonly':'disabled','required':false}).val('');
    //     }


    //     }




})





// $("#distanceBetweenVPs").change(function(){

// if($(this).val()=="Yes"){
// for(var i=2;i<=5;i++){
// $("#vP1Height"+i).attr('readonly',true);
// $("#vP1Height"+i).attr('required',false);
// }
// }else{




// }


// });























// url: "{{route('items/fire-rating-filter')}}",






// url: "{{route('items/glazing-beads-filter')}}",
































// </script>














// Over Panel Code
// Start






// End

// function cloneDoorType(QuotationId){
//     $.ajax({
//         url:  $("#getUpdatedDoors").html(),
//         method:"POST",
//         data:{quotationId:quotationId,_token:$("#_token").val()},
//         dataType:"Json",
//         success: function(result){
//             if(result.status=="ok"){


//             }else{

//             }

//         }
//         });





// }





// $("#maxNumberOfGroove").change(function(){
//     var maxNumberOfGroove =  $(this).val();
//     $("#numberOfGroove").attr('max', maxNumberOfGroove);

//     $("label[for='numberOfGroove']").text("Number of Grooves (Max "+maxNumberOfGroove+")");

// });


function BomCalculation(QuotationId) {
    let url = $("#bom_calculation").val();
    $.ajax({
        type: "POST",
        url: url,
        data: { QuotationId: QuotationId },
        success: function (data) {
            // console.log(data);
        }
    })
}

function IronmongeryIDItemsPrice() {
    $("#ironmongerySet-section2").addClass("table_row_show");
    $("#ironmongerySet-section2").removeClass("table_row_hide");
    const IronmongeryIDValue = $("#IronmongeryID").val();
    var ironname = $("#IronmongeryID").find('option:selected').text();
    $(".IronmongeryID-selected").empty().text(ironname);
    $.ajax({
        url: $("#IronmongeryIDPrice").html(),
        method: "POST",
        data: { _token: $("#_token").val(), IronmongeryIDValue: IronmongeryIDValue, optionType: 'IronmongeryIDItems' },
        dataType: "Json",
        success: function (result) {
            if (result.status == "ok") {
                $("#ironmongerySet1-price").empty().text("£" + result.IronmongeryInfo);
            }
        }
    });
}

$("#sOWidth,#sOHeight").on("keyup", function () {
    doorSize();
});

function doorSize() {
    $("#doorsize-section").addClass("table_row_show");
    $("#doorsize-section").removeClass("table_row_hide");
    var LeafWidth1 = $("#leafWidth1").val();
    var LeafHeightNoOP = $("#leafHeightNoOP").val();
    var fireRating = $("#fireRating").val();
    var issingleconfiguration = $("input[name=issingleconfiguration]").val();

    $.ajax({
        url: $("#doorStandardPrice").html(),
        method: "POST",
        data: { _token: $("#_token").val(), LeafWidth1: LeafWidth1, LeafHeightNoOP: LeafHeightNoOP, fireRating: fireRating, issingleconfiguration: issingleconfiguration },
        dataType: "Json",
        success: function (result) {
            if (result.status == "ok") {
                $("#doorsize-price").empty().text("£" + result.door_core);
                $("#doorsize-LeafWidth1").empty().text(result.selected_mm_width + ' x ' + result.selected_mm_height);
                // $("#doorsize-LeafHeight").empty().text(result.selected_mm_height);
            }
        }
    });
}
function IronmongeryIDPrice() {
    $("#ironmongerySet-section1").addClass("table_row_show");
    $("#ironmongerySet-section1").removeClass("table_row_hide");
    const IronmongeryIDValue = $("#IronmongeryID").val();
    var ironname = $("#IronmongeryID").find('option:selected').text();
    $(".IronmongeryID-selected").empty().text(ironname);
    $.ajax({
        url: $("#IronmongeryIDPrice").html(),
        method: "POST",
        data: { _token: $("#_token").val(), IronmongeryIDValue: IronmongeryIDValue, optionType: 'IronmongeryIDPrice' },
        dataType: "Json",
        success: function (result) {
            if (result.status == "ok") {
                $("#ironmongerySet-price").empty().text("£" + result.IronmongeryInfo);
            }
        }
    });
}

function DoorSetPrice() {
    $("#doorsetType-section1").addClass("table_row_show");
    $("#doorsetType-section1").removeClass("table_row_hide");
    $("#doorsetType-section2").addClass("table_row_show");
    $("#doorsetType-section2").removeClass("table_row_hide");
    var doorset = $("#doorsetType").find('option:selected').text();
    $('.doorsetType-selected').empty().text(doorset);
    const doorsetType = $("#doorsetType").val();
    $.ajax({
        url: $("#generalLabourCost").html(),
        method: "POST",
        data: { _token: $("#_token").val(), doorsetType: doorsetType, option: 'doorsetType' },
        dataType: "Json",
        success: function (result) {
            if (result.status == "ok") {
                $("#doorsetType-price").empty().text("£" + result.price['0']);
                $("#doorsetType-description").empty().text(result.description['0']);
                $("#doorsetType-price1").empty().text("£" + result.price['1']);
                $("#doorsetType-description1").empty().text(result.description['1']);
            }
        }
    });
}

$("#doorLeafFacing, #doorLeafFinish, #fireRating, #doorLeafFacingValue").on("change", function () {
    doorLeafFacingPrice('LeafSet');
});

$("#lippingThickness, #leafWidth1,#leafHeightNoOP, #doorThickness").on("keyup", function () {
    doorLeafFacingPrice('LeafSet');
});

$("#intumescentSealArrangement, #intumescentSealType, #intumescentSealLocation, #intumescentSealColor,#doorsetType").on("change", function () {
    doorLeafFacingPrice('intumescentSealArrangement');
});

$("#glazingSystems, #visionPanelQuantity").on("change", function () {
    doorLeafFacingPrice('glazingSystems');
});

$("#glassType, #visionPanelQuantity, #sideLight1, #sideLight2").on("change", function () {
    doorLeafFacingPrice('glassType');
});

$("#glazingBeads, #visionPanelQuantity").on("change", function () {
    doorLeafFacingPrice('glazingBead');
    $("#glazingBead-selected1").empty().text($("#glazingBeads").val());
});

$("#vP1Width, #vP1Height1,#vP1Height2, #vP1Height3,#vP1Height4, #vP1Height5,#glazingBeadsThickness,#glazingBeadsHeight").on("keyup", function () {
    doorLeafFacingPrice('glazingBead');
    $("#glazingBead-selected1").empty().text($("#glazingBeads").val());
    doorLeafFacingPrice('glazingSystems');
});

$("#SlBeadThickness, #SlBeadHeight,#SL2Width, #SL2Height").on("keyup", function () {
    if ($("#sideLight2").val() == "Yes") {
        doorLeafFacingPrice('sideLight12', Yes);
    }
});

$("#SlBeadThickness, #SlBeadHeight,#SL1Width, #SL1Height").on("keyup", function () {
    if ($("#sideLight1").val() == "Yes") {
        doorLeafFacingPrice('sideLight2', Yes);
    }
});

$("#oPHeigth, #oPWidth,#OpBeadThickness, #OpBeadHeight").on("keyup", function () {
    if ($("#overpanel").val() == "Fan_Light") {
        doorLeafFacingPrice('overpanel2', "Fan_Light");
    }
});

function labourPrice() {
    var doorsetType = localStorage.getItem('doorsetType');
    $.ajax({
        url: $("#generalLabourCost").html(),
        method: "POST",
        data: { _token: $("#_token").val(), option: 'common', doorsetType: doorsetType },
        dataType: "Json",
        success: function (result) {
            if (result.status == "ok") {
                for (var i = 0; i <= 7; i++) {
                    $("#labour-price" + i).empty().text("£" + result.price[i]);
                    $("#labour-description" + i).empty().text(result.description[i]);
                }
            }
        }
    });
}

function doorLeafFacingPrice(option, value = "") {

}
// function doorLeafFacingPrice(option, value = "") {
//     var doorsetType = localStorage.getItem('doorsetType');
//     $("#" + option + "-section1").addClass("table_row_show");
//     $("#" + option + "-section1").removeClass("table_row_hide");
//     var doorset = $("#" + option).find('option:selected').text();

//     $('#' + option + '-selected1').empty().text(doorset);
//     if (value == "") {
//         var optionname = $("#" + option).val();
//     } else {
//         var optionname = value;
//         $('#' + option + '-selected1').empty().text(value);
//     }
//     if (option == 'LeafSet') {
//         if (value == '') {
//             value = $('.doorLeafFinishSelect').val();
//         } else {
//             value = value;
//         }
//         $('#LeafSet-selected1').empty().text(value);
//         var doorLeafFacing = $("#doorLeafFacing").val();
//         var doorLeafFinish = value;
//         var lippingSpecies = $("#lippingSpeciesid").val();
//         var issingleconfiguration = $("input[name=issingleconfiguration]").val();
//         var lippingThickness = $("#lippingThickness").val();
//         var fireRating = $("#fireRating").val();
//         var leafWidth1 = $("#leafWidth1").val();
//         var leafHeightNoOP = $("#leafHeightNoOP").val();
//         var doorThickness = $("#doorThickness").val();
//         var doorLeafFacingValue = $("#doorLeafFacingValue").val();
//         $('#LeafSet-DoorLeafFacing').empty().text(doorLeafFacing);
//         if (doorLeafFacing == 'Laminate') {
//             var doorLeafFinish = 'Painted';
//         } else if (doorLeafFacing == 'PVC') {
//             var doorLeafFinish = 'Painted';
//         }
//         // setTimeout(function(){
//         $.ajax({
//             url: $("#generalLabourCost").html(),
//             method: "POST",
//             data: { _token: $("#_token").val(), option: option, doorLeafFacing: doorLeafFacing, doorLeafFinish: doorLeafFinish, lippingSpecies: lippingSpecies, issingleconfiguration: issingleconfiguration, lippingThickness: lippingThickness, leafWidth1: leafWidth1, leafHeightNoOP: leafHeightNoOP, doorThickness: doorThickness, doorLeafFacingValue: doorLeafFacingValue, fireRating: fireRating },
//             dataType: "Json",
//             success: function (result) {
//                 if (result.status == "ok") {
//                     $("#" + option + "-price1").empty().text("£" + result.price);
//                 }
//             }
//         });
//         //   },10000);

//     } else if (option == 'intumescentSealArrangement') {
//         if (value == '') {
//             value = $('#intumescentSealArrangement').val();
//         } else {
//             value = value;
//         }
//         var intumescentSealArrangement = value;
//         var intumescentSealType = $('#intumescentSealType').val();
//         var intumescentSealLocation = $('#intumescentSealLocation').val();
//         var intumescentSealColor = $('#intumescentSealColor').val();
//         var doorsetType = $('#doorsetType').val();
//         var intumescentSealMeetingEdges = $('#intumescentSealMeetingEdges').val();
//         $.ajax({
//             url: $("#generalLabourCost").html(),
//             method: "POST",
//             data: { _token: $("#_token").val(), option: option, intumescentSealArrangement: intumescentSealArrangement, intumescentSealType: intumescentSealType, intumescentSealLocation: intumescentSealLocation, intumescentSealColor: intumescentSealColor, doorsetType: doorsetType, intumescentSealMeetingEdges: intumescentSealMeetingEdges },
//             dataType: "Json",
//             success: function (result) {
//                 if (result.status == "ok") {
//                     $("#" + option + "-price1").empty().text("£" + result.price);
//                 }
//             }
//         });
//     } else if (option == 'glazingSystems') {
//         if (value == '') {
//             value = $('#glazingSystems').val();
//         } else {
//             value = value;
//         }
//         var issingleconfiguration = $("input[name=issingleconfiguration]").val();
//         // var intumescentSealArrangement =value;
//         var glazingSystems = value;
//         var visionPanelQuantity = $('#visionPanelQuantity').val();
//         var vP1Width = $('#vP1Width').val();
//         var vP1Height1 = $('#vP1Height1').val();
//         var vP1Height2 = $('#vP1Height2').val();
//         var vP1Height3 = $('#vP1Height3').val();
//         var vP1Height4 = $('#vP1Height4').val();
//         var vP1Height5 = $('#vP1Height5').val();

//         $.ajax({
//             url: $("#generalLabourCost").html(),
//             method: "POST",
//             data: { _token: $("#_token").val(), option: option, glazingSystems: glazingSystems, visionPanelQuantity: visionPanelQuantity, vP1Width: vP1Width, vP1Height1: vP1Height1, vP1Height2: vP1Height2, vP1Height3: vP1Height3, vP1Height4: vP1Height4, vP1Height5: vP1Height5, issingleconfiguration: issingleconfiguration },
//             dataType: "Json",
//             success: function (result) {
//                 if (result.status == "ok") {
//                     $("#" + option + "-price1").empty().text("£" + result.price);
//                 }
//             }
//         });
//     } else if (option == 'glassType') {
//         if (value == '') {
//             value = $('#glassType').val();
//         } else {
//             value = value;
//         }
//         var issingleconfiguration = $("input[name=issingleconfiguration]").val();
//         // var intumescentSealArrangement =value;
//         var glassType = value;
//         var visionPanelQuantity = $('#visionPanelQuantity').val();
//         var vP1Width = $('#vP1Width').val();
//         var sideLight1 = $('#sideLight1').val();
//         var sideLight2 = $('#sideLight2').val();
//         var vP1Height3 = $('#vP1Height3').val();
//         var vP1Height4 = $('#vP1Height4').val();
//         var vP1Height5 = $('#vP1Height5').val();

//         $.ajax({
//             url: $("#generalLabourCost").html(),
//             method: "POST",
//             data: { _token: $("#_token").val(), option: option, glassType: glassType, visionPanelQuantity: visionPanelQuantity, issingleconfiguration: issingleconfiguration },
//             dataType: "Json",
//             success: function (result) {
//                 if (result.status == "ok") {
//                     $("#" + option + "-price1").empty().text("£" + result.price);
//                     if (sideLight1 == 'Yes') {
//                         $("#sidelight1-section1").addClass("table_row_show");
//                         $("#sidelight1-section1").removeClass("table_row_hide");
//                         $("#sidelight1-price1").empty().text("£" + result.price);
//                     } else {
//                         $("#sidelight1-section1").removeClass("table_row_show");
//                         $("#sidelight1-section1").addClass("table_row_hide");
//                     }
//                     if (sideLight2 == 'Yes') {
//                         $("#sidelight2-section1").addClass("table_row_show");
//                         $("#sidelight2-section1").removeClass("table_row_hide");
//                         $("#sidelight2-price1").empty().text("£" + result.price);
//                     } else {
//                         $("#sidelight2-section1").removeClass("table_row_show");
//                         $("#sidelight2-section1").addClass("table_row_hide");
//                     }
//                 }
//             }
//         });

//     } else if (option == 'glazingBead') {
//         if (value == '') {
//             value = $('#glazingBeads').val();
//         } else {
//             value = value;
//         }
//         var issingleconfiguration = $("input[name=issingleconfiguration]").val();
//         var glazingBeads = value;
//         var visionPanelQuantity = $('#visionPanelQuantity').val();
//         var vP1Width = $('#vP1Width').val();
//         var vP1Height1 = $('#vP1Height1').val();
//         var vP1Height2 = $('#vP1Height2').val();
//         var vP1Height3 = $('#vP1Height3').val();
//         var vP1Height4 = $('#vP1Height4').val();
//         var vP1Height5 = $('#vP1Height5').val();
//         var glazingBeadSpecies = $('#glazingBeadSpeciesid').val();
//         var glazingBeadsThickness = $('#glazingBeadsThickness').val();
//         var lippingSpecies = $('#lippingSpeciesid').val();
//         var glazingBeadsHeight = $('#glazingBeadsHeight').val();

//         $.ajax({
//             url: $("#generalLabourCost").html(),
//             method: "POST",
//             data: { _token: $("#_token").val(), option: option, glazingBeads: glazingBeads, visionPanelQuantity: visionPanelQuantity, vP1Width: vP1Width, vP1Height1: vP1Height1, vP1Height2: vP1Height2, vP1Height3: vP1Height3, vP1Height4: vP1Height4, vP1Height5: vP1Height5, issingleconfiguration: issingleconfiguration, glazingBeadSpecies: glazingBeadSpecies, glazingBeadsThickness: glazingBeadsThickness, lippingSpecies: lippingSpecies, glazingBeadsHeight: glazingBeadsHeight },
//             dataType: "Json",
//             success: function (result) {
//                 if (result.status == "ok") {
//                     $("#" + option + "-price1").empty().text("£" + result.price);
//                 }
//             }
//         });
//     } else if (option == 'overpanel2') {
//         if (value == '') {
//             value = $('#overpanel').val();
//         } else {
//             value = value;
//         }
//         if (value == 'Fan_Light') {
//             var overpanel = value;
//             var oPHeigth = $('#oPHeigth').val();
//             var oPWidth = $('#oPWidth').val();
//             var OpBeadThickness = $('#OpBeadThickness').val();
//             var opGlazingBeadSpecies = $('#opGlazingBeadSpeciesïd').val();
//             var lippingSpecies = $('#lippingSpeciesid').val();
//             var OpBeadHeight = $('#OpBeadHeight').val();

//             $.ajax({
//                 url: $("#generalLabourCost").html(),
//                 method: "POST",
//                 data: { _token: $("#_token").val(), option: option, overpanel: overpanel, OpBeadThickness: OpBeadThickness, opGlazingBeadSpecies: opGlazingBeadSpecies, OpBeadHeight: OpBeadHeight, lippingSpecies: lippingSpecies, oPWidth: oPWidth, oPHeigth: oPHeigth },
//                 dataType: "Json",
//                 success: function (result) {
//                     if (result.status == "ok") {
//                         $("#" + option + "-price1").empty().text("£" + result.price);
//                     }
//                 }
//             });
//         }
//     } else if (option == 'sideLight2') {
//         if (value == '') {
//             value = $('#sideLight1').val();
//         } else {
//             value = value;
//         }
//         if (value == 'Yes') {
//             var sideLight1 = value;
//             var sideLight2 = $('#sideLight2').val();;
//             var SlBeadThickness = $('#SlBeadThickness').val();
//             var SlBeadHeight = $('#SlBeadHeight').val();
//             var SL1Width = $('#SL1Width').val();
//             var SL1Height = $('#SL1Height').val();
//             var SideLight1GlazingBeadSpeciesid = $('#SideLight1GlazingBeadSpeciesid').val();
//             $.ajax({
//                 url: $("#generalLabourCost").html(),
//                 method: "POST",
//                 data: { _token: $("#_token").val(), option: option, sideLight1: sideLight1, SlBeadThickness: SlBeadThickness, SlBeadHeight: SlBeadHeight, SL1Width: SL1Width, SideLight1GlazingBeadSpeciesid: SideLight1GlazingBeadSpeciesid, SL1Height: SL1Height, sideLight2: sideLight2 },
//                 dataType: "Json",
//                 success: function (result) {
//                     if (result.status == "ok") {
//                         $("#" + option + "-price1").empty().text("£" + result.price);
//                     }
//                 }
//             });
//         }
//     } else if (option == 'sideLight12') {
//         if (value == '') {
//             value = $('#sideLight2').val();
//         } else {
//             value = value;
//         }
//         if (value == 'Yes') {
//             var sideLight2 = value;
//             var sideLight1 = $('#sideLight1').val();;
//             var SlBeadThickness = $('#SlBeadThickness').val();
//             var SlBeadHeight = $('#SlBeadHeight').val();
//             var SL2Width = $('#SL2Width').val();
//             var SL2Height = $('#SL2Height').val();
//             var SideLight2GlazingBeadSpeciesid = $('#SideLight2GlazingBeadSpeciesid').val();
//             $.ajax({
//                 url: $("#generalLabourCost").html(),
//                 method: "POST",
//                 data: { _token: $("#_token").val(), option: option, sideLight2: sideLight2, SlBeadThickness: SlBeadThickness, SlBeadHeight: SlBeadHeight, SL2Width: SL2Width, SideLight2GlazingBeadSpeciesid: SideLight2GlazingBeadSpeciesid, SL2Height: SL2Height, sideLight1: sideLight1 },
//                 dataType: "Json",
//                 success: function (result) {
//                     if (result.status == "ok") {
//                         $("#" + option + "-price1").empty().text("£" + result.price);
//                     }
//                 }
//             });
//         }
//     } else {
//         $.ajax({
//             url: $("#generalLabourCost").html(),
//             method: "POST",
//             data: { _token: $("#_token").val(), option: option, optionName: optionname },
//             dataType: "Json",
//             success: function (result) {
//                 if (result.status == "ok") {
//                     $("#" + option + "-price1").empty().text("£" + result.price);
//                     $("#" + option + "-description1").empty().text(result.description);
//                 }
//             }
//         });
//     }
// }

$("#extLinerThickness,#frameDepth").on("keyup", function () {
    FramePrice('extLiner');
});

$("#frameThickness,#frameDepth,#SL1Height,#SL2Height").on("keyup", function () {
    FramePrice('sideLight3');
    FramePrice('Rebated_Frame');
});

$("#OPLippingThickness,#frameDepth").on("keyup", function () {
    FramePrice('overpanel3');
});

function FramePrice(option, value = "") {

}
// function FramePrice(option, value = "") {
//     var doorsetType = localStorage.getItem('doorsetType');
//     var frameType = $('#frameType').val();
//     var frameMaterial = $('#frameMaterialNew').val();
//     var lippingSpecies = $('#lippingSpeciesid').val();
//     if (option == 'Plant_on_Stop') {
//         $(".Plant_on_Stop_section").addClass("table_row_show");
//         $(".Plant_on_Stop_section").removeClass("table_row_hide");

//         var frameThickness = $('#frameThickness').val();
//         var plantonStopHeight = $('#plantonStopHeight').val();
//         var frameDepth = $('#frameDepth').val();
//         var plantonStopWidth = $('#plantonStopWidth').val();
//         var frameHeight = $('#frameHeight').val();

//         $.ajax({
//             url: $("#FrameCost").html(),
//             method: "POST",
//             data: { _token: $("#_token").val(), option: option, frameType: frameType, frameMaterial: frameMaterial, lippingSpecies: lippingSpecies, frameThickness: frameThickness, plantonStopHeight: plantonStopHeight, frameDepth: frameDepth, plantonStopWidth: plantonStopWidth, frameHeight: frameHeight },
//             dataType: "Json",
//             success: function (result) {
//                 if (result.status == "ok") {
//                     $("#Plant_on_Stop1-price1").empty().text("£" + result.price1);
//                     $("#Plant_on_Stop2-price1").empty().text("£" + result.price2);
//                     $("#Plant_on_Stop3-price1").empty().text("£" + result.price3);
//                     $("#Plant_on_Stop4-price1").empty().text("£" + result.price4);

//                 }
//             }
//         });
//     } else if (option == 'Rebated_Frame') {
//         $(".Rebated_Frame_section").addClass("table_row_show");
//         $(".Rebated_Frame_section").removeClass("table_row_hide");

//         var frameThickness = $('#frameThickness').val();
//         var plantonStopHeight = $('#plantonStopHeight').val();
//         var frameDepth = $('#frameDepth').val();
//         var plantonStopWidth = $('#plantonStopWidth').val();
//         var frameHeight = $('#frameHeight').val();

//         $.ajax({
//             url: $("#FrameCost").html(),
//             method: "POST",
//             data: { _token: $("#_token").val(), option: option, frameType: frameType, frameMaterial: frameMaterial, lippingSpecies: lippingSpecies, frameThickness: frameThickness, plantonStopHeight: plantonStopHeight, frameDepth: frameDepth, plantonStopWidth: plantonStopWidth, frameHeight: frameHeight },
//             dataType: "Json",
//             success: function (result) {
//                 if (result.status == "ok") {
//                     $("#Rebated_Frame1-price1").empty().text("£" + result.price);
//                     $("#Rebated_Frame2-price1").empty().text("£" + result.price);
//                 }
//             }
//         });
//     } else if (option == 'extLiner') {
//         $(".extLiner_section").addClass("table_row_show");
//         $(".extLiner_section").removeClass("table_row_hide");

//         var extLiner = $('#extLiner').val();
//         var extLinerThickness = $('#extLinerThickness').val();
//         var frameDepth = $('#frameDepth').val();

//         $.ajax({
//             url: $("#FrameCost").html(),
//             method: "POST",
//             data: { _token: $("#_token").val(), option: option, frameType: frameType, frameMaterial: frameMaterial, lippingSpecies: lippingSpecies, extLiner: extLiner, extLinerThickness: extLinerThickness, frameDepth: frameDepth },
//             dataType: "Json",
//             success: function (result) {
//                 if (result.status == "ok") {
//                     $("#extLiner1-price1").empty().text("£" + result.price);
//                     $("#extLiner2-price1").empty().text("£" + result.price);
//                 }
//             }
//         });
//     } else if (option == 'sideLight3') {
//         $(".sideLight3_section").addClass("table_row_show");
//         $(".sideLight3_section").removeClass("table_row_hide");

//         var sideLight1 = $('#sideLight1').val();
//         var frameThickness = $('#frameThickness').val();
//         var frameDepth = $('#frameDepth').val();
//         var SL1Height = $('#SL1Height').val();
//         var SL2Height = $('#SL2Height').val();

//         $.ajax({
//             url: $("#FrameCost").html(),
//             method: "POST",
//             data: { _token: $("#_token").val(), option: option, frameType: frameType, frameMaterial: frameMaterial, lippingSpecies: lippingSpecies, sideLight1: sideLight1, frameThickness: frameThickness, frameDepth: frameDepth, SL1Height: SL1Height, SL2Height: SL2Height },
//             dataType: "Json",
//             success: function (result) {
//                 if (result.status == "ok") {
//                     $("#sideLight31-price1").empty().text("£" + result.price);
//                     $("#sideLight32-price1").empty().text("£" + result.price);
//                 }
//             }
//         });
//     } else if (option == 'overpanel3') {
//         $(".overpanel3_section").addClass("table_row_show");
//         $(".overpanel3_section").removeClass("table_row_hide");

//         var overpanel = $('#overpanel').val();
//         var frameThickness = $('#frameThickness').val();
//         var frameDepth = $('#frameDepth').val();
//         var OPLippingThickness = $('#OPLippingThickness').val();

//         $.ajax({
//             url: $("#FrameCost").html(),
//             method: "POST",
//             data: { _token: $("#_token").val(), option: option, frameType: frameType, frameMaterial: frameMaterial, lippingSpecies: lippingSpecies, overpanel: overpanel, frameThickness: frameThickness, frameDepth: frameDepth, OPLippingThickness: OPLippingThickness },
//             dataType: "Json",
//             success: function (result) {
//                 if (result.status == "ok") {
//                     $("#overpanel3-price1").empty().text("£" + result.price);
//                 }
//             }
//         });
//     }
// }

function architrave(isModal=0) {
    var architraveMaterialNew = $('#architraveMaterialNew').val();
    var url = $("#architrave-system-filter").html();
    $.ajax({
        url: url,
        method: "POST",
        dataType: "Json",
        data: {  _token: $("#_token").val(), pageId: 6 },
        success: function (result) {
            if (result.status == "ok") {

                var innerHtml = '';
                var leepingSpecies = result.lippingSpecies;

                if (leepingSpecies != '') {
                    var leepingSpecieslength = result.lippingSpecies.length;
                    var innerHtml1 = "";
                    innerHtml1 += '<div class="container"><div class="row">';
                    var costToShow = 0;
                    for (var j = 0; j < leepingSpecieslength; j++) {
                        var filepath = $("input[name='base_url']").val() + "/uploads/Options/" + leepingSpecies[j].file;

                        innerHtml1 += '<div class="col-md-2 col-sm-4 col-6 cursor-pointer" onclick="GlazingValueFill(' + leepingSpecies[j].id + ',\'' + leepingSpecies[j].SpeciesName + '\',\'#glazingModal\',' + costToShow + ')">'
                            + '<div class="color_box">'
                            + '<div class="frameMaterialImage"><img width="100%" height="100" src="' + filepath + '"></div>'
                            + '<h4>' + leepingSpecies[j].SpeciesName + '</h4>'
                            + '</div></div>';

                        $("#UniversalModalBody").empty().append(innerHtml1);
                        if (architraveMaterialNew != null) {
                            if (architraveMaterialNew == leepingSpecies[j].id) {
                                $("#architraveMaterial").val(leepingSpecies[j].SpeciesName);
                            }
                        }
                    }
                    if(isModal == 0){
                        $('#UniversalModal').modal('show');
                    }
                }



            } else {
                innerHtmlPopUp += 'No Frame architrave Found';

                $("#architraveMaterial").empty().append(innerHtml);
            }
        }
    });
}

$("#doorsetType").on('change', function () {
    var doorsetType = $('#doorsetType').val();
    localStorage.setItem('doorsetType', doorsetType);
});


// $.when(doorLeafFacingPrice()).done(function(){
//     $('.loader').css({'display':'none'});
// });



$("#DoorDimensionsIcon").on("click", function () {

    var url = $("#door_dimension_url").val();
    var base_url = $("input[name='base_url']").val();
    var door_leaf_facing = $("#doorLeafFacing").val();
    var door_leaf_finish = $(".doorLeafFinishSelect").val();
    var leaf_type = $("#leafConstruction").val();
    var firerating = $("#fireRating").val();
    if(!door_leaf_facing || !leafConstruction || !firerating){
        return false;
    }
    let pageId = pageIdentity();
    $.ajax({
        url: url,
        type: "GET",
        data: { door_leaf_facing: door_leaf_facing, door_leaf_finish: door_leaf_finish, leaf_type: leaf_type, firerating, firerating, page_id: pageId },
        success: function (data) {
            var result = data.map((row) => (
                `<div class="col-md-2 col-sm-4 col-6 cursor-pointer" data-dismiss="modal" onclick="DoorDimensionValueFill(${row.id},'${row.code}',${row.mm_width},${row.mm_height})"><div class="color_box"><div class="frameMaterialImage"><img width="100%" height="100" src="${base_url}/DoorDimension/${row.image ? row.image : "vicaima_default_doorDimantion.jpg"}"></div><h4>${row.code}-${row.mm_width}x${row.mm_height}</h4></div></div>`
            ))
            $("#DoorDimensionBody").empty().append(result)

        }
    })


    var tollerance = $("#tollerance").val();

    setTimeout(() => {
        var so_width = parseInt($("#sOWidth").val());
        var so_height = parseInt($("#sOHeight").val());
        framewidth();
        $("#frameHeight").val(so_height - parseInt(tollerance));
    }, 500);



})

$("#DoorDimensionsIcon2").on("click", function () {
    var url = $("#door_dimension_url_leaf").val();
    var base_url = $("input[name='base_url']").val();
    var door_leaf_facing = $("#doorLeafFacing").val();
    var door_leaf_finish = $(".doorLeafFinishSelect").val();
    var leaf_type = $("#leafConstruction").val();
    var firerating = $("#fireRating").val();
    var DoorDimensionId = $("#DoorDimensionId").val();
    var DoorDimensionId = $("#DoorDimensionId").val();
    if(!door_leaf_facing || !leafConstruction || !firerating || !DoorDimensionId || !doorDimensionHeightWidth){
        return false;
    }
    let pageId = pageIdentity();
    $.ajax({
        url: url,
        type: "GET",
        data: { door_leaf_facing: door_leaf_facing, door_leaf_finish: door_leaf_finish, leaf_type: leaf_type, firerating, firerating, page_id: pageId ,DoorDimensionId:DoorDimensionId},
        success: function (data) {
            var result = data.map((row) => (
                `<div class="col-md-2 col-sm-4 col-6 cursor-pointer" data-dismiss="modal" onclick="DoorDimensionValueFill2(${row.id},'${row.code}',${row.mm_width},${row.mm_height})"><div class="color_box"><div class="frameMaterialImage"><img width="100%" height="100" src="${base_url}/DoorDimension/${row.image ? row.image : "vicaima_default_doorDimantion.jpg"}"></div><h4>${row.code}-${row.mm_width}x${row.mm_height}</h4></div></div>`
            ))
            $("#DoorDimensionBody2").empty().append(result)

        }
    })


    // var tollerance = $("#tollerance").val();

    // setTimeout(() => {
    //     var so_width = parseInt($("#sOWidth").val());
    //     var so_height = parseInt($("#sOHeight").val());
    //     $("#frameWidth").val(so_width - (parseInt(tollerance) * 2));
    //     $("#frameHeight").val(so_height - parseInt(tollerance));
    // }, 500);



})


function DoorDimensionValueFill(id, value, mm_width, mm_height) {
    let doortypeValue = $('#doorsetType').val();
    var adjustmentLeafWidth1 = $("#adjustmentLeafWidth1").val();
    var adjustmentLeafWidth2 = $("#adjustmentLeafWidth2").val();
    var adjustmentLeafHeightNoOP = $("#adjustmentLeafHeightNoOP").val();
    if (adjustmentLeafWidth1 == '') {
        adjustmentLeafWidth1 = 0;
    }
    if (adjustmentLeafWidth2 == '') {
        adjustmentLeafWidth2 = 0;
    }
    if (adjustmentLeafHeightNoOP == '') {
        adjustmentLeafHeightNoOP = 0;
    }
    var undercut = $("#undercut").val();
    $("#append_input_field").empty().append(`<input type="hidden" name="DoorDimensionId" value="${id}">`);
    $("#DoorDimensions").val(value);
    var inputValue = $('input[name="DoorDimensionsCode"]').val();
    if(inputValue){
        $('input[name="DoorDimensionsCode"]').val(inputValue);
        $("#DoorDimensions-selected").empty().text(inputValue);
        $("#DoorDimensions-section").removeClass("table_row_hide");
        $("#DoorDimensions-section").addClass("table_row_show");
    }

    $("#DoorDimensionId").val(id);
    $("#leafWidth1").val(mm_width - adjustmentLeafWidth1);
    $("#leafHeightNoOP").val(mm_height - adjustmentLeafHeightNoOP);

    if(doortypeValue != 'leaf_and_a_half'){
        if (doortypeValue == 'SD') {
            $(".LeaftypewithEmpty").val("");
        } else {
            $(".LeaftypewithEmpty").val(mm_width - adjustmentLeafWidth2);
        }
    }
    if (doortypeValue == 'DD') {

        $("#leafWidth2").val(mm_width - adjustmentLeafWidth1);
    }
    $('#doorDimensionHeightWidth').val(mm_width + ',' + mm_height);
    $("#DoorDimensionsCode-selected").empty().text(value);
    $("#DoorDimensionsCode-section").removeClass("table_row_hide");
    $("#DoorDimensionsCode-section").addClass("table_row_show");
    doorDimensionCalculation();
}

function DoorDimensionValueFill2(id, value, mm_width, mm_height) {
    $("#adjustmentLeafWidth2").attr('readonly', false);
    var adjustmentLeafHeightNoOP = $("#adjustmentLeafHeightNoOP").val();

    var adjustmentLeafWidth1 = $("#adjustmentLeafWidth1").val();
    var adjustmentLeafWidth2 = $("#adjustmentLeafWidth2").val();

    if (adjustmentLeafWidth1 == '') {
        adjustmentLeafWidth1 = 0;
    }
    if (adjustmentLeafWidth2 == '') {
        adjustmentLeafWidth2 = 0;
    }
    $("#leafWidth2").val(mm_width - adjustmentLeafWidth2);
    $("#leafHeightNoOP").val(mm_height - adjustmentLeafHeightNoOP);
    $("#append_input_field").empty().append(`<input type="hidden" name="DoorDimensionId2" value="${id}">`);
    $("#DoorDimensions2").val(value);
    $("#DoorDimensionId2").val(id);


    $("#DoorDimensionsCode2-selected").empty().text(value);
    $("#DoorDimensionsCode2-section").removeClass("table_row_hide");
    $("#DoorDimensionsCode2-section").addClass("table_row_show");

    $('#doorDimensionHeightWidth2').val(mm_width + ',' + mm_height);
    doorDimensionCalculation();
}

function doorDimensionCalculation(){
    let doortypeValue = $('#doorsetType').val();
    var tollerance = $("#tollerance").val();
    var frame_thickness = $("#frameThickness").val();
    var gap = $("#gap").val();
    var undercut = $("#undercut").val();

    if (tollerance == '') {
        tollerance = 0;
    }

    if (frame_thickness == '') {
        frame_thickness = 0;
    }

    if (undercut == '') {
        undercut = 0;
    }

    if (gap == '') {
        gap = 0;
    }
    var leafWidth1 = $("#leafWidth1").val();
    var leafWidth2 = $("#leafWidth2").val();
    var leafHeightNoOP = $("#leafHeightNoOP").val();
    if (leafHeightNoOP == '') {
        leafHeightNoOP = 0;
    }
     if (leafWidth1 == '') {
        leafWidth1 = 0;
    }
    if (leafWidth2 == '') {
        leafWidth2 = 0;
    }
    var so_width = parseInt(leafWidth1) + parseInt(tollerance) * 2 + parseInt(frame_thickness) * 2 + parseInt(gap) * 2;
    if (doortypeValue == 'DD') {
        so_width = (parseInt(leafWidth1)*2) + (parseInt(tollerance) * 2) + (parseInt(frame_thickness) * 2) + (parseInt(gap) * 2);
        $("#sOWidth").val(so_width)
    }else if (doortypeValue == 'SD'){
        $("#sOWidth").val(so_width)
    }else{
        so_width = (parseInt(leafWidth1) + parseInt(leafWidth2)) + (parseInt(tollerance) * 2) + (parseInt(frame_thickness) * 2) + (parseInt(gap) * 2);
        $("#sOWidth").val(so_width)
    }


    var soHeight = parseInt(leafHeightNoOP) + parseInt(tollerance) + parseInt(frame_thickness) + parseInt(undercut) + parseInt(gap);

    $("#sOHeight").val(soHeight);

    framewidth();

    var so_height = parseInt($("#sOHeight").val());
    $("#frameHeight").val(so_height - (parseInt(tollerance)));

    $("#sOWidth-selected").empty().text(so_width);
    $("#sOHeight-selected").empty().text(soHeight);
    $("#sOWidth-section,#sOHeight-section").removeClass("table_row_hide");
    $("#sOWidth-section,#sOHeight-section").addClass("table_row_show");

    let elements = $(this);
    render(elements);
}

$(document).on('change', '#doorsetType', function(){
    let doorsetType = $(this).val();
    $("#adjustmentLeafWidth1").val('');
    $("#adjustmentLeafWidth2").val('');
    $("#leafWidth1").val('');
    $("#leafWidth2").val('');
    $("#leafWidth1-selected").val('');
    $("#leafWidth1-selected").val('');

})

$("#adjustmentLeafWidth1, #adjustmentLeafWidth2, #adjustmentLeafHeightNoOP").on('keyup', function () {
    let height_width_doorDimension = $('#doorDimensionHeightWidth').val().split(',');
    let height_width_doorDimension2 = $('#doorDimensionHeightWidth2').val().split(',');
    var adjustmentLeafWidth1 = $("#adjustmentLeafWidth1").val();
    let doortypeValue = $('#doorsetType').val();
    if($('#doorsetType').val() =='DD' && adjustmentLeafWidth1){

        $('#adjustmentLeafWidth2').val(adjustmentLeafWidth1);
    }else if($('#doorsetType').val() != 'leaf_and_a_half'){
        $('#adjustmentLeafWidth2').val('');
    }

    if (height_width_doorDimension.length) {
        var leafW1 = height_width_doorDimension[0].trim();
        var leafW2 = height_width_doorDimension[0].trim();
        var leafH = height_width_doorDimension[1].trim();
        var adjustmentLeafWidth1 = $("#adjustmentLeafWidth1").val();
        var adjustmentLeafWidth2 = $("#adjustmentLeafWidth2").val();
        var adjustmentLeafHeightNoOP = $("#adjustmentLeafHeightNoOP").val();
        var tollerance = $("#tollerance").val();
        var frame_thickness = $("#frameThickness").val();
        var gap = $("#gap").val();
        var undercut = $("#undercut").val();

        if($('#doorsetType').val() =='DD' && adjustmentLeafWidth1){
            $('#adjustmentLeafWidth2').val(adjustmentLeafWidth1);
        }else if($('#doorsetType').val() != 'leaf_and_a_half'){
            $('#adjustmentLeafWidth2').val('');
        }

        if (adjustmentLeafWidth1 == '') {
            adjustmentLeafWidth1 = 0;
        }
        if (adjustmentLeafWidth2 == '') {
            adjustmentLeafWidth2 = 0;
        }
        if (adjustmentLeafHeightNoOP == '') {
            adjustmentLeafHeightNoOP = 0;
        }
        if (tollerance == '') {
            tollerance = 0;
        }
        if (frame_thickness == '') {
            frame_thickness = 0;
        }
        if (undercut == '') {
            undercut = 0;
        }
        if (gap == '') {
            gap = 0;
        }
        $("#leafWidth1").val(parseInt(leafW1) - parseInt(adjustmentLeafWidth1));

        let doortypeValue = $('#doorsetType').val();

        if(doortypeValue != 'leaf_and_a_half'){
            if (doortypeValue == 'SD') {
                $(".LeaftypewithEmpty").val("");
            } else {
                $(".LeaftypewithEmpty").val(parseInt(leafW2) - parseInt(adjustmentLeafWidth2));
            }
        }

        $("#leafHeightNoOP").val(parseInt(leafH) - parseInt(adjustmentLeafHeightNoOP));


        var leafWidth2 = $("#leafWidth2").val();

        var so_width = parseInt(leafW1) + parseInt(tollerance) * 2 + parseInt(frame_thickness) * 2 + parseInt(gap) * 2;
        if (doortypeValue == 'DD') {
            so_width = (parseInt(leafW1)*2) + (parseInt(tollerance) * 2) + (parseInt(frame_thickness) * 2) + (parseInt(gap) * 2);
            $("#sOWidth").val(so_width - (parseInt(adjustmentLeafWidth1)*2))
        }else if (doortypeValue == 'SD'){
            $("#sOWidth").val(so_width - parseInt(adjustmentLeafWidth1))
        }else{
            so_width = (parseInt(leafW1) + parseInt(leafWidth2)) + (parseInt(tollerance) * 2) + (parseInt(frame_thickness) * 2) + (parseInt(gap) * 2);
            $("#sOWidth").val(so_width - parseInt(adjustmentLeafWidth1))
        }

        var soHeight = parseInt(leafH) + parseInt(tollerance) + parseInt(frame_thickness) + parseInt(undercut) + parseInt(gap)
        $("#sOHeight").val(soHeight - parseInt(adjustmentLeafHeightNoOP))

    }
    if (doortypeValue == 'leaf_and_a_half') {
        if (height_width_doorDimension2.length) {
            var tollerance = $("#tollerance").val();
            var frame_thickness = $("#frameThickness").val();
            var gap = $("#gap").val();
            var undercut = $("#undercut").val();
            var leafW2 = height_width_doorDimension2[0].trim();
            var adjustmentLeafWidth2 = $("#adjustmentLeafWidth2").val();
            var adjustmentLeafHeightNoOP = $("#adjustmentLeafHeightNoOP").val();

            if (adjustmentLeafWidth2 == '') {
                adjustmentLeafWidth2 = 0;
            }
            if (tollerance == '') {
                tollerance = 0;
            }
            if (frame_thickness == '') {
                frame_thickness = 0;
            }
            if (undercut == '') {
                undercut = 0;
            }
            if (gap == '') {
                gap = 0;
            }
            $("#leafWidth2").val(parseInt(leafW2) - parseInt(adjustmentLeafWidth2));
            var leafWidth1 = $("#leafWidth1").val();
            var so_width = (parseInt(leafW2) + parseInt(leafWidth1)) + (parseInt(tollerance) * 2) + (parseInt(frame_thickness) * 2) + (parseInt(gap) * 2);
            $("#sOWidth").val(so_width - parseInt(adjustmentLeafWidth2))
        }
    }
    corewidth1Value();
    doorDimensionCalculation();
});

$(document).on('change','#doorsetType', function(){
  let doortypeValue = $('#doorsetType').val();

        if (doortypeValue == 'SD') {
            $(".LeaftypewithEmpty").val("");
        }
});
$(document).ready(function() {
    $('#floorFinish').on('input', function() {
        if($(this).val().length > 3) {
            $(this).val($(this).val().slice(0, 3));
        }
    });
    $('#undercut').on('input', function() {
        if($(this).val().length > 3) {
            $(this).val($(this).val().slice(0, 3));
        }
    });
    $('#frameThickness').on('input', function() {
        if($(this).val().length > 3) {
            $(this).val($(this).val().slice(0, 3));
        }
    });
    $('#gap').on('input', function() {
        if($(this).val().length > 3) {
            $(this).val($(this).val().slice(0, 3));
        }
    });
});

async function resetAll() {
    // $('#DoorDimensions').val('');
    $('#doorDimensionHeightWidth').val('');
    $('#doorDimensionHeightWidth').val('');
    $('#adjustmentLeafWidth1').val('');
    $('#adjustmentLeafWidth2').val('');
    $('#adjustmentLeafHeightNoOP').val('');
    $('#sOWidth').val('');
    $('#sOHeight').val('');
    $('#sODepth').val('');
    $('#decorativeGroves').val('No');
    $('#leafHeightNoOP').val('');
    $('#leafWidth1').val('');
    $('#hinge1Location').val('');
    $('#hinge2Location').val('');
    $('#hinge3Location').val('');
    $('#grooveWidth').val(0).attr('readonly', true);
    $('#grooveDepth').val(0).attr('readonly', true);
    $('#maxNumberOfGroove').val(0).attr('readonly', true);
    $('#numberOfGroove').val(0).attr('readonly', true);
    $('#numberOfVerticalGroove').val(0).attr('readonly', true);
    $('#numberOfHorizontalGroove').val(0).attr('readonly', true);
    $("#grooveLocation").val("");
    $('#doorThickness').val('');
    let elem = $(this);
    render(elem);
}

// 4th Hinges showing logic
$(document).ready(function() {
    $('#fourthHinges').change(function() {
        if ($(this).is(':checked')) {
            $('#hinge4LocationLabel').text('Hinge 4 Location  (Min 150 mm, Max 250 mm)');
            $('#hing4LocationDiv').removeClass("d-none");
        } else {
            $('#hinge4LocationLabel').text('Hinge 3 Location  (Min 150 mm, Max 250 mm)');
            $('#hing4LocationDiv').addClass("d-none");
        }
    });

    if($('#fourthHinges').is(':checked')){
        $('#hinge4LocationLabel').text('Hinge 4 Location  (Min 150 mm, Max 250 mm)');
        $('#hing4LocationDiv').removeClass("d-none");
    }
});
function onlyLipingSpecies(fireRating){
    let pageId = pageIdentity();
        var leaf1VpAreaSizeM2Value = $('#leaf1VpAreaSizeM2').val();
        leaf1VpAreaSizeM2Value = (leaf1VpAreaSizeM2Value == 0)?"":leaf1VpAreaSizeM2Value;
        $.ajax({
            url: $("#liping-glazing-system-filter").html(),
            method:"POST",
            dataType:"Json",
            data:{pageId:pageId,fireRating:fireRating,_token:$("#_token").val(), leaf1VpAreaSizeM2Value : leaf1VpAreaSizeM2Value},
            success: function(result){
                var innerHtml1='';
                if(result.status=="ok"){
                    var innerHtml ='';
                    var lippingSpecies = result.OnlylippingSpecies;
                    var lippingSpeciesLength =result.OnlylippingSpecies.length;

                    if(lippingSpecies!='' && lippingSpeciesLength>0){
                        innerHtml1 += '<div class="container"><div class="row">';

                        var LippingSpeciesValue = document.getElementById('LippingSpecies-value');
                        for(var leep =0; leep<lippingSpeciesLength;leep++){
                            if(LippingSpeciesValue != null){
                                LippingSpeciesValue = $("#LippingSpecies-value").data("value");
                                if(LippingSpeciesValue != "" && LippingSpeciesValue == lippingSpecies[leep].id){
                                    $("#lippingSpecies").val(lippingSpecies[leep].SpeciesName);
                                }
                            }


                            var filepath = $("input[name='base_url']").val()+"/uploads/Options/"+lippingSpecies[leep].file;

                            var possibleSelectedOptionsArray = JSON.parse(possibleSelectedOptionsJson);

                            if (possibleSelectedOptionsArray.hasOwnProperty("OnlylippingSpecies")) {

                                if (lippingSpecies[leep].hasOwnProperty("SelectedLippingSpeciesCost")) {
                                    var costToShow =  lippingSpecies[leep].SelectedLippingSpeciesCost;

                                } else {
                                    var costToShow =  lippingSpecies[leep].LippingSpeciesCost;
                                }
                            }else{
                                var costToShow =  lippingSpecies[leep].LippingSpeciesCost;
                            }

                            innerHtml1 += '<div class="col-md-2 col-sm-4 col-6 cursor-pointer" onClick="GlazingValueFill('+lippingSpecies[leep].id+',\''+lippingSpecies[leep].SpeciesName+'\',\'#LipingModal\','+costToShow+')">'
                            + '<div class="color_box">'
                            + '<div class="frameMaterialImage"><img width="100%" height="100" src="'+ filepath +'"></div>'
                            + '<h4>'+lippingSpecies[leep].SpeciesName+'</h4>'
                            + '</div></div>';

                        }
                    } else {
                    }
                    $("#LipingModalBody").empty().append(innerHtml1);
                }
                 else {
                    var lippingSpecies = result.lippingSpecies;
                    var lippingSpeciesLength =result.lippingSpecies.length;
                    innerHtml+='<option value="">No Glazing Systems Found</option>';
                    if(lippingSpecies!='' && lippingSpeciesLength>0){
                        innerHtml1 = "";
                        costToShow = 0;
                        innerHtml1 += '<div class="container"><div class="row">';
                        for(var leep =0; leep<lippingSpeciesLength;leep++){
                            var filepath = $("input[name='base_url']").val()+"/uploads/Options/"+lippingSpecies[leep].file;
                            innerHtml1 += '<div class="col-md-2 col-sm-4 col-6 cursor-pointer" onClick="GlazingValueFill('+lippingSpecies[leep].id+',\''+lippingSpecies[leep].SpeciesName+'\',\'#LipingModal\','+costToShow+')">'
                            + '<div class="color_box">'
                            + '<div class="frameMaterialImage"><img width="100%" height="100" src="'+ filepath +'"></div>'
                            + '<h4>'+lippingSpecies[leep].SpeciesName+'</h4>'
                            + '</div></div>';
                        }
                        $("#frameMaterialModalBody").empty().append(innerHtmlPopUp);
                    } else {
                        innerHtml1+='<option value="">No  Species Found</option>';
                    }
                    $("#lippingSpecies").empty().append(innerHtml1);
                }
                // $("#glazingSystemsThickness").val(0);
            }
        });
}
