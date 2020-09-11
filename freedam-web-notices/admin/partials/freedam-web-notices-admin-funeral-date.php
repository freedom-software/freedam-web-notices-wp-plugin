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
  <option value="dddd, Do MMMM YYYY">Wednesday, 23th September 2020</option>
  <option value="ddd Do MMMM YYYY">Wed 23th September 2020</option>
  <option value="Do MMMM YYYY">23th September 2020</option>
  <option value="Do MMM YYYY">23th Sept 2020</option>
  <option value="D MMMM YYYY">23 Sept 2020</option>
  <option value="D/M/YYYY">23/9/2020</option>
  <option value="YYYY-MM-DD">2020-09-23</option>
</select>
<p
  class="description"
  id="birth-description"
><?php echo esc_html( $args['title'] ); ?></p>
