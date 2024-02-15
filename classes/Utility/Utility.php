<?php

namespace MultiVendorX\Utility;

use Automattic\WooCommerce\Utilities\OrderUtil as WCOrderUtil;

defined('ABSPATH') || exit;

/**
 * MVX Utility class
 *
 * @version		2.2.0
 * @package		MultivendorX
 * @author 		MultiVendorX
 */

class Utility {

    /**
     * LOG method of MultiVendorX.
     * @param string
     * @return void
     */
    public static function LOG($string) {
        global $MVX;
        $file = $MVX->plugin_path . 'log/product_vendor.log';
        if (file_exists($file)) {
            // Open the file to get existing content
            $current = file_get_contents($file);
            if ($current) {
                // Append a new content to the file
                $current .= "$string" . "\r\n";
                $current .= "-------------------------------------\r\n";
            } else {
                $current = "$string" . "\r\n";
                $current .= "-------------------------------------\r\n";
            }
            // Write the contents back to the file
            file_put_contents($file, $current);
        }
    }

    /**
     * Helper function to get whether custom order tables are enabled or not.
     * This method can be removed, and we can directly use WC OrderUtil::custom_orders_table_usage_is_enabled method in future
     * if we set the minimum wc version requirements to 8.0
     * @return bool
     */
    public static function is_hpos_enabled(): bool {
        return version_compare( WC_VERSION, '8.2.0', '>=' ) ? WCOrderUtil::custom_orders_table_usage_is_enabled() : false;
    }
}