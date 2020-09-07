=== Plugin Name ===
Contributors: aidanchey
Donate link: https://github.com/freedom-software
Tags: FreeDAM, funeral, notices
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Retrieves your web notices from your FreeDAM database for displaying on your website.

== Description ==

Retrieves your web notices from your FreeDAM database for displaying on your website. Enter your API Key linked to your specific database to authorize yourself agianst the freedam-api. Customize the layout & sytle of individual web notices. Pick how many web notices to display on a page.

A few notes about the sections above:

*   "Contributors" is a comma separated list of wp.org/wp-plugins.org usernames
*   "Tags" is a comma separated list of tags that apply to the plugin
*   "Requires at least" is the lowest version that the plugin will work on
*   "Tested up to" is the highest version that you've *successfully used to test the plugin*. Note that it might work on
higher versions... this is just the highest one you've verified.
*   Stable tag should indicate the Subversion "tag" of the latest stable version, or "trunk," if you use `/trunk/` for
stable.

    Note that the `readme.txt` of the stable tag is the one that is considered the defining one for the plugin, so
if the `/trunk/readme.txt` file says that the stable tag is `4.3`, then it is `/tags/4.3/readme.txt` that'll be used
for displaying information about the plugin.  In this situation, the only thing considered from the trunk `readme.txt`
is the stable tag pointer.  Thus, if you develop in trunk, you can update the trunk `readme.txt` to reflect changes in
your in-development version, without having that information incorrectly disclosed about the current stable version
that lacks those changes -- as long as the trunk's `readme.txt` points to the correct stable tag.

    If no stable tag is provided, it is assumed that trunk is stable, but you should specify "trunk" if that's where
you put the stable version, in order to eliminate any doubt.

== Installation ==

1. Upload `freedam-web-notices.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `[freedam-web-notices]` in your a Shortcode block on a page
  Or in a template `<?php echo do_shortcode("[freedam-web-notices]"); ?>`
4. Add the ApiKey to the plugin's settings

== Frequently Asked Questions ==

= How do I customize the plugin settings =

1. From the Admin section, navigate to the "settings" sub-menu "FreeDAM Web Notices"
2. Adjust the listed settings to your liking
3. Press "Save Changes"

= How do I style the web-notices =

Add the custom css to your theme's "Aditional CSS".
1. From the Admin section, navigate to Appearance > Customize
2. Navigate to a page with the plugin's shortcode
3. Select "Additional CSS"
4. Write your styles. The main elements that can be targeted are:
  - freedam-web-notices-container
  - ul.freedam-web-notices
  - li.freedam-web-notice
Any other elements are from your own custom template, specified in the plugin's settings

= No web notices are displaying where I added the Shortcode block =

Make sure your have an ApiKey added to the plugin's settings. This ApiKey will be sourced by Freedom Software as it's how our API is able to authorize your access and identify which of our clients to pull the data from.

Make sure your FreeDAM Datebase is online and avaliable to the internet. Good way to check this is if you can access your data from the FreeDAM Online Interface (web app).

Make sure there are "published" web-notices in your database. Cases are not automatcially available to the web-notices system. A web-notice needs to be created for a case, this is so you can customize the information that will showen to the public. Finally the web-notice needs to be "published" by enabling the checkbox on the sae page you made the web-notice.


== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0 =
* A change since the previous version.
* Another change.

= 0.5 =
* List versions from most recent at top to oldest at bottom.

== Upgrade Notice ==

= 1.0 =
Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.

= 0.5 =
This version fixes a security related bug.  Upgrade immediately.

== Arbitrary section ==

You may provide arbitrary sections, in the same format as the ones above.  This may be of use for extremely complicated
plugins where more information needs to be conveyed that doesn't fit into the categories of "description" or
"installation."  Arbitrary sections will be shown below the built-in sections outlined above.

== A brief Markdown Example ==

Ordered list:

1. Some feature
1. Another feature
1. Something else about the plugin

Unordered list:

* something
* something else
* third thing

Here's a link to [WordPress](http://wordpress.org/ "Your favorite software") and one to [Markdown's Syntax Documentation][markdown syntax].
Titles are optional, naturally.

[markdown syntax]: http://daringfireball.net/projects/markdown/syntax
            "Markdown is what the parser uses to process much of the readme file"

Markdown uses email style notation for blockquotes and I've been told:
> Asterisks for *emphasis*. Double it up  for **strong**.

`<?php code(); // goes in backticks ?>`