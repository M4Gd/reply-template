<?php
/**
 * ReplyTemplateAdmin
 *
 * @package   axiom
 * @author    averta
 * @license   GPL-2.0+
 * @copyright 2014 
 */

/**
 *
 * @package ReplyTemplateAdmin
 */
class ReplyTemplateAdmin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		$plugin = ReplyTemplate::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();


		add_action( 'admin_footer'	, array( $this, 'flush_plugin_rewrite_rules'   ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}


	/**
	 * Flush rewrite rules on first run
	 */
	public function flush_plugin_rewrite_rules() {

		$is_flushed = get_option( 'axiom_plugin_reply_template_flushed');
		if( $is_flushed  != "1" ) {
			update_option( 'axiom_plugin_reply_template_flushed', 1 );
			flush_rewrite_rules();
		}

	}

}
