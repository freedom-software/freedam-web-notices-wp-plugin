<?php

/**
 * Provide a field for entering api key
 *
 * This file is used to markup the admin-facing field for the api key
 *
 * @link       https://github.com/freedom-software
 * @since      1.0.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin/partials
 */
  $option_name = $args['label_for'];
  $value = get_option( $option_name );
?>

<input
  type="text"
  name="<?php echo esc_attr( $option_name ); ?>"
  id="<?php echo esc_attr( $option_name ); ?>"
  value="<?php echo esc_attr( $value ); ?>"
  class="large-text"
  minlength="128"
  maxlength="128"
  aria-describedby="apikey-description"
>
<p
  class="description"
  id="apikey-description"
><?php echo esc_attr( $args['title'] ); ?></p>
