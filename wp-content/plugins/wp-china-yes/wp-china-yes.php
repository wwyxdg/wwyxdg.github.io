<?php
/**
 * Plugin Name: WP-China-Yes
 * Description: 将你的WordPress接入本土生态体系中，这将为你提供一个更贴近中国人使用习惯的WordPress
 * Author: WP中国本土化社区
 * Author URI:https://wp-china.org/
 * Version: 3.1.2
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if (is_admin() && !(defined('DOING_AJAX') && DOING_AJAX)) {
    require __DIR__ . '/setting.php';

    add_filter(sprintf('%splugin_action_links_%s', is_multisite() ? 'network_admin_' : '', plugin_basename(__FILE__)), function ($links) {
        return array_merge(
            [sprintf('<a href="%s">%s</a>', network_admin_url(is_multisite() ? 'settings.php?page=wp-china-yes' : 'options-general.php?page=wp-china-yes'), '设置')],
            $links
        );
    });

    if (empty(get_option('wpapi')) || empty(get_option('super_admin')) || empty(get_option('super_gravatar')) || empty(get_option('super_googlefonts'))) {
        update_option("wpapi", get_option('wpapi') ?: '2');
        update_option("super_admin", get_option('super_admin') ?: '1');
        update_option("super_gravatar", get_option('super_gravatar') ?: '1');
        update_option("super_googlefonts", get_option('super_googlefonts') ?: '2');
    }

    register_deactivation_hook(__FILE__, function () {
        delete_option("wpapi");
        delete_option("super_admin");
        delete_option("super_gravatar");
        delete_option("super_googlefonts");
    });

    add_action(is_multisite() ? 'network_admin_menu' : 'admin_menu', function () {
        add_submenu_page(
            is_multisite() ? 'settings.php' : 'options-general.php',
            'WP-China-Yes',
            'WP-China-Yes',
            is_multisite() ? 'manage_network_options' : 'manage_options',
            'wp-china-yes',
            'wpcy_options_page_html'
        );
    });

    if (get_option('super_admin') == 1) {
        add_action('init', function () {
            ob_start(function ($buffer) {
                return preg_replace('~' . home_url('/') . '(wp-admin|wp-includes)/(css|js)/~', sprintf('https://a2.wp-china-yes.net/WordPress@%s/$1/$2/', $GLOBALS['wp_version']), $buffer);
            });
        });
    }
}


if (is_admin()) {
    add_filter('pre_http_request', function ($preempt, $r, $url) {
        if ((!stristr($url, 'api.wordpress.org') && !stristr($url, 'downloads.wordpress.org')) || get_option('wpapi') == 3) {
            return false;
        }
        if (get_option('wpapi') == 1) {
            $url = str_replace('api.wordpress.org', 'api.wp-china-yes.net', $url);
            $url = str_replace('downloads.wordpress.org', 'download.wp-china-yes.net', $url);
        } else {
            $url = str_replace('api.wordpress.org', 'api.w.org.ibadboy.net', $url);
            $url = str_replace('downloads.wordpress.org', 'd.w.org.ibadboy.net', $url);
        }

        return wp_remote_request($url, $r);
    }, 10, 3);
}


if (!(defined('DOING_AJAX') && DOING_AJAX)) {
    if (get_option('super_gravatar') == 1) {
        add_filter('get_avatar', function ($avatar) {
            return str_replace([
                'www.gravatar.com',
                '0.gravatar.com',
                '1.gravatar.com',
                '2.gravatar.com',
                'secure.gravatar.com',
                'cn.gravatar.com'
            ], 'gravatar.wp-china-yes.net', $avatar);
        });
    }

    if (get_option('super_googlefonts') == 1) {
        add_action('init', function () {
            ob_start(function ($buffer) {
                return str_replace('fonts.googleapis.com', 'googlefonts.wp-china-yes.net', $buffer);
            });
        });
    }
}
