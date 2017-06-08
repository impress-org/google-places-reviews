=== Google Places Reviews ===
Contributors: wordimpress, dlocc, webdevmattcrom, shawnhooper
Donate link: https://wordimpress.com/plugins/google-places-reviews-pro/
Tags: google, reviews, google reviews, google places, google places reviews, google review widget, google business reviews, google review, review, google place review
Requires at least: 4.2
Tested up to: 4.8
Stable tag: 1.4.3
License: GPL2

Display Google Places Reviews on your WordPress website and help boost consumer confidence and search engine optimization.

== Description ==

Easily display Google Reviews on your WordPress website using a powerful and intuitive widget. Great for restaurants, retail stores, franchisees, real estate firms, hotels and hospitality, and nearly any business with a website and reviews on Google.

[youtube https://www.youtube.com/watch?v=fXQA5kXRm88]

**[View the Online Demo](http://googleplacesreviews.wordimpress.com/ "View the Online Demo of Google Places Reviews Pro")**

= Why Use this Plugin? =

This plugin allows you to display Google reviews on your WordPress website. Why is this important? Positive reviews on websites like Google help businesses gain additional exposure and further enhance their online credibility. A [recent study](http://marketingland.com/survey-customers-more-frustrated-by-how-long-it-takes-to-resolve-a-customer-service-issue-than-the-resolution-38756) conducted by Dimensional Research found that the buying decisions of 90% of consumers are influenced by positive online reviews. Additionally, a [2012 study published on Search Engine Land](http://searchengineland.com/study-72-of-consumers-trust-online-reviews-as-much-as-personal-recommendations-114152 "View the Article Online") found that 72% of consumers trust online reviews as much as personal recommendations. If you do the research it quickly becomes clear. Positive reviews add substantial credibility to any business and can lead to increased conversion rates and higher sales.

= Plugin Features =

* Google Business Reviews - Display up to 3 business reviews per location.
* Detailed Business Information - Show the business name, website, Google+ page and more.
* Versatile Widget Themes - Choose from a selection of stunning widget themes that fit light and dark color schemes that make integration with your website design effortless.
* Google Places Autocomplete - Easily lookup businesses in your area through the widget interface using the power of Google search.
* Actively Supported and Developed - We are a team of expert developers based in San Diego, California and we stand by our work. Got a problem? Hit us up.

There is significant data that shows how much consumers care about online reviews but very little information about what businesses are doing to respond to this phenomenon. How about displaying reviews on your WordPress website from Google?

Google Places Reviews allows you to display up to 3 business reviews with the free version and 5 with the Pro version. Setup is quick and easy. Simply install the plugin, insert your Google API (free and documented), and drag the widget into any sidebar to configure the options.

= Get More Features with Google Places Reviews PRO! =

[Upgrade to Google Places Reviews Pro](https://wordimpress.com/plugins/google-places-reviews-pro/ "Upgrade to Google Places Reviews Pro").

Use Promo Code: `googleplaces20off` for a 20% discount!

= Google Places Reviews Pro Version Features =

Google Places Reviews Pro is a significant upgrade to Google Places Reviews that adds many features to the core plugin:

* More Reviews - Display up to 5 reviews using the Google Places API
* Powerful Shortcode - Display reviews in your post and page content
* Review concatenation - Some reviews returned by Google may be very long which could result in a very long widget. The Pro version includes a customizable feature for collapsing and expanding long reviews with "Read more" and "Close" links.
* Fast loading - Optimized widget caching included within the plugin ensure you save on load time and API calls
* Priority Support - Get fast and responsive support from WordPress experts in the USA for the lifetime of your license.

= Additional Business Reviews Plugins =

Why limit your reviews to just Google Places Reviews? Check out our other free business reviews plugins to add to your site as well:

* [Facebook Reviews Pro](https://wordimpress.org/plugins/facebook-reviews-pro "Facebook Reviews Pro")
* [Yelp Widget Pro](https://wordpress.org/plugins/yelp-widget-pro "Yelp Widget Pro")
* [Yellow Pages Reviews](https://wordpress.org/plugins/yellow-pages-reviews/ "Google Places Reviews")
* Get all **FOUR** of our Premium Business Reviews plugins for one low price. [Premium Business Reviews Bundle](https://wordimpress.com/plugins/business-reviews-bundle/?utm_source=WordPress.org&utm_medium=readme&utm_campaign=Google%20Places%20Repo "Premium Business Reviews Bundle")

== Installation ==

**Automatic Install:**

1. Log into your WP-Admin
2. Navigate to 'Plugins' > 'Add New'
3. Search for "Google Places Reviews" and install it from there.

**Manual Install:**

1. Upload the `google-places-reviews` folder and it's contents to the `/wp-content/plugins/` directory or install via the WP plugins panel in your WordPress admin dashboard
2. Activate the plugin through the 'Plugins' menu in WordPress
3. That's it! You should now be able to use the widget.

== Frequently Asked Questions ==

= Why does the widget look funny in my theme? =

Most likely your theme has conflicting CSS that is interfering with the themes included with the plugin. If you're handy with CSS this can be an easy fix. If you are new to CSS then try out the Bare Bones theme to see if that looks nicer. Otherwise, you may have to hit up your coding friends to help you out.

= Are style (CSS) and/or theme compatibility issues supported? =

We do our best to support the free version of the plugin but it's not our priority. The premium plugin is where we spend most of our support time. If you are experiencing a style issue and need help, either upgrade to the Premium version or post in the WordPress.org support forum and we will do our darnedest to get it set up nicely for your theme.

= Are there prebuilt styles included in the plugin? =

Yes, there are three basic themes included in the free version of the plugin. The premium version has many more options and themes.

== Screenshots ==

1. An admin view of the widget in a WordPress sidebar

2. Minimal widget themes displayed on the demo site

3. Shadow widget themes displayed on the demo site

4. Inset widget themes displayed on the demo site

5. The plugins settings page found under Settings > Google Reviews

== Changelog ==

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