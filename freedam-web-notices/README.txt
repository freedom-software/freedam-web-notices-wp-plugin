=== FreeDAM Web Notices ===
Contributors: freedomsoftware, aidanchey
Donate link: https://freedomsoftware.co.nz
Tags: FreeDAM, funeral, notice, web-notice, freedomsoftware, freedom-software
Requires at least: 5.4.2
Requires PHP: 7.2
Tested up to: 6.0.0
Stable tag: 1.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

For usage by Freedom Software clients. Retrieves your web notices from your FreeDAM database for displaying on your website.

== Description ==
Retrieves your web/funeral notices from your FreeDAM database for displaying on your website. Create notices in the FreeDAM software to be "published" to the web, which are picked up by our service and displayed on your WordPress site by this plugin.

Enter your API Key linked to your specific database to authorize yourself against our service (API). Customize the layout & style of the notices. Pick how many web notices to display on a page and set restrictions on which notices display based on the funeral date.

Notices are paginated, giving the ability to move through the notices via next & previous buttons.

= Note =
Some level of HTML and CSS knowledge is expected to customize the layout and appearance of the notices.

== Third-Party Service Usage ==
This plugin makes requests to a third-party service (Freedom Software API). It uses the service to retrieve your FreeDAM web-notices from your FreeDAM database.

*This service (and by extension, the plugin) is intended for use by **Freedom Software clients only** and would not be of use to anyone that doesn't have a FreeDAM database.*

The third-party service's address is `api.freedam.co.nz` and will be making use of the `web-notices` endpoint via "https://api.freedam.co.nz/web-notices".

For information on the endpoint itself visit: https://api.freedam.co.nz/web-notices.def

For information on our terms of service / terms of use: https://api.freedam.co.nz/terms-of-service

For enquires about usage of the third-party service or obtaining an API Key to gain access to the service, please contact Freedom Software via:
* Email: support@freedomsoftware.co.nz
* Website: https://freedomsoftware.co.nz/contact-us/

== Installation ==
1. Upload `freedam-web-notices` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the **Plugins** menu in the WordPress **Admin** section.
1. Add your supplied FreeDAM database **API Key** to the plugin's settings page, located under the **Settings** sub-menu in the Admin section.
1. Place `[freedam-web-notices]` in a **Shortcode block** on a page. Should be possible to add directly to a theme by adding `<?php echo do_shortcode("[freedam-web-notices]"); ?>`.

== Frequently Asked Questions ==

= How do I change the appearance of notices (add style) =
Add the custom CSS to your theme's "Additional CSS".

1. From the "Admin" section, navigate to Appearance > Customize.
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

= How can I change the format of dates & times =
The plugin's settings page has a section called "Date Formats / Rules" which allows picking from a selection of formats.

1. From the "Admin" section, navigate to the "Settings" sub-menu "FreeDAM Web Notices".
1. Select the "Date Formats / Rules" navigation tab along the top of the page.
1. Adjust the listed settings to your liking.
1. Press "Save Changes".

= How can I change the layout of the notices =
The plugin's settings page has a tab dedicated to adjusting the template HTML for the notices.

1. From the "Admin" section, navigate to the "Settings" sub-menu "FreeDAM Web Notices".
1. Select the "Notice Template" navigation tab along the top of the page.
1. Adjust the HTML in the template textarea to your liking.
1. Press "Save Changes".

= What tokens can I use in my notice template =
The notice template consists of a collection of HTML and tokens. The plugin uses this template as the skeleton for constructing the individual notices by replacing the tokens with data returned by the API service.

Tokens consist of opening characters (`{{`), a keyword (`deceased-name-title`) and closing characters (`}}`). Token keywords are in a format that is easy for the script to find your intended data. For example, `deceased-name-title` indicates to use the deceased's title from the name node.

To find what keywords are available for use in the template, reference the example of the "success" exit in the [endpoint's definition](https://api.freedam.co.nz/web-notices.def). Additionally, there is a table of accepted tokens below the notice template editing form.

= How can I hide sections of a notice if there is no data =
We have built in a system to the notice generator to help with this issue. It identifies class-names in the template, matching tokens in the template, adding styles the elements to hide them if the associated token has nothing to display.

1. From the "Admin" section, navigate to the "Settings" sub-menu "FreeDAM Web Notices".
1. Select the "Notice Template" navigation tab along the top of the page.
1. Adjust the HTML in the template textarea.
  2. Identify the element you wish to hide when the data is empty.
  2. Identify the token in the template that the hiding action is dependent on.
  2. Add a class name to the identified element that matches the identified token (`class="deceased-name-maiden"`).
1. Press "Save Changes".

== Changelog ==

= 1.3.0 =
* Test on WordPress 6.0.0
* Adds ability to display the thumbnail of notice's assigned image
* Adds stream url & note for each notice
* Fixes issue with pagination so it displays the pagination under more circumstances
* Fixes issue with pagination that did not disable 'next' button incorrectly
* Adds messages that displays when no results and on a page greater than 1
* Adds messages that displays when no results and is searching (filtering by terms) for a notice
* Notice searches now ignore time limits (except for publish_from) to make it more useful
* Improves the default notice template so the venue displays better

= 1.2.1 =
* Test on WordPress 5.6.0

= 1.2.0 =
* Adds search capability to notice navigation
* Adds notice ordering date type option, allows ordering by funeral or death date
* Improves default notice styles, enabling use of carriage returns in tribute text
* Bug fixes

= 1.1.1 =
* Test on WordPress 5.5.1
* Improve sanitation of data from user & database

= 1.1.0 =
* Readme improvements
* Adds ability to customize funeral date/time format
* Adjusted the layout of settings, separating them into tabs
* Adds ability to hide template section based on token data availability
* Adds an "Instructions" tab to settings page

= 1.0.0 =
* Initial stable version
