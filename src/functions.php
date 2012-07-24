<?php
/**
 * Twenty Twelve functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, twentytwelve_setup(), sets up the theme by registering support
 * for various features in WordPress, such as a custom background and a navigation menu.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

/**
 * Enqueue scripts and styles for front-end.
 *
 * @since Twenty Twelve 1.0
 */
function jorbintwelve_scripts_styles() {
	global $twentytwelve_options;

	/**
	 * Add JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/**
	 * JavaScript for handling navigation menus and the resized
	 * styles for small screen sizes.
	 */
	wp_enqueue_script( 'twentytwelve-navigation', get_template_directory_uri() . '/js/theme.js', array( 'jquery' ), '20130320', true );
	wp_enqueue_script( 'jorbten', get_stylesheet_directory_uri() . '/js/app.js', array( 'jquery' ), '20130320', true );

    wp_dequeue_style( 'twentytwelve-fonts', 'http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700' );
    wp_enqueue_style( 'jorbintwelve-fonts', 'http://fonts.googleapis.com/css?family=Lato:300,700,300italic|Sorts+Mill+Goudy:400italic');

	/**
	 * Load our main CSS file.
	 */
	wp_enqueue_style( 'twentytwelve-style', get_stylesheet_uri() );

	/**
	 * Load HTML5 shiv for older IE version support for HTML5 elements.
	 * Ideally, should load after main CSS file.
	 *
	 * See html5.js link in header.php.
	 *
	 * TODO depends on IE dependency being in core for JS enqueuing
	 * before we can move here properly: see http://core.trac.wordpress.org/ticket/16024
	 */
}
add_action( 'wp_enqueue_scripts', 'jorbintwelve_scripts_styles', 12 );

function jorbin_register_haiku(){
		$labels = array(
			'name' => _x('Haikus', 'post type general name'),
			'singular_name' => _x('Haiku', 'post type singular name'),
			'add_new' => _x('Add New', 'haiku'),
			'add_new_item' => __('Add New Haiku'),
			'edit_item' => __('Edit Haiku'),
			'new_item' => __('New Haiku'),
			'view_item' => __('View Haiku'),
			'search_items' => __('Search Haiku'),
			'not_found' =>  __('No haikus found'),
			'not_found_in_trash' => __('No haikus found in Trash'),
			'parent_item_colon' => ''
		);
		$args = array(
			'labels' => $labels,
			'public' => true,
			'show_ui' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => false,
			'query_var' => 'haiku',
			'can_export' => true,
			'show_in_nav_menus' => false,
		);
			register_post_type('aj_haiku',$args);

}
add_action('init', 'jorbin_register_haiku');
add_action('after_setup_theme', 'jorbin_after_theme_setup', 11);

function jorbin_after_theme_setup(){

    set_post_thumbnail_size( 200, 600 );
    add_image_size( 'single-post-thumbnail', 800, 9999 ); // Permalink thumbnail size


}
/*  Add a copywrite line in the footer */
function jorbin_twentyten_credits(){
    echo "&copy; " . date('Y') . "&nbsp; Aaron Jorbin";
}

add_action ('twentytwelve_credits', 'jorbin_twentyten_credits');


/*  Biography shortcode */
function jorbin_short_bio(){
    $user_info = get_userdata(1);
    $gravatar_email = $user_info->user_email;


    return  get_avatar($gravatar_email, '84') ."Developer and teacher, Aaron speaks around the country on Open Source Software Development.<a href='/biography'>Read More About Aaron</a>";
    // Aaron is currently the Senior Interactivity Engineer for Privia Health where he is focused on building great user experiences. Formerly at AddThis where Aaron worked on publisher products used by fourteen million domains and one point three billion users every month. Aaron has contributed to WordPress and other open source projects including leading development of Phetric and slidedown.js. A graduate of Northern Michigan University, Aaron continues to be involved in higher education by serving as a simulation director for an international education conference. When not in front of his computer, Aaron can often be found with a glass of whisky or beer in his hand discussing international politics, sports and open source software.";
}

add_shortcode('jorbin_short_bio', 'jorbin_short_bio');
/* exclude some categories from  the front page and feed */
function jorbin_exclude_category($wp_query) {
    $wp_query;
    if ( $wp_query->is_feed || $wp_query->is_home ) {
        $query->set('cat', '-9,-2,-8,-25,-13');
    }
    if ( current_user_can('edit_theme_options') )
        var_dump($wp_query);
    return $wp_query;
}

add_filter('get_posts', 'jorbin_exclude_category');

function jorbin_add_this($content){

    if( is_single() || is_page() )

    return $content . '<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<!-- AddThis Button END -->
';

    else

    return $content;

}

add_filter ('the_content', 'jorbin_add_this');

add_filter ('addthis_config_js_var', 'jorbin_at_js_vars');
function jorbin_at_js_vars($vars){
    $vars['data_track_addressbar'] = true;
    return $vars;
}


