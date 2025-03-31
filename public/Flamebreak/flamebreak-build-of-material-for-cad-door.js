$(".form-control").change(function (event) {
    // alert(pageIdentity());

    var identifier = $(this);
    // var TagName = identifier.context.tagName;
    // SetBuildOfMaterial(identifier,TagName);

    SetBuildOfMaterial(identifier);
});

Array.prototype.findIndexOf = function (prop) {
    var i = -1;
    this.forEach(function (elem, index) {
        if (prop in elem) {
            i = index;
            return false;
        }
    })
    return i;
}

var price = 0.00;
function SetBuildOfMaterial(identifier, priceDirectSet = "") {
    // var TagName = identifier.context.tagName;
    var TagName = identifier.prop("tagName");
    var name = identifier.attr("name");

    var id = identifier.attr("id");
    var option_slug = identifier.attr("option_slug");

    var ElementValue = identifier.val();
    var ActualValue = identifier.val();

    if (priceDirectSet != "") {
        price = priceDirectSet;
    } else {

        var Colors = JSON.parse(ColorsJson);

        if (["intumescentSealArrangement"].includes(id)) {
            var Options = JSON.parse(intumescentSealArrangementJson);
            var SelectedOptions = JSON.parse(SelectedIntumescentSealArrangementJson);

        } else {
            var Options = JSON.parse(OptionsJson);
            var SelectedOptions = JSON.parse(SelectedOptionsJson);
        }

        var possibleSelectedOptionsArray = JSON.parse(possibleSelectedOptionsJson);

        if (name != "accoustics") {
            if (TagName && typeof TagName === "string" && TagName.toLowerCase() === "select") {
                var e = document.getElementById(id);
                ActualValue = e.options[e.selectedIndex].value;
                ElementValue = e.options[e.selectedIndex].text;
            } else {
                // These fields should give a warning if you enter a number higher than the set max number
                const InputValue = parseFloat($("#" + id).val());
                const getmaxinputvalue = parseInt($("#" + id).attr('max'));
                const getmininputvalue = parseInt($("#" + id).attr('min'));
                let getmsginput = null;
                if (id == 'grooveWidth') {
                    getmsginput = 'Groove Width should not be more than 10mm.';
                } else if (id == 'GrooveWidthLeaf2') {
                    getmsginput = 'Groove Width should not be more than 10mm.';
                } else if (id == 'numberOfVerticalGroove') {
                    getmsginput = 'Vertical Grooves should not exceed 4 Grooves';
                } else if (id == 'numberOfHorizontalGroove') {
                    getmsginput = 'Horizontal Grooves should not exceed 4 Grooves';
                } else if (id == 'grooveDepth') {
                    getmsginput = 'Groove Depth should not be more than 4mm';
                } else if (id == 'NumberOfVerticalGrooveLeaf2') {
                    getmsginput = 'Vertical Grooves Leaf2 should not exceed 4 Grooves';
                } else if (id == 'NumberOfHorizontalGrooveLeaf2') {
                    getmsginput = 'Horizontal Grooves Leaf2 should not exceed 4 Grooves';
                } else if (id == 'GrooveDepthLeaf2') {
                    getmsginput = 'Groove Depth Leaf2 should not be more than 4mm';
                } else if (id == 'extLinerThickness') {
                    getmsginput = 'Ex-Liner Thickness should not be more than 32mm';
                } else if (id == 'SLtransomThickness') {
                    getmsginput = 'The Min Transom Thickness is 32mm';
                } else if (id == 'standardWidth') {
                    getmsginput = 'Standard width is not more than ' + getmaxinputvalue + '.';
                } else if (id == 'standardHeight') {
                    getmsginput = 'Standard height is not more than ' + getmaxinputvalue + '.';
                } else if (id == 'distanceFromTopOfDoor') {
                    getmsginput = 'The minimum distance from the top of the door is ' + getmininputvalue + '.';
                } else if (id == 'distanceFromTheEdgeOfDoor') {
                    getmsginput = 'The minimum distance from the edge of the door is ' + getmininputvalue + '.';
                } else if (id == 'distanceBetweenVPs') {
                    getmsginput = 'The minimum distance between the VP’s is 80mm';
                } else if (id == 'SL1Width') {
                    getmsginput = 'SL1 Width should not be more than ' + getmaxinputvalue + '.';
                } else if (id == 'SL2Width') {
                    getmsginput = 'SL2 Width should not be more than ' + getmaxinputvalue + '.';
                } else if (id == 'tollerance') {
                    getmsginput = 'Tollerance should not be more than 20mm';
                } else if (id == 'frameThickness') {
                    getmsginput = 'FrameThickness should not be more than '+ getmininputvalue +'mm';
                } else if (id == 'oPHeigth') {
                    getmsginput = 'OP/FL Height should be more than ' + getmaxinputvalue + '.';
                } else if (id == 'OpBeadHeight') {
                    getmsginput = 'Fan Light/ Over Panel Frame Depth should be a minimum of ' + getmininputvalue + '.';
                } else if (id == 'OpBeadThickness') {
                    getmsginput = 'Fan Light/ Over Panel Frame Thickness should be a minimum of ' + getmininputvalue + '.';
                } else if (id == 'opglazingBeadsThickness') {
                    getmsginput = 'Glazing Beads Thickness should be a minimum of ' + getmininputvalue + '.';
                } else if (id == 'opglazingBeadsHeight') {
                    getmsginput = 'Glazing Beads Width should be a minimum of ' + getmininputvalue + '.';
                } else if (id == 'SL1TransomDepth') {
                    getmsginput = 'SL1 Transom Depth should be a minimum of ' + getmininputvalue + '.';
                } else if (id == 'SL1transomThickness') {
                    getmsginput = 'SL1 Transom Thickness should be a minimum of ' + getmininputvalue + '.';
                } else if (id == 'sideLight1GlazingBeadsThickness') {
                    getmsginput = 'SL1 Glazing Beads Thickness should be a minimum of ' + getmininputvalue + '.';
                } else if (id == 'sideLight1GlazingBeadsWidth') {
                    getmsginput = 'SL1 Glazing Beads Height should be a minimum of ' + getmininputvalue + '.';
                } else if (id == 'SL2TransomDepth') {
                    getmsginput = 'SL2 Transom Depth should be a minimum of ' + getmininputvalue + '.';
                } else if (id == 'SL2transomThickness') {
                    getmsginput = 'SL2 Transom Thickness should be a minimum of ' + getmininputvalue + '.';
                } else if (id == 'sideLight2GlazingBeadsThickness') {
                    getmsginput = 'SL2 Glazing Beads Thickness should be a minimum of ' + getmininputvalue + '.';
                } else if (id == 'sideLight2GlazingBeadsWidth') {
                    getmsginput = 'SL2 Glazing Beads Height should be a minimum of ' + getmininputvalue + '.';
                } else if (id == 'ScallopedHeight') {
                    getmsginput = 'Scalloped Height should be a minimum of ' + getmininputvalue + '.';
                }


                if (InputValue > getmaxinputvalue) {
                    if (id == 'gap') {
                        getmsginput = 'Gap should be between 2 - 4mm';
                    } else if(id == 'hinge1Location'){
                        getmsginput = 'Hinge 1 Location should be a minimum of 100mm and maximum of 180mm ';
                    } else if(id == 'hinge2Location'){
                        getmsginput = 'Hinge 2 Location should be a minimum of 200mm';
                    } else if(id == 'hinge3Location'){
                        getmsginput = 'Hinge 3 Location should be a minimum of 150mm and maximum of 250mm ';
                    } else if(id == 'hinge4Location'){
                        getmsginput = 'Hinge 3 Location should be a minimum of 200mm';
                    }

                    $("#" + name + "-section").removeClass("table_row_show");
                    $("#" + name + "-section").addClass("table_row_hide");
                    swal('Warning', getmsginput);
                    $("#" + id).val('');
                    $("#" + id).css({ 'border': '1px solid red' });
                    return false;
                } else {
                    $("#" + id).css({ 'border': '1px solid #ced4da' });
                }
                if (InputValue < getmininputvalue) {
                    if (id == 'gap') {
                        getmsginput = 'Gap should be between 2 - 4mm';
                    } else if (id == 'plantonStopHeight') {
                        getmsginput = 'Plant on Stop Height should be a minimum '+getmininputvalue+ 'mm';
                    } else if (id == 'plantonStopWidth') {
                        getmsginput = 'Plant on Stop Width should be a minimum '+getmininputvalue+ 'mm';
                    } else if (id == 'ScallopedWidth') {
                        getmsginput = 'Scalloped Width should be a minimum '+getmininputvalue+ 'mm';
                    } else if (id == 'ScallopedHeight') {
                        getmsginput = 'Scalloped Height should be a minimum '+getmininputvalue+ 'mm';
                    }else if (id == 'frameDepth') {
                        getmsginput = 'Frame Depth should be a minimum of 70mm';
                    }else if(id == 'rebatedWidth'){
                        getmsginput = 'Rebated Width should be a minimum '+getmininputvalue+ 'mm';
                    } else if(id == 'rebatedHeight'){
                        getmsginput = 'Rebated Height should be a minimum '+getmininputvalue+ 'mm';
                    } else if(id == 'hinge1Location'){
                        getmsginput = 'Hinge 1 Location should be a minimum of 100mm and maximum of 180mm ';
                    } else if(id == 'hinge2Location'){
                        getmsginput = 'Hinge 2 Location should be a minimum of 200mm';
                    } else if(id == 'hinge3Location'){
                        getmsginput = `Hinge ${document.getElementById('hing4LocationDiv') && document.getElementById('hing4LocationDiv').classList.contains('d-none')? '3' : '4'} Location should be a minimum of 150mm and maximum of 250mm `;
                    } else if(id == 'hinge4Location'){
                        getmsginput = 'Hinge 3 Location should be a minimum of 200mm';
                    }

                    $("#" + name + "-section").removeClass("table_row_show");
                    $("#" + name + "-section").addClass("table_row_hide");
                    swal('Warning', getmsginput);
                    $("#" + id).val('');
                    $("#" + id).css({ 'border': '1px solid red' });
                    return false;
                } else {
                    $("#" + id).css({ 'border': '1px solid #ced4da' });
                }
            }
        }

        if (ActualValue == "") {
            $("#" + name + "-section").removeClass("table_row_show");
            $("#" + name + "-section").addClass("table_row_hide");
            return false;
        }

        //var SelectedColor = Colors.findIndexOf(ElementValue);
        var isExist = false;


        if (TagName && typeof TagName === "string" && TagName.toLowerCase() === "select") {

            //Colors.forEach(function(elem, index) {
            //    if(ActualValue == elem.ColorName){
            //        isExist = true;
            //        price = elem.colorCost;
            //        console.log("Color id is = ",index);
            //    }
            //});

            // const possibleSelectedOptionsArray = ["doorLeafFacing"];

            console.log(possibleSelectedOptionsArray);
            console.log(SelectedOptions);

            if (isExist === false) {


                // if(possibleSelectedOptionsArray.includes(id)){


                if (possibleSelectedOptionsArray.hasOwnProperty(option_slug)) {
                    SetPrice(SelectedOptions, id, ActualValue, "selected_option");

                } else {
                    SetPrice(Options, id, ActualValue, "non_selected");
                }

            }

        }

    }

    $("#" + name + "-selected").empty().text(ElementValue);
    $("#" + name + "-price").empty().text("£" + price);
    $("#" + name + "-section").removeClass("table_row_hide");
    $("#" + name + "-section").addClass("table_row_show");

    if (name == "floorFinish") {
        var undercut = $("#undercut").val();

        var ConfigurableDoorFormula = JSON.parse(ConfigurableDoorFormulaJson);
        var UnderCutAdditionalNumber = 3;

        ConfigurableDoorFormula.forEach(function (elem, index) {

            var FormulaAdditionalData = JSON.parse(elem.value);
            if (elem.slug == "undercut") {
                UnderCutAdditionalNumber = parseFloat((FormulaAdditionalData.undercut != "") ? FormulaAdditionalData.undercut : 0);
            }
        });

        $("#undercut-selected").empty().text(parseFloat(ElementValue) + UnderCutAdditionalNumber);
        $("#undercut-price").empty().text("£" + price);
        $("#undercut-section").removeClass("table_row_hide");
        $("#undercut-section").addClass("table_row_show");
    }

    if (name == "sOWidth") {
        ShowLeafsWidth();
    }

}

function SetPrice(OptionsObj, id, ActualValue, type = "non_selected") {
    var isExist = false;
    OptionsObj.forEach(function (elem, index) {

        if (id == "intumescentSealArrangement") {

            if (ActualValue == elem.intumescentseals2_id) {

                if (type != "non_selected") {

                    price = elem.selected_cost;
                    return 1;

                } else {

                    price = elem.cost;
                    return 1;
                }
            }

        }
        else if (ActualValue == elem.OptionKey) {
            // if(ActualValue == elem.OptionKey){

            if (id == "leafConstruction" && elem.OptionSlug == "leaf_construction") {
                isExist = true;
            } else if (id == "fireRating" && elem.OptionSlug == "fire_rating") {
                isExist = true;
            } else if (id == "doorsetType" && elem.OptionSlug == "door_configuration_doorset_type") {
                isExist = true;
            } else if (id == "swingType" && elem.OptionSlug == "door_configuration_swing_type") {
                isExist = true;
            } else if (id == "latchType" && elem.OptionSlug == "door_configuration_latch_type") {
                isExist = true;
            } else if (id == "Handing" && elem.OptionSlug == "Handing") {
                isExist = true;
            } else if (id == "OpensInwards" && elem.OptionSlug == "Opens_Inwards") {
                isExist = true;
            } else if (id == "COC" && elem.OptionSlug == "COC") {
                isExist = true;
            } else if (id == "doorLeafFacing" && elem.OptionSlug == "Door_Leaf_Facing") {
                isExist = true;
            } else if (id == "doorLeafFacingValue" && elem.OptionSlug == "door_leaf_facing_value") {
                isExist = true;
            } else if (id == "doorLeafFinish" && elem.OptionSlug == "door_leaf_finish") {
                isExist = true;
            } else if (id == "decorativeGroves" && elem.OptionSlug == "Decorative_Groves") {
                isExist = true;
            } else if (id == "grooveLocation" && elem.OptionSlug == "Groove_Location") {
                isExist = true;
            } else if (id == "leaf1VisionPanel" && elem.OptionSlug == "leaf1_vision_panel") {
                isExist = true;
            } else if (id == "leaf1VisionPanelShape" && elem.OptionSlug == "Vision_panel_shape") {
                isExist = true;
            } else if (id == "visionPanelQuantity" && elem.OptionSlug == "leaf1_vision_panel_Qty") {
                isExist = true;
            } else if (id == "AreVPsEqualSizes" && elem.OptionSlug == "leaf1are_vps_equal_sizes") {
                isExist = true;
            } else if (id == "leaf2VisionPanel" && elem.OptionSlug == "leaf1_vision_panel") {
                isExist = true;
            } else if (id == "vpSameAsLeaf1" && elem.OptionSlug == "leaf1are_vps_equal_sizes") {
                isExist = true;
            } else if (id == "visionPanelQuantityforLeaf2" && elem.OptionSlug == "leaf1_vision_panel_Qty") {
                isExist = true;
            } else if (id == "AreVPsEqualSizesForLeaf2" && elem.OptionSlug == "leaf1are_vps_equal_sizes") {
                isExist = true;
            } else if (id == "lazingIntegrityOrInsulationIntegrity" && elem.OptionSlug == "Glass_Integrity") {
                isExist = true;
            } else if (id == "glassType" && elem.OptionSlug == "leaf1_glass_type") {
                isExist = true;
            } else if (id == "glazingSystems" && elem.OptionSlug == "leaf1_glazing_systems") {
                isExist = true;
            } else if (id == "glazingBeads" && elem.OptionSlug == "leaf1_glazing_beads") {
                isExist = true;
            } else if (id == "overPanel" && elem.OptionSlug == "Over_Panel_Fanlight") {
                isExist = true;
            } else if (id == "frameType" && elem.OptionSlug == "Frame_Type") {
                isExist = true;
            } else if (id == "frameFinish" && elem.OptionSlug == "Frame_Finish") {
                isExist = true;
            } else if (id == "extLiner" && elem.OptionSlug == "Ext_Liner") {
                isExist = true;
            } else if (id == "overpanel" && elem.OptionSlug == "door_configuration_overpanel") {
                isExist = true;
            } else if (id == "OPLippingThickness" && elem.OptionSlug == "op_lipping_thickness") {
                isExist = true;
            } else if (id == "transomThickness" && elem.OptionSlug == "transom_thickness") {
                isExist = true;
            } else if (id == "opGlassType" && elem.OptionSlug == "leaf1_glass_type") {
                isExist = true;
            } else if (id == "opGlazingBeads" && elem.OptionSlug == "leaf1_glazing_beads") {
                isExist = true;
            } else if (id == "sideLight1" && elem.OptionSlug == "SideLight1") {
                isExist = true;
            } else if (id == "sideLight1GlassType" && elem.OptionSlug == "leaf1_glass_type") {
                isExist = true;
            } else if (id == "SideLight1BeadingType" && elem.OptionSlug == "leaf1_glazing_beads") {
                isExist = true;
            } else if (id == "SL1Transom" && elem.OptionSlug == "SideLight1_transom") {
                isExist = true;
            } else if (id == "sideLight2" && elem.OptionSlug == "SideLight2") {
                isExist = true;
            } else if (id == "copyOfSideLite1" && elem.OptionSlug == "copy_Same_as_SL1") {
                isExist = true;
            } else if (id == "sideLight2GlassType" && elem.OptionSlug == "leaf1_glass_type") {
                isExist = true;
            } else if (id == "SideLight2BeadingType" && elem.OptionSlug == "leaf1_glazing_beads") {
                isExist = true;
            } else if (id == "SL2Transom" && elem.OptionSlug == "SideLight2_transom") {
                isExist = true;
            } else if (id == "lippingType" && elem.OptionSlug == "lipping_type") {
                isExist = true;
            } else if (id == "lippingThickness" && elem.OptionSlug == "lipping_thickness") {
                isExist = true;
            } else if (id == "meetingStyle" && elem.OptionSlug == "meeting_style") {
                isExist = true;
            } else if (id == "scallopedLippingThickness" && elem.OptionSlug == "scalloped_lipping_thickness") {
                isExist = true;
            } else if (id == "flatLippingThickness" && elem.OptionSlug == "flat_lipping_thickness") {
                isExist = true;
            } else if (id == "rebatedLippingThickness" && elem.OptionSlug == "rebeated_lipping_thickness") {
                isExist = true;
            } else if (id == "intumescentSealType" && elem.OptionSlug == "intumescent_seal_type") {
                isExist = true;
            } else if (id == "intumescentSealLocation" && elem.OptionSlug == "IntumescentSeal_location") {
                isExist = true;
            } else if (id == "intumescentSealColor" && elem.OptionSlug == "Intumescent_Seal_Color") {
                isExist = true;
            } else if (id == "accoustics" && elem.OptionSlug == "Accoustics") {
                isExist = true;
            } else if (id == "Architrave" && elem.OptionSlug == "Architrave") {
                isExist = true;
            } else if (id == "architraveMaterial" && elem.OptionSlug == "Architrave_Material") {
                isExist = true;
            } else if (id == "architraveType" && elem.OptionSlug == "Architrave_Type") {
                isExist = true;
            } else if (id == "architraveFinish" && elem.OptionSlug == "Architrave_Finish") {
                isExist = true;
            } else if (id == "architraveSetQty" && elem.OptionSlug == "Architrave_Set_Qty") {
                isExist = true;
            } else if (id == "IronmongeryID") {

                var Ironmongery = JSON.parse(IronmongeryJson);
                var IronmongeryID = $('input[name="IronmongeryID"]').val();

                Ironmongery.forEach(function (elem, index) {
                    if (IronmongeryID == elem.id) {
                        price = elem.discountprice;
                    }
                });

            }
            // alert(elem.SelectedOptionCost);

            if (isExist === true) {

                // if(elem.isUserWiseOption){
                if (type != "non_selected") {

                    price = elem.SelectedOptionCost;
                    return 1;

                } else {

                    price = elem.OptionCost;
                    return 1;
                }

            } else {
                price = 0.00;
            }

        }

    });
}
