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

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'gema' ); ?></a>

	<div id="content" class="site-content">

<?php
// ── Header Zone (Logo / Menu / Banner) — VietFarmy ──
$logo_url = get_theme_mod('vnf_header_logo', '');
$banner_url = get_theme_mod('vnf_header_banner', '');
$show_banner = get_theme_mod('vnf_header_show_banner', false);
$banner_link = get_theme_mod('vnf_header_banner_link', home_url('/'));
?>
<style>
.vnf-header { background: #fff; border-bottom: 1px solid #eee; }
.vnf-header-bar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 24px; max-width: 1200px; margin: 0 auto; gap: 16px;
}
.vnf-logo img { height: 48px; width: auto; display: block; }
.vnf-logo-text { font-size: 20px; font-weight: 700; color: #1a1a1a; text-decoration: none; }
.vnf-nav { flex: 1; display: flex; justify-content: center; gap: 4px; }
.vnf-nav a { padding: 8px 14px; color: #333; text-decoration: none; font-size: 14px; border-radius: 6px; transition: background .2s; }
.vnf-nav a:hover { background: #f0f0f0; }
.vnf-actions { display: flex; gap: 8px; }
.vnf-actions a { padding: 8px 16px; background: #2271b1; color: #fff; border-radius: 6px; text-decoration: none; font-size: 13px; transition: background .2s; }
.vnf-actions a:hover { background: #135e96; }
.vnf-banner img { width: 100%; height: auto; max-height: 380px; object-fit: cover; display: block; }
.vnf-banner a { display: block; }
@media (max-width: 768px) {
    .vnf-header-bar { flex-wrap: wrap; padding: 10px 16px; }
    .vnf-nav { order: 3; width: 100%; justify-content: center; flex-wrap: wrap; }
    .vnf-actions { order: 2; }
}
</style>

<div class="vnf-header">
    <div class="vnf-header-bar">
        <div class="vnf-logo">
            <?php if ($logo_url) : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url($logo_url); ?>" alt="<?php bloginfo('name'); ?>"></a>
            <?php else : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="vnf-logo-text"><?php bloginfo('name'); ?></a>
            <?php endif; ?>
        </div>
        <nav class="vnf-nav">
            <?php
            $menu_shortcode = get_theme_mod('vnf_header_menu', '');
            if ($menu_shortcode) {
                echo do_shortcode($menu_shortcode);
            } else {
                wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'items_wrap' => '%3$s', 'fallback_cb' => function() {
                    echo '<a href="'.esc_url(home_url('/')).'">Trang chủ</a>';
                    echo '<a href="'.esc_url(get_permalink(wc_get_page_id('shop'))).'">Sản phẩm</a>';
                    echo '<a href="'.esc_url(wc_get_cart_url()).'">Giỏ hàng</a>';
                }));
            }
            ?>
        </nav>
        <div class="vnf-actions">
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>">🛒 Giỏ hàng</a>
            <a href="<?php echo esc_url(home_url('/')); ?>">Liên hệ</a>
        </div>
    </div>
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
