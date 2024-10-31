<?php
/*
 * Plugin Name:       Online ordering System - ord.to
 * Description:       Add menu or ordering widget to your website, manage your product list and start receiving food orders from your clients.
 * Version:           1.0.3
 * Requires at least: 5.6
 * Requires PHP:      7.1
 * Author:            Getreve Ltd
 * Author URI:        https://getreve.com/
 * Text Domain:       ordto
 * License:     GPLv2+

Online ordering System - ord.to is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Online ordering System - ord.to is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Online ordering System - ord.to. If not, see https://www.gnu.org/licenses/gpl-3.0.html.
 */

if (!defined('ABSPATH')) {
    die;
}

require_once __DIR__ . '/includes/ords.php';
require_once __DIR__ . '/includes/items_view.php';
require_once __DIR__ . '/includes/prods.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/menu_or_widget.php';

function ordto_register_assets_is_admin()
{
    wp_register_style('ordto_style', plugins_url('admin/css/style.css', __FILE__), false, time());
}

function ordto_register_assets_isnt_admin()
{
    wp_register_script('ordto_script', plugins_url('admin/js/add-menu-script.js', __FILE__), false, time());
}

function ordto_enqueue_assets_is_admin($hook)
{
    if ($hook != 'toplevel_page_ordto-config') {
        if ($hook != 'ord-to_page_products') {
            if ($hook != 'ord-to_page_orders') {
                wp_deregister_style('ordto_style');

                return;
            }
        }
    }
    wp_enqueue_style('ordto_style');
}

function ordto_enqueue_assets_isnt_admin()
{
    wp_enqueue_script('ordto_script');
}

function ordto_show_new_items()
{
    $title = 'Ordering system configuration';
    if (current_user_can('manage_options')) {
        add_menu_page(
            esc_html__($title),
            esc_html__('Ord.to'),
            'manage_options',
            'ordto-config',
            'ordto_add_config',
            'dashicons-clipboard',
            3
        );

        add_submenu_page(
            'ordto-config',
            esc_html__($title),
            esc_html__('Configuration', 'ord_sys'),
            'manage_options',
            'ordto-config',
            'ordto_add_config'
        );

        add_submenu_page(
            'ordto-config',
            esc_html__('Products'),
            esc_html__('Products', 'ord_sys'),
            'manage_options',
            'products',
            'ordto_view_products'
        );

        add_submenu_page(
            'ordto-config',
            esc_html__('Orders'),
            esc_html__('Orders', 'ord_sys'),
            'manage_options',
            'orders',
            'ordto_view_orders'
        );
    }
}

if (is_admin()) {
    add_action('admin_enqueue_scripts', 'ordto_register_assets_is_admin');
    add_action('admin_enqueue_scripts', 'ordto_enqueue_assets_is_admin');
    add_action('admin_menu', 'ordto_show_new_items');
}

if (!is_admin()) {
    add_action('wp_enqueue_scripts', 'ordto_register_assets_isnt_admin');
    ordto_view_public();
}
