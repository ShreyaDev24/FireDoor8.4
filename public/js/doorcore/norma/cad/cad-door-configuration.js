d3.select('button#save').on('click', function() {
    var config = {
        filename: 'customFileName'
    };
    d3_save_svg.save(d3.select('svg').node(), config);
});

var options = {
    events: {
        mouseWheel: true, // enables mouse wheel zooming events
        doubleClick:  true, // enables double-click to zoom-in events
        drag:  true, // enables drag and drop to move the SVG events
        dragCursor: "move" // cursor to use while dragging the SVG
    },
    animationTime:  300, // time in milliseconds to use as default for animations. Set 0 to remove the animation
    zoomFactor:  0.25, // how much to zoom-in or zoom-out
    maxZoom:  3, //maximum zoom in, must be a number bigger than 1
    panFactor: 100, // how much to move the viewBox when calling .panDirection() methods
    initialViewBox: { // the initial viewBox, if null or undefined will try to use the viewBox set in the svg tag. Also accepts string in the format "X Y Width Height"
        x:  0, // the top-left corner X coordinate
        y:  0, // the top-left corner Y coordinate
        width:  780, // the width of the viewBox
        height:  780 // the height of the viewBox
    },
    limits: { // the limits in which the image can be moved. If null or undefined will use the initialViewBox plus 15% in each direction
        x:  -150,
        y:  -150,
        x2:  1150,
        y2:  1150
    }
};
// create svg element:
var svg = d3.select("#container")
    .append("svg")
    .attr("preserveAspectRatio", "xMinYMin meet")
    .attr("viewBox", "0 0 780 780")
    .classed("svg-content", true);

//var svgPanZoom= $("svg").svgPanZoom(options);

var DoorUrl = $("#door_url").text();

const render = (CustomElement = null) => {

    var WritingMode = "lr";
    var ShowMeasurements = true;

    var ChangedFieldName = CustomElement.attr("name");

    if (!$("#change-dimension").prop('checked')){
        // WritingMode = "tb";
        ShowMeasurements = false;
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

    var ix = 60 , iy = 150;

    var DoorSetType = $('select[name="doorsetType"]').val();
    if(DoorSetType == ""){
        DoorSetType = 0;
    }

    var SOWidthForMap = 0;
    var SOWidth = $('input[name="sOWidth"]').val();
    if(SOWidth == ""){
        SOWidth = 0;
    }else{
        SOWidth = parseFloat(SOWidth);
        //SOWidthForMap = NumberChanger(SOWidth);
        SOWidthForMap = SOWidth / 5;
    }

    var SOHeightForMap = 0;
    var SOHeight = $('input[name="sOHeight"]').val();
    if(SOHeight == ""){
        SOHeight = 0;
    }else{
        SOHeight = parseFloat(SOHeight);
        //SOHeightForMap = NumberChanger(SOHeight);
        SOHeightForMap = SOHeight / 5;
    }

    if((iy + SOHeightForMap) >= 780){
        shape = document.getElementsByTagName("svg")[0];
        shape.setAttribute("viewBox", "0 0 780 "+ (iy + SOHeightForMap + 100));
    }

    if(SOWidth > 0 && SOHeight > 0){

    var Tollerance = $('input[name="tollerance"]').val();
    if(Tollerance == ""){
        Tollerance = 0;
    }else{
        Tollerance = parseFloat(Tollerance);
    }

    var FrameThickness = $('input[name="frameThickness"]').val();
    var FrameThicknessForMap = 0;
    if(FrameThickness == ""){
        FrameThickness = 0;
    }else{
        FrameThickness = parseFloat(FrameThickness);
        FrameThicknessForMap = FrameThickness/5;
    }

    var Gap = $('input[name="gap"]').val();
    var GapForMap = 0;
    if(Gap == ""){
        Gap = 0;
    }else{
        Gap = parseFloat(Gap);
        // GapForMap = Gap / 5;
        GapForMap = 3;
    }

    var FloorFinish = $('input[name="floorFinish"]').val();
    if(FloorFinish == ""){
        FloorFinish = 0;
    }else{
        FloorFinish = parseFloat(FloorFinish);
    }

    var FrameWidthAdditionalNumber = 2;

    ConfigurableDoorFormula.forEach(function(elem, index) {

        var FormulaAdditionalData = JSON.parse(elem.value);
        if(elem.slug == "undercut"){
            UnderCutAdditionalNumber = parseFloat((FormulaAdditionalData.undercut != "")?FormulaAdditionalData.undercut:0);
        }

        if(elem.slug == "op_width"){
            OPTolleranceAdditionalNumber = parseFloat((FormulaAdditionalData.tolerance != "")?FormulaAdditionalData.tolerance:1);
            OPFrameThicknessAdditionalNumber = parseFloat((FormulaAdditionalData.frame_thickness != "")?FormulaAdditionalData.frame_thickness:1);
            OPGapAdditionalNumber = parseFloat((FormulaAdditionalData.gap != "")?FormulaAdditionalData.gap:1);
        }

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


        if(elem.slug == "frame_width"){
            FrameWidthAdditionalNumber = parseFloat((FormulaAdditionalData.tolerance != "")?FormulaAdditionalData.tolerance:2);
        }


    });

        var FrameWidth = SOWidth - (Tollerance * FrameWidthAdditionalNumber);
        var FrameWidthForMap = 0;

        // if(ChangedFieldName == "frameWidth"){
        //     FrameWidth = $('input[name="frameWidth"]').val();
        //     if(FrameWidth != "" && FrameWidth > 0){
        //         FrameWidth = parseFloat(FrameWidth);
        //     }
        // }

        if(FrameWidth > 0){
            FrameWidthForMap = FrameWidth/5;
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

        if(FrameHeight > 0){
            FrameHeightForMap = FrameHeight/5;
        }

    var UnderCut = 0;
    if(FloorFinish > 0){
        UnderCut = FloorFinish + UnderCutAdditionalNumber;
    }

    var LeafWidth1 = 0;
    var LeafWidth2 = 0;
    var LeafWidth1ForMap = 0;
    var LeafWidth2ForMap = 0;

    if(DoorSetType == "DD"){
        LeafWidth1 = LeafWidth2 = (SOWidth-(Tollerance*TolleranceAdditionalNumber)-(FrameThickness*FrameThicknessAdditionalNumber)-(GapAdditionalNumber*Gap))/2;
    }else if(DoorSetType == "SD"){
        LeafWidth1 = SOWidth - (Tollerance*TolleranceAdditionalNumber) - (FrameThickness*FrameThicknessAdditionalNumber) - (GapAdditionalNumber*Gap);
    }else if(DoorSetType == "leaf_and_a_half"){
        LeafWidth1 = $('input[name="leafWidth1"]').val();
        if(LeafWidth1 != ""){
            LeafWidth1 = parseFloat(LeafWidth1);
        }
        LeafWidth2 = SOWidth - (Tollerance*TolleranceAdditionalNumber) - (FrameThickness*FrameThicknessAdditionalNumber) - (GapAdditionalNumber*Gap) - LeafWidth1;
        DoorSetType = "DD";
    }

    if(LeafWidth1 > 0) {
        $("#leafWidth1-selected").empty().text(LeafWidth1);
        $("#leafWidth1-price").empty().text("£0.00");
        $("#leafWidth1-section").removeClass("table_row_hide");
        $("#leafWidth1-section").addClass("table_row_show");
    }
    if(LeafWidth2 > 0){
        $("#leafWidth2-selected").empty().text(LeafWidth2);
        $("#leafWidth2-price").empty().text("£0.00");
        $("#leafWidth2-section").removeClass("table_row_hide");
        $("#leafWidth2-section").addClass("table_row_show");
    }

    if(LeafWidth1 != "" && LeafWidth1 > 0){
        LeafWidth1ForMap = LeafWidth1 / 5;
    }

    if(LeafWidth2 != "" && LeafWidth2 > 0){
        LeafWidth2ForMap = LeafWidth2 / 5;
    }

    var LeafHeightNoOPForMap = 0;
    var LeafHeightNoOP = SOHeight - Tollerance - FrameThickness - UnderCut - Gap;
    if(LeafHeightNoOP == ""){
        LeafHeightNoOP = 0;
    }else{
        //LeafHeightNoOPForMap = NumberChanger(LeafHeightNoOP);
        LeafHeightNoOPForMap = LeafHeightNoOP / 5;
    }

    if(LeafHeightNoOP > 0){
        $("#leafHeightNoOP-selected").empty().text(LeafHeightNoOP);
        $("#leafHeightNoOP-price").empty().text("£0.00");
        $("#leafHeightNoOP-section").removeClass("table_row_hide");
        $("#leafHeightNoOP-section").addClass("table_row_show");
    }

    var LeftGapForLeaf1 = 0, RightGapForLeaf1 = 0, LeftGapForLeaf2 = 0, RightGapForLeaf2 = 0, UpperAndLowerGap  = 0;
    //UpperAndLowerGap  = NumberChanger(Tollerance + FrameThickness + UnderCut + Gap);
    // UpperAndLowerGap  = ((Tollerance + FrameThickness + UnderCut + Gap) / 5) / 2;
    UpperAndLowerGap  = FrameThicknessForMap + GapForMap;


    var TotalSideGap = 0;

    if(DoorSetType == "DD"){
        TotalSideGap = ((Tollerance*TolleranceAdditionalNumber) + (FrameThickness*FrameThicknessAdditionalNumber) + (GapAdditionalNumber*Gap)) / 2;
        LeftGapForLeaf1 = RightGapForLeaf2 = TotalSideGap / 5;
    }else if(DoorSetType == "SD"){
        TotalSideGap = ((Tollerance*TolleranceAdditionalNumber) + (FrameThickness*FrameThicknessAdditionalNumber) + (GapAdditionalNumber*Gap)) / 2;
        LeftGapForLeaf1 = RightGapForLeaf1 = TotalSideGap / 5;
    }

    var TotalDoorWidth = 0;
    var RemainingGap = 0;

    if(DoorSetType == "DD"){
        TotalDoorWidth = FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap + GapForMap + FrameThicknessForMap;
        if(TotalDoorWidth > 0){
            RemainingGap = (TotalDoorWidth - FrameWidthForMap) / 2;
            LeafWidth1ForMap = LeafWidth1ForMap - RemainingGap;
            LeafWidth2ForMap = LeafWidth2ForMap - RemainingGap;
        }
    }else{
        TotalDoorWidth = FrameThicknessForMap + GapForMap + LeafWidth1ForMap + GapForMap + FrameThicknessForMap;
        if(TotalDoorWidth > 0){
            RemainingGap = TotalDoorWidth - FrameWidthForMap;
            LeafWidth1ForMap = LeafWidth1ForMap - RemainingGap;
        }
    }

    var SideLightPanel1Width = 0;
    var SideLightPanel1WidthToShow = 0;

    var SideLightPanel1WidthSpaceForVerticalLines = 0;

    var SideLightPanel1 = $('select[name="sideLight1"]').val();
    if(SideLightPanel1 == "Yes"){
        SideLightPanel1Width = $('input[name="SL1Width"]').val();
        SideLightPanel1WidthToShow = 0;
        if(SideLightPanel1Width == ""){
            SideLightPanel1Width = 0;
        }else{
            SideLightPanel1Width = parseFloat(SideLightPanel1Width);
            SideLightPanel1WidthToShow = SideLightPanel1Width;
            if(SideLightPanel1Width > 0){
                SideLightPanel1Width = SideLightPanel1Width / 5;
            }
        }

        var SideLightPanel1Height = $('input[name="SL1Height"]').val();
        var SideLightPanel1HeightToShow = 0;
        if(SideLightPanel1Height == ""){
            SideLightPanel1Height = 0;
        }else{
            SideLightPanel1Height = parseFloat(SideLightPanel1Height);
            SideLightPanel1HeightToShow = SideLightPanel1Height;
            if(SideLightPanel1Height > 0){
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
    if(SideLightPanel2 == "Yes"){

        var CopyOfSideLite1 = $('select[name="copyOfSideLite1"]').val();

        if(CopyOfSideLite1 != "Yes"){

            SideLightPanel2Width = $('input[name="SL2Width"]').val();
            if(SideLightPanel2Width == ""){
                SideLightPanel2Width = 0;
            }else{
                SideLightPanel2Width = parseFloat(SideLightPanel2Width);
                SideLightPanel2WidthToShow = SideLightPanel2Width;
                if(SideLightPanel2Width > 0){
                    SideLightPanel2Width = SideLightPanel2Width / 5;
                }
            }

            SideLightPanel2Height = $('input[name="SL2Height"]').val();
            if(SideLightPanel2Height == ""){
                SideLightPanel2Height = 0;
            }else{
                SideLightPanel2Height = parseFloat(SideLightPanel2Height);
                SideLightPanel2HeightToShow = SideLightPanel2Height;
                if(SideLightPanel2Height > 0){
                    SideLightPanel2Height = SideLightPanel2Height / 5;
                }
            }
        }

        //SideLightPanel2WidthSpaceForVerticalLines = ( LeftGapForLeaf1 * 2 ) + SideLightPanel2Width;
        SideLightPanel2WidthSpaceForVerticalLines = SideLightPanel2Width;

        //ix = ix + ( LeftGapForLeaf1 * 2 ) + SideLightPanel1Width;
    }

    var TotalCadWidth = SideLightPanel1WidthSpaceForVerticalLines + SOWidthForMap + SideLightPanel2WidthSpaceForVerticalLines;

    if(ShowMeasurements){
        TotalCadWidth = TotalCadWidth + 100;
    }

    if(TotalCadWidth < 780){
        var RemainingSvgSpace = 780 - TotalCadWidth;
        ix = RemainingSvgSpace/2;
    }


    var handle = LeafHeightNoOPForMap / 2;

    var GapAfterOverPanelApplied = 0;



    var IsOverPanelActive = $('select[name="overpanel"]').val();
    if(IsOverPanelActive != "" && IsOverPanelActive != "No"){

        var GapForOverPanel = (Tollerance * OPTolleranceAdditionalNumber) + (FrameThickness * OPFrameThicknessAdditionalNumber) + (OPGapAdditionalNumber * Gap);

        var OverPanelWidthToShow = SOWidth - GapForOverPanel;
        var OverPanelWidth = OverPanelWidthToShow / 5;

        var OverPanelHeight = $('input[name="oPHeigth"]').val();
        var OverPanelHeightToShow = 0;
        if(OverPanelHeight == ""){
            OverPanelHeight = 0;
        }else{
            OverPanelHeight = parseFloat(OverPanelHeight);
            if(OverPanelHeight > 600){
                OverPanelHeight = 0;
            }

            OverPanelHeightToShow = OverPanelHeight;
            if(OverPanelHeight > 0){
                OverPanelHeight = OverPanelHeight / 5;
            }
        }

        SOHeightForMap = SOHeightForMap + (GapForOverPanel / 5) + OverPanelHeight;

        TopFrameHeight = (FrameThicknessForMap * 2) + OverPanelHeight;

        //GapAfterOverPanelApplied = (GapForOverPanel / 5) + OverPanelHeight;
        GapAfterOverPanelApplied = TopFrameHeight - FrameThicknessForMap;

    }

    if(SideLightPanel1 == "Yes"){

        svg.append('rect')
            .attr('x', ix )
            .attr('y', iy + GapAfterOverPanelApplied)
            //.attr('width', ( LeftGapForLeaf1 * 2 ) + SideLightPanel1Width)
            .attr('width',SideLightPanel1Width)
            //.attr('height', ( UpperAndLowerGap * 2 ) + SideLightPanel1Height)
            .attr('height',(FrameHeight/5))
            .attr('stroke', 'black')
            .attr('fill', 'none');

        svg.append('rect')
            //.attr('x', ix + LeftGapForLeaf1)
            .attr('x', ix + FrameThicknessForMap)
            //.attr('y', iy + GapAfterOverPanelApplied + UpperAndLowerGap)
            .attr('y', iy + GapAfterOverPanelApplied + FrameThicknessForMap)
            //.attr('width', SideLightPanel1Width)
            .attr('width', SideLightPanel1Width - (FrameThicknessForMap*2))
            //.attr('height', SideLightPanel1Height)
            .attr('height', (FrameHeight/5) - (FrameThicknessForMap*2))
            .attr('stroke', 'black')
            .attr('fill', '#aacbee');

        if(ShowMeasurements){
            svg.append('line')
            .style("stroke", "blue")
            .style("stroke-width", 0.5)
            .attr("x1", ix)
            .attr("y1", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 10 )
            .attr("x2", ix + SideLightPanel1Width )
            .attr("y2", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 10 )
            .attr("marker-start","url(#verticalMarket)")
            .attr("marker-end","url(#verticalMarket)");

        svg.append("text")            // append text
            .style("fill", "blue")      // make the text black
            .attr("font-size", 15)
            .attr("x", ix + (SideLightPanel1Width/2))         // set x position of left side of text
            .attr("y", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 30 )
            .text(SideLightPanel1WidthToShow);   // define the text to display
        }

        //ix = ix + ( LeftGapForLeaf1 * 2 ) + SideLightPanel1Width;
        ix = ix + SideLightPanel1Width;
    }

    if(SideLightPanel2 == "Yes"){

        svg.append('rect')
            //.attr('x', ix + SOWidthForMap )
            .attr('x', ix + FrameWidthForMap )
            .attr('y', iy + GapAfterOverPanelApplied)
            //.attr('width', ( LeftGapForLeaf1 * 2 ) + SideLightPanel2Width)
            .attr('width', SideLightPanel2Width)
            //.attr('height', ( UpperAndLowerGap * 2 ) + SideLightPanel2Height)
            .attr('height', (FrameHeight/5))
            .attr('stroke', 'black')
            .attr('fill', 'none');

        svg.append('rect')
            //.attr('x', ix + SOWidthForMap + LeftGapForLeaf1)
            .attr('x', ix + FrameWidthForMap + FrameThicknessForMap)
            //.attr('y', iy + GapAfterOverPanelApplied + UpperAndLowerGap)
            .attr('y', iy + GapAfterOverPanelApplied + FrameThicknessForMap)
            .attr('width', SideLightPanel2Width - (FrameThicknessForMap * 2))
            .attr('height', (FrameHeight/5) - (FrameThicknessForMap * 2))
            .attr('stroke', 'black')
            .attr('fill', '#aacbee');

        if(ShowMeasurements){
            svg.append('line')
                .style("stroke", "blue")
                .style("stroke-width", 0.5)
                .attr("x1", ix + FrameWidthForMap)
                .attr("y1", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 10 )
                .attr("x2", ix + FrameWidthForMap + SideLightPanel2Width )
                .attr("y2", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 10 )
                .attr("marker-start","url(#verticalMarket)")
                .attr("marker-end","url(#verticalMarket)");

            svg.append("text")            // append text
                .style("fill", "blue")      // make the text black
                .attr("font-size", 15)
                .attr("x", ix + FrameWidthForMap + (SideLightPanel2Width / 2))         // set x position of left side of text
                .attr("y", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 30 )
                .text(SideLightPanel2WidthToShow);   // define the text to display
        }

    }

    if(IsOverPanelActive != "" && IsOverPanelActive != "No"){
        svg.append('rect')
            .attr('x', ix + ((GapForOverPanel / 5) / 2))
            //.attr('y', iy + ((GapForOverPanel / 5) / 2))
            .attr('y', iy + FrameThicknessForMap)
            .attr('width', OverPanelWidth)
            .attr('height', OverPanelHeight)
            .attr('stroke', 'black')
            .attr('fill', '#aacbee');

        if(ShowMeasurements){

            //Text of Width of Outer frame of door
            svg.append("text")            // append text
                .style("fill", "blue")      // make the text black
                .attr("font-size", 15)
                .attr("x", ix + ((GapForOverPanel / 5) / 2) + (OverPanelWidth / 2))         // set x position of left side of text
                .attr("y", iy - 15)         // set y position of bottom of text
                .text(OverPanelWidthToShow);   // define the text to display
            //Text of Width of Outer frame of door

            svg.append('line')
                .style("stroke", "blue")
                .style("stroke-width", 0.5)
                .attr("x1", ix + ((GapForOverPanel / 5) / 2))
                .attr("y1", iy - 10)
                .attr("x2", ix + ((GapForOverPanel / 5) / 2) + OverPanelWidth )
                .attr("y2", iy - 10)
                .attr("marker-start","url(#verticalMarket)")
                .attr("marker-end","url(#verticalMarket)");

        }

    }

/* Frame */


    var IronmongerySet = $('select[name="ironmongerySet"]').val();
    var IronmongeryID = $('select[name="IronmongeryID"]').val();


    svg.append('rect')
        .attr('x', ix)
        .attr('y', iy)
        .attr('width', FrameWidthForMap)
        .attr('height', TopFrameHeight)
        .attr('stroke', 'black')
        .attr('fill', 'none');

    svg.append('rect')
        .attr('x', ix)
        .attr('y', iy + TopFrameHeight)
        .attr('width', FrameThicknessForMap)
        .attr('height', ( FrameHeight - FrameThickness ) / 5)
        .attr('stroke', 'black')
        .attr('fill', 'none');

    svg.append('rect')
        .attr('x', ix + (FrameWidthForMap - FrameThicknessForMap))
        .attr('y', iy + TopFrameHeight)
        .attr('width', FrameThicknessForMap)
        .attr('height', ( FrameHeight - FrameThickness ) / 5)
        .attr('stroke', 'black')
        .attr('fill', 'none');


    if(ShowMeasurements){

        svg.append('line')
            .style("stroke", "blue")
            .style("stroke-width", 0.5)
            .attr("x1", ix)
            .attr("y1", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 10 )
            .attr("x2", ix + FrameThicknessForMap )
            .attr("y2", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 10 )
            .attr("marker-start","url(#verticalMarket)")
            .attr("marker-end","url(#verticalMarket)");

        svg.append("text")            // append text
            .style("fill", "blue")      // make the text black
            .attr("font-size", 15)
            .attr("x", ix)         // set x position of left side of text
            .attr("y", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 30 )
            .text(FrameThickness);   // define the text to display
    }
/* Frame */


/* Hinges */

    svg.append('rect')
        .attr('x', ix + FrameThicknessForMap)
        .attr('y', iy + TopFrameHeight + GapForMap + 36)
        .attr('width', GapForMap)
        .attr('height', 20)
        .attr('stroke', 'black')
        .attr('fill', 'black');

    svg.append('rect')
        .attr('x', ix + FrameThicknessForMap)
        .attr('y', iy + TopFrameHeight + GapForMap + 100)
        .attr('width', GapForMap)
        .attr('height', 20)
        .attr('stroke', 'black')
        .attr('fill', 'black');

    svg.append('rect')
        .attr('x', ix + FrameThicknessForMap)
        .attr('y', iy + TopFrameHeight + (FrameHeightForMap - FrameThicknessForMap) - ( FrameThicknessForMap + GapForMap + 36))
        .attr('width', GapForMap)
        .attr('height', 20)
        .attr('stroke', 'black')
        .attr('fill', 'black');

    if(LeafHeightNoOP > 2400){
        svg.append('rect')
            .attr('x', ix + FrameThicknessForMap)
            .attr('y', iy + TopFrameHeight + (FrameHeightForMap - FrameThicknessForMap) - (FrameThicknessForMap + GapForMap + 100))
            .attr('width', GapForMap)
            .attr('height', 20)
            .attr('stroke', 'black')
            .attr('fill', 'black');
    }

    if(DoorSetType == "DD"){

        svg.append('rect')
            .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap)
            .attr('y', iy + TopFrameHeight + GapForMap + 36)
            .attr('width', GapForMap)
            .attr('height', 20)
            .attr('stroke', 'black')
            .attr('fill', 'black');

        svg.append('rect')
            .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap)
            .attr('y', iy + TopFrameHeight + GapForMap + 100)
            .attr('width', GapForMap)
            .attr('height', 20)
            .attr('stroke', 'black')
            .attr('fill', 'black');

        svg.append('rect')
            .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap)
            .attr('y', iy + TopFrameHeight + (FrameHeightForMap - FrameThicknessForMap) - ( FrameThicknessForMap + GapForMap + 36))
            .attr('width', GapForMap)
            .attr('height', 20)
            .attr('stroke', 'black')
            .attr('fill', 'black');

        if(LeafHeightNoOP > 2400){
            svg.append('rect')
                .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap)
                .attr('y', iy + TopFrameHeight + (FrameHeightForMap - FrameThicknessForMap) - (FrameThicknessForMap + GapForMap + 100))
                .attr('width', GapForMap)
                .attr('height', 20)
                .attr('stroke', 'black')
                .attr('fill', 'black');
        }
    }


/* Hinges */

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


    if(ShowMeasurements){

    //Text of Width of Outer frame of door
    svg.append("text")            // append text
        .style("fill", "blue")      // make the text black
        .attr("font-size", 15)
        //.attr("x", ix + (SOWidthForMap / 2))         // set x position of left side of text
        .attr("x", ix + (FrameWidthForMap / 2))         // set x position of left side of text
        // .attr("y", iy - 90)         // set y position of bottom of text
        .attr("y", iy - 65)         // set y position of bottom of text
        //.text(SOWidth);   // define the text to display
        .text(FrameWidth);   // define the text to display
    //Text of Width of Outer frame of door

//arrow
    svg.append("svg:defs").append("svg:marker")
        .attr("id", "verticalMarket")
        .attr("refX", 0)
        .attr("refY", 5)
        .attr("markerWidth", 20)
        .attr("markerHeight", 20)
        .attr("markerUnits","userSpaceOnUse")
        .attr("orient", "auto")
        .append("path")
        .attr("d", "M 0 0  L 0 10")
        .style("stroke-width", 1)
        .style("stroke", "blue");

//Line of width of Outer frame of door
    svg.append('line')
        .style("stroke", "blue")
        .style("stroke-width", 0.5)
        .attr("x1", ix)
        // .attr("y1", iy - 85)
        .attr("y1", iy - 60)
        //.attr("x2", ix + SOWidthForMap )
        .attr("x2", ix + FrameWidthForMap )
        // .attr("y2", iy - 85)
        .attr("y2", iy - 60)
        .attr("marker-start","url(#verticalMarket)")
        .attr("marker-end","url(#verticalMarket)");
//Line of width of Outer frame of door


    svg.append('line')
        .style("stroke", "blue")
        .style("stroke-width", 0.5)
        //.attr("x1", ix + SOWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 47)
        .attr("x1", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 92)
        .attr("y1", iy + (TopFrameHeight - FrameThicknessForMap))
        //.attr("x2", ix + SOWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 47)
        .attr("x2", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 92)
        //.attr("y2", iy + SOHeightForMap)
        .attr("y2", iy + (TopFrameHeight - FrameThicknessForMap) + FrameHeightForMap)
        .attr("marker-start","url(#verticalMarket)")
        .attr("marker-end","url(#verticalMarket)");

    svg.append("text")            // append text
        .style("fill", "blue")      // make the text black
        .style("writing-mode", WritingMode) // set the writing mode
        //.attr("x", ix + SOWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 55)         // set x position of left side of text
        .attr("x", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 93)         // set x position of left side of text
        .attr("font-size", 15)
        //.attr("y", iy + SOHeightForMap / 2)         // set y position of bottom of text
        .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + (FrameHeightForMap / 2))         // set y position of bottom of text
        //.text(SOHeight);   // define the text to display
        .text(FrameHeight);   // define the text to display

    //arrow
    svg.append("svg:defs").append("svg:marker")
        .attr("id", "verticalMarket")
        .attr("refX", 0)
        .attr("refY", 5)
        .attr("markerWidth", 20)
        .attr("markerHeight", 20)
        .attr("markerUnits","userSpaceOnUse")
        .attr("orient", "auto")
        .append("path")
        .attr("d", "M 0 0  L 0 10")
        .style("stroke-width", 1)
        .style("stroke", "blue");

    }

/* Outer Frame */


/* Inner Frame */

        svg.append('rect')
            //.attr('x', ix + GapForMap + LeftGapForLeaf1)
            .attr('x', ix + FrameThicknessForMap + GapForMap)
            //.attr('y', iy + FrameThicknessForMap + GapForMap + GapAfterOverPanelApplied + UpperAndLowerGap)
            .attr('y', iy + TopFrameHeight + GapForMap)
            .attr('width', LeafWidth1ForMap)
            .attr('height', LeafHeightNoOPForMap)
            .attr('stroke', 'black')
            .attr('fill', 'none');

        var IsKickPlateEnable = false;
        var IsLockNLatchesEnable = false;
        var IsLeverHandlesEnable = false;
        var IsDoorSinageEnable = false;
        var IsPullHandlesEnable = false;
        var IsPushHandlesEnable = false;
        var IsHingesEnable = false;

        if(IronmongerySet == "Yes" && IronmongeryID != ""){

            var ParsedIronmongerySet = JSON.parse(IronmongeryJson);

            ParsedIronmongerySet.forEach(function(elem, index) {
                if(elem.id == IronmongeryID){

                    console.log(elem);

                    if(elem.kickPlatesQty !== null){
                        IsKickPlateEnable = true;
                    }

                    if(elem.doorSignageQty !== null){
                        IsDoorSinageEnable = true;
                    }

                    if(elem.hingesQty !== null){
                        IsHingesEnable = true;
                    }

                    if(elem.lockesAndLatchesQty !== null){
                        IsLockNLatchesEnable = true;
                    }

                    if(elem.pullHandlesQty !== null){
                        IsPullHandlesEnable = true;
                    }

                    if(elem.pushHandlesQty !== null){
                        IsPushHandlesEnable = true;
                    }

                    if(elem.leverHandleQty !== null){
                        IsLeverHandlesEnable = true;
                    }
                }
            });

        }

        var KickPlateHeight = 0;

        if(IsKickPlateEnable){

            KickPlateHeight = (LeafHeightNoOP * 0.1)/5;

        }

        var DecorativeGroves = $('select[name="decorativeGroves"]').val();

        if(DecorativeGroves == "Yes"){

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

            if(GrooveLocation == "Vertical" || GrooveLocation == "Horizontal"){
                var NumberOfGroove = $('input[name="numberOfGroove"]').val();
                if(NumberOfGroove != ""){
                    NumberOfGrooveForVertical = NumberOfGrooveForHorizontal = parseFloat(NumberOfGroove) + 1;
                }

                if(GrooveLocation == "Vertical"){
                    Vertical = true;
                }else{
                    Horizontal = true;
                }
            }

            if(GrooveLocation == "Vertical_&_Horizontal"){

                var NumberOfVerticalGroove = $('input[name="numberOfVerticalGroove"]').val();
                if(NumberOfVerticalGroove != ""){
                    NumberOfGrooveForVertical = parseFloat(NumberOfVerticalGroove) + 1;
                }

                var NumberOfHorizontalGroove = $('input[name="numberOfHorizontalGroove"]').val();
                if(NumberOfHorizontalGroove != ""){
                    NumberOfGrooveForHorizontal = parseFloat(NumberOfHorizontalGroove) + 1;
                }

                Vertical = Horizontal = true;
            }

            if(Vertical){

                if(NumberOfGrooveForVertical > 0){
                    var GrooveGap = (LeafWidth1ForMap - (GrooveWidth*(NumberOfGrooveForVertical-1)))/NumberOfGrooveForVertical;
                    var GrooveStart = GrooveGap;
                    for(var t = 1; t < NumberOfGrooveForVertical; t++){

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", GrooveWidth)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap + GrooveStart)
                            .attr("y1", iy + TopFrameHeight + GapForMap)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + GrooveStart)
                            .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap);

                            GrooveStart = GrooveStart + GrooveGap + GrooveWidth;
                    }

                    if(DoorSetType == "DD"){
                        var GrooveGapForDD = (LeafWidth2ForMap - (GrooveWidth*(NumberOfGrooveForVertical-1)))/NumberOfGrooveForVertical;
                        var GrooveStartForDD = GrooveGapForDD;
                        for(var t = 1; t < NumberOfGrooveForVertical; t++){

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

            if(Horizontal){

                if(NumberOfGrooveForHorizontal > 0){
                    var GrooveGap = (LeafHeightNoOPForMap - (GrooveWidth*(NumberOfGrooveForHorizontal-1)))/NumberOfGrooveForHorizontal;
                    var GrooveStart = GrooveGap;
                    for(var t = 1; t < NumberOfGrooveForHorizontal; t++){

                        svg.append('line')
                            .style("stroke", "black")
                            .style("stroke-width", GrooveWidth)
                            .attr("x1", ix + FrameThicknessForMap + GapForMap)
                            .attr("y1", iy + TopFrameHeight + GapForMap + GrooveStart)
                            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                            .attr("y2", iy + TopFrameHeight + GapForMap + GrooveStart);

                            GrooveStart = GrooveStart + GrooveGap + GrooveWidth;
                    }

                    if(DoorSetType == "DD"){
                        var GrooveStartForDD = GrooveGap;
                        for(var t = 1; t < NumberOfGrooveForHorizontal; t++){

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

        if(IsKickPlateEnable){

            svg.append('rect')
                .attr('x', ix + FrameThicknessForMap + GapForMap + 3)
                .attr('y', iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (KickPlateHeight + 3)))
                .attr('width', LeafWidth1ForMap - 6)
                .attr('height', KickPlateHeight)
                .attr('stroke', 'black')
                .attr('fill', 'grey');

        }

        if(ShowMeasurements){

        /* Horizontal Line for First Door Leaf */

        svg.append("text")            // append text
            .style("fill", "blue")      // make the text black
            .attr("font-size", 15)
            // .attr("x", ix + ( LeafWidth1ForMap / 2))         // set x position of left side of text
            .attr("x", ix + FrameThicknessForMap + GapForMap + ( LeafWidth1ForMap / 2))         // set x position of left side of text
            // .attr("y", iy - 65)         // set y position of bottom of text
            .attr("y", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 30 )
            .text(LeafWidth1);   // define the text to display

        svg.append('line')
            .style("stroke", "blue")
            .style("stroke-width", 0.5)
            // .attr("x1", ix + LeftGapForLeaf1)
            .attr("x1", ix + FrameThicknessForMap + GapForMap)
            // .attr("y1", iy - 60)
            .attr("y1", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 10 )
            // .attr("x2", ix + LeafWidth1ForMap + LeftGapForLeaf1)
            .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
            // .attr("y2", iy - 60)
            .attr("y2", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 10 )
            .attr("marker-start","url(#verticalMarket)")
            .attr("marker-end","url(#verticalMarket)");
        }

        /* Horizontal Line for First Door Leaf */

        if(DoorSetType == "DD"){

            svg.append('rect')
                //.attr('x', ix + RightGapForLeaf2 + LeafWidth1ForMap)
                .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles)
                //.attr('y', iy + GapAfterOverPanelApplied + UpperAndLowerGap)
                .attr('y', iy + TopFrameHeight + GapForMap)
                .attr('width', LeafWidth2ForMap )
                .attr('height', LeafHeightNoOPForMap )
                .attr('stroke', 'black')
                .attr('fill', 'none');

            if(IsKickPlateEnable){
                svg.append('rect')
                    .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + 3)
                    .attr('y', iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (KickPlateHeight + 3)))
                    .attr('width', LeafWidth2ForMap - 6)
                    .attr('height', KickPlateHeight)
                    .attr('stroke', 'black')
                    .attr('fill', 'grey');
            }

            /* Horizontal Line for Second Door Leaf */
            if(ShowMeasurements){
                svg.append("text")            // append text
                    .style("fill", "blue")      // make the text black
                    .attr("font-size", 15)
                    // .attr("x", ix + ( LeafWidth1ForMap +  LeafWidth2ForMap / 2))         // set x position of left side of text
                    .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + (LeafWidth2ForMap / 2))         // set x position of left side of text
                    // .attr("y", iy - 65)         // set y position of bottom of text
                    .attr("y", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 30 )
                    .text(LeafWidth2);   // define the text to display

                svg.append('line')
                    .style("stroke", "blue")
                    .style("stroke-width", 0.5)
                    // .attr("x1", ix + LeafWidth1ForMap + RightGapForLeaf2 )
                    .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles)
                    // .attr("y1", iy - 60)
                    .attr("y1", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 10 )
                    // .attr("x2", ix + LeafWidth1ForMap + LeafWidth2ForMap + RightGapForLeaf2)
                    .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap)
                    // .attr("y2", iy - 60)
                    .attr("y2", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 10 )
                    .attr("marker-start","url(#verticalMarket)")
                    .attr("marker-end","url(#verticalMarket)");
            }
            /* Horizontal Line for Second Door Leaf */

        }

        /* Vertical Line for Inner Door Leaf */
        if(ShowMeasurements){
            svg.append('line')
                .style("stroke", "blue")
                .style("stroke-width", 0.5)
                .attr("x1", ix + SOWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 51)
                //.attr("y1", iy + GapAfterOverPanelApplied + UpperAndLowerGap)
                .attr("y1", iy + TopFrameHeight + GapForMap)
                .attr("x2", ix + SOWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 51)
                //.attr("y2", iy + GapAfterOverPanelApplied + UpperAndLowerGap + LeafHeightNoOPForMap)
                .attr("y2", iy + TopFrameHeight + GapForMap + LeafHeightNoOPForMap)
                .attr("marker-start","url(#verticalMarket)")
                .attr("marker-end","url(#verticalMarket)");

            svg.append("text")            // append text
                .style("fill", "blue")      // make the text black
                .style("writing-mode", WritingMode) // set the writing mode
                .attr("x", ix + SOWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 52)         // set x position of left side of text
                .attr("font-size", 15)
                //.attr("y", iy + GapAfterOverPanelApplied + (LeafHeightNoOPForMap / 2))         // set y position of bottom of text
                .attr("y", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap / 2))         // set y position of bottom of text
                .text(LeafHeightNoOP);   // define the text to display

            /* Vertical Line for Inner Door Leaf */

            if(DoorSetType == "DD"){
                svg.append('line')
                    .style("stroke", "blue")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap)
                    .attr("y1", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 10 )
                    .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap + GapForMap )
                    .attr("y2", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 10 )
                    .attr("marker-start","url(#verticalMarket)")
                    .attr("marker-end","url(#verticalMarket)");

                svg.append("text")            // append text
                    .style("fill", "blue")      // make the text black
                    .attr("font-size", 15)
                    .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + LeafWidth2ForMap)         // set x position of left side of text
                    .attr("y", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 30 )
                    .text(Gap);   // define the text to display
            }else{
                svg.append('line')
                    .style("stroke", "blue")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)
                    .attr("y1", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 10 )
                    .attr("x2", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles )
                    .attr("y2", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 10 )
                    .attr("marker-start","url(#verticalMarket)")
                    .attr("marker-end","url(#verticalMarket)");

                svg.append("text")            // append text
                    .style("fill", "blue")      // make the text black
                    .attr("font-size", 15)
                    .attr("x", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap)         // set x position of left side of text
                    .attr("y", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 30 )
                    .text(Gap);   // define the text to display
            }
        }
    //}

    var Leaf1VisionPanel = $('select[name="leaf1VisionPanel"]').val();

    var Leaf1VisionPanelWidth = $('input[name="vP1Width"]').val();
    var Leaf1VisionPanelWidthToShow = 0;
    if(Leaf1VisionPanelWidth == ""){
        Leaf1VisionPanelWidth = 0;
    }else{
        Leaf1VisionPanelWidthToShow = parseFloat(Leaf1VisionPanelWidth);
        if(Leaf1VisionPanelWidth > 0){
            Leaf1VisionPanelWidth = parseFloat(Leaf1VisionPanelWidth) / 5;
        }
    }

    var DistanceBetweenVPsMinValue = $('input[name="distanceBetweenVPs"]').attr("min");
    var DistanceFromTopOfDoorMinValue = $('input[name="distanceFromTopOfDoor"]').attr("min");
    var DistanceFromTheEdgeOfDoorMinValue = $('input[name="distanceFromTheEdgeOfDoor"]').attr("min");

    var DistanceBetweenVPsForLeaf1 = DistanceBetweenVPsMinValue;
    var DistanceFromTopOfDoorForLeaf1 = DistanceFromTopOfDoorMinValue;
    var DistanceFromTheEdgeOfDoorForLeaf1 = DistanceFromTheEdgeOfDoorMinValue;

    if(Leaf1VisionPanel == "Yes"){
        if(ChangedFieldName == "leaf1VisionPanel"){
            $('input[name="distanceFromTopOfDoor"]').val(DistanceFromTopOfDoorMinValue);

            $('input[name="distanceFromTheEdgeOfDoor"]').val(DistanceFromTheEdgeOfDoorMinValue);

        }else{
            DistanceFromTopOfDoorForLeaf1 = $('input[name="distanceFromTopOfDoor"]').val();
            if(DistanceFromTopOfDoorForLeaf1 == "" || parseFloat(DistanceFromTopOfDoorForLeaf1) < DistanceFromTopOfDoorMinValue){
                DistanceFromTopOfDoorForLeaf1 = DistanceFromTopOfDoorMinValue;
            }
            DistanceFromTheEdgeOfDoorForLeaf1 = $('input[name="distanceFromTheEdgeOfDoor"]').val();
            if(DistanceFromTheEdgeOfDoorForLeaf1 == "" || parseFloat(DistanceFromTheEdgeOfDoorForLeaf1) < DistanceFromTheEdgeOfDoorMinValue){
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
    if(VisionPanelQuantityForLeaf1 == ""){
        VisionPanelQuantityForLeaf1 = 1;
    }else{
        VisionPanelQuantityForLeaf1 = parseFloat(VisionPanelQuantityForLeaf1);
    }

    if(VisionPanelShape == "Rectangle"){
        $('input[name="vP1Height1"]').removeAttr("readonly");

        if(AreVPsEqualSizesForLeaf1 == "No"){
            if(VisionPanelQuantityForLeaf1 > 1){
                $('input[name="vP1Height2"]').removeAttr("readonly");
            }
            if(VisionPanelQuantityForLeaf1 > 2){
                $('input[name="vP1Height3"]').removeAttr("readonly");
            }
            if(VisionPanelQuantityForLeaf1 > 3){
                $('input[name="vP1Height4"]').removeAttr("readonly");
            }
            if(VisionPanelQuantityForLeaf1 > 4){
                $('input[name="vP1Height5"]').removeAttr("readonly");
            }
        }
    }else{
        $('input[name="vP1Height1"]').attr("readonly",true);
        if(VisionPanelQuantityForLeaf1 > 1){
            $('input[name="vP1Height2"]').attr("readonly",true);
        }
        if(VisionPanelQuantityForLeaf1 > 2){
            $('input[name="vP1Height3"]').attr("readonly",true);
        }
        if(VisionPanelQuantityForLeaf1 > 3){
            $('input[name="vP1Height4"]').attr("readonly",true);
        }
        if(VisionPanelQuantityForLeaf1 > 4){
            $('input[name="vP1Height5"]').attr("readonly",true);
        }
    }

    if(Leaf1VisionPanel == "Yes"){
        if(ChangedFieldName == "visionPanelQuantity" && VisionPanelQuantityForLeaf1 > 1){
            $('input[name="distanceBetweenVPs"]').val(DistanceBetweenVPsMinValue);
        }else{
            if(VisionPanelQuantityForLeaf1 < 2){
                DistanceBetweenVPsForLeaf1 = 0;
            }else{
                DistanceBetweenVPsForLeaf1 = $('input[name="distanceBetweenVPs"]').val();
                if(DistanceBetweenVPsForLeaf1 == "" || parseFloat(DistanceBetweenVPsForLeaf1) < DistanceBetweenVPsMinValue){
                    DistanceBetweenVPsForLeaf1 = DistanceBetweenVPsMinValue;
                }
            }
        }
    }



    if(Leaf1VisionPanel == "Yes"){
        if(ChangedFieldName == "distanceBetweenVPs"){
            DistanceBetweenVPsForLeaf1 = $('input[name="distanceBetweenVPs"]').val();
            if(DistanceBetweenVPsForLeaf1 == "" || parseFloat(DistanceBetweenVPsForLeaf1) < DistanceBetweenVPsMinValue){
                DistanceBetweenVPsForLeaf1 = DistanceBetweenVPsMinValue;
            }
        }
    }

    var DistanceBetweenVPsForLeaf1ToShow = 0;
    if(DistanceBetweenVPsForLeaf1 != "" && DistanceBetweenVPsForLeaf1 > 0){
        DistanceBetweenVPsForLeaf1ToShow = parseFloat(DistanceBetweenVPsForLeaf1);
        DistanceBetweenVPsForLeaf1 = parseFloat(DistanceBetweenVPsForLeaf1) / 5;
    }



    if(Leaf1VisionPanel == "Yes"){
        if(ChangedFieldName == "distanceFromTopOfDoor"){
            DistanceFromTopOfDoorForLeaf1 = $('input[name="distanceFromTopOfDoor"]').val();
            if(DistanceFromTopOfDoorForLeaf1 == "" || parseFloat(DistanceFromTopOfDoorForLeaf1) < DistanceFromTopOfDoorMinValue){
                DistanceFromTopOfDoorForLeaf1 = DistanceFromTopOfDoorMinValue;
            }
        }
    }

    var DistanceFromTopOfDoorForLeaf1ToShow = 0;
    if(DistanceFromTopOfDoorForLeaf1 != "" && DistanceFromTopOfDoorForLeaf1 > 0){
        DistanceFromTopOfDoorForLeaf1ToShow = parseFloat(DistanceFromTopOfDoorForLeaf1);
        if(DistanceFromTopOfDoorForLeaf1 > 0){
            DistanceFromTopOfDoorForLeaf1 = parseFloat(DistanceFromTopOfDoorForLeaf1) / 5;
        }
    }



    if(Leaf1VisionPanel == "Yes"){
        if(ChangedFieldName == "distanceFromTheEdgeOfDoor"){
            DistanceFromTheEdgeOfDoorForLeaf1 = $('input[name="distanceFromTheEdgeOfDoor"]').val();
            if(DistanceFromTheEdgeOfDoorForLeaf1 == "" || parseFloat(DistanceFromTheEdgeOfDoorForLeaf1) < DistanceFromTheEdgeOfDoorMinValue){
                DistanceFromTheEdgeOfDoorForLeaf1 = DistanceFromTheEdgeOfDoorMinValue;
            }
        }
    }

    var DistanceFromTheEdgeOfDoorForLeaf1ToShow = 0;
    if(DistanceFromTheEdgeOfDoorForLeaf1 != "" && DistanceFromTheEdgeOfDoorForLeaf1 > 0){
        DistanceFromTheEdgeOfDoorForLeaf1ToShow = parseFloat(DistanceFromTheEdgeOfDoorForLeaf1);
        if(DistanceFromTheEdgeOfDoorForLeaf1 > 0){
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

    if(IronmongerySet == "Yes" && IronmongeryID != ""){

        if(IsPushHandlesEnable){
            if(LeafWidth1ForMap > 32){
                svg.append('rect')
                    .attr('x', ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (DistanceOfIronmongeryItemsFromRightSideEnd - DistanceOfIronmongeryItemsFromRightSideEndForAdjusting)))
                    .attr('y', iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - DistanceOfPushHandlesFromBelow))
                    .attr('width', 10)
                    .attr('height', 80)
                    .style("stroke-dasharray","5,5")
                    .attr('stroke', 'black')
                    .attr('fill', 'white');
            }
        }

        if(IsPullHandlesEnable){
            if(LeafWidth1ForMap > 32){
                // svg.append("svg:image")
                //     .attr('x',  ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (DistanceOfIronmongeryItemsFromRightSideEnd - DistanceOfIronmongeryItemsFromRightSideEndForAdjusting)))
                //     .attr('y', iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - DistanceOfPullHandlesFromBelow))
                //     .attr('width', 10)
                //     .attr('height', 80)
                //     .attr("xlink:href", "http://firedoor.workdemo.online/public/images/pull_handles.png");

                svg.append("rect")
                    .attr('x',  ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (DistanceOfIronmongeryItemsFromRightSideEnd - DistanceOfIronmongeryItemsFromRightSideEndForAdjusting)))
                    .attr('y', iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - DistanceOfPullHandlesFromBelow))
                    .attr("width", 8)
                    .attr("height", 80)
                    .attr("rx", 4)
                    .style("fill", 'white')
                    .style("stroke", 'black')
            }
        }

        //if(DoorSetType == "SD"){
        if(IsLockNLatchesEnable){
             var DistanceOfLeverHandleFromBelow = 800 / 5;
            //  svg.append("circle")
            //      .style("stroke", "black")
            //      .style("fill", "grey")
            //      .attr("r", 3)
            //      .attr("cx", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (DistanceOfIronmongeryItemsFromRightSideEnd - (DistanceOfIronmongeryItemsFromRightSideEndForAdjusting*2))))
            //      .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - DistanceOfLeverHandleFromBelow));

            // svg.append("svg:image")
            //         .attr('x',  ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - 20))
            //         .attr('y', iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - DistanceOfLeverHandleFromBelow))
            //         .attr('width', 20)
            //         .attr('height', 20)
            //         .attr("xlink:href", "http://firedoor.workdemo.online/public/images/door_sinage.png");

            svg.append("circle")
                 .style("stroke", "black")
                 .style("fill", "white")
                 .attr("r", 5)
                 .attr("cx", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (DistanceOfIronmongeryItemsFromRightSideEnd - (DistanceOfIronmongeryItemsFromRightSideEndForAdjusting*2))))
                 .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - DistanceOfLeverHandleFromBelow));

            svg.append('line')
                .style("stroke", "black")
                .attr("x1", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (DistanceOfIronmongeryItemsFromRightSideEnd - DistanceOfIronmongeryItemsFromRightSideEndForAdjusting)) - 5)
                .attr("y1", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (DistanceOfLeverHandleFromBelow)) )
                .attr("x2", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (DistanceOfIronmongeryItemsFromRightSideEnd - DistanceOfIronmongeryItemsFromRightSideEndForAdjusting)) + 15)
                .attr("y2", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (DistanceOfLeverHandleFromBelow)) );
        }

        var DistanceOfDoorSinageFromTop = 1800 / 5;

        if(IsDoorSinageEnable){
            if(LeafWidth1ForMap > 32){
                svg.append("circle")
                   .style("stroke", "black")
                   .style("fill", "grey")
                   .attr("r", 5)
                   .attr("cx", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - (DistanceOfIronmongeryItemsFromRightSideEnd - (DistanceOfIronmongeryItemsFromRightSideEndForAdjusting*2))))
                   .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - DistanceOfDoorSinageFromTop));

                // svg.append("svg:image")
                //     .attr('x',  ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - 20))
                //     .attr('y', iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - DistanceOfDoorSinageFromTop))
                //     .attr('width', 20)
                //     .attr('height', 20)
                //     .attr("xlink:href", "http://firedoor.workdemo.online/public/images/door_sinage.png");
            }
        }

        if(DoorSetType == "DD"){

            if(IsDoorSinageEnable){
                svg.append("circle")
                   .style("stroke", "black")
                   .style("fill", "grey")
                   .attr("r", 5)
                   .attr("cx", ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + (DistanceOfIronmongeryItemsFromLeftSideEndForAdjusting*2))
                   .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - DistanceOfDoorSinageFromTop));

                // svg.append("svg:image")
                //     .attr('x',  ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles)
                //     .attr('y', iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - DistanceOfDoorSinageFromTop))
                //     .attr('width', 20)
                //     .attr('height', 20)
                //     .attr("xlink:href", "http://firedoor.workdemo.online/public/images/door_sinage.png");
            }

            if(IsPushHandlesEnable){
                svg.append('rect')
                    .attr('x', ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + DistanceOfIronmongeryItemsFromLeftSideEndForAdjusting)
                    .attr('y', iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - DistanceOfPushHandlesFromBelow))
                    .attr('width', 10)
                    .attr('height', 80)
                    .style("stroke-dasharray","5,5")
                    .attr('stroke', 'black')
                    .attr('fill', 'white');
            }

            if(IsPullHandlesEnable){

                // svg.append("svg:image")
                //     .attr('x',  ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + DistanceOfIronmongeryItemsFromLeftSideEndForAdjusting)
                //     .attr('y', iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - DistanceOfPullHandlesFromBelow))
                //     .attr('width', 10)
                //     .attr('height', 80)
                //     .attr("xlink:href", "http://firedoor.workdemo.online/public/images/pull_handles.png");

                svg.append("rect")
                    .attr('x',  ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + DistanceOfIronmongeryItemsFromLeftSideEndForAdjusting)
                    .attr('y', iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - DistanceOfPullHandlesFromBelow))
                    .attr("width", 8)
                    .attr("height", 80)
                    .attr("rx", 4)
                    .style("fill", 'white')
                    .style("stroke", 'black');
            }
        }
    }

    /* Ironmongery */

    var TotalKickPlateSectionHeight = KickPlateHeight;

    if(KickPlateHeight > 0){
        TotalKickPlateSectionHeight = KickPlateHeight + 3;
    }

    var RemainingHeightOfLeaf = LeafHeightNoOPForMap - TotalKickPlateSectionHeight;

    if(Leaf1VisionPanel == "Yes"){

        if(AreVPsEqualSizesForLeaf1 != "Yes"){

            Leaf1VisionPanel1Height = $('input[name="vP1Height1"]').val();
            if(Leaf1VisionPanel1Height == ""){
                Leaf1VisionPanel1Height = 0;
            }else{
                Leaf1VisionPanel1HeightToShow = parseFloat(Leaf1VisionPanel1Height);
                if(Leaf1VisionPanel1Height > 0){
                    Leaf1VisionPanel1Height = parseFloat(Leaf1VisionPanel1Height) / 5;
                }
            }

            Leaf1VisionPanel2Height = $('input[name="vP1Height2"]').val();
            if(Leaf1VisionPanel2Height == ""){
                Leaf1VisionPanel2Height = 0;
            }else{
                Leaf1VisionPanel2HeightToShow = parseFloat(Leaf1VisionPanel2Height);
                if(Leaf1VisionPanel2Height > 0){
                    Leaf1VisionPanel2Height = parseFloat(Leaf1VisionPanel2Height) / 5;
                }
            }

            Leaf1VisionPanel3Height = $('input[name="vP1Height3"]').val();
            if(Leaf1VisionPanel3Height == ""){
                Leaf1VisionPanel3Height = 0;
            }else{
                Leaf1VisionPanel3HeightToShow = parseFloat(Leaf1VisionPanel3Height);
                if(Leaf1VisionPanel3Height > 0){
                    Leaf1VisionPanel3Height = parseFloat(Leaf1VisionPanel3Height) / 5;
                }
            }

            Leaf1VisionPanel4Height = $('input[name="vP1Height4"]').val();
            if(Leaf1VisionPanel4Height == ""){
                Leaf1VisionPanel4Height = 0;
            }else{
                Leaf1VisionPanel4HeightToShow = parseFloat(Leaf1VisionPanel4Height);
                if(Leaf1VisionPanel4Height > 0){
                    Leaf1VisionPanel4Height = parseFloat(Leaf1VisionPanel4Height) / 5;
                }
            }

            Leaf1VisionPanel5Height = $('input[name="vP1Height5"]').val();
            if(Leaf1VisionPanel5Height == ""){
                Leaf1VisionPanel5Height = 0;
            }else{
                Leaf1VisionPanel5HeightToShow = parseFloat(Leaf1VisionPanel5Height);
                if(Leaf1VisionPanel5Height > 0){
                    Leaf1VisionPanel5Height = parseFloat(Leaf1VisionPanel5Height) / 5;
                }
            }

        }else{
            var VP1Height1 = $('input[name="vP1Height1"]').val();
            if(VP1Height1 == ""){
                VP1Height1 = 0;
            }else{
                Leaf1VisionPanel1HeightToShow = Leaf1VisionPanel2HeightToShow = Leaf1VisionPanel3HeightToShow = Leaf1VisionPanel4HeightToShow = Leaf1VisionPanel5HeightToShow = parseFloat(VP1Height1);
                if(VP1Height1 > 0){
                    VP1Height1 = parseFloat(VP1Height1) / 5;
                }
            }

            Leaf1VisionPanel1Height = Leaf1VisionPanel2Height = Leaf1VisionPanel3Height = Leaf1VisionPanel4Height = Leaf1VisionPanel5Height = VP1Height1;
        }

        if($.inArray( VisionPanelShape, ["Diamond","Circle","Square"] ) !== -1){

            $('select[name="AreVPsEqualSizes"]').attr("readonly",true);
            $('select[name="AreVPsEqualSizes"]').val("Yes");

            AreVPsEqualSizesForLeaf1 = "Yes";

            $('input[name="vP1Height1"]').attr("readonly",true);
            $('input[name="vP1Height2"]').attr("readonly",true);
            $('input[name="vP1Height3"]').attr("readonly",true);
            $('input[name="vP1Height4"]').attr("readonly",true);
            $('input[name="vP1Height5"]').attr("readonly",true);

            $('input[name="vP1Height1"]').val(Leaf1VisionPanelWidthToShow);
            Leaf1VisionPanel1Height = Leaf1VisionPanelWidth;
            Leaf1VisionPanel1HeightToShow = Leaf1VisionPanelWidthToShow;

            if(VisionPanelQuantityForLeaf1 > 1){
                $('input[name="vP1Height2"]').val(Leaf1VisionPanelWidthToShow);
                Leaf1VisionPanel2Height = Leaf1VisionPanelWidth;
                Leaf1VisionPanel2HeightToShow = Leaf1VisionPanelWidthToShow;
            }

            if(VisionPanelQuantityForLeaf1 > 2){
                $('input[name="vP1Height3"]').val(Leaf1VisionPanelWidthToShow);
                Leaf1VisionPanel3Height = Leaf1VisionPanelWidth;
                Leaf1VisionPanel3HeightToShow = Leaf1VisionPanelWidthToShow;
            }

            if(VisionPanelQuantityForLeaf1 > 3){
                $('input[name="vP1Height4"]').val(Leaf1VisionPanelWidthToShow);
                Leaf1VisionPanel4Height = Leaf1VisionPanelWidth;
                Leaf1VisionPanel4HeightToShow = Leaf1VisionPanelWidthToShow;
            }

            if(VisionPanelQuantityForLeaf1 > 4){
                $('input[name="vP1Height5"]').val(Leaf1VisionPanelWidthToShow);
                Leaf1VisionPanel5Height = Leaf1VisionPanelWidth;
                Leaf1VisionPanel5HeightToShow = Leaf1VisionPanelWidthToShow;
            }

        }else{

            $('select[name="AreVPsEqualSizes"]').removeAttr("readonly");

            if(AreVPsEqualSizesForLeaf1 == "No"){

                $('input[name="vP1Height1"]').removeAttr("readonly");

                if(VisionPanelQuantityForLeaf1 > 1){
                    $('input[name="vP1Height2"]').removeAttr("readonly");
                }

                if(VisionPanelQuantityForLeaf1 > 2){
                    $('input[name="vP1Height3"]').removeAttr("readonly");
                }

                if(VisionPanelQuantityForLeaf1 > 3){
                    $('input[name="vP1Height4"]').removeAttr("readonly");
                }

                if(VisionPanelQuantityForLeaf1 > 4){
                    $('input[name="vP1Height5"]').removeAttr("readonly");
                }

            }

        }

        var RemainingWidthOfVisionPanelForLeftLeaf = 0;
        var TotalHeightOfVisionPanelForLeftLeaf = 0;

        RemainingWidthOfVisionPanelForLeftLeaf = LeafWidth1 - (parseFloat(DistanceFromTheEdgeOfDoorForLeaf1ToShow) + parseFloat(Leaf1VisionPanelWidthToShow));
        TotalHeightOfVisionPanelForLeftLeaf = (parseFloat(DistanceFromTopOfDoorForLeaf1) * 2) + parseFloat(Leaf1VisionPanel1Height);

        if(VisionPanelQuantityForLeaf1 > 1){
            TotalHeightOfVisionPanelForLeftLeaf =  parseFloat(TotalHeightOfVisionPanelForLeftLeaf) + parseFloat(DistanceBetweenVPsForLeaf1) + parseFloat(Leaf1VisionPanel2Height);
        }

        if(VisionPanelQuantityForLeaf1 > 2){
            TotalHeightOfVisionPanelForLeftLeaf =  parseFloat(TotalHeightOfVisionPanelForLeftLeaf) + parseFloat(DistanceBetweenVPsForLeaf1) + parseFloat(Leaf1VisionPanel3Height);
        }

        if(VisionPanelQuantityForLeaf1 > 3){
            TotalHeightOfVisionPanelForLeftLeaf =  parseFloat(TotalHeightOfVisionPanelForLeftLeaf) + parseFloat(DistanceBetweenVPsForLeaf1) + parseFloat(Leaf1VisionPanel4Height);
        }

        if(VisionPanelQuantityForLeaf1 > 4){
            TotalHeightOfVisionPanelForLeftLeaf =  parseFloat(TotalHeightOfVisionPanelForLeftLeaf) + parseFloat(DistanceBetweenVPsForLeaf1) + parseFloat(Leaf1VisionPanel5Height);
        }

        // alert(TotalWidthOfVisionPanelForLeftLeaf + "-" + LeafWidth1ForMap + "-" + TotalHeightOfVisionPanelForLeftLeaf + "-" + LeafHeightNoOPForMap);

        //console.log(RemainingWidthOfVisionPanelForLeftLeaf+ '--' +DistanceFromTheEdgeOfDoorForLeaf1ToShow);
        console.log(LeafWidth1+ '--' +DistanceFromTheEdgeOfDoorMinValue+ '--' +Leaf1VisionPanelWidthToShow);

        if(RemainingWidthOfVisionPanelForLeftLeaf < DistanceFromTheEdgeOfDoorMinValue){

            if(VisionPanelShape == "Rectangle"){
                DistanceFromTheEdgeOfDoorForLeaf1ToShow = parseFloat(LeafWidth1) - (parseFloat(DistanceFromTheEdgeOfDoorMinValue) + parseFloat(Leaf1VisionPanelWidthToShow));
                DistanceFromTheEdgeOfDoorForLeaf1 = DistanceFromTheEdgeOfDoorForLeaf1ToShow/5;

                $('input[name="distanceFromTheEdgeOfDoor"]').val(DistanceFromTheEdgeOfDoorForLeaf1ToShow);

                swal('Warning!',"Distance of vision panel from left edge should be atleast " + DistanceFromTheEdgeOfDoorMinValue + "mm");
            }


            if($.inArray( VisionPanelShape, ["Diamond","Circle","Square"] ) !== -1){

                DistanceFromTheEdgeOfDoorForLeaf1ToShow = DistanceFromTheEdgeOfDoorMinValue;
                DistanceFromTheEdgeOfDoorForLeaf1 = DistanceFromTheEdgeOfDoorMinValue/5;

                $('input[name="distanceFromTheEdgeOfDoor"]').val(DistanceFromTheEdgeOfDoorMinValue);

                // var NewLeaf1VisionPanelWidth = LeafWidth1 - (parseFloat(DistanceFromTheEdgeOfDoorForLeaf1ToShow) * 2);
                // Leaf1VisionPanelWidth = NewLeaf1VisionPanelWidth/5;
                // Leaf1VisionPanelWidthToShow = NewLeaf1VisionPanelWidth;
                // $("#vP1Width").val(NewLeaf1VisionPanelWidth);
            }else{
                //swal('Warning!',"Distance of vision panel from left edge should not be less than the distance from right edge.");
            }
        }

        console.log(TotalHeightOfVisionPanelForLeftLeaf+ '--' + RemainingHeightOfLeaf);

        if(TotalHeightOfVisionPanelForLeftLeaf >= RemainingHeightOfLeaf){

            DistanceFromTopOfDoorForLeaf1ToShow = DistanceFromTopOfDoorMinValue;
            DistanceFromTopOfDoorForLeaf1 = DistanceFromTopOfDoorMinValue/5;

            $('input[name="distanceFromTopOfDoor"]').val(DistanceFromTopOfDoorMinValue);

            DistanceBetweenVPsForLeaf1ToShow = DistanceBetweenVPsMinValue;
            DistanceBetweenVPsForLeaf1 = DistanceBetweenVPsMinValue/5;

            $('input[name="distanceBetweenVPs"]').val(DistanceBetweenVPsForLeaf1ToShow);

            var NumberOfVps = VisionPanelQuantityForLeaf1 - 1;

            var NewLeaf1VisionPanelHeight = (LeafHeightNoOP - ((KickPlateHeight*5) + 3)) - ((parseFloat(DistanceFromTopOfDoorForLeaf1ToShow) * 2) + (parseFloat(DistanceBetweenVPsForLeaf1ToShow) * NumberOfVps));
            //alert(NewLeaf1VisionPanelHeight);
            console.log(NewLeaf1VisionPanelHeight);
            NewLeaf1VisionPanelHeight = Math.floor(NewLeaf1VisionPanelHeight/VisionPanelQuantityForLeaf1);

            console.log(NewLeaf1VisionPanelHeight);

            if(KickPlateHeight > 0){
                NewLeaf1VisionPanelHeight = NewLeaf1VisionPanelHeight - 3;
            }

            if($.inArray( VisionPanelShape, ["Diamond","Circle","Square"] ) !== -1) {

                if (NewLeaf1VisionPanelHeight > (LeafWidth1 - (parseFloat(DistanceFromTheEdgeOfDoorForLeaf1ToShow) * 2))) {

                    NewLeaf1VisionPanelHeight = LeafWidth1 - (parseFloat(DistanceFromTheEdgeOfDoorForLeaf1ToShow) * 2);

                    // NewLeaf1VisionPanelHeight = Math.floor(NewLeaf1VisionPanelHeight/VisionPanelQuantityForLeaf1);

                    if (KickPlateHeight > 0) {
                        NewLeaf1VisionPanelHeight = NewLeaf1VisionPanelHeight - 3;
                    }
                }

                Leaf1VisionPanelWidth = NewLeaf1VisionPanelHeight / 5;
                Leaf1VisionPanelWidthToShow = NewLeaf1VisionPanelHeight;
                $("#vP1Width").val(NewLeaf1VisionPanelHeight);
            }

            console.log(NewLeaf1VisionPanelHeight);
            //alert(NewLeaf1VisionPanelHeight);

                Leaf1VisionPanel1Height = NewLeaf1VisionPanelHeight/5;
                Leaf1VisionPanel1HeightToShow = NewLeaf1VisionPanelHeight;
                $("#vP1Height1").val(NewLeaf1VisionPanelHeight);

                if(VisionPanelQuantityForLeaf1 > 1){
                    Leaf1VisionPanel2Height = NewLeaf1VisionPanelHeight/5;
                    Leaf1VisionPanel2HeightToShow = NewLeaf1VisionPanelHeight;
                    $("#vP1Height2").val(NewLeaf1VisionPanelHeight);
                }

                if(VisionPanelQuantityForLeaf1 > 2){
                    Leaf1VisionPanel3Height = NewLeaf1VisionPanelHeight/5;
                    Leaf1VisionPanel3HeightToShow = NewLeaf1VisionPanelHeight;
                    $("#vP1Height3").val(NewLeaf1VisionPanelHeight);
                }

                if(VisionPanelQuantityForLeaf1 > 3){
                    Leaf1VisionPanel4Height = NewLeaf1VisionPanelHeight/5;
                    Leaf1VisionPanel4HeightToShow = NewLeaf1VisionPanelHeight;
                    $("#vP1Height4").val(NewLeaf1VisionPanelHeight);
                }

                if(VisionPanelQuantityForLeaf1 > 4){
                    Leaf1VisionPanel5Height = NewLeaf1VisionPanelHeight/5;
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

            if(ChangedFieldName == "distanceFromTheEdgeOfDoor"){
                swal('Warning!',"Entered distance from the edge of door of left leaf exceeds the width of left leaf.");
            }else if(ChangedFieldName == "distanceFromTopOfDoor"){
                swal('Warning!',"Entered distance from top of door of left leaf exceeds the height of left leaf.");
            }else if(ChangedFieldName == "distanceBetweenVPs"){
                swal('Warning!',"Entered distance between vps of left leaf exceeds the height of left leaf.");
            }else{
                swal('Warning!',"Sum of all vision panel's height should not be greater than leaf's height.");
            }

        }

        LeftGapForLeaf1 = FrameThicknessForMap + GapForMap;

        var LeftSideDistanceOfVisonPanelOfDoorLeaf1ToShow = LeafWidth1 - (parseFloat(DistanceFromTheEdgeOfDoorForLeaf1ToShow) + parseFloat(Leaf1VisionPanelWidthToShow)) ;
        var LeftSideDistanceOfVisonPanelOfDoorLeaf1 = (LeftSideDistanceOfVisonPanelOfDoorLeaf1ToShow/5) - RemainingGap;

        var CircleRadius = parseFloat(Leaf1VisionPanelWidth)/2;

        var DistanceXForLeaf1VPShape = ix + LeftGapForLeaf1 + LeftSideDistanceOfVisonPanelOfDoorLeaf1;
        var DistanceYForLeaf1VPShape = iy + GapAfterOverPanelApplied + UpperAndLowerGap + parseFloat(DistanceFromTopOfDoorForLeaf1);

        var DiamondDimension = 'M '+CircleRadius+' 0 '+Leaf1VisionPanelWidth+' '+CircleRadius+' '+CircleRadius+' '+Leaf1VisionPanelWidth+' 0 '+CircleRadius+' Z';

        if(VisionPanelShape == "Diamond"){

            // var DiamondVisonPanel  = d3.symbol()
            //     .type(d3.symbolDiamond).size(Leaf1VisionPanel1HeightToShow);
            // svg.append("path")
            //     .attr("d", DiamondVisonPanel)
            //     .attr("fill", "#aacbee")
            //     .style("stroke", "black")
            //     .attr("transform", "translate("+disX+","+disY+")");

            svg.append('path')
                .attr('d', DiamondDimension)
                .attr("fill", "#aacbee")
                .style("stroke", "black")
                .attr("transform", "translate("+DistanceXForLeaf1VPShape+","+DistanceYForLeaf1VPShape+")");

        }else if(VisionPanelShape == "Circle"){
            svg.append("circle")
                .style("stroke", "black")
                .style("fill", "#aacbee")
                .attr("r", CircleRadius)
                .attr("cx", DistanceXForLeaf1VPShape + CircleRadius)
                .attr("cy", DistanceYForLeaf1VPShape + CircleRadius);
        }else{
            svg.append('rect')
                .attr('x', DistanceXForLeaf1VPShape)
                .attr('y', DistanceYForLeaf1VPShape)
                .attr('width', Leaf1VisionPanelWidth)
                .attr('height', Leaf1VisionPanel1Height)
                .attr('stroke', 'black')
                .attr('fill', '#aacbee');
        }

        /* Horizontal Line for vision panel */
        if(ShowMeasurements){
            svg.append("text")            // append text
                .style("fill", "blue")      // make the text black
                .attr("font-size", 15)
                .attr("x", ix + LeftGapForLeaf1 + (parseFloat(LeftSideDistanceOfVisonPanelOfDoorLeaf1) / 2))         // set x position of left side of text
                .attr("y", iy - 40)         // set y position of bottom of text
                .text(LeftSideDistanceOfVisonPanelOfDoorLeaf1ToShow);   // define the text to display

            svg.append('line')
                .style("stroke", "blue")
                .style("stroke-width", 0.5)
                .attr("x1", ix + LeftGapForLeaf1 )
                .attr("y1", iy - 35)
                .attr("x2", DistanceXForLeaf1VPShape )
                .attr("y2", iy - 35)
                .attr("marker-start","url(#verticalMarket)")
                .attr("marker-end","url(#verticalMarket)");

            svg.append("text")            // append text
                .style("fill", "blue")      // make the text black
                .attr("font-size", 15)
                .attr("x", DistanceXForLeaf1VPShape + ( parseFloat(Leaf1VisionPanelWidth) / 2))         // set x position of left side of text
                .attr("y", iy - 40)         // set y position of bottom of text
                .text(Leaf1VisionPanelWidthToShow);   // define the text to display

            svg.append('line')
                .style("stroke", "blue")
                .style("stroke-width", 0.5)
                .attr("x1", DistanceXForLeaf1VPShape )
                .attr("y1", iy - 35)
                .attr("x2", DistanceXForLeaf1VPShape + parseFloat(Leaf1VisionPanelWidth))
                .attr("y2", iy - 35)
                .attr("marker-start","url(#verticalMarket)")
                .attr("marker-end","url(#verticalMarket)");
        }

        // var RemainedSpaceInLeaf1 = LeafWidth1 - (DistanceFromTheEdgeOfDoorForLeaf1ToShow + Leaf1VisionPanelWidthToShow);
        var RemainedSpaceInLeaf1 = DistanceFromTheEdgeOfDoorForLeaf1ToShow;

        if(ShowMeasurements){

            svg.append("text")            // append text
                .style("fill", "blue")      // make the text black
                .attr("font-size", 15)
                .attr("x", DistanceXForLeaf1VPShape + parseFloat(Leaf1VisionPanelWidth) + ( (RemainedSpaceInLeaf1 / 5) / 2))         // set x position of left side of text
                .attr("y", iy - 40)         // set y position of bottom of text
                .text(RemainedSpaceInLeaf1);   // define the text to display

            svg.append('line')
                .style("stroke", "blue")
                .style("stroke-width", 0.5)
                .attr("x1", DistanceXForLeaf1VPShape + parseFloat(Leaf1VisionPanelWidth))
                .attr("y1", iy - 35)
                .attr("x2", ix + LeafWidth1ForMap + LeftGapForLeaf1)
                .attr("y2", iy - 35)
                .attr("marker-start","url(#verticalMarket)")
                .attr("marker-end","url(#verticalMarket)");

            /* Horizontal Line for vision panel */

            /* Vertical Line for vision panel */

            svg.append('line')
                .style("stroke", "blue")
                .style("stroke-width", 0.5)
                .attr("x1", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                .attr("y1", iy + GapAfterOverPanelApplied + UpperAndLowerGap)
                .attr("x2", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                .attr("y2", DistanceYForLeaf1VPShape)
                .attr("marker-start","url(#verticalMarket)")
                .attr("marker-end","url(#verticalMarket)");

            svg.append("text")            // append text
                .style("fill", "blue")      // make the text black
                .style("writing-mode", WritingMode) // set the writing mode
                .attr("x", ix - 50 - SideLightPanel1WidthSpaceForVerticalLines)         // set x position of left side of text
                .attr("font-size", 15)
                .attr("y", iy + GapAfterOverPanelApplied + UpperAndLowerGap + (DistanceFromTopOfDoorForLeaf1 / 2))         // set y position of bottom of text
                .text(DistanceFromTopOfDoorForLeaf1ToShow);   // define the text to display


            svg.append('line')
                .style("stroke", "blue")
                .style("stroke-width", 0.5)
                .attr("x1", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                .attr("y1", DistanceYForLeaf1VPShape)
                .attr("x2", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                .attr("y2", DistanceYForLeaf1VPShape + parseFloat(Leaf1VisionPanel1Height))
                .attr("marker-start","url(#verticalMarket)")
                .attr("marker-end","url(#verticalMarket)");

            svg.append("text")            // append text
                .style("fill", "blue")      // make the text black
                .style("writing-mode", WritingMode) // set the writing mode
                .attr("x", ix - 50 - SideLightPanel1WidthSpaceForVerticalLines)         // set x position of left side of text
                .attr("font-size", 15)
                .attr("y", DistanceYForLeaf1VPShape + ( parseFloat(Leaf1VisionPanel1Height) / 2))         // set y position of bottom of text
                .text(Leaf1VisionPanel1HeightToShow);   // define the text to display
        }
        /* Vertical Line for vision panel */

        if(VisionPanelQuantityForLeaf1 > 1){

            DistanceYForLeaf1VPShape = DistanceYForLeaf1VPShape + Leaf1VisionPanel1Height + DistanceBetweenVPsForLeaf1;

            if(VisionPanelShape == "Diamond"){
                svg.append('path')
                    .attr('d', DiamondDimension)
                    .attr("fill", "#aacbee")
                    .style("stroke", "black")
                    .attr("transform", "translate("+DistanceXForLeaf1VPShape+","+ DistanceYForLeaf1VPShape +")");
            }else if(VisionPanelShape == "Circle"){
                svg.append("circle")
                    .style("stroke", "black")
                    .style("fill", "#aacbee")
                    .attr("r", CircleRadius)
                    .attr("cx", DistanceXForLeaf1VPShape + CircleRadius)
                    .attr("cy", DistanceYForLeaf1VPShape + CircleRadius);
            }else{
                svg.append('rect')
                    .attr('x', DistanceXForLeaf1VPShape)
                    .attr('y', DistanceYForLeaf1VPShape)
                    .attr('width', Leaf1VisionPanelWidth)
                    .attr('height', parseFloat(Leaf1VisionPanel2Height))
                    .attr('stroke', 'black')
                    .attr('fill', '#aacbee');
            }

            if(ShowMeasurements){

                /* Vertical Line for vision panel */

                svg.append('line')
                    .style("stroke", "blue")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                    .attr("y1", DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1)
                    .attr("x2", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                    .attr("y2", DistanceYForLeaf1VPShape)
                    .attr("marker-start","url(#verticalMarket)")
                    .attr("marker-end","url(#verticalMarket)");

                svg.append("text")            // append text
                    .style("fill", "blue")      // make the text black
                    .style("writing-mode", WritingMode) // set the writing mode
                    .attr("x", ix - 50 - SideLightPanel1WidthSpaceForVerticalLines)         // set x position of left side of text
                    .attr("font-size", 15)
                    .attr("y", (DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1) + ( DistanceBetweenVPsForLeaf1 / 2))         // set y position of bottom of text
                    .text(DistanceBetweenVPsForLeaf1ToShow);   // define the text to display

                svg.append('line')
                    .style("stroke", "blue")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                    .attr("y1", DistanceYForLeaf1VPShape)
                    .attr("x2", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                    .attr("y2", DistanceYForLeaf1VPShape + Leaf1VisionPanel2Height)
                    .attr("marker-start","url(#verticalMarket)")
                    .attr("marker-end","url(#verticalMarket)");

                svg.append("text")            // append text
                    .style("fill", "blue")      // make the text black
                    .style("writing-mode", WritingMode) // set the writing mode
                    .attr("x", ix - 50 - SideLightPanel1WidthSpaceForVerticalLines)         // set x position of left side of text
                    .attr("font-size", 15)
                    .attr("y", DistanceYForLeaf1VPShape +  (Leaf1VisionPanel2Height / 2))        // set y position of bottom of text
                    .text(Leaf1VisionPanel2HeightToShow);   // define the text to display


                //svg.append('line')
                //    .style("stroke", "blue")
                //    .style("stroke-width", 0.5)
                //    .attr("x1", ix - 15)
                //    .attr("y1", iy + GapAfterOverPanelApplied + UpperAndLowerGap + DistanceFromTopOfDoorForLeaf1 + parseFloat(Leaf1VisionPanel1Height) + parseFloat(DistanceBetweenVPsForLeaf1))
                //    .attr("x2", ix - 15)
                //    .attr("y2", iy + GapAfterOverPanelApplied + UpperAndLowerGap + DistanceFromTopOfDoorForLeaf1 + parseFloat(Leaf1VisionPanel1Height) + parseFloat(DistanceBetweenVPsForLeaf1) + parseFloat(Leaf1VisionPanel2Height))
                //    .attr("marker-start","url(#verticalMarket)")
                //    .attr("marker-end","url(#verticalMarket)");
                //
                //svg.append("text")            // append text
                //    .style("fill", "blue")      // make the text black
                //    .style("writing-mode", WritingMode) // set the writing mode
                //    .attr("x", ix - 25)         // set x position of left side of text
                //    .attr("font-size", 15)
                //    .attr("y", iy + GapAfterOverPanelApplied + UpperAndLowerGap + DistanceFromTopOfDoorForLeaf1 + parseFloat(Leaf1VisionPanel1Height) + parseFloat(DistanceBetweenVPsForLeaf1) + ( parseFloat(Leaf1VisionPanel2Height) / 2))         // set y position of bottom of text
                //    .text(Leaf1VisionPanel2HeightToShow);   // define the text to display

                /* Vertical Line for vision panel */
            }

        }

        if(VisionPanelQuantityForLeaf1 > 2){

            DistanceYForLeaf1VPShape = DistanceYForLeaf1VPShape
            + Leaf1VisionPanel2Height + DistanceBetweenVPsForLeaf1;

            if(VisionPanelShape == "Diamond"){
                svg.append('path')
                    .attr('d', DiamondDimension)
                    .attr("fill", "#aacbee")
                    .style("stroke", "black")
                    .attr("transform", "translate("+DistanceXForLeaf1VPShape+","+ DistanceYForLeaf1VPShape +")");
            }else if(VisionPanelShape == "Circle"){
                svg.append("circle")
                    .style("stroke", "black")
                    .style("fill", "#aacbee")
                    .attr("r", CircleRadius)
                    .attr("cx", DistanceXForLeaf1VPShape + CircleRadius)
                    .attr("cy", DistanceYForLeaf1VPShape + CircleRadius);
            }else{
                svg.append('rect')
                    .attr('x', DistanceXForLeaf1VPShape)
                    .attr('y', DistanceYForLeaf1VPShape)
                    .attr('width', parseFloat(Leaf1VisionPanelWidth))
                    .attr('height', parseFloat(Leaf1VisionPanel3Height))
                    .attr('stroke', 'black')
                    .attr('fill', '#aacbee');
            }

            if(ShowMeasurements){

                /* Vertical Line for vision panel */

                svg.append('line')
                    .style("stroke", "blue")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                    .attr("y1", DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1)
                    .attr("x2", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                    .attr("y2", DistanceYForLeaf1VPShape)
                    .attr("marker-start","url(#verticalMarket)")
                    .attr("marker-end","url(#verticalMarket)");

                svg.append("text")            // append text
                    .style("fill", "blue")      // make the text black
                    .style("writing-mode", WritingMode) // set the writing mode
                    .attr("x", ix - 50 - SideLightPanel1WidthSpaceForVerticalLines)         // set x position of left side of text
                    .attr("font-size", 15)
                    .attr("y", (DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1) + ( DistanceBetweenVPsForLeaf1 / 2))         // set y position of bottom of text
                    .text(DistanceBetweenVPsForLeaf1ToShow);   // define the text to display

                svg.append('line')
                    .style("stroke", "blue")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                    .attr("y1", DistanceYForLeaf1VPShape)
                    .attr("x2", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                    .attr("y2", DistanceYForLeaf1VPShape + Leaf1VisionPanel3Height)
                    .attr("marker-start","url(#verticalMarket)")
                    .attr("marker-end","url(#verticalMarket)");

                svg.append("text")            // append text
                    .style("fill", "blue")      // make the text black
                    .style("writing-mode", WritingMode) // set the writing mode
                    .attr("x", ix - 50 - SideLightPanel1WidthSpaceForVerticalLines)         // set x position of left side of text
                    .attr("font-size", 15)
                    .attr("y", DistanceYForLeaf1VPShape + (Leaf1VisionPanel3Height / 2))        // set y position of bottom of text
                    .text(Leaf1VisionPanel3HeightToShow);   // define the text to display

                /* Vertical Line for vision panel */
            }

        }

        if(VisionPanelQuantityForLeaf1 > 3){

            DistanceYForLeaf1VPShape = DistanceYForLeaf1VPShape
            + Leaf1VisionPanel3Height + DistanceBetweenVPsForLeaf1;

            if(VisionPanelShape == "Diamond"){
                svg.append('path')
                    .attr('d', DiamondDimension)
                    .attr("fill", "#aacbee")
                    .style("stroke", "black")
                    .attr("transform", "translate("+DistanceXForLeaf1VPShape+","+ DistanceYForLeaf1VPShape +")");
            }else if(VisionPanelShape == "Circle"){
                svg.append("circle")
                    .style("stroke", "black")
                    .style("fill", "#aacbee")
                    .attr("r", CircleRadius)
                    .attr("cx", DistanceXForLeaf1VPShape + CircleRadius)
                    .attr("cy", DistanceYForLeaf1VPShape + CircleRadius);
            }else{
                svg.append('rect')
                    .attr('x', DistanceXForLeaf1VPShape)
                    .attr('y', DistanceYForLeaf1VPShape)
                    .attr('width', parseFloat(Leaf1VisionPanelWidth))
                    .attr('height', parseFloat(Leaf1VisionPanel4Height))
                    .attr('stroke', 'black')
                    .attr('fill', '#aacbee');
            }

            if(ShowMeasurements){
                /* Vertical Line for vision panel */

                svg.append('line')
                    .style("stroke", "blue")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                    .attr("y1", (DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1) )
                    .attr("x2", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                    .attr("y2", DistanceYForLeaf1VPShape)
                    .attr("marker-start","url(#verticalMarket)")
                    .attr("marker-end","url(#verticalMarket)");

                svg.append("text")            // append text
                    .style("fill", "blue")      // make the text black
                    .style("writing-mode", WritingMode) // set the writing mode
                    .attr("x", ix - 50 - SideLightPanel1WidthSpaceForVerticalLines)         // set x position of left side of text
                    .attr("font-size", 15)
                    .attr("y", (DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1) + ( DistanceBetweenVPsForLeaf1 / 2))         // set y position of bottom of text
                    .text(DistanceBetweenVPsForLeaf1ToShow);   // define the text to display

                svg.append('line')
                    .style("stroke", "blue")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                    .attr("y1", DistanceYForLeaf1VPShape)
                    .attr("x2", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                    .attr("y2", DistanceYForLeaf1VPShape + Leaf1VisionPanel4Height)
                    .attr("marker-start","url(#verticalMarket)")
                    .attr("marker-end","url(#verticalMarket)");

                svg.append("text")            // append text
                    .style("fill", "blue")      // make the text black
                    .style("writing-mode", WritingMode) // set the writing mode
                    .attr("x", ix - 50 - SideLightPanel1WidthSpaceForVerticalLines)         // set x position of left side of text
                    .attr("font-size", 15)
                    .attr("y", DistanceYForLeaf1VPShape + (Leaf1VisionPanel4Height / 2))        // set y position of bottom of text
                    .text(Leaf1VisionPanel4HeightToShow);   // define the text to display

                /* Vertical Line for vision panel */
            }

        }

        if(VisionPanelQuantityForLeaf1 > 4){
            DistanceYForLeaf1VPShape = DistanceYForLeaf1VPShape
            + Leaf1VisionPanel4Height + DistanceBetweenVPsForLeaf1;

            if(VisionPanelShape == "Diamond"){
                svg.append('path')
                    .attr('d', DiamondDimension)
                    .attr("fill", "#aacbee")
                    .style("stroke", "black")
                    .attr("transform", "translate("+DistanceXForLeaf1VPShape+","+ DistanceYForLeaf1VPShape +")");
            }else if(VisionPanelShape == "Circle"){
                svg.append("circle")
                    .style("stroke", "black")
                    .style("fill", "#aacbee")
                    .attr("r", CircleRadius)
                    .attr("cx", DistanceXForLeaf1VPShape + CircleRadius)
                    .attr("cy", DistanceYForLeaf1VPShape + CircleRadius);
            }else{
                svg.append('rect')
                    .attr('x', DistanceXForLeaf1VPShape)
                    .attr('y', DistanceYForLeaf1VPShape)
                    .attr('width', parseFloat(Leaf1VisionPanelWidth))
                    .attr('height', parseFloat(Leaf1VisionPanel5Height))
                    .attr('stroke', 'black')
                    .attr('fill', '#aacbee');
            }

            if(ShowMeasurements){

                /* Vertical Line for vision panel */

                svg.append('line')
                    .style("stroke", "blue")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                    .attr("y1", (DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1))
                    .attr("x2", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                    .attr("y2", DistanceYForLeaf1VPShape)
                    .attr("marker-start","url(#verticalMarket)")
                    .attr("marker-end","url(#verticalMarket)");

                svg.append("text")            // append text
                    .style("fill", "blue")      // make the text black
                    .style("writing-mode", WritingMode) // set the writing mode
                    .attr("x", ix - 50 - SideLightPanel1WidthSpaceForVerticalLines)         // set x position of left side of text
                    .attr("font-size", 15)
                    .attr("y", (DistanceYForLeaf1VPShape - DistanceBetweenVPsForLeaf1) + ( DistanceBetweenVPsForLeaf1 / 2))         // set y position of bottom of text
                    .text(DistanceBetweenVPsForLeaf1ToShow);   // define the text to display

                svg.append('line')
                    .style("stroke", "blue")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                    .attr("y1", DistanceYForLeaf1VPShape)
                    .attr("x2", ix - 15 - SideLightPanel1WidthSpaceForVerticalLines)
                    .attr("y2", DistanceYForLeaf1VPShape + Leaf1VisionPanel5Height)
                    .attr("marker-start","url(#verticalMarket)")
                    .attr("marker-end","url(#verticalMarket)");

                svg.append("text")            // append text
                    .style("fill", "blue")      // make the text black
                    .style("writing-mode", WritingMode) // set the writing mode
                    .attr("x", ix - 50 - SideLightPanel1WidthSpaceForVerticalLines)         // set x position of left side of text
                    .attr("font-size", 15)
                    .attr("y", DistanceYForLeaf1VPShape + (Leaf1VisionPanel5Height / 2))        // set y position of bottom of text
                    .text(Leaf1VisionPanel5HeightToShow);   // define the text to display

                /* Vertical Line for vision panel */
            }

        }
    }

        if(IronmongerySet == "Yes" && IronmongeryID != ""){
            if(IsLeverHandlesEnable){

                var DistanceOfLeverHandlesFromBelow = 950 / 5;

                // svg.append("svg:image")
                //     .attr('x',  ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - 50))
                //     //.attr('y', iy + SOHeightForMap - handle)
                //     //.attr('y', iy + TopFrameHeight + GapForMap + ( LeafHeightNoOPForMap - handle ))
                //     .attr('y', iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - DistanceOfLeverHandlesFromBelow))
                //     .attr('width', 50)
                //     .attr('height', 50)
                //     //.attr("xlink:href", DoorUrl );
                //     .attr("xlink:href", "http://firedoor.workdemo.online/public/images/handle.png");

                svg.append("circle")
                    .style("stroke", "black")
                    .style("fill", "white")
                    .attr("r", 8)
                    .attr("cx", ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - 12))
                    .attr("cy", iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - DistanceOfLeverHandlesFromBelow));

                svg.append("rect")
                    .attr('x',  ix + FrameThicknessForMap + GapForMap + (LeafWidth1ForMap - 45))
                    .attr('y', iy + TopFrameHeight + GapForMap + (LeafHeightNoOPForMap - (DistanceOfLeverHandlesFromBelow + 4)))
                    .attr("width", 35)
                    .attr("height", 8)
                    .attr("rx", 4)
                    .style("fill", 'white')
                    .style("stroke", 'black');
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

    if(DoorSetType == "DD") {

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

                if($.inArray( ChangedFieldName, ["leaf2VisionPanel","vpSameAsLeaf1","visionPanelQuantityforLeaf2"] ) !== -1){

                    $('input[name="distanceFromTopOfDoorforLeaf2"]').val(DistanceFromTopOfDoorMinValueforLeaf2);
                    $('input[name="distanceFromTheEdgeOfDoorforLeaf2"]').val(DistanceFromTheEdgeOfDoorMinValueforLeaf2);
                }else{
                    DistanceFromTopOfDoorForLeaf2 = $('input[name="distanceFromTopOfDoorforLeaf2"]').val();
                    if(DistanceFromTopOfDoorForLeaf2 == "" || parseFloat(DistanceFromTopOfDoorForLeaf2) < DistanceFromTopOfDoorMinValueforLeaf2){
                        DistanceFromTopOfDoorForLeaf2 = DistanceFromTopOfDoorMinValueforLeaf2;
                    }
                    DistanceFromTheEdgeOfDoorForLeaf2 = $('input[name="distanceFromTheEdgeOfDoorforLeaf2"]').val();
                    if(DistanceFromTheEdgeOfDoorForLeaf2 == "" || parseFloat(DistanceFromTheEdgeOfDoorForLeaf2) < DistanceFromTheEdgeOfDoorMinValueforLeaf2){
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

                if(VisionPanelShape == "Rectangle"){

                    if(AreVPsEqualSizesForLeaf2 == "No"){
                        $('input[name="vP2Height1"]').removeAttr("readonly");
                        if(VisionPanelQuantityForLeaf2 > 1){
                            $('input[name="vP2Height2"]').removeAttr("readonly");
                        }
                        if(VisionPanelQuantityForLeaf2 > 2){
                            $('input[name="vP2Height3"]').removeAttr("readonly");
                        }
                        if(VisionPanelQuantityForLeaf2 > 3){
                            $('input[name="vP2Height4"]').removeAttr("readonly");
                        }
                        if(VisionPanelQuantityForLeaf2 > 4){
                            $('input[name="vP2Height5"]').removeAttr("readonly");
                        }
                    }
                }else{
                    $('input[name="vP2Height1"]').attr("readonly",true);
                    if(VisionPanelQuantityForLeaf2 > 1){
                        $('input[name="vP2Height2"]').attr("readonly",true);
                    }
                    if(VisionPanelQuantityForLeaf2 > 2){
                        $('input[name="vP2Height3"]').attr("readonly",true);
                    }
                    if(VisionPanelQuantityForLeaf2 > 3){
                        $('input[name="vP2Height4"]').attr("readonly",true);
                    }
                    if(VisionPanelQuantityForLeaf2 > 4){
                        $('input[name="vP2Height5"]').attr("readonly",true);
                    }
                }


                    if(ChangedFieldName == "visionPanelQuantityforLeaf2" && VisionPanelQuantityForLeaf2 > 1){
                        $('input[name="distanceBetweenVPsforLeaf2"]').val(DistanceBetweenVPsMinValueforLeaf2);
                    }else{
                        if(VisionPanelQuantityForLeaf2 < 2){
                            DistanceBetweenVPsForLeaf2 = 0;
                        }else{
                            DistanceBetweenVPsForLeaf2 = $('input[name="distanceBetweenVPsforLeaf2"]').val();
                            if(DistanceBetweenVPsForLeaf2 == "" || parseFloat(DistanceBetweenVPsForLeaf2) < DistanceBetweenVPsMinValueforLeaf2){
                                DistanceBetweenVPsForLeaf2 = DistanceBetweenVPsMinValueforLeaf2;
                            }
                        }
                    }

                    if(ChangedFieldName == "distanceBetweenVPsforLeaf2"){
                        DistanceBetweenVPsForLeaf2 = $('input[name="distanceBetweenVPsforLeaf2"]').val();
                        if(DistanceBetweenVPsForLeaf2 == "" || parseFloat(DistanceBetweenVPsForLeaf2) < DistanceBetweenVPsMinValueforLeaf2){
                            DistanceBetweenVPsForLeaf2 = DistanceBetweenVPsMinValueforLeaf2;
                        }
                    }

                DistanceBetweenVPsForLeaf2ToShow = 0;
                if(DistanceBetweenVPsForLeaf2 != "" && DistanceBetweenVPsForLeaf2 > 0){
                    DistanceBetweenVPsForLeaf2ToShow = parseFloat(DistanceBetweenVPsForLeaf2);
                    DistanceBetweenVPsForLeaf2 = parseFloat(DistanceBetweenVPsForLeaf2) / 5;
                }

                    if(ChangedFieldName == "distanceFromTopOfDoorforLeaf2"){
                        DistanceFromTopOfDoorForLeaf2 = $('input[name="distanceFromTopOfDoorforLeaf2"]').val();
                        if(DistanceFromTopOfDoorForLeaf2 == "" || parseFloat(DistanceFromTopOfDoorForLeaf2) < DistanceFromTopOfDoorMinValueforLeaf2){
                            DistanceFromTopOfDoorForLeaf2 = DistanceFromTopOfDoorMinValueforLeaf2;
                        }
                    }

                DistanceFromTopOfDoorForLeaf2ToShow = 0;
                if(DistanceFromTopOfDoorForLeaf2 != "" && DistanceFromTopOfDoorForLeaf2 > 0){
                    DistanceFromTopOfDoorForLeaf2ToShow = parseFloat(DistanceFromTopOfDoorForLeaf2);
                    if(DistanceFromTopOfDoorForLeaf2 > 0){
                        DistanceFromTopOfDoorForLeaf2 = parseFloat(DistanceFromTopOfDoorForLeaf2) / 5;
                    }
                }

                    if(ChangedFieldName == "distanceFromTheEdgeOfDoorforLeaf2"){
                        DistanceFromTheEdgeOfDoorForLeaf2 = $('input[name="distanceFromTheEdgeOfDoorforLeaf2"]').val();
                        if(DistanceFromTheEdgeOfDoorForLeaf2 == "" || parseFloat(DistanceFromTheEdgeOfDoorForLeaf2) < DistanceFromTheEdgeOfDoorMinValueforLeaf2){
                            DistanceFromTheEdgeOfDoorForLeaf2 = DistanceFromTheEdgeOfDoorMinValueforLeaf2;
                        }
                    }

                DistanceFromTheEdgeOfDoorForLeaf2ToShow = 0;
                if(DistanceFromTheEdgeOfDoorForLeaf2 != "" && DistanceFromTheEdgeOfDoorForLeaf2 > 0){
                    DistanceFromTheEdgeOfDoorForLeaf2ToShow = parseFloat(DistanceFromTheEdgeOfDoorForLeaf2);
                    if(DistanceFromTheEdgeOfDoorForLeaf2 > 0){
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

            if($.inArray( VisionPanelShape, ["Diamond","Circle","Square"] ) !== -1){

                $('select[name="AreVPsEqualSizesForLeaf2"]').attr("readonly",true);
                $('select[name="AreVPsEqualSizesForLeaf2"]').val("Yes");

                AreVPsEqualSizesForLeaf2 = "Yes";

                $('input[name="vP2Height1"]').attr("readonly",true);
                $('input[name="vP2Height2"]').attr("readonly",true);
                $('input[name="vP2Height3"]').attr("readonly",true);
                $('input[name="vP2Height4"]').attr("readonly",true);
                $('input[name="vP2Height5"]').attr("readonly",true);

                $('input[name="vP2Height1"]').val(Leaf2VisionPanelWidthToShow);
                Leaf2VisionPanel1Height = Leaf2VisionPanelWidth;
                Leaf2VisionPanel1HeightToShow = Leaf2VisionPanelWidthToShow;

                if(VisionPanelQuantityForLeaf2 > 1){
                    $('input[name="vP2Height2"]').val(Leaf2VisionPanelWidthToShow);
                    Leaf2VisionPanel2Height = Leaf2VisionPanelWidth;
                    Leaf2VisionPanel2HeightToShow = Leaf2VisionPanelWidthToShow;
                }

                if(VisionPanelQuantityForLeaf2 > 2){
                    $('input[name="vP2Height3"]').val(Leaf2VisionPanelWidthToShow);
                    Leaf2VisionPanel3Height = Leaf2VisionPanelWidth;
                    Leaf2VisionPanel3HeightToShow = Leaf2VisionPanelWidthToShow;
                }

                if(VisionPanelQuantityForLeaf2 > 3){
                    $('input[name="vP2Height4"]').val(Leaf2VisionPanelWidthToShow);
                    Leaf2VisionPanel4Height = Leaf2VisionPanelWidth;
                    Leaf2VisionPanel4HeightToShow = Leaf2VisionPanelWidthToShow;
                }

                if(VisionPanelQuantityForLeaf2 > 4){
                    $('input[name="vP2Height5"]').val(Leaf2VisionPanelWidthToShow);
                    Leaf2VisionPanel5Height = Leaf2VisionPanelWidth;
                    Leaf2VisionPanel5HeightToShow = Leaf2VisionPanelWidthToShow;
                }

            }else{

                $('select[name="AreVPsEqualSizesForLeaf2"]').removeAttr("readonly");

                if(AreVPsEqualSizesForLeaf2 == "No"){

                    $('input[name="vP2Height1"]').removeAttr("readonly");

                    if(VisionPanelQuantityForLeaf2 > 1){
                        $('input[name="vP2Height2"]').removeAttr("readonly");
                    }

                    if(VisionPanelQuantityForLeaf2 > 2){
                        $('input[name="vP2Height3"]').removeAttr("readonly");
                    }

                    if(VisionPanelQuantityForLeaf2 > 3){
                        $('input[name="vP2Height4"]').removeAttr("readonly");
                    }

                    if(VisionPanelQuantityForLeaf2 > 4){
                        $('input[name="vP2Height5"]').removeAttr("readonly");
                    }

                }

            }


            var RemainingWidthOfVisionPanelForRightLeaf = 0;
            var TotalHeightOfVisionPanelForRightLeaf = 0;

            RemainingWidthOfVisionPanelForRightLeaf = LeafWidth2 - (parseFloat(DistanceFromTheEdgeOfDoorForLeaf2ToShow) + parseFloat(Leaf2VisionPanelWidthToShow));
            TotalHeightOfVisionPanelForRightLeaf = parseFloat(DistanceFromTopOfDoorForLeaf2) + parseFloat(Leaf2VisionPanel1Height);

            if(VisionPanelQuantityForLeaf2 > 1){
                TotalHeightOfVisionPanelForRightLeaf =  parseFloat(TotalHeightOfVisionPanelForRightLeaf) + parseFloat(DistanceBetweenVPsForLeaf2) + parseFloat(Leaf2VisionPanel2Height);
            }

            if(VisionPanelQuantityForLeaf2 > 2){
                TotalHeightOfVisionPanelForRightLeaf =  parseFloat(TotalHeightOfVisionPanelForRightLeaf) + parseFloat(DistanceBetweenVPsForLeaf2) + parseFloat(Leaf2VisionPanel3Height);
            }

            if(VisionPanelQuantityForLeaf2 > 3){
                TotalHeightOfVisionPanelForRightLeaf =  parseFloat(TotalHeightOfVisionPanelForRightLeaf) + parseFloat(DistanceBetweenVPsForLeaf2) + parseFloat(Leaf2VisionPanel4Height);
            }

            if(VisionPanelQuantityForLeaf2 > 4){
                TotalHeightOfVisionPanelForRightLeaf =  parseFloat(TotalHeightOfVisionPanelForRightLeaf) + parseFloat(DistanceBetweenVPsForLeaf2) + parseFloat(Leaf2VisionPanel5Height);
            }

            console.log(RemainingWidthOfVisionPanelForRightLeaf+"--"+DistanceFromTheEdgeOfDoorMinValueforLeaf2);

            if(RemainingWidthOfVisionPanelForRightLeaf < DistanceFromTheEdgeOfDoorMinValueforLeaf2){


                if(VisionPanelShape == "Rectangle"){
                    DistanceFromTheEdgeOfDoorForLeaf2ToShow = parseFloat(LeafWidth2) - (parseFloat(DistanceFromTheEdgeOfDoorMinValueforLeaf2) + parseFloat(Leaf2VisionPanelWidthToShow));
                    DistanceFromTheEdgeOfDoorForLeaf2 = DistanceFromTheEdgeOfDoorForLeaf2ToShow/5;

                    $('input[name="distanceFromTheEdgeOfDoorforLeaf2"]').val(DistanceFromTheEdgeOfDoorForLeaf2ToShow);

                    swal('Warning!',"Distance of vision panel from right edge should be atleast " + DistanceFromTheEdgeOfDoorMinValueforLeaf2 + "mm");
                }


                if($.inArray( VisionPanelShape, ["Diamond","Circle","Square"] ) !== -1){

                    DistanceFromTheEdgeOfDoorForLeaf2ToShow = DistanceFromTheEdgeOfDoorMinValueforLeaf2;
                    DistanceFromTheEdgeOfDoorForLeaf2 = DistanceFromTheEdgeOfDoorMinValueforLeaf2/5;

                    $('input[name="distanceFromTheEdgeOfDoorforLeaf2"]').val(DistanceFromTheEdgeOfDoorMinValueforLeaf2);

                    // var NewLeaf2VisionPanelWidth = LeafWidth2 - (parseFloat(DistanceFromTheEdgeOfDoorForLeaf2ToShow) * 2);
                    // Leaf2VisionPanelWidth = NewLeaf2VisionPanelWidth/5;
                    // Leaf2VisionPanelWidthToShow = NewLeaf2VisionPanelWidth;
                    // $("#vP2Width").val(NewLeaf2VisionPanelWidth);
                }else{
                    //swal('Warning!',"Distance of vision panel from left edge should not be less than the distance from right edge.");
                }
            }

            if(TotalHeightOfVisionPanelForRightLeaf >= RemainingHeightOfLeaf){

                DistanceFromTopOfDoorForLeaf2ToShow = DistanceFromTopOfDoorMinValueforLeaf2;
                DistanceFromTopOfDoorForLeaf2 = DistanceFromTopOfDoorMinValueforLeaf2/5;

                $('input[name="distanceFromTopOfDoorforLeaf2"]').val(DistanceFromTopOfDoorMinValueforLeaf2);

                DistanceBetweenVPsForLeaf2ToShow = DistanceBetweenVPsMinValueforLeaf2;
                DistanceBetweenVPsForLeaf2 = DistanceBetweenVPsMinValueforLeaf2/5;

                $('input[name="distanceBetweenVPsforLeaf2"]').val(DistanceBetweenVPsMinValueforLeaf2);

                var NumberOfVps = VisionPanelQuantityForLeaf2 - 1;

                var NewLeaf2VisionPanelHeight = (LeafHeightNoOP - ((KickPlateHeight*5) + 3)) - ((parseFloat(DistanceFromTheEdgeOfDoorForLeaf2ToShow) * 2) + (parseFloat(DistanceBetweenVPsForLeaf2ToShow) * NumberOfVps));

                NewLeaf2VisionPanelHeight = Math.floor(NewLeaf2VisionPanelHeight/VisionPanelQuantityForLeaf2);

                if(KickPlateHeight > 0){
                    NewLeaf2VisionPanelHeight = NewLeaf2VisionPanelHeight - 3;
                }

                if($.inArray( VisionPanelShape, ["Diamond","Circle","Square"] ) !== -1){

                    if(NewLeaf2VisionPanelHeight > (LeafWidth2 - (parseFloat(DistanceFromTheEdgeOfDoorForLeaf2ToShow) * 2))){

                        NewLeaf2VisionPanelHeight = LeafWidth2 - (parseFloat(DistanceFromTheEdgeOfDoorForLeaf2ToShow) * 2);

                        // NewLeaf2VisionPanelHeight = Math.floor(NewLeaf2VisionPanelHeight/VisionPanelQuantityForLeaf2);

                        if(KickPlateHeight > 0){
                            NewLeaf2VisionPanelHeight = NewLeaf2VisionPanelHeight - 3;
                        }
                    }

                    Leaf2VisionPanelWidth = NewLeaf2VisionPanelHeight/5;
                    Leaf2VisionPanelWidthToShow = NewLeaf2VisionPanelHeight;
                    $("#vP2Width").val(NewLeaf2VisionPanelHeight);
                }

                Leaf2VisionPanel1Height = NewLeaf2VisionPanelHeight/5;
                Leaf2VisionPanel1HeightToShow = NewLeaf2VisionPanelHeight;
                $("#vP2Height1").val(NewLeaf2VisionPanelHeight);

                if(VisionPanelQuantityForLeaf2 > 1){
                    Leaf2VisionPanel2Height = NewLeaf2VisionPanelHeight/5;
                    Leaf2VisionPanel2HeightToShow = NewLeaf2VisionPanelHeight;
                    $("#vP2Height2").val(NewLeaf2VisionPanelHeight);
                }

                if(VisionPanelQuantityForLeaf2 > 2){
                    Leaf2VisionPanel3Height = NewLeaf2VisionPanelHeight/5;
                    Leaf2VisionPanel3HeightToShow = NewLeaf2VisionPanelHeight;
                    $("#vP2Height3").val(NewLeaf2VisionPanelHeight);
                }

                if(VisionPanelQuantityForLeaf2 > 3){
                    Leaf2VisionPanel4Height = NewLeaf2VisionPanelHeight/5;
                    Leaf2VisionPanel4HeightToShow = NewLeaf2VisionPanelHeight;
                    $("#vP2Height4").val(NewLeaf2VisionPanelHeight);
                }

                if(VisionPanelQuantityForLeaf2 > 4){
                    Leaf2VisionPanel5Height = NewLeaf2VisionPanelHeight/5;
                    Leaf2VisionPanel5HeightToShow = NewLeaf2VisionPanelHeight;
                    $("#vP2Height5").val(NewLeaf2VisionPanelHeight);
                }

                if(ChangedFieldName == "distanceFromTheEdgeOfDoorforLeaf2"){
                    swal('Warning!',"Entered distance from the edge of door of right leaf exceeds the width of right leaf.");
                }else if(ChangedFieldName == "distanceFromTopOfDoorforLeaf2"){
                    swal('Warning!',"Entered distance from top of door of right leaf exceeds the hight of right leaf.");
                }else if(ChangedFieldName == "distanceBetweenVPsforLeaf2"){
                    swal('Warning!',"Entered distance between vps of right leaf exceeds the hight of right leaf.");
                }else{
                    swal('Warning!',"Sum of all vision panel's height should not be greater than right leaf's height.");
                }
            }

            // var TotalDistanceForLeaf2VisionPanel1 = ix + LeftGapForLeaf1 + LeafWidth1ForMap + (RemainedSpaceInLeaf1 / 5);
            var TotalDistanceForLeaf2VisionPanel1WithOutRemainingSpace = ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles;
            var TotalDistanceForLeaf2VisionPanel1 = ix + FrameThicknessForMap + GapForMap + LeafWidth1ForMap + MeetingStiles + DistanceFromTheEdgeOfDoorForLeaf2;


            var RightSideDistanceOfVisonPanelOfDoorLeaf2ToShow = LeafWidth2 - (parseFloat(DistanceFromTheEdgeOfDoorForLeaf2ToShow) + parseFloat(Leaf2VisionPanelWidthToShow));
            var RightSideDistanceOfVisonPanelOfDoorLeaf2 = (RightSideDistanceOfVisonPanelOfDoorLeaf2ToShow/5) - RemainingGap;

            var DistanceXForLeaf2VPShape = TotalDistanceForLeaf2VisionPanel1;
            var DistanceYForLeaf2VPShape = iy + GapAfterOverPanelApplied + UpperAndLowerGap + DistanceFromTopOfDoorForLeaf2;

            var CircleRadiusForLeaf2 = parseFloat(Leaf2VisionPanelWidth)/2;
            var DiamondDimensionForLeaf2 = 'M '+CircleRadiusForLeaf2+' 0 '+Leaf2VisionPanelWidth+' '+CircleRadiusForLeaf2+' '+CircleRadiusForLeaf2+' '+Leaf2VisionPanelWidth+' 0 '+CircleRadiusForLeaf2+' Z';

            if(VisionPanelShape == "Diamond"){
                svg.append('path')
                    .attr('d', DiamondDimensionForLeaf2)
                    .attr("fill", "#aacbee")
                    .style("stroke", "black")
                    .attr("transform", "translate("+DistanceXForLeaf2VPShape+","+DistanceYForLeaf2VPShape+")");
            }else if(VisionPanelShape == "Circle"){
                svg.append("circle")
                    .style("stroke", "black")
                    .style("fill", "#aacbee")
                    .attr("r", CircleRadiusForLeaf2)
                    .attr("cx", DistanceXForLeaf2VPShape + CircleRadiusForLeaf2)
                    .attr("cy", DistanceYForLeaf2VPShape + CircleRadiusForLeaf2);
            }else{
                svg.append('rect')
                    .attr('x', DistanceXForLeaf2VPShape)
                    .attr('y', DistanceYForLeaf2VPShape)
                    .attr('width', Leaf2VisionPanelWidth)
                    .attr('height', Leaf2VisionPanel1Height)
                    .attr('stroke', 'black')
                    .attr('fill', '#aacbee');
            }

            if(ShowMeasurements){

                /* Horizontal Line for vision panel */

                svg.append("text")            // append text
                    .style("fill", "blue")      // make the text black
                    .attr("font-size", 15)
                    .attr("x", TotalDistanceForLeaf2VisionPanel1WithOutRemainingSpace + (DistanceFromTheEdgeOfDoorForLeaf2 / 2 ))         // set x position of left side of text
                    .attr("y", iy - 40)         // set y position of bottom of text
                    .text(DistanceFromTheEdgeOfDoorForLeaf2ToShow);   // define the text to display

                svg.append('line')
                    .style("stroke", "blue")
                    .style("stroke-width", 0.5)
                    .attr("x1", TotalDistanceForLeaf2VisionPanel1WithOutRemainingSpace)
                    .attr("y1", iy - 35)
                    .attr("x2", DistanceXForLeaf2VPShape)
                    .attr("y2", iy - 35)
                    .attr("marker-start", "url(#verticalMarket)")
                    .attr("marker-end", "url(#verticalMarket)");

                svg.append("text")            // append text
                    .style("fill", "blue")      // make the text black
                    .attr("font-size", 15)
                    .attr("x", DistanceXForLeaf2VPShape + (Leaf2VisionPanelWidth / 2))         // set x position of left side of text
                    .attr("y", iy - 40)         // set y position of bottom of text
                    .text(Leaf2VisionPanelWidthToShow);   // define the text to display

                svg.append('line')
                    .style("stroke", "blue")
                    .style("stroke-width", 0.5)
                    .attr("x1", DistanceXForLeaf2VPShape)
                    .attr("y1", iy - 35)
                    .attr("x2", DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth)
                    .attr("y2", iy - 35)
                    .attr("marker-start", "url(#verticalMarket)")
                    .attr("marker-end", "url(#verticalMarket)");


                svg.append("text")            // append text
                    .style("fill", "blue")      // make the text black
                    .attr("font-size", 15)
                    .attr("x", DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth + (RightSideDistanceOfVisonPanelOfDoorLeaf2 / 2))         // set x position of left side of text
                    .attr("y", iy - 40)         // set y position of bottom of text
                    .text(RightSideDistanceOfVisonPanelOfDoorLeaf2ToShow);   // define the text to display

                svg.append('line')
                    .style("stroke", "blue")
                    .style("stroke-width", 0.5)
                    .attr("x1", DistanceXForLeaf2VPShape + Leaf2VisionPanelWidth)
                    .attr("y1", iy - 35)
                    .attr("x2", TotalDistanceForLeaf2VisionPanel1WithOutRemainingSpace + LeafWidth2ForMap)
                    .attr("y2", iy - 35)
                    .attr("marker-start", "url(#verticalMarket)")
                    .attr("marker-end", "url(#verticalMarket)");

                /* Horizontal Line for vision panel */

                /* Vertical Line for vision panel */

                svg.append('line')
                    .style("stroke", "blue")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + SOWidthForMap + 15 + SideLightPanel2WidthSpaceForVerticalLines)
                    .attr("y1", iy + GapAfterOverPanelApplied + UpperAndLowerGap)
                    .attr("x2", ix + SOWidthForMap + 15 + SideLightPanel2WidthSpaceForVerticalLines)
                    .attr("y2", DistanceYForLeaf2VPShape)
                    .attr("marker-start", "url(#verticalMarket)")
                    .attr("marker-end", "url(#verticalMarket)");

                svg.append("text")            // append text
                    .style("fill", "blue")      // make the text black
                    .style("writing-mode", WritingMode) // set the writing mode
                    .attr("x", SOWidthForMap + ix + 20 + SideLightPanel2WidthSpaceForVerticalLines)         // set x position of left side of text
                    .attr("font-size", 15)
                    .attr("y", (DistanceYForLeaf2VPShape - DistanceFromTopOfDoorForLeaf2) + (DistanceFromTopOfDoorForLeaf2 / 2))         // set y position of bottom of text
                    .text(DistanceFromTopOfDoorForLeaf2ToShow);   // define the text to display


                svg.append('line')
                    .style("stroke", "blue")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + SOWidthForMap + 15 + SideLightPanel2WidthSpaceForVerticalLines)
                    .attr("y1", DistanceYForLeaf2VPShape)
                    .attr("x2", ix + SOWidthForMap + 15 + SideLightPanel2WidthSpaceForVerticalLines)
                    .attr("y2", DistanceYForLeaf2VPShape + Leaf2VisionPanel1Height)
                    .attr("marker-start", "url(#verticalMarket)")
                    .attr("marker-end", "url(#verticalMarket)");

                svg.append("text")            // append text
                    .style("fill", "blue")      // make the text black
                    .style("writing-mode", WritingMode) // set the writing mode
                    .attr("x", SOWidthForMap + ix + 20 + SideLightPanel2WidthSpaceForVerticalLines)         // set x position of left side of text
                    .attr("font-size", 15)
                    .attr("y", DistanceYForLeaf2VPShape + ( Leaf2VisionPanel1Height / 2))         // set y position of bottom of text
                    .text(Leaf2VisionPanel1HeightToShow);   // define the text to display

                /* Vertical Line for vision panel */
            }

            if (VisionPanelQuantityForLeaf2 > 1) {

                DistanceYForLeaf2VPShape = DistanceYForLeaf2VPShape + Leaf2VisionPanel1Height + DistanceBetweenVPsForLeaf2;

                if(VisionPanelShape == "Diamond"){
                    svg.append('path')
                        .attr('d', DiamondDimensionForLeaf2)
                        .attr("fill", "#aacbee")
                        .style("stroke", "black")
                        .attr("transform", "translate("+DistanceXForLeaf2VPShape+","+ DistanceYForLeaf2VPShape +")");
                }else if(VisionPanelShape == "Circle"){
                    svg.append("circle")
                        .style("stroke", "black")
                        .style("fill", "#aacbee")
                        .attr("r", CircleRadiusForLeaf2)
                        .attr("cx", DistanceXForLeaf2VPShape + CircleRadiusForLeaf2)
                        .attr("cy", DistanceYForLeaf2VPShape + CircleRadiusForLeaf2);
                }else{
                    svg.append('rect')
                        .attr('x', DistanceXForLeaf2VPShape)
                        .attr('y', DistanceYForLeaf2VPShape)
                        .attr('width', Leaf2VisionPanelWidth)
                        .attr('height', Leaf2VisionPanel2Height)
                        .attr('stroke', 'black')
                        .attr('fill', '#aacbee');
                }

                if(ShowMeasurements){
                    /* Vertical Line for vision panel 2 of leaf 2  */

                    svg.append('line')
                        .style("stroke", "blue")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + SOWidthForMap + 15 + SideLightPanel2WidthSpaceForVerticalLines)
                        .attr("y1", (DistanceYForLeaf2VPShape - DistanceBetweenVPsForLeaf2))
                        .attr("x2", ix + SOWidthForMap + 15 + SideLightPanel2WidthSpaceForVerticalLines)
                        .attr("y2", DistanceYForLeaf2VPShape)
                        .attr("marker-start", "url(#verticalMarket)")
                        .attr("marker-end", "url(#verticalMarket)");

                    svg.append("text")            // append text
                        .style("fill", "blue")      // make the text black
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", SOWidthForMap + ix + 20 + SideLightPanel2WidthSpaceForVerticalLines)         // set x position of left side of text
                        .attr("font-size", 15)
                        .attr("y", (DistanceYForLeaf2VPShape - DistanceBetweenVPsForLeaf2) + ( DistanceBetweenVPsForLeaf2 / 2))         // set y position of bottom of text
                        .text(DistanceBetweenVPsForLeaf2ToShow);   // define the text to display

                    svg.append('line')
                        .style("stroke", "blue")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + SOWidthForMap + 15 + SideLightPanel2WidthSpaceForVerticalLines)
                        .attr("y1", DistanceYForLeaf2VPShape)
                        .attr("x2", ix + SOWidthForMap + 15 + SideLightPanel2WidthSpaceForVerticalLines)
                        .attr("y2", DistanceYForLeaf2VPShape + Leaf2VisionPanel2Height)
                        .attr("marker-start", "url(#verticalMarket)")
                        .attr("marker-end", "url(#verticalMarket)");

                    svg.append("text")            // append text
                        .style("fill", "blue")      // make the text black
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", SOWidthForMap + ix + 20 + SideLightPanel2WidthSpaceForVerticalLines)         // set x position of left side of text
                        .attr("font-size", 15)
                        .attr("y", DistanceYForLeaf2VPShape + ( Leaf2VisionPanel2Height / 2))         // set y position of bottom of text
                        .text(Leaf2VisionPanel2HeightToShow);   // define the text to display

                    /* Vertical Line for vision panel 2 of leaf 2  */
                }
            }

            if (VisionPanelQuantityForLeaf2 > 2) {

                DistanceYForLeaf2VPShape = DistanceYForLeaf2VPShape + Leaf2VisionPanel2Height + DistanceBetweenVPsForLeaf2;

                if(VisionPanelShape == "Diamond"){
                    svg.append('path')
                        .attr('d', DiamondDimensionForLeaf2)
                        .attr("fill", "#aacbee")
                        .style("stroke", "black")
                        .attr("transform", "translate("+DistanceXForLeaf2VPShape+","+ DistanceYForLeaf2VPShape +")");
                }else if(VisionPanelShape == "Circle"){
                    svg.append("circle")
                        .style("stroke", "black")
                        .style("fill", "#aacbee")
                        .attr("r", CircleRadiusForLeaf2)
                        .attr("cx", DistanceXForLeaf2VPShape + CircleRadiusForLeaf2)
                        .attr("cy", DistanceYForLeaf2VPShape + CircleRadiusForLeaf2);
                }else{
                    svg.append('rect')
                        .attr('x', DistanceXForLeaf2VPShape)
                        .attr('y', DistanceYForLeaf2VPShape)
                        .attr('width', Leaf2VisionPanelWidth)
                        .attr('height', Leaf2VisionPanel3Height)
                        .attr('stroke', 'black')
                        .attr('fill', '#aacbee');
                }

                if(ShowMeasurements){

                    /* Vertical Line for vision panel 2 of leaf 2  */

                    svg.append('line')
                        .style("stroke", "blue")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + SOWidthForMap + 15 + SideLightPanel2WidthSpaceForVerticalLines)
                        .attr("y1", (DistanceYForLeaf2VPShape - DistanceBetweenVPsForLeaf2))
                        .attr("x2", ix + SOWidthForMap + 15 + SideLightPanel2WidthSpaceForVerticalLines)
                        .attr("y2", DistanceYForLeaf2VPShape)
                        .attr("marker-start", "url(#verticalMarket)")
                        .attr("marker-end", "url(#verticalMarket)");

                    svg.append("text")            // append text
                        .style("fill", "blue")      // make the text black
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", SOWidthForMap + ix + 20 + SideLightPanel2WidthSpaceForVerticalLines)         // set x position of left side of text
                        .attr("font-size", 15)
                        .attr("y", (DistanceYForLeaf2VPShape - DistanceBetweenVPsForLeaf2) + ( DistanceBetweenVPsForLeaf2 / 2))         // set y position of bottom of text
                        .text(DistanceBetweenVPsForLeaf2ToShow);   // define the text to display

                    svg.append('line')
                        .style("stroke", "blue")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + SOWidthForMap + 15 + SideLightPanel2WidthSpaceForVerticalLines)
                        .attr("y1", DistanceYForLeaf2VPShape)
                        .attr("x2", ix + SOWidthForMap + 15 + SideLightPanel2WidthSpaceForVerticalLines)
                        .attr("y2", DistanceYForLeaf2VPShape + Leaf2VisionPanel3Height)
                        .attr("marker-start", "url(#verticalMarket)")
                        .attr("marker-end", "url(#verticalMarket)");

                    svg.append("text")            // append text
                        .style("fill", "blue")      // make the text black
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", SOWidthForMap + ix + 20 + SideLightPanel2WidthSpaceForVerticalLines)         // set x position of left side of text
                        .attr("font-size", 15)
                        .attr("y", DistanceYForLeaf2VPShape + ( Leaf2VisionPanel3Height / 2))         // set y position of bottom of text
                        .text(Leaf2VisionPanel3HeightToShow);   // define the text to display

                    /* Vertical Line for vision panel 2 of leaf 2  */
                }
            }

            if (VisionPanelQuantityForLeaf2 > 3) {

                DistanceYForLeaf2VPShape = DistanceYForLeaf2VPShape + Leaf2VisionPanel3Height + DistanceBetweenVPsForLeaf2;

                if(VisionPanelShape == "Diamond"){
                    svg.append('path')
                        .attr('d', DiamondDimensionForLeaf2)
                        .attr("fill", "#aacbee")
                        .style("stroke", "black")
                        .attr("transform", "translate("+DistanceXForLeaf2VPShape+","+ DistanceYForLeaf2VPShape +")");
                }else if(VisionPanelShape == "Circle"){
                    svg.append("circle")
                        .style("stroke", "black")
                        .style("fill", "#aacbee")
                        .attr("r", CircleRadiusForLeaf2)
                        .attr("cx", DistanceXForLeaf2VPShape + CircleRadiusForLeaf2)
                        .attr("cy", DistanceYForLeaf2VPShape + CircleRadiusForLeaf2);
                }else{
                    svg.append('rect')
                        .attr('x', DistanceXForLeaf2VPShape)
                        .attr('y', DistanceYForLeaf2VPShape)
                        .attr('width', Leaf2VisionPanelWidth)
                        .attr('height', Leaf2VisionPanel4Height)
                        .attr('stroke', 'black')
                        .attr('fill', '#aacbee');
                }

                if(ShowMeasurements){

                    /* Vertical Line for vision panel 2 of leaf 2  */

                    svg.append('line')
                        .style("stroke", "blue")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + SOWidthForMap + 15 + SideLightPanel2WidthSpaceForVerticalLines)
                        .attr("y1", DistanceYForLeaf2VPShape - DistanceBetweenVPsForLeaf2)
                        .attr("x2", ix + SOWidthForMap + 15 + SideLightPanel2WidthSpaceForVerticalLines)
                        .attr("y2", DistanceYForLeaf2VPShape)
                        .attr("marker-start", "url(#verticalMarket)")
                        .attr("marker-end", "url(#verticalMarket)");

                    svg.append("text")            // append text
                        .style("fill", "blue")      // make the text black
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", SOWidthForMap + ix + 20 + SideLightPanel2WidthSpaceForVerticalLines)         // set x position of left side of text
                        .attr("font-size", 15)
                        .attr("y", (DistanceYForLeaf2VPShape - DistanceBetweenVPsForLeaf2) + ( DistanceBetweenVPsForLeaf2 / 2))         // set y position of bottom of text
                        .text(DistanceBetweenVPsForLeaf2ToShow);   // define the text to display

                    svg.append('line')
                        .style("stroke", "blue")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + SOWidthForMap + 15 + SideLightPanel2WidthSpaceForVerticalLines)
                        .attr("y1", DistanceYForLeaf2VPShape)
                        .attr("x2", ix + SOWidthForMap + 15 + SideLightPanel2WidthSpaceForVerticalLines)
                        .attr("y2", DistanceYForLeaf2VPShape + Leaf2VisionPanel4Height)
                        .attr("marker-start", "url(#verticalMarket)")
                        .attr("marker-end", "url(#verticalMarket)");

                    svg.append("text")            // append text
                        .style("fill", "blue")      // make the text black
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", SOWidthForMap + ix + 20 + SideLightPanel2WidthSpaceForVerticalLines)         // set x position of left side of text
                        .attr("font-size", 15)
                        .attr("y", DistanceYForLeaf2VPShape + ( Leaf2VisionPanel4Height / 2))         // set y position of bottom of text
                        .text(Leaf2VisionPanel4HeightToShow);   // define the text to display

                    /* Vertical Line for vision panel 2 of leaf 2  */
                }
            }

            if (VisionPanelQuantityForLeaf2 > 4) {

                DistanceYForLeaf2VPShape = DistanceYForLeaf2VPShape + Leaf2VisionPanel4Height + DistanceBetweenVPsForLeaf2;

                if(VisionPanelShape == "Diamond"){
                    svg.append('path')
                        .attr('d', DiamondDimensionForLeaf2)
                        .attr("fill", "#aacbee")
                        .style("stroke", "black")
                        .attr("transform", "translate("+DistanceXForLeaf2VPShape+","+ DistanceYForLeaf2VPShape +")");
                }else if(VisionPanelShape == "Circle"){
                    svg.append("circle")
                        .style("stroke", "black")
                        .style("fill", "#aacbee")
                        .attr("r", CircleRadiusForLeaf2)
                        .attr("cx", DistanceXForLeaf2VPShape + CircleRadiusForLeaf2)
                        .attr("cy", DistanceYForLeaf2VPShape + CircleRadiusForLeaf2);
                }else{
                    svg.append('rect')
                        .attr('x', DistanceXForLeaf2VPShape)
                        .attr('y', DistanceYForLeaf2VPShape)
                        .attr('width', Leaf2VisionPanelWidth)
                        .attr('height', Leaf2VisionPanel5Height)
                        .attr('stroke', 'black')
                        .attr('fill', '#aacbee');
                }

                if(ShowMeasurements){
                    /* Vertical Line for vision panel 2 of leaf 2  */

                    svg.append('line')
                        .style("stroke", "blue")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + SOWidthForMap + 15 + SideLightPanel2WidthSpaceForVerticalLines)
                        .attr("y1", DistanceYForLeaf2VPShape - DistanceBetweenVPsForLeaf2)
                        .attr("x2", ix + SOWidthForMap + 15 + SideLightPanel2WidthSpaceForVerticalLines)
                        .attr("y2", DistanceYForLeaf2VPShape)
                        .attr("marker-start", "url(#verticalMarket)")
                        .attr("marker-end", "url(#verticalMarket)");

                    svg.append("text")            // append text
                        .style("fill", "blue")      // make the text black
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", SOWidthForMap + ix + 20 + SideLightPanel2WidthSpaceForVerticalLines)         // set x position of left side of text
                        .attr("font-size", 15)
                        .attr("y", (DistanceYForLeaf2VPShape - DistanceBetweenVPsForLeaf2) + ( DistanceBetweenVPsForLeaf2 / 2))         // set y position of bottom of text
                        .text(DistanceBetweenVPsForLeaf2ToShow);   // define the text to display

                    svg.append('line')
                        .style("stroke", "blue")
                        .style("stroke-width", 0.5)
                        .attr("x1", ix + SOWidthForMap + 15 + SideLightPanel2WidthSpaceForVerticalLines)
                        .attr("y1", DistanceYForLeaf2VPShape)
                        .attr("x2", ix + SOWidthForMap + 15 + SideLightPanel2WidthSpaceForVerticalLines)
                        .attr("y2", DistanceYForLeaf2VPShape + Leaf2VisionPanel5Height)
                        .attr("marker-start", "url(#verticalMarket)")
                        .attr("marker-end", "url(#verticalMarket)");

                    svg.append("text")            // append text
                        .style("fill", "blue")      // make the text black
                        .style("writing-mode", WritingMode) // set the writing mode
                        .attr("x", SOWidthForMap + ix + 20 + SideLightPanel2WidthSpaceForVerticalLines)         // set x position of left side of text
                        .attr("font-size", 15)
                        .attr("y", DistanceYForLeaf2VPShape + ( Leaf2VisionPanel5Height / 2))         // set y position of bottom of text
                        .text(Leaf2VisionPanel5HeightToShow);   // define the text to display

                    /* Vertical Line for vision panel 2 of leaf 2  */
                }
            }
        }
    }

        if(ShowMeasurements){

            var x1 = ix;
            var FrameSection = FrameThicknessForMap + GapForMap + LeafWidth1ForMap + GapForMap + FrameThicknessForMap;
            var x2 = ix + FrameSection;
            var TotalWidth = (FrameThickness * 2) + (Gap * 2) + LeafWidth1;

            if(DoorSetType == "DD"){

                FrameSection = FrameThicknessForMap
                    + GapForMap + LeafWidth1ForMap
                    + MeetingStiles + LeafWidth2ForMap
                    + GapForMap + FrameThicknessForMap;

                x2 = ix + FrameSection;

                TotalWidth = TotalWidth + Gap + LeafWidth2;
            }

            if(SideLightPanel1 == "Yes"){
                x1 = ix - SideLightPanel1Width;
                FrameSection = FrameSection + SideLightPanel1Width;
                x2 = x1 + FrameSection;
                TotalWidth = TotalWidth + SideLightPanel1WidthToShow;
            }

            if(SideLightPanel2 == "Yes"){
                FrameSection = FrameSection + SideLightPanel2Width;
                x2 = x2 + SideLightPanel2Width;
                TotalWidth = TotalWidth + SideLightPanel2WidthToShow;
            }

            svg.append('line')
                .style("stroke", "blue")
                .style("stroke-width", 0.5)
                .attr("x1", x1)
                .attr("y1", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 50 )
                .attr("x2", x2)
                .attr("y2", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 50 )
                .attr("marker-start","url(#verticalMarket)")
                .attr("marker-end","url(#verticalMarket)");

            svg.append("text")            // append text
                .style("fill", "blue")      // make the text black
                .attr("font-size", 15)
                .attr("x", x1 + (FrameSection/2))         // set x position of left side of text
                .attr("y", iy + TopFrameHeight + (( FrameHeight - FrameThickness ) / 5) + 70 )
                .text(TotalWidth);   // define the text to display


            if(IsOverPanelActive != "" && IsOverPanelActive != "No"){

                svg.append('line')
                    .style("stroke", "blue")
                    .style("stroke-width", 0.5)
                    .attr("x1", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 127)
                    .attr("y1", iy + (TopFrameHeight - ((FrameThicknessForMap*2) + OverPanelHeight)))
                    .attr("x2", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 127)
                    .attr("y2", iy + FrameThicknessForMap + FrameHeightForMap + OverPanelHeight)
                    .attr("marker-start","url(#verticalMarket)")
                    .attr("marker-end","url(#verticalMarket)");

                svg.append("text")            // append text
                    .style("fill", "blue")      // make the text black
                    .attr("x", ix + FrameWidthForMap + SideLightPanel2WidthSpaceForVerticalLines + 128)         // set x position of left side of text
                    .attr("font-size", 15)
                    .attr("y", iy + (TopFrameHeight - FrameThicknessForMap) + (FrameHeightForMap / 2))         // set y position of bottom of text
                    .text(FrameHeight + OverPanelHeightToShow + FrameThickness);   // define the text to display
            }
        }

        var svgData = d3.select('svg').node();
        //get svg source.
        var serializer = new XMLSerializer();
        var source = serializer.serializeToString(svgData);

//add name spaces.
        if(!source.match(/^<svg[^>]+xmlns="http\:\/\/www\.w3\.org\/2000\/svg"/)){
            source = source.replace(/^<svg/, '<svg xmlns="http://www.w3.org/2000/svg"');
        }
        if(!source.match(/^<svg[^>]+"http\:\/\/www\.w3\.org\/1999\/xlink"/)){
            source = source.replace(/^<svg/, '<svg xmlns:xlink="http://www.w3.org/1999/xlink"');
        }

//add xml declaration
        source = '<?xml version="1.0" standalone="no"?>\r\n' + source;

//convert svg source to URI data scheme.
//        var image = "data:image/svg+xml;charset=utf-8,"+encodeURIComponent(source);

        var encodedData = "data:image/svg+xml;base64," + window.btoa(source);

        run(encodedData);

        //$('input[name="SvgImage"]').val(encodedData);

    }


}



// ------------------------


//$( ".door-configuration" ).change( function( event ) {
$( ".form-control" ).change( function( event ) {
    var element = $(this);
    render(element);
});

$("#change-dimension").change( function( event ) {
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
    if(activeArrow){
        activeArrow.classList.add('activo');
        activeArrow.classList.remove('activo');
    }
});

// ? ----- ----- Left arrow Event Listener ----- -----
arrowLeft.addEventListener('click', () => {
    optionRow.scrollLeft -= optionRow.offsetWidth;

    const activeArrow = document.querySelector('.indicadores .activo');
    if(activeArrow){
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
