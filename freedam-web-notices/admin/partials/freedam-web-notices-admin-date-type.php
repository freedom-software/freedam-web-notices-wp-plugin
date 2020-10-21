<?php

/**
 * Provide a field for determining type of date for sorting
 *
 * This file is used to markup the admin-facing field for the date type
 *
 * @link       https://github.com/freedom-software
 * @since      1.2.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin/partials
 */
  $option_name = $args['label_for'];
  $value = get_option( $option_name, $this->defaults['date_type'] );
  $options = $this->options_date_type;
?>

<select
  name="<?php echo esc_attr( $option_name ); ?>"
  id="<?php echo esc_attr( $option_name ); ?>"
  aria-describedby="date_type-description"
>
<?php foreach ($options as $key => $example) {
  echo '<option '.selected( $value, $key, false ).' value="'.esc_attr($key).'">'.esc_html($example).'</option>';
} ?>
</select>
<p
  class="description"
  id="date_type-description"
><?php echo esc_html( $args['title'] ); ?></p>
