<?php

namespace AarambhaWooCommerceBirthdayDiscount\WooCommerce;

use AarambhaWooCommerceBirthdayDiscount\Services\BirthdayService;
use AarambhaWooCommerceBirthdayDiscount\Core\Loader;

defined( 'ABSPATH' ) || exit;

/**
 * Handles birthday information in the WooCommerce customer account.
 */
class Account {

	/**
	 * Birthday service.
	 *
	 * @var BirthdayService
	 */
	private BirthdayService $birthday_service;

	/**
	 * Constructor.
	 *
	 * @param BirthdayService $birthday_service Birthday service.
	 */
	public function __construct( BirthdayService $birthday_service ) {

		$this->birthday_service = $birthday_service;
	}

	/**
	 * Register WooCommerce account hooks.
	 *
	 * @param Loader $loader Plugin loader.
	 *
	 * @return void
	 */
	public function register_hooks( Loader $loader ): void {
		$loader->add_action(
			'woocommerce_edit_account_form',
			$this,
			'display_dob_field'
		);

		$loader->add_action(
			'woocommerce_save_account_details',
			$this,
			'save_dob_field',
			10,
			1
		);
	}

	/**
	 * Display the date of birth field.
	 *
	 * @return void
	 */
	public function display_dob_field(): void {
		$user_id = get_current_user_id();

		if ( ! $user_id ) {
			return;
		}

		$email = wp_get_current_user()->user_email;

		$birthday = $this->birthday_service->get_birthday_by_email( $email );

		$date_of_birth = '';

		if ( $birthday ) {
			$date_of_birth = $birthday->date_of_birth;
		}

		?>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="awbd_date_of_birth">
				<?php esc_html_e( 'Date of Birth', 'aarambha-woocommerce-birthday-discount' ); ?>
				<span class="required">*</span>
			</label>

			<input
				type="date"
				class="woocommerce-Input woocommerce-Input--text input-text"
				name="awbd_date_of_birth"
				id="awbd_date_of_birth"
				value="<?php echo esc_attr( $date_of_birth ); ?>"
			/>
		</p>

		<?php
	}

	/**
	 * Save the date of birth.
	 *
	 * @param int $user_id WordPress user ID.
	 *
	 * @return void
	 */
	public function save_dob_field( int $user_id ): void {

		if ( ! isset( $_POST['awbd_date_of_birth'] ) ) {
			return;
		}

		$date_of_birth = sanitize_text_field(
			wp_unslash( $_POST['awbd_date_of_birth'] )
		);

		if ( empty( $date_of_birth ) ) {
			return;
		}

		$user = get_userdata( $user_id );

		if ( ! $user ) {
			return;
		}

		$this->birthday_service->save_birthday(
			$user->user_email,
			$date_of_birth,
			$user_id
		);
	}

}