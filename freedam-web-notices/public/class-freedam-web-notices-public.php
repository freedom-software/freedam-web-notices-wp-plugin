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

	public function register_shortcode( $atts = [], $content = '', $shortcode_tag = '' ) {
		// do something to $content
		ob_start(); // begin collecting output

		include 'partials/freedam-web-notices-public-display.php';

		// always return
		return ob_get_clean(); // retrieve output from myfile.php, stop buffering
	}

	/**
	 * REST API namespace used by the notice proxy endpoint.
	 */
	const REST_NAMESPACE = 'freedam-web-notices/v1';

	/**
	 * REST API route used by the notice proxy endpoint.
	 */
	const REST_ROUTE = '/notices';

	/**
	 * Register REST routes for proxying requests to the FreeDAM API.
	 *
	 * The API key is kept server-side; the browser only talks to this proxy.
	 *
	 * @since 1.6.0
	 */
	public function register_rest_routes() {
		register_rest_route(
			self::REST_NAMESPACE,
			self::REST_ROUTE,
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'rest_get_notices' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Proxy a notices request to the FreeDAM API, attaching the stored API key server-side.
	 *
	 * @since 1.6.0
	 * @param WP_REST_Request $request
	 * @return WP_REST_Response|WP_Error
	 */
	public function rest_get_notices( $request ) {
		$api_key = trim( (string) get_option( 'freedam_web_notices_apikey', '' ) );
		if ( '' === $api_key ) {
			return new WP_Error(
				'freedam_web_notices_no_api_key',
				__( 'FreeDAM Web Notices API key is not configured.', 'freedam-web-notices' ),
				array( 'status' => 503 )
			);
		}

		// Whitelist params we forward to FreeDAM. Anything else is dropped.
		$forward_keys = array(
			'page', 'pageSize', 'ascending', 'nulls', 'dateType',
			'after', 'before', 'filterTerms', 'includeImage', 'office',
		);

		$query = array( 'apiKey' => $api_key );
		foreach ( $forward_keys as $key ) {
			$value = $request->get_param( $key );
			if ( null === $value || '' === $value ) {
				continue;
			}
			$query[ $key ] = is_scalar( $value ) ? (string) $value : wp_json_encode( $value );
		}

		$url = $this->freedam_api_address . $this->freedam_api_endpoint . '?' . http_build_query( $query );

		$response = wp_remote_get(
			$url,
			array(
				'timeout' => 15,
				'headers' => array( 'Accept' => 'application/json' ),
			)
		);

		if ( is_wp_error( $response ) ) {
			return new WP_Error(
				'freedam_web_notices_upstream_error',
				$response->get_error_message(),
				array( 'status' => 502 )
			);
		}

		$status = (int) wp_remote_retrieve_response_code( $response );
		$body   = wp_remote_retrieve_body( $response );
		$data   = json_decode( $body, true );

		if ( $status < 200 || $status >= 300 ) {
			return new WP_Error(
				'freedam_web_notices_upstream_status',
				sprintf( /* translators: %d: HTTP status code from upstream */ __( 'FreeDAM API returned status %d.', 'freedam-web-notices' ), $status ),
				array( 'status' => 502 )
			);
		}

		if ( null === $data && JSON_ERROR_NONE !== json_last_error() ) {
			return new WP_Error(
				'freedam_web_notices_upstream_decode',
				__( 'FreeDAM API returned an invalid response.', 'freedam-web-notices' ),
				array( 'status' => 502 )
			);
		}

		return rest_ensure_response( $data );
	}

}
