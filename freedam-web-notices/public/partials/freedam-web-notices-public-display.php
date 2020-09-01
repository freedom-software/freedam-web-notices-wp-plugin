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
    const apiKey = '<?php echo $api_key ?>';
    const nulls<?php if ( $nulls ) echo ' = ' . $nulls ?>;
    const pageSize<?php if ( $page_size ) echo ' = ' . $page_size ?>;
    const template = `<?php echo $template ?>`;

    // Add params to address
    url.searchParams.set('apiKey', );
    if ( nulls !== undefined ) url.searchParams.set('nulls', nulls );
    if ( pageSize !== undefined ) url.searchParams.set('pageSize', pageSize );

    try {

      // begin fetch request for web notices
      fetch( url )
        .then( response => response.json() )
        .then( data => {

          // Find or create container for fetch result
          let outputContainer = container.querySelector('ul.freedam-web-notices');
          if ( !outputContainer ) {
            outputContainer = document.createElement('ul');
            outputContainer.classList.add('freedam-web-notices');
            container.appendChild(outputContainer);
          } else outputContainer.innerHTML = ''; // clear out the container if it aleady exists

          // loop through notices, replacing template tokens and adding resulting HTML to DOM
          if ( Array.isArray(data) && !!data.length ) data.forEach( notice => {

            const open = '{{';
            const close = '}}';
            // Matches strings between open and close mostache
            const regexp = RegExp(`(?<=${open})(.*?)(?=${close})`,'g');
            const matches = template.matchAll(regexp);
            let output = template;

            // Iterate over template token matches backwards as to not mess up indexes when replacing
            for ( const match of Array.from(matches).reverse() ) {
              /** @type {string} found token */
              const token = match[0];
              /** @type {string[]} token split into notice object path */
              const path = token.split('.');
              /** @type {any} Value for template token from notice data */
              let value = notice;
              // update value until reached value at end of token path
              for (let i in path) value = value[path[i]];
              // convert value to a string for use in HTML
              value = typeof(value) === 'string' ? value : (value ?? '').toString();
              // Insert value, replacing token and encapsulating mostache ('{{'&'}}')
              const beforeToken = output.substring(0,match.index-2);
              const afterToken = output.substring(match.index + token.length + 2);
              output = beforeToken + value + afterToken;
            }

            // Add converted template with notice data to container
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

