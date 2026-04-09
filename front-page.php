<?php
/**
 * Front Page Template — VietFarmy
 * Hiển thị Sản phẩm nổi bật + Bài viết
 *
 * @package Gema
 */

get_header();

// --- Sản phẩm nổi bật ---
$featured_ids = array();
for ($i = 1; $i <= 8; $i++) {
    $id = get_theme_mod("vnf_featured_product_$i", '');
    if (!empty($id)) $featured_ids[] = (int) $id;
}

// Nếu chưa có sản phẩm nổi bật được chọn → lấy 4 sản phẩm mới nhất
$show_featured = get_theme_mod('vnf_show_featured_products', true);
?>

<?php if ($show_featured) : ?>
<!-- FEATURED PRODUCTS SECTION -->
<section class="vnf-featured-products">
    <div class="vnf-featured-inner">
        <div class="vnf-section-header">
            <h2 class="vnf-section-title">Sản phẩm nổi bật</h2>
            <p class="vnf-section-subtitle">Những tinh túy nhất từ nông trại Gia Lai</p>
        </div>

        <?php if (!empty($featured_ids)) : ?>
            <?php
            $args = array(
                'post_type'      => 'product',
                'post__in'       => $featured_ids,
                'posts_per_page' => count($featured_ids),
                'orderby'        => 'post__in',
                'post_status'    => 'publish',
            );
            $products = new WP_Query($args);
        <?php else : ?>
            <?php
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => 8,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'post_status'    => 'publish',
            );
            $products = new WP_Query($args);
        <?php endif; ?>

        <?php if ($products->have_posts()) : ?>
            <div class="vnf-product-grid">
                <?php while ($products->have_posts()) : $products->the_post(); global $product; ?>
                    <div class="vnf-product-card">
                        <a href="<?php the_permalink(); ?>" class="vnf-product-thumb">
                            <?php
                            // Ưu tiên: Ảnh từ URL → Featured Image WC
                            $img_url = get_post_meta(get_the_ID(), '_vietfarmy_product_image_url', true);
                            if (!empty($img_url)) {
                                echo '<img src="' . esc_url($img_url) . '" alt="' . esc_attr(get_the_title()) . '">';
                            } elseif (has_post_thumbnail()) {
                                the_post_thumbnail('woocommerce_thumbnail', array('alt' => get_the_title()));
                            } else {
                                echo '<img src="' . wc_placeholder_img_src('woocommerce_thumbnail') . '" alt="Product">';
                            }
                            ?>

                            <?php if ($product->is_on_sale()) : ?>
                                <span class="vnf-badge vnf-badge-sale">Sale</span>
                            <?php elseif ($product->is_featured()) : ?>
                                <span class="vnf-badge vnf-badge-hot">Nổi bật</span>
                            <?php endif; ?>
                        </a>

                        <div class="vnf-product-info">
                            <h3 class="vnf-product-name">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

                            <div class="vnf-product-price">
                                <?php echo $product->get_price_html(); ?>
                            </div>

                            <a href="<?php the_permalink(); ?>" class="vnf-btn-buy">Xem chi tiết</a>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <p class="vnf-empty">Chưa có sản phẩm nào.</p>
        <?php endif; ?>
    </div>
</section>

<style>
/* FEATURED PRODUCTS */
.vnf-featured-products {
    background: #fff;
    padding: 48px 0;
}
.vnf-featured-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
}
.vnf-section-header {
    text-align: center;
    margin-bottom: 36px;
}
.vnf-section-title {
    font-size: 28px;
    font-weight: 700;
    color: #2d6a4f;
    margin: 0 0 8px;
}
.vnf-section-subtitle {
    font-size: 15px;
    color: #888;
    margin: 0;
}
.vnf-product-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}
.vnf-product-card {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
}
.vnf-product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}
.vnf-product-thumb {
    display: block;
    position: relative;
    overflow: hidden;
    aspect-ratio: 1/1;
    background: #f8f8f8;
}
.vnf-product-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s;
}
.vnf-product-card:hover .vnf-product-thumb img {
    transform: scale(1.05);
}
.vnf-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    color: #fff;
    z-index: 1;
}
.vnf-badge-sale { background: #e74c3c; }
.vnf-badge-hot { background: #2d6a4f; }
.vnf-product-info {
    padding: 16px;
}
.vnf-product-name {
    font-size: 15px;
    font-weight: 600;
    color: #333;
    margin: 0 0 10px;
    line-height: 1.3;
}
.vnf-product-name a {
    color: inherit;
    text-decoration: none;
}
.vnf-product-name a:hover { color: #2d6a4f; }
.vnf-product-price {
    font-size: 16px;
    font-weight: 700;
    color: #c0392b;
    margin-bottom: 12px;
}
.vnf-product-price .woocommerce-Price-amount { color: #c0392b; }
.vnf-product-price del { color: #999; font-size: 13px; font-weight: 400; margin-right: 6px; }
.vnf-btn-buy {
    display: block;
    text-align: center;
    padding: 10px;
    background: #2d6a4f;
    color: #fff;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.2s;
}
.vnf-btn-buy:hover { background: #1e4d34; color: #fff; }
.vnf-empty { text-align: center; color: #999; padding: 40px; }

/* Responsive */
@media (max-width: 1024px) {
    .vnf-product-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 768px) {
    .vnf-product-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .vnf-product-info { padding: 12px; }
    .vnf-product-name { font-size: 13px; }
    .vnf-section-title { font-size: 22px; }
}
@media (max-width: 480px) {
    .vnf-product-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
}
</style>
<?php endif; // $show_featured ?>

<?php get_footer(); ?>
