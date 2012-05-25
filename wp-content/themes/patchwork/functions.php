<?php
/**
 * Patchwork functions and definitions
 *
 * @package Patchwork
 * @since Patchwork 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Patchwork 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'patchwork_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Patchwork 1.0
 */
function patchwork_setup() {

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	//require( get_template_directory() . '/inc/tweaks.php' );

	/**
	 * Custom Theme Options
	 */
	require( get_template_directory() . '/inc/theme-options/theme-options.php' );

	/**
	 * WordPress.com-specific functions and definitions
	 */
	//require( get_template_directory() . '/inc/wpcom.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Patchwork, use a find and replace
	 * to change 'patchwork' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'patchwork', get_template_directory() . '/languages' );

	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'patchwork' ),
	) );
	
	/**
	 * Add support for custom backgrounds
	 */
	add_custom_background();
}
endif; // patchwork_setup

add_action( 'after_setup_theme', 'patchwork_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since Patchwork 1.0
 */
function patchwork_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', 'patchwork' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );
}
add_action( 'widgets_init', 'patchwork_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function patchwork_scripts() {
	global $post;
	
	if ( ! is_admin() ) {
		
		$options = get_option('patchwork_theme_options');
		
		$patchwork_themestyle = $options['theme_style'];
		$patchwork_customcss = $options['custom_css'];
		
		if ( file_exists( get_template_directory() . '/css/sunny.css' ) && 'sunny' == $patchwork_themestyle ) {
			wp_register_style( 'patchwork_sunny', get_template_directory_uri() . '/css/sunny.css' );
			wp_enqueue_style( 'patchwork_sunny' );
		} else if ( file_exists( get_template_directory() . '/css/babycakes.css' ) && 'babycakes' == $patchwork_themestyle ) {
			wp_register_style( 'patchwork_babycakes', get_template_directory_uri() . '/css/babycakes.css' );
			wp_enqueue_style( 'patchwork_babycakes' );
		} else if ( file_exists( get_template_directory() . '/css/maude.css' ) && 'maude' == $patchwork_themestyle ) {
			wp_register_style( 'patchwork_maude', get_template_directory_uri() . '/css/maude.css' );
			wp_enqueue_style( 'patchwork_maude' );
		}
		
		if ( $patchwork_customcss ) { 
			echo "<style type='text/css'>";
			echo $patchwork_customcss; 
			echo "</style>"; 
		}
				
	}

	wp_enqueue_style( 'style', get_stylesheet_uri() );

	wp_enqueue_script( 'jquery' );

	wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', 'jquery', '20120206', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image( $post->ID ) ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
	
	wp_register_style('googleFonts', 'http://fonts.googleapis.com/css?family=Sail|Cabin');
	
	wp_enqueue_style( 'googleFonts');
	
}
add_action( 'wp_enqueue_scripts', 'patchwork_scripts' );

/**
 * Implement the Custom Header feature
 */
require( get_template_directory() . '/inc/custom-header.php' );

?>
