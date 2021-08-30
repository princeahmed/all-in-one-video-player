<?php

defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'AIOVP_Shortcode' ) ) {
	class AIOVP_Shortcode {

		/**
		 * @var null
		 */
		private static $instance = null;

		/**
		 * AIOVP_Shortcode constructor.
		 */
		public function __construct() {
			add_shortcode( 'aiovp', array( $this, 'player' ) );
		}

		/**
		 * @param $atts
		 *
		 * @return false|string
		 * @since 1.0.0
		 */
		public function player( $atts ) {

			$atts = shortcode_atts( array(
				'id'          => '',
				'player_type' => 'shortcode',
			), $atts );

			ob_start();

			$id     = $atts['id'];
			$videos = aiovp_get_meta( $id, 'videos' );


			ob_start(); ?>

            <div class="all-in-one-video-player"
                 data-id="<?php echo $id; ?>"
                 data-videos='<?php echo json_encode( $videos ); ?>'
            ></div>

			<?php
			return ob_get_clean();
		}

		/**
		 * @return AIOVP_Shortcode|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
}

AIOVP_Shortcode::instance();