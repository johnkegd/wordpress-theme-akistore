<?php

/**
 * page.php
 * @package WordPress
 * @subpackage Akistore
 * @since Akistore 1.0
 */
?>

<?php get_header(); ?>

<?php $elementor_page = get_post_meta(get_the_ID(), '_elementor_edit_mode', true); ?>

<?php if (class_exists('woocommerce')) { ?>

	<?php if (is_cart()) { ?>
		<div class="shop-content mt-60">
			<div class="container">
				<?php while (have_posts()) : the_post(); ?>
					<?php the_content(); ?>
					<?php wp_link_pages(array('before' => '<div class="kegd-pagination">' . esc_html__('Pages:', 'akistore'), 'after'  => '</div>', 'next_or_number' => 'number')); ?>
				<?php endwhile; ?>
			</div>
		</div>
	<?php } elseif (is_checkout()) { ?>
		<div class="shop-content mt-60">
			<div class="container">
				<?php while (have_posts()) : the_post(); ?>
					<?php the_content(); ?>
					<?php wp_link_pages(array('before' => '<div class="kegd-pagination">' . esc_html__('Pages:', 'akistore'), 'after'  => '</div>', 'next_or_number' => 'number')); ?>
				<?php endwhile; ?>
			</div>
		</div>
	<?php } elseif (is_account_page()) { ?>
		<div class="shop-content my-account-page mt-60">
			<div class="container">
				<?php while (have_posts()) : the_post(); ?>
					<?php the_content(); ?>
					<?php wp_link_pages(array('before' => '<div class="kegd-pagination">' . esc_html__('Pages:', 'akistore'), 'after'  => '</div>', 'next_or_number' => 'number')); ?>
				<?php endwhile; ?>
			</div>
		</div>
	<?php } elseif ($elementor_page) { ?>

		<?php while (have_posts()) : the_post(); ?>
			<?php the_content(); ?>
			<?php wp_link_pages(array('before' => '<div class="kegd-pagination">' . esc_html__('Pages:', 'akistore'), 'after'  => '</div>', 'next_or_number' => 'number')); ?>
		<?php endwhile; ?>

	<?php } else { ?>
		<div class="empty-kegd"></div>
		<div class="kegd-page section">
			<div class="container">
				<div class="row ">
					<div class="col-12 col-lg-10 offset-lg-1">
						<?php while (have_posts()) : the_post(); ?>
							<h1 class="kegd-page-title"><?php the_title(); ?></h1>
							<div class="kegd-post">
								<?php the_content(); ?>
								<?php wp_link_pages(array('before' => '<div class="kegd-pagination">' . esc_html__('Pages:', 'akistore'), 'after'  => '</div>', 'next_or_number' => 'number')); ?>
							</div>
						<?php endwhile; ?>
						<?php comments_template(); ?>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
<?php } else { ?>

	<?php if ($elementor_page) { ?>

		<?php while (have_posts()) : the_post(); ?>
			<?php the_content(); ?>
			<?php wp_link_pages(array('before' => '<div class="kegd-pagination">' . esc_html__('Pages:', 'akistore'), 'after'  => '</div>', 'next_or_number' => 'number')); ?>
		<?php endwhile; ?>

	<?php } else { ?>
		<div class="empty-kegd"></div>
		<div class="kegd-page section">
			<div class="container">
				<div class="row ">
					<div class="col-12 col-lg-10 offset-lg-1">
						<?php while (have_posts()) : the_post(); ?>
							<h1 class="kegd-page-title"><?php the_title(); ?></h1>
							<div class="kegd-post">
								<?php the_content(); ?>
								<?php wp_link_pages(array('before' => '<div class="kegd-pagination">' . esc_html__('Pages:', 'akistore'), 'after'  => '</div>', 'next_or_number' => 'number')); ?>
							</div>
						<?php endwhile; ?>
						<?php comments_template(); ?>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
<?php } ?>
<?php get_footer(); ?>