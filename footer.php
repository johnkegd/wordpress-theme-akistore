<?php

/**
 * footer.php
 * @package WordPress
 * @subpackage Akistore
 * @since Akistore 1.0
 * 
 */
?>
</div><!-- site-content -->
</main><!-- site-primary -->

<?php akistore_do_action('akistore_before_main_footer'); ?>

<?php if (!function_exists('elementor_theme_do_location') || !elementor_theme_do_location('footer')) { ?>
	<?php
	/**
	 * Hook: akistore_main_footer
	 *
	 * @hooked akistore_main_footer_function - 10
	 */
	do_action('akistore_main_footer');

	?>
<?php } ?>

<?php akistore_do_action('akistore_after_main_footer'); ?>

<div class="site-overlay"></div>

<?php wp_footer(); ?>
</body>

</html>