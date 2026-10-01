<?php
/**
 * Plugin Name: Aarambha WooCommerce Birthday Discount
 * Description: Automatically gives eligible WooCommerce customers a percentage discount during their birthday period.
 * Version: 1.0.0
 * Author: RWS
 * Text Domain: aarambha-woocommerce-birthday-discount
 * Requires Plugins: woocommerce
 */

defined( 'ABSPATH' ) || exit;

define( 'AWBD_VERSION', '1.0.0' );
define( 'AWBD_FILE', __FILE__ );
define( 'AWBD_PATH', plugin_dir_path( __FILE__ ) );
define( 'AWBD_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load Composer autoloader.
 */
require_once AWBD_PATH . 'vendor/autoload.php';

use AarambhaWooCommerceBirthdayDiscount\Core\Activator;
register_activation_hook(
	AWBD_FILE,
	array( Activator::class, 'activate' )
);

/**
 * Initialize the plugin.
 */
add_action(
    'plugins_loaded',
    static function () {
        new AarambhaWooCommerceBirthdayDiscount\Core\Plugin();
    }
);

