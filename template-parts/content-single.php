<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Gema
 */

?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	// Ưu tiên: Ảnh từ URL custom → Featured Image mặc định
	$post_image_url = get_post_meta( get_the_ID(), '_vnf_post_image_url', true );
	$has_thumb = has_post_thumbnail() || ! empty( $post_image_url );
	$aspect_class = ! empty( $post_image_url ) ? 'entry-image--landscape' : 'entry-image--' . gema_get_post_thumbnail_aspect_ratio_class();
	?>

	<?php if ( $has_thumb ) : ?>

		<div class="entry-featured entry-thumbnail <?php echo $aspect_class; ?><?php echo ! empty( $post_image_url ) ? ' vnf-url-featured' : ''; ?>">
			<?php
			if ( ! empty( $post_image_url ) ) {
				echo '<img src="' . esc_url( $post_image_url ) . '" alt="' . esc_attr( get_the_title() ) . '" class="vnf-single-img">';
			} else {
				the_post_thumbnail( 'gema-single-' . gema_get_post_thumbnail_aspect_ratio_class() );
			}
			?>
		</div>
		<style>
		/* Fix layout ảnh featured cho blog posts — bỏ float/absolute positioning */
		/* Áp dụng cho MỌI loại featured image (URL custom lẫn upload WordPress) */
		.post-type-post.singular .entry-featured {
			float: none !important;
			max-width: none !important;
			position: static !important;
			margin: 0 0 24px 0 !important;
			padding: 0 !important;
		}
		.post-type-post.singular .entry-featured img {
			width: 100%;
			display: block;
		}
		/* Desktop: canh giữa, max-width content */
		@media (min-width: 900px) {
			.post-type-post.singular .entry-featured {
				max-width: 720px;
				margin-left: auto !important;
				margin-right: auto !important;
			}
		}
		/* Mobile: full width ra ngoài lề */
		@media (max-width: 899px) {
			.post-type-post.singular .entry-featured {
				margin-left: -30px !important;
				margin-right: -30px !important;
			}
		}
		/* Bỏ absolutely-positioned layout từ theme Gema */
		.post-type-post.singular.has-featured-image .post__content {
			clear: both;
			padding-top: 0 !important;
		}
		</style>

	<?php else : ?>
		<style>
		/* Fix khoảng trắng lớn khi không có ảnh đại diện */
		.singular .entry-featured { display: none !important; }
		</style>

	<?php endif; ?>

	<div class="entry-header">
		<?php the_title('<h1 class="entry-title"><span>', '</span></h1>'); ?>

		<div class="entry-meta">
			<?php gema_cats_list(); ?>
			<?php gema_posted_on(); ?>
			<?php
			edit_post_link(
				sprintf(
				/* translators: %s: Name of current post */
					esc_html__( 'Edit %s', 'gema' ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				), '<span class="edit-link-separator"></span>'
			);
			?>
		</div><!-- .entry-meta -->
	</div>

	<div class="post__content">

		<div class="entry-content">

			<?php the_content(); ?>

			<?php
			wp_link_pages(array(
				'before' => '<div class="page-links">' . esc_html__('Pages:', 'gema'),
				'after' => '</div>',
			));
			?>

		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php gema_entry_footer(); ?>
		</footer><!-- .entry-footer -->
	</div><!-- .post__content -->

</div><!-- #post-## -->
