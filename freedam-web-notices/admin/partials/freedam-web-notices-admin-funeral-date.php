<?php

/**
 * Provide a field for entering birth date format
 *
 * This file is used to markup the admin-facing field for the birth date format
 *
 * @link       https://github.com/freedom-software
 * @since      1.1.1
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin/partials
 */
  $option_name = $args['label_for'];
  $value = get_option( $option_name, $this->defaults['funeraldate'] );
?>
<select
  name="<?php echo esc_attr( $option_name ); ?>"
  id="<?php echo esc_attr( $option_name ); ?>"
  aria-describedby="birth-description"
>
<?php foreach ($this->options_funeral_date as $key => $example) {
  echo '<option '.selected( $value, $key, false ).' value="'.esc_attr($key).'">'.esc_html($example).'</option>';
} ?>
</select>
<p
  class="description"
  id="birth-description"
><?php echo esc_html( $args['title'] ); ?></p>
