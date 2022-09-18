<?php $wishlistheader = get_theme_mod('akistore_header_wishlist', 0); ?>
<?php if ($wishlistheader == 1) { ?>

	<?php if (function_exists('tinv_url_wishlist_default')) { ?>
		<div class="header-addons wishlist-button">
			<div class="header-addons-icon">
				<a href="<?php echo tinv_url_wishlist_default(); ?>"><i class="kegdth-icon-heart"></i></a>
				<div class="button-count"><?php echo do_shortcode('[ti_wishlist_products_counter]'); ?></div>
			</div><!-- header-addons-icon -->
		</div><!-- header-addons -->
	<?php } ?>

<?php } ?>