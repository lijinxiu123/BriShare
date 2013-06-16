<?php get_header(); ?>
<div id="content" class="<?php echo wrap_content_classes(); ?>">
	<div class="page-container clearfix">

		<?php if(have_posts()): the_post(); ?>

		<?php global $post, $admin_options; ?>
		<?php $slideshows = (array)get_post_meta($post->ID,'_slideshow'); ?>
		<?php $videourl   = get_post_meta($post->ID, '_video_url',true); ?>
		<?php $video_det  = next_grab_video_details($videourl); ?>

		<h1 class="post-title"><?php the_title();?></h1>

		<div class="post-meta">
			<div class="comments"><a href="<?php comments_link(); ?>"><?php comments_number( 'no comment', 'one comment', '% comments' ); ?></a></div>
			<div class="author"><a href="<?php the_author_link(); ?>"><?php the_author(); ?></a></div>
			<div class="date"><?php the_date(); ?></div>
		</div>

		<?php if($video_det): ?>
		<?php echo $video_det['embed_source']; ?>
		<?php elseif(!empty($slideshows)): ?>
				<div class="gallery">
					<ul class="slides">
						<?php foreach($slideshows as $slideshow): ?>
						<?php $image_url = wp_get_attachment_image_src($slideshow, 'large'); ?>
							<!-- Fancybox -->
							<?php if($admin_options['single-facybox']): ?>
							<?php $fullimage = wp_get_attachment_url($slideshow); ?>
							<?php $alt = get_post_meta($slideshow, '_wp_attachment_image_alt', true); ?>
								<li><a title="<?php echo $alt; ?>" rel="slideshow" class="fancybox" href="<?php echo $fullimage; ?>"><img src="<?php echo next_timthumb( $image_url[0] , 590, 315); ?>"></a></li>
							<?php else: ?>
								<li><img src="<?php echo next_timthumb( $image_url[0] , 603,312); ?>"></li>
							<?php endif; ?>
							<!-- Fancybox -->
						<?php endforeach; ?>
					</ul>
				</div>
		<?php elseif(has_post_thumbnail()): ?>
			<!-- One Image -->
				<div class="post-image">
					<?php
						$thumburl = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
					?>
					<!-- Fancybox -->
						<?php $fullimage = wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>
						<a href="<?php echo $fullimage; ?>" title="<?php echo get_the_title(); ?>"><img src="<?php echo next_timthumb($thumburl[0],600, 315); ?>"></a>
					<!-- Fancybox -->
				</div>
			<!-- End One Image -->
		<?php endif; ?>

		<?php the_content(); ?>
		<div class="pagenation clearfix" style="margin-top:20px;">
			<?php next_wp_link_pages('before=<ul>&after=</ul>'); ?>
		</div> 
		<!-- Social Media -->
		<div class="social-media clearfix">
				<ul>
					<li class="twitter">
						<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-text="<?php the_title() ?>">Tweet</a>
						<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
					</li>
					<li class="facebook">
						<div id="fb-root"></div>
						<script>(function(d, s, id) {
						  var js, fjs = d.getElementsByTagName(s)[0];
						  if (d.getElementById(id)) return;
						  js = d.createElement(s); js.id = id;
						  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
						  fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));
						</script>
						<div class="fb-like" data-href="<?php the_permalink(); ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="true"></div>
					</li>
					<li class="google_plus">
						<!-- Place this tag where you want the +1 button to render. -->
						<div class="g-plusone" data-size="medium"></div>
						<!-- Place this tag after the last +1 button tag. -->
						<script type="text/javascript">
						  (function() {
							var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
							po.src = 'https://apis.google.com/js/plusone.js';
							var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
						  })();
						</script>
					</li>
				</ul>
		</div>
			<!-- Social Media -->

			<div class="line clearfix"></div>

			<!-- Comments -->
			<?php comments_template(); ?> 
			<!-- End Comments -->
			<?php endif; ?> 
	</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>