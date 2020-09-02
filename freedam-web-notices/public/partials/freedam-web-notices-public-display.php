<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://github.com/Aidan-Chey
 * @since      1.0.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/public/partials
 */

  $api_key = get_option( 'freedam_web_notices_apikey' );
  $nulls = get_option( 'freedam_web_notices_nulls', $this->defaults['nulls'] );
  $page_size = get_option( 'freedam_web_notices_pagesize', $this->defaults['pagesize'] );
  $template = html_entity_decode(get_option( 'freedam_web_notices_template', htmlentities($this->defaults['template']) ));
  $locale = get_user_locale();
  $days_past = get_option( 'freedam_web_notices_past', $this->defaults['past'] );
  $days_future = get_option( 'freedam_web_notices_future', $this->defaults['future'] );
  $ascending = get_option( 'freedam_web_notices_ascending', $this->defaults['ascending'] );
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<freedam-web-notices-container>

  <script>

    const container = document.currentScript.parentNode;
    if ( !container ) {
      throw new Error('Couldn\'t find container element to add FreeDAM web notices to');
    }

    const url = new URL('<?php echo $this->freedam_api_address . $this->freedam_api_endpoint; ?>');
    const apiKey = '<?php echo $api_key; ?>';
    const nulls = !!(<?php if ( $nulls ) { echo $nulls; } else { echo 'undefined'; } ?>);
    const pageSize = <?php if ( $page_size ) { echo $page_size; } else { echo 'undefined'; } ?>;
    const template = `<?php echo $template; ?>`;
    const locale = navigator.language //(Netscape - Browser Localization)
     || navigator.browserLanguage //(IE-Specific - Browser Localized Language)
     || navigator.systemLanguage //(IE-Specific - Windows OS - Localized Language)
     || navigator.userLanguage
     || '<?php if( $locale ) { echo str_replace('_', '-', $locale); } else { echo 'en-NZ'; } ?>';
    const past = <?php if ( $days_past ) { echo $days_past; } else { echo 'null'; } ?>;
    const future = <?php if ( $days_future ) { echo $days_future; } else { echo 'null'; } ?>;
    const ascending = !!(<?php if ( $ascending ) { echo $ascending; } else { echo 'undefined'; } ?>)

    freedamWebNoticesGetNotices( container, template, url, apiKey, 1, pageSize, nulls, locale, past, future, ascending );

  </script>

</freedam-web-notices-container>

