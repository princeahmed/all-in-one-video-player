<?php

defined( 'ABSPATH' ) || exit;

/**
 * Class Install
 */
class AIOVP_Install {

	/**
	 * Plugin activation stuffs
	 *
	 * @since 1.0.0
	 */
	public static function activate() {
		self::create_default_data();
	}

	public static function deactivate() {
	}

	/**
	 * Create plugin settings default data
	 *
	 * @since 1.0.0
	 */
	private static function create_default_data() {

		$version      = get_option( 'aiovp_version', '0' );
		$install_time = get_option( 'aiovp_install_time', '' );

		if ( empty( $version ) ) {
			update_option( 'aiovp_version', AIOVP_VERSION );
		}

		if ( ! empty( $install_time ) ) {
			$date_format = get_option( 'date_format' );
			$time_format = get_option( 'time_format' );
			update_option( 'aiovp_install_time', date( $date_format . ' ' . $time_format ) );
		}

		update_option( 'aiovp_flush_rewrite_rules', true );

	}


}