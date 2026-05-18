<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://github.com/freedom-software
 * @since      1.4.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/public/partials
 */

defined( 'ABSPATH' ) || exit;

  $nulls        = sanitize_text_field( get_option( 'freedam_web_notices_nulls', $this->defaults['nulls'] ) );
  $page_size    = sanitize_text_field( get_option( 'freedam_web_notices_pagesize' ) );
  $days_past    = sanitize_text_field( get_option( 'freedam_web_notices_past' ) );
  $days_future  = sanitize_text_field( get_option( 'freedam_web_notices_future' ) );
  $ascending    = sanitize_text_field( get_option( 'freedam_web_notices_ascending', $this->defaults['ascending'] ) );
  $funeral_date = sanitize_text_field( get_option( 'freedam_web_notices_funeral_date' ) );
  $funeral_time = sanitize_text_field( get_option( 'freedam_web_notices_funeral_time' ) );
  $date_type    = sanitize_text_field( get_option( 'freedam_web_notices_date_type' ) );
  $birth_date   = sanitize_text_field( get_option( 'freedam_web_notices_birth_date' ) );
  $death_date   = sanitize_text_field( get_option( 'freedam_web_notices_death_date' ) );
  $search       = sanitize_text_field( get_option( 'freedam_web_notices_search', $this->defaults['search'] ) );
  $image        = sanitize_text_field( get_option( 'freedam_web_notices_image', $this->defaults['image'] ) );
  $offices      = sanitize_text_field( get_option( 'freedam_web_notices_offices' ) );

  // Legacy templates were stored via esc_html() in older plugin versions, so they
  // may contain entity-encoded markup. Decode first, then run wp_kses_post() to
  // strip anything unsafe regardless of how it was stored.
  $stored_template = get_option( 'freedam_web_notices_template' );
  if ( is_string( $stored_template ) && strlen( $stored_template ) > 0 ) {
    $template = wp_kses_post( html_entity_decode( $stored_template ) );
  } else {
    $template = $this->defaults['template'];
  }

  $config = array(
    'url'                => esc_url_raw( rest_url( Freedam_Web_Notices_Public::REST_NAMESPACE . Freedam_Web_Notices_Public::REST_ROUTE ) ),
    'template'           => $template,
    'pageSize'           => empty( $page_size ) ? (int) $this->defaults['pagesize'] : (int) $page_size,
    'nulls'              => (bool) $nulls,
    'searchEnabled'      => (bool) $search,
    'imageEnabled'       => (bool) $image,
    'ascending'          => (bool) $ascending,
    'past'               => '' === $days_past ? null : (int) $days_past,
    'future'             => '' === $days_future ? null : (int) $days_future,
    'funeralDateFormat'  => strlen( $funeral_date ) > 0 ? $funeral_date : $this->defaults['funeraldate'],
    'funeralTimeFormat'  => strlen( $funeral_time ) > 0 ? $funeral_time : $this->defaults['funeraltime'],
    'dateType'           => strlen( $date_type )    > 0 ? $date_type    : $this->defaults['date_type'],
    'birthDateFormat'    => strlen( $birth_date )   > 0 ? $birth_date   : $this->defaults['birthdate'],
    'deathDateFormat'    => strlen( $death_date )   > 0 ? $death_date   : $this->defaults['deathdate'],
    'offices'            => $offices,
  );

  $unique_id     = esc_attr( uniqid( 'freedam-web-notices-' ) );
  $config_json   = wp_json_encode( $config );
?>

<freedam-web-notices-container id="<?php echo $unique_id; ?>">

  <div class="loading-indicator" role="status" aria-live="polite"><?php esc_html_e( 'Loading…', 'freedam-web-notices' ); ?></div>

  <script>
  (function () {
    var container = document.getElementById('<?php echo $unique_id; ?>');
    if ( !container || container.localName !== 'freedam-web-notices-container' ) {
      throw new Error("Couldn't find container element to add FreeDAM web notices to");
    }

    var cfg = <?php echo $config_json; ?>;
    var functionRan = false;

    function ready() {
      if ( typeof freedamWebNoticesGetNotices === 'function' && typeof freedamWebNoticesFormatDate === 'function' ) {
        if ( !functionRan ) {
          freedamWebNoticesGetNotices(
            container, cfg.template, cfg.url,
            1, cfg.pageSize, cfg.nulls, cfg.dateType,
            cfg.past, cfg.future, cfg.ascending,
            cfg.funeralDateFormat, cfg.funeralTimeFormat,
            cfg.birthDateFormat, cfg.deathDateFormat,
            '', cfg.searchEnabled, false, cfg.imageEnabled, cfg.offices
          );
          functionRan = true;
        }
        return;
      }
      // The plugin script is enqueued in the footer, so it hasn't loaded yet
      // when this inline script runs mid-body. Wait for the parser to finish:
      // DOMContentLoaded fires after every blocking footer script has executed.
      // If the script is deferred/async (some themes), fall back to window.load.
      if ( document.readyState === 'loading' ) {
        document.addEventListener( 'DOMContentLoaded', ready, { once: true, passive: true } );
      } else {
        window.addEventListener( 'load', ready, { once: true, passive: true } );
      }
    }

    ready();
  })();
  </script>

</freedam-web-notices-container>
