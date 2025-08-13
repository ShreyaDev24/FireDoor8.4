$(function(){
    // Dynamic Tabs
    // Custom Tab Open
    // Dynamic tab makes use anywhere
    // Example :
    // <ul class="CustomTabs tabs">
    //     <li class="active"><a href="#configurable">Configurable</a></li>
    //     <li><a href="#nonconfigurable">Non Configurable</a></li>
    //     <li><a data-toggle="tab" href="#all">All</a></li>
    // </ul>

    // <div class="CustomTabContent tab-content">
    //     <div id="configurable" class="tab-pane active"></div>
    //     <div id="nonconfigurable" class="tab-pane"></div>
    // </div>

    // CustomTabs
    // CustomTabContent


    // Initial style for active tab link
    // Apply blue style to initially active tab
$('.CustomTabs li.active a').css({'color':'blue','font-weight':'bold'});

$(document).on('click', '.CustomTabs li a', function(e) {
    e.preventDefault();

    let href = $(this).attr('href');

    // Style for clicked tab
    $(this).closest('ul').find('a').removeAttr('style');
    $(this).css({'color':'blue','font-weight':'bold'});

    // Find the related tab content container
    let tabContent = $(this).closest('.col-sm-12').next().find('.CustomTabContent');

    // Show the selected tab content
    tabContent.find('.tab-pane').removeClass('active show');

    if (href === '#all') {
        tabContent.find('.tab-pane').addClass('active show');
    } else {
        tabContent.find(href).addClass('active show');
    }
});



})



// Read More Button
// Dynamic code use any where

// Just call this script in your page and pass two things: 1. TextLength, 2. Text
// function is `ReadMore(TextLength,Text)`
// <script type="text/javascript">
//     document.write(ReadMore(5,"'.$nonconfigdatas->description.'"))
// </script>

// ReadMore() function
// ReadMore(TextLength,FullText)


function ReadMore(TextLength,AllText){
    let limitText = AllText.substr(0,TextLength);
    let fullLength = AllText.length;
    if(fullLength > TextLength){
        return limitText +   `...
            <a href="#" class="ReadMoreFull" style="color:green">Read More</a>
            <input type="hidden" class="fullText" value="`+AllText+`">
            <input type="hidden" class="limitText" value="`+limitText+`">
            <input type="hidden" class="textlength" value="`+TextLength+`">
        `;
    } else {
        return limitText;
    }

}
$(document).on('click','.ReadMoreFull',function(e){
    e.preventDefault();
    let fullText = $(this).siblings('.fullText').val()
    let limitText = $(this).siblings('.limitText').val()
    let TextLength = $(this).siblings('.textlength').val()
    let data = fullText+   `
        <a href="#" class="ReadShortText" style="color:red">Close</a>
        <input type="hidden" class="fullText" value="`+fullText+`">
        <input type="hidden" class="limitText" value="`+limitText+`">
        <input type="hidden" class="textlength" value="`+TextLength+`">
    `;
    $(this).parent().html('').html(data)
})

$(document).on('keydown','input',function(e){
    if (this.value.length === 0 && e.which === 32) e.preventDefault();
});

$(document).on('click','.ReadShortText',function(e){
    e.preventDefault();
    let fullText = $(this).siblings('.fullText').val()
    let limitText = $(this).siblings('.limitText').val()
    let TextLength = $(this).siblings('.textlength').val()
    let data = limitText+   `...
        <a href="#" class="ReadMoreFull" style="color:green">Read More</a>
        <input type="hidden" class="fullText" value="`+fullText+`">
        <input type="hidden" class="limitText" value="`+limitText+`">
        <input type="hidden" class="textlength" value="`+TextLength+`">
        `;
    $(this).parent().html('').html(data)
})




