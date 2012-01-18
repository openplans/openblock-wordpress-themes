<?php
	global $woo_options, $wp_query;
	query_posts( 'suppress_filters=0&post_type=slide&order=ASC&orderby=date' );
	
	if ( have_posts() ) { $count = 0; 
?>


<div id="featured">
    <div id="slides">
        <?php if (  $wp_query->found_posts > 1 ) { ?>
        <ul class="nav-buttons">
                <li id="p"><a href="#" class="prev"><img src="<?php echo get_template_directory_uri(); ?>/images/slider-arrow-left.png" alt="&lt;" /></a></li>
                <li id="n"><a href="#" class="next"><img src="<?php echo get_template_directory_uri(); ?>/images/slider-arrow-right.png" alt="&gt;" /></a></li>
        </ul>    
		<?php } ?>
         <div class="slides_container" <?php if ( $woo_options['woo_slider_height'] &&  $woo_options['woo_slider_autoheight'] != 'true' ) echo 'style="height:' . $woo_options['woo_slider_height'] . 'px;"'; ?>>  
           
	        <?php while ( have_posts() ) { the_post(); $count++; ?>		        					
              
			    <div id="slide-<?php echo $count; ?>" class="slide">                

				    <?php if ( get_post_meta( $post->ID, 'slide_image', true ) ) { ?>
                    <img src="<?php echo get_post_meta( $post->ID, 'slide_image', true ); ?>" alt="" class="alignright slider-image" />				
                    <?php } ?>			       
                    <?php the_content(); ?>

                </div>     

			<?php } ?>
			
        </div><!-- .container ends -->   
            
    </div><!-- .content ends -->
</div><!-- #featured ends -->

<?php } ?>
