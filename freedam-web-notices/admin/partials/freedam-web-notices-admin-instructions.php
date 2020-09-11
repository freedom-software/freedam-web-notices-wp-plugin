<?php

/**
 * Provide a set of instructions for using the admin settings
 *
 * This file is used to markup the admin-facing section for instructions
 *
 * @link       https://github.com/freedom-software
 * @since      1.1
 *
 * @package    Freedam_Web_Notices
 * @subpackage Freedam_Web_Notices/admin/partials
 */
?>

<h5>Activating the Plugin</h5>
<p>Activate the plugin. This will allow you to then configure the options.</p>
<img style="border: 1px solid hsl(210deg 14% 89%);" src="assets/activate.png" height="148" width="994"/>
<br><br>
<p>Once activated go to the setting - FreeDAM Web Notices Section</p>
<img style="border: 1px solid hsl(210deg 14% 89%);" src="assets/settings.png" height="351" width="159"/>
<hr>
<h5>Settings section</h5>
<p>Enter the API Key that you have been allocated from Freedom Software.</p>
<p>Enter the number of notices to display on a page.</p>
<p>Choose when to stop showing the notice for the past and future. This is by entering the number of days. <br>
e.g. enter 7 in the 'limit by days in the past' if you would like to stop showing any notices that have been marked as published in FreeDAM 1 week after the funeral.</p>
<p>Choose if you would like notices that do not have a funeral date and time to be displayed.</p>
<p>Choose the order that the notices will be displayed.</p>


<hr>
<h5>Ensure there are some notices to display</h5>
<p>In the FreeDAM program users will have to mark some funerals as 'Publish to Website'. This is done in the 'Our website' section of FreeDAM.
    <br>Freedom Software are able to assist with this by contacting and instructiong the client, if required.</p>
<img style="border: 1px solid hsl(210deg 14% 89%);" src="assets/freedam-our-website2.png" height="319" width="500"/>
<br><br>
<p>The fields that are on this FreeDAM screen will self populate from the FreeDAM data. This allows the users to adjust the data that
will display on their website without changing their original data.</p>
<hr>
<h5>Adding the plugin to a page</h5>
<ul>
    <li>Create a page to display the web</li>
    <li>Add a block to the page to contain the web notices(1)</li>
    <li>Search for 'Shortcode' in the add block dialog(2)</li>
    <li>Select the 'Shortcode' block(3)</li>
</ul>
<img style="border: 1px solid hsl(210deg 14% 89%);" src="assets/addtopage.png" height="242" width="500"/>
<br><br>
<p>Inside the shortcode block enter [freedam-web-notices] and update the page.</p>
<img style="border: 1px solid hsl(210deg 14% 89%);" src="assets/shortcodeblock.png" height="182" width="500"/>
<br><br>


<hr>
<h5>Date Format / Rules</h5>
<p>Choose the date and time formats that are required for the funeral, birth and death dates.</p>
<h5>Hiding data</h5>
<p>Not all fields that you use in the template may always be populated. The maiden name is an example of this.</p>
<p>You are able to 'hide' elements in the template by giving that element a class name that is the same as the data name.
    <br>
If you have an element that is a label 'Maiden Name:' that would normally display the maiden name data next to it,
    if the deceased is a male then the data {{deceased-name-maiden}}} will come back empty. <br>
    In this case just attach the 'class="deceased-name-maiden"' to the label element and the plugin will hide the label element.
</p>

<hr>
<h5>Notice Template</h5>
<p>This is where you are able to construct the layout of each notice.</p>
<p>Insert a data name inside {{double curly brackets}} to display the FreeDAM date required.</p>
<p><strong>A list of the available fields are listed here in the documentation.</strong></p>
<p>Standard html tags (div p h1 etc) are able to be used.</p>
<p>Inline styles are also able to be used here - however see the 'Adjusting the CSS' section for the preferred method.</p>


<hr>
<h5>Adjusting the CSS</h5>
<p>Once you have web notices displaying on a page, go to that page and choose 'Customise' from the top wordpress menu.</p>
<img style="border: 1px solid hsl(210deg 14% 89%);" src="assets/customise.png" height="185" width="638"/>
<br><br>
<p>Choose 'Additional CSS from the menu.</p>
<img style="border: 1px solid hsl(210deg 14% 89%);" src="assets/additionalcss.png" height="187" width="296"/>
<br><br>
<p>Target the elements you wish and create the appropriate css.</p>
<img style="border: 1px solid hsl(210deg 14% 89%);" src="assets/additionalcss2.png" height="311" width="290"/>
<br><br>
<p>Click 'Publish' once you are done.</p>
<p>Remember you can create a class in the notice template html and then use that class for targeting the required CSS.</p>
<hr>