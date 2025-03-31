// const { ajax } = require("jquery");

function pageIdentity(){
    return $('#pageIdentity').val();
}
// Main option

    $("#fireRating").change(function(){
        FireRatingChange();
    });

    $("#doorsetType").change(function(){
        DoorSetTypeChange();
    });

    $(".combination_of").change(function(){
        var doorsetType = '';
        var swingType = '';
        var latch = '';
        $( ".combination_of" ).each(function() {
            // if($(this).attr('id')=="doorsetType"){
            //     doorsetType = $(this).val();
            if($(this).attr('id')=="doorsetType"){
                doorsetType = $(this).val();
                if($(this).val()=="SD"){
                    // $("#leafWidth1").attr('readonly',false);
                    // $("#leafWidth2").attr('readonly',true);

                }else if($(this).val()=="DD"){
                    // $("#leafWidth1").attr('readonly',false);
                    // $("#leafWidth2").attr('readonly',false);
                }else{
                    // $("#leafWidth1").attr('readonly',true);
                    // $("#leafWidth2").attr('readonly',true);
                }
            }else if($(this).attr('id')=="swingType"){
                swingType = $(this).val();
            }else{
                latch = $(this).val();
            }
        });
        $('.dsl').html("("+latch+doorsetType+swingType+")");
    });

    $("#swingType").change(function(){
        DoorSetTypeChange();
    });

    $(document).on('change','#latchType',function(e){
        e.preventDefault();
        IntumescentSeals();
    });

// Door Dimensions & Door Leaf
    $(document).on('change','#sOWidth',function(e){
        e.preventDefault();
        IntumescentSeals();
    });
    $(document).on('change','#sOHeight',function(e){
        e.preventDefault();
        IntumescentSeals();
    });
    $(document).on('change','#sODepth',function(e){
        e.preventDefault();
        const soDepthValue2 = parseInt($(this).val());
        const frameDepthValue2 = parseInt($('#frameDepth').val());
        const extLinerValue2 = $('#extLiner').val();
        if(frameDepthValue2 != '' && !isNaN(soDepthValue2) && extLinerValue2 == 'Yes'){
            const extLinerValue2 = soDepthValue2 - frameDepthValue2;
            $('#extLinerValue').val(extLinerValue2);
        }
    });

    $(".forcoreWidth1").change(function(){
        var leafWidth1 = 0;
        var leafWidth2 = 0;
        var leafHeight =0;
        var lipping_thickness = 0;
        var randomkey = 2;
        // var leafWidth1=0;
        var thisvalue = document.getElementsByClassName("forcoreWidth1");
        for (var i = 0; i < thisvalue.length; i++) {
            if(thisvalue[i].name=='leafWidth1'){
                if(thisvalue[i].value==''){
                    leafWidth1 = 0;
                }
                else{
                    leafWidth1 = parseInt(thisvalue[i].value);
                }
            }
            if(thisvalue[i].name=='leafWidth2'){
                if(thisvalue[i].value==''){
                    leafWidth2 = 0;
                }
                else{
                    leafWidth2 = parseInt(thisvalue[i].value);
                }
            }
            if(thisvalue[i].name=='leafHeightNoOP'){
                if(thisvalue[i].value==''){
                    leafHeight = 0;
                }
                else{
                    leafHeight = parseInt(thisvalue[i].value);
                }
            }

            if(thisvalue[i].name=='lippingThickness'){
                if(thisvalue[i].value==''){
                    lipping_thickness = 0;
                }
                else{
                    lipping_thickness = parseInt(thisvalue[i].value);
                }
            }
        }

        var ConfigurableDoorFormula = JSON.parse(ConfigurableDoorFormulaJson);
        var LippingThicknessAdditionalNumberForCoreWidth1 = 1;
        var LippingThicknessAdditionalNumberForCoreWidth2 = 1;
        var LippingThicknessAdditionalNumberForCoreHeight = 1;

        ConfigurableDoorFormula.forEach(function(elem, index) {

            var FormulaAdditionalData = JSON.parse(elem.value);
            if(elem.slug == "core_width_1"){
                LippingThicknessAdditionalNumberForCoreWidth1 = parseFloat((FormulaAdditionalData.lipping_thickness != "")?FormulaAdditionalData.lipping_thickness:1);
            }

            if(elem.slug == "core_width_2"){
                LippingThicknessAdditionalNumberForCoreWidth2 = parseFloat((FormulaAdditionalData.lipping_thickness != "")?FormulaAdditionalData.lipping_thickness:1);
            }

            if(elem.slug == "core_height"){
                LippingThicknessAdditionalNumberForCoreHeight = parseFloat((FormulaAdditionalData.lipping_thickness != "")?FormulaAdditionalData.lipping_thickness:1);
            }
        });

        var calculate = leafWidth1-(LippingThicknessAdditionalNumberForCoreWidth1 * lipping_thickness);
        var calculateCoreWidth2 = leafWidth2-(LippingThicknessAdditionalNumberForCoreWidth2 * lipping_thickness);
        var calculateCoreHeight = leafHeight-(LippingThicknessAdditionalNumberForCoreHeight * lipping_thickness);
        // var calculate = leafWidth1-(randomkey*lipping_thickness);


        let checkdoorsetType = $('#doorsetType').val();
        if(checkdoorsetType == 'DD' || checkdoorsetType == 'leaf_and_a_half'){
            $("#coreWidth2").val(calculateCoreWidth2);
        }
        $("#coreWidth1").val(calculate);
        $("#coreHeight").val(calculateCoreHeight);
    });

    // $(document).on('change','#leafHeightNoOP',function(e){
    //     e.preventDefault();
    //     IntumescentSeals();
    // });

    $("#doorLeafFacing").change(function(){
        DoorLeafFacingChange();
        // if($(this).val()=='Laminate'){
        // $("#decorativeGroves").removeAttr('required')
        // }
    });

    $(document).on('change','#doorLeafFacingValue',function(e){
        e.preventDefault();
        const doorLeafFacingValue_C = $(this).val();
        if(doorLeafFacingValue_C == 'CS_acrovyn'){
            IntumescentSeals();
        }
    });

    $("#vP1Height1").change(function(){

        var num = parseInt($("#visionPanelQuantity").val());
        var isEqualSize = $("#AreVPsEqualSizes").val();

        if(isEqualSize == 'Yes'){

            for(var j=2;j<=num;j++){
                $("#vP1Height"+j).val($(this).val());
            }
        }else{
            for(var j=(num+1);j<=5;j++){
                $("#vP1Height"+j).val('');
            }
        }

    });

    $("#vP2Height1").change(function(){

        var num = parseInt($("#visionPanelQuantityforLeaf2").val());
        var isEqualSize = $("#AreVPsEqualSizesForLeaf2").val();

        if(isEqualSize == 'Yes'){

            for(var j=2;j<=num;j++){
                $("#vP2Height"+j).val($(this).val());
            }
        }else{
            for(var j=(num+1);j<=5;j++){
                $("#vP2Height"+j).val('');
            }
        }

    });

    $("#decorativeGroves").change(function(){
        if($(this).val()=="Yes"){
            $("#grooveLocation").attr({'disabled':false,"required":true});
            $("#grooveWidth").attr({'readonly':false,"required":true}).val('0');
            $("#grooveDepth").attr({'disabled':false,"required":true}).val('0');

            if($("#grooveLocation").val() == "Vertical_&_Horizontal"){
                $("#numberOfGroove").attr({'readonly':true,"required":false}).val('0');
                $("#maxNumberOfGroove").attr({'readonly':true,"required":false}).val('0');
                $("#numberOfVerticalGroove").attr({'readonly':false,"required":true}).val('0');
                $("#numberOfHorizontalGroove").attr({'readonly':false,"required":true}).val('0');
            }else{
                $("#numberOfGroove").attr({'readonly':false,"required":true}).val('0');
                $("#maxNumberOfGroove").attr({'readonly':true,"required":true}).val('0');
                $("#numberOfVerticalGroove").attr({'readonly':true,"required":false}).val('0');
                $("#numberOfHorizontalGroove").attr({'readonly':true,"required":false}).val('0');
            }
        } else {
            $("#grooveLocation").attr({'disabled':true,"required":false}).val('');
            $("#grooveWidth").attr({'readonly':true,"required":false}).val('0');
            $("#grooveDepth").attr({'disabled':true,"required":false}).val('0');
            $("#numberOfGroove").attr({'readonly':true,"required":false}).val('0');
            $("#maxNumberOfGroove").attr({'readonly':true,"required":false}).val('0');
            $("#numberOfVerticalGroove").attr({'readonly':true,"required":false}).val('0');
            $("#numberOfHorizontalGroove").attr({'readonly':true,"required":false}).val('0');
        }
    });

    $("#grooveLocation").change(function(){
        var grooveLocation =  $(this).val();

        if(grooveLocation == "Vertical_&_Horizontal"){
            $("#numberOfGroove").attr({'readonly':true,"required":false}).val('0');
            $("#maxNumberOfGroove").attr({'readonly':true,"required":false}).val('0');
            $("#numberOfVerticalGroove").attr({'readonly':false,"required":true}).val('0');
            $("#numberOfHorizontalGroove").attr({'readonly':false,"required":true}).val('0');
        }else{
            $("#numberOfGroove").attr({'readonly':false,"required":true}).val('0');

            $("#maxNumberOfGroove").attr({'readonly':true,"required":true}).val('0');

            if(grooveLocation == "Vertical"){

                var leafWidth1 = $("#leafWidth1").val();
                leafWidth1 = leafWidth1!=''?(parseFloat(leafWidth1)):0;
                $("#maxNumberOfGroove").val(Math.round((leafWidth1/100)));

            }else if(grooveLocation == "Horizontal"){

                var leafHeightNoOP = $("#leafHeightNoOP").val();
                leafHeightNoOP = leafHeightNoOP !='' ?parseFloat(leafHeightNoOP):0;
                $("#maxNumberOfGroove").val(Math.round((leafHeightNoOP/100)));

            }

            $("#numberOfVerticalGroove").attr({'readonly':true,"required":false}).val('0');
            $("#numberOfHorizontalGroove").attr({'readonly':true,"required":false}).val('0');
        }
    });

    $(document).on('change',"#maxNumberOfGroove", function(){
        var maxNumberOfGroove =  Math.round($(this).val());
        $(this).val(maxNumberOfGroove);
        $("#numberOfGroove").attr('max', maxNumberOfGroove);
        $("label[for='numberOfGroove']").text("Number of Grooves (Max "+maxNumberOfGroove+")");
        var numberOfGrooveCheck = parseFloat($('#numberOfGroove').val());
        if(numberOfGrooveCheck > maxNumberOfGroove){
            swal('.','‘Number of Grooves’ is never greater than the value in ‘Maximum Number of Groove’.');
            $('#numberOfGroove').val(0);
        }
    });

    // checking ‘Number of Grooves’ is never greater than the value in ‘Maximum Number of Groove’.
    // Door Dimensions & Door Leaf (Fields)
    $(document).on('change','#numberOfGroove',function(e){
        e.preventDefault();
        var no_of_groove_B = parseFloat($(this).val());
        var maxNumberOfGroove_B = parseInt($('#maxNumberOfGroove').val());
        if(no_of_groove_B > maxNumberOfGroove_B){
            swal('Warning','Number of Grooves Error - “Number of Grooves should not exceed "'+maxNumberOfGroove_B+' Grooves”');
            $('#numberOfGroove').val(0);
        }
    })

// Vision Panel

    $("#leaf1VisionPanel").change(function(){
        if($(this).val()=="Yes"){
            $("#visionPanelQuantity").attr('disabled',false);
            $("#visionPanelQuantity").attr('required',true);
            $("#leaf1VisionPanelShape").attr('readonly',false);
            $("#leaf1VisionPanelShape").attr('required',true);
            //$("#AreVPsEqualSizes").attr('required',true);
            $("#AreVPsEqualSizes").attr('disabled',false);
            $("#vP1Width").attr('readonly',true);
            $("#vP1Height1").attr('readonly',true);
            $("#vP1Width").attr('required',true);
            $("#vP1Height1").attr('required',true);
            $("#distanceFromTopOfDoor").attr('readonly',false);
            $("#distanceFromTheEdgeOfDoor").attr({'required':true,'readonly':false});
            $('#glazingSystems').attr('required',true);
            $('#lazingIntegrityOrInsulationIntegrity').attr('required',true);
            $('#glassType').attr('required',true);
            $('#glassThickness').attr('required',true);
            $('#glazingBeads').attr('required',true);
            $('#glazingBeadsThickness').attr('required',true);
            $('#glazingBeadsWidth').attr('required',true);
            $('#glazingBeadsHeight').attr('required',true);
            $('#glazingBeadsFixingDetail').attr('required',true);
            $('#glazingBeadSpecies').attr('required',true);
            // $("#AreVPsEqualSizes").removeAttr('disabled')

        } else {
            $("#visionPanelQuantity").val('').attr({'disabled':true,'required':false});
            $("#leaf1VisionPanelShape").val('').attr({'readonly':true,'required':false});
            $("#AreVPsEqualSizes").attr('disabled',true);
            $("#vP1Width").attr({'required':false,'readonly':true});
            $("#vP1Height1").attr({'required':false,'readonly':true});
            $("#distanceFromTopOfDoor").attr('readonly',true);
            $("#distanceFromTheEdgeOfDoor").attr({'readonly':true , 'required':false});
            $("#distanceBetweenVPs").attr({'required':false,'readonly':true});
            for(var i=2;i<=5;i++){
                $("#vP1Height"+i).attr({'required':false,'readonly':true}).val("");
            }
            $('#leaf1VisionPanelShape').attr({'readonly':true,'required':false}).val("");
            $('#glazingSystems').attr('required',false);
            $('#lazingIntegrityOrInsulationIntegrity').attr('required',false);
            $('#glassType').attr('required',false);
            $('#glazingBeads').attr('required',false);
            $('#glazingBeadsThickness').attr('required',false);
            $('#glazingBeadsWidth').attr('required',false);
            $('#glazingBeadsHeight').attr('required',false);
            $('#glazingBeadsFixingDetail').attr('required',false);
            $('#glazingBeadSpecies').attr('required',false);
            $('#glassThickness').attr('required',false);
        }
    });

    $("#visionPanelQuantity").change(function(){
        if(parseInt($(this).val()) > 1){
            $("#distanceBetweenVPs").attr('readonly',false);
            $("#distanceBetweenVPs").attr('required',true);
            $("#vP1Width").attr('readonly',false);
            $("#vP1Width").attr('required',true);
            $("#distanceFromTopOfDoor").attr({'required':true,'readonly':false});
            var num =parseInt($(this).val());
            var isEqualSize = $("#AreVPsEqualSizes").val();
            $("#vP1Height1").attr('readonly',false);
            $("#vP1Height1").attr('required',true);
            $("#AreVPsEqualSizes").attr({'required':true,'readonly':false});
            // var previousNumber =0;
            if(isEqualSize == 'Yes'){
                for(var i=2;i<=5;i++){
                    $("#vP1Height"+i).val("").attr({'readonly':true,'required':false});
                }

                for(var j=2;j<=num;j++){
                    $("#vP1Height"+j).val($("#vP1Height1").val());
                }
            }else{
                for(var i=1;i<=num;i++){
                    $("#vP1Height"+i).attr('readonly',false);
                    $("#vP1Height"+i).attr('required',true);
                }
                for(var j=(num+1);j<=5;j++){
                    $("#vP1Height"+j).val('').attr({'readonly':true,'required':false});
                }
            }
            $("#distanceFromTopOfDoor").attr({'required':true,'readonly':false});
            $("#AreVPsEqualSizes").attr('disabled',false);
        } else {

            $("#AreVPsEqualSizes").attr({'disabled':true,'required':false,'readonly':true}).val('');
            $("#distanceBetweenVPs").attr('required',false);
            $("#distanceBetweenVPs").attr('readonly',true);
            // $("#vP1Width").val('');
            $("#vP1Width").attr('readonly',false);
            $("#vP1Width").attr('required',true);
            $("#vP1Height1").attr('required',true);
            $("#vP1Height1").attr('readonly',false);
            $("#distanceBetweenVPs").val(0);

            for(var i=2;i<=5;i++){
                $("#vP1Height"+i).val('').attr({'readonly':true,'required':false});
            }
        }
    });

    $("#AreVPsEqualSizes").change(function(){
        $("#vP1Width").attr('readonly',false);
        $("#vP1Width").attr('required',true);
        $("#distanceFromTopOfDoor").attr({'required':true,'readonly':false});
        var VisionPanelQuantity = parseInt($("#visionPanelQuantity").val());
        if($(this).val()=="Yes"){
            if($("#fireRating").val()!='NFR'){
                $("#distanceBetweenVPs").attr('min',320)
                $("#distanceBetweenVPs").attr('max',380)
            }
            $("#distanceFromTopOfDoor").attr('min',140)
            $("#vP1Height1").attr('readonly',false);
            $("#vP1Height1").attr('required',true);

            for(var i=2;i<=5;i++){
                $("#vP1Height"+i).attr({'readonly':true,'required':false});
            }

            for(var j=2;j<=VisionPanelQuantity;j++){
                $("#vP1Height"+j).val($("#vP1Height1").val());
            }
        } else {
            $("#distanceBetweenVPs").removeAttr('readonly','readonly')
            if(VisionPanelQuantity > 1){
                for(var j=1;j<=VisionPanelQuantity;j++){
                    $("#vP1Height"+j).attr({'readonly':false,'required':true});
                }
                for(var i=parseInt(VisionPanelQuantity)+1;i<=5;i++){
                    $("#vP1Height"+i).val('').attr({'readonly':true,'required':false});
                }
            } else {
                for(var i=2;i<=5;i++){
                    $("#vP1Height"+i).attr({'readonly':true,'required':false});
                }
            }
        }
    });

    $("#leaf2VisionPanel").change(function(){
        if($(this).val()=="Yes"){
            $("#vpSameAsLeaf1").attr({'disabled':false,'required':true});
            $("#visionPanelQuantityforLeaf2").attr({'disabled':false,'required':true});
            // $("#AreVPsEqualSizesForLeaf2").attr({'disabled':false,'required':true});
            $("#distanceFromTopOfDoorforLeaf2").attr({'required':true,'readonly':false});
            $("#distanceFromTheEdgeOfDoorforLeaf2").attr({'required':true,'readonly':false});
            // $("#vP2Width").attr({'readonly':false,'required':true}).val();
        } else {
            $("#vpSameAsLeaf1").attr({'disabled':true,'required':false}).val('');
            $("#visionPanelQuantityforLeaf2").attr({'disabled':true,'required':false}).val('');
            $("#AreVPsEqualSizesForLeaf2").attr({'disabled':true,'required':false}).val('');
            $("#distanceFromTopOfDoorforLeaf2").attr({'required':false,'readonly':true});
            $("#distanceFromTheEdgeOfDoorforLeaf2").attr({'required':false,'readonly':true});

            $("#distanceBetweenVPsforLeaf2").attr({'readonly':true,'required':false}).val('');

            $("#vP2Width").attr({'readonly':true,'required':false}).val("");
            for(var index=1;index<=5;index++){
                $("#vP2Height"+index).attr({'readonly':true,'required':false}).val('');
            }
        }
    });

    $("#vpSameAsLeaf1").change(function(){
        if($(this).val()=="Yes"){
            $("#visionPanelQuantityforLeaf2").attr({'disabled':true,'required':false}).val($("#visionPanelQuantity").val());
            $("#AreVPsEqualSizesForLeaf2").attr({'disabled':true,'required':false}).val($("#AreVPsEqualSizes").val());

            $("#distanceFromTopOfDoorforLeaf2").attr({'readonly':true,'required':false}).val($("#distanceFromTopOfDoor").val());
            $("#distanceFromTheEdgeOfDoorforLeaf2").attr({'readonly':true,'required':false}).val($("#distanceFromTheEdgeOfDoor").val());
            $("#distanceBetweenVPsforLeaf2").attr({'readonly':true,'required':false}).val($("#distanceBetweenVPs").val());

            $("#vP2Width").attr({'readonly':true,'required':false}).val($("#vP1Width").val());
            for(var index=1;index<=5;index++){
                $("#vP2Height"+index).attr({'readonly':true,'required':false}).val($("#vP1Height"+index).val());
            }
        } else {
            $("#visionPanelQuantityforLeaf2").attr({'disabled':false,'required':true}).val('');
            // $("#AreVPsEqualSizesForLeaf2").attr({'disabled':false,'required':true}).val('');

            $("#distanceFromTopOfDoorforLeaf2").attr({'readonly':false,'required':true});
            $("#distanceFromTheEdgeOfDoorforLeaf2").attr({'readonly':false,'required':true});
            // $("#distanceBetweenVPsforLeaf2").attr({'readonly':false,'required':true}).val('');

            $("#vP2Width").attr({'readonly':false,'required':true}).val('');
            // for(var index=1;index<=5;index++){
            //     $("#vP2Height"+index).attr({'readonly':false,'required':true}).val('');
            // }
        }
    });

    $("#visionPanelQuantityforLeaf2").change(function(){
        if(parseInt($(this).val())>1){

            $("#AreVPsEqualSizesForLeaf2").attr({'disabled':false,'required':true});

            $("#distanceBetweenVPsforLeaf2").attr('readonly',false);
            $("#distanceBetweenVPsforLeaf2").attr('required',true);
            $("#vP2Width").attr('readonly',false);
            $("#vP2Width").attr('required',true);
            var num =parseInt($(this).val());
            var isEqualSize = $("#AreVPsEqualSizesForLeaf2").val();
            $("#vP2Height1").attr('readonly',false);
            $("#vP2Height1").attr('required',true);
            // var previousNumber =0;
            if(isEqualSize == 'Yes'){
                for(var i=2;i<=5;i++){
                    $("#vP2Height"+i).val('').attr({'readonly':true,'required':false});
                }
                for(var j=2;j<=num;j++){
                    $("#vP2Height"+j).val($("#vP2Height1").val());
                }
            }else{
                for(var i=1;i<=num;i++){
                    $("#vP2Height"+i).attr('readonly',false);
                    $("#vP2Height"+i).attr('required',true);
                }
                for(var j=(num+1);j<=5;j++){
                    $("#vP2Height"+j).val('').attr({'readonly':true,'required':false});
                }
            }

        } else {
            $("#AreVPsEqualSizesForLeaf2").attr({'disabled':true,'required':false});
            $("#distanceBetweenVPsforLeaf2").attr({'readonly':true,'required':false});
            // $("#vP1Width").val('');
            $("#vP2Width").attr('readonly',false);
            $("#vP2Width").attr('required',true);
            $("#vP2Height1").attr('required',true);
            $("#vP2Height1").attr('readonly',false);
            $("#distanceBetweenVPsforLeaf2").val(80);

            for(var i=2;i<=5;i++){
                $("#vP2Height"+i).val('').attr({'readonly':true,'required':false});
            }
        }
    });

    $("#AreVPsEqualSizesForLeaf2").change(function(){
        $("#vP2Width").attr('readonly',false);
        $("#vP2Width").attr('required',true);

        var VisionPanelQuantity = parseInt($("#visionPanelQuantityforLeaf2").val());
        if($(this).val()=="Yes"){
            $("#vP2Height1").attr('readonly',false);
            $("#vP2Height1").attr('required',true);

            for(var i=2;i<=5;i++){
                $("#vP2Height"+i).attr({'readonly':true,'required':false});
            }

            for(var j=2;j<=VisionPanelQuantity;j++){
                $("#vP2Height"+j).val($("#vP2Height1").val());
            }
        } else {

            if(VisionPanelQuantity > 1){
                for(var j=1;j<=VisionPanelQuantity;j++){
                    $("#vP2Height"+j).attr({'readonly':false,'required':true});
                }
                for(var i=parseInt(VisionPanelQuantity)+1;i<=5;i++){
                    $("#vP2Height"+i).val('').attr({'readonly':true,'required':false});
                }
            } else {
                for(var i=2;i<=5;i++){
                    $("#vP2Height"+i).attr({'readonly':true,'required':false});
                }
            }
        }
    });

    $("#lazingIntegrityOrInsulationIntegrity, #fireRating").change(function(){
        glassTypeFilter(true);
        glazingSystemFIlter($("#fireRating").val());
    });

    $("#glassType").change(function(){
        GlassTypeChange();
    });

    //getting glazing thikness filter using glazing systems
    $("#glazingSystems").change(function(){
        GlazingSystemsChange();
    });

// Frame

    //dimention value using frame type
    $("#frameType").change(function(){
        if($(this).val()=="Plant_on_Stop"){
            $("#plantonStopWidth").attr('min','32');
            $("#plantonStopHeight").attr('min','12');
            $("#plantonStopWidth").attr({'readonly':false,'required':true});
            $("#plantonStopHeight").attr({'readonly':false,'required':true});
            $("#frameTypeDimensions").val('').attr('readonly',false);
        } else {
            $("#plantonStopWidth").val(0);
            $("#plantonStopHeight").val(0);
            $("#frameTypeDimensions").val(0).attr('readonly',true);
            $("#plantonStopWidth").attr({'readonly':true,'required':false});
            $("#plantonStopHeight").attr({'readonly':true,'required':false});
        }
    });

    $(document).on('change','#frameDepth',function(e){
        e.preventDefault();
        const frameDepthValue = parseInt($(this).val());
        const soDepthValue = parseInt($('#sODepth').val());
        const extLinerValue = $('#extLiner').val();
        if(frameDepthValue != '' && !isNaN(soDepthValue) && extLinerValue == 'Yes'){
            const extLinerValue = soDepthValue - frameDepthValue;
            $('#extLinerValue').val(extLinerValue);
        }
    })

    $("#frameFinish").on("change",function(){
        FrameFinishChange(true ,  'framefinish');
    });

    $("#extLiner").on("change",function(){
        var extLiner = $("#extLiner").val();
        if(extLiner == "Yes"){
            $("#extLinerSize").attr('readonly',false);
            $("#extLinerThickness").attr({'readonly':false});
            //$("#extLinerFinish").attr({'readonly':false});

            var sODepth = parseInt($("#sODepth").val());
            var frameDepth = parseInt($("#frameDepth").val());
            if(!isNaN(sODepth) && !isNaN(frameDepth))
            {
                $("#extLinerValue").val(parseInt(sODepth) - parseInt(frameDepth));
            }
        } else {
            $("#extLinerSize").attr({'readonly':true});
            $("#extLinerThickness").attr({'readonly':true});
            //$("#extLinerFinish").attr({'readonly':true});
        }
    });

    $(document).on('change','#extLinerThickness',function(e){
        e.preventDefault();
        const extLinerThicknessValue = parseFloat($(this).val());
        const frameThicknessValue = parseInt($('#frameThickness').val());

        if(extLinerThicknessValue > frameThicknessValue){
            swal('.','Ext-Liner Thickness value that should always be less than the frame thickness value');
            $('#extLinerThickness').val(0);
        }
    })

    // if Ironmongery Set is Yes then Enabled Select Ironmongery Set
    // In other words when id `ironmongerySet` is Yes it enabled id `IronmongeryID`
    $(document).on('change','#ironmongerySet',function(e){
        e.preventDefault();
        const ironmongerySetValue = $(this).val();
        if(ironmongerySetValue == 'Yes'){
            $('#IronmongeryID').attr({'disabled':false,'required':true})
        } else {
            $('#IronmongeryID').attr({'disabled':true,'required':false})
        }
    });

// Over Panel Section

    $(document).on('change','#overpanel',function(e){
        e.preventDefault();
        IntumescentSeals();
    });

    // we add the field ‘Glass Integrity’ before ‘Glass Type’ in the over panel section.
    $("#opGlassIntegrity").change(function(){
        $("#opGlassType").attr('disabled',false);
        opGlassTypeFilter();
        // glazingSystemFIlter($("#fireRating").val());
    });

    $("#opGlassType").change(function(){
        if($("#sideLight1").val()=="Yes"){
            $("#sideLight1GlassType").val($(this).val());
        }
    });

    $("#opGlazingBeads").change(function(){
        if($("#sideLight1").val()=="Yes"){
            $("#SideLight1BeadingType").val($(this).val());
        }
    });

    $("#opGlazingBeadSpecies").change(function(){
        if($("#sideLight1").val()=="Yes"){
            $("#SideLight1GlazingBeadSpecies").val($(this).val());
        }
    });

// Side Light

    $("#sideLight1").change(function(){
        if($(this).val()=="Yes"){

            if($("#sideLight2").val()=="Yes"){
                $("#copyOfSideLite1").attr({ 'disabled': false, "readonly" : false, "required": true });
            }

            if($("#overpanel").val()=="Yes"){
                $("#sideLight1GlassType").attr({ 'disabled': false, "required": true }).val($("#opGlassType").val());
                $("#SideLight1BeadingType").attr({ 'disabled': false, "required": true }).val($("#opGlazingBeads").val());
                $("#SideLight1GlazingBeadSpecies").attr({ 'disabled': false, "required": true }).val($("#opGlazingBeadSpecies").val());

            } else {
                $("#sideLight1GlassType").attr({ 'disabled': false, "required": true }).val();
                $("#SideLight1BeadingType").attr({ 'disabled': false, "required": true }).val();
                $("#SideLight1GlazingBeadSpecies").attr({ 'disabled': false, "required": true }).val();
            }

            $("#SL1Width").attr({ 'readonly': false, "required": true });
            $("#SL1Height").attr({ 'readonly': true, "required": true }).val($("#leafHeightNoOP").val());;
            $("#SL1Depth").attr({ 'readonly': false, "required": true });
            $("#SL1Transom").attr({ 'disabled': false, "required": true });
        } else {

            if($("#sideLight2").val()=="Yes"){
                $("#copyOfSideLite1").attr({'disabled': true,"readonly":true }).val("No");

                $("#sideLight2GlassType").attr({ 'disabled': false, "required": true }).val('');
                $("#SideLight2BeadingType").attr({ 'disabled': false, "required": true }).val('');
                $("#SideLight2GlazingBeadSpecies").attr({ 'disabled': false, "required": true }).val('');
                $("#SL2Width").attr({ 'disabled': false, 'readonly': false, "required": true }).val("");
                $("#SL2Height").attr({ 'disabled': false, 'readonly': true, "required": true }).val($("#leafHeightNoOP").val());
                $("#SL2Depth").attr({ 'disabled': false, 'readonly': false, "required": true }).val("");
                $("#SL2Transom").attr({ 'disabled': false, "required": true }).val('');
            }

            $("#sideLight1GlassType").attr({ 'disabled': true, "required": false }).val('');
            $("#SideLight1BeadingType").attr({ 'disabled': true, "required": false }).val('');
            $("#SideLight1GlazingBeadSpecies").attr({ 'disabled': true, "required": false }).val('');
            $("#SL1Width").attr({ 'readonly': true, "required": false }).val('');
            $("#SL1Height").attr({ 'readonly': true, "required": false }).val("");
            $("#SL1Depth").attr({ 'readonly': true, "required": false }).val('');
            $("#SL1Transom").attr({ 'disabled': true, "required": false }).val('');
        }
    });

    $("#sideLight2").change(function(){
        if($(this).val()=="Yes"){
            if($("#sideLight1").val()=="Yes"){
                $("#copyOfSideLite1").attr({ 'disabled': false, "required": true });
            }else{
                $("#copyOfSideLite1").attr({'disabled': true,"readonly":true }).val("No");

                $("#sideLight2GlassType").attr({ 'disabled': false, "required": true }).val('');
                $("#SideLight2BeadingType").attr({ 'disabled': false, "required": true }).val('');
                $("#SideLight2GlazingBeadSpecies").attr({ 'disabled': false, "required": true }).val('');
                $("#SL2Width").attr({ 'disabled': false, 'readonly': false, "required": true }).val("");
                $("#SL2Height").attr({ 'disabled': false, 'readonly': true, "required": true }).val($("#leafHeightNoOP").val());
                $("#SL2Depth").attr({ 'disabled': false, 'readonly': false, "required": true }).val("");
                $("#SL2Transom").attr({ 'disabled': false, "required": true }).val('');
            }

        } else {
            $("#sideLight2GlassType").attr({ 'disabled': true, "required": false }).val('');
            $("#SideLight2BeadingType").attr({ 'disabled': true, "required": false }).val('');
            $("#SideLight2GlazingBeadSpecies").attr({ 'disabled': true, "required": false }).val('');
            $("#copyOfSideLite1").attr({ 'disabled': true, "required": false }).val('');
            $("#SL2Width").attr({ 'readonly': true, "required": false }).val('');
            $("#SL2Height").attr({ 'readonly': true, "required": false }).val("");
            $("#SL2Depth").attr({ 'readonly': true, "required": false }).val('');
            $("#SL2Transom").attr({ 'disabled': true, "required": false }).val('');
        }
    });

    $("#copyOfSideLite1").change(function(){
        if($(this).val()=="Yes"){
            $("#sideLight2GlassType").attr({ 'disabled': true, "required": true }).val($("#sideLight1GlassType").val());
            $("#SideLight2BeadingType").attr({ 'disabled': true, "required": true }).val($("#SideLight1BeadingType").val());
            $("#SideLight2GlazingBeadSpecies").attr({ 'disabled': true, "required": true }).val($("#SideLight1GlazingBeadSpecies").val());
            $("input[name='SideLight2GlazingBeadSpecies']").val($("input[name='SideLight1GlazingBeadSpecies']").val());
            $("#SL2Width").attr({ 'readonly': true, "required": true }).val($("#SL1Width").val());
            $("#SL2Height").attr({ 'readonly': true, "required": true }).val($("#leafHeightNoOP").val());
            $("#SL2Depth").attr({ 'readonly': true, "required": true }).val($("#SL1Depth").val());
            $("#SL2Transom").attr({ 'disabled': true, "required": true }).val($("#SL1Transom").val());
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
            }else{
                $("#sideLight2GlassType").attr({ 'disabled': true, "required": false }).val('');
                $("#SideLight2BeadingType").attr({ 'disabled': true, "required": false }).val('');
                $("#SideLight2GlazingBeadSpecies").attr({ 'disabled': true, "required": false }).val('');
                $("input[name='SideLight2GlazingBeadSpecies']").val('');
                $("#SL2Width").attr({ 'readonly': true, "required": false }).val('');
                $("#SL2Height").attr({ 'readonly': true, "required": false }).val('');
                $("#SL2Depth").attr({ 'readonly': true, "required": false }).val('');
                $("#SL2Transom").attr({ 'disabled': true, "required": false }).val('');
            }
        }
    });

// Lipping And Intumescent

    $("#meetingStyle").change(function(){
        if($(this).val()=="Scalloped"){
            $("#scallopedLippingThickness").attr({'disabled':false});
            $("#flatLippingThickness").attr({'disabled':true}).val('');
            $("#rebatedLippingThickness").attr({'disabled':true}).val('');
        }else if($(this).val()=="Rebated"){
            $("#scallopedLippingThickness").attr({'disabled':true}).val('');
            $("#flatLippingThickness").attr({'disabled':true}).val('');
            $("#rebatedLippingThickness").attr({'disabled':false});
        }else if($(this).val()=="Flat"){
            $("#scallopedLippingThickness").attr({'disabled':true}).val('');
            $("#flatLippingThickness").attr({'disabled':false});
            $("#rebatedLippingThickness").attr({'disabled':true}).val('');
        } else{
            $("#scallopedLippingThickness").attr({'disabled':true}).val('');
            $("#flatLippingThickness").attr({'disabled':true}).val('');
            $("#rebatedLippingThickness").attr({'disabled':true}).val('');
        }
    });

    // Accoustics
    $("#accoustics").on("change",function(){
        var value = $("#accoustics").val();
        if(value=="Yes"){
            $("#rWdBRating").val('').attr({'readonly':false,'required':true});
            // $("#jambs").val('').attr({'readonly':false,'required':true});
            // $("#head").val('').attr({'readonly':false,'required':true});
            // $("#threshold_seal").val('').attr({'readonly':false,'required':true});
            // $("#accoustics_seal").val('').attr({'readonly':false,'required':true});
            // $("#meeting_stiles").val('').attr({'readonly':false,'required':true});
            // $("#glass_type").val('').attr({'readonly':false,'required':true});


            $("#perimeterSeal1").addClass('bg-white');
            $('#perimeterSeal1Icon').attr('onclick', "return openAccousticsModal('perimeterSeal1','Perimeter Seal 1','Perimeter_Seal_1')");

            $("#perimeterSeal2").addClass('bg-white');
            $('#perimeterSeal2Icon').attr('onclick', "return openAccousticsModal('perimeterSeal2','perimeter Seal 2','Perimeter_Seal_2')");

            $("#thresholdSeal1").addClass('bg-white');
            $('#thresholdSeal1Icon').attr('onclick', "return openAccousticsModal('thresholdSeal1','threshold Seal 1','Threshold_Seal_1')");

            $("#thresholdSeal2").addClass('bg-white');
            $('#thresholdSeal2Icon').attr('onclick', "return openAccousticsModal('thresholdSeal2','threshold Seal 1','Threshold_Seal_2')");


            let set = $('#doorsetType').val();
            if(set == "SD"){
                $("#accousticsmeetingStiles").removeClass('bg-white');
                $("#accousticsmeetingStiles").val('');
                $('#accousticsmeetingStilesIcon').attr('onclick','');
                $('input[name="accousticsmeetingStiles"]').val('');

                $('#accousticsmeetingStiles').css({'disaplay':'none'});
            } else {
                $("#accousticsmeetingStiles").addClass('bg-white');
                $('#accousticsmeetingStilesIcon').attr('onclick', "return openAccousticsModal('accousticsmeetingStiles','MeetingStiles' ,'Meeting_Stiles')");
            }
        } else {
            $("#rWdBRating").val('').attr({'readonly':true,'required':false});
            // $("#jambs").val('').attr({'readonly':true,'required':false});
            // $("#head").val('').attr({'readonly':true,'required':false});
            // $("#threshold_seal").val('').attr({'readonly':true,'required':false});
            // $("#accoustics_seal").val('').attr({'readonly':true,'required':false});
            // $("#meeting_stiles").val('').attr({'readonly':true,'required':false});
            // $("#glass_type").val('').attr({'readonly':true,'required':false});

            $("#perimeterSeal1").removeClass('bg-white');
            $("#perimeterSeal1").val('');
            $('#perimeterSeal1Icon').attr('onclick','');
            $('input[name="perimeterSeal1"]').val('');

            $("#perimeterSeal2").removeClass('bg-white');
            $("#perimeterSeal2").val('');
            $('#perimeterSeal2Icon').attr('onclick','');
            $('input[name="perimeterSeal2"]').val('');

            $("#thresholdSeal1").removeClass('bg-white');
            $("#thresholdSeal1").val('');
            $('#thresholdSeal1Icon').attr('onclick','');
            $('input[name="thresholdSeal1"]').val('');

            $("#thresholdSeal2").removeClass('bg-white');
            $("#thresholdSeal2").val('');
            $('#thresholdSeal2Icon').attr('onclick','');
            $('input[name="thresholdSeal2"]').val('');

            $("#accousticsmeetingStiles").removeClass('bg-white');
            $("#accousticsmeetingStiles").val('');
            $('#accousticsmeetingStilesIcon').attr('onclick','');
            $('input[name="accousticsmeetingStiles"]').val('');
        }
    });

    // Architrave
    $("#Architrave").change(function(){
        let ArcFin = $('#architraveFinish').val();
        if($(this).val()=="Yes"){
            $("#architraveMaterial").attr({'readonly':true,'required':true}).val('');
            $("#architraveMaterial").addClass('bg-white');
            $('#architraveMaterialIcon').attr('onclick', "return ArchitraveMaterial()");
            $("#architraveType").attr({'disabled':false,'required':true}).val('');
            $("#architraveWidth").attr({'readonly':false,'required':true}).val('');
            $("#architraveDepth").attr({'readonly':false,'required':true}).val('');
            $("#architraveFinish").attr({'disabled':false,'required':true}).val('');
            $("#architraveSetQty").attr({'disabled':false,'required':true}).val('');
            $("#architraveHeight").attr({'readonly':false,'required':true}).val('');
            $("#architraveFinishcolor").attr('readonly',true).val('');
            if(ArcFin == 'Painted_Finish'){
                $("#architraveFinishcolor").addClass('bg-white');
                $("#architraveFinishcolorIcon").attr('onclick', "return ArchitraveFinishColor()");
            }
        } else {
            $("#architraveMaterial").attr({'readonly':true,'required':false}).val('');
            $("#architraveMaterial").removeClass('bg-white');
            $("#architraveMaterial").val('');
            $('#architraveMaterialIcon').attr('onclick','');
            $('input[name="architraveMaterial"]').val('');
            $("#architraveType").attr({'disabled':true,'required':false}).val('');
            $("#architraveWidth").attr({'readonly':true,'required':false}).val('');
            $("#architraveDepth").attr({'readonly':true,'required':false}).val('');
            $("#architraveFinish").attr({'disabled':true,'required':false}).val('');
            $("#architraveSetQty").attr({'disabled':true,'required':false}).val('');
            $("#architraveHeight").attr({'readonly':true,'required':false}).val('');
            $("#architraveFinishcolor").attr('disabled',true).val('');
            $("#architraveFinishcolor").removeClass('bg-white');
            $('#architraveFinishcolorIcon').attr('onclick','');
            $('input[name="architraveFinishcolor"]').val('');
        }
    });

    $("#architraveFinish").change(function(){
        FrameFinishChange(true , 'architraveFinish');
    });
    $("#architraveFinishcolorIcon").on('click',function(){
        FrameFinishChange(true , 'architraveFinish');
    })


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // Page onload
    $(function(){
        var formValue = $("#fieldsValue").html();
        if(formValue!=''){
            setTimeout(function(){
                var parseFormValue = JSON.parse(formValue);
                $.each(parseFormValue, function(i, FormValue) {
                    if(i>0){
                        $("#"+FormValue.name).val(FormValue.value);
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


        if(GrooveLocationValue != null){
            GrooveLocationValue = $("#GrooveLocation-value").data("value");
            if(GrooveLocationValue == ""){
                $("#grooveLocation").attr('disabled',true).val('');
            }
        }else{
            $("#grooveLocation").attr('disabled',true).val('');
        }

        if(GrooveWidthValue != null){
            GrooveWidthValue = $("#GrooveWidth-value").data("value");
            if(GrooveWidthValue == ""){
                $("#grooveWidth").attr('readonly',true).val(0);
            }
        }else{
            $("#grooveWidth").attr('readonly',true).val(0);
        }

        if(GrooveDepthValue != null){
            GrooveDepthValue = $("#GrooveDepth-value").data("value");
            if(GrooveDepthValue == ""){
                $("#grooveDepth").attr('disabled',true).val(0);
            }
        }else{
            $("#grooveDepth").attr('disabled',true).val(0);
        }


        if(MaxNumberOfGrooveValue != null){
            MaxNumberOfGrooveValue = $("#MaxNumberOfGroove-value").data("value");
            if(MaxNumberOfGrooveValue == ""){
                $("#maxNumberOfGroove").attr('readonly',true).val(0);
            }
        }else{
            $("#maxNumberOfGroove").attr('readonly',true).val(0);
        }

        if(NumberOfGrooveValue != null){
            NumberOfGrooveValue = $("#NumberOfGroove-value").data("value");
            if(NumberOfGrooveValue == ""){
                $("#numberOfGroove").attr('readonly',true).val(0);
            }
        }else{
            $("#numberOfGroove").attr('readonly',true).val(0);
        }

        if(NumberOfVerticalGrooveValue != null){
            NumberOfVerticalGrooveValue = $("#NumberOfVerticalGroove-value").data("value");
            if(NumberOfVerticalGrooveValue == ""){
                $("#numberOfVerticalGroove").attr('readonly',true).val(0);
            }
        }else{
            $("#numberOfVerticalGroove").attr('readonly',true).val(0);
        }

        if(NumberOfHorizontalGrooveValue != null){
            NumberOfHorizontalGrooveValue = $("#NumberOfHorizontalGroove-value").data("value");
            if(NumberOfHorizontalGrooveValue == ""){
                $("#numberOfHorizontalGroove").attr('readonly',true).val(0);
            }
        }else{
            $("#numberOfHorizontalGroove").attr('readonly',true).val(0);
        }

        const overpanelValue = $('#overpanel').val();
        var tollerance = 0;
        var gap = 0;
        var framethikness = 0;
        var soWidth = 0;
        var soheight=0;
        var undercut=0;
        if(overpanelValue == 'No'){
            //var thisvalue = document.getElementsByClassName("foroPWidth");
            var thisvalue = document.getElementsByClassName("form-control");
            for (var i = 0; i < thisvalue.length; i++) {
                if(thisvalue[i].name=='tollerance'){
                    if(thisvalue[i].value==''){
                        tollerance = 0;
                    } else {
                        tollerance = parseInt(thisvalue[i].value);
                    }
                }

                if(thisvalue[i].name=='frameThickness'){
                    if(thisvalue[i].value==''){
                        framethikness = 0;
                    } else {
                        framethikness = parseInt(thisvalue[i].value);
                    }
                }

                if(thisvalue[i].name=='gap'){
                    if(thisvalue[i].value==''){
                        gap = 0;
                    } else {
                        gap = parseInt(thisvalue[i].value);
                    }
                }
                if(thisvalue[i].name=='sOWidth'){
                    if(thisvalue[i].value==''){
                        soWidth = 0;
                    } else {
                        soWidth = parseInt(thisvalue[i].value);
                    }
                }
                if(thisvalue[i].name=='sOHeight'){
                    if(thisvalue[i].value==''){
                        soheight = 0;
                    } else {
                        soheight = parseInt(thisvalue[i].value);
                    }
                }
                if(thisvalue[i].name=='undercut'){
                    if(thisvalue[i].value==''){
                        undercut = 0;
                    } else {
                        undercut = parseInt(thisvalue[i].value);
                    }
                }
            }

            var leafHeightNoOP = soheight-tollerance-framethikness-undercut-gap;

            // $("#leafHeightNoOP").val(leafHeightNoOP).attr('readonly',true);

            if($("#sideLight1").val() == "Yes"){
                $("#SL1Height").val(leafHeightNoOP).attr({'readonly':true, "required": true });
            }

            if($("#sideLight2").val() == "Yes"){
                $("#SL2Height").val(leafHeightNoOP).attr({'readonly':true, "required": true });
            }
            var plantonStopHeight = soheight-tollerance;

            //$("#plantonStopHeight").val(plantonStopHeight);
            $("#frameHeight").val(plantonStopHeight);
            var frameDepth = $("#sODepth").val()!=''?$("#sODepth").val():0;

            $("#leafHeightwithOP").val(0).attr('readonly',true);
            $("#oPWidth").val(0).attr('readonly',true);
            $("#oPHeigth").val(0).attr('readonly',true);
            $("#OPLippingThickness").val('').attr('disabled',true);
            $("#transomThickness").val('').attr('disabled',true);
            $("#opTransom").val('').attr('disabled',true);

            $("#opGlassIntegrity").attr({'disabled':true,required:false}).val('');
            $("#opGlassType").attr({'disabled':true,required:false}).val('');
            $("#opGlazingBeads").attr({'disabled':true,required:false}).val('');
            $("#opGlazingBeadSpecies").attr({'disabled':true,readonly:true,required:false}).val('');
            $("#opGlassIntegrity").attr({'disabled':true,readonly:true,required:false}).val('');


            // Rround down the field ‘Maximum Number of Groove’
            // for example, if the value there is 20.42 it should be 20.
            // Leaf Height with Groove Location = Vertical
            var grooveLocationValue = $('#grooveLocation').val();
            if(grooveLocationValue == "Horizontal" && leafHeightNoOP != ''){
                $("#maxNumberOfGroove").val(parseInt(Math.round((leafHeightNoOP)/100)));
            }
        }




        // Vision Panel ( By default No )
        const leaf1VisionPanelValue = $('#leaf1VisionPanel').val();
        if(leaf1VisionPanelValue == 'No'){
            $("#visionPanelQuantity").val('').attr({'disabled':true,'required':false});
            //$("#AreVPsEqualSizes").val('').attr({'disabled':true,'required':false});
            $("#AreVPsEqualSizes").val('').attr({'disabled':true});
            $("#vP1Width").attr({'required':false,'readonly':true});
            $("#vP1Height1").attr({'required':false,'readonly':true});
            $("#distanceFromTopOfDoor").attr({'required':false,'readonly':true});
            $("#distanceFromTheEdgeOfDoor").attr({'required':false,'readonly':true});
            $("#distanceBetweenVPs").attr({'required':false,'readonly':true});
            for(var i=2;i<=5;i++){
                $("#vP1Height"+i).attr({'required':false,'readonly':true});
            }
            $("#leaf1VpAreaSizeM2").val(0);
            $('#leaf1VisionPanelShape').attr({'required':false,'readonly':true});
        }


    });

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // function
    function FireRatingChange(){
        if($("#fireRating").val()!=''){
            if($("#fireRating").val()=="NFR"){
                $("#grooveDepth").attr("max","");
                $("#doorThickness").hide()
                $("#door_thickness_div").empty().append("<select name='doorThickness' id='doorThickness' class='form-control'><option value='35'>35</option> <option value='44'>44</option></select>")
                $("#swingType").removeAttr("disabled")
                glass_type();
                $("#distanceBetweenVPs").removeAttr("min");
                $("#distanceBetweenVPs").removeAttr("max");
                $("#distanceBetweenVPs").val("");
            } else {
                if($("#fireRating").val()=="FD30"){
                    $("#scallopedLippingThickness").empty().append('<option value="8"><option value="8">');
                    $("#grooveDepth").attr("max",4);
                    $("#door_thickness_div").empty().append(`<input type="number" readonly name="doorThickness" id="doorThickness" class="form-control"
                value="44">`);
                }
                if($("#fireRating").val()=="FD60"){
                    $("#grooveDepth").attr("max",5);
                    $("#door_thickness_div").empty().append(`<input type="number" readonly name="doorThickness" id="doorThickness" class="form-control"
                    value="54">`);
                }

                if($("#fireRating").val()=='FD30s'){
                    $("#grooveDepth").attr("max",4);
                    $("#door_thickness_div").empty().append(`<input type="number" readonly name="doorThickness" id="doorThickness" class="form-control"
                    value="44">`);
                }

                if($("#fireRating").val()=='FD60s'){
                    $("#grooveDepth").attr("max",5);
                    $("#door_thickness_div").empty().append(`<input type="number" readonly name="doorThickness" id="doorThickness" class="form-control"
                    value="54">`);
                }


                $("#distanceBetweenVPs").attr('min',320)
                $("#distanceBetweenVPs").attr('max',380)
                $("#distanceBetweenVPs").val("");
            }
            floor_finish_change();
            MeetingStyle();
            doorThicknessFilter($("#fireRating").val());
            glazingSystemFIlter($("#fireRating").val());
            glassTypeFilter(false);
            glazingBeadsFilter($("#fireRating").val());
            frameMaterialFilter($("#fireRating").val());
            scalloppedLippingThickness($("#fireRating").val());
            flatLippingThickness($("#fireRating").val());
            rebatedLippingThickness($("#fireRating").val());
            lipping_Thickness($("#fireRating").val());
            var leafConstruction = $("#leafConstruction").val();
            if(leafConstruction != 'Flush'){
                if($("#fireRating").val()!="NFR"){
                    $("#doorsetType option[value='DD']").hide();
                    $("#doorsetType option[value='leaf_and_a_half']").hide();
                }else{
                    $("#doorsetType option[value='DD']").show();
                    $("#doorsetType option[value='leaf_and_a_half']").show();
                }
            }else{
                $("#doorsetType option[value='DD']").show();
                $("#doorsetType option[value='leaf_and_a_half']").show();
            }
        }
        IntumescentSeals();
    }

    function glass_type(){
        let pageId = pageIdentity();
        $.ajax({
            url:$("#glass-type-nfr").html(),
            method:"POST",
            dataType:"Json",
            data:{pageId:pageId,_token:$("#_token").val()},
            success: function(result){
                if(result.status=="ok"){
                    var innerHtml ='';
                    var data = result.data;
                    var length = result.data.length;
                    innerHtml+='<option value="">Select Glass Type</option>';
                    for(var i =0; i<length;i++){
                        innerHtml+='<option value="'+data[i].OptionKey+'">'+data[i].OptionValue+'</option>';
                    }
                    setTimeout(() => {
                        $("#glassType").empty().append(innerHtml);
                    }, 500);

                } else {
                    innerHtml+='<option value="">No Glass Type Found</option>';
                    $("#glassType").empty().append(innerHtml);
                }
            }
        });
    }

    function doorThicknessFilter(fireRating){
        let pageId = pageIdentity();
        $.ajax({
            url: $("#door-thickness-filter").html(),
            method:"POST",
            data:{pageId:pageId,fireRating:fireRating,_token:$("#_token").val()},
            dataType:"Json",
            success: function(result){
                var innerHtml ='';
                var innerHtml1='';
                if(result.status=="ok"){
                    var data = result.data; console.log(data);
                    var datalength = result.data.length;
                    var GlassIntegrityValue = document.getElementById('GlassIntegrity-value');
                    var opGlassIntegrityValue = document.getElementById('opGlassIntegrity-value');
                    // innerHtml+='<option value="">Select Door Thickness</option>';
                    for(var index =0; index<datalength;index++){
                        if(data[index].OptionSlug=="Door_Thickness" &&  data[index].UnderAttribute == fireRating){
                            $("#doorThickness").val(data[index].OptionKey);
                        }
                        // if(data[index].OptionSlug=="Door_Thickness" &&  data[index].UnderAttribute == fireRating ){


                        //     innerHtml+='<option value="'+data[index].OptionKey+'">'+data[index].OptionValue+'</option>';
                        // }else if(data[index].OptionSlug=="Glass_Integrity" &&  data[index].UnderAttribute == fireRating){
                        //     innerHtml1+='<option value="'+data[index].OptionKey+'">'+data[index].OptionValue+'</option>';
                        // }

                        if(data[index].OptionSlug=="Glass_Integrity" &&  data[index].UnderAttribute == fireRating){
                            if (GlassIntegrityValue != null) {
                                GlassIntegrityValue = $("#GlassIntegrity-value").data("value");
                                var GlassIntegritySelected = "";
                                if(GlassIntegrityValue == data[index].OptionKey){
                                    GlassIntegritySelected = "selected";
                                }
                                innerHtml +='<option value="'+data[index].OptionKey+'" '+ GlassIntegritySelected +'>'+data[index].OptionValue+'</option>';
                            }else{
                                innerHtml +='<option value="'+data[index].OptionKey+'">'+data[index].OptionValue+'</option>';
                            }
                        }

                        if(data[index].OptionSlug=="Glass_Integrity" &&  data[index].UnderAttribute == fireRating){
                            if (opGlassIntegrityValue != null) {
                                opGlassIntegrityValue = $("#opGlassIntegrity-value").data("value");
                                var OPGlassIntegritySelected = "";
                                if(opGlassIntegrityValue == data[index].OptionKey){
                                    OPGlassIntegritySelected = "selected";
                                }
                                innerHtml1+='<option value="'+data[index].OptionKey+'" '+ OPGlassIntegritySelected +'>'+data[index].OptionValue+'</option>';
                            }else{
                                innerHtml1+='<option value="'+data[index].OptionKey+'">'+data[index].OptionValue+'</option>';
                            }
                        }

                    }
                    console.log(GlassIntegrityValue)
                    if(innerHtml !=''){
                        var intigrity ='<option value="">Select Glass Intrigrity</option>';
                        $("#lazingIntegrityOrInsulationIntegrity").attr('disabled',false).val();
                    }else{
                        var intigrity='';
                        innerHtml1+='<option value="">No Glass Intrigrity Found</option>';
                        $("#lazingIntegrityOrInsulationIntegrity").attr('disabled',true).val('');
                    }

                    if(innerHtml1 != ''){
                        var intigrity1 ='<option value="">Select Glass Intrigrity</option>';
                        $("#opGlassIntegrity").attr('disabled',false).val();
                    }else{
                        var intigrity1='';
                        innerHtml1+='<option value="">No Glass Intrigrity Found</option>';
                        $("#opGlassIntegrity").attr('disabled',true).val('');
                    }

                    // $("#doorThickness").empty().append(innerHtml);
                    $("#lazingIntegrityOrInsulationIntegrity").empty().append(intigrity).append(innerHtml);
                    $("#opGlassIntegrity").empty().append(intigrity1).append(innerHtml1);

                }else{
                    innerHtml+='<option value="">No Door Thickness</option>';
                    // $("#doorThickness").empty().append(innerHtml);
                    $("#doorThickness").val(0);

                    innerHtml1+='<option value="">No Glass Intrigrity Found</option>';
                    $("#lazingIntegrityOrInsulationIntegrity").empty().append(innerHtml1);
                    $("#opGlassIntegrity").empty().append(innerHtml1);
                }
            }
        });
    }
    function glazingSystemFIlter(fireRating){
        let pageId = pageIdentity();
        var leaf1VpAreaSizeM2Value = $('#leaf1VpAreaSizeM2').val();
        leaf1VpAreaSizeM2Value = (leaf1VpAreaSizeM2Value == 0)?"":leaf1VpAreaSizeM2Value;
        $.ajax({
            url: $("#glazing-system-filter").html(),
            method:"POST",
            dataType:"Json",
            data:{pageId:pageId,fireRating:fireRating,_token:$("#_token").val(), leaf1VpAreaSizeM2Value : leaf1VpAreaSizeM2Value},
            success: function(result){
                var innerHtml1='';
                if(result.status=="error"){
                    var innerHtml ='';
                    var data = result.data;
                    var datalength = result.data.length;
                    var lippingSpecies = result.lippingSpecies;
                    var lippingSpeciesLength =result.lippingSpecies.length;
                    innerHtml+='<option value="">Select Glazing Type</option>';
                    var GlazingSystemsValue = document.getElementById('GlazingSystems-value');
                    for(var index =0; index<datalength;index++){
                        if (GlazingSystemsValue != null) {
                            GlazingSystemsValue = $("#GlazingSystems-value").data("value");
                            var GlazingSystemsSelected = "";
                            if(GlazingSystemsValue == data[index].OptionKey){
                                GlazingSystemsSelected = "selected";
                            }
                            innerHtml+='<option value="'+data[index].OptionKey+'" '+ GlazingSystemsSelected +'>'+data[index].OptionValue+'</option>';
                        } else {
                            innerHtml+='<option value="'+data[index].OptionKey+'">'+data[index].OptionValue+'</option>';
                        }
                    }
                    if(lippingSpecies!='' && lippingSpeciesLength>0){
                        innerHtml1 += '<div class="container"><div class="row">';
                        // innerHtml1+='<option value="">Select Species Type</option>';

                        var LippingSpeciesValue = document.getElementById('LippingSpecies-value');
                        var OPGlazingBeadSpeciesValue = document.getElementById('OPGlazingBeadSpecies-value');
                        var GlazingBeadSpeciesValue = document.getElementById('GlazingBeadSpecies-value');
                        var SL1GlazingBeadSpeciesValue = document.getElementById('SL1GlazingBeadSpecies-value');
                        var SideLight2GlazingBeadSpeciesValue = document.getElementById('SideLight2GlazingBeadSpecies-value');
                        for(var leep =0; leep<lippingSpeciesLength;leep++){
                            if(LippingSpeciesValue != null){
                                LippingSpeciesValue = $("#LippingSpecies-value").data("value");
                                if(LippingSpeciesValue != "" && LippingSpeciesValue == lippingSpecies[leep].id){
                                    $("#lippingSpecies").val(lippingSpecies[leep].SpeciesName);
                                }
                            }

                            if(OPGlazingBeadSpeciesValue != null){
                                OPGlazingBeadSpeciesValue = $("#OPGlazingBeadSpecies-value").data("value");
                                if(OPGlazingBeadSpeciesValue != "" && OPGlazingBeadSpeciesValue == lippingSpecies[leep].id){
                                    $("#opGlazingBeadSpecies").val(lippingSpecies[leep].SpeciesName);
                                }
                            }

                            if(GlazingBeadSpeciesValue != null){
                                GlazingBeadSpeciesValue = $("#GlazingBeadSpecies-value").data("value");
                                if(GlazingBeadSpeciesValue != "" && GlazingBeadSpeciesValue == lippingSpecies[leep].id){
                                    $("#glazingBeadSpecies").val(lippingSpecies[leep].SpeciesName);
                                }
                            }

                            if(SL1GlazingBeadSpeciesValue != null){
                                SL1GlazingBeadSpeciesValue = $("#SL1GlazingBeadSpecies-value").data("value");
                                if(SL1GlazingBeadSpeciesValue != "" && SL1GlazingBeadSpeciesValue == lippingSpecies[leep].id){
                                    $("#SideLight1GlazingBeadSpecies").val(lippingSpecies[leep].SpeciesName);
                                }
                            }

                            if(SideLight2GlazingBeadSpeciesValue != null){
                                SideLight2GlazingBeadSpeciesValue = $("#SideLight2GlazingBeadSpecies-value").data("value");
                                if(SideLight2GlazingBeadSpeciesValue != "" && SideLight2GlazingBeadSpeciesValue == lippingSpecies[leep].id){
                                    $("#SideLight2GlazingBeadSpecies").val(lippingSpecies[leep].SpeciesName);
                                }
                            }

                            var filepath = $("input[name='base_url']").val()+"/uploads/Options/"+lippingSpecies[leep].file;

                            innerHtml1 += '<div class="col-md-2 col-sm-4 col-6 cursor-pointer" onClick="GlazingValueFill('+lippingSpecies[leep].id+',\''+lippingSpecies[leep].SpeciesName+'\',\'#glazingModal\')">'
                            + '<div class="color_box">'
                            + '<div class="frameMaterialImage"><img width="100%" height="100" src="'+ filepath +'"></div>'
                            + '<h4>'+lippingSpecies[leep].SpeciesName+'</h4>'
                            + '</div></div>';
                            // innerHtml1+='<option value="'+lippingSpecies[leep].id+'">'+lippingSpecies[leep].SpeciesName+'</option>'

                        }
                    } else {
                        // innerHtml1+='<option value="">No Species Found</option>';
                    }
                    $("#glazingSystems").empty().append(innerHtml);
                    // $("#lippingSpecies").empty().append(innerHtml1);
                    // $("#glazingBeadSpecies").empty().append(innerHtml1);
                    // $("#opGlazingBeadSpecies").empty().append(innerHtml1);
                    $("#glazingModalBody").empty().append(innerHtml1);

                    // $("#SideLight1GlazingBeadSpecies").empty().append(innerHtml1);
                    // $("#SideLight2GlazingBeadSpecies").empty().append(innerHtml1);
                } else {
                    var lippingSpecies = result.lippingSpecies;
                    var lippingSpeciesLength =result.lippingSpecies.length;
                    innerHtml+='<option value="">No Glazing Systems Found</option>';
                    $("#glazingSystems").empty().append(innerHtml);
                    if(lippingSpecies!='' && lippingSpeciesLength>0){
                        innerHtml1+='<option value="">Select  Species Type</option>';
                        for(var leep =0; leep<lippingSpeciesLength;leep++){
                            innerHtml1+='<option value="'+lippingSpecies[leep].id+'">'+lippingSpecies[leep].SpeciesName+'</option>'
                        }
                    } else {
                        innerHtml1+='<option value="">No  Species Found</option>';
                    }
                    $("#lippingSpecies").empty().append(innerHtml1);
                    $("#glazingBeadSpecies").empty().append(innerHtml1);
                    // $("#opGlazingBeadSpecies").empty().append(innerHtml1);
                    $("#glazingModalBody").empty().append(innerHtml1);
                    $("#SideLight1GlazingBeadSpecies").empty().append(innerHtml1);
                    $("#SideLight2GlazingBeadSpecies").empty().append(innerHtml1);
                }
                $("#glazingSystemsThickness").val(0);
            }
        });
    }

    function glassTypeFilter(isIntegrity){
        let pageId = pageIdentity();
        var fireRating =$("#fireRating").val();
        var integrity =  $("#lazingIntegrityOrInsulationIntegrity").val();
        var GlassIntegrityValue = document.getElementById('GlassIntegrity-value');
        if(GlassIntegrityValue != null){
            GlassIntegrityValue = $("#GlassIntegrity-value").data("value");
            if(GlassIntegrityValue != ""){
                integrity = GlassIntegrityValue;
            }
        }
        var leaf1VpAreaSizeM2Value = $('#leaf1VpAreaSizeM2').val();
        leaf1VpAreaSizeM2Value = (leaf1VpAreaSizeM2Value == 0)?"":leaf1VpAreaSizeM2Value;
        $.ajax({
            url: $("#fire-rating-filter").html(),
            method:"POST",
            dataType:"Json",
            data:{pageId:pageId,fireRating:fireRating,integrity:integrity,_token:$("#_token").val(), isIntegrity : isIntegrity, leaf1VpAreaSizeM2Value:leaf1VpAreaSizeM2Value},
            success: function(result){
                var glassTypeInnerHtml = "", OPGlassTypeInnerHtml = "",
                    sideLight1GlassTypeInnerHtml = "", sideLight2GlassTypeInnerHtml = "";
                if(result.status=="ok"){
                    var data = result.data;
                    var length = result.data.length;

                    var GlassTypeValue = document.getElementById('GlassType-value');
                    var OPGlassTypeValue = document.getElementById('OPGlassType-value');
                    var SideLight1GlassTypeValue = document.getElementById('SideLight1GlassType-value');
                    var SideLight2GlassTypeValue = document.getElementById('SideLight2GlassType-value');

                    glassTypeInnerHtml+='<option value="">Select Glass Type</option>';
                    OPGlassTypeInnerHtml+='<option value="">Select Glass Type</option>';
                    sideLight1GlassTypeInnerHtml+='<option value="">Select Glass Type</option>';
                    sideLight2GlassTypeInnerHtml+='<option value="">Select Glass Type</option>';

                    for(var i =0; i<length;i++){

                        if (GlassTypeValue != null) {
                            GlassTypeValue = $("#GlassType-value").data("value");
                            var GlassTypeSelected = "";
                            if(GlassTypeValue == data[i].OptionKey){
                                GlassTypeSelected = "selected";
                            }
                            glassTypeInnerHtml+='<option value="'+data[i].OptionKey+'" '+ GlassTypeSelected +'>'+data[i].OptionValue+'</option>';
                        }else{
                            glassTypeInnerHtml+='<option value="'+data[i].OptionKey+'">'+data[i].OptionValue+'</option>';
                        }

                        if (OPGlassTypeValue != null) {
                            OPGlassTypeValue = $("#OPGlassType-value").data("value");
                            var OPGlassTypeSelected = "";
                            if(OPGlassTypeValue == data[i].OptionKey){
                                OPGlassTypeSelected = "selected";
                            }
                            OPGlassTypeInnerHtml += '<option value="'+data[i].OptionKey+'" '+ OPGlassTypeSelected +'>'+data[i].OptionValue+'</option>';
                        }else{
                            OPGlassTypeInnerHtml += '<option value="'+data[i].OptionKey+'">'+data[i].OptionValue+'</option>';
                        }

                        if (SideLight1GlassTypeValue != null) {
                            SideLight1GlassTypeValue = $("#SideLight1GlassType-value").data("value");
                            var SideLight1GlassTypeSelected = "";
                            if(SideLight1GlassTypeValue == data[i].OptionKey){
                                SideLight1GlassTypeSelected = "selected";
                            }
                            sideLight1GlassTypeInnerHtml += '<option value="'+data[i].OptionKey+'" '+ SideLight1GlassTypeSelected +'>'+data[i].OptionValue+'</option>';
                        }else{
                            sideLight1GlassTypeInnerHtml += '<option value="'+data[i].OptionKey+'">'+data[i].OptionValue+'</option>';
                        }

                        if (SideLight2GlassTypeValue != null) {
                            SideLight2GlassTypeValue = $("#OPGlassType-value").data("value");
                            var SideLight2GlassTypeSelected = "";
                            if(SideLight2GlassTypeValue == data[i].OptionKey){
                                SideLight2GlassTypeSelected = "selected";
                            }
                            sideLight2GlassTypeInnerHtml += '<option value="'+data[i].OptionKey+'" '+ SideLight2GlassTypeSelected +'>'+data[i].OptionValue+'</option>';
                        } else {
                            sideLight2GlassTypeInnerHtml += '<option value="'+data[i].OptionKey+'">'+data[i].OptionValue+'</option>';
                        }
                    }
                    $("#glassType").empty().append(glassTypeInnerHtml);
                    // $("#opGlassType").empty().append(OPGlassTypeInnerHtml);
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
                $("#glassThickness").val(0);
            }
        });
    }
    function glazingBeadsFilter(fireRating){
        let pageId = pageIdentity();
        $.ajax({
            url: $("#glazing-beads-filter").html(),
            method:"POST",
            dataType:"Json",
            data:{pageId:pageId,fireRating:fireRating,_token:$("#_token").val()},
            success: function(result){
                var GlazingBeadsInnerHtml = '', OPGlazingBeadsInnerHtml = '',
                    SideLight1BeadingTypeInnerHtml = '', SideLight2BeadingTypeInnerHtml = '';
                if(result.status=="ok"){
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

                    for(var i =0; i<length;i++){
                        if (GlazingBeadsValue != null) {
                            GlazingBeadsValue = $("#GlazingBeads-value").data("value");
                            var GlazingBeadsSelected = "";
                            if(GlazingBeadsValue == data[i].OptionKey){
                                GlazingBeadsSelected = "selected";
                            }
                            GlazingBeadsInnerHtml +='<option value="'+data[i].OptionKey+'" '+ GlazingBeadsSelected +'>'+data[i].OptionValue+'</option>';
                        } else {
                            GlazingBeadsInnerHtml +='<option value="'+data[i].OptionKey+'">'+data[i].OptionValue+'</option>';
                        }

                        if (OPGlazingBeadsValue != null) {
                            OPGlazingBeadsValue = $("#OPGlazingBeads-value").data("value");
                            var OPGlazingBeadsSelected = "";
                            if(OPGlazingBeadsValue == data[i].OptionKey){
                                OPGlazingBeadsSelected = "selected";
                            }
                            OPGlazingBeadsInnerHtml +='<option value="'+data[i].OptionKey+'" '+ OPGlazingBeadsSelected +'>'+data[i].OptionValue+'</option>';
                        } else {
                            OPGlazingBeadsInnerHtml +='<option value="'+data[i].OptionKey+'">'+data[i].OptionValue+'</option>';
                        }

                        if (BeadingTypeValue != null) {
                            BeadingTypeValue = $("#BeadingType-value").data("value");
                            var BeadingTypeSelected = "";

                            if(BeadingTypeValue == data[i].OptionKey){
                                BeadingTypeSelected = "selected";
                            }
                            SideLight1BeadingTypeInnerHtml += '<option value="'+data[i].OptionKey+'" '+ BeadingTypeSelected +'>'+data[i].OptionValue+'</option>';
                        } else {
                            SideLight1BeadingTypeInnerHtml += '<option value="'+data[i].OptionKey+'">'+data[i].OptionValue+'</option>';
                        }

                        if (SideLight2BeadingTypeValue != null) {
                            SideLight2BeadingTypeValue = $("#SideLight2BeadingType-value").data("value");
                            var SideLight2BeadingTypeSelected = "";

                            if(SideLight2BeadingTypeValue == data[i].OptionKey){
                                SideLight2BeadingTypeSelected = "selected";
                            }
                            SideLight2BeadingTypeInnerHtml += '<option value="'+data[i].OptionKey+'" '+ SideLight2BeadingTypeSelected +'>'+data[i].OptionValue+'</option>';
                        } else {
                            SideLight2BeadingTypeInnerHtml += '<option value="'+data[i].OptionKey+'">'+data[i].OptionValue+'</option>';
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
                    $("#UniversalModalBody").empty().append(innerHtmlPopUp);

                    $("#frameMaterialIcon").attr("onclick","return FrameMaterial()");
                    $("#frameMaterialIcon").addClass("cursor-pointer");

                    let overPanel = $('#overpanel').val();
                    if(overPanel=="Fan_Light"){
                        $("#opGlazingBeadSpeciesIcon").attr("onclick","return  OpenglazingModal('OP Glazing Bead Species','opGlazingBeadSpecies')");
                        $("#opGlazingBeadSpeciesIcon").addClass("cursor-pointer");
                    }
                    $("#glazingBeadSpeciesIcon").attr("onclick","return  OpenglazingModal('Glazing Bead Species','glazingBeadSpecies')");
                    $("#glazingBeadSpeciesIcon").addClass("cursor-pointer");
                    $("#lippingSpeciesIcon").attr("onclick","return  OpenglazingModal('Lipping Species','lippingSpecies')");
                    $("#lippingSpeciesIcon").addClass("cursor-pointer");
                    $("#SideLight2GlazingBeadSpeciesIcon").attr("onclick","return  OpenglazingModal('Side Light 2 Glazing Bead Species','SideLight2GlazingBeadSpecies')");
                    $("#SideLight2GlazingBeadSpeciesIcon").addClass("cursor-pointer");
                    $("#SideLight1GlazingBeadSpeciesIcon").attr("onclick","return  OpenglazingModal('Glazing Bead Species','SideLight1GlazingBeadSpecies')");
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
    function scalloppedLippingThickness(fireRating){
        let pageId = pageIdentity();
        $.ajax({
            url: $("#scallopped-lipping-thickness").html(),
            method:"POST",
            dataType:"Json",
            data:{pageId:pageId,fireRating:fireRating,_token:$("#_token").val()},
            success: function(result){
                if(result.status=="ok"){
                    var innerHtml ='';
                    var data = result.data;
                    var length = result.data.length;
                    var ScallopedLippingThicknessValue = document.getElementById('ScallopedLippingThickness-value');
                    innerHtml+='<option value="">Select Scallopped lipping thickness</option>';
                    for(var i =0; i<length;i++){
                        if (ScallopedLippingThicknessValue != null) {
                            ScallopedLippingThicknessValue = $("#ScallopedLippingThickness-value").data("value");
                            var ScallopedLippingThicknessSelected = "";
                            if(ScallopedLippingThicknessValue == data[i].OptionKey){
                                ScallopedLippingThicknessSelected = "selected";
                            }
                            innerHtml+='<option value="'+data[i].OptionKey+'" '+ ScallopedLippingThicknessSelected +'>'+data[i].OptionValue+'</option>';
                        } else {
                            innerHtml+='<option value="'+data[i].OptionKey+'">'+data[i].OptionValue+'</option>';
                        }
                    }
                    $("#scallopedLippingThickness").empty().append(innerHtml);
                } else {
                    innerHtml+='<option value="">No Scallopped lipping thickness Found</option>';
                    $("#scallopedLippingThickness").empty().append(innerHtml);
                }
                if(fireRating=="NFR"){
                    var noData ='';
                    noData+='<option value="">No Scallopped lipping thickness Found</option>';
                    $("#scallopedLippingThickness").empty().append(noData);
                }
            }
        });
    }
    function flatLippingThickness(fireRating){
        let pageId = pageIdentity();
        $.ajax({
            url: $("#flat-lipping-thickness").html(),
            method:"POST",
            dataType:"Json",
            data:{pageId:pageId,fireRating:fireRating,_token:$("#_token").val()},
            success: function(result){
                if(result.status=="ok"){
                    var innerHtml ='';
                    var FlatLippingThicknessValue = document.getElementById('FlatLippingThickness-value');
                    var data = result.data;
                    var length = result.data.length;
                    innerHtml+='<option value="">Select Flat lipping Thickness</option>';
                    for(var i =0; i<length;i++){
                        if (FlatLippingThicknessValue != null) {
                            FlatLippingThicknessValue = $("#FlatLippingThickness-value").data("value");
                            var FlatLippingThicknessSelected = "";
                            if(FlatLippingThicknessValue == data[i].OptionKey){
                                FlatLippingThicknessSelected = "selected";
                            }
                            innerHtml+='<option value="'+data[i].OptionKey+'" '+ FlatLippingThicknessSelected +'>'+data[i].OptionValue+'</option>';
                        }else{
                            innerHtml+='<option value="'+data[i].OptionKey+'">'+data[i].OptionValue+'</option>';
                        }
                    }
                    $("#flatLippingThickness").empty().append(innerHtml);
                } else {
                    innerHtml+='<option value="">No Flat lipping Thickness Found</option>';
                    $("#flatLippingThickness").empty().append(innerHtml);
                }
                if(fireRating=="NFR"){
                    var noData ='';
                    noData+='<option value="">No Flat lipping Thickness Found</option>';
                    $("#flatLippingThickness").empty().append(noData);
                }
            }
        });
    }
    function rebatedLippingThickness(fireRating){
        let pageId = pageIdentity();
        $.ajax({
            url: $("#rebated-lipping-thickness").html(),
            method:"POST",
            dataType:"Json",
            data:{pageId:pageId,fireRating:fireRating,_token:$("#_token").val()},
            success: function(result){
                if(result.status=="ok"){
                    var innerHtml ='';
                    var data = result.data;
                    var length = result.data.length;
                    innerHtml+='<option value="">Select Rebated Lipping Thickness</option>';
                    var RebatedLippingThicknessValue = document.getElementById('RebatedLippingThickness-value');
                    for(var i =0; i<length;i++){
                        if (RebatedLippingThicknessValue != null) {
                            RebatedLippingThicknessValue = $("#RebatedLippingThickness-value").data("value");

                            var RebatedLippingThicknessSelected = "";
                            if(RebatedLippingThicknessValue == data[i].OptionKey){
                                RebatedLippingThicknessSelected = "selected";
                            }
                            innerHtml+='<option value="'+data[i].OptionKey+'" '+ RebatedLippingThicknessSelected +'>'+data[i].OptionValue+'</option>';
                        }else{
                            innerHtml+='<option value="'+data[i].OptionKey+'">'+data[i].OptionValue+'</option>';
                        }
                    }
                    $("#rebatedLippingThickness").empty().append(innerHtml);
                } else {
                    innerHtml+='<option value="">No Rebated Lipping Thickness Found</option>';
                    $("#rebatedLippingThickness").empty().append(innerHtml);
                }
                if(fireRating=="NFR"){
                    var noData ='';
                    noData+='<option value="">No Rebated lipping Thickness Found</option>';
                    $("#rebatedLippingThickness").empty().append(noData);
                }
            }
        });
    }

    function DoorSetTypeChange(){
        var accousticsvalue = $("#accoustics").val();
        if(accousticsvalue=="Yes"){
            let set = $('#doorsetType').val();
            if(set == "SD"){
                $("#accousticsmeetingStiles").removeClass('bg-white');
                $("#accousticsmeetingStiles").val('');
                $('#accousticsmeetingStilesIcon').attr('onclick','');
                $('input[name="accousticsmeetingStiles"]').val('');

                $('#accousticsmeetingStiles').css({'disaplay':'none'});
            } else {
                $('#accousticsmeetingStiles').css({'display':'block'});
                $("#accousticsmeetingStiles").addClass('bg-white');
                $('#accousticsmeetingStilesIcon').attr('onclick', "return openAccousticsModal('accousticsmeetingStiles','MeetingStiles' ,'Meeting_Stiles')");
                $('#accousticsmeetingStiles').attr({'required':true});
            }
        } else {
            $("#accousticsmeetingStiles").removeClass('bg-white');
            $("#accousticsmeetingStiles").val('');
            $('#accousticsmeetingStilesIcon').attr('onclick','');
            $('input[name="accousticsmeetingStiles"]').val('');
            $('#accousticsmeetingStiles').attr({'required':false});
        }
        MeetingStyle();
        var ConfigurableDoorFormula = JSON.parse(ConfigurableDoorFormulaJson);
        var TolleranceAdditionalNumber = 1;
        var FrameThicknessAdditionalNumber = 1;
        var GapAdditionalNumber = 1;

        var DoorSetType = $("#doorsetType").val();
        var swingType = $("#swingType").val();
        ConfigurableDoorFormula.forEach(function(elem, index) {
            var FormulaAdditionalData = JSON.parse(elem.value);
            if(DoorSetType == "SD"){
                if(elem.slug == "leaf_width_1_for_single_door_set"){
                    TolleranceAdditionalNumber = parseFloat((FormulaAdditionalData.tolerance != "")?FormulaAdditionalData.tolerance:1);
                    FrameThicknessAdditionalNumber = parseFloat((FormulaAdditionalData.frame_thickness != "")?FormulaAdditionalData.frame_thickness:1);
                    GapAdditionalNumber = parseFloat((FormulaAdditionalData.gap != "")?FormulaAdditionalData.gap:1);
                }
            }else if(DoorSetType == "DD"){
                if(elem.slug == "leaf_width_1_for_double_door_set"){
                    TolleranceAdditionalNumber = parseFloat((FormulaAdditionalData.tolerance != "")?FormulaAdditionalData.tolerance:1);
                    FrameThicknessAdditionalNumber = parseFloat((FormulaAdditionalData.frame_thickness != "")?FormulaAdditionalData.frame_thickness:1);
                    GapAdditionalNumber = parseFloat((FormulaAdditionalData.gap != "")?FormulaAdditionalData.gap:1);
                }

                //if(elem.slug == "leaf_width_2_for_double_door_set"){
                //    TolleranceAdditionalNumber = parseFloat((FormulaAdditionalData.tollerance != "")?FormulaAdditionalData.tollerance:1);
                //    FrameThicknessAdditionalNumber = parseFloat((FormulaAdditionalData.frame_thickness != "")?FormulaAdditionalData.frame_thickness:1);
                //    GapAdditionalNumber = parseFloat((FormulaAdditionalData.gap != "")?FormulaAdditionalData.gap:1);
                //}
            }else if(DoorSetType == "leaf_and_a_half"){
                if(elem.slug == "leaf_width_2_for_leaf_and_a_half"){
                    TolleranceAdditionalNumber = parseFloat((FormulaAdditionalData.tolerance != "")?FormulaAdditionalData.tolerance:1);
                    FrameThicknessAdditionalNumber = parseFloat((FormulaAdditionalData.frame_thickness != "")?FormulaAdditionalData.frame_thickness:1);
                    GapAdditionalNumber = parseFloat((FormulaAdditionalData.gap != "")?FormulaAdditionalData.gap:1);
                }
            }
        });

        if($("#doorsetType").val()=="DD"){
            var tollerance = 0;
            var gap = 0;
            var framethikness = 0;
            var soWidth = 0;
            // randomkey = 2;
            //var thisvalue = document.getElementsByClassName("foroPWidth");
            var thisvalue = document.getElementsByClassName("form-control");
            for (var i = 0; i < thisvalue.length; i++) {
                if(thisvalue[i].name=='tollerance'){
                    if(thisvalue[i].value==''){
                        tollerance = 0;
                    }
                    else{
                        tollerance = parseInt(thisvalue[i].value);
                    }
                }


                if(thisvalue[i].name=='gap'){
                    if(thisvalue[i].value==''){
                        gap = 0;
                    }
                    else{
                        gap = parseInt(thisvalue[i].value);
                    }
                }


                if(thisvalue[i].name=='frameThickness'){
                    if(thisvalue[i].value==''){
                        framethikness = 0;
                    }
                    else{
                        framethikness = parseInt(thisvalue[i].value);
                    }
                }

                if(thisvalue[i].name=='sOWidth'){
                    if(thisvalue[i].value==''){
                        soWidth = 0;
                    }
                    else{
                        soWidth = parseInt(thisvalue[i].value);
                    }
                }
            }
            var calculate = (soWidth-(tollerance*TolleranceAdditionalNumber)-(framethikness*FrameThicknessAdditionalNumber)-(GapAdditionalNumber*gap))/2;
            // $("#leafWidth2").val(calculate).attr('readonly',true);
            var Leaf2VisionPanelValue = document.getElementById('Leaf2VisionPanel-value');
            if(Leaf2VisionPanelValue == null){
                $("#leaf2VisionPanel").val('No').attr("disabled",false);
            }
        } else if($("#doorsetType").val() == "leaf_and_a_half"){
           // $("#leaf2VisionPanel").val('No').attr({'disabled':false,'required':true});
           $("#leaf2VisionPanel").attr({'disabled':false,'required':true});

        } else {
            // $("#leafWidth2").val(0).attr('readonly',true);
            var Leaf2VisionPanelValue = document.getElementById('Leaf2VisionPanel-value');
            if(Leaf2VisionPanelValue == null){
                // $("#leaf2VisionPanel").val('No').attr("disabled",true);
            }
        }

        if(swingType == 'DA'){
            $('#latchType').siblings('label').children('.dsl').html('');
            $('#latchType option').eq(0).prop('selected', true);
            $('#latchType').attr("disabled",true);

        } else{
            $('#latchType').attr("disabled",false);
        }
        filterHandling();
        IntumescentSeals();
    }
    function MeetingStyle(){
        // Lipping And Intumescent
        // Meeting Style input field
        if($("#fireRating").val()!="NFR"){
            var MeetingStyleValue = document.getElementById('MeetingStyle-value');


            if(MeetingStyleValue != null){
                MeetingStyleValue = $("#MeetingStyle-value").data("value");
                if(MeetingStyleValue == ""){
                    $("#meetingStyle").val('');
                }
            }else{
                $("#meetingStyle").val('');
            }

            if($("#swingType").val()=="SA"){
                if($("#doorsetType").val()=="DD" || $("#doorsetType").val()=="leaf_and_a_half"){
                    $('#meetingStyle').attr('disabled',false);
                    $('#meetingStyle').children('option[value="Scalloped"]').hide();
                    $('#meetingStyle').children('option[value="Rebated"]').show();
                    $('#meetingStyle').children('option[value="Flat"]').show();
                } else {
                    $('#meetingStyle').attr('disabled',true).val("");
                }
            } else if($("#swingType").val()=="DA"){
                if($("#doorsetType").val()=="DD"){
                    $('#meetingStyle').attr('disabled',false);
                    $('#meetingStyle').children('option[value="Scalloped"]').show();
                    $('#meetingStyle').children('option[value="Rebated"]').hide();
                    $('#meetingStyle').children('option[value="Flat"]').hide();
                } else {
                    $('#meetingStyle').attr('disabled',true).val("");
                }
            }
        } else {
            $('#meetingStyle').attr('disabled',true).val("");


        }
    }
    function filterHandling(){
        let pageId = pageIdentity();
        var doorsetType = $("#doorsetType").val();
        var swingType = $("#swingType").val();
        if(pageId == ''){
            swal('Warning','Somethings went wrong!');
            return false;
        }
        $.ajax({
            url: $("#get-handing-options").text(),
            method:"POST",
            dataType:"Json",
            data:{ pageId:pageId,doorsetType: doorsetType, swingType: swingType,_token:$("#_token").val()},
            success: function(result){
                var innerHtml ="";
                if(result.status=="ok"){
                    var  data = result.data;
                    var  length = data.length;
                    innerHtml+='<option value="">Select Handing</option>';
                    var HandingValue = document.getElementById('Handing-value');
                    if (HandingValue != null) {
                        HandingValue = $("#Handing-value").data("value");
                        for(var i=0; i< length ;i++){
                            var selected = "";
                            if(HandingValue == data[i].OptionKey){
                                selected = "selected";
                            }
                            innerHtml+='<option value="'+data[i].OptionKey+'" '+ selected +'>'+data[i].OptionValue+'</option>';
                        }
                    }else{
                        for(var i=0; i< length ;i++){
                            innerHtml+='<option value="'+data[i].OptionKey+'">'+data[i].OptionValue+'</option>';
                        }
                    }
                }else{
                    innerHtml+='<option value="">No Handing Found</option>';
                }
                $("#Handing").empty().append(innerHtml);
            },
            error: function(err){

            }
        });
    }


    // Lipping & Intumescent
    // It is a select field or select tag is `Intumescent Seal Arrangement`
    // These only works when minimum these fields Fire Rating , Doorset Type , Swing Type , S.O Width is fillin.
    // These function is also related other function you can easily find. Search these 'IntumescentSeals()'
    // These is related to `setting_intumescentseals2` table
    function IntumescentSeals(){
        let pageId = pageIdentity();
        const latchTypeValue = $('#latchType').val();       // L,UL
        const swingTypeValue = $('#swingType').val();       // SA,DA
        const doorsetTypeValue = $('#doorsetType').val();   // SD,DD
        const fireRatingValue = $('#fireRating').val();     // FD30
        const overpanelValue2 = $('#overpanel').val();     // Yes
        const leafWidth1Value = $('#leafWidth1').val();
        const leafHeightNoOPValue = $('#leafHeightNoOP').val();
        const sOWidthValue = $('#sOWidth').val();
        const sOHeightValue = $('#sOHeight').val();
        let overpanel = '';
        if(overpanelValue2 == 'Yes'){
            overpanel = 'OP';
        }

        const doorLeafFacingValueNew = $('#doorLeafFacingValue').val();
        const frameMaterialNew = $('#frameMaterialNew').val();
        // The leaf and a half should be treated as a double door so the same way it works for a double door should work for leaf and a half.
            // start
                let $aa = '';
                if(doorsetTypeValue == 'leaf_and_a_half'){
                    const dobledoor = 'DD';
                    $aa = latchTypeValue+swingTypeValue+dobledoor+overpanel; // LSASD
                } else {
                    $aa = latchTypeValue+swingTypeValue+doorsetTypeValue+overpanel; // LSASD
                }
            // end

        let SelectedValue = 0;

        var IntumescentLeapingSealArrangementValue = document.getElementById('IntumescentLeapingSealArrangement-value');
        if (IntumescentLeapingSealArrangementValue != null) {
            SelectedValue = $("#IntumescentLeapingSealArrangement-value").data("value");
        }

        console.log($aa);
        if(fireRatingValue != '' && sOWidthValue != '' && sOHeightValue != ''){
            $.ajax({
                url: $("#Filterintumescentseals").text(),
                method:"POST",
                dataType:"Json",
                data:{pageId:pageId,SelectedValue:SelectedValue,fireRatingValue:fireRatingValue,intumescentseals: $aa ,leafWidth1Value:leafWidth1Value, leafHeightNoOPValue:leafHeightNoOPValue,doorLeafFacingValueNew:doorLeafFacingValueNew,frameMaterialNew:frameMaterialNew, _token:$("#_token").val()},
                success: function(result){ console.log(result);
                    console.log(result.data);
                    console.log(result.c);
                    console.log(result.allValue);
                    console.log(result.sql);
                    if(result.status == 'ok'){
                        // let datalength = result.data.length;
                        // let i = 0;
                        // let dat = '';
                        // let data = result.data;
                        // while(datalength > i){
                        //     dat += '<option value="'+data[i].id+'">'+data[i].brand+' - '+data[i].intumescentSeals+'</option>';
                        //     i++;
                        // }
                        $('#intumescentSealArrangement').empty().append(result.data);
                        $('#sOWidth').css({'border':'1px solid #ced4da'});
                        $('#sOHeight').css({'border':'1px solid #ced4da'});
                    } else if(result.status == 'error2'){
                        $('#intumescentSealArrangement').empty('');
                        // swal('Warning',result.msg);
                        $('#sOWidth').css({'border':'1px solid red'});
                        $('#sOHeight').css({'border':'1px solid red'});
                    }
                }
            });
        }
    }
    function DoorLeafFacingChange(){
        let pageId = pageIdentity();
        var doorLeafFacing =  $("#doorLeafFacing").val();
        var DoorLeafFinishColorValue = document.getElementById('DoorLeafFinishColor-value');
        if (DoorLeafFinishColorValue != null) {
            DoorLeafFinishColorValue = $("#DoorLeafFinishColor-value").data("value");
            if(DoorLeafFinishColorValue == ""){
                $("#doorLeafFinishColor").val("");
            }
        }else{
            $("#doorLeafFinishColor").val("");
        }
        $("#doorLeafFinish").val("");
        $.ajax({
            url:  $("#door-leaf-face-value-filter").html(),
            method:"POST",
            dataType:"Json",
            data:{pageId:pageId,doorLeafFacing:doorLeafFacing,_token:$("#_token").val()},
            success: function(result){

                var DoorLeafFacingValueValue = document.getElementById('DoorLeafFacingValue-value');
                var DoorLeafFinishValue = document.getElementById('DoorLeafFinish-value');
                var DecorativeGrovesValue = document.getElementById('DecorativeGroves-value');
                if(result.status=="ok"){
                    if(doorLeafFacing=='Plywood' || doorLeafFacing=='Pre-Primed'){
                        var innerHtml1 = `
                        <label>Door Leaf Finish</label>
                        <select class="form-control doorLeafFinishSelect">
                        <option value="Primed">Primed</option>
                        <option value="Painted">Painted</option>
                        </select>`;
                        $(".doorLeafFinishDiv").empty().append(innerHtml1);
                        return;
                    }





                    var innerHtml ='';
                    var innerHtml1 ='';
                    var data = result.data;
                    var length = result.data.length;
                    innerHtml+='<option value="">Select Door leaf facing</option>';
                    innerHtml1+='<option value="">Select Door leaf finish</option>';
                    for(var index = 0; index<length;index++){

                        if(data[index].OptionSlug=="door_leaf_facing_value"){

                            if(data[index].UnderAttribute==doorLeafFacing){

                                if (DoorLeafFacingValueValue != null && doorLeafFacingValue == '') {
                                    DoorLeafFacingValueValue = $("#DoorLeafFacingValue-value").data("value");
                                    var DoorLeafFacingValueSelected = "";
                                    if(DoorLeafFacingValueValue == data[index].OptionKey){
                                        DoorLeafFacingValueSelected = "selected";
                                    }
                                    innerHtml+='<option value="'+data[index].OptionKey+'" '+ DoorLeafFacingValueSelected +'>'+data[index].OptionValue+'</option>';
                                }else{
                                    innerHtml+='<option value="'+data[index].OptionKey+'">'+data[index].OptionValue+'</option>';
                                }
                            }
                        }else{
                            if(data[index].UnderAttribute==doorLeafFacing){
                                if (DoorLeafFinishValue != null) {
                                    DoorLeafFinishValue = $("#DoorLeafFinish-value").data("value");
                                    var DoorLeafFinishValue = "";
                                    if(DoorLeafFacingValueValue == data[index].OptionKey){
                                        DoorLeafFinishSelected = "selected";
                                    }
                                    innerHtml1+='<option value="'+data[index].OptionKey+'" '+ DoorLeafFinishSelected +'>'+data[index].OptionValue+'</option>';
                                }else{
                                    innerHtml1+='<option value="'+data[index].OptionKey+'">'+data[index].OptionValue+'</option>';
                                }
                            }
                        }
                    }

                    if(doorLeafFacing=="Laminate" || doorLeafFacing=="PVC"){
                        if(doorLeafFacing=="Laminate"){
                            $("#decorativeGroves").attr({'disabled':true});
                            $('#decorativeGroves option:first').prop('selected', true);
                            $("#grooveLocation").attr({'disabled':true});
                            $('#grooveLocation option:first').prop('selected', true);
                            $("#grooveWidth").val(0).attr({'disabled':true});
                            $("#grooveDepth").val(0).attr({'disabled':true});
                            $("#numberOfGroove").val(0).attr({'disabled':true});
                            $("#numberOfVerticalGroove").val(0).attr({'disabled':true});
                            $("#decorativeGroves").removeAttr('required')
                            $("#numberOfHorizontalGroove").val(0).attr({'disabled':true});

                        }else{

                            $("#decorativeGroves").attr({'required':true});
                            $("#decorativeGroves").attr({'disabled':false});
                        }

                        if (DecorativeGrovesValue != null) {
                            DecorativeGrovesValue = $("#DecorativeGroves-value").data("value");
                            if(DecorativeGrovesValue == ""){
                                $("#decorativeGroves").val('No');
                            }
                        }else{
                            // $("#decorativeGroves").val('');
                        }

                        var color = result.color;
                        $("#ralColorModalLabel").text("Door Leaf Finish");
                        if (DoorLeafFinishValue != null) {
                            DoorLeafFinishValue = $("#DoorLeafFinish-value").data("value");
                        }else{
                            DoorLeafFinishValue = "";
                        }
                        innerHtml1='<label for="doorLeafFacing">Door Leaf Finish</label><div class="input-icons doorLeafFinishInputDiv"><i class="fa fa-info icon" id="doorLeafFinishIcon" onClick="$(\'#ralColor\').modal(\'show\');"></i><input type="text" required  readonly name="doorLeafFinish" id="doorLeafFinish" value="'+ DoorLeafFinishValue +'" class="form-control bg-white"></div>';
                        var innerHtmlPopUp='<div class="container"><div class="row">';
                        if(color!=''){
                            var length = result.color.length;
                            for(var colorIndex =0; colorIndex<length;colorIndex++){
                                innerHtmlPopUp+='<div class="col-md-2 col-sm-4 col-6" onClick="SelectRalColor(\'\','+color[colorIndex].id+',\''+color[colorIndex].Hex+'\',\''+color[colorIndex].ColorName+'\',\''+doorLeafFacing+'\')">';
                                innerHtmlPopUp+='<div class="color_box">';
                                innerHtmlPopUp+='<div class="color_place" style="background:'+color[colorIndex].Hex+'"></div>';
                                innerHtmlPopUp+=' <h4>'+color[colorIndex].ColorName+'</h4>';
                                innerHtmlPopUp+='</div></div>';
                            }
                        }

                        innerHtmlPopUp+='</div></div>';
                        $("#printedColor").empty().append(innerHtmlPopUp);
                        // $("#doorLeafFinishColor").empty().append(innerHtml);

                        // if(color!=''){
                        //     var length = result.color.length;
                        //     for(var colorIndex =0; colorIndex<length;colorIndex++){
                        //     innerHtml1+='<option value="'+color[colorIndex].id+'" style="background:'+color[colorIndex].Hex+'">'+color[colorIndex].ColorName+'</option>'
                        //     }
                        // }


                    }else if(doorLeafFacing=="Veneer" || doorLeafFacing=="Kraft_Paper" || doorLeafFacing=="Plywood"){

                        $("#decorativeGroves").attr({'required':true});
                        $("#decorativeGroves").attr({'disabled':false});
                        if (DecorativeGrovesValue != null) {
                            DecorativeGrovesValue = $("#DecorativeGroves-value").data("value");
                            if(DecorativeGrovesValue == ""){
                                $("#decorativeGroves").val('No');
                            }
                        }else{
                            // $("#decorativeGroves").val('No');
                        }

                        var color ='';
                        var color = result.color;

                        innerHtml1=`
                        <label for="doorLeafFacing" class="">Door Leaf Finish

                            </label>
                        <select required onchange="doorLeafFinishChange();" name="doorLeafFinish" id="doorLeafFinish" class="form-control doorLeafFinishSelect">`;
                        innerHtml1+='<option value="">Select Door Leaf Finish</option>';
                        if(color!=''){
                            var length = result.color.length;
                            for(var colorIndex =0; colorIndex<length;colorIndex++){

                                if (DoorLeafFinishValue != null) {
                                    DoorLeafFinishValue = $("#DoorLeafFinish-value").data("value");

                                    var DoorLeafFinishSelected = "";

                                    if(DoorLeafFinishValue == color[colorIndex].OptionKey){
                                        DoorLeafFinishSelected = "selected";
                                    }

                                    if(color[colorIndex].UnderAttribute=="Veneer"){
                                        innerHtml1+='<option value="'+color[colorIndex].OptionKey+'" '+ DoorLeafFinishSelected +'>'+color[colorIndex].OptionValue+'</option>';
                                    }else{
                                        innerHtml1+='<option value="'+color[colorIndex].OptionKey+'" '+ DoorLeafFinishSelected +'>'+color[colorIndex].OptionValue+'</option>';
                                    }
                                }else{
                                    if(color[colorIndex].UnderAttribute=="Veneer"){
                                        innerHtml1+='<option value="'+color[colorIndex].OptionKey+'">'+color[colorIndex].OptionValue+'</option>';
                                    }else{
                                        innerHtml1+='<option value="'+color[colorIndex].OptionKey+'">'+color[colorIndex].OptionValue+'</option>';
                                    }
                                }
                            }
                        }
                        innerHtml1+='</select>';

                        $("#doorLeafFinishColor").attr({'disabled':true});
                        if (DoorLeafFinishColorValue != null) {
                            DoorLeafFinishColorValue = $("#DoorLeafFinishColor-value").data("value");
                            if(DoorLeafFinishColorValue == ""){
                                $("#doorLeafFinishColor").val("");
                            }
                        }else{
                            $("#doorLeafFinishColor").val("");
                        }
                    }

                    $(".doorLeafFinishDiv").empty().append(innerHtml1);

                    $("#doorLeafFacingValue").empty().append(innerHtml);
                    if(doorLeafFacing=='Kraft_Paper'){
                        $("#doorLeafFacingValue").attr({'disabled':true});
                        var noFacingValue='';
                        noFacingValue+='<option value="">No Door facing Value found</option>';
                        $("#doorLeafFacingValue").empty().append(noFacingValue);
                    }else{
                        $("#doorLeafFacingValue").attr({'disabled':false});
                        $("#doorLeafFinishColor").removeClass("bg-white");
                        $("#doorLeafFinishColor").val('').attr({'disabled':true});
                        $("#doorLeafFinishColorIcon").attr("onclick","");
                    }
                }

                else{
                    if( doorLeafFacing=="Kraft_Paper" ){
                        var color ='';
                        var color = result.color;
                        innerHtml1='';
                        if(color!=''){
                            var length = result.color.length;
                            innerHtml1+='<option value="">Select face finish</option>';
                            for(var colorIndex =0; colorIndex<length;colorIndex++){
                                if (DoorLeafFinishValue != null) {
                                    DoorLeafFinishValue = $("#DoorLeafFinish-value").data("value");
                                    var DoorLeafFinishSelected = "";
                                    if(DoorLeafFinishValue == color[colorIndex].OptionKey){
                                        DoorLeafFinishSelected = "selected";
                                    }

                                    if(color[colorIndex].UnderAttribute=="Kraft_Paper"){
                                        innerHtml1+='<option value="'+color[colorIndex].OptionKey+'" '+ DoorLeafFinishSelected +'>'+color[colorIndex].OptionValue+'</option>';
                                    }
                                }else{
                                    if(color[colorIndex].UnderAttribute=="Kraft_Paper"){
                                        innerHtml1+='<option value="'+color[colorIndex].OptionKey+'">'+color[colorIndex].OptionValue+'</option>';
                                    }
                                }
                            }
                        }
                        $("#doorLeafFinish").empty().append(innerHtml1);
                        $("#doorLeafFinishColor").attr({'disabled':true});
                        if (DoorLeafFinishColorValue != null) {
                            DoorLeafFinishColorValue = $("#DoorLeafFinishColor-value").data("value");
                            if(DoorLeafFinishColorValue == ""){
                                $("#doorLeafFinishColor").val("");
                            }
                        }else{
                            $("#doorLeafFinishColor").val("");
                        }
                    }else{
                        $("#doorLeafFinish").empty().append('<option value="">No Door leaf Finish found</option>');

                        $("#doorLeafFinishColor").removeClass("bg-white");
                        $("#doorLeafFinishColor").val('').attr({'disabled':true});
                        $("#doorLeafFinishColorIcon").attr("onclick","");
                    }
                    $("#doorLeafFacingValue").empty().append('<option value="">No Door leaf facing value found</option>');
                }
            }
        });


    }


    function GlazingSystemsChange(id = null){

        var glazingSystems = (id == null)?$("#glazingSystems").val():id;

        if(glazingSystems != ''){
            let pageId = pageIdentity();
            $.ajax({
                url:  $("#glazing-thikness-filter").html(),
                method:"POST",
                dataType:"Json",
                data:{pageId:pageId,glazingSystems:glazingSystems,_token:$("#_token").val()},
                success: function(result){console.log(result)
                    if(result.status=="ok"){
                        var data = result.data;
                        var data2 = result.data2;
                        if(data != ''){
                            $("#glazingSystemsThickness").val(data[0].OptionValue);
                        } else {
                            $('#glazingSystemsThickness').val('');
                        }
                        if(data2 != '' && data2 != null){
                            $('#glazingBeadsFixingDetail').val(data2.OptionValue);
                        } else {
                            $('#glazingBeadsFixingDetail').val(''); console.log('2222')
                        }
                    } else {
                        $("#glazingSystemsThickness").val(0);
                    }
                }
            });
        }
    }

    function GlassTypeChange(id = null){

        var glassType = (id == null)?$("#glassType").val():id;

        if(glassType != ''){
            let pageId = pageIdentity();
            $.ajax({
                url:  $("#glass-type-filter").html(),
                method:"POST",
                dataType:"Json",
                data:{pageId:pageId,glassType:glassType,_token:$("#_token").val()},
                success: function(result){
                    if(result.status=="ok"){
                        var innerHtml ='';
                        var data = result.data;
                        var length = result.data.length;
                        innerHtml+='<option value="">Select Glass thikness</option>';


                        var GlassThicknessValue = document.getElementById('GlassThickness-value');


                        for(var index =0; index<length;index++){

                            if (GlassThicknessValue != null) {
                                GlassThicknessValue = $("#GlassThickness-value").data("value");

                                var GlassThicknessSelected = "";
                                if(GlassThicknessValue == data[index].OptionKey){
                                    GlassThicknessSelected = "selected";
                                }

                                innerHtml+='<option value="'+data[index].OptionKey+'" '+GlassThicknessSelected+'>'+data[index].OptionValue+'</option>';

                            }else{
                                innerHtml+='<option value="'+data[index].OptionKey+'">'+data[index].OptionValue+'</option>';

                            }


                        }
                        $("#glassThickness").empty().append(innerHtml);
                        // $("#glassThickness").val(data[0].OptionValue);
                    }else{
                        $("#glassThickness").val(0);
                    }
                }
            });
        }
    }
    function opGlassTypeFilter(id = null){
        let pageId = pageIdentity();
        let fireRating =$("#fireRating").val();
        let opGlassIntegrity = (id == null)?$("#opGlassIntegrity").val():id;
        $.ajax({
            url: $("#opGlassTypeFilterUrl").html(),
            method:"POST",
            dataType:"Json",
            data:{pageId:pageId,fireRating:fireRating,opGlassIntegrity:opGlassIntegrity,_token:$('input[name="token"]').val()},
            success: function(result){
                var OPGlassTypeInnerHtml = "";

                var OPGlassTypeValue = document.getElementById('OPGlassType-value');

                if(result.status=="ok"){
                    var data = result.data;
                    var length = result.data.length;
                    OPGlassTypeInnerHtml+='<option value="">Select OP Glass Type</option>';
                    for(var i =0; i<length;i++){

                        var selected = "";

                        if (OPGlassTypeValue != null) {
                            OPGlassTypeValue = $("#OPGlassType-value").data("value");
                            if(OPGlassTypeValue == data[i].OptionKey){
                                selected = "selected";
                            }
                        }

                        OPGlassTypeInnerHtml += '<option value="'+data[i].OptionKey+'" '+selected+'>'+data[i].OptionValue+'</option>';
                    }
                    $("#opGlassType").empty().append(OPGlassTypeInnerHtml);
                } else {
                    OPGlassTypeInnerHtml += '<option value="">No OP Glass Type Found</option>';
                    $("#opGlassType").empty().append(OPGlassTypeInnerHtml);
                }
            },
            error: function(data) {
                swal("Oops!!", "Something went wrong. Please try again.", "error");
            }
        });
    }
    function FrameFinishChange(showModal = false , typeinput){
        let pageId = pageIdentity();
        if(typeinput == 'framefinish'){
            var doorLeafFinish =  $("#frameFinish").val();
            $('#ralColorModalLabel').html('Frame Finish Color');
        } else if(typeinput == 'architraveFinish'){
            var doorLeafFinish =  $("#architraveFinish").val();
            $('#ralColorModalLabel').html('Architrave Finish Color');
        }
       var FrameFinishColorValue = document.getElementById('FrameFinishColor-value');
        if(doorLeafFinish=="Painted_Finish"){
            if(typeinput == 'architraveFinish'){
                $("#architraveFinishcolor").attr({'disabled':false});
                $('#architraveFinishcolor').addClass("bg-white");
            }
            $.ajax({
                url:  $("#ral-color-filter").html(),
                method:"POST",
                dataType:"Json",
                data:{pageId:pageId,doorLeafFinish:'Painted',_token:$("#_token").val()},
                success: function(result){
                    if(result.status=="ok"){
                        var innerHtml ='';
                        var data = result.data;
                        var length = result.data.length;
                        // innerHtml+='<option value="">Select Door leaf color value</option>';
                        innerHtml+='<div class="container"><div class="row">';
                        innerHtml+='<div id="field" hidden>'+doorLeafFinish+'</div>';
                        $("#doorLeafFinishColor").attr({'disabled':false});
                        $("#framefinishColor").attr({'disabled':false});


                        for(var index =0; index<length;index++){
                            if(FrameFinishColorValue != null){
                                FrameFinishColorValue = $("#FrameFinishColor-value").data("value");
                                if(FrameFinishColorValue == data[index].id){
                                    var select ='<option value="'+data[index].id+'" selected>'+data[index].ColorName+'</option>';
                                    $("#framefinishColor").empty().append(select);
                                }

                            }
                            innerHtml+='<div class="col-md-2 col-sm-4 col-6" onClick="SelectRalColor(\''+typeinput+'\','+data[index].id+',\''+data[index].Hex+'\',\''+data[index].ColorName+'\',\''+doorLeafFinish+'\')">';
                            innerHtml+='<div class="color_box">';
                            innerHtml+='<div class="color_place" style="background:'+data[index].Hex+'"></div>';
                            innerHtml+=' <h4>'+data[index].ColorName+'</h4>';
                            innerHtml+='</div></div>';
                            // innerHtml+='<option value="'+data[index].Hex+'" style="background:'+data[index].Hex+'">'+data[index].ColorName+'</option>'
                        }
                        innerHtml+='</div></div>';
                        $("#printedColor").empty().append(innerHtml);
                        // $("#doorLeafFinishColor").empty().append(innerHtml);
                        if(showModal == true){
                            $("#ralColor").modal('show');
                        }
                    } else {
                        $("#doorLeafFinishColor").empty().append('<option value="">No Door leaf Ral Color Found</option>');
                    }
                }
            });
        } else {
            $('input[name="architraveFinishcolor"]').val('');
            $('#architraveFinishcolor').removeClass('bg-white');
            $("#framefinishColor").val('').attr({'disabled':true});
            $("#doorLeafFinishColor").val('').attr({'disabled':true});
            if(typeinput == 'architraveFinish'){
                $("#architraveFinishcolor").html('<option value="">Architrave  Finish  Color</option>');
                $("#architraveFinishcolor").val('').attr({'disabled':true});
            }
        }
    }


    function doorLeafFinishChange(){

        // let pageId = pageIdentity();
        var doorLeafFinish =  $("#doorLeafFinish").val();
        var e = document.getElementById("doorLeafFinish");
        var ActualValue = e.options[e.selectedIndex].value;
        var ElementValue = e.options[e.selectedIndex].text;
        var DoorLeafFinishValue = document.getElementById('DoorLeafFinish-value');
        if(DoorLeafFinishValue != null){
            DoorLeafFinishValue = $("#DoorLeafFinish-value").data("value");
            if(DoorLeafFinishValue != ""){
                doorLeafFinish = ActualValue = ElementValue = DoorLeafFinishValue;
            }
        }
        var Options = JSON.parse(OptionsJson);
        var price = 0.00;
        Options.forEach(function(elem, index) {
            if(ActualValue == elem.OptionKey){
                price = elem.OptionCost;
            }
        });
        var name = $("#doorLeafFinish").attr("name");
        $("#"+name+"-selected").empty().text(ElementValue);
        $("#"+name+"-price").empty().text("£" + price);
        $("#"+name+"-section").removeClass("table_row_hide");
        $("#"+name+"-section").addClass("table_row_show");
        if(doorLeafFinish=="Painted" || doorLeafFinish == 'Paint_Finish'){
            $('.SheenLevel').css({'display':'none'});
            $("#doorLeafFinishColor").addClass("bg-white");
            $.ajax({
                url:  $("#ral-color-filter").html(),
                method:"POST",
                dataType:"Json",
                data:{doorLeafFinish:doorLeafFinish,_token:$("#_token").val()},
                success: function(result){console.log(result)
                    if(result.status=="ok"){

                        var innerHtml ='';
                        var innerHtml1 ='';
                        var data = result.data;
                        var length = result.data.length;
                        // innerHtml+='<option value="">Select Door leaf color value</option>';
                        innerHtml+='<div class="container"><div class="row">';
                        $("#ralColorModalLabel").text("Door Leaf Finish Color");
                        $("#doorLeafFinishColor").attr({'disabled':false});
                        for(var index =0; index<length;index++){
                            innerHtml+='<div class="col-md-2 col-sm-4 col-6" onClick="SelectRalColor(\'\','+data[index].id+',\''+data[index].Hex+'\',\''+data[index].ColorName+'\',\''+doorLeafFinish+'\')">';
                            innerHtml+='<div class="color_box">';
                            innerHtml+='<div class="color_place" style="background:'+data[index].Hex+'"></div>';
                            innerHtml+=' <h4>'+data[index].ColorName+'</h4>';
                            innerHtml+='</div></div>';
                            // innerHtml+='<option value="'+data[index].Hex+'" style="background:'+data[index].Hex+'">'+data[index].ColorName+'</option>'
                        }
                        innerHtml+='</div></div>';
                        $("#printedColor").empty().append(innerHtml);
                        // $("#doorLeafFinishColor").empty().append(innerHtml);
                        // $("#ralColor").modal('show');
                        $("#doorLeafFinishColorIcon").attr("onclick","$('#ralColor').modal('show')");
                    } else {
                        $("#doorLeafFinishColor").empty().append('<option value="">No Door leaf Ral Color Found</option>');
                    }
                }
            });
        } else {
            $("#doorLeafFinishColor").removeClass("bg-white");
            $("#doorLeafFinishColor").val('').attr({'disabled':true});
            $("#doorLeafFinishColorIcon").attr("onclick","");
            $('.SheenLevel').css({'display':'none'});
            if(doorLeafFinish == "Laqure_Finish"){
                $('.SheenLevel').css({'display':'block'});
            }
        }
    }
    function IronMongery(ironCategoryType,ironCategoryName){
        var data = $("#ironIronmongerydata").val();
        if(data!=''){
            data =  JSON.parse(data);
            var lenght = data.length;
            innerHtml = '';
            for(var index = 0; index<lenght;index++){
                if(data[index].Category==ironCategoryType){
                    var image = $("#url").html()+'/uploads/IronmongeryInfo/'+data[index].Image;
                    innerHtml+=' <div class="col-md-4 col-sm-6 col-6">';
                    innerHtml+='<div class="product_holder">';
                    innerHtml+='<div class="product_img"><img src="'+image+'"></div>';
                    innerHtml+='<a class="product_name" href="#"><span>'+data[index].Code+'-</span> '+data[index].Name+'</a>';
                    innerHtml+='<div class="product_face">';
                    innerHtml+='<b>'+data[index].FireRating+'</b>';
                    innerHtml+='<b>$'+data[index].Price+'</b>';
                    innerHtml+='<b>'+data[index].Category+'</b>';
                    innerHtml+='</div>';
                    innerHtml+='<a href="javascript:void(0);" onClick="makeOption('+data[index].id+',\''+data[index].Name+'\',\''+data[index].Code+'\',\''+ironCategoryType+'\')" class="product_edit">Select</a>';
                    innerHtml+='</div></div>';
                }
            }
            if(innerHtml==''){
                innerHtml+='<div class=" col-md-12 alert alert-danger" role="alert"> No '+ ironCategoryName.toLowerCase() +' found </div>';
            }
        }else{
            innerHtml = '';
            innerHtml+='<div class=" col-md-12 alert alert-danger" role="alert"> No '+ ironCategoryName.toLowerCase() +' found </div>';
        }
        $("#content").empty().append(innerHtml);
        $("#modalTitle").empty().append('Select '+ironCategoryName);
        $("#iron").modal('show');
    }
    function makeOption(id,name,code,category){
        $("#"+category+'Value').val(id);
        $("#"+category+'Key').val(code+'-'+name);
        $("#iron").modal('hide');
    }
    function SelectRalColor( typeinput,id,code,name,fieldname){
        var innerHtml ='';
        innerHtml+='<option value="'+code+'" style="background:'+code+'">'+name+'</option>'
        if(fieldname=="Painted" || fieldname=="Paint_Finish"){
            // $("#doorLeafFinishColor").empty().append(innerHtml);
            $("#doorLeafFinishColorIcon").show();
            // $("#doorLeafFinishColor").val(code);
            $("#doorLeafFinishColor").val(name);
            var Colors = JSON.parse(ColorsJson);
            var price = 0.00;
            Colors.forEach(function(elem, index) {
                if(id == elem.id){
                    price = elem.ColorCost;
                    console.log("Color id is = ",index);
                }
            });
            $("#doorLeafFinishColor-selected").empty().text(name);
            $("#doorLeafFinishColor-price").empty().text("£" + price);
            $("#doorLeafFinishColor-section").removeClass("table_row_hide");
            $("#doorLeafFinishColor-section").addClass("table_row_show");
        }else if(fieldname=="Laminate" || fieldname=="PVC"){
            // $("#doorLeafFinishColor").empty().append(innerHtml);
            $("#doorLeafFinishIcon").show();
            $("#doorLeafFinish").val(name);
            var Colors = JSON.parse(ColorsJson);
            var price = 0.00;
            Colors.forEach(function(elem, index) {
                if(id == elem.id){
                    price = elem.ColorCost;
                    console.log("Color id is = ",index);
                }
            });
            $("#doorLeafFinishColor-selected").empty().text(name);
            $("#doorLeafFinishColor-price").empty().text("£" + price);
            $("#doorLeafFinishColor-section").removeClass("table_row_hide");
            $("#doorLeafFinishColor-section").addClass("table_row_show");
        } else if(fieldname=="Painted_Finish"){
            if(typeinput == 'architraveFinish'){
                // $("#architraveFinishcolor").empty().append('<option value="'+id+'">'+name+'</option>');
                $("#architraveFinishcolor").val(name)
                $("input[name='architraveFinishcolor']").val(id)
            } else if(typeinput == 'framefinish'){
                $("#framefinishColor").empty().append('<option value="'+id+'">'+name+'</option>');
            }

            var Colors = JSON.parse(ColorsJson);
            var price = 0.00;
            Colors.forEach(function(elem, index) {
                if(id == elem.id){
                    price = elem.ColorCost;
                    console.log("Color id is = ",index);
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
    function SelectValueFill(id, key, value, modalId, data = []){

        let inputIdentity = $('.inputIdentity').val();
        if(inputIdentity == 'ArchitraveMaterial'){
            $('#architraveMaterial').val(value);
            $('input[name="architraveMaterial"]').val(key);
            $('#UniversalModal').modal('hide');
        } else{

            if(id == "#frameMaterial"){
                $(id).val(value);
                $("#frameMaterialNew").val(key);
            }else{
                $(id).val(key);
            }
            $(modalId).modal('hide');
            var price = 0;
            $(id + "-selected").empty().text(value);
            $(id + "-price").empty().text("£" + price);
            $(id + "-section").removeClass("table_row_hide");
            $(id + "-section").addClass("table_row_show");
            if(value == 'MDF'){
                $('#frameMaterialNew').empty().val(value);
                IntumescentSeals();
            }
        }
    }

    function OpenglazingModal(labelname,inputId){
        $('#glazingModalLabel').html(labelname);
        $('#inputId').val(inputId);
        $('#glazingModal').modal('show');
    }
    function OpenArchitraveMaterialModal(labelname,inputId){
        $('#glazingModalLabel').html(labelname);
        $('#inputId').val(inputId);
        $('#glazingModal').modal('show');
    }
    function GlazingValueFill(id,value,modalId){
        const inputId = $('#inputId').val();
        $('input[id='+inputId+']').val(value);
        $('input[name='+inputId+']').val(id);
        $(modalId).modal('hide');
        var LippingSpecies = JSON.parse(LippingSpeciesJson);
        var price = 0;
        LippingSpecies.forEach(function(elem, index) {
            if(id == elem.id){
                price = elem.LippingSpeciesCost;
            }
        });
        $("#"+ inputId +"-selected").empty().text(value);
        $("#"+ inputId +"-price").empty().text("£" + price);
        $("#"+ inputId +"-section").removeClass("table_row_hide");
        $("#"+ inputId +"-section").addClass("table_row_show");
    }
    function DoorFrameConstruction(id,OptionKey,OptionValue){
        $('input[id="frameCostuction"]').val(OptionValue);
        $('input[name="frameCostuction"]').val(OptionKey);
        $('#DoorFrameConstructionModal').modal('hide');
        var price = 0;
        $(id + "-selected").empty().text(OptionValue);
        $(id + "-price").empty().text("£" + price);
        $(id + "-section").removeClass("table_row_hide");
        $(id + "-section").addClass("table_row_show");
    }




    function LeafCoreCalculation(){
        const doorsetTypeValue_B = $("#doorsetType").val();
        const tolleranceValue_B = $('#tollerance').val();
        const frameThicknessValue_B = $('#frameThickness').val();
        const gapValue_B = $('#gap').val();

        if(doorsetTypeValue_B == 'SD'){
            let leafWidth1 = soWidth-(tolleranceValue_B*TolleranceAdditionalNumber)-(frameThicknessValue_B*FrameThicknessAdditionalNumber)-(gapValue_B*GapAdditionalNumber);
            // $('#leafWidth1').val(leafWidth1).attr('readonly',true);
            // $('#leafWidth2').val('').attr('readonly',true);
        } else if(doorsetTypeValue_B == 'DD'){
            let leafWidth1 = (soWidth-(tolleranceValue_B*TolleranceAdditionalNumber)-(frameThicknessValue_B*FrameThicknessAdditionalNumber)-(gapValue_B*GapAdditionalNumber))/2;
            // $('#leafWidth1').val(leafWidth1).attr('readonly',true);
            let leafWidth2 = leafWidth1;
            // $('#leafWidth2').val(leafWidth2).attr('readonly',true);
        } else if(doorsetTypeValue_B == 'leaf_and_a_half'){
            $('#leafWidth1').attr('readonly',false);
            let leafWidth1 = $('#leafWidth1').val();
            let leafWidth2 = soWidth-(tolleranceValue_B*TolleranceAdditionalNumber)-(gapValue_B*GapAdditionalNumber)-(frameThicknessValue_B*FrameThicknessAdditionalNumber)-parseInt(leafWidth1);
            // $('#leafWidth2').val(leafWidth2).attr('readonly',true);
        }
    }

    // Architrave Material needs to work exactly the same as ‘Frame Material’ so the exact same options needs to be here.
    function ArchitraveMaterial(){
        $('#UniversalModalLabel').html('Architrave Material');
        $('#UniversalModal').modal('show');
        $('.inputIdentity').val('ArchitraveMaterial');
    }
    function FrameMaterial(){
        $('.inputIdentity').val('FrameMaterial');
        $('#frameMaterialModal').modal('show');
    }


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Error Code
    function selectIronMongery(){
        $.ajax({
            url:  $("#filter-iron-mongery-category").html(),
            method:"POST",
            // data:{ironCategoryType:ironCategoryType,_token:$("#_token").val()},
            data:{_token:$("#_token").val()},
            dataType:"Json",
            success: function(result){
                if(result.status=="ok"){
                    $("#ironIronmongerydata").val(JSON.stringify(result.data));

                }else{
                    $("#ironIronmongerydata").html('');
                }
            }
        });
    }




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Not relate to any one
    $("#overpanel1").change(function(){
        var tollerance = 0;
        var gap = 0;
        var framethikness = 0;
        var soWidth = 0;
        var soheight=0;
        var undercut=0;
        randomkey = 2;
        if($("#overpanel").val()=="No"){
            var thisvalue = document.getElementsByClassName("foroPWidth");
            for (var i = 0; i < thisvalue.length; i++) {
                if(thisvalue[i].name=='tollerance'){
                    if(thisvalue[i].value==''){
                        tollerance = 0;
                    } else {
                        tollerance = parseInt(thisvalue[i].value);
                    }
                }
                if(thisvalue[i].name=='gap'){
                    if(thisvalue[i].value==''){
                        gap = 0;
                    }
                    else{
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

                if(thisvalue[i].name=='sOWidth'){
                    if(thisvalue[i].value==''){
                        soWidth = 0;
                    } else {
                        soWidth = parseInt(thisvalue[i].value);
                    }
                }
                if(thisvalue[i].name=='sOHeight'){
                    if(thisvalue[i].value==''){
                        soheight = 0;
                    } else {
                        soheight = parseInt(thisvalue[i].value);
                    }
                }
                if(thisvalue[i].name=='undercut'){
                    if(thisvalue[i].value==''){
                        undercut = 0;
                    } else {
                        undercut = parseInt(thisvalue[i].value);
                    }
                }
            }
            var leafHeightNoOP = soheight-tollerance-framethikness-undercut-gap;
            // $("#leafHeightNoOP").val(leafHeightNoOP).attr('readonly',true);
            $("#SL1Height").val(leafHeightNoOP).attr('readonly',true);
            var plantonStopHeight = soheight-tollerance;
            //$("#plantonStopHeight").val(plantonStopHeight);
            $("#frameHeight").val(plantonStopHeight);
            var frameDepth = $("#sODepth").val()!=''?$("#sODepth").val():0;
            // $("#frameDepth").val(frameDepth);
            $("#leafHeightwithOP").val(0).attr('readonly',true);
            $("#oPWidth").val(0).attr('readonly',true);
            $("#oPHeigth").val(0).attr('readonly',true);
            $("#OPLippingThickness").val('').attr('disabled','disabled');
            $("#transomThickness").val('').attr('disabled','disabled');
            $("#opTransom").val('').attr('disabled','disabled');
        } else {
            //$("#leafHeightNoOP").val(0).attr('readonly',false);
            $("#leafHeightNoOP").attr('readonly',false);
            $("#SL1Height").attr('readonly',false);
            $("#leafHeightwithOP").attr('readonly',false);
            $("#oPWidth").attr('readonly',true);
            $("#oPHeigth").attr('readonly',false);
            $("#OPLippingThickness").val('').attr('disabled',false);
            $("#transomThickness").val('').attr('disabled',false);
            $("#opTransom").val('').attr('disabled',false);
        }
    });
    $("#visionPanelQuantityforLeaf21").change(function(){
        var eqllSizeLeaf = $("#AreVPsEqualSizesForLeaf2").val();
        var vp2Quantity = $(this).val();
        $("#vP2Width").attr({'readonly':false,'required':true});
        $("#vP2Height1").attr({'readonly':false,'required':true});
        $("#distanceFromTopOfDoorforLeaf2").attr({'readonly':false,'required':true});
        $("#distanceFromTheEdgeOfDoorforLeaf2").attr({'readonly':false,'required':true});
        //$("#AreVPsEqualSizesForLeaf2").attr({'readonly':false,'required':true});
        $("#AreVPsEqualSizesForLeaf2").attr({'readonly':false});
        if(vp2Quantity>1){
            $("#distanceBetweenVPsforLeaf2").attr({'readonly':false,'required':true}).val('');
            //$("#AreVPsEqualSizesForLeaf2").attr({'readonly':false,'required':true});
            $("#AreVPsEqualSizesForLeaf2").attr({'readonly':false});
            if(eqllSizeLeaf=="No"){
                for(var index=2;index<=parseInt(vp2Quantity);index++){
                    $("#vP2Height"+index).attr({'readonly':false,'required':true}).val('');
                }
                for(var i=parseInt(vp2Quantity);index<=5;index++){
                    $("#vP2Height"+index).attr({'readonly':true,'required':false}).val('');
                }
            } else {
                for(var index=2;index<=5;index++){
                    $("#vP2Height"+index).attr({'readonly':true,'required':false}).val('');
                }
            }
        } else {
            //$("#AreVPsEqualSizesForLeaf2").attr({'readonly':true,'required':false}).val('');
            $("#AreVPsEqualSizesForLeaf2").attr({'readonly':true}).val('');
            $("#distanceBetweenVPsforLeaf2").attr({'readonly':'disabled','required':false}).val('');
            for(var index=2;index<=5;index++){
                $("#vP2Height"+index).attr({'readonly':true,'required':false}).val('');
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
    $(".for_Leaf_Width_1").change(function(){
        var tollerance = 0;
        var soWidth = 0;
        var framethikness = 0;
        var gap = 0;
        var leaf_width_1 = 0;
        var randomkey = 2;
        var thisvalue = document.getElementsByClassName("for_Leaf_Width_1");
        for (var i = 0; i < thisvalue.length; i++) {
            if(thisvalue[i].name=='tollerance'){
                if(thisvalue[i].value==''){
                    tollerance = 0;
                }
                else{
                    tollerance = parseInt(thisvalue[i].value);
                }
            }

            else if(thisvalue[i].name=='sOWidth'){
                if(thisvalue[i].value==''){
                    soWidth = 0;
                }
                else{
                    soWidthsoWidth = parseInt(thisvalue[i].value);
                }
            }

            else if(thisvalue[i].name=='gap'){
                if(thisvalue[i].value==''){
                    gap = 0;
                }
                else{
                    gap = parseInt(thisvalue[i].value);
                }
            }

            else if(thisvalue[i].name=='frameThickness'){
                if(thisvalue[i].value==''){
                    framethikness = 0;
                }
                else{
                    framethikness = parseInt(thisvalue[i].value);
                }
            }
        }

        DoorSetTypeChange();

        if($("#doorsetType").val()=="DD" ){

            var leafWidth1 = (soWidth-(tollerance*TolleranceAdditionalNumber)-(framethikness*FrameThicknessAdditionalNumber)-(GapAdditionalNumber*gap))/2;
            // $("#leafWidth2").val(leafWidth1);
            // $("#leafWidth1").val(leafWidth1);

            // sadique code
            // Rround down the field ‘Maximum Number of Groove’
            // for example, if the value there is 20.42 it should be 20.
            // Leaf Width 1 with Groove Location = Horizontal
            var grooveLocationValue = $('#grooveLocation').val();
            if(grooveLocationValue == "Vertical" && leafWidth1 != ''){
                $("#maxNumberOfGroove").val(Math.round((parseInt(leafWidth1)/100)));
            }
        } else if($("#doorsetType").val()=="SD"){
            var leafWidth1 = soWidth-(tollerance*TolleranceAdditionalNumber)-(framethikness*FrameThicknessAdditionalNumber)-(GapAdditionalNumber*gap);
            // $("#leafWidth2").val(0);
            // $("#leafWidth1").val(leafWidth1);

            // sadique code
            // Rround down the field ‘Maximum Number of Groove’
            // for example, if the value there is 20.42 it should be 20.
            // Leaf Width 1 with Groove Location = Horizontal
            var grooveLocationValue = $('#grooveLocation').val();
            if(grooveLocationValue == "Vertical" && leafWidth1 != ''){
                $("#maxNumberOfGroove").val(Math.round((parseInt(leafWidth1)/100)));
            }
        } else {
            var leafWidth1 = 0;
            $("#leafWidth1").val('').attr('readonly',false);
            if($("#leafWidth1").val()!='' && parseInt( $("#leafWidth1").val())){
                leafWidth1 = parseInt( $("#leafWidth1").val());

                // sadique code
                // Rround down the field ‘Maximum Number of Groove’
                // for example, if the value there is 20.42 it should be 20.
                // Leaf Width 1 with Groove Location = Horizontal
                var grooveLocationValue = $('#grooveLocation').val();
                if(grooveLocationValue == "Vertical" && leafWidth1 != ''){
                    $("#maxNumberOfGroove").val(Math.round((parseInt(leafWidth1)/100)));
                }
            }
            var leafWidth2 = soWidth-(tollerance*TolleranceAdditionalNumber)-(framethikness*FrameThicknessAdditionalNumber)-(GapAdditionalNumber*gap)-leafWidth1;
            // $("#leafWidth2").val(leafWidth2);

        }
    })

    $("#AreVPsEqualSizesForLeaf21").change(function(){
        var vp2Quantity = $("#visionPanelQuantityforLeaf2").val();
        var eqllSizeLeaf = $(this).val();
        $("#vP2Width").attr({'readonly':false,'required':true});
        $("#vP2Height1").attr({'readonly':false,'required':true});
        $("#distanceFromTopOfDoorforLeaf2").attr({'readonly':false,'required':true});
        $("#distanceFromTheEdgeOfDoorforLeaf2").attr({'readonly':false,'required':true});

        if(eqllSizeLeaf=="Yes"){

            $("#distanceBetweenVPsforLeaf2").attr({'readonly':true,'required':false});
            for(var index=2;index<=5;index++){
                $("#vP2Height"+index).attr({'readonly':true,'required':false}).val('');
            }


        }else{

            if(vp2Quantity>1){
                $("#distanceBetweenVPsforLeaf2").attr({'readonly':false,'required':true}).val('');

                for(var index=2;index<=parseInt(vp2Quantity);index++){
                    $("#vP2Height"+index).attr({'readonly':false,'required':true}).val('');
                }
                for(var index=parseInt(vp2Quantity)+1;index<=5;index++){
                    $("#vP2Height"+index).attr({'readonly':true,'required':false}).val('');
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

// sadique change upper code







// sadique code











///////////////





$("#fireRating").on("change",function(){
    var filter_swing_type = $("#filter_swing_type").val();
    var firerating = $(this).val();

    $.ajax({
        url:filter_swing_type,
        type:"GET",
        data:{firerating:$(this).val()},
        success:function(data){
           if(firerating!='NFR'){
            var option_data = `<option value=''>Select swing type</option><option value='${data.OptionKey}'>${data.OptionValue}</option>`;
           }
           else{
           var option_data = `<option value=''>Select swing type</option>`;
           data.map((row)=>(
            option_data += `<option value='${row.OptionKey}'>${row.OptionValue}</option>`
           ))
           }
            $("#swingType").empty().append(option_data);
            $("#append_input_field").empty();
            $("#DoorDimensions").val("");
        }
    })
})


$("#DoorDimensionsIcon").on("click",function(){
    var url = $("#door_dimension_url").val();
    var base_url = $("#base_url").val();
    var door_leaf_facing = $("#doorLeafFacing").val();
    var door_leaf_finish = $(".doorLeafFinishSelect").val();
    var leaf_type = $("#leafConstruction").val();
    var firerating = $("#fireRating").val();
    $.ajax({
        url:url,
        type:"GET",
        data:{door_leaf_facing:door_leaf_facing, door_leaf_finish:door_leaf_finish, leaf_type:leaf_type, firerating,firerating},
        success:function(data){
            var result = data.map((row)=>(
                `<div class="col-md-2 col-sm-4 col-6 cursor-pointer" data-dismiss="modal" onclick="DoorDimensionValueFill(${row.id},'${row.code}',${row.mm_width},${row.mm_height})"><div class="color_box"><div class="frameMaterialImage"><img width="100%" height="100" src="${base_url}/DoorDimension/${row.image}"></div><h4>${row.code}-${row.mm_height}x${row.mm_width}</h4></div></div>`
            ))
           $("#DoorDimensionBody").empty().append(result)

        }
    })


        var tollerance = $("#tollerance").val();

        setTimeout(() => {
            var so_width = parseInt($("#sOWidth").val());
            var so_height = parseInt($("#sOHeight").val());
            $("#frameWidth").val(so_width-(parseInt(tollerance)*2));
            $("#frameHeight").val(so_height-parseInt(tollerance));
        }, 500);



})


function DoorDimensionValueFill(id,value,mm_width, mm_height){
        var tollerance = $("#tollerance").val();
        var frame_thickness = $("#frameThickness").val();
        var gap = $("#gap").val();
        var undercut = $("#undercut").val();
        $("#append_input_field").empty().append(`<input type="hidden" name="DoorDimensionId" value="${id}">`);
        $("#DoorDimensions").val(value);
        $("#leafWidth1").val(mm_width);
        $("#leafWidth2").val(mm_width);
        $("#leafHeightNoOP").val(mm_height);

        if(mm_height==''){
            mm_height = 0;
        }

        if(tollerance==''){
            tollerance = 0;
        }

        if(frame_thickness==''){
            frame_thickness = 0;
        }

        if(undercut==''){
            undercut = 0;
        }

        if(gap==''){
            gap = 0;
        }

        var so_width = parseInt(mm_width)+parseInt(tollerance)*2+parseInt(frame_thickness)*2+parseInt(gap)*2
        $("#sOWidth").val(so_width)
        $("#leafHeightNoOP").val(mm_height)


         var soHeight = parseInt(mm_height)+parseInt(tollerance)+parseInt(frame_thickness)+parseInt(undercut)+parseInt(gap)

         $("#sOHeight").val(soHeight)

         $("#frameWidth").val(so_width-(parseInt(tollerance)*2));

         var so_height = parseInt($("#sOHeight").val());
         $("#frameHeight").val(so_height-(parseInt(tollerance)));

}

function lipping_Thickness(fireRating){
    let pageId = pageIdentity();
    $.ajax({
        url: $("#lipping-thickness").html(),
        method:"POST",
        dataType:"Json",
        data:{pageId:pageId,fireRating:fireRating,_token:$("#_token").val()},
        success: function(result){
            if(result.status=="ok"){
                var innerHtml ='';
                var data = result.data;
                var length = result.data.length;
                innerHtml+='<option value="">Select leaping thickness</option>';
                for(var i =0; i<length;i++){
                    innerHtml+='<option value="'+data[i].OptionKey+'">'+data[i].OptionValue+'</option>';
                }
                $("#lippingThickness").empty().append(innerHtml);
            } else {
                innerHtml+='<option value="">No lipping Thickness Found</option>';
                $("#lippingThickness").empty().append(innerHtml);
            }
        }
    });
}

$("#tollerance, #gap, #frameThickness, #doorsetType").on("change",function(){
    var tollerance = $("#tollerance").val();
    var frame_thickness = $("#frameThickness").val();
    var gap = $("#gap").val();
    var mm_width = $("#leafWidth1").val();
    var mm_height = $("#leafHeightNoOP").val();
    var leaf_width_2 = $("#leafWidth2").val();
    var undercut = $("#undercut").val();
    if(mm_width==null || mm_width==''){
        mm_width = 0
    }


    if(tollerance==''){
        tollerance = 0;
    }

    if(frame_thickness==''){
        frame_thickness = 0;
    }

    if(gap==''){
        gap = 0;
    }

    if(undercut==''){
        undercut = 0;
    }


    $("#leafWidth1").attr('readonly','readonly')
    var door_set_type = $("#doorsetType").val();
    if(door_set_type=='SD'){
    var so_width = parseInt(mm_width)+(parseInt(tollerance)*2)+(parseInt(frame_thickness)*2)+(parseInt(gap)*2)
    }
    else if(door_set_type=='DD'){
    var so_width = (parseInt(mm_width)*2)+(parseInt(tollerance)*2)+(parseInt(frame_thickness)*2)+(parseInt(gap)*2)
    }
    else{
    var so_width = parseInt(mm_width)+parseInt(leaf_width_2)+(parseInt(tollerance)*2)+parseInt(frame_thickness)*2+parseInt(gap)*2
    }



    $("#sOWidth").val(so_width)
    $("#leafHeightNoOP").val(mm_height)

    var soHeight = parseInt(mm_height)+parseInt(tollerance)+parseInt(frame_thickness)+parseInt(undercut)+parseInt(gap)

    $("#sOHeight").val(soHeight)

    setTimeout(() => {
        var so_width = parseInt($("#sOWidth").val());
        var so_height = parseInt($("#sOHeight").val());
        $("#frameWidth").val(so_width-(parseInt(tollerance)*2));
        $("#frameHeight").val(so_height-parseInt(tollerance));
    }, 500);

})

//latch type filter
$("#doorsetType, #swingType").on("change",function(){
    var url = $("#filter_latch_type").val();
    var door_set_type = $("#doorsetType").val();
    var swing_type = $("#swingType").val();
    $.ajax({
        url:url,
        data:{door_set_type:door_set_type,swing_type:swing_type},
        type:"POST",
        success: function(res){
            var i = 0;
            var data = '';
            for(i=0;i<res.length;i++){
                data += '<option value="'+ res[i].OptionKey +'">'+ res[i].OptionValue +'</option>';
            }
            $("#latchType").attr('disabled',false);
            $("#latchType").empty().append(data);

        }
    });
    $("#latchType").attr('disabled',true);
})


//door leaf finish filter
$("#door_leaf_facing").on("change",function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var url = $("#door_leaf_fanish_filter").val();

    $.ajax({
        url:url,
        type:"POST",
        data:{doorLeafFacing:$(this).val(), pageId:3},
        success:function(data){
            var result = "<option value=''>Select Door leaf finish</option>";
            data.map((row)=>(
               result += `<option value="${row.OptionKey}">${row.OptionValue}</option>`
            ))
            $("#door_leaf_finish").empty().append(result)
        }
    })
})



//glassThickness filter glazing system


$("#glassThickness, #glazingBeads, #glazingSystems").on("change",function(){
    var firerating = $("#fireRating").val();
    var lazingIntegrityOrInsulationIntegrity = $("#lazingIntegrityOrInsulationIntegrity").val();
    var glassType = $("#glassType").val();
    var glassThickness = $("#glassThickness").val();
    var option_data = "<option value='Odice_Super_Wool'>Odice Super Wool</option>"
    if((firerating=='FD30' || firerating=='NFR') && lazingIntegrityOrInsulationIntegrity=='Integrity_And_Insulation' && glassType=='Pyrobel_16_EG_Type_1' && glassThickness=='16'){
        $("#glazingSystems").empty().append(option_data)
        $("#glazingBeadsWidth").val(24);
        $("#glazingBeadsHeight").val(29);
        $("#glazingBeadsFixingDetail").val("Acrylodice fillere and pins 50mm long. Fixing positioned 50mm in from the corners, at max 150mm centres")
    }
    else  if((firerating=='FD30' || firerating=='NFR') && lazingIntegrityOrInsulationIntegrity=='Integrity_only' && glassType=='Pyrobelite_7mm_Type_3' && glassThickness=='7'){
        $("#glazingSystems").empty().append(option_data)
        $("#glazingBeadsWidth").val(28);
        $("#glazingBeadsHeight").val(28);
        $("#glazingBeadsFixingDetail").val("Acrylodice and screws 50mm long by No 8. Fixings positioned 50mm in from the corners, at max 150mm centres.")
    }


    else  if((firerating=='FD30' || firerating=='NFR') && lazingIntegrityOrInsulationIntegrity=='Integrity_only' && glassType=='Pyroguard_EW30_IMPACT_7mm' && glassThickness=='7'){
        option_data = "<option value='Sealmaster_Intumescent_Foam_Glazing_Tape'>Sealmaster Intumescent Foam Glazing Tape </option>"
        $("#glazingSystems").empty().append(option_data)
        $("#glazingBeadsWidth").val(5);
        $("#glazingBeadsHeight").val(10);
        $("#glazingBeadsFixingDetail").val("1.6x40mm long steel pins or No. 8x40mm long screws at 150 max. centres and 50mm from corners.")
    }

    else if((firerating=='FD30s' || firerating=='NFR') && lazingIntegrityOrInsulationIntegrity=='Integrity_And_Insulation' && glassType=='Pyrobel_16_EG_Type_1' && glassThickness=='16'){
        $("#glazingSystems").empty().append(option_data)
        $("#glazingBeadsWidth").val(24);
        $("#glazingBeadsHeight").val(29);
        $("#glazingBeadsFixingDetail").val("Acrylodice fillere and pins 50mm long. Fixing positioned 50mm in from the corners, at max 150mm centres")
    }

    else  if((firerating=='FD30s' || firerating=='NFR') && lazingIntegrityOrInsulationIntegrity=='Integrity_only' && glassType=='Pyrobelite_7mm_Type_3' && glassThickness=='7'){
        $("#glazingSystems").empty().append(option_data)
        $("#glazingBeadsWidth").val(28);
        $("#glazingBeadsHeight").val(28);
        $("#glazingBeadsFixingDetail").val("Acrylodice and screws 50mm long by No 8. Fixings positioned 50mm in from the corners, at max 150mm centres.")
    }


    else  if((firerating=='FD30s' || firerating=='NFR') && lazingIntegrityOrInsulationIntegrity=='Integrity_only' && glassType=='Pyroguard_EW30_IMPACT_7mm' && glassThickness=='7'){
        option_data = "<option value='Sealmaster_Intumescent_Foam_Glazing_Tape'>Sealmaster Intumescent Foam Glazing Tape </option>"
        $("#glazingSystems").empty().append(option_data)
        $("#glazingBeadsWidth").val(5);
        $("#glazingBeadsHeight").val(10);
        $("#glazingBeadsFixingDetail").val("1.6x40mm long steel pins or No. 8x40mm long screws at 150 max. centres and 50mm from corners.")
    }

    else  if((firerating=='FD60' || firerating=='NFR') && lazingIntegrityOrInsulationIntegrity=='Integrity_only' && glassType=='Pyrostem_7_EW60_Type_2' && glassThickness=='7'){
        $("#glazingSystems").empty().append(option_data)
        $("#glazingBeadsWidth").val(25);
        $("#glazingBeadsHeight").val(25);
        $("#glazingBeadsFixingDetail").val("Acrylodice and screws 50mm long. Fixings positioned 50mm in from the corners, at max 150mm centres.")
    }

    else  if((firerating=='FD60' || firerating=='NFR') && lazingIntegrityOrInsulationIntegrity=='Integrity_And_Insulation' && glassType=='Pyrobel 25-Bois_Type_1' && glassThickness=='7'){
        option_data = "<option value='Odice_Interdens'>Odice Interdens</option>"
        $("#glazingSystems").empty().append(option_data)
        $("#glazingBeadsWidth").val(28);
        $("#glazingBeadsHeight").val(28);
        $("#glazingBeadsFixingDetail").val("Acrylodice fillere and pins 50mm long. Fixing positioned 50mm in from the corners, at max 150mm centres.")
    }

    else  if((firerating=='FD60s' || firerating=='NFR') && lazingIntegrityOrInsulationIntegrity=='Integrity_only' && glassType=='Pyrostem_7_EW60_Type_2' && glassThickness=='7'){
        $("#glazingSystems").empty().append(option_data)
        $("#glazingBeadsWidth").val(25);
        $("#glazingBeadsHeight").val(25);
        $("#glazingBeadsFixingDetail").val("Acrylodice and screws 50mm long. Fixings positioned 50mm in from the corners, at max 150mm centres.")
    }

    else  if((firerating=='FD60s' || firerating=='NFR') && lazingIntegrityOrInsulationIntegrity=='Integrity_And_Insulation' && glassType=='Pyrobel 25-Bois_Type_1' && glassThickness=='7'){
        option_data = "<option value='Odice_Interdens'>Odice Interdens</option>"
        $("#glazingSystems").empty().append(option_data)
        $("#glazingBeadsWidth").val(28);
        $("#glazingBeadsHeight").val(28);
        $("#glazingBeadsFixingDetail").val("Acrylodice fillere and pins 50mm long. Fixing positioned 50mm in from the corners, at max 150mm centres.")
    }

    setTimeout(() => {
        $("#glazingSystemsThickness").val(2)
    }, 500);
})




$("#glazingBeads").on("change",function(){
    $("#glazingBeadsFixingDetail").append()
})

$("#distanceFromTopOfDoor, #vP1Width, #vP1Height1").on("change",function(){
    $("#vP1Height1").removeAttr('readonly','readonly')
})

// $("#leafConstruction, #fireRating").on("change",function(){
//     if($("#leafConstruction").val()=='Flush'){
//     $("#vision_panel_li").show();
//     $("#lipping_and_intument").show();
//     $("#frameThickness").prop({"min" : 28,"max" : 70});
//     $("#doorsetType").empty().append('<option value="">Select door set type</option><option value="SD">SD = Single Doorset</option><option value="DD">DD = Double Doorse</option><option value="leaf_and_a_half">Leaf and a half</option>')
//     $("#swingType").empty().append('<option value="">Select swing type</option>')
//     }
//     else{
//     $("#vision_panel_li").hide();
//     $("#lipping_and_intument").hide();
//     $("#frameThickness").prop({"min" : 30,"max" : 70});
//     $("#doorsetType").empty().append('<option value="SD">SD = Single Doorset</option>')
//     if($("#fireRating").val()=='FD30' || $("#fireRating").val()=='FD30s' || $("#fireRating").val()=='FD60' || $("#fireRating").val()=='FD60s')
//         setTimeout(() => {
//         $("#swingType").empty().append('<option value="SA">SA = Single Acting</option>')
//         $("#latchType").empty().append('<option value="L">L = Latched</option>')
//         }, 1000);
//     }

// })

$("#leafConstruction").on("change",function(){
    if($(this).val()=='Flush'){
    setTimeout(()=>{
        $("#doorsetType").empty().append('<option value="">Select door set type</option><option value="SD">SD = Single Doorset</option><option value="DD">DD = Double Doorse</option><option value="leaf_and_a_half">Leaf and a half</option>')
    },1000)
    $("#vision_panel_li").show();
    $("#lipping_and_intument").show();
    $("#frameThickness").prop({"min" : 28,"max" : 70});
    }
    else{
    $("#vision_panel_li").hide();
    $("#lipping_and_intument").hide();
    $("#frameThickness").prop({"min" : 30,"max" : 70});
    }
})

$("#configurableitemsdoordimension").on("change",function(){
    if($(this).val() == 1 || $(this).val() == 2){
        $("#leaf_type").attr({"required":false}).val('');
        $("#inch_height").attr({"required":false}).val('0');
        $("#inch_width").attr({"required":false}).val('0');
        $("#door_leaf_facing").attr({"required":false}).val('');
        $("#door_leaf_finish").attr({"required":false}).val('');
        $("#cost_price").attr({"required":false}).val('0');

        $("#selling_price").attr({"required":false}).val('0');
        $("#code").attr({"required":false}).val('');
        $(".leaf_type_door").css("display", "none");
        $("#image").attr({"required":false});
    }else{
        $(".leaf_type_door").css("display", "block");
    }
});

$(window).load(function() {
    if($("#configurableitemsdoordimension").val() == 1 || $("#configurableitemsdoordimension").val() == 2){
        $("#leaf_type").attr({"required":false}).val('');
        $("#inch_height").attr({"required":false}).val('0');
        $("#inch_width").attr({"required":false}).val('0');
        $("#door_leaf_facing").attr({"required":false}).val('');
        $("#door_leaf_finish").attr({"required":false}).val('');
        $("#cost_price").attr({"required":false}).val('0');

        $("#selling_price").attr({"required":false}).val('0');
        $("#code").attr({"required":false}).val('');
        $("#image").attr({"required":false});
    }
});
