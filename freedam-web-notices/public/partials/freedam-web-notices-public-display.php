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
  $nulls = get_option( 'freedam_web_notices_nulls' );
  $page_size = get_option( 'freedam_web_notices_pagesize' );
  $template = html_entity_decode(get_option( 'freedam_web_notices_template', htmlentities($default_template) ));
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<freedam-web-notices-container>

  <script>

    const container = document.currentScript.parentNode;
    if ( !container ) {
      throw new Error('Couldn\'t find container element to add FreeDAM web notices to');
    }

    const url = new URL('<?php echo $this->freedam_api_address . $this->freedam_api_endpoint ?>');

    // Add params to address
    url.searchParams.set('apiKey', '<?php echo $api_key ?>');
    <?php if ( $nulls ) echo "url.searchParams.set('nulls', " . $nulls . ");"; ?>
    <?php if ( $page_size ) echo "url.searchParams.set('pageSize', " . $page_size . ");"; ?>

    // begin fetch request
    try {

      fetch( url )
        .then( response => response.json() )
        .then( data => {

          const template = `<?php echo $template ?>`;

          // Find or create container for fetch result
          let outputContainer = container.querySelector('ul.freedam-web-notices');
          if ( !outputContainer ) {
            outputContainer = document.createElement('ul');
            outputContainer.classList.add('freedam-web-notices');
            container.appendChild(outputContainer);
          } else outputContainer.innerHTML = '';

          if ( Array.isArray(data) && !!data.length ) data.forEach( notice => {

            const open = '{{';
            const close = '}}';
            // Matches strings between open and close mostache
            const regexp = RegExp(`(?<=${open})(.*?)(?=${close})`,'g');
            const matches = template.matchAll(regexp);
            let output = template;

            // Iterate over string backwards as to not mess up indexes when replacing
            for ( const match of Array.from(matches).reverse() ) {
              // found token
              const token = match[0];
              // find value in notice matching requested date (deceased.name.last)
              const path = token.split('.');
              let value = notice;
              for (let i in path) value = value[path[i]];
              // Value for template token from notice data
              // convert to a string for use in the HTML
              value = typeof(value) === 'string' ? value : (value ?? '').toString();
              // Insert notice data, replacing token and encapsulating mostache ('{{'&'}}')
              const beforeToken = output.substring(0,match.index-2);
              const afterToken = output.substring(match.index + token.length + 2);
              output = beforeToken + value + afterToken;
            }

            // Add notice to container
            const item = document.createElement('li');
            item.classList.add('freedam-web-notice');
            item.innerHTML = output;
            outputContainer.appendChild(item);

          } );

      } );

    } catch (err) {
      console.error('Error while retrieveing web-notices from FreeDAM | ',err);
    }

  </script>

</freedam-web-notices-container>

