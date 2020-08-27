<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/Aidan-Chey
 * @since      1.0.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin
 * @author     Aidan Dunn <aidancheyd@gmail.com>
 */
class Freedam_Web_Notices_Admin {

	/**
	 * The options name to be used in this plugin
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var  	string 		$option_name 	Option name of this plugin
	 */
	private $option_name = 'freedam_web_notices';

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Freedam_Web_Notices_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Freedam_Web_Notices_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/freedam-web-notices-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Freedam_Web_Notices_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Freedam_Web_Notices_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/freedam-web-notices-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {

		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'FreeDAM Web Notices Settings', 'freedam-web-notices' ),
			__( 'FreeDAM Web Notices', 'freedam-web-notices' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);

	}

	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_options_page() {
		include_once 'partials/freedam-web-notices-admin-display.php';
	}

	/**
	 * Register the settings with WP
	 *
	 * @since  1.0.0
	 */
	public function register_settings() {

		// Add a General section
		add_settings_section(
			$this->option_name . '_general',
			__( 'General', 'freedam-web-notices' ),
			array( $this, $this->option_name . '_general_cb' ),
			$this->plugin_name
		);

		// Add setting for API Key
		add_settings_field(
			$this->option_name . '_apikey',
			__( 'API Key', 'freedam-bew-notices' ),
			array( $this, $this->option_name . '_apikey_cb' ),
			$this->plugin_name,
			$this->option_name . '_apikey',
			array( 'label_for' => $this->option_name . '_apikey' )
		);

	}

	/**
	 * Render the text for the general section
	 *
	 * @since  1.0.0
	 */
	public function freedam_web_notices_general_cb() {
		echo '<p>' . __( 'Please change the settings accordingly.', 'freedam-web-notices' ) . '</p>';
	}

	/**
	 * Render the text input field for api key
	 *
	 * @since  1.0.0
	 */
	public function freedam_web_notices_apikey_cb() {
		include_once 'partials/freedam-web-notices-admin-apikey.php';
	}

}
