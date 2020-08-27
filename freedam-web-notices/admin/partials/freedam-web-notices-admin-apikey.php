<?php

/**
 * Provide a field for entring api key
 *
 * This file is used to markup the admin-facing field for the api key
 *
 * @link       https://github.com/Aidan-Chey
 * @since      1.0.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin/partials
 */
?>

<input
  type="text"
  name="<?php echo $this->option_name . '_apikey' ?>"
  id="<?php echo esc_attr( $args['label_for'] ); ?>"
  title="API Key used by the plugin to authenticate with and identify the database to retrieve the web-notices from"
  value="<?php echo get_option( $this->option_name . '_apikey' ) ?>"
>
