<?php

/**
 * Provide a field for entring ascending
 *
 * This file is used to markup the admin-facing field for the ascending
 *
 * @link       https://github.com/Aidan-Chey
 * @since      1.0.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin/partials
 */
  $option_name = $args['label_for'];
  $value = get_option( $option_name , $this->defaults['ascending']);
?>

<input
  type="checkbox"
  name="<?php echo esc_attr( $option_name ); ?>"
  id="<?php echo esc_attr( $option_name ); ?>"
  <?php checked( $value, true ); ?>
  aria-describedby="ascending-description"
>
<p
  class="description"
  id="ascending-description"
><?php echo esc_html( $args['title'] ); ?></p>
