<?php

/**
 * functions.php
 * @package WordPress
 * @subpackage Akistore
 * @since Akistore 1.0.0
 * 
 */

/*************************************************
## Admin style and scripts  
 *************************************************/
function akistore_admin_styles()
{
	wp_enqueue_script('akistore-init', 	    get_template_directory_uri() . '/assets/js/init.js', array('jquery', 'media-upload', 'thickbox'));
	wp_enqueue_script('akistore-menu-extra',  get_template_directory_uri() . '/assets/js/admin/menu-extra.js', array('jquery'), '1.0', true);
	wp_enqueue_script('akistore-register',    get_template_directory_uri() . '/assets/js/admin/register.js', array('jquery'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'akistore_admin_styles');

/*************************************************
## Styles and Scripts
 *************************************************/
define('akistore_INDEX_CSS', 	  get_template_directory_uri()  . '/assets/css');
define('akistore_INDEX_JS', 	  get_template_directory_uri()  . '/assets/js');

function akistore_scripts()
{

	if (is_admin_bar_showing()) {
		wp_enqueue_style('akistore-kegdtheme', akistore_INDEX_CSS . '/admin/kegdtheme.css', false, '1.0');
	}

	if (is_singular()) wp_enqueue_script('comment-reply');

	wp_enqueue_style('akistore-typekit', 		akistore_INDEX_CSS . '/typekit.css', false, '1.0');
	wp_enqueue_style('bootstrap', 				akistore_INDEX_CSS . '/bootstrap.min.css', false, '1.0');
	wp_enqueue_style('select2', 				akistore_INDEX_CSS . '/select2.min.css', false, '1.0');
	wp_register_style('owl-carousel', 			akistore_INDEX_CSS . '/vendor/owl.carousel.min.css', false, '1.0');
	wp_register_style('owl-theme-default', 	akistore_INDEX_CSS . '/vendor/owl.theme.default.min.css', false, '1.0');
	wp_enqueue_style('akistore-base', 			akistore_INDEX_CSS . '/base.css', false, '1.0');
	wp_style_add_data('akistore-base', 'rtl', 'replace');
	wp_enqueue_style('akistore-style',         	get_stylesheet_uri());
	wp_style_add_data('akistore-style', 'rtl', 'replace');

	$mapkey = get_theme_mod('akistore_mapapi');

	wp_enqueue_script('imagesloaded');
	wp_enqueue_script('bootstrap-bundle',    	     akistore_INDEX_JS . '/bootstrap.bundle.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script('select2-full',    	 	     akistore_INDEX_JS . '/select2.full.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script('gsap',    	    		     akistore_INDEX_JS . '/vendor/gsap.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script('hover-slider',    	         akistore_INDEX_JS . '/vendor/hover-slider.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script('jquery-magnific-popup',      akistore_INDEX_JS . '/vendor/jquery.magnific-popup.min.js', array('jquery'), '1.0', true);
	if (is_rtl()) {
	} else {
	}
	wp_enqueue_script('perfect-scrolllbar',         akistore_INDEX_JS . '/vendor/perfect-scrollbar.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script('swiper-bundle',    	         akistore_INDEX_JS . '/vendor/swiper-bundle.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script('theia-sticky-sidebar',       akistore_INDEX_JS . '/vendor/theia-sticky-sidebar.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script('akistore-productsorting',      akistore_INDEX_JS . '/custom/productSorting.js', array('jquery'), '1.0', true);
	wp_enqueue_script('akistore-producthover',        akistore_INDEX_JS . '/custom/productHover.js', array('jquery'), '1.0', true);
	wp_enqueue_script('akistore-producthoverSlider',  akistore_INDEX_JS . '/custom/productHoverSlider.js', array('jquery'), '1.0', true);
	wp_enqueue_script('akistore-sidebarfilter',       akistore_INDEX_JS . '/custom/sidebarfilter.js', array('jquery'), '1.0', true);
	wp_enqueue_script('akistore-sitescroll',          akistore_INDEX_JS . '/custom/sitescroll.js', array('jquery'), '1.0', true);
	wp_register_script('akistore-counter',   		 akistore_INDEX_JS . '/custom/counter.js', array('jquery'), '1.0', true);
	wp_register_script('owl-carousel',   		     akistore_INDEX_JS . '/vendor/owl.carousel.min.js', array('jquery'), '1.0', true);
	wp_register_script('akistore_flex_thumbs',        akistore_INDEX_JS . '/custom/flex_thumbs.js', array('jquery'), '1.0', true);
	wp_register_script('akistore_mini_cart_slider',   akistore_INDEX_JS . '/custom/mini_cart_slider.js', array('jquery'), '1.0', true);
	wp_register_script('akistore-googlemap',          '//maps.googleapis.com/maps/api/js?key=' . $mapkey . '', array('jquery'), '1.0', true);
	wp_enqueue_script('akistore-bundle',     	     akistore_INDEX_JS . '/bundle.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'akistore_scripts');

/*************************************************
## Theme Setup
 *************************************************/

if (!isset($content_width)) $content_width = 960;

function akistore_theme_setup()
{

	add_theme_support('title-tag');
	add_theme_support('automatic-feed-links');
	add_theme_support('post-thumbnails');
	add_theme_support('custom-background');
	add_theme_support('post-formats', array('gallery', 'audio', 'video'));
	add_theme_support('wc-product-gallery-lightbox');
	add_theme_support('wc-product-gallery-slider');
	add_theme_support('woocommerce', array('gallery_thumbnail_image_width' => 99, 'thumbnail_image_width' => 90,));
	load_theme_textdomain('akistore', get_template_directory() . '/languages');
	remove_theme_support('widgets-block-editor');
}
add_action('after_setup_theme', 'akistore_theme_setup');


/*************************************************
## Include the TGM_Plugin_Activation class.
 *************************************************/

require_once get_template_directory() . '/includes/class-tgm-plugin-activation.php';

add_action('tgmpa_register', 'akistore_register_required_plugins');

function akistore_register_required_plugins()
{
	$url = 'https://github.com/johnkegd/machic-core/archive/refs/tags/';
	$mainurl = 'https://envato.github.io/wp-envato-market/dist/';


	$plugins = array(

		array(
			'name'                  => esc_html__('Meta Box', 'akistore'),
			'slug'                  => 'meta-box',
		),

		array(
			'name'                  => esc_html__('Contact Form 7', 'akistore'),
			'slug'                  => 'contact-form-7',
		),

		array(
			'name'                  => esc_html__('WooCommerce Wishlist', 'akistore'),
			'slug'                  => 'ti-woocommerce-wishlist',
		),

		array(
			'name'                  => esc_html__('WooCommerce Compare', 'akistore'),
			'slug'                  => 'woo-smart-compare',
		),

		array(
			'name'                  => esc_html__('Kirki', 'akistore'),
			'slug'                  => 'kirki',
		),

		array(
			'name'                  => esc_html__('MailChimp Subscribe', 'akistore'),
			'slug'                  => 'mailchimp-for-wp',
		),

		array(
			'name'                  => esc_html__('Elementor', 'akistore'),
			'slug'                  => 'elementor',
			'required'              => true,
		),

		array(
			'name'                  => esc_html__('WooCommerce', 'akistore'),
			'slug'                  => 'woocommerce',
			'required'              => true,
		),

		array(
			'name'                  => esc_html__('Variation Swatches', 'akistore'),
			'slug'                  => 'woo-variation-swatches',
		),

		array(
			'name'                  => esc_html__('WP Ajax Search', 'akistore'),
			'slug'                  => 'ajax-search-for-woocommerce',
		),

		array(
			'name'                  => esc_html__('akistore Core', 'akistore'),
			'slug'                  => 'akistore-core',
			'source'                => $url . '1.0.0.0.zip',
			'required'              => true,
			'version'               => '1.0.8',
			'force_activation'      => false,
			'force_deactivation'    => false,
			'external_url'          => '',
		),

		array(
			'name'                  => esc_html__('Envato Market', 'akistore'),
			'slug'                  => 'envato-market',
			'source'                => $mainurl . 'envato-market.zip',
			'required'              => true,
			'version'               => '2.0.7',
			'force_activation'      => false,
			'force_deactivation'    => false,
			'external_url'          => '',
		),


	);

	$config = array(
		'id'           => 'akistore',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa($plugins, $config);
}

/*************************************************
## akistore Register Menu 
 *************************************************/

function akistore_register_menus()
{
	register_nav_menus(array('main-menu' 	   => esc_html__('Primary Navigation Menu', 'akistore')));

	$topheader = get_theme_mod('akistore_top_header', '0');
	$sidebarmenu = get_theme_mod('akistore_header_sidebar', '0');

	if ($sidebarmenu == '1') {
		register_nav_menus(array('sidebar-menu'     => esc_html__('Sidebar Menu', 'akistore')));
	}

	if ($topheader == '1') {
		register_nav_menus(array('canvas-bottom' 	   => esc_html__('Canvas Bottom', 'akistore')));
		register_nav_menus(array('top-right-menu'    => esc_html__('Top Right Menu', 'akistore')));
		register_nav_menus(array('top-left-menu'     => esc_html__('Top Left Menu', 'akistore')));
	}
}
add_action('init', 'akistore_register_menus');

/*************************************************
## Akistore Main Menu
 *************************************************/
class Akistore_main_walker extends Walker_Nav_Menu
{
	function start_lvl(&$output, $depth = 0, $args = array())
	{
		// depth dependent classes
		$indent = ($depth > 0  ? str_repeat("\t", $depth) : ''); // code indent
		$display_depth = ($depth + 1); // because it counts the first submenu as 0
		$classes = array(
			'',
			($display_depth % 2  ? '' : ''),
			($display_depth >= 2 ? '' : ''),

		);
		$class_names = implode(' ', $classes);

		// build html
		$output .= "\n" . $indent . '<ul class="sub-menu">' . "\n";
	}

	function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
	{
		$id_field = $this->db_fields['id'];
		if (is_object($args[0])) {
			$args[0]->has_children = !empty($children_elements[$element->$id_field]);
		}
		return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
	}
	function start_el(&$output, $object, $depth = 0, $args = array(), $current_object_id = 0)
	{

		global $wp_query;

		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		$class_names = $value = '';

		$classes = empty($object->classes) ? array() : (array) $object->classes;
		$icon_class = $classes[0];
		$classes = array_slice($classes, 1);

		$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $object));


		if ($icon_class == 'mega-menu') {
			$class_names = 'class="' . esc_attr($icon_class) . ' ' . esc_attr($class_names) . '"';
		} else {
			$class_names = 'class="' . esc_attr($class_names) . '"';
		}

		$output .= $indent . '<li ' . $value . $class_names . '>';

		$attributes = !empty($object->url) ? ' href="'   . esc_attr($object->url) . '"' : '';


		$object_output = $args->before;

		$object_output .= '<a' . $attributes . '  >';
		if ($icon_class && $icon_class != 'mega-menu') {
			$object_output .= '<i class="' . esc_attr($icon_class) . '"></i> ';
		}
		$object_output .= $args->link_before .  apply_filters('the_title', $object->title, $object->ID) . '';
		$object_output .= $args->link_after;
		$object_output .= '</a>';


		$object_output .= $args->after;

		$output .= apply_filters('walker_nav_menu_start_el', $object_output, $object, $depth, $args);
	}
}

/*************************************************
## akistore Sidebar Menu
 *************************************************/
class Akistore_sidebar_walker extends Walker_Nav_Menu
{

	private $curItem;

	function start_lvl(&$output, $depth = 0, $args = array())
	{
		// depth dependent classes
		$indent = ($depth > 0  ? str_repeat("\t", $depth) : ''); // code indent
		$display_depth = ($depth + 1); // because it counts the first submenu as 0
		$classes = array(
			'',
			($display_depth % 2  ? '' : ''),
			($display_depth >= 2 ? '' : ''),

		);
		$class_names = implode(' ', $classes);
		// build html
		$menu_item_menuimage = get_post_meta($this->curItem->ID, '_menu_item_menuimage', true);
		$image_attributes = wp_get_attachment_image_src($menu_item_menuimage, 'full');

		if ($image_attributes != '') {
			$output .= "\n" . $indent . '<ul class="sub-menu" style="background-image: url(' . esc_url($image_attributes[0]) . ');">' . "\n";
		} else {
			$output .= "\n" . $indent . '<ul class="sub-menu">' . "\n";
		}
	}

	function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
	{
		$id_field = $this->db_fields['id'];
		if (is_object($args[0])) {
			$args[0]->has_children = !empty($children_elements[$element->$id_field]);
		}
		return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
	}
	function start_el(&$output, $object, $depth = 0, $args = array(), $current_object_id = 0)
	{
		$this->curItem = $object;
		global $wp_query;

		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		$class_names = $value = '';

		$classes = empty($object->classes) ? array() : (array) $object->classes;
		$myclasses = empty($object->classes) ? array() : (array) $object->classes;
		$icon_class = $classes[0];
		$classes = array_slice($classes, 1);


		$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $object));

		if ($args->has_children) {
			$menu_item_megamenu_columns = get_post_meta($object->ID, '_menu_item_megamenu_columns', true);
			$menu_item_menuimage = get_post_meta($this->curItem->ID, '_menu_item_menuimage', true);
			if ($menu_item_menuimage) {
				$class_names = 'class="' . esc_attr($class_names) . ' has-image column-' . esc_attr($menu_item_megamenu_columns) . '"';
			} else {
				$class_names = 'class="' . esc_attr($class_names) . ' column-' . esc_attr($menu_item_megamenu_columns) . '"';
			}
		} else {
			$class_names = 'class="' . esc_attr($class_names) . '"';
		}

		$output .= $indent . '<li ' . $value . $class_names . '>';

		$datahover = str_replace(' ', '', $object->title);


		$attributes = !empty($object->url) ? ' href="'   . esc_attr($object->url) . '"' : '';


		$object_output = $args->before;

		$object_output .= '<a' . $attributes . '  >';
		if ($icon_class) {
			$object_output .= '<i class="' . esc_attr($icon_class) . '"></i> ';
		}
		$object_output .= $args->link_before .  apply_filters('the_title', $object->title, $object->ID) . '';
		$object_output .= $args->link_after;

		if ($object->description) {
			$object_output .= '<span class="label danger">' . $object->description . '</span>';
		}
		$object_output .= '</a>';


		$object_output .= $args->after;

		$output .= apply_filters('walker_nav_menu_start_el', $object_output, $object, $depth, $args);
	}
}

/*************************************************
## Excerpt More
 *************************************************/

function akistore_excerpt_more($more)
{
	global $post;
}
add_filter('excerpt_more', 'akistore_excerpt_more');

/*************************************************
## Word Limiter
 *************************************************/
function akistore_limit_words($string, $limit)
{
	$words = explode(' ', $string);
	return implode(' ', array_slice($words, 0, $limit));
}

/*************************************************
## Widgets
 *************************************************/

function akistore_widgets_init()
{
	register_sidebar(array(
		'name' => esc_html__('Blog Sidebar', 'akistore'),
		'id' => 'blog-sidebar',
		'description'   => esc_html__('These are widgets for the Blog page.', 'akistore'),
		'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	));

	register_sidebar(array(
		'name' => esc_html__('Shop Sidebar', 'akistore'),
		'id' => 'shop-sidebar',
		'description'   => esc_html__('These are widgets for the Shop.', 'akistore'),
		'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	));

	register_sidebar(array(
		'name' => esc_html__('Footer First Column', 'akistore'),
		'id' => 'footer-1',
		'description'   => esc_html__('These are widgets for the Footer.', 'akistore'),
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	));

	register_sidebar(array(
		'name' => esc_html__('Footer Second Column', 'akistore'),
		'id' => 'footer-2',
		'description'   => esc_html__('These are widgets for the Footer.', 'akistore'),
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	));

	register_sidebar(array(
		'name' => esc_html__('Footer Third Column', 'akistore'),
		'id' => 'footer-3',
		'description'   => esc_html__('These are widgets for the Footer.', 'akistore'),
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	));

	register_sidebar(array(
		'name' => esc_html__('Footer Fourth Column', 'akistore'),
		'id' => 'footer-4',
		'description'   => esc_html__('These are widgets for the Footer.', 'akistore'),
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	));

	register_sidebar(array(
		'name' => esc_html__('Footer Fifth Column', 'akistore'),
		'id' => 'footer-5',
		'description'   => esc_html__('These are widgets for the Footer.', 'akistore'),
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	));
}
add_action('widgets_init', 'akistore_widgets_init');

/*************************************************
## akistore Comment
 *************************************************/

if (!function_exists('akistore_comment')) :
	function akistore_comment($comment, $args, $depth)
	{
		$GLOBALS['comment'] = $comment;
		switch ($comment->comment_type):
			case 'pingback':
			case 'trackback':
?>

				<article class="post pingback">
					<p><?php esc_html_e('Pingback:', 'akistore'); ?> <?php comment_author_link(); ?><?php edit_comment_link(esc_html__('(Edit)', 'akistore'), ' '); ?></p>
				<?php
				break;
			default:
				?>

					<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
						<div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
							<div class="comment-avatar">
								<div class="comment-author vcard">
									<img src="<?php echo get_avatar_url($comment, 90); ?>" alt="<?php comment_author(); ?>" class="avatar">
								</div>
							</div>
							<div class="comment-content">
								<div class="comment-meta">
									<b class="fn"><a class="url"><?php comment_author(); ?></a></b>
									<div class="comment-metadata">
										<time><?php comment_date(); ?></time>
									</div>
								</div>
								<?php comment_text(); ?>
								<?php if ($comment->comment_approved == '0') : ?>
									<em><?php esc_html_e('Your comment is awaiting moderation.', 'akistore'); ?></em>
								<?php endif; ?>
							</div>
							<div class="reply">
								<?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
							</div>
						</div>

						</div>
					</li>


	<?php
				break;
		endswitch;
	}
endif;

/*************************************************
## Akistore Widget Count Filter
 *************************************************/

function akistore_cat_count_span($links)
{
	$links = str_replace('</a> (', '</a> <span class="catcount">(', $links);
	$links = str_replace(')', ')</span>', $links);
	return akistore_sanitize_data($links);
}
add_filter('wp_list_categories', 'akistore_cat_count_span');

function akistore_archive_count_span($links)
{
	$links = str_replace('</a>&nbsp;(', '</a><span class="catcount">(', $links);
	$links = str_replace(')', ')</span>', $links);
	return akistore_sanitize_data($links);
}
add_filter('get_archives_link', 'akistore_archive_count_span');


/*************************************************
## Pingback url auto-discovery header for single posts, pages, or attachments
 *************************************************/
function akistore_pingback_header()
{
	if (is_singular() && pings_open()) {
		echo '<link rel="pingback" href="', esc_url(get_bloginfo('pingback_url')), '">';
	}
}
add_action('wp_head', 'akistore_pingback_header');

/************************************************************
## DATA CONTROL FROM PAGE METABOX OR ELEMENTOR PAGE SETTINGS
 *************************************************************/
function akistore_page_settings($opt_id)
{

	if (class_exists('\Elementor\Core\Settings\Manager')) {
		// Get the current post id
		$post_id = get_the_ID();

		// Get the page settings manager
		$page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers('page');

		// Get the settings model for current post
		$page_settings_model = $page_settings_manager->get_model($post_id);

		// Retrieve the color we added before
		$output = $page_settings_model->get_settings('akistore_elementor_' . $opt_id);

		return $output;
	}
}

/************************************************************
## Elementor Register Location
 *************************************************************/
function akistore_register_elementor_locations($elementor_theme_manager)
{

	$elementor_theme_manager->register_location('header');
	$elementor_theme_manager->register_location('footer');
	$elementor_theme_manager->register_location('single');
}
add_action('elementor/theme/register_locations', 'akistore_register_elementor_locations');

/************************************************************
## Elementor Get Templates
 *************************************************************/
function akistore_get_elementor_template($template_id)
{
	if ($template_id) {

		$frontend = new \Elementor\Frontend;
		printf('<div class="akistore-elementor-template template-' . esc_attr($template_id) . '">%1$s</div>', $frontend->get_builder_content_for_display($template_id, true));

		if (class_exists('\Elementor\Plugin')) {
			$elementor = \Elementor\Plugin::instance();
			$elementor->frontend->enqueue_styles();
			$elementor->frontend->enqueue_scripts();
		}

		if (class_exists('\ElementorPro\Plugin')) {
			$elementor_pro = \ElementorPro\Plugin::instance();
			$elementor_pro->enqueue_styles();
		}
	}
}
add_action('akistore_before_main_shop', 'akistore_get_elementor_template', 10);
add_action('akistore_after_main_shop', 'akistore_get_elementor_template', 10);
add_action('akistore_before_main_footer', 'akistore_get_elementor_template', 10);
add_action('akistore_after_main_footer', 'akistore_get_elementor_template', 10);
add_action('akistore_before_main_header', 'akistore_get_elementor_template', 10);
add_action('akistore_after_main_header', 'akistore_get_elementor_template', 10);

/************************************************************
## Do Action for Templates and Product Categories
 *************************************************************/
function akistore_do_action($hook)
{

	if (!class_exists('woocommerce')) {
		return;
	}

	$categorytemplate = get_theme_mod('akistore_elementor_template_each_shop_category');
	if (is_product_category()) {
		if ($categorytemplate && array_search(get_queried_object()->term_id, array_column($categorytemplate, 'category_id')) !== false) {
			foreach ($categorytemplate as $c) {
				if ($c['category_id'] == get_queried_object()->term_id) {
					do_action($hook, $c[$hook . '_elementor_template_category']);
				}
			}
		} else {
			do_action($hook, get_theme_mod($hook . '_elementor_template'));
		}
	} else {
		do_action($hook, get_theme_mod($hook . '_elementor_template'));
	}
}

/*************************************************
## akistore Get Image
 *************************************************/
function akistore_get_image($image)
{
	$img = !wp_attachment_is_image($image) ? $image : wp_get_attachment_url($image);

	return esc_html($img);
}

/*************************************************
## akistore Get options
 *************************************************/
function akistore_get_option()
{
	$getopt  = isset($_GET['opt']) ? $_GET['opt'] : '';

	return esc_html($getopt);
}

/*************************************************
## akistore Theme options
 *************************************************/

require_once get_template_directory() . '/includes/metaboxes.php';
require_once get_template_directory() . '/includes/woocommerce.php';
require_once get_template_directory() . '/includes/woocommerce-filter.php';
require_once get_template_directory() . '/includes/sanitize.php';
require_once get_template_directory() . '/includes/extra-menu-field.php';
require_once get_template_directory() . '/includes/merlin/theme-register.php';
require_once get_template_directory() . '/includes/merlin/setup-wizard.php';
require_once get_template_directory() . '/includes/pjax/filter-functions.php';
require_once get_template_directory() . '/includes/header/main-header.php';
require_once get_template_directory() . '/includes/footer/main-footer.php';
