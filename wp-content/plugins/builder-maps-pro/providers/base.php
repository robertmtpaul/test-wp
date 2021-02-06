<?php
/**
 * Base class for Builder_Data_Provider classes
 *
 * @package Themify
 */
if ( ! class_exists( 'Maps_Pro_Data_Provider' ) ) :
class Maps_Pro_Data_Provider {

	function is_available() {
		return true;
	}

	function get_id() {}

	function get_label() {}

	function get_options() {}
	function get_error() {}
	function get_items( $settings ) {}
}

class Maps_Pro_Data_Provider_Container {

	/* instances of data providers for this module */
	public $providers;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @return	A single instance of this class.
	 */
	public static function get_instance() {
		static $instance = null;
		if ( $instance === null ) {
			$instance = new self;
		}
		return $instance;
	}

	private function __construct() {
		add_action( 'wp_loaded', array( $this, 'init_providers' ), 100 );
	}

	/**
	 * Initialize data providers for the module
	 *
	 * Other plugins or themes can extend or add to this list
	 * by using the "builder_tiled_posts_providers" filter.
	 */
	function init_providers() {
		$dir = trailingslashit( dirname( __FILE__ ) );
		include( $dir . '/text.php' );
		$providers = apply_filters( 'tb_maps_pro_data_providers', array(
			'text' => 'Maps_Pro_Data_Provider_Text',
		) );

		foreach ( $providers as $id => $provider ) {
			if ( class_exists( $provider ) ) {
				$this->providers[ $id ] = new $provider();
			}
		}
	}

	/**
	 * Helper function to retrieve list of providers
	 *
	 * @return object
	 */
	public function get_providers() {
		return $this->providers;
	}

	/**
	 * Helper function to retrieve a provider instance
	 *
	 * @return object
	 */
	public function get_provider( $id ) {
		return isset( $this->providers[ $id ] ) ? $this->providers[ $id ] : false;
	}
}
Maps_Pro_Data_Provider_Container::get_instance();
endif;