<article id="post-<?php the_ID(); ?>" <?php post_class('kegd-article'); ?>>
	<div class="entry-post-header">
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<div class="entry-footer">
			<div class="entry-meta">
				<span class="meta-item entry-published"><a href="<?php the_permalink(); ?>"><i class="kegdth-icon-clock-outline"></i> <?php echo get_the_date(); ?></a></span>

				<?php if (has_category()) { ?>
					<span class="meta-item category"><i class="kegdth-icon-bookmark-empty"></i> <?php the_category(', '); ?></span>
				<?php } ?>

				<?php the_tags('<span class="meta-item entry-tags"><i class="kegdth-icon-cinema"></i>', ', ', ' </span>'); ?>

				<?php if (is_sticky()) {
					printf('<span class="meta-item sticky">%s</span>', esc_html__('Featured', 'akistore'));
				} ?>

			</div><!-- entry-meta -->
		</div><!-- entry-footer -->
	</div><!-- entry-post-header -->
	<figure class="entry-media">
		<?php $images = rwmb_meta('kegd_blogitemslides', 'type=image_advanced&size=medium'); ?>
		<?php if ($images) { ?>

			<div class="blog-gallery">
				<?php foreach ($images as $image) { ?>
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<img src="<?php echo esc_url($image['full_url']); ?>" alt="<?php the_title_attribute(); ?>">
					</a>
				<?php } ?>
			</div>
		<?php } ?>
	</figure>
	<div class="entry-content">
		<div class="kegd-post">
			<?php the_content(); ?>
			<?php wp_link_pages(array('before' => '<div class="kegd-pagination">' . esc_html__('Pages:', 'akistore'), 'after'  => '</div>', 'next_or_number' => 'number')); ?>
		</div>
	</div><!-- entry-content -->
</article><!-- post -->