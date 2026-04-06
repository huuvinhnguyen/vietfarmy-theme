<?php
/**
 * Plugin Name: Featured Image from URL
 * Description: Thêm chức năng đặt ảnh đại diện từ URL thay vì upload file
 * Version: 1.0
 * Author: VietFarmy
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Thêm meta box cho Image URL
add_action('add_meta_boxes', 'vietfarmy_add_image_url_meta_box');

function vietfarmy_add_image_url_meta_box() {
    // Cho Posts
    add_meta_box(
        'vietfarmy_image_url',
        '🔗 Ảnh từ URL',
        'vietfarmy_image_url_meta_box_callback',
        'post',
        'side',
        'default'
    );

    // Cho Pages
    add_meta_box(
        'vietfarmy_image_url',
        '🔗 Ảnh từ URL',
        'vietfarmy_image_url_meta_box_callback',
        'page',
        'side',
        'default'
    );

    // Cho WooCommerce Products
    if ( class_exists('WooCommerce') ) {
        add_meta_box(
            'vietfarmy_image_url',
            '🔗 Ảnh sản phẩm từ URL',
            'vietfarmy_image_url_meta_box_callback',
            'product',
            'side',
            'default'
        );
    }
}

function vietfarmy_image_url_meta_box_callback( $post ) {
    wp_nonce_field('vietfarmy_save_image_url', 'vietfarmy_image_url_nonce');
    $value = get_post_meta($post->ID, '_vietfarmy_featured_image_url', true);
    ?>
    <style>
        .vietfarmy-image-url-wrapper {
            margin: 15px 0;
        }
        .vietfarmy-image-url-wrapper label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #1d2327;
        }
        .vietfarmy-image-url-wrapper input[type="url"] {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #8c8c8c;
            border-radius: 4px;
            font-size: 13px;
        }
        .vietfarmy-image-url-wrapper input[type="url"]:focus {
            border-color: #2271b1;
            box-shadow: 0 0 0 1px #2271b1;
            outline: none;
        }
        .vietfarmy-image-preview {
            margin-top: 10px;
            max-width: 100%;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .vietfarmy-image-preview img {
            max-width: 100%;
            height: auto;
            display: block;
            margin-top: 5px;
        }
        .vietfarmy-help-text {
            font-size: 12px;
            color: #646970;
            margin-top: 5px;
        }
        .vietfarmy-btn {
            display: inline-block;
            margin-top: 8px;
            padding: 6px 12px;
            background: #2271b1;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }
        .vietfarmy-btn:hover {
            background: #135e96;
        }
        .vietfarmy-btn-secondary {
            background: #f0f0f1;
            color: #1d2327;
        }
        .vietfarmy-btn-secondary:hover {
            background: #dcdcde;
        }
        .vietfarmy-current-image {
            background: #f0f0f1;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .vietfarmy-current-image strong {
            display: block;
            margin-bottom: 5px;
            font-size: 12px;
            color: #646970;
        }
    </style>

    <div class="vietfarmy-image-url-wrapper">
        <label for="vietfarmy_featured_image_url">📎 Đường dẫn ảnh:</label>
        <input type="url"
               id="vietfarmy_featured_image_url"
               name="vietfarmy_featured_image_url"
               value="<?php echo esc_url($value); ?>"
               placeholder="https://example.com/image.jpg"
               class="widefat">

        <?php if ($value) : ?>
            <div class="vietfarmy-image-preview">
                <strong>Ảnh hiện tại:</strong>
                <img src="<?php echo esc_url($value); ?>" alt="Preview">
                <button type="button" class="vietfarmy-btn vietfarmy-btn-secondary" id="vietfarmy_remove_image_url">✕ Xóa ảnh URL</button>
            </div>
        <?php else : ?>
            <p class="vietfarmy-help-text">Dán link ảnh vào ô trên, sau đó click "Đặt làm ảnh đại diện"</p>
        <?php endif; ?>

        <button type="button" class="vietfarmy-btn" id="vietfarmy_set_image_url">✅ Đặt làm ảnh đại diện</button>
    </div>

    <script>
    jQuery(document).ready(function($) {
        var $input = $('#vietfarmy_featured_image_url');
        var $preview = $('.vietfarmy-image-preview');

        // Khi paste URL
        $input.on('blur paste', function() {
            var url = $(this).val();
            if (url && url.match(/\.(jpg|jpeg|png|gif|webp|svg)$/i)) {
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'vietfarmy_preview_image_url',
                        url: url,
                        post_id: <?php echo get_the_ID(); ?>,
                        nonce: '<?php echo wp_create_nonce('vietfarmy_image_url_nonce'); ?>'
                    },
                    success: function(response) {
                        if (response.success && response.data.preview) {
                            location.reload();
                        }
                    }
                });
            }
        });

        // Nút đặt ảnh
        $('#vietfarmy_set_image_url').on('click', function() {
            var url = $input.val();
            if (!url) {
                alert('Vui lòng nhập URL ảnh!');
                return;
            }

            $(this).text('⏳ Đang xử lý...');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'vietfarmy_set_featured_image_from_url',
                    url: url,
                    post_id: <?php echo get_the_ID(); ?>,
                    nonce: '<?php echo wp_create_nonce('vietfarmy_image_url_nonce'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Lỗi: ' + response.data);
                        $('#vietfarmy_set_image_url').text('✅ Đặt làm ảnh đại diện');
                    }
                },
                error: function() {
                    alert('Đã xảy ra lỗi!');
                    $('#vietfarmy_set_image_url').text('✅ Đặt làm ảnh đại diện');
                }
            });
        });

        // Nút xóa
        $('#vietfarmy_remove_image_url').on('click', function() {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'vietfarmy_remove_featured_image_url',
                    post_id: <?php echo get_the_ID(); ?>,
                    nonce: '<?php echo wp_create_nonce('vietfarmy_image_url_nonce'); ?>'
                },
                success: function() {
                    location.reload();
                }
            });
        });
    });
    </script>
    <?php
}

// Lưu URL
add_action('save_post', 'vietfarmy_save_image_url');

function vietfarmy_save_image_url($post_id) {
    if (!isset($_POST['vietfarmy_image_url_nonce']) ||
        !wp_verify_nonce($_POST['vietfarmy_image_url_nonce'], 'vietfarmy_save_image_url')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['vietfarmy_featured_image_url'])) {
        $url = esc_url_raw($_POST['vietfarmy_featured_image_url']);
        update_post_meta($post_id, '_vietfarmy_featured_image_url', $url);

        // Nếu có URL và chưa có featured image thì tự động tải lên
        if ($url && !has_post_thumbnail($post_id)) {
            vietfarmy_set_featured_image_from_url($url, $post_id);
        }
    }
}

// AJAX: Đặt ảnh từ URL
add_action('wp_ajax_vietfarmy_set_featured_image_from_url', 'vietfarmy_ajax_set_featured_image');

function vietfarmy_ajax_set_featured_image() {
    check_ajax_referer('vietfarmy_image_url_nonce', 'nonce');

    $url = isset($_POST['url']) ? esc_url_raw($_POST['url']) : '';
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if (!$url || !$post_id) {
        wp_send_json_error('Thiếu thông tin');
    }

    $result = vietfarmy_set_featured_image_from_url($url, $post_id);

    if ($result) {
        wp_send_json_success($result);
    } else {
        wp_send_json_error('Không thể tải ảnh lên');
    }
}

// AJAX: Xóa ảnh URL
add_action('wp_ajax_vietfarmy_remove_featured_image_url', 'vietfarmy_ajax_remove_featured_image');

function vietfarmy_ajax_remove_featured_image() {
    check_ajax_referer('vietfarmy_image_url_nonce', 'nonce');

    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if ($post_id) {
        delete_post_meta($post_id, '_vietfarmy_featured_image_url');
        delete_post_meta($post_id, '_vietfarmy_featured_image_id');

        // Xóa featured image nếu là ảnh đã tải lên
        $image_id = get_post_meta($post_id, '_vietfarmy_featured_image_id', true);
        if ($image_id) {
            wp_delete_attachment($image_id, true);
        }

        wp_send_json_success();
    }

    wp_send_json_error();
}

// Hàm chính: Tải ảnh từ URL và đặt làm featured image
function vietfarmy_set_featured_image_from_url($url, $post_id) {
    if (!function_exists('media_handle_sideload')) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
    }

    // Kiểm tra URL hợp lệ
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return false;
    }

    // Lấy nội dung ảnh
    $response = wp_remote_get($url, array(
        'timeout' => 30,
        'stream' => true,
        'filename' => get_temp_dir() . basename($url)
    ));

    if (is_wp_error($response)) {
        return false;
    }

    $tmp_file = wp_remote_retrieve_body($response);
    if (empty($tmp_file)) {
        return false;
    }

    // Lấy filename
    $url_path = parse_url($url, PHP_URL_PATH);
    $filename = basename($url_path);

    // Nếu không có extension, đoán từ content-type
    if (!pathinfo($filename, PATHINFO_EXTENSION)) {
        $content_type = wp_remote_retrieve_header($response, 'content-type');
        $ext = '';
        if ($content_type) {
            $mime_types = array(
                'image/jpeg' => 'jpg',
                'image/jpg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp',
            );
            $ext = isset($mime_types[$content_type]) ? $mime_types[$content_type] : '';
        }
        if ($ext) {
            $filename .= '.' . $ext;
        }
    }

    // Upload file
    $file_array = array(
        'name' => $filename,
        'tmp_name' => $tmp_file
    );

    $attachment_id = media_handle_sideload($file_array, $post_id);

    if (is_wp_error($attachment_id)) {
        // Thử cách khác - tải trực tiếp
        $attachment_id = vietfarmy_download_image_direct($url, $post_id);
    }

    if (!is_wp_error($attachment_id) && $attachment_id) {
        // Lưu image ID để có thể xóa sau
        update_post_meta($post_id, '_vietfarmy_featured_image_id', $attachment_id);

        // Đặt làm featured image
        set_post_thumbnail($post_id, $attachment_id);

        return array(
            'id' => $attachment_id,
            'url' => wp_get_attachment_url($attachment_id)
        );
    }

    return false;
}

// Cách khác: Tải ảnh trực tiếp bằng cURL
function vietfarmy_download_image_direct($url, $post_id) {
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    // Tải ảnh về server
    $tmp = download_url($url, 30);

    if (is_wp_error($tmp)) {
        return false;
    }

    // Lấy filename
    $filename = basename(parse_url($url, PHP_URL_PATH));
    if (!preg_match('/\.[a-zA-Z0-9]+$/', $filename)) {
        $filename .= '.jpg';
    }

    $file_array = array(
        'name' => $filename,
        'tmp_name' => $tmp
    );

    $attachment_id = media_handle_sideload($file_array, $post_id);

    // Xóa file tạm
    @unlink($tmp);

    return $attachment_id;
}

// Shortcode hiển thị sản phẩm với ảnh từ URL
add_shortcode('vietfarmy_product', function($atts) {
    $atts = shortcode_atts(array(
        'url' => '',
        'title' => '',
        'price' => '',
        'link' => ''
    ), $atts);

    if (!$atts['url']) return '';

    $output = '<div class="vietfarmy-product-card">';
    $output .= '<img src="' . esc_url($atts['url']) . '" alt="' . esc_attr($atts['title']) . '">';
    if ($atts['title']) {
        $output .= '<h3>' . esc_html($atts['title']) . '</h3>';
    }
    if ($atts['price']) {
        $output .= '<p class="price">' . esc_html($atts['price']) . '</p>';
    }
    if ($atts['link']) {
        $output .= '<a href="' . esc_url($atts['link']) . '" class="btn">Xem chi tiết</a>';
    }
    $output .= '</div>';

    return $output;
});
