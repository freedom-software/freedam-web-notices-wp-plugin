<?php

/**
 * Provide a field for entring past days
 *
 * This file is used to markup the admin-facing field for the past days
 *
 * @link       https://github.com/Aidan-Chey
 * @since      1.0.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin/partials
 */
  $option_name = $args['label_for'];
  $value = get_option( $option_name, $this->defaults['past'] );
?>

<input
  type="number"
  name="<?php echo esc_attr( $option_name ); ?>"
  id="<?php echo esc_attr( $option_name ); ?>"
  value="<?php echo esc_attr( $value ) ?>"
  class="small-text"
  step="1"
  aria-describedby="past-description"
>
<p
  class="description"
  id="past-description"
><?php echo esc_attr( $args['title'] ) ?></p>
