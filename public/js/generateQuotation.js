$(function(){
    let quotationId = $("#quotationId").val();
    let currentVersion = $("#currentVersion").val();
    let generatedId = $("#generatedId").val();
    let versionnumber = $("#version").val();
    let ProjectId = $("#ProjectId1").val();

    SendToClient = function(){
        $('.loader').css({'display':'block'});
        let sendToClientUrl = $("#sendToClientUrl").val();
        let CustomerContactId = $("#customer_id").val();
        if (CustomerContactId == ""){
            swal("Error!", "Select a main contractor first.", "error");
            return false;
        }
        if (currentVersion == 0){
            swal("Error!", "Please generate quotation first.", "error");
            return false;
        }
        if(ProjectId == '' || ProjectId == 0){
            swal("Error!", "Please select project first!", "error");
            $('.loader').css({'display':'none'});
            return false;
        }
        $.ajax({
            type: "POST",
            url: sendToClientUrl,
            data: {
                quotationId:quotationId,
                currentVersion:currentVersion,
                CustomerContactId:CustomerContactId
            },
            dataType: "json",
            success: function(data){
                $('.loader').css({'display':'none'});
                if(data.status == "error"){
                    swal("Oops!", data.message, "error");
                } else if(data.status == "success"){
                    swal("Success!", data.message, "success");
                }

            },
            error: function(data) {
                $('.loader').css({'display':'none'});
                swal("Oops!", "Something went wrong. Please try again.", "error");
            }
        });
    };

    PrintInvoiceInExcel = function(){
        $('.loader').css({'display':'block'});
        let printInvoiceExcelUrl = $("#printInvoiceExcelUrl").val();
        let bomTag = $("#bomTag").val();
        if (currentVersion != 0) {
            // if(bomTag > 0){
                var url = printInvoiceExcelUrl + '/' + quotationId + '/' + currentVersion;
                let filename = "DoorsetExcel.xlsx";
                // window.open(url, '_blank');
                // var request = new XMLHttpRequest();
                // request.onreadystatechange = function() {
                //     console.log(request.readyState);
                //     if(request.readyState == 4) {
                //         if(request.status == 200) {
                //             console.log(request.response); // should be a blob
                //             var blob = request.response;
                //             var link = document.createElement('a');
                //             link.href = window.URL.createObjectURL(blob);
                //             link.download = "DoorsetExcel.pdf";
                //             link.click();
                //             $('.loader').css({'display':'none'});
                //         } else if(request.responseText != "") {
                //             console.log(request.responseText);
                //         }
                //     } else if(request.readyState == 2) {
                //         if(request.status == 200) {
                //             request.responseType = "blob";
                //             var blob = request.response;
                //             var link = document.createElement('a');
                //             link.href = window.URL.createObjectURL(blob);
                //             link.download = "DoorsetExcel.pdf";
                //             link.click();
                //             $('.loader').css({'display':'none'});
                //         } else {
                //             request.responseType = "text";
                //         }
                //     }
                // };
                // request.open("GET", url, true);
                // request.send();

                convertToAudio(url , filename);


                // $.ajax({
                //     type: 'GET',
                //     url: url,
                //     success: function(response){
                //         var blob = new Blob([response]);
                //         var link = document.createElement('a');
                //         link.href = window.URL.createObjectURL(blob);
                //         link.download = "Sample.pdf";
                //         link.click();
                //     },
                //     error: function(blob){
                //         console.log(blob);
                //     }
                // });

            // } else {
            //     $('.loader').css({'display':'none'});
            //     swal("Oops!", "Please generate Bill Of Material.", "error");
            // }
        } else {
            $('.loader').css({'display':'none'});
            swal("Oops!", "You haven't selected any version yet.", "error");
        }
    }

    PrintInvoice = function() {
        $('.loader').css({'display':'block'});
        var printInvoiceUrl = $("#printInvoiceUrl").val();
        var bomTag = $("#bomTag").val();
        if (currentVersion != 0) {
            // if(bomTag > 0){
                    let url = printInvoiceUrl + '/' + quotationId + '/' + currentVersion;
                    var request = new XMLHttpRequest();
                    request.onreadystatechange = function() {
                        if(request.readyState == 4) {
                            if(request.status == 200) {
                                console.log(request.response); // should be a blob
                                var blob = request.response;
                                var link = document.createElement('a');
                                link.href = window.URL.createObjectURL(blob);
                                link.download = "Quote "+generatedId.replace('#','')+"-"+versionnumber+".pdf";
                                link.click();
                                $('.loader').css({'display':'none'});
                            } else if(request.responseText != "") {
                                $('.loader').css({'display':'none'});
                                swal("Oops!", "Please fill the required field to generate the quote!", "error");
                                console.log(request.responseText);
                            }
                        } else if(request.readyState == 2) {
                            if(request.status == 200) {
                                request.responseType = "blob";
                                var blob = request.response;
                                var link = document.createElement('a');
                                link.href = window.URL.createObjectURL(blob);
                                link.download = "Quote"+generatedId+"-"+versionnumber+".pdf";
                                link.click();
                                $('.loader').css({'display':'none'});
                            } else {
                                request.responseType = "text";
                                $('.loader').css({'display':'none'});
                                swal("Oops!", "Please fill the required field to generate the quote!", "error");
                            }
                        }
                    };
                    request.open("GET", url, true);
                    request.send();


                    // var data = '';
                    // $.ajax({
                    //     type: 'GET',
                    //     url: url,
                    //     data: data,
                    //     xhrFields: {
                    //         responseType: 'blob'
                    //     },
                    //     success: function(response){
                    //         console.log(response)
                    //         var blob = new Blob([response], { type: "application/octetstream" });
                    //         var link = document.createElement('a');
                    //         link.href = window.URL.createObjectURL(blob);
                    //         link.download = "GenerateQuote.pdf";
                    //         link.click();
                    //         $('.loader').css({'display':'none'});
                    //     },
                    //     error: function(blob){
                    //         console.log(blob);
                    //     }
                    // });
            // } else {
            //     $('.loader').css({'display':'none'});
            //     swal("Oops!", "Please generate Bill Of Material.", "error");
            // }
        } else {
            $('.loader').css({'display':'none'});
            swal("Oops!", "You haven't selected any version yet.", "error");
        }
    };


    BuildOfMaterial = function(){
        $('.loader').css({'display':'block'});
        let buildofmaterialUrl = $("#buildofmaterialUrl").val();
        let generateBOM = $("#generateBOM").val(); // goto function generateBOM()
        if (currentVersion != 0) {
            $.ajax({
                url: generateBOM,
                type: 'post',
                data: {
                    'quatationId': quotationId,
                    'versionID': currentVersion
                },
                dataType: "json",
                success: function(data) {
                    // console.log(data)
                    if(data.status == "error"){
                        $('.loader').css({'display':'none'});
                        swal("Oops!", data.msg , "error");
                    }
                    if(data.status == "success"){
                        $("#bomTag").val(data.tag);
                        let url = buildofmaterialUrl + '/' + quotationId + '/' + currentVersion+"/"+data.tag;        // goto function generateBOM2()
                        let filename = "BillOfMaterial.pdf";
                        convertToAudio(url , filename);
                    }

                        // window.open(url, '_blank');
                        // $('.loader').css({'display':'none'});

                }
            })
        } else {
            $('.loader').css({'display':'none'});
            swal("Oops!", "You haven't selected any version yet.", "error");
        }
    };

    BomCalculation = function(){
        $('.loader').css({'display':'block'});
        let BomCalculationUrl = $("#BomCalculationUrl").val();
        let BomCalculationUrlGet = $("#BomCalculationUrlGet").val(); // goto function generateBOM()
        if (currentVersion != 0) {
            $.ajax({
                url: BomCalculationUrl,
                type: 'post',
                data: {
                    'quatationId': quotationId,
                    'versionID': currentVersion
                },
                dataType: "json",
                success: function(data) {
                    if(data.status == "error"){
                        $('.loader').css({'display':'none'});
                        swal("Oops!", data.msg , "error");
                    }
                    if(data.status == "success"){
                        let url = BomCalculationUrlGet;     // goto function generateBOM2()
                        let filename = "BOM "+generatedId.replace('#','')+"-"+versionnumber+".pdf";
                        convertToAudio(url , filename);
                    }

                        window.open(url, '_blank');
                        $('.loader').css({'display':'none'});

                }
            })
        } else {
            $('.loader').css({'display':'none'});
            swal("Oops!", "You haven't selected any version yet.", "error");
        }
    };

    ScreenBomCalculation = function(){
        $('.loader').css({'display':'block'});
        let ScreenBomCalculationUrl = $("#ScreenBomCalculationUrl").val();
        let ScreenBomCalculationUrlGet = $("#ScreenBomCalculationUrlGet").val(); // goto function generateBOM()
        if (currentVersion != 0) {
            $.ajax({
                url: ScreenBomCalculationUrl,
                type: 'post',
                data: {
                    'quatationId': quotationId,
                    'versionID': currentVersion
                },
                dataType: "json",
                success: function(data) {
                    if(data.status == "error"){
                        $('.loader').css({'display':'none'});
                        swal("Oops!", data.msg , "error");
                    }
                    if(data.status == "success"){
                        let url = ScreenBomCalculationUrlGet;     // goto function generateBOM2()
                        let filename = "BOM "+generatedId.replace('#','')+"-"+versionnumber+".pdf";
                        convertToAudio(url , filename);
                    }

                        window.open(url, '_blank');
                        $('.loader').css({'display':'none'});

                }
            })
        } else {
            $('.loader').css({'display':'none'});
            swal("Oops!", "You haven't selected any version yet.", "error");
        }
    };

    DoorOrderSheet = function(){
        $('.loader').css({'display':'block'});
        let DoorOrderSheet = $("#DoorOrderSheet").val(); // goto function DoorOrderSheet()
        let DoorOrderSheetUrl = $("#DoorOrderSheetUrl").val(); // goto function DoorOrderSheet()

        if (currentVersion != 0) {
            $.ajax({
                url: DoorOrderSheetUrl,
                type: 'post',
                data: {
                    'quatationId': quotationId,
                    'versionID': currentVersion
                },
                dataType: "json",
                success: function(data) {
                    if(data.status == "error"){
                        $('.loader').css({'display':'none'});
                        swal("Oops!", data.msg , "error");
                    }
                    if(data.status == "success"){
                        let url = DoorOrderSheet;     // goto function DoorOrderSheet()
                        let filename = "DoorOrderSheet"+generatedId.replace('#','')+"-"+versionnumber+".pdf";
                        convertToAudio(url , filename);
                    }

                    window.open(url, '_blank');
                    $('.loader').css({'display':'none'});
                }
            });
        } else {
            $('.loader').css({'display':'none'});
            swal("Oops!", "You haven't selected any version yet.", "error");
        }
    };

    FrameTransoms = function(){
        $('.loader').css({'display':'block'});
        let FrameTransoms = $("#FrameTransoms").val(); // goto function FrameTransoms()
        let FrameTransomsUrl = $("#FrameTransomsUrl").val(); // goto function FrameTransoms()

        if (currentVersion != 0) {
            $.ajax({
                url: FrameTransomsUrl,
                type: 'post',
                data: {
                    'quatationId': quotationId,
                    'versionID': currentVersion
                },
                dataType: "json",
                success: function(data) {
                    if(data.status == "error"){
                        $('.loader').css({'display':'none'});
                        swal("Oops!", data.msg , "error");
                    }
                    if(data.status == "success"){
                        let url = FrameTransoms;     // goto function DoorOrderSheet()
                        let filename = "FrameTransoms"+generatedId.replace('#','')+"-"+versionnumber+".pdf";
                        convertToAudio(url , filename);
                    }

                    window.open(url, '_blank');
                    $('.loader').css({'display':'none'});
                }
            });
        } else {
            $('.loader').css({'display':'none'});
            swal("Oops!", "You haven't selected any version yet.", "error");
        }
    };

    GlassOrderSheet = function(){
        $('.loader').css({'display':'block'});
        let GlassOrderSheet = $("#GlassOrderSheet").val(); // goto function FrameTransoms()
        let FrameTransomsUrl = $("#FrameTransomsUrl").val(); // goto function FrameTransoms()

        if (currentVersion != 0) {
            $.ajax({
                url: FrameTransomsUrl,
                type: 'post',
                data: {
                    'quatationId': quotationId,
                    'versionID': currentVersion
                },
                dataType: "json",
                success: function(data) {
                    if(data.status == "error"){
                        $('.loader').css({'display':'none'});
                        swal("Oops!", data.msg , "error");
                    }
                    if(data.status == "success"){
                        let url = GlassOrderSheet;     // goto function DoorOrderSheet()
                        let filename = "GlassOrderSheet"+generatedId.replace('#','')+"-"+versionnumber+".pdf";
                        convertToAudio(url , filename);
                    }

                    window.open(url, '_blank');
                    $('.loader').css({'display':'none'});
                }
            });
        } else {
            $('.loader').css({'display':'none'});
            swal("Oops!", "You haven't selected any version yet.", "error");
        }
    };

    GlazingBeadsDoors = function(){
        $('.loader').css({'display':'block'});
        let GlazingBeadsDoors = $("#GlazingBeadsDoors").val(); // goto function FrameTransoms()
        let FrameTransomsUrl = $("#FrameTransomsUrl").val(); // goto function FrameTransoms()

        if (currentVersion != 0) {
            $.ajax({
                url: FrameTransomsUrl,
                type: 'post',
                data: {
                    'quatationId': quotationId,
                    'versionID': currentVersion
                },
                dataType: "json",
                success: function(data) {
                    if(data.status == "error"){
                        $('.loader').css({'display':'none'});
                        swal("Oops!", data.msg , "error");
                    }
                    if(data.status == "success"){
                        let url = GlazingBeadsDoors;     // goto function DoorOrderSheet()
                        let filename = "GlazingBeadsDoors"+generatedId.replace('#','')+"-"+versionnumber+".pdf";
                        convertToAudio(url , filename);
                    }

                    window.open(url, '_blank');
                    $('.loader').css({'display':'none'});
                }
            });
        } else {
            $('.loader').css({'display':'none'});
            swal("Oops!", "You haven't selected any version yet.", "error");
        }
    };


    OMmanualQuotation = function($QuotationId , $QVID , $projectId=null){
        $('.loader').css({'display':'block'});
        let ommanual = $('#ommanual').val();
        let url = ommanual + '/' + $QuotationId + '/' + $QVID;
        let filename = "OMmanual.pdf";
        // if($projectId > 0){
            convertToAudio(url , filename);
        // } else {
        //     $('.loader').css({'display':'none'});
        //     alert('These quotation is not have project.');
        // }

        // $('.loader').css({'display':'none'});
    };

    function convertToAudio(url,filename) {
        var request = new XMLHttpRequest();
        request.onreadystatechange = function() {
            if(request.readyState == 4) {
                if(request.status == 200) {
                    console.log(typeof request.response); // should be a blob
                    var blob = request.response;
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;
                    link.click();
                    $('.loader').css({'display':'none'});
                } else if(request.responseText != "") {
                    console.log(request.responseText);
                }
            } else if(request.readyState == 2) {
                if(request.status == 200) {
                    request.responseType = "blob";
                    var blob = request.response;
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;
                    link.click();
                    $('.loader').css({'display':'none'});
                } else {
                    request.responseType = "text";
                }
            }
        };
        request.open("GET", url, true);
        request.send();
    }

    QualityControl = function() {
        $('.loader').css({'display':'block'});
        let QualityControlUrl = $("#QualityControlUrl").val();
        var bomTag = $("#bomTag").val();
        if (currentVersion != 0) {
            // if(bomTag > 0){
                    let url = QualityControlUrl;
                    var request = new XMLHttpRequest();
                    request.onreadystatechange = function() {
                        if(request.readyState == 4) {
                            if(request.status == 200) {
                                console.log(request.response); // should be a blob
                                var blob = request.response;
                                var link = document.createElement('a');
                                link.href = window.URL.createObjectURL(blob);
                                link.download = "QualityControl "+generatedId.replace('#','')+"-"+versionnumber+".pdf";
                                link.click();
                                $('.loader').css({'display':'none'});
                            } else if(request.responseText != "") {
                                $('.loader').css({'display':'none'});
                                swal("Oops!", "Please fill the required field to generate the quote!", "error");
                                console.log(request.responseText);
                            }
                        } else if(request.readyState == 2) {
                            if(request.status == 200) {
                                request.responseType = "blob";
                                var blob = request.response;
                                var link = document.createElement('a');
                                link.href = window.URL.createObjectURL(blob);
                                link.download = "Quote"+generatedId+"-"+versionnumber+".pdf";
                                link.click();
                                $('.loader').css({'display':'none'});
                            } else {
                                request.responseType = "text";
                                $('.loader').css({'display':'none'});
                                swal("Oops!", "Please fill the required field to generate the quote!", "error");
                            }
                        }
                    };
                    request.open("GET", url, true);
                    request.send();


                    // var data = '';
                    // $.ajax({
                    //     type: 'GET',
                    //     url: url,
                    //     data: data,
                    //     xhrFields: {
                    //         responseType: 'blob'
                    //     },
                    //     success: function(response){
                    //         console.log(response)
                    //         var blob = new Blob([response], { type: "application/octetstream" });
                    //         var link = document.createElement('a');
                    //         link.href = window.URL.createObjectURL(blob);
                    //         link.download = "GenerateQuote.pdf";
                    //         link.click();
                    //         $('.loader').css({'display':'none'});
                    //     },
                    //     error: function(blob){
                    //         console.log(blob);
                    //     }
                    // });
            // } else {
            //     $('.loader').css({'display':'none'});
            //     swal("Oops!", "Please generate Bill Of Material.", "error");
            // }
        } else {
            $('.loader').css({'display':'none'});
            swal("Oops!", "You haven't selected any version yet.", "error");
        }
    };
})

$("#CustomerContactIdSTC_Name, #CustomerContactIdSTC").on('change',function(){
    var url = $("#get_contact_details").val();
    $.ajax({
        url:url,
        type:"POST",
        data:{id:$(this).val()},
        success:function(data){
            var customer_list = data.data.map((row)=>(`<option ${selected_function(row.id, data.selected_data.id)} value="${row.id},${row.MainContractorId}">${row.FirstName} ${row.LastName}</option>`));
            // alert(customer_list)
            $("#CustomerContactIdSTC_Email").val(data.selected_data.ContactEmail)
            $("#CustomerContactIdSTC_Phone").val(data.selected_data.ContactPhone)
            $("#CustomerContactIdSTC_Name").empty().append(customer_list);
            $("#CustomerContactIdSTC").empty().append(customer_list);
        }
    })
})


function selected_function(id, selected_id){
    if(id==selected_id){
        return 'selected';
    }
}
