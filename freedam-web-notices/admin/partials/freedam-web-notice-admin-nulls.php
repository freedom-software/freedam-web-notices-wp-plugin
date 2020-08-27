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

<label>
  <input type="checkbox" name="<?php echo $this->option_name . '_nulls' ?>" id="<?php echo $this->option_name . '_nulls' ?>">
  <?php _e( 'Include notices without date & time', 'freedam-web-notices' ); ?>
</label>
