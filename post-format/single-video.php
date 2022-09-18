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
	<figure class="entry-media embed-responsive embed-responsive-16by9">
		<?php
		if (get_post_meta(get_the_ID(), 'kegd_blog_video_type', true) == 'vimeo') {
			echo '<iframe src="//player.vimeo.com/video/' . get_post_meta(get_the_ID(), 'kegd_blog_video_embed', true) . '?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" height="443" allowFullScreen></iframe>';
		} else if (get_post_meta(get_the_ID(), 'kegd_blog_video_type', true) == 'youtube') {
			echo '<iframe height="450" src="//www.youtube.com/embed/' . get_post_meta(get_the_ID(), 'kegd_blog_video_embed', true) . '?rel=0&showinfo=0&modestbranding=1&hd=1&autohide=1&color=white" allowfullscreen></iframe>';
		} else {
			echo ' ' . get_post_meta(get_the_ID(), 'kegd_blog_video_embed', true) . ' ';
		}
		?>
	</figure>
	<div class="entry-content">
		<div class="kegd-post">
			<?php the_content(); ?>
			<?php wp_link_pages(array('before' => '<div class="kegd-pagination">' . esc_html__('Pages:', 'akistore'), 'after'  => '</div>', 'next_or_number' => 'number')); ?>
		</div>
	</div><!-- entry-content -->
</article><!-- post -->