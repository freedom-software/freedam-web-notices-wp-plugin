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

  $api_key = sanitize_text_field(get_option( 'freedam_web_notices_apikey' ));
  $nulls = sanitize_text_field(get_option( 'freedam_web_notices_nulls', $this->defaults['nulls'] ));
  $page_size = sanitize_text_field(get_option( 'freedam_web_notices_pagesize' ));
  $template = sanitize_text_field(get_option( 'freedam_web_notices_template'));
  $days_past = sanitize_text_field(get_option( 'freedam_web_notices_past' ));
  $days_future = sanitize_text_field(get_option( 'freedam_web_notices_future' ));
  $ascending = sanitize_text_field(get_option( 'freedam_web_notices_ascending', $this->defaults['ascending'] ));
  $funeral_date = sanitize_text_field(get_option( 'freedam_web_notices_funeral_date' ));
  $funeral_time = sanitize_text_field(get_option( 'freedam_web_notices_funeral_time' ));
  $date_type = sanitize_text_field(get_option( 'freedam_web_notices_date_type' ));
  $birth_date = sanitize_text_field(get_option( 'freedam_web_notices_birth_date' ));
  $death_date = sanitize_text_field(get_option( 'freedam_web_notices_death_date' ));
  $search = sanitize_text_field(get_option( 'freedam_web_notices_search', $this->defaults['search'] ));
  $image = sanitize_text_field(get_option( 'freedam_web_notices_image', $this->defaults['image'] ));
  $offices = sanitize_text_field(get_option( 'freedam_web_notices_offices' ));

  $unique_id = esc_attr( uniqid('freedam-web-notices-') );

  // This file should primarily consist of HTML with a little bit of PHP.
?>

<freedam-web-notices-container id="<?php echo $unique_id ?>">

  <script>

    let container = document.getElementById('<?php echo $unique_id ?>');
    if ( !container || container.localName !== 'freedam-web-notices-container' ) throw new Error('Couldn\'t find container element to add FreeDAM web notices to');

    const url = '<?php echo $this->freedam_api_address . $this->freedam_api_endpoint; ?>';
    const apiKey = '<?php echo $api_key; ?>';
    const offices = '<?php echo $offices; ?>';
    const nulls = <?php echo $nulls ? 'true' : 'false' ?>;
    const searchEnabled = <?php echo $search ? 'true' : 'false' ?>;
    const imageEnabled = <?php echo $image ? 'true' : 'false' ?>;
    const pageSize = <?php echo empty($page_size) ? $this->defaults['pagesize'] : $page_size ?>;
    const template = `<?php echo strlen($template) > 0 ? html_entity_decode($template) : $this->defaults['template'] ?>`;
    const past = <?php echo empty($days_past) ? 'null' : $days_past ?>;
    const future = <?php echo empty($days_future) ? 'null' : $days_future ?>;
    const ascending = <?php echo $ascending ? 'true' : 'false' ?>;
    const funeralDateFormat = '<?php echo strlen($funeral_date) > 0 ? $funeral_date : $this->defaults['funeraldate'] ?>';
    const funeralTimeFormat = '<?php echo strlen($funeral_time) > 0 ? $funeral_time : $this->defaults['funeraltime'] ?>';
    const dateType = '<?php echo strlen($date_type) > 0 ? $date_type : $this->defaults['date_type'] ?>';
    const birthDateFormat = '<?php echo strlen($birth_date) > 0 ? $birth_date : $this->defaults['birthdate'] ?>';
    const deathDateFormat = '<?php echo strlen($death_date) > 0 ? $death_date : $this->defaults['deathdate'] ?>';

    const loadScript = document.querySelector('#freedam-web-notices_public-js');
    const dateScript = document.querySelector('#freedam-web-notices_moment-js');

    let appliedLoadListener = false;
    let appliedDateListener = false;
    let functionRan = false;

    function freedamWebNoticesScriptsReady() {
      if ( typeof(freedamWebNoticesGetNotices) === 'function' && typeof(moment) === 'function' ) {
        if ( !functionRan ) {
          freedamWebNoticesGetNotices( container, template, url, apiKey, 1, pageSize, nulls, dateType, past, future, ascending, funeralDateFormat, funeralTimeFormat, birthDateFormat, deathDateFormat, '', searchEnabled, false, imageEnabled, offices );
          functionRan = true;
        }
      } else {
        if ( !appliedLoadListener ) {
          loadScript.addEventListener('load', freedamWebNoticesScriptsReady, { once: true, passive: true } );
          appliedLoadListener = true;
        }
        if ( !appliedDateListener ) {
          dateScript.addEventListener('load', freedamWebNoticesScriptsReady, { once: true, passive: true } );
          appliedDateListener = true;
        }
      }
    };

    freedamWebNoticesScriptsReady();

  </script>

</freedam-web-notices-container>
