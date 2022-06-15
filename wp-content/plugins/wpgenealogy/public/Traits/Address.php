<?php 
namespace WPGenealogy_Public\Traits;

trait Address {

	/**
	 * Function to get address.
	 *
	 * @since    3.0.0
	 */
	public function addresses_get() {

		/**
		 * Get all addresses.
		 *
		 */
		$this->get(\WPGenealogy\Models\Address::class);
	}
}