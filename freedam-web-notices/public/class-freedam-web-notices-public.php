<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/freedom-software
 * @since      1.0.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/public
 * @author     Freedom Software <support@freedomsoftware.co.nz>
 */
class Freedam_Web_Notices_Public {

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
	 * Address to the FreeDAM API
	 */
	protected $freedam_api_address;

	/**
	 * Endpoint in FreeDAM API to retrieve web notices
	 */
	protected $freedam_api_endpoint = '/web-notices';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $defaults, $freedam_api_address ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->defaults = $defaults;
		$this->freedam_api_address = $freedam_api_address;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/freedam-web-notices-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name . '_moment', plugin_dir_url( __FILE__ ) . 'js/freedam-web-notices-moment.js', array(), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '_public', plugin_dir_url( __FILE__ ) . 'js/freedam-web-notices-public.js', array( $this->plugin_name . '_moment' ), $this->version, false );

	}

	public function register_shortcode( $atts = [], $content = '', $shortcode_tag ) {
		// do something to $content
		ob_start(); // begin collecting output

		include 'partials/freedam-web-notices-public-display.php';

		// always return
		return ob_get_clean(); // retrieve output from myfile.php, stop buffering
	}

}
