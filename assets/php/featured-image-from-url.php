<?php
/**
 * Featured Image from URL - Simple Version
 * Chỉ lưu URL, hiển thị trực tiếp (không tải về server)
 */

// Thêm meta box
add_action('add_meta_boxes', 'vietfarmy_add_url_meta_box');

function vietfarmy_add_url_meta_box() {
    // Cho Products
    add_meta_box(
        'vietfarmy_url_image',
        '🔗 Ảnh sản phẩm từ URL',
        'vietfarmy_url_meta_box_callback',
        'product',
        'side',
        'low'
    );
}

function vietfarmy_url_meta_box_callback($post) {
    wp_nonce_field('vietfarmy_url_save', 'vietfarmy_url_nonce');
    $url = get_post_meta($post->ID, '_vietfarmy_product_image_url', true);
    ?>
    <style>
        .vietfarmy-url-box {
            padding: 10px 0;
        }
        .vietfarmy-url-box label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #1d2327;
        }
        .vietfarmy-url-box input[type="url"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 13px;
            box-sizing: border-box;
        }
        .vietfarmy-url-box input:focus {
            border-color: #2271b1;
            box-shadow: 0 0 0 1px #2271b1;
            outline: none;
        }
        .vietfarmy-preview {
            margin-top: 10px;
            background: #f0f0f1;
            padding: 10px;
            border-radius: 4px;
        }
        .vietfarmy-preview img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .vietfarmy-help {
            font-size: 12px;
            color: #646970;
            margin-top: 5px;
            line-height: 1.4;
        }
    </style>

    <div class="vietfarmy-url-box">
        <label for="vietfarmy_product_image_url">📎 Đường dẫn ảnh:</label>
        <input type="url"
               id="vietfarmy_product_image_url"
               name="vietfarmy_product_image_url"
               value="<?php echo esc_url($url); ?>"
               placeholder="https://example.com/image.jpg"
               class="widefat">

        <?php if ($url) : ?>
            <div class="vietfarmy-preview">
                <strong>👁️ Xem trước:</strong><br><br>
                <img src="<?php echo esc_url($url); ?>" alt="Preview">
            </div>
        <?php endif; ?>

        <p class="vietfarmy-help">
            💡 Dán link ảnh vào ô trên và Lưu sản phẩm.<br>
            Ảnh sẽ hiển thị trực tiếp từ URL.
        </p>
    </div>
    <?php
}

// Lưu URL
add_action('save_post', 'vietfarmy_save_url_meta');

function vietfarmy_save_url_meta($post_id) {
    if (!isset($_POST['vietfarmy_url_nonce']) || !wp_verify_nonce($_POST['vietfarmy_url_nonce'], 'vietfarmy_url_save')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['vietfarmy_product_image_url'])) {
        $url = esc_url_raw($_POST['vietfarmy_product_image_url']);
        update_post_meta($post_id, '_vietfarmy_product_image_url', $url);
    }
}

// Hiển thị ảnh từ URL trên trang sản phẩm
add_action('woocommerce_before_shop_loop_item_title', 'vietfarmy_show_url_image_shop', 5);
add_action('woocommerce_before_single_product_summary', 'vietfarmy_show_url_image_single', 5);

function vietfarmy_show_url_image_shop() {
    global $product;
    if (!$product) return;

    $url = get_post_meta($product->get_id(), '_vietfarmy_product_image_url', true);
    if ($url) {
        echo '<div class="vietfarmy-product-image">' .
             '<img src="' . esc_url($url) . '" alt="' . esc_attr($product->get_name()) . '">' .
             '</div>';
    }
}

function vietfarmy_show_url_image_single() {
    global $product;
    if (!$product) return;

    $url = get_post_meta($product->get_id(), '_vietfarmy_product_image_url', true);
    if ($url) {
        echo '<div class="vietfarmy-product-image-single">' .
             '<img src="' . esc_url($url) . '" alt="' . esc_attr($product->get_name()) . '">' .
             '</div>';
    }
}

// CSS cho ảnh URL
add_action('wp_head', 'vietfarmy_url_image_css');

function vietfarmy_url_image_css() {
    if (is_product() || is_shop()) {
        echo '<style>
            .vietfarmy-product-image img,
            .vietfarmy-product-image-single img {
                width: 100%;
                height: auto;
                border-radius: 8px;
            }
            .vietfarmy-product-image {
                margin-bottom: 15px;
            }
        </style>';
    }
}
