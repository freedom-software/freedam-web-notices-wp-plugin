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
  $days_past = get_option( 'freedam_web_notices_past' );
  $days_future = get_option( 'freedam_web_notices_future' );
  $ascending = get_option( 'freedam_web_notices_ascending', $this->defaults['ascending'] );
  $funeral_date = get_option( 'freedam_web_notices_funeral_date' );
  $funeral_time = get_option( 'freedam_web_notices_funeral_time' );
  $birth_date = get_option( 'freedam_web_notices_birth_date' );
  $death_date = get_option( 'freedam_web_notices_death_date' );
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
    const past = <?php echo is_int($days_past) ? $days_past : 'null' ?>;
    const future = <?php echo is_int($days_future) ? $days_future : 'null' ?>;
    const ascending = <?php echo $ascending ? 'true' : 'false' ?>;
    const funeralDateFormat = '<?php echo strlen($funeral_date) > 0 ? $funeral_date : $this->defaults['funeraldate'] ?>';
    const funeralTimeFormat = '<?php echo strlen($funeral_time) > 0 ? $funeral_time : $this->defaults['funeraltime'] ?>';
    const brithDateFormat = '<?php echo strlen($birth_date) > 0 ? $birth_date : $this->defaults['birthdate'] ?>';
    const deathDateFormat = '<?php echo strlen($death_date) > 0 ? $death_date : $this->defaults['deathdate'] ?>';

    freedamWebNoticesGetNotices( container, template, url, apiKey, 1, pageSize, nulls, past, future, ascending, funeralDateFormat, funeralTimeFormat, brithDateFormat, deathDateFormat );

  </script>

</freedam-web-notices-container>

