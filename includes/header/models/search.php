<?php $headersearch = get_theme_mod('akistore_header_search', '0'); ?>
<?php if ($headersearch == 1) { ?>
	<div class="header-form site-search">
		<?php if (get_theme_mod('akistore_ajax_search_form', 0) == 1 && class_exists('DGWT_WC_Ajax_Search')) { ?>
			<?php if (akistore_page_settings('page_header_type') == 'type2' || get_theme_mod('akistore_header_type') == 'type2' || akistore_page_settings('page_header_type') == 'type4' || get_theme_mod('akistore_header_type') == 'type4') { ?>
				<span><?php esc_html_e('What are you looking for ?', 'akistore'); ?></span>
			<?php } ?>
			<?php echo do_shortcode('[wcas-search-form]'); ?>
		<?php } else { ?>
			<?php if (akistore_page_settings('page_header_type') == 'type2' || get_theme_mod('akistore_header_type') == 'type2' || akistore_page_settings('page_header_type') == 'type4' || get_theme_mod('akistore_header_type') == 'type4') { ?>
				<span><?php esc_html_e('What are you looking for ?', 'akistore'); ?></span>
			<?php } ?>
			<?php echo akistore_header_product_search(); ?>
		<?php } ?>
	</div><!-- site-search -->
<?php } ?>