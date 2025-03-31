$(document).on('change','#GlazingType',function(e){
    e.preventDefault();
    glazingType();
});

$(document).on('change','#SinglePane',function(e){
    e.preventDefault();
    glazingFilterScreen();
    GlassGlazingFilter();
});

$(document).ready(function () {
    // Event listener for changes in Transom or Mullion quantity fields
    $(document).on('change', '#TransomQuantity, #MullionQuantity', function () {
        updateFields($(this).attr('id'), parseInt($(this).val(), 10));
    });
});

// Reusable function to handle field updates
function updateFields(target, quantity, isstatus = false) {
    const classes = target === 'TransomQuantity' ? ['.T1', '.T2', '.T3'] : ['.M1', '.M2', '.M3'];

    if(isstatus == true){
        // Reset all fields first
        $(classes.join(',')).attr({ required: false, readonly: true });
    }else{
        $(classes.join(',')).val(0).attr({ required: false, readonly: true });
    }

    // Enable fields up to the specified quantity
    for (let i = 0; i < quantity; i++) {
        $(classes[i]).attr({ required: true, readonly: false });
    }
}


$(document).on('change','#GlazingSystem',function(e){
    e.preventDefault();
    GlazingThicknessFilter();
});


function glazingType(){
    if($('#GlazingType').val() == 'Single Pane'){
        $('#IGUInnerPane').attr({'disabled':true,"required":false});
    }else{
        $('#IGUInnerPane').attr({'disabled':false,"required":true});
    }
}

$("#FireRating").change(function(){
    FireRatingChange();
    GlassGlazingFilter();
});

function FireRatingChange(){
    SinglePane();
}

function SinglePane(){
    var SinglePane = $("#SinglePane").val();
    var FireRating = $("#FireRating").val();
    if(FireRating == ''){
        swal('Warning','Somethings went wrong!');
        return false;
    }
    $.ajax({
        url: $("#get-glass-options").text(),
        method: "POST",
        dataType: "json",
        data: { FireRating: FireRating, _token: $("#_token").val() },
        success: function (result) {
            function buildOptions(data, valueElementId, defaultOptionText) {
                let optionsHtml = `<option value="">${defaultOptionText}</option>`;
                let selectedValue = $(valueElementId).data("value") || null;

                data.forEach(item => {
                    let selected = selectedValue === item.GlassType ? "selected" : "";
                    optionsHtml += `<option value="${item.GlassType}" ${selected}>${item.GlassType}</option>`;
                });

                return optionsHtml;
            }

            if (result.status === "ok") {
                let data = result.data;
                $("#SinglePane").empty().append(buildOptions(data, "#SinglePane-value", "Select Single Pane"));
                let dataNFR = result.dataNFR;
                $("#IGUInnerPane").empty().append(buildOptions(dataNFR, "#IGUInnerPane-value", "Select IGU Inner Pane"));
            } else {
                $("#SinglePane").empty().append('<option value="">No Single Pane Found</option>');
                $("#IGUInnerPane").empty().append('<option value="">No IGU Inner Pane Found</option>');
            }

            // let elements = $(this);
            // render(elements);
        },
        error: function (err) {
            console.error("AJAX Error:", err);
        }
    });
}

function GlazingThicknessFilter(id = null,isstatus = false){
    GlazingSystem = (id == null)?$("#GlazingSystem").val():id;

    var GlazingSystemValue = document.getElementById('GlazingSystem-value');
    if(GlazingSystemValue != null  && isstatus == true){
        GlazingSystemValue = $("#GlazingSystem-value").data("value");
        if(GlazingSystemValue != ""){
            GlazingSystem = GlazingSystemValue;
        }
    }
    if(GlazingSystem != ''){
        let FireRating =$("#FireRating").val();

        $.ajax({
            url:  $("#screen-glazing-thickness").html(),
            method:"POST",
            dataType:"Json",
            data:{GlazingSystem:GlazingSystem,FireRating:FireRating,_token:$("#_token").val()},
            success: function(result){
                if(result.status=="ok"){
                    var data = result.data;
                    $("#GlazingSystemThickness").val(data.GlazingThickness);
                    $("#GlazingSystemFixingDetail").val(data.FixingDetails);
                    $("#Transom1Thickness").attr('min',data.TransomThickness);
                    $("#Transom2Thickness").attr('min',data.TransomThickness);
                    $("#Transom3Thickness").attr('min',data.TransomThickness);
                    $("#Mullion1Thickness").attr('min',data.TransomThickness);
                    $("#Mullion2Thickness").attr('min',data.TransomThickness);
                    $("#Mullion3Thickness").attr('min',data.TransomThickness);
                    $("#FrameThickness").attr('min',data.TransomThickness);
                    $("#TransomDepth").attr('min',data.TransomDepth);
                    $("#FrameDepth").attr('min',data.TransomDepth);

                    const thicknessSelectors = [
                        "#Transom1Thickness", "#Transom2Thickness", "#Transom3Thickness",
                        "#Mullion1Thickness", "#Mullion2Thickness", "#Mullion3Thickness",
                        "#FrameThickness", "#TransomDepth", "#FrameDepth"
                    ];

                    thicknessSelectors.forEach(selector => {
                        let identifier = $(selector);
                        if (identifier.length && identifier.val() != 0) {
                            SetBuildOfMaterial(identifier);
                        }
                    });

                }
            }
        });
    }
}

function GlassGlazingFilter(id = null,isstatus = false){
    glassType = (id == null)?$("#SinglePane").val():id;
    $('#IGUOuterPane').val(glassType);

    var glassTypeValue = document.getElementById('SinglePane-value');
    if(glassTypeValue != null  && isstatus == true){
        glassTypeValue = $("#SinglePane-value").data("value");
        if(glassTypeValue != ""){
            glassType = glassTypeValue;
        }
    }
    if(glassType != ''){
        let FireRating =$("#FireRating").val();

        $.ajax({
            url:  $("#screen-glass-glazing").html(),
            method:"POST",
            dataType:"Json",
            data:{glassType:glassType,FireRating:FireRating,_token:$("#_token").val()},
            success: function(result){
                var innerHtml1='';
                if(result.status=="ok"){
                    var innerHtml ='';
                    var data = result.data;
                    var length = result.data.length;
                    var lippingSpecies = result.lippingSpecies;
                    var lippingSpeciesLength =result.lippingSpecies.length;

                    $("#GlazingBeadHeight").attr('min',data.BeadingHeight);
                    $("#GlazingBeadWidth").attr('min',data.BeadingWidth);
                    $("#Acoustic").val(data.DFRating);

                    // var identifier = $("#GlazingBeadHeight"); // Or specify a specific selector if needed
                    // SetBuildOfMaterial(identifier);
                    // identifier = $("#GlazingBeadWidth"); // Or specify a specific selector if needed
                    // SetBuildOfMaterial(identifier);
                    // identifier = $("#TransomThickness"); // Or specify a specific selector if needed
                    // SetBuildOfMaterial(identifier);
                    // identifier = $("#TransomDepth"); // Or specify a specific selector if needed
                    // SetBuildOfMaterial(identifier);

                    if(lippingSpecies!='' && lippingSpeciesLength>0){
                        innerHtml1 += '<div class="container"><div class="row">';
                        var GlazingBeadMaterialValue = document.getElementById('GlazingBeadMaterial-value');
                        var FrameMaterialValue = document.getElementById('FrameMaterial-value');
                        var SubFrameMaterialValue = document.getElementById('SubFrameMaterial-value');
                        var TransomMaterialValue = document.getElementById('TransomMaterial-value');
                        var MullionMaterialValue = document.getElementById('MullionMaterial-value');
                        for(var leep =0; leep<lippingSpeciesLength;leep++){

                            if(GlazingBeadMaterialValue != null){
                                GlazingBeadMaterialValue = $("#GlazingBeadMaterial-value").data("value");
                                if(GlazingBeadMaterialValue != "" && GlazingBeadMaterialValue == lippingSpecies[leep].id){
                                    $("#GlazingBeadMaterial").val(lippingSpecies[leep].SpeciesName);
                                }
                            }

                            if(FrameMaterialValue != null){
                                FrameMaterialValue = $("#FrameMaterial-value").data("value");
                                if(FrameMaterialValue != "" && FrameMaterialValue == lippingSpecies[leep].id){
                                    $("#FrameMaterial").val(lippingSpecies[leep].SpeciesName);
                                }
                            }

                            if(SubFrameMaterialValue != null){
                                SubFrameMaterialValue = $("#SubFrameMaterial-value").data("value");
                                if(SubFrameMaterialValue != "" && SubFrameMaterialValue == lippingSpecies[leep].id){
                                    $("#SubFrameMaterial").val(lippingSpecies[leep].SpeciesName);
                                }
                            }

                            if(TransomMaterialValue != null){
                                TransomMaterialValue = $("#TransomMaterial-value").data("value");
                                if(TransomMaterialValue != "" && TransomMaterialValue == lippingSpecies[leep].id){
                                    $("#TransomMaterial").val(lippingSpecies[leep].SpeciesName);
                                }
                            }

                            if(MullionMaterialValue != null){
                                MullionMaterialValue = $("#MullionMaterial-value").data("value");
                                if(MullionMaterialValue != "" && MullionMaterialValue == lippingSpecies[leep].id){
                                    $("#MullionMaterial").val(lippingSpecies[leep].SpeciesName);
                                }
                            }

                            var filepath = $("input[name='base_url']").val()+"/uploads/Options/"+lippingSpecies[leep].file;

                            var possibleSelectedOptionsArray = JSON.parse(possibleSelectedOptionsJson);

                            if (possibleSelectedOptionsArray.hasOwnProperty("lippingSpecies")) {

                                if (lippingSpecies[leep].hasOwnProperty("SelectedLippingSpeciesCost")) {
                                    var costToShow =  lippingSpecies[leep].SelectedLippingSpeciesCost;

                                } else {
                                    var costToShow =  lippingSpecies[leep].LippingSpeciesCost;
                                }
                            }else{
                                var costToShow =  lippingSpecies[leep].LippingSpeciesCost;
                            }

                            innerHtml1 += '<div class="col-md-2 col-sm-4 col-6 cursor-pointer" onClick="GlazingValueFill('+lippingSpecies[leep].id+',\''+lippingSpecies[leep].SpeciesName+'\',\'#glazingModal\','+costToShow+')">'
                            + '<div class="color_box">'
                            + '<div class="frameMaterialImage"><img width="100%" height="100" src="'+ filepath +'"></div>'
                            + '<h4>'+lippingSpecies[leep].SpeciesName+'</h4>'
                            + '</div></div>';
                        }
                        $("#glazingModalBody").empty().append(innerHtml1);

                        $("#GlazingBeadMaterialIcon").attr("onclick","return  OpenglazingModal('Glazing Bead Species','GlazingBeadMaterial')");
                        $("#GlazingBeadMaterialIcon").addClass("cursor-pointer");

                        $("#SubFrameMaterialIcon").attr("onclick","return  OpenglazingModal('Sub Frame Material','SubFrameMaterial')");
                        $("#SubFrameMaterialIcon").addClass("cursor-pointer");

                        $("#frameMaterialIcon").attr("onclick","return  OpenglazingModal('frame Material','FrameMaterial')");
                        $("#frameMaterialIcon").addClass("cursor-pointer");

                        $("#TransomMaterialIcon").attr("onclick","return  OpenglazingModal('Transom Material','TransomMaterial')");
                        $("#TransomMaterialIcon").addClass("cursor-pointer");

                        $("#MullionMaterialIcon").attr("onclick","return  OpenglazingModal('Mullion Material','MullionMaterial')");
                        $("#MullionMaterialIcon").addClass("cursor-pointer");
                    }

                }
            }
        });
    }
}

function OpenglazingModal(labelname,inputId){
    $('#glazingModalLabel').html(labelname);
    $('#inputId').val(inputId);
    $('#glazingModal').modal('show');
}

function GlazingValueFill(id,value,modalId, cost ="0.00"){
    let inputIdentity = $('.inputIdentity').val();

    const inputId = $('#inputId').val();
    $('input[id='+inputId+']').val(value);
    $('input[name='+inputId+']').val(id);
    $(modalId).modal('hide');

}

$(document).on('change', '#GlazingSystem', function () {
       addValueGlazingHidden();
});

function addValueGlazingHidden(){
    const glazing = $("#GlazingSystem").val();
    $('#SelectedValuehidden').val(glazing)
}

















