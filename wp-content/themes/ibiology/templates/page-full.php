<?php 
 
/**
*Template Name: Full Width
**/
 
add_action('full_width_content', 'do_full_width_content' );
 
add_filter('body_class', 'add_full_width_body_class' );
function add_full_width_body_class($classes) {
 
	$classes[] = 'full-width-template';
 
	return $classes;
 
}
 
add_theme_support( 'genesis-structural-wraps', array( 'header', 'footer-widgets', 'footer', 'nav', 'subnav', ) );
 
function do_full_width_content() {
 
?>
 
<main>
 
<?php // check if the flexible content field has rows of data ?>
 
<?php if( have_rows('flexible_content') ): ?>
 
 	<?php // loop through the rows of data ?>
    <?php while ( have_rows('flexible_content') ) : the_row(); ?>
 
		<?php // check current row layout ?>
        <?php if( get_row_layout() == 'hero' ): ?>
			<section>
				<div class="hero" style="background-image:url(<?php the_sub_field('hero_image') ?>)">
					<div class="cta_container">
						<div class="cta_content">
							<div class="cta_content wrap">
								<?php the_sub_field('hero_text'); ?>
								
									<?php $selected = get_sub_field('display_cta_button'); ?>
 
									<?php if( in_array( true , [$selected]) ) {	?>
 
										<a class="button" href="<?php the_sub_field('hero_cta_button_url') ?>"><?php the_sub_field('hero_button_text'); ?></a>
										
										<?php } else { ?>
 
										<!--no content--> <?php } ?>
							</div>
						</div>
					</div>	
				</div>
			</section>	 
        <?php endif; ?>
 
		<?php // check current row layout ?>
        <?php if( get_row_layout() == 'text-image' ): ?>
			<section>
				
				<div class="text-image">
					<div class="text-left <?php the_sub_field('css_class')?>"><?php the_sub_field('left_text'); ?></div>
					<div class="image-right"><img src="<?php the_sub_field('right_image') ?>"/></div>
				</div>
 
			</section>	 
        <?php endif; ?>
 
		<?php // check current row layout ?>
        <?php if( get_row_layout() == 'image-text' ): ?>
			<section>
				
				<div class="image-text">
					<div class="image-left"><img src="<?php the_sub_field('left_image') ?>"/></div>
					<div class="text-right <?php the_sub_field('css_class')?>"><?php the_sub_field('right_text'); ?></div>
				</div>
 
			</section>	 
        <?php endif; ?>
 
 
    <?php endwhile; ?>
 
<?php else : ?>
 
    <?php // no layouts found ?>
 
<?php endif; ?>
 
 
 
</main>
 
<?php
 
}
 
 
 
get_header();
 
do_action('full_width_content');
 
get_footer();