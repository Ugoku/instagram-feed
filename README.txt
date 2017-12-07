=== Instagram Feed ===
Contributors: smashballoon, craig-at-smash-balloon
Tags: Instagram, Instagram feed, Instagram photos, Instagram widget, Instagram gallery
Requires at least: 4.8
Requires PHP: 7.0.0
Tested up to: 4.9
Stable tag: 1.6.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display beautifully clean, customizable, and responsive feeds from multiple Instagram accounts

== Description ==

Display Instagram photos from any non-private Instagram accounts, either in the same single feed or in multiple different ones.

= Features =
* **Compatible with the June 1st Instagram API changes**
* Super **simple to set up**
* Display photos from **multiple Instagram accounts** in the same feed or in separate feeds
* Completely **responsive** and mobile ready - layout looks great on any screen size and in any container width
* **Completely customizable** - Customize the width, height, number of photos, number of columns, image size, background color, image spacing and more!
* Display **multiple Instagram feeds** on the same page or on different pages throughout your site
* Use the built-in **shortcode options** to completely customize each of your Instagram feeds
* Display thumbnail, medium or **full-size photos** from your Instagram feed
* **Infinitely load more** of your Instagram photos with the 'Load More' button
* Includes a **Follow on Instagram button** at the bottom of your feed
* Display a **beautiful header** at the top of your feed
* Display your Instagram photos chronologically or in random order
* Add your own Custom CSS and JavaScript for even deeper customizations

= Benefits =
* **Increase Social Engagement** - Increase engagement between you and your Instagram followers. Increase your number of followers by displaying your Instagram content directly on your site.
* **Save Time** - Don't have time to update your photos on your site? Save time and increase efficiency by only posting your photos to Instagram and automatically displaying them on your website
* **Display Your Content Your Way** - Customize your Instagram feeds to look exactly the way you want, so that they blend seemlessly into your site or pop out at your visitors!
* **Keep Your Site Looking Fresh** - Automatically push your new Instagram content straight to your site to keep it looking fresh and keeping your audience engaged.
* **Super simple to set up** - Once installed, you can be displaying your Instagram photos within 30 seconds! No confusing steps or Instagram Developer account needed.

= Featured Reviews =
"**Simple and concise** - Excellent plugin. Simple and non-bloated. I had a couple small issues with the plugin when I first started using it, but a quick comment on the support forums got a new version pushed out the next day with the fix. Awesome support!" - [Josh Jones](https://wordpress.org/support/topic/simple-and-concise-3 'Simple and concise Instagram plugin')

"**Great plugin, greater support!** - I've definitely noticed an increase in followers on Instagram since I added this plugin to my sidebar. Thanks for the help in making some adjustments...looks and works great!" - [BNOTP](https://wordpress.org/support/topic/thanks-for-a-great-plugin-6 'Great plugin, greater Support!')

= Feedback or Support =
We're dedicated to providing the most customizable, robust and well supported Instagram feed plugin in the world, so if you have an issue or have any feedback on how to improve the plugin then please open a ticket in the [Support forum](http://wordpress.org/support/plugin/instagram-feed 'Instagram Feed Support Forum').

For a pop-up photo **lightbox**, to display posts by **hashtag**, show photo **captions**, **video** support + more, check out the [Pro version](http://smashballoon.com/instagram-feed/ 'Instagram Feed Pro').

== Installation ==

1. Install the Instagram Feed plugin either via the WordPress plugin directory, or by uploading the files to your web server (in the `/wp-content/plugins/` directory).
2. Activate the Instagram Feed plugin through the 'Plugins' menu in WordPress.
3. Navigate to the 'Instagram Feed' settings page to obtain your Instagram Access Token and Instagram User ID and configure your settings.
4. Use the shortcode `[instagram-feed]` in your page, post or widget to display your Instagram photos.
5. You can display multiple Instagram feeds by using shortcode options, for example: `[instagram-feed num=6]`

For simple step-by-step directions on how to set up the Instagram Feed plugin please refer to our [setup guide](http://smashballoon.com/instagram-feed/free/ 'Instagram Feed setup guide').

= Display your Feed =

**Single Instagram Feed**

Copy and paste the following shortcode directly into the page, post or widget where you'd like the Instagram feed to show up: `[instagram-feed]`

**Multiple Instagram Feeds**

If you'd like to display multiple Instagram feeds then you can set different settings directly in the shortcode like so: `[instagram-feed num=9]`

You can display as many different Instagram feeds as you like, on either the same page or on different pages, by just using the shortcode options below. For example:
`[instagram-feed]`
`[instagram-feed id="ANOTHER_USER_ID"]`
`[instagram-feed id="ANOTHER_USER_ID, YET_ANOTHER_USER_ID" num=4]`

See the table below for a full list of available shortcode options:

= Shortcode Options =
* **General Options**
* **id** - An Instagram User ID - Example: `[instagram-feed id=AN_INSTAGRAM_USER_ID]`
* **class** - Add a CSS class to the Instagram feed container - Example: `[instagram-feed class=feedOne]`
*
* **Photo Options**
* **sortby** - Sort the Instagram posts by Newest to Oldest (none) or Random (random) - Example: `[instagram-feed sortby=random]`
* **num** - The number of Instagram posts to display initially. Maximum is 33 - Example: `[instagram-feed num=10]`

*
* **Header Options**
* **showheader** - Whether to show the Instagram feed Header. 'true' or 'false' - Example: `[instagram-feed showheader=false]`
*

For more shortcode options, check out the [Pro version](http://smashballoon.com/instagram-feed/ 'Instagram Feed Pro').

= Setting up the Free Instagram Feed WordPress Plugin =

1) Once you've installed the Instagram Feed plugin click on the Instagram Feed item in your WordPress menu

2) Click on the large blue Instagram button to log into your Instagram account and get your Instagram Access Token and Instagram User ID

3) Copy and paste the Instagram Access Token and Instagram User ID into the relevant Instagram Access Token and Instagram User ID fields. If you're having trouble retrieving your Instagram information from Instagram then try using the Instagram button on [this page](https://smashballoon.com/instagram-feed/token/) instead.

You can also display photos from other Instagram accounts by using [this tool](http://www.otzberg.net/iguserid/) to find their Instagram User ID. 

4) Navigate to the Instagram Feed customize page to customize your Instagram feed. 

5) Once you've customized your Instagram feed, click on the Display Your Feed tab to grab the [instagram-feed] shortcode.

6) Copy the Instagram Feed shortcode and paste it into any page, post or widget where you want the Instagram feed to appear.

7) You can paste the Instagram Feed shortcode directly into your page editor. 

8) You can use the default WordPress 'Text' widget to display your Instagram Feed in a sidebar or other widget area.

== Frequently Asked Questions ==

= Can I display multiple Instagram feeds on my site or on the same page? =

Yep. You can display multiple Instagram feeds by using our built-in shortcode options, for example: `[instagram-feed id="12986477"]`.

= Can I display photos from more than one Instagram account in one single feed? =

Yep. You can just separate the IDs by commas, either in the User ID(s) field on the plugin's Settings page, or directly in the shortcode like so: `[instagram-feed id="12986477,13460080"]`.

= How do I find my Instagram Access Token and Instagram User ID =

We've made it super easy. Simply click on the big blue button on the Instagram Feed Settings page and log into your Instagram account. The plugin will then retrieve and display both your Access Token and User ID from Instagram.

You can also display photos from other peoples Instagram accounts. To find their Instagram User ID you can use [this tool](http://www.otzberg.net/iguserid/).

= My Instagram feed isn't displaying. Why not!? =

There are a few common reasons for this:

* **Your Access Token may not be valid.** Try clicking on the blue Instagram login button on the plugin's Settings page again and copy and paste the Instagram token it gives you into the plugin's Access Token field.
* **Your Instagram account may be set to private.** Your Instagram account may be set to private. Instagram doesn't allow photos from private Instagram accounts to be displayed publicly.
* **Your User ID may not be valid**. Be sure you're not using your Instagram username instead of your User ID. You can find your Instagram User ID by using [this tool](http://www.otzberg.net/iguserid/).
* **The plugin's JavaScript file isn't being included in your page.** This is most likely because your WordPress theme is missing the WordPress [wp_footer](http://codex.wordpress.org/Function_Reference/wp_footer) function which is required for plugins to be able to add their JavaScript files to your page. You can fix this by opening your theme's **footer.php** file and adding the following directly before the closing </body> tag: `<?php wp_footer(); ?>`
* **Your website may contain a JavaScript error which is preventing JavaScript from running.** The plugin uses JavaScript to load the Instagram photos into your page and so needs JavaScript to be running in order to work. You would need to remove any existing JavaScript errors on your website for the plugin to be able to load in your feed.

If you're still having an issue displaying your feed then please open a ticket in the [Support forum](http://wordpress.org/support/plugin/instagram-feed 'Instagram Feed Support Forum') with a link to the page where you're trying to display the Instagram feed and, if possible, a link to your Instagram account.

= Are there any security issues with using an Access Token on my site? =

Nope. The Access Token used in the plugin is a "read only" token, which means that it could never be used maliciously to manipulate your Instagram account.

= Can I view the full-size photos or play Instagram videos directly on my website?  =

This is a feature of the [Pro version](http://smashballoon.com/instagram-feed/ 'Instagram Feed Pro') of the plugin, which allows you to view the photos in a pop-up lightbox, support videos, display captions, display photos by hashtag + more!

= How do I embed my Instagram Feed directly into a WordPress page template? =

You can embed your Instagram feed directly into a template file by using the WordPress [do_shortcode](http://codex.wordpress.org/Function_Reference/do_shortcode) function: `<?php echo do_shortcode('[instagram-feed]'); ?>`.

= My Feed Stopped Working – All I see is a Loading Symbol =

If your Instagram photos aren't loading and all your see is a loading symbol then there are a few common reasons:

1) There's an issue with the Instagram Access Token that you are using

You can obtain a new Instagram Access Token on the Instagram Feed Settings page by clicking the blue Instagram login button and then copy and pasting it into the plugin's 'Access Token' field.

Occasionally the blue Instagram login button does not produce a working access token. You can try [this link](https://smashballoon.com/instagram-feed/token/) as well.

2) Your Instagram User ID is incorrect or is from a private Instagram account

Please double check the Instagram User ID that you are using. Please note that your Instagram User ID is different from your Instagram username. To find your Instagram User ID simply enter your Instagram username into [this tool](http://www.otzberg.net/iguserid/).

If your Instagram User ID doesn't show any Instagram photos then it may be that your Instagram account is private and that the Instagram photos aren't able to be displayed.

3) The plugin's JavaScript file isn't being included in your page

This is most likely because your WordPress theme is missing the WordPress wp_footer function which is required for plugins to be able to add their JavaScript files to your page. You can fix this by opening your theme's footer.php file and adding the following directly before the closing </body> tag:

<?php wp_footer(); ?>

4) There's a JavaScript error on your site which is preventing the plugin's JavaScript file from running

You find find out whether this is the case by right clicking on your page, selecting 'Inspect Element', and then clicking on the 'Console' tab, or by selecting the 'JavaScript Console' option from your browser's Developer Tools.

If a JavaScript error is occurring on your site then you'll see it listed in red along with the JavaScript file which is causing it.

5) The feed you are trying to display has no Instagram posts

If you are trying to display an Instagram feed that has no posts made to it, a loading symbol may be all that shows for the Instagram feed or nothing at all. Once you add an Instagram post the Instagram feed should display normally

6) The shortcode you are using is incorrect

You may have an error in the Instagram Feed shortcode you are using or are missing a necessary argument.

= What are the available shortcode options that I can use to customize my Instagram feed? =

The below options are available on the Instagram Feed Settings page but can also be used directly in the `[instagram-feed]` shortcode to customize individual Instagram feeds on a feed-by-feed basis.

* **General Options**
* **id** - An Instagram User ID - Example: `[instagram-feed id=AN_INSTAGRAM_USER_ID]`
* **background** - The background color of the Instagram feed. Any hex color code - Example: `[instagram-feed background=#ffff00]`
* **class** - Add a CSS class to the Instagram feed container - Example: `[instagram-feed class=feedOne]`
*
* **Photo Options**
* **sortby** - Sort the Instagram posts by Newest to Oldest (none) or Random (random) - Example: `[instagram-feed sortby=random]`
* **num** - The number of Instagram posts to display initially. Maximum is 33 - Example: `[instagram-feed num=10]`

* **Header Options**
* **showheader** - Whether to show the Instagram feed Header. 'true' or 'false' - Example: `[instagram-feed showheader=false]`
*
* **'Load More' Button Options**
* **showbutton** - Whether to show the 'Load More' button. 'true' or 'false' - Example: `[instagram-feed showbutton='false']`
*

For more shortcode options, check out the [Pro version](http://smashballoon.com/instagram-feed/ 'Instagram Feed Pro').

For more FAQs related to the Instagram Feed plugin please visit the [FAQ section](https://smashballoon.com/instagram-feed/support/faq/ 'Instagram Feed plugin FAQs') on our website.

== Screenshots ==

1. Default plugin styling
2. Your Instagram Feed is completely customizable
3. Display multiple Instagram feeds from any non-private Instagram account
4. Your Instagram feeds are completely responsive and look great on any device
5. Display your Instagram photos in multiple columns, with or without a scrollbar
6. Just copy and paste the shortcode into any page, post or widget on your site
7. The Instagram Feed plugin Settings pages

== Other Notes ==

Add beautifully clean, customizable, and responsive Instagram feeds to your website. Super simple to set up and tons of customization options to seamlessly match the look and feel of your site.

= Why do I need this? =

**Increase Social Engagement**
Increase engagement between you and your Instagram followers. Increase your number of Instagram followers by displaying your Instagram content directly on your site.

**Save Time**
Don't have time to update your photos on your site? Save time and increase efficiency by only posting your photos to Instagram and automatically displaying them on your website.

**Display Your Content Your Way**
Customize your Instagram feeds to look exactly the way you want, so that they blend seemlessly into your site or pop out at your visitors!

**Keep Your Site Looking Fresh**
Automatically push your new Instagram content straight to your site to keep it looking fresh and keeping your audience engaged.

**No Coding Required**
Choose from tons of built-in Instagram Feed customization options to create a truly unique feed of your Instagram content.

**Super simple to set up**
Once installed, you can be displaying your Instagram photos within 30 seconds! No confusing steps or Instagram Developer account needed.

**Mind-blowing Customer Support**
We understand that sometimes you need help, have issues or just have questions. We love our users and strive to provide the best support experience in the business. We're experts in the Instagram API and can provide unparalleled service and expertise. If you need support then just let us know and we'll get back to you right away.

= What can it do? =

* Display Instagram photos from any non-private Instagram account.
* Completely responsive and mobile ready – your Instagram feed layout looks great on any screen size and in any container width
* Display multiple Instagram feeds on the same page or on different pages throughout your site
* Display posts from multiple Instagram User IDs
* Plus more features added all the time!

= Completely Customizable =

* By default the Instagram feed will adopt the style of your website, but can be completely customized to look however you like!
* Set the number of Instagram photos you want to display
* Choose to show or hide the header of the Instagram feed
* Display Instagram photos in chronological or random order

== Changelog ==

= 1.6.0 =
Forked from smashballoon's version

* Removed: a lot of stuff we don't need
* Removed: a bunch of settings that can be handled better in CSS
* Fix: image alt tag
* Added: image caption
