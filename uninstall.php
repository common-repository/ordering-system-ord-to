<?php

if ( ! defined('WP_UNINSTALL_PLUGIN')) exit;

$settingOptions = array('ordto_api_key', 'ordto_site_url', 'ordto_view_mode', 'ordto_page_number_products', 'ordto_page_number_orders');

foreach ($settingOptions as $settingName) {
    delete_option($settingName);
}
