(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );

function freedamWebNoticesGetNotices( template, url, apiKey, page = 1, pageSize = 10, nulls = false ) {

  // Add params to address
  url.searchParams.set('apiKey', apiKey);
  url.searchParams.set('page', page);
  if ( pageSize !== undefined ) url.searchParams.set('pageSize', pageSize );
  if ( nulls !== undefined ) url.searchParams.set('nulls', nulls );

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

}
