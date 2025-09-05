// alert('dsh')
d3.select('button#save').on('click', function () {
    var config = {
        filename: 'customFileName'
    };
    d3_save_svg.save(d3.select('svg').node(), config);
});

$(document).ready(function () {
    var element = $(this);
    render(element);
});
var first = true;
var options = {
    events: {
        mouseWheel: true, // enables mouse wheel zooming events
        doubleClick: true, // enables double-click to zoom-in events
        drag: true, // enables drag and drop to move the SVG events
        dragCursor: "move" // cursor to use while dragging the SVG
    },
    animationTime: 300, // time in milliseconds to use as default for animations. Set 0 to remove the animation
    zoomFactor: 0.25, // how much to zoom-in or zoom-out
    maxZoom: 3, //maximum zoom in, must be a number bigger than 1
    panFactor: 100, // how much to move the viewBox when calling .panDirection() methods
    initialViewBox: { // the initial viewBox, if null or undefined will try to use the viewBox set in the svg tag. Also accepts string in the format "X Y Width Height"
        x: 0, // the top-left corner X coordinate
        y: 0, // the top-left corner Y coordinate
        width: 780, // the width of the viewBox
        height: 780 // the height of the viewBox
    },
    limits: { // the limits in which the image can be moved. If null or undefined will use the initialViewBox plus 15% in each direction
        x: -150,
        y: -150,
        x2: 1150,
        y2: 1150
    }
};
// create svg element:
var svg = d3.select("#container")
    .append("svg")
    .attr("preserveAspectRatio", "xMinYMin meet")
    .attr("viewBox", "0 0 780 1000")
    // .attr("height", "auto")
    .classed("svg-content", true);

//var svgPanZoom= $("svg").svgPanZoom(options);

var DoorUrl = $("#door_url").text();

const render = (CustomElement = null) => {

    function createLine(color, strokeWidth, x1, y1, x2, y2) {
        svg.append('line')
            .style("stroke", color)            // set line color
            .style("stroke-width", strokeWidth) // set line width
            .attr("x1", x1)                    // starting x-coordinate
            .attr("y1", y1)                    // starting y-coordinate
            .attr("x2", x2)                    // ending x-coordinate
            .attr("y2", y2);                   // ending y-coordinate
    }
    function createText(color, writingMode, x, fontSize, y, text) {
        svg.append("text")
            .style("fill", color)
            .style("writing-mode", writingMode)
            .attr("x", x)
            .attr("font-size", fontSize)
            .attr("y", y)
            .text(text);
    }

    var WritingMode = "lr";
    var ShowMeasurements = true;

    var ChangedFieldName = CustomElement.attr("name");

    if (!$("#change-dimension").prop('checked')) {
        // WritingMode = "tb";
        ShowMeasurements = false;
    }

    var frameonoff = 1;
    if ($("#frameonoff").prop('checked')) {
        frameonoff = 0;
    }

    var ConfigurableDoorFormula = JSON.parse(ConfigurableDoorFormulaJson);
    var UnderCutAdditionalNumber = 3;
    var TolleranceAdditionalNumber = 1;
    var FrameThicknessAdditionalNumber = 1;
    var GapAdditionalNumber = 1;
    var OPTolleranceAdditionalNumber = 2;
    var OPFrameThicknessAdditionalNumber = 2;
    var OPGapAdditionalNumber = 2;

    var MeetingStiles = 3;

    // Remove old elements:
    svg.selectAll('*').remove();

    var ix = 60, iy = 150;

    var DoorSetType = $('select[name="doorsetType"]').val();
    if (DoorSetType == "") {
        DoorSetType = 0;
    }

    var swingType = $('select[name="swingType"]').val();
    if (swingType == "") {
        swingType = 0;
    }

    var SOWidthForMap = 0;
    var SOWidth = $('input[name="sOWidth"]').val();
    var withoutFrameId = $("#withoutFrameId").val();
    var IsFourSidedFrame = document.getElementById('foursidedframe').checked
    if (withoutFrameId == 1) {
        var SOWidth =parseInt($('input[name="leafWidth1"]').val(), 10);
        if (DoorSetType == "DD") {
            SOWidth = SOWidth * 2;
        } else if (DoorSetType == "leaf_and_a_half") {
            SOWidth = parseInt($('input[name="leafWidth1"]').val()) + parseInt($('input[name="leafWidth2"]').val());
        }
    }
    if (SOWidth == "") {
        SOWidth = 0;
    } else {
        SOWidth = parseFloat(SOWidth);
        //SOWidthForMap = NumberChanger(SOWidth);
        SOWidthForMap = SOWidth / 5;
    }

    var SOHeightForMap = 0;
    var SOHeight = $('input[name="sOHeight"]').val();//this is height of door from top to bottom Leaf Height
    if (withoutFrameId == 1) {
        var SOHeight = $('input[name="leafHeightNoOP"]').val();
    }
    if (SOHeight == "") {
        SOHeight = 0;
    } else {
        SOHeight = parseFloat(SOHeight);
        //SOHeightForMap = NumberChanger(SOHeight);
        SOHeightForMap = SOHeight / 5;
    }

    if ((iy + SOHeightForMap) >= 780) {
        shape = document.getElementsByTagName("svg")[0];
        shape.setAttribute("viewBox", "0 0 780 " + (iy + SOHeightForMap + 100));
    }

    var Handing = $('select[name="Handing"]').val();;


    if (SOWidth > 0 && SOHeight > 0) {

        var Tollerance = $('input[name="tollerance"]').val();
        if (Tollerance == "") {
            Tollerance = 0;
        } else {
            Tollerance = parseFloat(Tollerance);
        }

        // var FrameThickness = $('select[name="frameThickness"]').val();
        var FrameThickness = $('#frameThickness').val();
        var FrameThicknessForMap = 0;
        if (FrameThickness == "") {
            FrameThickness = 0;
        } else {
            FrameThickness = parseFloat(FrameThickness);
            FrameThicknessForMap = FrameThickness / 5;
        }

        var Gap = $('input[name="gap"]').val();
        var GapForMap = 0;
        if (Gap == "") {
            Gap = 0;
        } else {
            Gap = parseFloat(Gap);
            GapForMap = Gap / 5;
            // GapForMap = 3;
        }

        var FloorFinish = $('input[name="floorFinish"]').val();
        if (FloorFinish == "") {
            FloorFinish = 0;
        } else {
            FloorFinish = parseFloat(FloorFinish);
        }
        swingType = $('select[name="swingType"]').val();
        if (swingType == "") {
            swingType = 0;
        }

        //HINGES

        var hingeLocation1 = ($('input[name="hinge1Location"]').val() > 0) ? parseInt($('input[name="hinge1Location"]').val()) : 0;
        var hingeLocation2 = ($('input[name="hinge2Location"]').val() > 0) ? parseInt($('input[name="hinge2Location"]').val()) : 0;
        var hingeLocation3 = ($('input[name="hinge3Location"]').val() > 0) ? parseInt($('input[name="hinge3Location"]').val()) : 0;
        var hingeLocation4 = ($('input[name="hinge4Location"]').val() > 0) ? parseInt($('input[name="hinge4Location"]').val()) : 0;
        var hingeCenter = ($('input[name="hingeCenterCheck"]').is(':checked')) ? 1 : 0;

        var FrameWidthAdditionalNumber = 2;


        if (swingType == "DA") {
            $('input[name="hinge4Location"]').attr('readonly', true);
            $('input[name="hinge2Location"]').attr('readonly', true);
            $('input[name="hinge3Location"]').attr('readonly', true);
            $('input[name="hinge1Location"]').attr('readonly', true);
            $('input[name="hingeCenterCheck"]').attr('disabled', true);
        } else if (hingeCenter == 1) {
            $('input[name="hinge4Location"]').attr('readonly', true);
            $('input[name="hinge2Location"]').attr('readonly', true);
            $('input[name="hinge3Location"]').attr('readonly', false);
            $('input[name="hinge1Location"]').attr('readonly', false);
            $('input[name="hingeCenterCheck"]').attr('disabled', false);
        } else {
            $('input[name="hinge4Location"]').attr('readonly', false);
            $('input[name="hinge2Location"]').attr('readonly', false);
            $('input[name="hinge3Location"]').attr('readonly', false);
            $('input[name="hinge1Location"]').attr('readonly', false);
            $('input[name="hingeCenterCheck"]').attr('disabled', false);
        }

        ConfigurableDoorFormula.forEach(function (elem, index) {

            var FormulaAdditionalData = JSON.parse(elem.value);
            if (elem.slug == "undercut") {
                UnderCutAdditionalNumber = parseFloat((FormulaAdditionalData.undercut != "") ? FormulaAdditionalData.undercut : 0);
            }

            if (elem.slug == "op_width") {
                OPTolleranceAdditionalNumber = parseFloat((FormulaAdditionalData.tolerance != "") ? FormulaAdditionalData.tolerance : 1);
                OPFrameThicknessAdditionalNumber = parseFloat((FormulaAdditionalData.frame_thickness != "") ? FormulaAdditionalData.frame_thickness : 1);
                OPGapAdditionalNumber = parseFloat((FormulaAdditionalData.gap != "") ? FormulaAdditionalData.gap : 1);
            }

            if (DoorSetType == "SD") {

                if (elem.slug == "leaf_width_1_for_single_door_set") {
                    TolleranceAdditionalNumber = parseFloat((FormulaAdditionalData.tolerance != "") ? FormulaAdditionalData.tolerance : 1);
                    FrameThicknessAdditionalNumber = parseFloat((FormulaAdditionalData.frame_thickness != "") ? FormulaAdditionalData.frame_thickness : 1);
                    GapAdditionalNumber = parseFloat((FormulaAdditionalData.gap != "") ? FormulaAdditionalData.gap : 1);
                }

            } else if (DoorSetType == "DD") {

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
                if (elem.slug == "leaf_width_2_for_leaf_and_a_half") {
                    TolleranceAdditionalNumber = parseFloat((FormulaAdditionalData.tolerance != "") ? FormulaAdditionalData.tolerance : 1);
                    FrameThicknessAdditionalNumber = parseFloat((FormulaAdditionalData.frame_thickness != "") ? FormulaAdditionalData.frame_thickness : 1);
                    GapAdditionalNumber = parseFloat((FormulaAdditionalData.gap != "") ? FormulaAdditionalData.gap : 1);
                }
            }


            if (elem.slug == "frame_width") {
                FrameWidthAdditionalNumber = parseFloat((FormulaAdditionalData.tolerance != "") ? FormulaAdditionalData.tolerance : 2);
            }


        });

        var FrameWidth = SOWidth - (Tollerance * FrameWidthAdditionalNumber);
        var FrameWidthForMap = 0;
        $("#frameWidth").val(FrameWidth);
        framewidth();
        // if(ChangedFieldName == "frameWidth"){
        //     FrameWidth = $('input[name="frameWidth"]').val();
        //     if(FrameWidth != "" && FrameWidth > 0){
        //         FrameWidth = parseFloat(FrameWidth);
        //     }
        // }

        if (FrameWidth > 0) {
            FrameWidthForMap = FrameWidth / 5;
        }

        var TopFrameHeight = FrameThicknessForMap;

        var FrameHeight = SOHeight - Tollerance;
        var FrameHeightForMap = 0;
        // if(ChangedFieldName == "frameHeight"){
        //     FrameHeight = $('input[name="frameHeight"]').val();
        //     if(FrameHeight != "" && FrameHeight > 0){
        //         FrameHeight = parseFloat(FrameHeight);
        //     }
        // }

        if (FrameHeight > 0) {
            FrameHeightForMap = FrameHeight / 5;
        }

        var UnderCut = 0;
        if (FloorFinish > 0) {
            if ($("#fireRating").val() == 'FD30' || $("#fireRating").val() == 'FD60') {
                UnderCut = FloorFinish + 8;
            } else if ($("#fireRating").val() == 'FD30s' || $("#fireRating").val() == 'FD60s') {
                UnderCut = FloorFinish + 3;
            } else {
                UnderCut = $('#undercut').val();
            }
        }else{
            UnderCut = $('#undercut').val();
        }

        var LeafWidth1 = 0;
        var LeafWidth2 = 0;
        var LeafWidth1ForMap = 0;
        var LeafWidth2ForMap = 0;
        var ScallopedHeight = parseInt($('input[name="ScallopedHeight"]').val(), 10) || 0 ;

        if (DoorSetType == "DD" && withoutFrameId != 1) {
            LeafWidth1 = LeafWidth2 = (SOWidth - (Tollerance * TolleranceAdditionalNumber) - (FrameThickness * FrameThicknessAdditionalNumber) - (GapAdditionalNumber * Gap)) / 2;
            if($("#frameType").val() == 'Scalloped'){
                LeafWidth1 = LeafWidth2 = (SOWidth - (Tollerance * TolleranceAdditionalNumber) - ((FrameThickness - ScallopedHeight) * 2) - (GapAdditionalNumber * Gap)) / 2;
                console.log(
                    `(${SOWidth} - (${Tollerance} * ${TolleranceAdditionalNumber}) - ((${FrameThickness} - ${ScallopedHeight}) * 2) - (${GapAdditionalNumber} * ${Gap})) / 2 = LeafWidth1 = LeafWidth2 = ${LeafWidth1}`
                    );
            }
        } else if (DoorSetType == "SD" && withoutFrameId != 1) {
            LeafWidth1 = SOWidth - (Tollerance * TolleranceAdditionalNumber) - (FrameThickness * FrameThicknessAdditionalNumber) - (GapAdditionalNumber * Gap);
            if($("#frameType").val() == 'Scalloped'){
                LeafWidth1 = SOWidth - (Tollerance * TolleranceAdditionalNumber) - (FrameThickness - ScallopedHeight) - FrameThickness - (GapAdditionalNumber * Gap);
                console.log(
                `${SOWidth} - (${Tollerance} * ${TolleranceAdditionalNumber}) - (${FrameThickness} - ${ScallopedHeight}) - ${FrameThickness} - (${GapAdditionalNumber} * ${Gap}) = LeafWidth1 ${LeafWidth1}`
                );

            }
        } else if (DoorSetType == "leaf_and_a_half") {
            if (withoutFrameId != 1) {
                LeafWidth1 =parseInt($('input[name="leafWidth1"]').val(), 10);
                if (LeafWidth1 != "") {
                    LeafWidth1 = parseFloat(LeafWidth1);
                }
                LeafWidth2 = SOWidth - (Tollerance * TolleranceAdditionalNumber) - (FrameThickness * FrameThicknessAdditionalNumber) - (GapAdditionalNumber * Gap) - LeafWidth1;
            }
            DoorSetType = "DD";
        }

        if (withoutFrameId == 1) {
            var LeafHeightNoOP = $('input[name="leafHeightNoOP"]').val();
            var SOHeight = $('input[name="leafHeightNoOP"]').val();
            var SOWidth = parseInt($('input[name="leafWidth1"]').val(), 10);
            var LeafWidth1 = parseInt($('input[name="leafWidth1"]').val(), 10);
            if (DoorSetType == "DD") {
                var LeafWidth2 = parseInt($('input[name="leafWidth1"]').val(), 10);
            } else {
                var LeafWidth2 = parseInt($('input[name="leafWidth2"]').val(), 10);
            }

            if ($('select[name="doorsetType"]').val() == 'leaf_and_a_half') {
                var LeafWidth2 = parseInt($('input[name="leafWidth2"]').val(), 10);
                $('input[name="leafWidth2"]').attr('readonly', false)
            }
        }
        if (LeafWidth1 >= 0) {
            $('input[name="leafWidth1"]').val(LeafWidth1);
            $("#leafWidth1-selected").empty().text(LeafWidth1);
            $("#leafWidth1-price").empty().text("£0.00");
            $("#leafWidth1-section").removeClass("table_row_hide");
            $("#leafWidth1-section").addClass("table_row_show");
        }
        if (LeafWidth2 >= 0) {
            $('input[name="leafWidth2"]').val(LeafWidth2);
            $("#leafWidth2-selected").empty().text(LeafWidth2);
            $("#leafWidth2-price").empty().text("£0.00");
            $("#leafWidth2-section").removeClass("table_row_hide");
            $("#leafWidth2-section").addClass("table_row_show");
        }
        framewidth();

        if ($('#doorsetType').val() == 'leaf_and_a_half') {
            if (Handing == 'Right_Hand_Master_Left_Hand_Slave') {
                LeafWidth1 = LeafWidth1 + LeafWidth2;
                LeafWidth2 = LeafWidth1 - LeafWidth2;
                LeafWidth1 = LeafWidth1 - LeafWidth2;
            }
            if (LeafWidth1 != "" && LeafWidth1 > 0) {
                LeafWidth1ForMap = LeafWidth1 / 5;
            }
            if (LeafWidth2 != "" && LeafWidth2 > 0) {
                LeafWidth2ForMap = LeafWidth2 / 5;
            }
        } else {
            if (LeafWidth1 != "" && LeafWidth1 > 0) {
                LeafWidth1ForMap = LeafWidth1 / 5;
            }

            LeafWidth1 =parseInt($('input[name="leafWidth1"]').val(), 10);

            if (LeafWidth2 != "" && LeafWidth2 > 0) {
                LeafWidth2ForMap = LeafWidth2 / 5;
            }
        }

        var LeafHeightNoOPForMap = 0;
        var LeafHeightNoOP = SOHeight - Tollerance - FrameThickness - UnderCut - Gap;
        let foursidedframe = document.getElementById("foursidedframe");
        if (foursidedframe.checked) {
            LeafHeightNoOP = SOHeight - (Tollerance * 2) - (FrameThickness * 2) - (Gap * 2);
        }
        if (LeafHeightNoOP == "") {
            LeafHeightNoOP = 0;
        } else {
            //LeafHeightNoOPForMap = NumberChanger(LeafHeightNoOP);
             LeafHeightNoOPForMap = (parseInt(LeafHeightNoOP) + parseInt(UnderCut || 0)) / 5;
        }

        if (LeafHeightNoOP > 0) {
            $("#leafHeightNoOP-selected").empty().text(LeafHeightNoOP);
            $("#leafHeightNoOP-price").empty().text("£0.00");
            $("#leafHeightNoOP-section").removeClass("table_row_hide");
            $("#leafHeightNoOP-section").addClass("table_row_show");
        }

        var LeftGapForLeaf1 = 0, RightGapForLeaf1 = 0, LeftGapForLeaf2 = 0, RightGapForLeaf2 = 0, UpperAndLowerGap = 0;
        //UpperAndLowerGap  = NumberChanger(Tollerance + FrameThickness + UnderCut + Gap);
        // UpperAndLowerGap  = ((Tollerance + FrameThickness + UnderCut + Gap) / 5) / 2;
        UpperAndLowerGap = FrameThicknessForMap + GapForMap;

        // if (LeafHeightNoOP > 2400) {
        //     $('#hinge3LocationLabel').text('Hinge 4 Location  (Min 150 mm, Max 250 mm)');
        //     $('#hing4LocationDiv').removeClass("d-none");
        // } else {
        //     $('#hinge3LocationLabel').text('Hinge 3 Location  (Min 150 mm, Max 250 mm)');
        //     $('#hing4LocationDiv').addClass("d-none");
        // }

        // if(LeafWidth1 >= 0 && LeafHeightNoOP > 0) {
        //     var fireRating = $("#fireRating").val();
        //     var issingleconfiguration = $("input[name=issingleconfiguration]").val();
        //     $.ajax({
        //         url:  $("#doorStandardPrice").html(),
        //         method:"POST",
        //         data:{_token:$("#_token").val(), LeafWidth1:LeafWidth1, LeafHeightNoOP:LeafHeightNoOP, fireRating:fireRating,issingleconfiguration:issingleconfiguration},
        //         dataType:"Json",
        //         success: function(result){
        //             if(result.status=="ok"){
        //                 $("#leafWidth1-price").empty().text("£" + result.door_core);
        //                 $("#leafHeightNoOP-price").empty().text("£" + result.door_core);
        //             }
        //         }
        //     });
        // }

        var TotalSideGap = 0;

        if (DoorSetType == "DD") {
            TotalSideGap = ((Tollerance * TolleranceAdditionalNumber) + (FrameThickness * FrameThicknessAdditionalNumber) + (GapAdditionalNumber * Gap)) / 2;
            LeftGapForLeaf1 = RightGapForLeaf2 = TotalSideGap / 5;
        } else if (DoorSetType == "SD") {
            TotalSideGap = ((Tollerance * TolleranceAdditionalNumber) + (FrameThickness * FrameThicknessAdditionalNumber) + (GapAdditionalNumber * Gap)) / 2;
            LeftGapForLeaf1 = RightGapForLeaf1 = TotalSideGap / 5;
        }

        var TotalDoorWidth = 0;
        var RemainingGap = 0;

        if (DoorSetType == "DD") {
            TotalDoorWidth = FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap + GapForMap + FrameThicknessForMap;
            if (TotalDoorWidth > 0) {
                RemainingGap = (TotalDoorWidth - FrameWidthForMap) / 2;
                LeafWidth1ForMap = LeafWidth1ForMap - RemainingGap;
                LeafWidth2ForMap = LeafWidth2ForMap - RemainingGap;
            }
        } else {
            TotalDoorWidth = FrameThicknessForMap + GapForMap + LeafWidth1ForMap + GapForMap + FrameThicknessForMap;
            if (TotalDoorWidth > 0) {
                RemainingGap = TotalDoorWidth - FrameWidthForMap;
                LeafWidth1ForMap = LeafWidth1ForMap - RemainingGap;
            }
        }

        var SideLightPanel1Width = 0;
        var SideLightPanel1WidthToShow = 0;

        var SideLightPanel1WidthSpaceForVerticalLines = 0;

        var SideLightPanel1 = $('select[name="sideLight1"]').val();
        if (SideLightPanel1 == "Yes") {
            SideLightPanel1Width = $('input[name="SL1Width"]').val();
            SideLightPanel1WidthToShow = 0;
            if (SideLightPanel1Width == "") {
                SideLightPanel1Width = 0;
            } else {
                SideLightPanel1Width = parseFloat(SideLightPanel1Width);
                SideLightPanel1WidthToShow = SideLightPanel1Width;
                if (SideLightPanel1Width > 0) {
                    SideLightPanel1Width = SideLightPanel1Width / 5;
                }
            }

            var SideLightPanel1Height = $('input[name="SL1Height"]').val();
            var SideLightPanel1HeightToShow = 0;
            if (SideLightPanel1Height == "") {
                SideLightPanel1Height = 0;
            } else {
                SideLightPanel1Height = parseFloat(SideLightPanel1Height);
                SideLightPanel1HeightToShow = SideLightPanel1Height;
                if (SideLightPanel1Height > 0) {
                    SideLightPanel1Height = SideLightPanel1Height / 5;
                }
            }

            //SideLightPanel1WidthSpaceForVerticalLines = ( LeftGapForLeaf1 * 2 ) + SideLightPanel1Width;
            SideLightPanel1WidthSpaceForVerticalLines = SideLightPanel1Width;
        }

        var SideLightPanel2Width = SideLightPanel1Width;
        var SideLightPanel2WidthToShow = SideLightPanel1WidthToShow;
        var SideLightPanel2Height = SideLightPanel1Height;
        var SideLightPanel2HeightToShow = SideLightPanel1HeightToShow;

        var SideLightPanel2WidthSpaceForVerticalLines = 0;

        var SideLightPanel2 = $('select[name="sideLight2"]').val();
        if (SideLightPanel2 == "Yes") {

            var CopyOfSideLite1 = $('select[name="copyOfSideLite1"]').val();

            if (CopyOfSideLite1 != "Yes") {

                SideLightPanel2Width = $('input[name="SL2Width"]').val();
                if (SideLightPanel2Width == "") {
                    SideLightPanel2Width = 0;
                } else {
                    SideLightPanel2Width = parseFloat(SideLightPanel2Width);
                    SideLightPanel2WidthToShow = SideLightPanel2Width;
                    if (SideLightPanel2Width > 0) {
                        SideLightPanel2Width = SideLightPanel2Width / 5;
                    }
                }

                SideLightPanel2Height = $('input[name="SL2Height"]').val();
                if (SideLightPanel2Height == "") {
                    SideLightPanel2Height = 0;
                } else {
                    SideLightPanel2Height = parseFloat(SideLightPanel2Height);
                    SideLightPanel2HeightToShow = SideLightPanel2Height;
                    if (SideLightPanel2Height > 0) {
                        SideLightPanel2Height = SideLightPanel2Height / 5;
                    }
                }
            }

            //SideLightPanel2WidthSpaceForVerticalLines = ( LeftGapForLeaf1 * 2 ) + SideLightPanel2Width;
            SideLightPanel2WidthSpaceForVerticalLines = SideLightPanel2Width;

            //ix = ix + ( LeftGapForLeaf1 * 2 ) + SideLightPanel1Width;
        }

        var TotalCadWidth = SideLightPanel1WidthSpaceForVerticalLines + SOWidthForMap + SideLightPanel2WidthSpaceForVerticalLines;

        if (ShowMeasurements) {
            TotalCadWidth = TotalCadWidth + 100;
        }

        if (TotalCadWidth < 780) {
            var RemainingSvgSpace = 780 - TotalCadWidth;
            ix = RemainingSvgSpace / 2;
        }


        var handle = LeafHeightNoOPForMap / 2;

        var GapAfterOverPanelApplied = 0;



        var IsOverPanelActive = $('select[name="overpanel"]').val();
        if (IsOverPanelActive != "" && IsOverPanelActive != "No") {

            var GapForOverPanel = (Tollerance * OPTolleranceAdditionalNumber) + (FrameThickness * OPFrameThicknessAdditionalNumber) + (OPGapAdditionalNumber * Gap);

            var OverPanelWidthToShow = SOWidth - GapForOverPanel;
            var OverPanelWidth = OverPanelWidthToShow / 5;
 var OpFrameThickness = $('input[name="OpBeadThickness"]').val();
            var OverPanelHeight = $('input[name="oPHeigth"]').val();
            var OverPanelHeightToShow = 0;
            // if(OverPanelHeight == ""){
            //     OverPanelHeight = 0;
            // }else{
            OverPanelHeight = parseFloat(OverPanelHeight);
            // if (OverPanelHeight > 600) {
            //     OverPanelHeight = 0;
            // }

            OverPanelHeightToShow = OverPanelHeight;
            if (OverPanelHeight > 0) {
                OverPanelHeight = OverPanelHeight / 5;
            }
            // }

            SOHeightForMap = SOHeightForMap + (GapForOverPanel / 5) + OverPanelHeight;

            TopFrameHeight = (FrameThicknessForMap) + OverPanelHeight;

            //GapAfterOverPanelApplied = (GapForOverPanel / 5) + OverPanelHeight;
            GapAfterOverPanelApplied = TopFrameHeight - FrameThicknessForMap;

        }

        let hngLctn1Y = (hingeLocation1 > 0) ? iy + TopFrameHeight + GapForMap + (hingeLocation1 / 5) : iy + TopFrameHeight + GapForMap + 25;
        let hngLctn2Y = (hingeLocation2 > 0) ? hngLctn1Y + 20 + (hingeLocation2 / 5) : iy + 90 + 20; // 20 ADD (HINGE HEIGHT)

        // let hngLctn3Y = (hingeLocation3 > 0 ) ? iy+TopFrameHeight+(FrameHeightForMap-FrameThicknessForMap)-(FrameThicknessForMap+GapForMap+(hingeLocation3/5)) : iy+TopFrameHeight+(FrameHeightForMap-FrameThicknessForMap)-(FrameThicknessForMap+GapForMap+36);
        let hngLctn3Y = (hingeLocation3 > 0) ? iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap) - (hingeLocation3 / 5) - 20 : iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap) - 36 - 20;

        let hngLctn4Y = (hingeLocation4 > 0) ? hngLctn3Y - 20 - (hingeLocation4 / 5) : hngLctn3Y - 20 - 90; // 20 ADD (HINGE HEIGHT)

        if (hingeCenter == 1) {
            hngLctn2Y = hngLctn1Y + ((hngLctn3Y - hngLctn1Y) / 2);
        }
        if (LeafHeightNoOP > 2400) {
            if (hingeCenter == 1) {
                hngLctn2Y = hngLctn1Y + ((hngLctn3Y - hngLctn1Y) / 3) + 20;
                hngLctn4Y = hngLctn1Y + (((hngLctn3Y - hngLctn1Y) / 3) * 2) - 20;
            }
        }

        let hinge1hieght = hingeLocation1 > 0 ? hingeLocation1 : 25 * 5;
        let hinge2hieght = hingeLocation2 > 0 ? hingeLocation2 : ((90 * 5) - hinge1hieght); // 102mm HINGE HEIGHT
        let hinge3hieght = hingeLocation3 > 0 ? hingeLocation3 : 36 * 5;
        let hinge4hieght = hingeLocation4 > 0 ? hingeLocation4 : ((90 * 5));

        let hinge4FCenter = LeafHeightNoOP - (hinge3hieght + hinge2hieght + hinge1hieght) - (102 * 3);
        let hinge4SCenter = LeafHeightNoOP - (hinge3hieght + hinge4hieght + hinge2hieght + hinge1hieght) - (102 * 4);

        console.log(hinge1hieght, hinge2hieght, hinge3hieght, hinge4hieght, 'llllllllllllllllllll')

        if (hingeCenter == 1) {
            let centerHeight = LeafHeightNoOP - (hinge1hieght + hinge3hieght);// REMOVE 2 HINGE HEIGHT
            if (LeafHeightNoOP > 2400) {
                centerHeight = centerHeight - (102 * 4);
                hinge2hieght = (centerHeight / 3);
                hinge4SCenter = (centerHeight / 3);
                hinge4hieght = (centerHeight / 3);
            } else {
                hingeTotalHeight = 102 * 3;
                hinge2hieght = (centerHeight / 2) - (hingeTotalHeight / 2);
                hinge4FCenter = (centerHeight / 2) - (hingeTotalHeight / 2);
            }
        }

        if (swingType != "DA") {
            $('input[name="hinge1Location"]').val(hinge1hieght);
            $('input[name="hinge2Location"]').val(parseFloat(hinge2hieght.toFixed(1)));
            $('input[name="hinge3Location"]').val(parseFloat(hinge3hieght.toFixed(1)));
            $('input[name="hinge4Location"]').val(parseFloat(hinge4hieght.toFixed(1)));
        } else {
            $('input[name="hinge1Location"]').val('');
            $('input[name="hinge2Location"]').val('');
            $('input[name="hinge3Location"]').val('');
            $('input[name="hinge4Location"]').val('');
        }

      
        if (SideLightPanel1 == "Yes") {
            // console.log(FrameThicknessForMap, 'llllllllllllll')
            // if (OverPanelHeight) {
            //     svg.append('rect') //outer sidepanel rect
            //         .attr('x', ix)
            //         .attr('y', iy)
            //         //.attr('width', ( LeftGapForLeaf1 * 2 ) + SideLightPanel1Width)
            //         .attr('width', SideLightPanel1Width)
            //         //.attr('height', ( UpperAndLowerGap * 2 ) + SideLightPanel1Height)
            //         .attr('height', (FrameHeight / 5) + OverPanelHeight)
            //         .attr('stroke', 'black')
            //         .attr('fill', '#D0D0C6');

            //     svg.append('rect')// inner side panel rect
            //         //.attr('x', ix + LeftGapForLeaf1)
            //         .attr('x', ix + FrameThicknessForMap)
            //         //.attr('y', iy + GapAfterOverPanelApplied + UpperAndLowerGap)
            //         .attr('y', iy + GapAfterOverPanelApplied - OverPanelHeight + FrameThicknessForMap)
            //         //.attr('width', SideLightPanel1Width)
            //         .attr('width', SideLightPanel1Width - (FrameThicknessForMap * 2))
            //         //.attr('height', SideLightPanel1Height)
            //         .attr('height', (FrameHeight / 5) - (2 * FrameThicknessForMap) + OverPanelHeight)
            //         .attr('stroke', 'black')
            //         .attr('fill', '#CDD8DD');
            // } else {
                svg.append('rect') //outer sidepanel rect
                    .attr('x', ix)
                    .attr('y', iy + GapAfterOverPanelApplied)
                    //.attr('width', ( LeftGapForLeaf1 * 2 ) + SideLightPanel1Width)
                    .attr('width', SideLightPanel1Width)
                    //.attr('height', ( UpperAndLowerGap * 2 ) + SideLightPanel1Height)
                    .attr('height', (FrameHeight / 5))
                    .attr('stroke', 'black')
                    .attr('fill', '#D0D0C6');

                svg.append('rect')// inner side panel rect
                    //.attr('x', ix + LeftGapForLeaf1)
                    .attr('x', ix + FrameThicknessForMap)
                    //.attr('y', iy + GapAfterOverPanelApplied + UpperAndLowerGap)
                    .attr('y', iy + GapAfterOverPanelApplied + FrameThicknessForMap)
                    //.attr('width', SideLightPanel1Width)
                    .attr('width', SideLightPanel1Width - (FrameThicknessForMap * 2))
                    //.attr('height', SideLightPanel1Height)
                    .attr('height', (FrameHeight / 5) - (FrameThicknessForMap * 2))
                    .attr('stroke', 'black')
                    .attr('fill', '#CDD8DD');
            // }
            if (ShowMeasurements) {
                if (SideLightPanel1Width > 0) {

                        svg.append('line')//vertical line to show measurement of side panel
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix - 10)
                            .attr("y1", iy + GapAfterOverPanelApplied  )
                            .attr("x2", ix - 10)
                            .attr("y2", iy + GapAfterOverPanelApplied  + (FrameHeight / 5))
                            .attr("marker-start", "url(#arrowLeft)")
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")           // vertical line to show measurement text of side panel
                            .style("fill", "black")
                            .attr("font-size", 10)
                            .attr("x", ix - 15)
                            .attr("y", iy + GapAfterOverPanelApplied + ((FrameHeight / 5)/ 2) - 15)
                            .attr("transform", `rotate(-90, ${ix - 15},
                               ${iy +GapAfterOverPanelApplied  + (( (FrameHeight / 5) ) / 2) - 15})`)
                            .text((SideLightPanel1Height*5) );

                        svg.append('line')//vertical line to show measurement of side panel joining line top
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix - 10 - 5)
                            .attr("y1", iy + GapAfterOverPanelApplied )
                            .attr("x2", ix + FrameThicknessForMap)
                            .attr("y2", iy + GapAfterOverPanelApplied )

                        svg.append('line')//vertical line to show measurement of side panel joining line bottom
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix - 10 - 5)
                            .attr("y1", iy + GapAfterOverPanelApplied  + (FrameHeight / 5) )
                            .attr("x2", ix + FrameThicknessForMap)
                            .attr("y2", iy + GapAfterOverPanelApplied  + (FrameHeight / 5) )

                        svg.append('line')//horizontal line to show measurement of side panel
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix )
                            .attr("y1", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 10)
                            .attr("x2", ix + SideLightPanel1Width )
                            .attr("y2", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 10)
                            .attr("marker-start", "url(#arrowLeft)")
                            .attr("marker-end", "url(#arrowRight)")
                        svg.append("text")           //horizontal line to show measurement text of side panel
                            .style("fill", "black")
                            .attr("font-size", 10)
                            .attr("x", ix + (SideLightPanel1Width / 2) - 5)
                            .attr("y", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 30)
                            .text(SideLightPanel1WidthToShow );
                        svg.append('line')//horizontal line to show measurement of side panel joining line
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix )
                            .attr("y1", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5))
                            .attr("x2", ix )
                            .attr("y2", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 15)
                        svg.append('line')//horizontal line to show measurement of side panel joining line
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + SideLightPanel1Width )
                            .attr("y1", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5))
                            .attr("x2", ix + SideLightPanel1Width )
                            .attr("y2", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 15)
                    
                }


            }

            //ix = ix + ( LeftGapForLeaf1 * 2 ) + SideLightPanel1Width;
            ix = ix + SideLightPanel1Width;
        }

        if (SideLightPanel2 == "Yes") {
//  svg.append('rect') //outer sidepanel rect
//                     .attr('x', ix)
//                     .attr('y', iy + GapAfterOverPanelApplied)
//                     //.attr('width', ( LeftGapForLeaf1 * 2 ) + SideLightPanel1Width)
//                     .attr('width', SideLightPanel1Width)
//                     //.attr('height', ( UpperAndLowerGap * 2 ) + SideLightPanel1Height)
//                     .attr('height', (FrameHeight / 5))
//                     .attr('stroke', 'black')
//                     .attr('fill', '#D0D0C6');
            // if (OverPanelHeight) {
            //     svg.append('rect') //outer sidepanel rect
            //         .attr('x', ix + FrameWidthForMap)
            //         .attr('y', iy)
            //         .attr('width', SideLightPanel2Width)
            //         .attr('height', (FrameHeight / 5) + OverPanelHeight)
            //         .attr('stroke', 'black')
            //         .attr('fill', '#D0D0C6');

            //     svg.append('rect')//copy inner side light
            //         .attr('x', ix + FrameWidthForMap + FrameThicknessForMap)
            //         .attr('y', iy + GapAfterOverPanelApplied + FrameThicknessForMap - OverPanelHeight)
            //         .attr('width', SideLightPanel2Width - (FrameThicknessForMap * 2))
            //         .attr('height', (FrameHeight / 5) - (FrameThicknessForMap * 2) + OverPanelHeight)
            //         .attr('stroke', 'black')
            //         .attr('fill', '#CDD8DD');

            // } else {
                svg.append('rect') //outer sidepanel rect
                    .attr('x', ix + FrameWidthForMap)
                    .attr('y', iy + GapAfterOverPanelApplied)
                    .attr('width', SideLightPanel2Width)
                    .attr('height', (FrameHeight / 5))
                    .attr('stroke', 'black')
                    .attr('fill', '#D0D0C6');

                svg.append('rect')//copy inner side light
                    .attr('x', ix + FrameWidthForMap + FrameThicknessForMap)
                    .attr('y', iy + GapAfterOverPanelApplied + FrameThicknessForMap)
                    .attr('width', SideLightPanel2Width - (FrameThicknessForMap * 2))
                    .attr('height', (FrameHeight / 5) - (FrameThicknessForMap * 2))
                    .attr('stroke', 'black')
                    .attr('fill', '#CDD8DD');
            // }

            if (ShowMeasurements) {
                
                if (SideLightPanel2Width > 0) {

                        svg.append('line')//vertical line to show measurement of side panel
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameWidthForMap + SideLightPanel2Width + 10)
                            .attr("y1", iy + GapAfterOverPanelApplied  )
                            .attr("x2", ix + FrameWidthForMap + SideLightPanel2Width + 10)
                            .attr("y2", iy + GapAfterOverPanelApplied  + (FrameHeight / 5) )
                            .attr("marker-start", "url(#arrowLeft)")
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")           // vertical line to show measurement text of side panel
                            .style("fill", "black")
                            .attr("font-size", 10)
                            .attr("x", ix + FrameWidthForMap + SideLightPanel2Width + 20)
                            .attr("y", iy + GapAfterOverPanelApplied  + (( (FrameHeight / 5) ) / 2) - 15)
                            .attr("transform", `rotate(-90, ${ix + FrameWidthForMap + SideLightPanel2Width + 20},
                               ${iy + GapAfterOverPanelApplied  + (( (FrameHeight / 5) ) / 2) - 15})`)
                            .text((SideLightPanel1Height*5));

                        svg.append('line')//vertical line to show measurement of side panel joining line top
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameWidthForMap + SideLightPanel2Width + 10 + 5)
                            .attr("y1", iy + GapAfterOverPanelApplied )
                            .attr("x2", ix + FrameWidthForMap + SideLightPanel2Width + FrameThicknessForMap)
                            .attr("y2", iy + GapAfterOverPanelApplied )

                        svg.append('line')//vertical line to show measurement of side panel joining line bottom
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameWidthForMap + SideLightPanel2Width - FrameThicknessForMap)
                            .attr("y1", iy + GapAfterOverPanelApplied  + (FrameHeight / 5) )
                            .attr("x2", ix + FrameWidthForMap + SideLightPanel2Width + FrameThicknessForMap + 10)
                            .attr("y2", iy + GapAfterOverPanelApplied  + (FrameHeight / 5) )

                        svg.append('line')//horizontal line to show measurement of side panel
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameWidthForMap )
                            .attr("y1", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 10)
                            .attr("x2", ix + FrameWidthForMap + SideLightPanel2Width )
                            .attr("y2", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 10)
                            .attr("marker-start", "url(#arrowLeft)")
                            .attr("marker-end", "url(#arrowRight)")
                        svg.append("text")           //horizontal line to show measurement text of side panel
                            .style("fill", "black")
                            .attr("font-size", 10)
                            .attr("x", ix + FrameWidthForMap + (SideLightPanel2Width / 2) - 5)
                            .attr("y", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 30)
                            .text(SideLightPanel2WidthToShow );
                        svg.append('line')//horizontal line to show measurement of side panel joining line
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameWidthForMap)
                            .attr("y1", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5))
                            .attr("x2", ix + FrameWidthForMap )
                            .attr("y2", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 15)
                        svg.append('line')//horizontal line to show measurement of side panel joining line
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameWidthForMap + SideLightPanel2Width )
                            .attr("y1", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5))
                            .attr("x2", ix + FrameWidthForMap + SideLightPanel2Width )
                            .attr("y2", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 15)
                  
                }
            }

        }

        /* Frame */


        var IronmongerySet = $('select[name="ironmongerySet"]').val();
        var IronmongeryID = $('select[name="IronmongeryID"]').val();




       if (IsOverPanelActive != "" && IsOverPanelActive != "No") {

            svg.append('rect') // over panel rect (upper part)
                .attr('x', ix -SideLightPanel1Width )
                .attr('y', iy) // Remove FrameThicknessForMap from y
                .attr('width', OverPanelWidth + (2 * FrameThicknessForMap)+(2*GapForMap) + SideLightPanel1Width + (SideLightPanel2 === 'Yes' ? SideLightPanel2Width : 0))
                .attr('height', OverPanelHeight + FrameThicknessForMap) // Reduce height
                .attr('stroke', 'black')
                .attr('fill', '#D0D0C6');

            svg.append('rect') // over panel rect (upper part)
                .attr('x',  ix + FrameThicknessForMap + GapForMap -SideLightPanel1Width )
                .attr('y', iy + (FrameThicknessForMap)) // Remove FrameThicknessForMap from y
                .attr('width', OverPanelWidth + + SideLightPanel1Width + (SideLightPanel2 === 'Yes' ? SideLightPanel2Width : 0) )
                .attr('height', OverPanelHeight - (2 * FrameThicknessForMap) ) // Reduce height
                .attr('stroke', 'black')
                .attr('fill', IsOverPanelActive === 'Fan_Light' ? '#CDD8DD' : (IsOverPanelActive === 'Overpanel' ? '#EBECE6' : ''));


            if (ShowMeasurements) {
                if (OverPanelHeight > 0) {

                        svg.append("text")           
                            .style("fill", "black")      
                            .attr("font-size", 10)
                            .attr("x", ix + ((GapForOverPanel / 5) / 2) + (OverPanelWidth / 2))         
                            .attr("y", iy - 15)        
                            .text(FrameWidth + (( SideLightPanel1Width + (SideLightPanel2 === 'Yes' ? SideLightPanel2Width : 0))*5));   
                       

                        svg.append('line')//measurement line of Width of over panel of door
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix - SideLightPanel1Width )
                            .attr("y1", iy - 10)
                            .attr("x2", ix  + OverPanelWidth +  (2 * FrameThicknessForMap)+(2*GapForMap)  + (SideLightPanel2 === 'Yes' ? SideLightPanel2Width : 0))
                            .attr("y2", iy - 10)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")
                        svg.append('line')//measurement line of Width of over panel of door
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix - SideLightPanel1Width)
                            .attr("y1", iy + FrameThicknessForMap - 20)
                            .attr("x2", ix - SideLightPanel1Width)
                            .attr("y2", iy + FrameThicknessForMap)
                        svg.append('line')//measurement line of Width of over panel of door
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + OverPanelWidth + + (2 * FrameThicknessForMap)+(2*GapForMap)  + (SideLightPanel2 === 'Yes' ? SideLightPanel2Width : 0))
                            .attr("y1", iy + FrameThicknessForMap - 20)
                            .attr("x2", ix + OverPanelWidth + + (2 * FrameThicknessForMap)+(2*GapForMap) + (SideLightPanel2 === 'Yes' ? SideLightPanel2Width : 0))
                            .attr("y2", iy + FrameThicknessForMap)

                       svg.append("text")           //Text of height of over panel of door
                            .style("fill", "black")      // make the text black
                            .attr("font-size", 10)
                            .attr("x", ix - SideLightPanel1Width -35)         // set x position of left side of text
                            .attr("y", iy + FrameThicknessForMap + (OverPanelHeight / 2))         // set y position of bottom of text
                             .attr("transform", `rotate(-90, ${ix - SideLightPanel1Width - 35}, ${iy + FrameThicknessForMap + (OverPanelHeight / 2)})`) // rotate 45° around (x, y)
                            .text((typeof OverPanelHeightToShow !== 'undefined' ? OverPanelHeightToShow : 0) - (2*OpFrameThickness));   // define the text to display
                        //Text of Width of Outer frame of door

                        svg.append('line')//measurement line of height of over panel of door
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix - SideLightPanel1Width -30)
                            .attr("y1", iy )
                            .attr("x2", ix - SideLightPanel1Width -30)
                            .attr("y2", iy + OverPanelHeight)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")
                        svg.append('line')//measurement line of height of over panel of door
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix - SideLightPanel1Width -35)
                            .attr("y1", iy )
                            .attr("x2", ix )
                            .attr("y2", iy )
                        svg.append('line')//measurement line of height of over panel of door
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix - SideLightPanel1Width -35)
                            .attr("y1", iy + OverPanelHeight)
                            .attr("x2", ix )
                            .attr("y2", iy + OverPanelHeight)
                   
                }
            }

        }

        if (ShowMeasurements && frameonoff && FrameThickness) {

            svg.append('line')
                .style("stroke", "black")
                .style("stroke-width", 0.5)
                .attr("x1", ix)
                .attr("y1", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 10)
                .attr("x2", ix + FrameThicknessForMap)
                .attr("y2", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 10)
                .attr("marker-start", "url(#verticalMarket)")  // Left-pointing arrow
                .attr("marker-end", "url(#verticalMarket)")

            svg.append("text")            // append text
                .style("fill", "black")      // make the text black
                .attr("font-size", 10)
                .attr("x", ix)         // set x position of left side of text
                .attr("y", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 30)
                .text(FrameThickness);   // define the text to display
        }
        /* Frame */


        /* Hinges */
        // BY DEFAULT HINGES IS IN LEFT SIDE. CHANGE THE CONDITION IF HANDING SELECT RIGHT IT SHOULD BE RIGHT (02-12-2023)
        // IF DOOR OPEN IN AND OUT BOTH SIDE THEN HINGES SHOULD NOT COME (28-12-2023)// NEED TO CHANGE HINGE LOCATION (09-12-2023)

        if ((swingType && swingType == 'DA') || !frameonoff) {
        } else if (DoorSetType == 'SD' && Handing == 'Right') {
            svg.append('rect')
                .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap - 3)
                // .attr('y', iy + TopFrameHeight + GapForMap + 36)
                .attr('y', hngLctn1Y)
                .attr('width', GapForMap)
                .attr('height', 20)
                .attr('stroke', 'black')
                .attr('fill', 'black');
            svg.append('rect')
                .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap - 3)
                // .attr('y',iy + TopFrameHeight + GapForMap + 100)
                .attr('y', hngLctn2Y)
                .attr('width', GapForMap)
                .attr('height', 20)
                .attr('stroke', 'black')
                .attr('fill', 'black')
                .attr('class', 'middleHinges');
            svg.append('rect')
                .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap - 3)
                // .attr('y', iy + TopFrameHeight + (FrameHeightForMap - FrameThicknessForMap) - ( FrameThicknessForMap + GapForMap + 36))
                .attr('y', hngLctn3Y)
                .attr('width', GapForMap)
                .attr('height', 20)
                .attr('stroke', 'black')
                .attr('fill', 'black');

            // hingelinex = ix + SOWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 20;
            hingelinex = ix + SOWidthForMap + 20;
            hingetextx = hingelinex + 1;
        } else {
            //visible hinges with gap
            svg.append('rect')
                .attr('x', ix + FrameThicknessForMap)
                .attr('y', hngLctn1Y)
                .attr('width', 3)
                .attr('height', 20)
                .attr('stroke', 'black')
                .attr('fill', 'black');
            console.log("925", GapForMap, hngLctn1Y, ix + FrameThicknessForMap)
            svg.append('rect')
                .attr('x', ix + FrameThicknessForMap)
                // .attr('y', iy + TopFrameHeight + GapForMap + 100)
                .attr('y', hngLctn2Y)
                .attr('width', 3)
                .attr('height', 20)
                .attr('stroke', 'black')
                .attr('fill', 'black')
                .attr('class', 'middleHinges');

            svg.append('rect')
                .attr('x', ix + FrameThicknessForMap)
                .attr('y', hngLctn3Y)
                .attr('width', GapForMap)
                .attr('height', 20)
                .attr('stroke', 'black')
                .attr('fill', 'black');
            // hingelinex = ix - SideLightPanel2WidthSpaceForVerticalLines - 30;
            hingelinex = ix - 30;
            hingetextx = hingelinex - 28;
        }


        // IF DOOR OPEN IN AND OUT BOTH SIDE THEN HINGES SHOULD NOT COME (28-12-2023)
        if (DoorSetType == "DD" && swingType != 'DA') {

            svg.append('rect')
                .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap)
                // .attr('y', iy + TopFrameHeight + GapForMap + 36)
                .attr('y', hngLctn1Y)
                .attr('width', GapForMap)
                .attr('height', 20)
                .attr('stroke', 'black')
                .attr('fill', 'black');

            svg.append('rect')
                .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap)
                // .attr('y', iy + TopFrameHeight + GapForMap + 100)
                .attr('y', hngLctn2Y)
                .attr('width', GapForMap)
                .attr('height', 20)
                .attr('stroke', 'black')
                .attr('fill', 'black')
                .attr('class', 'middleHinges');

            svg.append('rect')
                .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap)
                // .attr('y', iy + TopFrameHeight + (FrameHeightForMap - FrameThicknessForMap) - ( FrameThicknessForMap + GapForMap + 36))
                .attr('y', hngLctn3Y)
                .attr('width', GapForMap)
                .attr('height', 20)
                .attr('stroke', 'black')
                .attr('fill', 'black');

            if (LeafHeightNoOP > 2400) {

                svg.append('rect')
                    .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap)
                    // .attr('y', iy + TopFrameHeight + (FrameHeightForMap - FrameThicknessForMap) - (FrameThicknessForMap + GapForMap + 100))
                    .attr('y', hngLctn4Y)
                    .attr('width', GapForMap)
                    .attr('height', 20)
                    .attr('stroke', 'black')
                    .attr('fill', 'black');
            }
        }


        // ADD HINGE HEIGHT AS A SUGGESTION
        svg.append("circle")
            .style("stroke", "black")
            .style("fill", "black")
            .attr("r", 4)
            .attr("cx", 625)
            .attr("cy", 83);

        svg.append("text")
            .style("fill", "black")
            .style("writing-mode", WritingMode)
            .attr("x", 635)
            .attr("font-size", 12)
            .attr("y", 87)
            .text("102 mm / Hinge");
        // ADD HINGE HEIGHT AS A SUGGESTION END

        /* Outer Frame */
        //Width of Outer frame of door
        //svg.append('rect')
        //    .attr('x', ix)
        //    .attr('y', iy)
        //    .attr('width', SOWidthForMap)
        //    .attr('height', SOHeightForMap)
        //    .attr('stroke', 'black')
        //    .attr('fill', 'none');
        //Width of Outer frame of door


        if (ShowMeasurements) {

            //arrow
            svg.append("svg:defs").append("svg:marker")
                .attr("id", "verticalMarket")
                .attr("refX", 0)
                .attr("refY", 5)
                .attr("markerWidth", 20)
                .attr("markerHeight", 20)
                .attr("markerUnits", "userSpaceOnUse")
                .attr("orient", "auto")
                .append("path")
                .attr("d", "M 0 0  L 0 10")
                .style("stroke-width", 1)
                .style("stroke", "black");
            // Right-pointing arrow marker
            // svg.append("svg:defs").append("svg:marker")
            //     .attr("id", "arrowRight")
            //     .attr("refX", 10)  // Adjusted for longer arrow
            //     .attr("refY", 2)  // Centered vertically for narrow arrow
            //     .attr("markerWidth", 10)  // Increased width for a longer arrow
            //     .attr("markerHeight", 4)  // Kept narrow height
            //     .attr("markerUnits", "userSpaceOnUse")
            //     .attr("orient", "auto")
            //     .append("path")
            //     .attr("d", "M 0 0 L 12 2 L 0 4 Z")  // Made the triangle longer and thinner
            //     .style("fill", "black");

            // svg.append("svg:defs").append("svg:marker")
            //     .attr("id", "arrowLeft")
            //     .attr("refX", 9)  // Position closer to the middle of the arrow to keep it within bounds
            //     .attr("refY", 2)  // Centered vertically
            //     .attr("markerWidth", 10)  // Adjusted width for the arrowhead size
            //     .attr("markerHeight", 4)  // Adjusted height for a thinner arrow
            //     .attr("markerUnits", "userSpaceOnUse")
            //     .attr("orient", "auto-start-reverse")
            //     .append("path")
            //     .attr("d", "M 0 0 L 10 2 L 0 4 Z")  // Adjusted path for a longer, thinner arrowhead
            //     .style("fill", "black");
            // Define the arrow marker for the right-facing arrow
            svg.append("svg:defs").append("svg:marker")
                .attr("id", "arrowRight")
                .attr("refX", 5)  // Position adjusted for smaller arrow size
                .attr("refY", 1.5)  // Centered vertically for smaller arrow
                .attr("markerWidth", 5)  // Decreased width for a smaller arrow
                .attr("markerHeight", 3)  // Decreased height for a smaller arrow
                .attr("markerUnits", "userSpaceOnUse")
                .attr("orient", "auto")
                .append("path")
                .attr("d", "M 0 0 L 6 1.5 L 0 3 Z")  // Adjusted path for a smaller arrowhead
                .style("fill", "black");

            // Define the arrow marker for the left-facing arrow
            svg.append("svg:defs").append("svg:marker")
                .attr("id", "arrowLeft")
                .attr("refX", 4.5)  // Position closer to the middle of the smaller arrow
                .attr("refY", 1.5)  // Centered vertically for smaller arrow
                .attr("markerWidth", 5)  // Adjusted width for smaller arrow size
                .attr("markerHeight", 3)  // Adjusted height for smaller arrow
                .attr("markerUnits", "userSpaceOnUse")
                .attr("orient", "auto-start-reverse")
                .append("path")
                .attr("d", "M 0 0 L 5 1.5 L 0 3 Z")  // Adjusted path for a smaller arrowhead
                .style("fill", "black");





            //Line of width of Outer frame of door
            svg.append('line')
                .style("stroke", "black")
                .style("stroke-width", 0.5)
                .attr("x1", ix)
                .attr("y1", iy - 60)
                .attr("x2", ix + FrameWidthForMap)
                .attr("y2", iy - 60)
                .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                .attr("marker-end", "url(#arrowRight)")
            //Line of width of Outer frame of door


            //Text of Width of Outer frame of door
            svg.append("text")            // append text
                .style("fill", "black")      // make the text black
                .attr("font-size", 10)
                .attr("x", ix + (FrameWidthForMap / 2))         // set x position of left side of text
                .attr("y", iy - 65)         // set y position of bottom of text
                .text(FrameWidth);   // define the text to display
            //Text of Width of Outer frame of door

            svg.append('line') //measurement line from top right
                .style("stroke", "black")
                .style("stroke-width", 0.5)
                .attr("x1", ix + FrameWidthForMap)
                .attr("y1", iy)
                .attr("x2", ix + FrameWidthForMap)
                .attr("y2", iy - 65)

            svg.append('line') // measurement line from top left
                .style("stroke", "black")
                .style("stroke-width", 0.5)
                .attr("x1", ix)
                .attr("y1", iy - 65)
                .attr("x2", ix)
                .attr("y2", iy + TopFrameHeight + GapForMap)


            if (frameonoff) {

                let additionalHeight = IsFourSidedFrame
                    ? TopFrameHeight + GapForMap
                    : 0
                svg.append('line') //line showing height of door
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    //.attr("x1", ix + SOWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 47)
                    .attr("x1", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 92 + 10)
                    .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap))
                    //.attr("x2", ix + SOWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 47)
                    .attr("x2", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 92 + 10)
                    //.attr("y2", iy + SOHeightForMap)
                    .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + additionalHeight)
                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                    .attr("marker-end", "url(#arrowRight)")

                svg.append("text")
                    .style("fill", "black")
                    .style("writing-mode", WritingMode)
                    .attr("x", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 93)
                    .attr("font-size", 10)
                    .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + (FrameHeightForMap / 2) + 10)
                    .attr("transform", `rotate(-90, ${ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 88}, ${iy + (TopFrameHeight - FrameThicknessForMap) + (FrameHeightForMap / 2)})`)
                    .text(FrameHeight);


                svg.append('line') // outer frame joining line top
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 92 + 15)
                    .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap))
                    .attr("x2", ix + FrameWidthForMap)
                    .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap))

                svg.append('line') // outer frame joining line bottom
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines)
                    .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + additionalHeight)
                    .attr("x2", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 92 + 15)
                    .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + additionalHeight)

                // svg.append('rect')
                //         .attr('x', ix + FrameThicknessForMap + GapForMap)
                //         .attr('y', iy + TopFrameHeight + GapForMap)
                //         .attr('width', LeafWidth1ForMap)
                //         .attr('height', LeafHeightNoOPForMap)
                //         .attr('stroke', 'black')
                //         .attr('fill', '#EBECE6');

            }

            //arrow
            svg.append("svg:defs").append("svg:marker")
                .attr("id", "arrowHead")
                .attr("refX", 10)  // Position the arrowhead at the end of the line
                .attr("refY", 5)
                .attr("markerWidth", 10)
                .attr("markerHeight", 10)
                .attr("markerUnits", "userSpaceOnUse")
                .attr("orient", "auto")
                .append("path")
                .attr("d", "M 0 0 L 10 5 L 0 10 Z")  // Defines a triangle arrowhead
                .style("fill", "black");


        }

        /* Outer Frame */


        /* Inner Frame */

        // Top Frame
        svg.append('rect')
            .attr('x', ix)
            .attr('y', iy + (OverPanelHeight > 0 ? OverPanelHeight : 0))
            .attr('width', FrameWidthForMap)
            .attr('height', FrameThicknessForMap)
            .attr('stroke', 'black')
            .attr('fill', '#D0D0C6');

        if (IsFourSidedFrame == true) {
            // Bottom Frame
            svg.append('rect')
                .attr('x', ix)
                .attr('y', iy + TopFrameHeight + LeafHeightNoOPForMap + GapForMap)
                .attr('width', FrameWidthForMap)
                .attr('height', FrameThicknessForMap)
                .attr('stroke', 'black')
                .attr('fill', '#D0D0C6');

            // Left Frame (Polygon without top and bottom borders)
            svg.append('polygon')
                .attr('points', `
    ${ix},${iy + TopFrameHeight}
    ${ix + FrameThicknessForMap},${iy + TopFrameHeight}
    ${ix + FrameThicknessForMap},${iy + TopFrameHeight + LeafHeightNoOPForMap + GapForMap}
    ${ix},${iy + TopFrameHeight + LeafHeightNoOPForMap + GapForMap}
  `)
                .attr('fill', '#D0D0C6');

            // Right Frame (Polygon without top and bottom borders)
            svg.append('polygon')
                .attr('points', `
    ${ix + FrameWidthForMap - FrameThicknessForMap},${iy + TopFrameHeight}
    ${ix + FrameWidthForMap},${iy + TopFrameHeight}
    ${ix + FrameWidthForMap},${iy + TopFrameHeight + LeafHeightNoOPForMap + GapForMap}
    ${ix + FrameWidthForMap - FrameThicknessForMap},${iy + TopFrameHeight + LeafHeightNoOPForMap + GapForMap}
  `)
                .attr('fill', '#D0D0C6');

            // Left Border (Line)
            svg.append('line')
                .attr('x1', ix)
                .attr('y1', iy + TopFrameHeight)
                .attr('x2', ix)
                .attr('y2', iy + TopFrameHeight + LeafHeightNoOPForMap + GapForMap)
                .attr('stroke', 'black')
                .attr('stroke-width', 1);

            // Right Border (Line)
            svg.append('line')
                .attr('x1', ix + FrameWidthForMap)
                .attr('y1', iy + TopFrameHeight)
                .attr('x2', ix + FrameWidthForMap)
                .attr('y2', iy + TopFrameHeight + LeafHeightNoOPForMap + GapForMap)
                .attr('stroke', 'black')
                .attr('stroke-width', 1);
        }
        else {
            // Left Frame (Polygon without top and bottom borders)
            svg.append('polygon')
                .attr('points', `
  ${ix},${iy + TopFrameHeight}
  ${ix + FrameThicknessForMap},${iy + TopFrameHeight}
  ${ix + FrameThicknessForMap},${iy + TopFrameHeight + LeafHeightNoOPForMap + GapForMap}
  ${ix},${iy + TopFrameHeight + LeafHeightNoOPForMap + GapForMap}
`)
                .attr('fill', '#D0D0C6');

            // Right Frame (Polygon without top and bottom borders)
            svg.append('polygon')
                .attr('points', `
  ${ix + FrameWidthForMap - FrameThicknessForMap},${iy + TopFrameHeight}
  ${ix + FrameWidthForMap},${iy + TopFrameHeight}
  ${ix + FrameWidthForMap},${iy + TopFrameHeight + LeafHeightNoOPForMap + GapForMap}
  ${ix + FrameWidthForMap - FrameThicknessForMap},${iy + TopFrameHeight + LeafHeightNoOPForMap + GapForMap}
`)
                .attr('fill', '#D0D0C6');

            // Left Border (Line)
            svg.append('line')
                .attr('x1', ix)
                .attr('y1', iy + TopFrameHeight)
                .attr('x2', ix)
                .attr('y2', iy + TopFrameHeight + LeafHeightNoOPForMap + GapForMap)
                .attr('stroke', 'black')
                .attr('stroke-width', 1);

            // Right Border (Line)
            svg.append('line')
                .attr('x1', ix + FrameWidthForMap)
                .attr('y1', iy + TopFrameHeight)
                .attr('x2', ix + FrameWidthForMap)
                .attr('y2', iy + TopFrameHeight + LeafHeightNoOPForMap + GapForMap)
                .attr('stroke', 'black')
                .attr('stroke-width', 1);

            // Bottom Line for Left Frame
            svg.append('line')
                .attr('x1', ix)
                .attr('y1', iy + TopFrameHeight + LeafHeightNoOPForMap + GapForMap)
                .attr('x2', ix + FrameThicknessForMap)
                .attr('y2', iy + TopFrameHeight + LeafHeightNoOPForMap + GapForMap)
                .attr('stroke', 'black')
                .attr('stroke-width', 1);

            // Bottom Line for Right Frame
            svg.append('line')
                .attr('x1', ix + FrameWidthForMap - FrameThicknessForMap)
                .attr('y1', iy + TopFrameHeight + LeafHeightNoOPForMap + GapForMap)
                .attr('x2', ix + FrameWidthForMap)
                .attr('y2', iy + TopFrameHeight + LeafHeightNoOPForMap + GapForMap)
                .attr('stroke', 'black')
                .attr('stroke-width', 1);

        }



        // Inner Frame (optional)
        svg.append('rect')
            .attr('x', ix + FrameThicknessForMap + GapForMap)
            .attr('y', iy + TopFrameHeight + GapForMap)
            .attr('width', LeafWidth1ForMap)
            .attr('height', LeafHeightNoOPForMap)
            .attr('stroke', 'black')
            .attr('fill', '#EBECE6');


        /* Hinges */

        if (Handing == 'Right') {
            // Adjust for hinges on the right side
            const rightHingeOffset = 2; // adjust this as needed to control the distance from the edge

            // HINGES LINE
            if (swingType != 'DA' && frameonoff) {

                if ((SideLightPanel2Width > 0)) {
                    // Hinge 1 line
                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex -50)
                        .attr("x2", hingelinex -50)
                        .attr("y1", iy + TopFrameHeight + GapForMap)
                        .attr("y2", hngLctn1Y)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line') // hinge 1 top
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex + rightHingeOffset + 5-60)
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                        .attr("y1", iy + TopFrameHeight + GapForMap)
                        .attr("y2", iy + TopFrameHeight + GapForMap)

                    svg.append('line')//hing 1 bottom
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                        .attr("x2", hingelinex + rightHingeOffset + 5 -60)
                        .attr("y1", hngLctn1Y)
                        .attr("y2", hngLctn1Y)

                    svg.append('line') //measurements hing 1 green
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex-50)
                        .attr("x2", hingelinex-50)
                        .attr("y1", hngLctn1Y + 20)
                        .attr("y2", hngLctn1Y)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    // Hinge 1 text
                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", hingetextx-48)
                        .attr("font-size", 10)
                        .attr("y", iy + TopFrameHeight + GapForMap + ((hngLctn1Y - (iy + TopFrameHeight + GapForMap)) / 2) + 5)
                        .text(parseFloat(hinge1hieght.toFixed(2)));

                    // Hinge 1 measurement text
                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", hingetextx-48)
                        .attr("font-size", 10)
                        .attr("y", hngLctn1Y + 20 + ((hngLctn1Y - (hngLctn1Y + 20)) / 2) + 5)
                        .text(102);

                    // Hinge 2 line
                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex-50)
                        .attr("x2", hingelinex-50)
                        .attr("y1", (hngLctn1Y + 20))
                        .attr("y2", hngLctn2Y)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line') // Hinge 2 line top
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex + rightHingeOffset + 5-60)
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                        .attr("y1", (hngLctn1Y + 20))
                        .attr("y2", hngLctn1Y + 20)

                    svg.append('line') //hing 2 line bottom
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                        .attr("x2", hingelinex + rightHingeOffset + 5-60)
                        .attr("y1", (hngLctn2Y))
                        .attr("y2", hngLctn2Y)

                    // Hinge 2 text
                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", hingetextx-48)
                        .attr("font-size", 10)
                        .attr("y", hngLctn1Y + 20 + ((hngLctn2Y - (hngLctn1Y + 20)) / 2) + 5)
                        .text(parseFloat(hinge2hieght.toFixed(1)));

                    svg.append('line') // measurement line 2
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex-50)
                        .attr("x2", hingelinex-50)
                        .attr("y1", (hngLctn2Y + 20))
                        .attr("y2", hngLctn2Y)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    // Hinge 2 text measurement
                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", hingetextx-48)
                        .attr("font-size", 10)
                        .attr("y", (hngLctn2Y + 20) + ((hngLctn2Y - (hngLctn2Y + 20)) / 2) + 5)
                        .text(102);

                    // 3 LINE SHOULD BE BREAK WHEN THERE IS 4 HINGES
                    if (LeafHeightNoOP > 2400) {
                        // Hinge 3 first line
                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", hingelinex-50)
                            .attr("x2", hingelinex-50)
                            .attr("y1", (hngLctn2Y + 20))
                            .attr("y2", hngLctn4Y)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append('line') //measurement line 3
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", hingelinex-50)
                            .attr("x2", hingelinex-50)
                            .attr("y1", (hngLctn4Y + 20))
                            .attr("y2", hngLctn4Y)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")
                        // Hinge 3 first text measurement
                        svg.append("text")
                            .style("fill", "black")
                            .style("writing-mode", WritingMode)
                            .attr("x", hingetextx-48)
                            .attr("font-size", 10)
                            .attr("y", (hngLctn4Y + 20) + ((hngLctn4Y - (hngLctn4Y + 20)) / 2) + 5)
                            .text(102);

                        svg.append('line') // hinge 3 first top
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", hingelinex + rightHingeOffset + 5-60)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                            .attr("y1", (hngLctn2Y + 20))
                            .attr("y2", hngLctn2Y + 20)

                        svg.append('line') // hinge 3 first bottom
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                            .attr("x2", hingelinex + rightHingeOffset + 5-60)
                            .attr("y1", hngLctn4Y)
                            .attr("y2", hngLctn4Y)

                        // Hinge 3 first text
                        svg.append("text")
                            .style("fill", "black")
                            .style("writing-mode", WritingMode)
                            .attr("x", hingetextx-48)
                            .attr("font-size", 10)
                            .attr("y", hngLctn2Y + 20 + ((hngLctn4Y - (hngLctn2Y + 20)) / 2) + 5)
                            .text(parseFloat((hinge4SCenter).toFixed(1)));

                        // Hinge 3 2nd line
                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", hingelinex-50)
                            .attr("x2", hingelinex-50)
                            .attr("y1", (hngLctn4Y + 20))
                            .attr("y2", hngLctn3Y)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append('line')//measurement line hing
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", hingelinex-50)
                            .attr("x2", hingelinex-50)
                            .attr("y1", (hngLctn3Y + 20))
                            .attr("y2", hngLctn3Y)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        // Hinge 3 2nd text measurement
                        svg.append("text")
                            .style("fill", "black")
                            .style("writing-mode", WritingMode)
                            .attr("x", hingetextx-48)
                            .attr("font-size", 10)
                            .attr("y", (hngLctn3Y + 20) + ((hngLctn3Y - (hngLctn3Y + 20)) / 2) + 5)
                            .text(102);

                        svg.append('line') //hing to line top
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", hingelinex + rightHingeOffset + 5-60)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                            .attr("y1", (hngLctn4Y + 20))
                            .attr("y2", hngLctn4Y + 20)

                        svg.append('line') // hing bottom line
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                            .attr("x2", hingelinex + rightHingeOffset + 5-60)
                            .attr("y1", hngLctn3Y)
                            .attr("y2", hngLctn3Y)


                        // Hinge 3 2nd text
                        svg.append("text")
                            .style("fill", "black")
                            .style("writing-mode", WritingMode)
                            .attr("x", hingetextx-48)
                            .attr("font-size", 10)
                            .attr("y", hngLctn4Y + 20 + ((hngLctn3Y - (hngLctn4Y + 20)) / 2) + 5)
                            .text(parseFloat(hinge4hieght.toFixed(1)));

                    } else {

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", hingelinex-50)
                            .attr("x2", hingelinex-50)
                            .attr("y1", (hngLctn2Y + 20))
                            .attr("y2", hngLctn3Y)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        // Hinge 3 text
                        svg.append("text")
                            .style("fill", "black")
                            .style("writing-mode", WritingMode)
                            .attr("x", hingetextx-48)
                            .attr("font-size", 10)
                            .attr("y", hngLctn2Y + 20 + ((hngLctn3Y - (hngLctn2Y + 20)) / 2) + 5)
                            .text(parseFloat((hinge4FCenter).toFixed(1)));

                        svg.append('line')//measurement line hing
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", hingelinex-50)
                            .attr("x2", hingelinex-50)
                            .attr("y1", (hngLctn3Y + 20))
                            .attr("y2", hngLctn3Y)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        // Hinge 3 2nd text measurement
                        svg.append("text")
                            .style("fill", "black")
                            .style("writing-mode", WritingMode)
                            .attr("x", hingetextx-48)
                            .attr("font-size", 10)
                            .attr("y", (hngLctn3Y + 20) + ((hngLctn3Y - (hngLctn3Y + 20)) / 2) + 5)
                            .text(102);

                        svg.append('line') // hinge 3 first top
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", hingelinex + rightHingeOffset + 5-60)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                            .attr("y1", (hngLctn2Y + 20))
                            .attr("y2", hngLctn2Y + 20)


                        svg.append('line') // hing bottom line
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                            .attr("x2", hingelinex + rightHingeOffset + 5-60)
                            .attr("y1", hngLctn3Y)
                            .attr("y2", hngLctn3Y)
                    }

                       if (IsFourSidedFrame == true) {
 // Hinge 4 line
                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex-50)
                        .attr("x2", hingelinex-50)
                        .attr("y1", (hngLctn3Y + 20))
                        .attr("y2", iy + (FrameHeight / 5)+(OverPanelHeight ?? 0)- (FrameThickness/5))
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                        svg.append('line') // hinge 4 joining line bottom
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                        .attr("x2", hingelinex + rightHingeOffset + 5-60)
                        .attr("y1", iy + (FrameHeight / 5)+(OverPanelHeight ?? 0)- (FrameThickness/5))
                        .attr("y2", iy + (FrameHeight / 5)+(OverPanelHeight ?? 0)- (FrameThickness/5))
                       }else{
 // Hinge 4 line
                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex-50)
                        .attr("x2", hingelinex-50)
                        .attr("y1", (hngLctn3Y + 20))
                        .attr("y2", iy + (FrameHeight / 5)+(OverPanelHeight ?? 0))
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                        svg.append('line') // hinge 4 joining line bottom
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                        .attr("x2", hingelinex + rightHingeOffset + 5-60)
                        .attr("y1", iy + (FrameHeight / 5)+(OverPanelHeight ?? 0))
                        .attr("y2", iy + (FrameHeight / 5)+(OverPanelHeight ?? 0))
                       }

                   

                    svg.append('line') // hinge 4 joining line top
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex + rightHingeOffset + 5-60)
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                        .attr("y1", (hngLctn3Y + 20))
                        .attr("y2", hngLctn3Y + 20)

                    

                    // Hinge 4 text
                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", hingetextx-48)
                        .attr("font-size", 10)
                        .attr("y", ((hngLctn3Y + 20)+iy + ((FrameHeight) / 5)+ (OverPanelHeight ?? 0))/2)
                        .text(parseFloat(hinge3hieght.toFixed(1)));

                } else {
                    // Hinge 1 line
                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex)
                        .attr("x2", hingelinex)
                        .attr("y1", iy + TopFrameHeight + GapForMap)
                        .attr("y2", hngLctn1Y)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line') // hinge 1 top
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex + rightHingeOffset + 5)
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                        .attr("y1", iy + TopFrameHeight + GapForMap)
                        .attr("y2", iy + TopFrameHeight + GapForMap)

                    svg.append('line')//hing 1 bottom
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                        .attr("x2", hingelinex + rightHingeOffset + 5)
                        .attr("y1", hngLctn1Y)
                        .attr("y2", hngLctn1Y)

                    svg.append('line') //measurements hing 1 green
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex)
                        .attr("x2", hingelinex)
                        .attr("y1", hngLctn1Y + 20)
                        .attr("y2", hngLctn1Y)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    // Hinge 1 text
                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", hingetextx)
                        .attr("font-size", 10)
                        .attr("y", iy + TopFrameHeight + GapForMap + ((hngLctn1Y - (iy + TopFrameHeight + GapForMap)) / 2) + 5)
                        .text(parseFloat(hinge1hieght.toFixed(2)));

                    // Hinge 1 measurement text
                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", hingetextx)
                        .attr("font-size", 10)
                        .attr("y", hngLctn1Y + 20 + ((hngLctn1Y - (hngLctn1Y + 20)) / 2) + 5)
                        .text(102);

                    // Hinge 2 line
                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex)
                        .attr("x2", hingelinex)
                        .attr("y1", (hngLctn1Y + 20))
                        .attr("y2", hngLctn2Y)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line') // Hinge 2 line top
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex + rightHingeOffset + 5)
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                        .attr("y1", (hngLctn1Y + 20))
                        .attr("y2", hngLctn1Y + 20)

                    svg.append('line') //hing 2 line bottom
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                        .attr("x2", hingelinex + rightHingeOffset + 5)
                        .attr("y1", (hngLctn2Y))
                        .attr("y2", hngLctn2Y)

                    // Hinge 2 text
                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", hingetextx)
                        .attr("font-size", 10)
                        .attr("y", hngLctn1Y + 20 + ((hngLctn2Y - (hngLctn1Y + 20)) / 2) + 5)
                        .text(parseFloat(hinge2hieght.toFixed(1)));

                    svg.append('line') // measurement line 2
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex)
                        .attr("x2", hingelinex)
                        .attr("y1", (hngLctn2Y + 20))
                        .attr("y2", hngLctn2Y)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    // Hinge 2 text measurement
                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", hingetextx)
                        .attr("font-size", 10)
                        .attr("y", (hngLctn2Y + 20) + ((hngLctn2Y - (hngLctn2Y + 20)) / 2) + 5)
                        .text(102);

                    // 3 LINE SHOULD BE BREAK WHEN THERE IS 4 HINGES
                    if (LeafHeightNoOP > 2400) {
                        // Hinge 3 first line
                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", hingelinex)
                            .attr("x2", hingelinex)
                            .attr("y1", (hngLctn2Y + 20))
                            .attr("y2", hngLctn4Y)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append('line') //measurement line 3
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", hingelinex)
                            .attr("x2", hingelinex)
                            .attr("y1", (hngLctn4Y + 20))
                            .attr("y2", hngLctn4Y)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")
                        // Hinge 3 first text measurement
                        svg.append("text")
                            .style("fill", "black")
                            .style("writing-mode", WritingMode)
                            .attr("x", hingetextx)
                            .attr("font-size", 10)
                            .attr("y", (hngLctn4Y + 20) + ((hngLctn4Y - (hngLctn4Y + 20)) / 2) + 5)
                            .text(102);

                        svg.append('line') // hinge 3 first top
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", hingelinex + rightHingeOffset + 5)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                            .attr("y1", (hngLctn2Y + 20))
                            .attr("y2", hngLctn2Y + 20)

                        svg.append('line') // hinge 3 first bottom
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                            .attr("x2", hingelinex + rightHingeOffset + 5)
                            .attr("y1", hngLctn4Y)
                            .attr("y2", hngLctn4Y)

                        // Hinge 3 first text
                        svg.append("text")
                            .style("fill", "black")
                            .style("writing-mode", WritingMode)
                            .attr("x", hingetextx)
                            .attr("font-size", 10)
                            .attr("y", hngLctn2Y + 20 + ((hngLctn4Y - (hngLctn2Y + 20)) / 2) + 5)
                            .text(parseFloat((hinge4SCenter).toFixed(1)));

                        // Hinge 3 2nd line
                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", hingelinex)
                            .attr("x2", hingelinex)
                            .attr("y1", (hngLctn4Y + 20))
                            .attr("y2", hngLctn3Y)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append('line')//measurement line hing
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", hingelinex)
                            .attr("x2", hingelinex)
                            .attr("y1", (hngLctn3Y + 20))
                            .attr("y2", hngLctn3Y)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        // Hinge 3 2nd text measurement
                        svg.append("text")
                            .style("fill", "black")
                            .style("writing-mode", WritingMode)
                            .attr("x", hingetextx)
                            .attr("font-size", 10)
                            .attr("y", (hngLctn3Y + 20) + ((hngLctn3Y - (hngLctn3Y + 20)) / 2) + 5)
                            .text(102);

                        svg.append('line') //hing to line top
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", hingelinex + rightHingeOffset + 5)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                            .attr("y1", (hngLctn4Y + 20))
                            .attr("y2", hngLctn4Y + 20)

                        svg.append('line') // hing bottom line
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                            .attr("x2", hingelinex + rightHingeOffset + 5)
                            .attr("y1", hngLctn3Y)
                            .attr("y2", hngLctn3Y)


                        // Hinge 3 2nd text
                        svg.append("text")
                            .style("fill", "black")
                            .style("writing-mode", WritingMode)
                            .attr("x", hingetextx)
                            .attr("font-size", 10)
                            .attr("y", hngLctn4Y + 20 + ((hngLctn3Y - (hngLctn4Y + 20)) / 2) + 5)
                            .text(parseFloat(hinge4hieght.toFixed(1)));

                    } else {

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", hingelinex)
                            .attr("x2", hingelinex)
                            .attr("y1", (hngLctn2Y + 20))
                            .attr("y2", hngLctn3Y)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        // Hinge 3 text
                        svg.append("text")
                            .style("fill", "black")
                            .style("writing-mode", WritingMode)
                            .attr("x", hingetextx)
                            .attr("font-size", 10)
                            .attr("y", hngLctn2Y + 20 + ((hngLctn3Y - (hngLctn2Y + 20)) / 2) + 5)
                            .text(parseFloat((hinge4FCenter).toFixed(1)));

                        svg.append('line')//measurement line hing
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", hingelinex)
                            .attr("x2", hingelinex)
                            .attr("y1", (hngLctn3Y + 20))
                            .attr("y2", hngLctn3Y)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        // Hinge 3 2nd text measurement
                        svg.append("text")
                            .style("fill", "black")
                            .style("writing-mode", WritingMode)
                            .attr("x", hingetextx)
                            .attr("font-size", 10)
                            .attr("y", (hngLctn3Y + 20) + ((hngLctn3Y - (hngLctn3Y + 20)) / 2) + 5)
                            .text(102);

                        svg.append('line') // hinge 3 first top
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", hingelinex + rightHingeOffset + 5)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                            .attr("y1", (hngLctn2Y + 20))
                            .attr("y2", hngLctn2Y + 20)


                        svg.append('line') // hing bottom line
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                            .attr("x2", hingelinex + rightHingeOffset + 5)
                            .attr("y1", hngLctn3Y)
                            .attr("y2", hngLctn3Y)
                    }

   if (IsFourSidedFrame == true) {
       svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex)
                        .attr("x2", hingelinex)
                        .attr("y1", (hngLctn3Y + 20))
                        .attr("y2", iy + (FrameHeight / 5)+(OverPanelHeight ?? 0)- (FrameThickness/5))
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")


                          svg.append('line') // hinge 4 joining line bottom
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                        .attr("x2", hingelinex + rightHingeOffset + 5)
                        .attr("y1", iy + (FrameHeight / 5)+(OverPanelHeight ?? 0)- (FrameThickness/5))
                        .attr("y2", iy + (FrameHeight / 5)+(OverPanelHeight ?? 0)- (FrameThickness/5))
   }else{
  // Hinge 4 line
                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex)
                        .attr("x2", hingelinex)
                        .attr("y1", (hngLctn3Y + 20))
                        .attr("y2", iy + (FrameHeight / 5)+(OverPanelHeight ?? 0))
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")


                          svg.append('line') // hinge 4 joining line bottom
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                        .attr("x2", hingelinex + rightHingeOffset + 5)
                        .attr("y1", iy + (FrameHeight / 5)+(OverPanelHeight ?? 0))
                        .attr("y2", iy + (FrameHeight / 5)+(OverPanelHeight ?? 0))
   }

                  
                    svg.append('line') // hinge 4 joining line top
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex + rightHingeOffset + 5)
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                        .attr("y1", (hngLctn3Y + 20))
                        .attr("y2", hngLctn3Y + 20)

                  

                    // Hinge 4 text
                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", hingetextx)
                        .attr("font-size", 10)
                        .attr("y", ((hngLctn3Y + 20)+iy + ((FrameHeight) / 5)+ (OverPanelHeight ?? 0))/2)
                        .text(parseFloat(hinge3hieght.toFixed(1)));
                }


            }

        }
        else if (swingType != 'DA' && frameonoff) {

            if ((SideLightPanel1Width > 0)) {
                // Hinge 1 line
                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", hingelinex + 60)
                    .attr("x2", hingelinex + 60)
                    .attr("y1", iy + TopFrameHeight + GapForMap)
                    .attr("y2", hngLctn1Y)
                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                    .attr("marker-end", "url(#arrowRight)")

                svg.append('line') // hinge 1 top
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", hingelinex - 5 + 70)
                    .attr("x2", ix + FrameThicknessForMap - 1)
                    .attr("y1", iy + TopFrameHeight + GapForMap)
                    .attr("y2", iy + TopFrameHeight + GapForMap)

                svg.append('line')//hing 1 bottom
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + FrameThicknessForMap - 1)
                    .attr("x2", hingelinex - 5 + 70)
                    .attr("y1", hngLctn1Y)
                    .attr("y2", hngLctn1Y)

                svg.append('line') //measurements hing 1 green
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", hingelinex + 60)
                    .attr("x2", hingelinex + 60)
                    .attr("y1", hngLctn1Y + 20)
                    .attr("y2", hngLctn1Y)
                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                    .attr("marker-end", "url(#arrowRight)")

                // Hinge 1 text
                svg.append("text")
                    .style("fill", "black")
                    .style("writing-mode", WritingMode)
                    .attr("x", hingetextx + 70)
                    .attr("font-size", 10)
                    .attr("y", iy + TopFrameHeight + GapForMap + ((hngLctn1Y - (iy + TopFrameHeight + GapForMap)) / 2) + 5)
                    .text(parseFloat(hinge1hieght.toFixed(2)));

                // Hinge 1 measurement text
                svg.append("text")
                    .style("fill", "black")
                    .style("writing-mode", WritingMode)
                    .attr("x", hingetextx + 70)
                    .attr("font-size", 10)
                    .attr("y", hngLctn1Y + 20 + ((hngLctn1Y - (hngLctn1Y + 20)) / 2) + 5)
                    .text(102);

                // Hinge 2 line
                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", hingelinex + 60)
                    .attr("x2", hingelinex + 60)
                    .attr("y1", (hngLctn1Y + 20))
                    .attr("y2", hngLctn2Y)
                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                    .attr("marker-end", "url(#arrowRight)")

                svg.append('line') // Hinge 2 line top
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", hingelinex - 5 + 70)
                    .attr("x2", ix + FrameThicknessForMap - 1)
                    .attr("y1", (hngLctn1Y + 20))
                    .attr("y2", hngLctn1Y + 20)

                svg.append('line') //hing 2 line bottom
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + FrameThicknessForMap - 1)
                    .attr("x2", hingelinex - 5 + 70)
                    .attr("y1", (hngLctn2Y))
                    .attr("y2", hngLctn2Y)

                // Hinge 2 text
                svg.append("text")
                    .style("fill", "black")
                    .style("writing-mode", WritingMode)
                    .attr("x", hingetextx + 70)
                    .attr("font-size", 10)
                    .attr("y", hngLctn1Y + 20 + ((hngLctn2Y - (hngLctn1Y + 20)) / 2) + 5)
                    .text(parseFloat(hinge2hieght.toFixed(1)));

                svg.append('line') // measurement line 2
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", hingelinex + 60)
                    .attr("x2", hingelinex + 60)
                    .attr("y1", (hngLctn2Y + 20))
                    .attr("y2", hngLctn2Y)
                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                    .attr("marker-end", "url(#arrowRight)")

                // Hinge 2 text measurement
                svg.append("text")
                    .style("fill", "black")
                    .style("writing-mode", WritingMode)
                    .attr("x", hingetextx + 70)
                    .attr("font-size", 10)
                    .attr("y", (hngLctn2Y + 20) + ((hngLctn2Y - (hngLctn2Y + 20)) / 2) + 5)
                    .text(102);

                // 3 LINE SHOULD BE BREAK WHEN THERE IS 4 HINGES
                if (LeafHeightNoOP > 2400) {
                    // Hinge 3 first line
                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex + 60)
                        .attr("x2", hingelinex + 60)
                        .attr("y1", (hngLctn2Y + 20))
                        .attr("y2", hngLctn4Y)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line') //measurement line 3
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex + 60)
                        .attr("x2", hingelinex + 60)
                        .attr("y1", (hngLctn4Y + 20))
                        .attr("y2", hngLctn4Y)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")
                    // Hinge 3 first text measurement
                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", hingetextx + 70)
                        .attr("font-size", 10)
                        .attr("y", (hngLctn4Y + 20) + ((hngLctn4Y - (hngLctn4Y + 20)) / 2) + 5)
                        .text(102);

                    svg.append('line') // hinge 3 first top
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex - 5 + 70)
                        .attr("x2", ix + FrameThicknessForMap - 1)
                        .attr("y1", (hngLctn2Y + 20))
                        .attr("y2", hngLctn2Y + 20)

                    svg.append('line') // hinge 3 first bottom
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap - 1)
                        .attr("x2", hingelinex - 5 + 70)
                        .attr("y1", hngLctn4Y)
                        .attr("y2", hngLctn4Y)

                    // Hinge 3 first text
                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", hingetextx + 70)
                        .attr("font-size", 10)
                        .attr("y", hngLctn2Y + 20 + ((hngLctn4Y - (hngLctn2Y + 20)) / 2) + 5)
                        .text(parseFloat((hinge4SCenter).toFixed(1))
                        );

                    // Hinge 3 2nd line
                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex + 60)
                        .attr("x2", hingelinex + 60)
                        .attr("y1", (hngLctn4Y + 20))
                        .attr("y2", hngLctn3Y)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')//measurement line hing
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex + 60)
                        .attr("x2", hingelinex + 60)
                        .attr("y1", (hngLctn3Y + 20))
                        .attr("y2", hngLctn3Y)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    // Hinge 3 2nd text measurement
                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", hingetextx + 70)
                        .attr("font-size", 10)
                        .attr("y", (hngLctn3Y + 20) + ((hngLctn3Y - (hngLctn3Y + 20)) / 2) + 5)
                        .text(102);

                    svg.append('line') //hing to line top
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex - 5 + 70)
                        .attr("x2", ix + FrameThicknessForMap - 1)
                        .attr("y1", (hngLctn4Y + 20))
                        .attr("y2", hngLctn4Y + 20)

                    svg.append('line') // hing bottom line
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap - 1)
                        .attr("x2", hingelinex - 5 + 70)
                        .attr("y1", hngLctn3Y)
                        .attr("y2", hngLctn3Y)


                    // Hinge 3 2nd text
                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", hingetextx + 70)
                        .attr("font-size", 10)
                        .attr("y", hngLctn4Y + 20 + ((hngLctn3Y - (hngLctn4Y + 20)) / 2) + 5)
                        .text(parseFloat(hinge4hieght.toFixed(1)));

                } else {

                    svg.append('line') // hing bottom line
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap - 1)
                        .attr("x2", hingelinex - 5 + 70)
                        .attr("y1", hngLctn3Y)
                        .attr("y2", hngLctn3Y)

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex + 60)
                        .attr("x2", hingelinex + 60)
                        .attr("y1", (hngLctn2Y + 20))
                        .attr("y2", hngLctn3Y)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    // Hinge 3 text
                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", hingetextx + 70)
                        .attr("font-size", 10)
                        .attr("y", hngLctn2Y + 20 + ((hngLctn3Y - (hngLctn2Y + 20)) / 2) + 5)
                        .text(parseFloat((hinge4FCenter).toFixed(1)));

                    svg.append('line')//measurement line hing
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex + 60)
                        .attr("x2", hingelinex + 60)
                        .attr("y1", (hngLctn3Y + 20))
                        .attr("y2", hngLctn3Y)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    // Hinge 3 2nd text measurement
                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", hingetextx + 70)
                        .attr("font-size", 10)
                        .attr("y", (hngLctn3Y + 20) + ((hngLctn3Y - (hngLctn3Y + 20)) / 2) + 5)
                        .text(102);

                    svg.append('line') // hinge 3 first top
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex - 5 + 70)
                        .attr("x2", ix + FrameThicknessForMap - 1)
                        .attr("y1", (hngLctn2Y + 20))
                        .attr("y2", hngLctn2Y + 20)
                }

                   if (IsFourSidedFrame == true) {
 // Hinge 4 line
                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", hingelinex + 60)
                    .attr("x2", hingelinex + 60)
                    .attr("y1", (hngLctn3Y + 20))
                    .attr("y2", iy + ((FrameHeight) / 5)+(OverPanelHeight ?? 0)- (FrameThickness/5))
                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                    .attr("marker-end", "url(#arrowRight)")

                    svg.append('line') // hinge 4 joining line bottom
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + FrameThicknessForMap - 1)
                    .attr("x2", hingelinex - 5 + 70)
                    .attr("y1", iy + ((FrameHeight) / 5)+ (OverPanelHeight ?? 0)- (FrameThickness/5))
                    .attr("y2", iy + ((FrameHeight) / 5)+ (OverPanelHeight ?? 0)- (FrameThickness/5))
                   }else{
 // Hinge 4 line
                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", hingelinex + 60)
                    .attr("x2", hingelinex + 60)
                    .attr("y1", (hngLctn3Y + 20))
                    .attr("y2", iy + ((FrameHeight) / 5)+(OverPanelHeight ?? 0))
                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                    .attr("marker-end", "url(#arrowRight)")

                    svg.append('line') // hinge 4 joining line bottom
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + FrameThicknessForMap - 1)
                    .attr("x2", hingelinex - 5 + 70)
                    .attr("y1", iy + ((FrameHeight) / 5)+ (OverPanelHeight ?? 0))
                    .attr("y2", iy + ((FrameHeight) / 5)+ (OverPanelHeight ?? 0))
                   }

               

                svg.append('line') // hinge 4 joining line top
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", hingelinex - 5 + 70)
                    .attr("x2", ix + FrameThicknessForMap - 1)
                    .attr("y1", (hngLctn3Y + 20))
                    .attr("y2", hngLctn3Y + 20)

                

                // Hinge 4 text
                svg.append("text")
                    .style("fill", "black")
                    .style("writing-mode", WritingMode)
                    .attr("x", hingetextx + 70)
                    .attr("font-size", 10)
                    .attr("y",((hngLctn3Y + 20)+iy + ((FrameHeight) / 5)+ (OverPanelHeight ?? 0))/2)
                    .text(parseFloat(hinge3hieght.toFixed(1)));
            } else {

                // Hinge 1 line
                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", hingelinex)
                    .attr("x2", hingelinex)
                    .attr("y1", iy + TopFrameHeight + GapForMap)
                    .attr("y2", hngLctn1Y)
                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                    .attr("marker-end", "url(#arrowRight)")

                // hinge 1 top
                createLine("black", 0.5, hingelinex - 5, iy + TopFrameHeight + GapForMap, ix + FrameThicknessForMap - 1, iy + TopFrameHeight + GapForMap);

                //hing 1 bottom
                createLine("black", 0.5, ix + FrameThicknessForMap - 1, hngLctn1Y, hingelinex - 5, hngLctn1Y);

                svg.append('line') //measurements hing 1 green
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", hingelinex)
                    .attr("x2", hingelinex)
                    .attr("y1", hngLctn1Y + 20)
                    .attr("y2", hngLctn1Y)
                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                    .attr("marker-end", "url(#arrowRight)")

                // Hinge 1 text
                svg.append("text")
                    .style("fill", "black")
                    .style("writing-mode", WritingMode)
                    .attr("x", hingetextx)
                    .attr("font-size", 10)
                    .attr("y", iy + TopFrameHeight + GapForMap + ((hngLctn1Y - (iy + TopFrameHeight + GapForMap)) / 2) + 5)
                    .text(parseFloat(hinge1hieght.toFixed(2)));

                // Hinge 1 measurement text
                svg.append("text")
                    .style("fill", "black")
                    .style("writing-mode", WritingMode)
                    .attr("x", hingetextx)
                    .attr("font-size", 10)
                    .attr("y", hngLctn1Y + 20 + ((hngLctn1Y - (hngLctn1Y + 20)) / 2) + 5)
                    .text(102);

                // Hinge 2 line
                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", hingelinex)
                    .attr("x2", hingelinex)
                    .attr("y1", (hngLctn1Y + 20))
                    .attr("y2", hngLctn2Y)
                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                    .attr("marker-end", "url(#arrowRight)")

                // Hinge 2 line top
                createLine("black", 0.5, hingelinex - 5, hngLctn1Y + 20, ix + FrameThicknessForMap - 1, hngLctn1Y + 20);

                //hing 2 line bottom
                createLine("black", 0.5, ix + FrameThicknessForMap - 1, hngLctn2Y, hingelinex - 5, hngLctn2Y);


                // Hinge 2 text
                svg.append("text")
                    .style("fill", "black")
                    .style("writing-mode", WritingMode)
                    .attr("x", hingetextx)
                    .attr("font-size", 10)
                    .attr("y", hngLctn1Y + 20 + ((hngLctn2Y - (hngLctn1Y + 20)) / 2) + 5)
                    .text(parseFloat(hinge2hieght.toFixed(1)));

                svg.append('line') // measurement line 2
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", hingelinex)
                    .attr("x2", hingelinex)
                    .attr("y1", (hngLctn2Y + 20))
                    .attr("y2", hngLctn2Y)
                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                    .attr("marker-end", "url(#arrowRight)")

                // Hinge 2 text measurement
                svg.append("text")
                    .style("fill", "black")
                    .style("writing-mode", WritingMode)
                    .attr("x", hingetextx)
                    .attr("font-size", 10)
                    .attr("y", (hngLctn2Y + 20) + ((hngLctn2Y - (hngLctn2Y + 20)) / 2) + 5)
                    .text(102);

                // 3 LINE SHOULD BE BREAK WHEN THERE IS 4 HINGES
                if (LeafHeightNoOP > 2400) {
                    // Hinge 3 first line
                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex)
                        .attr("x2", hingelinex)
                        .attr("y1", (hngLctn2Y + 20))
                        .attr("y2", hngLctn4Y)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line') //measurement line 3
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex)
                        .attr("x2", hingelinex)
                        .attr("y1", (hngLctn4Y + 20))
                        .attr("y2", hngLctn4Y)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")
                    // Hinge 3 first text measurement
                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", hingetextx)
                        .attr("font-size", 10)
                        .attr("y", (hngLctn4Y + 20) + ((hngLctn4Y - (hngLctn4Y + 20)) / 2) + 5)
                        .text(102);

                    // hinge 3 first top
                    createLine("black", 0.5, hingelinex - 5, hngLctn2Y + 20, ix + FrameThicknessForMap - 1, hngLctn2Y + 20);

                    // hinge 3 first bottom
                    createLine("black", 0.5, ix + FrameThicknessForMap - 1, hngLctn4Y, hingelinex - 5, hngLctn4Y);


                    // Hinge 3 first text
                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", hingetextx)
                        .attr("font-size", 10)
                        .attr("y", hngLctn2Y + 20 + ((hngLctn4Y - (hngLctn2Y + 20)) / 2) + 5)
                        .text(parseFloat((hinge4SCenter ).toFixed(1))
                        );

                    // Hinge 3 2nd line
                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex)
                        .attr("x2", hingelinex)
                        .attr("y1", (hngLctn4Y + 20))
                        .attr("y2", hngLctn3Y)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')//measurement line hing
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex)
                        .attr("x2", hingelinex)
                        .attr("y1", (hngLctn3Y + 20))
                        .attr("y2", hngLctn3Y)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    // Hinge 3 2nd text measurement
                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", hingetextx)
                        .attr("font-size", 10)
                        .attr("y", (hngLctn3Y + 20) + ((hngLctn3Y - (hngLctn3Y + 20)) / 2) + 5)
                        .text(102);

                    //hing to line top
                    createLine("black", 0.5, hingelinex - 5, hngLctn4Y + 20, ix + FrameThicknessForMap - 1, hngLctn4Y + 20);

                    // hing bottom line
                    createLine("black", 0.5, ix + FrameThicknessForMap - 1, hngLctn3Y, hingelinex - 5, hngLctn3Y);

                    // Hinge 3 2nd text
                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", hingetextx)
                        .attr("font-size", 10)
                        .attr("y", hngLctn4Y + 20 + ((hngLctn3Y - (hngLctn4Y + 20)) / 2) + 5)
                        .text(parseFloat(hinge4hieght.toFixed(1)));

                } else {
                    // hing bottom line
                    createLine("black", 0.5, ix + FrameThicknessForMap - 1, hngLctn3Y, hingelinex - 5, hngLctn3Y);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex)
                        .attr("x2", hingelinex)
                        .attr("y1", (hngLctn2Y + 20))
                        .attr("y2", hngLctn3Y)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    // Hinge 3 text
                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", hingetextx)
                        .attr("font-size", 10)
                        .attr("y", hngLctn2Y + 20 + ((hngLctn3Y - (hngLctn2Y + 20)) / 2) + 5)
                        .text(parseFloat((hinge4FCenter ).toFixed(1)));

                    svg.append('line')//measurement line hing
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", hingelinex)
                        .attr("x2", hingelinex)
                        .attr("y1", (hngLctn3Y + 20))
                        .attr("y2", hngLctn3Y)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    // Hinge 3 2nd text measurement
                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", hingetextx)
                        .attr("font-size", 10)
                        .attr("y", (hngLctn3Y + 20) + ((hngLctn3Y - (hngLctn3Y + 20)) / 2) + 5)
                        .text(102);
                    // hinge 3 first top
                    createLine("black", 0.5, hingelinex - 5, hngLctn2Y + 20, ix + FrameThicknessForMap - 1, hngLctn2Y + 20);
                }

                 if (IsFourSidedFrame == true) {
// Hinge 4 line
                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", hingelinex)
                    .attr("x2", hingelinex)
                    .attr("y1", (hngLctn3Y + 20))
                    .attr("y2", iy + ((FrameHeight) / 5)+(OverPanelHeight ?? 0)-(Gap) - (FrameThickness/5))
                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                    .attr("marker-end", "url(#arrowRight)")

                     // hinge 4 joining line bottom
                createLine("black", 0.5, ix + FrameThicknessForMap - 1, iy + (FrameHeight / 5)+(OverPanelHeight ?? 0)-(Gap) - (FrameThickness/5), hingelinex - 5, iy + (FrameHeight / 5)+(OverPanelHeight ?? 0)-(Gap) - (FrameThickness/5));

                 }else{
// Hinge 4 line
                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", hingelinex)
                    .attr("x2", hingelinex)
                    .attr("y1", (hngLctn3Y + 20))
                    .attr("y2", iy + ((FrameHeight) / 5)+(OverPanelHeight ?? 0))
                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                    .attr("marker-end", "url(#arrowRight)")

                     // hinge 4 joining line bottom
                createLine("black", 0.5, ix + FrameThicknessForMap - 1, iy + (FrameHeight / 5)+(OverPanelHeight ?? 0), hingelinex - 5, iy + (FrameHeight / 5)+(OverPanelHeight ?? 0));

                 }
                
                // hinge 4 joining line top
                createLine("black", 0.5, hingelinex - 5, hngLctn3Y + 20, ix + FrameThicknessForMap - 1, hngLctn3Y + 20);

               
                // Hinge 4 text
                svg.append("text")
                    .style("fill", "black")
                    .style("writing-mode", WritingMode)
                    .attr("x", hingetextx)
                    .attr("font-size", 10)
                    .attr("y", ((hngLctn3Y + 20)+iy + ((FrameHeight) / 5)+ (OverPanelHeight ?? 0))/2)
                    .text(parseFloat(hinge3hieght.toFixed(1)));
            }

        }

        // HINGES LINE END

        if ((swingType && swingType == 'DA') || !frameonoff) {
        } else if (DoorSetType == 'SD' && Handing == 'Right') {
            svg.append('rect')
                .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap - 5)
                // .attr('y', iy + TopFrameHeight + GapForMap + 36)
                .attr('y', hngLctn1Y)
                .attr('width', 3)
                .attr('height', 20)
                .attr('stroke', 'black')
                .attr('fill', '#C8C8BC');
            svg.append('rect')
                .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap - 5)
                // .attr('y',iy + TopFrameHeight + GapForMap + 100)
                .attr('y', hngLctn2Y)
                .attr('width', 3)
                .attr('height', 20)
                .attr('stroke', 'black')
                .attr('fill', '#C8C8BC')
            svg.append('rect')
                .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap - 5)
                // .attr('y', iy + TopFrameHeight + (FrameHeightForMap - FrameThicknessForMap) - ( FrameThicknessForMap + GapForMap + 36))
                .attr('y', hngLctn3Y)
                .attr('width', 3)
                .attr('height', 20)
                .attr('stroke', 'black')
                .attr('fill', '#C8C8BC');

            hingelinex = ix + SOWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 20;
            hingetextx = hingelinex + 1;
        } else {
            //visible hinges with gap
            svg.append('rect')
                .attr('x', ix + FrameThicknessForMap - 1)
                .attr('y', hngLctn1Y)
                .attr('width', 3)
                .attr('height', 20)
                .attr('stroke', 'black')
                .attr('fill', '#C8C8BC');
            console.log("925", GapForMap, hngLctn1Y, ix + FrameThicknessForMap)
            svg.append('rect')
                .attr('x', ix + FrameThicknessForMap - 1)
                // .attr('y', iy + TopFrameHeight + GapForMap + 100)
                .attr('y', hngLctn2Y)
                .attr('width', 3)
                .attr('height', 20)
                .attr('stroke', 'black')
                .attr('fill', '#C8C8BC')

            svg.append('rect')
                .attr('x', ix + FrameThicknessForMap - 1)
                .attr('y', hngLctn3Y)
                .attr('width', 3)
                .attr('height', 20)
                .attr('stroke', 'black')
                .attr('fill', '#C8C8BC');

            hingelinex = ix - SideLightPanel2WidthSpaceForVerticalLines - 30;
            hingetextx = hingelinex - 28;
        }
        if (LeafHeightNoOP > 2400) {
            if (hingeCenter == 1) {
                hngLctn2Y = hngLctn1Y + ((hngLctn3Y - hngLctn1Y) / 3);
                hngLctn4Y = hngLctn1Y + (((hngLctn3Y - hngLctn1Y) / 3) * 2);
            }

            $('.middleHinges').attr('y', hngLctn2Y);

            // IF DOOR OPEN IN AND OUT BOTH SIDE THEN HINGES SHOULD NOT COME (28-12-2023)
            if ((swingType && swingType == 'DA') || !frameonoff) {
            } else if (DoorSetType == 'SD' && Handing == 'Right') {
                svg.append('rect')
                    .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap - 5)
                    // .attr('y', iy + TopFrameHeight + (FrameHeightForMap - FrameThicknessForMap) - ( FrameThicknessForMap + GapForMap + 100))
                    .attr('y', hngLctn4Y)
                    .attr('width', 3)
                    .attr('height', 20)
                    .attr('stroke', 'black')
                    .attr('fill', '#C8C8BC');
            } else {
                svg.append('rect')
                    .attr('x', ix + FrameThicknessForMap - 1)
                    // .attr('y', iy + TopFrameHeight + (FrameHeightForMap - FrameThicknessForMap) - (FrameThicknessForMap + GapForMap + 100))
                    .attr('y', hngLctn4Y)
                    .attr('width', 3)
                    .attr('height', 20)
                    .attr('stroke', 'black')
                    .attr('fill', '#C8C8BC');
                console.log("4Th hinge")
            }
        }

        hingelinex = ix - SideLightPanel2WidthSpaceForVerticalLines - 30;
        hingetextx = hingelinex - 28;

      var IsKickPlateEnable = false;
        var IsLockNLatchesEnable2 = false;
        var IsLockNLatchesEnable = false;
        var IsLeverHandlesEnable = false;
        var IsDoorSinageEnable = false;
        var IsDoorSinageEnable2 = false;
        var IsPullHandlesEnable = false;
        var IsPullHandlesEnable2 = false;
        var IsPushHandlesEnable = false;
        var IsPushHandlesEnable2 = false;
        var IsSecurityViewerEnable = false;
         var IsSecurityViewerEnable2 = false;
        var IsLetterPlatesEnable = false;
        var IsLetterPlatesEnable2 = false;
        var IsHingesEnable = false;
        var IsAirTransferGrillsEnable = false;
         var IsAirTransferGrillsEnable2 = false;
        var IsFlushBoltsEnable = false;
        var IsFlushBoltsEnable2 = false;
        var IsMorticedDropdownSealsEnable = false;
        var IsFacefixeddropsealsEnable = false;
        var IsConcealedOverheadCloserEnable = false;
        var IsFaceFixedDoorClosersEnable = false;
        var IsCylindersEnable = false;
        var IsThumbturnEnable = false;
        var IsCylindersEnable2 = false;
        var IsThumbturnEnable2 = false;
        /** Doorsecurityviewer Measurements */
        var DoorsecurityviewerdistanceFromBottomOfDoor = 0;
         var DoorsecurityviewerdistanceFromBottomOfDoor2 = 0;
        /** DoorSignage Measurements */
        var DoorSignagedistanceFromLeadingEdgeOfDoor = 0
        var DoorSignageCentered = 0
        var DoorSignageDistanceFromBottomOfDoor = 0
   var DoorSignagedistanceFromLeadingEdgeOfDoor2 = 0
  var DoorSignageCentered2 = 0
   var DoorSignageDistanceFromBottomOfDoor2 = 0
        /** Letterplates Measurements */
        var LetterplatesHeight = 0
        var LetterplatesWidth = 0
        var LetterplatesDistanceFromBottomOfDoor = 0
        var LetterplatesDistanceFromLeadingEdgeOfDoor = 0
        var LetterplatesCentered = 0
         var LetterplatesHeight2 = 0
        var LetterplatesWidth2 = 0
        var LetterplatesDistanceFromBottomOfDoor2 = 0
        var LetterplatesDistanceFromLeadingEdgeOfDoor2 = 0
        var LetterplatesCentered2 = 0
        /** AirTransferGrills Measurements */
        var AirTransferGrillsHeight = 0
        var AirTransferGrillsWidth = 0
        var AirTransferGrillsDistanceFromBottomOfDoor = 0
        var AirTransferGrillsHeight2 = 0
        var AirTransferGrillsWidth2 = 0
        var AirTransferGrillsDistanceFromBottomOfDoor2 = 0
        /** PullHandle Measurements */
        var PullHandleHeight = 0
        var PullHandleDistanceFromBottomOfDoor = 0
        var PullHandleDistanceFromLeadingEdgeOfDoor = 0
           var PullHandleHeight2 = 0
        var PullHandleDistanceFromBottomOfDoor2 = 0
        var PullHandleDistanceFromLeadingEdgeOfDoor2 = 0
        /** LeverHandle Measurements */
        var LeverHandleDistanceFromBottomOfDoor = 0
        var LeverHandleDistanceFromLeadingEdgeOfDoor = 0
        /** KickPlates Measurements */
        var KickPlatesHeight = 0
        /** FlushBoltsData Measurements */
        var FlushBoltsHeight = 0
        var FlushBoltsWidth = 0
        var FlushBoltsHeight2 = 0
        var FlushBoltsWidth2 = 0
        /** PushHandle Measurements */
        var PushHandleHeight = 0
        var PushHandleDistanceFromBottomOfDoor = 0
        var PushHandleDistanceFromLeadingEdgeOfDoor = 0
        var PushHandleHeight2 = 0
        var PushHandleDistanceFromBottomOfDoor2 = 0
        var PushHandleDistanceFromLeadingEdgeOfDoor2 = 0
        /** LockNLatches Measurements */
        var LockNLatchesDistanceFromBottomOfDoor = 0
        var LockNLatchesDistanceFromLeadingEdgeOfDoor=0
        var LockNLatchesDistanceFromBottomOfDoor2 = 0
        var LockNLatchesDistanceFromLeadingEdgeOfDoor2=0
        /** LockNLatches Measurements */
        var ConcealedOverheadCloserHeight = 0
        var ConcealedOverheadCloserWidth = 0
        /** FaceFixedDoorCloser Measurements */
        var FaceFixedDoorCloserDataHeight = 0
        var FaceFixedDoorClosersWidth = 0
        /** Thumbturn Measurements */
        var ThumbturnDistanceFromBottomOfDoor = 0
        var ThumbturnDistanceFromLeadingEdgeOfDoor=0
        var ThumbturnDistanceFromBottomOfDoor2 = 0
        var ThumbturnDistanceFromLeadingEdgeOfDoor2=0
        /** Cylinders Measurements */
        var CylindersDistanceFromBottomOfDoor = 0
        var CylindersDistanceFromLeadingEdgeOfDoor=0
        var CylindersDistanceFromBottomOfDoor2 = 0
        var CylindersDistanceFromLeadingEdgeOfDoor2=0

         if (IronmongerySet == "Yes" && IronmongeryID != "") {

            var ParsedIronmongerySet = JSON.parse(IronmongeryJson);

            ParsedIronmongerySet.forEach(function (elem, index) {
                if (elem.id == IronmongeryID) {

                    // console.log(elem);

                    if (elem.kickPlatesQty != '' && elem.kickPlatesQty != null) {
                        IsKickPlateEnable = true;
                        const KickPlatesData = elem.additional_info.find((item) => item.Category === "KickPlates");
                        KickPlatesHeight = KickPlatesData.staticHeight
                    }

                   if (elem.doorSignageQty != '' && elem.doorSignageQty != null) {
                    IsDoorSinageEnable = true;
                    IsDoorSinageEnable2 = true;

                    const doorSignages = elem.additional_info.filter(item => item.Category === "DoorSignage");

                    if (doorSignages.length > 0) {
                        const DoorSignageData1 = doorSignages[0];
                        DoorSignagedistanceFromLeadingEdgeOfDoor = DoorSignageData1.distanceFromLeadingEdgeOfDoor;
                        DoorSignageCentered = DoorSignageData1.centered;
                        DoorSignageDistanceFromBottomOfDoor = DoorSignageData1.distanceFromBottomOfDoor
                    }

                    if (doorSignages.length > 1) {
                        const DoorSignageData2 = doorSignages[1];
                        DoorSignagedistanceFromLeadingEdgeOfDoor2 = DoorSignageData2.distanceFromLeadingEdgeOfDoor;
                        DoorSignageCentered2 = DoorSignageData2.centered;
                        DoorSignageDistanceFromBottomOfDoor2 = DoorSignageData2.distanceFromBottomOfDoor
                    }
               }

                    if (elem.hingesQty != '' && elem.hingesQty != null) {
                        IsHingesEnable = true;
                    }

                    if (elem.LocksAndLatches != '' && elem.LocksAndLatches != null) {

                          const LocksAndLatches = elem.additional_info.filter(item => item.Category === "LocksandLatches");
   
                    if (LocksAndLatches.length > 0) {
                        IsLockNLatchesEnable = true;
                        const LockNLatchesData1 = LocksAndLatches[0];
                      LockNLatchesDistanceFromBottomOfDoor = LockNLatchesData1.distanceFromBottomOfDoor
                        LockNLatchesDistanceFromLeadingEdgeOfDoor=LockNLatchesData1.distanceFromLeadingEdgeOfDoor
                    }

                    if (LocksAndLatches.length > 1) {
                        IsLockNLatchesEnable2 = true;
                        const LockNLatchesData2 = LocksAndLatches[1];
                        LockNLatchesDistanceFromBottomOfDoor2 = LockNLatchesData2.distanceFromBottomOfDoor
                        LockNLatchesDistanceFromLeadingEdgeOfDoor2=LockNLatchesData2.distanceFromLeadingEdgeOfDoor
                    }
                    }
                    if (elem.Thumbturn != '' && elem.Thumbturn != null) {
                    
                         const Thumbturns = elem.additional_info.filter(item => item.Category === "Thumbturn");
   
                    if (Thumbturns.length > 0) {
                        IsThumbturnEnable = true;
                        const ThumbturnData1 = Thumbturns[0];
                      ThumbturnDistanceFromBottomOfDoor = ThumbturnData1.distanceFromBottomOfDoor
                        ThumbturnDistanceFromLeadingEdgeOfDoor=ThumbturnData1.distanceFromLeadingEdgeOfDoor
                    }

                    if (Thumbturns.length > 1) {
                        IsThumbturnEnable2 = true;
                        const ThumbturnData2 = Thumbturns[1];
                        ThumbturnDistanceFromBottomOfDoor2 = ThumbturnData2.distanceFromBottomOfDoor
                        ThumbturnDistanceFromLeadingEdgeOfDoor2=ThumbturnData2.distanceFromLeadingEdgeOfDoor
                    }
                    }
                    if (elem.Cylinders != '' && elem.Cylinders != null) {
                        

                          const Cylinders = elem.additional_info.filter(item => item.Category === "Cylinders");
   
                    if (Cylinders.length > 0) {
                        IsCylindersEnable = true;
                        const CylindersData1 = Cylinders[0];
                      CylindersDistanceFromBottomOfDoor = CylindersData1.distanceFromBottomOfDoor
                        CylindersDistanceFromLeadingEdgeOfDoor=CylindersData1.distanceFromLeadingEdgeOfDoor
                    }

                    if (Cylinders.length > 1) {
                        IsCylindersEnable2 = true;
                        const CylindersData2 = Cylinders[1];
                        CylindersDistanceFromBottomOfDoor2 = CylindersData2.distanceFromBottomOfDoor
                        CylindersDistanceFromLeadingEdgeOfDoor2=CylindersData2.distanceFromLeadingEdgeOfDoor
                    }
                    }

                    if (elem.ConcealedOverheadCloser != '' && elem.ConcealedOverheadCloser != null) {
                        IsConcealedOverheadCloserEnable = true;
                        const ConcealedOverheadCloserData = elem.additional_info.find((item) => item.Category === "ConcealedOverheadCloser");
                        ConcealedOverheadCloserWidth = ConcealedOverheadCloserData.staticWidth
                        ConcealedOverheadCloserHeight = ConcealedOverheadCloserData.staticHeight
                        // console.log("ConcealedOverheadCloser", ConcealedOverheadCloserWidth, ConcealedOverheadCloserHeight)
                    }
                    if (elem.FaceFixedDoorCloser != '' && elem.FaceFixedDoorCloser != null) {
                        IsFaceFixedDoorClosersEnable = true;
                        const FaceFixedDoorCloserData = elem.additional_info.find((item) => item.Category === "FaceFixedDoorClosers");
                        FaceFixedDoorClosersWidth = FaceFixedDoorCloserData.staticWidth
                        FaceFixedDoorCloserDataHeight = FaceFixedDoorCloserData.staticHeight
                        // console.log("FaceFixedDoorClosers", FaceFixedDoorClosersWidth, FaceFixedDoorCloserDataHeight)
                    }

                    if (elem.pullHandlesQty != '' && elem.pullHandlesQty != null) {
                       

                         const PullHandles = elem.additional_info.filter(item => item.Category === "PullHandles");
   
                    if (PullHandles.length > 0) {
                        IsPullHandlesEnable = true;
                        const PullHandlesData1 = PullHandles[0];
                        PullHandleHeight = PullHandlesData1.staticHeight
                        PullHandleDistanceFromBottomOfDoor = PullHandlesData1.distanceFromBottomOfDoor
                        PullHandleDistanceFromLeadingEdgeOfDoor = PullHandlesData1.distanceFromLeadingEdgeOfDoor
                        console.log(PullHandleHeight,PullHandleDistanceFromBottomOfDoor,PullHandleDistanceFromLeadingEdgeOfDoor)
                    }

                    if (PullHandles.length > 1) {
                        IsPullHandlesEnable2 = true;
                        const PullHandlesData2 = PullHandles[1];
                        PullHandleHeight2 = PullHandlesData2.staticHeight
                        PullHandleDistanceFromBottomOfDoor2 = PullHandlesData2.distanceFromBottomOfDoor
                        PullHandleDistanceFromLeadingEdgeOfDoor2 = PullHandlesData2.distanceFromLeadingEdgeOfDoor
                        console.log(PullHandleHeight2,PullHandleDistanceFromBottomOfDoor2,PullHandleDistanceFromLeadingEdgeOfDoor2)
                    }
                    }

                    if (elem.pushHandlesQty != '' && elem.pushHandlesQty != null) {

                        const PushHandles = elem.additional_info.filter(item => item.Category === "PushHandles");
   
                    if (PushHandles.length > 0) {
                        IsPushHandlesEnable = true;
                        const PushHandlesData1 = PushHandles[0];
                        PushHandleHeight = PushHandlesData1.staticHeight
                        PushHandleDistanceFromBottomOfDoor = PushHandlesData1.distanceFromBottomOfDoor
                        PushHandleDistanceFromLeadingEdgeOfDoor = PushHandlesData1.distanceFromLeadingEdgeOfDoor
                    }

                    if (PushHandles.length > 1) {
                        IsPushHandlesEnable2 = true;
                        const PushHandlesData2 = PushHandles[1];
                        PushHandleHeight2 = PushHandlesData2.staticHeight
                        PushHandleDistanceFromBottomOfDoor2 = PushHandlesData2.distanceFromBottomOfDoor
                        PushHandleDistanceFromLeadingEdgeOfDoor2 = PushHandlesData2.distanceFromLeadingEdgeOfDoor
                    }
                    }

                    if (elem.leverHandleQty != '' && elem.leverHandleQty != null) {
                        IsLeverHandlesEnable = true;
                        const LeverHandleData = elem.additional_info.find((item) => item.Category === "LeverHandle");
                        LeverHandleDistanceFromBottomOfDoor = LeverHandleData.distanceFromBottomOfDoor
                        LeverHandleDistanceFromLeadingEdgeOfDoor = LeverHandleData.distanceFromLeadingEdgeOfDoor
                        // console.log(LeverHandleDistanceFromBottomOfDoor, LeverHandleDistanceFromLeadingEdgeOfDoor, '1111111111111111111')
                    }

                    if (elem.Doorsecurityviewer != '' && elem.Doorsecurityviewer != null) {
                        

                    const SecurityViewers = elem.additional_info.filter(item => item.Category === "Doorsecurityviewer");
   
                    if (SecurityViewers.length > 0) {
                        IsSecurityViewerEnable = true;
                        const DoorsecurityviewerData1 = SecurityViewers[0];
                        DoorsecurityviewerdistanceFromBottomOfDoor = DoorsecurityviewerData1.distanceFromBottomOfDoor;
                    }

                    if (SecurityViewers.length > 1) {
                   IsSecurityViewerEnable2 = true;
                        const DoorsecurityviewerData2 = SecurityViewers[1];
                        DoorsecurityviewerdistanceFromBottomOfDoor2 = DoorsecurityviewerData2.distanceFromBottomOfDoor;
                    }

                    }
                    if (elem.Letterplates != '' && elem.Letterplates != null) {
                        

                         const LetterPlates = elem.additional_info.filter(item => item.Category === "Letterplates");
   
                    if (LetterPlates.length > 0) {
                        IsLetterPlatesEnable = true;
                        const LetterplatesData1 = LetterPlates[0];
                        LetterplatesHeight = LetterplatesData1.staticHeight
                        LetterplatesWidth = LetterplatesData1.staticWidth
                        LetterplatesDistanceFromBottomOfDoor = LetterplatesData1.distanceFromBottomOfDoor
                        LetterplatesDistanceFromLeadingEdgeOfDoor = LetterplatesData1.distanceFromLeadingEdgeOfDoor
                        LetterplatesCentered = LetterplatesData1.centered
                    }

                    if (LetterPlates.length > 1) {
                        IsLetterPlatesEnable2 = true;
                        const LetterplatesData2 = LetterPlates[1];
                       LetterplatesHeight2 = LetterplatesData2.staticHeight
                        LetterplatesWidth2 = LetterplatesData2.staticWidth
                        LetterplatesDistanceFromBottomOfDoor2 = LetterplatesData2.distanceFromBottomOfDoor
                        LetterplatesDistanceFromLeadingEdgeOfDoor2 = LetterplatesData2.distanceFromLeadingEdgeOfDoor
                        LetterplatesCentered2 = LetterplatesData2.centered
                    }


                    }
                    if (elem.AirTransferGrill != '' && elem.AirTransferGrill != null) {

                    const AirTransfers = elem.additional_info.filter(item => item.Category === "Airtransfergrills");
   
                    if (AirTransfers.length > 0) {
                        IsAirTransferGrillsEnable = true;
                        const AirTransferGrillData1 = AirTransfers[0];
                        AirTransferGrillsHeight = AirTransferGrillData1.staticHeight;
                        AirTransferGrillsWidth = AirTransferGrillData1.staticWidth;
                        AirTransferGrillsDistanceFromBottomOfDoor = AirTransferGrillData1.distanceFromBottomOfDoor
                    }

                    if (AirTransfers.length > 1) {
                        IsAirTransferGrillsEnable2 = true;
                        const AirTransferGrillData2 = AirTransfers[1];
                        AirTransferGrillsHeight2 = AirTransferGrillData2.staticHeight;
                        AirTransferGrillsWidth2 = AirTransferGrillData2.staticWidth;
                        AirTransferGrillsDistanceFromBottomOfDoor2 = AirTransferGrillData2.distanceFromBottomOfDoor
                    }

                    }
                      if (elem.FlushBolts != '' && elem.FlushBolts != null) {

                        const FlushBoltsData = elem.additional_info.filter(item => item.Category === "FlushBolts");
   
                    if (FlushBoltsData.length > 0) {
                        IsFlushBoltsEnable = true;
                        const FlushBoltsData1 = FlushBoltsData[0];
                        FlushBoltsHeight = FlushBoltsData1.staticHeight
                        FlushBoltsWidth = FlushBoltsData1.staticWidth
                    }

                    if (FlushBoltsData.length > 1) {
                        IsFlushBoltsEnable2 = true;
                        const FlushBoltsData2 = FlushBoltsData[1];
                        FlushBoltsHeight2 = FlushBoltsData2.staticHeight
                        FlushBoltsWidth2 = FlushBoltsData2.staticWidth
                    }
                    }
                    if (elem.Morticeddropdownseals != '' && elem.Morticeddropdownseals != null) {
                        IsMorticedDropdownSealsEnable = true;

                    }
                    if (elem.Facefixeddropseals != '' && elem.Facefixeddropseals != null) {
                        IsFacefixeddropsealsEnable = true;

                    }
                }
            });

        }

        var DecorativeGroves = $('select[name="decorativeGroves"]').val();

        if (DecorativeGroves == "Yes") {

            var GrooveLocation = $('select[name="grooveLocation"]').val();

            // var GrooveWidth = $('input[name="grooveWidth"]').val();
            // if(GrooveWidth == ""){
            //     GrooveWidth = 0;
            // }else{
            //     GrooveWidth = parseFloat(GrooveWidth);
            //     if(GrooveWidth > 0){
            //         GrooveWidth = GrooveWidth / 5;
            //     }
            // }

            var GrooveWidth = 1;

            var NumberOfGrooveForVertical = 0, NumberOfGrooveForHorizontal = 0;

            var Vertical = false, Horizontal = false;

            if (GrooveLocation == "Vertical" || GrooveLocation == "Horizontal") {
                var NumberOfGroove = $('input[name="numberOfGroove"]').val();
                if (NumberOfGroove != "") {
                    NumberOfGrooveForVertical = NumberOfGrooveForHorizontal = parseFloat(NumberOfGroove) + 1;
                }

                if (GrooveLocation == "Vertical") {
                    Vertical = true;
                } else {
                    Horizontal = true;
                }
            }

            if (GrooveLocation == "Vertical_&_Horizontal") {

                var NumberOfVerticalGroove = $('input[name="numberOfVerticalGroove"]').val();
                if (NumberOfVerticalGroove != "") {
                    NumberOfGrooveForVertical = parseFloat(NumberOfVerticalGroove) + 1;
                }

                var NumberOfHorizontalGroove = $('input[name="numberOfHorizontalGroove"]').val();
                if (NumberOfHorizontalGroove != "") {
                    NumberOfGrooveForHorizontal = parseFloat(NumberOfHorizontalGroove) + 1;
                }

                Vertical = Horizontal = true;
            }

            if (Vertical) {

                if (NumberOfGrooveForVertical > 0) {
                    var GrooveGap = (LeafWidth1ForMap - (GrooveWidth * (NumberOfGrooveForVertical - 1))) / NumberOfGrooveForVertical;
                    var GrooveStart = GrooveGap;
                    for (var t = 1; t < NumberOfGrooveForVertical; t++) {

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", GrooveWidth)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + GrooveStart)
                            .attr("y1", iy + TopFrameHeight + GapForMap)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + GrooveStart)
                            .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap);

                        GrooveStart = GrooveStart + GrooveGap + GrooveWidth;
                    }

                    if (DoorSetType == "DD") {
                        var GrooveGapForDD = (LeafWidth2ForMap - (GrooveWidth * (NumberOfGrooveForVertical - 1))) / NumberOfGrooveForVertical;
                        var GrooveStartForDD = GrooveGapForDD;
                        for (var t = 1; t < NumberOfGrooveForVertical; t++) {

                            svg.append('line')
                                .style("stroke", "black")
                                .style("stroke-width", GrooveWidth)
                                .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + GrooveStartForDD)
                                .attr("y1", iy + TopFrameHeight + GapForMap)
                                .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + GrooveStartForDD)
                                .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap);

                            GrooveStartForDD = GrooveStartForDD + GrooveGapForDD + GrooveWidth;
                        }
                    }

                }

            }

            if (Horizontal) {

                if (NumberOfGrooveForHorizontal > 0) {
                    var GrooveGap = (LeafHeightNoOPForMap - (GrooveWidth * (NumberOfGrooveForHorizontal - 1))) / NumberOfGrooveForHorizontal;
                    var GrooveStart = GrooveGap;
                    for (var t = 1; t < NumberOfGrooveForHorizontal; t++) {

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", GrooveWidth)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap)
                            .attr("y1", iy + TopFrameHeight + GapForMap + GrooveStart)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                            .attr("y2", iy + TopFrameHeight + GapForMap + GrooveStart);

                        GrooveStart = GrooveStart + GrooveGap + GrooveWidth;
                    }

                    if (DoorSetType == "DD") {
                        var GrooveStartForDD = GrooveGap;
                        for (var t = 1; t < NumberOfGrooveForHorizontal; t++) {

                            svg.append('line')
                                .style("stroke", "black")
                                .style("stroke-width", GrooveWidth)
                                .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles)
                                .attr("y1", iy + TopFrameHeight + GapForMap + GrooveStartForDD)
                                .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap)
                                .attr("y2", iy + TopFrameHeight + GapForMap + GrooveStartForDD);

                            GrooveStartForDD = GrooveStartForDD + GrooveGap + GrooveWidth;
                        }
                    }

                }
            }

        }

        if (IsKickPlateEnable && DoorSetType == "SD") {

            svg.append('rect')
                .attr('x', ix + FrameThicknessForMap + GapForMap + 3)
                .attr('y', iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (KickPlatesHeight / 5)))
                .attr('width', LeafWidth1ForMap - 6)
                .attr('height', KickPlatesHeight / 5)
                .attr('stroke', 'black')
                .attr('fill', '#D0D0C6');
            // if (Handing == 'Right') {

            //     svg.append('line')
            //         .style("stroke", "black")
            //         .style("stroke-width", 0.5)
            //         .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - 13)
            //         .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (KickPlatesHeight / 5)))
            //         .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - 13)
            //         .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap))
            //         .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
            //         .attr("marker-end", "url(#arrowRight)")
            //     svg.append("text")
            //         .style("fill", "black")
            //         .attr("font-size", 10)
            //         .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - 5)
            //         .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap) - (KickPlatesHeight / 10))
            //         .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - 5}, ${iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap) - (KickPlatesHeight / 10)})`)
            //         .text(KickPlatesHeight);
            // } else {
                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + FrameThicknessForMap + GapForMap + 33)
                    .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (KickPlatesHeight / 5)))
                    .attr("x2", ix + FrameThicknessForMap + GapForMap + 33)
                    .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap))
                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                    .attr("marker-end", "url(#arrowRight)")
                svg.append("text")
                    .style("fill", "black")
                    .attr("font-size", 10)
                    .attr("x", ix + FrameThicknessForMap + GapForMap + 25)
                    .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap) - (KickPlatesHeight / 10) )
                    .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + 25}, ${iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap) - (KickPlatesHeight / 10) })`)
                    .text(KickPlatesHeight);
            // }
        }

        if (ShowMeasurements) {

            /* Horizontal Line for First Door Leaf */

            svg.append("text")            // append text
                .style("fill", "black")      // make the text black
                .attr("font-size", 10)
                // .attr("x", ix + ( LeafWidth1ForMap / 2))         // set x position of left side of text
                .attr("x", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap / 2))         // set x position of left side of text
                // .attr("y", iy - 65)         // set y position of bottom of text
                .attr("y", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 30)
                .text(LeafWidth1);   // define the text to display

            svg.append('line')
                .style("stroke", "black")
                .style("stroke-width", 0.5)
                .attr("x1", ix + FrameThicknessForMap + GapForMap)
                .attr("y1", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 10)
                .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                .attr("y2", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 10)
                .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                .attr("marker-end", "url(#arrowRight)")
        }

        /* Horizontal Line for First Door Leaf */

        if (DoorSetType == "DD") {

            svg.append('rect') // double door rect
                .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles)
                .attr('y', iy + TopFrameHeight + GapForMap)
                .attr('width', LeafWidth2ForMap)
                .attr('height', LeafHeightNoOPForMap)
                .attr('stroke', 'black')
                .attr('fill', '#EBECE6');

                if ((swingType && swingType == 'DA') || !frameonoff) {
                } else{

                if (LeafHeightNoOP > 2400) {
                    if (hingeCenter == 1) {
                        hngLctn2Y = hngLctn1Y + ((hngLctn3Y - hngLctn1Y) / 3);
                        hngLctn4Y = hngLctn1Y + (((hngLctn3Y - hngLctn1Y) / 3) * 2);
                    }

                    $('.middleHinges').attr('y', hngLctn2Y);

                    // IF DOOR OPEN IN AND OUT BOTH SIDE THEN HINGES SHOULD NOT COME (28-12-2023)

                        svg.append('rect')
                            .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap - 2)
                            // .attr('y', iy + TopFrameHeight + (FrameHeightForMap - FrameThicknessForMap) - ( FrameThicknessForMap + GapForMap + 100))
                            .attr('y', hngLctn4Y)
                            .attr('width', 3)
                            .attr('height', 20)
                            .attr('stroke', 'black')
                            .attr('fill', '#C8C8BC');

                }

                    svg.append('rect')
                    .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap - 2)
                    // .attr('y', iy + TopFrameHeight + GapForMap + 36)
                    .attr('y', hngLctn1Y)
                    .attr('width', 3)
                    .attr('height', 20)
                    .attr('stroke', 'black')
                    .attr('fill', '#C8C8BC');
                svg.append('rect')
                    .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap - 2)
                    // .attr('y',iy + TopFrameHeight + GapForMap + 100)
                    .attr('y', hngLctn2Y)
                    .attr('width', 3)
                    .attr('height', 20)
                    .attr('stroke', 'black')
                    .attr('fill', '#C8C8BC')
                svg.append('rect')
                    .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap - 2)
                    // .attr('y', iy + TopFrameHeight + (FrameHeightForMap - FrameThicknessForMap) - ( FrameThicknessForMap + GapForMap + 36))
                    .attr('y', hngLctn3Y)
                    .attr('width', 3)
                    .attr('height', 20)
                    .attr('stroke', 'black')
                    .attr('fill', '#C8C8BC');
            }
                // EBECE6
                if (IsKickPlateEnable) {


                    svg.append('rect')
                    .attr('x', ix + FrameThicknessForMap + GapForMap + 3)
                    .attr('y', iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (KickPlatesHeight / 5)))
                    .attr('width', LeafWidth1ForMap - 6)
                    .attr('height', KickPlatesHeight / 5)
                    .attr('stroke', 'black')
                    .attr('fill', '#D0D0C6');

                    svg.append('rect')
                    .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles+3)
                    .attr('y', iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (KickPlatesHeight / 5)))
                    .attr('width', LeafWidth2ForMap - 6)
                    .attr('height', KickPlatesHeight / 5)
                    .attr('stroke', 'black')
                    .attr('fill', '#D0D0C6');

                    svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + FrameThicknessForMap + GapForMap + 33)
                    .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (KickPlatesHeight / 5)))
                    .attr("x2", ix + FrameThicknessForMap + GapForMap + 33)
                    .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap))
                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                    .attr("marker-end", "url(#arrowRight)")
                svg.append("text")
                    .style("fill", "black")
                    .attr("font-size", 10)
                    .attr("x", ix + FrameThicknessForMap + GapForMap + 25)
                    .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap) - (KickPlatesHeight / 10) )
                    .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + 25}, ${iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap) - (KickPlatesHeight / 10) })`)
                    .text(KickPlatesHeight);
            }

            /* Horizontal Line for Second Door Leaf */
            if (ShowMeasurements) {
                svg.append("text")            // append text
                    .style("fill", "black")      // make the text black
                    .attr("font-size", 10)
                    // .attr("x", ix + ( LeafWidth1ForMap +  LeafWidth2ForMap / 2))         // set x position of left side of text
                    .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + (LeafWidth2ForMap / 2))         // set x position of left side of text
                    // .attr("y", iy - 65)         // set y position of bottom of text
                    .attr("y", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 30)
                    .text(LeafWidth2);   // define the text to display

                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    // .attr("x1", ix + LeafWidth1ForMap + RightGapForLeaf2 )
                    .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles)
                    // .attr("y1", iy - 60)
                    .attr("y1", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 10)
                    // .attr("x2", ix + LeafWidth1ForMap + LeafWidth2ForMap + RightGapForLeaf2)
                    .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap)
                    // .attr("y2", iy - 60)
                    .attr("y2", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 10)
                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                    .attr("marker-end", "url(#arrowRight)");
            }
            /* Horizontal Line for Second Door Leaf */

        }

        /* Vertical Line for Inner Door Leaf */
        if (ShowMeasurements) {
            svg.append('line')
                .style("stroke", "black")
                .style("stroke-width", 0.5)
                .attr("x1", ix + SOWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 51 + 15)
                //.attr("y1", iy + GapAfterOverPanelApplied + UpperAndLowerGap)
                .attr("y1", iy + TopFrameHeight + GapForMap)
                .attr("x2", ix + SOWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 51 + 15)
                //.attr("y2", iy + GapAfterOverPanelApplied + UpperAndLowerGap + LeafHeightNoOPForMap)
                .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                .attr("marker-end", "url(#arrowRight)");

            svg.append("text")           // append text
                .style("fill", "black")      // set text color
                .style("writing-mode", WritingMode) // set the writing mode
                .attr("x", ix + SOWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 52) // set x position
                .attr("font-size", 10)
                .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap / 2) + 15) // set y position
                .attr("transform", `rotate(-90, ${ix + SOWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 47}, ${iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap / 2)})`)
                .text(LeafHeightNoOP);


            svg.append('line') // inner frame measurement line top
                .style("stroke", "black")
                .style("stroke-width", 0.5)
                .attr("x1", ix + SOWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 51 + 20)
                .attr("y1", iy + TopFrameHeight + GapForMap)
                .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                .attr("y2", iy + TopFrameHeight + GapForMap)

            svg.append('line') // inner frame measurement line top
                .style("stroke", "black")
                .style("stroke-width", 0.5)
                .attr("x1", ix + SOWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 51 + 20)
                .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)


            /* Vertical Line for Inner Door Leaf */

            if (DoorSetType == "DD" && frameonoff && Gap) {
                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap)
                    .attr("y1", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 10)
                    .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap + GapForMap)
                    .attr("y2", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 10)
                    .attr("marker-start", "url(#verticalMarket)")  // Left-pointing arrow
                    .attr("marker-end", "url(#verticalMarket)")

                svg.append("text")            // append text
                    .style("fill", "black")      // make the text black
                    .attr("font-size", 10)
                    .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap)         // set x position of left side of text
                    .attr("y", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 30)
                    .text(Gap);   // define the text to display
            } else if (frameonoff && Gap) {
                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                    .attr("y1", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 11)
                    .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles)
                    .attr("y2", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 11)
                    .attr("marker-start", "url(#verticalMarket)")  // Left-pointing arrow
                    .attr("marker-end", "url(#verticalMarket)");

                svg.append("text")            // append text
                    .style("fill", "black")      // make the text black
                    .attr("font-size", 10)
                    .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)         // set x position of left side of text
                    .attr("y", iy + TopFrameHeight + ((FrameHeight - FrameThickness) / 5) + 30)
                    .text(Gap);   // define the text to display
            }
        }
        //}

        var Leaf1VisionPanel = $('select[name="leaf1VisionPanel"]').val();

        var Leaf1VisionPanelWidth = $('input[name="vP1Width"]').val();
        var Leaf1VisionPanelWidthToShow = 0;
        if (Leaf1VisionPanelWidth == "") {
            Leaf1VisionPanelWidth = 0;
        } else {
            Leaf1VisionPanelWidthToShow = parseFloat(Leaf1VisionPanelWidth);
            if (Leaf1VisionPanelWidth > 0) {
                Leaf1VisionPanelWidth = parseFloat(Leaf1VisionPanelWidth) / 5;
            }
        }
        var distanceBetweenVP = $('input[name="distanceBetweenVPs"]').val();
        var DistanceBetweenVPsMinValue = $('input[name="distanceBetweenVPs"]').attr("min");
        var DistanceFromTopOfDoorValue = $('input[name="distanceFromTopOfDoor"]').val();
        var DistanceFromTopOfDoorMinValue = $('input[name="distanceFromTopOfDoor"]').attr("min");
        var DistanceFromTheEdgeOfDoorMinValue = $('input[name="distanceFromTheEdgeOfDoor"]').attr("min");

        var DistanceBetweenVPsForLeaf1 = DistanceBetweenVPsMinValue;
        var DistanceFromTopOfDoorForLeaf1 = DistanceFromTopOfDoorMinValue;
        var DistanceFromTheEdgeOfDoorForLeaf1 = DistanceFromTheEdgeOfDoorMinValue;

        if (Leaf1VisionPanel == "Yes") {
            if (ChangedFieldName == "leaf1VisionPanel") {
                $('input[name="distanceFromTopOfDoor"]').val(DistanceFromTopOfDoorMinValue);

                $('input[name="distanceFromTheEdgeOfDoor"]').val(DistanceFromTheEdgeOfDoorMinValue);

            } else {
                DistanceFromTopOfDoorForLeaf1 = $('input[name="distanceFromTopOfDoor"]').val();
                if (DistanceFromTopOfDoorForLeaf1 == "" || parseFloat(DistanceFromTopOfDoorForLeaf1) < DistanceFromTopOfDoorMinValue) {
                    DistanceFromTopOfDoorForLeaf1 = DistanceFromTopOfDoorMinValue;
                }
                DistanceFromTheEdgeOfDoorForLeaf1 = $('input[name="distanceFromTheEdgeOfDoor"]').val();
                if (DistanceFromTheEdgeOfDoorForLeaf1 == "" || parseFloat(DistanceFromTheEdgeOfDoorForLeaf1) < DistanceFromTheEdgeOfDoorMinValue) {
                    DistanceFromTheEdgeOfDoorForLeaf1 = DistanceFromTheEdgeOfDoorMinValue;
                }
            }
        }

        var Leaf1VisionPanel1Height = 0, Leaf1VisionPanel2Height = 0,
            Leaf1VisionPanel3Height = 0, Leaf1VisionPanel4Height = 0,
            Leaf1VisionPanel5Height = 0;

        var Leaf1VisionPanel1HeightToShow = 0, Leaf1VisionPanel2HeightToShow = 0,
            Leaf1VisionPanel3HeightToShow = 0, Leaf1VisionPanel4HeightToShow = 0,
            Leaf1VisionPanel5HeightToShow = 0;

        var VisionPanelShape = $('select[name="leaf1VisionPanelShape"]').val();

        var AreVPsEqualSizesForLeaf1 = $('select[name="AreVPsEqualSizes"]').val();

        var VisionPanelQuantityForLeaf1 = $('select[name="visionPanelQuantity"]').val();
        if (VisionPanelQuantityForLeaf1 == "") {
            VisionPanelQuantityForLeaf1 = 1;
        } else {
            VisionPanelQuantityForLeaf1 = parseFloat(VisionPanelQuantityForLeaf1);
        }

        if (VisionPanelShape == "Rectangle") {
            $('input[name="vP1Height1"]').removeAttr("readonly");

            if (AreVPsEqualSizesForLeaf1 == "No") {
                if (VisionPanelQuantityForLeaf1 > 1) {
                    $('input[name="vP1Height2"]').removeAttr("readonly");
                }
                if (VisionPanelQuantityForLeaf1 > 2) {
                    $('input[name="vP1Height3"]').removeAttr("readonly");
                }
                if (VisionPanelQuantityForLeaf1 > 3) {
                    $('input[name="vP1Height4"]').removeAttr("readonly");
                }
                if (VisionPanelQuantityForLeaf1 > 4) {
                    $('input[name="vP1Height5"]').removeAttr("readonly");
                }
            }
        } else {
            $('input[name="vP1Height1"]').attr("readonly", true);
            if (VisionPanelQuantityForLeaf1 > 1) {
                $('input[name="vP1Height2"]').attr("readonly", true);
            }
            if (VisionPanelQuantityForLeaf1 > 2) {
                $('input[name="vP1Height3"]').attr("readonly", true);
            }
            if (VisionPanelQuantityForLeaf1 > 3) {
                $('input[name="vP1Height4"]').attr("readonly", true);
            }
            if (VisionPanelQuantityForLeaf1 > 4) {
                $('input[name="vP1Height5"]').attr("readonly", true);
            }
        }

        if (Leaf1VisionPanel == "Yes") {
            if (ChangedFieldName == "visionPanelQuantity" && VisionPanelQuantityForLeaf1 > 1) {
                $('input[name="distanceBetweenVPs"]').val(DistanceBetweenVPsMinValue);
            } else {
                if (VisionPanelQuantityForLeaf1 < 2) {
                    DistanceBetweenVPsForLeaf1 = 0;
                } else {
                    DistanceBetweenVPsForLeaf1 = $('input[name="distanceBetweenVPs"]').val();
                    if (DistanceBetweenVPsForLeaf1 == "" || parseFloat(DistanceBetweenVPsForLeaf1) < DistanceBetweenVPsMinValue) {
                        DistanceBetweenVPsForLeaf1 = DistanceBetweenVPsMinValue;
                    }
                }
            }
        }



        if (Leaf1VisionPanel == "Yes") {
            if (ChangedFieldName == "distanceBetweenVPs") {
                DistanceBetweenVPsForLeaf1 = $('input[name="distanceBetweenVPs"]').val();
                if (DistanceBetweenVPsForLeaf1 == "" || parseFloat(DistanceBetweenVPsForLeaf1) < DistanceBetweenVPsMinValue) {
                    DistanceBetweenVPsForLeaf1 = DistanceBetweenVPsMinValue;
                }
            }
        }

        var DistanceBetweenVPsForLeaf1ToShow = 0;
        if (DistanceBetweenVPsForLeaf1 != "" && DistanceBetweenVPsForLeaf1 > 0) {
            DistanceBetweenVPsForLeaf1ToShow = parseFloat(DistanceBetweenVPsForLeaf1);
            DistanceBetweenVPsForLeaf1 = parseFloat(DistanceBetweenVPsForLeaf1) / 5;
        }



        if (Leaf1VisionPanel == "Yes") {
            if (ChangedFieldName == "distanceFromTopOfDoor") {
                DistanceFromTopOfDoorForLeaf1 = $('input[name="distanceFromTopOfDoor"]').val();
                if (DistanceFromTopOfDoorForLeaf1 == "" || parseFloat(DistanceFromTopOfDoorForLeaf1) < DistanceFromTopOfDoorMinValue) {
                    DistanceFromTopOfDoorForLeaf1 = DistanceFromTopOfDoorMinValue;
                }
            }
        }

        var DistanceFromTopOfDoorForLeaf1ToShow = 0;
        if (DistanceFromTopOfDoorForLeaf1 != "" && DistanceFromTopOfDoorForLeaf1 > 0) {
            DistanceFromTopOfDoorForLeaf1ToShow = parseFloat(DistanceFromTopOfDoorForLeaf1);
            if (DistanceFromTopOfDoorForLeaf1 > 0) {
                DistanceFromTopOfDoorForLeaf1 = parseFloat(DistanceFromTopOfDoorForLeaf1) / 5;
            }
        }



        if (Leaf1VisionPanel == "Yes") {
            if (ChangedFieldName == "distanceFromTheEdgeOfDoor") {
                DistanceFromTheEdgeOfDoorForLeaf1 = $('input[name="distanceFromTheEdgeOfDoor"]').val();
                if (DistanceFromTheEdgeOfDoorForLeaf1 == "" || parseFloat(DistanceFromTheEdgeOfDoorForLeaf1) < DistanceFromTheEdgeOfDoorMinValue) {
                    DistanceFromTheEdgeOfDoorForLeaf1 = DistanceFromTheEdgeOfDoorMinValue;
                }
            }
        }

        var DistanceFromTheEdgeOfDoorForLeaf1ToShow = 0;
        if (DistanceFromTheEdgeOfDoorForLeaf1 != "" && DistanceFromTheEdgeOfDoorForLeaf1 > 0) {
            DistanceFromTheEdgeOfDoorForLeaf1ToShow = parseFloat(DistanceFromTheEdgeOfDoorForLeaf1);
            if (DistanceFromTheEdgeOfDoorForLeaf1 > 0) {
                DistanceFromTheEdgeOfDoorForLeaf1 = parseFloat(DistanceFromTheEdgeOfDoorForLeaf1) / 5;
            }
        }

        /* Ironmongery */

        var DistanceOfIronmongeryItemsFromRightSideEnd = 20;
        var DistanceOfIronmongeryItemsFromLeftSideEnd = 20;

        var DistanceOfIronmongeryItemsFromRightSideEndForAdjusting = 5;
        var DistanceOfIronmongeryItemsFromLeftSideEndForAdjusting = 5;

        var DistanceOfPushHandlesFromBelow = (1000 / 5) + 100;
        var DistanceOfPullHandlesFromBelow = (1000 / 5) + 100;

        if (IronmongerySet == "Yes" && IronmongeryID != "") {

            if (IsSecurityViewerEnable && DoorSetType == "SD") {
                if (Handing == 'Right') {

                    svg.append("circle")
                        .style("stroke", "black")
                        .style("fill", "black")
                        .attr("r", 3)
                        .attr("cx", ix + (FrameWidthForMap / 2))
                        .attr("cy", iy + FrameHeightForMap - (DoorsecurityviewerdistanceFromBottomOfDoor / 5));

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + (FrameWidthForMap / 2))
                        .attr("y1", iy + FrameHeightForMap - (DoorsecurityviewerdistanceFromBottomOfDoor / 5))
                        .attr("x2", ix - SideLightPanel2WidthSpaceForVerticalLines - 97)
                        .attr("y2", iy + FrameHeightForMap - (DoorsecurityviewerdistanceFromBottomOfDoor / 5));

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix - SideLightPanel2WidthSpaceForVerticalLines - 92)
                        .attr("y1", iy + FrameHeightForMap - (DoorsecurityviewerdistanceFromBottomOfDoor / 5))
                        .attr("x2", ix - SideLightPanel2WidthSpaceForVerticalLines - 92)
                        .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text green
                        .attr("font-size", 10)
                        .attr("x", ix - SideLightPanel2WidthSpaceForVerticalLines - 97) // set x position
                        .attr("y", (iy + FrameHeightForMap - (DoorsecurityviewerdistanceFromBottomOfDoor / 5) + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2)        // set y position
                        .attr("transform", `rotate(-90, ${ix - SideLightPanel2WidthSpaceForVerticalLines - 97}, ${(iy + FrameHeightForMap - (DoorsecurityviewerdistanceFromBottomOfDoor / 5) + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2})`) // rotate the text
                        .text(DoorsecurityviewerdistanceFromBottomOfDoor);        // define the text to display
                } else {

                    svg.append("circle")
                        .style("stroke", "black")
                        .style("fill", "black")
                        .attr("r", 3)
                        .attr("cx", ix + (FrameWidthForMap / 2))
                        .attr("cy", iy + FrameHeightForMap - (DoorsecurityviewerdistanceFromBottomOfDoor / 5));

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + (FrameWidthForMap / 2))
                        .attr("y1", iy + FrameHeightForMap - (DoorsecurityviewerdistanceFromBottomOfDoor / 5))
                        .attr("x2", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 92 + 40)
                        .attr("y2", iy + FrameHeightForMap - (DoorsecurityviewerdistanceFromBottomOfDoor / 5));

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 92 + 40)
                        .attr("y1", iy + FrameHeightForMap - (DoorsecurityviewerdistanceFromBottomOfDoor / 5))
                        .attr("x2", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 92 + 40)
                        .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text green
                        .attr("font-size", 10)
                        .attr("x", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 92 + 40) // set x position
                        .attr("y", (iy + FrameHeightForMap - (DoorsecurityviewerdistanceFromBottomOfDoor / 5) + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2)        // set y position
                        .attr("transform", `rotate(-90, ${ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 92 + 35}, ${(iy + FrameHeightForMap - (DoorsecurityviewerdistanceFromBottomOfDoor / 5) + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2})`) // rotate the text
                        .text(DoorsecurityviewerdistanceFromBottomOfDoor);        // define the text to display
                }
            }


            if (IsAirTransferGrillsEnable && DoorSetType == "SD") {
                const grillX = ix + (FrameWidthForMap / 2) - ((AirTransferGrillsWidth / 5) / 2);
                const grillY = iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (AirTransferGrillsDistanceFromBottomOfDoor / 5) - (AirTransferGrillsHeight / 5);
                const grillWidth = AirTransferGrillsWidth / 5;
                const grillHeight = AirTransferGrillsHeight / 5;

                // Draw the outer rectangle
                svg.append('rect')
                    .attr('x', grillX)
                    .attr('y', grillY)
                    .attr('width', grillWidth)
                    .attr('height', grillHeight)
                    .attr('stroke', 'black')
                    .attr('fill', '#D0D0C6');

                // Draw the horizontal slots
                const numSlots = AirTransferGrillsHeight / 20; // Number of horizontal slots
                const slotHeight = grillHeight / (2 * numSlots); // Slot height
                const gapHeight = slotHeight; // Gap between slots

                for (let i = 0; i < numSlots; i++) {
                    svg.append('rect')
                        .attr('x', grillX + 2) // Slight margin from the left
                        .attr('y', grillY + i * (slotHeight + gapHeight) + 2) // Space out slots
                        .attr('width', grillWidth - 4) // Slight margin on both sides
                        .attr('height', slotHeight)
                        .attr('fill', 'black'); // Black slots
                }
                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", grillX - 5)
                    .attr("y1", grillY)
                    .attr("x2", grillX - 5)
                    .attr("y2", grillY + grillHeight)
                    .attr("marker-start", "url(#arrowLeft)")
                    .attr("marker-end", "url(#arrowRight)")

                svg.append("text")            // append text
                    .style("fill", "black")   // make the text
                    .attr("font-size", 10)
                    .attr("x", grillX - 10)    // set x position
                    .attr("y", grillY + (grillHeight / 2) + 5) // set y position
                    .attr("transform", `rotate(-90, ${grillX - 10}, ${grillY + (grillHeight / 2) + 5})`) // rotate around the text's position
                    .text(AirTransferGrillsHeight);


                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", grillX)
                    .attr("y1", grillY)
                    .attr("x2", grillX)
                    .attr("y2", grillY - 10)

                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", grillX + grillWidth)
                    .attr("y1", grillY)
                    .attr("x2", grillX + grillWidth)
                    .attr("y2", grillY - 10)

                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", grillX)
                    .attr("y1", grillY - 5)
                    .attr("x2", grillX + grillWidth)
                    .attr("y2", grillY - 5)
                    .attr("marker-start", "url(#arrowLeft)")
                    .attr("marker-end", "url(#arrowRight)")

                svg.append("text")            // append text
                    .style("fill", "black")   // make the text
                    .attr("font-size", 10)
                    .attr("x", grillX + (grillWidth / 2) - 5)    // set x position
                    .attr("y", grillY - 10) // set y position
                    .text(AirTransferGrillsWidth);

                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", grillX)
                    .attr("y1", grillY)
                    .attr("x2", grillX - 10)
                    .attr("y2", grillY)

                if (Handing == 'Right') {

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", grillX + grillWidth)
                        .attr("y1", grillY + grillHeight)
                        .attr("x2", grillX + grillWidth + 30)
                        .attr("y2", grillY + grillHeight)

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", grillX + grillWidth + 25)
                        .attr("y1", grillY + grillHeight)
                        .attr("x2", grillX + grillWidth + 25)
                        .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", grillX + grillWidth + 30)    // set x position
                        .attr("y", ((grillY + grillHeight + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2) + 5) // set y position
                        .attr("transform", `rotate(-90, ${grillX + grillWidth + 30}, ${((grillY + grillHeight + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2)})`) // rotate around the text's position
                        .text(AirTransferGrillsDistanceFromBottomOfDoor);
                } else {

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", grillX)
                        .attr("y1", grillY + grillHeight)
                        .attr("x2", grillX - 30)
                        .attr("y2", grillY + grillHeight)

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", grillX - 25)
                        .attr("y1", grillY + grillHeight)
                        .attr("x2", grillX - 25)
                        .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", grillX - 30)    // set x position
                        .attr("y", ((grillY + grillHeight + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2) + 5) // set y position
                        .attr("transform", `rotate(-90, ${grillX - 30}, ${((grillY + grillHeight + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2) + 5})`) // rotate around the text's position
                        .text(AirTransferGrillsDistanceFromBottomOfDoor);
                }


            }

            if (IsPushHandlesEnable && DoorSetType == "SD" && !IsPullHandlesEnable) {
                if (LeafWidth1ForMap > 32) {
                    if (Handing == 'Right') {
                        svg.append("rect")
                            .attr('x', ix + FrameThicknessForMap + GapForMap + (PushHandleDistanceFromLeadingEdgeOfDoor / 5) )
                            .attr('y', iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PushHandleDistanceFromBottomOfDoor / 5) - (PushHandleHeight / 5))
                            .attr("width", 6)
                            .attr("height", (PushHandleHeight / 5))
                            .style("fill", '#D0D0C6')
                            .style("stroke", 'black')

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + (PushHandleDistanceFromLeadingEdgeOfDoor / 5) + 3)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PushHandleDistanceFromBottomOfDoor / 5) - (PushHandleHeight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 155)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PushHandleDistanceFromBottomOfDoor / 5) - (PushHandleHeight / 5));

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 155)    // set x position
                            .attr("y", (iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PushHandleDistanceFromBottomOfDoor / 5) - (PushHandleHeight / 5)) / 2 + 5) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 155}, ${(iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PushHandleDistanceFromBottomOfDoor / 5) - (PushHandleHeight / 5)) / 2 + 5})`) // rotate
                            .text(`${parseInt(PushHandleDistanceFromBottomOfDoor) + parseInt(PushHandleHeight)}`);

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 150)
                            .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 150)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PushHandleDistanceFromBottomOfDoor / 5) - (PushHandleHeight / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + (PushHandleDistanceFromLeadingEdgeOfDoor / 5) + 3)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PushHandleDistanceFromBottomOfDoor / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 125)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PushHandleDistanceFromBottomOfDoor / 5));

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 120)
                            .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 120)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PushHandleDistanceFromBottomOfDoor / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 125)    // set x position
                            .attr("y", (iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PushHandleDistanceFromBottomOfDoor / 5)) / 2 + 5) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 125}, ${(iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PushHandleDistanceFromBottomOfDoor / 5)) / 2 + 5})`) // rotate
                            .text(`${parseInt(PushHandleDistanceFromBottomOfDoor)}`);
                    } else {
                        svg.append("rect")
                            .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (PushHandleDistanceFromLeadingEdgeOfDoor / 5) - 6)
                            .attr('y', iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PushHandleDistanceFromBottomOfDoor / 5) - (PushHandleHeight / 5))
                            .attr("width", 6)
                            .attr("height", (PushHandleHeight / 5))
                            .style("fill", '#D0D0C6')
                            .style("stroke", 'black')

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (PushHandleDistanceFromLeadingEdgeOfDoor / 5) - 3)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PushHandleDistanceFromBottomOfDoor / 5) - (PushHandleHeight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 195)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PushHandleDistanceFromBottomOfDoor / 5) - (PushHandleHeight / 5));

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 185)    // set x position
                            .attr("y", (iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PushHandleDistanceFromBottomOfDoor / 5) - (PushHandleHeight / 5)) / 2 + 5) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 185}, ${(iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PushHandleDistanceFromBottomOfDoor / 5) - (PushHandleHeight / 5)) / 2 + 5})`) // rotate
                            .text(`${parseInt(PushHandleDistanceFromBottomOfDoor) + parseInt(PushHandleHeight)}`);

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 190)
                            .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 190)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PushHandleDistanceFromBottomOfDoor / 5) - (PushHandleHeight / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (PushHandleDistanceFromLeadingEdgeOfDoor / 5) - 3)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PushHandleDistanceFromBottomOfDoor / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 165)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PushHandleDistanceFromBottomOfDoor / 5));

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 160)
                            .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 160)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PushHandleDistanceFromBottomOfDoor / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 155)    // set x position
                            .attr("y", (iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PushHandleDistanceFromBottomOfDoor / 5)) / 2 + 5) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 155}, ${(iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PushHandleDistanceFromBottomOfDoor / 5)) / 2 + 5})`) // rotate
                            .text(`${parseInt(PushHandleDistanceFromBottomOfDoor)}`);
                    }
                }
            }

            if (IsPullHandlesEnable && DoorSetType == "SD") {
                if (LeafWidth1ForMap > 32) {
                    if (Handing == 'Right') {

                        svg.append("rect")
                            .attr('x', ix + FrameThicknessForMap + GapForMap + (PullHandleDistanceFromLeadingEdgeOfDoor / 5))
                            .attr('y', iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PullHandleDistanceFromBottomOfDoor / 5) - (PullHandleHeight / 5))
                            .attr("width", 6)
                            .attr("height", (PullHandleHeight / 5))
                            .attr("rx", 4)
                            .style("fill", '#D0D0C6')
                            .style("stroke", 'black')

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + (PullHandleDistanceFromLeadingEdgeOfDoor / 5))
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PullHandleDistanceFromBottomOfDoor / 5) - (PullHandleHeight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 165)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PullHandleDistanceFromBottomOfDoor / 5) - (PullHandleHeight / 5));

                        svg.append("text")
                            .style("fill", "black")
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 165)    // set x position
                            .attr("y", (iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PullHandleDistanceFromBottomOfDoor / 5) - (PullHandleHeight / 5)) / 2 + 5) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 165}, ${(iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PullHandleDistanceFromBottomOfDoor / 5) - (PullHandleHeight / 5)) / 2 + 5})`) // rotate
                            .text(`${parseInt(PullHandleDistanceFromBottomOfDoor) + parseInt(PullHandleHeight)}`);

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 160)
                            .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 160)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PullHandleDistanceFromBottomOfDoor / 5) - (PullHandleHeight / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + (PullHandleDistanceFromLeadingEdgeOfDoor / 5))
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PullHandleDistanceFromBottomOfDoor / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 145)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PullHandleDistanceFromBottomOfDoor / 5));

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 140)
                            .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 140)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PullHandleDistanceFromBottomOfDoor / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 145)    // set x position
                            .attr("y", (iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PullHandleDistanceFromBottomOfDoor / 5)) / 2 + 5) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 145}, ${(iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PullHandleDistanceFromBottomOfDoor / 5)) / 2 + 5})`) // rotate
                            .text(`${parseInt(PullHandleDistanceFromBottomOfDoor)}`);
                    }
                    else {
                        svg.append("rect")
                            .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (PullHandleDistanceFromLeadingEdgeOfDoor / 5) - 6)
                            .attr('y', iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PullHandleDistanceFromBottomOfDoor / 5) - (PullHandleHeight / 5))
                            .attr("width", 6)
                            .attr("height", (PullHandleHeight / 5))
                            .attr("rx", 4)
                            .style("fill", '#D0D0C6')
                            .style("stroke", 'black')

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (PullHandleDistanceFromLeadingEdgeOfDoor / 5) - 3)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PullHandleDistanceFromBottomOfDoor / 5) - (PullHandleHeight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 215)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PullHandleDistanceFromBottomOfDoor / 5) - (PullHandleHeight / 5));

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 205 -10)    // set x position
                            .attr("y", (iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PullHandleDistanceFromBottomOfDoor / 5) - (PullHandleHeight / 5)) / 2 + 5) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 205 -10}, ${(iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PullHandleDistanceFromBottomOfDoor / 5) - (PullHandleHeight / 5)) / 2 + 5})`) // rotate
                            .text(`${parseInt(PullHandleDistanceFromBottomOfDoor) + parseInt(PullHandleHeight)}`);

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 200)
                            .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 200)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PullHandleDistanceFromBottomOfDoor / 5) - (PullHandleHeight / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (PullHandleDistanceFromLeadingEdgeOfDoor / 5) - 3)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PullHandleDistanceFromBottomOfDoor / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 185)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PullHandleDistanceFromBottomOfDoor / 5));

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 180)
                            .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 180)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PullHandleDistanceFromBottomOfDoor / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 175)    // set x position
                            .attr("y", (iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PullHandleDistanceFromBottomOfDoor / 5)) / 2 + 5) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 175}, ${(iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PullHandleDistanceFromBottomOfDoor / 5)) / 2 + 5})`) // rotate
                            .text(`${parseInt(PullHandleDistanceFromBottomOfDoor)}`);

                    }
                }
            }

            //if(DoorSetType == "SD"){
            // if (IsLockNLatchesEnable) {
            //     var DistanceOfLeverHandleFromBelow = 800 / 5;

            //     if (Handing == 'Right') {
            //         svg.append("circle")
            //             .style("stroke", "black")
            //             .style("fill", "grey")
            //             .attr("r", 5)
            //             .attr("cx", ix + FrameThicknessForMap + GapForMap + (DistanceOfIronmongeryItemsFromRightSideEndForAdjusting * 2))
            //             .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - DistanceOfLeverHandleFromBelow));

            //     } else {
            //         svg.append("circle")
            //             .style("stroke", "black")
            //             .style("fill", "grey")
            //             .attr("r", 5)
            //             .attr("cx", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (DistanceOfIronmongeryItemsFromRightSideEnd - (DistanceOfIronmongeryItemsFromRightSideEndForAdjusting * 2))))
            //             .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (LockNLatchesDistanceFromBottomOfDoor / 5)));

            //         svg.append('line')
            //             .style("stroke", "black")
            //             .style("stroke-width", 0.5)
            //             .attr("x1", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (DistanceOfIronmongeryItemsFromRightSideEnd - (DistanceOfIronmongeryItemsFromRightSideEndForAdjusting * 2))))
            //             .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (LockNLatchesDistanceFromBottomOfDoor / 5)))
            //             .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + 255)
            //             .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (LockNLatchesDistanceFromBottomOfDoor / 5)))


            //         svg.append('line')
            //             .style("stroke", "black")
            //             .style("stroke-width", 0.5)
            //             .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + 250)
            //             .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (LockNLatchesDistanceFromBottomOfDoor / 5)))
            //             .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + 250)
            //             .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
            //             .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
            //             .attr("marker-end", "url(#arrowRight)")

            //         svg.append("text")            // append text
            //             .style("fill", "black")   // make the text color black
            //             .attr("font-size", 10)    // set font size
            //             .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + 245) // set x position
            //             .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (LockNLatchesDistanceFromBottomOfDoor / 10))) // set y position
            //             .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + 245}, ${iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (LockNLatchesDistanceFromBottomOfDoor / 10))})`) // rotate 45 degrees around the x and y
            //             .text(LockNLatchesDistanceFromBottomOfDoor);

            //     }
            // }
            // if (IsCylindersEnable) {
            //     var DistanceOfLeverHandleFromBelow = 800 / 5;

            //     if (Handing == 'Right') {
            //         svg.append("circle")
            //             .style("stroke", "black")
            //             .style("fill", "grey")
            //             .attr("r", 5)
            //             .attr("cx", ix + FrameThicknessForMap + GapForMap + (DistanceOfIronmongeryItemsFromRightSideEndForAdjusting * 2))
            //             .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - DistanceOfLeverHandleFromBelow));

            //     } else {
            //         svg.append("circle")
            //             .style("stroke", "black")
            //             .style("fill", "grey")
            //             .attr("r", 5)
            //             .attr("cx", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (DistanceOfIronmongeryItemsFromRightSideEnd - (DistanceOfIronmongeryItemsFromRightSideEndForAdjusting * 2))))
            //             .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (CylindersDistanceFromBottomOfDoor / 5)));

            //         svg.append('line')
            //             .style("stroke", "black")
            //             .style("stroke-width", 0.5)
            //             .attr("x1", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (DistanceOfIronmongeryItemsFromRightSideEnd - (DistanceOfIronmongeryItemsFromRightSideEndForAdjusting * 2))))
            //             .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (CylindersDistanceFromBottomOfDoor / 5)))
            //             .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + 255)
            //             .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (CylindersDistanceFromBottomOfDoor / 5)))


            //         svg.append('line')
            //             .style("stroke", "black")
            //             .style("stroke-width", 0.5)
            //             .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + 250)
            //             .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (CylindersDistanceFromBottomOfDoor / 5)))
            //             .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + 250)
            //             .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
            //             .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
            //             .attr("marker-end", "url(#arrowRight)")

            //         svg.append("text")            // append text
            //             .style("fill", "black")   // make the text color black
            //             .attr("font-size", 10)    // set font size
            //             .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + 245) // set x position
            //             .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (CylindersDistanceFromBottomOfDoor / 10))) // set y position
            //             .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + 245}, ${iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (CylindersDistanceFromBottomOfDoor / 10))})`) // rotate 45 degrees around the x and y
            //             .text(CylindersDistanceFromBottomOfDoor);

            //     }
            // }
            // if (IsThumbturnEnable) {
            //     var DistanceOfLeverHandleFromBelow = 800 / 5;

            //     if (Handing == 'Right') {
            //         svg.append("circle")
            //             .style("stroke", "black")
            //             .style("fill", "grey")
            //             .attr("r", 5)
            //             .attr("cx", ix + FrameThicknessForMap + GapForMap + (DistanceOfIronmongeryItemsFromRightSideEndForAdjusting * 2))
            //             .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - DistanceOfLeverHandleFromBelow));

            //     } else {
            //         svg.append("circle")
            //             .style("stroke", "black")
            //             .style("fill", "grey")
            //             .attr("r", 5)
            //             .attr("cx", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (DistanceOfIronmongeryItemsFromRightSideEnd - (DistanceOfIronmongeryItemsFromRightSideEndForAdjusting * 2))))
            //             .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (ThumbturnDistanceFromBottomOfDoor / 5)));

            //         svg.append('line')
            //             .style("stroke", "black")
            //             .style("stroke-width", 0.5)
            //             .attr("x1", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (DistanceOfIronmongeryItemsFromRightSideEnd - (DistanceOfIronmongeryItemsFromRightSideEndForAdjusting * 2))))
            //             .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (ThumbturnDistanceFromBottomOfDoor / 5)))
            //             .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + 255)
            //             .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (ThumbturnDistanceFromBottomOfDoor / 5)))


            //         svg.append('line')
            //             .style("stroke", "black")
            //             .style("stroke-width", 0.5)
            //             .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + 250)
            //             .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (ThumbturnDistanceFromBottomOfDoor / 5)))
            //             .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + 250)
            //             .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
            //             .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
            //             .attr("marker-end", "url(#arrowRight)")

            //         svg.append("text")            // append text
            //             .style("fill", "black")   // make the text color black
            //             .attr("font-size", 10)    // set font size
            //             .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + 245) // set x position
            //             .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (ThumbturnDistanceFromBottomOfDoor / 10))) // set y position
            //             .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + 245}, ${iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (ThumbturnDistanceFromBottomOfDoor / 10))})`) // rotate 45 degrees around the x and y
            //             .text(ThumbturnDistanceFromBottomOfDoor);

            //     }
            // }
            if(DoorSetType == "SD"){
                // Calculate distances
                const lockDistance = LockNLatchesDistanceFromBottomOfDoor / 5;
                const cylinderDistance = CylindersDistanceFromBottomOfDoor / 5;
                const thumbturnDistance = ThumbturnDistanceFromBottomOfDoor / 5;
                const lockDistanceFromEdge = LockNLatchesDistanceFromLeadingEdgeOfDoor / 5;
                const cylinderDistanceFromEdge = CylindersDistanceFromLeadingEdgeOfDoor / 5;
                const thumbturnDistanceFromEdge =  ThumbturnDistanceFromLeadingEdgeOfDoor/ 5;

                // Collect distances in an array
                const distances = [lockDistance, cylinderDistance, thumbturnDistance];
            const distanceFromEdge=[lockDistanceFromEdge,cylinderDistanceFromEdge,thumbturnDistanceFromEdge]
                // Use a Set to find unique distances
                const uniqueDistances = Array.from(new Set(distances)).filter(num => num !== 0);

                const uniqueDistancesFromEdge=Array.from(new Set(distanceFromEdge)).filter(num => num !== 0);

                function conditionalRenderItem(distance, label, distanceFromEdge, textdistanceRightHinge, linedistanceRightHinge,textdistanceLeftHinge,linedistanceLeftHinge) {
                    // console.log(distanceFromEdge,distance,'lllllllllllllllllllllllllllllllll')
                    if (distance !== 0) { // Check if the distance is not zero
                        renderItem(distance, label,distanceFromEdge,textdistanceRightHinge,linedistanceRightHinge,textdistanceLeftHinge,linedistanceLeftHinge);
                    }
                }

                function renderItem(distance, label, edgeDistance,textdistanceRightHinge, linedistanceRightHinge,textdistanceLeftHinge,linedistanceLeftHinge) {
                    if (Handing == 'Right') {
                        svg.append("circle")
                            .style("stroke", "black")
                            .style("fill", "grey")
                            .attr("r", 5)
                            .attr("cx", ix + FrameThicknessForMap + GapForMap + edgeDistance+2.5)
                            .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - distance));
                            // ix + FrameThicknessForMap + GapForMap + (LeverHandleDistanceFromLeadingEdgeOfDoor / 5) + 3.5
                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + edgeDistance)
                            .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - distance))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap - textdistanceRightHinge)
                            .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - distance));

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap - linedistanceRightHinge)
                            .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - distance))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap - linedistanceRightHinge)
                            .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("marker-start", "url(#arrowLeft)")
                            .attr("marker-end", "url(#arrowRight)");

                        svg.append("text")
                            .style("fill", "black")
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap - textdistanceRightHinge)
                            .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distance / 2)))
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap - textdistanceRightHinge}, ${iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distance / 2))})`)
                            .text(label);
                    } else {
                        // console.log(FrameThicknessForMap,GapForMap,LeafWidth1ForMap,edgeDistance)
                        svg.append("circle")
                            .style("stroke", "black")
                            .style("fill", "grey")
                            .attr("r", 5)
                            .attr("cx", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - edgeDistance)-2.5)
                            .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - distance));
                            // ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (LeverHandleDistanceFromLeadingEdgeOfDoor / 5) - 3.5)
                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - edgeDistance))
                            .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - distance))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + textdistanceLeftHinge+10)
                            .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - distance));

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + linedistanceLeftHinge)
                            .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - distance))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + linedistanceLeftHinge)
                            .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("marker-start", "url(#arrowLeft)")
                            .attr("marker-end", "url(#arrowRight)");

                        svg.append("text")
                            .style("fill", "black")
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + textdistanceLeftHinge)
                            .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distance / 2)))
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + textdistanceLeftHinge}, ${iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distance / 2))})`)
                            .text(label);
                    }
                }

                // Logic to render based on unique distances
                if (uniqueDistances.length === 1) {
                    if(uniqueDistancesFromEdge.length ===1){

                        conditionalRenderItem(uniqueDistances[0], uniqueDistances[0] * 5, uniqueDistancesFromEdge[0],215, 210,245,250);
                    }else{

                    conditionalRenderItem(uniqueDistances[0], uniqueDistances[0] * 5, 0,215, 210,245,250);
                    }
                } else if (uniqueDistances.length === 2) {
                    // Two distances are the same; show one item for each unique distance
                    console.log(uniqueDistancesFromEdge.length,'llllllllllllllllllllllll')
                    if(uniqueDistancesFromEdge.length ===1){

                        conditionalRenderItem(uniqueDistances[0], uniqueDistances[0] * 5, uniqueDistancesFromEdge[0],215, 210,245,250);
                        conditionalRenderItem(uniqueDistances[1], uniqueDistances[1] * 5, uniqueDistancesFromEdge[0],235, 230,265,270);
                    }else if(uniqueDistancesFromEdge.length ===2){
                        conditionalRenderItem(uniqueDistances[0], uniqueDistances[0] * 5, uniqueDistancesFromEdge[0],215, 210,245,250);
                        conditionalRenderItem(uniqueDistances[1], uniqueDistances[1] * 5, uniqueDistancesFromEdge[1],235, 230,265,270);
                    }else{
                        conditionalRenderItem(uniqueDistances[0], uniqueDistances[0] * 5, 0,215, 210,245,250);
                        conditionalRenderItem(uniqueDistances[1], uniqueDistances[1] * 5, 0,235, 230,265,270);
                    }

                } else {
                    if(uniqueDistancesFromEdge.length ===1){
                        // All distances are different; show all three items
                    conditionalRenderItem(lockDistance, lockDistance * 5, uniqueDistancesFromEdge[0],215, 210,245,250);
                    conditionalRenderItem(cylinderDistance, cylinderDistance * 5, uniqueDistancesFromEdge[0],235, 230,265,270);
                    conditionalRenderItem(thumbturnDistance, thumbturnDistance * 5, uniqueDistancesFromEdge[0], 255, 250,285,290);
                     }else if(uniqueDistancesFromEdge.length ===2){
            // All distances are different; show all three items
            conditionalRenderItem(lockDistance, lockDistance * 5, uniqueDistancesFromEdge[0],215, 210,245,250);
            conditionalRenderItem(cylinderDistance, cylinderDistance * 5, uniqueDistancesFromEdge[1],235, 230,265,270);
            const matchingIndices = [];
            const seen = new Map();
                distanceFromEdge.forEach((value, index) => {
                    if (value !== 0) {
                        if (seen.has(value)) {
                            matchingIndices.push([seen.get(value), index]);
                        } else {
                            seen.set(value, index);
                        }
                    }
                });
            conditionalRenderItem(thumbturnDistance, thumbturnDistance * 5, uniqueDistancesFromEdge[matchingIndices[0][1]], 255, 250,285,290);

                     }  else if(uniqueDistancesFromEdge.length ===3){
            // All distances are different; show all three items
            conditionalRenderItem(lockDistance, lockDistance * 5, uniqueDistancesFromEdge[0],215, 210,245,250);
            conditionalRenderItem(cylinderDistance, cylinderDistance * 5, uniqueDistancesFromEdge[1],235, 230,265,270);
            conditionalRenderItem(thumbturnDistance, thumbturnDistance * 5, uniqueDistancesFromEdge[2], 255, 250,285,290);
                     }else{
            // All distances are different; show all three items
            conditionalRenderItem(lockDistance, lockDistance * 5, 0,215, 210,245,250);
            conditionalRenderItem(cylinderDistance, cylinderDistance * 5, 0,235, 230,265,270);
            conditionalRenderItem(thumbturnDistance, thumbturnDistance * 5, 0, 255, 250,285,290);
                     }

                }
            }




            if (IsLetterPlatesEnable && DoorSetType == "SD") {

                if (LetterplatesCentered == 1) {
                    svg.append('rect')
                        .attr('x', ix + (FrameWidthForMap / 2) - ((LetterplatesWidth / 5) / 2))
                        .attr('y', iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - (LetterplatesHeight / 5))
                        .attr('width', (LetterplatesWidth / 5))
                        .attr('height', (LetterplatesHeight / 5))
                        .attr('stroke', 'black')

                        .attr('fill', '#D0D0C6')

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + (FrameWidthForMap / 2) - ((LetterplatesWidth / 5) / 2))
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 5 - (LetterplatesHeight / 5))
                        .attr("x2", ix + (FrameWidthForMap / 2) - ((LetterplatesWidth / 5) / 2) + (LetterplatesWidth / 5))
                        .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 5 - (LetterplatesHeight / 5))
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + (FrameWidthForMap / 2) - ((LetterplatesWidth / 5) / 2))
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - (LetterplatesHeight / 5))
                        .attr("x2", ix + (FrameWidthForMap / 2) - ((LetterplatesWidth / 5) / 2))
                        .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 25 - (LetterplatesHeight / 5))

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + (FrameWidthForMap / 2) - ((LetterplatesWidth / 5) / 2) + (LetterplatesWidth / 5))
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - (LetterplatesHeight / 5))
                        .attr("x2", ix + (FrameWidthForMap / 2) - ((LetterplatesWidth / 5) / 2) + (LetterplatesWidth / 5))
                        .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 25 - (LetterplatesHeight / 5))

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + (FrameWidthForMap / 2) - ((LetterplatesWidth / 5) / 2))
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 20 - (LetterplatesHeight / 5))
                        .attr("x2", ix + GapForMap + FrameThicknessForMap)
                        .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 20 - (LetterplatesHeight / 5))
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", (ix + (FrameWidthForMap / 2) - ((LetterplatesWidth / 5) / 2) + ix) / 2) // set x position
                        .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 25 - (LetterplatesHeight / 5)) // set y position
                        .text('eq');

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + (FrameWidthForMap / 2) - ((LetterplatesWidth / 5) / 2) + (LetterplatesWidth / 5))
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 20 - (LetterplatesHeight / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                        .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 20 - (LetterplatesHeight / 5))
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", (ix + (FrameWidthForMap / 2) - ((LetterplatesWidth / 5) / 2) + (LetterplatesWidth / 5) + ix + FrameWidthForMap) / 2) // set x position
                        .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 25 - (LetterplatesHeight / 5)) // set y position
                        .text('eq');

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text green
                        .attr("font-size", 10)
                        .attr("x", ix + (FrameWidthForMap / 2) - ((LetterplatesWidth / 5) / 2) + ((LetterplatesWidth / 5) / 2) - 5) // set x position
                        .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 10 - (LetterplatesHeight / 5))        // set y position
                        .text(LetterplatesWidth);        // define the text to display

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + (FrameWidthForMap / 2) - ((LetterplatesWidth / 5) / 2) - 5)
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - (LetterplatesHeight / 5))
                        .attr("x2", ix + (FrameWidthForMap / 2) - ((LetterplatesWidth / 5) / 2) - 5)
                        .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) + (LetterplatesHeight / 5) - (LetterplatesHeight / 5))
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", ix + (FrameWidthForMap / 2) - ((LetterplatesWidth / 5) / 2) - 15) // set x position
                        .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) + ((LetterplatesHeight / 5) / 2) - (LetterplatesHeight / 5)) // set y position
                        .attr("transform", `rotate(-90, ${ix + (FrameWidthForMap / 2) - ((LetterplatesWidth / 5) / 2) - 10}, ${iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - (LetterplatesHeight / 5) + ((LetterplatesHeight / 5) / 2)})`) // rotate around (x, y)
                        .text(LetterplatesHeight);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + (FrameWidthForMap / 2) - ((LetterplatesWidth / 5) / 2) + (LetterplatesWidth / 5))
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                        .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5))

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - 10)
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - 10)
                        .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - 15) // set x position
                        .attr("y", (iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2) // set y position
                        .attr("transform", `rotate(-90, ${ix + FrameWidthForMap - 15}, ${(iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2})`) // rotate
                        .text(LetterplatesDistanceFromBottomOfDoor);

                } else {
                    if (Handing == 'Right') {
                        svg.append('rect')
                            .attr('x', ix + FrameThicknessForMap + (LetterplatesDistanceFromLeadingEdgeOfDoor / 5))
                            .attr('y', iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - (LetterplatesHeight / 5))
                            .attr('width', (LetterplatesWidth / 5))
                            .attr('height', (LetterplatesHeight / 5))
                            .attr('stroke', 'black')

                            .attr('fill', '#D0D0C6')

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + (LetterplatesDistanceFromLeadingEdgeOfDoor / 5))
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 5 - (LetterplatesHeight / 5))
                            .attr("x2", ix + FrameThicknessForMap + (LetterplatesDistanceFromLeadingEdgeOfDoor / 5) + (LetterplatesWidth / 5))
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 5 - (LetterplatesHeight / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + (LetterplatesDistanceFromLeadingEdgeOfDoor / 5))
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - (LetterplatesHeight / 5))
                            .attr("x2", ix + FrameThicknessForMap + (LetterplatesDistanceFromLeadingEdgeOfDoor / 5))
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 10 - (LetterplatesHeight / 5))

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + (LetterplatesDistanceFromLeadingEdgeOfDoor / 5) + (LetterplatesWidth / 5))
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - (LetterplatesHeight / 5))
                            .attr("x2", ix + FrameThicknessForMap + (LetterplatesDistanceFromLeadingEdgeOfDoor / 5) + (LetterplatesWidth / 5))
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 10 - (LetterplatesHeight / 5))

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text green
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + (LetterplatesDistanceFromLeadingEdgeOfDoor / 5) + ((LetterplatesWidth / 5) / 2) - 5) // set x position
                            .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 10 - (LetterplatesHeight / 5))        // set y position
                            .text(LetterplatesWidth);        // define the text to display

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + (LetterplatesDistanceFromLeadingEdgeOfDoor / 5) + (LetterplatesWidth / 5) + 5)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - (LetterplatesHeight / 5))
                            .attr("x2", ix + FrameThicknessForMap + (LetterplatesDistanceFromLeadingEdgeOfDoor / 5) + (LetterplatesWidth / 5) + 5)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) + (LetterplatesHeight / 5) - (LetterplatesHeight / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + (LetterplatesDistanceFromLeadingEdgeOfDoor / 5) + (LetterplatesHeight / 5) + 20) // set x position
                            .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) + ((LetterplatesHeight / 5) / 2) - (LetterplatesHeight / 5)) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + (LetterplatesDistanceFromLeadingEdgeOfDoor / 5) + (LetterplatesHeight / 5) + 20}, ${iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) + ((LetterplatesHeight / 5) / 2) - (LetterplatesHeight / 5)}`) // rotate around (x, y)
                            .text(LetterplatesHeight);

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + (LetterplatesDistanceFromLeadingEdgeOfDoor / 5))
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5))
                            .attr("x2", ix + FrameThicknessForMap)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + (LetterplatesDistanceFromLeadingEdgeOfDoor / 10) - 5) // set x position
                            .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 5) // set y position
                            .text(LetterplatesDistanceFromLeadingEdgeOfDoor);

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + 10)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5))
                            .attr("x2", ix + FrameThicknessForMap + 10)
                            .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")
                            .style("fill", "black")
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + 20)
                            .attr("y", (iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + 20}, ${(iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2})`) // rotate
                            .text(LetterplatesDistanceFromBottomOfDoor);
                    } else {
                        svg.append('rect')
                            .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (LetterplatesDistanceFromLeadingEdgeOfDoor / 5) - (LetterplatesHeight / 5))
                            .attr('y', iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - (LetterplatesHeight / 5))
                            .attr('width', (LetterplatesWidth / 5))
                            .attr('height', (LetterplatesHeight / 5))
                            .attr('stroke', 'black')

                            .attr('fill', '#D0D0C6')

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (LetterplatesDistanceFromLeadingEdgeOfDoor / 5) - (LetterplatesHeight / 5))
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 5 - (LetterplatesHeight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (LetterplatesDistanceFromLeadingEdgeOfDoor / 5) - (LetterplatesHeight / 5) + (LetterplatesWidth / 5))
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 5 - (LetterplatesHeight / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (LetterplatesDistanceFromLeadingEdgeOfDoor / 5) - (LetterplatesHeight / 5))
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - (LetterplatesHeight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (LetterplatesDistanceFromLeadingEdgeOfDoor / 5) - (LetterplatesHeight / 5))
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 10 - (LetterplatesHeight / 5))

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (LetterplatesDistanceFromLeadingEdgeOfDoor / 5) - (LetterplatesHeight / 5) + (LetterplatesWidth / 5))
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - (LetterplatesHeight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (LetterplatesDistanceFromLeadingEdgeOfDoor / 5) - (LetterplatesHeight / 5) + (LetterplatesWidth / 5))
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 10 - (LetterplatesHeight / 5))

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text green
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (LetterplatesDistanceFromLeadingEdgeOfDoor / 5) - (LetterplatesHeight / 5) + ((LetterplatesWidth / 5) / 2) - 5) // set x position
                            .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 10 - (LetterplatesHeight / 5))        // set y position
                            .text(LetterplatesWidth);        // define the text to display

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (LetterplatesDistanceFromLeadingEdgeOfDoor / 5) - (LetterplatesHeight / 5) - 5)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - (LetterplatesHeight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (LetterplatesDistanceFromLeadingEdgeOfDoor / 5) - (LetterplatesHeight / 5) - 5)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) + (LetterplatesHeight / 5) - (LetterplatesHeight / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (LetterplatesDistanceFromLeadingEdgeOfDoor / 5) - (LetterplatesHeight / 5) - 15) // set x position
                            .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) + ((LetterplatesHeight / 5) / 2) - (LetterplatesHeight / 5)) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (LetterplatesDistanceFromLeadingEdgeOfDoor / 5) - (LetterplatesHeight / 5) - 10}, ${iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - (LetterplatesHeight / 5) + ((LetterplatesHeight / 5) / 2)})`) // rotate around (x, y)
                            .text(LetterplatesHeight);

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (LetterplatesDistanceFromLeadingEdgeOfDoor / 5) - (LetterplatesHeight / 5) + (LetterplatesWidth / 5))
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", (ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (LetterplatesDistanceFromLeadingEdgeOfDoor / 5) - (LetterplatesHeight / 5) + (LetterplatesWidth / 5) + ix + FrameWidthForMap) / 2 - 5) // set x position
                            .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) - 5) // set y position
                            .text(LetterplatesDistanceFromLeadingEdgeOfDoor);


                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - 10)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - 10)
                            .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - 15) // set x position
                            .attr("y", (iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - 15}, ${(iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2})`) // rotate
                            .text(LetterplatesDistanceFromBottomOfDoor);
                    }

                }


            }

            if (IsFlushBoltsEnable && DoorSetType == "SD") {
                if (Handing == 'Right') {

                    svg.append('rect')
                        .attr('x', ix + FrameThicknessForMap + GapForMap + 1)
                        .attr('y', iy + TopFrameHeight + GapForMap + 1)
                        .attr('width', (FlushBoltsWidth / 5))
                        .attr('height', (FlushBoltsHeight / 5))
                        .attr('stroke', 'none')
                        .attr('fill', '#D0D0C6')

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + (FlushBoltsWidth / 5) + 5)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 1)
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + (FlushBoltsWidth / 5) + 5)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + (FlushBoltsWidth / 5) + 5 + 5 + 1)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + (FlushBoltsWidth / 5) + 1)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))


                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", ix + FrameThicknessForMap + GapForMap + (FlushBoltsWidth / 5) + 20)    // set x position
                        .attr("y", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 10) + 5) // set y position
                        .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + (FlushBoltsWidth / 5) + 20}, ${iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 10) + 5})`) // rotate
                        .text(FlushBoltsHeight);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + 1)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5) + 5)
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + (FlushBoltsWidth / 5) + 1)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5) + 5)
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + (FlushBoltsWidth / 5) + 1)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + (FlushBoltsWidth / 5) + 1)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5) + 10);

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", ix + FrameThicknessForMap + GapForMap + (FlushBoltsWidth / 10) + 1)    // set x position
                        .attr("y", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5) + 15) // set y position
                        .text(FlushBoltsWidth);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix - SideLightPanel1WidthSpaceForVerticalLines - 150)
                        .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                        .attr("x2", ix - SideLightPanel2WidthSpaceForVerticalLines - 150)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix - SideLightPanel2WidthSpaceForVerticalLines - 155)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + 1)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5));

                    svg.append("text")            // append text
                        .style("fill", "black")   // set text color
                        .attr("font-size", 10)    // set font size
                        .attr("x", ix - SideLightPanel2WidthSpaceForVerticalLines - 155) // set x position
                        .attr("y", iy + TopFrameHeight + GapForMap + ((LeafHeightNoOPForMap + (FlushBoltsHeight / 5)) / 2)) // set y position
                        .attr("transform", `rotate(-90, ${ix - SideLightPanel2WidthSpaceForVerticalLines - 155}, ${iy + TopFrameHeight + GapForMap + ((LeafHeightNoOPForMap + (FlushBoltsHeight / 5)) / 2)})`) // apply rotation
                        .text(FrameHeight - FrameThickness - Gap - FlushBoltsHeight);
                } else {

                    svg.append('rect')
                        .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 1)
                        .attr('y', iy + TopFrameHeight + GapForMap + 1)
                        .attr('width', (FlushBoltsWidth / 5))
                        .attr('height', (FlushBoltsHeight / 5))
                        .attr('stroke', 'none')
                        .attr('fill', '#D0D0C6')

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 5)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 1)
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 5)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 5 - 5 - 1)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 1)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))


                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 10)    // set x position
                        .attr("y", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 10) + 5) // set y position
                        .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 10}, ${iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 10) + 5})`) // rotate
                        .text(FlushBoltsHeight);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 1 + (FlushBoltsWidth / 5))
                        .attr("y1", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5) + 5)
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 1)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5) + 5)
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 1)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 1)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5) + 10);

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 10) - 1 - 5)    // set x position
                        .attr("y", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5) + 15) // set y position
                        .text(FlushBoltsWidth);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 190)
                        .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                        .attr("x2", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 190)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 195)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 1 + (FlushBoltsWidth / 5))
                        .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5));

                    svg.append("text")            // append text
                        .style("fill", "black")   // set text color
                        .attr("font-size", 10)    // set font size
                        .attr("x", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 185) // set x position
                        .attr("y", iy + TopFrameHeight + GapForMap + ((LeafHeightNoOPForMap + (FlushBoltsHeight / 5)) / 2)) // set y position
                        .attr("transform", `rotate(-90, ${ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 185}, ${iy + TopFrameHeight + GapForMap + ((LeafHeightNoOPForMap + (FlushBoltsHeight / 5)) / 2)})`) // apply rotation
                        .text(FrameHeight - FrameThickness - Gap - FlushBoltsHeight);
                }


            }

            if (IsConcealedOverheadCloserEnable && DoorSetType == "SD") {
                if (Handing == 'Right') {
                    svg.append('rect')
                        .attr('x', ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5))
                        .attr('y', iy + TopFrameHeight + GapForMap + 10)
                        .attr('width', (ConcealedOverheadCloserWidth / 5))
                        .attr('height', (ConcealedOverheadCloserHeight / 5))
                        .attr('stroke', 'black')
                        .attr('fill', '#D0D0C6')

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameWidthForMap - GapForMap - 20 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5))
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10)
                        .attr("x2", ix + FrameWidthForMap - GapForMap - 20 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5))
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5))
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameWidthForMap - GapForMap - 25 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5))
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10)
                        .attr("x2", ix + FrameWidthForMap - GapForMap + 10 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5))
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10)

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameWidthForMap - GapForMap - 25 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5))
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5))
                        .attr("x2", ix + FrameWidthForMap - GapForMap + 10 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5))
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5))


                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", ix + FrameWidthForMap - GapForMap - 30 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5))    // set x position
                        .attr("y", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 10) + 5) // set y position
                        .attr("transform", `rotate(-90, ${ix + FrameWidthForMap - GapForMap - 30 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5)}, ${iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 10) + 5})`) // rotate
                        .text(ConcealedOverheadCloserHeight);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5))
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5) + 5)
                        .attr("x2", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5) + 5)
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5))
                        .attr("x2", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5) + 10);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5))
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5))
                        .attr("x2", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5))
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5) + 10);

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 10) - 5)    // set x position
                        .attr("y", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5) + 15) // set y position
                        .text(ConcealedOverheadCloserWidth);

                }
                else {
                    svg.append('rect')
                        .attr('x', ix + FrameThicknessForMap + GapForMap + 10)
                        .attr('y', iy + TopFrameHeight + GapForMap + 10)
                        .attr('width', (ConcealedOverheadCloserWidth / 5))
                        .attr('height', (ConcealedOverheadCloserHeight / 5))
                        .attr('stroke', 'black')
                        .attr('fill', '#D0D0C6')

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + 10 + (ConcealedOverheadCloserWidth / 5) + 10)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10)
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + 10 + (ConcealedOverheadCloserWidth / 5) + 10)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5))
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + (ConcealedOverheadCloserWidth / 5) + 10)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10)
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + 10 + (ConcealedOverheadCloserWidth / 5) + 10 + 5)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10)

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + (ConcealedOverheadCloserWidth / 5) + 10)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + 10 + (ConcealedOverheadCloserWidth / 5) + 10 + 5)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5))


                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", ix + FrameThicknessForMap + GapForMap + (ConcealedOverheadCloserWidth / 5) + 30)    // set x position
                        .attr("y", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 10) + 5) // set y position
                        .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + (ConcealedOverheadCloserWidth / 5) + 30}, ${iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 10) + 5})`) // rotate
                        .text(ConcealedOverheadCloserHeight);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + 10)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5) + 5)
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + 10 + (ConcealedOverheadCloserWidth / 5))
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5) + 5)
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + 10)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + 10)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5) + 10);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + 10 + (ConcealedOverheadCloserWidth / 5))
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + 10 + (ConcealedOverheadCloserWidth / 5))
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5) + 10);

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", ix + FrameThicknessForMap + GapForMap + 10 + (ConcealedOverheadCloserWidth / 10) - 5)    // set x position
                        .attr("y", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5) + 15) // set y position
                        .text(ConcealedOverheadCloserWidth);

                }
            }

            if (IsFaceFixedDoorClosersEnable && DoorSetType == "SD") {

                if (Handing == 'Right') {
                    svg.append('rect')
                        .attr('x', ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5))
                        .attr('y', iy + TopFrameHeight + GapForMap + 10)
                        .attr('width', (FaceFixedDoorClosersWidth / 5))
                        .attr('height', (FaceFixedDoorCloserDataHeight / 5))
                        .attr('stroke', 'black')
                        .attr('fill', '#D0D0C6')

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameWidthForMap - GapForMap - 20 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5))
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10)
                        .attr("x2", ix + FrameWidthForMap - GapForMap - 20 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5))
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5))
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameWidthForMap - GapForMap - 25 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5))
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10)
                        .attr("x2", ix + FrameWidthForMap - GapForMap + 10 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5))
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10)

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameWidthForMap - GapForMap - 25 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5))
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5))
                        .attr("x2", ix + FrameWidthForMap - GapForMap + 10 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5))
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5))


                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", ix + FrameWidthForMap - GapForMap - 30 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5))    // set x position
                        .attr("y", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 10) + 5) // set y position
                        .attr("transform", `rotate(-90, ${ix + FrameWidthForMap - GapForMap - 30 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5)}, ${iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 10) + 5})`) // rotate
                        .text(FaceFixedDoorCloserDataHeight);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5))
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5) + 5)
                        .attr("x2", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5) + 5)
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5))
                        .attr("x2", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5) + 10);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5))
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5))
                        .attr("x2", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5))
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5) + 10);

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 10) - 5)    // set x position
                        .attr("y", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5) + 15) // set y position
                        .text(FaceFixedDoorClosersWidth);

                } else {
                    svg.append('rect')
                        .attr('x', ix + FrameThicknessForMap + GapForMap + 10)
                        .attr('y', iy + TopFrameHeight + GapForMap + 10)
                        .attr('width', (FaceFixedDoorClosersWidth / 5))
                        .attr('height', (FaceFixedDoorCloserDataHeight / 5))
                        .attr('stroke', 'black')
                        .attr('fill', '#D0D0C6')

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + 10 + (FaceFixedDoorClosersWidth / 5) + 10)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10)
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + 10 + (FaceFixedDoorClosersWidth / 5) + 10)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5))
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + (FaceFixedDoorClosersWidth / 5) + 10)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10)
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + 10 + (FaceFixedDoorClosersWidth / 5) + 10 + 5)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10)

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + (FaceFixedDoorClosersWidth / 5) + 10)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + 10 + (FaceFixedDoorClosersWidth / 5) + 10 + 5)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5))


                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", ix + FrameThicknessForMap + GapForMap + (FaceFixedDoorClosersWidth / 5) + 30)    // set x position
                        .attr("y", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 10) + 5) // set y position
                        .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + (FaceFixedDoorClosersWidth / 5) + 30}, ${iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 10) + 5})`) // rotate
                        .text(FaceFixedDoorCloserDataHeight);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + 10)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5) + 5)
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + 10 + (FaceFixedDoorClosersWidth / 5))
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5) + 5)
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + 10)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + 10)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5) + 10);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + 10 + (FaceFixedDoorClosersWidth / 5))
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + 10 + (FaceFixedDoorClosersWidth / 5))
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5) + 10);

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", ix + FrameThicknessForMap + GapForMap + 10 + (FaceFixedDoorClosersWidth / 10) - 5)    // set x position
                        .attr("y", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5) + 19) // set y position
                        .text(FaceFixedDoorClosersWidth);
                }
            }

            if ((IsMorticedDropdownSealsEnable && DoorSetType == "SD")||(IsFacefixeddropsealsEnable && DoorSetType == "SD")) {


                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .style("stroke-dasharray", "4, 2")
                    .attr("x1", ix + FrameThicknessForMap + GapForMap)
                    .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap - 10)
                    .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                    .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap - 10)
            }



            var DistanceOfDoorSinageFromTop = 1800 / 5;

         function drawDoorSignage(distanceFromEdge, isCentered, distanceFromBottom,rightDistance,leftdistance,centerdistances) {
        if ( DoorSetType == "SD") {
          if (LeafWidth1ForMap > 32) {

              if (isCentered == 1) {

                if (Handing == 'Right'){
svg.append("circle")
                      .style("stroke", "black")
                      .style("fill", "#2C3360")
                      .attr("r", 7)
                      .attr("cx", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                      .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))

                                        svg.append('line')
                          .style("stroke", "black")
                          .style("stroke-width", 0.5)
                          .attr("x1",  ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                          .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                          .attr("x2", ix + FrameThicknessForMap + GapForMap - SideLightPanel2WidthSpaceForVerticalLines - leftdistance)
                          .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)));

                      svg.append('line')
                          .style("stroke", "black")
                          .style("stroke-width", 0.5)
                          .attr("x1", ix + FrameThicknessForMap + GapForMap - SideLightPanel2WidthSpaceForVerticalLines - leftdistance)
                          .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                          .attr("x2", ix + FrameThicknessForMap + GapForMap - SideLightPanel2WidthSpaceForVerticalLines - leftdistance)
                          .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                          .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                          .attr("marker-end", "url(#arrowRight)")

                      svg.append("text")            // append text
                          .style("fill", "black")   // make the text
                          .attr("font-size", 10)
                          .attr("x", ix + FrameThicknessForMap + GapForMap - SideLightPanel2WidthSpaceForVerticalLines - leftdistance-5)    // set x position
                          .attr("y", (iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5))) / 2 + 5) // set y position
                          .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap - SideLightPanel2WidthSpaceForVerticalLines - leftdistance-5}, ${((iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5))) / 2 + 5)})`) // rotate
                          .text(distanceFromBottom);

                  svg.append('line')
                      .style("stroke", "black")
                      .style("stroke-width", 0.5)
                      .attr("x1", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                      .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                      .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                      .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                      .attr("marker-start", "url(#arrowLeft)")
                      .attr("marker-end", "url(#arrowRight)")

                  svg.append('line')
                      .style("stroke", "black")
                      .style("stroke-width", 0.5)
                      .attr("x1", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                      .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                      .attr("x2", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                      .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 25)

                  svg.append("text")            // append text
                      .style("fill", "black")   // make the text
                      .attr("font-size", 10)
                      .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 4)))    // set x position
                      .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 25) // set y position
                      .text("eq");
                  svg.append('line')
                      .style("stroke", "black")
                      .style("stroke-width", 0.5)
                      .attr("x1", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                      .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                      .attr("x2", ix + FrameThicknessForMap + GapForMap)
                      .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                      .attr("marker-start", "url(#arrowLeft)")
                      .attr("marker-end", "url(#arrowRight)")

                  svg.append('line')
                      .style("stroke", "black")
                      .style("stroke-width", 0.5)
                      .attr("x1", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                      .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                      .attr("x2", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                      .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 25)

                  svg.append("text")            // append text
                      .style("fill", "black")   // make the text
                      .attr("font-size", 10)
                      .attr("x", ix - FrameThicknessForMap - GapForMap + ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 4)))    // set x position
                      .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 25) // set y position
                      .text("eq");
                }else{
                  svg.append("circle")
                      .style("stroke", "black")
                      .style("fill", "#2C3360")
                      .attr("r", 7)
                      .attr("cx", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                      .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))

                  svg.append('line')
                      .style("stroke", "black")
                      .style("stroke-width", 0.5)
                      .attr("x1", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                      .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                      .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + rightDistance)
                      .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)));

                  svg.append('line')
                      .style("stroke", "black")
                      .style("stroke-width", 0.5)
                      .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + rightDistance)
                      .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                      .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + rightDistance)
                      .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                      .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                      .attr("marker-end", "url(#arrowRight)")

                  svg.append("text")            // append text
                      .style("fill", "black")   // make the text
                      .attr("font-size", 10)
                      .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + rightDistance-5)    // set x position
                      .attr("y", (iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5))) / 2 + 5) // set y position
                      .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + rightDistance-5}, ${((iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5))) / 2 + 5)})`) // rotate
                      .text(distanceFromBottom);

                  svg.append('line')
                      .style("stroke", "black")
                      .style("stroke-width", 0.5)
                      .attr("x1", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                      .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                      .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                      .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                      .attr("marker-start", "url(#arrowLeft)")
                      .attr("marker-end", "url(#arrowRight)")

                  svg.append('line')
                      .style("stroke", "black")
                      .style("stroke-width", 0.5)
                      .attr("x1", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                      .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                      .attr("x2", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                      .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 25)

                  svg.append("text")            // append text
                      .style("fill", "black")   // make the text
                      .attr("font-size", 10)
                      .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 4)))    // set x position
                      .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 25) // set y position
                      .text("eq");
                  svg.append('line')
                      .style("stroke", "black")
                      .style("stroke-width", 0.5)
                      .attr("x1", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                      .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                      .attr("x2", ix + FrameThicknessForMap + GapForMap)
                      .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                      .attr("marker-start", "url(#arrowLeft)")
                      .attr("marker-end", "url(#arrowRight)")

                  svg.append('line')
                      .style("stroke", "black")
                      .style("stroke-width", 0.5)
                      .attr("x1", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                      .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                      .attr("x2", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                      .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 25)

                  svg.append("text")            // append text
                      .style("fill", "black")   // make the text
                      .attr("font-size", 10)
                      .attr("x", ix - FrameThicknessForMap - GapForMap + ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 4)))    // set x position
                      .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 25) // set y position
                      .text("eq");
                } 
              } else {

                  if (Handing == 'Right') {

                      svg.append("circle")
                          .style("stroke", "black")
                          .style("fill", "#2C3360")
                          .attr("r", 7)
                          .attr("cx", ix + FrameThicknessForMap + GapForMap + ((distanceFromEdge / 5)))
                          .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)));

                      svg.append('line')
                          .style("stroke", "black")
                          .style("stroke-width", 0.5)
                          .attr("x1", ix + FrameThicknessForMap + GapForMap + ((distanceFromEdge / 5)))
                          .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                          .attr("x2", ix + FrameThicknessForMap + GapForMap - SideLightPanel2WidthSpaceForVerticalLines - leftdistance)
                          .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)));

                      svg.append('line')
                          .style("stroke", "black")
                          .style("stroke-width", 0.5)
                          .attr("x1", ix + FrameThicknessForMap + GapForMap - SideLightPanel2WidthSpaceForVerticalLines - leftdistance)
                          .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                          .attr("x2", ix + FrameThicknessForMap + GapForMap - SideLightPanel2WidthSpaceForVerticalLines - leftdistance)
                          .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                          .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                          .attr("marker-end", "url(#arrowRight)")

                      svg.append("text")            // append text
                          .style("fill", "black")   // make the text
                          .attr("font-size", 10)
                          .attr("x", ix + FrameThicknessForMap + GapForMap - SideLightPanel2WidthSpaceForVerticalLines - leftdistance-5)    // set x position
                          .attr("y", (iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5))) / 2 + 5) // set y position
                          .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap - SideLightPanel2WidthSpaceForVerticalLines - leftdistance - 5}, ${((iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5))) / 2 + 5)})`) // rotate
                          .text(distanceFromBottom);

                      svg.append('line')
                          .style("stroke", "black")
                          .style("stroke-width", 0.5)
                          .attr("x1", ix + FrameThicknessForMap + GapForMap + ((distanceFromEdge / 5)))
                          .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                          .attr("x2", ix + FrameThicknessForMap + GapForMap)
                          .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                          .attr("marker-start", "url(#arrowLeft)")
                          .attr("marker-end", "url(#arrowRight)")

                      svg.append('line')
                          .style("stroke", "black")
                          .style("stroke-width", 0.5)
                          .attr("x1", ix + FrameThicknessForMap + GapForMap + ((distanceFromEdge / 5)))
                          .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                          .attr("x2", ix + FrameThicknessForMap + GapForMap + ((distanceFromEdge / 5)))
                          .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 25)

                      svg.append("text")            // append text
                          .style("fill", "black")   // make the text
                          .attr("font-size", 10)
                          .attr("x", ix + FrameThicknessForMap + GapForMap + (distanceFromEdge / 10) + 5)    // set x position
                          .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 25) // set y position
                          .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + (distanceFromEdge / 10)+5}, ${iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 25})`) // rotate
                          .text(distanceFromEdge);
                  } else {

                      svg.append("circle")
                          .style("stroke", "black")
                          .style("fill", "#2C3360")
                          .attr("r", 7)
                          .attr("cx", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (distanceFromEdge / 5)))
                          .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)));

                      svg.append('line')
                          .style("stroke", "black")
                          .style("stroke-width", 0.5)
                          .attr("x1", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (distanceFromEdge / 5)))
                          .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                          .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + rightDistance)
                          .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)));

                      svg.append('line')
                          .style("stroke", "black")
                          .style("stroke-width", 0.5)
                          .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + rightDistance)
                          .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                          .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + rightDistance)
                          .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                          .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                          .attr("marker-end", "url(#arrowRight)")

                      svg.append("text")            // append text
                          .style("fill", "black")   // make the text
                          .attr("font-size", 10)
                          .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + rightDistance-5)    // set x position
                          .attr("y", (iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5))) / 2 + 5) // set y position
                          .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + rightDistance-5}, ${((iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5))) / 2 + 5)})`) // rotate
                          .text(distanceFromBottom);

                      svg.append('line')
                          .style("stroke", "black")
                          .style("stroke-width", 0.5)
                          .attr("x1", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (distanceFromEdge / 5)))
                          .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                          .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                          .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                          .attr("marker-start", "url(#arrowLeft)")
                          .attr("marker-end", "url(#arrowRight)")

                      svg.append('line')
                          .style("stroke", "black")
                          .style("stroke-width", 0.5)
                          .attr("x1", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (distanceFromEdge / 5)))
                          .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                          .attr("x2", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (distanceFromEdge / 5)))
                          .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 25)

                      svg.append("text")            // append text
                          .style("fill", "black")   // make the text
                          .attr("font-size", 10)
                          .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (distanceFromEdge / 10))    // set x position
                          .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 30) // set y position
                          .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (distanceFromEdge / 10) + 5}, ${iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 30})`) // rotate
                          .text(distanceFromEdge);
                  }
              }

          }
      }
      }

     if (IsDoorSinageEnable ) {
    drawDoorSignage(DoorSignagedistanceFromLeadingEdgeOfDoor, DoorSignageCentered, DoorSignageDistanceFromBottomOfDoor,225,185,220);
    }

    if (IsDoorSinageEnable2 ) {
    drawDoorSignage(DoorSignagedistanceFromLeadingEdgeOfDoor2, DoorSignageCentered2, DoorSignageDistanceFromBottomOfDoor2,245,205,245);
    }

      function drawDoorSignageDD(distanceFromEdge, isCentered, distanceFromBottom,rightDistance,leftdistance){
         
            if (isCentered == 1) {
                svg.append("circle")
                    .style("stroke", "black")
                    .style("fill", "#2C3360")
                    .attr("r", 7)
                    .attr("cx", ix + (2*FrameThicknessForMap) + GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                    .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))

                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + (2*FrameThicknessForMap) + GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                    .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                    .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                    .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                    .attr("marker-start", "url(#arrowLeft)")
                    .attr("marker-end", "url(#arrowRight)")

                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + (2*FrameThicknessForMap)+ GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                    .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                    .attr("x2", ix + (2*FrameThicknessForMap) + GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                    .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 25)

                svg.append("text")            // append text
                    .style("fill", "black")   // make the text
                    .attr("font-size", 10)
                    .attr("x", ix + (FrameThicknessForMap) + GapForMap + LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 4)))    // set x position
                    .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 25) // set y position
                    .text("eq");
                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + (2*FrameThicknessForMap) + GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                    .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                    .attr("x2", ix + (FrameThicknessForMap) + GapForMap)
                    .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                    .attr("marker-start", "url(#arrowLeft)")
                    .attr("marker-end", "url(#arrowRight)")

                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + (2*FrameThicknessForMap) + GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                    .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                    .attr("x2", ix + (2*FrameThicknessForMap) + GapForMap + (LeafWidth1ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 2))))
                    .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 25)

                svg.append("text")            // append text
                    .style("fill", "black")   // make the text
                    .attr("font-size", 10)
                    .attr("x", ix - (FrameThicknessForMap) - GapForMap + ((FrameThicknessForMap + GapForMap + (LeafWidth1ForMap) / 4)))    // set x position
                    .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 25) // set y position
                    .text("eq");

                    // ...........................................................
                    svg.append("circle")
                    .style("stroke", "black")
                    .style("fill", "#2C3360")
                    .attr("r", 7)
                    .attr("cx", ix + (2*FrameThicknessForMap) + GapForMap + (LeafWidth1ForMap +MeetingStiles+LeafWidth2ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth2ForMap) / 2))))
                    .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))

                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + (2*FrameThicknessForMap) + GapForMap + (LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth2ForMap) / 2))))
                    .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                    .attr("x2", ix + (2*FrameThicknessForMap) + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 220)
                    .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)));

                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + (2*FrameThicknessForMap) + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 220)
                    .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                    .attr("x2", ix + (2*FrameThicknessForMap) + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 220)
                    .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                    .attr("marker-end", "url(#arrowRight)")

                svg.append("text")            // append text
                    .style("fill", "black")   // make the text
                    .attr("font-size", 10)
                    .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 215)    // set x position
                    .attr("y", (iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5))) / 2 + 5) // set y position
                    .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 215}, ${((iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5))) / 2 + 5)})`) // rotate
                    .text(distanceFromBottom);

                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + (2*FrameThicknessForMap) + GapForMap + (LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth2ForMap) / 2))))
                    .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                    .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap)
                    .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                    .attr("marker-start", "url(#arrowLeft)")
                    .attr("marker-end", "url(#arrowRight)")

                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + (2*FrameThicknessForMap)+ GapForMap + (LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth2ForMap) / 2))))
                    .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                    .attr("x2", ix + (2*FrameThicknessForMap) + GapForMap + (LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth2ForMap) / 2))))
                    .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 25)

                svg.append("text")            // append text
                    .style("fill", "black")   // make the text
                    .attr("font-size", 10)
                    .attr("x", ix + (FrameThicknessForMap) + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth2ForMap) / 4)))    // set x position
                    .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 25) // set y position
                    .text("eq");
                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + (2*FrameThicknessForMap) + GapForMap + (LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth2ForMap) / 2))))
                    .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                    .attr("x2", ix + (FrameThicknessForMap) + GapForMap)
                    .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                    .attr("marker-start", "url(#arrowLeft)")
                    .attr("marker-end", "url(#arrowRight)")

                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + (2*FrameThicknessForMap) + GapForMap + (LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth2ForMap) / 2))))
                    .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                    .attr("x2", ix + (2*FrameThicknessForMap) + GapForMap + (LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap - ((FrameThicknessForMap + GapForMap + (LeafWidth2ForMap) / 2))))
                    .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 25)

                svg.append("text")            // append text
                    .style("fill", "black")   // make the text
                    .attr("font-size", 10)
                    .attr("x", ix - (FrameThicknessForMap) - GapForMap +MeetingStiles+LeafWidth1ForMap+ ((FrameThicknessForMap + GapForMap + (LeafWidth2ForMap) / 4)))    // set x position
                    .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 25) // set y position
                    .text("eq");
            } else {


                svg.append("circle")
                .style("stroke", "black")
                .style("fill", "#2C3360")
                .attr("r", 7)
                .attr("cx", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (distanceFromEdge / 5)))
                .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)));



            svg.append('line')
                .style("stroke", "black")
                .style("stroke-width", 0.5)
                .attr("x1", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (distanceFromEdge / 5)))
                .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                .attr("marker-start", "url(#arrowLeft)")
                .attr("marker-end", "url(#arrowRight)")

            svg.append('line')
                .style("stroke", "black")
                .style("stroke-width", 0.5)
                .attr("x1", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (distanceFromEdge / 5)))
                .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                .attr("x2", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (distanceFromEdge / 5)))
                .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 25)

            svg.append("text")            // append text
                .style("fill", "black")   // make the text
                .attr("font-size", 10)
                .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (distanceFromEdge / 10))    // set x position
                .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 30) // set y position
                .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (distanceFromEdge / 10) }, ${iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 30})`) // rotate
                .text(distanceFromEdge);

                // ..........................................

                svg.append("circle")
                .style("stroke", "black")
                .style("fill", "#2C3360")
                .attr("r", 7)
                .attr("cx", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + ((distanceFromEdge / 5)))
                .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)));



            svg.append('line')
                .style("stroke", "black")
                .style("stroke-width", 0.5)
                .attr("x1", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + ((distanceFromEdge / 5)))
                .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                .attr("x2", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles)
                .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 20)
                .attr("marker-start", "url(#arrowLeft)")
                .attr("marker-end", "url(#arrowRight)")

            svg.append('line')
                .style("stroke", "black")
                .style("stroke-width", 0.5)
                .attr("x1", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + ((distanceFromEdge / 5)))
                .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                .attr("x2", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + ((distanceFromEdge / 5)))
                .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 25)

            svg.append("text")            // append text
                .style("fill", "black")   // make the text
                .attr("font-size", 10)
                .attr("x", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + (distanceFromEdge / 10)+5)    // set x position
                .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 30) // set y position
                .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + (distanceFromEdge / 10)+5}, ${iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)) - 30})`) // rotate
                .text(distanceFromEdge);

                svg.append('line')
                .style("stroke", "black")
                .style("stroke-width", 0.5)
                .attr("x1", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + ((distanceFromEdge / 5)))
                .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + SideLightPanel2WidthSpaceForVerticalLines + rightDistance)
                .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)));

            svg.append('line')
                .style("stroke", "black")
                .style("stroke-width", 0.5)
                .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + rightDistance)
                .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + rightDistance)
                .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5)))
                .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                .attr("marker-end", "url(#arrowRight)")

            svg.append("text")            // append text
                .style("fill", "black")   // make the text
                .attr("font-size", 10)
                .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + rightDistance-5)    // set x position
                .attr("y", (iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5))) / 2 + 5) // set y position
                .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + rightDistance-5}, ${((iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distanceFromBottom / 5))) / 2 + 5)})`) // rotate
                .text(distanceFromBottom);
            }
        
 }
           
            if (DoorSetType == "DD") {


        if (IsDoorSinageEnable ) {
    drawDoorSignageDD(DoorSignagedistanceFromLeadingEdgeOfDoor, DoorSignageCentered, DoorSignageDistanceFromBottomOfDoor,235,265,245);
    }
    if (IsDoorSinageEnable2 ) {
    drawDoorSignageDD(DoorSignagedistanceFromLeadingEdgeOfDoor2, DoorSignageCentered2, DoorSignageDistanceFromBottomOfDoor2,255,275,255);
    }

                       

                 if (IsPushHandlesEnable && !IsPushHandlesEnable2 && !IsPullHandlesEnable  ) {
                        if (LeafWidth2ForMap == LeafWidth1ForMap){
                            if(Handing == 'DD'){
                                LeftPushHandle(PushHandleHeight,PushHandleDistanceFromBottomOfDoor,PushHandleDistanceFromLeadingEdgeOfDoor)
                            }
                            else{
                                RightPushHandle(PushHandleHeight,PushHandleDistanceFromBottomOfDoor,PushHandleDistanceFromLeadingEdgeOfDoor)
                            }
                        }
                            if(LeafWidth2ForMap > LeafWidth1ForMap){ //right
                            RightPushHandle(PushHandleHeight,PushHandleDistanceFromBottomOfDoor,PushHandleDistanceFromLeadingEdgeOfDoor)
                            }
                            if(LeafWidth2ForMap < LeafWidth1ForMap){ //left
                            LeftPushHandle(PushHandleHeight,PushHandleDistanceFromBottomOfDoor,PushHandleDistanceFromLeadingEdgeOfDoor)
                            }
                }

                if (IsPushHandlesEnable2   && !IsPullHandlesEnable  ) {
                     LeftPushHandle(PushHandleHeight,PushHandleDistanceFromBottomOfDoor,PushHandleDistanceFromLeadingEdgeOfDoor)
                     RightPushHandle(PushHandleHeight2,PushHandleDistanceFromBottomOfDoor2,PushHandleDistanceFromLeadingEdgeOfDoor2)
                }

                  function LeftPushHandle(PHheight,PHdistancefrombottom,PHdistancefromedge){
                    
                        svg.append("rect")
                            .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (PHdistancefromedge / 5) - 6)
                            .attr('y', iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5))
                            .attr("width", 6)
                            .attr("height", (PHheight / 5))
                            .style("fill", '#D0D0C6')
                            .style("stroke", 'black')

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap  + LeafWidth1ForMap - (PHdistancefromedge / 5) - 6)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 155)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5));

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 155)    // set x position
                            .attr("y", (iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5)) / 2 + 5) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 155}, ${(iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5)) / 2 + 5})`) // rotate
                            .text(`${parseInt(PHdistancefrombottom) + parseInt(PHheight)}`);

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 150)
                            .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 150)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (PHdistancefromedge / 5) - 6)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 125)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5));

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 120)
                            .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 120)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 125)    // set x position
                            .attr("y", (iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5)) / 2 + 5) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 125}, ${(iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5)) / 2 + 5})`) // rotate
                            .text(`${parseInt(PHdistancefrombottom)}`);
                  }

                  function RightPushHandle(PHheight,PHdistancefrombottom,PHdistancefromedge){

                        svg.append("rect")
                            .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap +MeetingStiles+ (PHdistancefromedge / 5))
                            .attr('y', iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5))
                            .attr("width", 6)
                            .attr("height", (PHheight / 5))
                            .style("fill", '#D0D0C6')
                            .style("stroke", 'black')

                           

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap +MeetingStiles+ (PHdistancefromedge / 5))
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 160)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5));

                        svg.append("text")
                            .style("fill", "black")
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 150)    // set x position
                            .attr("y", (iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5)) / 2 + 5) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 150}, ${(iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5)) / 2 + 5})`) // rotate
                            .text(`${parseInt(PHdistancefrombottom) + parseInt(PHheight)}`);

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 155)
                            .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 155)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap +MeetingStiles+ (PHdistancefromedge / 5) )
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 145)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5));

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 140)
                            .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap+ SideLightPanel2WidthSpaceForVerticalLines + 140)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 135)    // set x position
                            .attr("y", (iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5)) / 2 + 5) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 135}, ${(iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5)) / 2 + 5})`) // rotate
                            .text(`${parseInt(PHdistancefrombottom)}`);
                    
                  }
         

             if (IsPullHandlesEnable && !IsPullHandlesEnable2  ) {
           
                        if (LeafWidth2ForMap == LeafWidth1ForMap){
                            if(Handing == 'DD'){
                                LeftPullHandle(PullHandleHeight,PullHandleDistanceFromBottomOfDoor,PullHandleDistanceFromLeadingEdgeOfDoor)
                            }
                            else{
                                RightPullHandle(PullHandleHeight,PullHandleDistanceFromBottomOfDoor,PullHandleDistanceFromLeadingEdgeOfDoor)
                            }
                        }
                            if(LeafWidth2ForMap > LeafWidth1ForMap){ //right
                            RightPullHandle(PullHandleHeight,PullHandleDistanceFromBottomOfDoor,PullHandleDistanceFromLeadingEdgeOfDoor)
                            }
                            if(LeafWidth2ForMap < LeafWidth1ForMap){ //left
                            LeftPullHandle(PullHandleHeight,PullHandleDistanceFromBottomOfDoor,PullHandleDistanceFromLeadingEdgeOfDoor)
                            }

            }

                if (IsPullHandlesEnable2 ) {
                     LeftPullHandle(PullHandleHeight,PullHandleDistanceFromBottomOfDoor,PullHandleDistanceFromLeadingEdgeOfDoor)
                     RightPullHandle(PullHandleHeight2,PullHandleDistanceFromBottomOfDoor2,PullHandleDistanceFromLeadingEdgeOfDoor2)

            }

                function LeftPullHandle(PHheight,PHdistancefrombottom,PHdistancefromedge){

                        svg.append("rect")
                            .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (PHdistancefromedge / 5) - 6)
                            .attr('y', iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5))
                            .attr("width", 6)
                            .attr("height", (PHheight / 5))
                            .attr("rx", 4)
                            .style("fill", '#D0D0C6')
                            .style("stroke", 'black')

                

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + LeafWidth1ForMap - (PHdistancefromedge / 5) - 6)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 165)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5));

                        svg.append("text")
                            .style("fill", "black")
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 165)    // set x position
                            .attr("y", (iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5)) / 2 + 5) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 165}, ${(iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5)) / 2 + 5})`) // rotate
                            .text(`${parseInt(PHdistancefrombottom) + parseInt(PHheight)}`);

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 160)
                            .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 160)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (PHdistancefromedge / 5))
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 145)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5));

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 140)
                            .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 140)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 145)    // set x position
                            .attr("y", (iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5)) / 2 + 5) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 145}, ${(iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5)) / 2 + 5})`) // rotate
                            .text(`${parseInt(PHdistancefrombottom)}`);

                    
                }
                function RightPullHandle(PHheight,PHdistancefrombottom,PHdistancefromedge){
                    
                        svg.append("rect")
                            .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap +MeetingStiles+ (PHdistancefromedge / 5))
                            .attr('y', iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5))
                            .attr("width", 6)
                            .attr("height", (PHheight / 5))
                            .attr("rx", 4)
                            .style("fill", '#D0D0C6')
                            .style("stroke", 'black')


                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap +MeetingStiles+ (PHdistancefromedge / 5))
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 175)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5));

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 165)    // set x position
                            .attr("y", (iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5)) / 2 + 5) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 165}, ${(iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5)) / 2 + 5})`) // rotate
                            .text(`${parseInt(PHdistancefrombottom) + parseInt(PHheight)}`);

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 170)
                            .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 170)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5) - (PHheight / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap +MeetingStiles+ (PHdistancefromedge / 5) )
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 145)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5));

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 140)
                            .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap+ SideLightPanel2WidthSpaceForVerticalLines + 140)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 135)    // set x position
                            .attr("y", (iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5)) / 2 + 5) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 135}, ${(iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap + iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (PHdistancefrombottom / 5)) / 2 + 5})`) // rotate
                            .text(`${parseInt(PHdistancefrombottom)}`);
                    
                }

                if(IsFlushBoltsEnable)
                    {
                        // if(LeafWidth2ForMap<LeafWidth1ForMap) {


                            svg.append('rect')
                            .attr('x', ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + 1)
                            .attr('y', iy + TopFrameHeight + GapForMap + 1)
                            .attr('width', (FlushBoltsWidth / 5))
                            .attr('height', (FlushBoltsHeight / 5))
                            .attr('stroke', 'none')
                            .attr('fill', '#D0D0C6')

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + (FlushBoltsWidth / 5) + 5)
                            .attr("y1", iy + TopFrameHeight + GapForMap + 1)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + (FlushBoltsWidth / 5) + 5)
                            .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))
                            .attr("marker-start", "url(#arrowLeft)")
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + (FlushBoltsWidth / 5) + 5 + 5 + 1)
                            .attr("y1", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + (FlushBoltsWidth / 5) + 1)
                            .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))


                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + (FlushBoltsWidth / 5) + 20)    // set x position
                            .attr("y", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 10) + 5) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + (FlushBoltsWidth / 5) + 20}, ${iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 10) + 5})`) // rotate
                            .text(FlushBoltsHeight);

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + 1)
                            .attr("y1", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5) + 5)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + (FlushBoltsWidth / 5) + 1)
                            .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5) + 5)
                            .attr("marker-start", "url(#arrowLeft)")
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + (FlushBoltsWidth / 5) + 1)
                            .attr("y1", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + (FlushBoltsWidth / 5) + 1)
                            .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5) + 10);

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + (FlushBoltsWidth / 10) + 1)    // set x position
                            .attr("y", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5) + 15) // set y position
                            .text(FlushBoltsWidth);

                            svg.append('line')
                                            .style("stroke", "black")
                                            .style("stroke-width", 0.5)
                                            .attr("x1", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 190)
                                            .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                                            .attr("x2", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 190)
                                            .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))
                                            .attr("marker-start", "url(#arrowLeft)")
                                            .attr("marker-end", "url(#arrowRight)")

                                        svg.append('line')
                                            .style("stroke", "black")
                                            .style("stroke-width", 0.5)
                                            .attr("x1", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 195)
                                            .attr("y1", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))
                                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+(FlushBoltsWidth / 5)  + 1 )
                                            .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5));

                                        svg.append("text")            // append text
                                            .style("fill", "black")   // set text color
                                            .attr("font-size", 10)    // set font size
                                            .attr("x", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 185) // set x position
                                            .attr("y", iy + TopFrameHeight + GapForMap + ((LeafHeightNoOPForMap + (FlushBoltsHeight / 5)) / 2)) // set y position
                                            .attr("transform", `rotate(-90, ${ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 185}, ${iy + TopFrameHeight + GapForMap + ((LeafHeightNoOPForMap + (FlushBoltsHeight / 5)) / 2)})`) // apply rotation
                                            .text(FrameHeight - FrameThickness - Gap - FlushBoltsHeight);
                        // }else{
                        //     svg.append('rect')
                        //     .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 1)
                        //     .attr('y', iy + TopFrameHeight + GapForMap + 1)
                        //     .attr('width', (FlushBoltsWidth / 5))
                        //     .attr('height', (FlushBoltsHeight / 5))
                        //     .attr('stroke', 'none')
                        //     .attr('fill', '#D0D0C6')

                        // svg.append('line')
                        //     .style("stroke", "black")
                        //     .style("stroke-width", 0.5)
                        //     .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 5)
                        //     .attr("y1", iy + TopFrameHeight + GapForMap + 1)
                        //     .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 5)
                        //     .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))
                        //     .attr("marker-start", "url(#arrowLeft)")
                        //     .attr("marker-end", "url(#arrowRight)")

                        // svg.append('line')
                        //     .style("stroke", "black")
                        //     .style("stroke-width", 0.5)
                        //     .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 5 - 5 - 1)
                        //     .attr("y1", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))
                        //     .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 1)
                        //     .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))


                        // svg.append("text")            // append text
                        //     .style("fill", "black")   // make the text
                        //     .attr("font-size", 10)
                        //     .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 10)    // set x position
                        //     .attr("y", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 10) + 5) // set y position
                        //     .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 10}, ${iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 10) + 5})`) // rotate
                        //     .text(FlushBoltsHeight);

                        // svg.append('line')
                        //     .style("stroke", "black")
                        //     .style("stroke-width", 0.5)
                        //     .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 1 + (FlushBoltsWidth / 5))
                        //     .attr("y1", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5) + 5)
                        //     .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 1)
                        //     .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5) + 5)
                        //     .attr("marker-start", "url(#arrowLeft)")
                        //     .attr("marker-end", "url(#arrowRight)")

                        // svg.append('line')
                        //     .style("stroke", "black")
                        //     .style("stroke-width", 0.5)
                        //     .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 1)
                        //     .attr("y1", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))
                        //     .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 5) - 1)
                        //     .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5) + 10);

                        // svg.append("text")            // append text
                        //     .style("fill", "black")   // make the text
                        //     .attr("font-size", 10)
                        //     .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (FlushBoltsWidth / 10) - 1 - 5)    // set x position
                        //     .attr("y", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5) + 15) // set y position
                        //     .text(FlushBoltsWidth);

                        //     svg.append('line')
                        //                     .style("stroke", "black")
                        //                     .style("stroke-width", 0.5)
                        //                     .attr("x1", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 190)
                        //                     .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                        //                     .attr("x2", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 190)
                        //                     .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))
                        //                     .attr("marker-start", "url(#arrowLeft)")
                        //                     .attr("marker-end", "url(#arrowRight)")

                        //                 svg.append('line')
                        //                     .style("stroke", "black")
                        //                     .style("stroke-width", 0.5)
                        //                     .attr("x1", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 195)
                        //                     .attr("y1", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5))
                        //                     .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap )
                        //                     .attr("y2", iy + TopFrameHeight + GapForMap + 1 + (FlushBoltsHeight / 5));

                        //                 svg.append("text")            // append text
                        //                     .style("fill", "black")   // set text color
                        //                     .attr("font-size", 10)    // set font size
                        //                     .attr("x", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 185) // set x position
                        //                     .attr("y", iy + TopFrameHeight + GapForMap + ((LeafHeightNoOPForMap + (FlushBoltsHeight / 5)) / 2)) // set y position
                        //                     .attr("transform", `rotate(-90, ${ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 185}, ${iy + TopFrameHeight + GapForMap + ((LeafHeightNoOPForMap + (FlushBoltsHeight / 5)) / 2)})`) // apply rotation
                        //                     .text(FrameHeight - FrameThickness - Gap - FlushBoltsHeight);
                        // }
                    }

                     if(IsFlushBoltsEnable2)
                    {
                            svg.append('rect')
                            .attr('x', ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + 1)
                            .attr('y',  iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap- 1 - (FlushBoltsHeight2 / 5))
                            .attr('width', (FlushBoltsWidth2 / 5))
                            .attr('height', (FlushBoltsHeight2 / 5))
                            .attr('stroke', 'none')
                            .attr('fill', '#D0D0C6')

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + (FlushBoltsWidth2 / 5) + 5)
                            .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap- 1)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + (FlushBoltsWidth2 / 5) + 5)
                            .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap - 1 - (FlushBoltsHeight2 / 5))
                            .attr("marker-start", "url(#arrowLeft)")
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + (FlushBoltsWidth2 / 5) + 20)    // set x position
                            .attr("y", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap - 1 - (FlushBoltsHeight2 / 10) + 5 ) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + (FlushBoltsWidth2 / 5) + 20}, ${iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap - 1 - (FlushBoltsHeight2 / 10) +5 })`) // rotate
                            .text(FlushBoltsHeight2);

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + 1)
                            .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap - 1 - (FlushBoltsHeight2 / 5) - 5)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + (FlushBoltsWidth2 / 5) + 1)
                            .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap - 1 - (FlushBoltsHeight2 / 5) - 5)
                            .attr("marker-start", "url(#arrowLeft)")
                            .attr("marker-end", "url(#arrowRight)")

               

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles + (FlushBoltsWidth2 / 10) + 1)    // set x position
                            .attr("y", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap - 1 - (FlushBoltsHeight2 / 5) - 10) // set y position
                            .text(FlushBoltsWidth2);

                    }
                if (IsMorticedDropdownSealsEnable || IsFacefixeddropsealsEnable) {

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .style("stroke-dasharray", "4, 2")
                        .attr("x1", ix + FrameThicknessForMap + GapForMap)
                        .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap - 10)
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                        .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap - 10)

                        svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .style("stroke-dasharray", "4, 2")
                        .attr("x1", ix + FrameThicknessForMap + GapForMap+ MeetingStiles+LeafWidth1ForMap)
                        .attr("y1", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap - 10)
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth2ForMap+ MeetingStiles+LeafWidth1ForMap)
                        .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap - 10)
                }


                if(IsFaceFixedDoorClosersEnable){

                        svg.append('rect')
                            .attr('x', ix + FrameThicknessForMap + GapForMap + 10)
                            .attr('y', iy + TopFrameHeight + GapForMap + 10)
                            .attr('width', (FaceFixedDoorClosersWidth / 5))
                            .attr('height', (FaceFixedDoorCloserDataHeight / 5))
                            .attr('stroke', 'black')
                            .attr('fill', '#D0D0C6')

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + 10 + (FaceFixedDoorClosersWidth / 5) + 10)
                            .attr("y1", iy + TopFrameHeight + GapForMap + 10)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + 10 + (FaceFixedDoorClosersWidth / 5) + 10)
                            .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5))
                            .attr("marker-start", "url(#arrowLeft)")
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + (FaceFixedDoorClosersWidth / 5) + 10)
                            .attr("y1", iy + TopFrameHeight + GapForMap + 10)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + 10 + (FaceFixedDoorClosersWidth / 5) + 10 + 5)
                            .attr("y2", iy + TopFrameHeight + GapForMap + 10)

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + (FaceFixedDoorClosersWidth / 5) + 10)
                            .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + 10 + (FaceFixedDoorClosersWidth / 5) + 10 + 5)
                            .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5))


                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap + (FaceFixedDoorClosersWidth / 5) + 30)    // set x position
                            .attr("y", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 10) + 5) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + (FaceFixedDoorClosersWidth / 5) + 30}, ${iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 10) + 5})`) // rotate
                            .text(FaceFixedDoorCloserDataHeight);

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + 10)
                            .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5) + 5)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + 10 + (FaceFixedDoorClosersWidth / 5))
                            .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5) + 5)
                            .attr("marker-start", "url(#arrowLeft)")
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + 10)
                            .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + 10)
                            .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5) + 10);

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + 10 + (FaceFixedDoorClosersWidth / 5))
                            .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + 10 + (FaceFixedDoorClosersWidth / 5))
                            .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5) + 10);

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap + 10 + (FaceFixedDoorClosersWidth / 10) - 5)    // set x position
                            .attr("y", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5) + 19) // set y position
                            .text(FaceFixedDoorClosersWidth);
                    // .........................................................................................................
                    {
                        svg.append('rect')
                            .attr('x', ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5))
                            .attr('y', iy + TopFrameHeight + GapForMap + 10)
                            .attr('width', (FaceFixedDoorClosersWidth / 5))
                            .attr('height', (FaceFixedDoorCloserDataHeight / 5))
                            .attr('stroke', 'black')
                            .attr('fill', '#D0D0C6')

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameWidthForMap - GapForMap - 20 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5))
                            .attr("y1", iy + TopFrameHeight + GapForMap + 10)
                            .attr("x2", ix + FrameWidthForMap - GapForMap - 20 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5))
                            .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5))
                            .attr("marker-start", "url(#arrowLeft)")
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameWidthForMap - GapForMap - 25 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5))
                            .attr("y1", iy + TopFrameHeight + GapForMap + 10)
                            .attr("x2", ix + FrameWidthForMap - GapForMap + 10 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5))
                            .attr("y2", iy + TopFrameHeight + GapForMap + 10)

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameWidthForMap - GapForMap - 25 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5))
                            .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5))
                            .attr("x2", ix + FrameWidthForMap - GapForMap + 10 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5))
                            .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5))


                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameWidthForMap - GapForMap - 30 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5))    // set x position
                            .attr("y", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 10) + 5) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameWidthForMap - GapForMap - 30 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5)}, ${iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 10) + 5})`) // rotate
                            .text(FaceFixedDoorCloserDataHeight);

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5))
                            .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5) + 5)
                            .attr("x2", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap)
                            .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5) + 5)
                            .attr("marker-start", "url(#arrowLeft)")
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap)
                            .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5))
                            .attr("x2", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap)
                            .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5) + 10);

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5))
                            .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5))
                            .attr("x2", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 5))
                            .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5) + 10);

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap - (FaceFixedDoorClosersWidth / 10) - 5)    // set x position
                            .attr("y", iy + TopFrameHeight + GapForMap + 10 + (FaceFixedDoorCloserDataHeight / 5) + 15) // set y position
                            .text(FaceFixedDoorClosersWidth);

                    }
                }

                if(IsConcealedOverheadCloserEnable){

                    svg.append('rect')
                        .attr('x', ix + FrameThicknessForMap + GapForMap + 10)
                        .attr('y', iy + TopFrameHeight + GapForMap + 10)
                        .attr('width', (ConcealedOverheadCloserWidth / 5))
                        .attr('height', (ConcealedOverheadCloserHeight / 5))
                        .attr('stroke', 'black')
                        .attr('fill', '#D0D0C6')

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + 10 + (ConcealedOverheadCloserWidth / 5) + 10)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10)
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + 10 + (ConcealedOverheadCloserWidth / 5) + 10)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5))
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + (ConcealedOverheadCloserWidth / 5) + 10)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10)
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + 10 + (ConcealedOverheadCloserWidth / 5) + 10 + 5)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10)

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + (ConcealedOverheadCloserWidth / 5) + 10)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + 10 + (ConcealedOverheadCloserWidth / 5) + 10 + 5)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5))


                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", ix + FrameThicknessForMap + GapForMap + (ConcealedOverheadCloserWidth / 5) + 30)    // set x position
                        .attr("y", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 10) + 5) // set y position
                        .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + (ConcealedOverheadCloserWidth / 5) + 30}, ${iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 10) + 5})`) // rotate
                        .text(ConcealedOverheadCloserHeight);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + 10)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5) + 5)
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + 10 + (ConcealedOverheadCloserWidth / 5))
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5) + 5)
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + 10)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + 10)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5) + 10);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + 10 + (ConcealedOverheadCloserWidth / 5))
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + 10 + (ConcealedOverheadCloserWidth / 5))
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5) + 10);

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", ix + FrameThicknessForMap + GapForMap + 10 + (ConcealedOverheadCloserWidth / 10) - 5)    // set x position
                        .attr("y", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5) + 19) // set y position
                        .text(ConcealedOverheadCloserWidth);
                // .........................................................................................................
                {
                    svg.append('rect')
                        .attr('x', ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5))
                        .attr('y', iy + TopFrameHeight + GapForMap + 10)
                        .attr('width', (ConcealedOverheadCloserWidth / 5))
                        .attr('height', (ConcealedOverheadCloserHeight / 5))
                        .attr('stroke', 'black')
                        .attr('fill', '#D0D0C6')

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameWidthForMap - GapForMap - 20 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5))
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10)
                        .attr("x2", ix + FrameWidthForMap - GapForMap - 20 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5))
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5))
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameWidthForMap - GapForMap - 25 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5))
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10)
                        .attr("x2", ix + FrameWidthForMap - GapForMap + 10 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5))
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10)

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameWidthForMap - GapForMap - 25 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5))
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5))
                        .attr("x2", ix + FrameWidthForMap - GapForMap + 10 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5))
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5))


                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", ix + FrameWidthForMap - GapForMap - 30 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5))    // set x position
                        .attr("y", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 10) + 5) // set y position
                        .attr("transform", `rotate(-90, ${ix + FrameWidthForMap - GapForMap - 30 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5)}, ${iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 10) + 5})`) // rotate
                        .text(ConcealedOverheadCloserHeight);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5))
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5) + 5)
                        .attr("x2", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5) + 5)
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap)
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5))
                        .attr("x2", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap)
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5) + 10);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5))
                        .attr("y1", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5))
                        .attr("x2", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 5))
                        .attr("y2", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5) + 10);

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", ix + FrameWidthForMap - GapForMap - 10 - FrameThicknessForMap - (ConcealedOverheadCloserWidth / 10) - 5)    // set x position
                        .attr("y", iy + TopFrameHeight + GapForMap + 10 + (ConcealedOverheadCloserHeight / 5) + 15) // set y position
                        .text(ConcealedOverheadCloserWidth);

                }
            }
            
               if (IsSecurityViewerEnable && !IsSecurityViewerEnable2 ) {
           
                   if (LeafWidth2ForMap == LeafWidth1ForMap){
                        if(Handing == 'DD'){
                            LeftSecurityViewer(DoorsecurityviewerdistanceFromBottomOfDoor)
                        }
                    else{
                        RightSecurityViewer(DoorsecurityviewerdistanceFromBottomOfDoor)
                    }
                }
            if(LeafWidth2ForMap > LeafWidth1ForMap){ //right
            RightSecurityViewer(DoorsecurityviewerdistanceFromBottomOfDoor)
            }
            if(LeafWidth2ForMap < LeafWidth1ForMap){ //left
              LeftSecurityViewer(DoorsecurityviewerdistanceFromBottomOfDoor)
            }


          

            }

                if (IsSecurityViewerEnable2 ) {
                     LeftSecurityViewer(DoorsecurityviewerdistanceFromBottomOfDoor)
            RightSecurityViewer(DoorsecurityviewerdistanceFromBottomOfDoor2)

            }

              function LeftSecurityViewer(SVdistancefrombottom){


                                 svg.append("circle")
                        .style("stroke", "black")
                        .style("fill", "black")
                        .attr("r", 3)
                        .attr("cx", ix + (LeafWidth1ForMap / 2))
                        .attr("cy", iy + FrameHeightForMap - (SVdistancefrombottom / 5));

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1",  ix + (LeafWidth1ForMap / 2))
                        .attr("y1", iy + FrameHeightForMap - (SVdistancefrombottom / 5))
                        .attr("x2", ix - SideLightPanel2WidthSpaceForVerticalLines - 97)
                        .attr("y2", iy + FrameHeightForMap - (SVdistancefrombottom / 5));

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix - SideLightPanel2WidthSpaceForVerticalLines - 92)
                        .attr("y1", iy + FrameHeightForMap - (SVdistancefrombottom / 5))
                        .attr("x2", ix - SideLightPanel2WidthSpaceForVerticalLines - 92)
                        .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text green
                        .attr("font-size", 10)
                        .attr("x", ix - SideLightPanel2WidthSpaceForVerticalLines - 97) // set x position
                        .attr("y", (iy + FrameHeightForMap - (SVdistancefrombottom / 5) + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2)        // set y position
                        .attr("transform", `rotate(-90, ${ix - SideLightPanel2WidthSpaceForVerticalLines - 97}, ${(iy + FrameHeightForMap - (DoorsecurityviewerdistanceFromBottomOfDoor / 5) + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2})`) // rotate the text
                        .text(SVdistancefrombottom);  
              }
                function RightSecurityViewer(SVdistancefrombottom){

                    svg.append("circle")
                        .style("stroke", "black")
                        .style("fill", "black")
                        .attr("r", 3)
                        .attr("cx", ix +LeafWidth1ForMap+MeetingStiles+ (LeafWidth2ForMap / 2))
                        .attr("cy", iy + FrameHeightForMap - (SVdistancefrombottom / 5));

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix +LeafWidth1ForMap+MeetingStiles+ (LeafWidth2ForMap / 2))
                        .attr("y1", iy + FrameHeightForMap - (SVdistancefrombottom / 5))
                        .attr("x2", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 92 + 25)
                        .attr("y2", iy + FrameHeightForMap - (SVdistancefrombottom / 5));

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 92 + 25)
                        .attr("y1", iy + FrameHeightForMap - (SVdistancefrombottom / 5))
                        .attr("x2", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 92 + 25)
                        .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text green
                        .attr("font-size", 10)
                        .attr("x", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 92 + 21) // set x position
                        .attr("y", (iy + FrameHeightForMap - (SVdistancefrombottom / 5) + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2)        // set y position
                        .attr("transform", `rotate(-90, ${ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 92 + 21}, ${(iy + FrameHeightForMap - (SVdistancefrombottom / 5) + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2})`) // rotate the text
                        .text(SVdistancefrombottom);        // define the text to display               
                }

             if (IsLetterPlatesEnable && !IsLetterPlatesEnable2 ) {
           
                if (LeafWidth2ForMap == LeafWidth1ForMap){
                    if(Handing == 'DD'){
                        if (LetterplatesCentered == 1) {
                            LeftLetterPlateCentered(LetterplatesHeight,LetterplatesWidth,LetterplatesDistanceFromBottomOfDoor)
                        }
                        else{
                            LeftLetterPlate(LetterplatesHeight,LetterplatesWidth,LetterplatesDistanceFromBottomOfDoor,LetterplatesDistanceFromLeadingEdgeOfDoor)
                        }
                        
                    }
                else{
                    if (LetterplatesCentered == 1) {
                        RightLetterPlateCentered(LetterplatesHeight,LetterplatesWidth,LetterplatesDistanceFromBottomOfDoor)
                    }
                    else{
                        RightLetterPlate(LetterplatesHeight,LetterplatesWidth,LetterplatesDistanceFromBottomOfDoor,LetterplatesDistanceFromLeadingEdgeOfDoor)
                    }
                    
                }
            }
            if(LeafWidth2ForMap > LeafWidth1ForMap){ //right
                 if (LetterplatesCentered == 1) {
                    RightLetterPlateCentered(LetterplatesHeight,LetterplatesWidth,LetterplatesDistanceFromBottomOfDoor)
                 }
                else{
                    RightLetterPlate(LetterplatesHeight,LetterplatesWidth,LetterplatesDistanceFromBottomOfDoor,LetterplatesDistanceFromLeadingEdgeOfDoor)
                }
            }
            if(LeafWidth2ForMap < LeafWidth1ForMap){ //left
                 if (LetterplatesCentered == 1) {
                    LeftLetterPlateCentered(LetterplatesHeight,LetterplatesWidth,LetterplatesDistanceFromBottomOfDoor)
                 }
                else{
                    LeftLetterPlate(LetterplatesHeight,LetterplatesWidth,LetterplatesDistanceFromBottomOfDoor,LetterplatesDistanceFromLeadingEdgeOfDoor)
                }      
            }
            }

                if (IsLetterPlatesEnable2 ) {
                    if (LetterplatesCentered == 1) {
LeftLetterPlate(LetterplatesHeight,LetterplatesWidth,LetterplatesDistanceFromBottomOfDoor,LetterplatesDistanceFromLeadingEdgeOfDoor)
                    }else{
 LeftLetterPlateCentered(LetterplatesHeight,LetterplatesWidth,LetterplatesDistanceFromBottomOfDoor)
                    }
                     if (LetterplatesCentered2 == 1) {
                    RightLetterPlateCentered(LetterplatesHeight,LetterplatesWidth,LetterplatesDistanceFromBottomOfDoor)
                 }
                else{
                    RightLetterPlate(LetterplatesHeight2,LetterplatesWidth2,LetterplatesDistanceFromBottomOfDoor2,LetterplatesDistanceFromLeadingEdgeOfDoor2)
                }
                   
            }



            function RightLetterPlate(LPheight,LPwidth,LPdistancefrombottom,LPdistancefromedge){

                        svg.append('rect')
                            .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles+ (LPdistancefromedge / 5) )
                            .attr('y', iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - (LPheight / 5))
                            .attr('width', (LPwidth / 5))
                            .attr('height', (LPheight / 5))
                            .attr('stroke', 'black')

                            .attr('fill', '#D0D0C6')

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap +MeetingStiles+ (LPdistancefromedge / 5) )
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 5 - (LPheight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap +MeetingStiles+ (LPdistancefromedge / 5) + (LPwidth / 5))
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 5 - (LPheight / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles + (LPdistancefromedge / 5) )
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - (LPheight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles + (LPdistancefromedge / 5) )
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 10 - (LPheight / 5))

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap +MeetingStiles+ (LPdistancefromedge / 5) + (LPwidth / 5))
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - (LPheight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap +MeetingStiles+ (LPdistancefromedge / 5) + (LPwidth / 5))
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 10 - (LPheight / 5))

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text green
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap +MeetingStiles+ (LPdistancefromedge / 5) + (LPwidth / 10)-5) // set x position
                            .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 10 - (LPheight / 5))        // set y position
                            .text(LPwidth);        // define the text to display

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap +MeetingStiles+ (LPdistancefromedge / 5) + (LPwidth / 5)+5)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - (LPheight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap +MeetingStiles+ (LPdistancefromedge / 5) + (LPwidth / 5) + 5)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) + (LPheight / 5) - (LPheight / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x",ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap +MeetingStiles+ (LPdistancefromedge / 5) + (LPwidth / 5) + 15) // set x position
                            .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5)- (LPheight / 10) +5) // set y position
                            // .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap +MeetingStiles+ (LPdistancefromedge / 5) + (LPwidth / 5) + 15}, ${iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) + (LPheight / 5) - (LPheight / 10)+5})`) // rotate around (x, y)
                            .text(LPheight);

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles+ (LPdistancefromedge / 5))
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", (ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles+ (LPdistancefromedge / 10)) - 5) // set x position
                            .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 5) // set y position
                            .text(LPdistancefromedge);

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles+ (LPdistancefromedge / 5)+5)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles+ (LPdistancefromedge / 5)+5)
                            .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles+ (LPdistancefromedge / 5)+ 20) // set x position
                            .attr("y", (iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles+ (LPdistancefromedge / 5)+ 15}, ${(iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2})`) // rotate
                            .text(LPdistancefrombottom);
                    
            }
             function LeftLetterPlate(LPheight,LPwidth,LPdistancefrombottom,LPdistancefromedge){
                        svg.append('rect')
                            .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap -(LPwidth / 5) - (LPdistancefromedge / 5) )
                            .attr('y', iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - (LPheight / 5))
                            .attr('width', (LPwidth / 5))
                            .attr('height', (LPheight / 5))
                            .attr('stroke', 'black')

                            .attr('fill', '#D0D0C6')

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap-(LPwidth / 5) -  (LPdistancefromedge / 5) )
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 5 - (LPheight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap -(LPwidth / 5) - (LPdistancefromedge / 5) + (LPwidth / 5))
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 5 - (LPheight / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap-(LPwidth / 5) -  (LPdistancefromedge / 5) )
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - (LPheight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap-(LPwidth / 5) - (LPdistancefromedge / 5) )
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 10 - (LPheight / 5))

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap -(LPwidth / 5) -  (LPdistancefromedge / 5) + (LPwidth / 5))
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - (LPheight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap -(LPwidth / 5) - (LPdistancefromedge / 5) + (LPwidth / 5))
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 10 - (LPheight / 5))

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text green
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap -(LPwidth / 5) -  (LPdistancefromedge / 5) + (LPwidth / 10)-5) // set x position
                            .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 10 - (LPheight / 5))        // set y position
                            .text(LPwidth);        // define the text to display

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap -(LPwidth / 5) -  (LPdistancefromedge / 5) -5)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - (LPheight / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap -(LPwidth / 5) -  (LPdistancefromedge / 5) - 5)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) + (LPheight / 5) - (LPheight / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x",ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap -(LPwidth / 5) -  (LPdistancefromedge / 5)- 25) // set x position
                            .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5)- (LPheight / 10) +5) // set y position
                            // .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap +MeetingStiles+ (LPdistancefromedge / 5) + (LPwidth / 5) + 15}, ${iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LetterplatesDistanceFromBottomOfDoor / 5) + (LPheight / 5) - (LPheight / 10)+5})`) // rotate around (x, y)
                            .text(LPheight);

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap -(LPwidth / 5) -  (LPdistancefromedge / 5))
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", (ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap  -  (LPdistancefromedge / 10)) -5) // set x position
                            .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 5) // set y position
                            .text(LPdistancefromedge);

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap  -  (LPdistancefromedge / 5)-5)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5))
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap  -  (LPdistancefromedge / 5)-5)
                            .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)")

                        svg.append("text")            // append text
                            .style("fill", "black")   // make the text
                            .attr("font-size", 10)
                            .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap  -  (LPdistancefromedge / 5)- 20) // set x position
                            .attr("y", (iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2) // set y position
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap  - (LPdistancefromedge / 5)- 15}, ${(iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2})`) // rotate
                            .text(LPdistancefrombottom);
                    
            }

             function RightLetterPlateCentered(LPheight,LPwidth,LPdistancefrombottom){

                        svg.append('rect')
                        .attr('x', ix +FrameWidthForMap- (LeafWidth2ForMap / 2) - ((LPwidth / 5) / 2))
                        .attr('y', iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - (LPheight / 5))
                        .attr('width', (LPwidth / 5))
                        .attr('height', (LPheight / 5))
                        .attr('stroke', 'black')

                        .attr('fill', '#D0D0C6')

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix +FrameWidthForMap- (LeafWidth2ForMap / 2) - ((LPwidth / 5) / 2))
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 5 - (LPheight / 5))
                        .attr("x2", ix +FrameWidthForMap- (LeafWidth2ForMap / 2) - ((LPwidth / 5) / 2) + (LPwidth / 5))
                        .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 5 - (LPheight / 5))
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix +FrameWidthForMap- (LeafWidth2ForMap / 2) - ((LPwidth / 5) / 2))
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - (LPheight / 5))
                        .attr("x2", ix +FrameWidthForMap- (LeafWidth2ForMap / 2) - ((LPwidth / 5) / 2))
                        .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 25 - (LPheight / 5))

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix +FrameWidthForMap- (LeafWidth2ForMap / 2) - ((LPwidth / 5) / 2) + (LPwidth / 5))
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - (LPheight / 5))
                        .attr("x2", ix +FrameWidthForMap- (LeafWidth2ForMap / 2) - ((LPwidth / 5) / 2) + (LPwidth / 5))
                        .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 25 - (LPheight / 5))

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix +FrameWidthForMap- (LeafWidth2ForMap / 2) - ((LPwidth / 5) / 2))
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 20 - (LPheight / 5))
                        .attr("x2", ix + GapForMap + FrameThicknessForMap +LeafWidth1ForMap+MeetingStiles)
                        .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 20 - (LPheight / 5))
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", (ix +FrameWidthForMap- (LeafWidth2ForMap / 2) - ((LPwidth / 5) / 2) + ix + GapForMap + FrameThicknessForMap +LeafWidth1ForMap+MeetingStiles) / 2) // set x position
                        .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 25 - (LPheight / 5)) // set y position
                        .text('eq');

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix +FrameWidthForMap- (LeafWidth2ForMap / 2) + ((LPwidth / 5) / 2) )
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 20 - (LPheight / 5))
                        .attr("x2", ix + FrameWidthForMap)//hjhjhj
                        .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 20 - (LPheight / 5))
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", (ix +FrameWidthForMap- (LeafWidth2ForMap / 2) - ((LPwidth / 5) / 2) + (LPwidth / 5) + ix + FrameWidthForMap) / 2) // set x position
                        .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 25 - (LPheight / 5)) // set y position
                        .text('eq');

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text green
                        .attr("font-size", 10)
                        .attr("x", ix +FrameWidthForMap- (LeafWidth2ForMap / 2) - ((LPwidth / 5) / 2) + ((LPwidth / 5) / 2) - 5) // set x position
                        .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 10 - (LPheight / 5))        // set y position
                        .text(LPwidth);        // define the text to display

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix +FrameWidthForMap- (LeafWidth2ForMap / 2) - ((LPwidth / 5) / 2) - 5)
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - (LPheight / 5))
                        .attr("x2", ix +FrameWidthForMap- (LeafWidth2ForMap / 2) - ((LPwidth / 5) / 2) - 5)
                        .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) + (LPheight / 5) - (LPheight / 5))
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", ix +FrameWidthForMap- (LeafWidth2ForMap / 2) - ((LPwidth / 5) / 2) - 15) // set x position
                        .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) + ((LPheight / 5) / 2) - (LPheight / 5)) // set y position
                        .attr("transform", `rotate(-90, ${ix +FrameWidthForMap- (LeafWidth2ForMap / 2) - ((LPwidth / 5) / 2) - 10}, ${iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - (LPheight / 5) + ((LPheight / 5) / 2)})`) // rotate around (x, y)
                        .text(LPheight);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix +FrameWidthForMap- (LeafWidth2ForMap / 2) - ((LPwidth / 5) / 2) + (LPwidth / 5))
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                        .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5))

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles + 10)
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles + 10)
                        .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + 25) // set x position
                        .attr("y", (iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2) // set y position
                        .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + 25}, ${(iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2})`) // rotate
                        .text(LPdistancefrombottom);
                    
             }
              function LeftLetterPlateCentered(LPheight,LPwidth,LPdistancefrombottom){

                    svg.append('rect')
                        .attr('x', ix + (LeafWidth1ForMap / 2) - ((LPwidth / 5) / 2))
                        .attr('y', iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - (LPheight / 5))
                        .attr('width', (LPwidth / 5))
                        .attr('height', (LPheight / 5))
                        .attr('stroke', 'black')

                        .attr('fill', '#D0D0C6')

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + (LeafWidth1ForMap / 2) - ((LPwidth / 5) / 2))
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 5 - (LPheight / 5))
                        .attr("x2", ix + (LeafWidth1ForMap / 2) - ((LPwidth / 5) / 2) + (LPwidth / 5))
                        .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 5 - (LPheight / 5))
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + (LeafWidth1ForMap / 2) - ((LPwidth / 5) / 2))
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - (LPheight / 5))
                        .attr("x2", ix + (LeafWidth1ForMap / 2) - ((LPwidth / 5) / 2))
                        .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 25 - (LPheight / 5))

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + (LeafWidth1ForMap / 2) - ((LPwidth / 5) / 2) + (LPwidth / 5))
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - (LPheight / 5))
                        .attr("x2", ix + (LeafWidth1ForMap / 2) - ((LPwidth / 5) / 2) + (LPwidth / 5))
                        .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 25 - (LPheight / 5))

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + (LeafWidth1ForMap / 2) - ((LPwidth / 5) / 2))
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 20 - (LPheight / 5))
                        .attr("x2", ix + GapForMap + FrameThicknessForMap)
                        .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 20 - (LPheight / 5))
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", (ix + (LeafWidth1ForMap / 2) - ((LPwidth / 5) / 2) + ix) / 2) // set x position
                        .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 25 - (LPheight / 5)) // set y position
                        .text('eq');

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + (LeafWidth1ForMap / 2) - ((LPwidth / 5) / 2) + (LPwidth / 5))
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 20 - (LPheight / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                        .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 20 - (LPheight / 5))
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", (ix + (LeafWidth1ForMap / 2) - ((LPwidth / 5) / 2) + (LPwidth / 5) + ix + LeafWidth1ForMap) / 2) // set x position
                        .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 25 - (LPheight / 5)) // set y position
                        .text('eq');

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text green
                        .attr("font-size", 10)
                        .attr("x", ix + (LeafWidth1ForMap / 2) - ((LPwidth / 5) / 2) + ((LPwidth / 5) / 2) - 5) // set x position
                        .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - 10 - (LPheight / 5))        // set y position
                        .text(LPwidth);        // define the text to display

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + (LeafWidth1ForMap / 2) - ((LPwidth / 5) / 2) - 5)
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - (LPheight / 5))
                        .attr("x2", ix + (LeafWidth1ForMap / 2) - ((LPwidth / 5) / 2) - 5)
                        .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) + (LPheight / 5) - (LPheight / 5))
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", ix + (LeafWidth1ForMap / 2) - ((LPwidth / 5) / 2) - 15) // set x position
                        .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) + ((LPheight / 5) / 2) - (LPheight / 5)) // set y position
                        .attr("transform", `rotate(-90, ${ix + (LeafWidth1ForMap / 2) - ((LPwidth / 5) / 2) - 10}, ${iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) - (LPheight / 5) + ((LPheight / 5) / 2)})`) // rotate around (x, y)
                        .text(LPheight);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + (LeafWidth1ForMap / 2) - ((LPwidth / 5) / 2) + (LPwidth / 5))
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                        .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5))

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - 10)
                        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5))
                        .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - 10)
                        .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - 15) // set x position
                        .attr("y", (iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2) // set y position
                        .attr("transform", `rotate(-90, ${ix + LeafWidth1ForMap - 15}, ${(iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LPdistancefrombottom / 5) + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2})`) // rotate
                        .text(LPdistancefrombottom);
                    
             }


            if (IsAirTransferGrillsEnable && !IsAirTransferGrillsEnable2 ) {
           
if (LeafWidth2ForMap == LeafWidth1ForMap){
    if(Handing == 'DD'){
        LeftAirTransferGrill(AirTransferGrillsDistanceFromBottomOfDoor,AirTransferGrillsHeight,AirTransferGrillsWidth)
    }
    else{
         RightAirtransferGrill(AirTransferGrillsDistanceFromBottomOfDoor,AirTransferGrillsHeight,AirTransferGrillsWidth)
    }
}
            if(LeafWidth2ForMap > LeafWidth1ForMap){ //right
            RightAirtransferGrill(AirTransferGrillsDistanceFromBottomOfDoor,AirTransferGrillsHeight,AirTransferGrillsWidth)
            }
            if(LeafWidth2ForMap < LeafWidth1ForMap){ //left
              LeftAirTransferGrill(AirTransferGrillsDistanceFromBottomOfDoor,AirTransferGrillsHeight,AirTransferGrillsWidth)
            }


          

            }

                if (IsAirTransferGrillsEnable2 ) {
                     LeftAirTransferGrill(AirTransferGrillsDistanceFromBottomOfDoor,AirTransferGrillsHeight,AirTransferGrillsWidth)
            RightAirtransferGrill(AirTransferGrillsDistanceFromBottomOfDoor2,AirTransferGrillsHeight2,AirTransferGrillsWidth2)

            }

            
            function RightAirtransferGrill(ATGDistanceFromBottomOfDoor,ATGheight,ATGwidth){
                const grillrightX = ix +FrameThicknessForMap + GapForMap+LeafWidth1ForMap+MeetingStiles+ (LeafWidth2ForMap / 2) - ((ATGwidth / 5) / 2);
                const grillrightY = iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (ATGDistanceFromBottomOfDoor / 5) - (ATGheight / 5);
                const grillrightWidth = ATGwidth / 5;
                const grillrightHeight = ATGheight / 5;

                // Draw the outer rectangle
                svg.append('rect')
                    .attr('x', grillrightX)
                    .attr('y', grillrightY)
                    .attr('width', grillrightWidth)
                    .attr('height', grillrightHeight)
                    .attr('stroke', 'black')
                    .attr('fill', '#D0D0C6');

                // Draw the horizontal slots
                const numSlotsright = ATGheight / 20; // Number of horizontal slots
                const slotHeightright = grillrightHeight / (2 * numSlotsright); // Slot height
                const gapHeightright = slotHeightright; // Gap between slots

                for (let i = 0; i < numSlotsright; i++) {
                    svg.append('rect')
                        .attr('x', grillrightX + 2) // Slight margin from the left
                        .attr('y', grillrightY + i * (slotHeightright + gapHeightright) + 2) // Space out slots
                        .attr('width', grillrightWidth - 4) // Slight margin on both sides
                        .attr('height', slotHeightright)
                        .attr('fill', 'black'); // Black slots
                }
                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", grillrightX - 5)
                    .attr("y1", grillrightY)
                    .attr("x2", grillrightX - 5)
                    .attr("y2", grillrightY + grillrightHeight)
                    .attr("marker-start", "url(#arrowLeft)")
                    .attr("marker-end", "url(#arrowRight)")

                svg.append("text")            // append text
                    .style("fill", "black")   // make the text
                    .attr("font-size", 10)
                    .attr("x", grillrightX - 10)    // set x position
                    .attr("y", grillrightY + (grillrightHeight / 2) + 5) // set y position
                    .attr("transform", `rotate(-90, ${grillrightX - 10}, ${grillrightY + (grillrightHeight / 2) + 5})`) // rotate around the text's position
                    .text(ATGheight);


                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", grillrightX)
                    .attr("y1", grillrightY)
                    .attr("x2", grillrightX)
                    .attr("y2", grillrightY - 10)

                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", grillrightX + grillrightWidth)
                    .attr("y1", grillrightY)
                    .attr("x2", grillrightX + grillrightWidth)
                    .attr("y2", grillrightY - 10)

                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", grillrightX)
                    .attr("y1", grillrightY - 5)
                    .attr("x2", grillrightX + grillrightWidth)
                    .attr("y2", grillrightY - 5)
                    .attr("marker-start", "url(#arrowLeft)")
                    .attr("marker-end", "url(#arrowRight)")

                svg.append("text")
                    .style("fill", "black")
                    .attr("font-size", 10)
                    .attr("x", grillrightX + (grillrightWidth / 2) - 5)
                    .attr("y", grillrightY - 10)
                    .text(ATGwidth);

                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", grillrightX)
                    .attr("y1", grillrightY)
                    .attr("x2", grillrightX - 10)
                    .attr("y2", grillrightY)

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", grillrightX + grillrightWidth)
                        .attr("y1", grillrightY + grillrightHeight)
                        .attr("x2", grillrightX + grillrightWidth + 30)
                        .attr("y2", grillrightY + grillrightHeight)

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", grillrightX + grillrightWidth + 25)
                        .attr("y1", grillrightY + grillrightHeight)
                        .attr("x2", grillrightX + grillrightWidth + 25)
                        .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", grillrightX + grillrightWidth + 30)    // set x position
                        .attr("y", ((grillrightY + grillrightHeight + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2) + 5) // set y position
                        .attr("transform", `rotate(-90, ${grillrightX + grillrightWidth + 30}, ${((grillrightY + grillrightHeight + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2)})`) // rotate around the text's position
                        .text(ATGDistanceFromBottomOfDoor);
            
            }
            function LeftAirTransferGrill(ATGDistanceFromBottomOfDoor,ATGheight,ATGwidth){
                const grillX = ix +FrameThicknessForMap + GapForMap+ (LeafWidth1ForMap / 2) - ((ATGwidth / 5) / 2);
                const grillY = iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (ATGDistanceFromBottomOfDoor / 5) - (ATGheight / 5);
                const grillWidth = ATGwidth / 5;
                const grillHeight = ATGheight / 5;

                // Draw the outer rectangle
                svg.append('rect')
                    .attr('x', grillX)
                    .attr('y', grillY)
                    .attr('width', grillWidth)
                    .attr('height', grillHeight)
                    .attr('stroke', 'black')
                    .attr('fill', '#D0D0C6');

                // Draw the horizontal slots
                const numSlots = ATGheight / 20; // Number of horizontal slots
                const slotHeight = grillHeight / (2 * numSlots); // Slot height
                const gapHeight = slotHeight; // Gap between slots

                for (let i = 0; i < numSlots; i++) {
                    svg.append('rect')
                        .attr('x', grillX + 2) // Slight margin from the left
                        .attr('y', grillY + i * (slotHeight + gapHeight) + 2) // Space out slots
                        .attr('width', grillWidth - 4) // Slight margin on both sides
                        .attr('height', slotHeight)
                        .attr('fill', 'black'); // Black slots
                }
                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", grillX - 5)
                    .attr("y1", grillY)
                    .attr("x2", grillX - 5)
                    .attr("y2", grillY + grillHeight)
                    .attr("marker-start", "url(#arrowLeft)")
                    .attr("marker-end", "url(#arrowRight)")

                svg.append("text")            // append text
                    .style("fill", "black")   // make the text
                    .attr("font-size", 10)
                    .attr("x", grillX - 10)    // set x position
                    .attr("y", grillY + (grillHeight / 2) + 5) // set y position
                    .attr("transform", `rotate(-90, ${grillX - 10}, ${grillY + (grillHeight / 2) + 5})`) // rotate around the text's position
                    .text(ATGheight);


                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", grillX)
                    .attr("y1", grillY)
                    .attr("x2", grillX)
                    .attr("y2", grillY - 10)

                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", grillX + grillWidth)
                    .attr("y1", grillY)
                    .attr("x2", grillX + grillWidth)
                    .attr("y2", grillY - 10)

                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", grillX)
                    .attr("y1", grillY - 5)
                    .attr("x2", grillX + grillWidth)
                    .attr("y2", grillY - 5)
                    .attr("marker-start", "url(#arrowLeft)")
                    .attr("marker-end", "url(#arrowRight)")

                svg.append("text")
                    .style("fill", "black")
                    .attr("font-size", 10)
                    .attr("x", grillX + (grillWidth / 2) - 5)
                    .attr("y", grillY - 10)
                    .text(ATGwidth);

                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", grillX)
                    .attr("y1", grillY)
                    .attr("x2", grillX - 10)
                    .attr("y2", grillY)



                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", grillX)
                        .attr("y1", grillY + grillHeight)
                        .attr("x2", grillX - 30)
                        .attr("y2", grillY + grillHeight)

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", grillX - 25)
                        .attr("y1", grillY + grillHeight)
                        .attr("x2", grillX - 25)
                        .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")   // make the text
                        .attr("font-size", 10)
                        .attr("x", grillX - 30)    // set x position
                        .attr("y", ((grillY + grillHeight + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2) + 5) // set y position
                        .attr("transform", `rotate(-90, ${grillX - 30}, ${((grillY + grillHeight + iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap) / 2) + 5})`) // rotate around the text's position
                        .text(ATGDistanceFromBottomOfDoor);
            
            }
                    const lockDistance = LockNLatchesDistanceFromBottomOfDoor / 5;
                   const cylinderDistance = CylindersDistanceFromBottomOfDoor / 5;
                   const thumbturnDistance = ThumbturnDistanceFromBottomOfDoor / 5;
                   const lockDistanceFromEdge = LockNLatchesDistanceFromLeadingEdgeOfDoor / 5;
                   const cylinderDistanceFromEdge = CylindersDistanceFromLeadingEdgeOfDoor / 5;
                   const thumbturnDistanceFromEdge =  ThumbturnDistanceFromLeadingEdgeOfDoor/ 5;
                   const lockDistance2 = LockNLatchesDistanceFromBottomOfDoor2 / 5;
                   const cylinderDistance2 = CylindersDistanceFromBottomOfDoor2 / 5;
                   const thumbturnDistance2 = ThumbturnDistanceFromBottomOfDoor2 / 5;
                   const lockDistanceFromEdge2 = LockNLatchesDistanceFromLeadingEdgeOfDoor2 / 5;
                   const cylinderDistanceFromEdge2 = CylindersDistanceFromLeadingEdgeOfDoor2 / 5;
                   const thumbturnDistanceFromEdge2 =  ThumbturnDistanceFromLeadingEdgeOfDoor2 / 5;

                   // Collect distances in an array
                   const distances = [lockDistance, cylinderDistance, thumbturnDistance];
               const distanceFromEdge=[lockDistanceFromEdge,cylinderDistanceFromEdge,thumbturnDistanceFromEdge]

               const uniqueDistances = Array.from(new Set(distances)).filter(num => num !== 0);

               const uniqueDistancesFromEdge=Array.from(new Set(distanceFromEdge)).filter(num => num !== 0);

               const distances2= [lockDistance2, cylinderDistance2, thumbturnDistance2];
               const distanceFromEdge2=[lockDistanceFromEdge2,cylinderDistanceFromEdge2,thumbturnDistanceFromEdge2]

               const uniqueDistances2 = Array.from(new Set(distances2)).filter(num => num !== 0);

               const uniqueDistancesFromEdge2=Array.from(new Set(distanceFromEdge2)).filter(num => num !== 0);
                   function conditionalRenderItem(distance, label,distanceFromEdge,textdistanceLeaf2,linedistanceLeaf2,textdistanceLeaf1,linedistanceLeaf1) {
                       if (distance !== 0 ) { // Check if the distance is not zero

                        if (LeafWidth2ForMap == LeafWidth1ForMap){
                            if(Handing == 'DD'){
                                 renderItem(distance, label,distanceFromEdge,textdistanceLeaf2,linedistanceLeaf2,textdistanceLeaf1,linedistanceLeaf1);
                            }
                            else{
                                 renderItem2(distance, label,distanceFromEdge,textdistanceLeaf2,linedistanceLeaf2,textdistanceLeaf1,linedistanceLeaf1);
                            }
                        }
                            if(LeafWidth2ForMap > LeafWidth1ForMap){ //right
                            renderItem2(distance, label,distanceFromEdge,textdistanceLeaf2,linedistanceLeaf2,textdistanceLeaf1,linedistanceLeaf1);
                            }
                            if(LeafWidth2ForMap < LeafWidth1ForMap){ //left
                             renderItem(distance, label,distanceFromEdge,textdistanceLeaf2,linedistanceLeaf2,textdistanceLeaf1,linedistanceLeaf1);
                            }
                       }
                   }
                    function conditionalRenderItemleft(distance, label,distanceFromEdge,textdistanceLeaf2,linedistanceLeaf2,textdistanceLeaf1,linedistanceLeaf1) {
 renderItem(distance, label,distanceFromEdge,textdistanceLeaf2,linedistanceLeaf2,textdistanceLeaf1,linedistanceLeaf1);
                    }

                     function conditionalRenderItemright(distance, label,distanceFromEdge,textdistanceLeaf2,linedistanceLeaf2,textdistanceLeaf1,linedistanceLeaf1) {
 renderItem2(distance, label,distanceFromEdge,textdistanceLeaf2,linedistanceLeaf2,textdistanceLeaf1,linedistanceLeaf1);
                    }

                   // Function to render an item
                   function renderItem(distance, label, edgeDistance,textdistanceLeaf2,linedistanceLeaf2,textdistanceLeaf1,linedistanceLeaf1) {

                    //    if(LeafWidth2ForMap<LeafWidth1ForMap){
                           svg.append("circle")
                               .style("stroke", "black")
                               .style("fill", "grey")
                               .attr("r", 5)
                               .attr("cx", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - edgeDistance)-2.5)
                               .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - distance));

                           svg.append('line')
                               .style("stroke", "black")
                               .style("stroke-width", 0.5)
                               .attr("x1", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - edgeDistance))
                               .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - distance))
                               .attr("x2", ix - SideLightPanel1WidthSpaceForVerticalLines - linedistanceLeaf1)
                               .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - distance));
                           svg.append('line')
                               .style("stroke", "black")
                               .style("stroke-width", 0.5)
                               .attr("x1", ix - SideLightPanel1WidthSpaceForVerticalLines - linedistanceLeaf1)
                               .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - distance))
                               .attr("x2", ix - SideLightPanel1WidthSpaceForVerticalLines - linedistanceLeaf1)
                               .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                               .attr("marker-start", "url(#arrowLeft)")
                               .attr("marker-end", "url(#arrowRight)");

                           svg.append("text")
                               .style("fill", "black")
                               .attr("font-size", 10)
                               .attr("x",ix - SideLightPanel1WidthSpaceForVerticalLines - textdistanceLeaf1 -10)
                               .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distance / 2)))
                               .attr("transform", `rotate(-90, ${ix - SideLightPanel1WidthSpaceForVerticalLines - textdistanceLeaf1 -10}, ${iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distance / 2))})`)
                               .text(label);
                    //    }
                      
                   }

                     function renderItem2(distance, label, edgeDistance,textdistanceLeaf2,linedistanceLeaf2,textdistanceLeaf1,linedistanceLeaf1){
                           svg.append("circle")
                               .style("stroke", "black")
                               .style("fill", "grey")
                               .attr("r", 5)
                               .attr("cx", ix + FrameThicknessForMap + GapForMap+MeetingStiles+LeafWidth1ForMap + edgeDistance+2.5)
                               .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - distance));
                               // ix + FrameThicknessForMap + GapForMap +MeetingStiles+LeafWidth1ForMap+ (LeverHandleDistanceFromLeadingEdgeOfDoor / 5) + 3.5
                           svg.append('line')
                               .style("stroke", "black")
                               .style("stroke-width", 0.5)
                               .attr("x1", ix + FrameThicknessForMap + GapForMap+MeetingStiles+LeafWidth1ForMap + edgeDistance+2.5)
                               .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - distance))
                               .attr("x2", ix + FrameThicknessForMap + GapForMap+MeetingStiles+LeafWidth2ForMap + LeafWidth1ForMap +SideLightPanel2WidthSpaceForVerticalLines+ linedistanceLeaf2)
                               .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - distance));

                           svg.append('line')
                               .style("stroke", "black")
                               .style("stroke-width", 0.5)
                               .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap+SideLightPanel2WidthSpaceForVerticalLines + linedistanceLeaf2)
                               .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - distance))
                               .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap+SideLightPanel2WidthSpaceForVerticalLines  + linedistanceLeaf2)
                               .attr("y2", iy + TopFrameHeight + GapForMap+ LeafHeightNoOPForMap)
                               .attr("marker-start", "url(#arrowLeft)")
                               .attr("marker-end", "url(#arrowRight)");

                           svg.append("text")
                               .style("fill", "black")
                               .attr("font-size", 10)
                               .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap+SideLightPanel2WidthSpaceForVerticalLines + textdistanceLeaf2)
                               .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distance / 2)))
                               .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap+SideLightPanel2WidthSpaceForVerticalLines + textdistanceLeaf2}, ${iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (distance / 2))})`)
                               .text(label);
                       
                    }
if((IsLockNLatchesEnable || IsThumbturnEnable || IsCylindersEnable) && (!IsLockNLatchesEnable2 && !IsThumbturnEnable2 && !IsCylindersEnable2)){
                   if (uniqueDistances.length === 1) {
                    if(uniqueDistancesFromEdge.length ===1){
                        conditionalRenderItem(uniqueDistances[0], uniqueDistances[0] * 5,uniqueDistancesFromEdge[0],165,170,165,170);
                    }else{
                        conditionalRenderItem(uniqueDistances[0], uniqueDistances[0] * 5,0,165,170,165,170);
                    }
                } else if (uniqueDistances.length === 2) {
                    // Two distances are the same; show one item for each unique distance
                    console.log(uniqueDistancesFromEdge.length,'llllllllllllllllllllllll')
                    if(uniqueDistancesFromEdge.length === 1){
                        conditionalRenderItem(uniqueDistances[0], uniqueDistances[0] * 5,uniqueDistancesFromEdge[0],165,170,165,170);
                        conditionalRenderItem(uniqueDistances[1], uniqueDistances[1] * 5,uniqueDistancesFromEdge[0],180,185,180,185);
                    }else if(uniqueDistancesFromEdge.length === 2){
                       conditionalRenderItem(uniqueDistances[0], uniqueDistances[0] * 5,uniqueDistancesFromEdge[0],165,170,165,170);
                       conditionalRenderItem(uniqueDistances[1], uniqueDistances[1] * 5,uniqueDistancesFromEdge[1],180,185,180,185);
                    }else{
                       conditionalRenderItem(uniqueDistances[0], uniqueDistances[0] * 5,0,165,170,165,170);
                       conditionalRenderItem(uniqueDistances[1], uniqueDistances[1] * 5,0,180,185,180,185);
                    }

                } else {
                    if(uniqueDistancesFromEdge.length ===1){
                        conditionalRenderItem(lockDistance, lockDistance * 5,uniqueDistancesFromEdge[0],165,170,165,170);
                       conditionalRenderItem(cylinderDistance, cylinderDistance * 5,uniqueDistancesFromEdge[0],180,185,180,185);
                       conditionalRenderItem(thumbturnDistance, thumbturnDistance * 5,uniqueDistancesFromEdge[0],200,205,200,205);
                     }else if(uniqueDistancesFromEdge.length ===2){
            const matchingIndices = [];
            const seen = new Map();
                distanceFromEdge.forEach((value, index) => {
                    if (value !== 0) {
                        if (seen.has(value)) {
                            matchingIndices.push([seen.get(value), index]);
                        } else {
                            seen.set(value, index);
                        }
                    }
                });
                        conditionalRenderItem(lockDistance, lockDistance * 5,uniqueDistancesFromEdge[0],165,170,165,170);
                       conditionalRenderItem(cylinderDistance, cylinderDistance * 5,uniqueDistancesFromEdge[1],180,185,180,185);
                       conditionalRenderItem(thumbturnDistance, thumbturnDistance * 5,uniqueDistancesFromEdge[matchingIndices[0][1]],200,205,200,205);
                     }  else if(uniqueDistancesFromEdge.length ===3){
                        conditionalRenderItem(lockDistance, lockDistance * 5,uniqueDistancesFromEdge[0],165,170,165,170);
                        conditionalRenderItem(cylinderDistance, cylinderDistance * 5,uniqueDistancesFromEdge[1],180,185,180,185);
                        conditionalRenderItem(thumbturnDistance, thumbturnDistance * 5,uniqueDistancesFromEdge[2],200,205,200,205);
                     }else{
                        conditionalRenderItem(lockDistance, lockDistance * 5,0,165,170,165,170);
                        conditionalRenderItem(cylinderDistance, cylinderDistance * 5,0,180,185,180,185);
                        conditionalRenderItem(thumbturnDistance, thumbturnDistance * 5,0,200,205,200,205);
                     }

                }
            }

            if((IsLockNLatchesEnable2 || IsThumbturnEnable2 || IsCylindersEnable2)){
                   if (uniqueDistances.length === 1) {
                    if(uniqueDistancesFromEdge.length ===1){
                        conditionalRenderItemleft(uniqueDistances[0], uniqueDistances[0] * 5,uniqueDistancesFromEdge[0],165,170,165,170);
                    }else{
                        conditionalRenderItemleft(uniqueDistances[0], uniqueDistances[0] * 5,0,165,170,165,170);
                    }
                } else if (uniqueDistances.length === 2) {
                    // Two distances are the same; show one item for each unique distance
                    console.log(uniqueDistancesFromEdge.length,'llllllllllllllllllllllll')
                    if(uniqueDistancesFromEdge.length === 1){
                        conditionalRenderItemleft(uniqueDistances[0], uniqueDistances[0] * 5,uniqueDistancesFromEdge[0],165,170,165,170);
                        conditionalRenderItemleft(uniqueDistances[1], uniqueDistances[1] * 5,uniqueDistancesFromEdge[0],180,185,180,185);
                    }else if(uniqueDistancesFromEdge.length === 2){
                       conditionalRenderItemleft(uniqueDistances[0], uniqueDistances[0] * 5,uniqueDistancesFromEdge[0],165,170,165,170);
                       conditionalRenderItemleft(uniqueDistances[1], uniqueDistances[1] * 5,uniqueDistancesFromEdge[1],180,185,180,185);
                    }else{
                       conditionalRenderItemleft(uniqueDistances[0], uniqueDistances[0] * 5,0,165,170,165,170);
                       conditionalRenderItemleft(uniqueDistances[1], uniqueDistances[1] * 5,0,180,185,180,185);
                    }

                } else {
                    if(uniqueDistancesFromEdge.length ===1){
                        conditionalRenderItemleft(lockDistance, lockDistance * 5,uniqueDistancesFromEdge[0],165,170,165,170);
                       conditionalRenderItemleft(cylinderDistance, cylinderDistance * 5,uniqueDistancesFromEdge[0],180,185,180,185);
                       conditionalRenderItemleft(thumbturnDistance, thumbturnDistance * 5,uniqueDistancesFromEdge[0],200,205,200,205);
                     }else if(uniqueDistancesFromEdge.length ===2){
            const matchingIndices = [];
            const seen = new Map();
                distanceFromEdge.forEach((value, index) => {
                    if (value !== 0) {
                        if (seen.has(value)) {
                            matchingIndices.push([seen.get(value), index]);
                        } else {
                            seen.set(value, index);
                        }
                    }
                });
                        conditionalRenderItemleft(lockDistance, lockDistance * 5,uniqueDistancesFromEdge[0],165,170,165,170);
                       conditionalRenderItemleft(cylinderDistance, cylinderDistance * 5,uniqueDistancesFromEdge[1],180,185,180,185);
                       conditionalRenderItemleft(thumbturnDistance, thumbturnDistance * 5,uniqueDistancesFromEdge[matchingIndices[0][1]],200,205,200,205);
                     }  else if(uniqueDistancesFromEdge.length ===3){
                        conditionalRenderItemleft(lockDistance, lockDistance * 5,uniqueDistancesFromEdge[0],165,170,165,170);
                        conditionalRenderItemleft(cylinderDistance, cylinderDistance * 5,uniqueDistancesFromEdge[1],180,185,180,185);
                        conditionalRenderItemleft(thumbturnDistance, thumbturnDistance * 5,uniqueDistancesFromEdge[2],200,205,200,205);
                     }else{
                        conditionalRenderItemleft(lockDistance, lockDistance * 5,0,165,170,165,170);
                        conditionalRenderItemleft(cylinderDistance, cylinderDistance * 5,0,180,185,180,185);
                        conditionalRenderItemleft(thumbturnDistance, thumbturnDistance * 5,0,200,205,200,205);
                     }

                }

                  if (uniqueDistances2.length === 1) {
                    if(uniqueDistancesFromEdge2.length ===1){
                        conditionalRenderItemright(uniqueDistances2[0], uniqueDistances2[0] * 5,uniqueDistancesFromEdge2[0],165,170,165,170);
                    }else{
                        conditionalRenderItemright(uniqueDistances2[0], uniqueDistances2[0] * 5,0,165,170,165,170);
                    }
                } else if (uniqueDistances2.length === 2) {
                    // Two distances are the same; show one item for each unique distance
                    console.log(uniqueDistancesFromEdge2.length,'llllllllllllllllllllllll')
                    if(uniqueDistancesFromEdge2.length === 1){
                        conditionalRenderItemright(uniqueDistances2[0], uniqueDistances2[0] * 5,uniqueDistancesFromEdge2[0],165,170,165,170);
                        conditionalRenderItemright(uniqueDistances2[1], uniqueDistances2[1] * 5,uniqueDistancesFromEdge2[0],180,185,180,185);
                    }else if(uniqueDistancesFromEdge2.length === 2){
                       conditionalRenderItemright(uniqueDistances2[0], uniqueDistances2[0] * 5,uniqueDistancesFromEdge2[0],165,170,165,170);
                       conditionalRenderItemright(uniqueDistances2[1], uniqueDistances2[1] * 5,uniqueDistancesFromEdge2[1],180,185,180,185);
                    }else{
                       conditionalRenderItemright(uniqueDistances2[0], uniqueDistances2[0] * 5,0,165,170,165,170);
                       conditionalRenderItemright(uniqueDistances2[1], uniqueDistances2[1] * 5,0,180,185,180,185);
                    }

                } else {
                    if(uniqueDistancesFromEdge.length ===1){
                        conditionalRenderItemleft(lockDistance2, lockDistance2 * 5,uniqueDistancesFromEdge2[0],165,170,165,170);
                       conditionalRenderItemleft(cylinderDistance2, cylinderDistance2 * 5,uniqueDistancesFromEdge2[0],180,185,180,185);
                       conditionalRenderItemleft(thumbturnDistance2, thumbturnDistance2 * 5,uniqueDistancesFromEdge2[0],200,205,200,205);
                     }else if(uniqueDistancesFromEdge2.length ===2){
            const matchingIndices = [];
            const seen = new Map();
                distanceFromEdge2.forEach((value, index) => {
                    if (value !== 0) {
                        if (seen.has(value)) {
                            matchingIndices.push([seen.get(value), index]);
                        } else {
                            seen.set(value, index);
                        }
                    }
                });
                        conditionalRenderItemleft(lockDistance2, lockDistance2 * 5,uniqueDistancesFromEdge2[0],165,170,165,170);
                       conditionalRenderItemleft(cylinderDistance2, cylinderDistance2 * 5,uniqueDistancesFromEdge2[1],180,185,180,185);
                       conditionalRenderItemleft(thumbturnDistance2, thumbturnDistance2 * 5,uniqueDistancesFromEdge2[matchingIndices[0][1]],200,205,200,205);
                     }  else if(uniqueDistancesFromEdge2.length ===3){
                        conditionalRenderItemleft(lockDistance2, lockDistance2 * 5,uniqueDistancesFromEdge2[0],165,170,165,170);
                        conditionalRenderItemleft(cylinderDistance2, cylinderDistance2 * 5,uniqueDistancesFromEdge2[1],180,185,180,185);
                        conditionalRenderItemleft(thumbturnDistance2, thumbturnDistance2 * 5,uniqueDistancesFromEdge2[2],200,205,200,205);
                     }else{
                        conditionalRenderItemleft(lockDistance2, lockDistance2 * 5,0,165,170,165,170);
                        conditionalRenderItemleft(cylinderDistance2, cylinderDistance2 * 5,0,180,185,180,185);
                        conditionalRenderItemleft(thumbturnDistance2, thumbturnDistance2 * 5,0,200,205,200,205);
                     }

                }
            }
            }
        }

        /* Ironmongery */

        var TotalKickPlateSectionHeight = KickPlatesHeight;

        if (KickPlatesHeight > 0) {
            TotalKickPlateSectionHeight = KickPlatesHeight + 3;
        }
        // 07-01-2024 (Note:  there is issue for vision panel james sir give temporary solution regarding we not able to change leaf height 1 so whenevre the kickplates selected TotalKickPlateSectionHeight is always 0 )
        if (KickPlatesHeight > 0) {
            TotalKickPlateSectionHeight = 0;
        }
        //end 07-01-2024
        var RemainingHeightOfLeaf = LeafHeightNoOPForMap - TotalKickPlateSectionHeight;

        if (Leaf1VisionPanel == "Yes") {

            if (AreVPsEqualSizesForLeaf1 != "Yes") {

                Leaf1VisionPanel1Height = $('input[name="vP1Height1"]').val();
                if (Leaf1VisionPanel1Height == "") {
                    Leaf1VisionPanel1Height = 0;
                } else {
                    Leaf1VisionPanel1HeightToShow = parseFloat(Leaf1VisionPanel1Height);
                    if (Leaf1VisionPanel1Height > 0) {
                        Leaf1VisionPanel1Height = parseFloat(Leaf1VisionPanel1Height) / 5;
                    }
                }

                Leaf1VisionPanel2Height = $('input[name="vP1Height2"]').val();
                if (Leaf1VisionPanel2Height == "") {
                    Leaf1VisionPanel2Height = 0;
                } else {
                    Leaf1VisionPanel2HeightToShow = parseFloat(Leaf1VisionPanel2Height);
                    if (Leaf1VisionPanel2Height > 0) {
                        Leaf1VisionPanel2Height = parseFloat(Leaf1VisionPanel2Height) / 5;
                    }
                }

                Leaf1VisionPanel3Height = $('input[name="vP1Height3"]').val();
                if (Leaf1VisionPanel3Height == "") {
                    Leaf1VisionPanel3Height = 0;
                } else {
                    Leaf1VisionPanel3HeightToShow = parseFloat(Leaf1VisionPanel3Height);
                    if (Leaf1VisionPanel3Height > 0) {
                        Leaf1VisionPanel3Height = parseFloat(Leaf1VisionPanel3Height) / 5;
                    }
                }

                Leaf1VisionPanel4Height = $('input[name="vP1Height4"]').val();
                if (Leaf1VisionPanel4Height == "") {
                    Leaf1VisionPanel4Height = 0;
                } else {
                    Leaf1VisionPanel4HeightToShow = parseFloat(Leaf1VisionPanel4Height);
                    if (Leaf1VisionPanel4Height > 0) {
                        Leaf1VisionPanel4Height = parseFloat(Leaf1VisionPanel4Height) / 5;
                    }
                }

                Leaf1VisionPanel5Height = $('input[name="vP1Height5"]').val();
                if (Leaf1VisionPanel5Height == "") {
                    Leaf1VisionPanel5Height = 0;
                } else {
                    Leaf1VisionPanel5HeightToShow = parseFloat(Leaf1VisionPanel5Height);
                    if (Leaf1VisionPanel5Height > 0) {
                        Leaf1VisionPanel5Height = parseFloat(Leaf1VisionPanel5Height) / 5;
                    }
                }

            } else {
                var VP1Height1 = $('input[name="vP1Height1"]').val();
                if (VP1Height1 == "") {
                    VP1Height1 = 0;
                } else {
                    Leaf1VisionPanel1HeightToShow = Leaf1VisionPanel2HeightToShow = Leaf1VisionPanel3HeightToShow = Leaf1VisionPanel4HeightToShow = Leaf1VisionPanel5HeightToShow = parseFloat(VP1Height1);
                    if (VP1Height1 > 0) {
                        VP1Height1 = parseFloat(VP1Height1) / 5;
                    }
                }

                Leaf1VisionPanel1Height = Leaf1VisionPanel2Height = Leaf1VisionPanel3Height = Leaf1VisionPanel4Height = Leaf1VisionPanel5Height = VP1Height1;
            }

            if ($.inArray(VisionPanelShape, ["Diamond", "Circle", "Square"]) !== -1) {

                $('select[name="AreVPsEqualSizes"]').attr("readonly", true);
                $('select[name="AreVPsEqualSizes"]').val("Yes");

                AreVPsEqualSizesForLeaf1 = "Yes";

                $('input[name="vP1Height1"]').attr("readonly", true);
                $('input[name="vP1Height2"]').attr("readonly", true);
                $('input[name="vP1Height3"]').attr("readonly", true);
                $('input[name="vP1Height4"]').attr("readonly", true);
                $('input[name="vP1Height5"]').attr("readonly", true);

                $('input[name="vP1Height1"]').val(Leaf1VisionPanelWidthToShow);
                Leaf1VisionPanel1Height = Leaf1VisionPanelWidth;
                Leaf1VisionPanel1HeightToShow = Leaf1VisionPanelWidthToShow;

                if (VisionPanelQuantityForLeaf1 > 1) {
                    $('input[name="vP1Height2"]').val(Leaf1VisionPanelWidthToShow);
                    Leaf1VisionPanel2Height = Leaf1VisionPanelWidth;
                    Leaf1VisionPanel2HeightToShow = Leaf1VisionPanelWidthToShow;
                }

                if (VisionPanelQuantityForLeaf1 > 2) {
                    $('input[name="vP1Height3"]').val(Leaf1VisionPanelWidthToShow);
                    Leaf1VisionPanel3Height = Leaf1VisionPanelWidth;
                    Leaf1VisionPanel3HeightToShow = Leaf1VisionPanelWidthToShow;
                }

                if (VisionPanelQuantityForLeaf1 > 3) {
                    $('input[name="vP1Height4"]').val(Leaf1VisionPanelWidthToShow);
                    Leaf1VisionPanel4Height = Leaf1VisionPanelWidth;
                    Leaf1VisionPanel4HeightToShow = Leaf1VisionPanelWidthToShow;
                }

                if (VisionPanelQuantityForLeaf1 > 4) {
                    $('input[name="vP1Height5"]').val(Leaf1VisionPanelWidthToShow);
                    Leaf1VisionPanel5Height = Leaf1VisionPanelWidth;
                    Leaf1VisionPanel5HeightToShow = Leaf1VisionPanelWidthToShow;
                }

            } else {

                $('select[name="AreVPsEqualSizes"]').removeAttr("readonly");

                if (AreVPsEqualSizesForLeaf1 == "No") {

                    $('input[name="vP1Height1"]').removeAttr("readonly");

                    if (VisionPanelQuantityForLeaf1 > 1) {
                        $('input[name="vP1Height2"]').removeAttr("readonly");
                    }

                    if (VisionPanelQuantityForLeaf1 > 2) {
                        $('input[name="vP1Height3"]').removeAttr("readonly");
                    }

                    if (VisionPanelQuantityForLeaf1 > 3) {
                        $('input[name="vP1Height4"]').removeAttr("readonly");
                    }

                    if (VisionPanelQuantityForLeaf1 > 4) {
                        $('input[name="vP1Height5"]').removeAttr("readonly");
                    }

                }

            }

            var RemainingWidthOfVisionPanelForLeftLeaf = 0;
            var TotalHeightOfVisionPanelForLeftLeaf = 0;

            RemainingWidthOfVisionPanelForLeftLeaf = LeafWidth1 - (parseFloat(DistanceFromTheEdgeOfDoorForLeaf1ToShow) + parseFloat(Leaf1VisionPanelWidthToShow));
            TotalHeightOfVisionPanelForLeftLeaf = (parseFloat(DistanceFromTopOfDoorForLeaf1)) + parseFloat(Leaf1VisionPanel1Height);
            // TotalHeightOfVisionPanelForLeftLeaf = (parseFloat(DistanceFromTopOfDoorForLeaf1) * 2) + parseFloat(Leaf1VisionPanel1Height);

            if (VisionPanelQuantityForLeaf1 > 1) {
                TotalHeightOfVisionPanelForLeftLeaf = parseFloat(TotalHeightOfVisionPanelForLeftLeaf) + parseFloat(DistanceBetweenVPsForLeaf1) + parseFloat(Leaf1VisionPanel2Height);
            }

            if (VisionPanelQuantityForLeaf1 > 2) {
                TotalHeightOfVisionPanelForLeftLeaf = parseFloat(TotalHeightOfVisionPanelForLeftLeaf) + parseFloat(DistanceBetweenVPsForLeaf1) + parseFloat(Leaf1VisionPanel3Height);
            }

            if (VisionPanelQuantityForLeaf1 > 3) {
                TotalHeightOfVisionPanelForLeftLeaf = parseFloat(TotalHeightOfVisionPanelForLeftLeaf) + parseFloat(DistanceBetweenVPsForLeaf1) + parseFloat(Leaf1VisionPanel4Height);
            }

            if (VisionPanelQuantityForLeaf1 > 4) {
                TotalHeightOfVisionPanelForLeftLeaf = parseFloat(TotalHeightOfVisionPanelForLeftLeaf) + parseFloat(DistanceBetweenVPsForLeaf1) + parseFloat(Leaf1VisionPanel5Height);
            }

            // alert(TotalWidthOfVisionPanelForLeftLeaf + "-" + LeafWidth1ForMap + "-" + TotalHeightOfVisionPanelForLeftLeaf + "-" + LeafHeightNoOPForMap);

            //console.log(RemainingWidthOfVisionPanelForLeftLeaf+ '--' +DistanceFromTheEdgeOfDoorForLeaf1ToShow);
            console.log(LeafWidth1 + '--' + DistanceFromTheEdgeOfDoorMinValue + '--' + Leaf1VisionPanelWidthToShow);

            if (RemainingWidthOfVisionPanelForLeftLeaf < DistanceFromTheEdgeOfDoorMinValue) {

                if (VisionPanelShape == "Rectangle") {
                    DistanceFromTheEdgeOfDoorForLeaf1ToShow = parseFloat(LeafWidth1) - (parseFloat(DistanceFromTheEdgeOfDoorMinValue) + parseFloat(Leaf1VisionPanelWidthToShow));
                    DistanceFromTheEdgeOfDoorForLeaf1 = DistanceFromTheEdgeOfDoorForLeaf1ToShow / 5;

                    $('input[name="distanceFromTheEdgeOfDoor"]').val(DistanceFromTheEdgeOfDoorMinValue);

                    swal('Warning!', "Distance of vision panel from left edge should be atleast " + DistanceFromTheEdgeOfDoorMinValue + "mm");
                }


                if ($.inArray(VisionPanelShape, ["Diamond", "Circle", "Square"]) !== -1) {

                    DistanceFromTheEdgeOfDoorForLeaf1ToShow = DistanceFromTheEdgeOfDoorMinValue;
                    DistanceFromTheEdgeOfDoorForLeaf1 = DistanceFromTheEdgeOfDoorMinValue / 5;

                    $('input[name="distanceFromTheEdgeOfDoor"]').val(DistanceFromTheEdgeOfDoorMinValue);

                    // var NewLeaf1VisionPanelWidth = LeafWidth1 - (parseFloat(DistanceFromTheEdgeOfDoorForLeaf1ToShow) * 2);
                    // Leaf1VisionPanelWidth = NewLeaf1VisionPanelWidth/5;
                    // Leaf1VisionPanelWidthToShow = NewLeaf1VisionPanelWidth;
                    // $("#vP1Width").val(NewLeaf1VisionPanelWidth);
                } else {
                    //swal('Warning!',"Distance of vision panel from left edge should not be less than the distance from right edge.");
                }
            }

            console.log(TotalHeightOfVisionPanelForLeftLeaf + '--' + RemainingHeightOfLeaf);

            if (TotalHeightOfVisionPanelForLeftLeaf >= RemainingHeightOfLeaf) {

                DistanceFromTopOfDoorForLeaf1ToShow = DistanceFromTopOfDoorMinValue;
                DistanceFromTopOfDoorForLeaf1 = DistanceFromTopOfDoorMinValue / 5;

                $('input[name="distanceFromTopOfDoor"]').val(DistanceFromTopOfDoorMinValue);

                DistanceBetweenVPsForLeaf1ToShow = DistanceBetweenVPsMinValue;
                DistanceBetweenVPsForLeaf1 = DistanceBetweenVPsMinValue / 5;

                $('input[name="distanceBetweenVPs"]').val(DistanceBetweenVPsForLeaf1ToShow);

                var NumberOfVps = VisionPanelQuantityForLeaf1 - 1;

                var NewLeaf1VisionPanelHeight = (LeafHeightNoOP - ((KickPlatesHeight * 5) + 3)) - ((parseFloat(DistanceFromTopOfDoorForLeaf1ToShow) * 2) + (parseFloat(DistanceBetweenVPsForLeaf1ToShow) * NumberOfVps));
                //alert(NewLeaf1VisionPanelHeight);
                console.log(NewLeaf1VisionPanelHeight);
                NewLeaf1VisionPanelHeight = Math.floor(NewLeaf1VisionPanelHeight / VisionPanelQuantityForLeaf1);

                console.log(NewLeaf1VisionPanelHeight);

                if (KickPlatesHeight > 0) {
                    NewLeaf1VisionPanelHeight = NewLeaf1VisionPanelHeight - 3;
                }

                if ($.inArray(VisionPanelShape, ["Diamond", "Circle", "Square"]) !== -1) {

                    if (NewLeaf1VisionPanelHeight > (LeafWidth1 - (parseFloat(DistanceFromTheEdgeOfDoorForLeaf1ToShow) * 2))) {

                        NewLeaf1VisionPanelHeight = LeafWidth1 - (parseFloat(DistanceFromTheEdgeOfDoorForLeaf1ToShow) * 2);

                        // NewLeaf1VisionPanelHeight = Math.floor(NewLeaf1VisionPanelHeight/VisionPanelQuantityForLeaf1);

                        if (KickPlatesHeight > 0) {
                            NewLeaf1VisionPanelHeight = NewLeaf1VisionPanelHeight - 3;
                        }
                    }

                    Leaf1VisionPanelWidth = NewLeaf1VisionPanelHeight / 5;
                    Leaf1VisionPanelWidthToShow = NewLeaf1VisionPanelHeight;
                    $("#vP1Width").val(NewLeaf1VisionPanelHeight);
                }

                console.log(NewLeaf1VisionPanelHeight);
                //alert(NewLeaf1VisionPanelHeight);

                Leaf1VisionPanel1Height = NewLeaf1VisionPanelHeight / 5;
                Leaf1VisionPanel1HeightToShow = NewLeaf1VisionPanelHeight;
                $("#vP1Height1").val(NewLeaf1VisionPanelHeight);

                if (VisionPanelQuantityForLeaf1 > 1) {
                    Leaf1VisionPanel2Height = NewLeaf1VisionPanelHeight / 5;
                    Leaf1VisionPanel2HeightToShow = NewLeaf1VisionPanelHeight;
                    $("#vP1Height2").val(NewLeaf1VisionPanelHeight);
                }

                if (VisionPanelQuantityForLeaf1 > 2) {
                    Leaf1VisionPanel3Height = NewLeaf1VisionPanelHeight / 5;
                    Leaf1VisionPanel3HeightToShow = NewLeaf1VisionPanelHeight;
                    $("#vP1Height3").val(NewLeaf1VisionPanelHeight);
                }

                if (VisionPanelQuantityForLeaf1 > 3) {
                    Leaf1VisionPanel4Height = NewLeaf1VisionPanelHeight / 5;
                    Leaf1VisionPanel4HeightToShow = NewLeaf1VisionPanelHeight;
                    $("#vP1Height4").val(NewLeaf1VisionPanelHeight);
                }

                if (VisionPanelQuantityForLeaf1 > 4) {
                    Leaf1VisionPanel5Height = NewLeaf1VisionPanelHeight / 5;
                    Leaf1VisionPanel5HeightToShow = NewLeaf1VisionPanelHeight;
                    $("#vP1Height5").val(NewLeaf1VisionPanelHeight);
                }
                //}
                //else if(VisionPanelShape == "Rectangle"){
                //    if(VisionPanelQuantityForLeaf1 == 2){
                //        Leaf1VisionPanel2Height = 0;
                //        Leaf1VisionPanel2HeightToShow = 0;
                //        $("#vP1Height2").val(0);
                //    }else if(VisionPanelQuantityForLeaf1 > 1){
                //        if(ChangedFieldName == "vP1Height2"){
                //            Leaf1VisionPanel2Height = 0;
                //            Leaf1VisionPanel2HeightToShow = 0;
                //            $("#vP1Height2").val(0);
                //        }
                //    }
                //
                //    if(VisionPanelQuantityForLeaf1 == 3){
                //        Leaf1VisionPanel3Height = 0;
                //        Leaf1VisionPanel3HeightToShow = 0;
                //        $("#vP1Height3").val(0);
                //    }else if(VisionPanelQuantityForLeaf1 > 2){
                //        if(ChangedFieldName == "vP1Height3"){
                //            Leaf1VisionPanel3Height = 0;
                //            Leaf1VisionPanel3HeightToShow = 0;
                //            $("#vP1Height3").val(0);
                //        }
                //    }
                //
                //    if(VisionPanelQuantityForLeaf1 == 4){
                //        Leaf1VisionPanel4Height = 0;
                //        Leaf1VisionPanel4HeightToShow = 0;
                //        $("#vP1Height4").val(0);
                //    }else if(VisionPanelQuantityForLeaf1 > 3){
                //        if(ChangedFieldName == "vP1Height4"){
                //            Leaf1VisionPanel4Height = 0;
                //            Leaf1VisionPanel4HeightToShow = 0;
                //            $("#vP1Height4").val(0);
                //        }
                //    }
                //
                //    if(VisionPanelQuantityForLeaf1 == 5){
                //        Leaf1VisionPanel5Height = 0;
                //        Leaf1VisionPanel5HeightToShow = 0;
                //        $("#vP1Height5").val(0);
                //    }else if(VisionPanelQuantityForLeaf1 > 4){
                //        if(ChangedFieldName == "vP1Height5"){
                //            Leaf1VisionPanel5Height = 0;
                //            Leaf1VisionPanel5HeightToShow = 0;
                //            $("#vP1Height5").val(0);
                //        }
                //    }
                //}

                if (ChangedFieldName == "distanceFromTheEdgeOfDoor") {
                    swal('Warning!', "Entered distance from the edge of door of left leaf exceeds the width of left leaf.");
                } else if (ChangedFieldName == "distanceFromTopOfDoor") {
                    swal('Warning!', "Entered distance from top of door of left leaf exceeds the height of left leaf.");
                } else if (ChangedFieldName == "distanceBetweenVPs") {
                    swal('Warning!', "Entered distance between vps of left leaf exceeds the height of left leaf.");
                } else {
                    swal('Warning!', "Sum of all vision panel's height should not be greater than leaf's height.");
                }

            }
            //  VISION PANEL SHOULD BE COME ACCORDING TO Handing (06-01-2023) OLD CODE
            // LeftGapForLeaf1 = FrameThicknessForMap + GapForMap;

            // var LeftSideDistanceOfVisonPanelOfDoorLeaf1ToShow = LeafWidth1 - (parseFloat(DistanceFromTheEdgeOfDoorForLeaf1ToShow) + parseFloat(Leaf1VisionPanelWidthToShow)) ;
            // var LeftSideDistanceOfVisonPanelOfDoorLeaf1 = (LeftSideDistanceOfVisonPanelOfDoorLeaf1ToShow/5) - RemainingGap;

            // var CircleRadius = parseFloat(Leaf1VisionPanelWidth)/2;

            // var DistanceXForLeaf1VPShape = ix + LeftGapForLeaf1 + LeftSideDistanceOfVisonPanelOfDoorLeaf1;
            // var DistanceYForLeaf1VPShape = iy + GapAfterOverPanelApplied + UpperAndLowerGap + parseFloat(DistanceFromTopOfDoorForLeaf1);

            // var DiamondDimension = 'M '+CircleRadius+' 0 '+Leaf1VisionPanelWidth+' '+CircleRadius+' '+CircleRadius+' '+Leaf1VisionPanelWidth+' 0 '+CircleRadius+' Z';


            // VISION PANEL SHOULD BE COME ACCORDING TO Handing (06-01-2023) NEW CODE

            LeftGapForLeaf1 = FrameThicknessForMap + GapForMap;

            var LeftSideDistanceOfVisonPanelOfDoorLeaf1ToShow = LeafWidth1 - (parseFloat(DistanceFromTheEdgeOfDoorForLeaf1ToShow) + parseFloat(Leaf1VisionPanelWidthToShow));


            var CircleRadius = parseFloat(Leaf1VisionPanelWidth) / 2;

            if (Handing == 'Right') {
                var LeftSideDistanceOfVisonPanelOfDoorLeaf1 = (LeftSideDistanceOfVisonPanelOfDoorLeaf1ToShow / 5) - RemainingGap;
                var DistanceXForLeaf1VPShape = ix + LeftGapForLeaf1 + (DistanceFromTheEdgeOfDoorForLeaf1ToShow / 5);
                var DistanceYForLeaf1VPShape = iy + GapAfterOverPanelApplied + UpperAndLowerGap + parseFloat(DistanceFromTopOfDoorForLeaf1);

            } else {
                var LeftSideDistanceOfVisonPanelOfDoorLeaf1 = (LeftSideDistanceOfVisonPanelOfDoorLeaf1ToShow / 5) - RemainingGap;
                var DistanceXForLeaf1VPShape = ix + LeftGapForLeaf1 + LeftSideDistanceOfVisonPanelOfDoorLeaf1;
                var DistanceYForLeaf1VPShape = iy + GapAfterOverPanelApplied + UpperAndLowerGap + parseFloat(DistanceFromTopOfDoorForLeaf1);
            }

            //Vission panel shapes
            var DiamondDimension = 'M ' + CircleRadius + ' 0 ' + Leaf1VisionPanelWidth + ' ' + CircleRadius + ' ' + CircleRadius + ' ' + Leaf1VisionPanelWidth + ' 0 ' + CircleRadius + ' Z';
            if (VisionPanelShape == "Diamond") {
                svg.append('path')
                    .attr('d', DiamondDimension)
                    .attr("fill", "#fff")
                    .style("stroke", "black")
                    .attr("transform", "translate(" + DistanceXForLeaf1VPShape + "," + DistanceYForLeaf1VPShape + ")");

            } else if (VisionPanelShape == "Circle") {
                svg.append("circle")
                    .style("stroke", "black")
                    .style("fill", "#fff")
                    .attr("r", CircleRadius)
                    .attr("cx", DistanceXForLeaf1VPShape + CircleRadius)
                    .attr("cy", DistanceYForLeaf1VPShape + CircleRadius);
            } else {
                svg.append('rect')
                    .attr('x', DistanceXForLeaf1VPShape)
                    .attr('y', DistanceYForLeaf1VPShape)
                    .attr('width', Leaf1VisionPanelWidth)
                    .attr('height', Leaf1VisionPanel1Height)
                    .attr('stroke', 'black')
                    .attr('fill', '#fff');
            }

            if (Leaf1VisionPanel1Height && Leaf1VisionPanelWidth) {
                /* Horizontal Line for vision panel */
                if (ShowMeasurements) {

                    if (Handing == 'Right') {

                        if ((Leaf1VisionPanelWidth * 2) > (LeafWidth1ForMap)) {
                            if (VisionPanelShape == "Diamond" || VisionPanelShape == "Circle") {
                                svg.append("text")
                                    .style("fill", "black")
                                    .attr("font-size", 10)
                                    .attr("x", DistanceXForLeaf1VPShape + (parseFloat(Leaf1VisionPanelWidth) / 2) - 7)
                                    .attr("y", DistanceYForLeaf1VPShape + (Leaf1VisionPanel1Height / 2) + 10)
                                    .text(Leaf1VisionPanelWidthToShow);

                                svg.append('line')
                                    .style("stroke", "black")
                                    .style("stroke-width", 0.5)
                                    .attr("x1", DistanceXForLeaf1VPShape)
                                    .attr("y1", DistanceYForLeaf1VPShape + (Leaf1VisionPanel1Height / 2))
                                    .attr("x2", DistanceXForLeaf1VPShape + parseFloat(Leaf1VisionPanelWidth))
                                    .attr("y2", DistanceYForLeaf1VPShape + (Leaf1VisionPanel1Height / 2))
                                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                                    .attr("marker-end", "url(#arrowRight)");
                            } else {
                                svg.append("text")
                                    .style("fill", "black")
                                    .attr("font-size", 10)
                                    .attr("x", DistanceXForLeaf1VPShape + (parseFloat(Leaf1VisionPanelWidth) / 2) - 7)
                                    .attr("y", iy + TopFrameHeight + GapForMap + 50)
                                    .text(Leaf1VisionPanelWidthToShow);

                                svg.append('line')
                                    .style("stroke", "black")
                                    .style("stroke-width", 0.5)
                                    .attr("x1", DistanceXForLeaf1VPShape)
                                    .attr("y1", iy + TopFrameHeight + GapForMap + 30)
                                    .attr("x2", DistanceXForLeaf1VPShape + parseFloat(Leaf1VisionPanelWidth))
                                    .attr("y2", iy + TopFrameHeight + GapForMap + 30)
                                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                                    .attr("marker-end", "url(#arrowRight)");
                            }


                        } else {
                            svg.append("text")
                                .style("fill", "black")
                                .attr("font-size", 10)
                                .attr("x", DistanceXForLeaf1VPShape + (parseFloat(Leaf1VisionPanelWidth) / 2) - 7)
                                .attr("y", iy - 40)
                                .text(Leaf1VisionPanelWidthToShow);

                            svg.append('line')
                                .style("stroke", "black")
                                .style("stroke-width", 0.5)
                                .attr("x1", DistanceXForLeaf1VPShape)
                                .attr("y1", iy - 35)
                                .attr("x2", DistanceXForLeaf1VPShape + parseFloat(Leaf1VisionPanelWidth))
                                .attr("y2", iy - 35)
                                .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                                .attr("marker-end", "url(#arrowRight)");

                            svg.append('line')
                                .style("stroke", "black")
                                .style("stroke-width", 0.5)
                                .attr("x1", DistanceXForLeaf1VPShape)
                                .attr("y1", iy - 35 - 5)
                                .attr("x2", DistanceXForLeaf1VPShape)
                                .attr("y2", DistanceYForLeaf1VPShape + (Leaf1VisionPanel1Height / 2))

                            svg.append('line')
                                .style("stroke", "black")
                                .style("stroke-width", 0.5)
                                .attr("x1", DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth)
                                .attr("y1", DistanceYForLeaf1VPShape + (Leaf1VisionPanel1Height / 2))
                                .attr("x2", DistanceXForLeaf1VPShape + parseFloat(Leaf1VisionPanelWidth))
                                .attr("y2", iy - 35 - 5)
                        }


                    } else {


                        if ((Leaf1VisionPanelWidth * 2) > (LeafWidth1ForMap)) {
                            if (VisionPanelShape == "Diamond" || VisionPanelShape == "Circle") {
                                svg.append("text")
                                    .style("fill", "black")
                                    .attr("font-size", 10)
                                    .attr("x", DistanceXForLeaf1VPShape + (parseFloat(Leaf1VisionPanelWidth) / 2) - 7)
                                    .attr("y", DistanceYForLeaf1VPShape + (Leaf1VisionPanel1Height / 2) + 10)
                                    .text(Leaf1VisionPanelWidthToShow);

                                svg.append('line')
                                    .style("stroke", "black")
                                    .style("stroke-width", 0.5)
                                    .attr("x1", DistanceXForLeaf1VPShape)
                                    .attr("y1", DistanceYForLeaf1VPShape + (Leaf1VisionPanel1Height / 2))
                                    .attr("x2", DistanceXForLeaf1VPShape + parseFloat(Leaf1VisionPanelWidth))
                                    .attr("y2", DistanceYForLeaf1VPShape + (Leaf1VisionPanel1Height / 2))
                                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                                    .attr("marker-end", "url(#arrowRight)");
                            } else {
                                svg.append("text")
                                    .style("fill", "black")
                                    .attr("font-size", 10)
                                    .attr("x", DistanceXForLeaf1VPShape + (parseFloat(Leaf1VisionPanelWidth) / 2) - 7)
                                    .attr("y", iy + TopFrameHeight + GapForMap + 50)
                                    .text(Leaf1VisionPanelWidthToShow);

                                svg.append('line')
                                    .style("stroke", "black")
                                    .style("stroke-width", 0.5)
                                    .attr("x1", DistanceXForLeaf1VPShape)
                                    .attr("y1", iy + TopFrameHeight + GapForMap + 30)
                                    .attr("x2", DistanceXForLeaf1VPShape + parseFloat(Leaf1VisionPanelWidth))
                                    .attr("y2", iy + TopFrameHeight + GapForMap + 30)
                                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                                    .attr("marker-end", "url(#arrowRight)");
                            }


                        } else {
                            svg.append("text")
                                .style("fill", "black")
                                .attr("font-size", 10)
                                .attr("x", DistanceXForLeaf1VPShape + (parseFloat(Leaf1VisionPanelWidth) / 2) - 7)
                                .attr("y", iy - 40)
                                .text(Leaf1VisionPanelWidthToShow);

                            svg.append('line')
                                .style("stroke", "black")
                                .style("stroke-width", 0.5)
                                .attr("x1", DistanceXForLeaf1VPShape)
                                .attr("y1", iy - 35)
                                .attr("x2", DistanceXForLeaf1VPShape + parseFloat(Leaf1VisionPanelWidth))
                                .attr("y2", iy - 35)
                                .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                                .attr("marker-end", "url(#arrowRight)");

                            svg.append('line')
                                .style("stroke", "black")
                                .style("stroke-width", 0.5)
                                .attr("x1", DistanceXForLeaf1VPShape)
                                .attr("y1", iy - 35 - 5)
                                .attr("x2", DistanceXForLeaf1VPShape)
                                .attr("y2", DistanceYForLeaf1VPShape + (Leaf1VisionPanel1Height / 2))

                            svg.append('line')
                                .style("stroke", "black")
                                .style("stroke-width", 0.5)
                                .attr("x1", DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth)
                                .attr("y1", DistanceYForLeaf1VPShape + (Leaf1VisionPanel1Height / 2))
                                .attr("x2", DistanceXForLeaf1VPShape + parseFloat(Leaf1VisionPanelWidth))
                                .attr("y2", iy - 35 - 5)
                        }

                    }

                }

                // var RemainedSpaceInLeaf1 = LeafWidth1 - (DistanceFromTheEdgeOfDoorForLeaf1ToShow + Leaf1VisionPanelWidthToShow);
                var RemainedSpaceInLeaf1 = DistanceFromTheEdgeOfDoorForLeaf1ToShow;


          if (ShowMeasurements) {


            if (Handing == 'Right') {
                svg.append("text")            // append text
                    .style("fill", "black")      // make the text black
                    .attr("font-size", 10)
                    .attr("x", ix + LeftGapForLeaf1 + ((RemainedSpaceInLeaf1 / 5) / 2) - 7)         // set x position of left side of text
                    .attr("y", DistanceYForLeaf1VPShape + (Leaf1VisionPanel1Height / 2) - 10)         // set y position of bottom of text
                    .text(RemainedSpaceInLeaf1);   // define the text to display

                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + LeftGapForLeaf1)
                    .attr("y1", DistanceYForLeaf1VPShape + (Leaf1VisionPanel1Height / 2))
                    .attr("x2", DistanceXForLeaf1VPShape)
                    .attr("y2", DistanceYForLeaf1VPShape + (Leaf1VisionPanel1Height / 2))
                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                    .attr("marker-end", "url(#arrowRight)");
            } else {
                svg.append("text")           //
                    .style("fill", "black")
                    .attr("font-size", 10)
                    .attr("x", DistanceXForLeaf1VPShape + parseFloat(Leaf1VisionPanelWidth) + ((RemainedSpaceInLeaf1 / 5) / 2) - 7)
                    .attr("y", DistanceYForLeaf1VPShape + (Leaf1VisionPanel1Height / 2) - 10)
                    .text(RemainedSpaceInLeaf1);
                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", DistanceXForLeaf1VPShape + parseFloat(Leaf1VisionPanelWidth))
                    .attr("y1", DistanceYForLeaf1VPShape + (Leaf1VisionPanel1Height / 2))
                    .attr("x2", ix + LeafWidth1ForMap + LeftGapForLeaf1)
                    .attr("y2", DistanceYForLeaf1VPShape + (Leaf1VisionPanel1Height / 2))
                    .attr("marker-start", "url(#arrowLeft)")
                    .attr("marker-end", "url(#arrowRight)");
            }

            /* Horizontal Line for vision panel */

            /* Vertical Line for vision panel */
            if (Handing == 'Right') {
                //default distance from top (vission panel)

                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                    .attr("y1", iy + GapAfterOverPanelApplied + UpperAndLowerGap)
                    .attr("x2", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                    .attr("y2", DistanceYForLeaf1VPShape)
                    .attr("marker-start", "url(#arrowLeft)")
                    .attr("marker-end", "url(#arrowRight)");

                svg.append("text")
                    .style("fill", "black")
                    .style("writing-mode", WritingMode)
                    .attr("x", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                    .attr("font-size", 10)
                    .attr("y", iy + GapAfterOverPanelApplied + UpperAndLowerGap + (DistanceFromTopOfDoorForLeaf1 / 2))
                    .attr("transform", `rotate(-90, ${DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2)}, ${iy + GapAfterOverPanelApplied + UpperAndLowerGap + (DistanceFromTopOfDoorForLeaf1 / 2) + 7})`)
                    .text(DistanceFromTopOfDoorForLeaf1ToShow);


                if ((Leaf1VisionPanel1Height * 5) < ((LeafHeightNoOPForMap * 5) / 2)) { // when height of vission pannel is less then half of height of inner frame
                    svg.append('line')   // yellow
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 10)
                        .attr("y1", DistanceYForLeaf1VPShape)
                        .attr("x2", DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 10)
                        .attr("y2", DistanceYForLeaf1VPShape + parseFloat(Leaf1VisionPanel1Height))
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)");

                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 25)
                        .attr("font-size", 10)
                        .attr("y", DistanceYForLeaf1VPShape + (parseFloat(Leaf1VisionPanel1Height) / 2) + 5)
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 25}, ${DistanceYForLeaf1VPShape + (parseFloat(Leaf1VisionPanel1Height) / 2) + 5})`)
                        .text(Leaf1VisionPanel1HeightToShow);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y1", DistanceYForLeaf1VPShape + Leaf1VisionPanel1Height)
                        .attr("x2", DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 15)
                        .attr("y2", DistanceYForLeaf1VPShape + parseFloat(Leaf1VisionPanel1Height))

                    svg.append('line')   //
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 15)
                        .attr("y1", DistanceYForLeaf1VPShape)
                        .attr("x2", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y2", DistanceYForLeaf1VPShape)


                } else {  // when height of vission pannel is more then half of height of inner frame
                    if (VisionPanelShape == "Diamond" || VisionPanelShape == "Circle") {
                        // ix - 15 - SideLightPanel1WidthSpaceForVerticalLines

                        svg.append('line')   // yellow
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                            .attr("y1", DistanceYForLeaf1VPShape)
                            .attr("x2", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                            .attr("y2", DistanceYForLeaf1VPShape + parseFloat(Leaf1VisionPanel1Height))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)");

                        svg.append("text")            // append text
                            .style("fill", "black")      // make the text black
                            .style("writing-mode", WritingMode) // set the writing mode
                            .attr("x", DistanceXForLeaf1VPShape + 20)         // set x position of left side of text
                            .attr("font-size", 10)
                            .attr("y", DistanceYForLeaf1VPShape + (parseFloat(Leaf1VisionPanel1Height) / 2) + 5)         // set y position of bottom of text
                            .text(Leaf1VisionPanel1HeightToShow);   // define the text to display
                    } else {
                        svg.append('line')   // yellow
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", DistanceXForLeaf1VPShape + 20)
                            .attr("y1", DistanceYForLeaf1VPShape)
                            .attr("x2", DistanceXForLeaf1VPShape + 20)
                            .attr("y2", DistanceYForLeaf1VPShape + parseFloat(Leaf1VisionPanel1Height))
                            .attr("marker-start", "url(#arrowLeft)")
                            .attr("marker-end", "url(#arrowRight)");

                        svg.append("text")
                            .style("fill", "black")
                            .style("writing-mode", WritingMode)
                            .attr("x", DistanceXForLeaf1VPShape + 10)
                            .attr("font-size", 10)
                            .attr("y", DistanceYForLeaf1VPShape + (parseFloat(Leaf1VisionPanel1Height) / 2) + 5)
                            .attr("transform", `rotate(-90, ${DistanceXForLeaf1VPShape + 10}, ${DistanceYForLeaf1VPShape + (parseFloat(Leaf1VisionPanel1Height) / 2)})`)
                            .text(Leaf1VisionPanel1HeightToShow);

                    }
                }




                const totalShapeHeight = (parseFloat(Leaf1VisionPanel1Height) * (VisionPanelQuantityForLeaf1)) + parseFloat(Leaf1VisionPanel1Height) / 2;
                const spaceBetweenShapes = (VisionPanelQuantityForLeaf1 - 1) * ((+DistanceBetweenVPsMinValue) / 5);
                const totalTopDistance = (totalShapeHeight + spaceBetweenShapes + iy + UpperAndLowerGap)
                // // console.log(SOHeightForMap,totalTopDistance,iy + SOHeightForMap);

                if (VisionPanelQuantityForLeaf1 == 1) {

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y1", DistanceYForLeaf1VPShape + parseFloat(Leaf1VisionPanel1Height))
                        .attr("x2", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y2", iy + SOHeightForMap)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)");
                        svg.append("text")            // append text
                        .style("fill", "black")      // make the text black
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2) + 5)         // set x position of left side of text
                        .attr("font-size", 10)
                        .attr("y", (   DistanceYForLeaf1VPShape + parseFloat(Leaf1VisionPanel1Height)+iy + SOHeightForMap)/2)         // set y position of bottom of text
                        .text(LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5));
                         if((LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5))<200){
                           var newHeight = (Leaf1VisionPanel1Height * 5)-(200 -(LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5)))
                           swal('Warning!', "Distance from bottom cannot be less than 200");
                           console.log('lllllllllllllll')
                           $(document).ready(function () {
    $('#vP1Height1').on('input', function () {
        first=true
    });
    $('#distanceFromTopOfDoor').on('input', function () {
        first=true
    });
});
                            if(first){
                             $('input[name="vP1Height1"]').val(newHeight).trigger('change');
                            first=false
                           }
                        }
                }





            } else {
                //default distance from top (vission panel)

                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                    .attr("y1", iy + GapAfterOverPanelApplied + UpperAndLowerGap)
                    .attr("x2", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                    .attr("y2", DistanceYForLeaf1VPShape)
                    .attr("marker-start", "url(#arrowLeft)")
                    .attr("marker-end", "url(#arrowRight)");

                svg.append("text")
                    .style("fill", "black")
                    .style("writing-mode", WritingMode)
                    .attr("x", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                    .attr("font-size", 10)
                    .attr("y", iy + GapAfterOverPanelApplied + UpperAndLowerGap + (DistanceFromTopOfDoorForLeaf1 / 2))
                    .attr("transform", `rotate(-90, ${DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2)}, ${iy + GapAfterOverPanelApplied + UpperAndLowerGap + (DistanceFromTopOfDoorForLeaf1 / 2) + 7})`)
                    .text(DistanceFromTopOfDoorValue);


                if ((Leaf1VisionPanel1Height * 5) < ((LeafHeightNoOPForMap * 5) / 2)) { // when height of vission pannel is less then half of height of inner frame
                    svg.append('line')   // yellow
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape - 10)
                        .attr("y1", DistanceYForLeaf1VPShape)
                        .attr("x2", DistanceXForLeaf1VPShape - 10)
                        .attr("y2", DistanceYForLeaf1VPShape + parseFloat(Leaf1VisionPanel1Height))
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)");

                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", DistanceXForLeaf1VPShape - 25)
                        .attr("font-size", 10)
                        .attr("y", DistanceYForLeaf1VPShape + (parseFloat(Leaf1VisionPanel1Height) / 2))
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf1VPShape - 15}, ${DistanceYForLeaf1VPShape + (parseFloat(Leaf1VisionPanel1Height) / 2)})`)
                        .text(Leaf1VisionPanel1HeightToShow);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y1", DistanceYForLeaf1VPShape + Leaf1VisionPanel1Height)
                        .attr("x2", DistanceXForLeaf1VPShape - 15)
                        .attr("y2", DistanceYForLeaf1VPShape + parseFloat(Leaf1VisionPanel1Height))

                    svg.append('line')   //
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape - 15)
                        .attr("y1", DistanceYForLeaf1VPShape)
                        .attr("x2", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y2", DistanceYForLeaf1VPShape)


                } else {  // when height of vission pannel is more then half of height of inner frame

                    if (VisionPanelShape == "Diamond" || VisionPanelShape == "Circle") {
                        // ix - 15 - SideLightPanel1WidthSpaceForVerticalLines

                        svg.append('line')   // yellow
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                            .attr("y1", DistanceYForLeaf1VPShape)
                            .attr("x2", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                            .attr("y2", DistanceYForLeaf1VPShape + parseFloat(Leaf1VisionPanel1Height))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)");

                        svg.append("text")            // append text
                            .style("fill", "black")      // make the text black
                            .style("writing-mode", WritingMode) // set the writing mode
                            .attr("x", DistanceXForLeaf1VPShape + 20)         // set x position of left side of text
                            .attr("font-size", 10)
                            .attr("y", DistanceYForLeaf1VPShape + (parseFloat(Leaf1VisionPanel1Height) / 2) + 5)         // set y position of bottom of text
                            .text(Leaf1VisionPanel1HeightToShow);   // define the text to display
                    } else {
                        svg.append('line')   // yellow
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", DistanceXForLeaf1VPShape + 20)
                            .attr("y1", DistanceYForLeaf1VPShape)
                            .attr("x2", DistanceXForLeaf1VPShape + 20)
                            .attr("y2", DistanceYForLeaf1VPShape + parseFloat(Leaf1VisionPanel1Height))
                            .attr("marker-start", "url(#arrowLeft)")
                            .attr("marker-end", "url(#arrowRight)");

                        svg.append("text")
                            .style("fill", "black")
                            .style("writing-mode", WritingMode)
                            .attr("x", DistanceXForLeaf1VPShape + 10)
                            .attr("font-size", 10)
                            .attr("y", DistanceYForLeaf1VPShape + (parseFloat(Leaf1VisionPanel1Height) / 2) + 5)
                            .attr("transform", `rotate(-90, ${DistanceXForLeaf1VPShape + 10}, ${DistanceYForLeaf1VPShape + (parseFloat(Leaf1VisionPanel1Height) / 2)})`)
                            .text(Leaf1VisionPanel1HeightToShow);

                    }
                }




                const totalShapeHeight = (parseFloat(Leaf1VisionPanel1Height) * (VisionPanelQuantityForLeaf1)) + parseFloat(Leaf1VisionPanel1Height) / 2;
                const spaceBetweenShapes = (VisionPanelQuantityForLeaf1 - 1) * ((+DistanceBetweenVPsMinValue) / 5);
                const totalTopDistance = (totalShapeHeight + spaceBetweenShapes + iy + UpperAndLowerGap)
                // // console.log(SOHeightForMap,totalTopDistance,iy + SOHeightForMap);

                if (VisionPanelQuantityForLeaf1 == 1) {
                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y1", DistanceYForLeaf1VPShape + parseFloat(Leaf1VisionPanel1Height))
                        .attr("x2", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y2", iy + SOHeightForMap)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)");
                        svg.append("text")            // append text
                        .style("fill", "black")      // make the text black
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2) + 5)         // set x position of left side of text
                        .attr("font-size", 10)
                        .attr("y", (   DistanceYForLeaf1VPShape + parseFloat(Leaf1VisionPanel1Height)+iy + SOHeightForMap)/2)         // set y position of bottom of text
                        .text(LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) - (VisionPanelQuantityForLeaf1 * (Leaf1VisionPanel1Height * 5)));
                        if((LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) - (VisionPanelQuantityForLeaf1 * (Leaf1VisionPanel1Height * 5)))<200){
                           var newHeight = (Leaf1VisionPanel1Height * 5)-(200 -(LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) - (VisionPanelQuantityForLeaf1 * (Leaf1VisionPanel1Height * 5))))
                           swal('Warning!', "Distance from bottom cannot be less than 200");
                                 $(document).ready(function () {
    $('#vP1Height1').on('input', function () {
        first=true });
     $('#distanceFromTopOfDoor').on('input', function () {
        first=true
      
    });
});
                    if(first){
                            $('input[name="vP1Height1"]').val(newHeight).trigger('change');
                            first=false
                           }
                           
                        }
                }




            }
        }
        /* Vertical Line for vision panel */

        if (VisionPanelQuantityForLeaf1 > 1) {

            DistanceYForLeaf1VPShape = DistanceYForLeaf1VPShape + Leaf1VisionPanel1Height + DistanceBetweenVPsForLeaf1;

            if (VisionPanelShape == "Diamond") {
                svg.append('path')
                    .attr('d', DiamondDimension)
                    .attr("fill", "#fff")
                    .style("stroke", "black")
                    .attr("transform", "translate(" + DistanceXForLeaf1VPShape + "," + DistanceYForLeaf1VPShape + ")");
                // console.log("2609", Leaf1VisionPanel1Height)
            } else if (VisionPanelShape == "Circle") {
                svg.append("circle")
                    .style("stroke", "black")
                    .style("fill", "#fff")
                    .attr("r", CircleRadius)
                    .attr("cx", DistanceXForLeaf1VPShape + CircleRadius)
                    .attr("cy", DistanceYForLeaf1VPShape + CircleRadius);
            } else {
                svg.append('rect')
                    .attr('x', DistanceXForLeaf1VPShape)
                    .attr('y', DistanceYForLeaf1VPShape)
                    .attr('width', Leaf1VisionPanelWidth)
                    .attr('height', parseFloat(Leaf1VisionPanel2Height))
                    .attr('stroke', 'black')
                    .attr('fill', '#fff');
            }

            if (ShowMeasurements) {

                /* Vertical Line for vision panel */
                if (Handing == 'Right') {

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y1", DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1)
                        .attr("x2", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y2", DistanceYForLeaf1VPShape)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)");

                    svg.append("text")            // append text
                        .style("fill", "black")      // make the text black
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2)) // set x position of the text
                        .attr("font-size", 10)
                        .attr("y", (DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1) + (DistanceBetweenVPsForLeaf1 / 2)) // set y position of the text
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2)}, ${(DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1) + (DistanceBetweenVPsForLeaf1 / 2) + 5})`)
                        .text(DistanceBetweenVPsForLeaf1ToShow); // define the text to display


                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 10)
                        .attr("y1", DistanceYForLeaf1VPShape)
                        .attr("x2", DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 10)
                        .attr("y2", DistanceYForLeaf1VPShape + Leaf1VisionPanel2Height)
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)");

                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 25) // set x position
                        .attr("font-size", 10)
                        .attr("y", DistanceYForLeaf1VPShape + (Leaf1VisionPanel2Height / 2) + 5) // set y position
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 25}, ${DistanceYForLeaf1VPShape + (Leaf1VisionPanel2Height / 2) + 5})`)
                        .text(Leaf1VisionPanel2HeightToShow);


                    createLine("black", 0.5, DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2), DistanceYForLeaf1VPShape, DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 15, DistanceYForLeaf1VPShape);
                    createLine("black", 0.5, DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 15, DistanceYForLeaf1VPShape + Leaf1VisionPanel2Height, DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2), DistanceYForLeaf1VPShape + Leaf1VisionPanel2Height);

                    if (Leaf1VisionPanel2Height && VisionPanelQuantityForLeaf1 == 2) {

                        const totalShapeHeight = (parseFloat(Leaf1VisionPanel1Height) * (VisionPanelQuantityForLeaf1)) + parseFloat(Leaf1VisionPanel1Height) / 2;
                        const spaceBetweenShapes = (VisionPanelQuantityForLeaf1 - 1) * ((+DistanceBetweenVPsMinValue) / 5);
                        const totalTopDistance = (totalShapeHeight + spaceBetweenShapes + iy + UpperAndLowerGap)
                        // // console.log(SOHeightForMap,totalTopDistance,iy + SOHeightForMap);
                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                            .attr("y1", DistanceYForLeaf1VPShape + Leaf1VisionPanel2Height)
                            .attr("x2", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                            .attr("y2", iy + SOHeightForMap )
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)");
                            svg.append("text")            // append text
                            .style("fill", "black")      // make the text black
                            .style("writing-mode", WritingMode) // set the writing mode
                            .attr("x", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2) + 5)         // set x position of left side of text
                            .attr("font-size", 10)
                            .attr("y", (   DistanceYForLeaf1VPShape + parseFloat(Leaf1VisionPanel2Height)+iy + SOHeightForMap)/2)         // set y position of bottom of text
                            .text(LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5)-(Leaf1VisionPanel2Height*5));
                             if((LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5)-(Leaf1VisionPanel2Height*5))<200){
                           var newHeight = (Leaf1VisionPanel2Height * 5)-(200 -(LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5)-(Leaf1VisionPanel2Height*5)))
                           swal('Warning!', "Distance from bottom cannot be less than 200");
                                 $(document).ready(function () {
                                     $('#vP1Height1').on('input', function () {
        first=true });
    $('#vP1Height2').on('input', function () {
        first=true
       
    });
     $('#distanceFromTopOfDoor').on('input', function () {
        first=true
       
    });
});
                            if(first){
                             $('input[name="vP1Height2"]').val(newHeight).trigger('change');
                            first=false
                           }
                        }
                          
                    }
                } else {
                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y1", DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1)
                        .attr("x2", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y2", DistanceYForLeaf1VPShape)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)");

                    svg.append("text")            // append text
                        .style("fill", "black")      // make the text black
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2)) // set x position of the text
                        .attr("font-size", 10)
                        .attr("y", (DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1) + (DistanceBetweenVPsForLeaf1 / 2)) // set y position of the text
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2)}, ${(DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1) + (DistanceBetweenVPsForLeaf1 / 2) + 5})`)
                        .text(DistanceBetweenVPsForLeaf1ToShow); // define the text to display


                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape - 10)
                        .attr("y1", DistanceYForLeaf1VPShape)
                        .attr("x2", DistanceXForLeaf1VPShape - 10)
                        .attr("y2", DistanceYForLeaf1VPShape + Leaf1VisionPanel2Height)
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)");

                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", DistanceXForLeaf1VPShape - 10) // set x position
                        .attr("font-size", 10)
                        .attr("y", DistanceYForLeaf1VPShape + (Leaf1VisionPanel2Height / 2)) // set y position
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf1VPShape - 10}, ${DistanceYForLeaf1VPShape + (Leaf1VisionPanel2Height / 2) + 5})`)
                        .text(Leaf1VisionPanel2HeightToShow);


                    createLine("black", 0.5, DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2), DistanceYForLeaf1VPShape, DistanceXForLeaf1VPShape - 15, DistanceYForLeaf1VPShape);
                    createLine("black", 0.5, DistanceXForLeaf1VPShape - 15, DistanceYForLeaf1VPShape + Leaf1VisionPanel2Height, DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2), DistanceYForLeaf1VPShape + Leaf1VisionPanel2Height);
                    if (Leaf1VisionPanel2Height && VisionPanelQuantityForLeaf1 == 2) {

                        const totalShapeHeight = (parseFloat(Leaf1VisionPanel1Height) * (VisionPanelQuantityForLeaf1)) + parseFloat(Leaf1VisionPanel1Height) / 2;
                        const spaceBetweenShapes = (VisionPanelQuantityForLeaf1 - 1) * ((+DistanceBetweenVPsMinValue) / 5);
                        const totalTopDistance = (totalShapeHeight + spaceBetweenShapes + iy + UpperAndLowerGap)
                        // // console.log(SOHeightForMap,totalTopDistance,iy + SOHeightForMap);
                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                            .attr("y1", DistanceYForLeaf1VPShape + Leaf1VisionPanel2Height)
                            .attr("x2", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                            .attr("y2", iy + SOHeightForMap)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)");

                            svg.append("text")            // append text
                            .style("fill", "black")      // make the text black
                            .style("writing-mode", WritingMode) // set the writing mode
                            .attr("x", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2) + 5)         // set x position of left side of text
                            .attr("font-size", 10)
                            .attr("y", (   DistanceYForLeaf1VPShape + parseFloat(Leaf1VisionPanel2Height)+iy + SOHeightForMap)/2)         // set y position of bottom of text
                            .text(LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5)-(Leaf1VisionPanel2Height*5));
                               if((LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5)-(Leaf1VisionPanel2Height*5))<200){
                           var newHeight = (Leaf1VisionPanel2Height * 5)-(200 -(LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5)-(Leaf1VisionPanel2Height*5)))
                           swal('Warning!', "Distance from bottom cannot be less than 200");
                           
      $(document).ready(function () {
    $('#vP1Height2').on('input', function () {
        first=true
        
    });
     $('#vP1Height1').on('input', function () {
        first=true });
     $('#distanceFromTopOfDoor').on('input', function () {
        first=true
       
    });
});
                            if(first){
                            $('input[name="vP1Height2"]').val(newHeight).trigger('change');
                            first=false
                           }
                        }
                    }
                }

            }

        }

        if (VisionPanelQuantityForLeaf1 > 2) {

            DistanceYForLeaf1VPShape = DistanceYForLeaf1VPShape
                + Leaf1VisionPanel2Height + DistanceBetweenVPsForLeaf1;

            if (VisionPanelShape == "Diamond") {
                svg.append('path')
                    .attr('d', DiamondDimension)
                    .attr("fill", "#fff")
                    .style("stroke", "black")
                    .attr("transform", "translate(" + DistanceXForLeaf1VPShape + "," + DistanceYForLeaf1VPShape + ")");
            } else if (VisionPanelShape == "Circle") {
                svg.append("circle")
                    .style("stroke", "black")
                    .style("fill", "#fff")
                    .attr("r", CircleRadius)
                    .attr("cx", DistanceXForLeaf1VPShape + CircleRadius)
                    .attr("cy", DistanceYForLeaf1VPShape + CircleRadius);
            } else {
                svg.append('rect')
                    .attr('x', DistanceXForLeaf1VPShape)
                    .attr('y', DistanceYForLeaf1VPShape)
                    .attr('width', parseFloat(Leaf1VisionPanelWidth))
                    .attr('height', parseFloat(Leaf1VisionPanel3Height))
                    .attr('stroke', 'black')
                    .attr('fill', '#fff');
            }

            if (ShowMeasurements) {

                /* Vertical Line for vision panel */
                if (Handing == 'Right') {
                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y1", DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1)
                        .attr("x2", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y2", DistanceYForLeaf1VPShape)
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)");

                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("font-size", 10)
                        .attr("y", (DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1) + (DistanceBetweenVPsForLeaf1 / 2)) // set y position of text
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2)}, ${(DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1) + (DistanceBetweenVPsForLeaf1 / 2) + 5})`)
                        .text(DistanceBetweenVPsForLeaf1ToShow);


                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 10)
                        .attr("y1", DistanceYForLeaf1VPShape)
                        .attr("x2", DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 10)
                        .attr("y2", DistanceYForLeaf1VPShape + Leaf1VisionPanel3Height)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)");

                    svg.append("text")            // append text
                        .style("fill", "black")      // set text color
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 25) // set x position of text
                        .attr("font-size", 10)
                        .attr("y", DistanceYForLeaf1VPShape + (Leaf1VisionPanel3Height / 2) + 5) // set y position of text
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 25}, ${DistanceYForLeaf1VPShape + (Leaf1VisionPanel3Height / 2) + 5})`)
                        .text(Leaf1VisionPanel3HeightToShow);
                    createLine("black", 0.5, DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2), DistanceYForLeaf1VPShape, DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 15, DistanceYForLeaf1VPShape);
                    createLine("black", 0.5, DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 15, DistanceYForLeaf1VPShape + Leaf1VisionPanel3Height, DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2), DistanceYForLeaf1VPShape + Leaf1VisionPanel3Height);
                    if (Leaf1VisionPanel3Height && VisionPanelQuantityForLeaf1 == 3) {

                        const totalShapeHeight = (parseFloat(Leaf1VisionPanel1Height) * (VisionPanelQuantityForLeaf1)) + parseFloat(Leaf1VisionPanel1Height) / 2;
                        const spaceBetweenShapes = (VisionPanelQuantityForLeaf1 - 1) * ((+DistanceBetweenVPsMinValue) / 5);
                        const totalTopDistance = (totalShapeHeight + spaceBetweenShapes + iy + UpperAndLowerGap)
                        // // console.log(SOHeightForMap,totalTopDistance,iy + SOHeightForMap);
                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                            .attr("y1", DistanceYForLeaf1VPShape + Leaf1VisionPanel3Height)
                            .attr("x2", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                            .attr("y2", iy + SOHeightForMap)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)");
                            svg.append("text")            // append text
                            .style("fill", "black")      // make the text black
                            .style("writing-mode", WritingMode) // set the writing mode
                            .attr("x", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2) + 5)         // set x position of left side of text
                            .attr("font-size", 10)
                            .attr("y", (   DistanceYForLeaf1VPShape + parseFloat(Leaf1VisionPanel3Height)+iy + SOHeightForMap)/2)         // set y position of bottom of text
                            .text(LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5) - (Leaf1VisionPanel2Height * 5)-(Leaf1VisionPanel3Height * 5)  );
                                 if((LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5) - (Leaf1VisionPanel2Height * 5)-(Leaf1VisionPanel3Height * 5))<200){
                           var newHeight = (Leaf1VisionPanel3Height * 5)-(200 -(LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5) - (Leaf1VisionPanel2Height * 5)-(Leaf1VisionPanel3Height * 5)))
                           swal('Warning!', "Distance from bottom cannot be less than 200");
      $(document).ready(function () {
            $('#vP1Height1').on('input', function () {
        first=true });
    $('#vP1Height2').on('input', function () {
        first=true
       
    });
    $('#vP1Height3').on('input', function () {

        first=true
       
    });
     $('#distanceFromTopOfDoor').on('input', function () {
        first=true
       
    });

});
                           if(first){
                            $('input[name="vP1Height3"]').val(newHeight).trigger('change');
                            first=false
                           }
   
                        }

                    }
                } else {
                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y1", DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1)
                        .attr("x2", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y2", DistanceYForLeaf1VPShape)
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)");

                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("font-size", 10)
                        .attr("y", (DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1) + (DistanceBetweenVPsForLeaf1 / 2)) // set y position of text
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2)}, ${(DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1) + (DistanceBetweenVPsForLeaf1 / 2) + 5})`)
                        .text(DistanceBetweenVPsForLeaf1ToShow);


                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape - 10)
                        .attr("y1", DistanceYForLeaf1VPShape)
                        .attr("x2", DistanceXForLeaf1VPShape - 10)
                        .attr("y2", DistanceYForLeaf1VPShape + Leaf1VisionPanel3Height)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)");

                    svg.append("text")            // append text
                        .style("fill", "black")      // set text color
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", DistanceXForLeaf1VPShape - 10) // set x position of text
                        .attr("font-size", 10)
                        .attr("y", DistanceYForLeaf1VPShape + (Leaf1VisionPanel3Height / 2)) // set y position of text
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf1VPShape - 10}, ${DistanceYForLeaf1VPShape + (Leaf1VisionPanel3Height / 2) + 5})`)
                        .text(Leaf1VisionPanel3HeightToShow);
                    createLine("black", 0.5, DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2), DistanceYForLeaf1VPShape, DistanceXForLeaf1VPShape - 15, DistanceYForLeaf1VPShape);
                    createLine("black", 0.5, DistanceXForLeaf1VPShape - 15, DistanceYForLeaf1VPShape + Leaf1VisionPanel3Height, DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2), DistanceYForLeaf1VPShape + Leaf1VisionPanel3Height);

                    if (Leaf1VisionPanel3Height && VisionPanelQuantityForLeaf1 == 3) {

                        const totalShapeHeight = (parseFloat(Leaf1VisionPanel1Height) * (VisionPanelQuantityForLeaf1)) + parseFloat(Leaf1VisionPanel1Height) / 2;
                        const spaceBetweenShapes = (VisionPanelQuantityForLeaf1 - 1) * ((+DistanceBetweenVPsMinValue) / 5);
                        const totalTopDistance = (totalShapeHeight + spaceBetweenShapes + iy + UpperAndLowerGap)
                        // // console.log(SOHeightForMap,totalTopDistance,iy + SOHeightForMap);
                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                            .attr("y1", DistanceYForLeaf1VPShape + Leaf1VisionPanel3Height)
                            .attr("x2", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                            .attr("y2", iy + SOHeightForMap )
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)");
                            svg.append("text")            // append text
                            .style("fill", "black")      // make the text black
                            .style("writing-mode", WritingMode) // set the writing mode
                            .attr("x", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2) + 5)         // set x position of left side of text
                            .attr("font-size", 10)
                            .attr("y", (   DistanceYForLeaf1VPShape + parseFloat(Leaf1VisionPanel3Height)+iy + SOHeightForMap)/2)         // set y position of bottom of text
                                                      .text(LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5) - (Leaf1VisionPanel2Height * 5)-(Leaf1VisionPanel3Height * 5)  );
                           if((LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5) - (Leaf1VisionPanel2Height * 5)-(Leaf1VisionPanel3Height * 5))<200){
                           var newHeight = (Leaf1VisionPanel3Height * 5)-(200 -(LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5) - (Leaf1VisionPanel2Height * 5)-(Leaf1VisionPanel3Height * 5)))
                           swal('Warning!', "Distance from bottom cannot be less than 200");
                                 $(document).ready(function () {
    $('#vP1Height1').on('input', function () {
        first=true });
    $('#vP1Height2').on('input', function () {
        first=true
       
    });
    $('#vP1Height3').on('input', function () {
        first=true
        console.log('User changed the input. isUserChanged =', isUserChanged);
    });
     $('#distanceFromTopOfDoor').on('input', function () {
        first=true
       
    });
});
                          if(first){
                            $('input[name="vP1Height3"]').val(newHeight).trigger('change');
                            first=false
                           }
                           

                        }
                    }

                }

                /* Vertical Line for vision panel */
            }

        }

        if (VisionPanelQuantityForLeaf1 > 3) {

            DistanceYForLeaf1VPShape = DistanceYForLeaf1VPShape
                + Leaf1VisionPanel3Height + DistanceBetweenVPsForLeaf1;

            if (VisionPanelShape == "Diamond") {
                svg.append('path')
                    .attr('d', DiamondDimension)
                    .attr("fill", "#fff")
                    .style("stroke", "black")
                    .attr("transform", "translate(" + DistanceXForLeaf1VPShape + "," + DistanceYForLeaf1VPShape + ")");
                // console.log("2850")
            } else if (VisionPanelShape == "Circle") {
                svg.append("circle")
                    .style("stroke", "black")
                    .style("fill", "#fff")
                    .attr("r", CircleRadius)
                    .attr("cx", DistanceXForLeaf1VPShape + CircleRadius)
                    .attr("cy", DistanceYForLeaf1VPShape + CircleRadius);
            } else {
                svg.append('rect')
                    .attr('x', DistanceXForLeaf1VPShape)
                    .attr('y', DistanceYForLeaf1VPShape)
                    .attr('width', parseFloat(Leaf1VisionPanelWidth))
                    .attr('height', parseFloat(Leaf1VisionPanel4Height))
                    .attr('stroke', 'black')
                    .attr('fill', '#fff');
            }

            if (ShowMeasurements) {
                /* Vertical Line for vision panel */
                if (Handing == 'Right') {

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y1", (DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1))
                        .attr("x2", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y2", DistanceYForLeaf1VPShape)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)");

                    svg.append("text")            // append text
                        .style("fill", "black")      // set text color
                        .style("writing-mode", WritingMode) // set writing mode
                        .attr("x", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2)) // set x position
                        .attr("font-size", 10)
                        .attr("y", (DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1) + (DistanceBetweenVPsForLeaf1 / 2)) // set y position
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2)}, ${(DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1) + (DistanceBetweenVPsForLeaf1 / 2) + 5})`)
                        .text(DistanceBetweenVPsForLeaf1ToShow); // display text


                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 10)
                        .attr("y1", DistanceYForLeaf1VPShape)
                        .attr("x2", DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 10)
                        .attr("y2", DistanceYForLeaf1VPShape + Leaf1VisionPanel4Height)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)");

                    svg.append("text")            // append text
                        .style("fill", "black")      // set text color
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 25) // set x position of text
                        .attr("font-size", 10)
                        .attr("y", DistanceYForLeaf1VPShape + (Leaf1VisionPanel4Height / 2) + 5) // set y position of text
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 25}, ${DistanceYForLeaf1VPShape + (Leaf1VisionPanel4Height / 2) + 5})`)
                        .text(Leaf1VisionPanel4HeightToShow);

                    createLine("black", 0.5, DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2), DistanceYForLeaf1VPShape, DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 15, DistanceYForLeaf1VPShape);
                    createLine("black", 0.5, DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 15, DistanceYForLeaf1VPShape + Leaf1VisionPanel4Height, DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2), DistanceYForLeaf1VPShape + Leaf1VisionPanel4Height);

                    if (Leaf1VisionPanel4Height && VisionPanelQuantityForLeaf1 == 4) {

                        const totalShapeHeight = (parseFloat(Leaf1VisionPanel1Height) * (VisionPanelQuantityForLeaf1)) + parseFloat(Leaf1VisionPanel1Height) / 2;
                        const spaceBetweenShapes = (VisionPanelQuantityForLeaf1 - 1) * ((+DistanceBetweenVPsMinValue) / 5);
                        const totalTopDistance = (totalShapeHeight + spaceBetweenShapes + iy + UpperAndLowerGap)
                        // // console.log(SOHeightForMap,totalTopDistance,iy + SOHeightForMap);
                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                            .attr("y1", DistanceYForLeaf1VPShape + Leaf1VisionPanel4Height)
                            .attr("x2", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                            .attr("y2", iy + SOHeightForMap )
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)");

                            svg.append("text")            // append text
                            .style("fill", "black")      // make the text black
                            .style("writing-mode", WritingMode) // set the writing mode
                            .attr("x", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2) + 5)         // set x position of left side of text
                            .attr("font-size", 10)
                            .attr("y", (   DistanceYForLeaf1VPShape + parseFloat(Leaf1VisionPanel4Height)+iy + SOHeightForMap)/2)         // set y position of bottom of text
                            .text(LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5)-  (Leaf1VisionPanel2Height * 5)-  (Leaf1VisionPanel3Height * 5) -  (Leaf1VisionPanel4Height * 5));
                                       if((LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5)-  (Leaf1VisionPanel2Height * 5)-  (Leaf1VisionPanel3Height * 5) -  (Leaf1VisionPanel4Height * 5))<200){
                           var newHeight = (Leaf1VisionPanel4Height * 5)-(200 -(LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5)-  (Leaf1VisionPanel2Height * 5)-  (Leaf1VisionPanel3Height * 5) -  (Leaf1VisionPanel4Height * 5)))
                           swal('Warning!', "Distance from bottom cannot be less than 200");
      $(document).ready(function () {

        $('#vP1Height1').on('input', function () {
        first=true });
    $('#vP1Height2').on('input', function () {
        first=true
       
    });
    $('#vP1Height3').on('input', function () {
        first=true
        console.log('User changed the input. isUserChanged =', isUserChanged);
    });
    $('#vP1Height4').on('input', function () {
        
        first=true
       
    });
     $('#distanceFromTopOfDoor').on('input', function () {
        first=true
       
    });

});
                           if(first){
                            $('input[name="vP1Height4"]').val(newHeight).trigger('change');
                            first=false
                           }
   
                        }
                    }

                } else {
                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y1", (DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1))
                        .attr("x2", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y2", DistanceYForLeaf1VPShape)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)");

                    svg.append("text")            // append text
                        .style("fill", "black")      // set text color
                        .style("writing-mode", WritingMode) // set writing mode
                        .attr("x", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2)) // set x position
                        .attr("font-size", 10)
                        .attr("y", (DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1) + (DistanceBetweenVPsForLeaf1 / 2)) // set y position
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2)}, ${(DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1) + (DistanceBetweenVPsForLeaf1 / 2) + 5})`)
                        .text(DistanceBetweenVPsForLeaf1ToShow); // display text


                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape - 10)
                        .attr("y1", DistanceYForLeaf1VPShape)
                        .attr("x2", DistanceXForLeaf1VPShape - 10)
                        .attr("y2", DistanceYForLeaf1VPShape + Leaf1VisionPanel4Height)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)");

                    svg.append("text")            // append text
                        .style("fill", "black")      // set text color
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", DistanceXForLeaf1VPShape - 10) // set x position of text
                        .attr("font-size", 10)
                        .attr("y", DistanceYForLeaf1VPShape + (Leaf1VisionPanel4Height / 2)) // set y position of text
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf1VPShape - 10}, ${DistanceYForLeaf1VPShape + (Leaf1VisionPanel4Height / 2) + 5})`)
                        .text(Leaf1VisionPanel4HeightToShow);
                    // define the text to display
                    createLine("black", 0.5, DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2), DistanceYForLeaf1VPShape, DistanceXForLeaf1VPShape - 15, DistanceYForLeaf1VPShape);
                    createLine("black", 0.5, DistanceXForLeaf1VPShape - 15, DistanceYForLeaf1VPShape + Leaf1VisionPanel4Height, DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2), DistanceYForLeaf1VPShape + Leaf1VisionPanel4Height);
                    if (Leaf1VisionPanel4Height && VisionPanelQuantityForLeaf1 == 4) {

                        const totalShapeHeight = (parseFloat(Leaf1VisionPanel1Height) * (VisionPanelQuantityForLeaf1)) + parseFloat(Leaf1VisionPanel1Height) / 2;
                        const spaceBetweenShapes = (VisionPanelQuantityForLeaf1 - 1) * ((+DistanceBetweenVPsMinValue) / 5);
                        const totalTopDistance = (totalShapeHeight + spaceBetweenShapes + iy + UpperAndLowerGap)
                        // // console.log(SOHeightForMap,totalTopDistance,iy + SOHeightForMap);
                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                            .attr("y1", DistanceYForLeaf1VPShape + Leaf1VisionPanel4Height)
                            .attr("x2", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                            .attr("y2", iy + SOHeightForMap )
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)");

                            svg.append("text")            // append text
                            .style("fill", "black")      // make the text black
                            .style("writing-mode", WritingMode) // set the writing mode
                            .attr("x", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2) + 5)         // set x position of left side of text
                            .attr("font-size", 10)
                            .attr("y", (   DistanceYForLeaf1VPShape + parseFloat(Leaf1VisionPanel4Height)+iy + SOHeightForMap)/2)         // set y position of bottom of text
                            .text(LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5)-  (Leaf1VisionPanel2Height * 5)-  (Leaf1VisionPanel3Height * 5) -  (Leaf1VisionPanel4Height * 5));
                            console.log(LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5)-  (Leaf1VisionPanel2Height * 5)-  (Leaf1VisionPanel3Height * 5) -  (Leaf1VisionPanel4Height * 5),'44444444444444444444444')
                            console.log(Leaf1VisionPanel4Height * 5,'111111111111111111')

                             if((LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5)-  (Leaf1VisionPanel2Height * 5)-  (Leaf1VisionPanel3Height * 5) -  (Leaf1VisionPanel4Height * 5))<200){
                                   console.log(Leaf1VisionPanel4Height * 5,'111111111111111111')
                           var newHeight = (Leaf1VisionPanel4Height * 5)-(200 -(LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5)-  (Leaf1VisionPanel2Height * 5)-  (Leaf1VisionPanel3Height * 5) -  (Leaf1VisionPanel4Height * 5)))
                           swal('Warning!', "Distance from bottom cannot be less than 200");
      $(document).ready(function () {
        $('#vP1Height1').on('input', function () {
        first=true });
    $('#vP1Height2').on('input', function () {
        first=true
       
    });
    $('#vP1Height3').on('input', function () {
        first=true
        console.log('User changed the input. isUserChanged =', isUserChanged);
    });
    $('#vP1Height4').on('input', function () {
        
        first=true
       
    });
     $('#distanceFromTopOfDoor').on('input', function () {
        first=true
       
    });

});
                           if(first){
                            $('input[name="vP1Height4"]').val(newHeight).trigger('change');
                            first=false
                           }
   
                        }

                    }

                }

                /* Vertical Line for vision panel */
            }

        }

        if (VisionPanelQuantityForLeaf1 > 4) {
            DistanceYForLeaf1VPShape = DistanceYForLeaf1VPShape
                + Leaf1VisionPanel4Height + DistanceBetweenVPsForLeaf1;

            if (VisionPanelShape == "Diamond") {
                svg.append('path')
                    .attr('d', DiamondDimension)
                    .attr("fill", "#fff")
                    .style("stroke", "black")
                    .attr("transform", "translate(" + DistanceXForLeaf1VPShape + "," + DistanceYForLeaf1VPShape + ")");
                // console.log(DistanceXForLeaf1VPShape, "DistanceXForLeaf1VPShape 2960")
            } else if (VisionPanelShape == "Circle") {
                svg.append("circle")
                    .style("stroke", "black")
                    .style("fill", "#fff")
                    .attr("r", CircleRadius)
                    .attr("cx", DistanceXForLeaf1VPShape + CircleRadius)
                    .attr("cy", DistanceYForLeaf1VPShape + CircleRadius);
            } else {
                svg.append('rect')
                    .attr('x', DistanceXForLeaf1VPShape)
                    .attr('y', DistanceYForLeaf1VPShape)
                    .attr('width', parseFloat(Leaf1VisionPanelWidth))
                    .attr('height', parseFloat(Leaf1VisionPanel5Height))
                    .attr('stroke', 'black')
                    .attr('fill', '#fff');
            }

            if (ShowMeasurements) {

                /* Vertical Line for vision panel */
                if (Handing == 'Right') {


                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y1", (DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1))
                        .attr("x2", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y2", DistanceYForLeaf1VPShape)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")      // set text color
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2)) // set x position of text
                        .attr("font-size", 10)
                        .attr("y", (DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1) + (DistanceBetweenVPsForLeaf1 / 2)) // set y position of text
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2)}, ${(DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1) + (DistanceBetweenVPsForLeaf1 / 2) + 5})`)
                        .text(DistanceBetweenVPsForLeaf1ToShow); // define the text to display


                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 10)
                        .attr("y1", DistanceYForLeaf1VPShape)
                        .attr("x2", DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 10)
                        .attr("y2", DistanceYForLeaf1VPShape + Leaf1VisionPanel5Height)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")      // make the text black
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 25)         // set x position of text
                        .attr("font-size", 10)
                        .attr("y", DistanceYForLeaf1VPShape + (Leaf1VisionPanel5Height / 2) + 5)        // set y position of text
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 25}, ${DistanceYForLeaf1VPShape + (Leaf1VisionPanel5Height / 2) + 5})`)
                        .text(Leaf1VisionPanel5HeightToShow);
                    createLine("black", 0.5, DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2), DistanceYForLeaf1VPShape, DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 15, DistanceYForLeaf1VPShape);
                    createLine("black", 0.5, DistanceXForLeaf1VPShape + Leaf1VisionPanelWidth + 15, DistanceYForLeaf1VPShape + Leaf1VisionPanel5Height, DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2), DistanceYForLeaf1VPShape + Leaf1VisionPanel5Height);

                    if (Leaf1VisionPanel5Height && VisionPanelQuantityForLeaf1 == 5) {

                        const totalShapeHeight = (parseFloat(Leaf1VisionPanel1Height) * (VisionPanelQuantityForLeaf1)) + parseFloat(Leaf1VisionPanel1Height) / 2;
                        const spaceBetweenShapes = (VisionPanelQuantityForLeaf1 - 1) * ((+DistanceBetweenVPsMinValue) / 5);
                        const totalTopDistance = (totalShapeHeight + spaceBetweenShapes + iy + UpperAndLowerGap)
                        // // console.log(SOHeightForMap,totalTopDistance,iy + SOHeightForMap);
                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                            .attr("y1", DistanceYForLeaf1VPShape + Leaf1VisionPanel5Height)
                            .attr("x2", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                            .attr("y2", iy + SOHeightForMap )
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)");

                            svg.append("text")            // append text
                            .style("fill", "black")      // make the text black
                            .style("writing-mode", WritingMode) // set the writing mode
                            .attr("x", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2) + 5)         // set x position of left side of text
                            .attr("font-size", 10)
                            .attr("y", (   DistanceYForLeaf1VPShape + parseFloat(Leaf1VisionPanel5Height)+iy + SOHeightForMap)/2)         // set y position of bottom of text
                            .text(LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5)-  (Leaf1VisionPanel2Height * 5)-  (Leaf1VisionPanel3Height * 5)-  (Leaf1VisionPanel4Height * 5)-  (Leaf1VisionPanel5Height * 5));
                                if((LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5)-  (Leaf1VisionPanel2Height * 5)-  (Leaf1VisionPanel3Height * 5)-  (Leaf1VisionPanel4Height * 5)-  (Leaf1VisionPanel5Height * 5))<200){
                           var newHeight = (Leaf1VisionPanel5Height * 5)-(200 -(LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5)-  (Leaf1VisionPanel2Height * 5)-  (Leaf1VisionPanel3Height * 5)-  (Leaf1VisionPanel4Height * 5)-  (Leaf1VisionPanel5Height * 5)))
                           swal('Warning!', "Distance from bottom cannot be less than 200");
      $(document).ready(function () {
         $('#vP1Height1').on('input', function () {
        first=true });
    $('#vP1Height2').on('input', function () {
        first=true
       
    });
    $('#vP1Height3').on('input', function () {
        first=true
        console.log('User changed the input. isUserChanged =', isUserChanged);
    });
    $('#vP1Height4').on('input', function () {
        
        first=true
       
    });
    $('#vP1Height5').on('input', function () {
        
        first=true
       
    });
     $('#distanceFromTopOfDoor').on('input', function () {
        first=true
       
    });

});
                           if(first){
                            $('input[name="vP1Height5"]').val(newHeight).trigger('change');
                            first=false
                           }
   
                        }

                    }
                } else {
                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y1", (DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1))
                        .attr("x2", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                        .attr("y2", DistanceYForLeaf1VPShape)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")      // set text color
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2)) // set x position of text
                        .attr("font-size", 10)
                        .attr("y", (DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1) + (DistanceBetweenVPsForLeaf1 / 2)) // set y position of text
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2)}, ${(DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1) + (DistanceBetweenVPsForLeaf1 / 2) + 5})`)
                        .text(DistanceBetweenVPsForLeaf1ToShow); // define the text to display


                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf1VPShape - 10)
                        .attr("y1", DistanceYForLeaf1VPShape)
                        .attr("x2", DistanceXForLeaf1VPShape - 10)
                        .attr("y2", DistanceYForLeaf1VPShape + Leaf1VisionPanel5Height)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")      // make the text black
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", DistanceXForLeaf1VPShape - 10)         // set x position of text
                        .attr("font-size", 10)
                        .attr("y", DistanceYForLeaf1VPShape + (Leaf1VisionPanel5Height / 2))        // set y position of text
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf1VPShape - 10}, ${DistanceYForLeaf1VPShape + (Leaf1VisionPanel5Height / 2) + 5})`)
                        .text(Leaf1VisionPanel5HeightToShow);
                    createLine("black", 0.5, DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2), DistanceYForLeaf1VPShape, DistanceXForLeaf1VPShape - 15, DistanceYForLeaf1VPShape);
                    createLine("black", 0.5, DistanceXForLeaf1VPShape - 15, DistanceYForLeaf1VPShape + Leaf1VisionPanel5Height, DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2), DistanceYForLeaf1VPShape + Leaf1VisionPanel5Height);
                    if (Leaf1VisionPanel5Height && VisionPanelQuantityForLeaf1 == 5) {

                        const totalShapeHeight = (parseFloat(Leaf1VisionPanel1Height) * (VisionPanelQuantityForLeaf1)) + parseFloat(Leaf1VisionPanel1Height) / 2;
                        const spaceBetweenShapes = (VisionPanelQuantityForLeaf1 - 1) * ((+DistanceBetweenVPsMinValue) / 5);
                        const totalTopDistance = (totalShapeHeight + spaceBetweenShapes + iy + UpperAndLowerGap)
                        // // console.log(SOHeightForMap,totalTopDistance,iy + SOHeightForMap);
                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                            .attr("y1", DistanceYForLeaf1VPShape + Leaf1VisionPanel5Height)
                            .attr("x2", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2))
                            .attr("y2", iy + SOHeightForMap )
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)");
                             svg.append("text")            // append text
                    .style("fill", "black")      // make the text black
                    .style("writing-mode", WritingMode) // set the writing mode
                    .attr("x", DistanceXForLeaf1VPShape + (Leaf1VisionPanelWidth / 2) + 5)         // set x position of left side of text
                    .attr("font-size", 10)
                    .attr("y", (   DistanceYForLeaf1VPShape + parseFloat(Leaf1VisionPanel5Height)+iy + SOHeightForMap)/2)         // set y position of bottom of text
                            .text(LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5)-  (Leaf1VisionPanel2Height * 5)-  (Leaf1VisionPanel3Height * 5)-  (Leaf1VisionPanel4Height * 5)-  (Leaf1VisionPanel5Height * 5));
                   
  if((LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5)-  (Leaf1VisionPanel2Height * 5)-  (Leaf1VisionPanel3Height * 5)-  (Leaf1VisionPanel4Height * 5)-  (Leaf1VisionPanel5Height * 5))<200){
                           var newHeight = (Leaf1VisionPanel5Height * 5)-(200 -(LeafHeightNoOP - DistanceFromTopOfDoorValue - ((VisionPanelQuantityForLeaf1 - 1) * (+distanceBetweenVP)) -  (Leaf1VisionPanel1Height * 5)-  (Leaf1VisionPanel2Height * 5)-  (Leaf1VisionPanel3Height * 5)-  (Leaf1VisionPanel4Height * 5)-  (Leaf1VisionPanel5Height * 5)))
                           swal('Warning!', "Distance from bottom cannot be less than 200");
      $(document).ready(function () {
         $('#vP1Height1').on('input', function () {
        first=true });
    $('#vP1Height2').on('input', function () {
        first=true
       
    });
    $('#vP1Height3').on('input', function () {
        first=true
        console.log('User changed the input. isUserChanged =', isUserChanged);
    });
    $('#vP1Height4').on('input', function () {
        
        first=true
       
    });
    $('#vP1Height5').on('input', function () {
        
        first=true
       
    });
     $('#distanceFromTopOfDoor').on('input', function () {
        first=true
       
    });

});
                           if(first){
                            $('input[name="vP1Height5"]').val(newHeight).trigger('change');
                            first=false
                           }
   
                        }
                    }

                }

                /* Vertical Line for vision panel */
            }

        }
            }
        }



        var Leaf2VisionPanelWidth = Leaf1VisionPanelWidth;
        var Leaf2VisionPanelWidthToShow = Leaf1VisionPanelWidthToShow;

        var Leaf2VisionPanel1Height = Leaf1VisionPanel1Height, Leaf2VisionPanel2Height = Leaf1VisionPanel2Height,
            Leaf2VisionPanel3Height = Leaf1VisionPanel3Height, Leaf2VisionPanel4Height = Leaf1VisionPanel4Height,
            Leaf2VisionPanel5Height = Leaf1VisionPanel5Height;

        var Leaf2VisionPanel1HeightToShow = Leaf1VisionPanel1HeightToShow, Leaf2VisionPanel2HeightToShow = Leaf1VisionPanel2HeightToShow,
            Leaf2VisionPanel3HeightToShow = Leaf1VisionPanel3HeightToShow, Leaf2VisionPanel4HeightToShow = Leaf1VisionPanel4HeightToShow,
            Leaf2VisionPanel5HeightToShow = Leaf1VisionPanel5HeightToShow;

        var AreVPsEqualSizesForLeaf2 = AreVPsEqualSizesForLeaf1;
        var VisionPanelQuantityForLeaf2 = VisionPanelQuantityForLeaf1;

        var DistanceBetweenVPsForLeaf2 = DistanceBetweenVPsForLeaf1;
        var DistanceBetweenVPsForLeaf2ToShow = DistanceBetweenVPsForLeaf1ToShow;

        var DistanceFromTopOfDoorForLeaf2 = DistanceFromTopOfDoorForLeaf1;
        var DistanceFromTopOfDoorForLeaf2ToShow = DistanceFromTopOfDoorForLeaf1ToShow;

        var DistanceFromTheEdgeOfDoorForLeaf2 = DistanceFromTheEdgeOfDoorForLeaf1;
        var DistanceFromTheEdgeOfDoorForLeaf2ToShow = DistanceFromTheEdgeOfDoorForLeaf1ToShow;

        if (DoorSetType == "DD") {

            var Leaf2VisionPanel = $('select[name="leaf2VisionPanel"]').val();
            if (Leaf2VisionPanel == "Yes") {

                if (AreVPsEqualSizesForLeaf2 == "Yes") {

                    var VP2Height1 = VP1Height1;

                    Leaf2VisionPanel1Height = Leaf2VisionPanel2Height = Leaf2VisionPanel3Height = Leaf2VisionPanel4Height = Leaf2VisionPanel5Height = VP2Height1;
                    Leaf2VisionPanel1HeightToShow = Leaf2VisionPanel2HeightToShow = Leaf2VisionPanel3HeightToShow = Leaf2VisionPanel4HeightToShow = Leaf2VisionPanel5HeightToShow = Leaf1VisionPanel1HeightToShow;

                }

                var DistanceBetweenVPsMinValueforLeaf2 = parseFloat($('input[name="distanceBetweenVPsforLeaf2"]').attr("min"));
                var DistanceFromTopOfDoorMinValueforLeaf2 = parseFloat($('input[name="distanceFromTopOfDoorforLeaf2"]').attr("min"));
                var DistanceFromTheEdgeOfDoorMinValueforLeaf2 = parseFloat($('input[name="distanceFromTheEdgeOfDoorforLeaf2"]').attr("min"));

                var VPSameAsLeaf1 = $('select[name="vpSameAsLeaf1"]').val();
                if (VPSameAsLeaf1 != "Yes") {



                    DistanceBetweenVPsForLeaf2 = DistanceBetweenVPsMinValueforLeaf2;
                    DistanceFromTopOfDoorForLeaf2 = DistanceFromTopOfDoorMinValueforLeaf2;
                    DistanceFromTheEdgeOfDoorForLeaf2 = DistanceFromTheEdgeOfDoorMinValueforLeaf2;

                    if ($.inArray(ChangedFieldName, ["leaf2VisionPanel", "vpSameAsLeaf1", "visionPanelQuantityforLeaf2"]) !== -1) {

                        $('input[name="distanceFromTopOfDoorforLeaf2"]').val(DistanceFromTopOfDoorMinValueforLeaf2);
                        $('input[name="distanceFromTheEdgeOfDoorforLeaf2"]').val(DistanceFromTheEdgeOfDoorMinValueforLeaf2);
                    } else {
                        DistanceFromTopOfDoorForLeaf2 = $('input[name="distanceFromTopOfDoorforLeaf2"]').val();
                        if (DistanceFromTopOfDoorForLeaf2 == "" || parseFloat(DistanceFromTopOfDoorForLeaf2) < DistanceFromTopOfDoorMinValueforLeaf2) {
                            DistanceFromTopOfDoorForLeaf2 = DistanceFromTopOfDoorMinValueforLeaf2;
                        }
                        DistanceFromTheEdgeOfDoorForLeaf2 = $('input[name="distanceFromTheEdgeOfDoorforLeaf2"]').val();
                        if (DistanceFromTheEdgeOfDoorForLeaf2 == "" || parseFloat(DistanceFromTheEdgeOfDoorForLeaf2) < DistanceFromTheEdgeOfDoorMinValueforLeaf2) {
                            DistanceFromTheEdgeOfDoorForLeaf2 = DistanceFromTheEdgeOfDoorMinValueforLeaf2;
                        }
                    }

                    Leaf2VisionPanelWidth = $('input[name="vP2Width"]').val();
                    if (Leaf2VisionPanelWidth == "") {
                        Leaf2VisionPanelWidth = 0;
                    } else {
                        Leaf2VisionPanelWidthToShow = parseFloat(Leaf2VisionPanelWidth);
                        if (Leaf2VisionPanelWidth > 0) {
                            Leaf2VisionPanelWidth = parseFloat(Leaf2VisionPanelWidth) / 5;
                        }
                    }

                    AreVPsEqualSizesForLeaf2 = $('select[name="AreVPsEqualSizesForLeaf2"]').val();
                    VisionPanelQuantityForLeaf2 = $('select[name="visionPanelQuantityforLeaf2"]').val();
                    if (VisionPanelQuantityForLeaf2 == "") {
                        VisionPanelQuantityForLeaf2 = 1;
                    } else {
                        VisionPanelQuantityForLeaf2 = parseFloat(VisionPanelQuantityForLeaf2);
                    }

                    if (VisionPanelShape == "Rectangle") {

                        if (AreVPsEqualSizesForLeaf2 == "No") {
                            $('input[name="vP2Height1"]').removeAttr("readonly");
                            if (VisionPanelQuantityForLeaf2 > 1) {
                                $('input[name="vP2Height2"]').removeAttr("readonly");
                            }
                            if (VisionPanelQuantityForLeaf2 > 2) {
                                $('input[name="vP2Height3"]').removeAttr("readonly");
                            }
                            if (VisionPanelQuantityForLeaf2 > 3) {
                                $('input[name="vP2Height4"]').removeAttr("readonly");
                            }
                            if (VisionPanelQuantityForLeaf2 > 4) {
                                $('input[name="vP2Height5"]').removeAttr("readonly");
                            }
                        }
                    } else {
                        $('input[name="vP2Height1"]').attr("readonly", true);
                        if (VisionPanelQuantityForLeaf2 > 1) {
                            $('input[name="vP2Height2"]').attr("readonly", true);
                        }
                        if (VisionPanelQuantityForLeaf2 > 2) {
                            $('input[name="vP2Height3"]').attr("readonly", true);
                        }
                        if (VisionPanelQuantityForLeaf2 > 3) {
                            $('input[name="vP2Height4"]').attr("readonly", true);
                        }
                        if (VisionPanelQuantityForLeaf2 > 4) {
                            $('input[name="vP2Height5"]').attr("readonly", true);
                        }
                    }


                    if (ChangedFieldName == "visionPanelQuantityforLeaf2" && VisionPanelQuantityForLeaf2 > 1) {
                        $('input[name="distanceBetweenVPsforLeaf2"]').val(DistanceBetweenVPsMinValueforLeaf2);
                    } else {
                        if (VisionPanelQuantityForLeaf2 < 2) {
                            DistanceBetweenVPsForLeaf2 = 0;
                        } else {
                            DistanceBetweenVPsForLeaf2 = $('input[name="distanceBetweenVPsforLeaf2"]').val();
                            if (DistanceBetweenVPsForLeaf2 == "" || parseFloat(DistanceBetweenVPsForLeaf2) < DistanceBetweenVPsMinValueforLeaf2) {
                                DistanceBetweenVPsForLeaf2 = DistanceBetweenVPsMinValueforLeaf2;
                            }
                        }
                    }

                    if (ChangedFieldName == "distanceBetweenVPsforLeaf2") {
                        DistanceBetweenVPsForLeaf2 = $('input[name="distanceBetweenVPsforLeaf2"]').val();
                        if (DistanceBetweenVPsForLeaf2 == "" || parseFloat(DistanceBetweenVPsForLeaf2) < DistanceBetweenVPsMinValueforLeaf2) {
                            DistanceBetweenVPsForLeaf2 = DistanceBetweenVPsMinValueforLeaf2;
                        }
                    }

                    DistanceBetweenVPsForLeaf2ToShow = 0;
                    if (DistanceBetweenVPsForLeaf2 != "" && DistanceBetweenVPsForLeaf2 > 0) {
                        DistanceBetweenVPsForLeaf2ToShow = parseFloat(DistanceBetweenVPsForLeaf2);
                        DistanceBetweenVPsForLeaf2 = parseFloat(DistanceBetweenVPsForLeaf2) / 5;
                    }

                    if (ChangedFieldName == "distanceFromTopOfDoorforLeaf2") {
                        DistanceFromTopOfDoorForLeaf2 = $('input[name="distanceFromTopOfDoorforLeaf2"]').val();
                        if (DistanceFromTopOfDoorForLeaf2 == "" || parseFloat(DistanceFromTopOfDoorForLeaf2) < DistanceFromTopOfDoorMinValueforLeaf2) {
                            DistanceFromTopOfDoorForLeaf2 = DistanceFromTopOfDoorMinValueforLeaf2;
                        }
                    }

                    DistanceFromTopOfDoorForLeaf2ToShow = 0;
                    if (DistanceFromTopOfDoorForLeaf2 != "" && DistanceFromTopOfDoorForLeaf2 > 0) {
                        DistanceFromTopOfDoorForLeaf2ToShow = parseFloat(DistanceFromTopOfDoorForLeaf2);
                        if (DistanceFromTopOfDoorForLeaf2 > 0) {
                            DistanceFromTopOfDoorForLeaf2 = parseFloat(DistanceFromTopOfDoorForLeaf2) / 5;
                        }
                    }

                    if (ChangedFieldName == "distanceFromTheEdgeOfDoorforLeaf2") {
                        DistanceFromTheEdgeOfDoorForLeaf2 = $('input[name="distanceFromTheEdgeOfDoorforLeaf2"]').val();
                        if (DistanceFromTheEdgeOfDoorForLeaf2 == "" || parseFloat(DistanceFromTheEdgeOfDoorForLeaf2) < DistanceFromTheEdgeOfDoorMinValueforLeaf2) {
                            DistanceFromTheEdgeOfDoorForLeaf2 = DistanceFromTheEdgeOfDoorMinValueforLeaf2;
                        }
                    }

                    DistanceFromTheEdgeOfDoorForLeaf2ToShow = 0;
                    if (DistanceFromTheEdgeOfDoorForLeaf2 != "" && DistanceFromTheEdgeOfDoorForLeaf2 > 0) {
                        DistanceFromTheEdgeOfDoorForLeaf2ToShow = parseFloat(DistanceFromTheEdgeOfDoorForLeaf2);
                        if (DistanceFromTheEdgeOfDoorForLeaf2 > 0) {
                            DistanceFromTheEdgeOfDoorForLeaf2 = parseFloat(DistanceFromTheEdgeOfDoorForLeaf2) / 5;
                        }
                    }

                    if (AreVPsEqualSizesForLeaf2 == "No") {

                        Leaf2VisionPanel1Height = $('input[name="vP2Height1"]').val();
                        if (Leaf2VisionPanel1Height == "") {
                            Leaf2VisionPanel1Height = 0;
                        } else {
                            Leaf2VisionPanel1HeightToShow = parseFloat(Leaf2VisionPanel1Height);
                            if (Leaf2VisionPanel1Height > 0) {
                                Leaf2VisionPanel1Height = parseFloat(Leaf2VisionPanel1Height) / 5;
                            }
                        }

                        Leaf2VisionPanel2Height = $('input[name="vP2Height2"]').val();
                        if (Leaf2VisionPanel2Height == "") {
                            Leaf2VisionPanel2Height = 0;
                        } else {
                            Leaf2VisionPanel2HeightToShow = parseFloat(Leaf2VisionPanel2Height);
                            if (Leaf2VisionPanel2Height > 0) {
                                Leaf2VisionPanel2Height = parseFloat(Leaf2VisionPanel2Height) / 5;
                            }
                        }

                        Leaf2VisionPanel3Height = $('input[name="vP2Height3"]').val();
                        if (Leaf2VisionPanel3Height == "") {
                            Leaf2VisionPanel3Height = 0;
                        } else {
                            Leaf2VisionPanel3HeightToShow = parseFloat(Leaf2VisionPanel3Height);
                            if (Leaf2VisionPanel3Height > 0) {
                                Leaf2VisionPanel3Height = parseFloat(Leaf2VisionPanel3Height) / 5;
                            }
                        }

                        Leaf2VisionPanel4Height = $('input[name="vP2Height4"]').val();
                        if (Leaf2VisionPanel4Height == "") {
                            Leaf2VisionPanel4Height = 0;
                        } else {
                            Leaf2VisionPanel4HeightToShow = parseFloat(Leaf2VisionPanel4Height);
                            if (Leaf2VisionPanel4Height > 0) {
                                Leaf2VisionPanel4Height = parseFloat(Leaf2VisionPanel4Height) / 5;
                            }
                        }

                        Leaf2VisionPanel5Height = $('input[name="vP2Height5"]').val();
                        if (Leaf2VisionPanel5Height == "") {
                            Leaf2VisionPanel5Height = 0;
                        } else {
                            Leaf2VisionPanel5HeightToShow = parseFloat(Leaf2VisionPanel5Height);
                            if (Leaf2VisionPanel5Height > 0) {
                                Leaf2VisionPanel5Height = parseFloat(Leaf2VisionPanel5Height) / 5;
                            }
                        }

                    } else {

                        var VP2Height1 = $('input[name="vP2Height1"]').val();
                        if (VP2Height1 == "") {
                            VP2Height1 = 0;
                        } else {
                            Leaf2VisionPanel1HeightToShow = Leaf2VisionPanel2HeightToShow = Leaf2VisionPanel3HeightToShow = Leaf2VisionPanel4HeightToShow = Leaf2VisionPanel5HeightToShow = parseFloat(VP2Height1);
                            if (VP2Height1 > 0) {
                                VP2Height1 = parseFloat(VP2Height1) / 5;
                            }
                        }

                        Leaf2VisionPanel1Height = Leaf2VisionPanel2Height = Leaf2VisionPanel3Height = Leaf2VisionPanel4Height = Leaf2VisionPanel5Height = VP2Height1;

                    }
                }

                if ($.inArray(VisionPanelShape, ["Diamond", "Circle", "Square"]) !== -1) {

                    $('select[name="AreVPsEqualSizesForLeaf2"]').attr("readonly", true);
                    $('select[name="AreVPsEqualSizesForLeaf2"]').val("Yes");

                    AreVPsEqualSizesForLeaf2 = "Yes";

                    $('input[name="vP2Height1"]').attr("readonly", true);
                    $('input[name="vP2Height2"]').attr("readonly", true);
                    $('input[name="vP2Height3"]').attr("readonly", true);
                    $('input[name="vP2Height4"]').attr("readonly", true);
                    $('input[name="vP2Height5"]').attr("readonly", true);

                    $('input[name="vP2Height1"]').val(Leaf2VisionPanelWidthToShow);
                    Leaf2VisionPanel1Height = Leaf2VisionPanelWidth;
                    Leaf2VisionPanel1HeightToShow = Leaf2VisionPanelWidthToShow;

                    if (VisionPanelQuantityForLeaf2 > 1) {
                        $('input[name="vP2Height2"]').val(Leaf2VisionPanelWidthToShow);
                        Leaf2VisionPanel2Height = Leaf2VisionPanelWidth;
                        Leaf2VisionPanel2HeightToShow = Leaf2VisionPanelWidthToShow;
                    }

                    if (VisionPanelQuantityForLeaf2 > 2) {
                        $('input[name="vP2Height3"]').val(Leaf2VisionPanelWidthToShow);
                        Leaf2VisionPanel3Height = Leaf2VisionPanelWidth;
                        Leaf2VisionPanel3HeightToShow = Leaf2VisionPanelWidthToShow;
                    }

                    if (VisionPanelQuantityForLeaf2 > 3) {
                        $('input[name="vP2Height4"]').val(Leaf2VisionPanelWidthToShow);
                        Leaf2VisionPanel4Height = Leaf2VisionPanelWidth;
                        Leaf2VisionPanel4HeightToShow = Leaf2VisionPanelWidthToShow;
                    }

                    if (VisionPanelQuantityForLeaf2 > 4) {
                        $('input[name="vP2Height5"]').val(Leaf2VisionPanelWidthToShow);
                        Leaf2VisionPanel5Height = Leaf2VisionPanelWidth;
                        Leaf2VisionPanel5HeightToShow = Leaf2VisionPanelWidthToShow;
                    }

                } else {

                    $('select[name="AreVPsEqualSizesForLeaf2"]').removeAttr("readonly");

                    if (AreVPsEqualSizesForLeaf2 == "No") {

                        $('input[name="vP2Height1"]').removeAttr("readonly");

                        if (VisionPanelQuantityForLeaf2 > 1) {
                            $('input[name="vP2Height2"]').removeAttr("readonly");
                        }

                        if (VisionPanelQuantityForLeaf2 > 2) {
                            $('input[name="vP2Height3"]').removeAttr("readonly");
                        }

                        if (VisionPanelQuantityForLeaf2 > 3) {
                            $('input[name="vP2Height4"]').removeAttr("readonly");
                        }

                        if (VisionPanelQuantityForLeaf2 > 4) {
                            $('input[name="vP2Height5"]').removeAttr("readonly");
                        }

                    }

                }


                var RemainingWidthOfVisionPanelForRightLeaf = 0;
                var TotalHeightOfVisionPanelForRightLeaf = 0;

                RemainingWidthOfVisionPanelForRightLeaf = LeafWidth2 - (parseFloat(DistanceFromTheEdgeOfDoorForLeaf2ToShow) + parseFloat(Leaf2VisionPanelWidthToShow));
                TotalHeightOfVisionPanelForRightLeaf = parseFloat(DistanceFromTopOfDoorForLeaf2) + parseFloat(Leaf2VisionPanel1Height);

                if (VisionPanelQuantityForLeaf2 > 1) {
                    TotalHeightOfVisionPanelForRightLeaf = parseFloat(TotalHeightOfVisionPanelForRightLeaf) + parseFloat(DistanceBetweenVPsForLeaf2) + parseFloat(Leaf2VisionPanel2Height);
                }

                if (VisionPanelQuantityForLeaf2 > 2) {
                    TotalHeightOfVisionPanelForRightLeaf = parseFloat(TotalHeightOfVisionPanelForRightLeaf) + parseFloat(DistanceBetweenVPsForLeaf2) + parseFloat(Leaf2VisionPanel3Height);
                }

                if (VisionPanelQuantityForLeaf2 > 3) {
                    TotalHeightOfVisionPanelForRightLeaf = parseFloat(TotalHeightOfVisionPanelForRightLeaf) + parseFloat(DistanceBetweenVPsForLeaf2) + parseFloat(Leaf2VisionPanel4Height);
                }

                if (VisionPanelQuantityForLeaf2 > 4) {
                    TotalHeightOfVisionPanelForRightLeaf = parseFloat(TotalHeightOfVisionPanelForRightLeaf) + parseFloat(DistanceBetweenVPsForLeaf2) + parseFloat(Leaf2VisionPanel5Height);
                }

                console.log(RemainingWidthOfVisionPanelForRightLeaf + "--" + DistanceFromTheEdgeOfDoorMinValueforLeaf2);

                if (RemainingWidthOfVisionPanelForRightLeaf < DistanceFromTheEdgeOfDoorMinValueforLeaf2) {


                    if (VisionPanelShape == "Rectangle") {
                        DistanceFromTheEdgeOfDoorForLeaf2ToShow = parseFloat(LeafWidth2) - (parseFloat(DistanceFromTheEdgeOfDoorMinValueforLeaf2) + parseFloat(Leaf2VisionPanelWidthToShow));
                        DistanceFromTheEdgeOfDoorForLeaf2 = DistanceFromTheEdgeOfDoorForLeaf2ToShow / 5;

                        $('input[name="distanceFromTheEdgeOfDoorforLeaf2"]').val(DistanceFromTheEdgeOfDoorForLeaf2ToShow);

                        swal('Warning!', "Distance of vision panel from right edge should be atleast " + DistanceFromTheEdgeOfDoorMinValueforLeaf2 + "mm");
                    }


                    if ($.inArray(VisionPanelShape, ["Diamond", "Circle", "Square"]) !== -1) {

                        DistanceFromTheEdgeOfDoorForLeaf2ToShow = DistanceFromTheEdgeOfDoorMinValueforLeaf2;
                        DistanceFromTheEdgeOfDoorForLeaf2 = DistanceFromTheEdgeOfDoorMinValueforLeaf2 / 5;

                        $('input[name="distanceFromTheEdgeOfDoorforLeaf2"]').val(DistanceFromTheEdgeOfDoorMinValueforLeaf2);

                        // var NewLeaf2VisionPanelWidth = LeafWidth2 - (parseFloat(DistanceFromTheEdgeOfDoorForLeaf2ToShow) * 2);
                        // Leaf2VisionPanelWidth = NewLeaf2VisionPanelWidth/5;
                        // Leaf2VisionPanelWidthToShow = NewLeaf2VisionPanelWidth;
                        // $("#vP2Width").val(NewLeaf2VisionPanelWidth);
                    } else {
                        //swal('Warning!',"Distance of vision panel from left edge should not be less than the distance from right edge.");
                    }
                }

                if (TotalHeightOfVisionPanelForRightLeaf >= RemainingHeightOfLeaf) {

                    DistanceFromTopOfDoorForLeaf2ToShow = DistanceFromTopOfDoorMinValueforLeaf2;
                    DistanceFromTopOfDoorForLeaf2 = DistanceFromTopOfDoorMinValueforLeaf2 / 5;

                    $('input[name="distanceFromTopOfDoorforLeaf2"]').val(DistanceFromTopOfDoorMinValueforLeaf2);

                    DistanceBetweenVPsForLeaf2ToShow = DistanceBetweenVPsMinValueforLeaf2;
                    DistanceBetweenVPsForLeaf2 = DistanceBetweenVPsMinValueforLeaf2 / 5;

                    $('input[name="distanceBetweenVPsforLeaf2"]').val(DistanceBetweenVPsMinValueforLeaf2);

                    var NumberOfVps = VisionPanelQuantityForLeaf2 - 1;

                    var NewLeaf2VisionPanelHeight = (LeafHeightNoOP - ((KickPlatesHeight * 5) + 3)) - ((parseFloat(DistanceFromTheEdgeOfDoorForLeaf2ToShow) * 2) + (parseFloat(DistanceBetweenVPsForLeaf2ToShow) * NumberOfVps));

                    NewLeaf2VisionPanelHeight = Math.floor(NewLeaf2VisionPanelHeight / VisionPanelQuantityForLeaf2);

                    if (KickPlatesHeight > 0) {
                        NewLeaf2VisionPanelHeight = NewLeaf2VisionPanelHeight - 3;
                    }

                    if ($.inArray(VisionPanelShape, ["Diamond", "Circle", "Square"]) !== -1) {

                        if (NewLeaf2VisionPanelHeight > (LeafWidth2 - (parseFloat(DistanceFromTheEdgeOfDoorForLeaf2ToShow) * 2))) {

                            NewLeaf2VisionPanelHeight = LeafWidth2 - (parseFloat(DistanceFromTheEdgeOfDoorForLeaf2ToShow) * 2);

                            // NewLeaf2VisionPanelHeight = Math.floor(NewLeaf2VisionPanelHeight/VisionPanelQuantityForLeaf2);

                            if (KickPlatesHeight > 0) {
                                NewLeaf2VisionPanelHeight = NewLeaf2VisionPanelHeight - 3;
                            }
                        }

                        Leaf2VisionPanelWidth = NewLeaf2VisionPanelHeight / 5;
                        Leaf2VisionPanelWidthToShow = NewLeaf2VisionPanelHeight;
                        $("#vP2Width").val(NewLeaf2VisionPanelHeight);
                    }

                    Leaf2VisionPanel1Height = NewLeaf2VisionPanelHeight / 5;
                    Leaf2VisionPanel1HeightToShow = NewLeaf2VisionPanelHeight;
                    $("#vP2Height1").val(NewLeaf2VisionPanelHeight);

                    if (VisionPanelQuantityForLeaf2 > 1) {
                        Leaf2VisionPanel2Height = NewLeaf2VisionPanelHeight / 5;
                        Leaf2VisionPanel2HeightToShow = NewLeaf2VisionPanelHeight;
                        $("#vP2Height2").val(NewLeaf2VisionPanelHeight);
                    }

                    if (VisionPanelQuantityForLeaf2 > 2) {
                        Leaf2VisionPanel3Height = NewLeaf2VisionPanelHeight / 5;
                        Leaf2VisionPanel3HeightToShow = NewLeaf2VisionPanelHeight;
                        $("#vP2Height3").val(NewLeaf2VisionPanelHeight);
                    }

                    if (VisionPanelQuantityForLeaf2 > 3) {
                        Leaf2VisionPanel4Height = NewLeaf2VisionPanelHeight / 5;
                        Leaf2VisionPanel4HeightToShow = NewLeaf2VisionPanelHeight;
                        $("#vP2Height4").val(NewLeaf2VisionPanelHeight);
                    }

                    if (VisionPanelQuantityForLeaf2 > 4) {
                        Leaf2VisionPanel5Height = NewLeaf2VisionPanelHeight / 5;
                        Leaf2VisionPanel5HeightToShow = NewLeaf2VisionPanelHeight;
                        $("#vP2Height5").val(NewLeaf2VisionPanelHeight);
                    }

                    if (ChangedFieldName == "distanceFromTheEdgeOfDoorforLeaf2") {
                        swal('Warning!', "Entered distance from the edge of door of right leaf exceeds the width of right leaf.");
                    } else if (ChangedFieldName == "distanceFromTopOfDoorforLeaf2") {
                        swal('Warning!', "Entered distance from top of door of right leaf exceeds the hight of right leaf.");
                    } else if (ChangedFieldName == "distanceBetweenVPsforLeaf2") {
                        swal('Warning!', "Entered distance between vps of right leaf exceeds the hight of right leaf.");
                    } else {
                        swal('Warning!', "Sum of all vision panel's height should not be greater than right leaf's height.");
                    }
                }

                // var TotalDistanceForLeaf2VisionPanel1 = ix + LeftGapForLeaf1 + LeafWidth1ForMap + (RemainedSpaceInLeaf1 / 5);
                var TotalDistanceForLeaf2VisionPanel1WithOutRemainingSpace = ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles;
                var TotalDistanceForLeaf2VisionPanel1 = ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + DistanceFromTheEdgeOfDoorForLeaf2;


                var RightSideDistanceOfVisonPanelOfDoorLeaf2ToShow = LeafWidth2 - (parseFloat(DistanceFromTheEdgeOfDoorForLeaf2ToShow) + parseFloat(Leaf2VisionPanelWidthToShow));
                var RightSideDistanceOfVisonPanelOfDoorLeaf2 = (RightSideDistanceOfVisonPanelOfDoorLeaf2ToShow / 5) - RemainingGap;

                var DistanceXForLeaf2VPShape = TotalDistanceForLeaf2VisionPanel1;
                var DistanceYForLeaf2VPShape = iy + GapAfterOverPanelApplied + UpperAndLowerGap + DistanceFromTopOfDoorForLeaf2;

                var CircleRadiusForLeaf2 = parseFloat(Leaf2VisionPanelWidth) / 2;
                var DiamondDimensionForLeaf2 = 'M ' + CircleRadiusForLeaf2 + ' 0 ' + Leaf2VisionPanelWidth + ' ' + CircleRadiusForLeaf2 + ' ' + CircleRadiusForLeaf2 + ' ' + Leaf2VisionPanelWidth + ' 0 ' + CircleRadiusForLeaf2 + ' Z';

                if (VisionPanelShape == "Diamond") {
                    svg.append('path')
                        .attr('d', DiamondDimensionForLeaf2)
                        .attr("fill", "#fff")
                        .style("stroke", "black")
                        .attr("transform", "translate(" + DistanceXForLeaf2VPShape + "," + DistanceYForLeaf2VPShape + ")");

                } else if (VisionPanelShape == "Circle") {
                    svg.append("circle")
                        .style("stroke", "black")
                        .style("fill", "#fff")
                        .attr("r", CircleRadiusForLeaf2)
                        .attr("cx", DistanceXForLeaf2VPShape + CircleRadiusForLeaf2)
                        .attr("cy", DistanceYForLeaf2VPShape + CircleRadiusForLeaf2);
                } else {
                    svg.append('rect')
                        .attr('x', DistanceXForLeaf2VPShape)
                        .attr('y', DistanceYForLeaf2VPShape)
                        .attr('width', Leaf2VisionPanelWidth)
                        .attr('height', Leaf2VisionPanel1Height)
                        .attr('stroke', 'black')
                        .attr('fill', '#fff');
                }
if (Leaf2VisionPanel1Height && Leaf2VisionPanelWidth) {

     if ((Leaf2VisionPanelWidth * 2) > (LeafWidth2ForMap)) {
                            if (VisionPanelShape == "Diamond" || VisionPanelShape == "Circle") {
                                svg.append("text")
                                    .style("fill", "black")
                                    .attr("font-size", 10)
                                    .attr("x", DistanceXForLeaf2VPShape + (parseFloat(Leaf2VisionPanelWidth) / 2) - 7)
                                    .attr("y", DistanceYForLeaf2VPShape + (Leaf2VisionPanel2Height / 2) + 10)
                                    .text(Leaf2VisionPanelWidthToShow);

                                svg.append('line')
                                    .style("stroke", "black")
                                    .style("stroke-width", 0.5)
                                    .attr("x1", DistanceXForLeaf2VPShape)
                                    .attr("y1", DistanceYForLeaf2VPShape + (Leaf2VisionPanel1Height / 2))
                                    .attr("x2", DistanceXForLeaf2VPShape + parseFloat(Leaf2VisionPanelWidth))
                                    .attr("y2", DistanceYForLeaf2VPShape + (Leaf2VisionPanel1Height / 2))
                                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                                    .attr("marker-end", "url(#arrowRight)");
                            } else {
                                svg.append("text")
                                    .style("fill", "black")
                                    .attr("font-size", 10)
                                    .attr("x", DistanceXForLeaf2VPShape + (parseFloat(Leaf2VisionPanelWidth) / 2) - 7)
                                    .attr("y", iy + TopFrameHeight + GapForMap + 50)
                                    .text(Leaf2VisionPanelWidthToShow);

                                svg.append('line')
                                    .style("stroke", "black")
                                    .style("stroke-width", 0.5)
                                    .attr("x1", DistanceXForLeaf2VPShape)
                                    .attr("y1", iy + TopFrameHeight + GapForMap + 30)
                                    .attr("x2", DistanceXForLeaf2VPShape + parseFloat(Leaf2VisionPanelWidth))
                                    .attr("y2", iy + TopFrameHeight + GapForMap + 30)
                                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                                    .attr("marker-end", "url(#arrowRight)");
                            }


                        } else {
                            svg.append("text")
                                .style("fill", "black")
                                .attr("font-size", 10)
                                .attr("x", DistanceXForLeaf2VPShape + (parseFloat(Leaf2VisionPanelWidth) / 2) - 7)
                                .attr("y", iy - 40)
                                .text(Leaf2VisionPanelWidthToShow);

                            svg.append('line')
                                .style("stroke", "black")
                                .style("stroke-width", 0.5)
                                .attr("x1", DistanceXForLeaf2VPShape)
                                .attr("y1", iy - 35)
                                .attr("x2", DistanceXForLeaf2VPShape + parseFloat(Leaf2VisionPanelWidth))
                                .attr("y2", iy - 35)
                                .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                                .attr("marker-end", "url(#arrowRight)");

                            svg.append('line')
                                .style("stroke", "black")
                                .style("stroke-width", 0.5)
                                .attr("x1", DistanceXForLeaf2VPShape)
                                .attr("y1", iy - 35 - 5)
                                .attr("x2", DistanceXForLeaf2VPShape)
                                .attr("y2", DistanceYForLeaf2VPShape + (Leaf2VisionPanel2Height / 2))

                            svg.append('line')
                                .style("stroke", "black")
                                .style("stroke-width", 0.5)
                                .attr("x1", DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth)
                                .attr("y1", DistanceYForLeaf2VPShape + (Leaf2VisionPanel1Height / 2))
                                .attr("x2", DistanceXForLeaf2VPShape + parseFloat(Leaf2VisionPanelWidth))
                                .attr("y2", iy - 35 - 5)
                        }
var RemainedSpaceInLeaf2 = DistanceFromTheEdgeOfDoorForLeaf2ToShow;

  if (ShowMeasurements) {
    
                svg.append("text")           //
                    .style("fill", "black")
                    .attr("font-size", 10)
                    .attr("x", ((TotalDistanceForLeaf2VisionPanel1WithOutRemainingSpace+DistanceXForLeaf2VPShape)/2)-7)
                    .attr("y", DistanceYForLeaf2VPShape + (Leaf2VisionPanel1Height / 2) - 10)
                    .text(RemainedSpaceInLeaf2);
                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", TotalDistanceForLeaf2VisionPanel1WithOutRemainingSpace)
                    .attr("y1", DistanceYForLeaf2VPShape + (Leaf2VisionPanel1Height / 2))
                    .attr("x2", DistanceXForLeaf2VPShape)
                    .attr("y2", DistanceYForLeaf2VPShape + (Leaf2VisionPanel1Height / 2))
                    .attr("marker-start", "url(#arrowLeft)")
                    .attr("marker-end", "url(#arrowRight)");

                    {
                //default distance from top (vission panel)

                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))
                    .attr("y1", iy + GapAfterOverPanelApplied + UpperAndLowerGap)
                    .attr("x2", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))
                    .attr("y2", DistanceYForLeaf2VPShape)
                    .attr("marker-start", "url(#arrowLeft)")
                    .attr("marker-end", "url(#arrowRight)");

                svg.append("text")
                    .style("fill", "black")
                    .style("writing-mode", WritingMode)
                    .attr("x", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2) + 20 )
                    .attr("font-size", 10)
                    .attr("y", iy + GapAfterOverPanelApplied + UpperAndLowerGap + (DistanceFromTopOfDoorForLeaf2 / 2))
                    .attr("transform", `rotate(-90, ${DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2)+ 20}, ${iy + GapAfterOverPanelApplied + UpperAndLowerGap + (DistanceFromTopOfDoorForLeaf2 / 2) + 7})`)
                    .text(DistanceFromTopOfDoorForLeaf2*5);


                if ((Leaf2VisionPanel1Height * 5) < ((LeafHeightNoOPForMap * 5) / 2)) { // when height of vission pannel is less then half of height of inner frame
                    svg.append('line')   // yellow
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf2VPShape+Leaf2VisionPanelWidth+ 10)
                        .attr("y1", DistanceYForLeaf2VPShape)
                        .attr("x2", DistanceXForLeaf2VPShape+Leaf2VisionPanelWidth+ 10)
                        .attr("y2", DistanceYForLeaf2VPShape + parseFloat(Leaf2VisionPanel1Height))
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)");

                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", DistanceXForLeaf2VPShape+ Leaf2VisionPanelWidth+ 25)
                        .attr("font-size", 10)
                        .attr("y", DistanceYForLeaf2VPShape + (parseFloat(Leaf2VisionPanel1Height) / 2))
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf2VPShape+Leaf2VisionPanelWidth+25}, ${DistanceYForLeaf2VPShape + (parseFloat(Leaf2VisionPanel1Height) / 2)})`)
                        .text(Leaf2VisionPanel1HeightToShow);

                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))
                        .attr("y1", DistanceYForLeaf2VPShape + Leaf2VisionPanel1Height)
                        .attr("x2", DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth + 15)
                        .attr("y2", DistanceYForLeaf2VPShape + parseFloat(Leaf2VisionPanel1Height))

                    svg.append('line')   //
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth + 15)
                        .attr("y1", DistanceYForLeaf2VPShape)
                        .attr("x2", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))
                        .attr("y2", DistanceYForLeaf2VPShape)


                } else {  // when height of vission pannel is more then half of height of inner frame

                    if (VisionPanelShape == "Diamond" || VisionPanelShape == "Circle") {
                        // ix - 15 - SideLightPanel1WidthSpaceForVerticalLines

                        svg.append('line')   // yellow
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                            .attr("y1", DistanceYForLeaf2VPShape)
                            .attr("x2", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                            .attr("y2", DistanceYForLeaf2VPShape + parseFloat(Leaf2VisionPanel1Height))
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)");

                        svg.append("text")            // append text
                            .style("fill", "black")      // make the text black
                            .style("writing-mode", WritingMode) // set the writing mode
                            .attr("x", DistanceXForLeaf2VPShape + 20)         // set x position of left side of text
                            .attr("font-size", 10)
                            .attr("y", DistanceYForLeaf2VPShape + (parseFloat(Leaf2VisionPanel1Height) / 2) + 5)         // set y position of bottom of text
                            .text(Leaf2VisionPanel1HeightToShow);   // define the text to display
                    } else {
                        svg.append('line')   // yellow
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", DistanceXForLeaf2VPShape + 20)
                            .attr("y1", DistanceYForLeaf2VPShape)
                            .attr("x2", DistanceXForLeaf2VPShape + 20)
                            .attr("y2", DistanceYForLeaf2VPShape + parseFloat(Leaf2VisionPanel1Height))
                            .attr("marker-start", "url(#arrowLeft)")
                            .attr("marker-end", "url(#arrowRight)");

                        svg.append("text")
                            .style("fill", "black")
                            .style("writing-mode", WritingMode)
                            .attr("x", DistanceXForLeaf2VPShape + 10)
                            .attr("font-size", 10)
                            .attr("y", DistanceYForLeaf2VPShape + (parseFloat(Leaf2VisionPanel1Height) / 2) + 5)
                            .attr("transform", `rotate(-90, ${DistanceXForLeaf2VPShape + 10}, ${DistanceYForLeaf2VPShape + (parseFloat(Leaf2VisionPanel1Height) / 2)})`)
                            .text(Leaf2VisionPanel1HeightToShow);

                    }
                }




                const totalShapeHeight = (parseFloat(Leaf2VisionPanel1Height) * (VisionPanelQuantityForLeaf2)) + parseFloat(Leaf2VisionPanel1Height) / 2;
                const spaceBetweenShapes = (VisionPanelQuantityForLeaf2 - 1) * ((+DistanceBetweenVPsMinValue) / 5);
                const totalTopDistance = (totalShapeHeight + spaceBetweenShapes + iy + UpperAndLowerGap)
                // // console.log(SOHeightForMap,totalTopDistance,iy + SOHeightForMap);

                if (VisionPanelQuantityForLeaf1 == 1) {
                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))
                        .attr("y1", DistanceYForLeaf2VPShape + parseFloat(Leaf2VisionPanel1Height))
                        .attr("x2", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))
                        .attr("y2", iy + SOHeightForMap)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)");
                        svg.append("text")            // append text
                        .style("fill", "black")      // make the text black
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2) + 5)         // set x position of left side of text
                        .attr("font-size", 10)
                        .attr("y", (   DistanceYForLeaf2VPShape + parseFloat(Leaf2VisionPanel1Height)+iy + SOHeightForMap)/2)         // set y position of bottom of text
                        .text(LeafHeightNoOP - (DistanceFromTopOfDoorForLeaf2*5) - ((VisionPanelQuantityForLeaf2 - 1) * (+DistanceBetweenVPsForLeaf2ToShow)) - (VisionPanelQuantityForLeaf2 * (Leaf2VisionPanel1Height * 5)));
                        if((LeafHeightNoOP - (DistanceFromTopOfDoorForLeaf2*5) - ((VisionPanelQuantityForLeaf2 - 1) * (+DistanceBetweenVPsForLeaf2ToShow)) - (VisionPanelQuantityForLeaf2 * (Leaf2VisionPanel1Height * 5)))<200){
                           var newHeight = (Leaf2VisionPanel1Height * 5)-(200 -(LeafHeightNoOP - (DistanceFromTopOfDoorForLeaf2*5) - ((VisionPanelQuantityForLeaf2 - 1) * (+DistanceBetweenVPsForLeaf2ToShow)) - (VisionPanelQuantityForLeaf2 * (Leaf2VisionPanel1Height * 5))))
                           swal('Warning!', "Distance from bottom cannot be less than 200");
                                 $(document).ready(function () {
    $('#vP2Height1').on('input', function () {
      first=true
      console.log("aaaaaaaaaaaaaaa") 
    });
     $('#distanceFromTopOfDoorforLeaf2').on('input', function () {
        first=true
        console.log("bbbbbbbbbbbbbb")
    });
});
                    if(first){
                            $('input[name="vP2Height1"]').val(newHeight).trigger('change');
                            first=false
                           }
                           
                        }
                }




            }
            
  }


  if (VisionPanelQuantityForLeaf2 > 1) {

                    DistanceYForLeaf2VPShape = DistanceYForLeaf2VPShape + Leaf2VisionPanel1Height + DistanceBetweenVPsForLeaf2;

                    if (VisionPanelShape == "Diamond") {
                        svg.append('path')
                            .attr('d', DiamondDimensionForLeaf2)
                            .attr("fill", "#fff")
                            .style("stroke", "black")
                            .attr("transform", "translate(" + DistanceXForLeaf2VPShape + "," + DistanceYForLeaf2VPShape + ")");
                        // console.log(DistanceXForLeaf2VPShape, "DistanceXForLeaf2VPShape")
                    } else if (VisionPanelShape == "Circle") {
                        svg.append("circle")
                            .style("stroke", "black")
                            .style("fill", "#fff")
                            .attr("r", CircleRadiusForLeaf2)
                            .attr("cx", DistanceXForLeaf2VPShape + CircleRadiusForLeaf2)
                            .attr("cy", DistanceYForLeaf2VPShape + CircleRadiusForLeaf2);
                    } else {
                        svg.append('rect')
                            .attr('x', DistanceXForLeaf2VPShape)
                            .attr('y', DistanceYForLeaf2VPShape)
                            .attr('width', Leaf2VisionPanelWidth)
                            .attr('height', Leaf2VisionPanel2Height)
                            .attr('stroke', 'black')
                            .attr('fill', '#fff');
                    }

                     if (ShowMeasurements) {

                /* Vertical Line for vision panel */
                
                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))
                        .attr("y1", DistanceYForLeaf2VPShape - DistanceBetweenVPsForLeaf2)
                        .attr("x2", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))
                        .attr("y2", DistanceYForLeaf2VPShape)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)");

                    svg.append("text")            // append text
                        .style("fill", "black")      // make the text black
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2)+20) // set x position of the text
                        .attr("font-size", 10)
                        .attr("y", (DistanceYForLeaf2VPShape - DistanceBetweenVPsForLeaf2) + (DistanceBetweenVPsForLeaf2 / 2)) // set y position of the text
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2)+20}, ${(DistanceYForLeaf2VPShape - DistanceBetweenVPsForLeaf2) + (DistanceBetweenVPsForLeaf2 / 2) + 5})`)
                        .text(DistanceBetweenVPsForLeaf2ToShow); // define the text to display


                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth + 10)
                        .attr("y1", DistanceYForLeaf2VPShape)
                        .attr("x2", DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth + 10)
                        .attr("y2", DistanceYForLeaf2VPShape + Leaf2VisionPanel2Height)
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)");

                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", DistanceXForLeaf2VPShape +Leaf2VisionPanelWidth + 25) // set x position
                        .attr("font-size", 10)
                        .attr("y", DistanceYForLeaf2VPShape + (Leaf2VisionPanel2Height / 2)) // set y position
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf2VPShape +Leaf2VisionPanelWidth + 25}, ${DistanceYForLeaf2VPShape + (Leaf2VisionPanel2Height / 2) + 5})`)
                        .text(Leaf2VisionPanel2HeightToShow);


                    createLine("black", 0.5, DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2), DistanceYForLeaf2VPShape, DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth + 15, DistanceYForLeaf2VPShape);
                    createLine("black", 0.5, DistanceXForLeaf2VPShape +Leaf2VisionPanelWidth+ 15, DistanceYForLeaf2VPShape + Leaf2VisionPanel2Height, DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2), DistanceYForLeaf2VPShape + Leaf2VisionPanel2Height);
                    if (Leaf2VisionPanel2Height && VisionPanelQuantityForLeaf2 == 2) {

                        const totalShapeHeight = (parseFloat(Leaf2VisionPanel1Height) * (VisionPanelQuantityForLeaf2)) + parseFloat(Leaf2VisionPanel1Height) / 2;
                        const spaceBetweenShapes = (VisionPanelQuantityForLeaf2 - 1) * ((+DistanceBetweenVPsMinValue) / 5);
                        const totalTopDistance = (totalShapeHeight + spaceBetweenShapes + iy + UpperAndLowerGap)
                        // // console.log(SOHeightForMap,totalTopDistance,iy + SOHeightForMap);
                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))
                            .attr("y1", DistanceYForLeaf2VPShape + Leaf2VisionPanel2Height)
                            .attr("x2", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))
                            .attr("y2", iy + SOHeightForMap)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)");

                            svg.append("text")            // append text
                            .style("fill", "black")      // make the text black
                            .style("writing-mode", WritingMode) // set the writing mode
                            .attr("x", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2) + 5)         // set x position of left side of text
                            .attr("font-size", 10)
                            .attr("y", (   DistanceYForLeaf2VPShape + parseFloat(Leaf2VisionPanel2Height)+iy + SOHeightForMap)/2)         // set y position of bottom of text
                            .text(LeafHeightNoOP - (DistanceFromTopOfDoorForLeaf2*5) - ((VisionPanelQuantityForLeaf2 - 1) * (+DistanceBetweenVPsForLeaf2ToShow)) -  (Leaf2VisionPanel1Height * 5)-(Leaf2VisionPanel2Height*5));
                               if((LeafHeightNoOP - (DistanceFromTopOfDoorForLeaf2*5) - ((VisionPanelQuantityForLeaf2 - 1) * (+DistanceBetweenVPsForLeaf2ToShow)) -  (Leaf2VisionPanel1Height * 5)-(Leaf2VisionPanel2Height*5))<200){
                           var newHeight = (Leaf2VisionPanel2Height * 5)-(200 -(LeafHeightNoOP - (DistanceFromTopOfDoorForLeaf2*5) - ((VisionPanelQuantityForLeaf2 - 1) * (+DistanceBetweenVPsForLeaf2ToShow)) -  (Leaf2VisionPanel1Height * 5)-(Leaf2VisionPanel2Height*5)))
                           swal('Warning!', "Distance from bottom cannot be less than 200");
                           
      $(document).ready(function () {
    $('#vP2Height2').on('input', function () {
        first=true
        
    });
     $('#vP2Height1').on('input', function () {
        first=true });
     $('#DistanceFromTopOfDoorForLeaf2').on('input', function () {
        first=true
       
    });
});
                            if(first){
                            $('input[name="vP2Height2"]').val(newHeight).trigger('change');
                            first=false
                           }
                        }
                    }
                

            }
                }
    if (VisionPanelQuantityForLeaf2 > 2) {

                    DistanceYForLeaf2VPShape = DistanceYForLeaf2VPShape + Leaf2VisionPanel2Height + DistanceBetweenVPsForLeaf2;

                    if (VisionPanelShape == "Diamond") {
                        svg.append('path')
                            .attr('d', DiamondDimensionForLeaf2)
                            .attr("fill", "#fff")
                            .style("stroke", "black")
                            .attr("transform", "translate(" + DistanceXForLeaf2VPShape + "," + DistanceYForLeaf2VPShape + ")");
                    } else if (VisionPanelShape == "Circle") {
                        svg.append("circle")
                            .style("stroke", "black")
                            .style("fill", "#fff")
                            .attr("r", CircleRadiusForLeaf2)
                            .attr("cx", DistanceXForLeaf2VPShape + CircleRadiusForLeaf2)
                            .attr("cy", DistanceYForLeaf2VPShape + CircleRadiusForLeaf2);
                    } else {
                        svg.append('rect')
                            .attr('x', DistanceXForLeaf2VPShape)
                            .attr('y', DistanceYForLeaf2VPShape)
                            .attr('width', Leaf2VisionPanelWidth)
                            .attr('height', Leaf2VisionPanel3Height)
                            .attr('stroke', 'black')
                            .attr('fill', '#fff');
                    }

                     if (ShowMeasurements) {

                /* Vertical Line for vision panel */
               
                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))
                        .attr("y1", DistanceYForLeaf2VPShape - DistanceBetweenVPsForLeaf2)
                        .attr("x2", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))
                        .attr("y2", DistanceYForLeaf2VPShape)
                        .attr("marker-start", "url(#arrowLeft)")
                        .attr("marker-end", "url(#arrowRight)");

                    svg.append("text")
                        .style("fill", "black")
                        .style("writing-mode", WritingMode)
                        .attr("x", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2)+ 20)
                        .attr("font-size", 10)
                        .attr("y", (DistanceYForLeaf2VPShape - DistanceBetweenVPsForLeaf2) + (DistanceBetweenVPsForLeaf2 / 2)) // set y position of text
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2)+20}, ${(DistanceYForLeaf2VPShape - DistanceBetweenVPsForLeaf2) + (DistanceBetweenVPsForLeaf2 / 2) + 5})`)
                        .text(DistanceBetweenVPsForLeaf2ToShow);


                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth + 10)
                        .attr("y1", DistanceYForLeaf2VPShape)
                        .attr("x2", DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth + 10)
                        .attr("y2", DistanceYForLeaf2VPShape + Leaf2VisionPanel3Height)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)");

                    svg.append("text")            // append text
                        .style("fill", "black")      // set text color
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth + 25) // set x position of text
                        .attr("font-size", 10)
                        .attr("y", DistanceYForLeaf2VPShape + (Leaf2VisionPanel3Height / 2)) // set y position of text
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth + 25}, ${DistanceYForLeaf2VPShape + (Leaf2VisionPanel3Height / 2) + 5})`)
                        .text(Leaf2VisionPanel3HeightToShow);
                    createLine("black", 0.5, DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2), DistanceYForLeaf2VPShape, DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth + 15, DistanceYForLeaf2VPShape);
                    createLine("black", 0.5, DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth + 15, DistanceYForLeaf2VPShape + Leaf2VisionPanel3Height, DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2), DistanceYForLeaf2VPShape + Leaf2VisionPanel3Height);

                    if (Leaf2VisionPanel3Height && VisionPanelQuantityForLeaf2 == 3) {

                        const totalShapeHeight = (parseFloat(Leaf2VisionPanel1Height) * (VisionPanelQuantityForLeaf2)) + parseFloat(Leaf2VisionPanel1Height) / 2;
                        const spaceBetweenShapes = (VisionPanelQuantityForLeaf2 - 1) * ((+DistanceBetweenVPsMinValue) / 5);
                        const totalTopDistance = (totalShapeHeight + spaceBetweenShapes + iy + UpperAndLowerGap)
                        // // console.log(SOHeightForMap,totalTopDistance,iy + SOHeightForMap);
                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))
                            .attr("y1", DistanceYForLeaf2VPShape + Leaf2VisionPanel3Height)
                            .attr("x2", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))
                            .attr("y2", iy + SOHeightForMap )
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)");
                            svg.append("text")            // append text
                            .style("fill", "black")      // make the text black
                            .style("writing-mode", WritingMode) // set the writing mode
                            .attr("x", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2) + 5)         // set x position of left side of text
                            .attr("font-size", 10)
                            .attr("y", (   DistanceYForLeaf2VPShape + parseFloat(Leaf2VisionPanel3Height)+iy + SOHeightForMap)/2)         // set y position of bottom of text
                                                      .text(LeafHeightNoOP - (DistanceFromTopOfDoorForLeaf2*5) - ((VisionPanelQuantityForLeaf2 - 1) * (+DistanceBetweenVPsForLeaf2ToShow)) -  (Leaf2VisionPanel1Height * 5) - (Leaf2VisionPanel2Height * 5)-(Leaf2VisionPanel3Height * 5)  );
                           if((LeafHeightNoOP - (DistanceFromTopOfDoorForLeaf2*5) - ((VisionPanelQuantityForLeaf2 - 1) * (+DistanceBetweenVPsForLeaf2ToShow)) -  (Leaf2VisionPanel1Height * 5) - (Leaf2VisionPanel2Height * 5)-(Leaf2VisionPanel3Height * 5))<200){
                           var newHeight = (Leaf2VisionPanel3Height * 5)-(200 -(LeafHeightNoOP - (DistanceFromTopOfDoorForLeaf2*5) - ((VisionPanelQuantityForLeaf2 - 1) * (+DistanceBetweenVPsForLeaf2ToShow)) -  (Leaf2VisionPanel1Height * 5) - (Leaf2VisionPanel2Height * 5)-(Leaf2VisionPanel3Height * 5)))
                           swal('Warning!', "Distance from bottom cannot be less than 200");
                                 $(document).ready(function () {
    $('#vP2Height1').on('input', function () {
        first=true });
    $('#vP2Height2').on('input', function () {
        first=true  
    });
    $('#vP2Height3').on('input', function () {
        first=true
        console.log('User changed the input. isUserChanged =', isUserChanged);
    });
     $('#distanceFromTopOfDoorforLeaf2').on('input', function () {
        first=true
    });
});
                          if(first){
                            $('input[name="vP1Height3"]').val(newHeight).trigger('change');
                            first=false
                           }
                           

                        }
                    }
                /* Vertical Line for vision panel */
            }

                }

                
                if (VisionPanelQuantityForLeaf2 > 3) {

                    DistanceYForLeaf2VPShape = DistanceYForLeaf2VPShape + Leaf2VisionPanel3Height + DistanceBetweenVPsForLeaf2;

                    if (VisionPanelShape == "Diamond") {
                        svg.append('path')
                            .attr('d', DiamondDimensionForLeaf2)
                            .attr("fill", "#fff")
                            .style("stroke", "black")
                            .attr("transform", "translate(" + DistanceXForLeaf2VPShape + "," + DistanceYForLeaf2VPShape + ")");
                    } else if (VisionPanelShape == "Circle") {
                        svg.append("circle")
                            .style("stroke", "black")
                            .style("fill", "#fff")
                            .attr("r", CircleRadiusForLeaf2)
                            .attr("cx", DistanceXForLeaf2VPShape + CircleRadiusForLeaf2)
                            .attr("cy", DistanceYForLeaf2VPShape + CircleRadiusForLeaf2);
                    } else {
                        svg.append('rect')
                            .attr('x', DistanceXForLeaf2VPShape)
                            .attr('y', DistanceYForLeaf2VPShape)
                            .attr('width', Leaf2VisionPanelWidth)
                            .attr('height', Leaf2VisionPanel4Height)
                            .attr('stroke', 'black')
                            .attr('fill', '#fff');
                    }

                    if (ShowMeasurements) {
                /* Vertical Line for vision panel */
               
                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))
                        .attr("y1", (DistanceYForLeaf2VPShape - DistanceBetweenVPsForLeaf2))
                        .attr("x2", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))
                        .attr("y2", DistanceYForLeaf2VPShape)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)");

                    svg.append("text")            // append text
                        .style("fill", "black")      // set text color
                        .style("writing-mode", WritingMode) // set writing mode
                        .attr("x", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2)+20) // set x position
                        .attr("font-size", 10)
                        .attr("y", (DistanceYForLeaf2VPShape - DistanceBetweenVPsForLeaf2) + (DistanceBetweenVPsForLeaf2 / 2)) // set y position
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2)+20}, ${(DistanceYForLeaf2VPShape - DistanceBetweenVPsForLeaf2) + (DistanceBetweenVPsForLeaf2 / 2) + 5})`)
                        .text(DistanceBetweenVPsForLeaf2ToShow); // display text


                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf2VPShape +Leaf2VisionPanelWidth + 10)
                        .attr("y1", DistanceYForLeaf2VPShape)
                        .attr("x2", DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth + 10)
                        .attr("y2", DistanceYForLeaf2VPShape + Leaf2VisionPanel4Height)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)");

                    svg.append("text")            // append text
                        .style("fill", "black")      // set text color
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth + 25) // set x position of text
                        .attr("font-size", 10)
                        .attr("y", DistanceYForLeaf2VPShape + (Leaf2VisionPanel4Height / 2)) // set y position of text
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth + 25}, ${DistanceYForLeaf2VPShape + (Leaf2VisionPanel4Height / 2) + 5})`)
                        .text(Leaf2VisionPanel4HeightToShow);
                    // define the text to display
                    createLine("black", 0.5, DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2), DistanceYForLeaf2VPShape, DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth + 15, DistanceYForLeaf2VPShape);
                    createLine("black", 0.5, DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth + 15, DistanceYForLeaf2VPShape + Leaf2VisionPanel4Height, DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2), DistanceYForLeaf2VPShape + Leaf2VisionPanel4Height);
                    if (Leaf2VisionPanel4Height && VisionPanelQuantityForLeaf2 == 4) {

                        const totalShapeHeight = (parseFloat(Leaf2VisionPanel1Height) * (VisionPanelQuantityForLeaf2)) + parseFloat(Leaf2VisionPanel1Height) / 2;
                        const spaceBetweenShapes = (VisionPanelQuantityForLeaf2 - 1) * ((+DistanceBetweenVPsMinValue) / 5);
                        const totalTopDistance = (totalShapeHeight + spaceBetweenShapes + iy + UpperAndLowerGap)
                        // // console.log(SOHeightForMap,totalTopDistance,iy + SOHeightForMap);
                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))
                            .attr("y1", DistanceYForLeaf2VPShape + Leaf2VisionPanel4Height)
                            .attr("x2", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))
                            .attr("y2", iy + SOHeightForMap )
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)");

                            svg.append("text")            // append text
                            .style("fill", "black")      // make the text black
                            .style("writing-mode", WritingMode) // set the writing mode
                            .attr("x", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2) + 5)         // set x position of left side of text
                            .attr("font-size", 10)
                            .attr("y", (   DistanceYForLeaf2VPShape + parseFloat(Leaf2VisionPanel4Height)+iy + SOHeightForMap)/2)         // set y position of bottom of text
                            .text(LeafHeightNoOP - (DistanceFromTopOfDoorForLeaf2*5) - ((VisionPanelQuantityForLeaf2 - 1) * (+DistanceBetweenVPsForLeaf2ToShow)) -  (Leaf2VisionPanel1Height * 5)-  (Leaf2VisionPanel2Height * 5)-  (Leaf2VisionPanel3Height * 5) -  (Leaf2VisionPanel4Height * 5));
                            
                             if((LeafHeightNoOP - (DistanceFromTopOfDoorForLeaf2*5) - ((VisionPanelQuantityForLeaf2 - 1) * (+DistanceBetweenVPsForLeaf2ToShow)) -  (Leaf2VisionPanel1Height * 5)-  (Leaf2VisionPanel2Height * 5)-  (Leaf2VisionPanel3Height * 5) -  (Leaf2VisionPanel4Height * 5))<200){
                           var newHeight = (Leaf2VisionPanel4Height * 5)-(200 -(LeafHeightNoOP - (DistanceFromTopOfDoorForLeaf2*5) - ((VisionPanelQuantityForLeaf2 - 1) * (+DistanceBetweenVPsForLeaf2ToShow)) -  (Leaf2VisionPanel1Height * 5)-  (Leaf2VisionPanel2Height * 5)-  (Leaf2VisionPanel3Height * 5) -  (Leaf2VisionPanel4Height * 5)))
                           swal('Warning!', "Distance from bottom cannot be less than 200");
      $(document).ready(function () {
        $('#vP2Height1').on('input', function () {
        first=true });
    $('#vP2Height2').on('input', function () {
        first=true
       
    });
    $('#vP2Height3').on('input', function () {
        first=true
        console.log('User changed the input. isUserChanged =', isUserChanged);
    });
    $('#vP2Height4').on('input', function () {
        
        first=true
       
    });
     $('#distanceFromTopOfDoor').on('input', function () {
        first=true
       
    });

});
                           if(first){
                            $('input[name="vP2Height4"]').val(newHeight).trigger('change');
                            first=false
                           }
   
                        }

                    }

                

                /* Vertical Line for vision panel */
            }

                    
                }

                 if (VisionPanelQuantityForLeaf2 > 4) {

                    DistanceYForLeaf2VPShape = DistanceYForLeaf2VPShape + Leaf2VisionPanel4Height + DistanceBetweenVPsForLeaf2;

                    if (VisionPanelShape == "Diamond") {
                        svg.append('path')
                            .attr('d', DiamondDimensionForLeaf2)
                            .attr("fill", "#fff")
                            .style("stroke", "black")
                            .attr("transform", "translate(" + DistanceXForLeaf2VPShape + "," + DistanceYForLeaf2VPShape + ")");
                    } else if (VisionPanelShape == "Circle") {
                        svg.append("circle")
                            .style("stroke", "black")
                            .style("fill", "#fff")
                            .attr("r", CircleRadiusForLeaf2)
                            .attr("cx", DistanceXForLeaf2VPShape + CircleRadiusForLeaf2)
                            .attr("cy", DistanceYForLeaf2VPShape + CircleRadiusForLeaf2);
                    } else {
                        svg.append('rect')
                            .attr('x', DistanceXForLeaf2VPShape)
                            .attr('y', DistanceYForLeaf2VPShape)
                            .attr('width', Leaf2VisionPanelWidth)
                            .attr('height', Leaf2VisionPanel5Height)
                            .attr('stroke', 'black')
                            .attr('fill', '#fff');
                    }

                     if (ShowMeasurements) {

                /* Vertical Line for vision panel */
                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))
                        .attr("y1", (DistanceYForLeaf2VPShape - DistanceBetweenVPsForLeaf2))
                        .attr("x2", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))
                        .attr("y2", DistanceYForLeaf2VPShape)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")      // set text color
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2)+20) // set x position of text
                        .attr("font-size", 10)
                        .attr("y", (DistanceYForLeaf2VPShape - DistanceBetweenVPsForLeaf2) + (DistanceBetweenVPsForLeaf2 / 2)) // set y position of text
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2)+20}, ${(DistanceYForLeaf2VPShape - DistanceBetweenVPsForLeaf2) + (DistanceBetweenVPsForLeaf2 / 2) + 5})`)
                        .text(DistanceBetweenVPsForLeaf2ToShow); // define the text to display


                    svg.append('line')
                        .style("stroke", "black")
                        .style("stroke-width", 0.5)
                        .attr("x1", DistanceXForLeaf2VPShape+Leaf2VisionPanelWidth + 10)
                        .attr("y1", DistanceYForLeaf2VPShape)
                        .attr("x2", DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth + 10)
                        .attr("y2", DistanceYForLeaf2VPShape + Leaf2VisionPanel5Height)
                        .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                        .attr("marker-end", "url(#arrowRight)")

                    svg.append("text")            // append text
                        .style("fill", "black")      // make the text black
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", DistanceXForLeaf2VPShape +Leaf2VisionPanelWidth +25)         // set x position of text
                        .attr("font-size", 10)
                        .attr("y", DistanceYForLeaf2VPShape + (Leaf2VisionPanel5Height / 2))        // set y position of text
                        .attr("transform", `rotate(-90, ${DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth + 25}, ${DistanceYForLeaf2VPShape + (Leaf2VisionPanel5Height / 2) + 5})`)
                        .text(Leaf2VisionPanel5HeightToShow);
                    createLine("black", 0.5, DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2), DistanceYForLeaf2VPShape, DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth + 15, DistanceYForLeaf2VPShape);
                    createLine("black", 0.5, DistanceXForLeaf2VPShape+ Leaf2VisionPanelWidth + 15, DistanceYForLeaf2VPShape + Leaf2VisionPanel5Height, DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2), DistanceYForLeaf2VPShape + Leaf2VisionPanel5Height);
                    if (Leaf2VisionPanel5Height && VisionPanelQuantityForLeaf2 == 5) {

                        const totalShapeHeight = (parseFloat(Leaf1VisionPanel2Height) * (VisionPanelQuantityForLeaf2)) + parseFloat(Leaf1VisionPanel2Height) / 2;
                        const spaceBetweenShapes = (VisionPanelQuantityForLeaf2 - 1) * ((+DistanceBetweenVPsMinValue) / 5);
                        const totalTopDistance = (totalShapeHeight + spaceBetweenShapes + iy + UpperAndLowerGap)
                        // // console.log(SOHeightForMap,totalTopDistance,iy + SOHeightForMap);
                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))
                            .attr("y1", DistanceYForLeaf2VPShape + Leaf2VisionPanel5Height)
                            .attr("x2", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))
                            .attr("y2", iy + SOHeightForMap )
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)");
                             svg.append("text")            // append text
                    .style("fill", "black")      // make the text black
                    .style("writing-mode", WritingMode) // set the writing mode
                    .attr("x", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2) + 5)         // set x position of left side of text
                    .attr("font-size", 10)
                    .attr("y", (   DistanceYForLeaf2VPShape + parseFloat(Leaf2VisionPanel5Height)+iy + SOHeightForMap)/2)         // set y position of bottom of text
                            .text(LeafHeightNoOP - (DistanceFromTopOfDoorForLeaf2*5) - ((VisionPanelQuantityForLeaf2 - 1) * (+DistanceBetweenVPsForLeaf2ToShow)) -  (Leaf2VisionPanel1Height * 5)-  (Leaf2VisionPanel2Height * 5)-  (Leaf2VisionPanel3Height * 5)-  (Leaf2VisionPanel4Height * 5)-  (Leaf2VisionPanel5Height * 5));
                   
  if((LeafHeightNoOP - (DistanceFromTopOfDoorForLeaf2*5) - ((VisionPanelQuantityForLeaf2 - 1) * (+DistanceBetweenVPsForLeaf2ToShow)) -  (Leaf2VisionPanel1Height * 5)-  (Leaf2VisionPanel2Height * 5)-  (Leaf2VisionPanel3Height * 5)-  (Leaf2VisionPanel4Height * 5)-  (Leaf2VisionPanel5Height * 5))<200){
                           var newHeight = (Leaf2VisionPanel5Height * 5)-(200 -(LeafHeightNoOP - (DistanceFromTopOfDoorForLeaf2*5) - ((VisionPanelQuantityForLeaf2 - 1) * (+DistanceBetweenVPsForLeaf2ToShow)) -  (Leaf2VisionPanel1Height * 5)-  (Leaf2VisionPanel2Height * 5)-  (Leaf2VisionPanel3Height * 5)-  (Leaf2VisionPanel4Height * 5)-  (Leaf2VisionPanel5Height * 5)))
                           swal('Warning!', "Distance from bottom cannot be less than 200");
      $(document).ready(function () {
         $('#vP2Height1').on('input', function () {
        first=true });
    $('#vP2Height2').on('input', function () {
        first=true  
    });
    $('#vP2Height3').on('input', function () {
        first=true
        console.log('User changed the input. isUserChanged =', isUserChanged);
    });
    $('#vP2Height4').on('input', function () {
        
        first=true
       
    });
    $('#vP2Height5').on('input', function () {
        
        first=true
       
    });
     $('#distanceFromTopOfDoor').on('input', function () {
        first=true
    });

});
                           if(first){
                            $('input[name="vP2Height5"]').val(newHeight).trigger('change');
                            first=false
                           }
   
                        }
                    }

                

                /* Vertical Line for vision panel */
            }
                }
}
            }
        }

        if (ShowMeasurements) {

            var x1 = ix;
            var FrameSection = FrameThicknessForMap + GapForMap + LeafWidth1ForMap + GapForMap + FrameThicknessForMap;
            var x2 = ix + FrameSection;
            var TotalWidth = (FrameThickness * 2) + (Gap * 2) + LeafWidth1;

            if (DoorSetType == "DD") {

                FrameSection = FrameThicknessForMap
                    + GapForMap + LeafWidth1ForMap
                    + MeetingStiles + LeafWidth2ForMap
                    + GapForMap + FrameThicknessForMap;

                x2 = ix + FrameSection;

                TotalWidth = TotalWidth + Gap + LeafWidth2;
            }

            if (SideLightPanel1 == "Yes") {
                x1 = ix - SideLightPanel1Width;
                FrameSection = FrameSection + SideLightPanel1Width;
                x2 = x1 + FrameSection;
                TotalWidth = TotalWidth + SideLightPanel1WidthToShow;
            }

            if (SideLightPanel2 == "Yes") {
                FrameSection = FrameSection + SideLightPanel2Width;
                x2 = x2 + SideLightPanel2Width;
                TotalWidth = TotalWidth + SideLightPanel2WidthToShow;
            }

            // svg.append('line')
            //     .style("stroke", "black")
            //     .style("stroke-width", 0.5)
            //     .attr("x1", x1)
            //     .attr("y1", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 50 )
            //     .attr("x2", x2)
            //     .attr("y2", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 50 )
            //     .attr("marker-start","url(#verticalMarket)")
            //     .attr("marker-end","url(#verticalMarket)");

            // svg.append("text")            // append text
            //     .style("fill", "black")      // make the text black
            //     .attr("font-size", 10)
            //     .attr("x", x1 + (FrameSection/2))         // set x position of left side of text
            //     .attr("y", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 70 )
            //     .text(TotalWidth);   // define the text to display


            if(IsOverPanelActive != "" && IsOverPanelActive != "No"){
                var THeight = FrameHeight + OverPanelHeightToShow;
                if(THeight >= 2950){
                    swal('.','The overall height of the door and fanlight exceeds 2950 mm')
                }
                $("#SL1Height,#SL2Height").attr({ 'readonly': true, "required": true }).val(THeight);

                svg.append('line') //height with overpanel of door
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 127)
                    .attr("y1", iy + (TopFrameHeight - ((FrameThicknessForMap) + OverPanelHeight)))
                    .attr("x2", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 127)
                    .attr("y2", iy + FrameHeightForMap + OverPanelHeight)
                    .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                    .attr("marker-end", "url(#arrowRight)")

                svg.append("text") // append text
                    .style("fill", "black") // make the text black
                    .attr("x", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 123) // set x position
                    .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + (FrameHeightForMap / 2)) // set y position
                    .attr("font-size", 10)
                    .attr("transform", `rotate(-90, ${ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 123}, ${iy + (TopFrameHeight - FrameThicknessForMap) + (FrameHeightForMap / 2)})`) // rotate 45 degrees
                    .text(FrameHeight + OverPanelHeightToShow); // define the text to display


                svg.append('line')
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines)
                    .attr("y1", iy + FrameThicknessForMap + FrameHeightForMap + OverPanelHeight - FrameThicknessForMap)
                    .attr("x2", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 132)
                    .attr("y2", iy + FrameThicknessForMap + FrameHeightForMap + OverPanelHeight - FrameThicknessForMap)

                svg.append('line') // outer frame joining line top
                    .style("stroke", "black")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 132)
                    .attr("y1", iy)
                    .attr("x2", ix + FrameWidthForMap)
                    .attr("y2", iy)
            }
            if (IronmongerySet == "Yes" && IronmongeryID != "") {
                if (IsLeverHandlesEnable && DoorSetType == "SD") {

                    // var DistanceOfLeverHandlesFromBelow = 950 / 5;

                    if (Handing == 'Right') {

                        svg.append("circle")
                            .style("stroke", "black")
                            .style("fill", "grey")
                            .attr("r", 7) // Adjust radius
                            .attr("cx", ix + FrameThicknessForMap + GapForMap + (LeverHandleDistanceFromLeadingEdgeOfDoor / 5) + 3.5) // Center x-coordinate
                            .attr("cy", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 5) - 7);
                        svg.append("rect")
                            .style("stroke", "black")
                            .style("fill", "grey")
                            .attr("x", ix + FrameThicknessForMap + GapForMap + (LeverHandleDistanceFromLeadingEdgeOfDoor / 5) + 3.5) // Adjust x position
                            .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 5) - 7 - 3) // Adjust y position
                            .attr("width", 40) // Adjust width
                            .attr("height", 5) // Adjust height
                            .attr("rx", 5) // Rounded corners
                            .attr("ry", 5); // Rounded corners

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + (LeverHandleDistanceFromLeadingEdgeOfDoor / 5) - 3)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 5) - 7)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap - SideLightPanel1WidthSpaceForVerticalLines - 125)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 5) - 7)

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap - SideLightPanel2WidthSpaceForVerticalLines - 120)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 5) - 7)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap - SideLightPanel2WidthSpaceForVerticalLines - 120)
                            .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)");

                        svg.append("text")            // append text
                            .style("fill", "black")      // make the text black
                            .style("writing-mode", WritingMode) // set the writing mode
                            .attr("x", ix + FrameThicknessForMap + GapForMap - SideLightPanel2WidthSpaceForVerticalLines - 125)         // set x position of text
                            .attr("font-size", 10)
                            .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 10) - 7)        // set y position of text
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap - SideLightPanel2WidthSpaceForVerticalLines - 125}, ${iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 10) - 7})`)
                            .text(LeverHandleDistanceFromBottomOfDoor);
                    } else {
                        svg.append("circle")
                            .style("stroke", "black")
                            .style("fill", "grey")
                            .attr("r", 7) // Adjust radius
                            .attr("cx", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (LeverHandleDistanceFromLeadingEdgeOfDoor / 5) - 3.5) // Center x-coordinate
                            .attr("cy", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 5) - 7);
                        svg.append("rect")
                            .style("stroke", "black")
                            .style("fill", "grey")
                            .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (LeverHandleDistanceFromLeadingEdgeOfDoor / 5) - 40 - 3.5) // Adjust x position
                            .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 5) - 7 - 3) // Adjust y position
                            .attr("width", 40) // Adjust width
                            .attr("height", 5) // Adjust height
                            .attr("rx", 5) // Rounded corners
                            .attr("ry", 5); // Rounded corners

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (LeverHandleDistanceFromLeadingEdgeOfDoor / 5) + 3)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 5) - 7)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 165)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 5) - 7)

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 160)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 5) - 7)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 160)
                            .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)");

                        svg.append("text")            // append text
                            .style("fill", "black")      // make the text black
                            .style("writing-mode", WritingMode) // set the writing mode
                            .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 155)         // set x position of text
                            .attr("font-size", 10)
                            .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 10) - 7)        // set y position of text
                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + SideLightPanel2WidthSpaceForVerticalLines + 155}, ${iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 10) - 7})`)
                            .text(LeverHandleDistanceFromBottomOfDoor);
                    }
                }
            }
            if (DoorSetType == "DD") {
                if (IsLeverHandlesEnable ) {


                    console.log(LeafWidth2ForMap,LeafWidth1ForMap,'width.............................')
                    if(LeafWidth2ForMap<LeafWidth1ForMap){
                        svg.append("circle")
                                            .style("stroke", "black")
                                            .style("fill", "grey")
                                            .attr("r", 7) // Adjust radius
                                            .attr("cx", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (LeverHandleDistanceFromLeadingEdgeOfDoor / 5) - 3.5) // Center x-coordinate
                                            .attr("cy", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 5) - 7);
                                        svg.append("rect")
                                            .style("stroke", "black")
                                            .style("fill", "grey")
                                            .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (LeverHandleDistanceFromLeadingEdgeOfDoor / 5) - 40 - 3.5) // Adjust x position
                                            .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 5) - 7 - 3) // Adjust y position
                                            .attr("width", 40) // Adjust width
                                            .attr("height", 5) // Adjust height
                                            .attr("rx", 5) // Rounded corners
                                            .attr("ry", 5); // Rounded corners

                                        svg.append('line')
                                            .style("stroke", "black")
                                            .style("stroke-width", 0.5)
                                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap - (LeverHandleDistanceFromLeadingEdgeOfDoor / 5) + 3)
                                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 5) - 7)
                                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap +MeetingStiles+LeafWidth2ForMap+ SideLightPanel2WidthSpaceForVerticalLines + 165)
                                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 5) - 7)

                                        svg.append('line')
                                            .style("stroke", "black")
                                            .style("stroke-width", 0.5)
                                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap+ SideLightPanel2WidthSpaceForVerticalLines + 160)
                                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 5) - 7)
                                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 160)
                                            .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                                            .attr("marker-end", "url(#arrowRight)");

                                        svg.append("text")            // append text
                                            .style("fill", "black")      // make the text black
                                            .style("writing-mode", WritingMode) // set the writing mode
                                            .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap +MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 155)         // set x position of text
                                            .attr("font-size", 10)
                                            .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 10) - 7)        // set y position of text
                                            .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap +MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 155}, ${iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 10) - 7})`)
                                            .text(LeverHandleDistanceFromBottomOfDoor);
                    }else{
                        svg.append("circle")
                        .style("stroke", "black")
                        .style("fill", "grey")
                        .attr("r", 7) // Adjust radius
                        .attr("cx", ix + FrameThicknessForMap + GapForMap +MeetingStiles+LeafWidth1ForMap+ (LeverHandleDistanceFromLeadingEdgeOfDoor / 5) + 3.5) // Center x-coordinate
                        .attr("cy", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 5) - 7);
                    svg.append("rect")
                        .style("stroke", "black")
                        .style("fill", "grey")
                        .attr("x", ix + FrameThicknessForMap + GapForMap +MeetingStiles+LeafWidth1ForMap+ (LeverHandleDistanceFromLeadingEdgeOfDoor / 5) + 3.5) // Adjust x position
                        .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 5) - 7 - 3) // Adjust y position
                        .attr("width", 40) // Adjust width
                        .attr("height", 5) // Adjust height
                        .attr("rx", 5) // Rounded corners
                        .attr("ry", 5); // Rounded corners

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap +MeetingStiles+LeafWidth1ForMap+ (LeverHandleDistanceFromLeadingEdgeOfDoor / 5) + 40)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 5) - 7)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 165)
                            .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 5) - 7)

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", 0.5)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap+MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 160)
                            .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 5) - 7)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap +MeetingStiles+LeafWidth2ForMap+ SideLightPanel2WidthSpaceForVerticalLines + 160)
                            .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                            .attr("marker-start", "url(#arrowLeft)")  // Left-pointing arrow
                            .attr("marker-end", "url(#arrowRight)");


                             svg.append("text")            // append text
                        .style("fill", "black")      // make the text black
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap +MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 155)         // set x position of text
                        .attr("font-size", 10)
                        .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 10) - 7)        // set y position of text
                        .attr("transform", `rotate(-90, ${ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap +MeetingStiles+LeafWidth2ForMap + SideLightPanel2WidthSpaceForVerticalLines + 155}, ${iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap - (LeverHandleDistanceFromBottomOfDoor / 10) - 7})`)
                        .text(LeverHandleDistanceFromBottomOfDoor);
                    }
                                    }
            }
        }

        var svgData = d3.select('svg').node();
        //get svg source.
        var serializer = new XMLSerializer();
        var source = serializer.serializeToString(svgData);

        //add name spaces.
        if (!source.match(/^<svg[^>]+xmlns="http\:\/\/www\.w3\.org\/2000\/svg"/)) {
            source = source.replace(/^<svg/, '<svg xmlns="http://www.w3.org/2000/svg"');
        }
        if (!source.match(/^<svg[^>]+"http\:\/\/www\.w3\.org\/1999\/xlink"/)) {
            source = source.replace(/^<svg/, '<svg xmlns:xlink="http://www.w3.org/1999/xlink"');
        }

        //add xml declaration
        source = '<?xml version="1.0" standalone="no"?>\r\n' + source;

        //convert svg source to URI data scheme.
        //        var image = "data:image/svg+xml;charset=utf-8,"+encodeURIComponent(source);

        var encodedData = "data:image/svg+xml;base64," + window.btoa(source);
        // $('input[name="SvgImage"]').val(encodedData);
        run(encodedData);


//  setTimeout(() => {
//         const svgElement = d3.select("#container svg").node();
//         const bbox = svgElement.getBBox();

//         const paddingY = 100;
// const paddingX = 70;
//         const viewBoxX = bbox.x - paddingX;
//         const viewBoxY = bbox.y - paddingY;
//         const viewBoxWidth = bbox.width + paddingX * 2;
//         const viewBoxHeight = bbox.height + paddingY * 2;

//         d3.select(svgElement)
//             .attr("viewBox", `${viewBoxX} ${viewBoxY} ${viewBoxWidth} ${viewBoxHeight}`);

//         svgPanZoom(svgElement, options);
//     }, 100);

setTimeout(() => {
  const svgElement = d3.select("#container svg").node();
  if (!svgElement) return;

  const bbox = svgElement.getBBox();

  const paddingX = 70;
  const paddingY = 100;

  const vbX = bbox.x - paddingX;
  const vbY = bbox.y - paddingY;
  const vbW = bbox.width + paddingX * 2;
  const vbH = bbox.height + paddingY * 2;

  // ✅ Set viewBox tightly around content + padding
  d3.select(svgElement)
    .attr("viewBox", `${vbX} ${vbY} ${vbW} ${vbH}`)
    .attr("preserveAspectRatio", "xMidYMid meet"); // 🧠 center in both X and Y

  // ✅ Zoom & pan enabled
  svgPanZoom(svgElement, options);
}, 100);


    }



}




//$( ".door-configuration" ).change( function( event ) {
// $(".form-control").change(function (event) {
//     var element = $(this);
//     render(element);
// });
$(document).ready(function () {



    $(".form-control").on("change", function () {
        // console.log("Triggered:", $(this).attr("name")); // ← check this logs
        render($(this));
    });
});

$("#change-dimension").change(function (event) {
    var element = $(this);
    render(element);
});

const optionRow = document.querySelector('.container-carousel');
const optionItems = document.querySelectorAll('.optionItem');

const arrowLeft = document.getElementById('arrow-left');
const arrowRight = document.getElementById('arrow-right');

// ? ----- ----- Right arrow Event Listener ----- -----
arrowRight.addEventListener('click', () => {

    optionRow.scrollLeft += optionRow.offsetWidth;

    const activeArrow = document.querySelector('.indicadores .activo');
    if (activeArrow) {
        activeArrow.classList.add('activo');
        activeArrow.classList.remove('activo');
    }
});

// ? ----- ----- Left arrow Event Listener ----- -----
arrowLeft.addEventListener('click', () => {
    optionRow.scrollLeft -= optionRow.offsetWidth;

    const activeArrow = document.querySelector('.indicadores .activo');
    if (activeArrow) {
        activeArrow.classList.add('activo');
        activeArrow.classList.remove('activo');
    }
});

let imageUtil = {};

function run(svgData) {
    //let svgSrc = document.getElementById('svgImage').src;
    let svgSrc = svgData;
    imageUtil.base64SvgToBase64Png(svgSrc, 200).then(pngSrc => {
        $('input[name="SvgImage"]').val(pngSrc);
        //document.getElementById('pngImage').src = pngSrc;
    });
}


/**
 * converts a base64 encoded data url SVG image to a PNG image
 * @param originalBase64 data url of svg image
 * @param width target width in pixel of PNG image
 * @param secondTry used internally to prevent endless recursion
 * @return {Promise<unknown>} resolves to png data url of the image
 */
imageUtil.base64SvgToBase64Png = function (originalBase64, width, secondTry) {
    return new Promise(resolve => {
        let img = document.createElement('img');
        img.onload = function () {
            if (!secondTry && (img.naturalWidth === 0 || img.naturalHeight === 0)) {
                let svgDoc = base64ToSvgDocument(originalBase64);
                let fixedDoc = fixSvgDocumentFF(svgDoc);
                return imageUtil.base64SvgToBase64Png(svgDocumentToBase64(fixedDoc), width, true).then(result => {
                    resolve(result);
                });
            }
            document.body.appendChild(img);
            let canvas = document.createElement("canvas");
            let ratio = (img.clientWidth / img.clientHeight) || 1;
            document.body.removeChild(img);
            //canvas.width = img.width;
            //canvas.height = width / ratio;
            canvas.width = 1000;
            canvas.height = 1000;
            let ctx = canvas.getContext("2d");
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            try {
                let data = canvas.toDataURL('image/png');
                resolve(data);
            } catch (e) {
                resolve(null);
            }
        };
        img.src = originalBase64;
    });
}

//needed because Firefox doesn't correctly handle SVG with size = 0, see https://bugzilla.mozilla.org/show_bug.cgi?id=700533
function fixSvgDocumentFF(svgDocument) {
    try {
        let widthInt = parseInt(svgDocument.documentElement.width.baseVal.value) || 500;
        let heightInt = parseInt(svgDocument.documentElement.height.baseVal.value) || 500;
        svgDocument.documentElement.width.baseVal.newValueSpecifiedUnits(SVGLength.SVG_LENGTHTYPE_PX, widthInt);
        svgDocument.documentElement.height.baseVal.newValueSpecifiedUnits(SVGLength.SVG_LENGTHTYPE_PX, heightInt);
        return svgDocument;
    } catch (e) {
        return svgDocument;
    }
}

function svgDocumentToBase64(svgDocument) {
    try {
        let base64EncodedSVG = btoa(new XMLSerializer().serializeToString(svgDocument));
        return 'data:image/svg+xml;base64,' + base64EncodedSVG;
    } catch (e) {
        return null;
    }
}

function base64ToSvgDocument(base64) {
    let svg = atob(base64.substring(base64.indexOf('base64,') + 7));
    svg = svg.substring(svg.indexOf('<svg'));
    let parser = new DOMParser();
    return parser.parseFromString(svg, "image/svg+xml");
}
