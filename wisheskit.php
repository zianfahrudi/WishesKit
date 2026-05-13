<?php
/**
 * Plugin Name: WishesKit
 * Description: Wishes & RSVP plugin with Elementor widget. Supports comments, reply, attendance confirmation, and full style customization.
 * Version: 1.3.0
 * Author: WishesKit
 * Text Domain: wisheskit
 * Requires PHP: 7.4
 * Requires at least: 5.8
 */

if (!defined('ABSPATH')) {
    exit;
}

define('WISHESKIT_VERSION', '1.3.0');
define('WISHESKIT_PATH', plugin_dir_path(__FILE__));
define('WISHESKIT_URL', plugin_dir_url(__FILE__));

/**
 * Register custom comment meta for RSVP and WishesKit marker
 */
function wisheskit_register_meta() {
    register_meta('comment', 'wisheskit_rsvp', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    register_meta('comment', '_wisheskit_comment', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => false,
        'sanitize_callback' => 'sanitize_text_field',
    ]);
}
add_action('init', 'wisheskit_register_meta');

/**
 * Add RSVP column to comments admin
 */
function wisheskit_add_comment_columns($columns) {
    $columns['wisheskit_rsvp'] = __('RSVP', 'wisheskit');
    return $columns;
}
add_filter('manage_edit-comments_columns', 'wisheskit_add_comment_columns');

function wisheskit_comment_column_content($column, $comment_id) {
    if ($column === 'wisheskit_rsvp') {
        $rsvp = get_comment_meta($comment_id, 'wisheskit_rsvp', true);
        $labels = [
            'hadir' => '<span style="color:green;">&#10003; Hadir</span>',
            'tidak_hadir' => '<span style="color:red;">&#10007; Tidak Hadir</span>',
            'ragu' => '<span style="color:orange;">? Masih Ragu</span>',
        ];
        echo isset($labels[$rsvp]) ? $labels[$rsvp] : '&mdash;';
    }
}
add_action('manage_comments_custom_column', 'wisheskit_comment_column_content', 10, 2);

/**
 * Enqueue frontend assets
 */
function wisheskit_enqueue_assets() {
    wp_register_style('wisheskit-style', WISHESKIT_URL . 'assets/css/wisheskit.css', [], WISHESKIT_VERSION);
    wp_register_script('wisheskit-script', WISHESKIT_URL . 'assets/js/wisheskit.js', ['jquery'], WISHESKIT_VERSION, true);
    wp_localize_script('wisheskit-script', 'wisheskit_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('wisheskit_nonce'),
        'is_admin' => current_user_can('moderate_comments') ? '1' : '',
    ]);
}
add_action('wp_enqueue_scripts', 'wisheskit_enqueue_assets');

/**
 * Get user IP address
 */
function wisheskit_get_user_ip() {
    $ip = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Can contain multiple IPs, take the first one
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim($ips[0]);
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return sanitize_text_field($ip);
}

/**
 * AJAX: Submit comment
 */
function wisheskit_submit_comment() {
    check_ajax_referer('wisheskit_nonce', 'nonce');

    $post_id = intval($_POST['post_id'] ?? 0);
    $name = sanitize_text_field($_POST['name'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');
    $rsvp = sanitize_text_field($_POST['rsvp'] ?? '');
    $parent = intval($_POST['parent'] ?? 0);

    if (empty($name) || empty($message)) {
        wp_send_json_error(['message' => 'Nama dan ucapan harus diisi.']);
    }

    // For replies, rsvp is optional
    if ($parent === 0 && empty($rsvp)) {
        wp_send_json_error(['message' => 'Konfirmasi kehadiran harus dipilih.']);
    }

    $allowed_rsvp = ['hadir', 'tidak_hadir', 'ragu', ''];
    if (!in_array($rsvp, $allowed_rsvp, true)) {
        wp_send_json_error(['message' => 'Pilihan konfirmasi tidak valid.']);
    }

    // Prevent duplicate submission (same name + message within last 5 seconds)
    $recent = get_comments([
        'post_id' => $post_id,
        'type' => '',
        'meta_key' => '_wisheskit_comment',
        'meta_value' => '1',
        'number' => 5,
        'date_query' => [
            [
                'after' => '5 seconds ago',
            ],
        ],
    ]);
    foreach ($recent as $r) {
        if ($r->comment_author === $name && $r->comment_content === $message && intval($r->comment_parent) === $parent) {
            wp_send_json_error(['message' => 'Duplikat terdeteksi. Ucapan sudah terkirim sebelumnya.']);
        }
    }

    $comment_data = [
        'comment_post_ID' => $post_id,
        'comment_author' => $name,
        'comment_author_email' => '',
        'comment_author_url' => '',
        'comment_author_IP' => wisheskit_get_user_ip(),
        'comment_content' => $message,
        'comment_type' => '',
        'comment_parent' => $parent,
        'comment_approved' => 1,
        'user_id' => 0,
    ];

    $comment_id = wp_insert_comment($comment_data);

    if ($comment_id) {
        // Mark as WishesKit comment
        update_comment_meta($comment_id, '_wisheskit_comment', '1');
        if (!empty($rsvp)) {
            update_comment_meta($comment_id, 'wisheskit_rsvp', $rsvp);
        }
        wp_send_json_success(['message' => 'Ucapan berhasil dikirim!', 'comment_id' => $comment_id]);
    } else {
        wp_send_json_error(['message' => 'Gagal mengirim ucapan.']);
    }
}
add_action('wp_ajax_wisheskit_submit', 'wisheskit_submit_comment');
add_action('wp_ajax_nopriv_wisheskit_submit', 'wisheskit_submit_comment');

/**
 * AJAX: Load comments
 */
function wisheskit_load_comments() {
    check_ajax_referer('wisheskit_nonce', 'nonce');

    $post_id = intval($_POST['post_id'] ?? 0);
    $page = max(1, intval($_POST['page'] ?? 1));
    $per_page = max(1, intval($_POST['per_page'] ?? 15));
    $offset = ($page - 1) * $per_page;

    // Paginated parent comments
    $comments = get_comments([
        'post_id' => $post_id,
        'meta_key' => '_wisheskit_comment',
        'meta_value' => '1',
        'status' => 'approve',
        'parent' => 0,
        'orderby' => 'comment_date',
        'order' => 'DESC',
        'number' => $per_page,
        'offset' => $offset,
    ]);

    $result = wisheskit_format_comments($comments);

    // Count total parents (for pagination)
    $total_parents = get_comments([
        'post_id' => $post_id,
        'meta_key' => '_wisheskit_comment',
        'meta_value' => '1',
        'status' => 'approve',
        'parent' => 0,
        'count' => true,
    ]);

    // Count RSVP across ALL parent comments (not just current page)
    $all_parents = get_comments([
        'post_id' => $post_id,
        'meta_key' => '_wisheskit_comment',
        'meta_value' => '1',
        'status' => 'approve',
        'parent' => 0,
        'fields' => 'ids',
    ]);

    $counts = ['hadir' => 0, 'tidak_hadir' => 0, 'ragu' => 0];
    foreach ($all_parents as $cid) {
        $rsvp = get_comment_meta($cid, 'wisheskit_rsvp', true);
        if (isset($counts[$rsvp])) {
            $counts[$rsvp]++;
        }
    }

    $total_pages = $per_page > 0 ? (int) ceil($total_parents / $per_page) : 1;

    wp_send_json_success([
        'comments' => $result,
        'counts' => $counts,
        'total' => intval($total_parents),
        'page' => $page,
        'per_page' => $per_page,
        'total_pages' => max(1, $total_pages),
    ]);
}
add_action('wp_ajax_wisheskit_load', 'wisheskit_load_comments');
add_action('wp_ajax_nopriv_wisheskit_load', 'wisheskit_load_comments');

/**
 * Format comments recursively
 */
function wisheskit_format_comments($comments) {
    $result = [];
    foreach ($comments as $comment) {
        $rsvp = get_comment_meta($comment->comment_ID, 'wisheskit_rsvp', true);

        $children = get_comments([
            'parent' => $comment->comment_ID,
            'meta_key' => '_wisheskit_comment',
            'meta_value' => '1',
            'status' => 'approve',
            'orderby' => 'comment_date',
            'order' => 'ASC',
        ]);

        $result[] = [
            'id' => $comment->comment_ID,
            'author' => $comment->comment_author,
            'content' => nl2br(esc_html($comment->comment_content)),
            'raw_content' => $comment->comment_content,
            'date' => human_time_diff(strtotime($comment->comment_date), current_time('timestamp')) . ' lalu',
            'rsvp' => $rsvp,
            'children' => wisheskit_format_comments($children),
        ];
    }
    return $result;
}

/**
 * AJAX: Delete comment (admin only)
 */
function wisheskit_delete_comment() {
    check_ajax_referer('wisheskit_nonce', 'nonce');

    if (!current_user_can('moderate_comments')) {
        wp_send_json_error(['message' => 'Anda tidak memiliki izin.']);
    }

    $comment_id = intval($_POST['comment_id'] ?? 0);

    if (!$comment_id) {
        wp_send_json_error(['message' => 'ID komentar tidak valid.']);
    }

    // Delete child comments first
    $children = get_comments([
        'parent' => $comment_id,
        'meta_key' => '_wisheskit_comment',
        'meta_value' => '1',
    ]);
    foreach ($children as $child) {
        wp_delete_comment($child->comment_ID, true);
    }

    $deleted = wp_delete_comment($comment_id, true);

    if ($deleted) {
        wp_send_json_success(['message' => 'Komentar berhasil dihapus.']);
    } else {
        wp_send_json_error(['message' => 'Gagal menghapus komentar.']);
    }
}
add_action('wp_ajax_wisheskit_delete', 'wisheskit_delete_comment');

/**
 * AJAX: Edit comment (admin only)
 */
function wisheskit_edit_comment() {
    check_ajax_referer('wisheskit_nonce', 'nonce');

    if (!current_user_can('moderate_comments')) {
        wp_send_json_error(['message' => 'Anda tidak memiliki izin.']);
    }

    $comment_id = intval($_POST['comment_id'] ?? 0);
    $content = sanitize_textarea_field($_POST['content'] ?? '');

    if (!$comment_id || empty($content)) {
        wp_send_json_error(['message' => 'Data tidak valid.']);
    }

    $updated = wp_update_comment([
        'comment_ID' => $comment_id,
        'comment_content' => $content,
    ]);

    if ($updated) {
        wp_send_json_success(['message' => 'Komentar berhasil diperbarui.']);
    } else {
        wp_send_json_error(['message' => 'Gagal memperbarui komentar.']);
    }
}
add_action('wp_ajax_wisheskit_edit', 'wisheskit_edit_comment');

/**
 * Migrate old WishesKit comments (comment_type = 'wisheskit') to new format
 * Runs once on plugin activation or admin init
 */
function wisheskit_migrate_old_comments() {
    if (get_option('wisheskit_migrated_v13')) {
        return;
    }

    $old_comments = get_comments([
        'type' => 'wisheskit',
        'number' => 0,
    ]);

    foreach ($old_comments as $comment) {
        // Add meta marker
        update_comment_meta($comment->comment_ID, '_wisheskit_comment', '1');
        // Change comment type to empty (standard)
        wp_update_comment([
            'comment_ID' => $comment->comment_ID,
            'comment_type' => '',
        ]);
    }

    update_option('wisheskit_migrated_v13', true);
}
add_action('admin_init', 'wisheskit_migrate_old_comments');

/**
 * Load Elementor widget
 */
function wisheskit_elementor_init() {
    if (did_action('elementor/loaded')) {
        add_action('elementor/widgets/register', 'wisheskit_register_widget');
    }
}
add_action('plugins_loaded', 'wisheskit_elementor_init');

function wisheskit_register_widget($widgets_manager) {
    require_once WISHESKIT_PATH . 'includes/class-wisheskit-widget.php';
    $widgets_manager->register(new \WishesKit_Elementor_Widget());
}
