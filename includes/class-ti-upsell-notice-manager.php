<?php
/**
 * Notice manager for Neve upsell.
 */

/**
 * Class Ti_Upsell_Notice_Manager
 */
class Ti_Upsell_Notice_Manager {

	/**
	 * Singleton object.
	 *
	 * @var null Instance object.
	 */
	protected static $instance = null;

	/**
	 * Dismiss option key.
	 *
	 * @var string Dismiss option key.
	 */
	protected static $dismiss_key = 'neve_upsell_off';

	/**
	 * Init the OrbitFox instance.
	 *
	 * @return Orbit_Fox_Neve_Dropin|null
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	/**
	 * Drop-in actions
	 */
	public function init() {
		add_action( 'admin_notices', array( $this, 'admin_notice' ), defined( 'PHP_INT_MIN' ) ? PHP_INT_MIN : 99999 );
		add_action( 'admin_init', array( $this, 'remove_notice' ), defined( 'PHP_INT_MIN' ) ? PHP_INT_MIN : 99999 );
	}

	/**
	 * Add notice.
	 */
	public function admin_notice() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}
		if ( is_network_admin() ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( function_exists( 'is_gutenberg_page' ) && is_gutenberg_page() ) {
			return;
		}

		$current_theme = wp_get_theme();
		$theme_name    = $current_theme->get( 'TextDomain' );
		$template      = $current_theme->get( 'Template' );
		if ( $theme_name === 'neve' || $template === 'neve' ) {
			return;
		}

		/**
		 * Backwards compatibility.
		 */
		global $current_user;
		$user_id        = $current_user->ID;
		$ignored_notice = get_user_meta( $user_id, 'navmenu_elementor_ignore_neve_notice' );
		if ( ! empty( $ignored_notice ) ) {
			update_option( self::$dismiss_key, 'yes' );
		}

		if ( get_option( self::$dismiss_key, 'no' ) === 'yes' ) {
			return;
		}

		$dismiss_button = sprintf(
			'<a href="%s" class="notice-dismiss" style="text-decoration:none;"></a>',
			wp_nonce_url( add_query_arg( array( self::$dismiss_key => 'yes' ) ), 'remove_upsell_confirmation', 'remove_upsell' )
		);

		$message =
			sprintf(
				/* translators: Install Neve link */
				esc_html__( 'NavMenu Addon For Elementor recommends %1$s. Fully AMP optimized and responsive, Neve will load in mere seconds and adapt perfectly on any viewing device. Neve works perfectly with Gutenberg and the most popular page builders. You will love it!', 'navmenu-addon-for-elementor' ),
				sprintf(
					/* translators: Install Neve link */
					'<a target="_blank" href="%1$s"><strong>%2$s</strong></a>',
					esc_url( admin_url( 'theme-install.php?theme=neve' ) ),
					esc_html__( 'Neve', 'navmenu-addon-for-elementor' )
				)
			);
		printf(
			'<div class="notice updated" style="position:relative;">%1$s<p>%2$s</p></div>',
			$dismiss_button,
			$message
		);
	}

	/**
	 * Remove notice;
	 */
	public function remove_notice() {
		if ( ! isset( $_GET[ self::$dismiss_key ] ) ) {
			return;
		}
		if ( $_GET[ self::$dismiss_key ] !== 'yes' ) {
			return;
		}
		if ( ! isset( $_GET['remove_upsell'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( $_GET['remove_upsell'], 'remove_upsell_confirmation' ) ) {
			return;
		}
		update_option( self::$dismiss_key, 'yes' );
	}

	/**
	 * Deny clone.
	 */
	public function __clone() {
	}

	/**
	 * Deny un-serialize.
	 */
	public function __wakeup() {
	}
}
