<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/freedom-software
 * @since      1.0.0
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin/partials
 */

  $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'settings';
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
  <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

  <h3 class="nav-tab-wrapper">
    <a
      href="?page=<?php echo $this->settings_page_name; ?>&tab=settings"
      class="nav-tab <?php echo $active_tab === 'settings' ? 'nav-tab-active' : '' ?>"
    >Settings</a>
    <a
      href="?page=<?php echo $this->settings_page_name; ?>&tab=formats"
      class="nav-tab <?php echo $active_tab === 'formats' ? 'nav-tab-active' : '' ?>"
    >Date Formats / Rules</a>
    <a
      href="?page=<?php echo $this->settings_page_name; ?>&tab=template"
      class="nav-tab <?php echo $active_tab === 'template' ? 'nav-tab-active' : '' ?>"
    >Notice Template</a>
    <a
      href="?page=<?php echo $this->settings_page_name; ?>&tab=instructions"
      class="nav-tab <?php echo $active_tab === 'instructions' ? 'nav-tab-active' : '' ?>"
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
          include_once( 'freedam-web-notices-admin-template-tokens.php' );
          submit_button();
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