<?php

/* Block direct access */
defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'AIOVP_CPT' ) ) {
	class AIOVP_CPT {
		private static $instance = null;

		/**
		 * Post_Types constructor.
		 */
		function __construct() {
			add_action( 'init', array( $this, 'register_post_types' ) );
			add_action( 'init', array( $this, 'flush_rewrite_rules' ), 99 );
		}

		/**
		 * Register custom post types
		 *
		 * @since 1.0.0
		 */
		public function register_post_types() {
			register_post_type( 'aiovp', array(
				'labels'              => $this->get_posts_labels( 'Video Player', 'Player', 'Players' ),
				'hierarchical'        => false, //Hierarchical causes memory issues - WP Loads all records
				'supports'            => array(
					'title',
					'thumbnail',
				),
				'public'              => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => false,
				'menu_position'       => 5,
				'menu_icon'           => AIOVP_ASSETS . '/images/icon.svg',
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'has_archive'         => false,
				'query_var'           => false,
				'can_export'          => true,
				'rewrite'             => array( 'slug' => apply_filters( 'aiovp/player_slug', 'aiovp' ) ),
				'capability_type'     => 'post',
			) );

		}


		/**
		 * Get all labels from post types
		 *
		 * @param $menu_name
		 * @param $singular
		 * @param $plural
		 *
		 * @return array
		 * @since 1.0.0
		 */
		protected static function get_posts_labels( $menu_name, $singular, $plural, $type = 'plural' ) {
			$labels = array(
				'name'                  => 'plural' == $type ? $plural : $singular,
				'all_items'             => sprintf( __( "All %s", 'radio-player' ), $plural ),
				'singular_name'         => $singular,
				'add_new'               => sprintf( __( 'Add New %s', 'radio-player' ), $singular ),
				'add_new_item'          => sprintf( __( 'Add New %s', 'radio-player' ), $singular ),
				'edit_item'             => sprintf( __( 'Edit %s', 'radio-player' ), $singular ),
				'new_item'              => sprintf( __( 'New %s', 'radio-player' ), $singular ),
				'view_item'             => sprintf( __( 'View %s', 'radio-player' ), $singular ),
				'search_items'          => sprintf( __( 'Search %s', 'radio-player' ), $plural ),
				'not_found'             => sprintf( __( 'No %s found', 'radio-player' ), $plural ),
				'not_found_in_trash'    => sprintf( __( 'No %s found in Trash', 'radio-player' ), $plural ),
				'parent_item_colon'     => sprintf( __( 'Parent %s:', 'radio-player' ), $singular ),
				'menu_name'             => $menu_name,
				'featured_image'        => sprintf( __( '%s Thumbnail', 'radio-player' ), $singular ),
				'set_featured_image'    => sprintf( __( 'Set %s Thumbnail', 'radio-player' ), $singular ),
				'remove_featured_image' => sprintf( __( 'Remove %s Thumbnail', 'radio-player' ), $singular ),
				'use_featured_image'    => sprintf( __( 'Use %s Thumbnail', 'radio-player' ), $singular ),
			);

			return $labels;
		}


		/**
		 * Flash The Rewrite Rules
		 *
		 * @since 1.0.0
		 */
		public function flush_rewrite_rules() {
			if ( get_option( 'aiovp_flush_rewrite_rules' ) ) {
				flush_rewrite_rules();
				delete_option( 'aiovp_flush_rewrite_rules' );
			}
		}

		/**
		 * @return AIOVP_CPT|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
}

AIOVP_CPT::instance();

