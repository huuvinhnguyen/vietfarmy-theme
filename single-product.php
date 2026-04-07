<?php
/**
 * Single Product Template — VietFarmy
 * Override WooCommerce default single-product.php
 *
 * @package Gema
 */

get_header(); ?>

    <style>
    .vnheader {
        background: #fff;
        border-bottom: 1px solid #eee;
        position: relative;
    }
    .vnheader-logo-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 20px;
        max-width: 1200px;
        margin: 0 auto;
        gap: 16px;
        flex-wrap: wrap;
    }
    .vnheader-logo-img img { height: 50px; width: auto; display: block; }
    .vnheader-logo-text { font-size: 20px; font-weight: 700; color: #1a1a1a; text-decoration: none; }
    .vnheader-nav { display: flex; gap: 4px; align-items: center; flex: 1; justify-content: center; }
    .vnheader-nav a { padding: 8px 14px; color: #333; text-decoration: none; font-size: 14px; border-radius: 6px; transition: background .2s; }
    .vnheader-nav a:hover { background: #f0f0f0; }
    .vnheader-actions { display: flex; align-items: center; gap: 8px; }
    .vnheader-actions a { padding: 8px 16px; background: #2271b1; color: #fff; border-radius: 6px; text-decoration: none; font-size: 13px; transition: background .2s; }
    .vnheader-actions a:hover { background: #135e96; }
    .vnheader-banner img { width: 100%; height: auto; display: block; max-height: 400px; object-fit: cover; }
    .vnheader-banner a { display: block; }
    @media (max-width: 768px) {
        .vnheader-logo-bar { flex-wrap: wrap; padding: 10px 16px; }
        .vnheader-nav { order: 3; width: 100%; justify-content: center; }
        .vnheader-actions { order: 2; }
    }
    </style>

    <?php
    // ── Header Zone ──
    $logo_mod = get_theme_mod('vietfarmy_header_logo', '');
    $logo_url = is_numeric($logo_mod) ? wp_get_attachment_image_url((int)$logo_mod, 'full') : $logo_mod;
    if (!$logo_url) $logo_url = '';

    $banner_mod = get_theme_mod('vietfarmy_header_banner', '');
    $banner_url = is_numeric($banner_mod) ? wp_get_attachment_image_url((int)$banner_mod, 'full') : $banner_mod;
    if (!$banner_url) $banner_url = '';

    $banner_link = get_theme_mod('vietfarmy_header_banner_link', home_url('/'));
    $show_banner = get_theme_mod('vietfarmy_header_show_banner', false);
    ?>

    <div class="vnheader">
        <div class="vnheader-logo-bar">
            <div class="vnheader-logo">
                <?php if ($logo_url) : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>"></a>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="vnheader-logo-text"><?php bloginfo('name'); ?></a>
                <?php endif; ?>
            </div>

            <nav class="vnheader-nav">
                <?php
                $menu_shortcode = get_theme_mod('vietfarmy_header_menu', '');
                if ($menu_shortcode) {
                    echo do_shortcode($menu_shortcode);
                } else {
                    wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'items_wrap' => '%3$s'));
                }
                ?>
            </nav>

            <div class="vnheader-actions">
                <a href="<?php echo esc_url(wc_get_cart_url()); ?>">🛒 Giỏ hàng</a>
                <a href="<?php echo esc_url(home_url('/')); ?>">Liên hệ</a>
            </div>
        </div>

        <?php if ($show_banner && $banner_url) : ?>
            <div class="vnheader-banner">
                <?php if ($banner_link !== home_url('/')) : ?>
                    <a href="<?php echo esc_url($banner_link); ?>"><img src="<?php echo esc_url($banner_url); ?>" alt="Banner"></a>
                <?php else : ?>
                    <img src="<?php echo esc_url($banner_url); ?>" alt="Banner">
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php woocommerce_content(); ?>

<?php get_footer();
