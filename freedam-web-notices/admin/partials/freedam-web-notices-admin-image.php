<?php

/**
 * Provide a image on notices
 *
 * This file is used to markup the admin-facing field for the image
 *
 * @link       https://github.com/freedom-software
 * @since      1.3.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin/partials
 */
  $option_name = $args['label_for'];
  $value = get_option( $option_name , $this->defaults['image']);
?>

<input
  type="checkbox"
  name="<?php echo esc_attr( $option_name ); ?>"
  id="<?php echo esc_attr( $option_name ); ?>"
  <?php checked( $value, true ); ?>
  aria-describedby="image-description"
>
<p
  class="description"
  id="image-description"
><?php echo esc_html( $args['title'] ); ?></p>
