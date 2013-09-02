<?php ob_start(); ?>
<?php
// Avoid direct calls to this file where wp core files not present
if (!function_exists ('add_action')) {
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
}
//Check Version
function get_themedata($file){
	global $wp_version;
	if ( version_compare( $wp_version, '3.3.2', '>' ) ) {
		return wp_get_theme();	
	}else{
		return get_theme_data($file); 
	}
}
//includes
require_once('inc/instance.php');
//////////////////////////////////////////
$lang = get_template_directory_uri() . '/lang';
load_theme_textdomain(TEXTDOMAIN, $lang);
//////////////////////////////////////////
require_once(get_template_directory().'/inc/next_options.php');
require_once(get_template_directory().'/admin/google-fonts.php'); 
global $admin_options;
//////////////////////////////////////////
require_once('inc/notifier/update-notifier.php');
require_once('inc/class.nextframwork.php');
require_once('inc/widgets/prc.php');

require_once('inc/widgets/archives.php');
require_once('inc/widgets/video.php');
require_once('inc/widgets/subscribe.php');

//Metaboxs
require_once('inc/metabox/featured.php');

//Class
 require_once('inc/class/class.action-hook.php');
 require_once('inc/class/class.category-field.php');
 require_once('inc/class/class.metabox.php');
 require_once('inc/class/class.sidebar.php');


/////////////////////////////////// IF ENABLE REMOVE SEO METABOX ////////////////////////////////////////////
if($admin_options['seo-activate']){
	require_once('inc/metabox/seo.php');
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

require_once('inc/menu/class.nextmagazine.menu.php');
require_once('inc/menu/class.nextmagazine.submenu.php');

require_once('inc/shortcodes/shortcodes.php');
require_once('inc/shortcodes/shortcodes-generator.php');

require_once('inc/functions.php');
require_once('inc/custom-post/gallery.php');

if ( ! isset( $content_width ) ) $content_width = 900;
///////////////////////////Featured Image//////////////////////////////
if ( function_exists( 'add_theme_support' ) ) {
	    add_theme_support( 'post-thumbnails' );
	    add_theme_support( 'automatic-feed-links' );
        set_post_thumbnail_size( 150, 150 ); // default Post Thumbnail dimensions  
        add_image_size( 'banner-image', 135, 135, true);     
        add_image_size( 'featured-image', 600, 375);     
		add_image_size( 'thumbnails-image', 500, 500, true); 
		add_image_size( 'category_ads', 291, 125);  
		add_image_size( 'category_ads_s', 615, 67);  

		function custom_image_sizes_choose( $sizes ) {
			$custom_sizes = array();
			
				$custom_sizes['featured-image'] = __('Featured Image',TEXTDOMAIN);
				$custom_sizes['banner-image'] = __('Widget Banner',TEXTDOMAIN);
			return array_merge( $sizes, $custom_sizes );
		}
		add_filter( 'image_size_names_choose', 'custom_image_sizes_choose' );

}

//Add Editor Style 
add_editor_style();


function register_my_menus() {
  register_nav_menus(
    array( 'main-menu' => __( 'Main Menu' ,TEXTDOMAIN) )
  );
    register_nav_menus(
    array( 'sub-menu' => __( 'Sub Menu', TEXTDOMAIN ) )
  );
}
add_action( 'init', 'register_my_menus' );

//Remove title from Category
function next_categories_remove_title_attributes($output) {
    $output = preg_replace('` title="(.+)"`', '', $output);
    return $output;
}
add_filter('wp_list_categories', 'next_categories_remove_title_attributes');

//Current menu item
add_filter( 'nav_menu_css_class', 'next_nav_menu_current_item', 10, 2 );
function next_nav_menu_current_item($classes = array(), $menu_item = false){

	if(@in_array('current-menu-item', $menu_item->classes)){
		$classes[] = 'active';
	}

	return $classes;
}
//////////////////////////////////////////////FOOTER///////////////////////////////////////////
function next_footer_js(){
	global $admin_options;
	if(!is_admin()){
		//Register javascript
		wp_register_script('next_flexslider', get_template_directory_uri()."/js/jquery.flexslider.js", array(),false, true);
		wp_register_script('next_superfish', get_template_directory_uri()."/js/jquery.superfish.js", array(),false, true);
		wp_register_script('next_selectbox', get_template_directory_uri()."/js/jquery.selectbox.min.js", array(),false, true);
		wp_register_script('next_masonry', get_template_directory_uri()."/js/jquery.masonry.min.js", array(),false, true);
		wp_register_script('next_fancybox', get_template_directory_uri()."/js/jquery.fancybox.js", array(),false, true);
		wp_register_script('next_carousel', get_template_directory_uri()."/js/jquery.jcarousel.min.js", array(),false, true);
		wp_register_script('next_validate', get_template_directory_uri().'/admin/js/comment/jquery.validate.min.js', false, false, true);
		wp_register_script('next_comment', get_template_directory_uri().'/admin/js/comment/comment.js', false, false, false);
		wp_register_script('next_map', 'http://maps.google.com/maps/api/js?sensor=false', false, false, false);
		wp_register_script('next_gmap', get_template_directory_uri().'/js/gmap3.min.js', false, false, false);
		wp_register_script('next_script', get_template_directory_uri()."/js/script.js", array('jquery'),false, true);

		//Jquery
		wp_enqueue_script('jquery');
		
		wp_enqueue_script('next_flexslider');
		wp_enqueue_script('next_superfish');
		wp_enqueue_script('next_selectbox');
		wp_enqueue_script('next_masonry');
		wp_enqueue_script('next_fancybox');
		wp_enqueue_script('next_carousel');

		if(is_page_template('contact-page.php')){ 
			wp_enqueue_script('next_map');
			wp_enqueue_script('next_gmap');
		}


		if(is_single() || is_singular() || is_page()){
			wp_enqueue_script('next_validate');
			wp_enqueue_script('next_comment');
		}

		wp_enqueue_script('next_script');

		$contactMap = array('lat' => $admin_options['lat'],'lon' => $admin_options['lng'], 'ajaxurl' => admin_url('admin-ajax.php'), 'siteurl'=> get_template_directory_uri());
		wp_localize_script( 'next_script', 'contact', $contactMap );

/////////////////////////////////////////////////////////// CSS /////////////////////////////////////////////////////////////////
		wp_register_style('next_validate_css', get_template_directory_uri().'/admin/css/validate.css');
		
		//Custom css with category
		$cats 	 = '';
		if(is_category()):
			$cat_id = get_query_var('cat');
			$cats .= '?cat_id='. $cat_id; 
		endif;
		wp_register_style('next_custom_css', get_template_directory_uri().'/css/custom.php'.$cats);

		// All fonts from google

		$googlefontsurl  = 'Acme';
		if($admin_options['select-font-text'] != 'arial' AND $admin_options['select-font-text'] != 'Times New Roman' AND $admin_options['select-font-text'] != 'Verdana' AND $admin_options['select-font-text'] != 'Georgia'):
			$googlefontsurl .=  '|'.$admin_options['select-font-text'];
		endif;
		if($admin_options['select-font-description'] != 'arial' AND $admin_options['select-font-description'] != 'Times New Roman' AND $admin_options['select-font-description'] != 'Verdana' AND $admin_options['select-font-description'] != 'Georgia'):
			$googlefontsurl .= '|'.$admin_options['select-font-description'];
		endif;
		if($admin_options['typography_general'] != 'arial' AND $admin_options['typography_general'] != 'Times New Roman' AND $admin_options['typography_general'] != 'Verdana' AND $admin_options['typography_general'] != 'Georgia'):
			$googlefontsurl .= '|'.$admin_options['typography_general']['font'];
		endif;
		if($admin_options['typography_submenu'] != 'arial' AND $admin_options['typography_submenu'] != 'Times New Roman' AND $admin_options['typography_submenu'] != 'Verdana' AND $admin_options['typography_submenu'] != 'Georgia'):
			$googlefontsurl .= '|'.$admin_options['typography_submenu']['font'];
		endif;
		if($admin_options['typography_pagetitle'] != 'arial' AND $admin_options['typography_pagetitle'] != 'Times New Roman' AND $admin_options['typography_pagetitle'] != 'Verdana' AND $admin_options['typography_pagetitle'] != 'Georgia'):
			$googlefontsurl .= '|'.$admin_options['typography_pagetitle']['font'];
		endif;
		if($admin_options['typography_article'] != 'arial' AND $admin_options['typography_article'] != 'Times New Roman' AND $admin_options['typography_article'] != 'Verdana' AND $admin_options['typography_article'] != 'Georgia'):
			$googlefontsurl .= '|'.$admin_options['typography_article']['font'];
		endif;
		if($admin_options['typography_postmeta'] !== 'arial' AND $admin_options['typography_postmeta'] !== 'Times New Roman' AND $admin_options['typography_postmeta'] !== 'Verdana' AND $admin_options['typography_postmeta'] !== 'Georgia'):
			$googlefontsurl .= '|'.$admin_options['typography_postmeta']['font'];
		endif;
		if($admin_options['typography_postentry'] != 'arial' AND $admin_options['typography_postentry'] != 'Times New Roman' AND $admin_options['typography_postentry'] != 'Verdana' AND $admin_options['typography_postentry'] != 'Georgia'):
			$googlefontsurl .= '|'.$admin_options['typography_postentry']['font'];
		endif;
		if($admin_options['typography_widgetstitles'] != 'arial' AND $admin_options['typography_widgetstitles'] != 'Times New Roman' AND $admin_options['typography_widgetstitles'] != 'Verdana' AND $admin_options['typography_widgetstitles'] != 'Georgia'):
			$googlefontsurl .= '|'.$admin_options['typography_widgetstitles']['font'];
		endif;
		if($admin_options['typography_widgetsfooter'] != 'arial' AND $admin_options['typography_widgetsfooter'] != 'Times New Roman' AND $admin_options['typography_widgetsfooter'] != 'Verdana' AND $admin_options['typography_widgetsfooter'] != 'Georgia'):
			$googlefontsurl .= '|'.$admin_options['typography_widgetsfooter']['font'];
		endif;
		if($admin_options['typography_hone'] != 'arial' AND $admin_options['typography_hone'] != 'Times New Roman' AND $admin_options['typography_hone'] != 'Verdana' AND $admin_options['typography_hone'] != 'Georgia'):
			$googlefontsurl .= '|'.$admin_options['typography_hone']['font'];
		endif;
		if($admin_options['typography_htwo'] != 'arial' AND $admin_options['typography_htwo'] != 'Times New Roman' AND $admin_options['typography_htwo'] != 'Verdana' AND $admin_options['typography_htwo'] != 'Georgia'):
			$googlefontsurl .= '|'.$admin_options['typography_htwo']['font'];
		endif;
		if($admin_options['typography_hthree'] != 'arial' AND $admin_options['typography_hthree'] != 'Times New Roman' AND $admin_options['typography_hthree'] != 'Verdana' AND $admin_options['typography_hthree'] != 'Georgia'):
			$googlefontsurl .= '|'.$admin_options['typography_hthree']['font'];
		endif;
		if($admin_options['typography_hfour'] != 'arial' AND $admin_options['typography_hfour'] != 'Times New Roman' AND $admin_options['typography_hfour'] != 'Verdana' AND $admin_options['typography_hfour'] != 'Georgia'):
			$googlefontsurl .= '|'.$admin_options['typography_hfour']['font'];
		endif;
		if($admin_options['typography_hfive'] != 'arial' AND $admin_options['typography_hfive'] != 'Times New Roman' AND $admin_options['typography_hfive'] != 'Verdana' AND $admin_options['typography_hfive'] != 'Georgia'):
			$googlefontsurl .= '|'.$admin_options['typography_hfive']['font'];
		endif;
		if($admin_options['typography_hsix'] != 'arial' AND $admin_options['typography_hsix'] != 'Times New Roman' AND $admin_options['typography_hsix'] != 'Verdana' AND $admin_options['typography_hsix'] != 'Georgia'):	
			$googlefontsurl .= '|'.$admin_options['typography_hsix']['font'];
		endif;

		wp_register_style('next_googlefonts', 'http://fonts.googleapis.com/css?family='.$googlefontsurl, array(), false, 'all');
		wp_enqueue_style('next_googlefonts', false, array(), false, 'all');

		wp_enqueue_style('next_custom_css');
		if(is_single() || is_singular() || is_page() || is_page_template('contact-page.php')):
			wp_enqueue_style('next_validate_css');
		endif;
		

	}
}
add_action('wp_enqueue_scripts','next_footer_js');

////////////////////////////////////////REMOVE GENERATOR//////////////////////////////////////////////////
remove_action('wp_head', 'wp_generator');
//////////////////////////////////////// AUTO BR ////////////////////////////////////////////////////////
//add_filter('the_content','nl2br');
add_filter('the_content','wpautop');
/////////////////////////////////////// DEFAULT AVATAR //////////////////////////////////////////////////
if ( !function_exists('next_defaultavatar') ) {
	function next_defaultavatar( $avatar_defaults ) {
		$myavatar = TEMPLATE_URL . '/images/avatar-magazine.png';
		$avatar_defaults[$myavatar] = 'nextWPthemes';
		return $avatar_defaults;
	}
	add_filter( 'avatar_defaults', 'next_defaultavatar' );
}
function favicon(){
	global $admin_options;
	if(!empty($admin_options['favicon-src'])){
		echo '<link rel="shortcut icon" href="'.$admin_options['favicon-src'].'" type="image/x-icon">';
	}
}
add_action('wp_head', 'favicon');
function googlecode(){
	global $admin_options;
	echo $admin_options['googlecode'];
}
add_action('next_footer','googlecode');

function next_footer(){
	do_action('next_footer');
}

//Custom CSS
add_filter('tiny_mce_before_init', 'add_custom_classes');
function add_custom_classes($arr_options) {
	global $post;
	if('page' == get_post_type($post->ID)):
		$arr_options['theme_advanced_styles'] = "Title Style=shortcodes-title";
		$arr_options['theme_advanced_buttons2_add_before'] = "styleselect";

	endif;
	return $arr_options;
}

////////////////////////////////////////////// SET CLASS OR ID //////////////////////////////////////////
function next_set_current($action, $current, $element, $echo=false){
	if($action == $current){
		if(!empty($element)){
			if($echo){
				echo $element;
			}else{
				return $element;
			}
		}
	}
}
function next_current_cat($current, $value = null, $echo = false){
	if($current == $value){
		if($echo){
			echo 'yes';
		}else{
			return 'yes';
		}
	}
}
///////////////////////////////////////////// SIDEBAR META /////////////////////////////////////////////
	$screen_meta  = get_all_posttype(); 
	$screen_meta = array_diff($screen_meta, array('next_gallery'));

	$metabox_test = array(
	array(
			'id'		=> 'sidebar-position',
			'title'		=> __('Sidebare Position', TEXTDOMAIN),
			'callback'	=> 'sidebar_position_fn',
			'screen'	=> $screen_meta,
			'save_fn'	=> 'sidebar_position_fn_save'
		)
	);

	$metabox  = new metaboxs($metabox_test);

	/////////////////////////////////// SIDEBAR //////////////////////////////////////////////

	function sidebar_position_fn(){
		global $post, $admin_options;
		$value = get_post_meta($post->ID, '_sidebar', true);
		
		!empty($value) ? $sidebar_name = $value['sidebar'] : $sidebar_name = 'default';
		!empty($value) ? $value = $value['position'] : $value = 'default';


		wp_nonce_field('sidebar_action', 'sidebar_nonce');
		?>
		<div id="align-sidebar">
			<div data-position="default" class="<?php next_set_current($value, 'default', 'active', true); ?>">
				<img src="<?php echo get_template_directory_uri(); ?>/admin/img/sidebar-default.png">
				<div class="done"></div>
			</div>
			<div data-position="left" class="<?php next_set_current($value, 'left', 'active', true); ?>">
				<img src="<?php echo get_template_directory_uri(); ?>/admin/img/sidebar-left.png">
				<div class="done"></div>
			</div>
			<div data-position="none" class="<?php next_set_current($value, 'none', 'active', true); ?>">
				<img src="<?php echo get_template_directory_uri(); ?>/admin/img/sidebar-no.png">
				<div class="done"></div>
			</div>
			<div data-position="right" class="<?php next_set_current($value, 'right', 'active', true); ?>">
				<img src="<?php echo get_template_directory_uri(); ?>/admin/img/sidebar-right.png">
				<div class="done"></div>
			</div>
		</div>
		<input type="hidden" name="sidebar_position" id="sidebar_position" value="<?php echo $value; ?>"> 
		<div class="choose-sidebars">
			<label for="choose-sidebar"><?php _e('Choose Sidebar', TEXTDOMAIN); ?></label>
			<select id="choose-sidebar" name="choose_sidebar">
				<option value="default"><?php _e('Default', TEXTDOMAIN); ?></option>
				<?php if(!empty($admin_options['sidebar'])): ?>
					<?php foreach($admin_options['sidebar'] as $sidebarkey => $sidebar): ?>
						<option <?php selected($sidebar_name, $sidebarkey, true) ?> value="<?php echo $sidebarkey; ?>"><?php echo $sidebar['name']; ?></option>	
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div>
		<div style="clear:both;"></div>
		<?php
	}
	function sidebar_position_fn_save($post_id){

		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $post_id;


		if(!@wp_verify_nonce($_POST['sidebar_nonce'], 'sidebar_action'))
			return $post_id;


		$value 			= $_POST['sidebar_position'];
		$sidebar 		= $_POST['choose_sidebar'];
		$value_array 	= array('position'=>$value, 'sidebar'=>$sidebar);

		if(get_post_meta($post_id, '_sidebar', true)):
			update_post_meta($post_id, '_sidebar', $value_array);
		else:
			if(!empty($value)){
				add_post_meta($post_id, '_sidebar', $value_array, true);
			}else{
				delete_post_meta($post_id, '_sidebar');
			}
		endif;
	}


/////////////////////////////////////////////// LIVE SIDEBAR /////////////////////////////////////////////
add_action('wp_ajax_nopriv_remove_sidebar', 'remove_sidebars');
add_action('wp_ajax_remove_sidebar', 'remove_sidebars');
function remove_sidebars(){
	global $wp_query;
	$postpage = array();
	$id = sanitize_title($_POST['name']);

	$args  = array(
					'post_per_page' => -1,
					'meta_key'		=> '_sidebar',
					'post_type'		=> array('page', 'post'),
					'order'			=> 'ASC'
				  );
	$posts = $wp_query->query($args);

	$i=0;
	foreach($posts as $p){
			$value   = get_post_meta($p->ID, '_sidebar', true);
			$update  = array('position'=> $value['position'], 'sidebar'=> 'default'); 



			if($value){
				if(array_search($id, $value)){
					update_post_meta($p->ID, '_sidebar', $update);
				}
			}

			$i++;
	
	}
	echo 'ok';
 	exit();
}

add_action('wp_ajax_nopriv_do_live_sidebar', 'live_sidebar');
add_action('wp_ajax_do_live_sidebar', 'live_sidebar');
function live_sidebar(){
	global $admin_options;

	$name = $_REQUEST['name'];
	if(!empty($name)){
	$title = $name;
	$name  = sanitize_title($name);
	?>
		<div class="sidebars-module">
			<h3><?php echo $title; ?><span id="remove-sidebar">Remove</span><span id="sidebar-loading">Loading</span></h3>
			<input type="hidden" name="next_options[sidebar][<?php echo $name; ?>][name]" id="sidebar-name" value="<?php echo $title; ?>">
			<div class="option-item option-category">
				<span class="label"><?php _e('Home Sidebar', TEXTDOMAIN); ?></span>
				<input type="checkbox" id="checked-sidebar" value="1" />
				<input type="hidden" name="next_options[sidebar][<?php echo $name; ?>][home]" value="0">
			</div>
			<div class="option-item option-category">
				<span class="label"><?php _e('Single Page Sidebar', TEXTDOMAIN); ?></span>
				<input type="checkbox" id="checked-sidebar" value="1" />
				<input type="hidden" name="next_options[sidebar][<?php echo $name; ?>][singlepage]" value="0">
			</div>
			<div class="option-item option-category">
				<span class="label"><?php _e('Single Article Sidebar', TEXTDOMAIN); ?></span>
				<input type="checkbox" id="checked-sidebar" value="1" />
				<input type="hidden" name="next_options[sidebar][<?php echo $name; ?>][single]" value="0">
			</div>
			<div class="option-item option-category">
				<span class="label"><?php _e('Category Sidebar', TEXTDOMAIN); ?></span>
				<input type="checkbox" id="checked-category" value="1" />
				<input type="hidden" name="next_options[sidebar][<?php echo $name; ?>][category-status]" value="0">
				<?php  
					$categories =  get_categories(array('hide_empty'=>0));
				?>

			</div>
			<div class="option-item cats-block">
				<span class="label">Choose Categories : </span>
				<div class="clear"></div>
				<ul id="tabs_cats">
					<li class="all"><span>
						<?php _e('All', TEXTDOMAIN); ?></span>
						<input type="hidden" name="next_options[sidebar][<?php echo $name; ?>][category][0]" value='0'>
					</li>
					<?php if(!empty($categories)): ?>
					<?php foreach($categories as $category): ?>
						<li class="">
							<span><?php echo $category->name; ?></span>
							<input type="hidden" name="next_options[sidebar][<?php echo $name; ?>][category][<?php echo $category->term_id; ?>]" value='0'>
						</li>
					<?php endforeach; ?>
					<?php endif; ?>
				</ul>
			</div>
		</div>

	<?php
	}
	exit();
}

///////////////////////////////////////// CUSTOM FUNCTIONS /////////////////////////////////////////////
//Body class
add_filter('body_class','custom_css_body');
function custom_css_body($classes){
	global $admin_options, $sidebardisplay;

	if(is_home()){
		$classes[] = 'home';

		if($admin_options['default-sidebar'] == 'left'){
			$classes[] = 'left-sidebar';
		}

	}else{
		if(is_page() || is_paged() || is_single() || is_singular() || is_category()){

			if($sidebardisplay->sidebar_position() == 'right'){
				$classes[] = 'right-sidebar';
			}elseif($sidebardisplay->sidebar_position() == 'left'){
				$classes[] = 'left-sidebar';
			}elseif($sidebardisplay->sidebar_position() == 'none'){
				$classes[] = 'none-sidebar';
			}elseif($sidebardisplay->sidebar_position() == 'default'){
				//If is default
				if($admin_options['default-sidebar'] == 'right'){
					$classes[] = 'right-sidebar';
				}elseif($admin_options['default-sidebar'] == 'left'){
					$classes[] = 'left-sidebar';
				}elseif($admin_options['default-sidebar'] == 'none'){

					if(is_category()){
						$classes[] = 'right-sidebar';
					}else{
						$classes[] = 'none-sidebar';
					}

				}
			}
		}else{
			if($admin_options['default-sidebar'] == 'right'){
				$classes[] = 'right-sidebar';
			}elseif($admin_options['default-sidebar'] == 'left'){
				$classes[] = 'left-sidebar';
			}elseif($admin_options['default-sidebar'] == 'none'){
				$classes[] = 'none-sidebar';
			}
		}
	}

	return $classes;
}

//Sidebar Hidden
function custom_css_sidebar($classes){
	global $admin_options, $sidebardisplay;
	if(is_page() || is_paged() || is_single() || is_singular() || is_category()){

		if($sidebardisplay->sidebar_position() == 'none'){
			$classes .=" full-width";
		}elseif($sidebardisplay->sidebar_position() == 'default'){
			if($admin_options['default-sidebar'] == 'none'){
				if(!is_category()){
					$classes .=" full-width";
				}
			}
		}

	}else{

		if($admin_options['default-sidebar'] == 'none'){
			$classes .=" full-width";
		}	

	}

	return $classes;
}
add_filter('none-sidebar', 'custom_css_sidebar');
//////////////////////////////////////// CONTENT CLASSES /////////////////////////////////////////////////
function wrap_content_classes($classes = 'clearfix'){
	return apply_filters('none-sidebar', 'clearfix', $classes);
}
/////////////////////////////////////// SEARCH FORM //////////////////////////////////////////////////////
function next_search_form( $form ) {

    
	$form =		'<form role="search" action="' . home_url( '/' ) . '" method="get">
		         <div id="search-bar">
			       	<input type="text" id="s" name="s" placeholder="'.__("Search", TEXTDOMAIN).'" value="'.get_search_query().'">
			        <input type="submit" value="">
			    </div>
		    </form>';
    

    return $form;
}

add_filter( 'get_search_form', 'next_search_form' );
////////////////////////////////////////////// Get Comments number from POST ID //////////////////////////////

function get_commnet_by_id($zero = false, $one = false, $more = false, $post_id=0){
        $number = get_comments_number($post_id);

        if ( $number > 1 )
                $output = str_replace('%', number_format_i18n($number), ( false === $more ) ? __('% Comments', TEXTDOMAIN) : $more);
        elseif ( $number == 0 )
                $output = ( false === $zero ) ? __('No Comments', TEXTDOMAIN) : $zero;
        else // must be one
                $output = ( false === $one ) ? __('1 Comment', TEXTDOMAIN) : $one;

        echo apply_filters('comments_number', $output, $number);

}
//////////////////////////////////////////// GET ALL POST TYPE ////////////////////////////////////////////////
function get_all_posttype(){
		global $wp_post_types;

	    $types = $wp_post_types;
	    $postypes = array();

	    foreach( $types as $typekey => $type )
	    {
	    	array_push($postypes, $typekey);
	    }

	    return $postypes;

	}
////////////////////////////////////////// CONTACT PAGE ////////////////////////////////////////////////////////
	//Check e-mail validation
	function check_email($email){
		if(!@eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)){
			return false;
		} else {
			return true;
		}
	}

	function contact_form(){
		global $admin_options;

		$data = wp_parse_args($_POST['data'], array('next_name'=>'','next_mail'=>'','next_comment'=>''));

		if(!empty($data['next_name']) && !empty($data['next_mail']) && !empty($data['next_comment'])):
			$name 	 = $data['next_name'];
			$mail 	 = $data['next_mail'];
			$comment = $data['next_comment'];

		if($name == '') {
			echo json_encode(array('info' => 'error', 'msg' => "Please enter your name."));
			exit();
		} else if($mail == '' or check_email($mail) == false){
			echo json_encode(array('info' => 'error', 'msg' => "Please enter valid e-mail."));
			exit();
		} else if($comment == ''){
			echo json_encode(array('info' => 'error', 'msg' => "Please enter your message."));
			exit();
		} else {
			//Send Mail
			$to = $admin_options['contact-email'];
			$subject = 'From: ' . $name;
			$message = '
			<html>
			<head>
			  <title>Mail from '. $name .'</title>
			</head>
			<body>
			  <table style="width: 500px; font-family: arial; font-size: 14px;" border="1">
				<tr style="height: 32px;">
				  <th align="right" style="width:150px; padding-right:5px;">Name:</th>
				  <td align="left" style="padding-left:5px; line-height: 20px;">'. $name .'</td>
				</tr>
				<tr style="height: 32px;">
				  <th align="right" style="width:150px; padding-right:5px;">E-mail:</th>
				  <td align="left" style="padding-left:5px; line-height: 20px;">'. $mail .'</td>
				</tr>
				<tr style="height: 32px;">
				  <th align="right" style="width:150px; padding-right:5px;">Comment:</th>
				  <td align="left" style="padding-left:5px; line-height: 20px;">'. $comment .'</td>
				</tr>
			  </table>
			</body>
			</html>
			';

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= 'From: ' . $mail . "\r\n";

			if(@wp_mail($to, $subject, $message, $headers)){
				echo json_encode(array('info' => 'success', 'msg' => __SUCCESS_MESSAGE__));
			} else {
				echo json_encode(array('info' => 'error', 'msg' => __ERROR_MESSAGE__));
			}
		} 
		else:
			echo json_encode(array('info' => 'error', 'msg' => __MESSAGE_EMPTY_FILDS__));
		endif;

		die();
	}

	add_action('wp_ajax_send_email', 'contact_form');
	add_action('wp_ajax_nopriv_send_email', 'contact_form');

	//////////////////////////////////////////////////// GET VIDEO DETAILS /////////////////////////////////////////
	function get_video_details($video=null){
		if(!is_null($video) || !empty($video)){

			$embed = wp_oembed_get($video, array('width'=>600, 'height'=>400 ));
			if($embed){
				$thumbnail 	= getinfo_videostream($video);
			}
				if($embed):
					return json_encode(array('iframe'=>$embed, 'thumbnail'=> $thumbnail, 'error'=>false));
				else:
					return json_encode(array('error'=> true));
				endif;
		}
	}
	function getinfo_videostream($url){

		$youtube = 'http://www.youtube.com/oembed?format=json&url=';
		$vimeo   = 'http://vimeo.com/api/oembed.json?url=';
		$dailymotion = 'http://www.dailymotion.com/services/oembed?format=json&url=';

		if(strstr($url, 'youtube.com') || strstr($url, 'youtu.be')):
			$youtubeget  = $youtube.$url;
			$bodyYoutube = wp_remote_get($youtubeget);
			$thumbnail   = json_decode($bodyYoutube['body']);
			$thumbnail   = $thumbnail->thumbnail_url;

		elseif(strstr($url, 'dailymotion.com')):
			$dailymotionget  	= $dailymotion.$url;
			$bodyDailymotion  	= wp_remote_get($dailymotionget);
			$thumbnail   		= json_decode($bodyDailymotion['body']);
			$thumbnail 			= $thumbnail->thumbnail_url;
		elseif(strstr($url, 'vimeo.com')):
			$vimeoget  		= $vimeo.$url;
			$bodyVimeo  	= wp_remote_get($vimeoget);
			$thumbnail   	= json_decode($bodyVimeo['body']);
			$thumbnail  	= $thumbnail->thumbnail_url;
		endif;

		return $thumbnail;

	}
	////////////////////////////////////////////////////// RGB to HEX //////////////////////////////////////////////
	 function hexToRgb($hex){
       $hex         = str_replace("#", "", $hex);
       $color         = array();

       if(strlen($hex) == 3):
               $color['r'] = hexdec(str_repeat(substr($hex, 0,1), 2));
               $color['g'] = hexdec(str_repeat(substr($hex, 1,1), 2));
               $color['b'] = hexdec(str_repeat(substr($hex, 2,1), 2));
       else:
               $color['r'] = hexdec(substr($hex, 0,2));
               $color['g'] = hexdec(substr($hex, 2,2));
               $color['b'] = hexdec(substr($hex, 4,2));
       endif;

       return $color;
}
/////////////////////////////////////////////////////// NEXT PAGE STYLE ////////////////////////////////////////////
function next_wp_link_pages($args = '') {
$defaults = array(
	                'before' => '<p>' . __('Pages:'), 'after' => '</p>',
	                'link_before' => '', 'link_after' => '',
	                'next_or_number' => 'number', 'nextpagelink' => __('Next page'),
	                'previouspagelink' => __('Previous page'), 'pagelink' => '%',
	                'echo' => 1
	        );
	
	        $r = wp_parse_args( $args, $defaults );
	        $r = apply_filters( 'wp_link_pages_args', $r );
	        extract( $r, EXTR_SKIP );
	
	        global $page, $numpages, $multipage, $more, $pagenow;
	
	        $output = '';
	        if ( $multipage ) {
	                if ( 'number' == $next_or_number ) {
	                        $output .= $before;

	                        for ( $i = 1; $i < ($numpages+1); $i = $i + 1 ) {
	                                $j = str_replace('%',$i,$pagelink);
	                                if ( ($i != $page) || ((!$more) && ($page==1)) ) {
	                                		$output  .= '<li>';
	                                        $output .= _wp_link_page($i);
	                                }else{
	                                	$output  .= '<li class="active">';
	                                	$output .= "<a href='#'>";
	                                }

	                                $output .= $link_before . $j . $link_after;
	                                if ( ($i != $page) || ((!$more) && ($page==1)) )
	                                        $output .= '</a>';
									$output  .= '</li>';
	                        }

	                        $output .= $after;
	                } else {
	                        if ( $more ) {
	                                $output .= $before;
	                                $i = $page - 1;
	                                if ( $i && $more ) {
	                                        $output .= _wp_link_page($i);
	                                        $output .= $link_before. $previouspagelink . $link_after . '</a>';
	                                }
	                                $i = $page + 1;
	                                if ( $i <= $numpages && $more ) {
	                                        $output .= _wp_link_page($i);
	                                        $output .= $link_before. $nextpagelink . $link_after . '</a>';
	                                }
	                                $output .= $after;
	                        }
	                }
	        }
	
	        if ( $echo )
	                echo $output;
	
	        return $output;
}
?>