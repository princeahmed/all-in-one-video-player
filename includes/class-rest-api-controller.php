<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'AIOVP_Rest_Api_Controller' ) ) {
	class AIOVP_Rest_Api_Controller extends WP_REST_Controller {
		/** @var null */
		private static $instance = null;

		/**
		 * AIOVP_Rest_Api_Controller constructor.
		 */
		public function __construct() {
			$this->namespace = 'aiovp/v1';

		}

		/**
		 * Register REST API routes
		 *
		 * @since 1.0.0
		 */
		public function register_routes() {

			//get all players
			register_rest_route( $this->namespace, '/player/all', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_all_player' ),
					'permission_callback' => '__return_true',
				),
			) );

			//get player by id
			register_rest_route( $this->namespace, '/player/(?P<id>\d+)', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_player' ),
					'permission_callback' => '__return_true',
					'args'                => $this->get_collection_params()
				),
			) );

			//get player data
			register_rest_route( $this->namespace, '/player-data/(?P<id>\d+)', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_player_data' ),
					'permission_callback' => '__return_true',
				),
			) );

			//update player data
			register_rest_route( $this->namespace, '/player-data/(?P<id>\d+)', array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'set_player_data' ),
					'permission_callback' => array( $this, 'check_permission' )
				),
			) );


			//get settings
			register_rest_route( $this->namespace, '/settings/', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_settings' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			) );


			//delete player
			register_rest_route( $this->namespace, '/delete-player/(?P<id>\d+)', array(
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_player' ),
					'permission_callback' => array( $this, 'check_delete_permission' ),
				),
			) );
		}

		public function delete_player( $data ) {
			$player_id = isset( $data['id'] ) ? $data['id'] : '';

			if ( $player_id ) {
				wp_delete_post( $player_id, true );
			}

			wp_send_json_success();
		}

		public function update_settings( $request ) {
			$posted = json_decode( $request->get_body() );

			$data = [
				'httpPlayer'      => ! empty( $posted->httpPlayer ) ? sanitize_key( $posted->httpPlayer ) : '',
				'volume'          => ! empty( $posted->volume ) ? intval( $posted->volume ) : '',
				'customPopupSize' => ! empty( $posted->customPopupSize ) ? sanitize_key( $posted->customPopupSize ) : '',
				'popupWidth'      => ! empty( $posted->popupWidth ) ? intval( $posted->popupWidth ) : '',
				'popupHeight'     => ! empty( $posted->popupHeight ) ? intval( $posted->popupHeight ) : '',
			];

			update_option( 'radio_player_settings', $data );

			wp_send_json_success();
		}

		public function get_settings() {
			$settings = (array) get_option( 'radio_player_settings', [] );

			wp_send_json_success( $settings );
		}

		/**
		 * @return bool
		 */
		public function check_permission() {
			return current_user_can( 'manage_options' );
		}

		public function check_delete_permission() {
			return current_user_can( 'delete_others_posts' );
		}

		public function set_player_data( $request ) {
			$data = json_decode( $request->get_body() );

			error_log( print_r( $data, 1 ) );

			$post_id = ! empty( $data->postID ) ? intval( $data->postID ) : '';
			$title   = ! empty( $data->playerTitle ) ? sanitize_text_field( $data->playerTitle ) : '';

			$values = [
				'videos'     => ! empty( $data->videos ) ? array_filter( $data->videos ) : '',
				'playerType' => ! empty( $data->playerType ) ? sanitize_text_field( $data->playerType ) : '',
			];

			foreach ( $values as $key => $value ) {
				update_post_meta( $post_id, $key, $value );
			}

			wp_update_post( [
				'ID'          => $post_id,
				'post_title'  => $title,
				'post_status' => 'publish'
			] );

			wp_send_json_success();
		}

		/**
		 * Get player data
		 *
		 * @param $data
		 */
		public function get_player_data( $data ) {
			$id = isset( $data['id'] ) ? $data['id'] : '';

			$meta = [
				'playerTitle' => get_the_title( $id ),
				'videos'      => array_filter( aiovp_get_meta( $id, 'videos' ) ),
				'playerType'  => aiovp_get_meta( $id, 'playerType' ),
			];


			wp_send_json_success( $meta );
		}

		/**
		 * Get the rest api station preview
		 *
		 * @param $data
		 *
		 * @since 1.0.0
		 */

		public function get_player( $data ) {
			$id = isset( $data['id'] ) ? $data['id'] : '';

			wp_send_json_success( do_shortcode( '[radio_player id="' . $id . '" player_type="shortcode"]' ) );
		}

		/**
		 * Get all players callback
		 *
		 * @since 1.0.0
		 */
		public function get_all_player() {
			$capability = 'edit_others_posts';
			if ( ! current_user_can( $capability ) ) {
				wp_send_json_error( array(
					'message' => __( 'You do not have access to this resource.', 'radio-player' )
				), 401 );
			}

			wp_send_json_success( get_players_array() );
		}

		/**
		 * @return AIOVP_Rest_Api_Controller|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}

}