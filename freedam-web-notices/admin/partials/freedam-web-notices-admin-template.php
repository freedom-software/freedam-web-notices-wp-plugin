<?php

/**
 * Provide a field for entering a notice template
 *
 * This file is used to markup the admin-facing field for the notice template
 *
 * @link       https://github.com/freedom-software
 * @since      1.1.1
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin/partials
 */

defined( 'ABSPATH' ) || exit;
  $option_name = $args['label_for'];
  $value = get_option( $option_name );

  // Legacy values were stored entity-encoded via esc_html(). Decode for display
  // so the user sees real HTML in the textarea; esc_textarea() then re-encodes
  // safely for embedding inside <textarea>.
  if ( is_string( $value ) && strlen( $value ) > 0 ) {
    $display_value = html_entity_decode( $value );
  } else {
    $display_value = $this->defaults['template'];
  }
?>

<textarea
  name="<?php echo esc_attr( $option_name ); ?>"
  id="<?php echo esc_attr( $option_name ); ?>"
  class="large-text"
  aria-describedby="template-description"
  rows="6"
  wrap="off"
  spellcheck="false"
><?php echo esc_textarea( $display_value ); ?></textarea>
<p
  class="description"
  id="template-description"
><?php echo esc_html( $args['title'] ); ?></p>
