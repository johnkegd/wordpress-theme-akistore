<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Akistore
 * @since 1.0.0
 */

remove_action('akistore_main_header', 'akistore_main_header_function', 10);
remove_action('akistore_main_footer', 'akistore_main_footer_function', 10);

remove_action('akistore_before_main_shop', 'akistore_get_elementor_template', 10);
remove_action('akistore_after_main_shop', 'akistore_get_elementor_template', 10);
remove_action('akistore_before_main_footer', 'akistore_get_elementor_template', 10);
remove_action('akistore_after_main_footer', 'akistore_get_elementor_template', 10);
remove_action('akistore_before_main_header', 'akistore_get_elementor_template', 10);
remove_action('akistore_after_main_header', 'akistore_get_elementor_template', 10);

get_header();

while (have_posts()) : the_post();
	the_content();
endwhile;

get_footer();
