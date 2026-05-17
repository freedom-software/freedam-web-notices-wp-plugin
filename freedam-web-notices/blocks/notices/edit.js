/**
 * Editor entry for the FreeDAM Web Notices block.
 *
 * Renders a static placeholder in the editor. The real output is produced by
 * the PHP render_callback on the frontend, so save() returns null (dynamic
 * block) and the editor does not call the FreeDAM API.
 *
 * Written in plain ES5-compatible JS against the global `wp` UMD bundles so
 * the plugin does not require a build step.
 */
( function ( wp ) {
	if ( ! wp || ! wp.blocks || ! wp.element ) {
		return;
	}

	var el = wp.element.createElement;
	var __ = ( wp.i18n && wp.i18n.__ ) ? wp.i18n.__ : function ( s ) { return s; };
	var useBlockProps = ( wp.blockEditor && wp.blockEditor.useBlockProps )
		? wp.blockEditor.useBlockProps
		: function () { return {}; };

	wp.blocks.registerBlockType( 'freedam-web-notices/notices', {
		edit: function () {
			var blockProps = useBlockProps( {
				className: 'freedam-web-notices-block-placeholder',
				style: {
					border: '1px dashed #c3c4c7',
					borderRadius: '4px',
					padding: '1.25rem',
					background: '#f6f7f7',
					textAlign: 'center'
				}
			} );

			return el(
				'div',
				blockProps,
				el(
					'strong',
					{ style: { display: 'block', marginBottom: '0.25rem' } },
					__( 'FreeDAM Web Notices', 'freedam-web-notices' )
				),
				el(
					'span',
					{ style: { color: '#50575e', fontSize: '0.9em' } },
					__(
						'Notices will be displayed here on the published page. Configure output in Settings → FreeDAM Web Notices.',
						'freedam-web-notices'
					)
				)
			);
		},

		// Dynamic block — output comes from the PHP render_callback.
		save: function () {
			return null;
		}
	} );
} )( window.wp );
