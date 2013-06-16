<?php get_header(); ?>
<div id="content" class="<?php echo wrap_content_classes(); ?>">
	<?php if(have_posts()): the_post(); ?>   
	<div class="page-title"><?php the_title(); ?></div>
	<div class="page-container clearfix">
		<?php the_content(__('<p>Read the rest of this page &raquo;</p>', TEXTDOMAIN)); ?>

		<?php comments_template(); ?> 
	</div>
	<?php endif; ?> 
	<div class="pagenation clearfix" style="margin-top:20px;">
		<?php next_wp_link_pages('before=<ul>&after=</ul>'); ?>
	</div> 
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>