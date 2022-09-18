<?php

/*************************************************
## Akistore Metabox
 *************************************************/

if (!function_exists('rwmb_meta')) {
	function rwmb_meta($key, $args = '', $post_id = null)
	{
		return false;
	}
}

add_filter('rwmb_meta_boxes', 'akistore_register_page_meta_boxes');

function akistore_register_page_meta_boxes($meta_boxes)
{

	$prefix = 'kegd_';
	$meta_boxes = array();


	/* ----------------------------------------------------- */
	// Product Data
	/* ----------------------------------------------------- */

	$meta_boxes[] = array(
		'id' => 'kegd_product_settings',
		'title' => esc_html__('Product Data', 'akistore'),
		'pages' => array('product'),
		'context' => 'side',
		'priority' => 'high',

		// List of meta fields
		'fields' => array(

			array(
				'name'		=> esc_html__('Badge Type', 'akistore'),
				'id'		=> $prefix . 'product_badge_type',
				'type'		=> 'select',
				'options'	=> array(
					'type1'		=> esc_html__('Type 1', 'akistore'),
					'type2'		=> esc_html__('Type 2', 'akistore'),
				),
				'multiple'	=> false,
				'std'		=> array('no'),
				'sanitize_callback' => 'none'
			),

			array(
				'name'		=> esc_html__('Badge if on sale', 'akistore'),
				'id'		=> $prefix . 'product_badge',
				'clone'		=> false,
				'type'		=> 'text',
				'std'		=> ''
			),

			array(
				'name'		=> esc_html__('Model', 'akistore'),
				'id'		=> $prefix . 'product_model',
				'clone'		=> false,
				'type'		=> 'text',
				'std'		=> ''
			),

		)
	);

	/* ----------------------------------------------------- */
	// Product Specification Tab
	/* ----------------------------------------------------- */

	$meta_boxes[] = [
		'id'      => 'kegd_product_extra_tab',
		'title'   => esc_html__('Product Specifications', 'akistore'),
		'pages' => array('product'),
		'context' => 'normal',
		'priority' => 'low',
		'fields'  => [
			[
				'type'    => 'wysiwyg',
				'id'      => $prefix . 'product_specification',
			],
		],
	];

	/* ----------------------------------------------------- */
	// Blog Post Slides Metabox
	/* ----------------------------------------------------- */

	$meta_boxes[] = array(
		'id'		=> 'kegd-blogmeta-gallery',
		'title'		=> esc_html__('Blog Post Image Slides', 'akistore'),
		'pages'		=> array('post'),
		'context' => 'normal',

		'fields'	=> array(
			array(
				'name'	=> esc_html__('Blog Post Slider Images', 'akistore'),
				'desc'	=> esc_html__('Upload unlimited images for a slideshow - or only one to display a single image.', 'akistore'),
				'id'	=> $prefix . 'blogitemslides',
				'type'	=> 'image_advanced',
			)

		)
	);

	/* ----------------------------------------------------- */
	// Blog Audio Post Settings
	/* ----------------------------------------------------- */
	$meta_boxes[] = array(
		'id' => 'kegd-blogmeta-audio',
		'title' => esc_html('Audio Settings', 'akistore'),
		'pages' => array('post'),
		'context' => 'normal',

		// List of meta fields
		'fields' => array(
			array(
				'name'		=> esc_html('Audio Code', 'akistore'),
				'id'		=> $prefix . 'blogaudiourl',
				'desc'		=> esc_html__('Enter your Audio URL(Oembed) or Embed Code.', 'akistore'),
				'clone'		=> false,
				'type'		=> 'textarea',
				'std'		=> '',
				'sanitize_callback' => 'none'
			),
		)
	);



	/* ----------------------------------------------------- */
	// Blog Video Metabox
	/* ----------------------------------------------------- */
	$meta_boxes[] = array(
		'id'		=> 'kegd-blogmeta-video',
		'title'		=> esc_html__('Blog Video Settings', 'akistore'),
		'pages'		=> array('post'),
		'context' => 'normal',

		'fields'	=> array(
			array(
				'name'		=> esc_html__('Video Type', 'akistore'),
				'id'		=> $prefix . 'blog_video_type',
				'type'		=> 'select',
				'options'	=> array(
					'youtube'		=> esc_html__('Youtube', 'akistore'),
					'vimeo'			=> esc_html__('Vimeo', 'akistore'),
					'own'			=> esc_html__('Own Embed Code', 'akistore'),
				),
				'multiple'	=> false,
				'std'		=> array('no'),
				'sanitize_callback' => 'none'
			),
			array(
				'name'	=> akistore_sanitize_data(__('Embed Code<br />(Audio Embed Code is possible, too)', 'akistore')),
				'id'	=> $prefix . 'blog_video_embed',
				'desc'	=> akistore_sanitize_data(__('Just paste the ID of the video (E.g. http://www.youtube.com/watch?v=<strong>GUEZCxBcM78</strong>) you want to show, or insert own Embed Code. <br />This will show the Video <strong>INSTEAD</strong> of the Image Slider.<br /><strong>Of course you can also insert your Audio Embedd Code!</strong>', 'akistore')),
				'type' 	=> 'textarea',
				'std' 	=> "",
				'cols' 	=> "40",
				'rows' 	=> "8"
			)
		)
	);

	return $meta_boxes;
}
