<!doctype html>
<html xmlns:wb="http://open.weibo.com/wb" <?php language_attributes(); ?>>
<head>
<meta property="wb:webmaster" content="f059e33b6c0d6037" />

	<?php global $admin_options ?>
	<?php global $post, $page, $paged; ?>

	<?php if(is_search() || is_category() || is_404()): ?>
		<?php $image_seo[0] = ''; ?>
	<?php else: ?>
		<?php $image_seo = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail');?>
	<?php endif; ?>

	<?php if(is_single()){
		$posttype = get_post_meta($post->ID, '_onoff', true);
		if(@$posttype['onoff'] == 1){
			$postformat = get_post_meta($post->ID, '_onoff', true);
			$posttype   = $postformat['postformat'];
		}else{
			$posttype   = 'article';
		}
	}else{
			$posttype = 'article';
	}
	//SEO ACTIVATE
		if($admin_options['seo-activate']){
			if(is_single()){
				next_seo($posttype, $post->post_title, get_permalink($post->ID), get_bloginfo('name'), $post->post_content,$image_seo[0]);
			}else{
				next_seo($posttype, get_the_title(), get_permalink(), get_bloginfo('name'), get_bloginfo('description'),$image_seo[0]);
			}
		}else{
			?>
			<title><?php
			wp_title( '|', true, 'right' );
			// Add the blog name.
			bloginfo( 'name' );
			// Add the blog description for the home/front page.
			$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description && ( is_home() || is_front_page() ) )
				echo " | $site_description";

			// Add a page number if necessary:
			if ( $paged >= 2 || $page >= 2 )
				echo ' | ' . sprintf( __( 'Page %s', TEXTDOMAIN ), max( $paged, $page ) );
			?></title>

			<?php
			if($admin_options['seo-og']){
				if(is_category() || is_tag() || is_archive() || is_search() || is_home() || is_front_page()):

				else:
				?>
				<!-- Open Graph Tags -->
				<meta property="og:type" content="<?php echo $type; ?>" />
				<meta property="og:title" content="<?php echo $title; ?>" />
				<meta property="og:url" content="<?php echo $url; ?>" />
				<meta property="og:site_name" content="<?php echo $site_name; ?>" />
				<meta property="og:description" content="<?php echo substr(strip_tags($description), 0, 150); ?>" />
				<?php if(!empty($image)): ?>
				<meta property="og:image" content="<?php echo $image; ?>" />
				<?php endif; ?>
				<!-- End Open Graph Tags -->
				<?php
				endif;
			}
		}
		//END SEO ACTIVATE
	?>

	<meta charset="<?php bloginfo('charset'); ?>">

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Favicon -->
	<?php favicon();?>
	<!-- Favicon -->

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/responsive.css" type="text/css" media="screen">

	<?php
		if(!empty($admin_options['custome-css'])){
			?>
			<style type="text/css">
				<?php echo $admin_options['custome-css']; ?>
			</style>
			<?php
		} 
	?>
	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	<?php wp_head(); ?>

	<?php if(is_home() || is_front_page()): ?>
		<?php if($admin_options['slider-fullwidth'] == 0 && $admin_options['choose-slider'] == 1){ ?>
			<!-- If is small version -->
			<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/slider.css">
			<!-- If is small version -->
		<?php } ?>
	<?php endif; ?>

	<?php if(is_category()){ ?>
			<!-- If is small version -->
			<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/slider.css">
			<!-- If is small version -->
	<?php } ?>
	<?php if(is_page() || is_single()): ?>
		<style type="text/css">
			.page-container .pagenation ul{
				margin: 0;
				padding: 0;
			}
			.page-container .pagenation ul li{
				list-style: none;
			}
			.page-container .pagenation ul li a{
				padding: 3px 12px;
			}
		</style>
	<?php endif; ?>

	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
<?php if (is_home()) { ?> 
<link rel="stylesheet" href="http://brixd.com/share/css_for_special_page/index_book_share.css" type="text/css" media="screen" />
<?php } ?> 

<?php if (is_single('745')) { ?> 
<link rel="stylesheet" href="http://brixd.com/share/css_for_special_page/book_share.css" type="text/css" media="screen" />
<?php } ?> 
<style>
.page-container p{
	font-size:14px;
}
@media (max-width: 480px){
	.page-container p{
	font-size:16px;
}
}
</style>
<script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=4291649258" type="text/javascript" charset="utf-8"></script>
<script>

	WB2.anyWhere(function(W){
    W.widget.followButton({
        'nick_name': 'MoonMonster',  //用户昵称
        'id': "wb_follow_btn",
        'show_head' : true, //是否显示头像
        'show_name' : true, //是否显示名称
        'show_cancel': true //是否显示取消关注按钮
    });
});

</script>
</head>
<body <?php body_class(); ?>>
	<!-- Container <--></-->
	<div id="container">
		<!-- Header -->
		<header class="clearfix">

			<!-- Logo -->
			<div id="logo">
				<?php if($admin_options['logo-type'] == 1): ?>
					<h1 class="name"><a href="<?php echo home_url(); ?>"><?php echo $admin_options['logo-text']; ?></a></h1>
					<p class="tagline"><?php echo $admin_options['logo-description']; ?></p>
				<?php else:  ?>
					<a href="<?php echo home_url(); ?>"><img alt="" src="<?php echo next_get_logo(); ?>"></a>
				<?php endif; ?>
			</div>
			<!-- End Logo -->

			<!-- Search Form -->
		    <?php if($admin_options['search-active']): ?>
				<?php get_search_form(true); ?>
			<?php  endif; ?>
		    <!-- End Search Form -->

			<!-- Header ADS -->
			<?php next_header_ads(); ?>
			<!-- End Header ADS -->

		    <!-- Navigation-->
		    <nav class="navigation clearfix">
				<ul class="sf-menu">
					<li><a <?php if(is_home() || is_front_page()){ echo 'class="active"';} ?> href="<?php echo home_url(); ?>"><?php _e('首页',TEXTDOMAIN) ?></a></li>
					<?php $args = array(
							'theme_location' => 'main-menu',
							'echo'=> true,
							'container'=> '',
							'menu_class'      => 'sf-menu',
							'fallback_cb'     => 'next_list_categories',
							'depth'=> 5,
							'items_wrap' => '%3$s',
							'walker' => new nextmagazineCustomeMenu()
						); 
						wp_nav_menu($args);
					?>
				</ul>
			</nav>
		   <!-- Navigation -->

		    <!-- Sub Menu -->
		    <div id="sub-menu" class="clearfix">
		    	<ul>
		    	<?php $args_submenu = array(
					'theme_location' => 'sub-menu',
					'container' 	 => '',
					'menu_class' 	 => '',
					'fallback_cb'    => 'next_list_pages',
					'items_wrap' 	 => '%3$s',
					'echo'			 => true,
					'depth'			 => 1
				); ?>
				<?php wp_nav_menu($args_submenu); ?>
				</ul>
		    </div>
		    <!-- Sub Menu -->
		</header>
		<!-- End Header -->