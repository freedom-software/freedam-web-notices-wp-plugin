/**
 * Editor entry for the FreeDAM Web Notices block.
 *
 * Renders a preview in the editor using the user's saved settings and a small
 * set of placeholder notices. The real output on the published page is
 * produced by the PHP render_callback, so save() returns null (dynamic block)
 * and no FreeDAM API call is ever made from the editor.
 *
 * The template substitution / null-hiding / moment-date-formatting here
 * mirrors public/js/freedam-web-notices-public.js so the preview matches what
 * visitors will see.
 *
 * Written in plain ES5-compatible JS against the global `wp` UMD bundles so
 * the plugin does not require a build step.
 */
( function ( wp ) {
	if ( ! wp || ! wp.blocks || ! wp.element ) {
		return;
	}

	var el            = wp.element.createElement;
	var useRef        = wp.element.useRef;
	var useEffect     = wp.element.useEffect;
	var __            = ( wp.i18n && wp.i18n.__ ) ? wp.i18n.__ : function ( s ) { return s; };
	var useBlockProps = ( wp.blockEditor && wp.blockEditor.useBlockProps )
		? wp.blockEditor.useBlockProps
		: function () { return {}; };

	var data     = window.freedamWebNoticesEditorData || {};
	var settings = data.settings || {};
	var samples  = Array.isArray( data.sampleNotices ) ? data.sampleNotices : [];

	/**
	 * Apply the user's template to one notice. Mirrors the logic in
	 * freedamWebNoticesGetNotices() so the editor preview is faithful.
	 *
	 * @param {string} template
	 * @param {Object} notice
	 * @return {{ html: string, toHide: string[] }}
	 */
	function renderNotice( template, notice ) {
		var open  = '{{';
		var close = '}}';
		var regex = new RegExp( open + '(.*?)' + close, 'g' );

		var matches = [];
		var match;
		while ( ( match = regex.exec( template ) ) !== null ) {
			matches.push( match );
		}

		var toHide = [];
		var output = template;

		// Walk matches in reverse so index positions remain valid as we splice.
		for ( var i = matches.length - 1; i >= 0; i-- ) {
			var m     = matches[ i ];
			var token = m[ 1 ];
			var path  = token.split( '-' );

			var value = notice;
			for ( var j = 0; j < path.length; j++ ) {
				value = ( value != null && value[ path[ j ] ] !== undefined ) ? value[ path[ j ] ] : undefined;
			}

			if ( ! value && value !== 0 && value !== false ) {
				toHide.push( token );
			}

			var hasMoment = ( typeof window.moment === 'function' );
			if ( path.indexOf( 'funeral' ) !== -1 && path.indexOf( 'dateTime' ) !== -1 && hasMoment ) {
				value = window.moment( value ).format( ( settings.funeralDateFormat || '' ) + ' ' + ( settings.funeralTimeFormat || '' ) );
			} else if ( path.indexOf( 'deceased' ) !== -1 && path.indexOf( 'birthDate' ) !== -1 && hasMoment ) {
				value = window.moment( value ).format( settings.birthDateFormat || '' );
			} else if ( path.indexOf( 'deceased' ) !== -1 && path.indexOf( 'deathDate' ) !== -1 && hasMoment ) {
				value = window.moment( value ).format( settings.deathDateFormat || '' );
			} else {
				value = ( typeof value === 'string' ) ? value : ( value == null ? '' : String( value ) );
			}

			output = output.substring( 0, m.index ) + value + output.substring( m.index + m[ 0 ].length );
		}

		return { html: output, toHide: toHide };
	}

	function PreviewBlock() {
		var blockProps = useBlockProps();
		var listRef    = useRef( null );

		var template     = settings.template || '';
		var pageSizeCap  = settings.pageSize ? Math.min( samples.length, settings.pageSize ) : samples.length;
		var visible      = samples.slice( 0, pageSizeCap );
		var renderedAll  = visible.map( function ( notice ) { return renderNotice( template, notice ); } );
		var combinedHtml = renderedAll.map( function ( r ) {
			return '<li class="freedam-web-notice">' + r.html + '</li>';
		} ).join( '' );

		// After React paints, apply the null-token hide pass to the live DOM.
		useEffect( function () {
			if ( ! listRef.current ) {
				return;
			}
			var items = listRef.current.children;
			renderedAll.forEach( function ( r, idx ) {
				var li = items[ idx ];
				if ( ! li ) {
					return;
				}
				r.toHide.forEach( function ( token ) {
					var els = li.querySelectorAll( '.' + token );
					for ( var k = 0; k < els.length; k++ ) {
						els[ k ].style.setProperty( 'display', 'none', 'important' );
					}
				} );
			} );
		} );

		var infoBar = el(
			'div',
			{
				style: {
					border:       '1px dashed #c3c4c7',
					borderRadius: '4px',
					padding:      '0.5rem 0.75rem',
					marginBottom: '0.5rem',
					background:   '#f6f7f7',
					fontSize:     '0.85em',
					color:        '#50575e'
				}
			},
			el( 'strong', null, __( 'FreeDAM Web Notices', 'freedam-web-notices' ) ),
			' — ',
			__( 'editor preview using sample data. Real notices appear on the published page.', 'freedam-web-notices' )
		);

		var previewList = el( 'ul', {
			className:               'freedam-web-notices',
			ref:                     listRef,
			dangerouslySetInnerHTML: { __html: combinedHtml }
		} );

		// Use the same custom element tag the frontend partial emits, so the
		// public stylesheet (which scopes every rule under the
		// `freedam-web-notices-container` element selector) actually matches.
		var wrapper = el( 'freedam-web-notices-container', null, previewList );

		return el( 'div', blockProps, infoBar, wrapper );
	}

	wp.blocks.registerBlockType( 'freedam-web-notices/notices', {
		edit: PreviewBlock,

		// Dynamic block — output comes from the PHP render_callback.
		save: function () {
			return null;
		}
	} );
} )( window.wp );
