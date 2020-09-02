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
  $nulls = get_option( 'freedam_web_notices_nulls', $defaults['nulls'] );
  $page_size = get_option( 'freedam_web_notices_pagesize', $defaults['pagesize'] );
  $template = html_entity_decode(get_option( 'freedam_web_notices_template', htmlentities($defaults['template']) ));
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
    const nulls = <?php if ( $nulls ) { echo $nulls; } else { echo 'undefined'; } ?>;
    const pageSize = <?php if ( $page_size ) { echo $page_size; } else { echo 'undefined'; } ?>;
    const template = `<?php echo $template; ?>`;

    freedamWebNoticesGetNotices( template, url, apiKey, 1, pageSize, nulls );

  </script>

</freedam-web-notices-container>

