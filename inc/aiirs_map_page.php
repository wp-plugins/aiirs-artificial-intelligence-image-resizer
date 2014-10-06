<?php 
$aiirs_total = 0;
$aiirs_total = count($this->imageMap);
$aiirs_layout = '';
$aiirs_values = $this->imageMap;
$aiirs_layout .=  $this->image_map_layout('');

?>
<script>
var current = <?php echo $aiirs_total; ?>;
var options = '<?php echo $this->gen_selBox(); ?>';
</script>
 
 
<form method="post">
    <table id="aiirs_map_image" class="details">
    	<thead>
            <tr valign="top">
                <th class="titledesc" scope="row">Existing Image Name</th>
                <th class="titledesc" scope="row">Width</th>
                <th class="titledesc" scope="row">New Image Name</th>
               <th class="titledesc" scope="row">Options</th>
            </tr>
    	
    	</thead>
        <tbody> 
        	
        	<?php 
        	if($aiirs_values){ 
        		foreach($aiirs_values as $key => $val){ 
        			$aiirs_layout .=  $this->image_map_layout($key,$val['key'],$val['val']); 
        		}
        	} else {
        		$aiirs_layout .=  $this->image_map_layout('0');
        	}
        	echo $aiirs_layout; 
        	?>
        </tbody>
    </table>
    <input id="aiirs_update" class="button button-primary" type="submit" value="Save Image Size's" name="aiirs_update_image_map">
</form> 