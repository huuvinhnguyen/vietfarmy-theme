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

// ============================================================
// SẢN PHẨM TỪ URL — Dùng chung key: _vietfarmy_product_image_url
// ============================================================

// 1. BỎ ảnh mặc định của WooCommerce (cả shop lẫn single)
// -----------------------------------------------
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

// 2. Thêm Meta Box nhập URL vào trang Edit Product
// -----------------------------------------------
add_action('add_meta_boxes', 'vietfarmy_add_url_meta_box');

function vietfarmy_add_url_meta_box() {
    add_meta_box(
        'vietfarmy_url_image',
        'Ảnh sản phẩm từ URL',
        'vietfarmy_url_meta_box_callback',
        'product',
        'side',
        'low'
    );
}

function vietfarmy_url_meta_box_callback($post) {
    wp_nonce_field('vietfarmy_url_save', 'vietfarmy_url_nonce');
    $url = get_post_meta($post->ID, '_vietfarmy_product_image_url', true);
    ?>
    <style>
        .vietfarmy-url-box { background: #fff; padding: 4px; }
        .vietfarmy-url-box .url-input-row { display: flex; gap: 6px; align-items: center; }
        .vietfarmy-url-box input[type="url"] { flex: 1; padding: 8px 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 13px; }
        .vietfarmy-url-box input[type="url"]:focus { border-color: #2271b1; box-shadow: 0 0 0 1px #2271b1; outline: none; }
        .vietfarmy-url-box .btn-save-url {
            background: #2271b1; color: #fff; border: none; padding: 8px 16px;
            border-radius: 4px; cursor: pointer; font-size: 13px; white-space: nowrap;
        }
        .vietfarmy-url-box .btn-save-url:hover { background: #135e96; }
        .vietfarmy-url-box .preview-box {
            margin-top: 10px; background: #f0f0f1; padding: 8px; border-radius: 4px; text-align: center;
        }
        .vietfarmy-url-box .preview-box img { max-width: 100%; border-radius: 4px; border: 1px solid #ddd; }
        .vietfarmy-url-box .hint { font-size: 12px; color: #646970; margin-top: 8px; }
    </style>
    <div class="vietfarmy-url-box">
        <div class="url-input-row">
            <input type="url"
                   id="vietfarmy_product_image_url"
                   name="vietfarmy_product_image_url"
                   value="<?php echo esc_url($url); ?>"
                   placeholder="https://example.com/image.jpg">
            <button type="button" class="btn-save-url" id="vietfarmy_save_url_btn">Lưu URL</button>
        </div>

        <?php if ($url) : ?>
            <div class="preview-box">
                <img src="<?php echo esc_url($url); ?>" alt="Preview">
            </div>
        <?php endif; ?>

        <p class="hint">📎 Dán link ảnh → nhấn <strong>Lưu URL</strong> hoặc dùng nút <strong>Xuất bản/Cập nhật</strong> của WordPress.</p>
    </div>

    <script>
    (function(){
        var btn = document.getElementById('vietfarmy_save_url_btn');
        if (!btn) return;
        btn.addEventListener('click', function(){
            var input = document.getElementById('vietfarmy_product_image_url');
            if (!input || !input.value) return;
            // submit form
            var form = btn.closest('form');
            if (form) {
                // ensure the input is enabled before submit
                input.disabled = false;
                form.submit();
            }
        });
    })();
    </script>
    <?php
}

// 3. Lưu URL khi save product
// -----------------------------------------------
add_action('save_post', 'vietfarmy_save_url_meta');

function vietfarmy_save_url_meta($post_id) {
    // Chỉ chạy cho product post type
    if (get_post_type($post_id) !== 'product') return;

    if (!isset($_POST['vietfarmy_url_nonce']) || !wp_verify_nonce($_POST['vietfarmy_url_nonce'], 'vietfarmy_url_save')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['vietfarmy_product_image_url'])) {
        update_post_meta($post_id, '_vietfarmy_product_image_url', esc_url_raw($_POST['vietfarmy_product_image_url']));
    }
}

// 4. Hiển thị ảnh từ URL — Trang danh sách (shop/archive)
// Chạy ở priority 9, thấp hơn default 10, nên in TRƯỚC ảnh mặc định
// -----------------------------------------------
add_action('woocommerce_before_shop_loop_item_title', 'vietfarmy_show_url_image_shop', 9);

function vietfarmy_show_url_image_shop() {
    global $product;
    if (!$product) return;

    $url = get_post_meta($product->get_id(), '_vietfarmy_product_image_url', true);
    if ($url) {
        echo '<div class="vietfarmy-product-image">' .
             '<img src="' . esc_url($url) . '" alt="' . esc_attr($product->get_name()) . '">' .
             '</div>';
    }
}

// 5. Hiển thị ảnh từ URL — Trang chi tiết sản phẩm
// Bỏ ảnh mặc định + inject ảnh URL ngay trong hook đúng vị trí
// -----------------------------------------------
add_filter('woocommerce_single_product_image_thumbnail_html', 'vietfarmy_replace_single_image_html', 20, 2);

function vietfarmy_replace_single_image_html($html, $post_thumbnail_id) {
    if (!is_product()) return $html;

    global $product;
    $url = get_post_meta($product->get_id(), '_vietfarmy_product_image_url', true);
    if ($url) {
        return '<div class="vietfarmy-product-image-single">' .
               '<img src="' . esc_url($url) . '" alt="' . esc_attr($product->get_name()) . '">' .
               '</div>';
    }
    return $html;
}

// 6. CSS căn chỉnh
// -----------------------------------------------
add_action('wp_head', 'vietfarmy_url_image_css');

function vietfarmy_url_image_css() {
    if (is_product() || is_shop() || is_product_category() || is_front_page()) {
        echo '<style>
            .vietfarmy-product-image img,
            .vietfarmy-product-image-single img {
                width: 100%;
                height: auto;
                display: block;
            }
            .vietfarmy-product-image {
                margin-bottom: 12px;
            }
        </style>';
    }
}

// ============================================================
// ALBUM ẢNH SẢN PHẨM TỪ URL
// ============================================================

// 7. Meta Box Album URL
// -----------------------------------------------
add_action('add_meta_boxes', 'vietfarmy_add_gallery_meta_box');

function vietfarmy_add_gallery_meta_box() {
    add_meta_box(
        'vietfarmy_gallery_urls',
        'Album ảnh sản phẩm từ URL',
        'vietfarmy_gallery_meta_box_callback',
        'product',
        'side',
        'low'
    );
}

function vietfarmy_gallery_meta_box_callback($post) {
    wp_nonce_field('vietfarmy_gallery_save', 'vietfarmy_gallery_nonce');
    $gallery_urls = get_post_meta($post->ID, '_vietfarmy_product_gallery_urls', true);
    if (!is_array($gallery_urls)) $gallery_urls = array();
    ?>
    <style>
        .vngallery-box { background: #fff; padding: 4px; }
        .vngallery-box .vngallery-input-row { display: flex; gap: 6px; align-items: center; margin-bottom: 6px; }
        .vngallery-box input[type="url"] { flex: 1; padding: 7px 9px; border: 1px solid #ccc; border-radius: 4px; font-size: 12px; }
        .vngallery-box input[type="url"]:focus { border-color: #2271b1; outline: none; }
        .vngallery-box .btn-add-url {
            background: #2271b1; color: #fff; border: none; padding: 7px 14px;
            border-radius: 4px; cursor: pointer; font-size: 12px; white-space: nowrap;
        }
        .vngallery-box .btn-add-url:hover { background: #135e96; }
        .vngallery-list { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
        .vngallery-item { position: relative; width: 60px; height: 60px; border-radius: 4px; overflow: hidden; border: 1px solid #ddd; background: #f0f0f1; }
        .vngallery-item img { width: 100%; height: 100%; object-fit: cover; }
        .vngallery-item .btn-remove-url {
            position: absolute; top: 0; right: 0; background: rgba(220,53,69,.85);
            color: #fff; border: none; width: 18px; height: 18px;
            border-radius: 0 0 0 4px; cursor: pointer; font-size: 10px;
            line-height: 18px; text-align: center; padding: 0;
        }
        .vngallery-item .btn-remove-url:hover { background: #dc3545; }
        .vngallery-empty { font-size: 12px; color: #999; padding: 8px 0; }
        .vngallery-hint { font-size: 11px; color: #646970; margin-top: 6px; line-height: 1.4; }
        .vngallery-hint strong { color: #444; }
    </style>

    <div class="vngallery-box">
        <div class="vngallery-input-row">
            <input type="url" id="vngallery_url_input" placeholder="https://example.com/image.jpg">
            <button type="button" class="btn-add-url" id="vngallery_add_btn">+ Thêm</button>
        </div>

        <div class="vngallery-list" id="vngallery_list">
            <?php foreach ($gallery_urls as $index => $img_url) : ?>
                <div class="vngallery-item" data-index="<?php echo $index; ?>">
                    <img src="<?php echo esc_url($img_url); ?>" alt="Thumbnail">
                    <button type="button" class="btn-remove-url" title="Xóa">×</button>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($gallery_urls)) : ?>
            <div class="vngallery-empty" id="vngallery_empty">Chưa có ảnh nào.</div>
        <?php endif; ?>

        <p class="vngallery-hint">📎 Dán URL ảnh → nhấn <strong>+ Thêm</strong> → nhấn <strong>Cập nhật</strong> sản phẩm để lưu.</p>
    </div>

    <!-- Hidden input lưu danh sách URL -->
    <input type="hidden" id="vngallery_urls_json" name="vietfarmy_product_gallery_urls_json" value='<?php echo esc_attr(json_encode($gallery_urls)); ?>'>

    <script>
    (function(){
        var input = document.getElementById('vngallery_url_input');
        var list = document.getElementById('vngallery_list');
        var hiddenInput = document.getElementById('vngallery_urls_json');
        var emptyMsg = document.getElementById('vngallery_empty');

        function getUrls() {
            try { return JSON.parse(hiddenInput.value || '[]'); }
            catch(e) { return []; }
        }

        function saveUrls(urls) {
            hiddenInput.value = JSON.stringify(urls);
            renderList(urls);
        }

        function renderList(urls) {
            if (!list) return;
            list.innerHTML = '';
            if (emptyMsg) emptyMsg.style.display = urls.length ? 'none' : 'block';
            urls.forEach(function(url, i) {
                var item = document.createElement('div');
                item.className = 'vngallery-item';
                item.setAttribute('data-index', i);
                item.innerHTML = '<img src="' + url + '" alt="Thumb"><button type="button" class="btn-remove-url" title="Xóa">×</button>';
                list.appendChild(item);
            });
        }

        // Thêm ảnh
        document.getElementById('vngallery_add_btn').addEventListener('click', function() {
            var val = input.value.trim();
            if (!val) return;
            var urls = getUrls();
            urls.push(val);
            saveUrls(urls);
            input.value = '';
            input.focus();
        });

        // Enter key
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') { e.preventDefault(); document.getElementById('vngallery_add_btn').click(); }
        });

        // Xóa ảnh (delegation)
        list.addEventListener('click', function(e) {
            var btn = e.target.closest('.btn-remove-url');
            if (!btn) return;
            var item = btn.closest('.vngallery-item');
            var idx = parseInt(item.getAttribute('data-index'), 10);
            var urls = getUrls();
            urls.splice(idx, 1);
            saveUrls(urls);
        });

        // Lưu JSON vào hidden input trước submit
        document.querySelector('form#post').addEventListener('submit', function() {
            var urls = getUrls();
            hiddenInput.value = JSON.stringify(urls);
        });
    })();
    </script>
    <?php
}

// 8. Lưu Album URL khi save product
// -----------------------------------------------
add_action('save_post', 'vietfarmy_save_gallery_meta');

function vietfarmy_save_gallery_meta($post_id) {
    if (get_post_type($post_id) !== 'product') return;
    if (!isset($_POST['vietfarmy_gallery_nonce']) || !wp_verify_nonce($_POST['vietfarmy_gallery_nonce'], 'vietfarmy_gallery_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['vietfarmy_product_gallery_urls_json'])) {
        $json = stripslashes($_POST['vietfarmy_product_gallery_urls_json']);
        $urls = json_decode($json, true);
        if (is_array($urls)) {
            $urls = array_map('esc_url_raw', $urls);
            update_post_meta($post_id, '_vietfarmy_product_gallery_urls', $urls);
        }
    }
}

// 9. Hiển thị Album URL trên trang chi tiết sản phẩm (gallery)
// -----------------------------------------------
add_filter('woocommerce_single_product_image_thumbnail_html', 'vietfarmy_replace_gallery_html', 20, 2);

function vietfarmy_replace_gallery_html($html, $post_thumbnail_id) {
    if (!is_product()) return $html;

    global $product;
    $gallery_urls = get_post_meta($product->get_id(), '_vietfarmy_product_gallery_urls', true);

    // Nếu không có gallery URL, giữ nguyên ảnh mặc định
    if (empty($gallery_urls) || !is_array($gallery_urls)) return $html;

    // Thay toàn bộ gallery bằng ảnh từ URL
    $main_url = get_post_meta($product->get_id(), '_vietfarmy_product_image_url', true);
    $output = '';

    // Ảnh chính (lấy từ main URL nếu có, không thì lấy ảnh mặc định)
    if ($main_url) {
        $output .= '<div class="vietfarmy-product-image-single woocommerce-product-gallery__image">' .
                   '<img src="' . esc_url($main_url) . '" alt="' . esc_attr($product->get_name()) . '"></div>';
    }

    // Ảnh gallery
    foreach ($gallery_urls as $url) {
        $output .= '<div class="vietfarmy-gallery-image woocommerce-product-gallery__image">' .
                   '<img src="' . esc_url($url) . '" alt="' . esc_attr($product->get_name()) . '"></div>';
    }

    return $output;
}

// 10. CSS cho album gallery
// -----------------------------------------------
add_action('wp_head', 'vietfarmy_gallery_css');

function vietfarmy_gallery_css() {
    if (is_product()) {
        echo '<style>
            .vietfarmy-gallery-image img,
            .vietfarmy-product-image-single img {
                width: 100%;
                height: auto;
                display: block;
            }
            .vietfarmy-gallery-image {
                margin-top: 8px;
            }
        </style>';
    }
}

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

// Trang danh sách sản phẩm (archive/shop): bỏ ảnh mặc định, thay bằng URL
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action('woocommerce_before_shop_loop_item_title', 'vietfarmy_display_url_image_archive', 9);
function vietfarmy_display_url_image_archive() {
    global $post;
    $image_url = get_post_meta($post->ID, 'fifu_image_url', true);
    if ($image_url) {
        echo '<div class="product-image-url"><img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title()) . '"></div>';
    }
}

// Trang chi tiết sản phẩm: ẩn ảnh mặc định, hiện ảnh từ URL
add_action('wp_head', 'vietfarmy_hide_default_single_image');
function vietfarmy_hide_default_single_image() {
    if (!is_singular('product')) return;
    echo '<style>.woocommerce-product-gallery { display: none !important; }</style>';
}
add_action('woocommerce_before_single_product_summary', 'vietfarmy_display_url_image_single', 5);
function vietfarmy_display_url_image_single() {
    global $post;
    $image_url = get_post_meta($post->ID, 'fifu_image_url', true);
    if ($image_url) {
        echo '<div class="product-image-url"><img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title()) . '" style="width:100%; height:auto;"></div>';
    }
}