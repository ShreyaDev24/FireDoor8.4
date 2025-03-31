function pagination2(data){    
    var pageList = '';
    var className = '';
    console.log(limit2)
    console.log(data)
    if(limit2 >= data){  
        pageList += '<li class="disabled"><a href="javascript:void(0);">PREV</a></li>';
        pageList += '<li class="active"><a href="javascript:void(0);">1</a></li>';
        pageList += '<li class="disabled"><a href="javascript:void(0);">NEXT</a></li>';
        console.log(pageList)
    } else { 
        var pageCount = Math.ceil(data / limit2);
        console.log(pageCount);
        if(pageCount <= 7){
            console.log("less");
            if(page2 == 1){
                pageList += '<li class="disabled"><a href="javascript:void(0);">PREV</a></li>';
            }else {
                pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ (page2 - 1) +'">PREV</a></li>';
            }
            for(var i=1;i<=pageCount;i++){

                if(page2 == i){
                    className = 'class="active"';
                    pageList += '<li '+ className +'><a href="javascript:void(0);">'+ i +'</a></li>';
                }else{
                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ i +'">'+ i +'</a></li>';
                }
            }
            if(page2 == pageCount){
                pageList += '<li class="disabled"><a href="javascript:void(0);">NEXT</a></li>';
            }else{
                pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ (page2 + 1) +'">NEXT</a></li>';
            }
        } else {
            console.log("more");

            if(page2 == 1){
                pageList += '<li class="disabled"><a href="javascript:void(0);">PREV</a></li>';
            }else {
                pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ (page2 - 1) +'">PREV</a></li>';
            }

            if(page2 <= 2){

                for(var j=1;j<=2;j++){

                    if(page2 == j){
                        className = 'class="active"';
                        pageList += '<li '+ className +'><a href="javascript:void(0);">'+ j +'</a></li>';
                    }else{
                        pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ j +'">'+ j +'</a></li>';
                    }


                }

                pageList += '<li class="disabled"><a href="javascript:void(0);">...</a></li>';

                for(var k=(pageCount - 1);k<=pageCount;k++){

                    if(page2 == k){
                        className = 'class="active"';
                        pageList += '<li '+ className +'><a href="javascript:void(0);">'+ k +'</a></li>';
                    }else{
                        pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ k +'">'+ k +'</a></li>';
                    }


                }

            }else if(page > 2){

                if(page2 == pageCount){

                    var mid = Math.ceil(pageCount / 2);

                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="1">1</a></li>';

                    pageList += '<li class="disabled"><a href="javascript:void(0);">...</a></li>';

                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ (mid - 1) +'">'+ (mid - 1) +'</a></li>';

                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ mid +'">'+ mid +'</a></li>';

                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ (mid + 1) +'">'+ (mid + 1) +'</a></li>';

                    pageList += '<li class="disabled"><a href="javascript:void(0);">...</a></li>';

                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ (pageCount - 1) +'">'+ (pageCount - 1) +'</a></li>';

                    pageList += '<li class="active"><a href="javascript:void(0);">'+ pageCount +'</a></li>';

                }else if(page2 == (pageCount - 1)){

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

                    if(page2 != 3){
                        pageList += '<li class="disabled"><a href="javascript:void(0);">...</a></li>';
                    }

                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ (page2 - 1) +'">'+ (page2 - 1) +'</a></li>';

                    pageList += '<li class="active"><a href="javascript:void(0);">'+ page2 +'</a></li>';

                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ (page2 + 1) +'">'+ (page2 + 1) +'</a></li>';

                    pageList += '<li class="disabled"><a href="javascript:void(0);">...</a></li>';

                    pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ pageCount +'">'+ pageCount +'</a></li>';
                }

            }

            if(page2 == pageCount){
                pageList += '<li class="disabled"><a href="javascript:void(0);">NEXT</a></li>';
            }else{
                pageList += '<li><a href="javascript:void(0);" class="page-setter" data-page="'+ (page2 + 1) +'">NEXT</a></li>';
            }
        }

    }

    $('.pagination').empty().append(pageList);

    $('.page-setter').click(function(){

        var that = $(this); 

        var newPage = that.data('page');

        var newFormData = (limit2 * (newPage-1)); 

        let filters = {};
        let dir = 'desc';
        /* Refresh the results */
        // requester(newFormData, limit, filters,[{ column : column, dir : dir }],true,newPage);
        OnloadFilter(newFormData,filters, dir,newPage)
    });
}