<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Gema
 */
?>

	</div><!-- #content -->

    <?php
    if ( shortcode_exists( 'instagram-feed' ) ) {
	    echo do_shortcode( '[instagram-feed]' );
    } ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<span class="site-info-additional-text">
				<?php
				$copyright_text = pixelgrade_option( 'gema_footer_copyright' );

				if ( ! empty( $copyright_text ) ) {
					echo $copyright_text;
				} else {
					echo pixelgrade_footer_get_copyright_content();
				} ?>
			</span>
		</div><!-- .site-info -->
		<?php
		wp_nav_menu( array(
			'theme_location' => 'footer',
			'menu_id' => 'footer-menu',
			'menu_class' => 'footer-menu',
			'depth' => 1,
			'container' => false,
            'fallback_cb' => false,
		) ); ?>
	</footer><!-- #colophon -->

<?php if ( ( ! pixelgrade_option( 'search_button', true ) ) ) { ?>
    <div class="overlay--search">
        <div class="overlay__wrapper">
            <?php get_search_form(); ?>
            <p><?php esc_html_e( 'Begin typing your search above and press return to search. Press Esc to cancel.', 'patch' ); ?></p>
        </div>
        <b class="overlay__close"></b>
    </div>
<?php } ?>
	<div class="overlay-shadow"></div>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
