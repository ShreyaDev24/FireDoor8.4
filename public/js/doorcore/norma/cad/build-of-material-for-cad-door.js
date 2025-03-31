$(".form-control").change( function( event ) {
    var identifier = $(this);
    var TagName = identifier.context.tagName;
    SetBuildOfMaterial(identifier,TagName);
});

Array.prototype.findIndexOf = function(prop) {
    var i = -1;
    this.forEach(function(elem, index) {
        if (prop in elem) {
            i = index;
            return false;
        }
    })
    return i;
}

function SetBuildOfMaterial(identifier,TagName){


    var name = identifier.attr("name");
    var id = identifier.attr("id");

    var Colors = JSON.parse(ColorsJson);
    var Options = JSON.parse(OptionsJson);



    var ElementValue = identifier.val();
    var ActualValue = identifier.val();


    if(name != "accoustics"){
        if(TagName.toLowerCase() == "select"){
            var e = document.getElementById(id);
            ActualValue = e.options[e.selectedIndex].value;
            ElementValue = e.options[e.selectedIndex].text;
        }  else {
            // These fields should give a warning if you enter a number higher than the set max number
            const InputValue = parseInt($("#"+id).val());
            const getmaxinputvalue = parseInt($("#"+id).attr('max'));
            const getmininputvalue = parseInt($("#"+id).attr('min'));
            let getmsginput = null;
            if(id == 'grooveWidth'){
                getmsginput = 'Groove Width should not be more than 10mm.';
            } else if(id == 'numberOfVerticalGroove'){
                getmsginput = 'Vertical Grooves should not exceed 4 Grooves';
            } else if(id == 'numberOfHorizontalGroove'){
                getmsginput = 'Horizontal Grooves should not exceed 4 Grooves';
            } else if(id == 'oPHeigth'){
                getmsginput = 'OP Height should not be more than 600mm';
            } else if(id == 'grooveDepth'){
                getmsginput = 'Groove Depth should not be more than 4mm';
            } else if(id == 'extLinerThickness'){
                getmsginput = 'Ex-Liner Thickness should not be more than '+getmaxinputvalue+'mm';
            } else if(id == 'SLtransomThickness'){
                getmsginput = 'The Min Transom Thickness is 32mm';
            } else if(id == 'standardWidth'){
                getmsginput = 'Standard width is not more than '+getmaxinputvalue+'.';
            } else if(id == 'standardHeight'){
                getmsginput = 'Standard height is not more than '+getmaxinputvalue+'.';
            } else if(id == 'distanceFromTopOfDoor'){
                getmsginput = 'The minimum distance from the top of the door is 140mm';
            } else if(id == 'distanceFromTheEdgeOfDoor'){
                getmsginput = 'The minimum distance from the edge of the door is 100mm';
            } else if(id == 'distanceBetweenVPs'){
                var firerating = $("#fireRating").val();
                if(firerating!='NFR'){
                if($("#distanceBetweenVPs").val()<320){
                getmsginput = 'The minimum distance between the VP’s is 320mm and a maximum of 380mm';
                }
                if($("#distanceBetweenVPs").val()>380){
                getmsginput = 'The minimum distance between the VP’s is 320mm and a maximum of 380mm';
                }
                }
            } else if(id =='SL1Width'){
                getmsginput = 'SL1 Width should not be more than 600mm';
            } else if(id =='SL2Width'){
                getmsginput = 'SL2 Width should not be more than 600mm';
            }else if(id =='frameThickness'){
                getmsginput = 'Frame Thickness should be minimum of '+getmininputvalue+'mm and a maximum of '+getmaxinputvalue+'mm';
            }else if(id =='distanceFromTheEdgeOfDoorforLeaf2'){
                getmsginput = 'Distance from the edge for Leaf 2 should be minimum of 100mm';
            }else if(id =='vP2Width'){
                getmsginput = 'Leaf 2 VP Width should be minimum of 0mm';
            }else if(id =='vP1Width'){
                getmsginput = 'Leaf 1 VP Width should be minimum of 0mm';
            }else if(id =='vP1Height1'){
                getmsginput = 'Leaf 1 VP Height (1) should be minimum of 0mm';
            }else if(id =='vP1Height2'){
                getmsginput = 'Leaf 1 VP Height (2) should be minimum of 0mm';
            }else if(id =='distanceFromTopOfDoorforLeaf2'){
                getmsginput = 'Distance from top of door for Leaf 2 should be minimum of 0mm';
            }else if(id =='glazingBeadsThickness'){
                getmsginput = 'Glazing Beads Thickness should be minimum of 0mm';
            }else if(id =='glazingBeadsWidth'){
                getmsginput = 'Glazing Beads Width should be minimum of 0mm';
            }else if(id =='glazingBeadsHeight'){
                getmsginput = 'Glazing Beads Height should be minimum of 0mm';
            }


            if(InputValue > getmaxinputvalue){
                if(id == 'gap'){
                    getmsginput = 'Gap should be between 2 - 4mm';
                }
                $("#"+name+"-section").removeClass("table_row_show");
                $("#"+name+"-section").addClass("table_row_hide");
                swal('Warning',getmsginput);
                $("#"+id).val('');
                $("#"+id).css({'border':'1px solid red'});
                return false;
            } else {
                $("#"+id).css({'border':'1px solid #ced4da'});
            }
            if(InputValue < getmininputvalue){
                if(id == 'gap'){
                    getmsginput = 'Gap should be between 2 - 4mm';
                } else if(id == 'plantonStopWidth'){
                    getmsginput = 'Plant on Stop Width should be a minimum of 32mm';
                } else if(id == 'plantonStopHeight'){
                    getmsginput = 'Plant on Stop Width should be a minimum of 12mm';
                } else if(id == 'frameDepth'){
                    getmsginput = 'Frame Depth should be a minimum of 80mm';
                }
                $("#"+name+"-section").removeClass("table_row_show");
                $("#"+name+"-section").addClass("table_row_hide");
                swal('Warning',getmsginput);
                $("#"+id).val('');
                $("#"+id).css({'border':'1px solid red'});
                return false;
            } else {
                $("#"+id).css({'border':'1px solid #ced4da'});
            }
        }
    }

    if(ActualValue == ""){
        $("#"+name+"-section").removeClass("table_row_show");
        $("#"+name+"-section").addClass("table_row_hide");
        return false;
    }

    //var SelectedColor = Colors.findIndexOf(ElementValue);

    var isExist = false;
    var price = 0.00;

    if(TagName.toLowerCase() == "select"){

        //Colors.forEach(function(elem, index) {
        //    if(ActualValue == elem.ColorName){
        //        isExist = true;
        //        price = elem.colorCost;
        //        console.log("Color id is = ",index);
        //    }
        //});

        if(isExist === false){
            Options.forEach(function(elem, index) {
                if(ActualValue == elem.OptionKey){

                    if(id == "leafConstruction" && elem.OptionSlug == "leaf_construction"){
                        isExist = true;
                    }else if(id == "fireRating" && elem.OptionSlug == "fire_rating"){
                        isExist = true;
                    }else if(id == "doorsetType" && elem.OptionSlug == "door_configuration_doorset_type"){
                        isExist = true;
                    }else if(id == "swingType" && elem.OptionSlug == "door_configuration_swing_type"){
                        isExist = true;
                    }else if(id == "latchType" && elem.OptionSlug == "door_configuration_latch_type"){
                        isExist = true;
                    }else if(id == "Handing" && elem.OptionSlug == "Handing"){
                        isExist = true;
                    }else if(id == "OpensInwards" && elem.OptionSlug == "Opens_Inwards"){
                        isExist = true;
                    }else if(id == "COC" && elem.OptionSlug == "COC"){
                        isExist = true;
                    }else if(id == "doorLeafFacing" && elem.OptionSlug == "Door_Leaf_Facing"){
                        isExist = true;
                    }else if(id == "doorLeafFacingValue" && elem.OptionSlug == "door_leaf_facing_value"){
                        isExist = true;
                    }else if(id == "doorLeafFinish" && elem.OptionSlug == "door_leaf_finish"){
                        isExist = true;
                    }else if(id == "decorativeGroves" && elem.OptionSlug == "Decorative_Groves"){
                        isExist = true;
                    }else if(id == "grooveLocation" && elem.OptionSlug == "Groove_Location"){
                        isExist = true;
                    }else if(id == "leaf1VisionPanel" && elem.OptionSlug == "leaf1_vision_panel"){
                        isExist = true;
                    }else if(id == "leaf1VisionPanelShape" && elem.OptionSlug == "Vision_panel_shape"){
                        isExist = true;
                    }else if(id == "visionPanelQuantity" && elem.OptionSlug == "leaf1_vision_panel_Qty"){
                        isExist = true;
                    }else if(id == "AreVPsEqualSizes" && elem.OptionSlug == "leaf1are_vps_equal_sizes"){
                        isExist = true;
                    }else if(id == "leaf2VisionPanel" && elem.OptionSlug == "leaf1_vision_panel"){
                        isExist = true;
                    }else if(id == "vpSameAsLeaf1" && elem.OptionSlug == "leaf1are_vps_equal_sizes"){
                        isExist = true;
                    }else if(id == "visionPanelQuantityforLeaf2" && elem.OptionSlug == "leaf1_vision_panel_Qty"){
                        isExist = true;
                    }else if(id == "AreVPsEqualSizesForLeaf2" && elem.OptionSlug == "leaf1are_vps_equal_sizes"){
                        isExist = true;
                    }else if(id == "lazingIntegrityOrInsulationIntegrity" && elem.OptionSlug == "Glass_Integrity"){
                        isExist = true;
                    }else if(id == "glassType" && elem.OptionSlug == "leaf1_glass_type"){
                        isExist = true;
                    }else if(id == "glazingSystems" && elem.OptionSlug == "leaf1_glazing_systems"){
                        isExist = true;
                    }else if(id == "glazingBeads" && elem.OptionSlug == "leaf1_glazing_beads"){
                        isExist = true;
                    }else if(id == "overPanel" && elem.OptionSlug == "Over_Panel_Fanlight"){
                        isExist = true;
                    }else if(id == "frameType" && elem.OptionSlug == "Frame_Type"){
                        isExist = true;
                    }else if(id == "frameFinish" && elem.OptionSlug == "Frame_Finish"){
                        isExist = true;
                    }else if(id == "extLiner" && elem.OptionSlug == "Ext_Liner"){
                        isExist = true;
                    }else if(id == "overpanel" && elem.OptionSlug == "door_configuration_overpanel"){
                        isExist = true;
                    }else if(id == "OPLippingThickness" && elem.OptionSlug == "op_lipping_thickness"){
                        isExist = true;
                    }else if(id == "transomThickness" && elem.OptionSlug == "transom_thickness"){
                        isExist = true;
                    }else if(id == "opGlassType" && elem.OptionSlug == "leaf1_glass_type"){
                        isExist = true;
                    }else if(id == "opGlazingBeads" && elem.OptionSlug == "leaf1_glazing_beads"){
                        isExist = true;
                    }else if(id == "sideLight1" && elem.OptionSlug == "SideLight1"){
                        isExist = true;
                    }else if(id == "sideLight1GlassType" && elem.OptionSlug == "leaf1_glass_type"){
                        isExist = true;
                    }else if(id == "SideLight1BeadingType" && elem.OptionSlug == "leaf1_glazing_beads"){
                        isExist = true;
                    }else if(id == "SL1Transom" && elem.OptionSlug == "SideLight1_transom"){
                        isExist = true;
                    }else if(id == "sideLight2" && elem.OptionSlug == "SideLight2"){
                        isExist = true;
                    }else if(id == "copyOfSideLite1" && elem.OptionSlug == "copy_Same_as_SL1"){
                        isExist = true;
                    }else if(id == "sideLight2GlassType" && elem.OptionSlug == "leaf1_glass_type"){
                        isExist = true;
                    }else if(id == "SideLight2BeadingType" && elem.OptionSlug == "leaf1_glazing_beads"){
                        isExist = true;
                    }else if(id == "SL2Transom" && elem.OptionSlug == "SideLight2_transom"){
                        isExist = true;
                    }else if(id == "lippingType" && elem.OptionSlug == "lipping_type"){
                        isExist = true;
                    }else if(id == "lippingThickness" && elem.OptionSlug == "lipping_thickness"){
                        isExist = true;
                    }else if(id == "meetingStyle" && elem.OptionSlug == "meeting_style"){
                        isExist = true;
                    }else if(id == "scallopedLippingThickness" && elem.OptionSlug == "scalloped_lipping_thickness"){
                        isExist = true;
                    }else if(id == "flatLippingThickness" && elem.OptionSlug == "flat_lipping_thickness"){
                        isExist = true;
                    }else if(id == "rebatedLippingThickness" && elem.OptionSlug == "rebeated_lipping_thickness"){
                        isExist = true;
                    }else if(id == "intumescentSealType" && elem.OptionSlug == "intumescent_seal_type"){
                        isExist = true;
                    }else if(id == "intumescentSealLocation" && elem.OptionSlug == "IntumescentSeal_location"){
                        isExist = true;
                    }else if(id == "intumescentSealColor" && elem.OptionSlug == "Intumescent_Seal_Color"){
                        isExist = true;
                    }else if(id == "accoustics" && elem.OptionSlug == "Accoustics"){
                        isExist = true;
                    }else if(id == "Architrave" && elem.OptionSlug == "Architrave"){
                        isExist = true;
                    }else if(id == "architraveMaterial" && elem.OptionSlug == "Architrave_Material"){
                        isExist = true;
                    }else if(id == "architraveType" && elem.OptionSlug == "Architrave_Type"){
                        isExist = true;
                    }else if(id == "architraveFinish" && elem.OptionSlug == "Architrave_Finish"){
                        isExist = true;
                    }else if(id == "architraveSetQty" && elem.OptionSlug == "Architrave_Set_Qty"){
                        isExist = true;
                    }else if(id == "IronmongeryID") {

                        var Ironmongery = JSON.parse(IronmongeryJson);
                        var IronmongeryID = $('input[name="IronmongeryID"]').val();

                        Ironmongery.forEach(function(elem, index) {
                            if(IronmongeryID == elem.id){
                                price = elem.discountprice;
                            }
                        });

                    }

                }

                if(isExist === true){
                    price = elem.OptionCost;
                    //console.log("Option id is = ",index);
                }
            });
        }

    }



    $("#"+name+"-selected").empty().text(ElementValue);
    $("#"+name+"-price").empty().text("£" + price);
    $("#"+name+"-section").removeClass("table_row_hide");
    $("#"+name+"-section").addClass("table_row_show");

    if(name == "floorFinish"){
        var undercut = $("#undercut").val();

        var ConfigurableDoorFormula = JSON.parse(ConfigurableDoorFormulaJson);
        var UnderCutAdditionalNumber = 3;

        ConfigurableDoorFormula.forEach(function(elem, index) {

            var FormulaAdditionalData = JSON.parse(elem.value);
            if(elem.slug == "undercut"){
                UnderCutAdditionalNumber = parseFloat((FormulaAdditionalData.undercut != "")?FormulaAdditionalData.undercut:0);
            }
        });

        $("#undercut-selected").empty().text(parseFloat(ElementValue) + UnderCutAdditionalNumber);
        $("#undercut-price").empty().text("£" + price);
        $("#undercut-section").removeClass("table_row_hide");
        $("#undercut-section").addClass("table_row_show");
    }

    if(name == "sOWidth"){
        ShowLeafsWidth();
    }

}

function ShowLeafsWidth(){

}
