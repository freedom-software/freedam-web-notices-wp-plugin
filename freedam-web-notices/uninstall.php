<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * Removes every option this plugin has stored, including the API key, so no
 * trace of the plugin's configuration is left behind in the database. Handles
 * both single-site and multisite installations.
 *
 * @link       https://github.com/freedom-software
 * @since      1.0.0
 *
 * @package    Freedam_Web_Notices
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Every option name this plugin writes via register_setting().
 *
 * Keep this list in sync with admin/class-freedam-web-notices-admin.php — if a
 * new setting is added there, add it here so it is cleaned up on uninstall.
 */
$freedam_web_notices_options = array(
	'freedam_web_notices_apikey',
	'freedam_web_notices_pagesize',
	'freedam_web_notices_date_type',
	'freedam_web_notices_past',
	'freedam_web_notices_future',
	'freedam_web_notices_nulls',
	'freedam_web_notices_search',
	'freedam_web_notices_image',
	'freedam_web_notices_offices',
	'freedam_web_notices_ascending',
	'freedam_web_notices_funeral_date',
	'freedam_web_notices_funeral_time',
	'freedam_web_notices_birth_date',
	'freedam_web_notices_death_date',
	'freedam_web_notices_template',
);

/**
 * Delete every plugin option on the current site.
 *
 * @param string[] $options Option names to delete.
 */
$freedam_web_notices_delete_options = static function ( array $options ) {
	foreach ( $options as $option ) {
		delete_option( $option );
	}
};

if ( is_multisite() ) {
	// On a network, the plugin's settings are stored per-site. Visit each site
	// and delete its copies. get_sites() can be expensive on very large
	// networks; cap the batch size so we don't load the entire site list at
	// once if there are thousands.
	$site_args  = array(
		'fields' => 'ids',
		'number' => 1000,
		'offset' => 0,
	);
	$batch_size = $site_args['number'];

	do {
		$site_ids = get_sites( $site_args );
		foreach ( $site_ids as $site_id ) {
			switch_to_blog( (int) $site_id );
			$freedam_web_notices_delete_options( $freedam_web_notices_options );
			restore_current_blog();
		}
		$site_args['offset'] += $batch_size;
	} while ( count( $site_ids ) === $batch_size );
} else {
	$freedam_web_notices_delete_options( $freedam_web_notices_options );
}
