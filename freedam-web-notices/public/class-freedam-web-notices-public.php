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

defined( 'ABSPATH' ) || exit;

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
	 * Register the plugin's scripts, styles, and the block on `init`.
	 *
	 * Nothing is enqueued here — registration only. The frontend assets are
	 * enqueued lazily by {@see render_notices()} (for shortcode placements)
	 * and automatically by the block API (via block.json's `style` and
	 * `viewScript` fields, for block placements). The result: pages that
	 * don't use this plugin pay zero CSS/JS cost.
	 *
	 * @since 1.6.0
	 */
	public function register_assets() {
		$plugin_url = plugin_dir_url( dirname( __FILE__ ) );

		wp_register_style(
			'freedam-web-notices',
			$plugin_url . 'public/css/freedam-web-notices-public.css',
			array(),
			$this->version
		);

		// Tiny date formatter that replaces moment.js. Shared between the
		// frontend renderer and the editor preview, so it's loaded by both.
		wp_register_script(
			'freedam-web-notices-format',
			$plugin_url . 'public/js/freedam-web-notices-format.js',
			array(),
			$this->version,
			true
		);

		wp_register_script(
			'freedam-web-notices-public',
			$plugin_url . 'public/js/freedam-web-notices-public.js',
			array( 'freedam-web-notices-format' ),
			$this->version,
			true
		);

		if ( function_exists( 'register_block_type' ) ) {
			wp_register_script(
				'freedam-web-notices-block-editor',
				$plugin_url . 'blocks/notices/edit.js',
				array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-i18n', 'freedam-web-notices-format' ),
				$this->version,
				true
			);

			// Plugin settings + placeholder notices for the editor preview.
			// The editor preview never calls the FreeDAM API.
			wp_localize_script(
				'freedam-web-notices-block-editor',
				'freedamWebNoticesEditorData',
				$this->get_editor_preview_data()
			);

			register_block_type(
				plugin_dir_path( dirname( __FILE__ ) ) . 'blocks/notices',
				array(
					'render_callback' => array( $this, 'render_notices' ),
				)
			);
		}
	}

	/**
	 * Enqueue the frontend assets. Called from the shortcode/block render
	 * path so the CSS/JS only load on pages that actually use them.
	 *
	 * Block placements also trigger asset enqueueing automatically via
	 * block.json's `style` and `viewScript`; calling these here as well is
	 * harmless (`wp_enqueue_*` is idempotent for an already-enqueued handle)
	 * and ensures shortcode placements get the same treatment.
	 *
	 * @since 1.6.0
	 */
	private function enqueue_frontend_assets() {
		wp_enqueue_style( 'freedam-web-notices' );
		wp_enqueue_script( 'freedam-web-notices-public' );
	}

	/**
	 * Render the notices markup. Shared between the shortcode and the block
	 * so both produce identical output.
	 *
	 * @since 1.6.0
	 * @param array|string $attributes Block attributes or shortcode atts (unused).
	 * @param string       $content    Inner content (unused).
	 * @param mixed        $context    Shortcode tag string or WP_Block instance (unused).
	 * @return string Rendered HTML.
	 */
	public function render_notices( $attributes = array(), $content = '', $context = null ) {
		$this->enqueue_frontend_assets();
		ob_start();
		include __DIR__ . '/partials/freedam-web-notices-public-display.php';
		return ob_get_clean();
	}

	/**
	 * Shortcode callback. Thin wrapper so existing [freedam-web-notices]
	 * placements continue to work; the block uses the same renderer.
	 */
	public function register_shortcode( $atts = [], $content = '', $shortcode_tag = '' ) {
		return $this->render_notices( $atts, $content, $shortcode_tag );
	}

	/**
	 * Build the data passed to the block editor for a faithful, API-free preview.
	 *
	 * Reads the same options the public display partial reads, applies the same
	 * template legacy-decode-then-kses, and builds a small set of fake notices
	 * shaped like the real FreeDAM response. Image fields use an inline SVG
	 * data URI so no upstream request is ever made from the editor.
	 *
	 * @since 1.6.0
	 * @return array
	 */
	private function get_editor_preview_data() {
		$stored_template = get_option( 'freedam_web_notices_template' );
		if ( is_string( $stored_template ) && strlen( $stored_template ) > 0 ) {
			$template = wp_kses_post( html_entity_decode( $stored_template ) );
		} else {
			$template = $this->defaults['template'];
		}

		$page_size    = sanitize_text_field( get_option( 'freedam_web_notices_pagesize' ) );
		$funeral_date = sanitize_text_field( get_option( 'freedam_web_notices_funeral_date' ) );
		$funeral_time = sanitize_text_field( get_option( 'freedam_web_notices_funeral_time' ) );
		$date_type    = sanitize_text_field( get_option( 'freedam_web_notices_date_type' ) );
		$birth_date   = sanitize_text_field( get_option( 'freedam_web_notices_birth_date' ) );
		$death_date   = sanitize_text_field( get_option( 'freedam_web_notices_death_date' ) );
		$search       = sanitize_text_field( get_option( 'freedam_web_notices_search', $this->defaults['search'] ) );
		$image        = sanitize_text_field( get_option( 'freedam_web_notices_image', $this->defaults['image'] ) );
		$nulls        = sanitize_text_field( get_option( 'freedam_web_notices_nulls', $this->defaults['nulls'] ) );

		$image_enabled = (bool) $image;

		// Inline SVG used wherever the real API would return an image URL,
		// so the editor preview never reaches out to the FreeDAM API.
		$placeholder_image = 'data:image/svg+xml;base64,' . base64_encode(
			'<svg xmlns="http://www.w3.org/2000/svg" width="120" height="160" viewBox="0 0 120 160">'
			. '<rect width="120" height="160" fill="#d1d5d9"/>'
			. '<text x="60" y="85" font-family="sans-serif" font-size="14" fill="#50575e" text-anchor="middle">Sample</text>'
			. '</svg>'
		);

		$build_sample = function ( $case_id, $title, $first, $last, $preferred, $maiden, $birth, $death, $age, $funeral_dt, $service_type, $tribute ) use ( $placeholder_image, $image_enabled ) {
			return array(
				'case'          => $case_id,
				'tributeText'   => $tribute,
				'publish_from'  => gmdate( 'Y-m-d\TH:i:s.000\Z', strtotime( $death ) ),
				'publish_until' => gmdate( 'Y-m-d\TH:i:s.000\Z', strtotime( '+90 days', strtotime( $death ) ) ),
				'stream_url'    => null,
				'stream_note'   => null,
				'caseImage'     => $case_id,
				'lastUpdated'   => gmdate( 'Y-m-d\TH:i:s.000\Z' ),
				'funeral'       => array(
					'dateTime'    => $funeral_dt,
					'serviceType' => $service_type,
				),
				'venue'         => array(
					'name'     => 'Sample Chapel',
					'street'   => '1 Main Street',
					'suburb'   => 'Central',
					'city'     => 'Sampletown',
					'postCode' => '1234',
					'state'    => null,
				),
				'rsa'           => array(
					'decorations'   => null,
					'serviceNumber' => null,
					'war'           => null,
					'serviceBranch' => null,
					'highestRank'   => null,
					'unit'          => null,
				),
				'deceased'      => array(
					'birthDate' => $birth,
					'deathDate' => $death,
					'age'       => $age,
					'name'      => array(
						'title'     => $title,
						'first'     => $first,
						'last'      => $last,
						'preferred' => $preferred,
						'maiden'    => $maiden,
					),
				),
				'office'        => array(
					'id'     => 1,
					'branch' => 1,
					'name'   => array(
						'internal' => 'Sample Office',
						'trading'  => 'Sample Funeral Services',
					),
				),
				'thumbnail'     => $placeholder_image,
				'image'         => $image_enabled ? $placeholder_image : null,
			);
		};

		return array(
			'settings'      => array(
				'template'          => $template,
				'pageSize'          => empty( $page_size ) ? (int) $this->defaults['pagesize'] : (int) $page_size,
				'searchEnabled'     => (bool) $search,
				'imageEnabled'      => $image_enabled,
				'nulls'             => (bool) $nulls,
				'dateType'          => strlen( $date_type ) > 0 ? $date_type : $this->defaults['date_type'],
				'funeralDateFormat' => strlen( $funeral_date ) > 0 ? $funeral_date : $this->defaults['funeraldate'],
				'funeralTimeFormat' => strlen( $funeral_time ) > 0 ? $funeral_time : $this->defaults['funeraltime'],
				'birthDateFormat'   => strlen( $birth_date ) > 0 ? $birth_date : $this->defaults['birthdate'],
				'deathDateFormat'   => strlen( $death_date ) > 0 ? $death_date : $this->defaults['deathdate'],
			),
			'sampleNotices' => array(
				$build_sample(
					1001,
					'Mrs', 'Jane', 'Smith', 'Jane', 'Brown',
					'1950-03-12', '2025-06-10', '75 years',
					'2025-06-15T10:00:00+13:00', 'Funeral Service',
					"SMITH Jane\nOn the 10th of June 2025. Aged 75 years.\nA service to celebrate Jane's life will be held at Sample Chapel, 1 Main Street, Sampletown on Sunday, 15th June at 10:00 AM."
				),
				$build_sample(
					1002,
					'Mr', 'Robert', 'Jones', 'Bob', null,
					'1943-01-20', '2025-06-05', '82 years',
					'2025-06-12T14:00:00+13:00', 'Memorial Service',
					"JONES Robert (Bob)\nOn the 5th of June 2025. Aged 82 years.\nA private cremation has taken place."
				),
			),
		);
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

		// Some upstream responses send the same header multiple times, in which
		// case wp_remote_retrieve_header() returns an array. Collapse to a single
		// string so header() doesn't trigger an Array-to-string warning (which
		// would itself be emitted as body output and corrupt the binary).
		if ( is_array( $content_type ) ) {
			$content_type = reset( $content_type );
		}
		if ( is_array( $cache_control ) ) {
			$cache_control = implode( ', ', $cache_control );
		}
		if ( ! is_string( $content_type ) || '' === $content_type ) {
			$content_type = 'application/octet-stream';
		}
		if ( ! is_string( $cache_control ) || '' === $cache_control ) {
			// Fallback when upstream omits the header. Matches the FreeDAM API's
			// own policy (public, 3-day max-age) so caching behaviour is
			// consistent regardless of which response set it.
			$cache_control = 'public, max-age=259200';
		}


		// Stream the binary body verbatim. The REST framework would otherwise
		// JSON-encode it; bypass that by sending the response ourselves.
		if ( ! headers_sent() ) {
			status_header( 200 );
			header( 'Content-Type: ' . $content_type );
			header( 'Cache-Control: ' . $cache_control );
			header( 'X-Content-Type-Options: nosniff' );
		}
		echo $body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- binary body
		exit;
	}

}
