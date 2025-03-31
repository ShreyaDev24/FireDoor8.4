var addMoreBox = 1;
// Daynamic append value
var ifMoreBox = 1;
var thenMoreBox = 1;
var rulesBox = 1;
// Closed
var addThenMoreBox = 1;

// Manage then roles box
function AddMoreThenBox(argumentID){
    $('#Add_thenMoreBox_'+argumentID).hide();
    $('#removeThenBoxId_'+argumentID).val(1);
    addThenMoreBox = addThenMoreBox+1;
    var html = '<li class="categoryList" id="thenConditionsBox_'+addThenMoreBox+'">'+
                '<div class="col-md-2 conditions">'+
                   '<div class="form-group conditionsDropDown">'+
                         '<select name="thenConditions[]" class="form-control">'+
                          '<option value="and">AND</option>'+
                          '<option value="or">OR</option>'+
                        '</select>'+
                   '</div>'+
                '</div>'+
            '</li>';
        html += '<li class="categoryList" id="thenContaniarsBox_'+addThenMoreBox+'">'+
                  '<div class="ruleValue">'+
                  '<div class="col-md-12">'+
                      '<div class="row">'+
                          '<div class="col-md-2 removePadding">'+
                                  '<div class="form-group">'+
                                    '<select name="thenShowMaxMin[]" id="thenCondations_'+addThenMoreBox+'" class="form-control" onChange="thenShowBox('+addThenMoreBox+')">'+
                                        '<option value="show">Show</option>'+
                                        '<option value="max">Max</option>'+
                                        '<option value="min">Min</option>'+
                                    '</select>'+
                               '</div>'+
                          '</div>'+
                          '<div class="col-md-3 removePadding">'+
                                  '<div class="form-group">'+
                                    '<select name="thencategoryListName[]" class="form-control">';
                                        $(itemField).each(function(index,res){
                                           html += '<option value="'+res.id+'">'+res.FieldName+'</option>';
                                        });
                          html += '</select>'+
                               '</div>'+
                          '</div>'+
                          '<div class="col-md-3 removePadding" id="thenMaxMin_'+addThenMoreBox+'" style="display: none;">'+
                                  '<div class="form-group">'+
                                    '<input type="text" class="form-control inputBox" name="thenmaxMinValue[]" placeholder="Max/Min value">'+
                               '</div>'+
                          '</div>'+
                          '<div class="col-md-2 removePadding">'+
                              '<div class="form-group"  style="text-align: center;line-height: 2;" onclick="removeThenBox('+addThenMoreBox+')">'+
                                   '<span class="AddMoreBtnRemove">Remove</span>'+
                              '</div>'+
                          '</div>'+
                          '<div class="col-md-1 removePadding" id="Add_thenMoreBox_'+addThenMoreBox+'">'+
                                  '<div class="form-group"  onclick="AddMoreThenBox('+addThenMoreBox+')">'+
                                   '<span class="AddMoreBtn">+</span> '+
                                  '</div>'+
                          '</div>'+
                          '</div><input type="hidden" name="removeThenBoxId" value="2" id="removeThenBoxId_'+addThenMoreBox+'">'+
                      '</div>'+
                  '</div>'+
              '</li>';
            $('#thenMoreBox_'+thenMoreBox).append(html);
}
// This is options box
function addMoreBtn(argumentID) {
	 $('#addMoreFirst_'+argumentID).hide();
	 $('#removeBoxId_'+argumentID).val(1);
	addMoreBox = addMoreBox+1;
	var html = '<li class="categoryList" id="ifmoreCondation_'+addMoreBox+'">'+
                    '<div class="col-md-2 conditions">'+
                       '<div class="form-group conditionsDropDown">'+
                             '<select name="ifConditions[]" class="form-control">'+
                              '<option value="and">AND</option>'+
                              '<option value="or">OR</option>'+
                            '</select>'+
                       '</div>'+
                    '</div>'+
                '</li>';
        html += '<li class="categoryList" id="containerBox_'+addMoreBox+'">'+
                                    '<div class="ruleValue">'+
                                    '<div class="col-md-12">'+
                                        '<div class="row">'+
                                            '<div class="col-md-4 removePadding">'+
                                                    '<div class="form-group">'+
                                                      '<select name="categoryListName[]" class="form-control">';
                                                        $(itemField).each(function(index,res){
                                                            if(res.FieldType != 'drop' && res.FieldType != 'single'){
                                                                html += '<option value="'+res.id+'">'+res.FieldName+'</option>';
                                                            }else{
                                                                html += '<optgroup label="'+res.FieldName+'">';
                                                                    if(res.Options.length != 0){
                                                                       $(res.Options).each(function(optionIndex,optionValue){
                                                                         html += '<option value="'+res.id+','+optionValue.OptionPrice+'">'+optionValue.OptionName+'</option>';
                                                                       });
                                                                    }
                                                                html += '</optgroup>';
                                                            }
                                                        });
                                                    html += '</select>'+
                                                     '</div>'+
                                            '</div>'+
                                            '<div class="col-md-3 removePadding">'+
                                                    '<div class="form-group">'+
                                                      '<select name="conditionsOption[]" class="form-control"  onchange="conditionsOptionValue('+addMoreBox+')" id="conditionsOption_'+addMoreBox+'" >'+
                                                          '<option value="1">is Checked</option>'+
                                                          '<option value="2">is Not Checked</option>'+
                                                          '<option value="3">is Greater Then</option>'+
                                                          '<option value="4">is Not Greater Then</option>'+
                                                      '</select>'+
                                                 '</div>'+
                                            '</div>'+
                                             '<div class="col-md-3 removePadding" style="display: none;" id="conditionsGreaterInput_'+addMoreBox+'">'+
                                                    '<div class="form-group">'+
                                                      '<input type="text" class="form-control inputBox" name="ifmaxMinValue[]" placeholder="Max/Min value">'+
                                                 '</div>'+
                                            '</div>'+
                                            '<div class="col-md-2 removePadding">'+
                                                '<div class="form-group" onClick="removeBox('+addMoreBox+')" style="text-align: center;line-height: 2;">'+
                                                     '<span class="AddMoreBtnRemove">Remove</span> '+
                                                '</div>'+
                                            '</div>'+
                                            '<div class="col-md-1 removePadding" id="addMoreFirst_'+addMoreBox+'">'+
                                                    '<div class="form-group"  onclick="addMoreBtn('+addMoreBox+')">'+
                                                     '<span class="AddMoreBtn">+</span>'+
                                                    '</div>'+
                                            '</div>'+
                                            '<input type="hidden" name="removeBoxId" value="2" id="removeBoxId_'+addMoreBox+'">'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</li>';
                           $('#ifMoreBox_1').append(html);
}
// Add New roles
function removeBox(conditionBoxID){
	  var oldId = conditionBoxID - 1;
    var currentData =  $('#removeBoxId_'+conditionBoxID).val();
    var data =  $('#removeBoxId_'+oldId).val();
    if(currentData == 2){
       if(data == undefined){
            for(i= oldId;  i<= addMoreBox; i--){
               var checkData =  $('#removeBoxId_'+i).val();
               if(checkData != undefined){
                 $('#addMoreFirst_'+i).show();
                 break;
               }
            }
       }else{
         $('#addMoreFirst_'+data).show();
       }
    }
    $('#ifmoreCondation_'+conditionBoxID).remove();
    $('#containerBox_'+conditionBoxID).remove();
}
function removeThenBox(conditionBox){
  var oldId = conditionBox - 1;
    var currentData =  $('#removeThenBoxId_'+conditionBox).val();
    var data =  $('#removeThenBoxId_'+oldId).val();
    if(currentData == 2){
       if(data == undefined){
            for(i= oldId;  i<= addThenMoreBox; i--){
               var checkData =  $('#removeThenBoxId_'+i).val();
               if(checkData != undefined){
                 $('#Add_thenMoreBox_'+i).show();
                 break;
               }
            }
       }else{
         $('#Add_thenMoreBox_'+data).show();
       }
    }
    $('#thenConditionsBox_'+conditionBox).remove();
    $('#thenContaniarsBox_'+conditionBox).remove();
}
function conditionsOptionValue(argumentID){
	var conditionsOptionValue = $('#conditionsOption_'+argumentID).val();
	 if(conditionsOptionValue == 3 || conditionsOptionValue == 4){
	 	$('#conditionsGreaterInput_'+argumentID).show();
	 }else{
	 	$('#conditionsGreaterInput_'+argumentID).hide();
	 }
}
function thenShowBox(argumentID){
	   var conditionsOptionValue = $('#thenCondations_'+argumentID).val();
	   if(conditionsOptionValue != 'show'){
	 	 $('#thenMaxMin_'+argumentID).show();
    	 }else{
	 	$('#thenMaxMin_'+argumentID).hide();
	 }
}
