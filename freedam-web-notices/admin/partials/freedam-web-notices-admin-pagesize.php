<?php

/**
 * Provide a field for entering page size
 *
 * This file is used to markup the admin-facing field for the page size
 *
 * @link       https://github.com/freedom-software
 * @since      1.0.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin/partials
 */
  $option_name = $args['label_for'];
  $value = get_option( $option_name, $this->defaults['pagesize'] );
?>

<input
  type="number"
  name="<?php echo esc_attr( $option_name ); ?>"
  id="<?php echo esc_attr( $option_name ); ?>"
  value="<?php echo esc_attr( $value ); ?>"
  class="small-text"
  min="1"
  max="100"
  step="1"
  aria-describedby="pagesize-description"
>
<p
  class="description"
  id="pagesize-description"
><?php echo esc_html( $args['title'] ); ?></p>
