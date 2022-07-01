=== Reviews Block for Google ===
Contributors: impressorg, dlocc, webdevmattcrom, shawnhooper, jason_the_adams, alaca
Donate link: https://wpbusinessreviews.com/
Tags: google, reviews, google reviews, google places, google business
Requires at least: 5.0
Tested up to: 6.1
Stable tag: 2.0.1
License: GPL2

Easily display Google business reviews on your WordPress website with a simple and intuitive block.

== Description ==

This free Google Reviews block includes a variety of options to customize how you display your Google Business information. 

Great for restaurants, retail stores, franchisees, real estate firms, hotels and hospitality, and nearly any business with Google Reviews.

=== ✨ Reviews Block for Google Features ===

The Reviews Block for Google allows you to display up to 5 business reviews. Setup is quick and easy. Simply install the plugin, insert your Google API key (instructions provided), and drag the block into the block editor to configure the options.

Then, choose from block options to display or hide your: 

* Businesses' hours
* Location
* Directions
* Contact Information
* Reviews (displays up to 5 reviews per location) 

You can also set the header image from the block settings. This way, if you choose to display your Google Business Header, you can display it with a nice branded background image. 

=== ⭐ WP Business Reviews ===
While the Reviews Block for Google can help you display your Google reviews on your website for free, we have an even better solution for you! 

*WP Business Reviews* is a **significant upgrade** to our free reviews blocks for Google and Yelp. With WP Business Reviews (WPBR), you can display reviews from many platforms, including:

* Google 
* Yelp 
* Facebook 
* WooCommerce 
* Zomato 
* Trustpilot 

When you use WP Business Reviews, you also gain more control over the style of your displayed reviews as well as which reviews you show. WPBR also allows you to mix and match your reviews across platforms. So, your Google and WooCommerce reviews can be displayed right alongside your Facebook and Yelp reviews. 

[Upgrade to WP Business Reviews](https://wpbusinessreviews.com "Upgrade to WP Business Reviews")

[youtube https://www.youtube.com/watch?v=3xNJX5cjdQ0]

=== 💡 Installation ===

**Automatic Install:**

1. Log into your WP-Admin
2. Navigate to 'Plugins' > 'Add New'
3. Search for "Reviews Block for Google" and install it from there.

**Manual Install:**

1. Upload the `google-places-reviews` folder and it's contents to the `/wp-content/plugins/` directory or install via the WP plugins panel in your WordPress admin dashboard
2. Activate the plugin through the 'Plugins' menu in WordPress
3. That's it! You should now be able to use the widget.

== Frequently Asked Questions ==

= Why Use this Plugin? =

This plugin allows you to display Google reviews on your WordPress website. Why is this important? Positive reviews on websites like Google help businesses gain additional exposure and further enhance their online credibility. As well, positive reviews add substantial credibility to any business and can lead to increased conversion rates and sales.

There is significant data that demonstrates consumers care about online reviews but very little information about what businesses are doing to respond to this phenomenon. How about displaying reviews on your WordPress website from Google?

= How do I obtain a Google Places API key? =

In order for the plugin to access your Google reviews, you must have a valid Google Places API key with the Google Places API and Google Maps API enabled. To get started, please review [how to create a Google Places API key](https://wpbusinessreviews.com/documentation/platforms/google/).

= Why does the block look funny in my theme? =

Most likely your theme has conflicting CSS that is interfering with the styles included with the plugin. If you're handy with CSS this can be an easy fix. If you are new to CSS then please submit a support request with the url of the website and we'll do our best to help you fix the issue.

= Are CSS style and theme compatibility issues supported? =

We do our best to support users of the free version of the plugin. [WP Business Reviews](https://wpbusinessreviews.com "Upgrade to WP Business Reviews") is where our customers receive priority support. If you are experiencing a style issue and need help, post in the WordPress.org support forum and we will do our best to get it set up nicely for your theme.

== Screenshots ==

1. This block is good for displaying information about and reviews for any location on Google.

2. Great for restaurants, bars, gyms, hotels, museums. Use the block for travel blogs, page content, location recommendations and more.

3. Make it yours with extensive customization options.

== Changelog ==

= 2.0.1: July 1st, 2022 =
* 🎨 Added CSS so the business review card has a white background and not transparent. This improves the look on non-white background websites. 
* 🛠 Optimized loading Google's autocomplete script so it doesn't load unnecessarily when not needed.
* 🛠 Fix "Google" logo image being overlapped on the API key insertion screen.

= 2.0.0: May 26th, 2022 =
* 🎉 Introducing the Reviews Block for Google WordPress plugin. This is a huge plugin revamp! In this new version we've added a new reviews block for the WordPress block editor. Don't worry, if you're still using the widget it will still work just fine.
* What else? The plugin settings have been cleaned up, further secured, and improved.
* Note: this plugin requires WordPress 5.0 and PHP 7.2 or higher.

= 1.5.2: October 28th, 2019 =
* Maintenance: Reviewed plugin compatibility with WordPress 5.3 for compatibility and updated the plugin tested up to version.

= 1.5.1: May 16th, 2019 =
* Maintenance: Reviewed plugin compatibility with WordPress 5.2 for compatibility and updated the plugin tested up to version.

= 1.5.0 =
* We have recently launched a new premium plugin for reviews called [WP Business Reviews](https://wpbusinessreviews.com/?plugin=google-places-reviews). You can use this new WordPress review plugin to display your best reviews from platforms like Google, Yelp, and Facebook right on your website.
* New: Implemented webpack and SASS for the styles.
* Tweak: Modified upsells for new WP Business Reviews plugin.

= 1.4.3 =
* New: Scripts and styles are now enqueued conditionally so that they are only loaded when the widget or shortcode is used.
* Fix: Reviewers with actual avatars appearing incorrectly as the default placeholder icon.
* Fix: Character Limit setting applied to all widgets on the page instead of individually.
* Tweak: Various JS fixes and code cleanup.
* Tweak: CSS compatibility with TwentySeventeen.

= 1.4.2 =
* Fix: Enqueue Maps JS due to new Google API Key requirement
* Tweak: Display warning message when an Google API error is detected to help prevent confusion

= 1.4.1 =
* Solidify Cache expiration setting for stability
* Store Place Avatar as Data URI to avoid additional API calls
* Wrap default image in url() correctly

= 1.4 =
* New: Improved widget UI when viewing under Appearances > Widget
* New: Updated Google logo through out plugin
* Tweak: CSS tweaks for better frontend theme compatibility
* Tweak: Gulp now used to compile POT file for translation usage
* Tweak: Text domain changed back to 'google-places-reviews' do that the plugin can be included for translation by WordPress.org polyglots
* Fix: z-index conflict with Custom Sidebars plugin by WPMUDev
* Fix: Error returned from google when API url contained malformed characters - now we sanitize the URL before passing it to Google's API; this will help businesses with unconventional characters in their name connect to Google's API
* Fix: Prevent conflict with other review bundle plugins' clear cache feature by prefixing ajax callback

= 1.3 =
* Added compatibility with Siteorigin's PageBuilder
* Added compatibility with WordPress Customizer
* Added compatibility with WPMUDEV's Custom Sidebar's plugin

= 1.2.1 =
* Fix: Broken avatar images displayed for some reviews where the user had not set an avatar

= 1.2 =
* New: Language .pot file for translators
* New: Added additional widget field tooltips
* New: Place "Type" filter to help users find their locations' Place ID on Google
* Update: Switched from Google Places API "Reference" to "Place ID"
* Update: Textdomain changed from 'google-places-reviews' to a much more manageable 'gpr'
* Update: Better tooltip output using new gpr_admin_tooltip function
* Update: Removed usage of GPR_DEBUG in favor of SCRIPT_DEBUG constant
* Fix: "Clear Cache" button under Advanced Options now actually clears the cache
* Fix: Several minor PHP warnings and notices
* Fix: UX improvement: "Hide Google Logo" label wasn't click-able to select checkbox, now it is
* Fix: Minor admin CSS touch ups for WP 4.2

= 1.1.3 =
* Modified text in activation banners so it's more informative & relevant to the free version

= 1.1.2 =
* Added activation banner
* Updated readme.txt
* Fully I18n (internationalization) ready

= 1.1.1 =
* Fix: "Disable Title Output" under the "Advanced Options" in the widget wasn't working, it is now; thanks "game writer" for notifying us of this bug.
* Fix: Avatar image overlapping into the content because of lack of max-width CSS property.

= 1.1.0 =
* New: Plugin thumbnail that now appears in WP repo search
* Improvement: If an error occurs the widget will output them and then ensure that the bad result is not cached so if the user fixes the error they won't have to manually clear the cache.
* Minor improvements the the widget's frontend appearance
* Fixed bad links to plugin docs
* Tested WP 4.0 functionality to approve bumping version compatibility

= 1.0.1 =
* Update: Removed schema.org tags from the free version of the plugin.
* Update: Fixed minor CSS issues with star counts collapsing below review avatar for some sidebars with tight widths; several padding reductions to support slimmer widths.

= 1.0 =
* Initial Free version release!
