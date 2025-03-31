$(document).on('click','#Dropseal',function(e){
    floor_finish_change();
});

function floor_finish_change(){

    if($("#fireRating").val()=='FD30' || $("#fireRating").val()=='FD60'){
        $("#undercut").attr("readonly","readonly")
        $("#floor_finish").show();
        $("#undercut").val(parseInt($("#floorFinish").val())+8);
    }else if($("#fireRating").val()=='FD30s' || $("#fireRating").val()=='FD60s'){
        $("#undercut").attr("readonly","readonly")
        $("#floor_finish").show();
        $("#undercut").val(parseInt($("#floorFinish").val())+3);
    }else{
        $("#undercut").attr('readonly',false)
        $("#floor_finish").show();
    }

    let Dropseal = document.getElementById("Dropseal");

    if (Dropseal.checked) {
        if($("#fireRating").val()=='FD30s' || $("#fireRating").val()=='FD60s'){
            $("#undercut").val(parseInt($("#floorFinish").val()) + 8);
        }
    }
    var withoutFrameId = $("#withoutFrameId").val();
    if(withoutFrameId == 1){
        $("#floor_finish").hide();
    }
}

$(document).on('click','#swingType',function(e){
    swingTypeFrameType();
});

function swingTypeFrameType(){
    if($("#swingType").val() == 'DA'){
        $("select[name=frameType]").val('Scalloped').trigger("change");
        $("#frameType option[value='Plant_on_Stop'], #frameType option[value='Rebated_Frame']").prop("disabled", true);
        $('#foursidedframe').prop({
            disabled: true,
            checked: false
        });
        $("#frameType option[value='Scalloped']").prop("disabled", false);
    }else if($("#swingType").val() == 'SA'){
        $("select[name=frameType]").val('Plant_on_Stop').trigger("change");
        $("#frameType option[value='Plant_on_Stop']").prop("disabled", false);
        $("#frameType option[value='Rebated_Frame']").prop("disabled", false);
        $("#frameType option[value='Scalloped']").prop("disabled", true);
        // $("#frameType").val('').trigger('change');
        $('#foursidedframe').prop('disabled', false);
    }
    framTypeChangeInputEnableDisable();
}


$(document).on('click','#foursidedframe',function(e){
    four_sided_frame();
});

function four_sided_frame(){
    let foursidedframe = document.getElementById("foursidedframe");

    if (foursidedframe.checked) {
        $("#frameType option[value='Scalloped']").prop("disabled", true);
        $("#undercut,#floorFinish").val(0).prop("readonly", true);
    }else{
        $("#frameType option[value='Scalloped']").prop("disabled", false);
        $("#floorFinish").prop("readonly", false);
        floor_finish_change();
    }
    swingTypeFrameType();
}

// JFDS-440
$(document).ready(function () {
    // S.O Width
    $('#sOWidth').on('input', function () {
        let value = $(this).val();
        value = value.replace(/[^0-9]/g, '');
        // Restrict to a maximum of 6 digits
        if (value.length > 6) {
            value = value.slice(0, 6);
        }
        // Update the input value
        $(this).val(value);
    });
    // S.O Height
    $('#sOHeight').on('input', function () {
        let value = $(this).val();
        value = value.replace(/[^0-9]/g, '');
        // Restrict to a maximum of 6 digits
        if (value.length > 6) {
            value = value.slice(0, 6);
        }
        // Update the input value
        $(this).val(value);
    });
    // S.O Depth
    $('#sODepth').on('input', function () {
        let value = $(this).val();
        value = value.replace(/[^0-9]/g, '');
        // Restrict to a maximum of 6 digits
        if (value.length > 6) {
            value = value.slice(0, 6);
        }
        // Update the input value
        $(this).val(value);
    });
    // Floor Finish
    $('#floorFinish').on('input', function () {
        let value = $(this).val();
        value = value.replace(/[^0-9]/g, '');
        // Restrict to a maximum of 6 digits
        if (value.length > 6) {
            value = value.slice(0, 6);
        }
        // Update the input value
        $(this).val(value);
    });
    // Frame Thickness
    $('#frameThickness').on('input', function () {
        let value = $(this).val();
        value = value.replace(/[^0-9]/g, '');
        // Restrict to a maximum of 6 digits
        if (value.length > 6) {
            value = value.slice(0, 6);
        }
        // Update the input value
        $(this).val(value);
    });
});

$(document).on('change','#doorsetType',function(e){
    doorsetTypeDecorativeGroves1();
});

function doorsetTypeDecorativeGroves1(id = null){
    var doorsetType = (id == null)?$("#doorsetType").val():id;
    if(doorsetType == 'SD'){
        $('#DecorativeGrovesLeaf2').val('No').attr({'disabled':true}).trigger('change');
    }else{
        $('#DecorativeGrovesLeaf2').val('No').attr({'disabled':false}).trigger('change');
    }
    DecorativeGrovesLeaf2Change();
}

$(document).on('change','#IsSameAsDecorativeGroves1',function(e){
    IsSameAsDecorativeGroves1();
});

function IsSameAsDecorativeGroves1(){
    var IsSameAsDecorativeGroves1 = $('#IsSameAsDecorativeGroves1').val();
    let pageId = pageIdentity();
    if(IsSameAsDecorativeGroves1 == 'Yes'){
        $("#GrooveLocationLeaf2").val($("#grooveLocation").val()).attr({'disabled': false, "required": true});
        $("#GrooveWidthLeaf2").val($("#grooveWidth").val()).attr({'readonly': false, "required": true});
        $("#GrooveDepthLeaf2").val($("#grooveDepth").val()).attr({'disabled': false, "required": true});
        if(pageId == 4 || pageId == 5 || pageId == 6){
            $("#NumberOfGrooveLeaf2").val($("#numberOfGroove").val()).attr({'readonly': true, "required": false});
            $("#DoorDimensionGrooveLeaf2").val($("#doorDimensionGroove").val()).attr({'readonly': false});
            $("#DoorDimensionGrooveLeaf2").attr({ 'disabled': false, "readonly": true });
            $("#DoorDimensionGrooveLeaf2").addClass("bg-white");
        }else{
            $("#NumberOfGrooveLeaf2").val($("#numberOfGroove").val()).attr({'readonly': false, "required": true});
        }
        $("#NaxNumberOfGrooveLeaf2").val($("#maxNumberOfGroove").val()).attr({'readonly': false, "required": true});
        if($("#GrooveLocationLeaf2").val() == "Vertical_&_Horizontal"){
            $("#NumberOfGrooveLeaf2").attr({'readonly':true,"required":false});
            $("#MaxNumberOfGrooveLeaf2").attr({'readonly':true,"required":false});
            $("#NumberOfVerticalGrooveLeaf2").attr({'readonly':false,"required":true});
            $("#NumberOfHorizontalGrooveLeaf2").attr({'readonly':false,"required":true});
        }else if($("#GrooveLocationLeaf2").val() == "Vertical" || $("#GrooveLocationLeaf2").val() == "Horizontal"){
            $("#NumberOfGrooveLeaf2").attr({'readonly':false,"required":true});
            $("#MaxNumberOfGrooveLeaf2").attr({'readonly':true,"required":true});
            $("#NumberOfVerticalGrooveLeaf2").attr({'readonly':true,"required":false});
            $("#NumberOfHorizontalGrooveLeaf2").attr({'readonly':true,"required":false});
        }
        $("#NumberOfVerticalGrooveLeaf2").val($("#numberOfVerticalGroove").val()).attr({'readonly': false, "required": true});
        $("#NumberOfHorizontalGrooveLeaf2").val($("#numberOfHorizontalGroove").val()).attr({'readonly': false, "required": true});
    } else {
        $("#GrooveLocationLeaf2").val('').attr({'disabled': false, "required": true});
        $("#GrooveWidthLeaf2").val('0').attr({'readonly': false, "required": true});
        $("#GrooveDepthLeaf2").val('0').attr({'disabled': false, "required": true});


        if(pageId == 4 || pageId == 5 || pageId == 6){
            $('#GroovesIconLeaf2').removeAttr('data-target', '#DoorDimensionGrooves');
            $("#DoorDimensionGrooveLeaf2").attr({ 'disabled': false, "readonly": true }).val('');
            $("#DoorDimensionGrooveLeaf2").addClass("bg-white");
            $("#NumberOfVerticalGrooveLeaf2").val('0').attr({'readonly': true, "required": false});
            $("#NumberOfHorizontalGrooveLeaf2").val('0').attr({'readonly': true, "required": false});
            $("#NumberOfGrooveLeaf2").val('0').attr({'readonly': true, "required": false});
            $("#MaxNumberOfGrooveLeaf2").val('0').attr({'readonly': true, "required": false});
        }else{
            $("#NumberOfVerticalGrooveLeaf2").val('0').attr({'readonly': false, "required": true});
            $("#NumberOfHorizontalGrooveLeaf2").val('0').attr({'readonly': false, "required": true});
            $("#NumberOfGrooveLeaf2").val('0').attr({'readonly': false, "required": true});
            $("#MaxNumberOfGrooveLeaf2").val('0').attr({'readonly': false, "required": true});
        }
    }

}

$("#DecorativeGrovesLeaf2").change(function(){
    DecorativeGrovesLeaf2Change();
});

function DecorativeGrovesLeaf2Change(){
    let pageId = pageIdentity();
    if($('#DecorativeGrovesLeaf2').val()=="Yes"){
        $("#GrooveLocationLeaf2").attr({'disabled':false,"required":true});
        $("#IsSameAsDecorativeGroves1").attr({'disabled':false,"required":true});
        $("#GrooveWidthLeaf2").attr({'readonly':false,"required":true}).val('0');
        $("#GrooveDepthLeaf2").attr({'disabled':false,"required":true}).val('0');

        if($("#GrooveLocationLeaf2").val() == "Vertical_&_Horizontal"){
            $("#NumberOfGrooveLeaf2").attr({'readonly':true,"required":false}).val('0');
            $("#MaxNumberOfGrooveLeaf2").attr({'readonly':true,"required":false}).val('0');
            $("#NumberOfVerticalGrooveLeaf2").attr({'readonly':false,"required":true}).val('0');
            $("#NumberOfHorizontalGrooveLeaf2").attr({'readonly':false,"required":true}).val('0');
        }else  if($("#GrooveLocationLeaf2").val() == "Vertical" || $("#GrooveLocationLeaf2").val() == "Horizontal"){
            $("#NumberOfGrooveLeaf2").attr({'readonly':false,"required":true}).val('0');
            $("#MaxNumberOfGrooveLeaf2").attr({'readonly':true,"required":true}).val('0');
            $("#NumberOfVerticalGrooveLeaf2").attr({'readonly':true,"required":false}).val('0');
            $("#NumberOfHorizontalGrooveLeaf2").attr({'readonly':true,"required":false}).val('0');
        }
        if(pageId == 4 || pageId == 5 || pageId == 6){
            $("#DoorDimensionGrooveLeaf2").attr({ 'disabled': false, "readonly": true });
            $("#DoorDimensionGrooveLeaf2").addClass("bg-white");
        }
        doorLeafFacingPrice('DecorativeGrovesLeaf2',"Yes");
    } else {
        $("#GrooveLocationLeaf2").attr({'disabled':true,"required":false}).val('');
        $("#IsSameAsDecorativeGroves1").attr({'disabled':true,"required":false}).val('');
        $("#GrooveWidthLeaf2").attr({'readonly':true,"required":false}).val('0');
        $("#GrooveDepthLeaf2").attr({'disabled':true,"required":false}).val('0');
        $("#NumberOfGrooveLeaf2").attr({'readonly':true,"required":false}).val('0');
        $("#MaxNumberOfGrooveLeaf2").attr({'readonly':true,"required":false}).val('0');
        $("#NumberOfVerticalGrooveLeaf2").attr({'readonly':true,"required":false}).val('0');
        $("#NumberOfHorizontalGrooveLeaf2").attr({'readonly':true,"required":false}).val('0');
        if(pageId == 4 || pageId == 5 || pageId == 6){
            $('#GroovesIconLeaf2').removeAttr('data-target', '#DoorDimensionGrooves');
            $("#DoorDimensionGrooveLeaf2").removeClass("bg-white");
            $("#DoorDimensionGrooveLeaf2").attr({ 'disabled': false, "readonly": true }).val('');
        }
    }
}

$("#GrooveLocationLeaf2").change(function(){
    var GrooveLocationLeaf2 =  $(this).val();

    if(GrooveLocationLeaf2 == "Vertical_&_Horizontal"){
        $("#NumberOfGrooveLeaf2").attr({'readonly':true,"required":false}).val('0');
        $("#MaxNumberOfGrooveLeaf2").attr({'readonly':true,"required":false}).val('0');
        $("#NumberOfVerticalGrooveLeaf2").attr({'readonly':false,"required":true}).val('0');
        $("#NumberOfHorizontalGrooveLeaf2").attr({'readonly':false,"required":true}).val('0');
    }else{
        $("#NumberOfGrooveLeaf2").attr({'readonly':false,"required":true}).val('0');
        $("#MaxNumberOfGrooveLeaf2").attr({'readonly':true,"required":true}).val('0');

        if(GrooveLocationLeaf2 == "Vertical"){
            var LeafWidth1 = $("#LeafWidth1").val();
            LeafWidth1 = LeafWidth1 != '' ? (parseFloat(LeafWidth1)) : 0;
            $("#MaxNumberOfGrooveLeaf2").val(Math.round((LeafWidth1 / 100)));
        }else if(GrooveLocationLeaf2 == "Horizontal"){
            var LeafHeightNoOP = $("#LeafHeightNoOP").val();
            LeafHeightNoOP = LeafHeightNoOP != '' ? parseFloat(LeafHeightNoOP) : 0;
            $("#MaxNumberOfGrooveLeaf2").val(Math.round((LeafHeightNoOP / 100)));
        }

        $("#NumberOfVerticalGrooveLeaf2").attr({'readonly':true,"required":false}).val('0');
        $("#NumberOfHorizontalGrooveLeaf2").attr({'readonly':true,"required":false}).val('0');
    }
});

$(document).on('change', "#MaxNumberOfGrooveLeaf2", function(){
    var MaxNumberOfGrooveLeaf2 = Math.round($(this).val());
    $(this).val(MaxNumberOfGrooveLeaf2);
    $("#NumberOfGroovesLeaf2").attr('max', MaxNumberOfGrooveLeaf2);
    $("label[for='NumberOfGroovesLeaf2']").text("Number of Grooves (Max " + MaxNumberOfGrooveLeaf2 + ")");
    var NumberOfGroovesCheck = parseFloat($('#NumberOfGroovesLeaf2').val());
    if(NumberOfGroovesCheck > MaxNumberOfGrooveLeaf2){
        swal('.', "‘Number of Grooves’ is never greater than the value in ‘Decorative Grooves Leaf 2’.");
        $('#NumberOfGroovesLeaf2').val(0);
    }
});

$(document).on('change','#NumberOfGrooveLeaf2',function(e){
    e.preventDefault();
    var no_of_groove_B = parseFloat($(this).val());
    var maxNumberOfGroove_B = parseInt($('#MaxNumberOfGrooveLeaf2').val());
    if(no_of_groove_B > maxNumberOfGroove_B){
        swal('Warning','Number of Grooves Error - “Number of Grooves should not exceed "'+maxNumberOfGroove_B+' Grooves”');
        $('#numberOfGroove').val(0);
    }
})
