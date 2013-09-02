<?php get_header(); ?>
<?php global $admin_options; ?> 

		<!-- Slider --> 
			<?php if($admin_options['slider-fullwidth'] == 1 && $admin_options['choose-slider'] == 1){ ?>
			
				<?php if($admin_options['show-slider']): ?>
					<?php next_modul_slider($admin_options['featured-slider'],$admin_options['slider-category'],$admin_options['slider-showposts'], $admin_options['choose-slider']); ?>
			    <?php endif; ?>
	    	
	    	<?php }elseif($admin_options['choose-slider'] == 2){ ?>

				<?php if($admin_options['show-slider']): ?>
					<?php next_modul_slider($admin_options['featured-slider'],$admin_options['slider-category'],$admin_options['slider-showposts'], $admin_options['choose-slider']); ?>
			    <?php endif; ?>
		    
	    <?php } ?>
		<!-- End Slider -->
		<!-- Content -->
		<div id="content" class="clearfix">

			<?php if($admin_options['slider-fullwidth'] == 0 && $admin_options['choose-slider'] == 1){ ?>
				<!-- Slider -->
				<?php if($admin_options['show-slider']): ?>
					<?php next_modul_slider($admin_options['featured-slider'],$admin_options['slider-category'],$admin_options['slider-showposts'], $admin_options['choose-slider']); ?>
		        <?php endif; ?>
		        <!-- End Slider -->
	        <?php } ?>

			<?php front_page_draging(); ?> 

		</div>
		<!-- End Content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>