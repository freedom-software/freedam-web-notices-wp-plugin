<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/freedom-software
 * @since      1.2.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin/partials
 */

defined( 'ABSPATH' ) || exit;

  // Allow-list valid tab values; anything else falls back to 'settings'. The
  // $_GET value is read for navigation only — no nonce required for a GET that
  // doesn't change state — but we explicitly validate to remove any reflected
  // XSS surface, and unslash before sanitize per WP standards.
  $valid_tabs  = array( 'settings', 'formats', 'template', 'instructions' );
  $param_tab   = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : '';
  $active_tab  = in_array( $param_tab, $valid_tabs, true ) ? $param_tab : 'settings';

  $tab_url = function ( $tab ) {
    return esc_url( add_query_arg(
      array(
        'page' => $this->settings_page_name,
        'tab'  => $tab,
      ),
      admin_url( 'options-general.php' )
    ) );
  };
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
  <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

  <h3 class="nav-tab-wrapper">
    <a
      href="<?php echo $tab_url( 'settings' ); ?>"
      class="nav-tab <?php echo $active_tab === 'settings' ? 'nav-tab-active' : ''; ?>"
    >Settings</a>
    <a
      href="<?php echo $tab_url( 'formats' ); ?>"
      class="nav-tab <?php echo $active_tab === 'formats' ? 'nav-tab-active' : ''; ?>"
    >Date Formats / Rules</a>
    <a
      href="<?php echo $tab_url( 'template' ); ?>"
      class="nav-tab <?php echo $active_tab === 'template' ? 'nav-tab-active' : ''; ?>"
    >Notice Template</a>
    <a
      href="<?php echo $tab_url( 'instructions' ); ?>"
      class="nav-tab <?php echo $active_tab === 'instructions' ? 'nav-tab-active' : ''; ?>"
    >Instructions</a>
  </h3>

  <form action="options.php" method="post">
    <?php
      switch ($active_tab) {
        case 'instructions':
          do_settings_sections( $this->instructions_options_group );
          break;
        case 'template':
          settings_fields( $this->template_options_group );
          do_settings_sections( $this->template_options_group );
          submit_button();
          include_once( 'freedam-web-notices-admin-template-tokens.php' );
          break;
        case 'formats':
          settings_fields( $this->formats_options_group );
          do_settings_sections( $this->formats_options_group );
          submit_button();
          break;
        default:
          settings_fields( $this->settings_options_group );
          do_settings_sections( $this->settings_options_group );
          submit_button();
          break;
      }
    ?>
  </form>
</div>