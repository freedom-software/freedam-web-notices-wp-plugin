<?php

/**
 * Provide a field for entring birth date format
 *
 * This file is used to markup the admin-facing field for the birth date format
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
<select
  name="<?php echo esc_attr( $option_name ); ?>"
  id="<?php echo esc_attr( $option_name ); ?>"
  value="<?php echo esc_attr( $value ); ?>"
  aria-describedby="birth-description"
>
  <option value="h:mm a">1:00 pm</option>
  <option value="h:mm A">1:00 PM</option>
  <option value="HH:mm">13:00</option>
</select>
<p
  class="description"
  id="birth-description"
><?php echo esc_html( $args['title'] ); ?></p>
