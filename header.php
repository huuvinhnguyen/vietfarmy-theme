<?php
/**
 * The header for our theme.
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

<?php
// ── VietFarmy Header Settings ──
$logo_url    = get_theme_mod('vnf_header_logo', '');
$banner_url  = get_theme_mod('vnf_header_banner', '');
$show_banner = get_theme_mod('vnf_header_show_banner', false);
$banner_link = get_theme_mod('vnf_header_banner_link', home_url('/'));
?>
<style>
/* ── Reset ── */
#page { margin: 0 !important; }

/* ── Desktop Header (VietFarmy) ── */
.vnf-header {
    background: #fff;
    border-bottom: 2px solid #2d6a4f;
    position: sticky;
    top: 0;
    z-index: 9999;
}
.vnf-header-bar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 24px; max-width: 1200px; margin: 0 auto; gap: 16px; height: 64px;
}
.vnf-logo img { height: 44px; width: auto; display: block; }
.vnf-logo-text { font-size: 18px; font-weight: 700; color: #2d6a4f; text-decoration: none; }

/* Desktop menu */
.vnf-nav ul { all: unset; list-style: none !important; margin: 0 !important; padding: 0 !important; display: flex !important; gap: 2px; flex-wrap: wrap; justify-content: center; }
.vnf-nav li { all: unset !important; list-style: none !important; }
.vnf-nav a { all: unset !important; text-decoration: none !important; display: block; padding: 8px 16px; color: #333; font-size: 14px; border-radius: 6px; font-weight: 500; transition: all .2s; }
.vnf-nav a:hover, .vnf-nav .current-menu-item a { background: #2d6a4f !important; color: #fff !important; }

.vnf-banner img { width: 100%; height: auto; max-height: 380px; object-fit: cover; display: block; }
.vnf-banner a { display: block; }

/* ── Gema Mobile Menu — đổi style sang VietFarmy ── */
.mobile-header-wrapper {
    background: #fff;
    border-bottom: 2px solid #2d6a4f;
    position: sticky;
    top: 0;
    z-index: 9998;
}
.mobile-header-wrapper .mobile-logo {
    flex: 1;
}
.mobile-header-wrapper .mobile-logo a {
    font-size: 15px;
    font-weight: 700;
    color: #2d6a4f;
    text-decoration: none;
}
.mobile-header-wrapper .mobile-logo img {
    height: 36px;
    width: auto;
}
.mobile-header-wrapper .menu-toggle {
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
    border-radius: 6px;
}
.mobile-header-wrapper .menu-toggle:hover { background: #f0f0f0; }

/* Banner */
.vnf-banner img { width: 100%; max-height: 380px; object-fit: cover; }

/* ── Mobile (<= 768px) ── */
@media (max-width: 768px) {
    /* Ẩn desktop header trên mobile */
    .vnf-header { display: none !important; }

    /* Hiện Gema mobile menu — logo VietFarmy + icon ☰ */
    .mobile-header-wrapper { display: flex !important; }

    /* Banner full width mobile */
    .vnf-banner img { max-height: none !important; }
}
</style>

<!-- VietFarmy Desktop Header -->
<header class="vnf-header">
    <div class="vnf-header-bar">
        <div class="vnf-logo">
            <?php if ($logo_url) : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url($logo_url); ?>" alt="<?php bloginfo('name'); ?>"></a>
            <?php else : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="vnf-logo-text"><?php bloginfo('name'); ?></a>
            <?php endif; ?>
        </div>
        <nav class="vnf-nav" aria-label="Primary navigation">
            <?php wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'fallback_cb' => false)); ?>
        </nav>
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
</header>

<!-- Gema Mobile Menu (tận dụng Gema — icon ☰ mở menu overlay) -->
<div class="mobile-header-wrapper" style="display:flex!important;align-items:center;justify-content:space-between;padding:0 16px;height:52px;">
    <div class="mobile-logo">
        <?php if ($logo_url) : ?>
            <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url($logo_url); ?>" alt="<?php bloginfo('name'); ?>"></a>
        <?php else : ?>
            <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
        <?php endif; ?>
    </div>
    <button class="overlay-toggle menu-toggle menu-open" aria-controls="primary-menu" aria-expanded="false">
        <svg width="22" height="16" viewBox="0 0 22 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1 1h20M1 8h20M1 15h20" stroke="#333" stroke-width="2" stroke-linecap="round"/>
        </svg>
        <span class="screen-reader-text"><?php esc_html_e('Primary Menu', 'gema'); ?></span>
    </button>
</div>

<!-- Banner dưới mobile header -->
<?php if ($show_banner && $banner_url) : ?>
    <div class="vnf-banner">
        <?php if ($banner_link !== home_url('/')) : ?>
            <a href="<?php echo esc_url($banner_link); ?>"><img src="<?php echo esc_url($banner_url); ?>" alt="Banner"></a>
        <?php else : ?>
            <img src="<?php echo esc_url($banner_url); ?>" alt="Banner">
        <?php endif; ?>
    </div>
<?php endif; ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'gema'); ?></a>
	<div id="content" class="site-content">
