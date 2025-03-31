$(function(){
    $(document).on('click','.Crossjs',function(){
        $(this).siblings('.input-group').children('input[type="text"]').val('');
        let id = $(this).siblings('.input-group').children('input[type="hidden"]').val();
        let price = $(this).siblings('.input-group').children('.price').val();
        let qty = $(this).siblings('.qty').val();
        $(this).siblings('.qty').attr('required',false);
        $(this).siblings('.input-group').children('input[type="hidden"]').val('');
        let totalprice = $('#totalprice').val();

        if(price != '' && qty != ''){
            let singlePrice = price*qty;
            let AppendAmount = totalprice-singlePrice;
            $('#totalprice').val(AppendAmount);
            $(this).siblings('.input-group').children('input[type="hidden"]').val('');
            $(this).siblings('.qty').val('');
        }
    })
})
