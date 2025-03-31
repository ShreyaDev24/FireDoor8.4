$(document).ready(function() {
        $("#standardleaf1VisionPanel").change(function(){
            if($(this).val()=="Yes"){
                $("#standardvisionPanelQuantity").attr('disabled',false);
                $("#standardvisionPanelQuantity").attr('required',true);
                $("#standardleaf1VisionPanelShape").attr('readonly',false);
                $("#standardleaf1VisionPanelShape").attr('required',true);
                $("#standardvP1Width").attr('readonly',true);
                $("#standardvP1Height1").attr('readonly',true);
                $("#standardvP1Width").attr('required',true);
                $("#standardvP1Height1").attr('required',true);
                $("#standarddistanceFromTopOfDoor").attr('readonly',false);
                $("#standarddistanceFromTheEdgeOfDoor").attr({'required':true,'readonly':false});
            } else {
                $("#standardvisionPanelQuantity").val('').attr({'disabled':true,'required':false});
                $("#standardleaf1VisionPanelShape").val('').attr({'readonly':true,'required':false});
                //$("#standardAreVPsEqualSizes").val('').attr({'disabled':true,'required':false});
                $("#standardAreVPsEqualSizes").val('').attr({'disabled':true});
                $("#standardvP1Width").val('').attr({'required':false,'readonly':true});
                $("#standardvP1Height1").val('').attr({'required':false,'readonly':true});
                $("#standarddistanceFromTopOfDoor").val('').attr('readonly',true);
                $("#standarddistanceFromTheEdgeOfDoor").val('').attr({'readonly':true , 'required':false});
                $("#standarddistanceBetweenVPs").val('').attr({'required':false,'readonly':true});
                for(var i=2;i<=5;i++){
                    $("#standardvP1Height"+i).attr({'required':false,'readonly':true}).val("");
                }
                $('#standardleaf1VisionPanelShape').val('').attr({'readonly':true,'required':false}).val("");
            }
        });

        $("#standardvisionPanelQuantity").change(function(){
            if(parseInt($(this).val()) > 1){
                $("#standarddistanceBetweenVPs").attr('readonly',false);
                $("#standarddistanceBetweenVPs").attr('required',true);
                $("#standardvP1Width").attr('readonly',false);
                $("#standardvP1Width").attr('required',true);
                $("#standarddistanceFromTopOfDoor").attr({'required':true,'readonly':false});
                var num =parseInt($(this).val());
                var isEqualSize = $("#standardAreVPsEqualSizes").val();
                $("#standardvP1Height1").attr('readonly',false);
                $("#standardvP1Height1").attr('required',true);
                $("#standardAreVPsEqualSizes").attr({'required':true,'readonly':false});
                // var previousNumber =0;
                if(isEqualSize == 'Yes'){
                    for(var i=2;i<=5;i++){
                        $("#standardvP1Height"+i).val("").attr({'readonly':true,'required':false});
                    }

                    for(var j=2;j<=num;j++){
                        $("#standardvP1Height"+j).val($("#standardvP1Height1").val());
                    }
                }else{
                    for(var i=1;i<=num;i++){
                        $("#standardvP1Height"+i).attr('readonly',false);
                        $("#standardvP1Height"+i).attr('required',true);
                    }
                    for(var j=(num+1);j<=5;j++){
                        $("#standardvP1Height"+j).val('').attr({'readonly':true,'required':false});
                    }
                }
                $("#standarddistanceFromTopOfDoor").attr({'required':true,'readonly':false});
                $("#standardAreVPsEqualSizes").attr('disabled',false);
            } else {

                $("#standardAreVPsEqualSizes").attr({'disabled':true,'required':false,'readonly':true}).val('');
                $("#standarddistanceBetweenVPs").attr('required',false);
                $("#standarddistanceBetweenVPs").attr('readonly',true);
                // $("#standardvP1Width").val('');
                $("#standardvP1Width").attr('readonly',false);
                $("#standardvP1Width").attr('required',true);
                $("#standardvP1Height1").attr('required',true);
                $("#standardvP1Height1").attr('readonly',false);
                $("#standarddistanceBetweenVPs").val(0);

                for(var i=2;i<=5;i++){
                    $("#standardvP1Height"+i).val('').attr({'readonly':true,'required':false});
                }
            }
        });

        $("#standardAreVPsEqualSizes").change(function(){
            $("#standardvP1Width").attr('readonly',false);
            $("#standardvP1Width").attr('required',true);
            $("#standarddistanceFromTopOfDoor").attr({'required':true,'readonly':false});
            var VisionPanelQuantity = parseInt($("#standardvisionPanelQuantity").val());
            if($(this).val()=="Yes"){
                $("#standardvP1Height1").attr('readonly',false);
                $("#standardvP1Height1").attr('required',true);

                for(var i=2;i<=5;i++){
                    $("#standardvP1Height"+i).attr({'readonly':true,'required':false});
                }

                for(var j=2;j<=VisionPanelQuantity;j++){
                    $("#standardvP1Height"+j).val($("#standardvP1Height1").val());
                }
            } else {

                if(VisionPanelQuantity > 1){
                    for(var j=1;j<=VisionPanelQuantity;j++){
                        $("#standardvP1Height"+j).attr({'readonly':false,'required':true});
                    }
                    for(var i=parseInt(VisionPanelQuantity)+1;i<=5;i++){
                        $("#standardvP1Height"+i).val('').attr({'readonly':true,'required':false});
                    }
                } else {
                    for(var i=2;i<=5;i++){
                        $("#standardvP1Height"+i).attr({'readonly':true,'required':false});
                    }
                }
            }
        });

        $("#standardleaf2VisionPanel").change(function(){
            if($(this).val()=="Yes"){
                $("#standardvpSameAsLeaf1").attr({'disabled':false,'required':true});
                $("#standardvisionPanelQuantityforLeaf2").attr({'disabled':false,'required':true});
                // $("#standardAreVPsEqualSizesForLeaf2").attr({'disabled':false,'required':true});
                $("#standarddistanceFromTopOfDoorforLeaf2").attr({'required':true,'readonly':false});
                $("#standarddistanceFromTheEdgeOfDoorforLeaf2").attr({'required':true,'readonly':false});
                // $("#standardvP2Width").attr({'readonly':false,'required':true}).val();
            } else {
                $("#standardvpSameAsLeaf1").attr({'disabled':true,'required':false}).val('');
                $("#standardvisionPanelQuantityforLeaf2").attr({'disabled':true,'required':false}).val('');
                $("#standardAreVPsEqualSizesForLeaf2").attr({'disabled':true,'required':false}).val('');
                $("#standarddistanceFromTopOfDoorforLeaf2").attr({'required':false,'readonly':true});
                $("#standarddistanceFromTheEdgeOfDoorforLeaf2").attr({'required':false,'readonly':true});

                $("#standarddistanceBetweenVPsforLeaf2").attr({'readonly':true,'required':false}).val('');

                $("#standardvP2Width").attr({'readonly':true,'required':false}).val("");
                for(var index=1;index<=5;index++){
                    $("#standardvP2Height"+index).attr({'readonly':true,'required':false}).val('');
                }
            }
        });

        $("#standardvpSameAsLeaf1").change(function(){
            if($(this).val()=="Yes"){
                $("#standardvisionPanelQuantityforLeaf2").attr({'disabled':true,'required':false}).val($("#standardvisionPanelQuantity").val());
                $("#standardAreVPsEqualSizesForLeaf2").attr({'disabled':true,'required':false}).val($("#standardAreVPsEqualSizes").val());

                $("#standarddistanceFromTopOfDoorforLeaf2").attr({'readonly':true,'required':false}).val($("#standarddistanceFromTopOfDoor").val());
                $("#standarddistanceFromTheEdgeOfDoorforLeaf2").attr({'readonly':true,'required':false}).val($("#standarddistanceFromTheEdgeOfDoor").val());
                $("#standarddistanceBetweenVPsforLeaf2").attr({'readonly':true,'required':false}).val($("#standarddistanceBetweenVPs").val());

                $("#standardvP2Width").attr({'readonly':true,'required':false}).val($("#standardvP1Width").val());
                for(var index=1;index<=5;index++){
                    $("#standardvP2Height"+index).attr({'readonly':true,'required':false}).val($("#standardvP1Height"+index).val());
                }
            } else {
                $("#standardvisionPanelQuantityforLeaf2").attr({'disabled':false,'required':true}).val('');
                // $("#standardAreVPsEqualSizesForLeaf2").attr({'disabled':false,'required':true}).val('');

                $("#standarddistanceFromTopOfDoorforLeaf2").attr({'readonly':false,'required':true});
                $("#standarddistanceFromTheEdgeOfDoorforLeaf2").attr({'readonly':false,'required':true});
                // $("#standarddistanceBetweenVPsforLeaf2").attr({'readonly':false,'required':true}).val('');

                $("#standardvP2Width").attr({'readonly':false,'required':true}).val('');
                // for(var index=1;index<=5;index++){
                //     $("#standardvP2Height"+index).attr({'readonly':false,'required':true}).val('');
                // }
            }
        });

        $("#standardvisionPanelQuantityforLeaf2").change(function(){
            if(parseInt($(this).val())>1){

                $("#standardAreVPsEqualSizesForLeaf2").attr({'disabled':false,'required':true});

                $("#standarddistanceBetweenVPsforLeaf2").attr('readonly',false);
                $("#standarddistanceBetweenVPsforLeaf2").attr('required',true);
                $("#standardvP2Width").attr('readonly',false);
                $("#standardvP2Width").attr('required',true);
                var num =parseInt($(this).val());
                var isEqualSize = $("#standardAreVPsEqualSizesForLeaf2").val();
                $("#standardvP2Height1").attr('readonly',false);
                $("#standardvP2Height1").attr('required',true);
                // var previousNumber =0;
                if(isEqualSize == 'Yes'){
                    for(var i=2;i<=5;i++){
                        $("#standardvP2Height"+i).val('').attr({'readonly':true,'required':false});
                    }
                    for(var j=2;j<=num;j++){
                        $("#standardvP2Height"+j).val($("#standardvP2Height1").val());
                    }
                }else{
                    for(var i=1;i<=num;i++){
                        $("#standardvP2Height"+i).attr('readonly',false);
                        $("#standardvP2Height"+i).attr('required',true);
                    }
                    for(var j=(num+1);j<=5;j++){
                        $("#standardvP2Height"+j).val('').attr({'readonly':true,'required':false});
                    }
                }

            } else {
                $("#standardAreVPsEqualSizesForLeaf2").attr({'disabled':true,'required':false});
                $("#standarddistanceBetweenVPsforLeaf2").attr({'readonly':true,'required':false});
                // $("#standardvP1Width").val('');
                $("#standardvP2Width").attr('readonly',false);
                $("#standardvP2Width").attr('required',true);
                $("#standardvP2Height1").attr('required',true);
                $("#standardvP2Height1").attr('readonly',false);
                $("#standarddistanceBetweenVPsforLeaf2").val(80);

                for(var i=2;i<=5;i++){
                    $("#standardvP2Height"+i).val('').attr({'readonly':true,'required':false});
                }
            }
        });

        $("#standardAreVPsEqualSizesForLeaf2").change(function(){
            $("#standardvP2Width").attr('readonly',false);
            $("#standardvP2Width").attr('required',true);

            var VisionPanelQuantity = parseInt($("#standardvisionPanelQuantityforLeaf2").val());
            if($(this).val()=="Yes"){
                $("#standardvP2Height1").attr('readonly',false);
                $("#standardvP2Height1").attr('required',true);

                for(var i=2;i<=5;i++){
                    $("#standardvP2Height"+i).attr({'readonly':true,'required':false});
                }

                for(var j=2;j<=VisionPanelQuantity;j++){
                    $("#standardvP2Height"+j).val($("#standardvP2Height1").val());
                }
            } else {

                if(VisionPanelQuantity > 1){
                    for(var j=1;j<=VisionPanelQuantity;j++){
                        $("#standardvP2Height"+j).attr({'readonly':false,'required':true});
                    }
                    for(var i=parseInt(VisionPanelQuantity)+1;i<=5;i++){
                        $("#standardvP2Height"+i).val('').attr({'readonly':true,'required':false});
                    }
                } else {
                    for(var i=2;i<=5;i++){
                        $("#standardvP2Height"+i).attr({'readonly':true,'required':false});
                    }
                }
            }
        });
        $("#standardvP1Height1").change(function(){

            var num = parseInt($("#standardvisionPanelQuantity").val());
            var isEqualSize = $("#standardAreVPsEqualSizes").val();

            if(isEqualSize == 'Yes'){

                for(var j=2;j<=num;j++){
                    $("#standardvP1Height"+j).val($(this).val());
                }
            }else{
                for(var j=(num+1);j<=5;j++){
                    $("#standardvP1Height"+j).val('');
                }
            }

        });

        $("#standardvP2Height1").change(function(){

            var num = parseInt($("#standardvisionPanelQuantityforLeaf2").val());
            var isEqualSize = $("#standardAreVPsEqualSizesForLeaf2").val();

            if(isEqualSize == 'Yes'){

                for(var j=2;j<=num;j++){
                    $("#standardvP2Height"+j).val($(this).val());
                }
            }else{
                for(var j=(num+1);j<=5;j++){
                    $("#standardvP2Height"+j).val('');
                }
            }

        });

        $("#standardArchitrave").change(function(){
            let ArcFin = $('#standardarchitraveFinish').val();
            if($(this).val()=="Yes"){
                $("#standardarchitraveMaterial").attr({'readonly':true,'required':true}).val('');
                $("#standardarchitraveMaterial").addClass('bg-white');
                $('#standardarchitraveMaterialIcon').attr('onclick', "return standardArchitraveMaterial()");
                $("#standardarchitraveType").attr({'disabled':false,'required':true}).val('');
                $("#standardarchitraveWidth").attr({'readonly':false,'required':true}).val('');
                // $("#standardarchitraveDepth").attr({'readonly':false,'required':true}).val('');
                $("#standardarchitraveFinish").attr({'disabled':false,'required':true}).val('');
                $("#standardarchitraveSetQty").attr({'disabled':false,'required':true}).val('');
                $("#standardarchitraveHeight").attr({'readonly':false,'required':true}).val('');
                $("#standardarchitraveFinishcolor").attr('readonly',true).val('');
                if(ArcFin == 'Painted_Finish'){
                    $("#standardarchitraveFinishcolor").addClass('bg-white');
                    $("#standardarchitraveFinishcolorIcon").attr('onclick', "return ArchitraveFinishColor()");
                }
            } else {
                $("#standardarchitraveMaterial").attr({'readonly':true,'required':false}).val('');
                $("#standardarchitraveMaterial").removeClass('bg-white');
                $("#standardarchitraveMaterial").val('');
                $('#standardarchitraveMaterialIcon').attr('onclick','');
               // $('input[name="architraveMaterial"]').val('');
                $("#standardarchitraveType").attr({'disabled':true,'required':false}).val('');
                $("#standardarchitraveWidth").attr({'readonly':true,'required':false}).val('');
                // $("#standardarchitraveDepth").attr({'readonly':true,'required':false}).val('');
                $("#standardarchitraveFinish").attr({'disabled':true,'required':false}).val('');
                $("#standardarchitraveSetQty").attr({'disabled':true,'required':false}).val('');
                $("#standardarchitraveHeight").attr({'readonly':true,'required':false}).val('');
                $("#standardarchitraveFinishcolor").attr('disabled',true).val('');
                $("#standardarchitraveFinishcolor").removeClass('bg-white');
                $('#standardarchitraveFinishcolorIcon').attr('onclick','');
             //   $('input[name="architraveFinishcolor"]').val('');
            }
        });

        let ArcFin = $('#standardArchitrave').val();
        if(ArcFin=="Yes"){
            $("#standardarchitraveMaterial").attr({'readonly':false,'required':true});
            $("#standardarchitraveMaterial").addClass('bg-white');
            $('#standardarchitraveMaterialIcon').attr('onclick', "return standardArchitraveMaterial()");
        } else {
            $("#standardarchitraveMaterial").attr({'readonly':true,'required':false}).val('');
        }

    });

    function standardArchitraveMaterial(){
        $('#standardUniversalModalLabel').html('Architrave Material');
        $('.standardinputIdentity').val('ArchitraveMaterial');
        // $('#UniversalModal').modal('show');
        standardarchitrave();

    }

    function standardarchitrave(isModal=0) {
        var architraveMaterialNew = $('#standardarchitraveMaterialNew').val();
        var url = $("#architrave-system-filter").html();
        $.ajax({
            url: url,
            method: "POST",
            dataType: "Json",
            data: {  _token: $("#_token").val(), pageId: 4 },
            success: function (result) {
                if (result.status == "ok") {

                    var innerHtml = '';
                    var leepingSpecies = result.lippingSpecies;

                    if (leepingSpecies != '') {
                        var leepingSpecieslength = result.lippingSpecies.length;
                        var innerHtml1 = "";
                        innerHtml1 += '<div class="container"><div class="row">';
                        var costToShow = 0;
                        for (var j = 0; j < leepingSpecieslength; j++) {
                            var filepath = $("input[name='base_url']").val() + "/uploads/Options/" + leepingSpecies[j].file;

                            innerHtml1 += '<div class="col-md-2 col-sm-4 col-6 cursor-pointer" onclick="standardGlazingValueFill(' + leepingSpecies[j].id + ',\'' + leepingSpecies[j].SpeciesName + '\',\'#standardglazingModal\',' + costToShow + ')">'
                                + '<div class="color_box">'
                                + '<div class="frameMaterialImage"><img width="100%" height="100" src="' + filepath + '"></div>'
                                + '<h4>' + leepingSpecies[j].SpeciesName + '</h4>'
                                + '</div></div>';

                            $("#standardUniversalModalBody").empty().append(innerHtml1);
                            if (architraveMaterialNew != null) {
                                if (architraveMaterialNew == leepingSpecies[j].id) {
                                    $("#standardarchitraveMaterial").val(leepingSpecies[j].SpeciesName);
                                }
                            }
                        }
                        if(isModal == 0){
                            $('#standardUniversalModal').modal('show');
                        }
                    }



                } else {
                    innerHtmlPopUp += 'No Frame architrave Found';

                    $("#standardarchitraveMaterial").empty().append(innerHtml);
                }
            }
        });
    }

    function standardGlazingValueFill(id,value,modalId, cost ="0.00"){
        let inputIdentity = $('.standardinputIdentity').val();
        if (inputIdentity == 'ArchitraveMaterial') {
            $('#standardarchitraveMaterial').val(value);
            $('input[name="architraveMaterial"]').val(id);
            $('#standardUniversalModal').modal('hide');
            $('#inputId').val('architraveMaterial');
        }else{
            const inputId = $('#standardinputId').val();
            $('input[id='+inputId+']').val(value);
            if(inputId == "StandardlippingSpecies"){
                $('input[name=lippingSpecies]').val(id);
            }else{
                $('input[name='+inputId+']').val(id);
            }
            $(modalId).modal('hide');
        }

        // alert($('#lippingThickness').val());

        let lippingThickness =  $('#lippingThickness').val();
        // let lippingThickness =  1;

        var SelectedLippingSpecies = JSON.parse(SelectedLippingSpeciesJson);
        // var price = 0;


        SelectedLippingSpecies.forEach(function(elem, index) {
            if(id == elem.id){
                // alert(elem.id);

                // console.log(SelectedLippingSpecies);

                elem.lipping_species_items.forEach(function(elem1) {

                    elem1.selected_lipping_species_items.forEach(function(elem2) {
                        if(lippingThickness == elem2.selected_thickness){
                            // alert(elem2.selected_price);
                            cost = elem2.selected_price;
                        }

                    });
                });

            }
        });

    }

    standardfilterHandling();
    $("#standarddoorsetType, #standardswingType").on("change",function(){
        standardfilterHandling();
    });

    function standardfilterHandling(){
        let pageId = pageIdentity();
        var doorsetType = $("#standarddoorsetType").val();
        var swingType = $("#standardswingType").val();
        if(pageId == ''){
            swal('Warning','Somethings went wrong!');
            return false;
        }
        $.ajax({
            url: $("#get-handing-options").text(),
            method:"POST",
            dataType:"Json",
            data:{ pageId:4,doorsetType: doorsetType, swingType: swingType,_token:$("#_token").val()},
            success: function(result){
                var innerHtml ="";
                if(result.status=="ok"){
                    var  data = result.data;
                    var  length = data.length;
                    innerHtml+='<option value="">Select Handing</option>';
                    var HandingValue = document.getElementById('Handing-value');
                    if (HandingValue != null) {
                        HandingValue = $("#standardHanding-value").data("value");
                        for(var i=0; i< length ;i++){
                            var selected = "";
                            if(HandingValue == data[i].OptionKey){
                                selected = "selected";
                            }
                            innerHtml+='<option value="'+data[i].OptionKey+'" '+ selected +'>'+data[i].OptionValue+'</option>';
                        }
                    }else{
                        for(var i=0; i< length ;i++){
                            innerHtml+='<option value="'+data[i].OptionKey+'">'+data[i].OptionValue+'</option>';
                        }
                    }
                }else{
                    innerHtml+='<option value="">No Handing Found</option>';
                }
                $("#standardHanding").empty().append(innerHtml);
            },
            error: function(err){

            }
        });
    }

    StandardFireRatingChange();
    StandardglazingSystemFIlter($("#StandardfireRating").val());
    standardOnlyLipingSpecies($("#StandardfireRating").val())
    $("#StandardfireRating").change(function(){
        StandardFireRatingChange();
        StandardglazingSystemFIlter($("#StandardfireRating").val());
        standardOnlyLipingSpecies($("#StandardfireRating").val());

    });

    function StandardFireRatingChange(){
        StandardframeMaterialFilter($("#StandardfireRating").val());
    }

    function StandardframeMaterialFilter(fireRating){
        $.ajax({
            url: $("#frame-material-filter").html(),
            method:"POST",
            dataType:"Json",
            data:{pageId:4,fireRating:fireRating,_token:$("#_token").val()},
            success: function(result){
                if(result.status=="ok"){
                    var innerHtml ='';
                    var data = result.data;
                    var length = result.data.length;
                    var leepingSpecies = result.leepingSpecies;



                    var FrameMaterialValue = document.getElementById('StandardFrameMaterial-value');
                    var innerHtmlPopUp='<div class="container"><div class="row">';
                    for(var i =0; i<length;i++){
                        var filepath = $("input[name='base_url']").val()+"/uploads/Options/"+data[i].file;
                        var url = "{{route('project/get-project-list')}}";

                        //innerHtml+='<option value="'+data[i].OptionKey+'">'+data[i].OptionValue+'</option>'

                        innerHtmlPopUp+='<div class="col-md-2 col-sm-4 col-6 cursor-pointer" onClick="StandardSelectValueFill(\'#StandardframeMaterial\',\''+data[i].OptionKey+'\',\''+data[i].OptionValue+'\',\'#frameMaterialModal\')">';
                        innerHtmlPopUp+='<div class="color_box">';
                        innerHtmlPopUp+='<div class="frameMaterialImage"><img width="100%" height="100" src="'+filepath+'"></div>';
                        innerHtmlPopUp+=' <h4>'+data[i].OptionValue+'</h4>';
                        innerHtmlPopUp+='</div></div>';

                    }

                    if(leepingSpecies!=''){
                        var leepingSpecieslength = result.leepingSpecies.length;
                        for(var j =0; j<leepingSpecieslength;j++){
                            if(FrameMaterialValue != null){
                                FrameMaterialValue = $("#StandardFrameMaterial-value").data("value");
                                if(FrameMaterialValue != "" && FrameMaterialValue == leepingSpecies[j].id){
                                    $("#StandardframeMaterial").val(leepingSpecies[j].SpeciesName);
                                    console.log(leepingSpecies[j].SpeciesName)
                                }
                            }
                            var filepath = $("input[name='base_url']").val()+"/uploads/Options/"+leepingSpecies[j].file;

                            //innerHtml+='<option value="'+leepingSpecies[j].id+'">'+leepingSpecies[j].SpeciesName+'</option>'

                            innerHtmlPopUp+='<div class="col-md-2 col-sm-4 col-6 cursor-pointer" onClick="StandardSelectValueFill(\'#StandardframeMaterial\',\''+leepingSpecies[j].id+'\',\''+leepingSpecies[j].SpeciesName+'\',\'#standardframeMaterialModal\')">';
                            innerHtmlPopUp+='<div class="color_box">';
                            innerHtmlPopUp+='<div class="frameMaterialImage"><img width="100%" height="100" src="'+filepath+'"></div>';
                            innerHtmlPopUp+=' <h4>'+leepingSpecies[j].SpeciesName+'</h4>';
                            innerHtmlPopUp+='</div></div>';
                        }
                    }

                    innerHtmlPopUp+='</div></div>';

                    $("#standardframeMaterialModalBody").empty().append(innerHtmlPopUp);
                    // $("#UniversalModalBody").empty().append(innerHtmlPopUp);

                    $("#StandardframeMaterialIcon").attr("onclick","return StandardFrameMaterial()");
                    $("#StandardframeMaterialIcon").addClass("cursor-pointer");


                    $("#StandardlippingSpeciesIcon").attr("onclick","return  standardOpenLipingModal('Lipping Species','StandardlippingSpecies')");
                    $("#StandardlippingSpeciesIcon").addClass("cursor-pointer");


                }else{
                    //innerHtml+='<option value="">No Frame Material Found</option>';
                    innerHtmlPopUp+='No Frame Material Found';

                    $("#StandardframeMaterial").empty().append(innerHtml);
                }
            }
        });
    }

    function standardOpenglazingModal(labelname,inputId){
        $('#standardglazingModalLabel').html(labelname);
        $('#standardinputId').val(inputId);
        $('#standardglazingModal').modal('show');
    }
    function standardOpenLipingModal(labelname,inputId){
        $('#StandardLipingModalLabel').html(labelname);
        $('#standardinputId').val(inputId);
        $('#StandardLipingModal').modal('show');
    }

    function StandardFrameMaterial(){
        $('.inputIdentity').val('FrameMaterial');
        $('#standardframeMaterialModal').modal('show');
    }

    function StandardSelectValueFill(id, key, value, modalId, data = []){
        let inputIdentity = $('.inputIdentity').val();
        console.log(id, key, value, modalId)
        if(inputIdentity == 'ArchitraveMaterial'){
            $('#architraveMaterial').val(value);
            $('input[name="architraveMaterial"]').val(key);
            $('#standardUniversalModal').modal('hide');
        } else  if(inputIdentity == 'FrameMaterial'){
            if(id == "#StandardframeMaterial"){
                $(id).val(value);
                $("#StandardframeMaterialNew").val(key);
                console.log(key)
            }else{
                $(id).val(key);
            }
            $(modalId).modal('hide');

        }
    }

    $("#StandardframeFinish").change(function(){
        StandardFrameFinishChange(true ,  'StandardframeFinish');
    });

    $("#standardarchitraveFinish").change(function(){
        StandardFrameFinishChange(true , 'standardarchitraveFinish');
    });

    function StandardFrameFinishChange(showModal = false , typeinput){
        let pageId = pageIdentity();
        if(typeinput == 'StandardframeFinish'){
            var doorLeafFinish =  $("#StandardframeFinish").val();
            $('#standardralColorModalLabel').html('Frame Finish Color');
        }
        if(typeinput == 'standardarchitraveFinish'){
            var doorLeafFinish =  $("#standardarchitraveFinish").val();
            $('#standardralColorModalLabel').html('Architrave Finish Color');
        }
       var FrameFinishColorValue = $('#StandardFrameFinishColor-value').val();
        if(doorLeafFinish == "Painted_Finish"){
            if(typeinput == 'standardarchitraveFinish'){
                $("#standardarchitraveFinishcolor").attr({'disabled':false});
                $('#standardarchitraveFinishcolor').addClass("bg-white");
            }
            $.ajax({
                url:  $("#ral-color-filter").html(),
                method:"POST",
                dataType:"Json",
                data:{pageId:4,doorLeafFinish:'Painted',_token:$("#_token").val()},
                success: function(result){
                    if(result.status=="ok"){
                        var innerHtml ='';
                        var data = result.data;
                        var length = result.data.length;
                        // innerHtml+='<option value="">Select Door leaf color value</option>';
                        innerHtml+='<div class="container"><div class="row">';
                        innerHtml+='<div id="field" hidden>'+doorLeafFinish+'</div>';
                        $("#doorLeafFinishColor").attr({'disabled':false});
                        $("#StandardframefinishColor").attr({'disabled':false});


                        for(var index =0; index<length;index++){
                            if(FrameFinishColorValue != null){
                                FrameFinishColorValue = $('#StandardFrameFinishColor-value').val();
                                if(FrameFinishColorValue == data[index].id){
                                    var select ='<option value="'+data[index].id+'" selected>'+data[index].ColorName+'</option>';
                                    $("#StandardframefinishColor").empty().append(select);
                                }
                            }
                            innerHtml+='<div class="col-md-2 col-sm-4 col-6" onClick="StandardSelectRalColor(\''+typeinput+'\','+data[index].id+',\''+data[index].Hex+'\',\''+data[index].ColorName+'\',\''+doorLeafFinish+'\')">';
                            innerHtml+='<div class="color_box">';
                            innerHtml+='<div class="color_place" style="background:'+data[index].Hex+'"></div>';
                            innerHtml+=' <h4>'+data[index].ColorName+'</h4>';
                            innerHtml+='</div></div>';
                            // innerHtml+='<option value="'+data[index].Hex+'" style="background:'+data[index].Hex+'">'+data[index].ColorName+'</option>'
                        }
                        innerHtml+='</div></div>';
                        $("#standardprintedColor").empty().append(innerHtml);
                        // $("#doorLeafFinishColor").empty().append(innerHtml);
                        if(showModal == true){
                            $("#standardralColor").modal('show');
                        }
                    } else {
                        $("#doorLeafFinishColor").empty().append('<option value="">No Door leaf Ral Color Found</option>');
                    }
                }
            });
        } else {
            var frameFinish =  $("#StandardframeFinish").val();
            var architraveFinish =  $("#standardarchitraveFinish").val();
            if(frameFinish != 'Painted_Finish'){
                $("#StandardframefinishColor").val('').attr({'disabled':true});
            }else if(architraveFinish != 'Painted_Finish'){
                $('input[name="architraveFinishcolor"]').val('');
                $('#standardarchitraveFinishcolor').removeClass('bg-white');
                if(typeinput == 'standardarchitraveFinish'){
                    $("#standardarchitraveFinishcolor").html('<option value="">Architrave  Finish  Color</option>');
                    $("#standardarchitraveFinishcolor").val('').attr({'disabled':true});
                }
            }
        }
    }

    function StandardSelectRalColor( typeinput,id,code,name,fieldname){
        console.log(typeinput,id,code,name,fieldname)
        var innerHtml ='';
        innerHtml+='<option value="'+code+'" style="background:'+code+'">'+name+'</option>'
        if(fieldname=="Painted" || fieldname=="Paint_Finish"){
            // $("#doorLeafFinishColor").empty().append(innerHtml);
            $("#doorLeafFinishColorIcon").show();
            // $("#doorLeafFinishColor").val(code);
            $("#doorLeafFinishColor").val(name);
            var Colors = JSON.parse(ColorsJson);
            var price = 0.00;
            Colors.forEach(function(elem, index) {
                if(id == elem.id){
                    price = elem.ColorCost;
                }
            });
        } else if(fieldname=="Painted_Finish"){
            if(typeinput == 'standardarchitraveFinish'){
                // $("#architraveFinishcolor").empty().append('<option value="'+id+'">'+name+'</option>');
                $("#standardarchitraveFinishcolor").val(name)
                $("input[name='architraveFinishcolor']").val(name)
            } else if(typeinput == 'StandardframeFinish'){
                $("#StandardframefinishColor").empty().append('<option value="'+id+'">'+name+'</option>');
            }

            var Colors = JSON.parse(ColorsJson);
            var price = 0.00;
            Colors.forEach(function(elem, index) {
                if(id == elem.id){
                    price = elem.ColorCost;
                    // console.log("Color id is = ",index);
                }
            });
        } else {
            $("#doorLeafFinishIcon").hide();
            $("#doorLeafFinish").hide();
        }
        $("#standardralColor").modal('hide');
    }

    function StandardglazingSystemFIlter(fireRating){
        let pageId = pageIdentity();
        var leaf1VpAreaSizeM2Value = $('#standardleaf1VpAreaSizeM2').val();
        leaf1VpAreaSizeM2Value = (leaf1VpAreaSizeM2Value == 0)?"":leaf1VpAreaSizeM2Value;
        $.ajax({
            url: $("#glazing-system-filter").html(),
            method:"POST",
            dataType:"Json",
            data:{pageId:4,fireRating:fireRating,_token:$("#_token").val(), leaf1VpAreaSizeM2Value : leaf1VpAreaSizeM2Value},
            success: function(result){
                var innerHtml1='';
                if(result.status=="ok"){
                    var innerHtml ='';
                    var data = result.data;
                    var datalength = result.data.length;
                    var lippingSpecies = result.lippingSpecies;
                    var lippingSpeciesLength =result.lippingSpecies.length;
                    innerHtml+='<option value="">Select Glazing Type</option>';


                    if(lippingSpecies!='' && lippingSpeciesLength>0){
                        innerHtml1 += '<div class="container"><div class="row">';
                        // innerHtml1+='<option value="">Select Species Type</option>';

                        var LippingSpeciesValue = document.getElementById('StandardLippingSpecies-value');

                        for(var leep =0; leep<lippingSpeciesLength;leep++){
                            if(LippingSpeciesValue != null){
                                LippingSpeciesValue = $("#StandardLippingSpecies-value").data("value");
                                if(LippingSpeciesValue != "" && LippingSpeciesValue == lippingSpecies[leep].id){
                                    $("#StandardlippingSpecies").val(lippingSpecies[leep].SpeciesName);
                                }
                            }

                            var filepath = $("input[name='base_url']").val()+"/uploads/Options/"+lippingSpecies[leep].file;

                            var possibleSelectedOptionsArray = JSON.parse(possibleSelectedOptionsJson);

                            if (possibleSelectedOptionsArray.hasOwnProperty("lippingSpecies")) {

                                if (lippingSpecies[leep].hasOwnProperty("SelectedLippingSpeciesCost")) {
                                    var costToShow =  lippingSpecies[leep].SelectedLippingSpeciesCost;

                                } else {
                                    var costToShow =  lippingSpecies[leep].LippingSpeciesCost;
                                }
                            }else{
                                var costToShow =  lippingSpecies[leep].LippingSpeciesCost;
                            }

                            innerHtml1 += '<div class="col-md-2 col-sm-4 col-6 cursor-pointer" onClick="standardGlazingValueFill('+lippingSpecies[leep].id+',\''+lippingSpecies[leep].SpeciesName+'\',\'#standardglazingModal\','+costToShow+')">'
                            + '<div class="color_box">'
                            + '<div class="frameMaterialImage"><img width="100%" height="100" src="'+ filepath +'"></div>'
                            + '<h4>'+lippingSpecies[leep].SpeciesName+'</h4>'
                            + '</div></div>';
                            // innerHtml1+='<option value="'+lippingSpecies[leep].id+'">'+lippingSpecies[leep].SpeciesName+'</option>'

                        }
                    }

                    $("#standardglazingModalBody").empty().append(innerHtml1);

                } else {
                    var lippingSpecies = result.lippingSpecies;
                    var lippingSpeciesLength =result.lippingSpecies.length;
                    if(lippingSpecies!='' && lippingSpeciesLength>0){
                        innerHtml1 = "";
                        costToShow = 0;
                        innerHtml1 += '<div class="container"><div class="row">';
                        for(var leep =0; leep<lippingSpeciesLength;leep++){
                            var filepath = $("input[name='base_url']").val()+"/uploads/Options/"+lippingSpecies[leep].file;
                            innerHtml1 += '<div class="col-md-2 col-sm-4 col-6 cursor-pointer" onClick="standardGlazingValueFill('+lippingSpecies[leep].id+',\''+lippingSpecies[leep].SpeciesName+'\',\'#standardglazingModal\','+costToShow+')">'
                            + '<div class="color_box">'
                            + '<div class="frameMaterialImage"><img width="100%" height="100" src="'+ filepath +'"></div>'
                            + '<h4>'+lippingSpecies[leep].SpeciesName+'</h4>'
                            + '</div></div>';
                        }
                    } else {
                        innerHtml1+='<option value="">No  Species Found</option>';
                    }
                    $("#standardglazingModalBody").empty().append(innerHtml1);
                    $("#StandardlippingSpecies").empty().append(innerHtml1);
                }
            }
        });
    }

    function standardOnlyLipingSpecies(fireRating){
        let pageId = pageIdentity();
            var leaf1VpAreaSizeM2Value = $('#leaf1VpAreaSizeM2').val();
            leaf1VpAreaSizeM2Value = (leaf1VpAreaSizeM2Value == 0)?"":leaf1VpAreaSizeM2Value;
            $.ajax({
                url: $("#liping-glazing-system-filter").html(),
                method:"POST",
                dataType:"Json",
                data:{pageId:pageId,fireRating:fireRating,_token:$("#_token").val(), leaf1VpAreaSizeM2Value : leaf1VpAreaSizeM2Value},
                success: function(result){
                    var innerHtml1='';
                    if(result.status=="ok"){
                        var innerHtml ='';
                        var lippingSpecies = result.OnlylippingSpecies;
                        var lippingSpeciesLength =result.OnlylippingSpecies.length;

                        if(lippingSpecies!='' && lippingSpeciesLength>0){
                            innerHtml1 += '<div class="container"><div class="row">';

                            var LippingSpeciesValue = document.getElementById('LippingSpecies-value');
                            for(var leep =0; leep<lippingSpeciesLength;leep++){
                                if(LippingSpeciesValue != null){
                                    LippingSpeciesValue = $("#LippingSpecies-value").data("value");
                                    if(LippingSpeciesValue != "" && LippingSpeciesValue == lippingSpecies[leep].id){
                                        $("#lippingSpecies").val(lippingSpecies[leep].SpeciesName);
                                    }
                                }


                                var filepath = $("input[name='base_url']").val()+"/uploads/Options/"+lippingSpecies[leep].file;

                                var possibleSelectedOptionsArray = JSON.parse(possibleSelectedOptionsJson);

                                if (possibleSelectedOptionsArray.hasOwnProperty("OnlylippingSpecies")) {

                                    if (lippingSpecies[leep].hasOwnProperty("SelectedLippingSpeciesCost")) {
                                        var costToShow =  lippingSpecies[leep].SelectedLippingSpeciesCost;

                                    } else {
                                        var costToShow =  lippingSpecies[leep].LippingSpeciesCost;
                                    }
                                }else{
                                    var costToShow =  lippingSpecies[leep].LippingSpeciesCost;
                                }

                                innerHtml1 += '<div class="col-md-2 col-sm-4 col-6 cursor-pointer" onClick="standardGlazingValueFill('+lippingSpecies[leep].id+',\''+lippingSpecies[leep].SpeciesName+'\',\'#StandardLipingModal\','+costToShow+')">'
                                + '<div class="color_box">'
                                + '<div class="frameMaterialImage"><img width="100%" height="100" src="'+ filepath +'"></div>'
                                + '<h4>'+lippingSpecies[leep].SpeciesName+'</h4>'
                                + '</div></div>';

                            }
                        } else {
                        }
                        $("#StandardLipingModalBody").empty().append(innerHtml1);
                    }
                     else {
                        var lippingSpecies = result.lippingSpecies;
                        var lippingSpeciesLength =result.lippingSpecies.length;
                        innerHtml+='<option value="">No Glazing Systems Found</option>';
                        if(lippingSpecies!='' && lippingSpeciesLength>0){
                            innerHtml1 = "";
                            costToShow = 0;
                            innerHtml1 += '<div class="container"><div class="row">';
                            for(var leep =0; leep<lippingSpeciesLength;leep++){
                                var filepath = $("input[name='base_url']").val()+"/uploads/Options/"+lippingSpecies[leep].file;
                                innerHtml1 += '<div class="col-md-2 col-sm-4 col-6 cursor-pointer" onClick="GlazingValueFill('+lippingSpecies[leep].id+',\''+lippingSpecies[leep].SpeciesName+'\',\'#LipingModal\','+costToShow+')">'
                                + '<div class="color_box">'
                                + '<div class="frameMaterialImage"><img width="100%" height="100" src="'+ filepath +'"></div>'
                                + '<h4>'+lippingSpecies[leep].SpeciesName+'</h4>'
                                + '</div></div>';
                            }
                            $("#StandardLipingModalBody").empty().append(innerHtmlPopUp);
                        } else {
                            innerHtml1+='<option value="">No  Species Found</option>';
                        }
                        $("#lippingSpecies").empty().append(innerHtml1);
                    }
                    // $("#glazingSystemsThickness").val(0);
                }
            });
    }


// for custome rules

function floor_finish_change(){
    if($("#StandardfireRating").val()=='FD30' || $("#StandardfireRating").val()=='FD60'){
        $("#undercut").attr("readonly","readonly")
        $("#floor_finish").show();
        $("#undercut").val(parseInt($("#floorFinish").val())+8);
    }else if($("#StandardfireRating").val()=='FD30s' || $("#StandardfireRating").val()=='FD60s'){
        $("#undercut").attr("readonly","readonly")
        $("#floor_finish").show();
        $("#undercut").val(parseInt($("#floorFinish").val())+3);
    }else{
        $("#undercut").attr('readonly',false)
        $("#floor_finish").show();
    }
    var withoutFrameId = $("#withoutFrameId").val();
    if(withoutFrameId == 1){
        $("#floor_finish").hide();
    }
}

$(document).on('click','#standardswingType',function(e){
    if($("#standardswingType").val() == 'DA'){
        $("select[name=frameType]").val('Scalloped').trigger("change");
        $("#frameType option[value='Plant_on_Stop'], #frameType option[value='Rebated_Frame']")
            .prop("disabled", true);
        $('#foursidedframe').prop({
            disabled: true,
            checked: false
        });
        $("#frameType option[value='Scalloped']").prop("disabled", false);
        $('.mylatch').siblings('label').children('.dsl').html('');
        $('.mylatch option').eq(0).prop('selected', true);
        $('.mylatch').attr("disabled",true);
    }else{
        $("#frameType option[value='Plant_on_Stop']").prop("disabled", false);
        $("#frameType option[value='Rebated_Frame']").prop("disabled", false);
        $("#frameType option[value='Scalloped']").prop("disabled", true);
        $("#frameType").val('').trigger('change');
        $('#foursidedframe').prop('disabled', false);
        $('.mylatch').attr("disabled",false);
    }
    framTypeChangeInputEnableDisable();
});

// for custome door

$(document).on('click','#swingType',function(e){
    if($("#swingType").val() == 'DA'){
        $("select[name=frameType]").val('Scalloped').trigger("change");
        $("#frameType option[value='Plant_on_Stop'], #frameType option[value='Rebated_Frame']")
            .prop("disabled", true);
        $('#foursidedframe').prop({
            disabled: true,
            checked: false
        });
        $("#frameType option[value='Scalloped']").prop("disabled", false);
        $('.mylatch').siblings('label').children('.dsl').html('');
        $('.mylatch option').eq(0).prop('selected', true);
        $('.mylatch').attr("disabled",true);
    }else{
        $("#frameType option[value='Plant_on_Stop']").prop("disabled", false);
        $("#frameType option[value='Rebated_Frame']").prop("disabled", false);
        $("#frameType option[value='Scalloped']").prop("disabled", true);
        $("#frameType").val('').trigger('change');
        $('#foursidedframe').prop('disabled', false);
        $('.mylatch').attr("disabled",false);
    }
    framTypeChangeInputEnableDisable();
});





