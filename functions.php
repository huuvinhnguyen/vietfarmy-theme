<?php
/**
 * Gema functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Gema
 */

// Include Featured Image from URL
require_once get_template_directory() . '/assets/php/featured-image-from-url.php';

if ( ! function_exists( 'gema_setup' ) ) :/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */

	function gema_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Gema, use a find and replace
		 * to change 'gema' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'gema', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		//used as featured image for posts on home page and archive pages
		add_image_size( 'gema-super-small', 10, 10, false );
		add_image_size( 'gema-archive-landscape', 432, 9999, false );
		add_image_size( 'gema-archive-portrait', 396, 9999, false );

		//used for the single post featured image
		add_image_size( 'gema-single-landscape', 1120, 9999, false );
		add_image_size( 'gema-single-portrait', 660, 9999, false );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary Menu', 'gema' ),
			'footer'  => esc_html__( 'Footer Menu', 'gema' ),
		) );
		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'gallery',
			'caption',
		) );

		/*
		 * Enable support for custom logo.
		 *
		 *  @since Gema 1.0
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 220,
			'width'       => 710,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array(
				'site-title',
				'site-description-text',
			)
		) );

		if ( ! function_exists( 'the_custom_logo' ) ) {
			//in case we are on a WP version older than 4.5, try to use Jetpack's Site Logo feature
			add_theme_support( 'site-logo', array(
				'size'        => 'gema-site-logo',
				'header-text' => array(
					'site-title',
					'site-description-text',
				)
			) );
		}

		add_image_size( 'gema-site-logo', 710, 220, false );

		/*
		 * Enable support for Post Formats.
		 * See https://developer.wordpress.org/themes/functionality/post-formats/
		 */
		add_theme_support( 'post-formats', array( 'quote', 'gallery', 'video', 'audio', 'image', 'link' ) );

		/*
		 * Add editor styles and fonts
		 */
		add_editor_style( array( 'editor-style.css' ) );
		add_editor_style( array( gema_google_fonts_url() ) );

		/*
		 * Enable support for Visible Edit Shortcuts in the Customizer Preview
		 *
		 * @link https://make.wordpress.org/core/2016/11/10/visible-edit-shortcuts-in-the-customizer-preview/
		 */
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Enable support for the Style Manager Customizer section (via Customify).
		 */
		add_theme_support( 'customizer_style_manager' );
		add_theme_support( 'style_manager_font_palettes' );

		/**
		 * Add support for wide and full aligned blocks
		 */
		add_theme_support( 'align-wide' );
	}
endif; // gema_setup

add_action('after_setup_theme', 'gema_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function gema_content_width() {
	$GLOBALS['content_width'] = apply_filters('gema_content_width', 720);
}

add_action('after_setup_theme', 'gema_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function gema_widgets_init() {
	register_sidebar(array(
		'name' => esc_html__('Sidebar', 'gema'),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h2 class="widget__title">',
		'after_title' => '</h2>',
	));
}

add_action('widgets_init', 'gema_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function gema_scripts() {
	/* The main theme stylesheet */
	if( !is_rtl() ) wp_enqueue_style( 'gema-style', get_stylesheet_uri() );

	/* Default Self-hosted Fonts */
	wp_enqueue_style( 'gema-fonts-butler', gema_butler_font_url() );

	wp_enqueue_style( 'gema-google-fonts', gema_google_fonts_url() );

	wp_enqueue_script('bricklayer', get_template_directory_uri() . '/js/bricklayer.js', array(), '20170421', true);
	wp_enqueue_script('gema-modernizr', get_template_directory_uri() . '/js/modernizr-custom.js', array(), '20160322', true);
	wp_enqueue_script('gema-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20160126', true);

	/* Enqueue the main theme script file */
	wp_enqueue_script( 'gema-scripts', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery', 'bricklayer', 'imagesloaded' ), '1.1.5.1', true );

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}

add_action( 'wp_enqueue_scripts', 'gema_scripts' );

function gema_gutenberg_styles() {
	wp_enqueue_style( 'gema-gutenberg', get_theme_file_uri( '/editor.css' ), false );

	$padding = 60;
	$sidebar = 400;
	$width = pixelgrade_option( 'main_content_content_width' ) - $sidebar - $padding;

	$style = '
	    .edit-post-visual-editor[class][class] .block-editor-block-list__layout .wp-block:not([data-align="wide"]):not([data-align="full"]),
        .edit-post-visual-editor[class][class] .editor-post-title__block {
            max-width: ' . $width . 'px;
        }';
	wp_add_inline_style( 'gema-gutenberg', $style );

}

add_action( 'enqueue_block_editor_assets', 'gema_gutenberg_styles' );


/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images
 *
 * @since Gema 1.0.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function gema_content_image_sizes_attr( $sizes, $size ) {
	$sizes = '(max-width: 600px) 91vw, (max-width: 900px) 600px, (max-width: 1060px) 50vw, (max-width: 1200px) 520px, (max-width: 1400px) 43vw, 600px';
	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'gema_content_image_sizes_attr', 10 , 2 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails
 *
 * @since Gema 1.0.0
 *
 * @param array $attr Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size Registered image size or flat array of height and width dimensions.
 * @return array
 */
function gema_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	switch ($size) {
		case 'gema-single-landscape':
		case 'gema-single-portrait':
			$attr['sizes'] = '(max-width: 900px) 100vw, (max-width: 1260px) 920px, 1060px';
			break;
		case 'gema-portrait':
			$attr['sizes'] = '(max-width: 470px) 100vw, 432px';
			break;
		case 'gema-landscape':
			$attr['sizes'] = '(max-width: 470px) 100vw, 396px';
			break;
		default:
			break;
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'gema_post_thumbnail_sizes_attr', 10 , 3 );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Load Recommended/Required plugins notification
 */
require get_template_directory() . '/inc/required-plugins/required-plugins.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load the Hybrid Media Grabber class
 */
require get_template_directory() . '/inc/hybrid-media-grabber.php';

/* Automagical updates */
function gema_wupdates_check_ML4Gm( $transient ) {
	// First get the theme directory name (the theme slug - unique)
	$slug = basename( get_template_directory() );

	// Nothing to do here if the checked transient entry is empty or if we have already checked
	if ( empty( $transient->checked ) || empty( $transient->checked[ $slug ] ) || ! empty( $transient->response[ $slug ] ) ) {
		return $transient;
	}

	// Let's start gathering data about the theme
	// Then WordPress version
	include( ABSPATH . WPINC . '/version.php' );
	$http_args = array (
		'body' => array(
			'slug' => $slug,
			'url' => home_url( '/' ), //the site's home URL
			'version' => 0,
			'locale' => get_locale(),
			'phpv' => phpversion(),
			'child_theme' => is_child_theme(),
			'data' => null, //no optional data is sent by default
		),
		'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url( '/' )
	);

	// If the theme has been checked for updates before, get the checked version
	if ( isset( $transient->checked[ $slug ] ) && $transient->checked[ $slug ] ) {
		$http_args['body']['version'] = $transient->checked[ $slug ];
	}

	// Use this filter to add optional data to send
	// Make sure you return an associative array - do not encode it in any way
	$optional_data = apply_filters( 'wupdates_call_data_request', $http_args['body']['data'], $slug, $http_args['body']['version'] );

	// Encrypting optional data with private key, just to keep your data a little safer
	// You should not edit the code bellow
	$optional_data = json_encode( $optional_data );
	$w=array();$re="";$s=array();$sa=md5('598f55c897d6388b8b4679bf26c2f0d8fab53aad');
	$l=strlen($sa);$d=$optional_data;$ii=-1;
	while(++$ii<256){$w[$ii]=ord(substr($sa,(($ii%$l)+1),1));$s[$ii]=$ii;} $ii=-1;$j=0;
	while(++$ii<256){$j=($j+$w[$ii]+$s[$ii])%255;$t=$s[$j];$s[$ii]=$s[$j];$s[$j]=$t;}
	$l=strlen($d);$ii=-1;$j=0;$k=0;
	while(++$ii<$l){$j=($j+1)%256;$k=($k+$s[$j])%255;$t=$w[$j];$s[$j]=$s[$k];$s[$k]=$t;
		$x=$s[(($s[$j]+$s[$k])%255)];$re.=chr(ord($d[$ii])^$x);}
	$optional_data=bin2hex($re);

	// Save the encrypted optional data so it can be sent to the updates server
	$http_args['body']['data'] = $optional_data;

	// Check for an available update
	$url = $http_url = set_url_scheme( 'https://wupdates.com/wp-json/wup/v1/themes/check_version/ML4Gm', 'http' );
	if ( $ssl = wp_http_supports( array( 'ssl' ) ) ) {
		$url = set_url_scheme( $url, 'https' );
	}

	$raw_response = wp_remote_post( $url, $http_args );
	if ( $ssl && is_wp_error( $raw_response ) ) {
		$raw_response = wp_remote_post( $http_url, $http_args );
	}
	// We stop in case we haven't received a proper response
	if ( is_wp_error( $raw_response ) || 200 != wp_remote_retrieve_response_code( $raw_response ) ) {
		return $transient;
	}

	$response = (array) json_decode($raw_response['body']);
	if ( ! empty( $response ) ) {
		// You can use this action to show notifications or take other action
		do_action( 'wupdates_before_response', $response, $transient );
		if ( isset( $response['allow_update'] ) && $response['allow_update'] && isset( $response['transient'] ) ) {
			$transient->response[ $slug ] = (array) $response['transient'];
		}
		do_action( 'wupdates_after_response', $response, $transient );
	}

	return $transient;
}
add_filter( 'pre_set_site_transient_update_themes', 'gema_wupdates_check_ML4Gm' );

function gema_wupdates_add_id_ML4Gm( $ids = array() ) {
	// First get the theme directory name (unique)
	$slug = basename( get_template_directory() );

	// Now add the predefined details about this product
	// Do not tamper with these please!!!
	$ids[ $slug ] = array( 'name' => 'Gema', 'slug' => 'gema', 'id' => 'ML4Gm', 'type' => 'theme', 'digest' => '1397d3087860bfd8d462ebbce25090cb', );

	return $ids;
}
add_filter( 'wupdates_gather_ids', 'gema_wupdates_add_id_ML4Gm', 10, 1 );
/**
* Various plugins integrations.
*/
require get_template_directory() . '/inc/integrations.php';

// Tự động lấy ảnh từ URL trong Custom Field làm ảnh sản phẩm
add_action('woocommerce_before_shop_loop_item_title', 'vietfarmy_display_url_image', 10);
add_action('woocommerce_before_single_product_summary', 'vietfarmy_display_url_image', 10);

function vietfarmy_display_url_image() {
    global $post;
    $image_url = get_post_meta($post->ID, 'fifu_image_url', true);
    if ($image_url) {
        echo '<div class="product-image-url"><img src="' . esc_url($image_url) . '" alt="' . get_the_title() . '" style="width:100%; height:auto;"></div>';
        // Ẩn ảnh mặc định của WC nếu cần
        remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
    }
}