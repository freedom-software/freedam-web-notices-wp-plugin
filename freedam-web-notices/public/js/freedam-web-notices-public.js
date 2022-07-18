
function freedamWebNoticesGetNotices(
	container,
	template,
	url,
	apiKey,
	page = 1,
	pageSize = 10,
	nulls = false,
  dateType = 'funeral',
	past = null,
	future = null,
	ascending = false,
  funeralDateFormat = 'l',
  funeralTimeFormat = 'LT',
  birthDateFormat = 'l',
  deathDateFormat = 'l',
  searchTerms = '',
  searchEnabled = true,
  scrollToTop = false,
  imageEnabled = false,
  offices = ''
) {
  // Add params to address
  urlObject = new URL(url);
  urlObject.searchParams.set('apiKey', apiKey);
  urlObject.searchParams.set('page', page);
  urlObject.searchParams.set('ascending', ascending);
  if ( pageSize !== undefined ) urlObject.searchParams.set('pageSize', pageSize );
  if ( nulls !== undefined ) urlObject.searchParams.set('nulls', nulls );
  if ( !!dateType && typeof(dateType) === 'string' ) urlObject.searchParams.set('dateType', dateType);
  if ( !!searchEnabled && typeof(searchTerms) === 'string' && !!searchTerms.length ) urlObject.searchParams.set('filterTerms', searchTerms );
  if ( !!imageEnabled ) urlObject.searchParams.set('includeImage', true );
  if ( !!offices ) urlObject.searchParams.set('office', JSON.stringify(offices.split(',')));
  if ( !!past ) {
  	// Convert number of days in the past limiter to a date
  	const afterDate = new Date();
  	const afterDays = afterDate.getDate() - past;
  	afterDate.setDate(afterDays);
    if ( afterDate.toString() === 'Invalid Date' ) console.error(`Could not determine appropriate 'past' limit date (${past}). Value too large?`);
  	else urlObject.searchParams.set('after', afterDate.toISOString() );
  }
  if ( !!future ) {
  	// Convert number of days in the future limiter to a date
  	const beforeDate = new Date();
  	const beforeDays = beforeDate.getDate() + future;
  	beforeDate.setDate(beforeDays);
    if ( beforeDate.toString() === 'Invalid Date' ) console.error(`Could not determine appropriate 'future' limit date (${future}). Value too large?`);
  	else urlObject.searchParams.set('before', beforeDate.toISOString() );
  }

  // begin fetch request for web notices
  fetch( urlObject )
    .then( response => response.status === 200 ? response.json() : [] )
    .catch( err => {
      console.error('Error while retrieving web-notices from FreeDAM | ',err);
    } )
    .then( data => {

      if ( !!searchEnabled ) {
      // Find or create container for search form
      let searchForm = container.querySelector('ul.freedam-web-notices');
      if ( !searchForm ) {
        searchForm = document.createElement('form');
        searchForm.classList.add('search-form');
        searchForm.onsubmit = submitEvent => {
          const form = submitEvent.srcElement;
          const searchField = form[0];
          freedamWebNoticesGetNotices( container, template, url, apiKey, 1, pageSize, nulls, dateType, past, future, ascending, funeralDateFormat, funeralTimeFormat, birthDateFormat, deathDateFormat, searchField.value, searchEnabled, true, imageEnabled, offices );
          return false;
        }
        container.appendChild(searchForm);
      } else searchForm.innerHTML = ''; // clear out the container if it already exists

        // Add the search field
        const searchElement = document.createElement('input');
        searchElement.classList.add('search-field');
        searchElement.placeholder = 'smith 2017 march';
        searchElement.type = 'search';
        searchElement.value = searchTerms;
        searchElement.name = 'searchTerms'
        searchElement.title = 'Search for an entry, using their name & funeral/death date';
        searchForm.appendChild(searchElement);

        //Add the search submit
        const searchButton = document.createElement('button');
        searchButton.classList.add('search-submit');
        searchButton.type = 'submit';
        searchButton.textContent = 'Search';
        searchForm.appendChild(searchButton);
      }

      // Find or create container for fetch result
      let outputContainer = container.querySelector('ul.freedam-web-notices');
      if ( !outputContainer ) {
        outputContainer = document.createElement('ul');
        outputContainer.classList.add('freedam-web-notices');
        container.appendChild(outputContainer);
      } else outputContainer.innerHTML = ''; // clear out the container if it already exists

      // loop through notices, replacing template tokens and adding resulting HTML to DOM
      if ( Array.isArray(data) && !!data.length ) data.forEach( notice => {

        const open = '{{';
        const close = '}}';
        // Matches strings between open and close moustache
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
          if ( path.includes('funeral') && path.includes('dateTime') ) value = moment(value).format( funeralDateFormat + ' ' + funeralTimeFormat );
          else if ( path.includes('deceased') && path.includes('birthDate') ) value = moment(value).format( birthDateFormat );
          else if ( path.includes('deceased') && path.includes('deathDate') ) value = moment(value).format( deathDateFormat );
          else value = typeof(value) === 'string' ? value : (value ?? '').toString();
          // Insert value, replacing token and encapsulating moustache ('{{'&'}}')
          const beforeToken = output.substring(0,match.index-2);
          const afterToken = output.substring(match.index + token.length + 2);
          output = beforeToken + value + afterToken;
        }

        // Add converted template with notice data to container
        item.innerHTML = output;

        if ( toHide.length ) toHide.forEach( token => { hideElementsByClass( item, token ) } );

        outputContainer.appendChild(item);

      } );
      else if ( page > 1 ) {
        const message = document.createElement('li');
        message.innerHTML = 'No further notices available';
        outputContainer.appendChild(message);
      }
      else if ( !!searchTerms ) {
        const message = document.createElement('li');
        message.innerHTML = 'No notices match search query';
        outputContainer.appendChild(message);
      }

    // Add pagination
    // Find or create container for result pagination
    let paginationContainer = container.querySelector('div.paginator');
    if ( !paginationContainer ) {
      paginationContainer = document.createElement('div');
      paginationContainer.classList.add('paginator');
      container.appendChild(paginationContainer);
    } else paginationContainer.innerHTML = ''; // clear out the paginator if it already exists

    if ( !Array.isArray(data) ) data = [];

    // More than a page of results
    if ( (page === 1 && data.length >= pageSize) || page > 1 ) {

      // Add the "previous" button
      const previousPageElement = document.createElement('button');
      previousPageElement.classList.add('previous');
      previousPageElement.textContent = 'Previous';
      if ( page < 2 ) previousPageElement.disabled = true;
      previousPageElement.onclick = () => {
        freedamWebNoticesGetNotices( container, template, url, apiKey, page - 1, pageSize, nulls, dateType, past, future, ascending, funeralDateFormat, funeralTimeFormat, birthDateFormat, deathDateFormat, searchTerms, searchEnabled, true, imageEnabled, offices );
        container.scrollIntoView(true, { behavior: 'smooth' });
      }
      paginationContainer.appendChild(previousPageElement);

      // Add the current page
      const currentPageElement = document.createElement('span');
      currentPageElement.classList.add('current');
      currentPageElement.textContent = 'Page ' + page;
      paginationContainer.appendChild(currentPageElement);

      // Add the "next" button
      const nextPageElement = document.createElement('button');
      nextPageElement.classList.add('next');
      nextPageElement.textContent = 'Next';
      if ( data.length < pageSize ) nextPageElement.disabled = true;
      nextPageElement.onclick = () => {
        freedamWebNoticesGetNotices( container, template, url, apiKey, page + 1, pageSize, nulls, dateType, past, future, ascending, funeralDateFormat, funeralTimeFormat, birthDateFormat, deathDateFormat, searchTerms, searchEnabled, true, imageEnabled, offices );
      }
      paginationContainer.appendChild(nextPageElement);

    }

    if ( !!scrollToTop ) {
      setTimeout( () => { container.scrollIntoView(true, { behavior: 'smooth' }); }, 20 );
    }
  } )
  .catch( err => {
    console.error('Error while displaying web-notices on page | ',err);
  } );

}

/** Adds style to elements with a certain class */
function hideElementsByClass( container, className ) {
  const elements = container.querySelectorAll( '.' + className );
  for (var i = elements.length - 1; i >= 0; i--) {
    const element = elements[i];
    const style = element.style;
    if ( style.getPropertyValue('display') !== 'none' ) style.setProperty('display','none','important');
  }
}
