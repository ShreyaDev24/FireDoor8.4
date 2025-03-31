
    $(document).on('keyup',".optName", function(e){
        e.preventDefault();
        const optionName = $(this).val();
        const slug = optionName.split(" ").join("_");
        $('#optionSlug').val(slug).toLowerCase();
        // $(this).parent('div').parent('div').siblings('div').children('div').children(".optSlug").val(slug);
    })
    // alert(122);
//    $("#optionName").keyup(function(e){

//     var optionName = $(this).val();

//     var slug = optionName.split(" ").join("_");
//      //alert(slug);

//     $("#optionSlug").val(slug);

//    })

   $(document).on('keyup','.OptionKey',function(e){
        var optionName = $(this).val();
        var slug = optionName.split(" ").join("_");
        $(this).val(slug);
   })






function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {

        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

$(function(){
    var dtToday = new Date();

    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();

    var maxDate = year + '-' + month + '-' + day;
    // alert(maxDate);
    $('#ExpiryDate').attr('min', maxDate);
});

let delay_timer = null;
let filters = {};

let column = "created_at";
let dir = 'desc';

let page = 1;
let fromData = 0;
let limit = 12;

function requester(from = 0,limitTo = 20, filters = [],order = [],doEmpty = false,setPage = false){
    $('.loader').empty().css({
        'display': 'block'
    });
    //$('select[name="limit"]').val(limit);
    let results = $('.results');
    let quotation_count = $('.quotation_count');
    let url = $('input[name="directory_ajax"]').val();
    let _token = $('input[name="_token"]').val();
    //let category = $('#category').val();
    $.ajax({
        type: "POST",
        url: url,
        data: {
            //category : category,
            _token : _token,
            ajaxCall : 1,
            from : from,
            limit : limit,
            filters : filters,
            orders : order
        },
        dataType: 'json',
        success: function (data) { console.log(data)
            if(data.st == "success"){
                if(doEmpty){
                    $('.results').empty().append(data.html);
                    $(".quotation_count").empty().append(data.total);
                }else{
                    $('.results').append(data.html);
                    $(".quotation_count").append(data.total);
                }
                fromData = (parseInt(fromData) + parseInt(limit));
                if(setPage){
                    page = setPage;
                }
                pagination(data.total);
                $('.loader').empty().css({'display': 'none'});
            }else{
                swal("Oops!", data.txt, "error");
            }
        },
        error: function (data) {
            //$(".page-loader-action").fadeOut();
            swal("Oops!", "Something went wrong. Please try again.", "error");
        }
    });
}

function pagination(data){

    //let limit = $('select[name="limit"]').find(':selected').val();

    var pageList = '';
    var className = '';

    if(limit >= data){
        pageList += '<li class="disabled"><a href="javascript:void(0);">PREV</a></li>';
        pageList += '<li class="active"><a href="javascript:void(0);">1</a></li>';
        pageList += '<li class="disabled"><a href="javascript:void(0);">NEXT</a></li>';
    }else{

        var pageCount = Math.ceil(data / limit);

        console.log(pageCount);

        if(pageCount <= 7){

            console.log("less");

            if(page == 1){
                pageList += '<li class="disabled"><a href="javascript:void(0);">PREV</a></li>';
            }else {
                pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ (page - 1) +'">PREV</a></li>';
            }

            for(var i=1;i<=pageCount;i++){

                if(page == i){
                    className = 'class="active"';
                    pageList += '<li '+ className +'><a href="javascript:void(0);">'+ i +'</a></li>';
                }else{
                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ i +'">'+ i +'</a></li>';
                }


            }

            if(page == pageCount){
                pageList += '<li class="disabled"><a href="javascript:void(0);">NEXT</a></li>';
            }else{
                pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ (page + 1) +'">NEXT</a></li>';
            }



        }else{

            console.log("more");

            if(page == 1){
                pageList += '<li class="disabled"><a href="javascript:void(0);">PREV</a></li>';
            }else {
                pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ (page - 1) +'">PREV</a></li>';
            }

            if(page <= 2){

                for(var j=1;j<=2;j++){

                    if(page == j){
                        className = 'class="active"';
                        pageList += '<li '+ className +'><a href="javascript:void(0);">'+ j +'</a></li>';
                    }else{
                        pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ j +'">'+ j +'</a></li>';
                    }


                }

                pageList += '<li class="disabled"><a href="javascript:void(0);">...</a></li>';

                for(var k=(pageCount - 1);k<=pageCount;k++){

                    if(page == k){
                        className = 'class="active"';
                        pageList += '<li '+ className +'><a href="javascript:void(0);">'+ k +'</a></li>';
                    }else{
                        pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ k +'">'+ k +'</a></li>';
                    }


                }

            }else if(page > 2){

                if(page == pageCount){

                    var mid = Math.ceil(pageCount / 2);

                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="1">1</a></li>';

                    pageList += '<li class="disabled"><a href="javascript:void(0);">...</a></li>';

                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ (mid - 1) +'">'+ (mid - 1) +'</a></li>';

                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ mid +'">'+ mid +'</a></li>';

                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ (mid + 1) +'">'+ (mid + 1) +'</a></li>';

                    pageList += '<li class="disabled"><a href="javascript:void(0);">...</a></li>';

                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ (pageCount - 1) +'">'+ (pageCount - 1) +'</a></li>';

                    pageList += '<li class="active"><a href="javascript:void(0);">'+ pageCount +'</a></li>';

                }else if(page == (pageCount - 1)){

                    var mid = Math.ceil(pageCount / 2);

                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="1">1</a></li>';

                    pageList += '<li class="disabled"><a href="javascript:void(0);">...</a></li>';

                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ (mid - 1) +'">'+ (mid - 1) +'</a></li>';

                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ mid +'">'+ mid +'</a></li>';

                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ (mid + 1) +'">'+ (mid + 1) +'</a></li>';

                    pageList += '<li class="disabled"><a href="javascript:void(0);">...</a></li>';

                    pageList += '<li class="active"><a href="javascript:void(0);">'+ (pageCount - 1) +'</a></li>';

                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ pageCount +'">'+ pageCount +'</a></li>';

                }else{
                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="1">1</a></li>';

                    if(page != 3){
                        pageList += '<li class="disabled"><a href="javascript:void(0);">...</a></li>';
                    }

                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ (page - 1) +'">'+ (page - 1) +'</a></li>';

                    pageList += '<li class="active"><a href="javascript:void(0);">'+ page +'</a></li>';

                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ (page + 1) +'">'+ (page + 1) +'</a></li>';

                    pageList += '<li class="disabled"><a href="javascript:void(0);">...</a></li>';

                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ pageCount +'">'+ pageCount +'</a></li>';
                }

            }

            if(page == pageCount){
                pageList += '<li class="disabled"><a href="javascript:void(0);">NEXT</a></li>';
            }else{
                pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ (page + 1) +'">NEXT</a></li>';
            }
        }

    }

    $('.pagination').empty().append(pageList);

    $('.page-setter').click(function(){

        var that = $(this);

        //let limit = $('select[name="limit"]').find(':selected').val();

        var newPage = that.data('page');

        var newFormData = (limit * (newPage-1));

        /* Refresh the results */
        requester(newFormData, limit, filters,[{ column : column, dir : dir }],true,newPage);

    });
}
