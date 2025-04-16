$(".change-event-calulation").change(function(){
    var unitMeter =1000;
    var tollerance = 0;
    var gap = 0;
    var framethikness = 0;
    var soWidth = 0;
    var soHeight=0;
    var soDepth=0;
    var undercut=0;
    var randomkey = 2;
    var hingeLocation1 = 0;
    var hingeLocation2 = 0;
    var hingeLocation3 = 0;
    var hingeLocation4 = 0;
    var hingeCenter = 0;
    var doorSetType = 'SD';
    var leafWidth1  = 0;
    var overPanel= 'Yes';
    var floorFinish= 0;
    var leaf1Visiblepanel="No";
    var leaf1VisblePanelQuantity=1;
    var leaf1VisblePanelEqullSize='Yes';
    var leaf1VpWidth=0;
    var leaf1VpHeight1=0;
    var leaf1VpHeight2=0;
    var leaf1VpHeight3=0;
    var leaf1VpHeight4=0;
    var leaf1VpHeight5=0;
    var leaf1VpHeight='leaf1VpHeight';
    var frameFinish ='';
    var Ext_Liner ='No';
    var UnderCutAdditionalNumber_a = 3;
    var thisvalue = document.getElementsByClassName("change-event-calulation");
    for (var i = 0; i < thisvalue.length; i++) {
        //get value of tollerance from field
         if(thisvalue[i].name=='tollerance'){
            if(thisvalue[i].value==''){
                tollerance = 0;
            }
            else{
                tollerance = parseInt(thisvalue[i].value);
            }
        }

        //get value of gap from field
        if(thisvalue[i].name=='gap'){
            if(thisvalue[i].value==''){
                gap = 0;
            }
            else{
                gap = parseInt(thisvalue[i].value);
            }
        }

        //get value of framethickness from field
        if(thisvalue[i].name=='frameThickness'){
            if(thisvalue[i].value==''){
                framethikness = 0;
            }
            else{
                framethikness = parseInt(thisvalue[i].value);

                $("#extLinerThickness").attr({'max':framethikness});
            }
        }


        //get value of SO Width from field
        if(thisvalue[i].name=='sOWidth'){
            if(thisvalue[i].value==''){
                soWidth = 0;
            }
            else{
                soWidth = parseInt(thisvalue[i].value);
            }

        }
        //get value of SO Height from field
        if(thisvalue[i].name=='sODepth'){
            if(thisvalue[i].value==''){
                soDepth = 0;
            }
            else{
                soDepth = parseInt(thisvalue[i].value);
            }
        //  alert(soDepth);
        }

        //get value of door set type Width from field
        if(thisvalue[i].name=='doorsetType'){
            if(thisvalue[i].value==''){
                doorSetType = 'SD';
            }
            else{
                doorSetType = thisvalue[i].value;
            }

        }
        //get value of leaf width 1 Width from field
        if(thisvalue[i].name=='leafWidth1'){
            if(thisvalue[i].value==''){
                leafWidth1 = 0;
            }
            else{
                leafWidth1 = parseInt(thisvalue[i].value);
            }

        }

        //get value of over panel Width from field
        if(thisvalue[i].name=='overpanel'){
            if(thisvalue[i].value=='Yes'){
                overPanel = 'Yes';
            }
            else{
                overPanel = thisvalue[i].value;
            }

        }

        //get value of SO Height Width from field
        if(thisvalue[i].name=='sOHeight'){
            if(thisvalue[i].value==''){
                soHeight = 0;
            }
            else{
                soHeight = parseInt(thisvalue[i].value);
            }
        }
        //get value of undercut Width from field
        if(thisvalue[i].name=='undercut'){
            if(thisvalue[i].value==''){
                undercut = 0;
            }
            else{
                undercut = parseInt(thisvalue[i].value);
            }
        }
        //get value of floor finish Width from field
        if(thisvalue[i].name=='floorFinish'){
            if(thisvalue[i].value==''){
                floorFinish = 0;
            }
            else{
                floorFinish = parseInt(thisvalue[i].value);
            }
        }


        if(thisvalue[i].name=='leaf1VisionPanel'){
            if(thisvalue[i].value==''|| thisvalue[i].value=='No'){
                leaf1Visiblepanel = "No";
            }
            else{
                leaf1Visiblepanel = thisvalue[i].value;
            }
        }

        if(thisvalue[i].name=='visionPanelQuantity'){
            if(thisvalue[i].value==''){
                leaf1VisblePanelQuantity =1;
            }
            else{
                leaf1VisblePanelQuantity = parseInt(thisvalue[i].value);
            }
        }

        if(thisvalue[i].name=='AreVPsEqualSizes'){
            if(thisvalue[i].value==''|| thisvalue[i].value=='Yes' ){
                leaf1VisblePanelEqullSize = "Yes";
            }
            else{
                leaf1VisblePanelEqullSize = thisvalue[i].value;
            }
        }


        if(thisvalue[i].name=='vP1Width'){
            if(thisvalue[i].value==''){
                leaf1VpWidth = 0;
            }
            else{
                leaf1VpWidth = parseInt(thisvalue[i].value);
            }
        }
        if(thisvalue[i].name=='vP1Height1'){
            if(thisvalue[i].value==''){
                leaf1VpHeight1 = 0;
            }
            else{
                leaf1VpHeight1 = thisvalue[i].value;
            }
        }
        if(thisvalue[i].name=='vP1Height2'){
            if(thisvalue[i].value==''){
                leaf1VpHeight2 = 0;
            }
            else{
                leaf1VpHeight2 = thisvalue[i].value;
            }
        }
        if(thisvalue[i].name=='vP1Height3'){
            if(thisvalue[i].value==''){
                leaf1VpHeight3 = 0;
            }
            else{
                leaf1VpHeight3 = thisvalue[i].value;
            }
        }
        if(thisvalue[i].name=='vP1Height4'){
            if(thisvalue[i].value==''){
                leaf1VpHeight4 = 0;
            }
            else{
                leaf1VpHeight4 = thisvalue[i].value;
            }
        }
        if(thisvalue[i].name=='vP1Height5'){
            if(thisvalue[i].value==''){
                leaf1VpHeight5 = 0;
            }
            else{
                leaf1VpHeight5 = thisvalue[i].value;
            }
        }


        if(thisvalue[i].name=='frameFinish'){
            if(thisvalue[i].value==''){
                frameFinish = '';
            }
            else{
                frameFinish = thisvalue[i].value;
            }
        }


        if(thisvalue[i].name=='extLiner'){
            if(thisvalue[i].value=='' || thisvalue[i].value=='No' ){
                Ext_Liner = 'No';
            }
            else{
                Ext_Liner = thisvalue[i].value;
            }
        }

        // GET ALL HINGE LOCATION START
        if(thisvalue[i].name=='hinge1Location'){
            if(thisvalue[i].value==''){
                hingeLocation1 = 0;
            }
            else{
                hingeLocation1 = thisvalue[i].value;
            }
        }

        if(thisvalue[i].name=='hinge2Location'){
            if(thisvalue[i].value==''){
                hingeLocation2 = 0;
            }
            else{
                hingeLocation2 = thisvalue[i].value;
            }
        }

        if(thisvalue[i].name=='hinge3Location'){
            if(thisvalue[i].value==''){
                hingeLocation3 = 0;
            }
            else{
                hingeLocation3 = thisvalue[i].value;
            }
        }

        if(thisvalue[i].name=='hinge4Location'){
            if(thisvalue[i].value==''){
                hingeLocation4 = 0;
            }
            else{
                hingeLocation4 = thisvalue[i].value;
            }
        }

        if(thisvalue[i].name=='hingeCenterCheck'){
            if(thisvalue[i].checked){
                hingeCenter = 1;
            }
            else{
                hingeCenter = 0;
            }
        }
        // GET ALL HINGE LOCATION END

    }


    // OP Width Calculation
    var ConfigurableDoorFormula = JSON.parse(ConfigurableDoorFormulaJson);
    var TolleranceAdditionalNumberForOPWidth = 1;
    var FrameThicknessAdditionalNumberForOPWidth = 1;
    var GapAdditionalNumberForOPWidth = 1;

    ConfigurableDoorFormula.forEach(function(elem, index) {

        var FormulaAdditionalData = JSON.parse(elem.value);

        if(elem.slug == "undercut"){
            UnderCutAdditionalNumber_a = parseFloat((FormulaAdditionalData.undercut != "")?FormulaAdditionalData.undercut:0);
        }

        if(elem.slug == "op_width"){
            TolleranceAdditionalNumberForOPWidth = parseFloat((FormulaAdditionalData.tolerance != "")?FormulaAdditionalData.tolerance:1);
            FrameThicknessAdditionalNumberForOPWidth = parseFloat((FormulaAdditionalData.frame_thickness != "")?FormulaAdditionalData.frame_thickness:1);
            GapAdditionalNumberForOPWidth = parseFloat((FormulaAdditionalData.gap != "")?FormulaAdditionalData.gap:1);
        }
    });


    if(floorFinish > 0){
        if($("#fireRating").val()=='FD30' || $("#fireRating").val()=='FD60'){
            undercut = floorFinish + 8;
        }else if($("#fireRating").val()=='FD30s' || $("#fireRating").val()=='FD60s'){
            undercut = floorFinish + 3;
        }else{
            undercut = $('#undercut').val();
        }
    } else{
        if(floorFinish == 0){
            if($("#fireRating").val()=='FD30' || $("#fireRating").val()=='FD60'){
                $("#floorFinish").on("keyup", function(event) {
                    let inputVal = $(this).val();
                    if (inputVal === "0") {
                        let undercut = 8; // Set the value you want to assign
                        $("#undercut").val(undercut);
                    }
                });
            }else if($("#fireRating").val()=='FD30s' || $("#fireRating").val()=='FD60s'){
                $("#floorFinish").on("keyup", function(event) {
                    let inputVal = $(this).val();
                    if (inputVal === "0") {
                        let undercut = 3; // Set the value you want to assign
                        $("#undercut").val(undercut);
                    }
                });

            }
        }
    }
    var calculateOfOpWidth = soWidth-(tollerance*TolleranceAdditionalNumberForOPWidth)-(framethikness*FrameThicknessAdditionalNumberForOPWidth)-(GapAdditionalNumberForOPWidth*gap);
    $("#oPWidth").val(calculateOfOpWidth);

    // Leaf width 1 and leaf width 2 calculation according to doorsetType

    var ConfigurableDoorFormula = JSON.parse(ConfigurableDoorFormulaJson);
    var TolleranceAdditionalNumber = 1;
    var FrameThicknessAdditionalNumber = 1;
    var GapAdditionalNumber = 1;

    var DoorSetType = $("#doorsetType").val();

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


    const lippingThicknessValue_A = $('#lippingThickness').val();
    if(lippingThicknessValue_A != ''){
        leafWidth1 - ( lippingThicknessValue_A * 2);
    }

    if(doorSetType=="DD"){

        // console.log("width ===",soWidth);
        // console.log("tollerance ===",tollerance);
        // console.log("framethikness ===",framethikness);
        // console.log("gap ===",gap);
        TolleranceAdditionalNumber = 2;
        FrameThicknessAdditionalNumber = 2;
        GapAdditionalNumber = 3;
        var leafWidth1 = (soWidth-(tollerance*TolleranceAdditionalNumber)-(framethikness*FrameThicknessAdditionalNumber)-(GapAdditionalNumber*gap))/2;
        // $("#leafWidth2").val(leafWidth1).attr('readonly',true);
        // $("#leafWidth1").val(leafWidth1).attr('readonly',true);
        $("#adjustmentLeafWidth2").attr('readonly',true);
        if($("#leaf2VisionPanel").val() == ""){
            $("#leaf2VisionPanel").val('No').attr({'disabled':false,'required':true});
        }

    }else if(doorSetType=="SD"){
        TolleranceAdditionalNumber = 2;
        FrameThicknessAdditionalNumber = 2;
        GapAdditionalNumber = 2;
        var leafWidth1 = soWidth-(tollerance*TolleranceAdditionalNumber)-(framethikness*FrameThicknessAdditionalNumber)-(GapAdditionalNumber*gap);
        // $("#leafWidth2").val('').attr('readonly',true);
        // $("#leafWidth1").val(leafWidth1).attr('readonly',true);
        $("#adjustmentLeafWidth2").attr('readonly',true);

        if($("#leaf2VisionPanel").val() == ""){
            $("#leaf2VisionPanel").val('No').attr({'disabled':true,'required':false});
        }
    }else{
        // if($("#leafWidth1").attr('readonly')){
        //     $("#leafWidth1").val(0).attr('readonly',false);
        // }
        TolleranceAdditionalNumber = 2;
        FrameThicknessAdditionalNumber = 2;
        GapAdditionalNumber = 3;
        var leafWidth2 = soWidth-(tollerance*TolleranceAdditionalNumber)-(framethikness*FrameThicknessAdditionalNumber)-(GapAdditionalNumber*gap)-parseInt(leafWidth1);
        // $("#leafWidth2").val(leafWidth2).attr('readonly',true);

        if($("#leaf2VisionPanel").val() == ""){
            $("#leaf2VisionPanel").val('No').attr({'disabled':false,'required':true});
        }


    }

    doorDimensionCalculation1();

    // leaf height clculation and hide show OP leaping thickness , OP height, OP transom, and Transom thickness acording to Over panel
    if(overPanel=="Fan_Light"){

        $("#leafHeightNoOP").attr({'readonly':false, "required": true });
        // $("#SL1Height").val(0).attr('readonly',false);

        if($("#sideLight1").val() == "Yes"){
            $("#SL1Height").attr({'readonly':false, "required": true });
        }

        if($("#sideLight2").val() == "Yes"){
            $("#SL2Height").attr({'readonly':false, "required": true });
        }

        $("#OPLippingThickness").attr({'disabled':false,required:true});
        $("#oPHeigth").val('').attr({'readonly':false,'required':true});
        $("#opTransom").attr('disabled',false);
        $("#transomThickness").attr('disabled',false);
        $("#opGlassType").attr({'disabled':false, 'required':true});
        $("#opGlazingBeads").attr({'disabled':false,required:true});
        $("#opGlazingBeadSpecies").attr({'disabled':false,'readonly':false,'required':true});
        $("#opGlassIntegrity").attr({'disabled':false,readonly:false,required:true});

        $("#opGlazingBeadSpeciesIcon").attr("onclick","return  OpenglazingModal('OP Glazing Bead Species','opGlazingBeadSpecies')");
        $("#opGlazingBeadSpeciesIcon").addClass("cursor-pointer");

    } else if(overPanel=="Overpanel"){
        $("#leafHeightNoOP").attr({'readonly':false, "required": true });

        if($("#sideLight1").val() == "Yes"){
            $("#SL1Height").attr({'readonly':false, "required": true });
        }


        if($("#sideLight2").val() == "Yes"){
            $("#SL2Height").attr({'readonly':false, "required": true });
        }

        $("#OPLippingThickness").attr({'disabled':false,required:true});
        $("#oPHeigth").val('').attr({'readonly':false,'required':true});
        $("#opTransom").attr('disabled',false);
        $("#transomThickness").attr('disabled',true);
        $("#opGlassType").attr({'disabled':true, 'required':false});
        $("#opGlazingBeads").attr({'disabled':true,required:false});
        $("#opGlassIntegrity").attr({'disabled':true,readonly:true,required:false});
        $("#opGlazingBeadSpecies").attr({'disabled':true,'readonly':true,'required':false});
        $("#opGlazingBeadSpeciesIcon").removeAttr("onclick","");
        $("#opGlazingBeadSpeciesIcon").removeClass("cursor-pointer");
        $('input[name="opGlazingBeadSpecies"]').val('')
        $('#opGlazingBeadSpecies').val('')
    }else{

        // $("#SL1Height").val(leafHeightNoOP).attr('readonly',true);
        // var calculationOfLeafHeight = soHeight-tollerance-framethikness-undercut-gap;
        var calculationOfLeafHeight = parseInt($('#leafHeightNoOP').val());
        $("#leafHeightNoOP").val(calculationOfLeafHeight).attr({'readonly':true, "required": true });

        if($("#sideLight1").val() == "Yes"){
            $("#SL1Height").val(calculationOfLeafHeight).attr({'readonly':true, "required": true });
        }

        if($("#sideLight2").val() == "Yes"){
            $("#SL2Height").val(calculationOfLeafHeight).attr({'readonly':true, "required": true });
        }

         $("#OPLippingThickness").attr({'disabled':true,'required':false});
         $("#oPHeigth").attr({'readonly':true ,'required':false});
         $("#opTransom").attr('disabled',true);
         $("#transomThickness").attr({'disabled':true,'required':false});
         $("#opGlassType").attr({'disabled':true,'required':false}).val('');
         $("#opGlazingBeads").attr({'disabled':true,'required':false}).val('');
         $("#opGlazingBeadSpecies").attr({'disabled':true,'readonly':true,'required':false}).val('');
         $("#opGlassIntegrity").attr({'disabled':true,'required':false}).val('');
         $("#opGlazingBeadSpeciesIcon").removeAttr("onclick","");
         $("#opGlazingBeadSpeciesIcon").removeClass("cursor-pointer");
         $('input[name="opGlazingBeadSpecies"]').val('')
    }

     // calculating undercut
       if(floorFinish!=''){

        //    var ConfigurableDoorFormula = JSON.parse(ConfigurableDoorFormulaJson);
        //    var UnderCutAdditionalNumber = 3;

        //    ConfigurableDoorFormula.forEach(function(elem, index) {

        //        var FormulaAdditionalData = JSON.parse(elem.value);
        //        if(elem.slug == "undercut"){
        //            UnderCutAdditionalNumber = parseFloat((FormulaAdditionalData.undercut != "")?FormulaAdditionalData.undercut:0);
        //        }
        //     });

        //     $("#undercut").val(floorFinish + UnderCutAdditionalNumber);


         floor_finish_change();

        }



        if(leaf1Visiblepanel=="Yes"){

            if(leaf1VisblePanelEqullSize=="Yes"){

                    var vpArea = (parseInt(leaf1VpWidth)/unitMeter)*(parseInt(leaf1VpHeight1)/unitMeter);

            }else{

                    if(leaf1VisblePanelQuantity==1){
                        var vpArea = (parseInt(leaf1VpWidth)/unitMeter)*(parseInt(leaf1VpHeight1)/unitMeter);

                    }else if(leaf1VisblePanelQuantity==2){

                        var vpArea = (parseInt(leaf1VpWidth)/unitMeter)*(parseInt(leaf1VpHeight1)/unitMeter)+(parseInt(leaf1VpWidth)/unitMeter)*(parseInt(leaf1VpHeight2)/unitMeter);
                    }else if(leaf1VisblePanelQuantity==3){

                        var vpArea = (parseInt(leaf1VpWidth)/unitMeter)*(parseInt(leaf1VpHeight1)/unitMeter)+(parseInt(leaf1VpWidth)/unitMeter)*(parseInt(leaf1VpHeight2)/unitMeter)+(parseInt(leaf1VpWidth)/unitMeter)*(parseInt(leaf1VpHeight3)/unitMeter);
                    }else if(leaf1VisblePanelQuantity==4){

                        var vpArea = (parseInt(leaf1VpWidth)/unitMeter)*(parseInt(leaf1VpHeight1)/unitMeter)+(parseInt(leaf1VpWidth)/unitMeter)*(parseInt(leaf1VpHeight2)/unitMeter)+(parseInt(leaf1VpWidth)/unitMeter)*(parseInt(leaf1VpHeight3)/unitMeter)+(parseInt(leaf1VpWidth)/unitMeter)*(parseInt(leaf1VpHeight4)/unitMeter);
                    }else if(leaf1VisblePanelQuantity==5){

                        var vpArea = (parseInt(leaf1VpWidth)/unitMeter)*(parseInt(leaf1VpHeight1)/unitMeter)+(parseInt(leaf1VpWidth)/unitMeter)*(parseInt(leaf1VpHeight2)/unitMeter)+(parseInt(leaf1VpWidth)/unitMeter)*(parseInt(leaf1VpHeight3)/unitMeter)+(parseInt(leaf1VpWidth)/unitMeter)*(parseInt(leaf1VpHeight4)/unitMeter)+(parseInt(leaf1VpWidth)/unitMeter)*(parseInt(leaf1VpHeight5)/unitMeter);
                    }


                }
                // alert(vpArea);
                $("#leaf1VpAreaSizeM2").val(vpArea.toFixed(2));
                // Sadique Code
                //    $('#lazingIntegrityOrInsulationIntegrity').val("");
                //    $('#glassType').val("");

         }else{
             $("#leaf1VpAreaSizeM2").val(0);
         }

        var ElementId = $(this).attr("id");

        // if($.inArray( ElementId, ["vP1Width","vP1Height1","vP1Height2","vP1Height3","vP1Height4","vP1Height5","lazingIntegrityOrInsulationIntegrity"] ) !== -1){

        if($("#glassType").val() == "" && $("#glassThickness").val() == "" && $("#glazingSystems").val() == ""){

            if($("#lazingIntegrityOrInsulationIntegrity").val() != "" && $("#fireRating").val() != 'NFR'){
                glassTypeFilter(true);
            }else{
                glassTypeFilter(false);
            }
            glazingSystemFIlter($("#fireRating").val());

        }

        // }



        var ConfigurableDoorFormula = JSON.parse(ConfigurableDoorFormulaJson);
        var TolleranceAdditionalNumberForFrameWidth = 1;

        ConfigurableDoorFormula.forEach(function(elem, index) {

            var FormulaAdditionalData = JSON.parse(elem.value);
            if(elem.slug == "frame_width"){
                TolleranceAdditionalNumberForFrameWidth = parseFloat((FormulaAdditionalData.tolerance != "")?FormulaAdditionalData.tolerance:1);
            }
        });

        let calculateFrameWidth = soWidth-(parseInt(tollerance)*TolleranceAdditionalNumberForFrameWidth);
        framewidth();
        $("#frameHeight").val(soHeight-tollerance);
        //  alert(soHeight);
        //  $("#frameDepth").val(soHeight);
         $("#extLinerFinish").val(frameFinish);

        //  if(Ext_Liner=="Yes"){

            // alert(soDepth);
            // alert(soHeight);
            // $("#extLinerValue").val(soDepth-soHeight);
        // }else{
        //     $("#extLinerValue").val(0);
        // }

        if(Ext_Liner == "No"){
            $("#extLinerValue").val(0);
        }





    // // var frameWidth = soWidth-(tollerance);
    // $("#frameWidth").val(frameWidth);


    // if($("#doorsetType").val()=="DD"){

    //     var leafWidth1 = (soWidth-(tollerance*2)-(framethikness*2)-(3*gap))/2;
    //     $("#leafWidth2").val(leafWidth1);
    //     $("#leafWidth1").val(leafWidth1);

    // }else if($("#doorsetType").val()=="SD"){
    //     // var leafWidth2 =  soWidth-(tollerance*2)-(framethikness*2)-((3*gap)/2);
    //     var leafWidth1 = soWidth-(tollerance*2)-(framethikness*2)-(2*gap);
    //     $("#leafWidth2").val(0);
    //     $("#leafWidth1").val(leafWidth1);
    // }else{
    //     var leafWidth1 = 0;
    //     $("#leafWidth1").val('').attr('readonly',false);
    //     if($("#leafWidth1").val()!='' && parseInt( $("#leafWidth1").val())){
    //         leafWidth1 = parseInt( $("#leafWidth1").val());
    //     }
    //      var leafWidth2 = soWidth-(tollerance*2)-(framethikness*2)-(3*gap)-leafWidth1;
    //      $("#leafWidth2").val(leafWidth2);

    // }

    //var identifier = $(this);

    // sadique code
    // Rround down the field ‘Maximum Number of Groove’
    // for example, if the value there is 20.42 it should be 20.


    // Door Dimensions & Door Leaf
    // Leaf Width 1 with Groove Location = Horizontal
    var leafWidth1 = $("#leafWidth1").val(); // horizontal
    // Leaf Height with Groove Location = Vertical
    var leafHeightNoOP = $("#leafHeightNoOP").val(); // vertical
    let grooveLocationValue = $('#grooveLocation').val();


    if(grooveLocationValue == "Vertical"){
            leafWidth1 = leafWidth1!=''?leafWidth1:0;
            $("#maxNumberOfGroove").val(parseInt(Math.round((leafWidth1)/100)));
    } else if(grooveLocationValue == "Horizontal"){
            leafHeightNoOP = leafHeightNoOP!=''?leafHeightNoOP:0;
            $("#maxNumberOfGroove").val(Math.round((parseInt(leafHeightNoOP)/100)));
    }



    // VP size larger than Door size
    const vP1WidthValue_A = parseFloat($('#vP1Width').val());
    const vP1Height1Value_A = parseFloat($('#vP1Height1').val());
    const sOWidthValue_A = parseFloat($('#sOWidth').val());
    const sOHeightValue_A = parseFloat($('#sOHeight').val());

    if(vP1WidthValue_A != '' && sOWidthValue_A != ''){
        if(vP1WidthValue_A > sOWidthValue_A){
            swal('.','Leaf width 1 is never greater than S.O. width.')
            $('#vP1Width').val(0);
            $('#vP1Width-section').removeClass("table_row_show");
        }
    }
    if(vP1Height1Value_A != '' && sOHeightValue_A != ''){
        if(vP1Height1Value_A > sOHeightValue_A){
            swal('.','Leaf height 1 is never greater than S.O. height.')
            $('#vP1Height1').val(0);
            $('#vP1Height1-section').removeClass("table_row_show");
        }
    }



});


function floor_finish_change(){

    if($("#fireRating").val()=='FD30' || $("#fireRating").val()=='FD60'){
        $("#undercut").attr("readonly","readonly")
        $("#floor_finish").show();
        $("#undercut").val(parseInt($("#floorFinish").val())+8);

        }
        else if($("#fireRating").val()=='FD30s' || $("#fireRating").val()=='FD60s'){
        $("#undercut").attr("readonly","readonly")
        $("#floor_finish").show();
        $("#undercut").val(parseInt($("#floorFinish").val())+3);
        }
        else{
        $("#undercut").attr('readonly',false)
        $("#floor_finish").show();
        }
        var withoutFrameId = $("#withoutFrameId").val();
    if(withoutFrameId == 1){
        $("#floor_finish").hide();
    }
}

function doorDimensionCalculation1(){
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

    let calculateFrameWidth = so_width - (parseInt(tollerance) * 2);
    framewidth();

    var so_height = parseInt($("#sOHeight").val());
    $("#frameHeight").val(so_height - (parseInt(tollerance)));


    let elements = $(this);
    render(elements);
}
