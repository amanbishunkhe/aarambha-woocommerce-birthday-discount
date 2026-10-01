<?php

namespace AarambhaWooCommerceBirthdayDiscount\Core;

use AarambhaWooCommerceBirthdayDiscount\Database\Database;

defined( 'ABSPATH' ) || exit;

/**
 * Handles plugin activation.
 */
class Activator {

	/**
	 * Run plugin activation tasks.
	 *
	 * @return void
	 */
	public static function activate(): void {

		Database::create_tables();
	}
}