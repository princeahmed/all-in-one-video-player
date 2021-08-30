<?php

/** Block direct access */
defined( 'ABSPATH' ) || exit();

/** check if class `AIVOP_Metabox` not exists yet */
if ( ! class_exists( 'AIVOP_Metabox' ) ) {
	/**
	 * Class AIVOP_Metabox
	 *
	 * Handle metaboxes
	 *
	 *
	 * @since 1.0.0
	 */
	class AIVOP_Metabox {

		/**
		 * @var null
		 */
		private static $instance = null;

		/**
		 * AIVOP_Metabox constructor.
		 * Initialize the custom Meta Boxes for prince-options api.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
			add_action( 'do_meta_boxes', [ $this, 'remove_meta_box' ] );
		}

		public function remove_meta_box() {
			remove_meta_box( 'postimagediv', [ 'aiovp' ], 'side' );
			remove_meta_box( 'submitdiv', [ 'aiovp' ], 'side' );
		}

		/**
		 * register metaboxes
		 */
		public function register_meta_boxes() {

			// Radio player configuration
			add_meta_box( 'aiovp_metabox', __( 'Player Configuration', 'aiovp-player' ), [ $this, 'render_metabox' ], [ 'aiovp' ], 'normal', 'high' );

		}

		/**
		 * render station info metabox content
		 *
		 * @since 1.0.0
		 */
		public function render_metabox() {
			echo '<div id="aiovp_metabox_app"></div>';
		}



		/**
		 * @return AIVOP_Metabox|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

	}
}

AIVOP_Metabox::instance();
