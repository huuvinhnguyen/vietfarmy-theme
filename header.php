<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Gema
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div class="mobile-header-wrapper">

	<div class="mobile-logo"></div>

	<button class="overlay-toggle  menu-toggle  menu-open" aria-controls="primary-menu" aria-expanded="false">
		<?php get_template_part( 'assets/images/menu-bars-svg' ); ?>
        <span class="screen-reader-text"><?php esc_html_e( 'Primary Menu', 'gema' ); ?></span>
	</button>
	<?php if ( ! is_single() && ( ! pixelgrade_option( 'search_button', true ) ) ) { ?>
        <div class="search-toggle js-search"> <span class="screen-reader-text"><?php esc_html_e( 'Open Search', 'gema' ); ?></span></div>
    <?php } ?>
    <?php if( is_single() ) { ?>
        <button class="overlay-toggle  sidebar-toggle  sidebar-open"<?php if( ! is_single() ) echo ' disabled="disabled"'; ?> aria-expanded="false">
            <?php get_template_part( 'assets/images/sidebar-icon-svg' ); ?>
            <span class="screen-reader-text"><?php esc_html_e( 'Open Sidebar', 'gema' ); ?></span>
        </button>
    <?php } ?>
</div>

<style>
/* Ẩn mobile-header-wrapper gốc của Gema (đã có header mới của VietFarmy) */
.mobile-header-wrapper { display: none !important; }
</style>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'gema' ); ?></a>

	<div id="content" class="site-content">

<?php
// ── Header Zone (Logo / Menu / Banner) — VietFarmy ──
$logo_url = get_theme_mod('vnf_header_logo', '');
$banner_url = get_theme_mod('vnf_header_banner', '');
$show_banner = get_theme_mod('vnf_header_show_banner', false);
$banner_link = get_theme_mod('vnf_header_banner_link', home_url('/'));
$menu_shortcode = get_theme_mod('vnf_header_menu', '');
?>
<style>
/* ── Desktop ── */
.vnf-header {
    background: #fff;
    border-bottom: 2px solid #2d6a4f;
    position: sticky;
    top: 0;
    z-index: 999;
}
.vnf-header-bar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 24px; max-width: 1200px; margin: 0 auto; gap: 16px; height: 64px;
}
.vnf-logo img { height: 44px; width: auto; display: block; }
.vnf-logo-text { font-size: 18px; font-weight: 700; color: #2d6a4f; text-decoration: none; }
.vnf-nav { flex: 1; display: flex; justify-content: center; gap: 2px; }
.vnf-nav a {
    padding: 8px 16px; color: #333; text-decoration: none;
    font-size: 14px; border-radius: 6px; transition: all .2s; font-weight: 500;
}
.vnf-nav a:hover, .vnf-nav a.current-menu-item { background: #2d6a4f; color: #fff; }
.vnf-banner img { width: 100%; height: auto; max-height: 380px; object-fit: cover; display: block; }
.vnf-banner a { display: block; }

/* ── Mobile drawer — mặc định ẩn trên mọi màn hình ── */
.vnf-overlay { display: none; }
.vnf-drawer { display: none; }
.vnf-hamburger { display: none; }
.vnf-overlay.open { display: block; opacity: 1; }
.vnf-drawer.open { display: block; }

/* ── Mobile (<= 768px) ── */
@media (max-width: 768px) {
    .vnf-header { position: relative; margin-top: 0; }
    .vnf-header-bar { height: 54px; padding: 0 16px; gap: 8px; }

    /* Logo trái — Hamburger phải */
    .vnf-logo { flex-shrink: 0; }
    .vnf-logo img { height: 36px; }
    .vnf-logo-text { font-size: 16px; }

    /* Ẩn menu ngang */
    .vnf-nav { display: none; }

    /* Hamburger — đẩy sang phải */
    .vnf-hamburger {
        display: flex; flex-direction: column; justify-content: center;
        align-items: center; width: 40px; height: 40px; cursor: pointer;
        background: none; border: none; padding: 8px; border-radius: 6px;
        transition: background .2s; margin-left: auto; flex-shrink: 0;
    }
    .vnf-hamburger:hover { background: #f0f0f0; }
    .vnf-hamburger span {
        display: block; width: 22px; height: 2px; background: #333;
        margin: 4px 0; border-radius: 2px; transition: all .3s;
    }

    /* Overlay backdrop */
    .vnf-overlay {
        display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,.5); z-index: 10000; opacity: 0; transition: opacity .3s;
    }
    .vnf-overlay.open { display: block; opacity: 1; }

    /* Drawer menu */
    .vnf-drawer {
        position: fixed; top: 0; right: 0; bottom: 0; width: 280px;
        background: #fff; z-index: 10001; overflow-y: auto;
        transform: translateX(100%); transition: transform .3s;
        box-shadow: -4px 0 20px rgba(0,0,0,.15);
    }
    .vnf-drawer.open { transform: translateX(0); }
    .vnf-drawer-header {
        display: flex; justify-content: space-between; align-items: center;
        padding: 16px; border-bottom: 1px solid #eee;
    }
    .vnf-drawer-header strong { font-size: 15px; color: #2d6a4f; }
    .vnf-drawer-close {
        background: none; border: none; font-size: 22px; cursor: pointer;
        width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center;
        justify-content: center; color: #666;
    }
    .vnf-drawer-close:hover { background: #f0f0f0; }
    .vnf-drawer-nav li a {
        display: block; padding: 14px 20px; color: #333; text-decoration: none;
        font-size: 15px; border-bottom: 1px solid #f0f0f0; transition: all .2s;
    }
    .vnf-drawer-nav li a:hover { background: #f8f8f8; color: #2d6a4f; }
    .vnf-drawer-nav li:last-child a { border-bottom: none; }
}
</style>

<div class="vnf-header">
    <div class="vnf-header-bar">
        <!-- Logo -->
        <div class="vnf-logo">
            <?php if ($logo_url) : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url($logo_url); ?>" alt="<?php bloginfo('name'); ?>"></a>
            <?php else : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="vnf-logo-text"><?php bloginfo('name'); ?></a>
            <?php endif; ?>
        </div>

        <!-- Menu Desktop -->
        <nav class="vnf-nav">
            <?php if ($menu_shortcode) : ?>
                <?php echo do_shortcode($menu_shortcode); ?>
            <?php else : ?>
                <?php wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container' => false,
                    'items_wrap' => '%3$s',
                    'fallback_cb' => function() {
                        echo '<a href="'.esc_url(home_url('/')).'">Trang chủ</a>';
                        echo '<a href="'.esc_url(get_permalink(wc_get_page_id('shop'))).'">Sản phẩm</a>';
                    }
                )); ?>
            <?php endif; ?>
        </nav>

        <!-- Hamburger (mobile) -->
        <button class="vnf-hamburger" aria-label="Mở menu" id="vnf_hamburger">
            <span></span><span></span><span></span>
        </button>
    </div>

    <!-- Banner -->
    <?php if ($show_banner && $banner_url) : ?>
        <div class="vnf-banner">
            <?php if ($banner_link !== home_url('/')) : ?>
                <a href="<?php echo esc_url($banner_link); ?>"><img src="<?php echo esc_url($banner_url); ?>" alt="Banner"></a>
            <?php else : ?>
                <img src="<?php echo esc_url($banner_url); ?>" alt="Banner">
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Mobile Drawer -->
<div class="vnf-overlay" id="vnf_overlay"></div>
<div class="vnf-drawer" id="vnf_drawer">
    <div class="vnf-drawer-header">
        <strong>📋 Menu</strong>
        <button class="vnf-drawer-close" id="vnf_close">&#10005;</button>
    </div>
    <nav class="vnf-drawer-nav">
        <?php if ($menu_shortcode) : ?>
            <?php echo do_shortcode($menu_shortcode); ?>
        <?php else : ?>
            <?php wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'items_wrap' => '%3$s')); ?>
        <?php endif; ?>
    </nav>
</div>

<script>
(function(){
    var btn = document.getElementById('vnf_hamburger');
    var overlay = document.getElementById('vnf_overlay');
    var drawer = document.getElementById('vnf_drawer');
    var closeBtn = document.getElementById('vnf_close');
    function open() {
        overlay.classList.add('open');
        drawer.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function close() {
        overlay.classList.remove('open');
        drawer.classList.remove('open');
        document.body.style.overflow = '';
    }
    if (btn) btn.addEventListener('click', open);
    if (closeBtn) closeBtn.addEventListener('click', close);
    if (overlay) overlay.addEventListener('click', close);
    document.addEventListener('keydown', function(e){ if (e.key === 'Escape') close(); });
})();
</script>
