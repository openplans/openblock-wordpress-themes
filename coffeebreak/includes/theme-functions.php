<?php

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- Exlude the pages from the slider
- WordPress 3.0 New Features Support
- Custom Post Type - Slides
- Custom Post Type - Info Boxes
- Add custom typograhpy to HEAD
- Featured Slider Settings
- Subscribe & Connect

-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* Exlude the pages from the slider */
/*-----------------------------------------------------------------------------------*/

// Show menu in header.php
function woo_menu( $exclude="" ) {
    // Split the featured pages from the options, and put in an array
    if ( get_option('woo_ex_feat_pages') == "true" ) {
        $menupages = get_option('woo_feat_pages');
        $exclude = $menupages . ',' . $exclude;
    }
    // Split the main content pages from the options, and put in an array
    if ( get_option('woo_ex_main_pages') == "true" ) {
        $menupages = get_option('woo_main_pages');
        $exclude = $menupages . ',' . $exclude;
    }
    
    $pages = wp_list_pages('title_li=&echo=0&depth=3&exclude='.$exclude);
    $pages = preg_replace('%<a ([^>]+)>%U','<a $1><span>', $pages);
    $pages = str_replace('</a>','</span></a>', $pages);
    echo $pages;
}

/*-----------------------------------------------------------------------------------*/
/* WordPress 3.0 New Features Support */
/*-----------------------------------------------------------------------------------*/

if ( function_exists('wp_nav_menu') ) {
	add_theme_support( 'nav-menus' );
	register_nav_menus( array( 'primary-menu' => __( 'Primary Menu' ) ) );
}       

/*-----------------------------------------------------------------------------------*/
/* Custom Post Type - Slides */
/*-----------------------------------------------------------------------------------*/

add_action('init', 'woo_add_slides');
function woo_add_slides() 
{
  $labels = array(
    'name' => _x('Slides', 'post type general name', 'woothemes' ),
    'singular_name' => _x('Slide', 'post type singular name', 'woothemes' ),
    'add_new' => _x('Add New', 'slide', 'woothemes' ),
    'add_new_item' => __('Add New Slide', 'woothemes' ),
    'edit_item' => __('Edit Slide', 'woothemes' ),
    'new_item' => __('New Slide', 'woothemes' ),
    'view_item' => __('View Slide', 'woothemes' ),
    'search_items' => __('Search Slides', 'woothemes' ),
    'not_found' =>  __('No slides found', 'woothemes' ),
    'not_found_in_trash' => __('No slides found in Trash', 'woothemes' ), 
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => false,
    'publicly_queryable' => false,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_icon' => get_template_directory_uri() .'/includes/images/slides.png',
    'menu_position' => null,
    'supports' => array('title','editor',/*'author','thumbnail','excerpt','comments'*/)
  ); 
  register_post_type('slide',$args);
}



/*-----------------------------------------------------------------------------------*/
/* Custom Post Type - Info Boxes */
/*-----------------------------------------------------------------------------------*/

add_action('init', 'woo_add_infoboxes');
function woo_add_infoboxes() 
{
  $labels = array(
    'name' => _x('Mini-Features', 'post type general name', 'woothemes' ),
    'singular_name' => _x('Mini-Feature', 'post type singular name', 'woothemes' ),
    'add_new' => _x('Add New', 'infobox', 'woothemes' ),
    'add_new_item' => __('Add New Mini-Feature', 'woothemes' ),
    'edit_item' => __('Edit Mini-Feature', 'woothemes' ),
    'new_item' => __('New Mini-Feature', 'woothemes' ),
    'view_item' => __('View Mini-Feature', 'woothemes' ),
    'search_items' => __('Search Mini-Features', 'woothemes' ),
    'not_found' =>  __('No Mini-Features found', 'woothemes' ),
    'not_found_in_trash' => __('No Mini-Features found in Trash', 'woothemes' ), 
    'parent_item_colon' => ''
  );
  
  $infobox_rewrite = get_option('woo_infobox_rewrite');
  if(empty($infobox_rewrite)) $infobox_rewrite = 'infobox';
  
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => array('slug'=> $infobox_rewrite),
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_icon' => get_template_directory_uri() .'/includes/images/box.png',
    'menu_position' => null,
    'supports' => array('title','editor',/*'author','thumbnail','excerpt','comments'*/)
  ); 
  register_post_type('infobox',$args);
}

/*-----------------------------------------------------------------------------------*/
/* 3. Add custom typograhpy to HEAD */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('woo_custom_typography')) {
	function woo_custom_typography() {
	
		// Get options
		global $woo_options;
				
		// Reset	
		$output = '';
		
		// Add Text title and tagline if text title option is enabled
		if ( $woo_options['woo_texttitle'] == "true" ) {		
			
			if ( $woo_options['woo_font_site_title'] )
				$output .= '#logo .site-title a {'.woo_generate_font_css($woo_options['woo_font_site_title']).'}' . "\n";	
			if ( $woo_options['woo_font_tagline'] )
				$output .= '#logo .site-description {'.woo_generate_font_css($woo_options['woo_font_tagline']).'}' . "\n";	
		}

		// Output styles
		if ( $output <> "" ) {
		
			// Enable Google Fonts stylesheet in HEAD
			if (function_exists('woo_google_webfonts')) woo_google_webfonts();
			
			$output = "\n<!-- Woo Custom Typography -->\n<style type=\"text/css\">\n" . $output . "</style>\n";
			echo $output;
			
		}
			
	}
} 

if (!function_exists('woo_generate_font_css')) {
	// Returns proper font css output
	function woo_generate_font_css($option, $em = '1') {
		return 'font:'.$option["style"].' '.$option["size"].$option["unit"].'/'.$em.'em '.stripslashes($option["face"]).';color:'.$option["color"].';';
	}
}

add_action('wp_head','woo_custom_typography');			// Add custom typography to HEAD

/*-----------------------------------------------------------------------------------*/
/* Featured Slider Settings */
/*-----------------------------------------------------------------------------------*/

add_filter('wp_head', 'woo_slider_options');
function woo_slider_options() { 
	
	global $woo_options;
	
	$effect = $woo_options[ 'woo_slider_effect' ];
	if ( !$effect )
		$effect = "slide";
	
	?>
	
		<script type="text/javascript">
		<!--//--><![CDATA[//><!--
			jQuery(window).load(function(){
				
				if ( jQuery( '#slides .slide' ).length > 1 && jQuery( '#slides .slide' ).length > 0 ) {		
					jQuery('#slides').slides({
						container: 'slides_container',
						preload: true,
						preloadImage: '<?php echo get_template_directory_uri(); ?>/images/loading.png',
						<?php if ($woo_options[ 'woo_slider_autoheight' ] == "true"): ?>			
						autoHeight: true,
						<?php endif; ?>
						effect: '<?php echo $effect; ?>',
						<?php if ($woo_options[ 'woo_slider_hover' ] == "true"): ?>			
						hoverPause: true,
						<?php endif; ?>
						<?php if ($woo_options[ 'woo_slider_auto' ] == "true"): ?>
						play: <?php echo $woo_options[ 'woo_slider_interval' ] *1000; ?>,
						<?php endif; ?>			
						slideSpeed: <?php echo $woo_options[ 'woo_slider_speed' ] *1000; ?>,
						fadeSpeed: <?php echo $woo_options[ 'woo_slider_speed' ] *1000; ?>,
						crossfade: false,
						generateNextPrev: false,
						generatePagination: false
					});
				} else {
					jQuery( '#slides .slides_container' ).fadeIn();
				}
				
			});
		//-->!]]>
		</script>
				
	<?php 

}

/*-----------------------------------------------------------------------------------*/
/* Subscribe / Connect */
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'woo_subscribe_connect' ) ) {
	function woo_subscribe_connect($widget = 'false', $title = '', $form = '', $social = '') {

		global $woo_options;

		// Setup title
		if ( $widget != 'true' )
			$title = $woo_options[ 'woo_connect_title' ];

		// Setup related post (not in widget)
		$related_posts = '';
		if ( $woo_options[ 'woo_connect_related' ] == "true" AND $widget != "true" )
			$related_posts = do_shortcode( '[related_posts limit="5"]' );

?>
	<?php if ( $woo_options[ 'woo_connect' ] == "true" OR $widget == 'true' ) : ?>
	<div id="connect">
		<h3><?php if ( $title ) echo apply_filters( 'widget_title', $title ); else _e('Subscribe','woothemes'); ?></h3>

		<div <?php if ( $related_posts != '' ) echo 'class="col-left"'; ?>>
			<p><?php if ($woo_options[ 'woo_connect_content' ] != '') echo stripslashes($woo_options[ 'woo_connect_content' ]); else _e( 'Subscribe to our e-mail newsletter to receive updates.', 'woothemes' ); ?></p>

			<?php if ( $woo_options[ 'woo_connect_newsletter_id' ] != "" AND $form != 'on' ) : ?>
			<form class="newsletter-form<?php if ( $related_posts == '' ) echo ' fl'; ?>" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open( 'http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $woo_options[ 'woo_connect_newsletter_id' ]; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520' );return true">
				<input class="email" type="text" name="email" value="<?php esc_attr_e( 'E-mail', 'woothemes' ); ?>" onfocus="if (this.value == '<?php _e( 'E-mail', 'woothemes' ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'E-mail', 'woothemes' ); ?>';}" />
				<input type="hidden" value="<?php echo $woo_options[ 'woo_connect_newsletter_id' ]; ?>" name="uri"/>
				<input type="hidden" value="<?php bloginfo( 'name' ); ?>" name="title"/>
				<input type="hidden" name="loc" value="en_US"/>
				<input class="submit" type="submit" name="submit" value="<?php _e( 'Submit', 'woothemes' ); ?>" />
			</form>
			<?php endif; ?>

			<?php if ( $woo_options['woo_connect_mailchimp_list_url'] != "" AND $form != 'on' AND $woo_options['woo_connect_newsletter_id'] == "" ) : ?>
			<!-- Begin MailChimp Signup Form -->
			<div id="mc_embed_signup">
				<form class="newsletter-form<?php if ( $related_posts == '' ) echo ' fl'; ?>" action="<?php echo $woo_options['woo_connect_mailchimp_list_url']; ?>" method="post" target="popupwindow" onsubmit="window.open('<?php echo $woo_options['woo_connect_mailchimp_list_url']; ?>', 'popupwindow', 'scrollbars=yes,width=650,height=520');return true">
					<input type="text" name="EMAIL" class="required email" value="<?php _e('E-mail','woothemes'); ?>"  id="mce-EMAIL" onfocus="if (this.value == '<?php _e('E-mail','woothemes'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('E-mail','woothemes'); ?>';}">
					<input type="submit" value="<?php _e('Submit', 'woothemes'); ?>" name="subscribe" id="mc-embedded-subscribe" class="btn submit button">
				</form>
			</div>
			<!--End mc_embed_signup-->
			<?php endif; ?>

			<?php if ( $social != 'on' ) : ?>
			<div class="social<?php if ( $related_posts == '' AND $woo_options[ 'woo_connect_newsletter_id' ] != "" ) echo ' fr'; ?>">
		   		<?php if ( $woo_options[ 'woo_connect_rss' ] == "true" ) { ?>
		   		<a href="<?php if ( $woo_options['woo_feed_url'] ) { echo esc_url( $woo_options['woo_feed_url'] ); } else { echo get_bloginfo_rss('rss2_url'); } ?>" class="subscribe"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-rss.png" title="<?php _e('Subscribe to our RSS feed', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_twitter' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_twitter'] ); ?>" class="twitter"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-twitter.png" title="<?php _e('Follow us on Twitter', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_facebook' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_facebook'] ); ?>" class="facebook"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-facebook.png" title="<?php _e('Connect on Facebook', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_youtube' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_youtube'] ); ?>" class="youtube"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-youtube.png" title="<?php _e('Watch on YouTube', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_flickr' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_flickr'] ); ?>" class="flickr"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-flickr.png" title="<?php _e('See photos on Flickr', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_linkedin' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_linkedin'] ); ?>" class="linkedin"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-linkedin.png" title="<?php _e('Connect on LinkedIn', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_delicious' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_delicious'] ); ?>" class="delicious"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-delicious.png" title="<?php _e('Discover on Delicious', 'woothemes'); ?>" alt=""/></a>

		   		<?php } if ( $woo_options[ 'woo_connect_googleplus' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $woo_options['woo_connect_googleplus'] ); ?>" class="googleplus"><img src="<?php echo get_template_directory_uri(); ?>/images/ico-social-googleplus.png" title="<?php _e('View Google+ profile', 'woothemes'); ?>" alt=""/></a>

				<?php } ?>
			</div>
			<?php endif; ?>

		</div><!-- col-left -->

		<?php if ( $woo_options[ 'woo_connect_related' ] == "true" AND $related_posts != '' ) : ?>
		<div class="related-posts col-right">
			<h4><?php _e( 'Related Posts:', 'woothemes' ); ?></h4>
			<?php echo $related_posts; ?>
		</div><!-- col-right -->
		<?php wp_reset_query(); endif; ?>

        <div class="fix"></div>
	</div>
	<?php endif; ?>
<?php
	}
}

    
?>