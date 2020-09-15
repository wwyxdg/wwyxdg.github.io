<?php

/*
            /$$
    /$$    /$$$$
   | $$   |_  $$    /$$$$$$$
 /$$$$$$$$  | $$   /$$_____/
|__  $$__/  | $$  |  $$$$$$
   | $$     | $$   \____  $$
   |__/    /$$$$$$ /$$$$$$$/
          |______/|_______/
================================
        Keep calm and get rich.
                    Is the best.

  	@Author: Dami
  	@Date:   2018-10-08 14:21:45
 * @Last Modified by: suxing
 * @Last Modified time: 2019-07-23 15:06:13
  	Plugin Name: nicetheme Font Awesome
	Plugin URI: https://www.nicetheme.cn/modules/font-awesome-jimu
	Description: 添加 Font Awesome 支持
	Version: 1.0.0
	Author URI: http://www.nicetheme.cn
	Nicetheme Module: nice-fontawesome
	Compatible: 
*/

define( 'NC_FA_DIR', dirname(__FILE__) );
define( 'NC_FA_RELATIVE_DIR', NC_FA_DIR );
define( 'NC_FA_URI', plugin_dir_url(__FILE__));
define( 'NC_FA_VERSION', '1.0.0' );
define( 'NC_FA__FILE__', __FILE__ );

// nc store check
function nc_fa_init() {

	if( !defined('NC_STORE_ROOT_PATH') ) {
		add_action( 'admin_notices', 'nc_fa_init_check' );
		function nc_fa_init_check() {
			$html = '<div class="notice notice-error">
				<p><b>错误：</b> Font Awesome 积木 缺少依赖插件 <code>nicetheme 积木</code> 请先安装并启用 <code>nicetheme 积木</code> 插件。</p>
			</div>';
			echo $html;
		}
	} else {
		add_filter('nc_save_json_paths', 'nc_fa_acf_json_save_point');
		function nc_fa_acf_json_save_point( $path ) {
			$path[] = NC_FA_DIR . '/functions/conf';
			return $path;
		}
	
		add_filter('acf/settings/load_json', 'nc_fa_acf_json_load_point');
		function nc_fa_acf_json_load_point( $paths ) {
			$paths[] = NC_FA_DIR . '/functions/conf';
			return $paths;
		}

		acf_add_options_sub_page(
			array(
				'page_title'      => 'Font Awesome 积木',
				'menu_title'      => 'Font Awesome 积木',
				'menu_slug'       => 'nc-fa-options',
				'parent_slug'     => 'nc-modules-store',
				'capability'      => 'manage_options',
				'update_button'   => '保存',
				'updated_message' => '设置已保存！'
			)
		);
		require_once NC_FA_DIR . '/functions/static-load.php';
	}
}
add_action( 'plugins_loaded', 'nc_fa_init', 999 );
