<?php
$aiirs_total = 0;
$aiirs_total = count($this->ex_values);
$aiirs_layout = '';
$aiirs_values = $this->ex_values;
$aiirs_layout .=  $this->table_trLayout('');
if($aiirs_values){
	$aiirs_trans = array_values($aiirs_values);
	foreach($aiirs_trans as $aiirs_k => $aiirs_tra){
		if(isset($aiirs_tra['img_crop'])){
			$check = 'checked';
		} else {
			$check="";
		}
		$aiirs_layout .= $this->table_trLayout(@$aiirs_k,@$aiirs_tra['size_name'],@$aiirs_tra['img_width'],@$aiirs_tra['img_height'],$check);
	}
} else  {
	$aiirs_layout .= $aiirs_layout .= $this->table_trLayout();
} 
?>


 
<script>
var current = <?php echo $aiirs_total; ?>;
</script>
 

    <form method="post">
    <table id="aiirs_image_size" class="form-table">
    	<thead>
            <tr valign="top">
                <th class="titledesc" scope="row">Size Name</th>
                <th class="titledesc" scope="row">Width</th>
                <th class="titledesc" scope="row">Height</th>
                <th class="titledesc" scope="row">Crop Type *</th>
                <th class="titledesc" scope="row">Options</th>
            </tr>
    	
    	</thead>
        <tbody> 
          <?php echo $aiirs_layout; ?>
           
             
            <tr >
                <td class="titledesc" scope="row"> 
                     <input id="aiirs_update" class="button button-primary" type="submit" value="Save Image Size's" name="aiirs_update">
                </td>
         
            </tr>

        </tbody>
    </table>
        <p>* Whether to crop images to specified height and width or resize <a title="http://www.davidtan.org/wordpress-hard-crop-vs-soft-crop-difference-comparison-example/" class="external text" href="http://www.davidtan.org/wordpress-hard-crop-vs-soft-crop-difference-comparison-example/">Difference between soft and hard crop</p>
    </form> 