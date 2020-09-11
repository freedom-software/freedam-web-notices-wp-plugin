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
  <option value="dddd, Do MMMM YYYY">Monday, 17rd January 1972</option>
  <option value="ddd Do MMMM YYYY">Mon 17rd January 1972</option>
  <option value="Do MMMM YYYY">17rd January 1972</option>
  <option value="Do MMM YYYY">17rd Jan 1972</option>
  <option value="D MMMM YYYY">17 Sept 1972</option>
  <option value="D.M.YYYY">17.1.1972</option>
  <option value="D/M/YYYY">17/1/1972</option>
  <option value="YYYY-MM-DD">1972-1-17</option>
  <option value="YYYY">1972</option>
</select>
<p
  class="description"
  id="birth-description"
><?php echo esc_html( $args['title'] ); ?></p>
