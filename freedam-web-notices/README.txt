=== Plugin Name ===
Contributors: aidanchey, freedomsoftware
Donate link: https://freedomsoftware.co.nz
Tags: FreeDAM, funeral, notices
Requires at least: 3.0.1
Tested up to: 5.4.2
Stable tag: 1.0.10
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Retrieves your web notices from your FreeDAM database for displaying on your website.

== Description ==

Retrieves your web notices from your FreeDAM database for displaying on your website. Enter your API Key linked to your specific database to authorize yourself against the freedam-api (https://api.freedam.co.nz). Customize the layout & style of individual web notices. Pick how many web notices to display on a page and create restrictions on what displays based on the funeral date.

== Installation ==

1. Upload `freedam-web-notices` folder to the `/wp-content/plugins/` directory
2. **Activate** the plugin through the 'Plugins' menu in WordPress Admin section
3. Add your supplied FreeDAM database **ApiKey** to the plugin's settings page, located under the **Settings** sub-menu in the Admin section
4. Place `[freedam-web-notices]` in your a **Shortcode block** on a page
  Or in a template `<?php echo do_shortcode("[freedam-web-notices]"); ?>`

== Frequently Asked Questions ==

= How do I customize the plugin settings =

1. From the Admin section, navigate to the "settings" sub-menu "FreeDAM Web Notices"
1. Adjust the listed settings to your liking
1. Press "Save Changes"

= How do I style the web-notices =

Add the custom CSS to your theme's "Additional CSS".
1. From the Admin section, navigate to Appearance > Customize
1. Navigate to a page where you have added a Shortcode block for the plugin. (helps with previewing the style changes)
1. Select "Additional CSS" on the left-hand panel
1. Write your styles. The main elements that can be targeted are:
  * `freedam-web-notices-container`: Main container for plugin output
  * `ul.freedam-web-notices`: Container for the list of web-notices
  * `li.freedam-web-notice`: Container for individual web-notices
Any other elements are from your own custom template, specified in the plugin's settings.

= No web notices are displaying where I added the Shortcode block =

Make sure your Freedom Software supplied ApiKey has been added to the plugin's settings. This ApiKey is how our API can authorize your access and identify which of our clients to pull the data from.

Make sure your FreeDAM Database is online and available to the internet. Good way to check this is if you can access your data from the FreeDAM Online Interface (web app).

Make sure there are "published" web-notices in your database. Cases are not automatically available to the web-notices system. A web-notice needs to be created for a case, this is so you can customize the information that will show to the public. Finally, the web-notice needs to be "published" by enabling the checkbox on the same page you made the web-notice.

== Changelog ==

= 1.0 =
* Intial stable version
