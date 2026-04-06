<?php
/**
 * Template part for displaying the header "card".
 *
 * @package Gema
 */

?>

<header id="masthead" class="site-header" role="banner">

	<!-- TẦNG 1: Thông tin nhanh -->
	<div class="header-top">
		<div class="header-container">
			<span class="header-hotline">
				<span class="hotline-icon">📞</span>
				Hotline: <strong>0906 680 182</strong>
			</span>
			<span class="header-slogan">
				<span class="slogan-icon">☕</span>
				Cà phê rang xay nguyên chất từ Gia Lai
			</span>
		</div>
	</div>

	<!-- TẦNG 2: Logo và Menu -->
	<div class="header-main">
		<div class="header-container header-main-inner">
			<div class="site-branding">
				<?php gema_the_custom_logo(); ?>

				<div class="brand-text">
					<?php
					echo ( is_front_page() && is_home() ) ? '<h1 class="site-title">' : '<div class="site-title">'; ?>

					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<span><?php bloginfo( 'name' ); ?></span>
					</a>

					<?php
					echo ( is_front_page() && is_home() ) ? '</h1>' : '</div>';

					$description = get_bloginfo( 'description', 'display' );
					if ( $description || is_customize_preview() ) : ?>

						<p class="site-description-text"><?php echo $description; ?></p>

					<?php endif; ?>
				</div>
			</div>

			<nav id="site-navigation" class="main-navigation" role="navigation">
				<button class="overlay-toggle menu-toggle menu-close" aria-expanded="false">
					<?php get_template_part( 'assets/images/close-icon-svg' ); ?>
					<span class="screen-reader-text"><?php esc_html_e( 'Close Menu', 'gema' ); ?></span>
				</button>

				<?php wp_nav_menu( array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'menu_class'     => 'nav-menu',
					'container'      => '',
					'fallback_cb'    => false,
				) ); ?>

				<?php if ( function_exists( 'jetpack_social_menu' ) ) jetpack_social_menu(); ?>
			</nav>
		</div>
	</div>

</header><!-- #masthead -->
