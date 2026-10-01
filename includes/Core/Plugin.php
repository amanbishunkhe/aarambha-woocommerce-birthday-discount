<?php

namespace AarambhaWooCommerceBirthdayDiscount\Core;

use AarambhaWooCommerceBirthdayDiscount\Services\BirthdayService;
use AarambhaWooCommerceBirthdayDiscount\WooCommerce\Account;

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin class.
 */
class Plugin {

    /**
     * Plugin loader.
     *
     * @var Loader
     */
    private Loader $loader;

    /**
     * Constructor.
     */
    public function __construct() {
        	
        $this->loader = new Loader();
        $this->init();
        $this->loader->run();
    }

    /**
     * Initialize plugin components.
     *
     * @return void
     */
    private function init(): void {
        $birthday_service = new BirthdayService();
        $account = new Account( $birthday_service );
        $account->register_hooks( $this->loader );
    }

    /**
     * Get the plugin loader.
     *
     * @return Loader
     */
    public function get_loader(): Loader {
        return $this->loader;
    }
}