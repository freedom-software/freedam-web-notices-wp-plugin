<?php

/**
 * Provide a field for entring api key
 *
 * This file is used to markup the admin-facing field for the api key
 *
 * @link       https://github.com/Aidan-Chey
 * @since      1.0.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin/partials
 */
?>

<label>
  <input type="text" name="<?php echo $this->option_name . '_apikey' ?>" id="<?php echo $this->option_name . '_apikey' ?>">
  <?php _e( 'API Key', 'freedam-web-notices' ); ?>
</label>
