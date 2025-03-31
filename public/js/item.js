/**All js used in Item module **/

/**Item listing page js for search filter**/
$("#box").on('keyup', function() {
  var matcher = new RegExp($(this).val(), 'i');
  $('.connect-cat').show().not(function() {
    return matcher.test($(this).find('.name, .category').text())
  }).hide();
});
/**Search filter end**/

/** item listing js for left panel filtering **/
function AddActive(e) {
  var elems = document.querySelectorAll(".active_item");
  [].forEach.call(elems, function(el) {
    el.classList.remove("active_item");
  });
  e.target.className = "active_item , item_type";
}
function filterSelection(value){
		if(value =='config'){
			showdiv(value);
			hidediv("nonconfig");
		}else if(value =='nonconfig'){
			hidediv("config");
			showdiv(value);
		}else{
			showdiv("config");
			showdiv("nonconfig");
		}
		
	
}
function hidediv(value){
	var divsToHide = document.getElementsByClassName(value); //divsToHide is an array
	    for (var i = 0; i < divsToHide.length; i ++) {
	    	divsToHide[i].style.display = 'none';
	    	divsToHide[i].style.visibility = 'none';
		}
	    
}
function showdiv(value){
	var divsToShow = document.getElementsByClassName(value); //divsToHide is an array
	    for (var i = 0; i < divsToShow.length; i ++) {
	    	divsToShow[i].style.display = 'block';
	    	divsToShow[i].style.visibility = 'vissible';
		}
}
/**Left panel filtering end**/

/**Toggle button js for setting active and inactive status submiting form**/
function OnToggle(id,usertype){
	//you post variables
	if(usertype==1){
		$.ajax({
	        type: "GET",
	        dataType: "json",
	        url: 'items/changeStatus',
	        data: {'id': id},
	        success: function(data){
	          console.log(data);
	          		if(data.success == 200){ 
					}
	        }
	    });
	}
}
/**Toggle button end**/
var fieldcounter=1;
/**js for drag and drop field in form builder**/
function draganddrop(){
$(function() {
	var dragOptions = {
		revert: "invalid",
		//scope: "items",
		helper: "clone"
	}
	$('.draggable').draggable(dragOptions);
	$('.droppable').droppable({
		//scope: "items",
		drop: function(e, ui) {
			var DragInputType=$(ui.draggable).attr("data-item");
			switch(DragInputType){
				case "heading" 		: 	var DropElement=$("#InputTypeHeading").html();
									break;
				case "number" 		: 	var DropElement=$("#InputTypeNumber").html();
									break;
				case "text" 		: 	var DropElement=$("#InputTypeText").html();
									break;
				case "single" 		: 	var DropElement=$("#InputTypeSingle").html();
									break;
				case "drop" 		: 	var DropElement=$("#InputTypeDrop").html();
									break;
				case "heightnwidth" : 	var DropElement=$("#InputTypeHNW").html();
									break;					
			}
			$(this).css('border','none');
			$(this).append(DropElement).find('.toaddclass').addClass('accordion SortableElement');
			var fieldcategoryid=$(this).attr('id');
			$(this).find('input[name ="category"]').attr('value', fieldcategoryid);

			$(this).find('#RequiredNew').addClass('requiredclass');
			$(this).find('#RequiredNew').attr('name','RequiredNew'+fieldcounter);
			$(this).find('#RequiredNew').attr('id','#RequiredNew'+fieldcounter);
			$(this).find('[for=RequiredNew]').attr('for','#RequiredNew'+fieldcounter);

			$(this).find('#ReadOnlyNew').addClass('readonlyclass');
			$(this).find('#ReadOnlyNew').attr('name','ReadOnlyNew'+fieldcounter);
			$(this).find('#ReadOnlyNew').attr('id','#ReadOnlyNew'+fieldcounter);
			$(this).find('[for=ReadOnlyNew]').attr('for','#ReadOnlyNew'+fieldcounter);
			
			$(this).find('#HideFieldNew').addClass('hidefieldclass');
			$(this).find('#HideFieldNew').attr('name','HideFieldNew'+fieldcounter);
			$(this).find('#HideFieldNew').attr('id','#HideFieldNew'+fieldcounter);
			$(this).find('[for=HideFieldNew]').attr('for','#HideFieldNew'+fieldcounter);
			fieldcounter=fieldcounter+1;
			Init();
			SortElement();
			AddMoreOption();
			DeleteField();
			FieldLabelChange();
			SaveForm();
		}

	});
});
}
draganddrop();

/**js for field accordian**/
function Init(){
	$(document).on('click', '.accordion', function(event) {
		var acc = document.getElementsByClassName("accordion");
		for (i = 0; i < acc.length; i++) {
			var panel1 = acc[i].nextElementSibling;
			if ($(acc[i]).hasClass('active') && this!=acc[i]) {
			    $(acc[i]).toggleClass("active");
			    panel1.style.visibility ="hidden";
			    panel1.style.opacity ="0";
			    panel1.style.display ="none";
		    }
		} 
		event.stopPropagation();
    	event.stopImmediatePropagation();
	    var panel = this.nextElementSibling;
	    if ($(this).hasClass('active')) {
	      	$(this).toggleClass("active");
	     	panel.style.visibility ="hidden";
			panel.style.opacity ="0";
	     	panel.style.display ="none";
	      	$('.droppable').sortable('enable');
	    } else {
	      $(this).toggleClass("active");
	    	panel.style.display ="block";
	    	panel.style.visibility ="visible";
			panel.style.opacity ="1";
	      $('.droppable').sortable('disable');
	    }
	  });
}
Init();
/**accordian end**/


/**Rearrange Function**/
function SortElement(){
$(function() {
    $('.droppable').sortable({placeholder: "ui-state-highlight",helper:'clone',cancel:'.NotSortable',item:'SortableElement'});
});
}
SortElement();
/**Rearrange end **/

/**Add More Option **/
function AddMoreOption(){
	$(document).on('click', '.addoption', function(event) {
		event.stopPropagation();
    	event.stopImmediatePropagation();
    	var InputType=$(this).attr("data-inputtype");
		var ParentObj=$(this).parent().parent();
		var htmlDiv='';
		if(InputType=='single'){
				htmlDiv+='<div class="row ToSaveOption"><div class="col-md-4">';
	            htmlDiv+='<label class="control-label">Label *</label><input type="text" class="form-control" placeholder="Enter Choice Option Lable" name="optionlabel" value=""></div>';
	            htmlDiv+='<div class="col-md-4"><label class="control-label">Value *</label><input type="text" class="form-control" placeholder="Enter Choice Option Value" name="optionprice"></div>';
	            htmlDiv+='<div class="col-md-4"><label class="control-label">Image</label>';
	            htmlDiv+='<div class="col-md-12 pad_left_none"><label for="chioceimg"><span class="btn btn-primary" aria-hidden="true">Upload Image</span>';
	            htmlDiv+='<input id="chioceimg" type="file" class="form-control custome_input_file" name="optionimg" value="" ></label></div></div></div>';
	    }else if(InputType=='drop'){
	    	 	htmlDiv+='<div class="row ToSaveOption"><div class="col-md-4">';
	            htmlDiv+='<label class="control-label">Label *</label><input type="text" class="form-control" placeholder="Enter Drop Option Label" name="optionlabel" value=""></div>';
	            htmlDiv+='<div class="col-md-4"><label class="control-label">Value *</label><input type="text" class="form-control" placeholder="Enter Drop Option Value" name="optionprice" value=""></div>';
	            htmlDiv+='<div class="col-md-4"><label class="control-label">Image</label>';
	            htmlDiv+='<div class="col-md-12 pad_left_none"><label for="dropimg"><span class="btn btn-primary" aria-hidden="true">Upload Image</span>';
	            htmlDiv+='<input id="dropimg" type="file" class="form-control custome_input_file" name="optionimg" value="" ></label></div></div></div>';
	    }
		ParentObj.before(htmlDiv);
	});
}
AddMoreOption();
/**Add new cotegory js**/

/**Delete Field js**/
function DeleteField(fieldObj){
	var fieldthis=fieldObj;
	var FieldId='';
	if(fieldthis!=undefined){
		FieldId = fieldthis.parent().parent().parent().find('input[name ="fieldid"]').val();
	}
	if(FieldId != ''){
		$.ajax({
        type: "GET",
        dataType: "json",
        url: '../additem/ChangeFieldStatus',
        data: {'id': FieldId},
        success: function(data){
          console.log(data);
          		if(data.success == 200){ 
					var ParentObj=fieldthis.parent().parent().parent();
					ParentObj.remove();
					fieldcounter=fieldcounter-1;
				}else{
					alert('some error has occurred');
				}
        }
    });
    console.log(FieldId);
	}else{
		if(fieldthis!=undefined){
			var ParentObj=fieldthis.parent().parent().parent();
			ParentObj.remove();
			fieldcounter=fieldcounter-1;
		}
	}

}
$(document).on('click', '.DeleteField', function(event) {
	var deleteObj=$(this);
	var deleteType='field';
	var msg='<div>Deleting field will also delete all its data.</div><div>Please click yes to confirm!</div>';
	functionAlert(deleteObj,deleteType,msg);
});
/**delete field js end**/

/**Delete category js**/
function DeleteCategory(categoryObj){
	$this=categoryObj;
	var CategoryTabId 	= $this.parent().attr("href");
	var CatgoryId 		= $this.parent().attr("data-categoryid");
	if(CatgoryId != ''){
		$.ajax({
        type: "GET",
        dataType: "json",
        url: '../additem/ChangeCategoryStatus',
        data: {'id': CatgoryId},
        success: function(data){
          console.log(data);
          		if(data.success == 200){ 
					$(CategoryTabId).remove();
					var ParentObj 		=$this.parent().parent();
					ParentObj.remove();
					$('#MainOptionTab').trigger('click');
				}else{
					alert('some error has occurred');
				}
        }
    });
    console.log(CatgoryId);
	}else{
		$(CategoryTabId).remove();
		var ParentObj 		=$this.parent().parent();
		ParentObj.remove();
		$('#MainOptionTab').trigger('click');
	}
}
$(document).on('click', '.DeleteCategory', function(event) {
	var deleteObj=$(this);
	var deleteType='category';
	var msg='<div>Deleting category will delete all its fields.</div><div>Please click yes to confirm!</div>';
	functionAlert(deleteObj,deleteType,msg);
});
/**Delete category js end**/

/**Confirmation box js**/
function functionAlert(deleteObj,deleteType,msg) {
	var confirmBox = $("#confirmbox");
    confirmBox.find(".confirmmessage").html(msg);
	confirmBox.show();
    confirmBox.find(".confirmyes").unbind().click(function() {
    	if(deleteType=='category'){
    		DeleteCategory(deleteObj);
    	}else if(deleteType=='field'){
    		DeleteField(deleteObj);
    	}
       	confirmBox.hide();
    });
    confirmBox.find(".confirmno").unbind().click(function() {
       confirmBox.hide();
    });
    $('body').click(function (event) 
	{
	   if(!$(event.target).closest('#confirmbox').length && !$(event.target).is('#confirmbox')) {
	     confirmBox.hide();
	   }     
	});
}
/**Confirmation box js end**/

/**add new category js**/
$(document).ready(function() {
  $('#savecategorybtn').click(function() {
    var CategoryName=$('#savecategoryname').val();
    $('#addcategorydiv').find(' > li:nth-last-child(1)').before('<li class="nav-item"><a class="nav-link text_Uppercase" data-categoryid="" data-toggle="tab" href="#'+CategoryName+'">'+CategoryName+'<i class="fa fa-times-circle align-text-top pl-2 DeleteCategory"></i></a></li>');
    $('.tab-content').append('<div class="col-md-12 form_build_area droppable tab-pane fade" id="'+CategoryName+'" ></div>');
  	draganddrop();
  	DeleteCategory();
  });
});
/**add new category js end**/

/**Change field name while typing js**/
function FieldLabelChange(){
	$(document).on('keyup', '.FieldLabel', function(event) {
		var Objhtml = $(this).parent().parent().parent().parent().parent().find('.FieldHeading');
		Objhtml.html($(this).val());
	});
}
FieldLabelChange();
/**Change field name while typing js end**/

/**To save form js**/
var ValidateFlag;
var OptionFlag;
function SaveForm(){
	$(document).on('click', '#SaveForm', function(event) {
		event.stopPropagation();
    	event.stopImmediatePropagation();
		var ArrayObj = {};
		ValidateFlag=1;
		OptionFlag=1;
		$url='additem/SaveItem';
		if($('#itemname').val()==''){
			$('#itemname').parent().parent().find('.control-label').addClass('text-danger');
			ValidateFlag=0;
		}else{
			$('#itemname').parent().parent().find('.control-label').removeClass('text-danger');
			ValidateFlag=1;
		}
		  ArrayObj={
		  			'ItemId'  :$('#itemid').val(),
		  			'ItemName':$('#itemname').val(),
		  			'ItemLogo':$('#itemlogo').val(),
		  			'Fields':[]
		  		}
		if(ArrayObj.ItemId!=''){
			$url='../additem/SaveItem';
		}
		$('.form-horizontal .ToSaveField').each(function(){
    		var Field={};
    		var $this = $(this);
    		Field={'CategoryName':$this.find('input[name ="category"]').val(),
					'FieldName':$this.find('input[name ="label"]').val(),
    				'FieldType':$this.find('input[name ="fieldtype"]').val(),
					'FieldId':$this.find('input[name ="fieldid"]').val(),
					'DefaultValue':$this.find('input[name ="defaultvalue"]').val(),
					'Instruction':$this.find('textarea[name ="instruction"]').val(),
					'Price':$this.find('input[name ="price"]').val(),
					'MinValue':$this.find('input[name ="min"]').val(),
					'MaxValue':$this.find('input[name ="max"]').val(),
					'FiledValidation':$this.find('input[name ="FiledValidation"]').val(),
					'Required':$this.find('.requiredclass').val(),
					'ReadOnly':$this.find('.readonlyclass').val(),
					'HideField':$this.find('.hidefieldclass').val(),
					'Minheight':$this.find('input[name ="minheight"]').val(),
					'Maxheight':$this.find('input[name ="maxheight"]').val(),
					'Minwidth':$this.find('input[name ="minwidth"]').val(),
					'Maxwidth':$this.find('input[name ="maxwidth"]').val(),
					'FontSize':$this.find('input[name ="fontsize"]').val(),
					'Heading':$this.find('input[name ="heading"]').val(),
					'OptionArray':[]
				}
			if(Field.FieldType=='drop' || Field.FieldType=='single'){
				var option= $this.find('.ToSaveOption');
				option.each(function(){
					var $optionthis=$(this);
					var OptionObj={
						'OptionName':$optionthis.find('input[name="optionlabel"]').val(),
						'OptionPrice':$optionthis.find('input[name="optionprice"]').val(),
						'OptionImg':$optionthis.find('input[name="optionimg"]').val()
					}
					ValidateOption(option,OptionObj);
					Field['OptionArray'].push(OptionObj);
				});
			}
			ValidateFields($this,Field);
			OptionFlag=1;
			ArrayObj['Fields'].push(Field);
		});
		console.log(ArrayObj);
		if(ValidateFlag==1){
		var jsonString = JSON.stringify(ArrayObj);
		console.log(jsonString);
			$.ajax({
		        type: "POST",
		        headers: {
				    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
		        dataType: "json",
		        url: $url,
		        data: {data : jsonString},
		        success: function(data){
		          console.log(data);
		          		if(data.dataitem.StatusCode == 200 && ArrayObj.ItemId==''){ 
							window.location.href = window.location.href + "/"+data.dataitem.ItemId;

						}else if(data.dataitem.StatusCode == 200 && ArrayObj.ItemId!=''){
							window.location.reload();
						}
						else{
							alert('some error has occurred');
						}
		        },
		         error: function(data){
		         	console.log(data);
		         }
		    });
		}else{
			alert("Please fill all mandatory field");
		}
	});
}
SaveForm();

/**save form js end**/

/**Validate field js**/
function ValidateFields(thisobj,FieldArray){
	
	if(FieldArray.FieldName=='' || OptionFlag==0){
		thisobj.find('.toaddclass').addClass('bg-danger');
		ValidateFlag=0;
	}else if(FieldArray.FieldType=='heading' && (FieldArray.FieldName=='' || FieldArray.Heading=='')){
		thisobj.find('.toaddclass').addClass('bg-danger');
		ValidateFlag=0;
	}
	else{
		OptionFlag=1;
		thisobj.find('.toaddclass').removeClass('bg-danger');
	}
}
function ValidateOption(OptionObj,OptionArray){
	if(OptionArray.OptionName==''){
		OptionObj.find('.control-label:first').addClass('text-danger');
		OptionFlag=0;
	}else{
		OptionObj.find('.control-label:first').removeClass('text-danger');
	}
}
/**Validate field js end**/

/**Image covert to base64 for uploading js**/
// function ConvertImage(){
// 	$(document).on('change', 'input[type ="file"]', function(event) {
// 		if ($(this).files && $(this).files[0]) {
// 		    var reader = new FileReader();
		    
// 		    reader.onload = function(e) {
// 		      $(this).attr('value', e.target.result);
// 		    }
		    
// 		    reader.readAsDataURL($(this).files[0]); // convert to base64 string
// 		}
// 		alert(1)
// 	});
// }
// ConvertImage();
/**Image covert to base64 for uploading js end**/