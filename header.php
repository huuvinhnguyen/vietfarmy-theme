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
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<!-- ===== COFFEE BEANS DECORATIVE STRIP ===== -->
<div class="coffee-beans-strip">
    <div class="coffee-beans-wrapper">
        <span class="coffee-bean">☕</span>
        <span class="coffee-bean">☕</span>
        <span class="coffee-bean">☕</span>
        <span class="coffee-bean">☕</span>
        <span class="coffee-bean">☕</span>
        <span class="coffee-bean">☕</span>
        <span class="coffee-bean">☕</span>
        <span class="coffee-bean">☕</span>
        <span class="coffee-bean">☕</span>
        <span class="coffee-bean">☕</span>
        <span class="coffee-bean">☕</span>
        <span class="coffee-bean">☕</span>
        <span class="coffee-bean">☕</span>
        <span class="coffee-bean">☕</span>
        <span class="coffee-bean">☕</span>
    </div>
</div>

<!-- ===== MAIN HEADER TOP SECTION ===== -->
<header id="masthead" class="site-header-top">
    <div class="header-top-container">
        <!-- Left: Logo Company -->
        <div class="header-logo-section">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-company-link">
                <?php
                if ( has_custom_logo() ) {
                    the_custom_logo();
                } else {
                ?>
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-coffee-new.png" alt="COFFEE NEW" class="logo-company-img">
                <?php } ?>
            </a>
        </div>

        <!-- Center: Company Name (no tagline) -->
        <div class="header-company-section">
            <h1 class="company-name">CÔNG TY TNHH XUẤT NHẬP KHẨU COFFEE NEW</h1>
        </div>

        <!-- Right: Hotline + Flags + Search -->
        <div class="header-right-section">
            <div class="hotline-section">
                <span class="hotline-icon">📞</span>
                <div class="hotline-text">
                    <span class="hotline-label">Hotline 24/24</span>
                    <span class="hotline-number">0906 680 182</span>
                </div>
            </div>
            <div class="language-flags">
                <a href="#" class="flag-en" title="English">🇬🇧</a>
                <a href="#" class="flag-vi" title="Tiếng Việt">🇻🇳</a>
            </div>
            <div class="search-box">
                <input type="text" class="search-input" placeholder="Tìm kiếm...">
                <button class="search-btn">🔍</button>
            </div>
        </div>
    </div>
</header>

<!-- ===== NAVIGATION BAR ===== -->
<nav class="main-navigation-bar">
    <div class="nav-container">
        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
            ☰
        </button>
        <?php
        wp_nav_menu( array(
            'theme_location' => 'primary',
            'menu_id'        => 'primary-menu',
            'menu_class'     => 'nav-menu',
            'container'      => false,
            'fallback_cb'    => false,
        ) );
        ?>
    </div>
</nav>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'gema' ); ?></a>

    <div id="content" class="site-content">
