$(document).on('click','.quote_filter',function(){
    if ($(this).next("ul").css("visibility") == "hidden"){
        $(this).next("ul").css("visibility", "visible");
        $(this).next("ul").css("opacity", 1);
        $(this).next("ul").css("transform", "translateX(-50%) scaleY(1)");
    } else {
        $(this).next("ul").css("visibility", "hidden");
        $(this).next("ul").css("opacity", 0);
        $(this).next("ul").css("transform", "translateX(-50%) scaleY(1)");
    }
});

$('#buttonfilter').removeAttr('class')
$(document).on('click', '#buttonfilter', function(e) {
    e.preventDefault();
    $('#popover').addClass('active')
    $(this).addClass('removeCheckbox')
})
$(document).on('click', '.removeCheckbox', function(e) {
    $(this).removeAttr('class')
    $('#popover').removeClass('active')
})
window.addEventListener('mouseup', function(e){
    e.preventDefault();
    var quoteList = $("ul.QuotationMenu");
    if (!quoteList.is(e.target) && quoteList.has(e.target).length === 0) {
        quoteList.css({
            "visibility": "hidden",
            'opacity': 0
        });
    }
    let filterBox = $('#popover');
    if (!filterBox.is(e.target) && filterBox.has(e.target).length === 0) {
        filterBox.removeClass('active')
       
    }
});

