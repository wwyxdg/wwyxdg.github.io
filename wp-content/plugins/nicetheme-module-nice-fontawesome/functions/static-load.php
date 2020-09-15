<?php
function nc_fa_load()
{
    $type = get_field('load_type', 'option') ?: '';
    $styles = get_field('load_styles', 'option') ?: array();

    wp_register_style('font-awesome', NC_FA_URI . 'assets/css/fontawesome.min.css');
    wp_register_style('font-awesome-brands', NC_FA_URI . 'assets/css/brands.min.css');
    wp_register_style('font-awesome-regular', NC_FA_URI . 'assets/css/regular.min.css');
    wp_register_style('font-awesome-solid', NC_FA_URI . 'assets/css/solid.min.css');

    wp_register_script('font-awesome', NC_FA_URI . 'assets/js/fontawesome.min.js', array(), NC_FA_VERSION, true);
    wp_register_script('font-awesome-brands', NC_FA_URI . 'assets/js/brands.min.js', array(), NC_FA_VERSION, true);
    wp_register_script('font-awesome-regular', NC_FA_URI . 'assets/js/regular.min.js', array(), NC_FA_VERSION, true);
    wp_register_script('font-awesome-solid', NC_FA_URI . 'assets/js/solid.min.js', array(), NC_FA_VERSION, true);

    if (!$type || !count($styles)) return;
    
    if ($type === 'css') {
        wp_enqueue_style('font-awesome');
        foreach ($styles as $style) {
            wp_enqueue_style("font-awesome-$style");
        }
        return;
    }

    wp_enqueue_script('font-awesome');
    foreach ($styles as $style) {
        wp_enqueue_script("font-awesome-$style");
    }
}
add_action('wp_enqueue_scripts', 'nc_fa_load');


function nc_fa_load_admin()
{
    wp_register_script('font-awesome-all', NC_FA_URI . 'assets/js/all.min.js', array(), NC_FA_VERSION, true);
    wp_register_script('font-awesome-icons', NC_FA_URI . 'assets/js/icons.js', array('jquery'), NC_FA_VERSION, true);
    wp_enqueue_script('font-awesome-all');
    wp_enqueue_script('font-awesome-icons');
}
add_action('admin_enqueue_scripts', 'nc_fa_load_admin');

function nc_fa_script_loader_tag($tag, $handle)
{
    if (strpos($handle, 'font-awesome') !== false) {
        $tag = str_replace('<script ', '<script defer ', $tag);
    }

    return $tag;
}
add_filter('script_loader_tag', 'nc_fa_script_loader_tag', 10, 2);
