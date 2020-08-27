<?php

/**
 * Provide a field for entring page size
 *
 * This file is used to markup the admin-facing field for the page size
 *
 * @link       https://github.com/Aidan-Chey
 * @since      1.0.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin/partials
 */
?>

<input
  type="number"
  name="<?php echo $this->option_name . '_pagesize' ?>"
  id="<?php echo $this->option_name . '_pagesize' ?>"
  value="<?php echo get_option( $this->option_name . '_pagesize' ) ?>"
>
