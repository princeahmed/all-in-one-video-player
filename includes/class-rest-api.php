<?php

defined( 'ABSPATH' ) || exit;

require_once AIOVP_INCLUDES . '/class-rest-api-controller.php';

if ( ! class_exists( 'AIOVP_Rest_Api' ) ) {
	class AIOVP_Rest_Api {
		/** @var null */
		private static $instance = null;

		/**
		 * AIOVP_Rest_Api constructor.
		 *
		 */
		public function __construct() {
			add_action( 'rest_api_init', [ $this, 'register_api' ] );
		}

		/**
		 * Register rest API
		 *
		 * @since 1.0.0
		 */
		public function register_api() {
			$controller = AIOVP_Rest_Api_Controller::instance();

			$controller->register_routes();
		}

		/**
		 * @return AIOVP_Rest_Api|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}

}

AIOVP_Rest_Api::instance();