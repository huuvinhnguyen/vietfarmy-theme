<header class="site-header">
    <!-- Tầng 1: Thông tin nhanh -->
    <div class="header-top">
        <div class="container">
            <span class="hotline">Hotline: 09xx xxx xxx</span>
            <span class="slogan">Cà phê rang xay nguyên chất từ Gia Lai</span>
        </div>
    </div>

    <!-- Tầng 2: Logo và Menu -->
    <div class="header-main">
        <div class="container">
            <div class="logo">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <img src="path-to-your-logo.png" alt="VietFarmy Logo">
                </a>
            </div>
            <nav class="main-navigation">
                <?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
            </nav>
        </div>
    </div>
</header>