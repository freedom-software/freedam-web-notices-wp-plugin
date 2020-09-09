<?php

/**
 * Provide a field for entring a notice template
 *
 * This file is used to markup the admin-facing field for the notice template
 *
 * @link       https://github.com/freedom-software
 * @since      1.0.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin/partials
 */
  $option_name = $args['label_for'];
  $value = html_entity_decode(get_option($option_name));
?>

<textarea
  name="<?php echo esc_attr( $option_name ); ?>"
  id="<?php echo esc_attr( $option_name ); ?>"
  class="large-text"
  aria-describedby="template-description"
  rows="6"
  wrap="off"
  spellcheck="false"
><?php echo (strlen($value) > 0 ? $value : $this->defaults['template']); ?></textarea>
<p
  class="description"
  id="template-description"
><?php echo esc_html( $args['title'] ); ?></p>
