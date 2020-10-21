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

  /** Loops through nested object adding keys to output array */
  function add_keys( &$output, $input = array(), $parent = '' ) {
    foreach ($input as $key => $value) {
      $token = (strlen($parent) > 0 ? $parent . '-' : '') . $key;
      if ( gettype($value) === 'object' ) add_keys( $output, $value, $token );
      else $output[$token] = $value;
    }
  }

  $tokens = array();
  $response = wp_remote_get( $this->freedam_api_address . '/web-notices.def' );
  if ( !is_wp_error($response) ) {
    $definition = json_decode($response['body']);
    $example = $definition->exits->success->outputExample[0];

    add_keys($tokens, $example);
  } else {
    $errors = json_encode($response->errors);
    echo "Error occured while retrieving list of potential tokens from '/web-notices.def'";
    echo '<br>';
    echo $errors;
  }

if ( count($tokens) > 0 ):

?>

<style>
  .freedam-web-notices-template-examples {
    border-collapse: collapse;
  }
  .freedam-web-notices-template-examples td:first-child,
  .freedam-web-notices-template-examples th:first-child {
    white-space: nowrap; border-right: 1px solid black;
  }
  .freedam-web-notices-template-examples tr:not(:last-child) {
    border-bottom: 1px solid black;
  }
</style>

<h2>Token Examples</h2>
<p>These are tokens that can be used in the notice template that will be converted to matching data from the web-notice</p>
<table class="freedam-web-notices-template-examples">
  <thead>
    <tr>
      <th>Token</th>
      <th>Example</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($tokens as $token => $value): ?>
    <tr>
      <td><?php echo esc_html($token) ?></td>
      <td><?php echo esc_html($value) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<?php endif; ?>
