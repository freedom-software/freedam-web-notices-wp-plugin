<?php

/**
 * Provide a field for entering nulls
 *
 * This file is used to markup the admin-facing field for the nulls
 *
 * @link       https://github.com/freedom-software
 * @since      1.0.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin/partials
 */
  $option_name = $args['label_for'];
  $value = get_option( $option_name , $this->defaults['nulls']);
?>

<input
  type="checkbox"
  name="<?php echo esc_attr( $option_name ); ?>"
  id="<?php echo esc_attr( $option_name ); ?>"
  <?php checked( $value, true ); ?>
  aria-describedby="nulls-description"
>
<p
  class="description"
  id="nulls-description"
><?php echo esc_html( $args['title'] ); ?></p>
