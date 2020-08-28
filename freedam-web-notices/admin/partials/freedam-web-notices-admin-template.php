<?php

/**
 * Provide a field for entring a notice template
 *
 * This file is used to markup the admin-facing field for the notice template
 *
 * @link       https://github.com/Aidan-Chey
 * @since      1.0.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin/partials
 */

  $value = get_option( $this->option_name . '_template', $this->default_template )
?>

<textarea
  name="<?php echo $this->option_name . '_template' ?>"
  id="<?php echo esc_attr( $args['label_for'] ); ?>"
  title="Customize the layout of individual web notices"
  class="large-text"
><?php echo (strlen($value) > 0 ? $value : $this->default_template) ?></textarea>
