$(".form-control").change(function (event) {
    var identifier = $(this);
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
    var TagName = identifier.prop("tagName");
    var name = identifier.attr("name");

    var id = identifier.attr("id");

    // These fields should give a warning if you enter a number higher than the set max number
    const InputValue = parseFloat($("#" + id).val());
    const getmaxinputvalue = parseInt($("#" + id).attr('max'));
    const getmininputvalue = parseInt($("#" + id).attr('min'));
    let getmsginput = null;
    if (id == 'GlazingBeadHeight') {
        getmsginput = 'Glazing Bead Height should be a minimum of ' + getmininputvalue + '.';
    } else if (id == 'GlazingBeadWidth') {
        getmsginput = 'Glazing Bead Width should be a minimum of ' + getmininputvalue + '.';
    } else if (id == 'Transom1Thickness') {
        getmsginput = 'Transom 1 Thickness should be a minimum of ' + getmininputvalue + '.';
    } else if (id == 'Transom2Thickness') {
        getmsginput = 'Transom 2 Thickness should be a minimum of ' + getmininputvalue + '.';
    } else if (id == 'Transom3Thickness') {
        getmsginput = 'Transom 3 Thickness should be a minimum of ' + getmininputvalue + '.';
    } else if (id == 'Mullion1Thickness') {
        getmsginput = 'Mullion 1 Thickness should be a minimum of ' + getmininputvalue + '.';
    } else if (id == 'Mullion2Thickness') {
        getmsginput = 'Mullion 2 Thickness should be a minimum of ' + getmininputvalue + '.';
    } else if (id == 'Mullion3Thickness') {
        getmsginput = 'Mullion 3 Thickness should be a minimum of ' + getmininputvalue + '.';
    } else if (id == 'FrameThickness') {
        getmsginput = 'Frame Thickness should be a minimum of ' + getmininputvalue + '.';
    } else if (id == 'TransomDepth') {
        getmsginput = 'Transom Depth should be a minimum of ' + getmininputvalue + '.';
    } else if (id == 'FrameDepth') {
        getmsginput = 'Frame Depth should be a minimum of ' + getmininputvalue + '.';
    } else if (id == 'CAVITY') {
        getmsginput = 'CAVITY is not more than ' + getmaxinputvalue + '.';
    }

    if (InputValue > getmaxinputvalue) {
        swal('Warning', getmsginput);
        $("#" + id).val(0);
        $("#" + id).css({ 'border': '1px solid red' });
        return false;
    } else {
        $("#" + id).css({ 'border': '1px solid #ced4da' });
    }
    if (InputValue < getmininputvalue) {
        swal('Warning', getmsginput);
        $("#" + id).val(0);
        $("#" + id).css({ 'border': '1px solid red' });
        return false;
    } else {
        $("#" + id).css({ 'border': '1px solid #ced4da' });
    }

}
