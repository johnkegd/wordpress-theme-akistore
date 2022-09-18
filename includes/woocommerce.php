<?php

/*************************************************
## Woocommerce 
 *************************************************/

function akistore_product_image()
{
	if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) {
		$att = get_post_thumbnail_id();
		$image_src = wp_get_attachment_image_src($att, 'full');
		$image_src = $image_src[0];

		$size = get_theme_mod('akistore_product_image_size', array('width' => '', 'height' => ''));

		if ($size['width'] && $size['height']) {
			$image = akistore_resize($image_src, $size['width'], $size['height'], true, true, true);
		} else {
			$image = $image_src;
		}

		return esc_url($image);
	} else {
		return wc_placeholder_img_src('');
	}
}

function akistore_product_second_image()
{
	global $product;

	$product_image_ids = $product->get_gallery_image_ids();

	if ($product_image_ids && get_theme_mod('akistore_product_box_gallery') == 1) {
		echo '<div class="product-card">';
		echo '<img src="' . akistore_product_image() . '"';
		echo ' data-hover-slides="';

		$total_count = count($product_image_ids);
		$count = 1;
		foreach ($product_image_ids as $product_image_id) {
			if ($count == $total_count) {
				$size = get_theme_mod('akistore_product_image_size', array('width' => '', 'height' => ''));

				if ($size['width'] && $size['height']) {
					$image = akistore_resize(wp_get_attachment_url($product_image_id), $size['width'], $size['height'], true, true, true);
				} else {
					$image = wp_get_attachment_url($product_image_id); //comma removed for the latest item
				}

				echo akistore_sanitize_data($image);
			} else {
				$size = get_theme_mod('akistore_product_image_size', array('width' => '', 'height' => ''));

				if ($size['width'] && $size['height']) {
					$image = '' . akistore_resize(wp_get_attachment_url($product_image_id), $size['width'], $size['height'], true, true, true) . ',';
				} else {
					$image = '' . wp_get_attachment_url($product_image_id) . ','; //comma added for each item
				}

				echo akistore_sanitize_data($image);
			}
			$count++;
		}

		echo '"';
		echo ' data-options=\'{"touch": "end", "preloadImages": true }\' alt="' . the_title_attribute('echo=0') . '">';
		echo '</div>';
	} else {
		echo '<img src="' . akistore_product_image() . '" alt="' . the_title_attribute('echo=0') . '">';
	}
}

function akistore_sale_percentage()
{
	global $product;

	$output = '';

	$badge = get_post_meta(get_the_ID(), 'kegd_product_badge', true);
	$badge_type = get_post_meta(get_the_ID(), 'kegd_product_badge_type', true);
	$badgestyle = $badge_type == 'type2' ? 'top' : 'super';

	if ($product->is_on_sale() && $product->is_type('variable')) {
		$percentage = ceil(100 - ($product->get_variation_sale_price() / $product->get_variation_regular_price('min')) * 100);
		$output .= '<div class="product-badges"><span class="badge style-1 onsale">' . $percentage . '%</span></div>';
	} elseif ($product->is_on_sale() && $product->get_regular_price()  && !$product->is_type('grouped')) {
		$percentage = ceil(100 - ($product->get_sale_price() / $product->get_regular_price()) * 100);
		$output .= '<div class="product-badges">';
		if ($badge) {
			$output .= '<span class="badge ' . esc_attr($badgestyle) . '">' . esc_html($badge) . '</span>';
		} else {
			$output .= '<span class="badge onsale">' . $percentage . '%</span>';
		}
		$output .= '</div>';
	}

	return $output;
}

function akistore_featured_icon()
{
	global $product;

	$output = '';

	if ($product->is_featured()) {
		$output .= '<a href="' . esc_url(add_query_arg('featured', 'yes', wc_get_page_permalink('shop'))) . '" class="most-comments">';
		$output .= '<img src="' . get_template_directory_uri() . '/assets/images/featured.png" alt="featured">';
		$output .= '</a>';
	}

	return $output;
}

function akistore_vendor_name()
{
	if (function_exists('get_wcmp_vendor_settings')) {
		global $post;
		$vendor = get_wcmp_product_vendors($post->ID);
		if (isset($vendor->page_title)) {
			$store_name = $vendor->page_title;
			return '<a href="' . esc_url($vendor->permalink) . '">' . esc_html($store_name) . '</a>';
		}
	} elseif (class_exists('WeDevs_Dokan')) {
		// Get the author ID (the vendor ID)
		$vendor_id = get_post_field('post_author', get_the_id());

		$store_info  = dokan_get_store_info($vendor_id); // Get the store data
		$store_name  = $store_info['store_name'];          // Get the store name
		$store_url   = dokan_get_store_url($vendor_id);  // Get the store URL

		if (isset($store_name)) {
			return '<a href="' . esc_url($store_url) . '">' . esc_html($store_name) . '</a>';
		}
	}
}

if (class_exists('woocommerce')) {
	add_theme_support('woocommerce');
	add_image_size('akistore-woo-product', 450, 450, true);

	// Remove woocommerce defauly styles
	add_filter('woocommerce_enqueue_styles', '__return_false');

	// hide default shop title anasayfadaki title gizlemek için
	add_filter('woocommerce_show_page_title', 'akistore_override_page_title');
	function akistore_override_page_title()
	{
		return false;
	}

	remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20); /*remove result count above products*/
	remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
	remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5); //remove rating
	remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10); //remove rating
	remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
	remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
	remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
	remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);

	add_action('woocommerce_before_shop_loop_item', 'akistore_shop_thumbnail', 10);
	remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
	remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
	remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20); /*remove breadcrumb*/


	remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
	remove_action('woocommerce_after_single_product', 'woocommerce_output_related_products', 10);
	add_action('woocommerce_after_single_product_summary', 'akistore_related_products', 20);
	function akistore_related_products()
	{
		$related_column = get_theme_mod('akistore_shop_related_post_column') ? get_theme_mod('akistore_shop_related_post_column') : '4';
		woocommerce_related_products(array('posts_per_page' => $related_column, 'columns' => $related_column));
	}

	remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
	add_action('woocommerce_after_cart', 'woocommerce_cross_sell_display', 20);
	add_filter('woocommerce_cross_sells_columns', 'akistore_change_cross_sells_columns');
	function akistore_change_cross_sells_columns($columns)
	{
		return 4;
	}

	/*************************************************
## Single Gallery Columns
	 *************************************************/

	add_filter('woocommerce_product_thumbnails_columns', 'akistore_product_thumbnails_columns', 10, 1);
	function akistore_product_thumbnails_columns($columns)
	{
		return get_theme_mod('akistore_shop_single_gallery_columns', 7);
	}

	/*************************************************
## Wishlist Shortcode
	 *************************************************/
	function akistore_wishlist_shortcode()
	{
		$output = '';

		$wishlist = get_theme_mod('akistore_wishlist_button', '0');

		if ($wishlist == '1' && function_exists('run_tinv_wishlist')) {
			$output .= do_shortcode('[ti_wishlists_addtowishlist]');
		}

		return $output;
	}

	/*************************************************
## Compare Shortcode
	 *************************************************/
	function akistore_compare_shortcode()
	{
		$output = '';

		$compare = get_theme_mod('akistore_compare_button', '0');

		if ($compare == '1' && function_exists('woosc_init')) {
			$output .= do_shortcode('[woosc type="link"]');
		}

		return $output;
	}

	/*************************************************
## Shipping Class Name
	 *************************************************/

	function akistore_shipping_class_name($type = 'name')
	{
		global $product;
		$class_id = $product->get_shipping_class_id();
		if ($class_id) {
			$term = get_term_by('id', $class_id, 'product_shipping_class');
			if ($type == 'desc') {
				if ($term && !is_wp_error($term)) {
					return $term->description;
				}
			} else {
				if ($term && !is_wp_error($term)) {
					return $term->name;
				}
			}
		}
		return '';
	}

	/*************************************************
## People Have this in their carts.
	 *************************************************/
	function akistore_people_added_in_cart()
	{
		global $wpdb, $product;
		$in_basket = 0;
		$wc_session_data = $wpdb->get_results("SELECT session_key FROM {$wpdb->prefix}woocommerce_sessions");
		$wc_session_keys = wp_list_pluck($wc_session_data, 'session_key');
		if ($wc_session_keys) {
			foreach ($wc_session_keys as $key => $_customer_id) {
				// if you want to skip current viewer cart item in counts or else can remove belows checking
				if (WC()->session->get_customer_id() == $_customer_id) continue;

				$session_contents = WC()->session->get_session($_customer_id, array());
				$cart_contents = maybe_unserialize($session_contents['cart']);
				if ($cart_contents) {
					foreach ($cart_contents as $cart_key => $item) {
						if ($item['product_id'] == $product->get_id()) {
							$in_basket += 1;
						}
					}
				}
			}
		}

		if ($in_basket) {
			echo '<div class="people-have product-message warning">';
			echo '<i class="kegdth-icon-shopping-bag-3"></i>';
			echo '<strong>' . esc_html__('Other people want this.', 'akistore') . ' </strong>' . sprintf(esc_html__('%d  people have this in their carts right now.', 'akistore'), $in_basket);
			echo '</div>';
		}
	}
	add_action('kegd_people_added_in_cart', 'akistore_people_added_in_cart');


	/*************************************************
## Re-order WooCommerce Single Product Summary
	 *************************************************/
	if (class_exists('akistore_Elementor_Addons')) {
		$reorder_single = get_theme_mod(
			'akistore_shop_single_reorder',
			array(
				'woocommerce_template_single_title',
				'woocommerce_template_single_rating',
				'woocommerce_template_single_price',
				'woocommerce_template_single_add_to_cart',
				'akistore_people_added_in_cart',
				'woocommerce_template_single_meta',
				'akistore_social_share',
				'woocommerce_template_single_excerpt'
			)
		);

		if ($reorder_single) {
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
			remove_action('woocommerce_single_product_summary', 'akistore_social_share', 70);

			$count = 7;

			foreach ($reorder_single as $single_part) {

				add_action('woocommerce_single_product_summary', $single_part, $count);

				$count += 7;
			}
		}
	}

	/*----------------------------
  Product Type 1
 ----------------------------*/
	function akistore_product_type1()
	{
		global $product;
		global $post;
		global $woocommerce;

		$output = '';

		$id = get_the_ID();
		$allproduct = wc_get_product(get_the_ID());

		$cart_url = wc_get_cart_url();
		$price = $allproduct->get_price_html();
		$weight = $product->get_weight();
		$stock_status = $product->get_stock_status();
		$stock_text = $product->get_availability();
		$short_desc = $product->get_short_description();
		$rating = wc_get_rating_html($product->get_average_rating());
		$ratingcount = $product->get_review_count();
		$wishlist = get_theme_mod('akistore_wishlist_button', '0');
		$compare = get_theme_mod('akistore_compare_button', '0');
		$quickview = get_theme_mod('akistore_quick_view_button', '0');

		$managestock = $product->managing_stock();
		$stock_quantity = $product->get_stock_quantity();
		$stock_format = esc_html__('Only %s left in stock', 'akistore');
		$stock_poor = '';
		if ($managestock && $stock_quantity < 10) {
			$stock_poor .= '<div class="product-message color-danger">' . sprintf($stock_format, $stock_quantity) . '</div>';
		}

		$postview  = isset($_POST['shop_view']) ? $_POST['shop_view'] : '';

		if (akistore_shop_view() == 'list_view' || $postview == 'list_view') {
			$output .= '<div class="product-wrapper">';
			$output .= '<div class="product-content">';
			$output .= '<div class="thumbnail-wrapper">';
			$output .= akistore_sale_percentage();
			$output .= '<a href="' . get_permalink() . '">';
			ob_start();
			$output .= akistore_product_second_image();
			$output .= ob_get_clean();
			$output .= '</a>';
			$output .= '<div class="product-buttons">';

			$output .= akistore_wishlist_shortcode();


			$output .= akistore_featured_icon();

			$output .= '</div><!-- product-buttons -->';
			$output .= '</div><!-- thumbnail-wrapper -->';
			$output .= '<div class="content-wrapper">';
			$output .= '<h3 class="product-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
			if (akistore_vendor_name()) {
				$review_class = $ratingcount ? 'has-rating' : 'no-rating';

				$output .= '<div class="product-switcher ' . esc_attr($review_class) . '">';
				$output .= '<div class="switcher-wrapper">';
				$output .= '<div class="store-info">';
				$output .= esc_html__('Store:', 'akistore') . akistore_sanitize_data(akistore_vendor_name());
				$output .= '</div><!-- store-info -->';
				if ($ratingcount) {
					$output .= '<div class="product-rating">';
					$output .= $rating;
					$output .= '<div class="count-rating">' . esc_html($ratingcount) . ' <span class="rating-text">Ratings</span></div>';
					$output .= '</div>';
				}
				$output .= '</div><!-- switcher-wrapper -->';
				$output .= '</div><!-- product-switcher -->';
			} else {
				if ($ratingcount) {
					$output .= '<div class="product-rating">';
					$output .= $rating;
					$output .= '<div class="count-rating">' . esc_html($ratingcount) . ' <span class="rating-text">Ratings</span></div>';
					$output .= '</div>';
				}
			}
			$output .= '<div class="product-price-cart">';
			$output .= '<span class="price">';
			$output .= $price;
			$output .= '</span><!-- price -->';
			$output .= '</div><!-- product-price-cart -->';
			$output .= '<div class="product-meta">';
			if (akistore_shipping_class_name()) {
				$output .= '<div class="product-message color-light">' . akistore_shipping_class_name() . '</div>';
			}
			$output .= $stock_poor;
			$output .= '</div><!-- product-meta -->';
			if ($short_desc) {
				$output .= '<div class="product-list-details">';
				$output .= $short_desc;
				$output .= '</div><!-- product-list-details -->';
			}
			$output .= '</div><!-- content-wrapper -->';
			$output .= '</div><!-- product-content -->';

			$output .= '<div class="product-footer">';
			$output .= '<div class="product-footer-buttons style-2">';
			$output .= akistore_add_to_cart_button();

			$output .= akistore_compare_shortcode();

			if ($quickview == '1') {
				$output .= '<a href="' . $product->get_id() . '" class="detail-bnt quickview animated"><i class="kegdth-icon-eye-empty"></i></a>';
			}
			$output .= '</div>';
			$output .= '</div><!-- product-footer -->';
			$output .= '</div><!-- product-wrapper -->';
			$output .= '<div class="product-content-fade"></div>';
		} else {
			$output .= '<div class="product-wrapper product-type-1">';
			$output .= '<div class="product-content">';
			$output .= '<div class="thumbnail-wrapper">';
			$output .= akistore_sale_percentage();
			$output .= '<a href="' . get_permalink() . '">';
			ob_start();
			$output .= akistore_product_second_image();
			$output .= ob_get_clean();
			$output .= '</a>';
			$output .= '<div class="product-buttons">';

			$output .= akistore_wishlist_shortcode();

			$output .= akistore_featured_icon();

			$output .= akistore_compare_shortcode();

			if ($quickview == '1') {
				$output .= '<a href="' . $product->get_id() . '" class="detail-bnt quickview animated"><i class="kegdth-icon-eye-empty"></i></a>';
			}

			$output .= '</div>';
			$output .= '</div>';
			$output .= '<div class="content-wrapper">';
			$output .= '<h3 class="product-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
			if (akistore_vendor_name()) {
				$review_class = $ratingcount ? 'has-rating' : 'no-rating';

				$output .= '<div class="product-switcher ' . esc_attr($review_class) . '">';
				$output .= '<div class="switcher-wrapper">';
				$output .= '<div class="store-info">';
				$output .= esc_html__('Store:', 'akistore') . akistore_sanitize_data(akistore_vendor_name());
				$output .= '</div><!-- store-info -->';
				if ($ratingcount) {
					$output .= '<div class="product-rating">';
					$output .= $rating;
					$output .= '<div class="count-rating">' . esc_html($ratingcount) . ' <span class="rating-text">Ratings</span></div>';
					$output .= '</div>';
				}
				$output .= '</div><!-- switcher-wrapper -->';
				$output .= '</div><!-- product-switcher -->';
			} else {
				if ($ratingcount) {
					$output .= '<div class="product-rating">';
					$output .= $rating;
					$output .= '<div class="count-rating">' . esc_html($ratingcount) . ' <span class="rating-text">Ratings</span></div>';
					$output .= '</div>';
				}
			}
			$output .= '<div class="product-price-cart">';
			$output .= '<span class="price">';
			$output .= $price;
			$output .= '</span>';
			$output .= akistore_add_to_cart_button();
			$output .= '</div>';
			$output .= '<div class="product-meta">';
			if (akistore_shipping_class_name()) {
				$output .= '<div class="product-message color-light">' . akistore_shipping_class_name() . '</div>';
			}
			$output .= $stock_poor;
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';
			if ($short_desc) {
				$output .= '<div class="product-footer">';
				$output .= '<div class="product-footer-details">';
				$output .= $short_desc;
				$output .= '</div>';
				$output .= '</div>';
			}
			$output .= '</div>';
			$output .= '<div class="product-content-fade"></div>';
		}

		return $output;
	}

	/*----------------------------
  Product Type 2
 ----------------------------*/
	function akistore_product_type2()
	{
		global $product;
		global $post;
		global $woocommerce;

		$output = '';

		$id = get_the_ID();
		$allproduct = wc_get_product(get_the_ID());

		$cart_url = wc_get_cart_url();
		$price = $allproduct->get_price_html();
		$weight = $product->get_weight();
		$stock_status = $product->get_stock_status();
		$stock_text = $product->get_availability();
		$short_desc = $product->get_short_description();
		$rating = wc_get_rating_html($product->get_average_rating());
		$ratingcount = $product->get_review_count();
		$wishlist = get_theme_mod('akistore_wishlist_button', '0');
		$compare = get_theme_mod('akistore_compare_button', '0');
		$quickview = get_theme_mod('akistore_quick_view_button', '0');

		$managestock = $product->managing_stock();
		$stock_quantity = $product->get_stock_quantity();
		$stock_format = esc_html__('Only %s left in stock', 'akistore');
		$stock_poor = '';
		if ($managestock && $stock_quantity < 10) {
			$stock_poor .= '<div class="product-message color-danger">' . sprintf($stock_format, $stock_quantity) . '</div>';
		}

		if (akistore_shop_view() == 'list_view') {
			$output .= '<div class="product-wrapper">';
			$output .= '<div class="product-content">';
			$output .= '<div class="thumbnail-wrapper">';
			$output .= akistore_sale_percentage();
			$output .= '<a href="' . get_permalink() . '">';
			ob_start();
			$output .= akistore_product_second_image();
			$output .= ob_get_clean();
			$output .= '</a>';
			$output .= '<div class="product-buttons">';

			$output .= akistore_wishlist_shortcode();

			$output .= akistore_featured_icon();

			$output .= '</div><!-- product-buttons -->';
			$output .= '</div><!-- thumbnail-wrapper -->';
			$output .= '<div class="content-wrapper">';
			$output .= '<h3 class="product-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
			if ($ratingcount) {
				$output .= '<div class="product-rating">';
				$output .= $rating;
				$output .= '<div class="count-rating">' . esc_html($ratingcount) . ' <span class="rating-text">Ratings</span></div>';
				$output .= '</div>';
			}
			$output .= '<div class="product-price-cart">';
			$output .= '<span class="price">';
			$output .= $price;
			$output .= '</span><!-- price -->';
			$output .= '</div><!-- product-price-cart -->';
			$output .= '<div class="product-meta">';
			if (akistore_shipping_class_name()) {
				$output .= '<div class="product-message color-light">' . akistore_shipping_class_name() . '</div>';
			}
			$output .= $stock_poor;
			$output .= '</div><!-- product-meta -->';
			if ($short_desc) {
				$output .= '<div class="product-list-details">';
				$output .= $short_desc;
				$output .= '</div><!-- product-list-details -->';
			}
			$output .= '</div><!-- content-wrapper -->';
			$output .= '</div><!-- product-content -->';

			$output .= '<div class="product-footer">';
			$output .= '<div class="product-footer-buttons style-2">';
			$output .= akistore_add_to_cart_button();

			$output .= akistore_compare_shortcode();

			if ($quickview == '1') {
				$output .= '<a href="' . $product->get_id() . '" class="detail-bnt quickview animated"><i class="kegdth-icon-eye-empty"></i></a>';
			}
			$output .= '</div>';
			$output .= '</div><!-- product-footer -->';
			$output .= '</div><!-- product-wrapper -->';
			$output .= '<div class="product-content-fade"></div>';
		} else {
			$output .= '<div class="product-wrapper product-type-2">';
			$output .= '<div class="product-content">';
			$output .= '<div class="thumbnail-wrapper">';
			$output .= akistore_sale_percentage();
			$output .= '<a href="' . get_permalink() . '">';
			ob_start();
			$output .= akistore_product_second_image();
			$output .= ob_get_clean();
			$output .= '</a>';
			$output .= '<div class="product-buttons">';

			$output .= akistore_wishlist_shortcode();

			$output .= akistore_featured_icon();

			$output .= akistore_compare_shortcode();

			if ($quickview == '1') {
				$output .= '<a href="' . $product->get_id() . '" class="detail-bnt quickview animated"><i class="kegdth-icon-eye-empty"></i></a>';
			}
			$output .= '</div><!-- product-buttons -->';
			$output .= '</div><!-- thumbnail-wrapper -->';
			$output .= '<div class="content-wrapper">';
			$output .= '<h3 class="product-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
			if (akistore_vendor_name()) {
				$review_class = $ratingcount ? 'has-rating' : 'no-rating';

				$output .= '<div class="product-switcher ' . esc_attr($review_class) . '">';
				$output .= '<div class="switcher-wrapper">';
				$output .= '<div class="store-info">';
				$output .= esc_html__('Store:', 'akistore') . akistore_sanitize_data(akistore_vendor_name());
				$output .= '</div><!-- store-info -->';
				if ($ratingcount) {
					$output .= '<div class="product-rating">';
					$output .= $rating;
					$output .= '<div class="count-rating">' . esc_html($ratingcount) . ' <span class="rating-text">Ratings</span></div>';
					$output .= '</div>';
				}
				$output .= '</div><!-- switcher-wrapper -->';
				$output .= '</div><!-- product-switcher -->';
			} else {
				if ($ratingcount) {
					$output .= '<div class="product-rating">';
					$output .= $rating;
					$output .= '<div class="count-rating">' . esc_html($ratingcount) . ' <span class="rating-text">Ratings</span></div>';
					$output .= '</div>';
				}
			}
			$output .= '<span class="price">';
			$output .= $price;
			$output .= '</span><!-- price -->';
			$output .= '<div class="product-meta">';
			if (akistore_shipping_class_name()) {
				$output .= '<div class="product-message color-light">' . akistore_shipping_class_name() . '</div>';
			}
			$output .= $stock_poor;
			$output .= '</div><!-- product-meta -->';
			$output .= '</div><!-- content-wrapper -->';
			$output .= '</div><!-- product-content -->';

			$output .= '<div class="product-footer">';
			if ($short_desc) {
				$output .= '<div class="product-footer-details">';
				$output .= $short_desc;
				$output .= '</div>';
			}
			$output .= '<div class="product-footer-buttons">';
			$output .= akistore_add_to_cart_button();
			$output .= '</div><!-- product-footer-buttons -->';
			$output .= '</div><!-- product-footer -->';

			$output .= '</div><!-- product-wrapper -->';
			$output .= '<div class="product-content-fade"></div>';
		}


		return $output;
	}

	/*----------------------------
  Product Type 3
 ----------------------------*/
	function akistore_product_type3()
	{
		global $product;
		global $post;
		global $woocommerce;

		$output = '';

		$id = get_the_ID();
		$allproduct = wc_get_product(get_the_ID());

		$cart_url = wc_get_cart_url();
		$price = $allproduct->get_price_html();
		$weight = $product->get_weight();
		$stock_status = $product->get_stock_status();
		$stock_text = $product->get_availability();
		$short_desc = $product->get_short_description();
		$rating = wc_get_rating_html($product->get_average_rating());
		$ratingcount = $product->get_review_count();
		$wishlist = get_theme_mod('akistore_wishlist_button', '0');
		$compare = get_theme_mod('akistore_compare_button', '0');
		$quickview = get_theme_mod('akistore_quick_view_button', '0');

		$managestock = $product->managing_stock();
		$stock_quantity = $product->get_stock_quantity();
		$stock_format = esc_html__('Only %s left in stock', 'akistore');
		$stock_poor = '';
		if ($managestock && $stock_quantity < 10) {
			$stock_poor .= '<div class="product-message color-danger">' . sprintf($stock_format, $stock_quantity) . '</div>';
		}


		if (akistore_shop_view() == 'list_view') {
			$output .= '<div class="product-wrapper">';
			$output .= '<div class="product-content">';
			$output .= '<div class="thumbnail-wrapper">';
			$output .= akistore_sale_percentage();
			$output .= '<a href="' . get_permalink() . '">';
			ob_start();
			$output .= akistore_product_second_image();
			$output .= ob_get_clean();
			$output .= '</a>';
			$output .= '<div class="product-buttons">';

			$output .= akistore_wishlist_shortcode();

			$output .= akistore_featured_icon();

			$output .= '</div><!-- product-buttons -->';
			$output .= '</div><!-- thumbnail-wrapper -->';
			$output .= '<div class="content-wrapper">';
			$output .= '<h3 class="product-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
			if ($ratingcount) {
				$output .= '<div class="product-rating">';
				$output .= $rating;
				$output .= '<div class="count-rating">' . esc_html($ratingcount) . ' <span class="rating-text">Ratings</span></div>';
				$output .= '</div>';
			}
			$output .= '<div class="product-price-cart">';
			$output .= '<span class="price">';
			$output .= $price;
			$output .= '</span><!-- price -->';
			$output .= '</div><!-- product-price-cart -->';
			$output .= '<div class="product-meta">';
			if (akistore_shipping_class_name()) {
				$output .= '<div class="product-message color-light">' . akistore_shipping_class_name() . '</div>';
			}
			$output .= $stock_poor;
			$output .= '</div><!-- product-meta -->';
			if ($short_desc) {
				$output .= '<div class="product-list-details">';
				$output .= $short_desc;
				$output .= '</div><!-- product-list-details -->';
			}
			$output .= '</div><!-- content-wrapper -->';
			$output .= '</div><!-- product-content -->';

			$output .= '<div class="product-footer">';
			$output .= '<div class="product-footer-buttons style-2">';
			$output .= akistore_add_to_cart_button();

			$output .= akistore_compare_shortcode();

			if ($quickview == '1') {
				$output .= '<a href="' . $product->get_id() . '" class="detail-bnt quickview animated"><i class="kegdth-icon-eye-empty"></i></a>';
			}
			$output .= '</div>';
			$output .= '</div><!-- product-footer -->';
			$output .= '</div><!-- product-wrapper -->';
			$output .= '<div class="product-content-fade"></div>';
		} else {
			$output .= '<div class="product-wrapper product-type-3">';
			$output .= '<div class="product-content">';
			$output .= '<div class="thumbnail-wrapper">';
			$output .= akistore_sale_percentage();

			$output .= '<a href="' . get_permalink() . '">';
			ob_start();
			$output .= akistore_product_second_image();
			$output .= ob_get_clean();
			$output .= '</a>';
			$output .= '<div class="product-buttons">';

			$output .= akistore_wishlist_shortcode();

			$output .= akistore_featured_icon();

			$output .= akistore_compare_shortcode();

			if ($quickview == '1') {
				$output .= '<a href="' . $product->get_id() . '" class="detail-bnt quickview animated"><i class="kegdth-icon-eye-empty"></i></a>';
			}
			$output .= '</div><!-- product-buttons -->';
			$output .= '</div><!-- thumbnail-wrapper -->';
			$output .= '<div class="content-wrapper">';
			$output .= '<h3 class="product-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
			if (akistore_vendor_name()) {
				$review_class = $ratingcount ? 'has-rating' : 'no-rating';

				$output .= '<div class="product-switcher ' . esc_attr($review_class) . '">';
				$output .= '<div class="switcher-wrapper">';
				$output .= '<div class="store-info">';
				$output .= esc_html__('Store:', 'akistore') . akistore_sanitize_data(akistore_vendor_name());
				$output .= '</div><!-- store-info -->';
				if ($ratingcount) {
					$output .= '<div class="product-rating">';
					$output .= $rating;
					$output .= '<div class="count-rating">' . esc_html($ratingcount) . ' <span class="rating-text">Ratings</span></div>';
					$output .= '</div>';
				}
				$output .= '</div><!-- switcher-wrapper -->';
				$output .= '</div><!-- product-switcher -->';
			} else {
				if ($ratingcount) {
					$output .= '<div class="product-rating">';
					$output .= $rating;
					$output .= '<div class="count-rating">' . esc_html($ratingcount) . ' <span class="rating-text">Ratings</span></div>';
					$output .= '</div>';
				}
			}
			$output .= '<span class="price">';
			$output .= $price;
			$output .= '</span><!-- price -->';
			$output .= '<div class="product-meta">';
			if (akistore_shipping_class_name()) {
				$output .= '<div class="product-message color-light">' . akistore_shipping_class_name() . '</div>';
			}
			$output .= $stock_poor;
			$output .= '</div><!-- product-meta -->';
			$output .= '</div><!-- content-wrapper -->';
			$output .= '</div><!-- product-content -->';
			$output .= '<div class="product-footer">';
			$output .= '<div class="product-footer-buttons">';
			$output .= akistore_add_to_cart_button();
			$output .= '</div><!-- product-footer-buttons -->';
			if ($short_desc) {
				$output .= '<div class="product-footer-details">';
				$output .= $short_desc;
				$output .= '</div>';
			}


			$output .= '</div><!-- product-footer -->';
			$output .= '</div><!-- product-wrapper -->';
			$output .= '<div class="product-content-fade"></div>';
		}


		return $output;
	}

	/*----------------------------
  Product Type 4
 ----------------------------*/
	function akistore_product_type4()
	{
		global $product;
		global $post;
		global $woocommerce;

		$output = '';

		$id = get_the_ID();
		$allproduct = wc_get_product(get_the_ID());

		$cart_url = wc_get_cart_url();
		$price = $allproduct->get_price_html();
		$weight = $product->get_weight();
		$stock_status = $product->get_stock_status();
		$stock_text = $product->get_availability();
		$short_desc = $product->get_short_description();
		$rating = wc_get_rating_html($product->get_average_rating());
		$ratingcount = $product->get_review_count();
		$wishlist = get_theme_mod('akistore_wishlist_button', '0');
		$compare = get_theme_mod('akistore_compare_button', '0');
		$quickview = get_theme_mod('akistore_quick_view_button', '0');

		$managestock = $product->managing_stock();
		$stock_quantity = $product->get_stock_quantity();
		$stock_format = esc_html__('Only %s left in stock', 'akistore');
		$stock_poor = '';
		if ($managestock && $stock_quantity < 10) {
			$stock_poor .= '<div class="product-message color-danger">' . sprintf($stock_format, $stock_quantity) . '</div>';
		}


		if (akistore_shop_view() == 'list_view') {
			$output .= '<div class="product-wrapper">';
			$output .= '<div class="product-content">';
			$output .= '<div class="thumbnail-wrapper">';
			$output .= akistore_sale_percentage();
			$output .= '<a href="' . get_permalink() . '">';
			ob_start();
			$output .= akistore_product_second_image();
			$output .= ob_get_clean();
			$output .= '</a>';
			$output .= '<div class="product-buttons">';

			$output .= akistore_wishlist_shortcode();

			$output .= akistore_featured_icon();

			$output .= '</div><!-- product-buttons -->';
			$output .= '</div><!-- thumbnail-wrapper -->';
			$output .= '<div class="content-wrapper">';
			$output .= '<h3 class="product-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
			if ($ratingcount) {
				$output .= '<div class="product-rating">';
				$output .= $rating;
				$output .= '<div class="count-rating">' . esc_html($ratingcount) . ' <span class="rating-text">Ratings</span></div>';
				$output .= '</div>';
			}
			$output .= '<div class="product-price-cart">';
			$output .= '<span class="price">';
			$output .= $price;
			$output .= '</span><!-- price -->';
			$output .= '</div><!-- product-price-cart -->';
			$output .= '<div class="product-meta">';
			if (akistore_shipping_class_name()) {
				$output .= '<div class="product-message color-light">' . akistore_shipping_class_name() . '</div>';
			}
			$output .= $stock_poor;
			$output .= '</div><!-- product-meta -->';
			if ($short_desc) {
				$output .= '<div class="product-list-details">';
				$output .= $short_desc;
				$output .= '</div><!-- product-list-details -->';
			}
			$output .= '</div><!-- content-wrapper -->';
			$output .= '</div><!-- product-content -->';

			$output .= '<div class="product-footer">';
			$output .= '<div class="product-footer-buttons style-2">';
			$output .= akistore_add_to_cart_button();

			$output .= akistore_compare_shortcode();

			if ($quickview == '1') {
				$output .= '<a href="' . $product->get_id() . '" class="detail-bnt quickview animated"><i class="kegdth-icon-eye-empty"></i></a>';
			}
			$output .= '</div>';
			$output .= '</div><!-- product-footer -->';
			$output .= '</div><!-- product-wrapper -->';
			$output .= '<div class="product-content-fade"></div>';
		} else {
			$output .= '<div class="product-wrapper product-type-4">';
			$output .= '<div class="product-content">';
			$output .= '<div class="thumbnail-wrapper">';
			$output .= akistore_sale_percentage();
			$output .= '<a href="' . get_permalink() . '">';
			ob_start();
			$output .= akistore_product_second_image();
			$output .= ob_get_clean();
			$output .= '</a>';
			$output .= '<div class="product-buttons">';

			$output .= akistore_wishlist_shortcode();

			$output .= akistore_featured_icon();

			$output .= '</div><!-- product-buttons -->';
			$output .= '</div><!-- thumbnail-wrapper -->';
			$output .= '<div class="content-wrapper">';
			$output .= '<h3 class="product-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
			if (akistore_vendor_name()) {
				$review_class = $ratingcount ? 'has-rating' : 'no-rating';

				$output .= '<div class="product-switcher ' . esc_attr($review_class) . '">';
				$output .= '<div class="switcher-wrapper">';
				$output .= '<div class="store-info">';
				$output .= esc_html__('Store:', 'akistore') . akistore_sanitize_data(akistore_vendor_name());
				$output .= '</div><!-- store-info -->';
				if ($ratingcount) {
					$output .= '<div class="product-rating">';
					$output .= $rating;
					$output .= '<div class="count-rating">' . esc_html($ratingcount) . ' <span class="rating-text">Ratings</span></div>';
					$output .= '</div>';
				}
				$output .= '</div><!-- switcher-wrapper -->';
				$output .= '</div><!-- product-switcher -->';
			} else {
				if ($ratingcount) {
					$output .= '<div class="product-rating">';
					$output .= $rating;
					$output .= '<div class="count-rating">' . esc_html($ratingcount) . ' <span class="rating-text">Ratings</span></div>';
					$output .= '</div>';
				}
			}
			$output .= '<span class="price">';
			$output .= $price;
			$output .= '</span><!-- price -->';
			$output .= '<div class="product-meta">';
			if (akistore_shipping_class_name()) {
				$output .= '<div class="product-message color-light">' . akistore_shipping_class_name() . '</div>';
			}
			$output .= $stock_poor;
			$output .= '</div><!-- product-meta -->';
			$output .= '</div><!-- content-wrapper -->';
			$output .= '</div><!-- product-content -->';
			$output .= '<div class="product-footer">';
			$output .= '<div class="product-footer-buttons style-2">';
			$output .= akistore_add_to_cart_button();

			$output .= akistore_compare_shortcode();

			if ($quickview == '1') {
				$output .= '<a href="' . $product->get_id() . '" class="detail-bnt quickview animated"><i class="kegdth-icon-eye-empty"></i></a>';
			}
			$output .= '</div><!-- product-footer-buttons -->';
			$output .= '</div><!-- product-footer -->';
			$output .= '</div><!-- product-wrapper -->';
			$output .= '<div class="product-content-fade"></div>';
		}


		return $output;
	}

	/*----------------------------------
  Product Type 5 with progress bar
 -----------------------------------*/
	function akistore_product_type5()
	{
		global $product;
		global $post;
		global $woocommerce;

		$output = '';

		$id = get_the_ID();
		$allproduct = wc_get_product(get_the_ID());

		$cart_url = wc_get_cart_url();
		$price = $allproduct->get_price_html();
		$weight = $product->get_weight();
		$stock_status = $product->get_stock_status();
		$stock_text = $product->get_availability();
		$short_desc = $product->get_short_description();
		$rating = wc_get_rating_html($product->get_average_rating());
		$ratingcount = $product->get_review_count();
		$wishlist = get_theme_mod('akistore_wishlist_button', '0');
		$compare = get_theme_mod('akistore_compare_button', '0');
		$quickview = get_theme_mod('akistore_quick_view_button', '0');

		$managestock = $product->managing_stock();
		$stock_quantity = $product->get_stock_quantity();
		$stock_format = esc_html__('Only %s left in stock', 'akistore');
		$stock_poor = '';
		if ($managestock && $stock_quantity < 10) {
			$stock_poor .= '<div class="product-message color-danger">' . sprintf($stock_format, $stock_quantity) . '</div>';
		}

		$total_sales = $product->get_total_sales();
		$total_stock = $total_sales + $stock_quantity;

		if ($managestock) {
			$progress_percentage = floor($total_sales / (($total_sales + $stock_quantity) / 100)); // yuvarlama
		}

		$postview  = isset($_POST['shop_view']) ? $_POST['shop_view'] : '';

		if (akistore_shop_view() == 'list_view' || $postview == 'list_view') {
			$output .= '<div class="product-wrapper">';
			$output .= '<div class="product-content">';
			$output .= '<div class="thumbnail-wrapper">';
			$output .= akistore_sale_percentage();
			$output .= '<a href="' . get_permalink() . '">';
			ob_start();
			$output .= akistore_product_second_image();
			$output .= ob_get_clean();
			$output .= '</a>';
			$output .= '<div class="product-buttons">';

			$output .= akistore_wishlist_shortcode();

			$output .= akistore_featured_icon();

			$output .= '</div><!-- product-buttons -->';
			$output .= '</div><!-- thumbnail-wrapper -->';
			$output .= '<div class="content-wrapper">';
			$output .= '<h3 class="product-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
			if ($ratingcount) {
				$output .= '<div class="product-rating">';
				$output .= $rating;
				$output .= '<div class="count-rating">' . esc_html($ratingcount) . ' <span class="rating-text">Ratings</span></div>';
				$output .= '</div>';
			}
			$output .= '<div class="product-price-cart">';
			$output .= '<span class="price">';
			$output .= $price;
			$output .= '</span><!-- price -->';
			$output .= '</div><!-- product-price-cart -->';
			$output .= '<div class="product-meta">';
			if (akistore_shipping_class_name()) {
				$output .= '<div class="product-message color-light">' . akistore_shipping_class_name() . '</div>';
			}
			$output .= $stock_poor;
			$output .= '</div><!-- product-meta -->';
			if ($short_desc) {
				$output .= '<div class="product-list-details">';
				$output .= '<p>' . $short_desc . '</p>';
				$output .= '</div><!-- product-list-details -->';
			}
			$output .= '</div><!-- content-wrapper -->';
			$output .= '</div><!-- product-content -->';

			$output .= '<div class="product-footer">';
			$output .= '<div class="product-footer-buttons style-2">';
			$output .= akistore_add_to_cart_button();

			$output .= akistore_compare_shortcode();

			if ($quickview == '1') {
				$output .= '<a href="' . $product->get_id() . '" class="detail-bnt quickview animated"><i class="kegdth-icon-eye-empty"></i></a>';
			}
			$output .= '</div>';
			$output .= '</div><!-- product-footer -->';
			$output .= '</div><!-- product-wrapper -->';
			$output .= '<div class="product-content-fade"></div>';
		} else {
			$output .= '<div class="product-wrapper product-type-1 product-type-5">';
			$output .= '<div class="product-content">';
			$output .= '<div class="thumbnail-wrapper">';
			$output .= akistore_sale_percentage();
			$output .= '<a href="' . get_permalink() . '">';
			ob_start();
			$output .= akistore_product_second_image();
			$output .= ob_get_clean();
			$output .= '</a>';
			$output .= '<div class="product-buttons">';

			$output .= akistore_wishlist_shortcode();

			$output .= akistore_featured_icon();

			$output .= akistore_compare_shortcode();

			if ($quickview == '1') {
				$output .= '<a href="' . $product->get_id() . '" class="detail-bnt quickview animated"><i class="kegdth-icon-eye-empty"></i></a>';
			}
			$output .= '</div>';
			$output .= '</div>';
			$output .= '<div class="content-wrapper">';

			if ($managestock && $stock_quantity > 0) {
				$output .= '<div class="product-offer-count color-red">';
				$output .= '<div class="product-count-top">' . esc_html__('Sold:', 'akistore') . ' <strong>' . esc_html($total_sales) . '</strong> / ' . esc_html($total_stock) . '</div>';
				$output .= '<div class="product-progress"><span style="width: ' . esc_attr($progress_percentage) . '%;"></span></div>';
				$output .= '</div>';
			}

			$output .= '<h3 class="product-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
			if (akistore_vendor_name()) {
				$review_class = $ratingcount ? 'has-rating' : 'no-rating';

				$output .= '<div class="product-switcher ' . esc_attr($review_class) . '">';
				$output .= '<div class="switcher-wrapper">';
				$output .= '<div class="store-info">';
				$output .= esc_html__('Store:', 'akistore') . akistore_sanitize_data(akistore_vendor_name());
				$output .= '</div><!-- store-info -->';
				if ($ratingcount) {
					$output .= '<div class="product-rating">';
					$output .= $rating;
					$output .= '<div class="count-rating">' . esc_html($ratingcount) . ' <span class="rating-text">Ratings</span></div>';
					$output .= '</div>';
				}
				$output .= '</div><!-- switcher-wrapper -->';
				$output .= '</div><!-- product-switcher -->';
			} else {
				if ($ratingcount) {
					$output .= '<div class="product-rating">';
					$output .= $rating;
					$output .= '<div class="count-rating">' . esc_html($ratingcount) . ' <span class="rating-text">Ratings</span></div>';
					$output .= '</div>';
				}
			}
			$output .= '<div class="product-price-cart">';
			$output .= '<span class="price">';
			$output .= $price;
			$output .= '</span>';
			$output .= akistore_add_to_cart_button();
			$output .= '</div>';
			$output .= '<div class="product-meta">';
			if (akistore_shipping_class_name()) {
				$output .= '<div class="product-message color-light">' . akistore_shipping_class_name() . '</div>';
			}
			$output .= $stock_poor;
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';
			if ($short_desc) {
				$output .= '<div class="product-footer">';
				$output .= '<div class="product-footer-details">';
				$output .= $short_desc;
				$output .= '</div>';
				$output .= '</div>';
			}
			$output .= '</div>';
			$output .= '<div class="product-content-fade"></div>';
		}

		return $output;
	}


	/*----------------------------
  Product Type Header
 ----------------------------*/
	function akistore_product_type_header()
	{
		global $product;
		global $post;
		global $woocommerce;

		$output = '';

		$id = get_the_ID();
		$allproduct = wc_get_product(get_the_ID());

		$cart_url = wc_get_cart_url();
		$price = $allproduct->get_price_html();
		$weight = $product->get_weight();
		$stock_status = $product->get_stock_status();
		$stock_text = $product->get_availability();
		$short_desc = $product->get_short_description();
		$rating = wc_get_rating_html($product->get_average_rating());
		$ratingcount = $product->get_review_count();
		$wishlist = get_theme_mod('akistore_wishlist_button', '0');
		$compare = get_theme_mod('akistore_compare_button', '0');
		$quickview = get_theme_mod('akistore_quick_view_button', '0');

		$managestock = $product->managing_stock();
		$stock_quantity = $product->get_stock_quantity();
		$stock_format = esc_html__('Only %s left in stock', 'akistore');
		$stock_poor = '';
		if ($managestock && $stock_quantity < 10) {
			$stock_poor .= '<div class="product-message color-danger">' . sprintf($stock_format, $stock_quantity) . '</div>';
		}

		$postview  = isset($_POST['shop_view']) ? $_POST['shop_view'] : '';

		$output .= '<div class="product-wrapper">';
		$output .= '<div class="product-content">';
		$output .= '<div class="thumbnail-wrapper">';
		$output .= '<a href="' . get_permalink() . '"><img src="' . akistore_product_image() . '" alt="' . the_title_attribute('echo=0') . '"></a>';
		$output .= '</div><!-- thumbnail-wrapper -->';
		$output .= '<div class="content-wrapper">';
		$output .= '<h3 class="product-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
		if ($ratingcount) {
			$output .= '<div class="product-rating">';
			$output .= $rating;
			$output .= '<div class="count-rating">' . esc_html($ratingcount) . ' <span class="rating-text">Ratings</span></div>';
			$output .= '</div>';
		}

		$output .= '<span class="price">';
		$output .= $price;
		$output .= '</span><!-- price -->';
		$output .= '<div class="product-meta">';
		if (akistore_shipping_class_name()) {
			$output .= '<div class="product-message color-light">' . akistore_shipping_class_name() . '</div>';
		}
		$output .= $stock_poor;
		$output .= '</div><!-- product-meta -->';
		$output .= '</div><!-- content-wrapper -->';
		$output .= '</div><!-- product-content -->';
		$output .= '</div>';

		return $output;
	}

	/*----------------------------
  Product Type List
 ----------------------------*/
	function akistore_product_type_list($countdown)
	{
		global $product;
		global $post;
		global $woocommerce;

		$output = '';

		$id = get_the_ID();
		$allproduct = wc_get_product(get_the_ID());

		$cart_url = wc_get_cart_url();
		$price = $allproduct->get_price_html();
		$weight = $product->get_weight();
		$stock_status = $product->get_stock_status();
		$stock_text = $product->get_availability();
		$short_desc = $product->get_short_description();
		$rating = wc_get_rating_html($product->get_average_rating());
		$ratingcount = $product->get_review_count();
		$wishlist = get_theme_mod('akistore_wishlist_button', '0');
		$compare = get_theme_mod('akistore_compare_button', '0');
		$quickview = get_theme_mod('akistore_quick_view_button', '0');
		$sale_price_dates_to    = ($date = get_post_meta($id, '_sale_price_dates_to', true)) ? date_i18n('Y/m/d', $date) : '';

		$managestock = $product->managing_stock();
		$stock_quantity = $product->get_stock_quantity();
		$stock_format = esc_html__('Only %s left in stock', 'akistore');
		$stock_poor = '';
		if ($managestock && $stock_quantity < 10) {
			$stock_poor .= '<div class="product-message color-danger">' . sprintf($stock_format, $stock_quantity) . '</div>';
		}

		$output .= '<div class="product-wrapper">';
		$output .= '<div class="product-content">';
		$output .= '<div class="thumbnail-wrapper">';
		$output .= akistore_sale_percentage();
		$output .= '<a href="' . get_permalink() . '">';
		ob_start();
		$output .= akistore_product_second_image();
		$output .= ob_get_clean();
		$output .= '</a>';
		$output .= '<div class="product-buttons">';

		$output .= akistore_wishlist_shortcode();

		$output .= akistore_featured_icon();

		$output .= akistore_compare_shortcode();

		if ($quickview == '1') {
			$output .= '<a href="' . $product->get_id() . '" class="detail-bnt quickview animated"><i class="kegdth-icon-eye-empty"></i></a>';
		}
		$output .= '</div><!-- product-buttons -->';
		$output .= '</div><!-- thumbnail-wrapper -->';
		$output .= '<div class="content-wrapper">';
		$output .= '<h3 class="product-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';

		if ($ratingcount) {
			$output .= '<div class="product-rating">';
			$output .= $rating;
			$output .= '<div class="count-rating">' . esc_html($ratingcount) . ' <span class="rating-text">Ratings</span></div>';
			$output .= '</div>';
		}

		$output .= '<span class="price">';
		$output .= $price;
		$output .= '</span><!-- price -->';
		if ($short_desc) {
			$output .= '<div class="product-details">';
			$output .= $short_desc;
			$output .= '</div>';
		}
		$output .= '</div><!-- content-wrapper -->';
		$output .= '</div><!-- product-content -->';

		if ($sale_price_dates_to && $countdown == 'yes') {
			$output .= '<div class="product-countdown">';
			$output .= '<div class="countdown" data-date="' . esc_attr($sale_price_dates_to) . '" data-text="' . esc_attr__('Expired', 'akistore') . '">';
			$output .= '<div class="count-item days"></div>';
			$output .= '<span>:</span>';
			$output .= '<div class="count-item hours"></div>';
			$output .= '<span>:</span>';
			$output .= '<div class="count-item minutes"></div>';
			$output .= '<span>:</span>';
			$output .= '<div class="count-item second"></div>';
			$output .= '</div><!-- countdown -->';
			$output .= '<div class="countdown-text">' . esc_html__('Remains until the end of the offer', 'akistore') . '</div>';
			$output .= '</div><!-- product-countdown -->';
		}

		$output .= '<div class="product-footer">';
		$output .= '<div class="product-footer-buttons">';
		$output .= akistore_add_to_cart_button();
		$output .= '</div><!-- product-footer-buttons -->';
		$output .= '</div><!-- product-footer -->';
		$output .= '</div><!-- product-wrapper -->';
		$output .= '<div class="product-content-fade"></div>';

		return $output;
	}

	/*----------------------------
  Add my owns
 ----------------------------*/
	function akistore_shop_thumbnail()
	{
		if (get_theme_mod('akistore_product_box_type') == 'type4') {
			echo akistore_product_type4();
		} elseif (get_theme_mod('akistore_product_box_type') == 'type3') {
			echo akistore_product_type3();
		} elseif (get_theme_mod('akistore_product_box_type') == 'type2') {
			echo akistore_product_type2();
		} else {
			echo akistore_product_type1();
		}
	}

	/*************************************************
## Woocommerce Cart Text
	 *************************************************/

	//add to cart button
	function akistore_add_to_cart_button()
	{
		global $product;
		$output = '';

		ob_start();
		woocommerce_template_loop_add_to_cart();
		$output .= ob_get_clean();

		if (!empty($output)) {
			$pos = strpos($output, ">");

			if ($pos !== false) {
				$output = substr_replace($output, ">", $pos, strlen(1));
			}
		}

		if ($product->get_type() == 'variable' && empty($output)) {
			$output = "<a class='btn btn-primary add-to-cart cart-hover' href='" . get_permalink($product->get_id()) . "'>" . esc_html__('Select options', 'akistore') . "</a>";
		}

		if ($product->get_type() == 'simple') {
			$output .= "";
		} else {
			$btclass  = "single_bt";
		}

		if ($output) return "$output";
	}



	/*************************************************
## Woo Cart Ajax
	 *************************************************/

	add_filter('woocommerce_add_to_cart_fragments', 'akistore_header_add_to_cart_fragment');
	function akistore_header_add_to_cart_fragment($fragments)
	{
		global $woocommerce;
		ob_start();
?>

		<div class="button-count cart-count"><?php echo sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'akistore'), $woocommerce->cart->cart_contents_count); ?></div>


	<?php
		$fragments['div.cart-count'] = ob_get_clean();

		return $fragments;
	}

	add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {

		ob_start();
	?>

		<div class="fl-mini-cart-content">
			<?php woocommerce_mini_cart(); ?>
		</div>

	<?php $fragments['div.fl-mini-cart-content'] = ob_get_clean();

		return $fragments;
	});

	add_filter('woocommerce_add_to_cart_fragments', 'akistore_header_add_to_cart_fragment_subtotal');
	function akistore_header_add_to_cart_fragment_subtotal($fragments)
	{
		global $woocommerce;
		ob_start();
	?>

		<div class="primary-text cart-subtotal"><?php echo WC()->cart->get_cart_subtotal(); ?></div>

<?php $fragments['.primary-text.cart-subtotal'] = ob_get_clean();

		return $fragments;
	}


	/*************************************************
## akistore Woo Search Form
	 *************************************************/

	add_filter('get_product_search_form', 'akistore_custom_product_searchform');

	function akistore_custom_product_searchform($form)
	{

		$form = '<form class="product-search-form" action="' . esc_url(home_url('/')) . '" role="search" method="get" id="searchform">
				<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . esc_attr__('Search', 'akistore') . '">
				<button type="submit"><i class="kegd-right"></i></button>
                <input type="hidden" name="post_type" value="product" />
			</form>';

		return $form;
	}

	function akistore_header_product_search()
	{
		$terms = get_terms(array(
			'taxonomy' => 'product_cat',
			'hide_empty' => true,
			'parent'    => 0,
		));

		$form = '';
		$form .= '<form action="' . esc_url(home_url('/')) . '" class="search-form" role="search" method="get" id="searchform">';
		$form .= '<div class="input-group">';
		$form .= '<div class="input-search-addon">';
		$form .= '<select class="form-select custom-width" name="product_cat" id="categories">';
		$form .= '<option class="select-value" value="" selected="selected">' . esc_html__('All', 'akistore') . '</option>';
		foreach ($terms as $term) {
			if ($term->count >= 1) {
				$form .= '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
			}
		}
		$form .= '</select>';
		$form .= '</div><!-- input-search-addon -->';
		$form .= '<div class="input-search-field">';
		$form .= '<i class="kegdth-icon-search"></i>';
		$form .= '<input type="search" value="' . get_search_query() . '" class="form-control" name="s" placeholder="' . esc_attr('Search your favorite product...', 'akistore') . '" autocomplete="off" >';
		$form .= '</div><!-- input-search-field -->';
		$form .= '<div class="input-search-button">';
		$form .= '<button class="btn btn-primary" type="submit">' . esc_html__('Search', 'akistore') . '</button>';
		$form .= '</div><!-- input-search-button -->';
		$form .= '</div><!-- input-group -->';
		$form .= '<input type="hidden" name="post_type" value="product" />';
		$form .= '</form>';

		return $form;
	}

	/*************************************************
## akistore Gallery Thumbnail Size
	 *************************************************/
	add_filter('woocommerce_get_image_size_gallery_thumbnail', function ($size) {
		return array(
			'width' => 90,
			'height' => 54,
			'crop' => 0,
		);
	});


	/*************************************************
## Quick View Scripts
	 *************************************************/

	function akistore_quick_view_scripts()
	{
		wp_enqueue_script('akistore-quick-ajax', get_template_directory_uri() . '/assets/js/custom/quick_ajax.js', array('jquery'), '1.0.0', true);
		wp_localize_script('akistore-quick-ajax', 'MyAjax', array(
			'ajaxurl' => esc_url(admin_url('admin-ajax.php')),
		));
	}
	add_action('wp_enqueue_scripts', 'akistore_quick_view_scripts');

	/*************************************************
## Quick View CallBack
	 *************************************************/

	add_action('wp_ajax_nopriv_quick_view', 'akistore_quick_view_callback');
	add_action('wp_ajax_quick_view', 'akistore_quick_view_callback');
	function akistore_quick_view_callback()
	{

		$id = intval($_POST['id']);
		$loop = new WP_Query(
			array(
				'post_type' => 'product',
				'p' => $id,
			)
		);

		while ($loop->have_posts()) : $loop->the_post();
			$product = new WC_Product(get_the_ID());

			$rating = wc_get_rating_html($product->get_average_rating());
			$price = $product->get_price_html();
			$rating_count = $product->get_rating_count();
			$review_count = $product->get_review_count();
			$average      = $product->get_average_rating();
			$product_image_ids = $product->get_gallery_attachment_ids();

			$output = '';

			$output .= '<div class="quickview-product single-product-wrapper product white-popup">';
			$output .= '<div class="quick-product-wrapper single-product-container">';
			$output .= '<button title="Close (Esc)" type="button" class="mfp-close">×</button>';
			$output .= '<div class="row">';

			$output .= '<div class="col col-12 col-lg-6">';

			if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) {
				$att = get_post_thumbnail_id();
				$image_src = wp_get_attachment_image_src($att, 'full');
				$image_src = $image_src[0];

				$output .= '<div class="single-thumbnails default">';
				$output .= '<div class="woocommerce-product-gallery">';

				$output .= '<div class="images-wrapper">';
				$output .= '<div id="product-images" class="swiper-container" data-effect="slide" data-direction="horizontal" data-loop="false" data-speed="1000" data-spacebetween="0" data-autoplay="false" data-autospeed="300" data-items="1" data-mobileitems="1" data-tabletitems="1">';
				$output .= '<div class="swiper-wrapper">';
				$output .= '<div class="swiper-slide">';
				$output .= '<a href="#"><img src="' . esc_url($image_src) . '"></a>';
				$output .= '</div><!-- swiper-slide -->';
				foreach ($product_image_ids as $product_image_id) {
					$image_url = wp_get_attachment_url($product_image_id);
					$output .= '<div class="swiper-slide">';
					$output .= '<a href="#"><img src="' . esc_url($image_url) . '"></a>';
					$output .= '</div><!-- swiper-slide -->';
				}
				$output .= '</div><!-- swiper-wrapper -->';
				if ($product_image_ids) {
					$output .= '<div class="swiper-button-prev"></div>';
					$output .= '<div class="swiper-button-next"></div>';
				}
				$output .= '</div><!-- product-images -->';
				$output .= '</div><!-- images-wrapper -->';

				if ($product_image_ids) {
					$output .= '<div class="thumbnails-wrapper">';
					$output .= '<div id="product-thumbnails" class="swiper-container" data-effect="slide" data-direction="horizontal" data-loop="false" data-speed="1000" data-spacebetween="5" data-autoplay="false" data-autospeed="300" data-items="4" data-mobileitems="1" data-tabletitems="1">';
					$output .= '<div class="swiper-wrapper">';
					$output .= '<div class="swiper-slide">';
					$output .= '<a href="#"><img src="' . esc_url($image_src) . '"></a>';
					$output .= '</div><!-- swiper-slide -->';
					foreach ($product_image_ids as $product_image_id) {
						$image_url = wp_get_attachment_url($product_image_id);
						$output .= '<div class="swiper-slide">';
						$output .= '<a href="#"><img src="' . esc_url($image_url) . '"></a>';
						$output .= '</div><!-- swiper-slide -->';
					}
					$output .= '</div><!-- swiper-wrapper -->';
					$output .= '</div><!-- product-thumbnails -->';
					$output .= '</div><!-- thumbnails-wrapper -->';
				}

				$output .= '</div><!-- woocommerce-product-gallery -->';
				$output .= '</div><!-- single-thumbnails -->';
			}
			$output .= '</div><!-- col -->';

			$output .= '<div class="col col-12 col-lg-6">';
			ob_start();
			woocommerce_template_single_title();
			$output .= ob_get_clean();

			ob_start();
			woocommerce_template_single_rating();
			$output .= ob_get_clean();

			ob_start();
			woocommerce_template_single_price();
			$output .= ob_get_clean();

			ob_start();
			woocommerce_template_single_add_to_cart();
			$output .= ob_get_clean();

			ob_start();
			do_action('kegd_people_added_in_cart');
			$output .= ob_get_clean();

			ob_start();
			woocommerce_template_single_meta();
			$output .= ob_get_clean();
			$output .= '</div><!-- col -->';

			$output .= '</div><!-- row -->';
			$output .= '</div><!-- quick-product-wrapper -->';
			$output .= '</div><!-- quickview-product -->';

		endwhile;
		wp_reset_postdata();

		$output_escaped = $output;
		echo $output_escaped;

		wp_die();
	}


	/*************************************************
## akistore Filter by Attribute
	 *************************************************/
	function akistore_woocommerce_layered_nav_term_html($term_html, $term, $link, $count)
	{

		$attribute_label_name = wc_attribute_label($term->taxonomy);;
		$attribute_id = wc_attribute_taxonomy_id_by_name($attribute_label_name);
		$attr  = wc_get_attribute($attribute_id);
		$array = json_decode(json_encode($attr), true);

		if ($array['type'] == 'color') {
			$color = get_term_meta($term->term_id, 'product_attribute_color', true);
			$term_html = '<div class="type-color"><span class="color-box" style="background-color:' . esc_attr($color) . ';"></span>' . $term_html . '</div>';
		}

		if ($array['type'] == 'button') {
			$term_html = '<div class="type-button"><span class="button-box"></span>' . $term_html . '</div>';
		}

		return $term_html;
	};

	add_filter('woocommerce_layered_nav_term_html', 'akistore_woocommerce_layered_nav_term_html', 10, 4);


	/*************************************************
## Shop Width Body Classes
	 *************************************************/

	function akistore_body_classes($classes)
	{

		if (get_theme_mod('akistore_shop_width') == 'wide' || akistore_get_option() == 'wide' && is_shop()) {
			$classes[] = 'shop-wide';
		} else {
			$classes[] = '';
		}

		return $classes;
	}
	add_filter('body_class', 'akistore_body_classes');

	/*************************************************
## Stock Availability Translation
	 *************************************************/

	if (get_theme_mod('akistore_stock_quantity', 0) != 1) {
		add_filter('woocommerce_get_availability', 'akistore_custom_get_availability', 1, 2);
		function akistore_custom_get_availability($availability, $_product)
		{

			// Change In Stock Text
			if ($_product->is_in_stock()) {
				$availability['availability'] = esc_html__('In Stock', 'akistore');
			}
			// Change Out of Stock Text
			if (!$_product->is_in_stock()) {
				$availability['availability'] = esc_html__('Out of stock', 'akistore');
			}
			return $availability;
		}
	}

	/*************************************************
## Archive Description After Content
	 *************************************************/
	if (get_theme_mod('akistore_category_description_after_content', 0) == 1) {
		remove_action('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10);
		remove_action('woocommerce_archive_description', 'woocommerce_product_archive_description', 10);
		add_action('akistore_after_main_shop', 'woocommerce_taxonomy_archive_description', 5);
		add_action('akistore_after_main_shop', 'woocommerce_product_archive_description', 5);
	}

	/*************************************************
## Catalog Mode - Disable Add to cart Button
	 *************************************************/
	if (get_theme_mod('akistore_catalog_mode', 0) == 1 || akistore_get_option() == 'catalogmode') {
		add_filter('woocommerce_loop_add_to_cart_link', 'akistore_remove_add_to_cart_buttons', 1);
		function akistore_remove_add_to_cart_buttons()
		{
			return false;
		}
	}

	/*************************************************
## Product Specification Tab
	 *************************************************/
	add_filter('woocommerce_product_tabs', 'akistore_product_specification_tab');
	function akistore_product_specification_tab($tabs)
	{
		$specification = get_post_meta(get_the_ID(), 'kegd_product_specification', true);

		// Adds the new tab
		if ($specification) {
			$tabs['specification'] = array(
				'title' 	=> esc_html__('Specification', 'akistore'),
				'priority' 	=> 15,
				'callback' 	=> 'akistore_product_specification_tab_content'
			);
		}

		return $tabs;
	}
	function akistore_product_specification_tab_content()
	{
		$specification = get_post_meta(get_the_ID(), 'kegd_product_specification', true);
		echo '<div class="specification-content">' . akistore_sanitize_data($specification) . '</div>';
	}

	/*************************************************
## Woo Smart Compare Disable
	 *************************************************/
	add_filter('woosc_button_position_archive', '__return_false');
	add_filter('woosc_button_position_single', '__return_false');
} // is woocommerce activated

?>