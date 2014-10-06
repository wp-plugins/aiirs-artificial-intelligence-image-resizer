jQuery(document).ready(function(){
    createCheckBox(''); 
	
	
	jQuery('table#aiirs_image_size tbody tr:first').hide();
	jQuery('table#aiirs_image_size input.addmore').hide();
	jQuery('table#aiirs_image_size .addmore:last').show();
	jQuery('table#aiirs_image_size').on('click','input.addmore',function(){ 
		jQuery('table#aiirs_image_size tr:last').before(table_layout());
		createCheckBox(current);
		jQuery('table#aiirs_image_size input.addmore').hide();
		jQuery('table#aiirs_image_size .addmore:last').show();
	});
	
	jQuery('table#aiirs_image_size').on('click','input.delete',function(){ 
		jQuery(this).parent().parent().remove();
		jQuery('input.addmore').hide();
		jQuery('table#aiirs_image_size .addmore:last').show();
	});	
	
	
	
	
	
	
	
	jQuery('table#aiirs_map_image tbody tr:first').hide();
	jQuery('table#aiirs_map_image input.addmore').hide();
	jQuery('table#aiirs_map_image .addmore:last').show();
	jQuery('table#aiirs_map_image').on('click','input.addmore',function(){ 
		jQuery('table#aiirs_map_image tr:last').after(Img_map());
		createCheckBox(current);
		jQuery('table#aiirs_map_image input.addmore').hide();
		jQuery('table#aiirs_map_image .addmore:last').show();
	});
	
	jQuery('table#aiirs_map_image').on('click','input.delete',function(){ 
		jQuery(this).parent().parent().remove();
		jQuery('input.addmore').hide();
		jQuery('table#aiirs_map_image .addmore:last').show();
	});		
});

function Img_map(){
	var key = current + 1;
	var layaout ='<tr>'+
	'<td>'+
		'<select name="aiirs_mapimagep['+key+'][key]" >'+options+'</select>'+
	'</td>'+
	'<td> <==> </td>'+
	'<td>'+  
		'<select name="aiirs_mapimagep['+key+'][val]" >'+options+'</select>'+
	'</td>'+
	'<td class="action_button" >'+
		'<input class="delete button button-secondary" type="button" value="-"/>'+
		'<input class="addmore button button-primary" type="button" value="+"/>'+
	'</td>'+
'</tr>';
	
	current = key;
	return layaout;
}
function table_layout() {
	var key = current + 1;
	var layaout = '<tr id="'+key+'">'+
	'<td>'+
	'	<input type="text" name="aiirs['+key+'][size_name]" value="" class="regular-text" />'+
	'</td>'+
	'<td>'+
	'	<input type="text" name="aiirs['+key+'][img_width]" value="" class="minBox regular-text" />'+
	'</td>'+
	'<td>'+
	'		<input type="text" name="aiirs['+key+'][img_height]" value="" class="minBox regular-text" />'+
	'</td>'+
	'<td class="checkbox" >'+
	'	<label><input name="aiirs['+key+'][img_crop]" id="chkbox'+key+'" type="checkbox" class="ios-switch" /> </label>'+
	'</td>'+
	'<td class="action_button" >'+
	'	<input data-id="'+key+'"  class="delete button button-secondary" type="button" value="-"/>'+
	'	<input data-id="'+key+'"  class="addmore button button-primary" type="button" value="+"/>'+
	'</td>'+
	'</tr>';
	current = key;
	return layaout;
}
  

function createCheckBox(key){
    if(key){  var switches = document.querySelectorAll('input[id="chkbox'+key+'"].ios-switch');  } 
    else {  var switches = document.querySelectorAll('input[type="checkbox"].ios-switch');   }
	for (var i=0, sw; sw = switches[i++]; ) {
		var div = document.createElement('div');
		div.className = 'switch';
		sw.parentNode.insertBefore(div, sw.nextSibling);
	}
}