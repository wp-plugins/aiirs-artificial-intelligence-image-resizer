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
    Version: 0.2
    Author: Varun Sridharan
    Author URI: http://varunsridharan.in/
    License: GPL2

*/
defined('ABSPATH') or die("No script kiddies please!");
define('aiirs_url',plugins_url('',__FILE__).'/');
define('aiirs_path',plugin_dir_path( __FILE__ ));


class Artificial_Intelligence_Image_Resizer{

	public $aiirs_v;
	public $ex_values;
	private $imageSize;
	private $imageMap;
	public $remap_image;
	
	/**
	 * Base Class Setup
	 * @since 0.1
	 * @access public
	 */
	public function __construct() {
		$this->aiirs_v = '0.2';
		register_activation_hook( __FILE__, array($this ,'_activate') ); 
		$this->SaveData();
		$this->get_existing_value();
		$this->add_new_image_size();
		$this->reamp_array_ready();
		
		add_filter('post_thumbnail_size',array($this,'map_image_size'));
		add_filter( 'image_size_names_choose', array($this,'add_size_media_uploader') );
		add_filter( 'init', array($this,'get_image_sizes') );
		if(is_admin()){
			add_action('admin_menu', array($this,'add_menu'));
		}
	}	
	
	/**
	 * Plugin Activation Function
	 * @since 0.1
	 * @access public
	 */
	public function _activate(){
		$value = '';
		if(get_option('aiirs_settings')){
			$value = get_option('aiirs_settings');
			delete_option('aiirs_settings');
		}
		add_option("aiirs_image_size", $value, '', 'yes');
		add_option("aiirs_size_map", '', '', 'yes');
		
	}
	
	/**
	 * Add Link Under Settings Menu
	 * @since 0.1
	 * @access public
	 */
	public function add_menu(){
		$page1 = add_menu_page('AiirS', 'AiirS', 'administrator','aiirs-page',array($this,'aiirs_page'), '');
		add_submenu_page( 'aiirs-page', 'Add Image Size', 'Add Image Size', 'administrator', 'aiirs-page', array($this,'aiirs_page') );
		$page2 = add_submenu_page( 'aiirs-page', 'Map Image Size', 'Map Image Size', 'administrator', 'aiirs-map-sizepage',array($this,'aiirs_map_sizepage') );

		# Register Style & Script
		$this->register_script_style();
		add_action( 'admin_print_styles-' . $page1, array($this,'enqueue_script_style') ); 
		add_action( 'admin_print_styles-' . $page2, array($this,'enqueue_script_style') );
	}
	


	/**
	 * Register All Needed Scripts & Styles
	 * @since 0.1
	 * @access public
	 */
	public function register_script_style(){
		wp_register_script('aiirs_script',aiirs_url.'js/script.js', array( 'jquery' ), $this->aiirs_v, false );
		wp_register_style ('aiirs_style', aiirs_url.'css/style.css', false,$this->aiirs_v, 'all' );
	}
	
	/**
	 * Enqueue All Needed Scripts
	 * @since 0.1
	 * @access public
	 */
	public function enqueue_script_style() {
		wp_enqueue_script( 'aiirs_script' );
		wp_enqueue_style( 'aiirs_style' );
	}

	/**
	 * Save Plugin Data
	 * @since 0.1
	 * @access public
	 */	
	private function SaveData(){ 
		if(isset($_POST['aiirs_update'])) {
			$image = $_POST['aiirs'];
			foreach($image as $key => $img){
				if(empty($img['size_name'])){
					unset($image[$key]);
				}
			} 
			update_option( 'aiirs_image_size', json_encode(array_values($image)) );
		}	

		if(isset($_POST['aiirs_update_image_map'])){
			$image = $_POST['aiirs_mapimagep'];
			foreach($image as $key => $img){
				if(empty($img['key'])){
					unset($image[$key]);
				}
			} 
			update_option( 'aiirs_size_map', json_encode(array_values($image)) );
		}
	}
	
	/**
	 * Get Existing Database Values
	 * @return Array [$this->ex_values,$this->imageMap]
	 * @since 0.1
	 * @access public
	 */
	private function get_existing_value() {
		$values = json_decode(get_option('aiirs_image_size'),true);
		$values2 = json_decode(get_option('aiirs_size_map'),true);
		
		if(isset($values) && ! empty($values)){ $this->ex_values = $values; } 
		else { $this->ex_values = array(); }

		if(isset($values2) && ! empty($values2)){ $this->imageMap = $values2; } 
		else { $this->imageMap = array(); }
	}
	
	/**
	 * Add Image Sizes To WP
	 * @since 0.1
	 * @access public 
	 */
	private function add_new_image_size(){
		$aiirs_trans = array_values($this->ex_values);
		foreach($aiirs_trans as $aiirs_k => $aiirs_tra){
			if(isset($aiirs_tra['img_crop'])){
				$check = true;
			} else {
				$check=false;
			} 
			$this->add_size(@$aiirs_tra['size_name'], @$aiirs_tra['img_width'], @$aiirs_tra['img_height'], @$check);
		}		
	}
	
	/**
	 * Adds Image Size To WordPress
	 * @param string $name name of the image size
	 * @param int $width size of the image in px
	 * @param int $height size of the image in px
	 * @param boolean $check
	 * @since 0.1
	 * @access private
	 */
	private function add_size($name,$width,$height,$check){
		add_image_size($name,$width,$height,$check);
	}
	
	
	/**
	 * Generate Re-Map Image Array
	 * @since 0.2
	 * @return array [$this->remap_image]
	 * @access private 
	 */
	private function reamp_array_ready(){
		$temp_array = array();
		foreach($this->imageMap as $value){
			$temp_array[$value['key']] = $value['val'];
		}
		$this->remap_image = $temp_array; 
	}
	
	/**
	 * Re-Map Image Size If called image size exist in remap_image array
	 * @param string $size called image size
	 * @return string new image size
	 * @since 0.2
	 * @access public
	 */	
	public function map_image_size($size){ 
		if(isset($this->remap_image[$size])){
			return $this->remap_image[$size];
		}
		return $size;		
	}
	
	/**
	 * Adds Custom Sizes In Media Uploader / Media Selector Box
	 * @return array sizes
	 * @since 0.1
	 */	
	public function add_size_media_uploader($sizes){
		global $_wp_additional_image_sizes;
		if ( empty($_wp_additional_image_sizes) )
			return $sizes;
		
		foreach ( $_wp_additional_image_sizes as $id => $data ) {
			if ( !isset($sizes[$id]) )
				$sizes[$id] = ucfirst( str_replace( '-', ' ', $id ) );
		}
		
		return $sizes;		
	}
	
	/**
	 * Get All Registed Image Size
	 * @return array [$this->imageSize]
	 * @since 0.1
	 * @access public
	 */
	public function get_image_sizes(){
		$imgsize = get_intermediate_image_sizes();
		$sizes = array();
		$searchReplace = array('-','_','/','&');
		foreach ( $imgsize as $id => $data ) {
			if ( !isset($sizes[$id]) )
				$sizes[$data] = ucfirst( str_replace($searchReplace,' ',$data ));
		}
		$this->imageSize =  $sizes;
	}
	
	/**
	 * Add Image Size Page Layout
	 * @since 0.1
	 * @access public
	 */
	public function aiirs_page(){
		$pageTitle = 'Artificial Intelligence Image Resizer [Aiirs]';
		require(aiirs_path.'inc/header.php');
		require(aiirs_path.'inc/aiirs_page.php');
		require(aiirs_path.'inc/footer.php');
	}
	
	/**
	 * Re-Map Image Size Page
	 * @since 0.2
	 * @access public
	 */	
	public function aiirs_map_sizepage(){ 
		$pageTitle = 'Artificial Intelligence Image Size Mapper';
		require(aiirs_path.'inc/header.php');
		require(aiirs_path.'inc/aiirs_map_page.php');
		require(aiirs_path.'inc/footer.php');
	}
	
	/**
	 * Table Tr Layout for add image size page
	 * @param number $key
	 * @param string $name
	 * @param string $width
	 * @param string $height
	 * @param string $crop
	 * @return html tr
	 * @since 0.2
	 * @access private
	 */
	private function table_trLayout($key = 0,$name = '',$width = '',$height = '',$crop = ''){
		return '<tr id="'.$key.'">
                <td><input type="text" name="aiirs['.$key.'][size_name]" value="'.$name.'" class="regular-text" /> </td>
                <td><input type="text" name="aiirs['.$key.'][img_width]" value="'.$width.'" class="minBox regular-text" /> </td>
                <td><input type="text" name="aiirs['.$key.'][img_height]" value="'.$height.'" class="minBox regular-text" /> </td>
                <td class="checkbox" ><label><input name="aiirs['.$key.'][img_crop]" type="checkbox" class="ios-switch" '.$crop.' /> </label></td>
                <td class="action_button" >
                    <input data-id="'.$key.'"  class="delete button button-secondary" type="button" value="-"/>
                    <input data-id="'.$key.'"  class="addmore button button-primary" type="button" value="+"/>
                </td>
            </tr>';
		
	}

	/**
	 * Table Tr Layout for Re-Map image page
	 * @param number $id
	 * @param string $kselected
	 * @param string $vselected
	 * @return string
	 * @since 0.2
	 * @access private
	 */
	private function image_map_layout($id=0,$kselected='',$vselected=''){
		$layout = '<tr id="'.$id.'">';
			$layout .= '<td>';
				$layout .= '<select name="aiirs_mapimagep['.$id.'][key]" >';
					$layout .= $this->gen_selBox($kselected);
				$layout .= '</select>';
			$layout .= '</td>';
			
			$layout .= '<td>';
				$layout .= '<==>';
			$layout .= '</td>';
			
			$layout .= '<td>';  
				$layout .= '<select name="aiirs_mapimagep['.$id.'][val]" >';
					$layout .= $this->gen_selBox($vselected);
				$layout .= '</select>';
			$layout .= '</td>';
			
			$layout .= '<td class="action_button" >';
				$layout .= '<input class="delete button button-secondary" type="button" value="-"/>';
				$layout .= '<input class="addmore button button-primary" type="button" value="+"/>';
			$layout .= '</td>';
		$layout .= '</tr>';
		return $layout; 	
	}
	
	/**
	 * Select Box Generator For Re-Map Image Size
	 * @param string $selected
	 * @return string
	 * @since 0.2
	 * @access private
	 */
	private function gen_selBox($selected =''){
		$selc = '';
		if(! $selected){ $selc  = 'selected'; }
		$layout  = '<option value="">Select Any One</option>'; 
		foreach($this->imageSize as $key => $val){
			$select = '';
			if($key == $selected){
				$select = 'selected';
			}			
			$layout .= '<option value="'.$key.'" '.$select.'>'.$val.'</option>';
		}
		return $layout;		
	}
}

$Artificial_Intelligence_Image_Resizer = new Artificial_Intelligence_Image_Resizer;
?>