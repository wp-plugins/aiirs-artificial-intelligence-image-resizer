jQuery(document).ready(function(){
    createCheckBox('');
    deleteMe();
 
    
    
});

function addMore() {
        var Ccount = current + 1;
        var addMoreLayout = '<tr id="aiirs['+Ccount+']">    '+
    '<td><input type="text" name="aiirs['+Ccount+'][size_name]" class="regular-text" /> </td>'+
    '<td><input type="text" name="aiirs['+Ccount+'][img_width] " class="regular-text" /> </td>'+
    '<td><input type="text" name="aiirs['+Ccount+'][img_height]" class="regular-text" /> </td>'+
    '<td><label><input name="aiirs['+Ccount+'][img_crop]" id="aiirs['+Ccount+'][img_crop]" type="checkbox" class="ios-switch" /> </label></td>'+
    '<td><input id="deleteCurrent" data-id="aiirs['+Ccount+']" class="button hidden button-secondary" type="button" value="-" name="deleteCurrent"> <input onclick="addMore();" id="addmore" class="button button-secondary" type="button" value="+" name="addmore"></td>'+
    '</tr>';
        current = Ccount;
        jQuery('td #addmore').remove();
        jQuery('td #deleteCurrent.hidden').removeClass('hidden');
        jQuery('table#translations tr:last').before(addMoreLayout);
        createCheckBox('aiirs['+Ccount+'][img_crop]');
        deleteMe();    
}

function deleteMe() {
 jQuery('input#deleteCurrent').unbind('click')
	jQuery('table#translations td').on('click','input#deleteCurrent',function(){
		jQuery(this).parent().parent().remove();
	});
}

function createCheckBox(elId){
    if(elId){  var switches = document.querySelectorAll('input[id="'+elId+'"].ios-switch');  } 
    else {  var switches = document.querySelectorAll('input[type="checkbox"].ios-switch');   }
	for (var i=0, sw; sw = switches[i++]; ) {
		var div = document.createElement('div');
		div.className = 'switch';
		sw.parentNode.insertBefore(div, sw.nextSibling);
	}
}