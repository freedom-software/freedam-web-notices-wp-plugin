<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/Aidan-Chey
 * @since      1.0.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<form action="/wp-admin/admin-post.php" method="POST">
  <input type="hidden" name="action" value="freedam-web-notices-settings">
  <p>
    <label for="freedam-api-key">API Key</label>
    <input id="freedam-api-key" type="text" name="apiKey" value="<?php echo $freedam_api_params['apiKey']; ?>">
  </p>
  <p>
    <label for="freedam-api-page-size">Page Size</label>
    <input id="freedam-api-page-size" type="number" name="pageSize" value="<?php echo $freedam_api_params['pageSize']; ?>">
  </p>
  <p>
    <label for="freedam-api-nulls">Include notices without date & time</label>
    <input id="freedam-api-nulls" type="checkbox" name="nulls" value="<?php echo $freedam_api_params['nulls']; ?>">
  </p>
</form>
