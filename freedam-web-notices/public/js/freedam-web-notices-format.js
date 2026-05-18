/**
 * Date formatter used by the FreeDAM Web Notices block + shortcode renderer.
 *
 * Replaces moment.js — supports the subset of moment format tokens used by
 * the plugin's preset date-format options. Exposed as
 * `window.freedamWebNoticesFormatDate(input, formatString)`.
 *
 * Supported tokens:
 *   dddd  - full weekday  (Wednesday)
 *   ddd   - short weekday (Wed)
 *   MMMM  - full month    (September)
 *   MMM   - short month   (Sep)
 *   MM    - month, zero-padded (09)
 *   M     - month               (9)
 *   YYYY  - 4-digit year  (2020)
 *   Do    - day with ordinal (23rd)
 *   DD    - day, zero-padded (23)
 *   D     - day              (23)
 *   HH    - 24-hour, zero-padded (13)
 *   H     - 24-hour              (13)
 *   hh    - 12-hour, zero-padded (01)
 *   h     - 12-hour              (1)
 *   mm    - minutes, zero-padded (05)
 *   m     - minutes              (5)
 *   A     - AM/PM
 *   a     - am/pm
 *
 * Any other characters in the format string are emitted verbatim.
 *
 * Plain ES5; no dependencies; works in any browser that runs modern WP.
 */
( function ( global ) {
	'use strict';

	var WEEKDAYS       = [ 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ];
	var WEEKDAYS_SHORT = [ 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' ];
	var MONTHS         = [ 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ];
	var MONTHS_SHORT   = [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ];

	// Longest-first so 4-char tokens win over their prefixes.
	var TOKENS = [
		'dddd', 'ddd',
		'MMMM', 'MMM', 'MM', 'M',
		'YYYY',
		'Do', 'DD', 'D',
		'HH', 'H', 'hh', 'h',
		'mm', 'm',
		'A', 'a'
	];

	function pad( n, width ) {
		var s = String( n );
		while ( s.length < width ) {
			s = '0' + s;
		}
		return s;
	}

	function ordinal( n ) {
		var suffixes = [ 'th', 'st', 'nd', 'rd' ];
		var v        = n % 100;
		return n + ( suffixes[ ( v - 20 ) % 10 ] || suffixes[ v ] || suffixes[ 0 ] );
	}

	function parse( input ) {
		if ( input == null || input === '' ) {
			return null;
		}
		if ( input instanceof Date ) {
			return isNaN( input.getTime() ) ? null : input;
		}
		if ( typeof input === 'string' ) {
			// Bare ISO date (no time component) — parse as local midnight to
			// match moment.js's behaviour. `new Date('1972-01-20')` would
			// otherwise be parsed as UTC and shift in the user's timezone.
			var bare = /^(\d{4})-(\d{2})-(\d{2})$/.exec( input );
			if ( bare ) {
				return new Date( parseInt( bare[ 1 ], 10 ), parseInt( bare[ 2 ], 10 ) - 1, parseInt( bare[ 3 ], 10 ) );
			}
		}
		var d = new Date( input );
		return isNaN( d.getTime() ) ? null : d;
	}

	function tokenValue( token, d ) {
		var day      = d.getDate();
		var monthIdx = d.getMonth();
		var year     = d.getFullYear();
		var weekday  = d.getDay();
		var h24      = d.getHours();
		var h12      = ( ( h24 + 11 ) % 12 ) + 1;
		var minutes  = d.getMinutes();
		var ampm     = h24 < 12 ? 'am' : 'pm';

		switch ( token ) {
			case 'dddd': return WEEKDAYS[ weekday ];
			case 'ddd':  return WEEKDAYS_SHORT[ weekday ];
			case 'MMMM': return MONTHS[ monthIdx ];
			case 'MMM':  return MONTHS_SHORT[ monthIdx ];
			case 'MM':   return pad( monthIdx + 1, 2 );
			case 'M':    return String( monthIdx + 1 );
			case 'YYYY': return pad( year, 4 );
			case 'Do':   return ordinal( day );
			case 'DD':   return pad( day, 2 );
			case 'D':    return String( day );
			case 'HH':   return pad( h24, 2 );
			case 'H':    return String( h24 );
			case 'hh':   return pad( h12, 2 );
			case 'h':    return String( h12 );
			case 'mm':   return pad( minutes, 2 );
			case 'm':    return String( minutes );
			case 'A':    return ampm.toUpperCase();
			case 'a':    return ampm;
		}
		return token;
	}

	function formatDate( input, format ) {
		if ( typeof format !== 'string' || format.length === 0 ) {
			return '';
		}
		var d = parse( input );
		if ( ! d ) {
			return ( input == null ) ? '' : String( input );
		}

		var out = '';
		var i   = 0;
		while ( i < format.length ) {
			var matched = false;
			for ( var t = 0; t < TOKENS.length; t++ ) {
				var tok = TOKENS[ t ];
				if ( format.substring( i, i + tok.length ) === tok ) {
					out += tokenValue( tok, d );
					i   += tok.length;
					matched = true;
					break;
				}
			}
			if ( ! matched ) {
				out += format.charAt( i );
				i   += 1;
			}
		}
		return out;
	}

	global.freedamWebNoticesFormatDate = formatDate;
} )( window );
