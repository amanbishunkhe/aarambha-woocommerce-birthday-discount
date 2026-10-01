<?php

namespace AarambhaWooCommerceBirthdayDiscount\Services;

use AarambhaWooCommerceBirthdayDiscount\Database\BirthdayRepository;

defined( 'ABSPATH' ) || exit;

/**
 * Handles birthday discount business logic.
 */
class BirthdayService {

	/**
	 * Birthday repository.
	 *
	 * @var BirthdayRepository
	 */
	private BirthdayRepository $repository;

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->repository = new BirthdayRepository();
	}

	/**
	 * Save a customer's birthday information.
	 *
	 * This method works for both guests and registered customers.
	 *
	 * @param string   $email         Customer email.
	 * @param string   $date_of_birth Customer date of birth.
	 * @param int|null $user_id       WordPress user ID.
	 *
	 * @return int|false Birthday record ID or false on failure.
	 */
	public function save_birthday(
		string $email,
		string $date_of_birth,
		?int $user_id = null
	) {

		$email = sanitize_email( $email );

		if ( empty( $email ) || empty( $date_of_birth ) ) {
			return false;
		}

		if ( ! $this->is_valid_date_of_birth( $date_of_birth ) ) {
			return false;
		}

		$existing_record = $this->repository->find_by_email( $email );

		/**
		 * If a birthday record already exists,
		 * update it instead of creating a duplicate.
		 */
		if ( $existing_record ) {

			$updated = $this->repository->update(
				(int) $existing_record->id,
				$date_of_birth,
				$user_id ?: $existing_record->user_id
			);

			if ( ! $updated ) {
				return false;
			}

			return (int) $existing_record->id;
		}

		/**
		 * No existing record.
		 * Create a new birthday record.
		 */
		return $this->repository->create(
			$email,
			$date_of_birth,
			$user_id
		);
	}

	/**
	 * Get a customer's birthday record.
	 *
	 * @param string $email Customer email.
	 *
	 * @return object|null Birthday record or null.
	 */
	public function get_birthday_by_email( string $email ): ?object {

		return $this->repository->find_by_email( $email );
	}

	/**
	 * Link an existing birthday record to a WordPress user.
	 *
	 * This is useful when a guest later creates an account
	 * using the same email address.
	 *
	 * @param string $email   Customer email.
	 * @param int    $user_id WordPress user ID.
	 *
	 * @return bool
	 */
	public function link_user_by_email(
		string $email,
		int $user_id
	): bool {

		$record = $this->repository->find_by_email( $email );

		if ( ! $record ) {
			return false;
		}

		return $this->repository->link_user(
			(int) $record->id,
			$user_id
		);
	}

	/**
	 * Validate a date of birth.
	 *
	 * @param string $date_of_birth Date of birth.
	 *
	 * @return bool
	 */
	public function is_valid_date_of_birth( string $date_of_birth ): bool {

		$date_of_birth = sanitize_text_field( $date_of_birth );

		$date = \DateTime::createFromFormat( 'Y-m-d', $date_of_birth );

		if ( ! $date ) {
			return false;
		}

		$errors = \DateTime::getLastErrors();

		if ( false !== $errors ) {

			if ( $errors['warning_count'] > 0 || $errors['error_count'] > 0 ) {
				return false;
			}
		}

		return $date->format( 'Y-m-d' ) === $date_of_birth;
	}


}