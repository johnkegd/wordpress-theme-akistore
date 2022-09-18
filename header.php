<?php

/**
 * header.php
 * @package WordPress
 * @subpackage Akistore
 * @since Akistore 1.0
 * 
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo("charset"); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<?php if (get_theme_mod('akistore_preloader')) { ?>
		<div class="site-loading">
			<div class="preloading">
				<svg class="circular" viewBox="25 25 50 50">
					<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
				</svg>
			</div>
		</div>
	<?php } ?>

	<?php get_template_part('includes/header/models/canvas-menu'); ?>

	<?php akistore_do_action('akistore_before_main_header'); ?>

	<?php if (!function_exists('elementor_theme_do_location') || !elementor_theme_do_location('header')) { ?>
		<?php
		/**
		 * Hook: akistore_main_header
		 *
		 * @hooked akistore_main_header_function - 10
		 */
		do_action('akistore_main_header');

		?>
	<?php } ?>

	<?php akistore_do_action('akistore_after_main_header'); ?>

	<main id="main" class="site-primary">
		<div class="site-content">