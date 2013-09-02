<?php get_header(); ?>
<?php global $paged, $admin_options; ?>
		<!-- Content -->
		<div id="content" class="<?php echo wrap_content_classes(); ?>">

			<!-- Option section -->
			<?php 
				$cat_ID = get_query_var('cat');
				$numposts = $admin_options['category-module-numposts'];
				$exclude_posts  = array();

				if($admin_options['category-module-active']){
					empty($admin_options['category-module-featured'])  ? $featured_cat = 0 : $featured_cat = 1;

					if($admin_options['slider-category-show'] == 0){
						$exclude_posts  	= next_modul_post_container_one($cat_ID, 7 ,'DESC','date', $featured_cat);
					}else{
						$exclude_posts    	= next_category_slider($featured_cat);
					}
					
				}

			?>
			<?php
				$cats = next_getchild_cat($cat_ID);
				
				$category_posts = new WP_Query(array(
					'category__in'   => $cats,
					'post__not_in'   => $exclude_posts,
					'posts_per_page' => $numposts,
					'paged' => $paged,
				));

				wp_reset_postdata();
				$npages   = ceil($category_posts->found_posts / $numposts);
				$i=0;
			?>
			<!-- END Option section -->

			<?php if(!$admin_options['category-module-active']): ?> 
				<div class="page-title"><?php echo get_the_category_by_ID($cat_ID); ?> </div>
			<?php endif; ?>
			<?php foreach($category_posts->posts as $category_post):  ?> 
			<?php
				$post_images = next_get_post_image($category_post->ID);
				$post_formats = get_post_meta($category_post->ID,'_onoff',true);

				if(!empty($post_formats)){
					if($post_formats['postformat'] == 1){
						$data_type = 'data-type='.$post_formats['postformat'];
					}else{
						$data_type = '';
					}
				}else{
					$data_type = '';
				}
			?> 

			<!-- Single banner & code --> 
			<?php if($i==$admin_options['category-module-ads-one-pos-range'] AND $admin_options['category-module-ads-one-active'] == 1): ?>

				<?php if($admin_options['category-module-ads-one-choose'] == 'code'): ?>
					<?php if(!empty($admin_options['category-module-ads-one-code'])): ?>
		        			<div class='ads-code clearfix'>
								<!-- Code here -->
								<?php echo $admin_options['category-module-ads-one-code']; ?>
								<!-- End code here -->
							</div>
		        	<?php endif; ?>
		    	<?php else: ?>
					<div class="ads-1 clearfix">
			        	<a href="<?php echo $admin_options['category-module-ads-one-image-url']; ?>"><img src="<?php echo $admin_options['category-module-ads-one-image']; ?>"></a>
			    	</div>
		    	<?php endif; ?>
			<?php endif; ?>
			<!-- Single banner & code --> 

			<!-- One Post -->
			<div <?php post_class('post-container clearfix'); ?> >
				<a href="<?php echo get_permalink( $category_post->ID ); ?>"><img alt="" src="<?php echo next_timthumb($post_images, 250, 192, 1); ?>"></a>

				<article class="post-content">

					<h1 class="post-title"><a href="<?php echo get_permalink( $category_post->ID ); ?>"><?php echo strip_tags($category_post->post_title); ?></a></h1>

					<p><?php echo excerpt_by_id($category_post->ID,50); ?></p>
				</article>

				<div class="post-meta">
					<div class="comments"><a href="<?php echo get_comments_link($category_post->ID); ?>"><?php echo get_commnet_by_id('no comment', '1 comment', '% comments'); ?></a></div>
					<div class="author"><a href="<?php echo get_author_posts_url($category_post->post_author); ?>"><?php echo the_author_meta( 'user_nicename' , $category_post->post_author); ?></a></div>
					<div class="date"><?php echo mysql2date('d M, Y', $category_post->post_date); ?></div>
				</div>

				<div class="social-media clearfix">
					<ul>
						<li class="twitter">
							<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo get_permalink($category_post->ID); ?>" data-text="<?php echo $category_post->post_title; ?>" data-lang="en">Tweet</a>
							<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
						</li>
						<li class="facebook">
							<div id="fb-root"></div>
							<script>(function(d, s, id) {
							  var js, fjs = d.getElementsByTagName(s)[0];
							  if (d.getElementById(id)) return;
							  js = d.createElement(s); js.id = id;
							  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=428581110508787";
							  fjs.parentNode.insertBefore(js, fjs);
							}(document, 'script', 'facebook-jssdk'));</script>
							<div class="fb-like" data-send="false" data-href="<?php echo get_permalink($category_post->ID); ?>" data-layout="button_count" data-width="450" data-show-faces="true"></div>							
						</li>
						<li class="google_plus" style="width: 60px;">
								<!-- Place this tag where you want the +1 button to render. -->
								<div class="g-plusone" data-size="medium" data-href="<?php echo get_permalink($category_post->ID); ?>"></div>

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
				
				<a href="<?php echo get_permalink( $category_post->ID ); ?>" class="read-more">Read More</a>
			</div>
			<?php $i++; ?>
			<!-- End One Post -->
			<?php endforeach; ?>

			<!-- Pagenation -->
			<div class="pagenation clearfix">
				<?php next_pagination(); ?>
			</div>
			<!-- End Pagenation -->
		</div>
		<!-- End Content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>