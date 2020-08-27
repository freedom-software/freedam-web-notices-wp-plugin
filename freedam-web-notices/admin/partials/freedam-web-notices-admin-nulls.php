<?php

/**
 * Provide a field for entring nulls
 *
 * This file is used to markup the admin-facing field for the nulls
 *
 * @link       https://github.com/Aidan-Chey
 * @since      1.0.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin/partials
 */
?>

<input
  type="checkbox"
  name="<?php echo $this->option_name . '_nulls' ?>"
  id="<?php echo esc_attr( $args['label_for'] ); ?>"
  title="Whether notices that don\'t have a funeral date/time should be included in results"
  <?php checked( get_option( $this->option_name . '_nulls' ), true ) ?>
>
