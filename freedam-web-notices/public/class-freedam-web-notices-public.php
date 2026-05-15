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
	 * REST API route used by the binary asset proxy endpoint
	 * (e.g. images embedded in notice responses).
	 */
	const REST_ASSET_ROUTE = '/asset';

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

		register_rest_route(
			self::REST_NAMESPACE,
			self::REST_ASSET_ROUTE,
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'rest_proxy_asset' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'path' => array(
						'required'          => true,
						'type'              => 'string',
						'validate_callback' => function ( $value ) {
							return is_string( $value ) && strlen( $value ) > 0 && '/' === $value[0];
						},
					),
				),
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

		// FreeDAM embeds absolute URLs (with the apiKey query param baked in) for
		// images and similar assets. Rewrite every such URL so it points at the
		// local asset proxy below; that way the key never leaves the server.
		$data = $this->rewrite_upstream_urls( $data );

		return rest_ensure_response( $data );
	}

	/**
	 * Recursively walk a decoded API response and rewrite any absolute FreeDAM URL
	 * to point at this plugin's asset proxy, stripping the upstream apiKey.
	 *
	 * @since 1.6.0
	 * @param mixed $data
	 * @return mixed
	 */
	private function rewrite_upstream_urls( $data ) {
		if ( is_array( $data ) ) {
			foreach ( $data as $key => $value ) {
				$data[ $key ] = $this->rewrite_upstream_urls( $value );
			}
			return $data;
		}

		if ( ! is_string( $data ) ) {
			return $data;
		}

		$prefix = rtrim( $this->freedam_api_address, '/' );
		if ( 0 !== strpos( $data, $prefix . '/' ) ) {
			return $data;
		}

		$remainder = substr( $data, strlen( $prefix ) ); // e.g. /web-notices/365/image?apiKey=...
		$parts     = wp_parse_url( $remainder );
		$path      = isset( $parts['path'] ) ? $parts['path'] : '';
		if ( '' === $path ) {
			return $data;
		}

		$query = array();
		if ( ! empty( $parts['query'] ) ) {
			parse_str( $parts['query'], $query );
			unset( $query['apiKey'] );
		}

		$proxy_url = rest_url( self::REST_NAMESPACE . self::REST_ASSET_ROUTE );
		return add_query_arg( array_merge( array( 'path' => $path ), $query ), $proxy_url );
	}

	/**
	 * Proxy a binary asset (image, etc.) from FreeDAM, attaching the stored API
	 * key server-side. The browser passes only the upstream path; this endpoint
	 * concatenates it onto the configured API base, so callers cannot pivot to
	 * arbitrary hosts.
	 *
	 * @since 1.6.0
	 * @param WP_REST_Request $request
	 * @return WP_Error|void Streams the upstream body on success.
	 */
	public function rest_proxy_asset( $request ) {
		$api_key = trim( (string) get_option( 'freedam_web_notices_apikey', '' ) );
		if ( '' === $api_key ) {
			return new WP_Error(
				'freedam_web_notices_no_api_key',
				__( 'FreeDAM Web Notices API key is not configured.', 'freedam-web-notices' ),
				array( 'status' => 503 )
			);
		}

		$path = (string) $request->get_param( 'path' );
		// Reject anything that isn't a clean absolute path on the API host.
		if ( '' === $path || '/' !== $path[0] || false !== strpos( $path, '..' ) || false !== strpos( $path, '://' ) ) {
			return new WP_Error(
				'freedam_web_notices_bad_path',
				__( 'Invalid asset path.', 'freedam-web-notices' ),
				array( 'status' => 400 )
			);
		}

		// Forward any extra query params the caller supplied (other than apiKey,
		// which we attach ourselves).
		$extra = $request->get_query_params();
		unset( $extra['path'], $extra['apiKey'], $extra['rest_route'] );
		$extra['apiKey'] = $api_key;

		$url = rtrim( $this->freedam_api_address, '/' ) . $path;
		$url = add_query_arg( $extra, $url );

		$response = wp_remote_get(
			$url,
			array(
				'timeout' => 15,
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
		if ( $status < 200 || $status >= 300 ) {
			return new WP_Error(
				'freedam_web_notices_upstream_status',
				sprintf( /* translators: %d: HTTP status code from upstream */ __( 'FreeDAM API returned status %d.', 'freedam-web-notices' ), $status ),
				array( 'status' => 502 )
			);
		}

		$body          = wp_remote_retrieve_body( $response );
		$content_type  = wp_remote_retrieve_header( $response, 'content-type' );
		$cache_control = wp_remote_retrieve_header( $response, 'cache-control' );

		if ( ! $content_type ) {
			$content_type = 'application/octet-stream';
		}

		// Stream the binary body verbatim. The REST framework would otherwise
		// JSON-encode it; bypass that by sending the response ourselves.
		if ( ! headers_sent() ) {
			status_header( 200 );
			header( 'Content-Type: ' . $content_type );
			header( 'Cache-Control: ' . ( $cache_control ? $cache_control : 'public, max-age=300' ) );
			header( 'X-Content-Type-Options: nosniff' );
		}
		echo $body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- binary body
		exit;
	}

}
