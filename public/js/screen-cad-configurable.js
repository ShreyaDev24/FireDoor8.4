// alert('dsh')
d3.select("button#save").on("click", function () {
    var config = {
        filename: "customFileName",
    };
    d3_save_svg.save(d3.select("svg").node(), config);
});

$(document).ready(function () {
    var element = $(this);
    render(element);
});

var options = {
    events: {
        mouseWheel: true, // enables mouse wheel zooming events
        doubleClick: true, // enables double-click to zoom-in events
        drag: true, // enables drag and drop to move the SVG events
        dragCursor: "move", // cursor to use while dragging the SVG
    },
    animationTime: 300, // time in milliseconds to use as default for animations. Set 0 to remove the animation
    zoomFactor: 0.25, // how much to zoom-in or zoom-out
    maxZoom: 3, //maximum zoom in, must be a number bigger than 1
    panFactor: 100, // how much to move the viewBox when calling .panDirection() methods
    initialViewBox: {
        // the initial viewBox, if null or undefined will try to use the viewBox set in the svg tag. Also accepts string in the format "X Y Width Height"
        x: 0, // the top-left corner X coordinate
        y: 0, // the top-left corner Y coordinate
        width: 780, // the width of the viewBox
        height: 780, // the height of the viewBox
    },
    limits: {
        // the limits in which the image can be moved. If null or undefined will use the initialViewBox plus 15% in each direction
        x: -150,
        y: -150,
        x2: 1150,
        y2: 1150,
    },
};
// create svg element:
var svg = d3
    .select("#container")
    .append("svg")
    .attr("preserveAspectRatio", "xMinYMin meet")
    .attr("viewBox", "0 0 780 1000")
    // .attr("height", "auto")
    .classed("svg-content", true);

//var svgPanZoom= $("svg").svgPanZoom(options);

var DoorUrl = $("#door_url").text();
const render = (CustomElement = null) => {
    const container = d3.select("#container");
    container.html("");
    var svg = container
        .append("svg")
        .attr("preserveAspectRatio", "xMinYMin meet")
        .attr("viewBox", "0 0 780 1000")
        // .attr("height", "auto")
        .classed("svg-content", true);

    svg.append("svg:defs")
        .append("svg:marker")
        .attr("id", "arrowRight")
        .attr("refX", 5) // Position adjusted for smaller arrow size
        .attr("refY", 1.5) // Centered vertically for smaller arrow
        .attr("markerWidth", 5) // Decreased width for a smaller arrow
        .attr("markerHeight", 3) // Decreased height for a smaller arrow
        .attr("markerUnits", "userSpaceOnUse")
        .attr("orient", "auto")
        .append("path")
        .attr("d", "M 0 0 L 6 1.5 L 0 3 Z") // Adjusted path for a smaller arrowhead
        .style("fill", "black");

    // Define the arrow marker for the left-facing arrow
    svg.append("svg:defs")
        .append("svg:marker")
        .attr("id", "arrowLeft")
        .attr("refX", 4.5) // Position closer to the middle of the smaller arrow
        .attr("refY", 1.5) // Centered vertically for smaller arrow
        .attr("markerWidth", 5) // Adjusted width for smaller arrow size
        .attr("markerHeight", 3) // Adjusted height for smaller arrow
        .attr("markerUnits", "userSpaceOnUse")
        .attr("orient", "auto-start-reverse")
        .append("path")
        .attr("d", "M 0 0 L 5 1.5 L 0 3 Z") // Adjusted path for a smaller arrowhead
        .style("fill", "black");

    var ix = 200,
        iy = 150;
    var FrameWidth = $('input[name="FrameWidth"]').val();
    var FrameHeight = $('input[name="FrameHeight"]').val();
    var FrameThickness = $('input[name="FrameThickness"]').val();


    if (FrameThickness > 0) {
        svg.append("rect")
            .attr("x", ix - FrameThickness / 5)
            .attr("y", iy - FrameThickness / 5)
            .attr("width", FrameWidth / 5 + 2 * (FrameThickness / 5))
            .attr("height", FrameThickness / 5)
            .attr("stroke", "black")
            .attr("fill", "#D0D0C6");

        svg.append("rect")
            .attr("x", ix - FrameThickness / 5)
            .attr("y", iy + FrameHeight / 5)
            .attr("width", FrameWidth / 5 + 2 * (FrameThickness / 5))
            .attr("height", FrameThickness / 5)
            .attr("stroke", "black")
            .attr("fill", "#D0D0C6");

        svg.append("rect")
            .attr("x", ix - FrameThickness / 5)
            .attr("y", iy)
            .attr("width", FrameThickness / 5)
            .attr("height", FrameHeight / 5)
            .attr("stroke", "black")
            .attr("fill", "#D0D0C6");

        svg.append("rect")
            .attr("x", ix + FrameWidth / 5)
            .attr("y", iy)
            .attr("width", FrameThickness / 5)
            .attr("height", FrameHeight / 5)
            .attr("stroke", "black")
            .attr("fill", "#D0D0C6");

        //frame thickness
        svg.append("line") //(Transom 2 thickness)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", ix)
            .attr("y1", iy + FrameHeight / 5 + FrameThickness / 5 + 30)
            .attr("x2", ix - FrameThickness / 5)
            .attr("y2", iy + FrameHeight / 5 + FrameThickness / 5 + 30)
            .attr("marker-start", "url(#arrowLeft)")
            .attr("marker-end", "url(#arrowRight)");

        svg.append("text") // (Transom 2 thickness)
            .style("fill", "black")
            .attr("font-size", 10)
            .attr("x", (ix + ix - FrameThickness / 5) / 2)
            .attr("y", iy + FrameHeight / 5 + FrameThickness / 5 + 30 + 20)
            .attr(
                "transform",
                `rotate(-90, ${(ix + ix - FrameThickness / 5) / 2}, ${
                    iy + FrameHeight / 5 + FrameThickness / 5 + 30 + 20
                })`
            ) // Rotate 45 degrees
            .text(FrameThickness);

        svg.append("line") //(Transom 2)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", ix)
            .attr("y1", iy + FrameHeight / 5 + FrameThickness / 5 + 30 - 30)
            .attr("x2", ix)
            .attr("y2", iy + FrameHeight / 5 + FrameThickness / 5 + 30 + 5);
        svg.append("line") //(Transom 2)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", ix - FrameThickness / 5)
            .attr("y1", iy + FrameHeight / 5 + FrameThickness / 5 + 30 - 30)
            .attr("x2", ix - FrameThickness / 5)
            .attr("y2", iy + FrameHeight / 5 + FrameThickness / 5 + 30 + 5);
    }
    svg.append("rect")
        .attr("x", ix)
        .attr("y", iy)
        .attr("width", FrameWidth / 5)
        .attr("height", FrameHeight / 5)
        .attr("stroke", "black")
        .attr("fill", "#CDD8DD");

    if (FrameHeight > 0 && FrameWidth > 0) {
        svg.append("line") //glass height
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", ix - 50)
            .attr("y1", iy - FrameThickness / 5)
            .attr("x2", ix - 50)
            .attr("y2", iy + FrameHeight / 5 + FrameThickness / 5)
            .attr("marker-start", "url(#arrowLeft)") // Left-pointing arrow
            .attr("marker-end", "url(#arrowRight)");

        svg.append("text")
            .style("fill", "black")
            .attr("font-size", 10)
            .attr("x", ix - 60)
            .attr("y", iy + FrameHeight / 10)
            .attr(
                "transform",
                `rotate(-90, ${ix - 60}, ${iy + FrameHeight / 10})`
            ) // Rotates 45 degrees around the text's position
            .text(FrameHeight);

        svg.append("line") //glass height
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", ix - 50 - 5)
            .attr("y1", iy - FrameThickness / 5)
            .attr("x2", ix)
            .attr("y2", iy - FrameThickness / 5);

        svg.append("line") //glass height
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", ix - 50 - 5)
            .attr("y1", iy + FrameHeight / 5 + FrameThickness / 5)
            .attr("x2", ix)
            .attr("y2", iy + FrameHeight / 5 + FrameThickness / 5);

        svg.append("line") //glass width
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", ix - FrameThickness / 5)
            .attr("y1", iy - 50)
            .attr("x2", ix + FrameWidth / 5 + FrameThickness / 5)
            .attr("y2", iy - 50)
            .attr("marker-start", "url(#arrowLeft)") // Left-pointing arrow
            .attr("marker-end", "url(#arrowRight)");

        svg.append("text")
            .style("fill", "black")
            .attr("font-size", 10)
            .attr("x", ix + FrameWidth / 10)
            .attr("y", iy - 55)
            .text(FrameWidth);

        svg.append("line") //glass width
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", ix - FrameThickness / 5)
            .attr("y1", iy)
            .attr("x2", ix - FrameThickness / 5)
            .attr("y2", iy - 50 - 5);

        svg.append("line") //glass width
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", ix + FrameWidth / 5 + FrameThickness / 5)
            .attr("y1", iy)
            .attr("x2", ix + FrameWidth / 5 + FrameThickness / 5)
            .attr("y2", iy - 50 - 5);
    }

    var TransomQuantity = $('select[name="TransomQuantity"]').val();
    var TransomHeightPoint1 = $('input[name="TransomHeight1"]').val();
    var TransomHeightPoint2 = $('input[name="TransomHeightPoint2"]').val();
    var TransomHeightPoint3 = $('input[name="TransomHeightPoint3"]').val();
    var TransomHeightPoint4 = $('input[name="TransomHeightPoint4"]').val();
    var Transom1Thickness = $('input[name="Transom1Thickness"]').val();
    var Transom2Thickness = $('input[name="Transom2Thickness"]').val();
    var Transom3Thickness = $('input[name="Transom3Thickness"]').val();
    var distanceForTransomMeasurement =
        ix + FrameWidth / 5 + FrameThickness / 5 + 30;

    //Transom measurements (Transom 1)
    T1height =
        iy + FrameHeight / 5 - TransomHeightPoint1 / 5 - Transom1Thickness / 5;
    svg.append("rect")
        .attr("x", ix)
        .attr("y", T1height)
        .attr("width", FrameWidth / 5)
        .attr("height", Transom1Thickness / 5)
        .attr("stroke", "black")
        .attr("fill", "#D0D0C6");

    if (Transom1Thickness > 0 && TransomHeightPoint1 > 0) {
        svg.append("line") //(Transom 1)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", distanceForTransomMeasurement)
            .attr("y1", iy + FrameHeight / 5)
            .attr("x2", distanceForTransomMeasurement)
            .attr("y2", iy + FrameHeight / 5 - TransomHeightPoint1 / 5)
            .attr("marker-start", "url(#arrowLeft)") // Left-pointing arrow
            .attr("marker-end", "url(#arrowRight)");

        svg.append("text") //(Transom 1)
            .style("fill", "black")
            .attr("font-size", 10)
            .attr("x", distanceForTransomMeasurement + 5)
            .attr("y", iy + FrameHeight / 5 - TransomHeightPoint1 / 10)
            .text(TransomHeightPoint1);

        svg.append("line") //(Transom 1)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", distanceForTransomMeasurement - 30)
            .attr("y1", iy + FrameHeight / 5 - TransomHeightPoint1 / 5)
            .attr("x2", distanceForTransomMeasurement)
            .attr("y2", iy + FrameHeight / 5 - TransomHeightPoint1 / 5);

        svg.append("line") //(Transom 1)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", distanceForTransomMeasurement - 30)
            .attr("y1", iy + FrameHeight / 5)
            .attr("x2", distanceForTransomMeasurement)
            .attr("y2", iy + FrameHeight / 5);

        svg.append("line") //(Transom 1 thickness)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", distanceForTransomMeasurement)
            .attr("y1", iy + FrameHeight / 5 - TransomHeightPoint1 / 5)
            .attr("x2", distanceForTransomMeasurement)
            .attr(
                "y2",
                iy +
                    FrameHeight / 5 -
                    TransomHeightPoint1 / 5 -
                    Transom1Thickness / 5
            )
            .attr("marker-start", "url(#arrowLeft)") // Left-pointing arrow
            .attr("marker-end", "url(#arrowRight)");

        svg.append("text") //(Transom 1 thickness)
            .style("fill", "black")
            .attr("font-size", 10)
            .attr("x", distanceForTransomMeasurement + 5)
            .attr(
                "y",
                iy +
                    FrameHeight / 5 -
                    TransomHeightPoint1 / 5 -
                    Transom1Thickness / 10
            )
            .text(Transom1Thickness);

        svg.append("line") //(Transom 1)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", distanceForTransomMeasurement - 30)
            .attr(
                "y1",
                iy +
                    FrameHeight / 5 -
                    TransomHeightPoint1 / 5 -
                    Transom1Thickness / 5
            )
            .attr("x2", distanceForTransomMeasurement)
            .attr(
                "y2",
                iy +
                    FrameHeight / 5 -
                    TransomHeightPoint1 / 5 -
                    Transom1Thickness / 5
            );
    }
    //Transom measurements (Transom 2)
    T2height =
        iy +
        FrameHeight / 5 -
        TransomHeightPoint1 / 5 -
        Transom1Thickness / 5 -
        TransomHeightPoint2 / 5 -
        Transom2Thickness / 5;

    svg.append("rect")
        .attr("x", ix)
        .attr("y", T2height)
        .attr("width", FrameWidth / 5)
        .attr("height", Transom2Thickness / 5)
        .attr("stroke", "black")
        .attr("fill", "#D0D0C6");

    if (Transom2Thickness > 0 && TransomHeightPoint2 > 0) {
        svg.append("line") //(Transom 2)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", distanceForTransomMeasurement)
            .attr("y1", T1height)
            .attr("x2", distanceForTransomMeasurement)
            .attr("y2", T2height + Transom2Thickness / 5)
            .attr("marker-start", "url(#arrowLeft)") // Left-pointing arrow
            .attr("marker-end", "url(#arrowRight)");

        svg.append("text") //(Transom 2)
            .style("fill", "black")
            .attr("font-size", 10)
            .attr("x", distanceForTransomMeasurement + 5)
            .attr("y", (T1height + T2height + Transom2Thickness / 5) / 2)
            .text(TransomHeightPoint2);

        svg.append("line") //(Transom 2)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", distanceForTransomMeasurement - 30)
            .attr("y1", T1height)
            .attr("x2", distanceForTransomMeasurement)
            .attr("y2", T1height);

        svg.append("line") //(Transom 2)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", distanceForTransomMeasurement - 30)
            .attr("y1", T2height + Transom2Thickness / 5)
            .attr("x2", distanceForTransomMeasurement)
            .attr("y2", T2height + Transom2Thickness / 5);

        svg.append("line") //(Transom 2 thickness)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", distanceForTransomMeasurement)
            .attr("y1", T2height + Transom2Thickness / 5)
            .attr("x2", distanceForTransomMeasurement)
            .attr("y2", T2height)
            .attr("marker-start", "url(#arrowLeft)")
            .attr("marker-end", "url(#arrowRight)");

        svg.append("text") //(Transom 2 thickness)
            .style("fill", "black")
            .attr("font-size", 10)
            .attr("x", distanceForTransomMeasurement + 5)
            .attr("y", T2height + Transom2Thickness / 10)
            .text(Transom2Thickness);

        svg.append("line") //(Transom 2)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", distanceForTransomMeasurement - 30)
            .attr("y1", T2height)
            .attr("x2", distanceForTransomMeasurement)
            .attr("y2", T2height);
    }
    //Transom measurements (Transom 3)
    T3height =
        iy +
        FrameHeight / 5 -
        TransomHeightPoint1 / 5 -
        Transom1Thickness / 5 -
        TransomHeightPoint2 / 5 -
        Transom2Thickness / 5 -
        TransomHeightPoint3 / 5 -
        Transom3Thickness / 5;

    svg.append("rect")
        .attr("x", ix)
        .attr("y", T3height)
        .attr("width", FrameWidth / 5)
        .attr("height", Transom3Thickness / 5)
        .attr("stroke", "black")
        .attr("fill", "#D0D0C6");

    if (Transom3Thickness > 0 && TransomHeightPoint3 > 0) {
        svg.append("line") //(Transom 3)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", distanceForTransomMeasurement)
            .attr("y1", T2height)
            .attr("x2", distanceForTransomMeasurement)
            .attr("y2", T3height + Transom3Thickness / 5)
            .attr("marker-start", "url(#arrowLeft)") // Left-pointing arrow
            .attr("marker-end", "url(#arrowRight)");

        svg.append("text") //(Transom 3)
            .style("fill", "black")
            .attr("font-size", 10)
            .attr("x", distanceForTransomMeasurement + 5)
            .attr("y", (T2height + T3height + Transom3Thickness / 5) / 2)
            .text(TransomHeightPoint3);

        svg.append("line") //(Transom 3)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", distanceForTransomMeasurement - 30)
            .attr("y1", T2height)
            .attr("x2", distanceForTransomMeasurement)
            .attr("y2", T2height);

        svg.append("line") //(Transom 3)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", distanceForTransomMeasurement - 30)
            .attr("y1", T3height + Transom3Thickness / 5)
            .attr("x2", distanceForTransomMeasurement)
            .attr("y2", T3height + Transom3Thickness / 5);

        svg.append("line") //(Transom 3 thickness)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", distanceForTransomMeasurement)
            .attr("y1", T3height + Transom3Thickness / 5)
            .attr("x2", distanceForTransomMeasurement)
            .attr("y2", T3height)
            .attr("marker-start", "url(#arrowLeft)")
            .attr("marker-end", "url(#arrowRight)");

        svg.append("text") //(Transom 3 thickness)
            .style("fill", "black")
            .attr("font-size", 10)
            .attr("x", distanceForTransomMeasurement + 5)
            .attr("y", T3height + Transom3Thickness / 10)
            .text(Transom3Thickness);

        svg.append("line") //(Transom 3)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", distanceForTransomMeasurement - 30)
            .attr("y1", T3height)
            .attr("x2", distanceForTransomMeasurement)
            .attr("y2", T3height);
    }

    if (Transom3Thickness > 0 && TransomHeightPoint3 > 0) {
        svg.append("line") //(Transom 2 thickness)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", distanceForTransomMeasurement)
            .attr("y1", iy)
            .attr("x2", distanceForTransomMeasurement)
            .attr("y2", T3height)
            .attr("marker-start", "url(#arrowLeft)")
            .attr("marker-end", "url(#arrowRight)");

        svg.append("text") //(Transom 2 thickness)
            .style("fill", "black")
            .attr("font-size", 10)
            .attr("x", distanceForTransomMeasurement + 5)
            .attr("y", (iy + T3height) / 2)
            .text(TransomHeightPoint4);

        svg.append("line") //(Transom 2)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", distanceForTransomMeasurement - 30)
            .attr("y1", iy)
            .attr("x2", distanceForTransomMeasurement)
            .attr("y2", iy);
    } else if (Transom2Thickness > 0 && TransomHeightPoint2 > 0) {
        svg.append("line") //(Transom 2 thickness)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", distanceForTransomMeasurement)
            .attr("y1", iy)
            .attr("x2", distanceForTransomMeasurement)
            .attr("y2", T2height)
            .attr("marker-start", "url(#arrowLeft)")
            .attr("marker-end", "url(#arrowRight)");
        svg.append("text") //(Transom 2 thickness)
            .style("fill", "black")
            .attr("font-size", 10)
            .attr("x", distanceForTransomMeasurement + 5)
            .attr("y", (iy + T2height) / 2)
            .text(FrameHeight -(2*FrameThickness)  -TransomHeightPoint1-Transom1Thickness-TransomHeightPoint2-Transom2Thickness);

        svg.append("line") //(Transom 2)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", distanceForTransomMeasurement - 30)
            .attr("y1", iy)
            .attr("x2", distanceForTransomMeasurement)
            .attr("y2", iy);
    } else if (Transom1Thickness > 0 && TransomHeightPoint1 > 0) {
        svg.append("line") //(Transom 2 thickness)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", distanceForTransomMeasurement)
            .attr("y1", iy)
            .attr("x2", distanceForTransomMeasurement)
            .attr("y2", T1height)
            .attr("marker-start", "url(#arrowLeft)")
            .attr("marker-end", "url(#arrowRight)");
        svg.append("text") //(Transom 2 thickness)
            .style("fill", "black")
            .attr("font-size", 10)
            .attr("x", distanceForTransomMeasurement + 5)
            .attr("y", (iy + T1height) / 2)
            .text(FrameHeight - (2*FrameThickness)  -TransomHeightPoint1-Transom1Thickness);

        svg.append("line") //(Transom 2)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", distanceForTransomMeasurement - 30)
            .attr("y1", iy)
            .attr("x2", distanceForTransomMeasurement)
            .attr("y2", iy);
    } else {
    }

    var MullionQuantity = $('select[name="MullionQuantity"]').val();
    var MullionWidthPoint1 = $('input[name="MullionWidthPoint1"]').val();
    var MullionWidthPoint2 = $('input[name="MullionWidthPoint2"]').val();
    var MullionWidthPoint3 = $('input[name="MullionWidthPoint3"]').val();
    var MullionWidthPoint4 = $('input[name="MullionWidthPoint4"]').val();
    var Mullion1Thickness = $('input[name="Mullion1Thickness"]').val();
    var Mullion2Thickness = $('input[name="Mullion2Thickness"]').val();
    var Mullion3Thickness = $('input[name="Mullion3Thickness"]').val();
    distanceForMullionMeasurement =
        iy + FrameHeight / 5 + FrameThickness / 5 + 30;
    //Mullion 1
    M1Width = ix + MullionWidthPoint1 / 5;

    svg.append("rect")
        .attr("x", M1Width)
        .attr("y", iy)
        .attr("width", Mullion1Thickness / 5)
        .attr("height", FrameHeight / 5)
        .attr("stroke", "black")
        .attr("fill", "#D0D0C6");

    if (Mullion1Thickness > 0 && MullionWidthPoint1 > 0) {
        svg.append("line") //(Mullion 1)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", ix)
            .attr("y1", distanceForMullionMeasurement)
            .attr("x2", M1Width)
            .attr("y2", distanceForMullionMeasurement)
            .attr("marker-start", "url(#arrowLeft)") // Left-pointing arrow
            .attr("marker-end", "url(#arrowRight)");

        svg.append("text") // (Mullion 1)
            .style("fill", "black")
            .attr("font-size", 10)
            .attr("x", (ix + M1Width) / 2)
            .attr("y", distanceForMullionMeasurement + 20)
            .attr(
                "transform",
                `rotate(-90, ${(ix + M1Width) / 2}, ${
                    distanceForMullionMeasurement + 20
                })`
            ) // Rotate 45 degrees
            .text(MullionWidthPoint1);

        svg.append("line") //(Mullion 1)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", ix)
            .attr("y1", distanceForMullionMeasurement - 30)
            .attr("x2", ix)
            .attr("y2", distanceForMullionMeasurement + 5);

        svg.append("line") //(Mullion 1)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", M1Width)
            .attr("y1", distanceForMullionMeasurement - 30)
            .attr("x2", M1Width)
            .attr("y2", distanceForMullionMeasurement + 5);

        svg.append("line") //( 1 Mullion)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", M1Width)
            .attr("y1", distanceForMullionMeasurement)
            .attr("x2", M1Width + Mullion1Thickness / 5)
            .attr("y2", distanceForMullionMeasurement)
            .attr("marker-start", "url(#arrowLeft)") // Left-pointing arrow
            .attr("marker-end", "url(#arrowRight)");

        svg.append("text") // (1 Mullion)
            .style("fill", "black")
            .attr("font-size", 10)
            .attr("x", (M1Width + M1Width + Mullion1Thickness / 5) / 2)
            .attr("y", distanceForMullionMeasurement + 20)
            .attr(
                "transform",
                `rotate(-90, ${
                    (M1Width + M1Width + Mullion1Thickness / 5) / 2
                }, ${distanceForMullionMeasurement + 20})`
            ) // Rotate 45 degrees
            .text(Mullion1Thickness);

        svg.append("line") //(Mullion 1)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", M1Width + Mullion1Thickness / 5)
            .attr("y1", distanceForMullionMeasurement + 5)
            .attr("x2", M1Width + Mullion1Thickness / 5)
            .attr("y2", distanceForMullionMeasurement - 30);
    }

    //Mullion 2
    M2Width =
        ix +
        MullionWidthPoint1 / 5 +
        Mullion1Thickness / 5 +
        MullionWidthPoint2 / 5;

    svg.append("rect")
        .attr("x", M2Width)
        .attr("y", iy)
        .attr("width", Mullion2Thickness / 5)
        .attr("height", FrameHeight / 5)
        .attr("stroke", "black")
        .attr("fill", "#D0D0C6");

    if (Mullion2Thickness > 0 && MullionWidthPoint2 > 0) {
        svg.append("line") //(Mullion 2)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", M1Width + Mullion1Thickness / 5)
            .attr("y1", distanceForMullionMeasurement)
            .attr("x2", M2Width)
            .attr("y2", distanceForMullionMeasurement)
            .attr("marker-start", "url(#arrowLeft)") // Left-pointing arrow
            .attr("marker-end", "url(#arrowRight)");

        svg.append("text") // (Mullion 2)
            .style("fill", "black")
            .attr("font-size", 10)
            .attr("x", (M1Width + Mullion1Thickness / 5 + M2Width) / 2)
            .attr("y", distanceForMullionMeasurement + 20)
            .attr(
                "transform",
                `rotate(-90, ${
                    (M1Width + Mullion1Thickness / 5 + M2Width) / 2
                }, ${distanceForMullionMeasurement + 20})`
            ) // Rotate 45 degrees
            .text(MullionWidthPoint2);

        svg.append("line") //(Mullion 2)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", M1Width + Mullion1Thickness / 5)
            .attr("y1", distanceForMullionMeasurement - 30)
            .attr("x2", M1Width + Mullion1Thickness / 5)
            .attr("y2", distanceForMullionMeasurement + 5);

        svg.append("line") //(Mullion 2)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", M2Width)
            .attr("y1", distanceForMullionMeasurement - 30)
            .attr("x2", M2Width)
            .attr("y2", distanceForMullionMeasurement + 5);

        svg.append("line") //( 2 Mullion thickness)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", M2Width)
            .attr("y1", distanceForMullionMeasurement)
            .attr("x2", M2Width + Mullion2Thickness / 5)
            .attr("y2", distanceForMullionMeasurement)
            .attr("marker-start", "url(#arrowLeft)") // Left-pointing arrow
            .attr("marker-end", "url(#arrowRight)");

        svg.append("text") // (2 Mullion thickness)
            .style("fill", "black")
            .attr("font-size", 10)
            .attr("x", (M2Width + M2Width + Mullion2Thickness / 5) / 2)
            .attr("y", distanceForMullionMeasurement + 20)
            .attr(
                "transform",
                `rotate(-90, ${
                    (M2Width + M2Width + Mullion2Thickness / 5) / 2
                }, ${distanceForMullionMeasurement + 20})`
            ) // Rotate 45 degrees
            .text(Mullion2Thickness);

        svg.append("line") //(Mullion 2 thickness)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", M2Width + Mullion2Thickness / 5)
            .attr("y1", distanceForMullionMeasurement + 5)
            .attr("x2", M2Width + Mullion2Thickness / 5)
            .attr("y2", distanceForMullionMeasurement - 30);
    }

    M3Width =
        ix +
        MullionWidthPoint1 / 5 +
        Mullion1Thickness / 5 +
        MullionWidthPoint2 / 5 +
        Mullion2Thickness / 5 +
        MullionWidthPoint3 / 5;

    svg.append("rect")
        .attr("x", M3Width)
        .attr("y", iy)
        .attr("width", Mullion3Thickness / 5)
        .attr("height", FrameHeight / 5)
        .attr("stroke", "black")
        .attr("fill", "#D0D0C6");

    if (Mullion3Thickness > 0 && MullionWidthPoint3 > 0) {
        svg.append("line") //(Mullion 3)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", M2Width + Mullion2Thickness / 5)
            .attr("y1", distanceForMullionMeasurement)
            .attr("x2", M3Width)
            .attr("y2", distanceForMullionMeasurement)
            .attr("marker-start", "url(#arrowLeft)")
            .attr("marker-end", "url(#arrowRight)");

        svg.append("text") // (Mullion 3)
            .style("fill", "black")
            .attr("font-size", 10)
            .attr("x", (M2Width + Mullion2Thickness / 5 + M3Width) / 2)
            .attr("y", distanceForMullionMeasurement + 20)
            .attr(
                "transform",
                `rotate(-90, ${
                    (M2Width + Mullion2Thickness / 5 + M3Width) / 2
                }, ${distanceForMullionMeasurement + 20})`
            ) // Rotate 45 degrees
            .text(MullionWidthPoint3);

        svg.append("line") //(Mullion 3)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", M2Width + Mullion2Thickness / 5)
            .attr("y1", distanceForMullionMeasurement - 30)
            .attr("x2", M2Width + Mullion2Thickness / 5)
            .attr("y2", distanceForMullionMeasurement + 5);

        svg.append("line") //(Mullion 3)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", M3Width)
            .attr("y1", distanceForMullionMeasurement - 30)
            .attr("x2", M3Width)
            .attr("y2", distanceForMullionMeasurement + 5);

        svg.append("line") //( 3 Mullion thickness)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", M3Width)
            .attr("y1", distanceForMullionMeasurement)
            .attr("x2", M3Width + Mullion3Thickness / 5)
            .attr("y2", distanceForMullionMeasurement)
            .attr("marker-start", "url(#arrowLeft)") // Left-pointing arrow
            .attr("marker-end", "url(#arrowRight)");

        svg.append("text") // (3 Mullion thickness)
            .style("fill", "black")
            .attr("font-size", 10)
            .attr("x", (M3Width + M3Width + Mullion3Thickness / 5) / 2)
            .attr("y", distanceForMullionMeasurement + 20)
            .attr(
                "transform",
                `rotate(-90, ${
                    (M3Width + M3Width + Mullion3Thickness / 5) / 2
                }, ${distanceForMullionMeasurement + 20})`
            ) // Rotate 45 degrees
            .text(Mullion3Thickness);

        svg.append("line") //(Mullion 3 thickness)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", M3Width + Mullion3Thickness / 5)
            .attr("y1", distanceForMullionMeasurement + 5)
            .attr("x2", M3Width + Mullion3Thickness / 5)
            .attr("y2", distanceForMullionMeasurement - 30);
    }

    if (Mullion3Thickness > 0 && MullionWidthPoint3 > 0) {
        svg.append("line") //(Transom 2 thickness)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", M3Width + Mullion3Thickness / 5)
            .attr("y1", distanceForMullionMeasurement)
            .attr("x2", ix + FrameWidth / 5)
            .attr("y2", distanceForMullionMeasurement)
            .attr("marker-start", "url(#arrowLeft)")
            .attr("marker-end", "url(#arrowRight)");

        svg.append("text") // (Transom 2 thickness)
            .style("fill", "black")
            .attr("font-size", 10)
            .attr(
                "x",
                (M3Width + Mullion3Thickness / 5 + ix + FrameWidth / 5) / 2
            )
            .attr("y", distanceForMullionMeasurement + 20)
            .attr(
                "transform",
                `rotate(-90, ${
                    (M3Width + Mullion3Thickness / 5 + ix + FrameWidth / 5) / 2
                }, ${distanceForMullionMeasurement + 20})`
            ) // Rotate 45 degrees
            .text(MullionWidthPoint4);

        svg.append("line") //(Transom 2)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", ix + FrameWidth / 5)
            .attr("y1", distanceForMullionMeasurement - 30)
            .attr("x2", ix + FrameWidth / 5)
            .attr("y2", distanceForMullionMeasurement + 5);
    } else if (Mullion2Thickness > 0 && MullionWidthPoint2 > 0) {
        svg.append("line") //(Transom 2 thickness)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", M2Width + Mullion2Thickness / 5)
            .attr("y1", distanceForMullionMeasurement)
            .attr("x2", ix + FrameWidth / 5)
            .attr("y2", distanceForMullionMeasurement)
            .attr("marker-start", "url(#arrowLeft)")
            .attr("marker-end", "url(#arrowRight)");

        svg.append("text") // (Transom 2 thickness)
            .style("fill", "black")
            .attr("font-size", 10)
            .attr(
                "x",
                (M2Width + Mullion2Thickness / 5 + ix + FrameWidth / 5) / 2
            )
            .attr("y", distanceForMullionMeasurement + 20)
            .attr(
                "transform",
                `rotate(-90, ${
                    (M2Width + Mullion2Thickness / 5 + ix + FrameWidth / 5) / 2
                }, ${distanceForMullionMeasurement + 20})`
            ) // Rotate 45 degrees
            .text(FrameWidth-(2*FrameThickness)-MullionWidthPoint1-Mullion1Thickness-MullionWidthPoint2-Mullion2Thickness);

        svg.append("line") //(Transom 2)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", ix + FrameWidth / 5)
            .attr("y1", distanceForMullionMeasurement - 30)
            .attr("x2", ix + FrameWidth / 5)
            .attr("y2", distanceForMullionMeasurement + 5);
    } else if (Mullion1Thickness > 0 && MullionWidthPoint1 > 0) {
        svg.append("line") //(Transom 2 thickness)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", M1Width + Mullion1Thickness / 5)
            .attr("y1", distanceForMullionMeasurement)
            .attr("x2", ix + FrameWidth / 5)
            .attr("y2", distanceForMullionMeasurement)
            .attr("marker-start", "url(#arrowLeft)")
            .attr("marker-end", "url(#arrowRight)");

        svg.append("text") // (Transom 2 thickness)
            .style("fill", "black")
            .attr("font-size", 10)
            .attr(
                "x",
                (M1Width + Mullion1Thickness / 5 + ix + FrameWidth / 5) / 2
            )
            .attr("y", distanceForMullionMeasurement + 20)
            .attr(
                "transform",
                `rotate(-90, ${
                    (M1Width + Mullion1Thickness / 5 + ix + FrameWidth / 5) / 2
                }, ${distanceForMullionMeasurement + 20})`
            ) // Rotate 45 degrees
            .text(FrameWidth-(2*FrameThickness)-MullionWidthPoint1-Mullion1Thickness);

        svg.append("line") //(Transom 2)
            .style("stroke", "black")
            .style("stroke-width", 0.5)
            .attr("x1", ix + FrameWidth / 5)
            .attr("y1", distanceForMullionMeasurement - 30)
            .attr("x2", ix + FrameWidth / 5)
            .attr("y2", distanceForMullionMeasurement + 5);
    } else {
    }
    

    //labeling A1, A2, ....
    if(MullionQuantity == 3 && TransomQuantity == 3){
    svg.append("text")
        .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A1");

        svg.append("text")
        .attr("x", (M1Width+M2Width)/2) // Place text at the midpoint between Mullions
        .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A2");

        svg.append("text")
        .attr("x", (M2Width+M3Width)/2) // Place text at the midpoint between Mullions
        .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A3");

        svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A4");

        svg.append("text")
        .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T1height+T2height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("B1");

        svg.append("text")
        .attr("x", (M1Width+M2Width)/2) // Place text at the midpoint between Mullions
        .attr("y", (T1height+T2height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("B2");

        svg.append("text")
        .attr("x", (M2Width+M3Width)/2) // Place text at the midpoint between Mullions
        .attr("y", (T1height+T2height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("B3");

        svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T1height+T2height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("B4");

        svg.append("text")
        .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T2height+T3height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("C1");

        svg.append("text")
        .attr("x", (M1Width+M2Width)/2) // Place text at the midpoint between Mullions
        .attr("y", (T2height+T3height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("C2");

        svg.append("text")
        .attr("x", (M2Width+M3Width)/2) // Place text at the midpoint between Mullions
        .attr("y", (T2height+T3height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("C3");

        svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T2height+T3height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("C4");
        svg.append("text")
        .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("D1");

        svg.append("text")
        .attr("x", (M1Width+M2Width)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("D2");

        svg.append("text")
        .attr("x", (M2Width+M3Width)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("D3");

        svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("D4");
}

if(MullionQuantity == 0 && TransomQuantity == 0 && FrameHeight>0 && FrameWidth>0){
    svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A1");
}
if(MullionQuantity == 0 && TransomQuantity == 1){
    svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("B1");
        svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A1");
}
if(MullionQuantity == 0 && TransomQuantity == 2){
    svg.append("text")
    .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("C1");
    svg.append("text")
    .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (T1height+T2height)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("B1");
    svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A1");
}
if(MullionQuantity == 0 && TransomQuantity == 3){
    svg.append("text")
    .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("D1");
    svg.append("text")
    .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (T2height+T3height)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("C1");
    svg.append("text")
    .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (T1height+T2height)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("B1");
    svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A1");
}
if(MullionQuantity == 1 && TransomQuantity == 0){
    svg.append("text")
    .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("A1");
    svg.append("text")
    .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("A2");
}
if(MullionQuantity == 2 && TransomQuantity == 0){
    svg.append("text")
    .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("A1");
    svg.append("text")
        .attr("x", (M1Width+M2Width)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A2");
        svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A3");
}
if(MullionQuantity == 3 && TransomQuantity == 0){
    svg.append("text")
    .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("A1");
    svg.append("text")
        .attr("x", (M1Width+M2Width)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A2");
        svg.append("text")
        .attr("x", (M2Width+M3Width)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A3");
        svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A4");
}
if(MullionQuantity == 1 && TransomQuantity == 1){
    svg.append("text")
    .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("B1");
    svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("B2");
        svg.append("text")
        .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A1");
        svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A2");
}
if(MullionQuantity == 2 && TransomQuantity == 1){
    svg.append("text")
    .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("A1");

    svg.append("text")
    .attr("x", (M1Width+M2Width)/2) // Place text at the midpoint between Mullions
    .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("A2");

    svg.append("text")
    .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("A3");
    svg.append("text")
        .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("B1");

        svg.append("text")
        .attr("x", (M1Width+M2Width)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("B2");

        svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("B3");
}
if(MullionQuantity == 3 && TransomQuantity == 1){
    svg.append("text")
    .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("B1");

    svg.append("text")
    .attr("x", (M1Width+M2Width)/2) // Place text at the midpoint between Mullions
    .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("B2");

    svg.append("text")
    .attr("x", (M2Width+M3Width)/2) // Place text at the midpoint between Mullions
    .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("B3");

    svg.append("text")
    .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("B4");

    svg.append("text")
        .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A1");

        svg.append("text")
        .attr("x", (M1Width+M2Width)/2) // Place text at the midpoint between Mullions
        .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A2");

        svg.append("text")
        .attr("x", (M2Width+M3Width)/2) // Place text at the midpoint between Mullions
        .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A3");

        svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A4");
}
if(MullionQuantity == 1 && TransomQuantity == 2){
    svg.append("text")
    .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("A1");
    svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A2");
        svg.append("text")
        .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T1height+T2height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("B1");
        svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T1height+T2height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("B2");
        svg.append("text")
        .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("C1");
        svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("C2");
}
if(MullionQuantity == 2 && TransomQuantity == 2){
    svg.append("text")
    .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("A1");

    svg.append("text")
    .attr("x", (M1Width+M2Width)/2) // Place text at the midpoint between Mullions
    .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("A2");

    svg.append("text")
    .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("A3");

    
    svg.append("text")
    .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (T1height+T2height)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("B1");

    svg.append("text")
    .attr("x", (M1Width+M2Width)/2) // Place text at the midpoint between Mullions
    .attr("y", (T1height+T2height)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("B2");

    svg.append("text")
    .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (T1height+T2height)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("B3");

    svg.append("text")
    .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("C1");

    svg.append("text")
    .attr("x", (M1Width+M2Width)/2) // Place text at the midpoint between Mullions
    .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("C2");

    svg.append("text")
    .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("C3");
}
if(MullionQuantity == 3 && TransomQuantity == 2){
    svg.append("text")
    .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("A1");

    svg.append("text")
    .attr("x", (M1Width+M2Width)/2) // Place text at the midpoint between Mullions
    .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("A2");

    svg.append("text")
    .attr("x", (M2Width+M3Width)/2) // Place text at the midpoint between Mullions
    .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("A3");

    svg.append("text")
    .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("A4");

    svg.append("text")
    .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (T1height+T2height)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("B1");

    svg.append("text")
    .attr("x", (M1Width+M2Width)/2) // Place text at the midpoint between Mullions
    .attr("y", (T1height+T2height)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("B2");

    svg.append("text")
    .attr("x", (M2Width+M3Width)/2) // Place text at the midpoint between Mullions
    .attr("y", (T1height+T2height)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("B3");

    svg.append("text")
    .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
    .attr("y", (T1height+T2height)/2) // Adjust the vertical position as needed
    .attr("text-anchor", "middle") // Center the text horizontally
    .attr("font-size", "12px")
    .style("fill", "black")
    .text("B4");
    svg.append("text")
        .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("C1");

        svg.append("text")
        .attr("x", (M1Width+M2Width)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("C2");

        svg.append("text")
        .attr("x", (M2Width+M3Width)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("C3");

        svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("C4");
}
if(MullionQuantity == 1 && TransomQuantity == 3){
    
    svg.append("text")
        .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A1");
        svg.append("text")
        .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T1height+T2height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("B1");
        svg.append("text")
        .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T2height+T3height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("C1");
        svg.append("text")
        .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("D1");
        svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A2");
        svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T1height+T2height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("B2");
        svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T2height+T3height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("C2");
        svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("D2");
}
if(MullionQuantity == 2 && TransomQuantity == 3){
    
    svg.append("text")
        .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A1");
        svg.append("text")
        .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T1height+T2height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("B1");
        svg.append("text")
        .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T2height+T3height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("C1");
        svg.append("text")
        .attr("x", (ix+ix+MullionWidthPoint1/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("D1");
        svg.append("text")
        .attr("x", (M1Width+M2Width)/2) // Place text at the midpoint between Mullions
        .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A2");
        svg.append("text")
        .attr("x", (M1Width+M2Width)/2) // Place text at the midpoint between Mullions
        .attr("y", (T1height+T2height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("B2");
        svg.append("text")
        .attr("x", (M1Width+M2Width)/2) // Place text at the midpoint between Mullions
        .attr("y", (T2height+T3height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("C2");
        svg.append("text")
        .attr("x", (M1Width+M2Width)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("D2");
        svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (iy+FrameHeight/5+T1height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("A3");
        svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T1height+T2height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("B3");
        svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T2height+T3height)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("C3");
        svg.append("text")
        .attr("x", (M3Width+ ix+FrameWidth/5)/2) // Place text at the midpoint between Mullions
        .attr("y", (T3height+iy)/2) // Adjust the vertical position as needed
        .attr("text-anchor", "middle") // Center the text horizontally
        .attr("font-size", "12px")
        .style("fill", "black")
        .text("D4");
}

    

    var svgData = d3.select("svg").node();
    //get svg source.
    var serializer = new XMLSerializer();
    var source = serializer.serializeToString(svgData);

    //add name spaces.
    if (!source.match(/^<svg[^>]+xmlns="http\:\/\/www\.w3\.org\/2000\/svg"/)) {
        source = source.replace(
            /^<svg/,
            '<svg xmlns="http://www.w3.org/2000/svg"'
        );
    }
    if (!source.match(/^<svg[^>]+"http\:\/\/www\.w3\.org\/1999\/xlink"/)) {
        source = source.replace(
            /^<svg/,
            '<svg xmlns:xlink="http://www.w3.org/1999/xlink"'
        );
    }

    //add xml declaration
    source = '<?xml version="1.0" standalone="no"?>\r\n' + source;

    //convert svg source to URI data scheme.
    //        var image = "data:image/svg+xml;charset=utf-8,"+encodeURIComponent(source);

    var encodedData = "data:image/svg+xml;base64," + window.btoa(source);
    // $('input[name="SvgImage"]').val(encodedData);
    run(encodedData);
};

//$( ".door-configuration" ).change( function( event ) {
$(".form-control").change(function (event) {
    var element = $(this);
    render(element);
});

$("#change-dimension").change(function (event) {
    var element = $(this);
    render(element);
});

const optionRow = document.querySelector(".container-carousel");
const optionItems = document.querySelectorAll(".optionItem");

const arrowLeft = document.getElementById("arrow-left");
const arrowRight = document.getElementById("arrow-right");

// ? ----- ----- Right arrow Event Listener ----- -----
arrowRight.addEventListener("click", () => {
    optionRow.scrollLeft += optionRow.offsetWidth;

    const activeArrow = document.querySelector(".indicadores .activo");
    if (activeArrow) {
        activeArrow.classList.add("activo");
        activeArrow.classList.remove("activo");
    }
});

// ? ----- ----- Left arrow Event Listener ----- -----
arrowLeft.addEventListener("click", () => {
    optionRow.scrollLeft -= optionRow.offsetWidth;

    const activeArrow = document.querySelector(".indicadores .activo");
    if (activeArrow) {
        activeArrow.classList.add("activo");
        activeArrow.classList.remove("activo");
    }
});

let imageUtil = {};

function run(svgData) {
    //let svgSrc = document.getElementById('svgImage').src;
    let svgSrc = svgData;
    imageUtil.base64SvgToBase64Png(svgSrc, 200).then((pngSrc) => {
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
    return new Promise((resolve) => {
        let img = document.createElement("img");
        img.onload = function () {
            if (
                !secondTry &&
                (img.naturalWidth === 0 || img.naturalHeight === 0)
            ) {
                let svgDoc = base64ToSvgDocument(originalBase64);
                let fixedDoc = fixSvgDocumentFF(svgDoc);
                return imageUtil
                    .base64SvgToBase64Png(
                        svgDocumentToBase64(fixedDoc),
                        width,
                        true
                    )
                    .then((result) => {
                        resolve(result);
                    });
            }
            document.body.appendChild(img);
            let canvas = document.createElement("canvas");
            let ratio = img.clientWidth / img.clientHeight || 1;
            document.body.removeChild(img);
            //canvas.width = img.width;
            //canvas.height = width / ratio;
            canvas.width = 1000;
            canvas.height = 1000;
            let ctx = canvas.getContext("2d");
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            try {
                let data = canvas.toDataURL("image/png");
                resolve(data);
            } catch (e) {
                resolve(null);
            }
        };
        img.src = originalBase64;
    });
};

//needed because Firefox doesn't correctly handle SVG with size = 0, see https://bugzilla.mozilla.org/show_bug.cgi?id=700533
function fixSvgDocumentFF(svgDocument) {
    try {
        let widthInt =
            parseInt(svgDocument.documentElement.width.baseVal.value) || 500;
        let heightInt =
            parseInt(svgDocument.documentElement.height.baseVal.value) || 500;
        svgDocument.documentElement.width.baseVal.newValueSpecifiedUnits(
            SVGLength.SVG_LENGTHTYPE_PX,
            widthInt
        );
        svgDocument.documentElement.height.baseVal.newValueSpecifiedUnits(
            SVGLength.SVG_LENGTHTYPE_PX,
            heightInt
        );
        return svgDocument;
    } catch (e) {
        return svgDocument;
    }
}

function svgDocumentToBase64(svgDocument) {
    try {
        let base64EncodedSVG = btoa(
            new XMLSerializer().serializeToString(svgDocument)
        );
        return "data:image/svg+xml;base64," + base64EncodedSVG;
    } catch (e) {
        return null;
    }
}

function base64ToSvgDocument(base64) {
    let svg = atob(base64.substring(base64.indexOf("base64,") + 7));
    svg = svg.substring(svg.indexOf("<svg"));
    let parser = new DOMParser();
    return parser.parseFromString(svg, "image/svg+xml");
}
