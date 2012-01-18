<?php
if (!is_admin()) add_action( 'wp_print_scripts', 'woothemes_add_javascript' );
function woothemes_add_javascript( ) {
	if (is_home()) {
		wp_enqueue_script( 'slidesJS', get_bloginfo('template_directory') . '/includes/js/slides.min.jquery.js', array( 'jquery' ) );
	}
	wp_enqueue_script( 'superfish', get_bloginfo('template_directory').'/includes/js/superfish.js', array( 'jquery' ) );	
	wp_enqueue_script( 'general', get_bloginfo('template_directory').'/includes/js/general.js', array( 'jquery' ) );
}
?>