<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/freedom-software
 * @since      1.3.0
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
 * @author     Freedom Software <support@freedomsoftware.co.nz>
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

	private $settings_section_name;
	private $formats_section_name;
	private $template_section_name;
	private $instructions_section_name;
	private $settings_page_name;

	private $options_funeral_date;
	private $options_funeral_time;
	private $options_birth_date;
	private $options_death_date;
	private $options_date_type;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.3.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $defaults, $freedam_api_address ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->defaults = $defaults;
		$this->freedam_api_address = $freedam_api_address;

		$this->settings_section_name = $this->option_name . '_settings';
		$this->settings_options_group = $this->option_name . '_settings_options';
		$this->formats_section_name = $this->option_name . '_formats';
		$this->formats_options_group = $this->option_name . '_formats_options';
		$this->template_section_name = $this->option_name . '_template';
		$this->template_options_group = $this->option_name . '_template_options';
		$this->instructions_section_name = $this->option_name . '_instructions';
		$this->instructions_options_group = $this->option_name . '_instructions_options';
		$this->settings_page_name = $this->plugin_name;

		$this->options_funeral_date = array(
			'dddd, Do MMMM YYYY' => 'Wednesday, 23th September 2020',
			'ddd Do MMMM YYYY' => 'Wed 23th September 2020',
			'Do MMMM YYYY' => '23th September 2020',
			'Do MMM YYYY' => '23th Sept 2020',
			'D MMMM YYYY' => '23 Sept 2020',
			'D/M/YYYY' => '23/9/2020',
			'YYYY-MM-DD' => '2020-09-23',
		);
		$this->options_funeral_time = array(
			'h:mm a' => '1:00 pm',
			'h:mm A' => '1:00 PM',
			'HH:mm' => '13:00',
		);
		$this->options_birth_date = array(
			'dddd, Do MMMM YYYY' => 'Monday, 17rd January 1972',
			'ddd Do MMMM YYYY' => 'Mon 17rd January 1972',
			'Do MMMM YYYY' => '17rd January 1972',
			'Do MMM YYYY' => '17rd Jan 1972',
			'D MMMM YYYY' => '17 Sept 1972',
			'D.M.YYYY' => '17.1.1972',
			'D/M/YYYY' => '17/1/1972',
			'YYYY-MM-DD' => '1972-1-17',
			'YYYY' => '1972',
		);
		$this->options_death_date = array(
			'dddd, Do MMMM YYYY' => 'Wednesday, 23th September 2020',
			'ddd Do MMMM YYYY' => 'Wed 23th September 2020',
			'Do MMMM YYYY' => '23th September 2020',
			'Do MMM YYYY' => '23th Sept 2020',
			'D MMMM YYYY' => '23 Sept 2020',
			'D.M.YYYY' => '23.9.2020',
			'D/M/YYYY' => '23/9/2020',
			'YYYY-MM-DD' => '2020-09-23',
			'YYYY' => '2020',
		);
		$this->options_date_type = array(
			'funeral' => 'Funeral',
			'death' => 'Death',
			'disposition' => 'Disposition',
		);

	}

	/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {

		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'FreeDAM Web Notices', $this->plugin_name ),
			__( 'FreeDAM Web Notices', $this->plugin_name ),
			'manage_options',
			$this->settings_page_name,
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
	 * @since  1.2.0
	 */
	public function register_settings() {

		// Add a Settings section
		add_settings_section(
			$this->settings_section_name,
			__( 'Settings', $this->plugin_name ),
			array( $this, $this->option_name . '_settings_section_cb' ),
			$this->settings_options_group
		);
		// Add setting for API Key
		add_settings_field(
			$this->option_name . '_apikey',
			__( 'API Key', $this->plugin_name ),
			array( $this, $this->option_name . '_apikey_cb' ),
			$this->settings_options_group,
			$this->settings_section_name,
			array(
				'label_for' => $this->option_name . '_apikey',
				'title' => 'Used by the plugin to authenticate with and identify the database to retrieve the web-notices from'
			)
		);
		register_setting(
			$this->settings_options_group,
			$this->option_name . '_apikey',
			array(
				'type' => 'string',
				'description' => 'API Key used by the plugin to authenticate with and identify the DB to retrieve the web-notices from',
				'sanitize_callback' => array( $this, $this->option_name . '_sanitize_apikey' )
			)
		);

		// Add setting for page size
		add_settings_field(
			$this->option_name . '_pagesize',
			__( 'Page Size', $this->plugin_name ),
			array( $this, $this->option_name . '_pagesize_cb' ),
			$this->settings_options_group,
			$this->settings_section_name,
			array(
				'label_for' => $this->option_name . '_pagesize',
				'title' => 'Number of notices to show per page'
			)
		);
		register_setting(
			$this->settings_options_group,
			$this->option_name . '_pagesize',
			array(
				'type' => 'integer',
				'description' => 'Number of notices to show per page',
				'sanitize_callback' => array( $this, $this->option_name . '_sanitize_pagesize' ),
				'default' => $this->defaults['pagesize']
			)
		);

		// Add setting for date type
		add_settings_field(
			$this->option_name . '_date_type',
			__( 'Sort by date', $this->plugin_name ),
			array( $this, $this->option_name . '_date_type_cb' ),
			$this->settings_options_group,
			$this->settings_section_name,
			array(
				'label_for' => $this->option_name . '_date_type',
				'title' => 'Type of date to use for sorting the web notices'
			)
		);
		register_setting(
			$this->settings_options_group,
			$this->option_name . '_date_type',
			array(
				'type' => 'string',
				'description' => 'Type of date to use for sorting the web notices',
				'sanitize_callback' => array( $this, $this->option_name . '_sanitize_date_type' ),
				'default' => $this->defaults['date_type']
			)
		);

		// Add setting for after past
		add_settings_field(
			$this->option_name . '_past',
			__( 'Limit by days in the past', $this->plugin_name ),
			array( $this, $this->option_name . '_past_cb' ),
			$this->settings_options_group,
			$this->settings_section_name,
			array(
				'label_for' => $this->option_name . '_past',
				'title' => 'Only show notices with funeral dates after this many days in the past'
			)
		);
		register_setting(
			$this->settings_options_group,
			$this->option_name . '_past',
			array(
				'type' => 'integer',
				'description' => 'Limits how old web-notices can be to be included',
				'sanitize_callback' => array( $this, $this->option_name . '_sanitize_days' ),
			)
		);

		// Add setting for before future
		add_settings_field(
			$this->option_name . '_future',
			__( 'Limit by day in the future', $this->plugin_name ),
			array( $this, $this->option_name . '_future_cb' ),
			$this->settings_options_group,
			$this->settings_section_name,
			array(
				'label_for' => $this->option_name . '_future',
				'title' => 'Only show notices with funeral dates before this many days in the future'
			)
		);
		register_setting(
			$this->settings_options_group,
			$this->option_name . '_future',
			array(
				'type' => 'integer',
				'description' => 'Limits how new web-notices can be to be included',
				'sanitize_callback' => array( $this, $this->option_name . '_sanitize_days' ),
			)
		);

		// Add setting for nulls
		add_settings_field(
			$this->option_name . '_nulls',
			__( 'Include notices without date & time', $this->plugin_name ),
			array( $this, $this->option_name . '_nulls_cb' ),
			$this->settings_options_group,
			$this->settings_section_name,
			array(
				'label_for' => $this->option_name . '_nulls',
				'title' => 'Whether notices that don\'t have a funeral date/time should be included in results'
			)
		);
		register_setting(
			$this->settings_options_group,
			$this->option_name . '_nulls',
			array(
				'type' => 'boolean',
				'description' => 'Whether notices that don\'t have a funeral date/time should be included in results',
				'sanitize_callback' => array( $this, $this->option_name . '_sanitize_boolean' ),
				'default' => $this->defaults['nulls']
			)
		);

		// Add setting for search
		add_settings_field(
			$this->option_name . '_search',
			__( 'Show search', $this->plugin_name ),
			array( $this, $this->option_name . '_search_cb' ),
			$this->settings_options_group,
			$this->settings_section_name,
			array(
				'label_for' => $this->option_name . '_search',
				'title' => 'Whether users should be given the option to search for web-notices'
			)
		);
		register_setting(
			$this->settings_options_group,
			$this->option_name . '_search',
			array(
				'type' => 'boolean',
				'description' => 'Whether users should be given the option to search for web-notices',
				'sanitize_callback' => array( $this, $this->option_name . '_sanitize_boolean' ),
				'default' => $this->defaults['search']
			)
		);

		// Add setting for ascending order
		add_settings_field(
			$this->option_name . '_ascending',
			__( 'Oldest notices first', $this->plugin_name ),
			array( $this, $this->option_name . '_ascending_cb' ),
			$this->settings_options_group,
			$this->settings_section_name,
			array(
				'label_for' => $this->option_name . '_ascending',
				'title' => 'Whether notices results should be ordered by oldest first'
			)
		);
		register_setting(
			$this->settings_options_group,
			$this->option_name . '_ascending',
			array(
				'type' => 'boolean',
				'description' => 'Whether notices results should be ordered by oldest first',
				'sanitize_callback' => array( $this, $this->option_name . '_sanitize_boolean' ),
				'default' => $this->defaults['ascending']
			)
		);

		// Add a Formats section
		add_settings_section(
			$this->formats_section_name,
			__( 'Date Formats / Rules', $this->plugin_name ),
			array( $this, $this->option_name . '_formats_section_cb' ),
			$this->formats_options_group
		);

		// Add setting for funeral date
		add_settings_field(
			$this->option_name . '_funeral_date',
			__( 'Funeral Date', $this->plugin_name ),
			array( $this, $this->option_name . '_funeral_date_cb' ),
			$this->formats_options_group,
			$this->formats_section_name,
			array(
				'label_for' => $this->option_name . '_funeral_date',
				'title' => 'Format the funeral date should be displayed in'
			)
		);
		register_setting(
			$this->formats_options_group,
			$this->option_name . '_funeral_date',
			array(
				'type' => 'string',
				'description' => 'Format the funeral date should be displayed in',
				'sanitize_callback' => array( $this, $this->option_name . '_sanitize_funeral_date' ),
			)
		);

		// Add setting for funeral time
		add_settings_field(
			$this->option_name . '_funeral_time',
			__( 'Funeral Time', $this->plugin_name ),
			array( $this, $this->option_name . '_funeral_time_cb' ),
			$this->formats_options_group,
			$this->formats_section_name,
			array(
				'label_for' => $this->option_name . '_funeral_time',
				'title' => 'Format the funeral time should be displayed in'
			)
		);
		register_setting(
			$this->formats_options_group,
			$this->option_name . '_funeral_time',
			array(
				'type' => 'string',
				'description' => 'Format the funeral time should be displayed in',
				'sanitize_callback' => array( $this, $this->option_name . '_sanitize_funeral_time' ),
			)
		);

		// Add setting for birth date
		add_settings_field(
			$this->option_name . '_birth_date',
			__( 'Birth Date', $this->plugin_name ),
			array( $this, $this->option_name . '_birth_date_cb' ),
			$this->formats_options_group,
			$this->formats_section_name,
			array(
				'label_for' => $this->option_name . '_birth_date',
				'title' => 'Format the birth date should be displayed in'
			)
		);
		register_setting(
			$this->formats_options_group,
			$this->option_name . '_birth_date',
			array(
				'type' => 'string',
				'description' => 'Format the birth date should be displayed in',
				'sanitize_callback' => array( $this, $this->option_name . '_sanitize_birth_date' ),
			)
		);

		// Add setting for death date
		add_settings_field(
			$this->option_name . '_death_date',
			__( 'Death Date', $this->plugin_name ),
			array( $this, $this->option_name . '_death_date_cb' ),
			$this->formats_options_group,
			$this->formats_section_name,
			array(
				'label_for' => $this->option_name . '_death_date',
				'title' => 'Format the death date should be displayed in'
			)
		);
		register_setting(
			$this->formats_options_group,
			$this->option_name . '_death_date',
			array(
				'type' => 'string',
				'description' => 'Format the death date should be displayed in',
				'sanitize_callback' => array( $this, $this->option_name . '_sanitize_death_date' ),
			)
		);

		// Add a Template section
		add_settings_section(
			$this->template_section_name,
			__( 'Notice Template', $this->plugin_name ),
			array( $this, $this->option_name . '_template_section_cb' ),
			$this->template_options_group
		);

		// Add setting for template
		add_settings_field(
			$this->option_name . '_template',
			__( 'Notice Template', $this->plugin_name ),
			array( $this, $this->option_name . '_template_cb' ),
			$this->template_options_group,
			$this->template_section_name,
			array(
				'label_for' => $this->option_name . '_template',
				'title' => 'Customize the layout of individual notices'
			)
		);
		register_setting(
			$this->template_options_group,
			$this->option_name . '_template',
			array(
				'type' => 'string',
				'description' => 'Custom template for individual web notices',
				'sanitize_callback' => array( $this, $this->option_name . '_sanitize_template' ),
				'default' => $this->defaults['template']
			)
		);

		// Add a Instructions section
		add_settings_section(
			$this->instructions_section_name,
			__( 'Instructions', $this->plugin_name ),
			array( $this, $this->option_name . '_instructions_section_cb' ),
			$this->instructions_options_group
		);

	}

	/**
	 * Render the text for the settings section
	 *
	 * @since  1.0.0
	 */
	public function freedam_web_notices_settings_section_cb() {}

	/**
	 * Render the text for the formats section
	 *
	 * @since  1.0.0
	 */
	public function freedam_web_notices_formats_section_cb() {}

	/**
	 * Render the text for the template section
	 *
	 * @since  1.0.0
	 */
	public function freedam_web_notices_template_section_cb() {}

	/**
	 * Render the text for the general section
	 *
	 * @since  1.0.0
	 */
	public function freedam_web_notices_instructions_section_cb() {
		include_once 'partials/freedam-web-notices-admin-instructions.php';
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
	 * Render the checkbox input field for search
	 *
	 * @since  1.2.0
	 */
	public function freedam_web_notices_search_cb( $args ) {
		include_once 'partials/freedam-web-notices-admin-search.php';
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
	 * Render the select field for funeral date format
	 *
	 * @since  1.1.0
	 */
	public function freedam_web_notices_funeral_date_cb( $args ) {
		include_once 'partials/freedam-web-notices-admin-funeral-date.php';
	}

	/**
	 * Render the select field for funeral time format
	 *
	 * @since  1.1.0
	 */
	public function freedam_web_notices_funeral_time_cb( $args ) {
		include_once 'partials/freedam-web-notices-admin-funeral-time.php';
	}

	/**
	 * Render the select field for birth date format
	 *
	 * @since  1.1.0
	 */
	public function freedam_web_notices_birth_date_cb( $args ) {
		include_once 'partials/freedam-web-notices-admin-birth-date.php';
	}

	/**
	 * Render the select field for death date format
	 *
	 * @since  1.1.0
	 */
	public function freedam_web_notices_death_date_cb( $args ) {
		include_once 'partials/freedam-web-notices-admin-death-date.php';
	}

	/**
	 * Render the select field for date type format
	 *
	 * @since  1.2.0
	 */
	public function freedam_web_notices_date_type_cb( $args ) {
		include_once 'partials/freedam-web-notices-admin-date-type.php';
	}

	/**
	 * Sanitize the api key value before being saved to database
	 *
	 * Checks if value is a 128 length string and only contains alpha-numerics
	 *
	 * @param  string $apikey $_POST value
	 * @since  1.1.1
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
		  		__( 'API Key must be 128 characters', $this->plugin_name )
	  		);
			}

			if ( $invalid_content ) {
		  	add_settings_error(
		  		$this->option_name . '_apikey',
		  		'apikey_content',
		  		__( 'API Key may only contain lowercase alpha-numeric characters', $this->plugin_name )
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
	  		__( 'Page Size must between 1 and 100', $this->plugin_name )
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
	 * Sanitize the funeral date value before being saved to database
	 *
	 * Checks if value is in the list of options
	 *
	 * @param  string $var $_POST value
	 * @since  1.1.1
	 * @return boolean           Sanitized value
	 */
	public function freedam_web_notices_sanitize_funeral_date( $var ) {
		if ( !array_key_exists($var, $this->options_funeral_date) ) return null;
		return sanitize_text_field($var);
	}

	/**
	 * Sanitize the select format value before being saved to database
	 *
	 * Checks if value is in the list of options
	 *
	 * @param  string $var $_POST value
	 * @since  1.1.1
	 * @return boolean           Sanitized value
	 */
	public function freedam_web_notices_sanitize_funeral_time( $var ) {
		if ( !array_key_exists($var, $this->options_funeral_time) ) return null;
		return sanitize_text_field($var);
	}

	/**
	 * Sanitize the select format value before being saved to database
	 *
	 * Checks if value is in the list of options
	 *
	 * @param  string $var $_POST value
	 * @since  1.1.1
	 * @return boolean           Sanitized value
	 */
	public function freedam_web_notices_sanitize_birth_date( $var ) {
		if ( !array_key_exists($var, $this->options_birth_date) ) return null;
		return sanitize_text_field($var);
	}

	/**
	 * Sanitize the select format value before being saved to database
	 *
	 * Checks if value is in the list of options
	 *
	 * @param  string $var $_POST value
	 * @since  1.1.1
	 * @return boolean           Sanitized value
	 */
	public function freedam_web_notices_sanitize_death_date( $var ) {
		if ( !array_key_exists($var, $this->options_death_date) ) return null;
		return sanitize_text_field($var);
	}

	/**
	 * Sanitize the select format value before being saved to database
	 *
	 * Checks if value is in the list of options
	 *
	 * @param  string $var $_POST value
	 * @since  1.2.0
	 * @return boolean           Sanitized value
	 */
	public function freedam_web_notices_sanitize_date_type( $var ) {
		if ( !array_key_exists($var, $this->options_date_type) ) return null;
		return sanitize_text_field($var);
	}

	/**
	 * Sanitize the html template before being saved to database
	 *
	 * @param  string $var $_POST value
	 * @since  1.0.0
	 * @return boolean           Sanitized value
	 */
	public function freedam_web_notices_sanitize_template( $var ) {
		return esc_html($var);
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
	 * Determines the truthiness of $val
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
