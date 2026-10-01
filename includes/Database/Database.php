<?php

namespace AarambhaWooCommerceBirthdayDiscount\Database;

defined( 'ABSPATH' ) || exit;

/**
 * Handles database operations for the birthday discount plugin.
 */
class Database {

	/**
	 * Get the birthday table name.
	 *
	 * @return string
	 */
	public static function get_birthdays_table(): string {

		global $wpdb;

		return $wpdb->prefix . 'awbd_birthdays';
	}

	/**
	 * Create plugin database tables.
	 *
	 * @return void
	 */
	public static function create_tables(): void {

		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$table_name      = self::get_birthdays_table();
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table_name} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			email varchar(190) NOT NULL,
			date_of_birth date NOT NULL,
			user_id bigint(20) unsigned DEFAULT NULL,
			last_discount_year int(4) unsigned DEFAULT NULL,
			created_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY email (email),
			KEY user_id (user_id)
		) {$charset_collate};";

		dbDelta( $sql );
	}
}