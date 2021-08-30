<?php

defined( 'ABSPATH' ) || exit();

final class AIOVP {

	/**
	 * Minimum PHP version required
	 *
	 * @var string
	 */
	private $min_php = '5.6.0';

	/**
	 * The single instance of the class.
	 *
	 * @var AIOVP
	 * @since 1.0.0
	 */
	protected static $instance = null;

	/**
	 * AIOVP constructor.
	 */
	public function __construct() {
		$this->check_environment();
		$this->includes();
		$this->init_hooks();

		register_activation_hook( AIOVP_FILE, array( $this, 'activate' ) );

		do_action( 'aiovp_loaded' );
	}

	public function activate() {
		if ( ! class_exists( 'AIOVP_Install' ) ) {
			include_once AIOVP_INCLUDES . '/class-install.php';
		}

		AIOVP_Install::activate();
	}

	/**
	 * Ensure theme and server variable compatibility
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function check_environment() {

		if ( version_compare( PHP_VERSION, $this->min_php, '<=' ) ) {
			deactivate_plugins( plugin_basename( AIOVP_FILE ) );

			wp_die( "Unsupported PHP version Min required PHP Version:{$this->min_php}" );
		}

	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function includes() {

		//core includes
		include_once AIOVP_INCLUDES . '/functions.php';
		include_once AIOVP_INCLUDES . '/class-cpt.php';
		include_once AIOVP_INCLUDES . '/class-enqueue.php';
		include_once AIOVP_INCLUDES . '/class-hooks.php';
		include_once AIOVP_INCLUDES . '/class-shortcode.php';
		include_once AIOVP_INCLUDES . '/class-rest-api.php';


		//admin includes

		if ( is_admin() ) {
			include_once AIOVP_INCLUDES . '/admin/class-metabox.php';
		}

	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.0
	 */
	private function init_hooks() {

		add_action( 'admin_notices', [ $this, 'print_notices' ], 15 );

		//Localize our plugin
		add_action( 'init', [ $this, 'localization_setup' ] );

		//register elementor widget
		//add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widget' ] );
	}


	/**
	 * Register elementor player widget
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function register_widget() {

		require AIOVP_PATH . '/elementor/class-elementor-player-widget.php';

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new AIOVP_Elementor_Widget() );
	}

	/**
	 * Initialize plugin for localization
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function localization_setup() {
		load_plugin_textdomain( 'aiovp', false, AIOVP_PATH . '/languages/' );
	}


	public function add_notice( $class, $message ) {

		$notices = get_option( sanitize_key( 'aiovp_notices' ), [] );
		if ( is_string( $message ) && is_string( $class ) && ! wp_list_filter( $notices, array( 'message' => $message ) ) ) {

			$notices[] = array(
				'message' => $message,
				'class'   => $class,
			);

			update_option( sanitize_key( 'aiovp_notices' ), $notices );
		}

	}

	/**
	 * Prince admin notice
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function print_notices() {
		$notices = get_option( sanitize_key( 'aiovp_notices' ), [] );
		foreach ( $notices as $notice ) { ?>
            <div class="notice notice-large is-dismissible wp-radio-player-notice notice-<?php echo esc_attr( $notice['class'] ); ?>">
				<?php echo $notice['message']; ?>
            </div>
			<?php
			update_option( sanitize_key( 'aiovp_notices' ), [] );
		}
	}


	/**
	 * Main AIOVP Instance.
	 *
	 * Ensures only one instance of AIOVP is loaded or can be loaded.
	 *
	 * @return AIOVP - Main instance.
	 * @since 1.0.0
	 * @static
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

//kickoff aiovp
if ( ! function_exists( 'aiovp' ) ) {
	function aiovp() {
		return AIOVP::instance();
	}
}

aiovp();