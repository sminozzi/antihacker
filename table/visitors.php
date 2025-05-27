<?php
/**
 * @author William Sergio Minossi
 * @copyright 2020
 */
if (!defined('ABSPATH'))
  exit; // Exit if accessed directly

global $wpdb;
add_action('admin_menu', 'antihacker_add_menu_items2');
// add_action('wp_head', 'antibots_ajaxurl');

function antihacker_add_menu_items2()
{
  $sbb_table_page = add_submenu_page(
    'anti_hacker_plugin', // $parent_slug
    'Blocked Visits Log', // string $page_title
    'Blocked Visits Log', // string $menu_title
    'manage_options', // string $capability
    'antihacker_my-custom-submenu-page',
    'antihacker_render_list_page'
  );
}
function antihacker_render_list_page()
{
  require_once ANTIHACKERPATH . 'table/visitors_render.php';
}