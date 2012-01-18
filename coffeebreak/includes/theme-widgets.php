<?php
/*-----------------------------------------------------------------------------------*/
/* Load the widgets, with support for overriding the widget via a child theme.
/*-----------------------------------------------------------------------------------*/

$widgets = array(
				'includes/widgets/widget-woo-ad.php', 
				'includes/widgets/widget-woo-campaignmonitor.php', 
				'includes/widgets/widget-woo-feedburner.php', 
				'includes/widgets/widget-woo-flickr.php', 
				'includes/widgets/widget-woo-news.php', 
				'includes/widgets/widget-woo-search.php', 
				'includes/widgets/widget-woo-twitter.php',
				'includes/widgets/widget-woo-subscribe.php'
				);

// Allow child themes/plugins to add widgets to be loaded.
$widgets = apply_filters( 'woo_widgets', $widgets );
				
	foreach ( $widgets as $w ) {
		locate_template( $w, true );
	}

/*---------------------------------------------------------------------------------*/
/* Deregister Default Widgets */
/*---------------------------------------------------------------------------------*/
if (!function_exists( 'woo_deregister_widgets')) {
	function woo_deregister_widgets(){
	    unregister_widget( 'WP_Widget_Search' );         
	}
}
add_action( 'widgets_init', 'woo_deregister_widgets' );  


?>