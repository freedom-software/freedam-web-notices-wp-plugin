
function freedamWebNoticesGetNotices(
	container,
	template,
	url,
	apiKey,
	page = 1,
	pageSize = 10,
	nulls = false,
	locale = 'en-NZ',
	past = null,
	future = null,
	ascending = false
) {
  // Add params to address
  url.searchParams.set('apiKey', apiKey);
  url.searchParams.set('page', page);
  url.searchParams.set('ascending', ascending);
  if ( pageSize !== undefined ) url.searchParams.set('pageSize', pageSize );
  if ( nulls !== undefined ) url.searchParams.set('nulls', nulls );
  if ( !!past ) {
  	// Convert number of days in the past limiter to a date
  	const afterDate = new Date();
  	const afterDays = afterDate.getDate() - past;
  	afterDate.setDate(afterDays);
  	url.searchParams.set('after', afterDate.toISOString() );
  }
  if ( !!future ) {
  	// Convert number of days in the future limiter to a date
  	const beforeDate = new Date();
  	const beforeDays = beforeDate.getDate() + future;
  	beforeDate.setDate(beforeDays);
  	url.searchParams.set('before', beforeDate.toISOString() );
  }

  // begin fetch request for web notices
  fetch( url )
    .then( response => response.status === 200 ? response.json() : [] )
    .catch( err => {
      console.error('Error while retrieveing web-notices from FreeDAM | ',err);
    } );
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

        const item = document.createElement('li');
        item.classList.add('freedam-web-notice');
        const toHide = [];

        let output = template;

        // Iterate over template token matches backwards as to not mess up indexes when replacing
        for ( const match of Array.from(matches).reverse() ) {
          /** @type {string} found token */
          const token = match[0];
          /** @type {string[]} token split into notice object path */
          const path = token.split('-');
          /** @type {any} Value for template token from notice data */
          let value = notice;
          // update value until reached value at end of token path
          for (let i in path) value = value[path[i]];
          // hide elements with token class
          if ( !value && (value !== 0 && value !== false) ) toHide.push(token);
          // convert value to a string for use in HTML
          if ( path.includes('dateTime') ) value = new Date(value).toLocaleString(locale);
          else value = typeof(value) === 'string' ? value : (value ?? '').toString();
          // Insert value, replacing token and encapsulating mostache ('{{'&'}}')
          const beforeToken = output.substring(0,match.index-2);
          const afterToken = output.substring(match.index + token.length + 2);
          output = beforeToken + value + afterToken;
        }

        // Add converted template with notice data to container
        item.innerHTML = output;

        if ( toHide.length ) toHide.forEach( token => { hideElementsByClass( item, token ) } );

        outputContainer.appendChild(item);

      } );

    // Add pagination
    // Find or create container for result pagination
    let paginationContainer = container.querySelector('div.paginator');
    if ( !paginationContainer ) {
      paginationContainer = document.createElement('div');
      paginationContainer.classList.add('paginator');
      container.appendChild(paginationContainer);
    } else paginationContainer.innerHTML = ''; // clear out the paginator if it aleady exists

    if ( !Array.isArray(data) ) data = [];

    if ( page < 2 && !data.length ) {
    	// Remove paginator if there is no data and we are on the first page
    	paginationContainer.remove();
    	return;
    }

  	if ( page > 1 ) {
  		// There was data before hand
  		// Add the "previous" button
  		const previousPageElement = document.createElement('button');
  		previousPageElement.classList.add('previous');
  		previousPageElement.textContent = 'Previous';
  		previousPageElement.onclick = () => {
  			freedamWebNoticesGetNotices( container, template, url, apiKey, page - 1, pageSize, nulls, locale, past, future, ascending );
			  container.scrollIntoView(true, { behavior: 'smooth' });
  		}
  		paginationContainer.appendChild(previousPageElement);
  	}

  	// Add the current page
  	const currentPageElement = document.createElement('span');
  	currentPageElement.classList.add('current');
  	currentPageElement.textContent = 'Page ' + page;
  	paginationContainer.appendChild(currentPageElement);

  	if ( data.length === pageSize ) {
  		// Assume there is more data to show
  		// Add the "next" button
  		const previousPageElement = document.createElement('button');
  		previousPageElement.classList.add('next');
  		previousPageElement.textContent = 'Next';
  		previousPageElement.onclick = () => {
  			freedamWebNoticesGetNotices( container, template, url, apiKey, page + 1, pageSize, nulls, locale, past, future, ascending );
			  container.scrollIntoView(true, { behavior: 'smooth' });
  		}
  		paginationContainer.appendChild(previousPageElement);
  	}

  } )
  .catch( err => {
    console.error('Error while displaying web-notices on page | ',err);
  } );

}

/** Adds style to elements with a certian class */
function hideElementsByClass( container, className ) {
  const elements = container.querySelectorAll( '.' + className );
  for (var i = elements.length - 1; i >= 0; i--) {
    const element = elements[i];
    const style = element.style;
    if ( style.getPropertyValue('display') !== 'none' ) style.setProperty('display','none','important');
  }
}
