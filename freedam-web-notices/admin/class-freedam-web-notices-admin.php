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
	 * The defaults used by the plugin
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $defaults    The defaults used by the plugin
	 */
	protected $defaults;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $defaults ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->defaults = $defaults;

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

		register_setting(
			$this->plugin_name,
			$this->option_name . '_apikey',
			array(
				'type' => 'string',
				'description' => 'API Key used by the plugin to authenticate with and identify the DB to retrieve the web-notices from',
				'sanitize_callback' => array( $this, $this->option_name . '_sanitize_apikey' )
			)
		);

		register_setting(
			$this->plugin_name,
			$this->option_name . '_pagesize',
			array(
				'type' => 'integer',
				'description' => 'Number of notices to show per page',
				'sanitize_callback' => array( $this, $this->option_name . '_sanitize_pagesize' ),
				'default' => $this->defaults['pagesize']
			)
		);

		register_setting(
			$this->plugin_name,
			$this->option_name . '_past',
			array(
				'type' => 'integer',
				'description' => 'Limits how old web-notices can be to be included',
				'sanitize_callback' => array( $this, $this->option_name . '_sanitize_days' ),
				'default' => $this->defaults['past']
			)
		);

		register_setting(
			$this->plugin_name,
			$this->option_name . '_future',
			array(
				'type' => 'integer',
				'description' => 'Limits how new web-notices can be to be included',
				'sanitize_callback' => array( $this, $this->option_name . '_sanitize_days' ),
				'default' => $this->defaults['future']
			)
		);

		register_setting(
			$this->plugin_name,
			$this->option_name . '_nulls',
			array(
				'type' => 'boolean',
				'description' => 'Whether notices that don\'t have a funeral date/time should be included in results',
				'sanitize_callback' => array( $this, $this->option_name . '_sanitize_boolean' ),
				'default' => $this->defaults['nulls']
			)
		);

		register_setting(
			$this->plugin_name,
			$this->option_name . '_ascending',
			array(
				'type' => 'boolean',
				'description' => 'Whether notices results should be ordered by oldest first',
				'sanitize_callback' => array( $this, $this->option_name . '_sanitize_boolean' ),
				'default' => $this->defaults['ascending']
			)
		);

		register_setting(
			$this->plugin_name,
			$this->option_name . '_template',
			array(
				'type' => 'string',
				'description' => 'Custom template for individual web notices',
				'sanitize_callback' => array( $this, $this->option_name . '_sanitize_template' ),
				'default' => $this->defaults['template']
			)
		);

		/**
		 * Name of the genral section the admin setting are in
		 *
		 * @since  1.0.0
		 */
		$section_name = $this->option_name . '_general';

		// Add a General section
		add_settings_section(
			$section_name,
			__( 'General', $this->$plugin_name ),
			array( $this, $this->option_name . '_general_cb' ),
			$this->plugin_name
		);

		// Add setting for API Key
		add_settings_field(
			$this->option_name . '_apikey',
			__( 'API Key', $this->$plugin_name ),
			array( $this, $this->option_name . '_apikey_cb' ),
			$this->plugin_name,
			$section_name,
			array(
				'label_for' => $this->option_name . '_apikey',
				'title' => 'Used by the plugin to authenticate with and identify the database to retrieve the web-notices from'
			)
		);

		// Add setting for page size
		add_settings_field(
			$this->option_name . '_pagesize',
			__( 'Page Size', $this->$plugin_name ),
			array( $this, $this->option_name . '_pagesize_cb' ),
			$this->plugin_name,
			$section_name,
			array(
				'label_for' => $this->option_name . '_pagesize',
				'title' => 'Number of notices to show per page'
			)
		);

		// Add setting for after past
		add_settings_field(
			$this->option_name . '_past',
			__( 'Limit by days in the past', $this->$plugin_name ),
			array( $this, $this->option_name . '_past_cb' ),
			$this->plugin_name,
			$section_name,
			array(
				'label_for' => $this->option_name . '_past',
				'title' => 'Only show notices with funeral dates after this many days in the past'
			)
		);

		// Add setting for before future
		add_settings_field(
			$this->option_name . '_future',
			__( 'Limit by day in the future', $this->$plugin_name ),
			array( $this, $this->option_name . '_future_cb' ),
			$this->plugin_name,
			$section_name,
			array(
				'label_for' => $this->option_name . '_future',
				'title' => 'Only show notices with funeral dates before this many days in the future'
			)
		);

		// Add setting for nulls
		add_settings_field(
			$this->option_name . '_nulls',
			__( 'Include notices without date & time', $this->$plugin_name ),
			array( $this, $this->option_name . '_nulls_cb' ),
			$this->plugin_name,
			$section_name,
			array(
				'label_for' => $this->option_name . '_nulls',
				'title' => 'Whether notices that don\'t have a funeral date/time should be included in results'
			)
		);

		// Add setting for nulls
		add_settings_field(
			$this->option_name . '_ascending',
			__( 'Order notices ascending', $this->$plugin_name ),
			array( $this, $this->option_name . '_ascending_cb' ),
			$this->plugin_name,
			$section_name,
			array(
				'label_for' => $this->option_name . '_ascending',
				'title' => 'Whether notices results should be ordered by oldest first'
			)
		);

		// Add setting for template
		add_settings_field(
			$this->option_name . '_template',
			__( 'Notice Template', $this->$plugin_name ),
			array( $this, $this->option_name . '_template_cb' ),
			$this->plugin_name,
			$section_name,
			array(
				'label_for' => $this->option_name . '_template',
				'title' => 'Customize the layout of individual notices'
			)
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
	public function freedam_web_notices_apikey_cb( $args ) {
		include_once 'partials/freedam-web-notices-admin-apikey.php';
	}

	/**
	 * Render the number input field for page size
	 *
	 * @since  1.0.0
	 */
	public function freedam_web_notices_pagesize_cb( $args ) {
		include_once 'partials/freedam-web-notices-admin-pagesize.php';
	}

	/**
	 * Render the checkbox input field for nulls
	 *
	 * @since  1.0.0
	 */
	public function freedam_web_notices_nulls_cb( $args ) {
		include_once 'partials/freedam-web-notices-admin-nulls.php';
	}

	/**
	 * Render the textarea field for template
	 *
	 * @since  1.0.0
	 */
	public function freedam_web_notices_template_cb( $args ) {
		include_once 'partials/freedam-web-notices-admin-template.php';
	}

	/**
	 * Render the number input field for after past
	 *
	 * @since  1.0.0
	 */
	public function freedam_web_notices_past_cb( $args ) {
		include_once 'partials/freedam-web-notices-admin-after-past.php';
	}

	/**
	 * Render the number input field for before future
	 *
	 * @since  1.0.0
	 */
	public function freedam_web_notices_future_cb( $args ) {
		include_once 'partials/freedam-web-notices-admin-before-future.php';
	}

	/**
	 * Render the checkbox input field for ascending
	 *
	 * @since  1.0.0
	 */
	public function freedam_web_notices_ascending_cb( $args ) {
		include_once 'partials/freedam-web-notices-admin-ascending.php';
	}

	/**
	 * Sanitize the api key value before being saved to database
	 *
	 * Checks if value is a 128 length string and only contains alpha-numerics
	 *
	 * @param  string $apikey $_POST value
	 * @since  1.0.0
	 * @return string           Sanitized value
	 */
	public function freedam_web_notices_sanitize_apikey( $apikey ) {
		$invalid_length = !(strlen($apikey) === 0 || strlen($apikey) === 128);
		$invalid_content = preg_match('/[^a-z0-9]/', $apikey);

		if ( $invalid_length || $invalid_content ) {

			if ( $invalid_length ) {
		  	add_settings_error(
		  		$this->option_name . '_apikey',
		  		'apikey_length',
		  		__( 'API Key must be 128 characters', $this->$plugin_name )
	  		);
			}

			if ( $invalid_content ) {
		  	add_settings_error(
		  		$this->option_name . '_apikey',
		  		'apikey_content',
		  		__( 'API Key may only contain lowercase alpha-numeric characters', $this->$plugin_name )
	  		);
			}

			return;
	  }

	  return $apikey;
	}

	/**
	 * Sanitize the page size value before being saved to database
	 *
	 * Checks if value is an integer greater than zero
	 *
	 * @param  string $pagesize $_POST value
	 * @since  1.0.0
	 * @return string           Sanitized value
	 */
	public function freedam_web_notices_sanitize_pagesize( $pagesize ) {
		$value = intval($pagesize);
		$invalid_size = $value < 1 || $value > 100;

		if ( $invalid_size ) {
			add_settings_error(
	  		$this->option_name . '_pagesize',
	  		'pagesize_content',
	  		__( 'Page Size must between 1 and 100', $this->$plugin_name )
			);
			return;
		}

		return $value;

	}

	/**
	 * Sanitize the boolean value before being saved to database
	 *
	 * Checks if value is a boolean value
	 *
	 * @param  string $var $_POST value
	 * @since  1.0.0
	 * @return boolean           Sanitized value
	 */
	public function freedam_web_notices_sanitize_boolean( $var ) {
		return $this->is_true( $var );
	}

	/**
	 * Sanitize the html template before being saved to database
	 *
	 * @param  string $var $_POST value
	 * @since  1.0.0
	 * @return boolean           Sanitized value
	 */
	public function freedam_web_notices_sanitize_template( $var ) {
		return htmlentities(stripslashes($var));
	}

	/**
	 * Sanitize the before & after days value before being saved to database
	 *
	 * Checks if value is an integer
	 *
	 * @param  string $days $_POST value
	 * @since  1.0.0
	 * @return string           Sanitized value
	 */
	public function freedam_web_notices_sanitize_days( $days ) {
		$value = intval($days);
		if ( $value === 0 ) return null;
	  else return $value;
	}

	/**
	 * Determines the truithyness of $val
	 *
	 * @param  any  $val         Value to test
	 * @param  boolean $return_null The value nulls should return
	 * @return boolean              The boolean result
	 */
	public function is_true($val, $return_null=false){
    $boolval = ( is_string($val) ? filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : (bool) $val );
    return ( $boolval===null && !$return_null ? false : $boolval );
	}

}
