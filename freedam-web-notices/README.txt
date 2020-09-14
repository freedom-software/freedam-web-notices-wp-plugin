=== Plugin Name ===
Contributors: freedomsoftware, aidanchey
Donate link: https://freedomsoftware.co.nz
Tags: FreeDAM, funeral, notices, web-notices, freedomsoftware, freedom-software
Requires at least: 3.0.1
Tested up to: 5.4.2
Stable tag: 1.0.10
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

For usage by Freedom Software clients. Retrieves your web notices from your FreeDAM database for displaying on your website.

== Description ==

Retrieves your web notices from your FreeDAM database for displaying on your website.

Enter your API Key linked to your specific database to authorize yourself against the freedam-api. Customize the layout & style of individual web notices.

Pick how many web notices to display on a page and create restrictions on what displays based on the funeral date.

== Third-Party Service Usage ==

This plugin makes requests to a third-party service (Freedom Software API). It use a couple of the endpoints made available to the public by the service.

*This service (and by extension, the plugin) is intended for use by **Freedom Software clients only** and would not be of use to anyone that doesn't have a FreeDAM database.*

The third-party service's address is `api.freedam.co.nz` and will be making use of the `web-notices` endpoint via "https://api.freedam.co.nz/web-notices".

For information on the endpoint itself visit: https://api.freedam.co.nz/web-notices.def

Link to our terms of service: https://api.freedam.co.nz/terms-of-service

For enquires about usage of the third-party service or obtaining an API Key to gain access to the service, please contact Freedom Software via:
 * Email: support@freedomsoftware.co.nz
 * Website: https://freedomsoftware.co.nz/contact-us/

== Installation ==

1. Upload `freedam-web-notices` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the **Plugins** menu in the WordPress **Admin** section.
1. Add your supplied FreeDAM database **API Key** to the plugin's settings page, located under the **Settings** sub-menu in the Admin section.
1. Place `[freedam-web-notices]` in your a **Shortcode block** on a page. Should be possible to add directly to a theme by adding `<?php echo do_shortcode("[freedam-web-notices]"); ?>`.

== Frequently Asked Questions ==

= How do I customize the plugin settings =

1. From the Admin section, navigate to the "settings" sub-menu "FreeDAM Web Notices".
1. Adjust the listed settings to your liking.
1. Press "Save Changes".

= How do I style the web-notices =

Add the custom CSS to your theme's "Additional CSS".

1. From the Admin section, navigate to Appearance > Customize.
1. Navigate to a page where you have added a Shortcode block for the plugin. (helps with previewing the style changes).
1. Select "Additional CSS" on the left-hand panel.
1. Write your styles. The main elements that can be targeted are:
  * `freedam-web-notices-container`: Main container for plugin output.
  * `ul.freedam-web-notices`: Container for the list of web-notices.
  * `li.freedam-web-notice`: Container for individual web-notices.

Any other elements are from your own custom template, specified in the plugin's settings.

= No web notices are displaying where I added the Shortcode block =

 * Make sure your Freedom Software supplied **API Key** has been added to the plugin's settings. This API Key is how our API can authorize your access and identify which of our clients to pull the data from.
 * Make sure your **FreeDAM Database is online** and available to the internet. Good way to check this is if you can access your data from the FreeDAM Online Interface (web app).
 * Make sure there are **published** web-notices in your database. Cases are not automatically available to the web-notices system. A web-notice needs to be created for a case; this is so you can customize the information that will show to the public. Finally, the web-notice needs to be "published" by enabling the checkbox on the same page you made the web-notice.

= What tokens can I use in my web-notice template =

The web-notice template consists of a collection of HTML and tokens.

The plugin uses this template as the skeleton for constructing the individual web-notices by replacing the tokens with data returned by the API service.

Tokens consist of opening characters (`{{`), a keyword (`deceased-name-title`) and closing characters (`}}`).

Token keywords are in a format that is easy for the script to find your intended data. For example, `deceased-name-title` indicates to use the deceased's title in the name node.

To find what keywords are available for use in the template, reference the example of the "success" exit in the [endpoint's definition](https://api.freedam.co.nz/web-notices.def)

== Changelog ==

= 1.1 =
* Readme improvements
* Adds ability to customize funeral date/time format
* Adjusted the layout of settings, separating them into sections

= 1.0 =
* Initial stable version
