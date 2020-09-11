<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://github.com/freedom-software
 * @since      1.0.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/public/partials
 */

  $api_key = get_option( 'freedam_web_notices_apikey' );
  $nulls = get_option( 'freedam_web_notices_nulls', $this->defaults['nulls'] );
  $page_size = get_option( 'freedam_web_notices_pagesize' );
  $template = html_entity_decode(get_option( 'freedam_web_notices_template'));
  $locale = get_user_locale();
  $days_past = get_option( 'freedam_web_notices_past' );
  $days_future = get_option( 'freedam_web_notices_future' );
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
    const nulls = <?php echo $nulls ? 'true' : 'false' ?>;
    const pageSize = <?php echo is_int($page_size) ? $page_size : $this->defaults['pagesize'] ?>;
    const template = `<?php echo strlen($template) > 0 ? $template : $this->defaults['template'] ?>`;
    const locale = navigator.language //(Netscape - Browser Localization)
     || navigator.browserLanguage //(IE-Specific - Browser Localized Language)
     || navigator.systemLanguage //(IE-Specific - Windows OS - Localized Language)
     || navigator.userLanguage
     || '<?php echo $locale ? str_replace('_', '-', $locale) : 'en-NZ' ?>';
    const past = <?php echo is_int($days_past) ? $days_past : 'null' ?>;
    const future = <?php echo is_int($days_future) ? $days_future : 'null' ?>;
    const ascending = <?php echo $ascending ? 'true' : 'false' ?>

    freedamWebNoticesGetNotices( container, template, url, apiKey, 1, pageSize, nulls, locale, past, future, ascending );

  </script>

</freedam-web-notices-container>

