<?php

defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'AIOVP_Enqueue' ) ) {
	class AIOVP_Enqueue {

		/**
		 * @var null
		 */
		private static $instance = null;

		/**
		 * AIOVP_Enqueue constructor.
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue' ] );
			//add_action( 'elementor/frontend/before_enqueue_scripts', [ $this, 'elementor_scripts' ] );
		}


		/**
		 * Frontend Scripts
		 *
		 * @param $hook
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public static function frontend_scripts() {

			wp_enqueue_style( 'aiovp', AIOVP_ASSETS . '/css/frontend.css', [] );

			/* enqueue frontend script */
			wp_enqueue_script( 'aiovp', AIOVP_ASSETS . '/js/frontend.min.js', array(
				'wp-element',
				'wp-components',
				'wp-util',
				'wp-api-fetch',
			), AIOVP_VERSION, true );


			/* localized script attached to 'wp-radio' */
			wp_localize_script( 'aiovp', 'aiovp', array(
				'plugin_url'      => AIOVP_URL,
				'admin_url'       => admin_url(),
				'ajax_url'        => admin_url( 'admin-ajax.php' ),
				'site_url'        => site_url(),
				'is_popup_player' => ! empty( $_GET['radio_player'] ),
				'isPreview'       => isset( $_GET['preview'] ),
				'popup_url'       => str_replace( 'https', 'http', site_url() ),
				'nonce'           => wp_create_nonce( 'aiovp' ),
				'i18n'            => array(),
				'settings'        => (array) get_option( 'radio_player_settings' ),
				'isHTTP'          => empty( $_SERVER['HTTPS'] ) || 'off' == $_SERVER['HTTPS'],
			) );


		}

		/**
		 * Admin Scripts
		 *
		 * @param $hook
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function admin_enqueue( $hook ) {

			//admin styles
			wp_enqueue_style( 'aiovp-admin', AIOVP_ASSETS . '/css/admin.css', [ 'wp-components' ], AIOVP_VERSION );

			/**---- admin scripts -----*/

			//radio player admin
			wp_enqueue_script( 'aiovp-admin', AIOVP_ASSETS . '/js/admin.min.js', array(
				'wp-element',
				'wp-components',
				'wp-block-editor',
				'wp-api-fetch',
				'wp-util',
			), AIOVP_VERSION, true );

			wp_localize_script( 'aiovp-admin', 'aiovp', array(
				'site_url'   => site_url(),
				'plugin_url' => AIOVP_URL,
				'admin_url'  => admin_url(),
				'ajax_url'   => admin_url( 'admin-ajax.php' ),
				'nonce'      => wp_create_nonce( 'wp_rest' ),
				'i18n'       => array(),
				'settings'   => (array) get_option( 'aiovp_settings' ),
			) );


		}

		public function elementor_scripts() {
			wp_enqueue_script( 'aiovp-elementor', AIOVP_ASSETS . '/js/elementor.min.js', [ 'jquery' ], AIOVP_VERSION, true );
		}

		/**
		 * @return AIOVP_Enqueue|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
}

AIOVP_Enqueue::instance();




