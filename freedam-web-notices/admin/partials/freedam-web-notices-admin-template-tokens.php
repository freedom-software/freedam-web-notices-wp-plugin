<?php
/**
 * Provide html to display accepted template tokens
 *
 * This file is used to request the notices definition from FreeDAM API and display the accepted tokens from it
 *
 * @link       https://github.com/freedom-software
 * @since      1.1.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin/partials
 */

  $response = wp_remote_get( $this->freedam_api_address . '/web-notices.def' );
  $definition = json_decode($response['body']);

  var_dump($definition->exits->success->outputExample[0]);

?>