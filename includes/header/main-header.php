<?php

/*************************************************
## Main Header Function
 *************************************************/

add_action('akistore_main_header', 'akistore_main_header_function', 10);

if (!function_exists('akistore_main_header_function')) {
	function akistore_main_header_function()
	{

		if (akistore_page_settings('page_header_type') == 'type4') {
			get_template_part('includes/header/header-type4');
		} elseif (akistore_page_settings('page_header_type') == 'type3') {
			get_template_part('includes/header/header-type3');
		} elseif (akistore_page_settings('page_header_type') == 'type2') {
			get_template_part('includes/header/header-type2');
		} elseif (akistore_page_settings('page_header_type') == 'type1') {
			get_template_part('includes/header/header-type1');
		} elseif (get_theme_mod('akistore_header_type') == 'type4') {
			get_template_part('includes/header/header-type4');
		} elseif (get_theme_mod('akistore_header_type') == 'type3') {
			get_template_part('includes/header/header-type3');
		} elseif (get_theme_mod('akistore_header_type') == 'type1') {
			get_template_part('includes/header/header-type1');
		} else {
			get_template_part('includes/header/header-type2');
		}
	}
}
