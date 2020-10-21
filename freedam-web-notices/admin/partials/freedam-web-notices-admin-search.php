<?php

/**
 * Provide a field for searching web-notices
 *
 * This file is used to markup the admin-facing field for the search
 *
 * @link       https://github.com/freedom-software
 * @since      1.2.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin/partials
 */
  $option_name = $args['label_for'];
  $value = get_option( $option_name , $this->defaults['search']);
?>

<input
  type="checkbox"
  name="<?php echo esc_attr( $option_name ); ?>"
  id="<?php echo esc_attr( $option_name ); ?>"
  <?php checked( $value, true ); ?>
  aria-describedby="search-description"
>
<p
  class="description"
  id="search-description"
><?php echo esc_html( $args['title'] ); ?></p>
