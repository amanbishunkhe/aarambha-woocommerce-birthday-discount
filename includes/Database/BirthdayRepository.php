<?php

namespace AarambhaWooCommerceBirthdayDiscount\Database;

defined( 'ABSPATH' ) || exit;

/**
 * Handles birthday record database operations.
 */
class BirthdayRepository {

	/**
	 * Get the birthday table name.
	 *
	 * @return string
	 */
	private function get_table_name(): string {

		return Database::get_birthdays_table();
	}

	/**
	 * Find a birthday record by email.
	 *
	 * @param string $email Customer email address.
	 *
	 * @return object|null
	 */
	public function find_by_email( string $email ): ?object {

		global $wpdb;

		$table_name = $this->get_table_name();

		$email = sanitize_email( $email );

		if ( empty( $email ) ) {
			return null;
		}

		$record = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$table_name} WHERE email = %s LIMIT 1",
				$email
			)
		);

		return $record ?: null;
	}

	/**
	 * Create a new birthday record.
	 *
	 * @param string   $email         Customer email.
	 * @param string   $date_of_birth Customer date of birth.
	 * @param int|null $user_id       WordPress user ID.
	 *
	 * @return int|false Inserted record ID or false on failure.
	 */
	public function create(
		string $email,
		string $date_of_birth,
		?int $user_id = null
	) {

		global $wpdb;

		$table_name = $this->get_table_name();

		$now = current_time( 'mysql' );

		$result = $wpdb->insert(
			$table_name,
			array(
				'email'              => sanitize_email( $email ),
				'date_of_birth'      => $date_of_birth,
				'user_id'            => $user_id,
				'last_discount_year' => null,
				'created_at'         => $now,
				'updated_at'         => $now,
			),
			array(
				'%s',
				'%s',
				'%d',
				'%d',
				'%s',
				'%s',
			)
		);

		if ( false === $result ) {
			return false;
		}

		return (int) $wpdb->insert_id;
	}

	/**
	 * Update an existing birthday record.
	 *
	 * @param int         $id            Birthday record ID.
	 * @param string      $date_of_birth Date of birth.
	 * @param int|null    $user_id       WordPress user ID.
	 *
	 * @return bool
	 */
	public function update(
		int $id,
		string $date_of_birth,
		?int $user_id = null
	): bool {

		global $wpdb;

		$table_name = $this->get_table_name();

		$result = $wpdb->update(
			$table_name,
			array(
				'date_of_birth' => $date_of_birth,
				'user_id'       => $user_id,
				'updated_at'    => current_time( 'mysql' ),
			),
			array(
				'id' => $id,
			),
			array(
				'%s',
				'%d',
				'%s',
			),
			array(
				'%d',
			)
		);

		return false !== $result;
	}

	/**
	 * Link a birthday record to a WordPress user.
	 *
	 * @param int $id      Birthday record ID.
	 * @param int $user_id WordPress user ID.
	 *
	 * @return bool
	 */
	public function link_user( int $id, int $user_id ): bool {

		global $wpdb;

		$table_name = $this->get_table_name();

		$result = $wpdb->update(
			$table_name,
			array(
				'user_id'    => $user_id,
				'updated_at' => current_time( 'mysql' ),
			),
			array(
				'id' => $id,
			),
			array(
				'%d',
				'%s',
			),
			array(
				'%d',
			)
		);

		return false !== $result;
	}
}