<?php
/*  Copyright 2014  Varun Sridharan  (email : varunsridharan23@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 
    Plugin Name: AIIRS Artificial Intelligence Image Resizer
    Plugin URI: http://varunsridharan.in/
    Description: Add image size to crop image dynamically  with using code
    Version: 0.1
    Author: Varun Sridharan
    Author URI: http://varunsridharan.in/
    License: GPL2

*/
defined('ABSPATH') or die("No script kiddies please!");
$aiirs_plug_url = plugins_url('',__FILE__).'/';
$aiirs_path = plugin_dir_path( __FILE__ );
if ( is_admin() ){ 
    add_action('admin_menu', 'aiirs_add_menu');
	function aiirs_add_menu() {
		add_options_page('AIIRS Settings', 'AIIRS Settings', 'administrator','aiirs-page', 'aiirs_page');
 		add_option("aiirs_settings", '', '', 'yes');
	}
    if(isset($_POST['aiirs_update'])) {update_option( 'aiirs_settings', json_encode($_POST['aiirs']) );}
    $aiirs_values = json_decode(get_option('aiirs_settings'),true);

    if($aiirs_values){
        $aiirs_trans = array_values($aiirs_values);
        foreach($aiirs_trans as $aiirs_k => $aiirs_tra){
            if(isset($aiirs_tra['img_crop'])){$check = true;}
            else {$check=false;}
            add_image_size( $aiirs_tra['size_name'], $aiirs_tra['img_width'], $aiirs_tra['img_height'], $check ); 
        }

    }    

 
    function display_custom_image_sizes( $sizes ) {
        global $_wp_additional_image_sizes;
        if ( empty($_wp_additional_image_sizes) )
        return $sizes;

        foreach ( $_wp_additional_image_sizes as $id => $data ) {
        if ( !isset($sizes[$id]) )
          $sizes[$id] = ucfirst( str_replace( '-', ' ', $id ) );
        }

        return $sizes;
    } 
   add_filter( 'image_size_names_choose', 'display_custom_image_sizes' );  
  
    
}
 

function aiirs_page(){
    global $aiirs_plug_url,$aiirs_values;
    $aiirs_total = 0;
   
    $aiirs_total = count($aiirs_total);
    $aiirs_layout = '';

if($aiirs_values){
     $aiirs_trans = array_values($aiirs_values);
foreach($aiirs_trans as $aiirs_k => $aiirs_tra){
    if(isset($aiirs_tra['img_crop'])){$check = 'checked';}
    else {$check="";}
	$aiirs_layout .= '
            <tr id="aiirs['.$aiirs_k.']">    
                <td><input type="text" name="aiirs['.$aiirs_k.'][size_name]" value="'.$aiirs_tra['size_name'].'" class="regular-text" /> </td>
                <td><input type="text" name="aiirs['.$aiirs_k.'][img_width]" value="'.$aiirs_tra['img_width'].'" class="regular-text" /> </td>
                <td><input type="text" name="aiirs['.$aiirs_k.'][img_height]" value="'.$aiirs_tra['img_height'].'" class="regular-text" /> </td>
                <td><label><input name="aiirs['.$aiirs_k.'][img_crop]" id="aiirs['.$aiirs_k.'][img_crop]" type="checkbox" class="ios-switch" '.$check.' /> </label></td>
                <td>
                    <input  id="deleteCurrent" data-id="aiirs['.$aiirs_k.']"  class="button hidden button-secondary" type="button" value="-" name="deleteCurrent">
                    <input onclick="addMore();"  id="addmore" class="button button-secondary" type="button" value="+" name="addmore">
                </td>
            </tr> ';	
}

} else  {
$aiirs_layout .= '
            <tr id="aiirs[0]">    
                <td><input type="text" name="aiirs[0][size_name]" class="regular-text" /> </td>
                <td><input type="text" name="aiirs[0][img_width]" class="regular-text" /> </td>
                <td><input type="text" name="aiirs[0][img_height]" class="regular-text" /> </td>
                <td><label><input name="aiirs[0][img_crop]" id="aiirs[0][img_crop]" type="checkbox" class="ios-switch" /> </label></td>
                <td>
                    <input  id="deleteCurrent" data-id="aiirs[0]"  class="button hidden button-secondary" type="button" value="-" name="deleteCurrent">
                    <input onclick="addMore();"  id="addmore" class="button button-secondary" type="button" value="+" name="addmore">
                </td>
            </tr>
';		
}
         
?>


 
<script>
var current = <?php echo $aiirs_total; ?>;
</script>

<script src="<?php echo $aiirs_plug_url; ?>script.js"></script>
<link href="<?php echo $aiirs_plug_url; ?>style.css" rel="stylesheet"/>
<div class="wrap">
 
	<h2>Artificial Intelligence Image Resizer [AIIRS]</h2>
    <hr/>
    <form method="post">
    <table id="translations" class="tab form-table">
        <tbody> 
            
            <tr valign="top">
                <th class="titledesc" scope="row">Size Name</th>
                <th class="titledesc" scope="row">Width</th>
                <th class="titledesc" scope="row">Height</th>
                <th class="titledesc" scope="row">Crop Type *</th>
                <th class="titledesc" scope="row">Options</th>
            </tr>
             
          <?php echo $aiirs_layout; ?>
           
             
            <tr >
                <td class="titledesc" scope="row"> 
                    <input style="display:none;" id="addmore" onclick="addMore();" class="button button-secondary" type="button" value="Add More" name="addmore">
                     <input id="aiirs_update" class="button button-primary" type="submit" value="Save Size's" name="aiirs_update">
                </td>
         
            </tr>

        </tbody>
    </table>
        <p>* Whether to crop images to specified height and width or resize <a title="http://www.davidtan.org/wordpress-hard-crop-vs-soft-crop-difference-comparison-example/" class="external text" href="http://www.davidtan.org/wordpress-hard-crop-vs-soft-crop-difference-comparison-example/">Difference between soft and hard crop</p>
    </form>
</div>



<?php
}
?>